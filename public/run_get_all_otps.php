<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$query = $db->query("SELECT * FROM otp_verification ORDER BY id DESC LIMIT 15");
$results = [];
while ($row = $query->fetch_assoc()) {
    $results[] = $row;
}
echo json_encode($results, JSON_PRETTY_PRINT);
$db->close();
