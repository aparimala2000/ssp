<?php
$load_url = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
include $load_url[0] . 'wp-load.php';
global $woocommerce;

$productId = $_REQUEST['product_id'];
$quantity = isset($_REQUEST['qty']) ? intval($_REQUEST['qty']) : 1;
$proVar = $_REQUEST['variation_id'];
$product_type = $_REQUEST['product_type'];

// Set the session and cookie (similar to your previous code)
$COOKIE_NAME = "CART_COOKIE";
$COOKIE_VALUE = $product_type;
session_start();

if (!empty($product_type)) {
    $_SESSION['cart_type'] = $product_type;
    WC()->session->set('checkout_type', $product_type);
}

// Clear the cart
$woocommerce->cart->empty_cart();

// Add the new product to the cart
$cartAdd = $woocommerce->cart->add_to_cart($productId, $quantity, $proVar, null, null);

// Return a response (you can customize the response as needed)
$response = array('status' => 'success');

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
