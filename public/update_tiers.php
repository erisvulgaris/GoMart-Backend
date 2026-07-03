<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$tiers = json_encode([
    ["min_cart" => 599, "cashback" => 50],
    ["min_cart" => 999, "cashback" => 100]
]);

$q = "UPDATE `deliverable_area` SET `cashback_tiers` = '" . $db->real_escape_string($tiers) . "' WHERE `id` = 1";
$res = $db->query($q);
if ($res) {
    echo json_encode(["status" => "success", "message" => "Cashback tiers updated successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => $db->error]);
}
$db->close();
