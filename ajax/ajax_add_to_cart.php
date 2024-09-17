<?php	
$load_url = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
include $load_url[0] . 'wp-load.php';
global $wpdb;
global $woocommerce;
$cartUrl = $woocommerce->cart->get_cart_url();
$productId = $_REQUEST['product_id'];
$quantity = $_REQUEST['qty']?$_REQUEST['qty']:1;
$proVar = $_REQUEST['variation_id'];
$product_type = $_REQUEST['product_type'];
$COOKIE_NAME    = "CART_COOKIE";
$COOKIE_VALUE   = $product_type;
session_start();
if (!empty($product_type)) {
	$_SESSION['cart_type'] = $product_type;
	WC()->session->set('checkout_type', $product_type);
}
// echo $COOKIE_NAME. $COOKIE_VALUE;
if (!isset($_COOKIE[$COOKIE_NAME])) {
setcookie($COOKIE_NAME, $COOKIE_VALUE, $COOKIE_SET);
$cartAdd = $woocommerce->cart->add_to_cart($productId, $quantity, $proVar, null, null);
$response = array('status' => 'success');
// echo count($woocommerce->cart->get_cart());
} elseif(isset($_COOKIE[$COOKIE_NAME]) && (($_COOKIE[$COOKIE_NAME]) == $product_type)) {
$cartAdd = $woocommerce->cart->add_to_cart($productId, $quantity, $proVar,null,null);
$response = array('status' => 'success');
// echo count($woocommerce->cart->get_cart());
}
elseif(isset($_COOKIE[$COOKIE_NAME]) && (($_COOKIE[$COOKIE_NAME]) != $product_type)){
setcookie($COOKIE_NAME, $COOKIE_VALUE, $COOKIE_SET);
$response = array('status' => 'different', 'Product_type' => $COOKIE_VALUE);
// $woocommerce->cart->empty_cart();
// $cartAdd = $woocommerce->cart->add_to_cart($productId, $quantity, $proVar,null,null);
// echo count($woocommerce->cart->get_cart());
}
header('Content-Type: application/json');
echo json_encode($response);
// ob_start();
// woocommerce_mini_cart();
// $mini_cart_html = ob_get_clean();

// echo $mini_cart_html;
// session_start();
// $_SESSION['cart_type'] = $product_type;
$errors= wc_get_notices( 'error' );
foreach ($errors as $error) {
	echo 'error_log:'.$error;
}
wc_clear_notices();


			
