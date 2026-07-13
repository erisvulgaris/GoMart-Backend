<?php
/**
 * Import pharmacy products from writable/scrape_pharmacy_delhi.json into category 14.
 * ?key=cityloop_img_fix_2026&action=import|status
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

$file = dirname(__DIR__) . '/writable/scrape_pharmacy_delhi.json';
// also allow public/data copy
if (!is_file($file)) {
    $file = __DIR__ . '/data/scrape_pharmacy_delhi.json';
}

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

$action = $_GET['action'] ?? 'status';
$pharmaCount = (int) ($db->query(
    "SELECT COUNT(DISTINCT p.id) c FROM product p
     JOIN product_categories pc ON pc.product_id=p.id AND pc.category_id=14
     WHERE p.is_delete=0"
)->fetch_assoc()['c'] ?? 0);

if ($action === 'status') {
    echo json_encode([
        'ok' => true,
        'file_exists' => is_file($file),
        'file' => $file,
        'pharmacy_products_in_db' => $pharmaCount,
        'json_count' => is_file($file) ? count(json_decode((string) file_get_contents($file), true) ?: []) : 0,
    ], JSON_PRETTY_PRINT);
    exit;
}

if ($action === 'import') {
    if (!is_file($file)) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'scrape_pharmacy_delhi.json missing — run scrape first']);
        exit;
    }
    $products = json_decode((string) file_get_contents($file), true);
    if (!is_array($products)) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'invalid json']);
        exit;
    }

    $existing = [];
    $r = $db->query('SELECT id, LOWER(TRIM(product_name)) n FROM product WHERE is_delete=0');
    while ($row = $r->fetch_assoc()) {
        $existing[$row['n']] = (int) $row['id'];
    }

    $maxId = (int) ($db->query('SELECT COALESCE(MAX(id),0) m FROM product')->fetch_assoc()['m'] ?? 0);
    $maxVar = (int) ($db->query('SELECT COALESCE(MAX(id),0) m FROM product_variants')->fetch_assoc()['m'] ?? 0);
    $inserted = 0;
    $linked = 0;
    $skipped = 0;

    $stmtP = $db->prepare(
        'INSERT INTO product (id, brand_id, seller_id, tax_id, product_name, slug, main_img, description, popular, deal_of_the_day, status, is_delete, date)
         VALUES (?, 0, 1, 0, ?, ?, ?, ?, 0, 0, 1, 0, NOW())'
    );
    $stmtV = $db->prepare(
        'INSERT INTO product_variants (id, product_id, title, price, discounted_price, stock, is_unlimited_stock, status, is_delete)
         VALUES (?, ?, ?, ?, ?, 40, 0, 1, 0)'
    );
    $stmtC = $db->prepare('INSERT IGNORE INTO product_categories (product_id, category_id) VALUES (?, 14)');

    foreach ($products as $p) {
        $name = trim((string) ($p['product_name'] ?? ''));
        $img = (string) ($p['main_img'] ?? $p['image_url'] ?? '');
        if ($name === '' || !str_starts_with($img, 'http')) {
            $skipped++;
            continue;
        }
        $key = mb_strtolower($name);
        if (isset($existing[$key])) {
            $pid = $existing[$key];
            $stmtC->bind_param('i', $pid);
            $stmtC->execute();
            // ensure CDN image
            $db->query('UPDATE product SET main_img="' . $db->real_escape_string($img) . '" WHERE id=' . (int) $pid);
            $linked++;
            continue;
        }
        $maxId++;
        $maxVar++;
        $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($name)) ?: 'product';
        $slug = trim($slug, '-') . '-' . $maxId;
        $desc = (string) ($p['description'] ?? ('Pharmacy & wellness — ' . $name));
        $unit = (string) ($p['unit'] ?? '1 unit');
        $price = (string) ($p['price'] ?? 99);
        $dprice = (string) ($p['discounted_price'] ?? $price);
        $stmtP->bind_param('issss', $maxId, $name, $slug, $img, $desc);
        if (!$stmtP->execute()) {
            $skipped++;
            continue;
        }
        $stmtV->bind_param('iisss', $maxVar, $maxId, $unit, $price, $dprice);
        $stmtV->execute();
        $stmtC->bind_param('i', $maxId);
        $stmtC->execute();
        $existing[$key] = $maxId;
        $inserted++;
    }

    echo json_encode([
        'ok' => true,
        'inserted' => $inserted,
        'linked_existing' => $linked,
        'skipped' => $skipped,
        'max_product_id' => $maxId,
        'pharmacy_total_now' => (int) ($db->query(
            "SELECT COUNT(DISTINCT p.id) c FROM product p
             JOIN product_categories pc ON pc.product_id=p.id AND pc.category_id=14
             WHERE p.is_delete=0"
        )->fetch_assoc()['c'] ?? 0),
    ], JSON_PRETTY_PRINT);
    exit;
}

http_response_code(400);
echo json_encode(['ok' => false, 'error' => 'Unknown action']);
