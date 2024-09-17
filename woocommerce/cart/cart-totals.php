<?php

/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined('ABSPATH') || exit;
session_start();
$cartSession =  WC()->session->get('checkout_type');
if (isset($cartSession)) {
	$cartType = $cartSession;
}
// echo $cartType.'Test';
global $woocommerce;
$countries = new WC_Countries();
$cc = WC()->customer->get_shipping_country();
$allCountries = $countries->get_shipping_countries();
if ($cartType == "domestic") {
	$ex = 'disabled';
	$shippingCharge = "FREE";
} else {
	$shippingCharge = "₹" . get_option($cc . '_five') . ".00";
}
?>
<div class="cart_totals col-xl-4 <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>" style="display: block;">

	<?php do_action('woocommerce_before_cart_totals'); ?>
	<!-- <div class="col-xl-4"> -->
	<div class="cart-blk ml-xl-auto">
		<div class="pb-80">
			<h5 class="v1 mb-30"><?php esc_html_e('Summary', 'woocommerce'); ?></h5>
			<form>
				<div class="floating-blk">
					<label for="country_id" class="floating-row">
						<span class="arrow-btn ld"><i class="las la-angle-down"></i></span>
						<?php foreach (WC()->cart->get_cart() as $item) {
							$itemId = (!empty($item['variation_id'])) ? $item['variation_id'] : '';
							if (!empty($itemId)) {
								$minQty = get_post_meta($itemId, '_min_qty_', true);
								$maxQty = get_post_meta($itemId, '_max_qty_', true);
								$quantity = $item['quantity'];
								if ($item['data']->get_shipping_class() != 'enable' || $quantity < $minQty || $quantity > $maxQty) {
									WC()->customer->set_shipping_country('IN');
									WC()->customer->set_billing_country('IN');
						?>
						<?php  }
							}
						} ?>
						<select id="country_id" class="floating-input ld country_select" name="floating-label" <?php echo $ex; ?>>
							<option value="" hidden></option>
							<?php if ($cartType == "domestic") { ?>
								<option selected="true">India</option>
							<?php } else { ?>
								<option selected="true">Select Country</option>
								<?php
								foreach ($allCountries as $key => $countryName) {
									$countryCode = $key;
									$sel = ($countryCode == $cc) ? "selected" : " ";
									if ($countryCode != "IN") {
								?>
										<option <?php echo $sel; ?> data-code="<?php echo $countryCode; ?>" data-val="<?php echo get_option($countryCode . '_five'); ?>"><?php echo $countryName; ?></option>
							<?php }
								}
							}
							?>
						</select>
					</label>
					<p class="floating-input-error" id="country-err">Please select country</p>
				</div>
				<?php if (empty(WC()->cart->get_coupons())) { ?>
					<div class="search-blk voucher-code">
						<input class="search-box w-100" type="text" id="coupon_cd" placeholder="voucher code" name='apply_coupon' />
						<a href="javascript:void(0);" class="search-btn button apply_coupon">Apply</a>
					</div>
					<div class="err-msg">Please enter a valid coupon.</div>
				<?php } else { ?>
					<?php foreach (WC()->cart->get_coupons() as $code => $coupon) :
						$ccode = esc_attr(sanitize_title($code));
						if ($coupon->discount_type == "percent") {
							$coupon_amount = $coupon->coupon_amount . "%";
						} else {
							$coupon_amount = $coupon->coupon_amount;
						}

					?>
				     <div class="d-flex justify-content-between align-items-center mb-30 coupon-succ" style="display: block;">
							<span class="b-font ld" style="color: green;">Discount (<?php echo $coupon_amount . ' - <span>' . $ccode . '</span>'; ?>)</span>
							<span class="b-font ld"><?php //wc_cart_totals_coupon_html($coupon); ?><a href="javascript:void(0);" class="code-remove">Remove</a></span>
						</div>
				<?php endforeach;
				} ?>
			</form>
		</div>
		<?php wc_cart_totals_shipping_html(); ?>

		<div class="d-flex justify-content-between align-items-center">
			<span class="b-font ld cart-tot-val">Total</span>
			<span class="b-font ld cart-tot" id="cart_total"><?php wc_cart_totals_order_total_html(); ?></span>
		</div>
		<div class="mb-50"></div>
		<div class="d-flex justify-content-between align-items-center">
			<?php do_action('woocommerce_proceed_to_checkout'); ?>
		</div>
	</div>
	<!-- </div> -->

	<table cellspacing="0" class="shop_table shop_table_responsive" style="display: block;">


		<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

			<?php do_action('woocommerce_cart_totals_before_shipping'); ?>

			<?php //wc_cart_totals_shipping_html(); 
			?>

			<?php do_action('woocommerce_cart_totals_after_shipping'); ?>

		<?php elseif (WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc')) : ?>

			<tr class="shipping">
				<th><?php esc_html_e('Shipping', 'woocommerce'); ?></th>
				<td data-title="<?php esc_attr_e('Shipping', 'woocommerce'); ?>"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>

		<?php endif; ?>

		<?php foreach (WC()->cart->get_fees() as $fee) : ?>
			<tr class="fee">
				<th><?php echo esc_html($fee->name); ?></th>
				<td data-title="<?php echo esc_attr($fee->name); ?>"><?php wc_cart_totals_fee_html($fee); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php
		if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if (WC()->customer->is_customer_outside_base() && !WC()->customer->has_calculated_shipping()) {
				/* translators: %s location. */
				$estimated_text = sprintf(' <small>' . esc_html__('(estimated for %s)', 'woocommerce') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
			}

			if ('itemized' === get_option('woocommerce_tax_total_display')) {
				foreach (WC()->cart->get_tax_totals() as $code => $tax) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
						<th><?php echo esc_html($tax->label) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							?></th>
						<td data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); ?></td>
					</tr>
				<?php
				}
			} else {
				?>
				<tr class="tax-total">
					<th><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						?></th>
					<td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
		<?php
			}
		}
		?>

		<?php do_action('woocommerce_cart_totals_before_order_total'); ?>


		<?php do_action('woocommerce_cart_totals_after_order_total'); ?>

	</table>



	<?php do_action('woocommerce_after_cart_totals'); ?>

</div>