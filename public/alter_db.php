<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$q = "ALTER TABLE `deliverable_area` ADD COLUMN `cashback_tiers` TEXT DEFAULT NULL AFTER `base_delivery_time`";
$res = $db->query($q);
if ($res) {
    echo json_encode(["status" => "success", "message" => "Column cashback_tiers added successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => $db->error]);
}
$db->close();
