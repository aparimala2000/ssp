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
if (isset($_SESSION['cart_type'])) {
	$checkoutType = $_SESSION['cart_type'];
}
?>

<form name="checkout" method="post" id="checkout-form" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

	<?php if ($checkout->get_checkout_fields()) : ?>

		<?php do_action('woocommerce_checkout_before_customer_details'); ?>

		<div class="row justify-content-xl-between mb-50">
			<div class="col-lg-10 col-xl-7 mb-50 mb-xl-0">
				<div class="mb-50">
					<h3>Checkout</h3>
					<!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco</p> -->
					<div class="mb-4"></div>
					<?php
					if (is_user_logged_in()) {
						$nextDiv = "display:block";
						global $current_user;
						$useremail = $current_user->user_email;
						if ($useremail == "") {
							$useremail = $current_user->user_login;
							$labelName = 'Username';
						} else {
							$labelName = 'Email';
						}
					?>
						<form>
							<div class="floating-blk">
								<label for="user_email" class="floating-row">
									<span class="floating-label"><?php echo $labelName; ?></span>
									<input class="floating-input adrs-book-input" type="Email" id="user_email" value="<?php echo $useremail; ?>" disabled>
								</label>
							</div>
						</form>
					<?php } ?>
					<?php if (!is_user_logged_in()) {
						$nextDiv = "display:none"; ?>
						<input type="hidden" id="scrVal" value="Guest">
						<input type="hidden" id="guest_customer_id">
						<input type="hidden" id="bPhone" value="<?php echo $_SESSION['orderPhone']; ?>">
						<input type="hidden" id="bEmail" value="<?php echo $_SESSION['orderEmail']; ?>">
						<input type="hidden" id="bFname" value="<?php echo $_SESSION['orderFName']; ?>">
						<input type="hidden" id="bLname" value="<?php echo $_SESSION['orderLName']; ?>">
						<form name="guest_checkout" class="guest_checkout" method="post">
							<div class="floating-blk guest_checkout">
								<label for="user_email" class="floating-row">
									<?php if ($checkoutType == "domestic") { ?>
										<span class="floating-label">Email / Phone</span>
										<input class="floating-input adrs-book-input username" type="text" id="user_email">
										<p class="floating-input-error log_email">Looks like you’ve forgotten to, or entered an invalid email or phone number.</p>
									<?php } else { ?>
										<span class="floating-label">Email</span>
										<input class="floating-input adrs-book-input username" type="text" id="user_email">
										<p class="floating-input-error log_email">Looks like you’ve forgotten to, or entered an invalid email.</p>
									<?php } ?>

								</label>
							</div>
							<a href="javascript:void(0);" class="button guest_checkout mb tb proceed_checkout">Proceed</a>
						</form>
						<form name="guest_checkout_otp" class="guest_checkout_otp" method="post" style="display:none;">
							<div class="floating-blk">
								<label for="guest_user" class="floating-row">
									<span class="floating-label">Enter the OTP</span>
									<input class="floating-input" type="text" id="guest_user_otp" maxlength="6">
									<p class="floating-input-error guest_otp">Please enter the OTP sent to the registered mobile number/ email address</p>
								</label>
							</div>
							<a href="javascript:void(0);" class="button mb tb guest_otp_verify">Submit</a>
							<p>If you haven't received OTP yet, please <a href="javascript:void(0)" class="link-anim resendOtpguestLogin">click here.</a></p>
						</form>
					<?php } ?>
				</div>
				<!-- address card start -->
				<?php $user_id = get_current_user_id();
				$addresses = get_user_meta($user_id, 'user_addresses', true);
				// var_dump($addresses); 
				if (is_array($addresses) && !empty($addresses)) { ?>
					<div class="adrs-book" style="<?php echo $nextDiv; ?>">
						<div class="row mb-30">
							<?php

							$count = 0;
							foreach ($addresses as $index => $address) {
								$Fname = $address["first_name"];
								$Lname = $address["last_name"];
								$email = $address["email"];
								$phone = $address["phone"];
								$address_1 = $address["address_1"];
								$city = $address["city"];
								$state = $address["state"];
								$postcode = $address["postcode"];
								$country = $address["country"]; ?>
								<div class="col-md-6 mb-30">
									<div class="checkboxradio with-card">
										<input type="radio" id="address_id_<?php echo $count; ?>" name="radio-group">
										<label for="address_id_<?php echo $count; ?>" class="h-100 w-100">
											<div class="normal-cards">
												<div>
													<h6><?php echo $Fname . ' ' . $Lname; ?></h6>
													<p><?php echo $email; ?></p>
													<p><?php echo $phone; ?></p>
													<p><?php echo $address_1 . ' ' . $city; ?></p>
													<p><?php echo $state . ',' . $postcode; ?></p>
													<p><?php echo $country; ?></p>
												</div>
											</div>
										</label>
									</div>
								</div>
							<?php
								$count++;
							}
							?>
							<div class="col-md-6 mb-30">
								<div class="checkboxradio with-card no-radio">
									<input type="radio" id="test4" name="radio-group">
									<label for="test4" class="h-100 w-100">
										<div class="normal-cards d-flex primary">
											<span class="mr-2"><i class="fa fa-plus" aria-hidden="true"></i></span>
											<h6 class="pb-0">Add Address</h6>
										</div>
									</label>
								</div>
							</div>

						</div>
					</div>
				<?php
				}
				?>
				<!-- address card end -->
				<div class="adrs-book-form" style="<?php echo $nextDiv; ?>">
					<h5 class="v1 mb-30">Shipping Address</h5>
					<?php do_action('woocommerce_checkout_billing'); ?>
					<a href="javascript:void(0);" class="button mb" id="continue_checkout">continue</a>
				</div>
			</div>
			<?php do_action('woocommerce_checkout_after_customer_details'); ?>
		<?php endif; ?>
		<div class="col-xl-5">
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
