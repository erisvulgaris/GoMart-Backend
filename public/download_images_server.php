<?php
// Set execution time limit to 10 minutes
set_time_limit(600);
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$res = $db->query("SELECT id, product_name FROM product WHERE is_delete = 0");
$products = [];
while ($row = $res->fetch_assoc()) {
    $products[] = $row;
}

$success_count = 0;
$log = [];

// Create products folder if not exists
$dir = __DIR__ . '/uploads/products';
if (!file_exists($dir)) {
    mkdir($dir, 0755, true);
}

foreach ($products as $p) {
    $p_id = $p['id'];
    $p_name = $p['product_name'];
    
    // Create clean slug
    $slug = preg_replace('/[^a-z0-9]+/', '_', strtolower(trim($p_name)));
    $slug = trim($slug, '_');
    $local_path = $dir . '/' . $slug . '.jpg';
    $db_path = 'uploads/products/' . $slug . '.jpg';
    
    // Search on DuckDuckGo HTML
    $query = urlencode($p_name . ' site:blinkit.com');
    $url = "https://html.duckduckgo.com/html/?q=" . $query;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
    $search_html = curl_exec($ch);
    curl_close($ch);
    
    // Extract actual link from DDG redirects
    preg_match_all('/uddg=(https%3A%2F%2Fblinkit\.com%2F[^&"\']+)/', $search_html, $matches);
    
    $product_url = null;
    if (!empty($matches[1])) {
        foreach ($matches[1] as $m) {
            $decoded_url = urldecode($m);
            if (strpos($decoded_url, '/prn/') !== false || strpos($decoded_url, '/prid/') !== false || strpos($decoded_url, '/prd/') !== false) {
                $product_url = $decoded_url;
                break;
            }
        }
    }
    
    $downloaded = false;
    if ($product_url) {
        // Fetch product page
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $product_url);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        $prod_html = curl_exec($ch2);
        curl_close($ch2);
        
        // Find og:image
        preg_match('/<meta[^>]*property="og:image"[^>]*content="([^"]+)"/i', $prod_html, $og_matches);
        $img_url = null;
        if (!empty($og_matches[1])) {
            $img_url = html_entity_decode($og_matches[1]);
        } else {
            // Try twitter:image
            preg_match('/<meta[^>]*name="twitter:image"[^>]*content="([^"]+)"/i', $prod_html, $tw_matches);
            if (!empty($tw_matches[1])) {
                $img_url = html_entity_decode($tw_matches[1]);
            }
        }
        
        if ($img_url) {
            // Download image
            $ch3 = curl_init();
            curl_setopt($ch3, CURLOPT_URL, $img_url);
            curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch3, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch3, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
            $img_data = curl_exec($ch3);
            $http_code = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
            curl_close($ch3);
            
            if ($http_code == 200 && $img_data) {
                file_put_contents($local_path, $img_data);
                $db_path_esc = $db->real_escape_string($db_path);
                $db->query("UPDATE product SET main_img = '$db_path_esc' WHERE id = $p_id");
                $downloaded = true;
                $success_count++;
                $log[] = "Downloaded: " . $p_name . " -> " . $db_path;
            }
        }
    }
    
    if (!$downloaded) {
        $log[] = "Failed: " . $p_name;
    }
    
    // Sleep 3 seconds to avoid rate limiting
    sleep(3);
}

echo json_encode([
    "status" => "success",
    "updated_count" => $success_count,
    "log" => $log
]);
$db->close();
