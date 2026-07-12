<?php
// Set high memory limit and execution time
ini_set('memory_limit', '1024M');
set_time_limit(0);

header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $db->connect_error]);
    exit;
}

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 200;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

// Create uploads directory if not exists
$upload_dir = '/var/www/html/public/uploads/products';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// 1. Fetch products to process
// We want to process products that have an external URL (starts with http)
// OR products that have the placeholder image, but have original images in product_images table
$query = "
    SELECT DISTINCT p.id, p.product_name, p.main_img, p.slug 
    FROM product p
    LEFT JOIN product_images pi ON pi.product_id = p.id
    WHERE p.is_delete = 0 AND (
        p.main_img LIKE 'http%' 
        OR p.main_img = 'uploads/products/placeholder.png'
    )
    ORDER BY p.id ASC
    LIMIT $limit
";

$res = $db->query($query);
if (!$res) {
    echo json_encode(["status" => "error", "message" => "Query failed: " . $db->error]);
    exit;
}

$products = [];
while ($row = $res->fetch_assoc()) {
    $products[] = $row;
}

$processed = 0;
$succeeded = 0;
$failed = 0;
$logs = [];

foreach ($products as $p) {
    $p_id = $p['id'];
    $p_name = $p['product_name'];
    $p_slug = $p['slug'];
    $current_img = $p['main_img'];
    
    $processed++;
    
    // Find candidate URL
    $candidate_url = null;
    if (strpos($current_img, 'http') === 0) {
        $candidate_url = $current_img;
    } else {
        // If placeholder, find the first external image in product_images table
        $img_res = $db->query("SELECT image FROM product_images WHERE product_id = $p_id AND image LIKE 'http%' LIMIT 1");
        if ($img_res && $img_res->num_rows > 0) {
            $img_row = $img_res->fetch_assoc();
            $candidate_url = $img_row['image'];
        }
    }
    
    if (!$candidate_url) {
        $failed++;
        $logs[] = ["id" => $p_id, "name" => $p_name, "status" => "failed", "reason" => "No candidate URL found"];
        continue;
    }
    
    // Download image
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $candidate_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    $img_data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code != 200 || !$img_data) {
        $failed++;
        $logs[] = ["id" => $p_id, "name" => $p_name, "status" => "failed", "reason" => "Download failed (HTTP $http_code) from $candidate_url"];
        continue;
    }
    
    // Process image using GD
    $img = @imagecreatefromstring($img_data);
    if (!$img) {
        $failed++;
        $logs[] = ["id" => $p_id, "name" => $p_name, "status" => "failed", "reason" => "GD failed to parse image data"];
        continue;
    }
    
    // Resize to fit in 1000x1000
    $width = imagesx($img);
    $height = imagesy($img);
    $max_dim = 1000;
    
    if ($width > $max_dim || $height > $max_dim) {
        if ($width > $height) {
            $new_width = $max_dim;
            $new_height = floor($height * ($max_dim / $width));
        } else {
            $new_height = $max_dim;
            $new_width = floor($width * ($max_dim / $height));
        }
    } else {
        $new_width = $width;
        $new_height = $height;
    }
    
    $new_img = imagecreatetruecolor($new_width, $new_height);
    
    // Keep transparent background for PNG/WebP images
    imagealphablending($new_img, false);
    imagesavealpha($new_img, true);
    $transparent = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
    imagefilledrectangle($new_img, 0, 0, $new_width, $new_height, $transparent);
    
    imagecopyresampled($new_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    // Save as WebP
    $clean_slug = preg_replace('/[^a-z0-9]+/', '_', strtolower(trim($p_slug)));
    $clean_slug = trim($clean_slug, '_');
    $local_path = $upload_dir . '/' . $clean_slug . '.webp';
    $db_path = 'uploads/products/' . $clean_slug . '.webp';
    
    if (imagewebp($new_img, $local_path, 80)) {
        // Update product main_img
        $db_path_esc = $db->real_escape_string($db_path);
        $db->query("UPDATE product SET main_img = '$db_path_esc' WHERE id = $p_id");
        
        // Also update product_images references to point to local WebP
        $db->query("UPDATE product_images SET image = '$db_path_esc' WHERE product_id = $p_id");
        
        $succeeded++;
        $logs[] = ["id" => $p_id, "name" => $p_name, "status" => "success", "path" => $db_path];
    } else {
        $failed++;
        $logs[] = ["id" => $p_id, "name" => $p_name, "status" => "failed", "reason" => "Failed to save WebP to disk"];
    }
    
    imagedestroy($img);
    imagedestroy($new_img);
}

$db->close();

echo json_encode([
    "status" => "success",
    "limit" => $limit,
    "offset" => $offset,
    "processed" => $processed,
    "succeeded" => $succeeded,
    "failed" => $failed,
    "next_offset" => $offset + $limit,
    "results" => $logs
], JSON_PRETTY_PRINT);
