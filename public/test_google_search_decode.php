<?php
header('Content-Type: application/json');

$query = urlencode('Fresh Tomato site:blinkit.com');
$url = "https://www.google.com/search?q=" . $query;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
$html = curl_exec($ch);
curl_close($ch);

// Match all /url?q= links
preg_match_all('/\/url\?q=([^&"\'>]+)/', $html, $matches);

$blinkit_urls = [];
if (!empty($matches[1])) {
    foreach ($matches[1] as $m) {
        $decoded = urldecode($m);
        if (strpos($decoded, 'blinkit.com') !== false) {
             $blinkit_urls[] = $decoded;
        }
    }
}

echo json_encode([
    "url" => $url,
    "html_length" => strlen($html),
    "all_url_q_matches_count" => count($matches[1] ?? []),
    "blinkit_urls" => array_values(array_unique($blinkit_urls))
]);
