<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// 1. Update map API key in settings
$db->query("UPDATE settings SET value = 'AIzaSyAmX29-nyb3BDTtovxvhaJR_u82fphs-6M' WHERE `key` = 'map_api_key'");
$map_updated = $db->affected_rows;

// 2. Fetch updates list
$updates_json = file_get_contents(__DIR__ . '/update_images_db.php');
// Extract the updates array using regex
preg_match('/\$updates = (\[.*?\]);/s', $updates_json, $matches);
$success = 0;

if (!empty($matches[1])) {
    $updates = json_decode($matches[1], true);
    if (is_array($updates)) {
        foreach ($updates as $update) {
            $p_id = (int)$update[0];
            $img_path = $db->real_escape_string($update[1]);
            
            $q = "UPDATE product SET main_img = '$img_path' WHERE id = $p_id";
            if ($db->query($q)) {
                $success++;
            }
        }
    }
}

echo json_encode([
    "status" => "success",
    "map_key_updated" => $map_updated > 0 ? "Yes" : "No change",
    "products_updated" => $success
]);
$db->close();
