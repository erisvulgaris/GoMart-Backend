<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$res = $db->query("DESCRIBE product_categories");
$cols = [];
while ($row = $res->fetch_assoc()) {
    $cols[] = $row;
}

echo json_encode($cols);
$db->close();
