<?php $load_url = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
include $load_url[0] . 'wp-load.php';
// Get the cart contents
$cart = WC()->cart->get_cart();

// Count the number of items in the cart
$itemCount = count($cart);

// Return the item count as a JSON response
header('Content-Type: application/json');
echo json_encode($itemCount);
 
