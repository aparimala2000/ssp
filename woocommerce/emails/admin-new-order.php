<?php

/**
 * Admin new order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/admin-new-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails\HTML
 * @version 3.7.0
 */

defined('ABSPATH') || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email); ?>

<?php /* translators: %s: Customer billing full name */
$customer_id = $order->get_customer_id();
$billing_phone = get_user_meta($customer_id, 'phone', true);
$billing_first_name = get_user_meta($customer_id, 'first_name', true);
$billing_last_name = get_user_meta($customer_id, 'last_name', true);
$billing_full_name = $billing_first_name . ' ' . $billing_asst_name;
?>
<!-- <img src="http://localhost/ssp_new/wp-content/uploads/2023/03/logo.png" style="align-items: center; width: 35px;height: 35px;"> -->

<!-- <p><?php //printf(esc_html__('You’ve received the following order from %s:', 'woocommerce'), $order->get_formatted_billing_full_name()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
	?></p> -->
<p><?php printf(__('You’ve received an order from %s. The order is as follows:', 'woocommerce'), $order->get_formatted_billing_full_name()); ?></p>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ($additional_content) {
	// echo wp_kses_post(wpautop(wptexturize($additional_content)));
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);
