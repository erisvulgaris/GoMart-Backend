<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// Find user Sakshi Shukla
$userQuery = $db->query("SELECT * FROM user WHERE mobile = '9651112348'");
$user = $userQuery->fetch_assoc();

if (!$user) {
    echo json_encode(["error" => "User not found"]);
    $db->close();
    exit;
}

// Find addresses
$addrQuery = $db->query("SELECT * FROM address WHERE user_id = " . $user['id'] . " AND is_delete = 0");
$addresses = [];
while ($row = $addrQuery->fetch_assoc()) {
    $addresses[] = $row;
}

echo json_encode([
    "user" => $user,
    "addresses" => $addresses
], JSON_PRETTY_PRINT);

$db->close();
