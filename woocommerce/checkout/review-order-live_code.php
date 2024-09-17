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
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */
defined('ABSPATH') || exit;

$product = new WC_Product_Variable($post->ID);
$variations = $product->get_available_variations();
session_start();
$cartSession = WC()->session->get('checkout_type');
if (isset($cartSession)) {
	$checkoutProType = $cartSession;
}
?>
<div>

	<div class="mb-50">
		<h5 class="v1 mr-3 mb-30">Order Details</h5>
		<div>
			<?php
			do_action('woocommerce_review_order_before_cart_contents');
			$last_row_added = false;
			$free_product_id = 0;
			$free_variation_id = 0;
			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				$_product     = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
				$var_id = $cart_item['variation_id'];
				$variation_product = new WC_Product_Variation($var_id);
				$product_image_url = get_the_post_thumbnail_url($product_id, 'thumbnail');
				$var_weight = $variation_product->weight;
				if ($var_weight >= 1000) {
					$var_weight_display = ($var_weight / 1000) . ' kg';
				} else {
					$var_weight_display = $var_weight . ' gms';
				}
				$pr_name = $_product->get_name();
				if (strpos($pr_name, ' - ') !== false) {
					$pr_name = substr($pr_name, 0, strpos($pr_name, ' - '));
				}

				if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
			?>
					<?php if ($product_id == 11899) {
						$has_last_row = true;
						$free_product_id = $product_id;
						$free_variation_id = $var_id;
						$free_product_name = $pr_name;
						$free_product_image = $product_image_url;
						continue;
					?>

					<?php 	} else { ?>
						<div class="cart-row">
							<!-- <div class="d-flex align-items-start justify-content-between"> -->
								<div class="d-flex">
									<div class="sm-icon"><img src="<?php echo $product_image_url; ?>"></div>
									<div class="flex-grow-1">
									<div class="d-flex justify-content-between">
										<span class="md px-3"><?php echo  $pr_name; ?></span>
										<?php $qtyy = $cart_item['quantity'];
										if (!empty($cart_item['variation_id'])) {
											$var_id = $cart_item['variation_id'];
											$var_val = wc_get_product($var_id);
											$var_reg_prc = $var_val->get_regular_price();
											$var_sal_prc = $var_val->get_sale_price();
											$var_prc = $var_sal_prc == '' ? $var_reg_prc : $var_sal_prc;
											if ($var_sal_prc != '') {
										?>
												<span class="md text-right" id="checkout_price<?php echo $var_id; ?>"><?php echo wc_price($var_reg_prc * $qtyy); ?></span>
												<span class="md mb-1" style="display: none">&#x20B9;<?php echo $var_prc; ?></span>
												<input type="hidden" name="check-prc" class="check-prc" value="<?php echo $var_prc; ?>">
												<input type="hidden" name="unit-prc" class="unit-prc" value="<?php echo $var_reg_prc; ?>">
											<?php
											} else {
											?>
												<span class="md text-right" id="checkout_price<?php echo $var_id; ?>"><?php echo  wc_price($var_reg_prc * $qtyy); ?></span>
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
												<span class="md text-right" id="checkout_price<?php echo $simpleid; ?>"><?php echo $regular_price * $qtyy; ?></span>
												<span class="md mb-1" style="display: none">&#x20B9;<?php echo $sale_price; ?></span>
												<input type="hidden" name="check-prc" class="check-prc" value="<?php echo $sale_price; ?>">
												<input type="hidden" name="unit-prc" class="unit-prc" value="<?php echo $regular_price; ?>">
											<?php } else { ?>
												<span class="md text-right" id="checkout_price<?php echo $simpleid; ?>"><?php echo wc_price($regular_price * $qtyy); ?></span>
												<span class="md mb-1" style="display: none"></span>
												<input type="hidden" name="check-prc" class="check-prc" value="<?php echo $regular_price; ?>">

											<?php } ?>
										<?php } ?>
									</div>
										<span class="sm mb-3 d-inline-block px-3">(<?php echo $var_weight_display; ?>)</span>
										<div class="d-flex justify-content-between">
														<div class="d-flex align-items-center px-3">
										<?php if ($checkoutProType == "domestic") { ?>
											<?php $qty = $cart_item['quantity'];
											$product_quantity = sprintf('
									<div class="count" id="preload' . $product_id . '">
										<span class="domes_check_dec box dummy-box" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $var_id . '>-</span>
										<input style="display:none" onchange="quantityonchange(' . $product_id . ',this)" id="qty" type="text" class="textbox-small" name="cart[%s][qty]" value="%d" />
										<input id="domes_check_qty' . $var_id . '" type="text" readonly class="box" value="%d" />
										<span class="domes_check_inc box active dummy-box" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $var_id . ' data-field="quantity">+</span>
									</div>', $cart_item_key, $qty, $qty);

											echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); ?>
										<?php } else { ?>
											<?php $qty = $cart_item['quantity'];
											if ($cart_item['data']->get_shipping_class() === 'enable') {
												$variation = $cart_item['data'];
												$minqty = get_post_meta($variation->get_variation_id(), '_min_qty_', true);
												$maxqty = get_post_meta($variation->get_variation_id(), '_max_qty_', true);
											}
											$product_quantity = sprintf('
									<div class="count" id="preload' . $product_id . '">
										<span class="glob_check_dec box"  data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $var_id . ' data-min=' . $minqty . ' data-max=' . $maxqty . '>-</span>
										<input style="display:none" onchange="quantityonchange(' . $product_id . ',this)" id="qty" type="text" class="textbox-small" name="cart[%s][qty]" value="%d" />
										<input id="glob_check_qty' . $var_id . '" type="text" readonly class="box" value="%d" />
										<span class="glob_check_inc box active" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $var_id . ' data-field="quantity" data-min=' . $minqty . ' data-max=' . $maxqty . '>+</span>
									</div>', $cart_item_key, $qty, $qty);

											echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); ?>
										<?php } ?>
										<a href="javascript:void(0);" class="trash-icon ml-5 checkout_product_remove" data-product-id="<?php echo $product_id; ?>" data-variation-id="<?php echo $var_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
									</div>
								</div>
								</div>
								</div>
							<!-- </div> -->
						</div>

					<?php } ?>


			<?php
				}
			} ?>
			<?php if ($has_last_row) { ?>
				<div class="cart-row">
					<!-- <div class="d-flex align-items-start justify-content-between"> -->
					<div class="d-flex">
						<div class="sm-icon"><img src="<?php echo $free_product_image; ?>"></div>
						<div class="flex-grow-1">
							<div class="d-flex justify-content-between">
								<span class="md px-3"><?php echo $free_product_name; ?></span>
								<span class="md  text-right"><?php echo wc_price("0.00"); ?></span>
							</div>
							<span class="sm mb-3 d-inline-block px-3">Free Gift</span>
							<!-- <div class="d-flex justify-content-between">
								<div class="d-flex align-items-center px-3">
									<div class="count">
										<span class="box">-</span>
										<input class="box" type="text" name="" value="1" readonly>
										<span class="box">+</span>
									</div>
								</div>
							</div> -->
						</div>
					</div>
					<!-- <a href="javascript:void(0);" class="circle-icon checkout_product_remove type2" data-product-id="<?php echo $free_product_id; ?>" data-variation-id="<?php echo $free_variation_id; ?>"><i class="las la-times"></i></a> -->
					<!-- </div> -->
				</div>
			<?php } ?>
		</div>
	</div>
	<?php

	do_action('woocommerce_review_order_after_cart_contents');
	?>
	<?php
	function is_customer_last_purchase_3_months_ago()
	{
		// Check if the user is logged in
		if (is_user_logged_in()) {
			$user_id = get_current_user_id();

			// Get all orders for the user
			$customer_orders = wc_get_orders(['customer' => $user_id]);

			// Check if there are at least 2 orders
			if (count($customer_orders) >= 2) {
				// Get the current date
				$current_date = new DateTime();

				// Count orders within the last 3 months
				$count_recent_orders = 0;
				foreach ($customer_orders as $order) {
					if ($order->get_status() === 'completed') {
					$order_date = $order->get_date_created();
					$interval = $current_date->diff($order_date);
					$months_difference = $interval->y * 12 + $interval->m;

					if ($months_difference <= 3) {
						$count_recent_orders++;
					}
				}
				}

				// Check if the customer has at least 2 orders within the last 3 months
				if ($count_recent_orders >= 2) {
					return true;
				} else {
					return false;
				}
			} else {
				return false; // Customer has fewer than 3 orders
			}
		} else {
			return false; // User is not logged in
		}
		 
	}

	// Display the result
	$is_qualifying_customer = is_customer_last_purchase_3_months_ago();
	// var_dump($is_qualifying_customer);
	?>
	<!-- apply coppen start -->
	<div class="coupon-success-blk mb-50">
		<a href="javascript:void(0)" id="apply-coupon-btn" class="button mb apply-coupon opn-coupon apply_coupon_check_btn">
			<div>
				<img src="<?php echo get_bloginfo('template_url'); ?>/lib/images/discount1.svg">
				View All Coupons
			</div>
			<span><i class="fa fa-angle-right" aria-hidden="true"></i></span>
		</a>
		<div class="success-msg">
			<div class="d-flex align-items-center justify-content-between">
				<div>
					<span class="md" id="applied_coupon"></span>
					<span class="sm m-font mt-1 d-block green ">Offer applied on the bill</span>
				</div>
				<a href="javascript:void(0);" class="link-anim md code-remove">Remove</a>
			</div>
		</div>
		<div id="coupon-success-container">
		</div>
	</div>

	<!-- apply cuopon end -->
	<?php

	if ($checkoutProType == "domestic") {
		$coupon_cart_total = WC()->cart->get_cart_contents_total();
	} else {
		$coupon_cart_total = WC()->cart->get_cart_contents_total();
	}

	// var_dump($coupon_cart_total);
	$coupon_ids = array(12068);
	$coupons = get_posts(
		array(
			'post_type'   => 'shop_coupon',
			'numberposts' => -1,
			'post__in'       => $coupon_ids,
		)
	); ?>
	<!-- slide coupon start -->
	<div class="coupon-detail-blk">
		<div class="mb-30">
			<a href="javascript:void(0);" class="coupon-close-btn"><i class="las la-times"></i></a>
		</div>
		<!-- <form id="voucherCodeForm">  -->
		<div class="position-relative mb-40">
				<div class="search-blk voucher-code">
					<input class="search-box w-100" type="text" id="apply_coupon_text" placeholder="Voucher Code" style="background-color: #f8f2f285;">
					<a href="javascript:void(0);" class="search-btn button apply_coupon_text">Apply</a>
				</div>
				<div class="err-msg">Please enter a valid coupon.</div>
			 
			</div>
		<!-- </form> -->
		<!-- <h6 class="md b-font mb-2">AVAILABLE COUPONS</h6> -->
		<div class="coupon-content mr-auto mb-5">
			<?php
			$coupon_array_range = [];
			foreach ($coupons as $coupon_post) {
				$coupon = new WC_Coupon($coupon_post->ID);
				$coupon_code     = $coupon->get_code();
				$coupon_minimum_spend = $coupon->get_minimum_amount();
				$coupon_maximum_spend = $coupon->get_maximum_amount();
				$coupon_array_range[$coupon_post->ID]['code'] = $coupon_code;
				$coupon_array_range[$coupon_post->ID]['min_value'] = $coupon_minimum_spend;
				$coupon_array_range[$coupon_post->ID]['max_value'] = $coupon_maximum_spend;
			}
			?>
			<?php
			$displayed_coupon = false;
			foreach ($coupons as $coupon_post) {
				$coupon = new WC_Coupon($coupon_post->ID);
				$coupon_code     = $coupon->get_code();
				$coupon_amount   = $coupon->get_amount();
				$coupon_description   = $coupon->get_description();
				$coupon_discount = $coupon->get_discount_type();
				$coupon_expiry   = $coupon->get_date_expires();
				$coupon_minimum_spend = $coupon->get_minimum_amount();
				$coupon_maximum_spend = $coupon->get_maximum_amount();
				// $is_customer_specific_coupon = get_post_meta($coupon_post->ID, 'customer_eligibility', true);
				$multiple_coupons_available = get_post_meta($coupon_post->ID, 'multiple_coupons', true);
			?> 
					<div class="coupon-row">

 						<div class="offer-label type1 mb-3">
							<span><?php echo ucfirst($coupon_code); ?></span>
						</div>
						<div> 
							<span class="md  mb-3"><?php echo $coupon_description; ?></span>
						</div>
						<a href="javascript:void(0)" class="button mb tb condition_coupon" data-coupon="<?php echo $coupon_code; ?>" data-percentage="<?php echo $coupon_amount; ?>" data-coupon_minimum="<?php echo $coupon_minimum_spend; ?>" data-coupon_maximum="<?php echo $coupon_maximum_spend; ?>">Apply Coupon</a>

					</div>
			 
			<?php } ?>
		 
		</div>
		 
	</div>
	<script>
// Convert PHP array to JavaScript array
var defaultCoupons = <?php echo json_encode($coupon_array_range); ?>;
console.log(defaultCoupons);
</script>
	<!-- slide coupon end -->

	<!-- popup massege start -->
	<div class="popup-box-blk" style="background-color: #242222e0;">
		<div class="popup-box text-center type1">
			<div class="coupon-tick"><img src="<?php echo get_bloginfo('template_url'); ?>/lib/images/discount1.svg" alt="coupon"></div>
			<div class="saving_amount px-4">
				<!-- <span class="md">5% OFF APPLIED</span> -->
				<!-- <span class="md">Here's a welcome-back gift for you.</span> -->
				<h3 class="mb-1" id="coupon_saving_amount">₹250</h3>
				<span class="md mb-4 ">with this coupon</span>
				<!-- <span class="md b-font">Unlock delightful offers with every order.</span> -->
			</div>
			<a href="javascript:void(0);" class="button tb mb popup-close yay mt-4">Yay! To more smiles, better deals!</a>

		</div>
	</div>

	<!-- popup massege end -->
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
	<!-- <div class="cart-table">
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
							<span class="md m-font mt-1 green"><?php //echo $coupon_amount; 
																?> Offer applied on the bill</span>
						</div>
						<a href="javascript:void(0);" class="link-anim md ccode-remove">Remove</a>
					</div>

				<?php } ?>

				<?php do_action('woocommerce_cart_actions'); ?>

				<?php wp_nonce_field('woocommerce-cart'); ?>

			</div>
		</form>
	</div> -->
	<?php if ($checkoutProType == "global" && is_checkout()) {
		$defCountry = WC()->customer->get_shipping_country();
		// echo $defCountry;
		if ($checkoutProType == "global") {
			if ($defCountry == "IN" || $defCountry == "") {
				WC()->customer->set_shipping_country("US");
				WC()->customer->set_billing_country("US");
				$myCountry = "US";
			} else {
				$myCountry = $defCountry;
			}
		}
	?>
		<script type="text/javascript">
			$(document).ready(function(e) {
				// console.log("Checkout");
				var countryName = sessionStorage.getItem("selectedCountry");
				// var countryName = "<?php echo $myCountry; ?>";
				// console.log("Checkout country:" + countryName);
				if (countryName != "") {
					$.ajax({
						type: "POST",
						url: blogUri + "/wp-admin/admin-ajax.php",
						data: {
							action: 'checkout_country_shipping',
							country_code: countryName,
						},
						success: function(data) {
							console.log(data);
							var succVal = data.split('|');
							var successRet = succVal[0];
							var shippingVal = succVal[1];
							$('.cart-table #checkout_total').html(successRet);
							$('#checkout_total1').html(successRet);
							$('.cart-table #chkout-shipping-tot').html(shippingVal);
							$('#chkout-shipping-tot-mob').html(shippingVal);
							$(document.body).trigger('update_order_review');
						}
					});
				}
				return false;
			});
		</script>
	<?php } ?>
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
							$reg =  (int)(get_post_meta($var_id, '_regular_price', true)) *  (int)$qtyy;
						} else {
							$reg =  (int)(get_post_meta($product_id, '_regular_price', true)) *  (int)($qtyy);
						}
						$total[] = $reg;
					}
					foreach ($total as $key => $totalval) {
						$sum += $totalval;
					}
					$subVal = WC()->cart->subtotal;
					// $discVal = $sum - $subVal;
					$discVal = (($sum * $perc_disc) / 100);
					$discVal = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $discVal);
					$sum = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $sum);
					?>
					<span class="md" id="checkout_total_price">&#x20B9;<?php echo $sum; ?>.00</span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="md">Shipping</span>
				</td>
				<td>
					<?php $cc = WC()->customer->get_shipping_country();
					if ($checkoutProType == "domestic") {
						$shippingCharge = "FREE!";
					} else {
						$shippingCharge = '₹' . get_option($cc . '_five') . '.00';
					}
					?>
					<span class="md" id="chkout-shipping-tot"><?php echo $shippingCharge; ?></span>
				</td>
			</tr>
			<tr>
				<td>
					<span class="md">Discount</span>
				</td>
				<td>
					<?php
					// Get applied coupons
					$applied_coupons = WC()->cart->get_applied_coupons();
					if (!empty($applied_coupons)) {
						$total_discount = 0;

						foreach ($applied_coupons as $applied_coupon) {
							$coupon_discount = WC()->cart->get_coupon_discount_amount($applied_coupon);
							$total_discount += $coupon_discount;
						}
						$rounded_total_discount = floor($total_discount); 
						$formatted_total_discount = wc_price($rounded_total_discount);
						echo '<span class="md discount_amount">' . $formatted_total_discount . '</span>';
					} else {
						echo '<span class="md discount_amount">' . wc_price(0) . '</span>';
					}
					?>
				</td>
			</tr>
			<tr>
				<td>
					<span class="b-font md">Total</span>
				</td>
				<td>
					<?php
					if ($checkoutProType == "domestic") {
						$cart_total = WC()->cart->get_cart_contents_total(); ?>
						<span class="b-font md" id="checkout_total"><?php echo wc_price($cart_total); ?></span>
					<?php	} else { ?>
						<span class="b-font md" id="checkout_total"><?php wc_cart_totals_order_total_html(); ?></span>
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>

	<!-- <h6 class="mb-3">Payment</h6> -->
</div>
<!--coupon script  -->
<script>
	$(document).ready(function() {
		var discountAmountText = $('.discount_amount').text();
		var discountAmount = parseFloat(discountAmountText.replace(/[^\d.-]/g, ''));
		console.log('Discount Amount:', discountAmount);
		if (discountAmount == 0) {
			setTimeout(function() {
				$(".coupon-success-blk").removeClass("active");
			}, 2000);
			localStorage.removeItem('couponApplied');
			localStorage.removeItem('appliedCoupon');
		}
		// Check if a coupon is already applied on page load
		var isCouponApplied = localStorage.getItem('couponApplied');

		if (isCouponApplied === 'true') {
			$(".coupon-success-blk").addClass("active");
			$("#applied_coupon").text(localStorage.getItem('appliedCoupon'));

		}
		$(document).on('click', '.condition_coupon', function(e) {
			var coupon = $(this).data('coupon');
			var totalAmount = $("#checkout_total_price").text();
			var checkoutcouponTotalAmount = parseFloat(totalAmount.replace(/[^0-9.-]+/g, ""));
			var couponPercentage = parseFloat($(this).data('percentage'));

			$.ajax({
				type: "POST",
				url: blogUri + "/wp-admin/admin-ajax.php",
				data: {
					action: 'my_special_action',
					couponcode: coupon,
				},
				success: function(data) {
					console.log(data);
					if (data != 1) {
						$(".coupon-detail-blk").removeClass("active");
						setTimeout(function() {
							$('.popup-box-blk').addClass('active');
						}, 300);
						var applied_coupon_name = coupon.replace("get", "");

						$("#applied_coupon").text(applied_coupon_name);
						localStorage.setItem('couponApplied', 'true');
						localStorage.setItem('appliedCoupon', applied_coupon_name);
						var savingsAmount = (couponPercentage / 100) * checkoutcouponTotalAmount;
						// var discountedAmount = checkoutcouponTotalAmount - savingsAmount; 
						$("#coupon_saving_amount").text("Save ₹" + Math.floor(savingsAmount));
						// $("#coupon_saving_amount").text("₹" + savingsAmount.toFixed(2)); 
						// Coupon applied successfully
						// alert('Coupon applied successfully!'); 

					} else {
						// alert('Coupon application failed!');
					}
				}
			});

		});
//form text coupon 
$('.apply_coupon_text').on('click', function (e) {
    var coupon_text = $("#apply_coupon_text").val();
    var totalAmount = $("#checkout_total").text(); 
     if (coupon_text == '' || coupon_text == undefined || isDefaultCoupon(coupon_text)) {
        $('.err-msg').addClass('active');
    } else {
        $('.err-msg').removeClass('active');
        $.ajax({
            type: "POST",
            url: blogUri + "/wp-admin/admin-ajax.php",
            data: {
                action: 'my_special_action',
                couponcode: coupon_text
            },
            success: function (data) {
                console.log(data);
                if (data != 1) {
                    $.ajax({
                        type: "POST",
                        url: blogUri + "/wp-admin/admin-ajax.php",
                        data: {
                            action: 'get_coupon_amount',
                            couponcode: coupon_text
                        },
                        success: function (couponData) {
                             // Access the coupon amount from couponData.coupon_amount
                            var couponAmount = parseFloat(couponData.coupon_amount);
							// alert(couponAmount);
                            $(".coupon-detail-blk").removeClass("active");
                            setTimeout(function () {
                                $('.popup-box-blk').addClass('active');
                            }, 300);
                            $("#apply-coupon-btn").hide();
                            $("#applied_coupon").text(coupon_text); 
                            localStorage.setItem('couponApplied', 'true');
                            localStorage.setItem('appliedCoupon', coupon_text);  
                            $("#coupon_saving_amount").text("Save ₹" + Math.floor(couponAmount));

                        }
                    }); 
                  
                } else {  
                    $('.voucher-code').after('<div class="err-msg active">Please enter a valid coupon.</div>');
                }
            }
        });
    }
});
function isDefaultCoupon(couponCode) {
    var isDefault = false;

    Object.keys(defaultCoupons).forEach(function (id) {
        if (defaultCoupons[id].code === couponCode) {
            isDefault = true;
        }
    });

    return isDefault;
}
		$('.code-remove').on('click', function() {
			setTimeout(function() {
				$(".coupon-success-blk").removeClass("active");
			}, 2000);
			localStorage.removeItem('couponApplied');
			localStorage.removeItem('appliedCoupon'); // Remove stored coupon code

		});

		$('popup-close', '.popup-box-blk').on('click', function() {
			$(".bg-layer").removeClass('active');
			$('.popup-box-blk').removeClass('active');
			location.reload();
			$(".coupon-success-blk").addClass("active");


		});

	});
</script>
<!-- coupon script end  -->
<script>
	// Domestic checkout qty incre decre
	$(document).ready(function() {
		$('.domes_check_dec').each(function() {
			var qty = parseInt($(this).attr('data-qty'));
			if (qty > 1) {
				$(this).addClass('active');
				// $(this).hide();
			}
		});
		$('.domes_check_inc').each(function() {
			var qty = parseInt($(this).attr('data-qty'));
			if (qty == 20) {
				$(this).removeClass('active');
			}
		});

		$('.glob_check_dec').each(function() {
			var qty = parseInt($(this).attr('data-qty'));
			var minqty = parseInt($(this).attr('data-min'));
			var maxqty = parseInt($(this).attr('data-max'));
			if (qty > minqty) {
				$(this).addClass('active');
			}
			if (minqty == maxqty) {
				$(this).removeClass('active');
			}
		});
		$('.glob_check_inc').each(function() {
			var qty = parseInt($(this).attr('data-qty'));
			var maxqty = parseInt($(this).attr('data-max'));
			var minqty = parseInt($(this).attr('data-min'));
			if (qty == maxqty) {
				$(this).removeClass('active');
			}
			if (minqty == maxqty) {
				$(this).removeClass('active');
			}
		});

	});
	$(document).on('click', '.domes_check_inc , .domes_check_dec', function() {
		var cartQtyMinus = $(this).closest('.count').find('.domes_check_dec');
		var cartQtyPlus = $(this).closest('.count').find('.domes_check_inc');
		var cart_item_key = $(this).attr('data-key');
		var qty = $(this).attr('data-qty');
		var variation_id = $(this).attr('data-variation-id');
		if ($(this).hasClass('domes_check_inc')) {
			if (qty < 20) {
				qty++;
				cartQtyMinus.addClass('active');
			}
			if (qty == 20) {
				$(this).removeClass('active');
			}

		} else if ($(this).hasClass('domes_check_dec')) {
			if (qty > 1) {
				qty--;
				cartQtyPlus.addClass("active");
			}
			if (qty == 1) {
				$(this).removeClass('active');

			}
		}
		$.ajax({
			type: "POST",
			url: blogUri + "/wp-admin/admin-ajax.php",
			data: {
				action: "update_item_from_minicart",
				cart_item_key: cart_item_key,
				qty: qty,
				variation_id: variation_id
			},
			success: function(response) {
				updateheaderCartCount();
				console.log(response);
				if (response.success) {
					location.reload();
					var updatedQty = response.data.quantity;
					var updatedPrice = response.data.price;
					var updatedTotal = response.data.total;
					var productID = response.data.product_id;
					var variationID = response.data.variation_id;
					$('#domes_check_qty' + variationID).val(updatedQty);
					$('#checkout_price' + variationID).html(updatedPrice);

					$('#checkout_total').html(updatedTotal);
					$('#checkout_total_price').html(updatedTotal);
					$('.domes_check_inc[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);
					$('.domes_check_dec[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);
				}
			}
		});
	});

	$(document).on('click', '.glob_check_inc , .glob_check_dec', function() {
		var cartQtyMinus = $(this).closest('.count').find('.glob_check_dec');
		var cartQtyPlus = $(this).closest('.count').find('.glob_check_inc');
		var cart_item_key = $(this).attr('data-key');
		var qty = parseInt($(this).attr('data-qty'));
		var variation_id = $(this).attr('data-variation-id');
		var minqty = parseInt($(this).attr('data-min'));
		var maxqty = parseInt($(this).attr('data-max'));
		if ($(this).hasClass('glob_check_inc')) {
			if (qty < maxqty) {
				qty++;
				cartQtyMinus.addClass('active');
			}
			if (qty == maxqty) {
				$(this).removeClass("active");
			}
		} else if ($(this).hasClass('glob_check_dec')) {
			if (qty > minqty) {
				qty--;
				cartQtyPlus.addClass("active");
			}
			if (qty == minqty) {
				$(this).removeClass('active');
			}
		}
		$.ajax({
			type: "POST",
			url: blogUri + "/wp-admin/admin-ajax.php",
			data: {
				action: "update_item_from_cart",
				cart_item_key: cart_item_key,
				qty: qty,
				variation_id: variation_id
			},
			success: function(response) {
				updateheaderCartCount();
				if (response.success) {
					location.reload();
					var updatedQty = response.data.quantity;
					var updatedPrice = response.data.price;
					var updatedTotal = response.data.total;
					var productID = response.data.product_id;
					var variationID = response.data.variation_id;
					var updated_total_price = response.data.updated_price;

					$('#glob_check_qty' + variationID).val(updatedQty);
					$('#checkout_price' + variationID).html(updatedPrice);
					$('#checkout_total').html(updatedTotal);
					$('#checkout_total_price').html(updated_total_price);
					$('.glob_check_inc[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);
					$('.glob_check_dec[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);

				}
			}
		});
	});


	$(document).on('click', '.checkout_product_remove', function() {
		var productId = $(this).data('product-id');
		var variationId = $(this).data('variation-id');

		$.ajax({
			url: blogUri + "/wp-admin/admin-ajax.php",
			type: 'POST',
			data: {
				action: 'remove_from_cart',
				product_id: variationId,
			},
			success: function(response) {
				updateheaderCartCount();
				if (response.success) {
					var cart_total_with_shipping = response.data.cart_total_with_shipping;
					var cartTotal = response.data.cart_total;
					console.log(cartTotal);
					// Check if the cart total is zero
					if (cartTotal === "<span class=\"woocommerce-Price-amount amount\"><bdi><span class=\"woocommerce-Price-currencySymbol\">&#8377;</span>0.00</bdi></span>") {
						$.ajax({
							url: blogUri + "/wp-admin/admin-ajax.php",
							type: 'POST',
							data: {
								action: 'empty_cart',
							},
							success: function(response) {
								if (response.success) {
									// Cart is emptied, reload the page
									location.reload();
								}
							}
						});


					}
					window.location.reload();
				}

			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus + ': ' + errorThrown);
			}
		});
	});
</script>