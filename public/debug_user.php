<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$res = $db->query("SELECT * FROM user WHERE mobile = '9651112348'");
$user = $res->fetch_assoc();

echo json_encode([
    "user" => $user
], JSON_PRETTY_PRINT);

$db->close();
