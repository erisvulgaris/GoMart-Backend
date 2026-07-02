<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// product_categories
$res = $db->query("SELECT * FROM product_categories");
$prod_cats = [];
while ($row = $res->fetch_assoc()) {
    $prod_cats[] = $row;
}

// product_subcategories
$res2 = $db->query("SELECT * FROM product_subcategories");
$prod_subs = [];
while ($row = $res2->fetch_assoc()) {
    $prod_subs[] = $row;
}

// products
$res3 = $db->query("SELECT id, product_name FROM product WHERE is_delete = 0");
$products = [];
while ($row = $res3->fetch_assoc()) {
    $products[] = $row;
}

// subcategory
$res4 = $db->query("SELECT id, name, category_id FROM subcategory");
$subcategories = [];
while ($row = $res4->fetch_assoc()) {
    $subcategories[] = $row;
}

echo json_encode([
    "product_categories" => $prod_cats,
    "product_subcategories" => $prod_subs,
    "products" => $products,
    "subcategories" => $subcategories
]);
$db->close();
