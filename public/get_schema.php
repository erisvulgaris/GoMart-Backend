<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// Get product table columns
$res = $db->query("DESCRIBE product");
$product_cols = [];
while ($row = $res->fetch_assoc()) {
    $product_cols[] = $row;
}

// Get category table columns
$res2 = $db->query("DESCRIBE category");
$category_cols = [];
while ($row = $res2->fetch_assoc()) {
    $category_cols[] = $row;
}

echo json_encode([
    "product" => $product_cols,
    "category" => $category_cols
]);
$db->close();
