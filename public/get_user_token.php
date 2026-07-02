<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}
// Get a user who is active and has a token (or we can just generate a JWT token for them!)
$res = $db->query("SELECT * FROM user WHERE is_active = 1 AND is_delete = 0 LIMIT 1;");
$user = $res->fetch_assoc();
if ($user) {
    echo json_encode(["user" => $user]);
} else {
    echo json_encode(["error" => "No user found"]);
}
$db->close();
