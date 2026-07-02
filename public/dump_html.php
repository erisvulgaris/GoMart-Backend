<?php
$query = urlencode('Fresh Tomato site:blinkit.com');
$url = "https://www.google.com/search?q=" . $query;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
$html = curl_exec($ch);
curl_close($ch);

file_put_contents(__DIR__ . '/uploads/google_vps_search.html', $html);
echo "HTML dumped successfully. Size: " . strlen($html);
