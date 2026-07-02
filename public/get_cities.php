<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}
$res = $db->query("SELECT * FROM city WHERE is_delete = 0;");
$cities = [];
while ($row = $res->fetch_assoc()) {
    $cities[] = $row;
}
echo json_encode($cities);
$db->close();
