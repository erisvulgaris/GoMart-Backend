<?php
/**
 * Continuous bulk download of product images (CDN -> local 100/500/1000).
 * Runs many batches in one HTTP request so Coolify can fill the catalog.
 *
 * ?key=cityloop_img_fix_2026&batches=20&limit=30&start=0
 */
declare(strict_types=1);
set_time_limit(0);
ini_set('memory_limit', '512M');
header('Content-Type: application/json; charset=utf-8');

const FIX_KEY = 'cityloop_img_fix_2026';
if (!hash_equals(FIX_KEY, (string) ($_GET['key'] ?? ''))) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Forbidden']);
    exit;
}

$batches = max(1, min(100, (int) ($_GET['batches'] ?? 20)));
$limit = max(1, min(50, (int) ($_GET['limit'] ?? 25)));
$start = max(0, (int) ($_GET['start'] ?? 0));

// Reuse logic by including functions from fix_product_images via HTTP loop internally
// Inline minimal download here for reliability.

$mapFile = __DIR__ . '/data/blinkit_image_map.json';
if (!is_file($mapFile)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'map missing']);
    exit;
}
$map = json_decode(file_get_contents($mapFile), true);
$products = $map['products'] ?? [];
$ids = array_keys($products);
sort($ids, SORT_NUMERIC);

$db = @new mysqli('db', 'gomart', 'gomart_secure_pass', 'gomart');
if ($db->connect_error) {
    $db = @new mysqli('127.0.0.1', 'gomart', 'gomart_secure_pass', 'gomart');
}
if ($db->connect_error) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $db->connect_error]);
    exit;
}
$db->set_charset('utf8mb4');

function dl(string $url): ?string
{
    $url = preg_replace('#https://cdn\.grofers\.com/cdn-cgi/image/[^/]+/#', 'https://cdn.grofers.com/', $url) ?? $url;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/124.0.0.0',
        CURLOPT_HTTPHEADER => ['Referer: https://blinkit.com/', 'Accept: image/*'],
    ]);
    $data = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($code === 200 && is_string($data) && strlen($data) > 200) ? $data : null;
}

function resize_jpg(string $binary, int $size, int $q = 80): ?string
{
    if (!function_exists('imagecreatefromstring')) {
        return null;
    }
    $src = @imagecreatefromstring($binary);
    if (!$src) {
        return null;
    }
    $w = imagesx($src);
    $h = imagesy($src);
    $dst = imagecreatetruecolor($size, $size);
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefilledrectangle($dst, 0, 0, $size, $size, $white);
    $scale = min($size / max(1, $w), $size / max(1, $h));
    $nw = (int) max(1, round($w * $scale));
    $nh = (int) max(1, round($h * $scale));
    $x = (int) (($size - $nw) / 2);
    $y = (int) (($size - $nh) / 2);
    imagecopyresampled($dst, $src, $x, $y, 0, 0, $nw, $nh, $w, $h);
    ob_start();
    imagejpeg($dst, null, $q);
    $out = ob_get_clean();
    imagedestroy($src);
    imagedestroy($dst);
    return $out === false ? null : $out;
}

// 100px retired — list/cards use 300px (sharper on retina displays)
foreach ([300, 500, 1000] as $sz) {
    $d = __DIR__ . "/uploads/products/{$sz}";
    if (!is_dir($d)) {
        mkdir($d, 0755, true);
    }
}

$ok = 0;
$skip = 0;
$fail = 0;
$offset = $start;
$processed = 0;

$stmt = $db->prepare('UPDATE product SET main_img = ? WHERE id = ?');

for ($b = 0; $b < $batches; $b++) {
    $slice = array_slice($ids, $offset, $limit);
    if (!$slice) {
        break;
    }
    foreach ($slice as $pid) {
        $id = (int) $pid;
        $cdn = $products[$pid];
        $abs300 = __DIR__ . "/uploads/products/300/{$id}.jpg";
        $abs500 = __DIR__ . "/uploads/products/500/{$id}.jpg";
        $abs1000 = __DIR__ . "/uploads/products/1000/{$id}.jpg";
        $rel300 = "uploads/products/300/{$id}.jpg";

        if (is_file($abs300) && filesize($abs300) > 200 && is_file($abs500) && is_file($abs1000)) {
            $stmt->bind_param('si', $rel300, $id);
            $stmt->execute();
            $skip++;
            $processed++;
            continue;
        }

        $bytes = dl($cdn);
        if ($bytes === null) {
            // Keep CDN in DB so proxy can serve
            $stmt->bind_param('si', $cdn, $id);
            $stmt->execute();
            $fail++;
            $processed++;
            continue;
        }

        $j300 = resize_jpg($bytes, 300, 82) ?? $bytes;
        $j500 = resize_jpg($bytes, 500, 82) ?? $bytes;
        $j1000 = resize_jpg($bytes, 1000, 85) ?? $bytes;
        file_put_contents($abs300, $j300);
        file_put_contents($abs500, $j500);
        file_put_contents($abs1000, $j1000);
        $stmt->bind_param('si', $rel300, $id);
        $stmt->execute();
        $ok++;
        $processed++;
    }
    $offset += $limit;
}

$stmt->close();

echo json_encode([
    'ok' => true,
    'start' => $start,
    'next_start' => $offset,
    'done' => $offset >= count($ids),
    'total_in_map' => count($ids),
    'processed' => $processed,
    'downloaded_ok' => $ok,
    'skipped_existing' => $skip,
    'failed_kept_cdn' => $fail,
], JSON_PRETTY_PRINT);
