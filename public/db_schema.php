<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$tables = ["seller", "product", "category"];
$schemas = [];
foreach ($tables as $t) {
    $res = $db->query("DESCRIBE $t");
    $fields = [];
    while ($row = $res->fetch_assoc()) {
        $fields[] = $row;
    }
    $schemas[$t] = $fields;
}

echo json_encode($schemas, JSON_PRETTY_PRINT);
$db->close();
