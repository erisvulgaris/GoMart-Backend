<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// Get user ID
$res = $db->query("SELECT id FROM user WHERE mobile = '9651112348'");
$user = $res->fetch_assoc();
$user_id = $user['id'];

// Select addresses for this user
$addresses = [];
$res_addr = $db->query("SELECT * FROM address WHERE user_id = $user_id AND is_delete = 0");
while ($row = $res_addr->fetch_assoc()) {
    $addresses[] = $row;
}

echo json_encode([
    "user_id" => $user_id,
    "addresses" => $addresses
], JSON_PRETTY_PRINT);

$db->close();
