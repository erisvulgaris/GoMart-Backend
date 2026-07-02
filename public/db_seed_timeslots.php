<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// 1. Truncate timeslot and insert timeslots
$db->query("TRUNCATE TABLE timeslot;");
$db->query("INSERT INTO timeslot (id, mintime, maxtime) VALUES 
(1, '08.00', '11.00'),
(2, '11.00', '14.00'),
(3, '14.00', '17.00'),
(4, '17.00', '20.00'),
(5, '20.00', '23.00');");

// 2. Update home_delivery_status in settings table
$new_status = json_encode([
    "id" => "homeDelivery",
    "title" => "Instant Delivery",
    "description" => "Get it delivered to your door in 10 minutes.",
    "image" => "assets/dist/img/dm-home.png",
    "status" => "1"
]);
$new_status_escaped = $db->real_escape_string($new_status);
$db->query("UPDATE settings SET value = '$new_status_escaped' WHERE key_name = 'home_delivery_status';");

echo json_encode([
    "status" => "success",
    "message" => "Timeslots seeded and Home Delivery renamed to Instant Delivery successfully."
]);
$db->close();
