<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

const FIX_KEY = 'cityloop_img_fix_2026';
if (!hash_equals(FIX_KEY, (string) ($_GET['key'] ?? ''))) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Forbidden']);
    exit;
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
// Set connection charset to latin1 to match the product table default collation
$db->set_charset('latin1');

// Get JSON POST input
$input = file_get_contents('php://input');
$products = json_decode($input, true);
if (!is_array($products)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid JSON input']);
    exit;
}

function safe_latin1(string $str): string {
    return (string) mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
}

$inserted = 0;
$skipped = 0;
$errors = [];

foreach ($products as $p) {
    $name = trim($p['name'] ?? '');
    $description = trim($p['description'] ?? '');
    $price = floatval($p['price'] ?? 0);
    $salePrice = floatval($p['salePrice'] ?? 0);
    $images = $p['images'] ?? [];
    
    if (empty($name)) {
        continue;
    }
    
    // Generate unique slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $slug = trim($slug, '-');
    
    // Convert strings to Latin1 for safe insertion into Latin1-collated columns
    $name_latin = safe_latin1($name);
    $description_latin = safe_latin1($description);
    $slug_latin = safe_latin1($slug);
    
    // Check if product already exists (by slug or name)
    $stmt = $db->prepare("SELECT id FROM product WHERE slug = ? OR product_name = ?");
    $stmt->bind_param("ss", $slug_latin, $name_latin);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $skipped++;
        $stmt->close();
        continue;
    }
    $stmt->close();
    
    // Insert into product table
    $main_img = !empty($images) ? $images[0] : '';
    $main_img_latin = safe_latin1($main_img);
    $brand_id = 1;
    $seller_id = 1;
    $tax_id = 0;
    $popular = 0;
    $deal = 0;
    $manufacturer = safe_latin1('Sanskriti Foods');
    $made_in = safe_latin1('India');
    $total_allowed_qty = 10;
    $tax_inc = 1;
    $date = date('Y-m-d H:i:s');
    $status = 1;
    
    $stmt = $db->prepare("INSERT INTO product (brand_id, seller_id, tax_id, product_name, slug, main_img, description, popular, deal_of_the_day, manufacturer, made_in, total_allowed_quantity, tax_included_in_price, date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        $errors[] = "Failed to prepare product insert for $name: " . $db->error;
        continue;
    }
    $stmt->bind_param("iiissssiississs", $brand_id, $seller_id, $tax_id, $name_latin, $slug_latin, $main_img_latin, $description_latin, $popular, $deal, $manufacturer, $made_in, $total_allowed_qty, $tax_inc, $date, $status);
    if (!$stmt->execute()) {
        $errors[] = "Failed to execute product insert for $name: " . $stmt->error;
        $stmt->close();
        continue;
    }
    $product_id = $db->insert_id;
    $stmt->close();
    
    // Insert into product_variants
    $var_title = 'Pack';
    if (preg_match('/(\d+\s*(G|g|KG|kg))/i', $name, $m)) {
        $var_title = strtolower($m[1]);
    }
    $var_title_latin = safe_latin1($var_title);
    
    $var_status = 1;
    $stock = 50;
    $is_unlimited = 0;
    $stock_unit_id = 1; // grams/units
    
    $v_stmt = $db->prepare("INSERT INTO product_variants (product_id, status, title, price, discounted_price, stock, is_unlimited_stock, stock_unit_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($v_stmt) {
        $v_stmt->bind_param("iisddiii", $product_id, $var_status, $var_title_latin, $price, $salePrice, $stock, $is_unlimited, $stock_unit_id);
        $v_stmt->execute();
        $v_stmt->close();
    }
    
    // Insert into product_images
    foreach ($images as $img) {
        $img_latin = safe_latin1($img);
        $i_stmt = $db->prepare("INSERT INTO product_images (product_id, product_variant_id, image) VALUES (?, ?, ?)");
        if ($i_stmt) {
            $variant_id = 0;
            $i_stmt->bind_param("iis", $product_id, $variant_id, $img_latin);
            $i_stmt->execute();
            $i_stmt->close();
        }
    }
    
    // Link to category (default Category 8 - Staples, or Category 7 - Instant/Frozen for Dosa/Idli mix, or Category 3 - Munchies for Thekua)
    $category_id = 8; // Staples
    if (stripos($name, 'idli mix') !== false || stripos($name, 'dosa mix') !== false || stripos($name, 'thekua') !== false) {
        if (stripos($name, 'thekua') !== false) {
            $category_id = 3; // Munchies
        } else {
            $category_id = 7; // Instant
        }
    }
    
    $c_stmt = $db->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
    if ($c_stmt) {
        $c_stmt->bind_param("ii", $product_id, $category_id);
        $c_stmt->execute();
        $c_stmt->close();
    }
    
    $inserted++;
}

echo json_encode([
    'ok' => true,
    'inserted' => $inserted,
    'skipped' => $skipped,
    'errors' => $errors
]);
