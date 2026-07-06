<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// 1. Get user_id
$user_id = null;
$user_name = "Test User";
$res_user = $db->query("SELECT id, name FROM user WHERE mobile = '9651112348' LIMIT 1");
if ($res_user && $row = $res_user->fetch_assoc()) {
    $user_id = $row['id'];
    if (!empty($row['name'])) {
        $user_name = $row['name'];
    }
} else {
    echo json_encode(["status" => "error", "message" => "User 9651112348 not found in user table"]);
    exit;
}

// 2. Get city_id
$city_id = null;
$res_city = $db->query("SELECT id FROM city WHERE name = 'Gorakhpur' LIMIT 1");
if ($res_city && $row = $res_city->fetch_assoc()) {
    $city_id = $row['id'];
} else {
    // Try to get any city
    $res_city2 = $db->query("SELECT id FROM city LIMIT 1");
    if ($res_city2 && $row = $res_city2->fetch_assoc()) {
        $city_id = $row['id'];
    } else {
        echo json_encode(["status" => "error", "message" => "No city found in city table"]);
        exit;
    }
}

// 3. Get deliverable_area_id
$area_id = null;
$res_area = $db->query("SELECT id FROM deliverable_area LIMIT 1");
if ($res_area && $row = $res_area->fetch_assoc()) {
    $area_id = $row['id'];
} else {
    echo json_encode(["status" => "error", "message" => "No deliverable area found in deliverable_area table"]);
    exit;
}

// 4. Update status = 0 for existing addresses of this user
$db->query("UPDATE address SET status = 0 WHERE user_id = $user_id");

// 5. Insert new address
$address = "Flat 202, Sunrise Heights, Near City Mall, Gorakhpur";
$area = "Gorakhpur";
$city_name = "Gorakhpur";
$state = "Uttar Pradesh";
$pincode = "273001";
$lat = "26.7605545";
$lng = "83.3731675";
$map_address = "Gorakhpur, Uttar Pradesh, India";
$address_type = "Home";
$flat = "Flat 202";
$floor = "2nd Floor";

$stmt = $db->prepare("INSERT INTO address (
    user_id, city_id, address, area, city, state, pincode, status, latitude, longitude, map_address, is_delete, deliverable_area_id, address_type, flat, floor, user_name, user_mobile
) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("iissssssssisssss", 
    $user_id, $city_id, $address, $area, $city_name, $state, $pincode, $lat, $lng, $map_address, $area_id, $address_type, $flat, $floor, $user_name, $user_mobile
);

$user_mobile = "9651112348";
if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Successfully inserted pre-saved address into DB",
        "user_id" => $user_id,
        "city_id" => $city_id,
        "area_id" => $area_id
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to execute insert statement: " . $stmt->error
    ]);
}

$db->close();
