<?php

/**********
Template Name: Cart Page
 **********/
get_header('sub');
$customer_id = get_current_user_id();
session_start();
$cartSession = WC()->session->get('checkout_type');
if (isset($cartSession)) {
    $cartType = $cartSession;
}
// echo $cartType;
global $woocommerce;
$countries = new WC_Countries();
$cc = WC()->customer->get_shipping_country();
$allCountries = $countries->get_shipping_countries();
if ($cartType == "domestic") {
    $ex = 'disabled';
    $shippingCharge = "FREE";
} else {
    $shippingCharge = "₹" . get_option($cc . '_five') . ".00";
}
?>
<div class="container">
    <div class="mb-60">
        <div class="row mb-4">
            <div class="col-xl-5">
                <h3>My cart</h3>
                <?php
                $Introcopy = get_field('introcopy');
                echo $Introcopy;
                ?>
            </div>
        </div>
        <div class="row mb-60" id="cart-temp">
            <div class="col-xl-8 mb-50 mb-xl-0">
                <div class="cart-table-blk">
                    <div id="woocart">
                        <?php echo apply_filters('the_content', wpautop($post->post_content)); ?>
                    </div>
                </div>
            </div>
            <?php if (count(WC()->cart->get_cart()) != 0) :
            do_action('woocommerce_cart_collaterals');
            endif;
            ?>
        </div>
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
                <a href="<?php echo get_permalink($rvp_id); ?>" class="col-6 col-lg-3">
                    <div class="cards-blk">
                        <div class="cardsimg mb-4">
                            <img src="<?php echo $image_url; ?>">
                        </div>
                        <div>
                            <h6><?php echo $productname; ?></h6>
                            <div class="ash-color">
                                <?php echo apply_filters('the_content', $productdesc); ?>
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
                                <p class="amnt">From<?php if ($var_sal_prc != '') { ?>&nbsp;<?php echo wc_price($var_sal_prc); ?><?php } else { ?> <?php echo wc_price($var_reg_prc);
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
        <!-- section end -->
    </div>
</div>
<?php get_footer(); ?>