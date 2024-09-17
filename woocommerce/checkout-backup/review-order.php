<?php

/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */
$product = new WC_Product_Variable($post->ID);
$variations = $product->get_available_variations();
if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="shop_table woocommerce-checkout-review-order-table">
	<div class="mb-50">
		<h5 class="v1 mr-3 mb-30">Order Details</h5>
		<?php
		do_action('woocommerce_review_order_before_cart_contents');
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$_product     = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
			$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
			$var_id = $cart_item['variation_id'];
			$variation_product = new WC_Product_Variation($var_id);
			$var_weight = $variation_product->weight;

			if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
		?>

				<div class="d-flex justify-xl-content-between">
					<?php $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

					$thumbnail = preg_replace('/(width|height)=\"\d*\"\s/', "", $thumbnail); ?>
					<a href="#" class="product-thum mb-3" style="background-color:#fff"><?php echo $thumbnail; ?></a>
					<div>
						<div>
							<span class="md mb-2"><?php echo apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;'; ?></span>
							<?php $qtyy = $cart_item['quantity'];
							if (!empty($cart_item['variation_id'])) {
								$var_id = $cart_item['variation_id'];
								$var_val = wc_get_product($var_id);
								$var_reg_prc = $var_val->get_regular_price();
								$var_sal_prc = $var_val->get_sale_price();
								$var_prc = $var_sal_prc == '' ? $var_reg_prc : $var_sal_prc;
								if ($var_sal_prc != '') {
							?>
									<span class="md mb-1">&#x20B9;<?php echo $var_reg_prc * $qtyy; ?></span>
									<span class="md mb-1" style="display: none">&#x20B9;<?php echo $var_prc; ?></span>
									<input type="hidden" name="check-prc" class="check-prc" value="<?php echo $var_prc; ?>">
									<input type="hidden" name="unit-prc" class="unit-prc" value="<?php echo $var_reg_prc; ?>">
								<?php
								} else {
								?>
									<span class="md mb-1">&#x20B9;<?php echo  $var_reg_prc * $qtyy; ?></span>
									<span class="md mb-1" style="display: none"></span>
									<input type="hidden" name="check-prc" class="check-prc" value="<?php echo  $var_reg_prc; ?>">
								<?php
								}
								?>
								<?php } else {
								$simpleid = $product_id;
								$regular_price = $_product->get_regular_price();
								$sale_price = $_product->get_sale_price();
								if ($sale_price != '') {
								?>
									<span class="md mb-1">&#x20B9;<?php echo $regular_price * $qtyy; ?></span>
									<span class="md mb-1" style="display: none">&#x20B9;<?php echo $sale_price; ?></span>
									<input type="hidden" name="check-prc" class="check-prc" value="<?php echo $sale_price; ?>">
									<input type="hidden" name="unit-prc" class="unit-prc" value="<?php echo $regular_price; ?>">
								<?php } else { ?>
									<span class="md mb-1">&#x20B9;<?php echo $regular_price * $qtyy; ?></span>
									<span class="md mb-1" style="display: none"></span>
									<input type="hidden" name="check-prc" class="check-prc" value="<?php echo $regular_price; ?>">

								<?php } ?>
							<?php } ?>

							<span class="sm mb-3 d-inline-block">(<?php echo $var_weight . " gms"; ?>)</span>

							<?php $qty = $cart_item['quantity'];
							$product_quantity = sprintf('
									<div class="count" id="preload' . $product_id . '">
										<span class="minus box" disabled="disabled">-</span>
										<input style="display:none" onchange="quantityonchange(' . $product_id . ',this)" id="qty" type="text" class="textbox-small" name="cart[%s][qty]" value="%d" />
										<input id="dummyQty" type="text" readonly class="box" value="%d" />
										<span class="plus box" data-field="quantity" disabled="disabled">+</span>
									</div>', $cart_item_key, $qty, $qty);

							echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); ?>
						</div>
					</div>
				</div>

		<?php
			}
		} ?>
	</div>
	<?php

	do_action('woocommerce_review_order_after_cart_contents');
	?>
	<?php if (wc_coupons_enabled()) {
		$couponArray = WC()->cart->get_coupons();
	}
	$couponArray = WC()->cart->get_coupons();
	$coup_style = count($couponArray) != 0 ? "active" : '';


	foreach ($couponArray  as $code => $coupon) :
		$ccode = esc_attr(sanitize_title($code));
		if ($coupon->discount_type == "percent") {
			$coupon_amount = $coupon->coupon_amount . "%";
			$perc_disc = $coupon->coupon_amount;
		} else {
			$coupon_amount = $coupon->coupon_amount;
		}
	endforeach;
	?>
	<div class="cart-table">
		<form>
			<div class="position-relative mb-40">

				<?php
				if (wc_coupons_enabled()) {
				?>
					<div class="search-blk voucher-code">
						<input class="search-box w-100" type="text" id="checkout_coupon_code" placeholder="Voucher Code">
						<a href="javascript:void(0);" class="search-btn button" id="checkout_apply_coupon">Apply</a>
					</div>
					<div class="err-msg">Please enter a valid coupon.</div>
					<div class="success-msg d-flex align-items-center justify-content-between <?php echo $coup_style; ?>">
						<div>
							<span class="lg b-font applied_txt">#<?php echo $ccode; ?></span>
							<span class="md m-font mt-1 green"><?php //echo $coupon_amount; ?> Offer applied on the bill</span>
						</div>
						<a href="javascript:void(0);" class="link-anim md ccode-remove">Remove</a>
					</div>

				<?php } ?>

				<?php do_action('woocommerce_cart_actions'); ?>

				<?php wp_nonce_field('woocommerce-cart'); ?>

			</div>
		</form>
	</div>

	<table class="cart-table with-check mb-30">
		<tbody>
			<tr>
				<td>
					<span class="md">Price</span>
				</td>
				<td>
					<?php
					$total = array();
					foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
						$_product     = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
						$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
						$variation_id = $cart_item['variation_id'];
						$qtyy = $cart_item['quantity'];
						if (!empty($cart_item['variation_id'])) {
							$var_id = $cart_item['variation_id'];
							$var_val = wc_get_product($var_id);
							$reg = get_post_meta($var_id, '_regular_price', true) * $qtyy;
						} else {
							$reg = get_post_meta($product_id, '_regular_price', true) * $qtyy;
						}
						$total[] = $reg;
					}
					foreach ($total as $key => $totalval) {
						$sum += $totalval;
					}
					$subVal = WC()->cart->subtotal;
					// $discVal = $sum - $subVal;
					$discVal = (($sum * $perc_disc)/100);
					$discVal = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $discVal);
					$sum = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $sum);
					?>
					<span class="md">&#x20B9;<?php echo $sum; ?>.00</span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="md">Shipping</span>
				</td>
				<td>
					<?php $shipping_cost = WC()->cart->get_cart_shipping_total(); ?>
					<span class="md"><?php echo $shipping_cost; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="md">Discount</span>
				</td>
				<td>
					<span class="md">-&#x20B9;<?php echo $discVal; ?>.00</span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="b-font md">Total</span>
				</td>
				<td>
					<span class="b-font md"><?php wc_cart_totals_order_total_html(); ?></span>
				</td>
			</tr>
		</tbody>
	</table>

	<h6 class="mb-3">Payment</h6>
</div>
