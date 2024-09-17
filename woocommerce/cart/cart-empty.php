<?php

/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
// do_action( 'woocommerce_cart_is_empty' );

if (wc_get_page_id('shop') > 0) : ?>

	<div class="cart-table-blk">
		<table class="cart-table v1">
			<thead>
				<tr>
					<th class="product-name"><span class="ld"><?php esc_html_e('Product Name', 'woocommerce'); ?></span></th>
					<th class="product-quantity"><span class="ld"><?php esc_html_e('Quantity', 'woocommerce'); ?></span></th>
					<th class="product-price"><span class="ld"><?php esc_html_e('Total', 'woocommerce'); ?></span></th>
				</tr>
			</thead>
		</table>
		<div class="empty-cart" style="display: block;">
			<div class="row pt-5 mt-xl-5 justify-content-center">
				<div class="col-md-3 mb-30">
					<img src="<?php echo get_bloginfo('template_url'); ?>/lib/images/cart1.png;">
				</div>
				<div class="col-md-6">
					<h5 class="v1 mb-3">Your cart is currently empty</h5>
					<p>Before proceed to checkout, you must add some products to your cart.You will find a lot of interesting products on our page.</p>
					<a href="<?php echo get_bloginfo('url'); ?>/home-use" class="button mb">Return To Shop</a>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>