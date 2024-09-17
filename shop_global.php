<?php

/**********
Template Name: Shop Global use

 **********/
get_header('shop');

?>
<!-- banner section start -->
<?php if ($post = get_page_by_path('global', OBJECT, 'page')) { ?>
    <?php if (have_rows('home_use')) : the_row(); ?>
        <?php if (get_row_layout() == 'banner') :
            $bannerContent = get_sub_field("banner_content");
            $backgroundImage = get_sub_field("background_image")['url'];
            $backgroundImageAlt = !empty(get_sub_field("background_image")['alt']) ? get_sub_field('background_image')['alt'] : get_sub_field('background_image')['name'];
            $Image = get_sub_field("image")['url'];
            $imageAlt = !empty(get_sub_field("image")['alt']) ? get_sub_field('image')['alt'] : get_sub_field('image')['name'];
        ?>
            <div class="container">
                <div class="mid-container">
                    <div class="banner-blk v2 d-md-flex align-items-md-center justify-content-between">
                        <div class="mb-30 mb-md-0 mr-md-5 banner-blk-content">
                            <?php echo $bannerContent; ?>
                        </div>
                        <div class="banner-blk-img text-right">
                            <div class="img-blk1">
                                <img src="<?php echo $Image; ?>" alt="<?php echo $imageAlt; ?>">
                            </div>
                            <img src="<?php echo $backgroundImage; ?>" alt="<?php echo $backgroundImageAlt; ?>">
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php } ?>
<!-- banner section end -->

<?php
$args = array(
    'post_type' => 'product',
    'product_cat' => 'global',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'product_shipping_class',
            'operator' => 'EXISTS',
        ),
    ),
);
$products = new WP_Query($args);

if ($products->have_posts()) {
    $displayed_products = array(); // Initialize an empty array to keep track of displayed products
?>
    <div class="white-bg pt-80">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="mb-30">For Global</h5>
                    <div class="row cardsection mb-20 mb-md-0">
                        <?php
                        while ($products->have_posts()) {
                            $products->the_post();
                            $product_id = get_the_ID(); // Get the ID of the current product
                            if (in_array($product_id, $displayed_products)) {
                                // If the product has already been displayed, skip to the next product
                                continue;
                            }
                            $product_short_description = get_the_excerpt();
                            $product_url = get_permalink();
                            $product_type_val = get_post_meta($product_id, 'product_type_fetch', true);

                            $product = wc_get_product($product_id);
                            if ($product->is_type('variable') && $product->get_shipping_class()) {
                                $available_variations = $product->get_available_variations();
                                $variation_found = false;
                                foreach ($available_variations as $variation) {
                                    $min_qty = get_post_meta($variation['variation_id'], '_min_qty_', true);
                                    $max_qty = get_post_meta($variation['variation_id'], '_max_qty_', true);
                                    if ($min_qty && $max_qty && !$variation_found) {
                                        $variation_found = true;
                                        $weight = $variation['weight'];
                                        $price = $variation['display_price'];
                        ?>
                                        <a href="<?php echo $product_url; ?>" class="col-6 col-md-4 col-xl-3">
                                            <div class="cards-blk">
                                                <div class="cardsimg mb-4">
                                                    <?php if (has_post_thumbnail()) : ?>
                                                        <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
                                                        <img src="<?php echo $image[0]; ?>" alt="<?php the_title_attribute(); ?>">
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <!-- <h6><?php echo the_title(); ?> (<?php echo $weight; ?> gms) </h6> -->
                                                    <h6><?php echo the_title(); ?></h6>
                                                    <div class="ash-color mb-1">
                                                        <?php echo apply_filters('the_content', $product_short_description); ?>
                                                    </div>
                                                    <p class="ash-color"><?php echo $weight; ?>gms <?php if (!empty($product_type_val)) { ?> / <?php echo $product_type_val; ?> <?php } ?>
                                                    <!-- <p class="amnt"><?php echo wc_price($price); ?> (<?php echo $weight; ?> gms)</p> -->
                                                    <?php
                                                    $total_value = $min_qty * $price;
                                                    // echo '<p>Total Value: ' . wc_price($total_value) . '</p>';
                                                    ?>
                                                    <p class="amnt"><?php echo wc_price($total_value); ?> (Pack of <?php echo $min_qty; ?>)</p>
                                                </div>
                                            </div>
                                        </a>
                                <?php
                                    }
                                }

                                ?>
                        <?php
                                $displayed_products[] = $product_id; // Add the product ID to the displayed products array
                            }
                        }

                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<?php get_footer(); ?>