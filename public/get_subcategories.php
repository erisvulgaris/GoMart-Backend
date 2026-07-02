<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// Describe subcategory
$res = $db->query("DESCRIBE subcategory");
$sub_cols = [];
while ($row = $res->fetch_assoc()) {
    $sub_cols[] = $row;
}

// Describe product_subcategories
$res2 = $db->query("DESCRIBE product_subcategories");
$prod_sub_cols = [];
while ($row = $res2->fetch_assoc()) {
    $prod_sub_cols[] = $row;
}

echo json_encode([
    "subcategory" => $sub_cols,
    "product_subcategories" => $prod_sub_cols
]);
$db->close();
