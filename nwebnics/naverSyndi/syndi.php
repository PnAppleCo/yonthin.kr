<?php
$ping_auth_header = "Authorization: Bearer KKAARBaYmh9…(중략)…Wdjghm1c";
$ping_url = "http%3A%2F%2Fwww.example.com %2Fsyndi%2Fdoc.xml";
$ping_client_opt = array(
CURLOPT_URL => "https://apis.naver.com/crawl/nsyndi/v2",
CURLOPT_POST => true,
CURLOPT_POSTFIELDS => "ping_url=" . $ping_url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_CONNECTTIMEOUT => 10,
CURLOPT_TIMEOUT => 10,
CURLOPT_HTTPHEADER => array("Host: apis.naver.com", "Pragma: no-cache", "Accept: */*", $ping_auth_header)
);
$ping = curl_init();
curl_setopt_array($ping, $ping_client_opt);
curl_exec($ping);
curl_close($ping);
?>