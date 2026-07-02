<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$res = $db->query("
    SELECT s.id, s.name as subcategory_name, s.category_id, c.category_name 
    FROM subcategory s
    LEFT JOIN category c ON s.category_id = c.id
");
$subs = [];
while ($row = $res->fetch_assoc()) {
    $subs[] = $row;
}

echo json_encode($subs);
$db->close();
