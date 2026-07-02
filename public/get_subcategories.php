<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// Fetch subcategories
$res = $db->query("SELECT id, name, category_id FROM subcategory");
$subcategories = [];
while ($row = $res->fetch_assoc()) {
    $subcategories[] = $row;
}

// Fetch product subcategories mapping
$res2 = $db->query("
    SELECT ps.id, ps.product_id, p.product_name, ps.subcategory_id, s.name as subcategory_name, s.category_id
    FROM product_subcategories ps
    JOIN product p ON ps.product_id = p.id
    JOIN subcategory s ON ps.subcategory_id = s.id
    WHERE p.is_delete = 0
");
$mappings = [];
while ($row = $res2->fetch_assoc()) {
    $mappings[] = $row;
}

echo json_encode([
    "subcategories" => $subcategories,
    "mappings" => $mappings
]);
$db->close();
