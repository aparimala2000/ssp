<?php

/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined('ABSPATH') || exit;
?>

<div class="woocommerce-order">

	<?php
	if ($order) :

		do_action('woocommerce_before_thankyou', $order->get_id());
	?>

		<?php if ($order->has_status('failed')) : ?>
			<section>
				<div class="row">
					<div class="col-lg-10 mx-auto">

						<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e('Apologies, your order could not be processed, as the originating bank / merchant has declined your transaction. We request you to attempt this purchase again.', 'woocommerce'); ?></p>

						<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
							<a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay mb tb mt-4"><?php esc_html_e('    Pay    ', 'woocommerce'); ?></a>
							<?php if (is_user_logged_in()) : ?>
								<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button pay mb tb mt-4"><?php esc_html_e('My account', 'woocommerce'); ?></a>
							<?php endif; ?>
						</p>

					</div>
				</div>
			</section>

		<?php else : ?>

			<!-- <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php //echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
																												?></p> -->
			<div class="center-content mb-60" style="max-width: initial;">
				<div class="container">
					<a href="javascript:void(0);" class="circle-icon mx-auto mb-3" style="border: 1px solid #188114; color: #188114; background-color: transparent; font-size: 22px;"><i class="las la-check"></i></a>
					<h3>Thank you for your purchase</h3>
					<p>Your order number is <a href="<?php echo get_bloginfo('url') . '/my-account/view-order/' . $order->get_order_number(); ?>">#<?php echo $order->get_order_number(); ?></a>.</p>
					<p>We'll Email you an order confirmation with details and tracking info</p>
					<a href="<?php echo get_bloginfo('url'); ?>/home-use" class="button mb mt-2">Continue Shopping</a>
				</div>
			</div>

			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details" style="display: none;">

				<li class="woocommerce-order-overview__order order">
					<?php esc_html_e('Order number:', 'woocommerce'); ?>
					<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							?></strong>
				</li>

				<li class="woocommerce-order-overview__date date">
					<?php esc_html_e('Date:', 'woocommerce'); ?>
					<strong><?php echo wc_format_datetime($order->get_date_created()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							?></strong>
				</li>

				<?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()) : ?>
					<li class="woocommerce-order-overview__email email">
						<?php esc_html_e('Email:', 'woocommerce'); ?>
						<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?></strong>
					</li>
				<?php endif; ?>

				<li class="woocommerce-order-overview__total total">
					<?php esc_html_e('Total:', 'woocommerce'); ?>
					<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							?></strong>
				</li>

				<?php if ($order->get_payment_method_title()) : ?>
					<li class="woocommerce-order-overview__payment-method method">
						<?php esc_html_e('Payment method:', 'woocommerce'); ?>
						<strong><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
					</li>
				<?php endif; ?>

			</ul>

		<?php endif; ?>

		<?php //do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); 
		?>
		<?php do_action('woocommerce_thankyou', $order->get_id()); ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters('woocommerce_thankyou_order_received_text', esc_html__('Thank you. Your order has been received.', 'woocommerce'), null); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
																										?></p>

	<?php endif; ?>

</div>
<script>
	window.dataLayer = window.dataLayer || [];
	window.dataLayer.push({
		ecommerce: null
	});

	window.dataLayer.push({
		event: "purchase",
		ecommerce: {
			transaction_id: "<?php echo $order->get_id(); ?>",
			value: <?php echo $order->get_total(); ?>,
			tax: <?php echo $order->get_total_tax(); ?>,
			shipping: <?php echo $order->get_shipping_total(); ?>,
			currency: "<?php echo get_woocommerce_currency(); ?>",
			coupon: "<?php echo implode(', ', $order->get_coupon_codes()); ?>",
			items: [
				<?php
				$items = $order->get_items();
				foreach ($items as $item_id => $item) {
					$product = $item->get_product();
					$variation_id = $item->get_variation_id();
					$item_name = $item->get_name();
					$sku = $product->get_sku();
					$price = $product->get_price();
					$quantity = $item->get_quantity(); 
				 if ($variation_id) {
					$parent_product_id = $product->get_parent_id();  
					$product = wc_get_product($parent_product_id);  
				}
			 
				$terms = get_the_terms($product->get_id(), 'product_cat');
				$categories = [];
			
				if ($terms && !is_wp_error($terms)) {
					foreach ($terms as $term) {
						$categories[] = $term->slug;
					}
				}
			    $category = !empty($categories) ? implode(', ', $categories) : 'No category';
				// error_log('Categories: ' . print_r($categories, true));
				// error_log('Category String: ' . $category); 
				$weight = $product->get_weight();
					if ($variation_id) {
						$variation = new WC_Product_Variation($variation_id);
						$weight = $variation->get_weight();
					}
				?> {
						item_id: "<?php echo $variation_id ? $variation_id : $product->get_id(); ?>",
						item_name: "<?php echo $item_name; ?>",
						item_brand: "SSP",
						item_category: "<?php echo esc_js($category); ?>",
						item_variant: "<?php echo $sku; ?>",
						item_weight: "<?php echo $weight; ?>g",
						price: <?php echo $price; ?>,
						quantity: <?php echo $quantity; ?>
					},
				<?php } ?>
			]
		}
	});
</script>

<script>
	fbq('track', 'Purchase', {
		currency: "INR",
		value: <?php echo $order->get_formatted_order_total(); ?>
	});
</script>