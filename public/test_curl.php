<?php
header('Content-Type: application/json');

$query = urlencode('Fresh Tomato (Hybrid) site:blinkit.com');
$url = "https://html.duckduckgo.com/html/?q=" . $query;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
$search_html = curl_exec($ch);
$err = curl_error($ch);
$errno = curl_errno($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Regex matching
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

echo json_encode([
    "url" => $url,
    "http_code" => $http_code,
    "curl_error" => $err,
    "curl_errno" => $errno,
    "html_length" => strlen($search_html),
    "matches_count" => count($matches[1] ?? []),
    "all_matches" => $matches[1] ?? [],
    "selected_product_url" => $product_url,
    "is_bot_blocked" => (strpos($search_html, "check your browser") !== false || strpos($search_html, "If you are a human") !== false) ? "Yes" : "No"
]);
