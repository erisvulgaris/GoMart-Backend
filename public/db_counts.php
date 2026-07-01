<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$r1 = $db->query("SELECT COUNT(*) as cnt FROM category")->fetch_assoc();
$r2 = $db->query("SELECT COUNT(*) as cnt FROM product")->fetch_assoc();
$r3 = $db->query("SELECT COUNT(*) as cnt FROM product_variants")->fetch_assoc();
$r4 = $db->query("SELECT COUNT(*) as cnt FROM subcategory")->fetch_assoc();

$cats = [];
$res_cats = $db->query("SELECT id, category_name FROM category LIMIT 20");
while ($row = $res_cats->fetch_assoc()) {
    $cats[] = $row;
}

echo json_encode([
    "categories_count" => $r1['cnt'],
    "products_count" => $r2['cnt'],
    "variants_count" => $r3['cnt'],
    "subcategories_count" => $r4['cnt'],
    "sample_categories" => $cats
], JSON_PRETTY_PRINT);
$db->close();
