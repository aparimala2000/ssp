<?php

/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

get_header(); ?>
<?php
global $product;
$terms_post = get_the_terms($post->ID, 'product_cat');
global $post;
$product = new WC_Product_Variable($post->ID);
$variations = $product->get_available_variations();
foreach ($terms_post as $term_cat) {
	$term_cat_name = $term_cat->name;
	$term_cat_id = $term_cat->term_id;
	// echo $term_cat_name;
}
// echo $product; 
$productid = $post->ID;
$productname = $post->post_title;
$productcontent = $post->post_content;
$productdesc = $post->post_excerpt;
$productprice = $post->_regular_price;
$productsaleprice = $post->_sale_price;
$price = ($productsaleprice == '' ? $productprice : $productsaleprice);
$image_url = get_the_post_thumbnail_url($productid, 'full');
?>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<!-- product detail start -->
<div class="container">
	<div class="mb-2">
		<?php
		foreach ($terms_post as $term_cat) {
			if ($term_cat->parent == 0) {
				$team_slug = $term_cat->slug;
				// echo $team_slug;
				if ($team_slug === 'industrial-use') { ?>
					<a href="<?php echo get_bloginfo('url'); ?>/industrial-use" style="font-size:14px">Industrial-use</a> /
				<?php  } else if (($team_slug === 'home-use')) { ?>
					<a href="<?php echo get_bloginfo('url'); ?>/home-use" style="font-size:14px">Home-use</a> /
				<?php } else { ?>
					<a href="<?php echo get_bloginfo('url'); ?>/global" style="font-size:14px">Global</a> /
				<?php } ?>
		<?php 	}
		} ?>
	</div>


	<div class="row	product-detail-blk mx-xxl-n4">

		<?php
		$first_variation = reset($variations); // Get the first variation
		$first_vari_id = $first_variation['variation_id'];
		$variation_gallery_images = get_variation_gallery_images($first_vari_id);
		?>
		<?php if (!empty($variation_gallery_images)) {  ?>
			<div class="col-lg-6 col-xl-3 col-xl-32  mb-60 px-xxl-4">
				<?php $firstVariation = true;
				foreach ($variations as $variation) {
					$vari_id = $variation['variation_id'];
					$variation_gallery_image = get_variation_gallery_images($vari_id);
				?>
					<div class="detail-slider-con  <?php if ($firstVariation) echo 'active'; ?>" id="variation-<?php echo $vari_id; ?>">
						<div class="product-main">
							<div class="veg-icon">
								<div class="veg"></div>
							</div>
							<div class="product-slider-for">
								<?php
								 foreach ($variation_gallery_image as $image_url) {
									$image_id = attachment_url_to_postid($image_url); // Get the attachment ID from the URL
									$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true); // Fetch the alt text
								  
									
								?>
									<div class="product-card dynamic-gallery">
										<img src="<?php echo esc_url($image_url); ?>" alt="<?php //echo $image_alt; ?>Variation Image">
									</div>
								<?php } ?>
							</div>
						</div>
						<?php
						$url = $_SERVER['REQUEST_URI'];
						if (strpos($url, "global/") !== false) { ?>
							<div class="product-card-label mb-30">
								<div class="circle-icon type1 mr-4">
									<img src="<?php echo get_bloginfo('template_url'); ?>/lib/images/truck.png">
								</div>
								<p>Shipping charges extra</p>
							</div>
						<?php } else { ?>
							<div class="product-card-label mb-30">
								<div class="circle-icon type1 mr-4">
									<img src="<?php echo get_bloginfo('template_url'); ?>/lib/images/truck.png">
								</div>
								<p>Free Shipping across India</p>
							</div>
						<?php } ?>
						<div class="product-thum-blk product-slider-nav">
							<?php foreach ($variation_gallery_image as $image_url) {
							?>
								<div class="product-thum dynamic-thumbnail">
									<img src="<?php echo esc_url($image_url); ?>" alt="Main Image Thumbnail">
								</div>
							<?php } ?>

						</div>
					</div>
				<?php $firstVariation = false;
				} ?>
			</div>


		<?php } else { ?>
			<!-- Original code -->
			<div class="col-lg-6 col-xl-3 col-xl-32  mb-60 px-xxl-4">

				<div class="product-main">
					<div class="veg-icon">
						<div class="veg"></div>
					</div>
					<div class="product-slider-for">
						<?php

						foreach ($variations as $key => $variation) {
							$vari_id = $variation['variation_id'];
							$varImgs = $variation['image']['url'];
							$variation = new WC_Product_Variation($vari_id);
							$image_id = $variation->get_image_id('edit');
							if ($image_id) {
						?>
								<div class="product-card variation_image" style="display: none;" var_id="<?php echo $vari_id; ?>">
									<img src="<?php echo wp_get_attachment_url($image_id); ?>">
								</div>


						<?php
							}
						}
						?>
					</div>
				</div>
				<?php
				$product = new WC_Product($productid);
				$attachment_ids = $product->get_gallery_image_ids();
				if (!empty($attachment_ids)) {
				?>
					<div class="product-main gallery_img">
						<div class="veg-icon">
							<div class="veg"></div>
						</div>
						<div class="product-slider-for">
							<?php foreach ($attachment_ids as $attachment_id) {
								$image_link = wp_get_attachment_url($attachment_id);
							?>
								<div class="product-card ">

									<img src="<?php echo $image_link; ?>">
								</div>
							<?php } ?>
						</div>
					</div>
					<?php
					$url = $_SERVER['REQUEST_URI'];
					// var_dump($url);
					if (strpos($url, "global/") !== false) { ?>
						<div class="product-card-label mb-30">
							<div class="circle-icon type1 mr-4">
								<img src="<?php echo get_bloginfo('template_url'); ?>/lib/images/truck.png">
							</div>
							<p>Shipping charges extra</p>
						</div>
					<?php } else { ?>
						<div class="product-card-label mb-30">
							<div class="circle-icon type1 mr-4">
								<img src="<?php echo get_bloginfo('template_url'); ?>/lib/images/truck.png">
							</div>
							<p>Free Shipping across india</p>
						</div>
					<?php } ?>
					<div class="product-thum-blk product-slider-nav gallery_thumimg">
						<?php foreach ($attachment_ids as $attachment_id) {
							$image_link = wp_get_attachment_url($attachment_id);
						?>
							<div class="product-thum">
								<img src="<?php echo $image_link; ?>">
							</div>
						<?php } ?>
					</div>
				<?php }  ?>
				<div class="product-thum-blk product-slider-nav">
					<?php
					foreach ($variations as $key => $variation) {
						$vari_id = $variation['variation_id'];
						$varImgs = $variation['image']['url'];
						$variation = new WC_Product_Variation($vari_id);
						$image_id = $variation->get_image_id('edit');
						if ($image_id) { ?>

							<div class="product-thum variation_thumimage" style="display: none;" var_id="<?php echo $vari_id; ?>">
								<img src="<?php echo wp_get_attachment_url($image_id); ?>">
							</div>

					<?php
						}
					}
					?>
				</div>

			</div>
		<?php } ?>
		<div class="col-lg-6 col-xl-40 mb-60 px-xxl-4">
			<div>
				<?php
				foreach ($variations as $key => $variation) {
					$var_val = wc_get_product($variation['variation_id']);

					$min_qty = get_post_meta($variation['variation_id'], '_min_qty_', true);
					$max_qty = get_post_meta($variation['variation_id'], '_max_qty_', true);
					$url = $_SERVER['REQUEST_URI'];
					// var_dump($url);
					if (strpos($url, "global/") !== false) {
						$ship_aval = "";
					} else if ($min_qty && $max_qty) {
						$ship_aval = "international";
						break;
					} else {
						$ship_aval = "not-international";
					}
				}
				?>
				<div class="product-detail point-arrow mb-40 <?php echo $ship_aval; ?>">
					<?php $offer_content = get_post_meta($productid, 'offer_content', true); ?>
					<?php if (!empty($offer_content)) { ?>
						<div class='offer-label'>
							<span class='dot'></span><span><?php echo $offer_content; ?></span>
						</div>
					<?php } ?>
					<h3><?php echo $productname; ?></h3>
					<?php echo apply_filters('the_content', $productcontent); ?>
					<?php //echo $productcontent; 
					?>
				</div>
				<?php if (have_rows('product')) : ?>
					<?php while (have_rows('product')) : the_row(); ?>
						<?php if (get_row_layout() == 'usp') :
							$product_field = get_sub_field("usp_product");
						?>
							<div class="row detail-icon-blk align-items-center">
								<?php foreach ($product_field as $field) {
									$Title = $field['title'];
									$Description = $field['description'];
									$Image = $field['image']['url'];
									$ImageAlt = !empty($field["image"]['alt']) ? $field["image"]['alt'] : $field["image"]['name'];

								?>
									<div class="col-6 mb-4">
										<div class="d-flex align-items-center">
											<img src="<?php echo $Image; ?>" alt="<?php echo $ImageAlt; ?>">
											<div class="ml-3">
												<span class="md b-font"><?php echo $Title; ?></span class="md">
												<span class="md" class="v1"><?php echo $Description; ?></span class="md">
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						<?php endif; ?>
						<?php if (get_row_layout() == 'spacer') :
							$space_val =  get_sub_field('space_value'); ?>
							<div class="spacer" data-space="<?php echo $space_val; ?>"></div>
						<?php endif; ?>
					<?php endwhile; ?>
				<?php endif; ?>

				<?php
				$url = $_SERVER['REQUEST_URI'];
				// var_dump($url);
				if (strpos($url, "global/") !== false) {

					$_product = wc_get_product($post->ID);
					$shipclass = $_product->get_shipping_class();
					if ((!empty($shipclass)) && ($shipclass == "enable")) { ?>
						<div class="quantity-blk row">
							<div class="col-md-6 mb-30 d-flex align-items-center">
								<span class="ld b-font mr-3">Choose Weight</span>
								<form>
									<div class="floating-blk v1 d-inline-block mb-0">
										<label for="glo_weight" class="floating-row">
											<span class="arrow-btn arrow_drop_down_glo"><i class="las la-angle-down"></i></span>
											<select id="glo_weight" class="pro_weight floating-input" name="floating-label">
												<?php
												foreach ($variations as $key => $variation) {
													$varImgs = $variation['image']['url'];
													$var_val = wc_get_product($variation['variation_id']);
													$var_reg_prc = $var_val->get_regular_price();
													$var_sal_prc = $var_val->get_sale_price();
													$variationprice = ($var_sal_prc == '' ? $var_reg_prc : $var_sal_prc);
													$var_weight = $var_val->get_weight();
													$min_qty = get_post_meta($variation['variation_id'], '_min_qty_', true);
													$max_qty = get_post_meta($variation['variation_id'], '_max_qty_', true);
													// check if variation has enabled quantity
													if ($min_qty && $max_qty) {
												?>
														<option value="<?php echo $var_weight; ?>" price-id="<?php echo $variationprice; ?>" data-min-qty="<?php echo $min_qty; ?>" data-max-qty="<?php echo $max_qty; ?>" var-id="<?php echo $variation['variation_id']; ?>"><?php echo $var_weight; ?> gms</option>
												<?php

													}
												} ?>

											</select>
										</label>
									</div>
								</form>
							</div>
							<?php
							// Loop through each variation of the product
							foreach ($variations as $variation) {
								$variation_obj = wc_get_product($variation['variation_id']);
								$var_reg_prc = $variation_obj->get_regular_price();
								$var_sal_prc = $variation_obj->get_sale_price();
								$variation_price = ($var_sal_prc == '' ? $var_reg_prc : $var_sal_prc);
								$min_qty = get_post_meta($variation['variation_id'], '_min_qty_', true);
								$max_qty = get_post_meta($variation['variation_id'], '_max_qty_', true);
								// var_dump($variation_price);
								// Display the variation price and its minimum and maximum quantity if they are set
								if ($min_qty && $max_qty) {
									$latest_variation_price = $variation_price;
									break; // Exit the loop after finding the first variation with enabled quantity
								}
							}

							?>


							<div class="col-md-6 mb-30 d-flex align-items-center">
								<span class="ld b-font mr-3">Quantity</span>
								<div class="count">
									<span class="decre box">-</span>
									<input class="boxglobal prd_qty" type="number" name="" value="<?php echo $min_qty; ?>" min="<?php echo $min_qty; ?>" max="<?php echo $max_qty; ?>" readonly>
									<span class="incre box active">+</span>
								</div>
							</div>
							<div class="col-md-6 mb-30 d-flex align-items-center">
								<span class="ld b-font mr-3">Price</span>
								<span class="globalprice md">₹ <?php echo number_format($latest_variation_price * $min_qty); ?> </span> &nbsp;<span class="minqty packglobal">( Pack of <?php echo $min_qty; ?> )</span>

							</div>


						</div>
						<a href="javascript:void(0);" class="button mb tb add_to_cart_btn add-cart" data-val="global">Add to Cart</a>
						<input type="hidden" id="add_to_cart_url" name="add_to_cart_url" value="<?php echo get_bloginfo('template_url'); ?>/ajax/ajax_add_cart.php" />
						<input type="hidden" name="add-to-cart" value="<?php echo absint($product->id); ?>" />
						<input type="hidden" id="product_id" name="product_id" value="<?php echo absint($product->id); ?>" />
						<input type="hidden" id="product_type" name="product_type" value="global" />
						<input type="hidden" id="variation_id" name="variation_id" class="variation_id" value="0" />

					<?php } ?>
				<?php } else { ?>
					<?php $BleeProdalign = $productid == 457 ? "align-items-start" : ""; ?>
					<div class="quantity-blk row <?php echo $BleeProdalign; ?>">
						<?php $BleeProd = $productid == 457 ? "start" : "center"; ?>
						<div class="d-flex align-items-<?php echo $BleeProd; ?> col-md-6 mb-30">
							<span class="ld b-font mr-3">Choose Weight</span>
							<form>
								<div class="floating-blk v1 d-inline-block mb-0">
									<label for="pro_weight" class="floating-row">
										<span class="arrow-btn arrow_drop_down"><i class="las la-angle-down"></i></span>
										<select id="pro_weight" class="floating-input py-0 pro_weight" name="floating-label">
											<?php
											foreach ($variations as $key => $variation) {
												$varImgs = $variation['image']['url'];
												$var_val = wc_get_product($variation['variation_id']);
												$var_reg_prc = $var_val->get_regular_price();
												$var_sal_prc = $var_val->get_sale_price();
												$variationprice = ($var_sal_prc == '' ? $var_reg_prc : $var_sal_prc);
												$var_weight = $var_val->get_weight();
												// Convert weight to kilograms if it's 1000 grams or more
												if ($var_weight >= 1000) {
													$var_weight_display = ($var_weight / 1000) . ' kg';
												} else {
													$var_weight_display = $var_weight . ' gms';
												}
											?>
												<option value="<?php echo $var_weight; ?>" price-id="<?php echo $variationprice; ?>" var-id="<?php echo $variation['variation_id']; ?>"><?php echo $var_weight_display; ?></option>
											<?php } ?>
										</select>
									</label>
									<?php if ($productid == 457) { ?>
										<span class="md ash-color text-nowrap">( x 5 Units per pack )</span>
									<?php } ?>
								</div>
							</form>


						</div>

						<?php
						$default_variation = new WC_Product_Variation($variations[0]['variation_id']);
						$default_price = $default_variation->get_regular_price();
						?>
						<!-- <span class="price">Rs <?php echo $default_price; ?></span> -->
						<div class="col-md-6 mb-30 d-flex align-items-center">
							<span class="ld b-font mr-3">Price</span>
							<span class="price md">₹ <?php echo number_format($default_price); ?></span>
						</div>
						<div class="col-md-6 mb-30 d-flex align-items-center">
							<span class="ld b-font mr-3">Quantity</span>
							<div class="count">
								<span class="minus box">-</span>
								<input class="box domesticbox prd_qty" type="text" name="" value="1" readonly>
								<span class="plus box active">+</span>
							</div>
						</div>
					</div>

					<a href="javascript:void(0);" class="button mb tb add_to_cart_btn add-cart" data-val="domestic">Add to Cart</a>
					<input type="hidden" id="add_to_cart_url" name="add_to_cart_url" value="<?php echo get_bloginfo('template_url'); ?>/ajax/ajax_add_cart.php" />
					<input type="hidden" name="add-to-cart" value="<?php echo absint($product->id); ?>" />
					<input type="hidden" id="product_id" name="product_id" value="<?php echo absint($product->id); ?>" />
					<input type="hidden" id="product_type" name="product_type" value="domestic" />
					<input type="hidden" id="variation_id" name="variation_id" class="variation_id" value="0" />
				<?php } ?>
			</div>
		</div>
		<?php
		$product_type = WC()->session->get('checkout_type');
		// echo $product_type;
		if ($product_type == 'global') { ?>
			<div class="col-xl-3 col-xl-28 desk-card px-xxl-4 mini_cart_box">
				<div class="cart-blk ml-auto mini_add_to_cart" style="max-width:initial" data-cart-type="global">
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
								$last_row_added = false;
								$free_product_id = 0;
								$free_variation_id = 0;
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
									// $product_image_url = get_the_post_thumbnail_url($product->get_id(), 'thumbnail');
									$product_image_url = get_the_post_thumbnail_url($product_id, 'thumbnail');
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
									<?php if ($product_id == 725) {
										$has_last_row = true;
										$free_product_id = $product_id;
										$free_variation_id = $variation_id;
										$free_product_name = $pr_name;
										$free_product_total = $line_subtotal;
										$free_product_image = $product_image_url;
										continue;
									?>
									<?php 	} else { ?>
										<div class="cart-row cartremove">
											<!-- <div class="d-flex align-items-start justify-content-between"> -->
											<div class="d-flex">
												<div class="sm-icon"><img src="<?php echo $product_image_url; ?>"></div>
												<div class="flex-grow-1">
													<div class="d-flex justify-content-between">

														<span class="md px-3"><?php echo $pr_name; ?></span>
														<span class="md text-right" id="cart_price<?php echo $variation_id; ?>" style="flex:0 0 58px"><?php echo wc_price($line_subtotal); ?></span>
													</div>
													<span class="sm mb-3 d-inline-block px-3">(<?php echo $var_weight; ?> gms)</span>
													<div class="d-flex justify-content-between">
														<div class="d-flex align-items-center px-3">
															<div class="count">
																<span class="box cart-global-qty-minus" data-key="<?php echo $cart_item_key; ?>" data-qty="<?php echo $quantity; ?>" data-variation-id="<?php echo $variation_id; ?>" data-min="<?php echo $minqty; ?>" data-max="<?php echo $maxqty; ?>">-</span>
																<input style="display:none" onchange="quantityonchange(<?php echo $product_id; ?>, this)" id="qty" prod-id="<?php echo $product_id; ?>" type="text" class="textbox-small" name="cart[<?php echo $cart_item_key; ?>][qty]" value="<?php echo $quantity; ?>" />
																<input id="global_qty<?php echo $variation_id; ?>" name="qty" type="text" readonly class="box qty" value="<?php echo $quantity; ?>" />
																<span class="box cart-global-qty-plus active" data-key="<?php echo $cart_item_key; ?>" data-qty="<?php echo $quantity; ?>" data-variation-id="<?php echo $variation_id; ?>" data-min="<?php echo $minqty; ?>" data-max="<?php echo $maxqty; ?>">+</span>
															</div>
															<a href="javascript:void(0);" class="trash-icon ml-5 mini_cart_product_remove " data-product-id="<?php echo $product_id; ?>" data-variation-id="<?php echo $variation_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
														</div>
													</div>

												</div>
											</div>
											<!-- </div> -->
										</div>

										<!-- <div class="mb-20 cartremove cart-row">
											<div class="d-flex justify-content-between align-items-center">
												<span class="md"><?php echo $pr_name; ?></span>
												<a href="javascript:void(0);" class="circle-icon mini_cart_product_remove type2" data-product-id="<?php echo $product_id; ?>" data-variation-id="<?php echo $variation_id; ?>"><i class="las la-times"></i></a>
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
										</div> -->
									<?php } ?>
								<?php } ?>
								<?php if ($has_last_row) {
								?>
									<div class="cart-row">
										<!-- <div class="d-flex align-items-start justify-content-between"> -->
										<div class="d-flex">
											<div class="sm-icon"><img src="<?php echo $free_product_image; ?>"></div>
											<div class="flex-grow-1">
												<div class="d-flex justify-content-between">
													<span class="md px-3"><?php echo $free_product_name; ?></span>
													<span class="md text-right" style="flex:0 0 58px"><?php echo wc_price($free_product_total); ?></span>
												</div>
												<span class="sm mb-3 d-inline-block px-3">Free Gift</span>
												<div class="d-flex justify-content-between">
													<div class="d-flex align-items-center px-3">
														<div class="count">
															<span class="box">-</span>
															<input class="box" type="text" name="" value="1" readonly>
															<span class="box">+</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!--  <a href="javascript:void(0);" class="circle-icon mini_cart_product_remove type2" data-product-id="<?php echo $free_product_id; ?>" data-variation-id="<?php echo $free_variation_id; ?>"><i class="las la-times"></i></a> -->
										<!-- </div> -->
									</div>
									<!-- <div class="mb-20 cartremove cart-row">
										<div class="d-flex justify-content-between align-items-center">
											<span class="md"><?php echo $free_product_name; ?></span>
											<a href="javascript:void(0);" class="circle-icon mini_cart_product_remove type2" data-product-id="<?php echo $free_product_id; ?>" data-variation-id="<?php echo $free_variation_id; ?>"><i class="las la-times"></i></a>
										</div>
										<span class="md pt-2 pb-3 ash-color">Free Gift</span>
										<div class="d-flex justify-content-between align-items-center">
											<div class="count">
												<span class="box">-</span>
												<input class="box" type="text" name="" value="1" readonly>
												<span class="box">+</span>
											</div>
											<span class="md"><?php echo wc_price($free_product_total); ?></span>
										</div>
									</div> -->
								<?php
								}
								?>
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
							<!-- <?php if (empty(WC()->cart->get_coupons())) { ?>
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
									} ?> -->
						</form>
						<div class="mb-30"></div>
						<!-- <div class="d-flex justify-content-between align-items-center mb-30">
							<span class="md">GST within Karnataka SGST 2.5% CGST 2.5% and GST outside Karnataka 5%.</span>
						</div> -->
						<?php
						$cc = WC()->customer->get_shipping_country();
						$shippingCharge = '₹' . get_option($cc . '_five') . '.00';
						$subtotal = WC()->cart->get_subtotal();
						$shipping_total = WC()->cart->get_shipping_total();
						$total_with_shipping = $subtotal + $shipping_total;
						// echo $subtotal . "sub_total";
						// echo $shipping_total . "shi_charge";
						// echo $total_with_shipping . "total";
						?>
						<div class="d-flex justify-content-between align-items-center mb-30">
							<span class="md">Shipping:</span>
							<span class="md" id="check-pr"><?php echo $shippingCharge; ?></span>
						</div>
						<div class="d-flex justify-content-between align-items-center">
							<span class="md b-font">Total</span>
							<span class="md b-font" id="cart_total"><?php wc_cart_totals_order_total_html(); ?></span>
							<!-- <span class="md b-font" id="cart_total"><?php echo wc_price($total_with_shipping); ?></span> -->
						</div>
						<span class="sm b-font ash-color">(Inclusive of all taxes)</span>
						<div class="mb-50"></div>
						<div class="d-flex justify-content-between align-items-center">
							<a href="<?php echo get_bloginfo('url'); ?>/global" class="link-anim md">Continue Shopping</a>
							<a href="<?php echo get_bloginfo('url'); ?>/checkout" class="button mb">Checkout</a>
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
			</div>
		<?php } else { ?>
			<div class="col-xl-3 col-xl-28 desk-card px-xxl-4 mini_cart_box">
				<div class="cart-blk ml-auto mini_add_to_cart" data-cart-type="domestic" style="max-width:initial">
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
								$last_row_added = false;
								$free_product_id = 0;
								$free_variation_id = 0;
								foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
									// get the data of the cart item
									$product_id = $cart_item['product_id'];
									$variation_id = $cart_item['variation_id'];
									// gets the cart item quantity
									$quantity = $cart_item['quantity'];
									$variation_product = new WC_Product_Variation($variation_id);
									$var_weight = $variation_product->weight;
									if ($var_weight >= 1000) {
										$var_weight_display = ($var_weight / 1000) . ' kg';
									} else {
										$var_weight_display = $var_weight . ' gms';
									}
									// gets the cart item subtotal
									$line_subtotal = $cart_item['line_subtotal'];
									$line_subtotal_tax = $cart_item['line_subtotal_tax'];

									// unit price of the product
									$item_price = $line_subtotal / $quantity;
									$item_tax = $line_subtotal_tax / $quantity;

									// gets the product object
									$product = $cart_item['data'];
									$product_image_url = get_the_post_thumbnail_url($product_id, 'thumbnail');
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
									<?php if ($product_id == 725) {
										$has_last_row = true;
										$free_product_id = $product_id;
										$free_variation_id = $variation_id;
										$free_product_name = $pr_name;
										$free_product_total = $line_subtotal;
										$free_product_image = $product_image_url;
										continue;
									?>

									<?php 	} else { ?>
										<div class="cartremove cart-row">
											<!-- <div class="d-flex align-items-start justify-content-between"> -->
											<div class="d-flex">
												<div class="sm-icon"><img src="<?php echo $product_image_url; ?>"></div>
												<div class="flex-grow-1">
													<div class="d-flex justify-content-between">
														<span class="md px-3"><?php echo $pr_name; ?></span>
														<span class="md text-right" id="cart_price<?php echo $variation_id; ?>" style="flex:0 0 58px"><?php echo wc_price($line_subtotal); ?></span>
													</div>
													<span class="sm mb-3 d-inline-block px-3">(<?php echo $var_weight_display; ?>)</span>
													<div class="d-flex justify-content-between">
														<div class="d-flex align-items-center px-3">
															<div class="count">
																<span class="box cart-qty-minus" data-key="<?php echo $cart_item_key; ?>" data-qty="<?php echo $quantity; ?>" data-variation-id="<?php echo $variation_id; ?>">-</span>
																<input style="display:none" onchange="quantityonchange(<?php echo $product_id; ?>, this)" id="qty" prod-id="<?php echo $product_id; ?>" type="text" class="textbox-small" name="cart[<?php echo $cart_item_key; ?>][qty]" value="<?php echo $quantity; ?>" />
																<input id="dummyQty<?php echo $variation_id; ?>" name="qty" type="text" readonly class="box qty" value="<?php echo $quantity; ?>" />
																<span class="box cart-qty-plus active" data-key="<?php echo $cart_item_key; ?>" data-qty="<?php echo $quantity; ?>" data-variation-id="<?php echo $variation_id; ?>">+</span>
															</div>
															<a href="javascript:void(0);" class="trash-icon ml-5 mini_cart_product_remove" data-product-id="<?php echo $product_id; ?>" data-variation-id="<?php echo $variation_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
														</div>
													</div>
												</div>
											</div>
											<!-- </div> -->
										</div>
										<!-- <div class="mb-20 cartremove cart-row">
											<div class="d-flex justify-content-between align-items-center">
												<span class="md"><?php echo $pr_name; ?></span>
												<a href="javascript:void(0);" class="circle-icon mini_cart_product_remove type2" data-product-id="<?php echo $product_id; ?>" data-variation-id="<?php echo $variation_id; ?>"><i class="las la-times"></i></a>
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

										</div> -->
									<?php } ?>
								<?php } ?>
								<?php if ($has_last_row) {
								?>
									<div class="cart-row">
										<!-- <div class="d-flex align-items-start justify-content-between"> -->
										<div class="d-flex">
											<div class="sm-icon"><img src="<?php echo $free_product_image; ?>"></div>
											<div class="flex-grow-1">
												<div class="d-flex justify-content-between">
													<span class="md px-3"><?php echo $free_product_name; ?></span>
													<span class="md text-right" style="flex:0 0 58px"><?php echo wc_price($free_product_total); ?></span>
												</div>
												<span class="sm mb-3 d-inline-block px-3">Free Gift</span>
												<div class="d-flex justify-content-between">
													<div class="d-flex align-items-center px-3">
														<div class="count">
															<span class="box">-</span>
															<input class="box" type="text" name="" value="1" readonly>
															<span class="box">+</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- <a href="javascript:void(0);" class="circle-icon mini_cart_product_remove type2" data-product-id="<?php echo $free_product_id; ?>" data-variation-id="<?php echo $free_variation_id; ?>"><i class="las la-times"></i></a>										</div> -->
										<!-- </div> -->
									</div>

								<?php
								}
								?>
							</div>
						</div>
						<div class="mb-30"></div>
						<?php
						//if (is_user_logged_in() && has_bought(get_current_user_id())) {

						?>


						<!-- <form>
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
										<div class="mb-30"></div> -->
						<?php //} 
						?>
						<!-- <div class="d-flex justify-content-between align-items-center mb-30">
							<span class="md">GST within Karnataka SGST 2.5% CGST 2.5% and GST outside Karnataka 5%.</span>
						</div> -->
						<?php
						$cc = "IN";
						$shippingCharge = "Free";  ?>
						<div class="d-flex justify-content-between align-items-center mb-30">
							<span class="md">Shipping:</span>
							<span class="md"><?php echo $shippingCharge; ?></span>
						</div>
						<?php
						// $cart_total = WC()->cart->get_subtotal();
						$cart_total = WC()->cart->get_cart_contents_total();
						// Display the cart total
						// echo 'Cart Total: ' . wc_price($cart_total);
						?>
						<?php
						// $cart_total = WC()->cart->get_total('edit');  
						?>
						<div class="d-flex justify-content-between align-items-center">
							<span class="md b-font">Total</span>
							<span class="md b-font without_ship_remove_tot" id="cart_total"><?php echo wc_price($cart_total); ?></span>
						</div>
						<span class="sm b-font ash-color">(Inclusive of all taxes)</span>
						<div class="mb-50"></div>
						<?php
						$previous_url =	$_SERVER['HTTP_REFERER'];
						if (strpos($previous_url, 'home-use') !== false) {
							$redirect_url = get_bloginfo('url') . '/home-use';
						} else {
							$redirect_url = get_bloginfo('url') . '/industrial-use';
						}
						?>
						<div class="d-flex justify-content-between align-items-center">
							<a href="<?php echo $redirect_url; ?>" class="link-anim md">Continue Shopping</a>
							<a href="<?php echo get_bloginfo('url'); ?>/checkout" class="button mb">Checkout</a>
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
			</div>
		<?php } ?>
	</div>
</div>
<!-- product detail end -->
<!-- popup box1 start -->
<div class="popup-box1-blk cart_popup">
	<div class="bg-layer1 popup_outside_click"></div>
	<div class="popup-box1">
		<a href="javascript:void(0);" class="popup1-close popup-close1 d-block text-right not_clear_cart"><i class="las la-times"></i></a>

		<div class="popup-box1-con text-center d-flex justify-content-center align-items-center">
			<div>
				<h5>Alert Message</h5>
				<p>Domestic cart clear is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
				<a href="javascript:void(0);" class="button mb mt-3 popup1-close clear_cart">Clear All</a>
			</div>
		</div>
	</div>
</div>
<div class="popup-box1-blk cart_popup_global">
	<div class="bg-layer1 popup_outside_click"></div>
	<div class="popup-box1">
		<a href="javascript:void(0);" class="popup1-close popup-close1 d-block text-right not_clear_cart"><i class="las la-times"></i></a>

		<div class="popup-box1-con text-center d-flex justify-content-center align-items-center">
			<div>
				<h5>Alert Message</h5>
				<p>global cart clear is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
				<a href="javascript:void(0);" class="button mb mt-3 popup1-close clear_cart">Clear All</a>
			</div>
		</div>
	</div>
</div>
<!-- popup box1 end -->
<!-- popup massege start -->
<?php
$cart_count = WC()->cart->get_cart_contents_count();
$product_type = WC()->session->get('checkout_type');
// echo $product_type;
if ($product_type == 'global') {
	$total = WC()->cart->cart_contents_total + WC()->cart->shipping_total;
} else {
	$total = WC()->cart->cart_contents_total;
}
 
$is_in_cart = false;
foreach (WC()->cart->get_cart() as $cart_item) { 
	if ($cart_item['product_id'] == $productid) {
		$is_in_cart = true;
		break;
	}
}
// if ($is_in_cart) {
//     echo "This product is already in the cart.";
// } else {
//     echo "This product is not in the cart.";
// }
if ($is_in_cart) { ?>
	<div class="popup-card py-3 mob-card 1">
		<div class="container">
			<div class="d-flex justify-content-between align-items-center">
				<div style="position: relative;flex: 0 0 120px;">
					<?php if ($cart_count == 1) { ?>
						<span class="ld"><?php echo $cart_count; ?> item | <span>&#8377</span><?php echo $total; ?></span>
					<?php } else { ?>
						<span class="ld"><?php echo $cart_count; ?> items | <span>&#8377</span><?php echo $total; ?></span>
					<?php } ?>
				</div>
				<div class="cart-btn"> 
					<a href="<?php echo get_bloginfo('url'); ?>/checkout" class="button mb view-cart-btn d-block">Checkout</a>
				</div>
			</div>
		</div>
	</div>
<?php } else { ?>
	<div class="popup-card py-3 mob-card 1">
		<div class="container">
			<div class="d-flex justify-content-center align-items-center">
				<div class="cart-btn">
					<a href="javascript:void(0);" class="button mb add-cart-btn add_to_cart_btn add-cart d-block">Add to Cart</a> 
				</div>
			</div>
		</div>
	</div>
<?php }
?>
<?php //if ($cart_count > 0) { 
?>
 <?php //} 
?>

<!-- popup massege end -->
<!-- banner start -->
<?php $url = $_SERVER['REQUEST_URI'];
// var_dump($url);
if (strpos($url, "global/") !== false) { ?>

<?php } else { ?>
	<?php if (have_rows('product')) :  ?>
		<?php while (have_rows('product')) : the_row(); ?>
			<?php if (get_row_layout() == 'banner') :
				$content = get_sub_field("content");
				$primary_cta = get_sub_field("primary_cta");
				$Link = !empty(get_sub_field("primary_cta_link")['url']) ? get_sub_field("primary_cta_link")['url'] : "javascript:void(0):";
				$targetLink = !empty(get_sub_field("primary_cta_link")['target']) ? get_sub_field("primary_cta_link")['target'] : "_self";
				$global_image = isset(get_sub_field("global_image")['url']) ? get_sub_field("global_image")['url'] : '';
				$global_imageAlt = !empty(get_sub_field("global_image")['alt']) ? get_sub_field('global_image')['alt'] : '';

			?>
				<div class="new-banner-blk" style="background-color : #404465;">
					<div class="container">
						<div class="row">
							<div class="col-xl-10">
								<div class="row align-items-center">
									<div class="col-lg-6 mb-40 mb-lg-0">
										<?php if ($global_image) { ?>
											<div>
												<img src="<?php echo $global_image; ?>" alt="<?php echo $global_imageAlt; ?>">
											</div>
										<?php } ?>
									</div>
									<div class="col-lg-6">
										<?php echo $content; ?>
										<?php if ($primary_cta) { ?>
											<div class="mb-30"></div>
											<a href="<?php echo $Link; ?>" target="<?php echo $targetLink; ?>" class="button mb-40"><?php echo $primary_cta; ?></a>
									</div>
								<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endwhile; ?>
	<?php endif; ?>
<?php } ?>

<!-- banner end -->
<!-- section start -->
<?php
global $product;
$product = new WC_Product_Variable($post->ID);
$cross_sell_ids = $product->get_cross_sell_ids();

if (!empty($cross_sell_ids)) { ?>
	<div class="white-bg pt-80">
		<div class="container">
			<div class="mb-30">
				<h5>Similar Products</h5>
			</div>
			<div class="row">
				<div class="col-xl-11">
					<div class="row cardsection mb-40 mb-md-0">
						<?php
						foreach ($cross_sell_ids as $cross_sell_id) {
							$cross_sell_product = wc_get_product($cross_sell_id);
							$cross_sell_price = $cross_sell_product->get_price();
							// var_dump($cross_sell_product);
						?>
							<a href="<?php echo get_permalink($cross_sell_id); ?>" class="col-6 col-lg-3">
								<div class="cards-blk">
									<div class="cardsimg mb-4">
										<?php if (has_post_thumbnail($cross_sell_id)) {
											$image_url = wp_get_attachment_image_src(get_post_thumbnail_id($cross_sell_id), 'full');
										?>
											<img src="<?php echo $image_url[0]; ?>">
										<?php } ?>
									</div>
									<div>
										<h6><?php echo $cross_sell_product->get_name(); ?></h6>
										<div class="ash-color">
											<?php echo apply_filters('the_content', $cross_sell_product->get_short_description()); ?>
										</div>

										<p class="amnt"><?php echo wc_price($cross_sell_price); ?></p>
									</div>
								</div>
							</a>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!-- section end -->
<?php
global $product;

if (!$product) {
	return;
}

$terms = get_the_terms($product->get_id(), 'product_cat');
$category = '';
if (!empty($terms) && !is_wp_error($terms)) {
	foreach ($terms as $term) {
		$category .= $term->slug . ',';
	}
}

?>
<!-- view_item -->
<script>
	window.dataLayer = window.dataLayer || [];
	window.dataLayer.push({
		ecommerce: null
	});

	window.dataLayer.push({
		event: "view_item",
		ecommerce: {
			currency: "<?php echo get_woocommerce_currency(); ?>",
			// value: <?php echo $product->get_price(); ?>,
			items: [
				<?php
				// Check if the product has variations
				if ($product->is_type('variable')) {
					// Loop through variations
					foreach ($product->get_available_variations() as $variation) {
						$variation_product = wc_get_product($variation['variation_id']);

				?> {
							item_id: "<?php echo $variation['variation_id']; ?>",
							item_name: "<?php echo $variation_product->get_name(); ?>",
							item_brand: "SSP",
							item_category: "<?php echo rtrim($category, ','); ?>",
							item_variant: "<?php echo $variation_product->get_sku(); ?>",
							price: <?php echo $variation_product->get_price(); ?>,
							quantity: 1
						},
				<?php
				 break;
					}
				}
				?>

			]
		}
	});
</script>
<!-- Add to cart -->
 <script>
jQuery(document).ready(function($) {
    $('.add_to_cart_btn').click(function(event) {
        event.preventDefault(); 
        sessionStorage.setItem('addToCartClicked', 'true'); 
        // sendAddtoCartDataToDataLayer();
    }); 
    if (sessionStorage.getItem('addToCartClicked') === 'true') { 
        sessionStorage.removeItem('addToCartClicked'); 
        sendAddtoCartDataToDataLayer();
    }
});
 </script>

<?php
global $woocommerce;
global $product;

// Clear the previous ecommerce object
echo '<script>';
echo 'window.dataLayer = window.dataLayer || [];';
echo 'window.dataLayer.push({ ecommerce: null });';
echo '</script>';

// Fetch cart items
$cart_items = $woocommerce->cart->get_cart();
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
		'item_category' =>  rtrim($category_cart, ','),
		'item_variant' => $_product->get_sku(),
		'price' => $_product->get_price(),
		'quantity' => $cart_item['quantity']
	);

	// Add item data to items array
	$items[] = $item_data;
}
$cart_count = WC()->cart->get_cart_contents_count();
$product_type = WC()->session->get('checkout_type');
if ($product_type == 'global') {
	$ecom_cart_tot = WC()->cart->cart_contents_total + WC()->cart->shipping_total;
} else {
	$ecom_cart_tot = WC()->cart->cart_contents_total;
}

// Prepare the add_to_cart event with cart items
echo '<script>';
echo 'function sendAddtoCartDataToDataLayer () {';
echo 'window.dataLayer = window.dataLayer || [];';
echo 'window.dataLayer.push({';
echo 'event: "add_to_cart",';
echo 'ecommerce: {';
echo 'currency: "' . get_woocommerce_currency() . '",';
echo 'value: "' . ceil($ecom_cart_tot) . '",';
echo 'items: ' . json_encode($items);
echo '}';
echo '});';
echo '}';

echo '</script>';
?>

<?php
//echo do_shortcode( '[cusrev_all_reviews]' ); 
?>
<?php
// echo do_shortcode( '[cusrev_reviews_slider]' ); 
?>
<?php // echo do_shortcode('[cusrev_reviews_grid]'); 
?>
<?php
// echo do_shortcode( '[cusrev_qna]' ); 
?>
<?php
//echo do_shortcode( '[cusrev_reviews]' ); 
?>

<?php
get_footer();
?>