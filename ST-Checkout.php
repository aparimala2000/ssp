<?php

/**********
Template Name:Checkout
 **********/
global $post;
if (is_wc_endpoint_url('order-received')) {
	get_header('sub');
	?>
	<?php echo apply_filters('the_content', wpautop($post->post_content)); ?>

	<?php get_footer(); ?>
	<?php
} else {
	get_header('sub');
	?>
	<div class="container">
		<div class="mid-container">
			<?php echo apply_filters('the_content', $post->post_content); ?>
			
			<!-- Recently viewed start -->
			<!-- <div style="border-top: 1px solid #F8F2F2; padding-top: 56px;">
				<section>
					<div>
						<?php $rvp_ids = do_shortcode('[recently_viewed_products]');
						$str_arr = explode(",", $rvp_ids);
						if ($str_arr[0] !== "") : ?>
						<h5>recently viewed</h5>
						<div class="row cardsection mb-40 mb-md-0">
							<?php
							$i = 0;
							foreach (array_reverse($str_arr) as $rvp_id) {
								$productname = get_the_title($rvp_id);
								$productdesc = get_the_excerpt($rvp_id);
								$productprice = $post->_regular_price;
								$productsaleprice = $post->_sale_price;
								$price = ($productsaleprice == '' ? $productprice : $productsaleprice);
								$image_url = get_the_post_thumbnail_url($rvp_id, 'full');
								?>
								<a href="<?php echo get_permalink($rvp_id); ?>" class="col-6 col-md-3">
									<div class="cards-blk">
										<div class="cardsimg mb-4">
											<img src="<?php echo $image_url; ?>">
										</div> 
										<div>
											<h6 class="mb-1"><?php echo $productname; ?></h6>
											<div class="ash-color">
												<p><?php echo $productdesc; ?></p>
											</div>
											
											<?php
											$var = new WC_Product_Variable($rvp_id);
											$variations = $var->get_available_variations();
											$variation_product_id = $variations[0]['variation_id'];
											if (count($variations) != 0) {
												$variation_product = new WC_Product_Variation($variation_product_id);
												$var_reg_prc = $variation_product->regular_price;
												$var_sal_prc = $variation_product->sale_price;
												$var_weight = $variation_product->weight;
												?>
												<p class="amnt"><?php if ($var_sal_prc != '') { ?>&nbsp;<?php echo wc_price($var_sal_prc); ?><?php } else { ?> <?php echo wc_price($var_reg_prc);
												} ?> (<?php echo $var_weight; ?> gms)</p>
												<?php } ?>
											</div>
										</div>
									</a>
									<?php $i++;
									if ($i == 4) break;
								} ?>
							</div>
						<?php endif; ?>
					</div>
				</section>
			</div> -->
			<!-- Recently viewed end -->
		</div>
	</div>
	<!-- Footer start -->
	<?php get_footer(); } ?>