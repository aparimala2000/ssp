<?php
 $load_url = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
 include $load_url[0] . 'wp-load.php';
 global $wpdb;
 global $woocommerce;
 $product_type = $_REQUEST['product_type'];
 $COOKIE_NAME    = "CART_COOKIE";
 $COOKIE_VALUE   = $product_type; 
session_start();
if (!empty($product_type)) {
    // $_SESSION['cart_type'] = $product_type;
    WC()->session->set('checkout_type', $product_type);
} 

setcookie($COOKIE_NAME, $COOKIE_VALUE, $COOKIE_SET);