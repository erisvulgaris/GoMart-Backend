<?php
/**
 * CityLoop product image fixer
 *
 * Actions:
 *   ?action=restore_cdn          Restore CDN URLs from blinkit_image_map.json into MySQL
 *   ?action=status               Report missing local files / CDN vs local stats
 *   ?action=download&offset=0&limit=40
 *                                Download CDN images, write 500x500 + 1000x1000, update DB
 *   ?key=cityloop_img_fix_2026  Required secret (query or header X-Fix-Key)
 *
 * Safe to re-run. Designed for Coolify/Docker (DB host "db").
 */
declare(strict_types=1);

set_time_limit(0);
ini_set('memory_limit', '512M');
header('Content-Type: application/json; charset=utf-8');

const FIX_KEY = 'cityloop_img_fix_2026';
const MAP_FILE = __DIR__ . '/data/blinkit_image_map.json';
const UPLOAD_100 = __DIR__ . '/uploads/products/100';
const UPLOAD_500 = __DIR__ . '/uploads/products/500';
const UPLOAD_1000 = __DIR__ . '/uploads/products/1000';
const UPLOAD_GALLERY_100 = __DIR__ . '/uploads/products/gallery/100';
const UPLOAD_GALLERY_500 = __DIR__ . '/uploads/products/gallery/500';
const UPLOAD_GALLERY_1000 = __DIR__ . '/uploads/products/gallery/1000';

$key = $_GET['key'] ?? $_SERVER['HTTP_X_FIX_KEY'] ?? '';
if (!hash_equals(FIX_KEY, (string) $key)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Forbidden — pass ?key=...']);
    exit;
}

$action = $_GET['action'] ?? 'status';

$db = @new mysqli('db', 'gomart', 'gomart_secure_pass', 'gomart');
if ($db->connect_error) {
    // Local/dev fallback
    $db = @new mysqli('127.0.0.1', 'gomart', 'gomart_secure_pass', 'gomart');
}
if ($db->connect_error) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'DB connect failed: ' . $db->connect_error]);
    exit;
}
$db->set_charset('utf8mb4');

function load_map(): array
{
    if (!is_file(MAP_FILE)) {
        throw new RuntimeException('Missing map file: data/blinkit_image_map.json');
    }
    $json = file_get_contents(MAP_FILE);
    $data = json_decode($json, true);
    if (!is_array($data) || empty($data['products'])) {
        throw new RuntimeException('Invalid image map JSON');
    }
    return $data;
}

function ensure_dirs(): void
{
    foreach ([UPLOAD_100, UPLOAD_500, UPLOAD_1000, UPLOAD_GALLERY_100, UPLOAD_GALLERY_500, UPLOAD_GALLERY_1000] as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

function download_bytes(string $url): ?string
{
    // Prefer original CDN asset (strip cdn-cgi resize wrapper when present)
    $url = preg_replace(
        '#https://cdn\.grofers\.com/cdn-cgi/image/[^/]+/#',
        'https://cdn.grofers.com/',
        $url
    ) ?? $url;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 25,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; CityLoopImageFix/1.0)',
        CURLOPT_HTTPHEADER => [
            'Accept: image/avif,image/webp,image/apng,image/*,*/*;q=0.8',
            'Referer: https://blinkit.com/',
        ],
    ]);
    $data = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code === 200 && is_string($data) && strlen($data) > 100) {
        return $data;
    }
    return null;
}

function resize_to_jpeg(string $binary, int $size, int $quality = 82): ?string
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
    if ($w < 1 || $h < 1) {
        imagedestroy($src);
        return null;
    }

    $dst = imagecreatetruecolor($size, $size);
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefilledrectangle($dst, 0, 0, $size, $size, $white);

    $scale = min($size / $w, $size / $h);
    $nw = (int) max(1, round($w * $scale));
    $nh = (int) max(1, round($h * $scale));
    $x = (int) (($size - $nw) / 2);
    $y = (int) (($size - $nh) / 2);
    imagecopyresampled($dst, $src, $x, $y, 0, 0, $nw, $nh, $w, $h);

    ob_start();
    imagejpeg($dst, null, $quality);
    $out = ob_get_clean();
    imagedestroy($src);
    imagedestroy($dst);
    return $out === false ? null : $out;
}

function save_triple(string $binary, string $path100, string $path500, string $path1000): bool
{
    $jpg100 = resize_to_jpeg($binary, 100, 78);
    $jpg500 = resize_to_jpeg($binary, 500, 82);
    $jpg1000 = resize_to_jpeg($binary, 1000, 85);
    if ($jpg500 === null || $jpg1000 === null) {
        if (@file_put_contents($path500, $binary) === false) {
            return false;
        }
        @file_put_contents($path1000, $binary);
        @file_put_contents($path100, $binary);
        return true;
    }
    if ($jpg100 === null) {
        $jpg100 = $jpg500;
    }
    return file_put_contents($path100, $jpg100) !== false
        && file_put_contents($path500, $jpg500) !== false
        && file_put_contents($path1000, $jpg1000) !== false;
}

/** @deprecated use save_triple */
function save_pair(string $binary, string $path500, string $path1000): bool
{
    $path100 = str_replace(['/500/', '\\500\\'], ['/100/', '\\100\\'], $path500);
    return save_triple($binary, $path100, $path500, $path1000);
}

try {
    if ($action === 'status') {
        $map = is_file(MAP_FILE) ? load_map() : null;
        $total = (int) ($db->query('SELECT COUNT(*) c FROM product WHERE is_delete=0')->fetch_assoc()['c'] ?? 0);
        $cdn = (int) ($db->query("SELECT COUNT(*) c FROM product WHERE is_delete=0 AND main_img LIKE 'http%'")->fetch_assoc()['c'] ?? 0);
        $local = (int) ($db->query("SELECT COUNT(*) c FROM product WHERE is_delete=0 AND main_img LIKE 'uploads/%'")->fetch_assoc()['c'] ?? 0);
        $local500 = (int) ($db->query("SELECT COUNT(*) c FROM product WHERE is_delete=0 AND main_img LIKE 'uploads/products/500/%'")->fetch_assoc()['c'] ?? 0);
        $gallery = (int) ($db->query('SELECT COUNT(*) c FROM product_images')->fetch_assoc()['c'] ?? 0);
        $galleryCdn = (int) ($db->query("SELECT COUNT(*) c FROM product_images WHERE image LIKE 'http%'")->fetch_assoc()['c'] ?? 0);

        echo json_encode([
            'ok' => true,
            'action' => 'status',
            'products_total' => $total,
            'products_cdn_main' => $cdn,
            'products_local_main' => $local,
            'products_optimized_500' => $local500,
            'gallery_rows' => $gallery,
            'gallery_cdn_rows' => $galleryCdn,
            'map_loaded' => $map !== null,
            'map_product_count' => $map['product_count'] ?? 0,
            'map_gallery_image_count' => $map['gallery_image_count'] ?? 0,
            'gd_available' => function_exists('imagecreatefromstring'),
        ], JSON_PRETTY_PRINT);
        exit;
    }

    if ($action === 'restore_cdn') {
        $map = load_map();
        $updatedMain = 0;
        $updatedGalleryProducts = 0;
        $insertedGallery = 0;

        $stmtMain = $db->prepare('UPDATE product SET main_img = ? WHERE id = ?');
        foreach ($map['products'] as $pid => $url) {
            $id = (int) $pid;
            $stmtMain->bind_param('si', $url, $id);
            $stmtMain->execute();
            if ($stmtMain->affected_rows >= 0) {
                $updatedMain++;
            }
        }
        $stmtMain->close();

        // Replace gallery rows from map for known products
        $del = $db->prepare('DELETE FROM product_images WHERE product_id = ?');
        $ins = $db->prepare('INSERT INTO product_images (product_id, product_variant_id, image) VALUES (?, 0, ?)');
        foreach (($map['galleries'] ?? []) as $pid => $urls) {
            $id = (int) $pid;
            $del->bind_param('i', $id);
            $del->execute();
            $updatedGalleryProducts++;
            foreach ($urls as $url) {
                $ins->bind_param('is', $id, $url);
                $ins->execute();
                $insertedGallery++;
            }
        }
        $del->close();
        $ins->close();

        echo json_encode([
            'ok' => true,
            'action' => 'restore_cdn',
            'updated_main_img_rows' => $updatedMain,
            'gallery_products_rewritten' => $updatedGalleryProducts,
            'gallery_images_inserted' => $insertedGallery,
            'next' => 'Call ?action=download&offset=0&limit=40&key=... repeatedly until done',
        ], JSON_PRETTY_PRINT);
        exit;
    }

    if ($action === 'download') {
        ensure_dirs();
        $map = load_map();
        $offset = max(0, (int) ($_GET['offset'] ?? 0));
        $limit = max(1, min(100, (int) ($_GET['limit'] ?? 40)));

        $ids = array_keys($map['products']);
        sort($ids, SORT_NUMERIC);
        $slice = array_slice($ids, $offset, $limit);

        $ok = 0;
        $fail = 0;
        $skipped = 0;
        $details = [];

        $stmtMain = $db->prepare('UPDATE product SET main_img = ? WHERE id = ?');
        $delGal = $db->prepare('DELETE FROM product_images WHERE product_id = ?');
        $insGal = $db->prepare('INSERT INTO product_images (product_id, product_variant_id, image) VALUES (?, 0, ?)');

        foreach ($slice as $pid) {
            $id = (int) $pid;
            $cdn = $map['products'][$pid];
            // Prefer lightweight 100px for list/cards in DB; UI swaps to 500/1000
            $path100Rel = "uploads/products/100/{$id}.jpg";
            $path500Rel = "uploads/products/500/{$id}.jpg";
            $path1000Rel = "uploads/products/1000/{$id}.jpg";
            $abs100 = UPLOAD_100 . "/{$id}.jpg";
            $abs500 = UPLOAD_500 . "/{$id}.jpg";
            $abs1000 = UPLOAD_1000 . "/{$id}.jpg";

            if (is_file($abs100) && filesize($abs100) > 200 && is_file($abs500) && is_file($abs1000)) {
                $stmtMain->bind_param('si', $path100Rel, $id);
                $stmtMain->execute();
                $skipped++;
            } else {
                $bytes = download_bytes($cdn);
                if ($bytes === null || !save_triple($bytes, $abs100, $abs500, $abs1000)) {
                    // Keep CDN so site still works
                    $stmtMain->bind_param('si', $cdn, $id);
                    $stmtMain->execute();
                    $fail++;
                    $details[] = ['id' => $id, 'status' => 'download_failed', 'cdn' => $cdn];
                    continue;
                }
                $stmtMain->bind_param('si', $path100Rel, $id);
                $stmtMain->execute();
                $ok++;
            }

            // Gallery images (cap at 6 extras to control storage)
            $gallery = array_slice($map['galleries'][$pid] ?? [], 0, 6);
            if ($gallery) {
                $delGal->bind_param('i', $id);
                $delGal->execute();
                $gIdx = 0;
                foreach ($gallery as $gUrl) {
                    $gIdx++;
                    $g500Rel = "uploads/products/gallery/500/{$id}_{$gIdx}.jpg";
                    $g1000Rel = "uploads/products/gallery/1000/{$id}_{$gIdx}.jpg";
                    $gAbs500 = UPLOAD_GALLERY_500 . "/{$id}_{$gIdx}.jpg";
                    $gAbs1000 = UPLOAD_GALLERY_1000 . "/{$id}_{$gIdx}.jpg";

                    if (!(is_file($gAbs500) && filesize($gAbs500) > 500)) {
                        $gBytes = download_bytes($gUrl);
                        if ($gBytes === null || !save_pair($gBytes, $gAbs500, $gAbs1000)) {
                            // store CDN as fallback for this gallery slot
                            $insGal->bind_param('is', $id, $gUrl);
                            $insGal->execute();
                            continue;
                        }
                    }
                    $insGal->bind_param('is', $id, $g500Rel);
                    $insGal->execute();
                }
            }
        }

        $stmtMain->close();
        $delGal->close();
        $insGal->close();

        $nextOffset = $offset + $limit;
        $done = $nextOffset >= count($ids);

        echo json_encode([
            'ok' => true,
            'action' => 'download',
            'offset' => $offset,
            'limit' => $limit,
            'processed' => count($slice),
            'downloaded_ok' => $ok,
            'skipped_existing' => $skipped,
            'failed' => $fail,
            'next_offset' => $done ? null : $nextOffset,
            'done' => $done,
            'total_products_in_map' => count($ids),
            'failures_sample' => array_slice($details, 0, 5),
            'note' => 'List/card UI uses 500px path; zoom should swap to uploads/products/1000/{id}.jpg',
        ], JSON_PRETTY_PRINT);
        exit;
    }

    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Unknown action', 'allowed' => ['status', 'restore_cdn', 'download']]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
