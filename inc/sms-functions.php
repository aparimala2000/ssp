<?php
// CURL Functions for SMS with 2factor api integration
//add this line for calling your function on creation of order in woocommerce - SM
add_action('woocommerce_order_status_processing', 'new_order', 10, 3);
function new_order($order_id)
{
    $order = new WC_Order($order_id);
    $items = $order->get_items();
    //fetch all required fields
    $billing_phone = $order->get_billing_phone();
    $total_bill = $order->get_total();
    $order_date = $order->order_date;
    $billing_first_name = $order->get_billing_first_name();
    $billing_last_name = $order->get_billing_last_name();
    $billing_email = $order->get_billing_last_name();
    $billing_state = $order->get_billing_state();
    $billing_address_1 = $order->get_billing_address_1();
    $billing_postcode = $order->get_billing_postcode();
    $billing_country = $order->get_billing_country();
    $billing_city = $order->get_billing_city();
    update_post_meta($order->ID, '_shipping_first_name', $billing_first_name);
    update_post_meta($order->ID, '_shipping_last_name', $billing_last_name);
    update_post_meta($order->ID, '_shipping_address_1', $billing_address_1);
    update_post_meta($order->ID, '_shipping_city', $billing_city);
    update_post_meta($order->ID, '_shipping_state', $billing_state);
    update_post_meta($order->ID, '_shipping_postcode', $billing_postcode);
    update_post_meta($order->ID, '_shipping_country', $billing_country);
    update_post_meta($order->ID, '_shipping_email', $billing_email);
    update_post_meta($order->ID, '_shipping_phone', $billing_phone);
    $Sms_func = neworder_conf($billing_phone, $order->get_order_number(), $order_date);
    return $order_id;
}

// Curl for new order confirmation - SM
function neworder_conf($phone, $order_id, $ord_date)
{
    $smsUrl = "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=55ac36b7-6881-11e7-94da-0200cd936042&to=$phone&from=SSPING&templatename=SSPORDCNF&var1=$phone&var2=$order_id&var3=$ord_date";
    $curl_fnc = curl_init();
    $headers = array(
        'Content-Type: application/x-www-form-urlencoded',
        'charset: utf-8',
    );
    curl_setopt($curl_fnc, CURLOPT_URL, $smsUrl);
    curl_setopt($curl_fnc, CURLOPT_HEADER, true);
    curl_setopt($curl_fnc, CURLOPT_VERBOSE, 1);
    curl_setopt($curl_fnc, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl_fnc, CURLOPT_POST, 1);
    curl_setopt($curl_fnc, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl_fnc);
    $header_size = curl_getinfo($curl_fnc, CURLINFO_HEADER_SIZE);
    $body = substr($output, $header_size);
    curl_close($curl_fnc);
    return $smsUrl;
}

// Curl for order cancellation - SM
function order_cancel($phone, $fname, $order_id, $items, $total)
{
    $smsUrl = "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=55ac36b7-6881-11e7-94da-0200cd936042&to=$phone&from=SSPORD&templatename=SSPOCNCL&var1=$fname&var2=$order_id&var3=$items&var4=$total";
    $curl_fnc = curl_init();
    $headers = array(
        'Content-Type: application/x-www-form-urlencoded',
        'charset: utf-8',
    );
    curl_setopt($curl_fnc, CURLOPT_URL, $smsUrl);
    curl_setopt($curl_fnc, CURLOPT_HEADER, true);
    curl_setopt($curl_fnc, CURLOPT_VERBOSE, 1);
    curl_setopt($curl_fnc, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl_fnc, CURLOPT_POST, 1);
    curl_setopt($curl_fnc, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl_fnc);
    $header_size = curl_getinfo($curl_fnc, CURLINFO_HEADER_SIZE);
    $body = substr($output, $header_size);
    curl_close($curl_fnc);
    return $smsUrl;
}

add_action('woocommerce_order_status_processing_to_cancelled', 'cancelsms', 10, 3);
add_action('woocommerce_order_status_processing_to_cancelled_by_customer', 'cancelsms', 10, 3);
function cancelsms($order_id)
{
    $order = new WC_Order($order_id);
    $status = $order->get_status(); // order status
    $items = $order->get_items();
    $item = count($items);
    //fetch all required fields
    $billing_phone = $order->get_billing_phone();
    $billing_name = $order->get_billing_first_name();
    $billing_last_name = $order->get_billing_last_name();
    $total_bill = $order->get_total();
    $order_date = $order->order_date;
    $Sms_func = order_cancel($billing_phone, $billing_name, $order_id, $item, $total_bill);
    return $order_id;
}
