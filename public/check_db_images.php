<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => $db->connect_error]);
    exit;
}

$res = $db->query("SELECT id, product_name, main_img FROM product WHERE product_name LIKE '%Coriander%' LIMIT 5");
$products = [];
while ($row = $res->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode([
    "products" => $products
]);
$db->close();
