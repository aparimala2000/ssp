<?php

/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined('ABSPATH') || exit; ?>
<?php
session_start();
$product_type = WC()->session->get('checkout_type');
// echo $product_type;
if ($product_type == 'global') { ?>
	<div class="cart-blk mx-auto">
		<?php if (count(WC()->cart->get_cart()) > 0) { ?>
			<!-- <div class="cart-blk-con"> -->
			<div>
				<?php if (count(WC()->cart->get_cart()) == 1) { ?>
					<h5 class="v1 mr-3 mb-30">Cart</h5> <span class="mini_cart_count">(<?php print_r(count(WC()->cart->get_cart())); ?> Item)</span>
				<?php } else { ?>
					<h5 class="v1 mr-3 mb-30">Cart</h5> <span class="mini_cart_count">(<?php print_r(count(WC()->cart->get_cart())); ?> Items)</span>
				<?php } ?>
				<div>
					<?php
					foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
						// get the data of the cart item
						$product_id = $cart_item['product_id'];
						$variation_id = $cart_item['variation_id'];
						// gets the cart item quantity
						$quantity = $cart_item['quantity'];
						$variation_product = new WC_Product_Variation($variation_id);
						$var_weight = $variation_product->weight;
						// gets the cart item subtotal
						$line_subtotal = $cart_item['line_subtotal'];
						$line_subtotal_tax = $cart_item['line_subtotal_tax'];

						// unit price of the product
						$item_price = $line_subtotal / $quantity;
						$item_tax = $line_subtotal_tax / $quantity;

						// gets the product object
						$product = $cart_item['data'];
						// get the data of the product
						$pr_name = $product->get_name();
						if (strpos($pr_name, ' - ') !== false) {
							$pr_name = substr($pr_name, 0, strpos($pr_name, ' - '));
						}

						$regular_price = $product->get_regular_price();
						$sale_price = $product->get_sale_price();
						$price = $product->get_price();
						$stock_qty = $product->get_stock_quantity();
						// attributes
						$attributes = $product->get_attributes();
						$attribute = $product->get_attribute('pa_attribute-name'); // // specific attribute eg. "pa_color"
						// custom meta
						$custom_meta = $product->get_meta('_custom_meta_key', true);
						// product categories
						$categories = wc_get_product_category_list($product->get_id()); // returns a string with all product categories separated by a comma
						if ($cart_item['data']->get_shipping_class() === 'enable') {
							$variation = $cart_item['data'];
							$minqty = get_post_meta($variation->get_variation_id(), '_min_qty_', true);
							$maxqty = get_post_meta($variation->get_variation_id(), '_max_qty_', true);
						}
					?>
						<div class="mb-20 cartremove cart-row">
							<div class="d-flex justify-content-between align-items-center">
								<span class="md"><?php echo $pr_name; ?></span>
								<a href="javascript:void(0);" class="circle-icon mini_cart_product_remove type2" data-product-id="<?php echo $product_id; ?>"><i class="las la-times"></i></a>
							</div>
							<span class="sm mb-2 d-inline-block">(<?php echo $var_weight; ?> gms)</span>
							<div class="d-flex justify-content-between align-items-center">
								<div class="count">
									<span class="box cart-global-qty-minus" data-key="<?php echo $cart_item_key; ?>" data-qty="<?php echo $quantity; ?>" data-variation-id="<?php echo $variation_id; ?>" data-min="<?php echo $minqty; ?>" data-max="<?php echo $maxqty; ?>">-</span>
									<input style="display:none" onchange="quantityonchange(<?php echo $product_id; ?>, this)" id="qty" prod-id="<?php echo $product_id; ?>" type="text" class="textbox-small" name="cart[<?php echo $cart_item_key; ?>][qty]" value="<?php echo $quantity; ?>" />
									<input id="global_qty<?php echo $variation_id; ?>" name="qty" type="text" readonly class="box qty" value="<?php echo $quantity; ?>" />
									<span class="box cart-global-qty-plus active" data-key="<?php echo $cart_item_key; ?>" data-qty="<?php echo $quantity; ?>" data-variation-id="<?php echo $variation_id; ?>" data-min="<?php echo $minqty; ?>" data-max="<?php echo $maxqty; ?>">+</span>
								</div>
								<span class="md" id="cart_price<?php echo $variation_id; ?>"><?php echo wc_price($line_subtotal); ?></span>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="mb-30"></div>
			<form>
				<div class="floating-blk">
					<label for="country_id" class="floating-row">
						<span class="arrow-btn ld"><i class="las la-angle-down"></i></span>
						<?php
						global $woocommerce;
						$countries = new WC_Countries();
						$cc = WC()->customer->get_shipping_country();
						$allCountries = $countries->get_shipping_countries(); ?>
						<select id="country_id" class="floating-input ld country_select" name="floating-label">
							<option value="" hidden></option>
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

							?>
						</select>
					</label>
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
							<span class="b-font ld"><?php //wc_cart_totals_coupon_html($coupon); 
													?><a href="javascript:void(0);" class="code-remove">Remove</a></span>
						</div>
				<?php endforeach;
				} ?>
			</form>
			<div class="mb-30"></div>
			<div class="d-flex justify-content-between align-items-center mb-30">
				<span class="ld">GST within Karnataka SGST 2.5% CGST 2.5% and GST outside Karnataka 5%.</span>
			</div>
			<?php
			$cc = WC()->customer->get_shipping_country();
			if ($cc != "") {
				$shippingCharge = '₹' . get_option($cc . '_five') . '.00';
			} else {
				$shippingCharge = '₹' . '0' . '.00';
			}
			?>
			<div class="d-flex justify-content-between align-items-center mb-30">
				<span class="ld">Shipping:</span>
				<span class="ld"><?php echo $shippingCharge; ?></span>
			</div>
			<div class="d-flex justify-content-between align-items-center">
				<span class="md b-font">Total</span>
				<span class="md b-font" id="cart_total"><?php wc_cart_totals_order_total_html(); ?></span>
			</div>
			<div class="mb-50"></div>
			<div class="d-flex justify-content-between align-items-center">
				<a href="<?php echo get_bloginfo('url'); ?>/global" class="link-anim ld">Continue Shopping</a>
				<a href="javascript:void(0);" class="button mb">Checkout</a>
			</div>
			<!-- </div> -->
		<?php } else { ?>
			<div class="empty-cart active pt-5">
				<div>
					<div class="mb-30 mx-auto" style="max-width:200px; width: 100%;">
						<img src="<?php echo get_bloginfo('template_url'); ?>/lib/images/cart1.png;">
					</div>
					<div class="text-center">
						<h6 class=" mb-2">Your cart is currently empty</h6>
						<span class="ld d-block mb-3">Before proceed to checkout, you must add some products to your cart.</span>
					</div>
				</div>
			</div>

		<?php } ?>
	</div>

<?php } else { ?>
	<div class="cart-blk mx-auto">
		<?php if (count(WC()->cart->get_cart()) > 0) { ?>
			<!-- <div class="cart-blk-con"> -->
			<div>
				<?php if (count(WC()->cart->get_cart()) == 1) { ?>
					<h5 class="v1 mr-3 mb-30">Cart</h5> <span class="mini_cart_count">(<?php print_r(count(WC()->cart->get_cart())); ?> Item)</span>
				<?php } else { ?>
					<h5 class="v1 mr-3 mb-30">Cart</h5> <span class="mini_cart_count">(<?php print_r(count(WC()->cart->get_cart())); ?> Items)</span>
				<?php } ?>
				<div>
					<?php
					foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
						// get the data of the cart item
						$product_id = $cart_item['product_id'];
						$variation_id = $cart_item['variation_id'];
						// gets the cart item quantity
						$quantity = $cart_item['quantity'];
						$variation_product = new WC_Product_Variation($variation_id);
						$var_weight = $variation_product->weight;
						// gets the cart item subtotal
						$line_subtotal = $cart_item['line_subtotal'];
						$line_subtotal_tax = $cart_item['line_subtotal_tax'];

						// unit price of the product
						$item_price = $line_subtotal / $quantity;
						$item_tax = $line_subtotal_tax / $quantity;

						// gets the product object
						$product = $cart_item['data'];
						// get the data of the product
						$pr_name = $product->get_name();
						if (strpos($pr_name, ' - ') !== false) {
							$pr_name = substr($pr_name, 0, strpos($pr_name, ' - '));
						}

						$regular_price = $product->get_regular_price();
						$sale_price = $product->get_sale_price();
						$price = $product->get_price();
						$stock_qty = $product->get_stock_quantity();
						// attributes
						$attributes = $product->get_attributes();
						$attribute = $product->get_attribute('pa_attribute-name'); // // specific attribute eg. "pa_color"
						// custom meta
						$custom_meta = $product->get_meta('_custom_meta_key', true);
						// product categories
						$categories = wc_get_product_category_list($product->get_id()); // returns a string with all product categories separated by a comma
					?>
						<div class="mb-20 cartremove cart-row">
							<div class="d-flex justify-content-between align-items-center">
								<span class="md"><?php echo $pr_name; ?></span>
								<a href="javascript:void(0);" class="circle-icon mini_cart_product_remove type2" data-product-id="<?php echo $product_id; ?>"><i class="las la-times"></i></a>
							</div>

							<span class="sm mb-2 d-inline-block">(<?php echo $var_weight; ?> gms)</span>
							<div class="d-flex justify-content-between align-items-center">
								<div class="count">
									<span class="box cart-qty-minus" data-key="<?php echo $cart_item_key; ?>" data-qty="<?php echo $quantity; ?>" data-variation-id="<?php echo $variation_id; ?>">-</span>
									<input style="display:none" onchange="quantityonchange(<?php echo $product_id; ?>, this)" id="qty" prod-id="<?php echo $product_id; ?>" type="text" class="textbox-small" name="cart[<?php echo $cart_item_key; ?>][qty]" value="<?php echo $quantity; ?>" />
									<input id="dummyQty<?php echo $variation_id; ?>" name="qty" type="text" readonly class="box qty" value="<?php echo $quantity; ?>" />
									<span class="box cart-qty-plus active" data-key="<?php echo $cart_item_key; ?>" data-qty="<?php echo $quantity; ?>" data-variation-id="<?php echo $variation_id; ?>">+</span>
								</div>
								<span class="md" id="cart_price<?php echo $variation_id; ?>"><?php echo wc_price($line_subtotal); ?></span>

							</div>

						</div>
					<?php } ?>
				</div>
			</div>
			<div class="mb-30"></div>
			<form>
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
							<span class="b-font ld"><?php //wc_cart_totals_coupon_html($coupon); 
													?><a href="javascript:void(0);" class="code-remove">Remove</a></span>
						</div>
				<?php endforeach;
				} ?>
			</form>
			<div class="mb-30"></div>
			<div class="d-flex justify-content-between align-items-center mb-30">
				<span class="ld">GST within Karnataka SGST 2.5% CGST 2.5% and GST outside Karnataka 5%.</span>
			</div>
			<?php
			$cc = "IN";
			$shippingCharge = "Free";  ?>
			<div class="d-flex justify-content-between align-items-center mb-30">
				<span class="ld">Shipping:</span>
				<span class="ld"><?php echo $shippingCharge; ?></span>
			</div>
			<?php
			// $cart_total = WC()->cart->get_subtotal();
			$cart_total = WC()->cart->get_cart_contents_total(); ?>
			<div class="d-flex justify-content-between align-items-center">
				<span class="md b-font">Total</span>
				<span class="md b-font" id="cart_total"><?php echo wc_price($cart_total); ?></span>
			</div>
			<div class="mb-50"></div>
			<div class="d-flex justify-content-between align-items-center">
				<a href="<?php echo get_bloginfo('url'); ?>/home-use" class="link-anim ld">Continue Shopping</a>
				<a href="javascript:void(0);" class="button mb">Checkout</a>
			</div>
			<!-- </div> -->
		<?php } else { ?>
			<div class="empty-cart active pt-5">
				<div>
					<div class="mb-30 mx-auto" style="max-width:200px; width: 100%;">
						<img src="<?php echo get_bloginfo('template_url'); ?>/lib/images/cart1.png;">
					</div>
					<div class="text-center">
						<h6 class=" mb-2">Your cart is currently empty</h6>
						<span class="ld d-block mb-3">Before proceed to checkout, you must add some products to your cart.</span>
					</div>
				</div>
			</div>

		<?php } ?>
	</div>

<?php } ?>
<script>
	// Domestic cart qty incre decre
	$(document).ready(function() {
		$('.cart-qty-minus').each(function() {
			var qty = parseInt($(this).attr('data-qty'));
			if (qty > 1) {
				$(this).addClass('active');
			}
		});
		$('.cart-qty-plus').each(function() {
			var qty = parseInt($(this).attr('data-qty'));
			if (qty == 20) {
				$(this).removeClass('active');
			}
		});
	});
	$('.cart-qty-plus, .cart-qty-minus').on('click', function() {
		var cartQtyMinus = $(this).closest('.count').find('.cart-qty-minus');
		var cartQtyPlus = $(this).closest('.count').find('.cart-qty-plus');
		var cart_item_key = $(this).attr('data-key');
		var qty = $(this).attr('data-qty');
		var variation_id = $(this).attr('data-variation-id');
		if ($(this).hasClass('cart-qty-plus')) {
			if (qty < 20) {
				qty++;
				cartQtyMinus.addClass('active');
			}
			if (qty == 20) {
				$(this).removeClass('active');
			}

		} else if ($(this).hasClass('cart-qty-minus')) {
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
				if (response.success) {
					var updatedQty = response.data.quantity;
					var updatedPrice = response.data.price;
					var updatedTotal = response.data.total;
					var productID = response.data.product_id;
					var variationID = response.data.variation_id;
					// console.log(variationID);
					// console.log(updatedQty);
					// console.log(updatedPrice);

					$('#dummyQty' + variationID).val(updatedQty);
					$('#cart_price' + variationID).html(updatedPrice);

					$('#cart_total').html(updatedTotal);
					$('.cart-qty-plus[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);
					$('.cart-qty-minus[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);
				}
			}
		});
	});

	//Global cart qty incre decre
	$(document).ready(function() {

		$('.cart-global-qty-minus').each(function() {
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
		$('.cart-global-qty-plus').each(function() {
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
	$('.cart-global-qty-plus, .cart-global-qty-minus').on('click', function() {
		var cartQtyMinus = $(this).closest('.count').find('.cart-global-qty-minus');
		var cartQtyPlus = $(this).closest('.count').find('.cart-global-qty-plus');
		var cart_item_key = $(this).attr('data-key');
		var qty = parseInt($(this).attr('data-qty'));
		var variation_id = $(this).attr('data-variation-id');
		var minqty = parseInt($(this).attr('data-min'));
		var maxqty = parseInt($(this).attr('data-max'));
		// console.log(qty);
		// console.log(typeof minqty);
		// console.log(typeof maxqty);
		if ($(this).hasClass('cart-global-qty-plus')) {
			if (qty < maxqty) {
				qty++;
				cartQtyMinus.addClass('active');
			}
			if (qty == maxqty) {
				$(this).removeClass("active");
			}
		} else if ($(this).hasClass('cart-global-qty-minus')) {
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
				if (response.success) {
					var updatedQty = response.data.quantity;
					var updatedPrice = response.data.price;
					var updatedTotal = response.data.total;
					var productID = response.data.product_id;
					var variationID = response.data.variation_id;
					// console.log(variationID);
					// console.log(updatedQty);
					// console.log(updatedPrice);

					$('#global_qty' + variationID).val(updatedQty);
					$('#cart_price' + variationID).html(updatedPrice);

					$('#cart_total').html(updatedTotal);
					$('.cart-global-qty-plus[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);
					$('.cart-global-qty-minus[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);

				}
			}
		});
	});

	// Remove product from mini cart
	$('.mini_cart_product_remove').on('click', function(e) {
		e.preventDefault();
		var productId = $(this).data('product-id');
		var cartremove = $(this).closest('.cartremove');
		$.ajax({
			url: blogUri + "/wp-admin/admin-ajax.php",
			type: 'POST',
			data: {
				action: 'remove_from_cart',
				product_id: productId,
			},
			success: function(response) {
				if (response.success) {
					// Remove the cart item row
					cartremove.remove();

					var cartTotal = response.data.cart_total;
					// Check if the cart total is zero
					if (cartTotal === "<span class=\"woocommerce-Price-amount amount\"><bdi><span class=\"woocommerce-Price-currencySymbol\">&#8377;</span>0.00</bdi></span>") {
						// Reload the page if the cart total matches
						location.reload();

					} else {
						var addTocartBtn = $('.quantity-blk .add_to_cart_btn');
						addTocartBtn.css({
							'pointer-events': 'auto',
							'opacity': '1'
						});
						var itemCount = response.data.cart_count;
						updateCartCount(itemCount);
						console.log(itemCount);
						// Reload the page if the cart total is zero
						$('#cart_total').html(cartTotal);

					}
				} else {
					console.log(response.data);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus + ': ' + errorThrown);
			}
		});
	});

	function updateCartCount(count) {
		var cartCountElement = $('.mini_cart_count');
		if (count === 1) {
			cartCountElement.text('(' + count + ' Item)');
		} else {
			cartCountElement.text('(' + count + ' Items)');
		}
	}
	$(document).ready(function() {
		var defaultCountryCode = $('#country_id').find('option:selected').attr('data-code');
		updateShippingCharge(defaultCountryCode);
	});

	function updateShippingCharge(country_code) {
		if (country_code !== "IN") {
			var shipping_cost = $('#country_id').find('option:selected').attr('data-val');
			$('#shipping_charge').text('₹' + shipping_cost + '.00');
		} else {
			$('#shipping_charge').text('Free');
		}

	}
	$('.country_select').on('change', function(e) {
		e.preventDefault();
		var country_code = $(this).find('option:selected').attr('data-code');
		var shipping_cost = $(this).find('option:selected').attr('data-val');
		e.preventDefault();
		if (country_code != "" || country_code != undefined) {
			$.ajax({
				type: "POST",
				url: blogUri + "/wp-admin/admin-ajax.php",
				data: {
					action: 'country_shipping',
					country_code: country_code,
					shipping_cost: shipping_cost,
				},
				success: function(data) {
					// console.log(data);
					location.reload();
				}
			});
		}
	});
	$('.apply_coupon').on('click', function(e) {
		var coupon = $("#coupon_cd").val();
		if (coupon == '' || coupon == undefined) {
			$('.err-msg').addClass('active');
		} else {
			$('.err-msg').removeClass('active');
			$.ajax({
				type: "POST",
				url: blogUri + "/wp-admin/admin-ajax.php",
				data: {
					action: 'my_special_action',
					couponcode: coupon
				},
				success: function(data) {
					console.log(data);
					if (data != 1) {
						location.reload();
						// window.location.href = blogUri + "/cart/";
						// $('html, body').animate({
						//     'scrollTop': $("#cart-temp").position().top
						// });
					} else {
						$('.coupon-succ').css('display', 'none');
						$('.voucher-code').css('display', 'block');
						$('.voucher-code').after('<div class="err-msg active">Please enter a valid coupon.</div>');
					}
				}
			});
		}
	});
	$(document).on('click', '.code-remove', function(e) {
		// Get the coupon code
		$.ajax({
			type: "POST",
			url: blogUri + "/wp-admin/admin-ajax.php",
			data: {
				action: 'ajax_remove_coupon',
				coupon_code: "remove"
			},
			success: function(data) {
				console.log(data);
				location.reload();
			}
		});
	});
</script>