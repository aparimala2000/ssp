<?php

/**********
Template Name: Shop Home use

 **********/
get_header('shop');

?>
<!-- banner section start -->
<?php if ($post = get_page_by_path('home-use', OBJECT, 'page')) { ?>
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

<!-- fillter section start -->
<?php

$category_slug = 'home-use'; // replace with the slug of the category you want to retrieve 

$category = get_term_by('slug', $category_slug, 'product_cat');

if ($category) {
?>
    <!-- filter start -->
    <div class="white-bg pt-80">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- filter start -->
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mob-list-blk">
                                <a href="javascript:void(0);" class="mob-list-btn active">
                                    <h5 class="b-font mr-3 border-0">Filter by type</h5>
                                    <span class="down-arrow"><i class="las la-angle-up"></i></span>
                                </a>
                                <?php
                                $subcategories = get_terms(array(
                                    'taxonomy' => 'product_cat',
                                    'hide_empty' => false,
                                    'parent' => $category->term_id
                                ));
                                if ($subcategories) { ?>
                                    <form class="mob-list">
                                        <ul>
                                            <?php foreach ($subcategories as $subcategory) {   ?>
                                                <li>
                                                    <div class="checkbox-animate">
                                                        <label>
                                                            <input type="checkbox" name="subcategory" value="<?php echo $subcategory->slug; ?>" <?php if (isset($_GET['subcategory']) && in_array($subcategory->slug, $_GET['subcategory'])) {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?>>
                                                            <span class="tick-anim"></span>
                                                            <?php echo $subcategory->name; ?>
                                                        </label>
                                                    </div>
                                                </li>
                                            <?php  } ?>

                                        </ul>
                                    </form>
                                <?php  } ?>

                            </div>
                        </div>
                        <div class="col-lg-9">
                            <!-- <h5 class="mb-30 mt-4 mt-lg-0">For <?php echo $category->name; ?></h5> -->
                            <h5 class="mb-30 mt-4 mt-lg-0">For Home</h5>
                            <div class="row cardsection filter mb-20 mb-md-0">
                                <?php
                                // Query products in the home category
                                $args = array(
                                    'post_type' => 'product',
                                    'orderby' => 'menu_order',
                                    'order' => 'ASC',
                                    'posts_per_page' => -1,
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'product_cat',
                                            'field' => 'term_id',
                                            'terms' => $category->term_id,
                                        ),
                                    ),
                                );

                                if (isset($_GET['subcategory'])) {
                                    $args['tax_query'][] = array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'slug',
                                        'terms' => $_GET['subcategory'],
                                    );
                                }
                                $products = new WP_Query($args);
                                //var_dump($products->posts);
                                if ($products->have_posts()) :
                                    while ($products->have_posts()) : $products->the_post();
                                        $product_short_description = get_the_excerpt();
                                        $weight = apply_filters('woocommerce_product_get_weight', get_post_meta(get_the_ID(), '_weight', true), get_the_ID());
                                        $product_url = get_permalink();
                                        $product_id = get_the_ID();
                                        $product_type_val = get_post_meta($product_id, 'product_type_fetch', true);
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
                                                    <h6><?php the_title(); ?></h6>
                                                    <div class="ash-color mb-1">
                                                        <?php echo apply_filters('the_content', $product_short_description); ?>
                                                    </div>

                                                    <?php if ($product->is_type('variable')) {
                                                        $available_variations = $product->get_available_variations();
                                                        if (count($available_variations) > 0) {
                                                            // Get weight and price of the first variation
                                                            $first_variation = $available_variations[0];
                                                            $variation_weight = $first_variation['weight'];
                                                            $variation_price = $first_variation['display_price'];
                                                        }
                                                        // Display weight and price
                                                        //echo wc_price($variation_price) . ' (' . $variation_weight . ' gms)'; 
                                                    ?>
                                                    <p class="ash-color"><?php echo $variation_weight; ?>gms <?php if (!empty($product_type_val)) { ?> / <?php echo $product_type_val; ?> <?php } ?>
                                                        <p class="amnt"><?php echo wc_price($variation_price); ?></p>

                                                    <?php
                                                    } 
                                                    ?>

                                                </div>
                                            </div>
                                        </a>
                                    <?php
                                    endwhile;
                                    wp_reset_postdata();
                                else :
                                    ?>
                                    <p><?php _e('No products found'); ?></p>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- fillter section end -->
   <div class="white-bg pt-80">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- filter start -->
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mob-list-blk">
                                <a href="javascript:void(0);" class="mob-list-btn active">
                                    <h5 class="b-font mr-3 border-0">Filter by type</h5>
                                    <span class="down-arrow"><i class="las la-angle-up"></i></span>
                                </a>
                                <?php
                                $subcategories = get_terms(array(
                                    'taxonomy' => 'product_cat',
                                    'hide_empty' => false,
                                    'parent' => $category->term_id
                                ));
                                if ($subcategories) { ?>
                                    <form class="mob-list">
                                        <ul>
                                            <?php foreach ($subcategories as $subcategory) {   ?>
                                                <li>
                                                    <div class="checkbox-animate">
                                                        <label>
                                                            <input type="checkbox" name="subcategory" value="<?php echo $subcategory->slug; ?>" <?php if (isset($_GET['subcategory']) && in_array($subcategory->slug, $_GET['subcategory'])) {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?>>
                                                            <span class="tick-anim"></span>
                                                            <?php echo $subcategory->name; ?>
                                                        </label>
                                                    </div>
                                                </li>
                                            <?php  } ?>

                                        </ul>
                                    </form>
                                <?php  } ?>

                            </div>
                        </div>
                        <div class="col-lg-9">
                            <!-- <h5 class="mb-30 mt-4 mt-lg-0">For <?php echo $category->name; ?></h5> -->
                            <h5 class="mb-30 mt-4 mt-lg-0">For Home</h5>
                            <div class="row cardsection filter mb-20 mb-md-0">
                                <?php
                                // Query products in the home category
                                $args = array(
                                    'post_type' => 'product',
                                    'orderby' => 'menu_order',
                                    'order' => 'ASC',
                                    'posts_per_page' => -1,
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'product_cat',
                                            'field' => 'term_id',
                                            'terms' => $category->term_id,
                                        ),
                                    ),
                                );

                                if (isset($_GET['subcategory'])) {
                                    $args['tax_query'][] = array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'slug',
                                        'terms' => $_GET['subcategory'],
                                    );
                                }
                                $products = new WP_Query($args);
                                //var_dump($products->posts);
                                if ($products->have_posts()) :
                                    while ($products->have_posts()) : $products->the_post();
                                        $product_short_description = get_the_excerpt();
                                        $weight = apply_filters('woocommerce_product_get_weight', get_post_meta(get_the_ID(), '_weight', true), get_the_ID());
                                        $product_url = get_permalink();
                                        $product_id = get_the_ID();
                                        $product_type_val = get_post_meta($product_id, 'product_type_fetch', true);
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
                                                    <h6><?php the_title(); ?></h6>
                                                    <div class="ash-color mb-1">
                                                        <?php echo apply_filters('the_content', $product_short_description); ?>
                                                    </div>

                                                    <?php if ($product->is_type('variable')) {
                                                        $available_variations = $product->get_available_variations();
                                                        if (count($available_variations) > 0) {
                                                            // Get weight and price of the first variation
                                                            $first_variation = $available_variations[0];
                                                            $variation_weight = $first_variation['weight'];
                                                            $variation_price = $first_variation['display_price'];
                                                        }
                                                        // Display weight and price
                                                        //echo wc_price($variation_price) . ' (' . $variation_weight . ' gms)'; 
                                                    ?>
                                                    <p class="ash-color"><?php echo $variation_weight; ?>gms <?php if (!empty($product_type_val)) { ?> / <?php echo $product_type_val; ?> <?php } ?>
                                                        <p class="amnt"><?php echo wc_price($variation_price); ?></p>

                                                    <?php
                                                    } 
                                                    ?>

                                                </div>
                                            </div>
                                        </a>
                                    <?php
                                    endwhile;
                                    wp_reset_postdata();
                                else :
                                    ?>
                                    <p><?php _e('No products found'); ?></p>
                                <?php endif; ?>

                            </div>

                            <h5 class="mb-30 mt-4 mt-lg-0">Offers</h5>
                            <div class="row cardsection filter mb-20 mb-md-0">
                                <?php
                                // Query products in the home category
                                $args = array(
                                    'post_type' => 'product',
                                    'orderby' => 'menu_order',
                                    'order' => 'ASC',
                                    'posts_per_page' => -1,
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'product_cat',
                                            'field' => 'term_id',
                                            'terms' => $category->term_id,
                                        ),
                                    ),
                                );

                                if (isset($_GET['subcategory'])) {
                                    $args['tax_query'][] = array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'slug',
                                        'terms' => $_GET['subcategory'],
                                    );
                                }
                                $products = new WP_Query($args);
                                //var_dump($products->posts);
                                if ($products->have_posts()) :
                                    while ($products->have_posts()) : $products->the_post();
                                        $product_short_description = get_the_excerpt();
                                        $weight = apply_filters('woocommerce_product_get_weight', get_post_meta(get_the_ID(), '_weight', true), get_the_ID());
                                        $product_url = get_permalink();
                                        $product_id = get_the_ID();
                                        $product_type_val = get_post_meta($product_id, 'product_type_fetch', true);
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
                                                    <h6><?php the_title(); ?></h6>
                                                    <div class="ash-color mb-1">
                                                        <?php echo apply_filters('the_content', $product_short_description); ?>
                                                    </div>

                                                    <?php if ($product->is_type('variable')) {
                                                        $available_variations = $product->get_available_variations();
                                                        if (count($available_variations) > 0) {
                                                            // Get weight and price of the first variation
                                                            $first_variation = $available_variations[0];
                                                            $variation_weight = $first_variation['weight'];
                                                            $variation_price = $first_variation['display_price'];
                                                        }
                                                        // Display weight and price
                                                        //echo wc_price($variation_price) . ' (' . $variation_weight . ' gms)'; 
                                                    ?>
                                                    <p class="ash-color"><?php echo $variation_weight; ?>gms <?php if (!empty($product_type_val)) { ?> / <?php echo $product_type_val; ?> <?php } ?>
                                                        <p class="amnt"><?php echo wc_price($variation_price); ?></p>

                                                    <?php
                                                    } 
                                                    ?>

                                                </div>
                                            </div>
                                        </a>
                                    <?php
                                    endwhile;
                                    wp_reset_postdata();
                                else :
                                    ?>
                                    <p><?php _e('No products found'); ?></p>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- banner start -->
<?php if ($post = get_page_by_path('home-use', OBJECT, 'page')) { ?>
    <?php if (have_rows('home_use')) : the_row(); ?>
        <?php if (get_row_layout() == 'sub_banner') :
            $content = get_sub_field("content");
            $primary_cta = get_sub_field("primary_cta");
            $Link = !empty(get_sub_field("primary_cta_link")['url']) ? get_sub_field("primary_cta_link")['url'] : "javascript:void(0):";
            $targetLink = !empty(get_sub_field("primary_cta_link")['target']) ? get_sub_field("primary_cta_link")['target'] : "_self";
            $global_image = isset(get_sub_field("global_image")['url']) ? get_sub_field("global_image")['url'] : '';
            $global_imageAlt = !empty(get_sub_field("global_image")['alt']) ? get_sub_field('global_image')['alt'] : '';

        ?>
            <!-- <div class="new-banner-blk" style="background-color : #404465;">
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
            </div> -->

        <?php endif; ?>
    <?php endif; ?>
<?php } ?>
<!-- banner end -->
<?php
$offer_slug = 'offers'; // Replace 'my-category-slug' with the slug of the category you want to fetch
$offer_category = get_term_by('slug', $offer_slug, 'product_cat');

if ($offer_category) {
?>
    <!-- <div class="white-bg pt-80">
        <div class="container">
            <div class="mb-30">
                <h5><?php echo $offer_category->name; ?></h5>
            </div>
            <div class="row">
                <div class="col-xl-11">
                    <div class="row cardsection mb-40 mb-md-0">
                        <?php
                        $args = array(
                            'post_type' => 'product',
                            'posts_per_page' => -1,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field' => 'term_id',
                                    'terms' => $offer_category->term_id,
                                    'operator' => 'IN'
                                )
                            )
                        );
                        $offerproducts = new WP_Query($args);

                        if ($offerproducts->have_posts()) {
                            while ($offerproducts->have_posts()) {
                                $offerproducts->the_post();
                                $product_short_description = get_the_excerpt();
                                $weight = get_post_meta(get_the_ID(), '_weight', true);
                        ?>
                                <a href="<?php the_permalink(); ?>" class="col-6 col-lg-4 col-xl-3 cards-blk">
                                    <div class="cardsimg mb-4">
                                        <img src="<?php the_post_thumbnail_url('thumbnail'); ?>">
                                    </div>
                                    <div>
                                        <h6><?php the_title(); ?></h6>
                                        <div class="ash-color">
                                            <?php echo apply_filters('the_content', $product_short_description); ?>
                                        </div>
                                        <?php if ($product->is_type('variable')) {
                                            $available_variations = $product->get_available_variations();
                                            if (count($available_variations) > 0) {
                                                // Get weight and price of the first variation
                                                $first_variation = $available_variations[0];
                                                $variation_weight = $first_variation['weight'];
                                                $variation_price = $first_variation['display_price'];
                                            }
                                            // Display weight and price
                                            //echo wc_price($variation_price) . ' (' . $variation_weight . ' gms)'; 
                                        ?>
                                            <p class="amnt"><?php echo wc_price($variation_price); ?> (<?php echo $variation_weight; ?> gms)</p>

                                        <?php
                                        }
                                        ?>
                                    </div>
                                </a>
                        <?php
                            }
                            wp_reset_postdata(); // add this line to reset the post data
                        } else {
                            echo 'No products found';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
<?php }  ?>

<!-- section start -->

<!-- section end -->
<?php get_footer(); ?>