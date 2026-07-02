<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// Fetch categories
$res = $db->query("SELECT id, category_name FROM category");
$categories = [];
while ($row = $res->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch products and their category ID (if mapped)
$res2 = $db->query("
    SELECT p.id, p.product_name, pc.category_id, c.category_name 
    FROM product p
    LEFT JOIN product_categories pc ON p.id = pc.product_id
    LEFT JOIN category c ON pc.category_id = c.id
    WHERE p.is_delete = 0
");
$products = [];
while ($row = $res2->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode([
    "categories" => $categories,
    "products" => $products
]);
$db->close();
