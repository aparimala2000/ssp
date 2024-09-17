<?php

/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

defined('ABSPATH') || exit;

// Get the current order ID
$order_id = get_query_var('view-order');

// Get the order object
$order = wc_get_order($order_id);

// Get the order items
$items = $order->get_items();

// Get the order total
$total = $order->get_formatted_order_total();
$status = $order->get_status();
$orderDate = $order->order_date;
date_default_timezone_set('Asia/Kolkata');
$now = date('Y-m-d H:i:s');
$date1 = new DateTime($orderDate);
$date2 = new DateTime($now);
$interval = $date1->diff($date2);
$countedDays = $interval->days;
$cancelDuration = get_option('cancel_option');
?>

<div class="container">
	<div class="mid-container">
		<div class="row justify-content-between mb-40">
			<div class="col-md-9">
				<!-- <h3>Order Reference: # <?php echo $order_id; ?></h3> -->
				<h3>Order Reference: # <?php echo $order->get_order_number(); ?></h3>
				<!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco</p> -->
			</div>
			<?php if ($status === 'processing' && ($countedDays <= $cancelDuration)) { ?>
				<div class="col-md text-md-right">
					<a href="javascript:void(0);" class="button mb tb mt-2 mt-md-0 popup-open" data-order-id="<?php echo $order_id; ?>">cancel Order</a>
				</div>
			<?php } ?>
		</div>
		<div class="full-width">
			<div style="background-color:#F8F2F2; margin-bottom: 40px;">
				<div class="container">
					<div class="accord d-block d-xl-none">
						<div>
							<a href="javascript:void(0);" class="toggle_btn active">
								<p><span class="mr-2"><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>Order Details</p>
							</a>
							<div class="inner pt-3 show">
								<div>
									<table class="cart-table with-check mb-30">
										<tbody>
											<tr>
												<td>
													<span class="md">Sub Total</span>
												</td>
												<td>
													<span class="md"><?php echo wc_price($order->subtotal); ?></span>
												</td>
											</tr>
											<tr>
												<td>
													<span class="md">Shipping</span>
												</td>
												<td>
													<?php $checkPrice = $order->get_shipping_total();
													if ($checkPrice == 0) {
														$shipVal = "Free!";
													} else {
														$shipVal = wc_price($order->get_shipping_total());
													}
													?>
													<span class="md"><?php echo $shipVal; ?></span>
												</td>
											</tr>
											<tr>
												<td>
													<span class="md">Discount</span>
												</td>
												<td>
													<?php
													$discount_total = $order->get_discount_total();
													if ($discount_total > 0) {
														echo '<span class="md">' . wc_price($discount_total) . '</span>';
													} else {
														echo '<span class="md">-0</span>';
													}
													?>
												</td>
											</tr>
											<tr>
												<td>
													<span class="md">Payment</span>
												</td>
												<td>
													<span class="md"><?php echo ucfirst($order->get_payment_method()); ?></span>
												</td>
											</tr>
											<tr>
												<td>
													<span class="b-font md">Total</span>
												</td>
												<td>
													<span class="b-font md"><?php echo $total; ?></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mb-60">
			<div class="col-xl-8">
				<table class="cart-table mb-60">
					<thead>
						<tr>
							<th><span class="ld b-font">Product Name</span></th>
							<th><span class="ld b-font">Count</span></th>
							<th><span class="ld b-font">Total</span></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$has_last_row = false;
						foreach ($items as $item) {
							$product = $item->get_product();
							$product_id = $item->get_product_id();
							$product_name = $product->get_title();
							$product_price = $product->get_price_html();
							$weight = $product->get_weight();
							if ($weight >= 1000) {
								$var_weight_display = ($weight / 1000) . ' kg';
							} else {
								$var_weight_display = $weight . ' gms';
							}
							$image_id = $product->get_image_id();
							$image_url = wp_get_attachment_image_src($image_id, 'full')[0];
							$quantity = $item->get_quantity();
							$subtotal = $item->get_subtotal();
							$formatted_subtotal = wc_price($subtotal);
							$product_permalink = get_permalink($product->get_id());
							$product_permalink = remove_query_arg('attribute_weight', $product_permalink);
						?>
							<?php if ($product_id == 725) {
								$has_last_row = true;
								$free_product_id = $product_id;
								$free_quantity = $quantity;
								$free_product_name = $product_name;
								$free_product_price = $product_price;
								$free_product_total = $formatted_subtotal;
								$free_product_image = $image_url;
								continue;
							} else { ?>

								<tr>
									<td>
										<div class="d-flex">
											<a href="<?php echo $product_permalink; ?>" class="product-thum mr-3"><img src="<?php echo $image_url; ?>" alt="images"></a>
											<div>
												<span class="ld mb-2"><?php echo $product_name; ?></span>
												<span class="ld mb-2"><?php echo $var_weight_display; ?></span>
												<span class="price ld"><?php echo $product_price; ?></span>
											</div>
										</div>
									</td>
									<td>
										<span class="ld">&times;<?php echo $quantity; ?></span>
									</td>

									<td>
										<span class="ld b-font"><?php echo $formatted_subtotal; ?></span>
									</td>
								</tr>
							<?php } ?>
						<?php } ?>
						<?php if ($has_last_row) {
						?>
							<tr>
								<td>
									<div class="d-flex">
										<a href="javascript:void(0);" class="product-thum mr-3"><img src="<?php echo $free_product_image; ?>" alt="images"></a>

										<div>
											<span class="ld mb-2"><?php echo $product_name; ?></span>
											<!-- <span class="ld mb-2"><?php echo $weight; ?> gms</span> -->
											<span class="price ld"><?php echo $free_product_price; ?></span>
										</div>
									</div>
								</td>
								<td>
									<span class="ld">&times;<?php echo $free_quantity; ?></span>
								</td>

								<td>
									<span class="ld b-font"><?php echo $free_product_total; ?></span>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
			<div class="col-xl-4 d-none d-xl-block">
				<div class="cart-blk ml-auto">
					<h5 class="v1 mr-3 mb-30">Order Details</h5>
					<table class="cart-table with-check mb-30">
						<tbody>
							<tr>
								<td>
									<span class="md">Sub Total</span>
								</td>
								<td>
									<span class="md"><?php echo wc_price($order->subtotal); ?></span>
								</td>
							</tr>
							<tr>
								<td>
									<span class="md">Shipping</span>
								</td>
								<td>
									<?php $checkPrice = $order->get_shipping_total();
									if ($checkPrice == 0) {
										$shipVal = "Free!";
									} else {
										$shipVal = wc_price($order->get_shipping_total());
									}
									?>
									<span class="md"><?php echo $shipVal; ?></span>
								</td>
							</tr>
							<tr>
												<td>
													<span class="md">Discount</span>
												</td>
												<td>
													<?php
													$discount_total = $order->get_discount_total();
													if ($discount_total > 0) {
														echo '<span class="md">' . wc_price($discount_total) . '</span>';
													} else {
														echo '<span class="md">-0</span>';
													}
													?>
												</td>
											</tr>
							<tr>
								<td>
									<span class="md">Payment</span>
								</td>
								<td>
									<span class="md"><?php echo ucfirst($order->get_payment_method()); ?></span>
								</td>
							</tr>
							<tr>
								<td>
									<span class="b-font md">Total</span>
								</td>
								<td>
									<span class="b-font md"><?php echo $total; ?></span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="popup-box-blk">
	<div class="popup-box">
		<a href="javascript:void(0);" class="popup-close d-block text-right"><i class="las la-times"></i></a>
		<div>
			<p>Are you absolutely sure you wish to cancel this order?</p>
			<a href="javascript:void(0);" class="button mb popup-close" id="confirm-cancel-order">Yes</a>
		</div>
	</div>
</div>