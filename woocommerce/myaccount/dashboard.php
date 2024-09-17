<?php

/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
$user = wp_get_current_user();
$first_name = $user->first_name;
$last_name = $user->last_name;
$phone = get_user_meta($user->ID, 'phone', true);
$email = $user->user_email;
// var_dump($email);
?>
 
<div class="container">
	<div class="mb-60">
		<div class="mid-container">
			<div class="d-md-flex justify-content-between mb-50 pb-5" style="border-bottom: 1px solid #F8F2F2;">
				<div>
					<h3><?php echo $first_name; ?> <?php echo $last_name; ?></h4>
						<span class="ld"><?php echo $phone; ?> <?php echo $email; ?></span>
				</div>
				<div>
					<a href="<?php echo get_bloginfo('url'); ?>/my-account/edit-account/" class="button mb tb mt-4 mt-md-0">Edit Profile</a>
				</div>
			</div>
			<!-- account body start -->
			<div class="row justify-content-between">
				<div class="col-xl-3 col-lg-4 mb-40 mb-xl-0 ">
					<div class="dropdown-blk">
						<a href="javascript:(0);" class="dropdown-btn active">Orders</a>
						<div class="with-dropdown">
							<ul class="dropdown-con">
								<li class="active" data-tab="id1"><a href="javascript:void(0);"><span class="mr-3"><i class="fa fa-user" aria-hidden="true"></i></span>Orders</a></li>
								<li data-tab="id2"><a href="javascript:void(0);"><span class="mr-3"><i class="fa fa-map-marker" aria-hidden="true"></i></span>Addresses</a></li>
								<li><a href="<?php echo wp_logout_url(home_url()); ?>"><span class="mr-3"><i class="fa fa-sign-out" aria-hidden="true"></i></span>Logout</a></li>

							</ul>
						</div>
					</div>
				</div>
				<div class="col-lg-8">
					<div id="id1" class="tab-content active">
						<div class="d-md-inline-block mb-30 p-1 tab-nav-blk">
							<ul class="tab-nav tab-btn1 d-flex justify-content-between align-items-center py-0">
								<li class="active" data-tab1="id01">
									<a href="javascript:void(0);" class="button ">History</a>
								</li>
								<li data-tab1="id02">
									<a href="javascript:void(0);" class="button">Cancelled</a>
								</li>
								<li data-tab1="id03">
									<a href="javascript:void(0);" class="button">Ongoing</a>
								</li>
							</ul>
						</div>
						<div class="mb-60">
							<?php
							$customer_orders = wc_get_orders(array(
								'customer_id' => get_current_user_id(),
								'status' => array('wc-processing', 'wc-completed', 'wc-cancelled'),
								'limit' => -1,
							));
							// echo 'Total Orders: ' . count($customer_orders) . '<br>';
							?>
							<div class="tab-content active" id="id01" id="completed-orders-tab">
								<div class="overflow-auto mb-60">
									<table class="cart-table with-hover">
										<thead>
											<tr>
												<th><span class="ld b-font">Order No</span></th>
												<th><span class="ld b-font">Date</span></th>
												<th><span class="ld b-font">Total</span></th>
											</tr>
										</thead>
										<tbody>
											<?php
											if ($customer_orders) {
												$completed_orders = array_filter($customer_orders, function ($order) {
													
													return $order->get_status() == 'completed';
												});
												$noOrder = 5; // Number of orders to show per page
												$pagination = new Pagination;
												$completed_orders = $pagination->generate($completed_orders, $noOrder);
											 foreach ($completed_orders as $customer_order) {
												
													$order_number = $customer_order->get_order_number();
													$order_date = $customer_order->get_date_created()->format('F j, Y');
													$order_total = $customer_order->get_formatted_order_total();
													
											?>
													<tr>
														<td>
															<div>
																<a href="<?php echo esc_url($customer_order->get_view_order_url()); ?>" class="link-anim ld d-inline-block">#<?php echo esc_html($order_number); ?></a>
															</div>
														</td>
														<td>
															<span class="ld"><?php echo $order_date; ?></span>
														</td>
														<td>
															<span class="ld"><?php echo $order_total; ?></span>
														</td>
													</tr>
												<?php } ?>
											<?php } 
											?>
										</tbody>
									</table>
								</div>
								<!-- pagination start    -->
								<?php 
								if($pagination == ""){
								$pagination = new Pagination(); } 
								$links = $pagination->links();
								echo $links; ?>
								<!-- pagination end    -->
							</div>
							<div class="tab-content" id="id02">
								<div class="overflow-auto">
									<table class="cart-table with-hover">
										<thead>
											<tr>
												<th><span class="ld b-font">Order No</span></th>
												<th><span class="ld b-font">Date</span></th>
												<th><span class="ld b-font">Total</span></th>
											</tr>
										</thead>
										<tbody>
											<?php
											if ($customer_orders) { 
												foreach ($customer_orders as $customer_order) {
													if ($customer_order->get_status() == 'cancelled') {
														$order_number = $customer_order->get_order_number();
														$order_date = $customer_order->get_date_created()->format('F j, Y');
														$order_total = $customer_order->get_formatted_order_total();
													
											?>
														<tr>
															<td>
																<div>
																	<a href="<?php echo esc_url($customer_order->get_view_order_url()); ?>" class="link-anim ld d-inline-block">#<?php echo esc_html($order_number); ?></a>
																</div>
															</td>

															<td>
																<span class="ld"><?php echo $order_date; ?></span>
															</td>
															<td>
																<span class="ld"><?php echo $order_total; ?></span>
															</td>
														</tr>
													<?php } ?>
											<?php }
											} ?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="tab-content" id="id03">
								<div class="overflow-auto">
									<table class="cart-table with-hover">
										<thead>
											<tr>
												<th><span class="ld b-font">Order No</span></th>
												<th><span class="ld b-font">Date</span></th>
												<th><span class="ld b-font">Total</span></th>
												<?php if ($customer_orders) {
													$has_processing_orders = false;
													foreach ($customer_orders as $customer_order) {
														if ($customer_order->get_status() == 'processing') {
															$has_processing_orders = true;
															break;
														}
													}
													if ($has_processing_orders) {
												?>
														<th><span class="ld b-font"></span></th>
												<?php }
												} ?>
											</tr>
										</thead>
										<tbody>
											<?php
											if ($customer_orders) {
												foreach ($customer_orders as $customer_order) {
													$orderDate = $customer_order->order_date;
													date_default_timezone_set('Asia/Kolkata');
													$now = date('Y-m-d H:i:s');
													$date1 = new DateTime($orderDate);
													$date2 = new DateTime($now);
													$interval = $date1->diff($date2);
													$countedDays = $interval->days;
													$cancelDuration = get_option('cancel_option');
													if ($customer_order->get_status() == 'processing') {
														$order_number = $customer_order->get_order_number();
														$order_date = $customer_order->get_date_created()->format('F j, Y');
														$order_total = $customer_order->get_formatted_order_total();
													
											?>
														<tr>
															<td>
																<div>
																	<a href="<?php echo esc_url($customer_order->get_view_order_url()); ?>" class="link-anim ld d-inline-block">#<?php echo esc_html($order_number); ?></a>
																</div>
															</td>
															<td>
																<span class="ld"><?php echo $order_date; ?></span>
															</td>
															<td>
																<span class="ld"><?php echo $order_total; ?></span>
															</td>
															<td>
																<?php if ($countedDays <= $cancelDuration) : ?>
																	<a href="javascript:void(0);" class="button sm-btn popup-open" data-order-id="<?php echo $order_number; ?>">Cancel</a>
																<?php endif; ?>

															</td>
														</tr>
													<?php } ?>
											<?php }
											} ?>
										</tbody>
									</table>
								</div>
							</div>


						</div>

					</div>
					<div id="id2" class="tab-content">

						<form id="address-list">
							<div class="row mb-30">
								<?php
								$user_id = get_current_user_id();
								$addresses = get_user_meta($user_id, 'user_addresses', true);
								// var_dump($addresses); 
								if (is_array($addresses) && !empty($addresses)) {
									foreach ($addresses as $index => $address) {
										$Fname = $address["first_name"];
										$Lname = $address["last_name"];
										$email = $address["email"];
										$phone = $address["phone"];
										$address_1 = $address["address_1"];
										$city = $address["city"];
										$state = $address["state"];
										$postcode = $address["postcode"];
										$country = $address["country"];
										$address_group = $address["address_group"];
								?>
										<div class="col-sm-6 mb-30">
											<div class="with-card">
												<div class="normal-cards">
													<div>
														<div class="d-flex justify-content-between">
															<h6><?php echo $address_group; ?></h6>
															<div class="d-flex">
																<a href="javascript:void(0)" title="Edit" class="circle-icon type2 mr-1 edit_address" id="edit_address" data-index="<?php echo $index; ?>" data-fname=" <?php echo $Fname; ?>" data-lname="<?php echo $Lname; ?>" data-email="<?php echo $email; ?>" data-phone="<?php echo $phone; ?>" data-address1="<?php echo $address_1; ?>" data-city="<?php echo $city; ?>" data-state="<?php echo $state; ?>" data-postcode="<?php echo $postcode; ?>" data-country="<?php echo $country; ?>" data-address_group="<?php echo $address_group; ?>"><i class="las la-pencil"></i></a>
																<a href="#" title="Remove" class="circle-icon type2 remove-address" data-index="<?php echo $index; ?>"><i class="las la-trash"></i></a>

																<!-- <a href="javascript:void(0)" title="Remove" class="circle-icon type2"><i class="las la-trash"></i></a> -->
															</div>
														</div>
														<div class="ash-color">
															<p><?php echo $Fname . ' ' . $Lname . ','; ?></p>
															<p><?php echo $email . ','; ?></p>
															<p><?php echo $phone . ','; ?></p>
															<p><?php echo $address_1 . ' ' . $city . ','; ?></p>
															<p><?php echo $state . '-' . $postcode . ','; ?></p>
															<p><?php echo $country . '.'; ?></p>
														</div>
													</div>
												</div>
											</div>
										</div>
								<?php }
								} ?>
								<div class="col-sm-6 mb-30">
									<div class="with-card add-adrs-btn  add-adrs-btn1">
										<div class="normal-cards d-flex primary">
											<span class="mr-2"><i class="fa fa-plus" aria-hidden="true"></i></span>
											<h6 class="pb-0">Add Address</h6>
										</div>
									</div>
								</div>
							</div>
						</form>
						<form id="Adress_form">
							<div class="bg-layer"></div>
							<div class="adrs-form">
								<div class="text-right">
									<a href="javascript:void(0);" class="add-adrs-close mb-4"><i class="las la-times"></i></a>
								</div>
								<div class="adrs-form-content ml-auto">

									<h5 class="v1 mb-30">Shipping Address</h5>
									<div class="floating-blk">
										<label for="billing_first_name" class="floating-row">
											<span class="floating-label">First Name</span>
											<input class="floating-input" type="text" name="billing_first_name" id="billing_first_name" value="" />
											<p id="err_billing_first_name" class="floating-input-error">Please provide your first name.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="billing_last_name" class="floating-row">
											<span class="floating-label">Last Name</span>
											<input class="floating-input" type="text" name="billing_last_name" id="billing_last_name" />
											<p id="err_billing_last_name" class="floating-input-error">Please provide your last name.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="billing_email" class="floating-row">
											<span class="floating-label">Email</span>
											<input class="floating-input" type="Email" name="billing_email" id="billing_email" />
											<p id="err_billing_email" class="floating-input-error">Looks like you’ve missed entering your email.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="billing_phone" class="floating-row">
											<span class="floating-label">Phone Number</span>
											<input class="floating-input" type="number" name="billing_phone" id="billing_phone" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
											<p id="err_billing_phone" class="floating-input-error">It’s important for us to know your phone number.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="billing_address_1" class="floating-row">
											<span class="floating-item-label">Address</span>
											<textarea type="text" name="billing_address_1" id="billing_address_1" class="floating-input" rows="3"></textarea>
											<p id="err_billing_address_1" class="floating-input-error">Oops! You’ve forgotten to enter your address.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="billing_postcode" class="floating-row">
											<span class="floating-label">PIN Code</span>
											<input class="floating-input" type="text" name="billing_postcode" id="billing_postcode1" />
											<p id="err_billing_postcode" class="floating-input-error">Seems like you’e forgotten to enter your postcode.</p>
										</label>
									</div>

									<div class="floating-blk">
										<label for="billing_state" class="floating-row">
											<span class="floating-label">State</span>
											<input class="floating-input" type="text" name="billing_state" id="billing_state" />
											<p id="err_billing_state" class="floating-input-error">Your state is as important as your address.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="billing_city" class="floating-row">
											<span class="floating-label">City</span>
											<input class="floating-input" type="text" name="billing_city" id="billing_city" />
											<p id="err_billing_city" class="floating-input-error">Looks like you’ve forgotten to enter your city.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="billing_country" class="floating-row">
											<span class="arrow-btn"><i class="las la-angle-down"></i></span>
											<span class="floating-label">Click to select country</span>
											<select id="billing_country" name="billing_country" class="floating-input">
												<option value="" hidden></option>
												<?php
												$allowed_countries = WC()->countries->get_shipping_countries();
												foreach ($allowed_countries as $code => $country) {
													echo '<option>' . esc_html($country) . '</option>';
												}
												?>
											</select>
											<p id="err_billing_country" class="floating-input-error">Please select your country.</p>
										</label>
									</div>
									<div class="d-flex mb-4" id="address_group_field_ad">
										<div class="checkboxradio mr-3 my-0">
											<input type="radio" id="hmtest" name="address-group-name-add" checked value="Home">
											<label for="hmtest">Home</label>
										</div>
										<div class="checkboxradio mr-3 my-0">
											<input type="radio" id="oftest" name="address-group-name-add" value="Office">
											<label for="oftest">Office</label>
										</div>
										<div class="checkboxradio mr-3 my-0" id="other">
											<input type="radio" class="other" id="ottest" name="address-group-name-add" value="Other">
											<label for="ottest">Other</label>
										</div>
										<input type="hidden" name="address_group" class="address_group" value="Home">
									</div>
									<div class="floating-blk other-form-field">
										<label for="other_label" class="floating-row">
											<span class="floating-label">Address Label</span>
											<input class="floating-input other_label1" type="text" id="other_label" />
										</label>
									</div>
									<a href="javascript:void(0);" id="add_address" class="button mb">Save</a>
								</div>
							</div>
							<div class="mb-40"></div>
						</form>
						<form>
							<div class="bg-layer-edit"></div>
							<div class="edit_address_form">
								<div class="text-right">
									<a href="javascript:void(0);" class="add-adrs-close mb-4"><i class="las la-times"></i></a>
								</div>
								<div class="adrs-form-content ml-auto">
									<h5 class="v1 mb-30">Edit Shipping Address</h5>

									<div class="floating-blk">
										<label for="first_name" class="floating-row">
											<span class="floating-label">First Name</span>
											<input class="floating-input" type="text" name="first_name" id="first_name" />
											<p id="err_first_name" class="floating-input-error">Please provide your first name.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="last_name" class="floating-row">
											<span class="floating-label">Last Name</span>
											<input class="floating-input" type="text" name="last_name" id="last_name" />
											<p id="err_last_name" class="floating-input-error">Please provide your last name.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="email" class="floating-row">
											<span class="floating-label">Email</span>
											<input class="floating-input" type="Email" name="email" id="email" />
											<p id="err_email" class="floating-input-error">Looks like you’ve missed entering your email.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="phone" class="floating-row">
											<span class="floating-label">Phone Number</span>
											<input class="floating-input" type="number" name="phone" id="phone" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
											<p id="err_phone" class="floating-input-error">It’s important for us to know your phone number.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="address_1" class="floating-row">
											<span class="floating-item-label">Address</span>
											<textarea type="text" name="address_1" id="address_1" class="floating-input" rows="3"></textarea>
											<p id="err_address_1" class="floating-input-error">Oops! You’ve forgotten to enter your address.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="postcode" class="floating-row">
											<span class="floating-label">PIN Code</span>
											<input class="floating-input" type="text" name="postcode" id="postcode" />
											<p id="err_postcode" class="floating-input-error">Seems like you’e forgotten to enter your postcode.</p>
										</label>
									</div>

									<div class="floating-blk">
										<label for="state" class="floating-row">
											<span class="floating-label">State</span>
											<input class="floating-input" type="text" name="state" id="state" />
											<p id="err_state" class="floating-input-error">Your state is as important as your address.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="city" class="floating-row">
											<span class="floating-label">City</span>
											<input class="floating-input" type="text" name="city" id="city" />
											<p id="err_city" class="floating-input-error">Looks like you’ve forgotten to enter your city.</p>
										</label>
									</div>
									<div class="floating-blk">
										<label for="country" class="floating-row">
											<span class="arrow-btn"><i class="las la-angle-down"></i></span>
											<span class="floating-label">Click to select country</span>
											<select id="country" name="country" class="floating-input">
												<option value="" hidden></option>
												<?php
												$allowed_countries = WC()->countries->get_shipping_countries();
												foreach ($allowed_countries as $code => $country) {
													echo '<option>' . esc_html($country) . '</option>';
												}

												?>
											</select>
											<p id="err_country" class="floating-input-error">Please select your country.</p>
										</label>
									</div>
									<div class="d-flex mb-4" id="address_group_field_up">
										<div class="checkboxradio mr-3 my-0">
											<input type="radio" id="hm" name="address-group-name-update" value="Home">
											<label for="hm">Home</label>
										</div>
										<div class="checkboxradio mr-3 my-0">
											<input type="radio" id="of" name="address-group-name-update" value="Office">
											<label for="of">Office</label>
										</div>
										<div class="checkboxradio mr-3 my-0" id="other">
											<input type="radio" class="other" id="ot" name="address-group-name-update" value="Other">
											<label for="ot">Other</label>
										</div>
										<input type="hidden" name="address_group" class="address_group" value="Home">
									</div>
									<div class="floating-blk other-form-field">
										<label for="other_label" class="floating-row">
											<span class="floating-label">Address Label</span>
											<input class="floating-input other_label2" type="text" id="other_label" />
										</label>
									</div>
									<a href="javascript:void(0);" class="button mb" id="edit_address_form">Update</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>

			<!-- account body end -->


		</div>
	</div>
</div>
<!-- delete popup start -->


<div class="popup-box-blk">
	<div class="popup-box">
		<a href="javascript:void(0);" class="popup-close d-block text-right"><i class="las la-times"></i></a>
		<div>
			<p>Are you sure you wish to cancel this order?</p>
			<a href="javascript:void(0);" class="button mb popup-close" id="confirm-cancel-order">Yes</a>
		</div>
	</div>
</div>

<!-- delete popup end -->


<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action('woocommerce_account_dashboard');

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_before_my_account');

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_after_my_account');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
