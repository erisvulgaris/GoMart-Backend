<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$redirect_url = "https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQGKTsJ9zYr0U3iZKlF-4KPb_80RnHaHSoVA84Uk_Qr-1tATRGWEnDjCAYeKr949LNHLnAZSMvfWOcSElg1IF0gCvHPetJkILong1pFUi6-BTBPwNrCCYYKR616GNGZR";

// Fetch product page following location redirects
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $redirect_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
$prod_html = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);

// Find og:image
preg_match('/<meta[^>]*property="og:image"[^>]*content="([^"]+)"/i', $prod_html, $og_matches);
$img_url = !empty($og_matches[1]) ? html_entity_decode($og_matches[1]) : null;

$img_data_len = 0;
$img_http_code = 0;
if ($img_url) {
    $ch3 = curl_init();
    curl_setopt($ch3, CURLOPT_URL, $img_url);
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch3, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch3, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $img_data = curl_exec($ch3);
    $img_http_code = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
    curl_close($ch3);
    $img_data_len = strlen($img_data);
}

echo json_encode([
    "http_code" => $http_code,
    "curl_error" => $err,
    "html_length" => strlen($prod_html),
    "og_image" => $img_url,
    "img_http_code" => $img_http_code,
    "img_data_len" => $img_data_len
]);
$db->close();
