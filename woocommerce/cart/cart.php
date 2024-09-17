<?php

/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.4.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<form id="cartform" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
	<?php do_action('woocommerce_before_cart_table'); ?>
	<table class="cart-table v1">
		<thead>
			<tr>
				<th class="product-name"><span class="ld"><?php esc_html_e('Product Name', 'woocommerce'); ?></span></th>
				<th class="product-quantity"><span class="ld"><?php esc_html_e('Quantity', 'woocommerce'); ?></span></th>
				<th class="product-price"><span class="ld"><?php esc_html_e('Total', 'woocommerce'); ?></span></th>
			</tr>
		</thead>
		<tbody>
			<?php do_action('woocommerce_before_cart_contents'); ?>
			<?php
			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
				if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
					$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
					$pr_name = $_product->get_name();
					if (strpos($pr_name, ' - ') !== false) {
						$pr_name = substr($pr_name, 0, strpos($pr_name, ' - '));
					} ?>
					<?php
					session_start();
					$product_type = WC()->session->get('checkout_type');
					// echo $product_type;
					$product_pos = strpos($product_permalink, "/products/");
					$product_url = substr_replace($product_permalink, "global/", $product_pos + 10, 0);

					$product_url_parts = explode('/', $product_url);
					if (isset($product_url_parts[6]) && !empty($product_url_parts[6])) {
						unset($product_url_parts[6]);
					}

					$product_url = implode('/', $product_url_parts);
					// echo $product_url;
					if ($product_type == 'global') { ?>
						<tr class="t-row woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
							<td>
								<div class="d-flex">
									<a href="javascript:void(0);" class="circle-icon product_remove_cart type2" data-product-id="<?php echo $product_id; ?>"><i class="las la-times"></i></a>
									<?php
									$thumbnail = wp_get_attachment_url(get_post_thumbnail_id($cart_item['product_id']), $_product->get_title(), false);
									?>
									<a href="<?php echo $product_url; ?>" class="product-thum mr-3"><img src="<?php echo $thumbnail; ?>" alt="images"></a>
									<div>
										<span class="ld mb-2"><?php echo $pr_name; ?></span>
										<!-- <form> -->
										<div class="floating-blk v1 d-inline-block mb-2">
											<label for="select1" class="floating-row">
												<select id="select1" class="floating-input ld py-0" name="floating-label" disabled>
													<option value="" hidden=""></option>
													<?php
													$var_id = $cart_item['variation_id'];
													$itemId = (!empty($cart_item['variation_id'])) ? $cart_item['variation_id'] : '';
													$variation_product = new WC_Product_Variation($var_id);
													$var_weight = $variation_product->weight;
													?>
													<option selected="true"><?php echo $var_weight . " gms"; ?></option>
												</select>
											</label>
										</div>
										<!-- </form> -->
										<?php
										if (!empty($cart_item['variation_id'])) {
											$var_id = $cart_item['variation_id'];
											$var_val = wc_get_product($var_id);
											$var_reg_prc = $var_val->get_regular_price();
											$var_sal_prc = $var_val->get_sale_price();
											$var_prc = $var_sal_prc == '' ? $var_reg_prc : $var_sal_prc;
											if ($var_sal_prc != '') {
										?>
												<span class="price ld"><?php echo wc_price($var_reg_prc); ?></span>

											<?php
											} else {
											?>
												<span class="price ld"><?php echo  wc_price($var_reg_prc); ?></span>
										<?php
											}
										}
										?>
									</div>
								</div>
							</td>

							<td data-title="<?php _e('Quantity', 'woocommerce'); ?>">
								<?php
								$qty = $cart_item['quantity'];
								if ($cart_item['data']->get_shipping_class() === 'enable') {
									$variation = $cart_item['data'];
									$min_qty = get_post_meta($variation->get_variation_id(), '_min_qty_', true);
									$max_qty = get_post_meta($variation->get_variation_id(), '_max_qty_', true);
								}
								$product_quantity = sprintf('
									<div class="count justify-content-center" id="preload' . $product_id . '">
										<span class="box cart-global-qty-minus" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $cart_item['variation_id'] . ' data-min=' . $min_qty . ' data-max=' . $max_qty . '>-</span>
										<input style="display:none" onchange="quantityonchange(' . $product_id . ',this)" id="qty" prod-id=' . $product_id . '  type="text" class="textbox-small" name="cart[%s][qty]" value="%d"  />
										<input id="global_qty' . $cart_item['variation_id'] . '" name="qty" type="text" readonly class="box qty"  value="%d"  />
										<span class="box cart-global-qty-plus active" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $cart_item['variation_id'] . ' data-min=' . $min_qty . ' data-max=' . $max_qty . '>+</span></div>', $cart_item_key, $qty, $qty);
								echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
								?></td>
							<td data-title="<?php _e('Total', 'woocommerce'); ?>">
								<span class="ld b-font" id="cart_price<?php echo $cart_item['variation_id']; ?>">
									<?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
								</span>
							</td>
						</tr>
					<?php } else { ?>
						<tr class="t-row woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
							<td>
								<div class="d-flex">
									<a href="javascript:void(0);" class="circle-icon product_remove_cart type2" data-product-id="<?php echo $product_id; ?>"><i class="las la-times"></i></a>
									<?php
									$thumbnail = wp_get_attachment_url(get_post_thumbnail_id($cart_item['product_id']), $_product->get_title(), false);
									?>
									<a href="<?php echo $product_permalink; ?>" class="product-thum mr-3"><img src="<?php echo $thumbnail; ?>" alt="images"></a>
									<div>
										<!-- <a href="<?php echo $product_permalink; ?>" class="link-anim ld mb-2"><?php echo $display_product_name; ?></a> -->
										<span class="ld mb-2"><?php echo $pr_name; ?></span>
										<!-- <form> -->
										<div class="floating-blk v1 d-inline-block mb-2">
											<label for="select1" class="floating-row">
												<select id="select1" class="floating-input ld py-0" name="floating-label" disabled>
													<option value="" hidden=""></option>
													<?php
													$var_id = $cart_item['variation_id'];
													$itemId = (!empty($cart_item['variation_id'])) ? $cart_item['variation_id'] : '';
													$variation_product = new WC_Product_Variation($var_id);
													$var_weight = $variation_product->weight;
													?>
													<option selected="true"><?php echo $var_weight . " gms"; ?></option>
												</select>
											</label>
										</div>
										<!-- </form> -->
										<?php
										if (!empty($cart_item['variation_id'])) {
											$var_id = $cart_item['variation_id'];
											$var_val = wc_get_product($var_id);
											$var_reg_prc = $var_val->get_regular_price();
											$var_sal_prc = $var_val->get_sale_price();
											$var_prc = $var_sal_prc == '' ? $var_reg_prc : $var_sal_prc;
											if ($var_sal_prc != '') {
										?>
												<span class="price ld"><?php echo wc_price($var_reg_prc); ?></span>

											<?php
											} else {
											?>
												<span class="price ld"><?php echo  wc_price($var_reg_prc); ?></span>
										<?php
											}
										}
										?>
									</div>
								</div>
							</td>

							<td data-title="<?php _e('Quantity', 'woocommerce'); ?>">
								<?php
								$qty = $cart_item['quantity'];

								$product_quantity = sprintf('
									<div class="count justify-content-center" id="preload' . $product_id . '">
										<span class="box cart-qty-minus" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $cart_item['variation_id'] . '>-</span>
										<input style="display:none" onchange="quantityonchange(' . $product_id . ',this)" id="qty" prod-id=' . $product_id . '  type="text" class="textbox-small" name="cart[%s][qty]" value="%d"  />
										<input id="dummyQty' . $cart_item['variation_id'] . '" name="qty" type="text" readonly class="box qty"  value="%d"  />
										<span class="box cart-qty-plus active" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $cart_item['variation_id'] . '>+</span></div>', $cart_item_key, $qty, $qty);
								echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
								?></td>
							<td data-title="<?php _e('Total', 'woocommerce'); ?>">
								<span class="ld b-font" id="cart_price<?php echo $cart_item['variation_id']; ?>">
									<?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
								</span>
							</td>
						</tr>
					<?php } ?>
			<?php
				}
			}
			?>
			<?php do_action('woocommerce_cart_contents'); ?>
			<?php do_action('woocommerce_after_cart_contents'); ?>
		</tbody>
	</table>
	<?php do_action('woocommerce_after_cart_table'); ?>
</form>

<?php //do_action( 'woocommerce_before_cart_collaterals' ); 
?>

<div class="cart-collaterals">
	<?php //do_action('woocommerce_cart_collaterals'); 	
	?>
</div>
<?php //do_action('woocommerce_after_cart'); 
?>
<style>
	.woocommerce-notices-wrapper {
		display: none !important;
	}
</style>