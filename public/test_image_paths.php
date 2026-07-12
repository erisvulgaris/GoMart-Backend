<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => $db->connect_error]);
    exit;
}

$res = $db->query("SELECT id, product_name, main_img FROM product ORDER BY id ASC LIMIT 50");
$products = [];
while ($row = $res->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products, JSON_PRETTY_PRINT);
$db->close();
