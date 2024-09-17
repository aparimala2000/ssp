<?php
global $_POST;
$load_url = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
include $load_url[0] . 'wp-load.php';

// Set the session cookie
if (isset($_POST['cookie_name']) && isset($_POST['cookie_value'])) {
    $cookieName = $_POST['cookie_name'];
    $cookieValue = $_POST['cookie_value'];

    // Set the session cookie
    setcookie($cookieName, $cookieValue, 0, '/');

    if (!isset($_COOKIE[$cookieName])) {
        echo 2;
    } else {
        echo 1;
    }
}
