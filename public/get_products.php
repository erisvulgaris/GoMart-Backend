<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$res = $db->query("SELECT id, product_name FROM product WHERE is_delete = 0");
$products = [];
while ($row = $res->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
$db->close();
