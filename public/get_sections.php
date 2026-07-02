<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// Fetch sections
$res = $db->query("SELECT id, title FROM sections");
$sections = [];
while ($row = $res->fetch_assoc()) {
    $sections[] = $row;
}

// Fetch section products mapping
$res2 = $db->query("
    SELECT sp.id, sp.section_id, s.title as section_title, sp.product_id, p.product_name
    FROM section_products sp
    JOIN sections s ON sp.section_id = s.id
    JOIN product p ON sp.product_id = p.id
    WHERE p.is_delete = 0
");
$mappings = [];
while ($row = $res2->fetch_assoc()) {
    $mappings[] = $row;
}

echo json_encode([
    "sections" => $sections,
    "mappings" => $mappings
]);
$db->close();
