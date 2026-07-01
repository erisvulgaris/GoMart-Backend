<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$otp_query = $db->query("SELECT id, mobile, otp, verify_by, created_at FROM otp_verification ORDER BY id DESC LIMIT 10");
$otps = [];
while ($row = $otp_query->fetch_assoc()) {
    $otps[] = $row;
}

$settings_query = $db->query("SELECT * FROM settings WHERE `key` IN ('phone_login', 'country_code', 'refer_and_earn_status')");
$settings = [];
while ($row = $settings_query->fetch_assoc()) {
    $settings[$row['key']] = $row['value'];
}

$gateways_query = $db->query("SELECT id, name, is_active FROM sms_gateway");
$gateways = [];
while ($row = $gateways_query->fetch_assoc()) {
    $gateways[] = $row;
}

echo json_encode([
    "otps" => $otps,
    "settings" => $settings,
    "gateways" => $gateways
], JSON_PRETTY_PRINT);
$db->close();
