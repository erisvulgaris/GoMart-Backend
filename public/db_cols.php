<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$address_cols = [];
$res = $db->query("DESCRIBE address");
while ($row = $res->fetch_assoc()) {
    $address_cols[] = $row;
}

$cities = [];
$res_cities = $db->query("SELECT * FROM cities WHERE is_delete = 0");
while ($row = $res_cities->fetch_assoc()) {
    $cities[] = $row;
}

$deliverable_areas = [];
$res_areas = $db->query("SELECT id, city_id, name, boundary_points_web FROM deliverable_area WHERE is_delete = 0");
while ($row = $res_areas->fetch_assoc()) {
    $deliverable_areas[] = $row;
}

$users = [];
$res_users = $db->query("SELECT id, name, mobile, email FROM users WHERE mobile='9651112348'");
while ($row = $res_users->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode([
    "address_cols" => $address_cols,
    "cities" => $cities,
    "deliverable_areas" => $deliverable_areas,
    "users" => $users
], JSON_PRETTY_PRINT);
$db->close();
