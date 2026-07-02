<?php
header('Content-Type: application/json');

$query = urlencode('Fresh Tomato site:blinkit.com');
$url = "https://search.aol.com/aol/search?q=" . $query;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
$html = curl_exec($ch);
$err = curl_error($ch);
$errno = curl_errno($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Parse urls containing blinkit.com from AOL search
// e.g. <a href="https://blinkit.com/prn/fresh-tomato..." class="...">
preg_match_all('/href="([^"]*blinkit\.com[^"]*)"/', $html, $matches);

echo json_encode([
    "url" => $url,
    "http_code" => $http_code,
    "curl_error" => $err,
    "curl_errno" => $errno,
    "html_length" => strlen($html),
    "matches_count" => count($matches[1] ?? []),
    "matches" => $matches[1] ?? []
]);
