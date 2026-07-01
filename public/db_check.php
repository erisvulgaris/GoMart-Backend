<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$result = $db->query("SELECT * FROM deliverable_area");
$areas = [];
while ($row = $result->fetch_assoc()) {
    $areas[] = $row;
}

$cities = [];
$res2 = $db->query("SELECT * FROM city");
while ($row = $res2->fetch_assoc()) {
    $cities[] = $row;
}

echo json_encode(["areas" => $areas, "cities" => $cities], JSON_PRETTY_PRINT);
$db->close();
