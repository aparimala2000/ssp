<?php
// Visitor IP
function get_client_ip() {
$ip_address = '';

if (isset($_SERVER['HTTP_CLIENT_IP'])) {
$ip_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
$ip_address = $_SERVER['HTTP_X_FORWARDED'];
} elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
$ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
} elseif (isset($_SERVER['HTTP_FORWARDED'])) {
$ip_address = $_SERVER['HTTP_FORWARDED'];
} elseif (isset($_SERVER['REMOTE_ADDR'])) {
$ip_address = $_SERVER['REMOTE_ADDR'];
}

return $ip_address;
}
// User country
function get_user_country_name() {
$ip = $_SERVER['REMOTE_ADDR'];
$api_key = '6db7ef388a95ba';
$clientIP = get_client_ip();
$url = "https://ipinfo.io/{$clientIP}?token={$api_key}";
$response = wp_remote_get($url);
if (is_wp_error($response)) {
return 'Unknown';
}
$data = json_decode(wp_remote_retrieve_body($response), true);

return isset($data['country']) ? $data['country'] : 'Unknown';

}
add_shortcode('user_country', 'get_user_country_name');
