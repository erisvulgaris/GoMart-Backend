<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}
$res = $db->query("SELECT otp FROM otp_verification WHERE mobile = '7007691934' ORDER BY id DESC LIMIT 1;");
$row = $res->fetch_assoc();
echo json_encode(["otp" => $row ? $row['otp'] : null]);
$db->close();
