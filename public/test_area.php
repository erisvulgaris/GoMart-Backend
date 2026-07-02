<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$res = $db->query("SELECT * FROM deliverable_area WHERE is_delete=0 LIMIT 1");
if ($row = $res->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "No deliverable area found"]);
}
$db->close();
