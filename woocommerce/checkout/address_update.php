<?php
$load_url = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
include $load_url[0] . 'wp-load.php';
global $wpdb;
	$first_name = $_POST['firstname'];
	$last_name = $_POST['lastname'];
	$email_address = $_POST['email_address'];
	$phone_number = $_POST['phone_number'];
	$address = $_POST['address'];
	$postcode = $_POST['postcode'];
	$address_key = $_POST['addresskey'];
	$city=$_POST['city'];
	$state=$_POST['state'];
	$cust_id = get_current_user_id();
	// var_dump($cust_id);
	$existing_addresses = get_user_meta($cust_id, 'user_addresses', 'true');
	unset($existing_addresses[$address_key]);	
	update_user_meta( $cust_id, 'user_addresses', $existing_addresses );
	$new_addr = get_user_meta($cust_id, 'user_addresses', 'true');
	$new_addr[$address_key]['first_name'] = $first_name;
	$new_addr[$address_key]['last_name'] = $last_name;
	$new_addr[$address_key]['email'] = $email_address;
	$new_addr[$address_key]['phone'] = $phone_number;
	$new_addr[$address_key]['address_1'] = $address;
	$new_addr[$address_key]['city'] = $city;
	$new_addr[$address_key]['state'] = $state;
	$new_addr[$address_key]['postcode'] = $postcode;
	delete_user_meta($cust_id, 'user_addresses');
// if ($first_name != '') {
//    update_user_meta( $cust_id, 'user_addresses', $new_addr );
//    		unset($_POST);
//    		// exit();
// }
$response = array('first_name' =>$first_name , 'last_name' => $last_name, 'email' => $email_address,'phone' => $phone_number,'address_1' => $address,'postcode' => $postcode, 'city' => $city,'state' => $state, 'address_key' =>$address_key);

   echo json_encode($response);
