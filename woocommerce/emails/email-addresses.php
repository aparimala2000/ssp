<?php

/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 5.6.0
 */

if (!defined('ABSPATH')) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();
$customer_id = $order->get_customer_id();
$billing_country    = $order->get_billing_country();
$billing_phone = get_user_meta($customer_id, 'phone', true);
$billing_first_name = get_user_meta($customer_id, 'first_name', true);
$billing_last_name = get_user_meta($customer_id, 'last_name', true);
?><table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
	<tr>
		<td style="text-align:<?php echo esc_attr($text_align); ?>;font-family:'Montserrat-Medium', sans-serif; border:0; padding:0;" valign="top" width="50%">
			<h2><?php esc_html_e('Delivery address', 'woocommerce'); ?></h2>
			<address class="address" style="font-style: normal;">

				<?php if ($billing_country === "IN") {
					echo wp_kses_post('<span class="im">' . ($address ? $address . '<br />' : '') . "India</span>");
					// echo wp_kses_post($address ? $address . '<br />' . "India" : esc_html__('N/A', 'woocommerce'));
				} else {
					echo wp_kses_post($address ? $address : esc_html__('N/A', 'woocommerce'));
				} ?>

				<?php //echo wp_kses_post($address ? $address : esc_html__('N/A', 'woocommerce')); 
				?>
				<?php //echo wp_kses_post($address ? $address . '<br />' . esc_html($order->get_billing_country()) : esc_html__('N/A', 'woocommerce')); 
				?>


				<?php if ($order->get_billing_phone()) : ?>
					<br /><?php echo wc_make_phone_clickable($order->get_billing_phone()); ?>
				<?php endif; ?>
				<?php if ($order->get_billing_email()) : ?>
					<br /><?php echo esc_html($order->get_billing_email()); ?>
				<?php endif; ?>
			</address>
		</td>
		<?php if (!wc_ship_to_billing_address_only() && $order->needs_shipping_address() && $shipping) : ?>
			<td style="text-align:<?php echo esc_attr($text_align); ?>; font-family:'Montserrat-Medium', sans-serif; padding:0;" valign="top" width="50%">
				<h2><?php esc_html_e('Shipping address', 'woocommerce'); ?></h2>

				<address class="address">
					<?php echo wp_kses_post($shipping); ?>
					<?php if ($order->get_shipping_phone()) : ?>
						<br /><?php echo wc_make_phone_clickable($order->get_shipping_phone()); ?>
					<?php endif; ?>
				</address>
			</td>
		<?php endif; ?>
	</tr>
</table>