<?php
/**
 * Full image recovery:
 * 1) restore_cdn from map by product id
 * 2) optionally apply name-based merge from scrape_273015
 * 3) fix broken local paths that don't exist on disk
 *
 * ?key=cityloop_img_fix_2026&action=recover|status
 */
declare(strict_types=1);
set_time_limit(0);
header('Content-Type: application/json; charset=utf-8');

const FIX_KEY = 'cityloop_img_fix_2026';
if (!hash_equals(FIX_KEY, (string) ($_GET['key'] ?? ''))) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Forbidden']);
    exit;
}

$action = $_GET['action'] ?? 'status';
$mapFile = __DIR__ . '/data/blinkit_image_map.json';
$mergeFile = __DIR__ . '/data/blinkit_image_map_merge.json';

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

function file_exists_public(string $rel): bool
{
    $rel = ltrim($rel, '/');
    // strip query
    $rel = preg_replace('/[?#].*$/', '', $rel) ?? $rel;
    $abs = __DIR__ . '/' . $rel;
    return is_file($abs) && filesize($abs) > 200;
}

if ($action === 'status') {
    $total = (int) ($db->query('SELECT COUNT(*) c FROM product')->fetch_assoc()['c'] ?? 0);
    $cdn = (int) ($db->query("SELECT COUNT(*) c FROM product WHERE main_img LIKE 'http%'")->fetch_assoc()['c'] ?? 0);
    $local = (int) ($db->query("SELECT COUNT(*) c FROM product WHERE main_img LIKE 'uploads/%'")->fetch_assoc()['c'] ?? 0);
    $brokenLocal = 0;
    $res = $db->query("SELECT id, main_img FROM product WHERE main_img LIKE 'uploads/%' LIMIT 5000");
    while ($row = $res->fetch_assoc()) {
        if (!file_exists_public($row['main_img'])) {
            $brokenLocal++;
        }
    }
    echo json_encode([
        'ok' => true,
        'products_total' => $total,
        'products_cdn' => $cdn,
        'products_local' => $local,
        'products_local_missing_file' => $brokenLocal,
        'map_exists' => is_file($mapFile),
        'merge_exists' => is_file($mergeFile),
    ], JSON_PRETTY_PRINT);
    exit;
}

if ($action === 'recover') {
    $map = is_file($mapFile) ? json_decode(file_get_contents($mapFile), true) : ['products' => []];
    $products = $map['products'] ?? [];
    $merge = is_file($mergeFile) ? json_decode(file_get_contents($mergeFile), true) : [];
    $byName = $merge['by_name'] ?? [];

    $restoredById = 0;
    $restoredByName = 0;
    $fixedMissingLocal = 0;
    $stillBroken = 0;

    $stmt = $db->prepare('UPDATE product SET main_img = ? WHERE id = ?');
    $res = $db->query('SELECT id, product_name, main_img FROM product');
    while ($row = $res->fetch_assoc()) {
        $id = (int) $row['id'];
        $img = trim((string) $row['main_img']);
        $nameKey = strtolower(trim((string) $row['product_name']));
        $new = null;

        // Prefer map CDN by id
        if (isset($products[(string) $id]) && str_starts_with($products[(string) $id], 'http')) {
            $cdn = $products[(string) $id];
            // If local path missing or empty/placeholder, restore CDN
            $isLocal = str_starts_with($img, 'uploads/');
            $missing = $isLocal && !file_exists_public($img);
            $empty = $img === '' || str_contains($img, 'placeholder') || preg_match('#^https?://[^/]+/?$#', $img);
            if ($missing || $empty || !$isLocal) {
                // Always restore CDN for reliability unless local file exists
                if ($missing || $empty) {
                    $new = $cdn;
                    $restoredById++;
                } elseif ($isLocal && file_exists_public($img)) {
                    // keep local optimized
                    $new = null;
                } else {
                    $new = $cdn;
                    $restoredById++;
                }
            }
        }

        // Name-based merge for products without map id match
        if ($new === null && isset($byName[$nameKey]) && str_starts_with($byName[$nameKey], 'http')) {
            $isLocal = str_starts_with($img, 'uploads/');
            $missing = $isLocal && !file_exists_public($img);
            if ($missing || $img === '' || str_contains($img, 'placeholder') || !str_starts_with($img, 'http')) {
                $new = $byName[$nameKey];
                $restoredByName++;
            }
        }

        // Local path without file and no CDN found → leave, count broken
        if ($new === null) {
            if (str_starts_with($img, 'uploads/') && !file_exists_public($img)) {
                $stillBroken++;
            }
            continue;
        }

        $stmt->bind_param('si', $new, $id);
        $stmt->execute();
        if (str_starts_with($img, 'uploads/')) {
            $fixedMissingLocal++;
        }
    }
    $stmt->close();

    // Gallery: restore CDN for rows that are local missing
    $galFixed = 0;
    if (!empty($map['galleries'])) {
        // truncate and reinsert is heavy; update common broken local paths to first CDN if available
        $gres = $db->query("SELECT id, product_id, image FROM product_images WHERE image LIKE 'uploads/%'");
        $gstmt = $db->prepare('UPDATE product_images SET image = ? WHERE id = ?');
        while ($g = $gres->fetch_assoc()) {
            if (file_exists_public($g['image'])) {
                continue;
            }
            $pid = (string) $g['product_id'];
            $cdnList = $map['galleries'][$pid] ?? [];
            if (!$cdnList && isset($products[$pid])) {
                $cdnList = [$products[$pid]];
            }
            if (!$cdnList) {
                continue;
            }
            $cdn = $cdnList[0];
            $gid = (int) $g['id'];
            $gstmt->bind_param('si', $cdn, $gid);
            $gstmt->execute();
            $galFixed++;
        }
        $gstmt->close();
    }

    echo json_encode([
        'ok' => true,
        'action' => 'recover',
        'restored_by_product_id' => $restoredById,
        'restored_by_name' => $restoredByName,
        'fixed_missing_local_main' => $fixedMissingLocal,
        'gallery_rows_fixed' => $galFixed,
        'still_broken_estimate' => $stillBroken,
        'next' => 'Run bulk_download_images.php to cache local 100/500/1000',
    ], JSON_PRETTY_PRINT);
    exit;
}

http_response_code(400);
echo json_encode(['ok' => false, 'error' => 'Unknown action']);
