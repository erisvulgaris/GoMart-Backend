<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$query = $db->query("SELECT * FROM otp_verification WHERE mobile = '9651112348' ORDER BY id DESC LIMIT 1");
$row = $query->fetch_assoc();
echo json_encode($row, JSON_PRETTY_PRINT);
$db->close();
