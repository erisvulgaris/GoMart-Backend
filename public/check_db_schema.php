<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => $db->connect_error]);
    exit;
}

// Get columns of product table
$res = $db->query("SHOW COLUMNS FROM product");
$columns = [];
while ($row = $res->fetch_assoc()) {
    $columns[] = $row;
}

// Get columns of product_variants table
$res_v = $db->query("SHOW COLUMNS FROM product_variants");
$columns_v = [];
while ($row = $res_v->fetch_assoc()) {
    $columns_v[] = $row;
}

echo json_encode([
    "product" => $columns,
    "product_variants" => $columns_v
]);
$db->close();
