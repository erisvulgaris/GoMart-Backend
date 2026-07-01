<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// Check sellers
$result = $db->query("SELECT id, name, deliverable_area_id, status, is_delete FROM seller");
$sellers = [];
while ($row = $result->fetch_assoc()) {
    $sellers[] = $row;
}

// Check product count per seller
$result2 = $db->query("SELECT seller_id, COUNT(id) as product_count FROM product GROUP BY seller_id");
$products = [];
while ($row = $result2->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode(["sellers" => $sellers, "product_counts" => $products], JSON_PRETTY_PRINT);
$db->close();
