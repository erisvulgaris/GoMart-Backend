<?php
/**
 * Apply PIN 273015 scrape image URLs onto product.main_img by name match.
 * Also merges into blinkit_image_map.json for future downloads.
 * Optionally inserts missing products from scrape.
 *
 * ?key=cityloop_img_fix_2026&action=apply|status|insert_missing
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

$mergeFile = __DIR__ . '/data/blinkit_image_map_merge.json';
$mapFile = __DIR__ . '/data/blinkit_image_map.json';
$action = $_GET['action'] ?? 'status';

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

if (!is_file($mergeFile)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'merge file missing — run scrape first']);
    exit;
}
$merge = json_decode((string) file_get_contents($mergeFile), true);
if (!is_array($merge)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'invalid merge json']);
    exit;
}
$byName = $merge['by_name'] ?? [];
$products = $merge['products'] ?? [];

function normalize_name(string $s): string
{
    $s = mb_strtolower(trim($s), 'UTF-8');
    $s = preg_replace('/\s+/u', ' ', $s) ?? $s;
    // strip cityloop branding noise for matching
    $s = str_replace(['cityloop', 'blinkit'], '', $s);
    $s = preg_replace('/\s+/u', ' ', trim($s)) ?? $s;
    return $s;
}

function is_http_url(?string $url): bool
{
    return is_string($url) && str_starts_with($url, 'http');
}

function needs_image(?string $mainImg): bool
{
    if ($mainImg === null || $mainImg === '') {
        return true;
    }
    if (str_starts_with($mainImg, 'http')) {
        return false;
    }
    // local path without guaranteed file — replace with CDN
    return true;
}

function significant_tokens(string $name): array
{
    $stop = [
        'the', 'and', 'for', 'with', 'pack', 'of', 'pcs', 'pc', 'ml', 'ltr', 'kg', 'gm', 'g',
        'fresh', 'farm', 'grocery', 'cityloop', 'blinkit', 'value', 'combo', 'unit',
    ];
    $parts = preg_split('/[^a-z0-9]+/u', $name) ?: [];
    $out = [];
    foreach ($parts as $p) {
        $p = trim($p);
        if ($p === '' || is_numeric($p) || mb_strlen($p) < 3) {
            continue;
        }
        if (in_array($p, $stop, true)) {
            continue;
        }
        $out[] = $p;
    }
    return array_values(array_unique($out));
}

function find_url_for_name(string $productName, array $byName): ?string
{
    $key = normalize_name($productName);
    if ($key === '') {
        return null;
    }
    // exact
    foreach ($byName as $n => $url) {
        if (!is_http_url($url)) {
            continue;
        }
        if (normalize_name((string) $n) === $key) {
            return $url;
        }
    }
    // contains either way (prefer longer name match)
    $best = null;
    $bestLen = 0;
    foreach ($byName as $n => $url) {
        if (!is_http_url($url)) {
            continue;
        }
        $nn = normalize_name((string) $n);
        if ($nn === '' || mb_strlen($nn) < 4) {
            continue;
        }
        if (str_contains($key, $nn) || str_contains($nn, $key)) {
            $len = mb_strlen($nn);
            if ($len > $bestLen) {
                $bestLen = $len;
                $best = $url;
            }
        }
    }
    if ($best !== null) {
        return $best;
    }

    // token overlap (≥2 significant tokens, prefer highest overlap then longer name)
    $keyTokens = significant_tokens($key);
    if (count($keyTokens) < 2) {
        return null;
    }
    $bestScore = 0;
    $bestUrl = null;
    foreach ($byName as $n => $url) {
        if (!is_http_url($url)) {
            continue;
        }
        $nn = normalize_name((string) $n);
        $tokens = significant_tokens($nn);
        if (count($tokens) < 2) {
            continue;
        }
        $overlap = count(array_intersect($keyTokens, $tokens));
        if ($overlap < 2) {
            continue;
        }
        // score: overlap * 100 + length of shorter name (stability)
        $score = $overlap * 100 + min(mb_strlen($key), mb_strlen($nn));
        if ($score > $bestScore) {
            $bestScore = $score;
            $bestUrl = $url;
        }
    }
    return $bestUrl;
}

if ($action === 'status') {
    $missingLocal = (int) ($db->query(
        "SELECT COUNT(*) c FROM product WHERE is_delete=0 AND (main_img IS NULL OR main_img='' OR main_img LIKE 'uploads/%')"
    )->fetch_assoc()['c'] ?? 0);
    $cdn = (int) ($db->query(
        "SELECT COUNT(*) c FROM product WHERE is_delete=0 AND main_img LIKE 'http%'"
    )->fetch_assoc()['c'] ?? 0);
    echo json_encode([
        'ok' => true,
        'merge_names' => count($byName),
        'merge_products' => count($products),
        'with_images' => $merge['with_images'] ?? count($byName),
        'pincode' => $merge['pincode'] ?? '273015',
        'count' => $merge['count'] ?? 0,
        'db_cdn_main' => $cdn,
        'db_missing_or_local' => $missingLocal,
        'scraped_at' => $merge['scraped_at'] ?? null,
    ], JSON_PRETTY_PRINT);
    exit;
}

if ($action === 'apply') {
    $updated = 0;
    $matched = 0;
    $skippedOk = 0;
    $stmt = $db->prepare('UPDATE product SET main_img = ? WHERE id = ?');
    $res = $db->query('SELECT id, product_name, main_img FROM product WHERE is_delete=0');
    while ($row = $res->fetch_assoc()) {
        $url = find_url_for_name((string) $row['product_name'], $byName);
        if ($url === null) {
            continue;
        }
        $matched++;
        if (!needs_image($row['main_img']) && is_http_url($row['main_img'])) {
            // still upgrade if map has image and DB already CDN — keep existing CDN
            $skippedOk++;
            continue;
        }
        $id = (int) $row['id'];
        $stmt->bind_param('si', $url, $id);
        $stmt->execute();
        if ($stmt->affected_rows >= 0) {
            $updated++;
        }
    }
    $stmt->close();

    // Enrich primary image map by product id for download pipeline
    // Prefer writable path (public/data may be read-only in Docker image)
    $added = 0;
    $map = ['products' => [], 'galleries' => []];
    if (is_file($mapFile)) {
        $map = json_decode((string) file_get_contents($mapFile), true) ?: $map;
    }
    if (!isset($map['products']) || !is_array($map['products'])) {
        $map['products'] = [];
    }
    $res2 = $db->query("SELECT id, main_img FROM product WHERE is_delete=0 AND main_img LIKE 'http%'");
    while ($r = $res2->fetch_assoc()) {
        $pid = (string) $r['id'];
        if (empty($map['products'][$pid]) || !str_starts_with((string) $map['products'][$pid], 'http')) {
            $map['products'][$pid] = $r['main_img'];
            $added++;
        }
    }
    $map['product_count'] = count($map['products']);
    $map['updated_at'] = gmdate('c');
    $writableMap = dirname(__DIR__) . '/writable/blinkit_image_map_runtime.json';
    $mapJson = json_encode($map, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $mapWritten = @file_put_contents($mapFile, $mapJson);
    if ($mapWritten === false) {
        @file_put_contents($writableMap, $mapJson);
    }

    echo json_encode([
        'ok' => true,
        'matched_products' => $matched,
        'updated_rows' => $updated,
        'already_had_cdn' => $skippedOk,
        'map_entries_added' => $added,
        'map_written_public' => $mapWritten !== false,
        'next' => 'Run insert_missing if needed, then bulk_download_images.php',
    ], JSON_PRETTY_PRINT);
    exit;
}

if ($action === 'insert_missing') {
    // Insert scraped products whose names are not already in catalog
    $existing = [];
    $res = $db->query('SELECT id, product_name FROM product WHERE is_delete=0');
    while ($row = $res->fetch_assoc()) {
        $existing[normalize_name((string) $row['product_name'])] = (int) $row['id'];
    }

    $maxId = (int) ($db->query('SELECT COALESCE(MAX(id), 0) m FROM product')->fetch_assoc()['m'] ?? 0);
    $maxVar = (int) ($db->query('SELECT COALESCE(MAX(id), 0) m FROM product_variants')->fetch_assoc()['m'] ?? 0);
    $inserted = 0;
    $skipped = 0;

    $stmtP = $db->prepare(
        'INSERT INTO product (id, brand_id, seller_id, tax_id, product_name, slug, main_img, description, popular, deal_of_the_day, status, is_delete, date) '
        . 'VALUES (?, 0, 1, 0, ?, ?, ?, ?, 0, 0, 1, 0, NOW())'
    );
    $stmtV = $db->prepare(
        'INSERT INTO product_variants (id, product_id, title, price, discounted_price, stock, is_unlimited_stock, status, is_delete) '
        . 'VALUES (?, ?, ?, ?, ?, 50, 0, 1, 0)'
    );

    $source = $products;
    if (!$source && is_array($byName)) {
        foreach ($byName as $n => $url) {
            $source[] = ['product_name' => $n, 'image_url' => $url, 'unit' => '1 unit', 'price' => 99, 'discounted_price' => 99];
        }
    }

    foreach ($source as $p) {
        $name = trim((string) ($p['product_name'] ?? ''));
        $url = (string) ($p['image_url'] ?? $p['main_img'] ?? '');
        if ($name === '' || !is_http_url($url)) {
            continue;
        }
        $key = normalize_name($name);
        if ($key === '' || isset($existing[$key])) {
            $skipped++;
            continue;
        }
        // soft-dup check
        $dup = false;
        foreach ($existing as $en => $_) {
            if ($en !== '' && (str_contains($en, $key) || str_contains($key, $en)) && mb_strlen($en) > 5) {
                $dup = true;
                break;
            }
        }
        if ($dup) {
            $skipped++;
            continue;
        }

        $maxId++;
        $maxVar++;
        $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($name)) ?: ('product-' . $maxId);
        $slug = trim($slug, '-') . '-' . $maxId;
        $desc = 'Fresh and high-quality ' . mb_strtolower($name) . '. Delivered fast via CityLoop.';
        $unit = (string) ($p['unit'] ?? '1 unit');
        $price = (string) ($p['price'] ?? 99);
        $dprice = (string) ($p['discounted_price'] ?? $price);

        $stmtP->bind_param('issss', $maxId, $name, $slug, $url, $desc);
        if (!$stmtP->execute()) {
            continue;
        }
        $stmtV->bind_param('iisss', $maxVar, $maxId, $unit, $price, $dprice);
        $stmtV->execute();
        $existing[$key] = $maxId;
        $inserted++;
    }
    $stmtP->close();
    $stmtV->close();

    // refresh map (writable fallback if public/data is read-only)
    $map = ['products' => [], 'galleries' => []];
    if (is_file($mapFile)) {
        $map = json_decode((string) file_get_contents($mapFile), true) ?: $map;
    }
    $res2 = $db->query("SELECT id, main_img FROM product WHERE is_delete=0 AND main_img LIKE 'http%'");
    while ($r = $res2->fetch_assoc()) {
        $map['products'][(string) $r['id']] = $r['main_img'];
    }
    $map['product_count'] = count($map['products'] ?? []);
    $mapJson = json_encode($map, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if (@file_put_contents($mapFile, $mapJson) === false) {
        @file_put_contents(dirname(__DIR__) . '/writable/blinkit_image_map_runtime.json', $mapJson);
    }

    echo json_encode([
        'ok' => true,
        'inserted' => $inserted,
        'skipped_existing' => $skipped,
        'max_product_id' => $maxId,
        'next' => 'apply then bulk_download',
    ], JSON_PRETTY_PRINT);
    exit;
}

http_response_code(400);
echo json_encode(['ok' => false, 'error' => 'Unknown action']);
