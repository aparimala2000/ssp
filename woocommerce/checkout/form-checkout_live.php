<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
	exit;
}
do_action('woocommerce_before_checkout_form', $checkout);
// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}
session_start();
$cartSession = WC()->session->get('checkout_type');
if (isset($cartSession)) {
	$checkoutType = $cartSession;
} elseif (!isset($cartSession)) {
	global $woocommerce;
	$woocommerce->cart->empty_cart();
}
if ($checkoutType == "domestic") {
	$previous_url =	$_SERVER['HTTP_REFERER'];
	if (strpos($previous_url, 'home-use') !== false) {
		$prevUrl = '/home-use';
		$breadCrumbname = "Home Use";
	} else {
		$prevUrl = '/industrial-use';
		$breadCrumbname = "Industrial Use";
	}
	$countryOpt = "IN";
} else {
	$prevUrl = "/global";
	$breadCrumbname = "Global";
}
?>

<form name="checkout" method="post" id="checkout-form" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

	<?php if ($checkout->get_checkout_fields()) : ?>
		<?php do_action('woocommerce_checkout_before_customer_details'); ?>
		<div class="row mb-60">
			<div class="col-xl-8">
				<div>
					<div class="mb-20">
						<a href="<?php echo get_bloginfo('url') . $prevUrl; ?>"><?php echo $breadCrumbname; ?></a> /
					</div>
					<div class="mb-40">
						<h3>Checkout</h3>
						<p>Here’s a list of all the items and quantities that you’ve added to your cart for review. If these are in order, please proceed to checkout.</p>
					</div>
					<?php wc_get_template('checkout/order_detail_mob_checkout.php'); ?>
					<div class="accord-new">
						<?php
						if (is_user_logged_in()) {
							$first_block = "finish";
							$second_block = "active";
							$second_block_style = "block";
							$first_action_class = "";
						} else {
							$first_block = "";
							$second_block = "";
							$first_action_class = "active";
							$second_block_style = "none";
						}
						?>
						<div class="accord-blk <?php echo $first_block . " " . $first_action_class; ?>">
							<div class="accord-btn">
								<?php
								if (is_user_logged_in()) {
									global $current_user;
									$user_id = get_current_user_id();
									$userphone = get_user_meta($user_id, 'phone', true);
									$useremail = $current_user->user_email;
									if ($checkoutType == "domestic" && $userphone != "") { ?>
										<h5 class=" mb-0 v1">Mobile</h5>
										<div class="after" style="display: block;">
											<p id="user_email"><?php echo $userphone; ?> </p>
										</div>
									<?php } elseif ($checkoutType == "domestic" && $userphone == "") { ?>
										<h5 class=" mb-0 v1">Email</h5>
										<div class="after" style="display: block;">
											<p id="user_email"><?php echo $useremail; ?> </p>
										</div>
									<?php } elseif ($checkoutType == "global" && $useremail != "") { ?>
										<h5 class=" mb-0 v1">Email</h5>
										<div class="after" style="display: block;">
											<p id="user_email"><?php echo $useremail; ?> </p>
										</div>
									<?php } elseif ($checkoutType == "global" && $useremail == "") { ?>
										<h5 class=" mb-0 v1">Mobile</h5>
										<div class="after" style="display: block;">
											<p id="user_email"><?php echo $userphone; ?> </p>
										</div>
									<?php } ?>
								<?php } ?>
							</div>
							<?php if (!is_user_logged_in()) { ?>
								<div class="accord-content active">
									<input type="hidden" id="scrVal" value="Guest">
									<input type="hidden" id="guest_customer_id">
									<input type="hidden" id="user_customer_id">
									<form name="guest_checkout" class="guest_checkout" method="post">
										<div class="floating-blk guest_checkout">
											<label for="user_email" class="floating-row">
												<?php if ($checkoutType == "domestic") { ?>
													<span class="floating-label">Phone</span>
													<input class="floating-input adrs-book-input username" type="text" id="user_email" maxlength="15" onKeyPress="return isNumber(event);">
													<p class="floating-input-error log_email">Seems like you haven’t entered a phone number.</p>
												<?php } else { ?>
													<span class="floating-label">Email</span>
													<input class="floating-input adrs-book-input username" type="text" id="user_email">
													<p class="floating-input-error log_email">Seems like you’ve forgotten to enter your email.</p>
												<?php } ?>

											</label>
										</div>
										<a href="javascript:void(0);" class="button guest_checkout mb tb proceed_checkout">Login with OTP</a>
									</form>
									<form name="guest_checkout_otp" class="guest_checkout_otp" method="post" style="display:none;">
										<div class="floating-blk">
											<label for="guest_user" class="floating-row">
												<span class="floating-label">Enter the OTP</span>
												<input class="floating-input" type="text" id="guest_user_otp" maxlength="6">
												<p class="floating-input-error guest_otp">Here’s where you enter the OTP sent to your registered mobile number / email.</p>
											</label>
										</div>
										<a href="javascript:void(0);" class="button mb tb guest_otp_verify">Submit</a>
										<span class="md">If you haven't received OTP yet, please <a href="javascript:void(0)" class="link-anim resendOtpguestLogin">click here.</a></span>
									</form>
									<form name="user_checkout_otp" class="user_checkout_otp" method="post" style="display:none;">
										<div class="floating-blk">
											<label for="chkout_user" class="floating-row">
												<span class="floating-label">Enter the OTP</span>
												<input class="floating-input" type="text" id="chkout_user_otp" maxlength="6">
												<p class="floating-input-error chkout_otp">Here’s where you enter the OTP sent to your registered mobile number / email.</p>
											</label>
										</div>
										<a href="javascript:void(0);" class="button mb tb chkout_otp_verify">Submit</a>
										<span class="md">If you haven't received OTP yet, please <a href="javascript:void(0)" class="link-anim resendOtpuserLogin">click here.</a></span>
									</form>
								</div>
							<?php } ?>
						</div>
						<div class="accord-blk second-blk <?php echo $second_block; ?>">
							<div class="accord-btn">
								<div class="d-flex justify-content-between align-items-center">
									<h5 class="mb-0 v1">Contact Details</h5>
									<div class="after">
										<a href="javascript:void(0);" class="button sm-btn v1 mb change-btn">Change</a>
									</div>
								</div>
								<div class="after">
									<?php
									$user_id = get_current_user_id();
									$saddresses = get_user_meta($user_id, 'user_addresses', true);
									if (!empty($saddresses)) :
										foreach ($saddresses as $key => $value) {
											$address_1 = $value["address_1"];
											$Fname = $value["first_name"];
											$Lname = $value["last_name"];
											$email = $value["email"];
											$phone = $value["phone"];
											$state = $value["state"];
											$postcode = $value["postcode"];
											$country = $value["country"];
											$address_group = $value["address_group"];
									?>
											<div class="show_div" data-val="<?php echo $key; ?>">
												<h6><?php echo ucfirst($address_group); ?></h6>
												<span class="ld"><?php echo $Fname . ' ' . $Lname . ','; ?></span>
												<span class="ld"><?php echo $email . ','; ?></span>
												<span class="ld"><?php echo $phone . ','; ?></span>
												<span class="ld"><?php echo $address_1 . ' ' . $city . ','; ?></span>
												<span class="ld"><?php echo $state . '-' . $postcode . ','; ?></span>
												<span class="ld"><?php echo $country . '.'; ?></span>
											</div>
									<?php }
									endif; ?>
								</div>
							</div>
							<div class="accord-content" style="<?php echo "display:" . $second_block_style; ?>">
								<div class="user-info">
									<?php
									$user_id = get_current_user_id();
									$firstname = get_user_meta($user_id, 'first_name', true);
									$user_mobile = get_user_meta($user_id, 'phone', true);
									$user_email = $current_user->user_email;
									if ($firstname == "") {
										$fclass =  "det-empty";
									}
									if ($user_mobile == "") {
										$mclass =  "det-empty";
									}
									if ($user_email == "") {
										$eclass =  "det-empty";
									}
									if ($firstname == "") {
									?>
										<div class="floating-blk <?php echo $fclass; ?>">
											<label for="first_name" class="floating-row">
												<span class="floating-label">Name</span>
												<input class="floating-input" type="text" id="first_name" />
											</label>
										</div>
									<?php }
									if ($user_mobile == "") {
									?>
										<div class="floating-blk <?php echo $mclass; ?>">
											<label for="phone" class="floating-row">
												<span class="floating-label">Mobile</span>
												<input class="floating-input" type="text" id="phone" maxlength="15" onKeyPress="return isNumber(event);" />
											</label>
										</div>
									<?php } else if ($user_email == "") { ?>
										<div class="floating-blk <?php echo $eclass; ?>">
											<label for="email" class="floating-row">
												<span class="floating-label">Email</span>
												<input class="floating-input" type="text" id="email" />
											</label>
										</div>
									<?php }  ?>
									<input type="hidden" id="updateuser_id" value="<?php echo $user_id; ?>" />
								</div>
								<div class="pb-2"></div>
								<?php
								$user_id = get_current_user_id();
								$addresses = get_user_meta($user_id, 'user_addresses', true);
								if (!empty($addresses)) : ?>
									<h6>Choose Your Address</h6>
								<?php endif; ?>
								<!-- <div class="mb-20"></div> -->
								<div class="row address-section mt-n3">
									<?php
									if (is_array($addresses) && !empty($addresses)) {
										foreach ($addresses as $key => $value) {
											$adclass = "";
											$adStyle = "";
											$Fname = $value["first_name"];
											$Lname = $value["last_name"];
											$email = $value["email"];
											$phone = $value["phone"];
											$address_1 = $value["address_1"];
											$city = $value["city"];
											$state = $value["state"];
											$postcode = $value["postcode"];
											$country = $value["country"];
											$address_group = $value["address_group"];
											$country_code = countryToCountryCode($country);
											if ($checkoutType == "global" && $country_code == "IN") {
												$adclass = "pointer-disab";
												$adStyle = "style='display:none;'";
											} else if ($checkoutType == "domestic" && $country_code != "IN") {
												$adclass = "pointer-disab";
												$adStyle = "style='display:none;'";
											}
									?>
											<div class="col-md-6 address_switch" <?php echo $adStyle; ?>>
												<div class="with-card">

													<div class="normal-cards">
														<div>
															<div class="d-flex justify-content-between">
																<h6><?php echo ucfirst($address_group); ?></h6>
																<div>
																	<a href="javascript:void(0)" title="Edit" addrs-val="<?php echo $value['first_name'] . "~" . $value['last_name'] . "~" . $value['email'] . "~" . $value['phone'] . "~" . $value['address_1'] . "~" . $value['city'] . "~" . $value['state'] . "~" . $value['postcode'] . "~" . $value['country'] . "~" . $key . "~" . ucfirst($value['address_group']); ?>" class="checkout_edit_address circle-icon type2 mr-1" style="width: 24px; height: 24px; font-size: 14px;"><i class="las la-pencil"></i></a>
																</div>
															</div>
															<div class="ash-color">
																<span class="ld"><?php echo $Fname . ' ' . $Lname . ','; ?></span>
																<span class="ld"><?php echo $email . ','; ?></span>
																<span class="ld"><?php echo $phone . ','; ?></span>
																<span class="ld"><?php echo $address_1 . ' ' . $city . ','; ?></span>
																<span class="ld"><?php echo $state . '-' . $postcode . ','; ?></span>
																<span class="ld"><?php echo $country . '.'; ?></span>
																<a href="javascript:void(0);" class="<?php echo $adclass; ?> button sm-btn mt-3 nxt-btn address_choose v1" data-id="<?php echo $key; ?>" addrs-val="<?php echo $value['first_name'] . "~" . $value['last_name'] . "~" . $value['email'] . "~" . $value['phone'] . "~" . $value['address_1'] . "~" . $value['city'] . "~" . $value['state'] . "~" . $value['postcode'] . "~" . $country_code . "~" . $key; ?>" checkout-addrs-val="<?php echo $value['first_name'] . "~" . $value['last_name'] . "~" . $user_email . "~" . $user_mobile . "~" . $value['address_1'] . "~" . $value['city'] . "~" . $value['state'] . "~" . $value['postcode'] . "~" . $country_code . "~" . $key; ?>">Deliver Here</a>
															</div>
														</div>
													</div>
												</div>
											</div>
									<?php
											$count++;
										}
									}
									?>
									<div class="col-md-6">
										<div class="new_add_btn with-card h-100">
											<div class="normal-cards d-flex primary">
												<span class="mr-2"><i class="fa fa-plus" aria-hidden="true"></i></span>
												<h6 class="mb-0" id="add_Addr_check">Add Address</h6>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="accord-blk third-blk">
							<div class="accord-btn">
								<h5 class="mr-2 mb-2 v1">Payment</h5>
							</div>
							<div class="accord-content pt-0">
								<?php wc_get_template('checkout/payment.php'); ?>
							</div>
						</div>
					</div>
					<form class="checkout_address_form">
						<div class="bg-layer"></div>
						<div class="adrs-form">
							<div class="text-right">
								<a href="javascript:void(0);" class="add-adrs-close mb-4"><i class="las la-times"></i></a>
							</div>
							<div class="adrs-form-content ml-auto customised-checkout">
								<h5 class="v1 mb-30">Shipping Address</h5>
								<form name="shipping-address">
									<?php
									do_action('woocommerce_checkout_billing');
									$billing_user_id = get_current_user_id();

									?>
									<input type="hidden" class="woocommerce-checkout" id="billing_user_id" name="billing_user_id" value="<?php echo $billing_user_id; ?>">
									<div class="floating-blk" id="address_key_field">
										<input type="hidden" class="woocommerce-checkout" id="address_key" name="address_key">
									</div>
									<div class="floating-blk" id="billingcountry_field">
										<label for="country" class="floating-row">
											<span class="arrow-btn"><i class="las la-angle-down"></i></span>
											<span class="floating-label">Click to select country</span>
											<?php if ($checkoutType == "domestic") {
												$opt = 'disabled="true"';
											} ?>
											<select id="country" name="country" class="woocommerce-checkout floating-input checkout_country" <?php echo $opt; ?>>
												<?php
												$allowed_countries = WC()->countries->get_shipping_countries();
												foreach ($allowed_countries as $code => $country) {
													if ($checkoutType == "global" && $code != "IN") {
														echo '<option>' . esc_html($country) . '</option>';
													} elseif ($checkoutType == "domestic" && $code == "IN") {

														echo '<option selected="selected">' . esc_html($country) . '</option>';
													}
												}
												?>
											</select>
											<select id="country_add" name="country_add" class="woocommerce-checkout floating-input checkout_country_add" <?php echo $opt; ?> style="display:none;">
												<?php
												$allowed_countries = WC()->countries->get_shipping_countries();
												foreach ($allowed_countries as $code => $country) {
													if ($checkoutType == "global" && $code != "IN") {
														$selected = ($selectedCountry == $code) ? 'selected="selected"' : '';

														echo '<option class="country_option" value="' . esc_attr($code) . '" ' . $selected . '>' . esc_html($country) . '</option>';
													} elseif ($checkoutType == "domestic" && $code == "IN") {

														echo '<option selected="selected">' . esc_html($country) . '</option>';
													}
												}
												?>
											</select>
											<p id="err_country" class="floating-input-error">Please select your country.</p>
										</label>
										<input type="hidden" class="woocommerce-checkout" id="billing_country" name="billing_country">
										<input type="hidden" id="selected_country_value" name="selected_country_value" value="" />
									</div>
									<div class="d-flex mb-4" id="address_group_field">
										<div class="checkboxradio mr-3 my-0">
											<input type="radio" id="test20" name="address-group-name" checked value="Home">
											<label for="test20">Home</label>
										</div>
										<div class="checkboxradio mr-3 my-0">
											<input type="radio" id="test21" name="address-group-name" value="Office">
											<label for="test21">Office</label>
										</div>
										<div class="checkboxradio mr-3 my-0" id="other">
											<input type="radio" class="other" id="test22" name="address-group-name" value="Other">
											<label for="test22">Other</label>
										</div>
										<input type="hidden" id="address_group" value="Home">
									</div>
									<div class="floating-blk other-form-field">
										<label for="other_label" class="floating-row">
											<span class="floating-label">Address Label</span>
											<input class="floating-input" type="text" id="other_label" />
										</label>
									</div>
									<a href="javascript:void(0);" class="button mb" id="continue_addr_checkout">Save</a>
								</form>
							</div>
						</div>
					</form>
				</div>

			</div>
			<?php do_action('woocommerce_checkout_after_customer_details'); ?>
		<?php endif; ?>
		<div class="col-xl-4 d-none d-xl-block">
			<div class="cart-blk ml-auto">
				<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

				<?php do_action('woocommerce_checkout_before_order_review'); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">

					<?php do_action('woocommerce_checkout_order_review'); ?>
				</div>

				<?php do_action('woocommerce_checkout_after_order_review'); ?>
			</div>
		</div>
		</div>
</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>


<script>
	$(document).ready(function() {
		console.log("Checkout Page");
		var selectedCountry = sessionStorage.getItem("selectedCountry");
		console.log("Selected Country: " + selectedCountry);
		$("#add_Addr_check").on('click', function() {
			$(".checkout_country").hide();
			$(".checkout_country_add").show();
			$('#country_add .country_option[value="' + selectedCountry + '"]').prop('selected', true);
			// $('#country_add').val(selectedCountry);  

		});
		$(".checkout_edit_address").on('click', function() {
			$(".checkout_country").show();
			$(".checkout_country_add").hide();
		})

	});
</script>

<?php
// Fetch cart items
$cart_items = WC()->cart->get_cart();

// Initialize the items array
$items = array();
// Loop through cart items to gather data
foreach ($cart_items as $cart_item_key => $cart_item) {
	// Get product data
	$_product = $cart_item['data'];
	$category_cart = '';
	if ($_product->is_type('variation')) {
		$parent_id = $_product->get_parent_id();
		$category_names = get_the_terms($parent_id, 'product_cat');
	} else {
		$category_names = get_the_terms($_product->get_id(), 'product_cat');
	}

	// If categories are found, collect their names
	if (!empty($category_names) && !is_wp_error($category_names)) {
		foreach ($category_names as $category) {
			$category_cart .= $category->slug . ',';
		}
	}

	// Prepare item data
	$item_data = array(
		'item_id' => $_product->get_id(),
		'item_name' => $_product->get_name(),
		'item_brand' => 'SSP', // You may need to fetch brand data from your product meta
		'item_category' => rtrim($category_cart, ','),
		'item_variant' => $_product->get_sku(),
		'price' => $_product->get_price(),
		'quantity' => $cart_item['quantity']
	);

	$items[] = $item_data;
}

$product_type = WC()->session->get('checkout_type');
if ($product_type == 'global') {
	$ecom_cart_tot = WC()->cart->cart_contents_total + WC()->cart->shipping_total;
} else {
	$ecom_cart_tot = WC()->cart->cart_contents_total;
}

?>
<script>
	window.dataLayer = window.dataLayer || [];
	window.dataLayer.push({
		event: "begin_checkout",
		ecommerce: {
			currency: "<?php echo get_woocommerce_currency(); ?>",
			value: <?php echo  ceil($ecom_cart_tot); ?>,
			items: <?php echo json_encode($items); ?>
		}
	});
</script>