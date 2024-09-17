<?php

/*****
Template Name: Home
 *****/
get_header();
?> 

<!-- banner section start -->
<div class="container">
    <?php if (have_rows('home_page')) : the_row(); ?>
        <?php if (get_row_layout() == 'banner_section') :
            $banner_slider = get_sub_field("banner_slider");
        ?>
            <div class="mb-40 position-relative">
                <div class="primary-slider">
                    <?php foreach ($banner_slider as $banner) {
                        $bannerbackgroundImage = $banner["background_image"]['url'];
                        $bannerbackgroundAlt = !empty($banner["background_image"]['alt']) ? $banner["background_image"]['alt'] : $banner["background_image"]['name'];
                        $bannerImage = $banner["banner_image"]['url'];
                        $bannerAlt = !empty($banner["banner_image"]['alt']) ? $banner["banner_image"]['alt'] : $banner["banner_image"]['name'];
                        $imageContent = $banner["image_content"];
                        $bannerContent = $banner["banner_content"];
                        $primaryCta = $banner["primary_cta"];
                        $primaryCtaLink = !empty($banner["primary_cta_link"]['url']) ? $banner["primary_cta_link"]['url'] : "javascript:void(0):";
                        $primaryCtaTarget = !empty($banner["primary_cta_link"]['target']) ? $banner["primary_cta_link"]['target'] : "_self";
                    ?>

                        <div>
                            <div class="row banner-blk align-items-md-center flex-md-row-reverse justify-content-between">
                                <div class="col-md-7 col-lg-6">
                                    <div class="img-blk">
                                        <div class="row  align-items-center">
                                            <div class="col-6 col-lg-8 col-xxl-7 pr-0">
                                                <img src="<?php echo $bannerImage; ?>" alt="<?php echo $bannerAlt; ?>">
                                            </div>
                                            <div class="col-6 col-lg-4 col-xxl-5 pl-0">
                                                <div class="img-con">
                                                    <!-- <span><?php echo $imageContent; ?></span> -->
                                                    <?php echo $imageContent; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <?php echo $bannerContent; ?>
                                    <a href="<?php echo $primaryCtaLink; ?>" target="<?php echo $primaryCtaTarget; ?>" class="button mt-4 mt-xl-5"><?php echo $primaryCta; ?></a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="primary-dots mt-5"></div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (have_rows('home_page')) : the_row(); ?>
        <?php if (get_row_layout() == 'cards_section') :
            $cards = get_sub_field("cards");
        ?>
            <div class="row">
                <div class="col-md-12 mx-auto mb-50">
                    <!-- three cards start -->
                    <div class="row">
                        <div class="col-lg-11 col-xl-10 mx-auto">
                            <div class="row justify-content-center">
                                <?php foreach ($cards as $card) {
                                    $Image = $card["icon"]['url'];
                                    $ImageAlt = !empty($card["icon"]['alt']) ? $card["icon"]['alt'] : $card["icon"]['name'];
                                    $Title = $card["title"];
                                    $Description = $card["description"];

                                ?>
                                    <div class="col-xl-4 col-md-6 mb-30">
                                        <div class="normal-cards  v1">
                                            <img src="<?php echo $Image; ?>" alt="<?php echo $ImageAlt; ?>">
                                            <div class="ml-md-3 mt-3 mt-md-0 text-center text-md-left">
                                                <h6><?php echo $Title; ?></h6>
                                                <h6 class="v1"><?php echo $Description; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                    <!-- three cards end -->
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<!-- banner section end -->

<!-- tab section start -->
<?php
$args = array(
    'post_type'      => 'product',
    'taxonomy'     => 'product_cat',
    'order'         => 'ASC',
    'orderby'      => 'term_order',
    'numberposts'  => -1,
    'number' => 0,
    'include'     => array(17, 18),

    // 'include'     => array(40, 41),
);
$categories = get_categories($args);
//  var_dump($categories);
?>
<div class="white-bg pt-80">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="tab-btns d-flex justify-content-between justify-content-md-start mb-30">

                    <?php
                    $counter = 0;
                    foreach ($categories as $category) {
                        $Name = $category->name;
                        $active_class = $counter == 0 ? 'active' : ''; ?>
                        <a href="javascript:void(0);" data-tab="<?php echo $category->term_id; ?>" class=" tab-btn link-anim <?php echo $active_class; ?> v1"><?php echo $Name; ?></a>

                    <?php $counter++;
                    } ?>
                    <a href="javascript:void(0);" data-tab="global" class="tab-btn link-anim v1">GLOBAL</a>
                </div>
                <div class="tabContentWrap">
                    <?php
                    $counter = 0;
                    foreach ($categories as $category) {
                        $product_cat_args = array(
                            'post_type' => 'product',
                            'orderby'    => 'menu_order',
                            'order' => 'ASC',
                            'numberposts' => -1,
                            'tax_query' =>  array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field' => 'id',
                                    'terms' => $category->term_id
                                )
                            )
                        );
                        $products = get_posts($product_cat_args);
                        $active_class = $counter == 0 ? 'active' : '';
                    ?>
                        <div id="<?php echo $category->term_id; ?>" class="tab-content <?php echo $active_class; ?> ">
                            <div class=" row secondary-slider">
                                <?php
                                $products_count = count($products);
                                $rows = ceil($products_count / 4);
                                $startindex = 0;
                                $endindex = 4;
                                for ($i = 1; $i <= $rows; $i++) {
                                ?>
                                    <div class="col-md-12">
                                        <div class="row cardsection">
                                            <?php
                                            for ($j = $startindex; $j < min($endindex, $products_count); $j++) {
                                                $product_data = $products[$j];
                                                $productid = $product_data->ID;
                                                $productname = $product_data->post_title;
                                                $productdesc = $product_data->post_excerpt;
                                                $productprice = $product_data->_regular_price;
                                                $productsaleprice = $product_data->_sale_price;
                                                $price = $productsaleprice == '' ? $productprice : $productsaleprice;
                                                $product_weight = get_post_meta($productid, '_weight', true);
                                                // var_dump($product_weight);
                                                $product_type_val = get_post_meta($productid, 'product_type_fetch', true);
                                            ?>
                                                <a href="<?php echo get_permalink($productid); ?>" class="col-6 col-md-3">
                                                    <div class="cards-blk for-home">
                                                        <div class="cardsimg mb-4">
                                                            <img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id($productid)); ?>" alt="Image">
                                                        </div>
                                                        <div>
                                                            <h6><?php echo $productname; ?></h6>
                                                            <?php
                                                            $var = new WC_Product_Variable($product_data);
                                                            $variations = $var->get_available_variations();
                                                            $variation_product_id = $variations[0]['variation_id'];
                                                            //var_dump($variations);
                                                            if (count($variations) != 0) {
                                                                $variation_product = new WC_Product_Variation($variation_product_id);
                                                                $var_reg_prc = $variation_product->get_regular_price();
                                                                $var_sal_prc = $variation_product->get_sale_price();
                                                                $variationprice = ($var_sal_prc == '' ? $var_reg_prc : $var_sal_prc);
                                                                $var_weight = $variation_product->get_weight();
                                                            ?>
                                                                <p class="ash-color"><?php echo $var_weight; ?>gms <?php if (!empty($product_type_val)) { ?> / <?php echo $product_type_val; ?> 
                                                                <?php } ?></p>
                                                              
                                                                <p class="amnt"><?php echo wc_price($variationprice); ?></p>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php  } ?>
                                        </div>
                                    </div>
                                <?php
                                    $startindex = $endindex;
                                    $endindex += 4;
                                } ?>
                            </div>
                            <div class="primary-dots v1 pb-80"></div>
                        </div>
                    <?php $counter++;
                    } ?>
                    <div id="global" class="tab-content">
                        <div class="row secondary-slider">
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
                            $shipping_class_products = new WP_Query($args);
                            $global_counter = 0; ?>
                            <div class="col-md-12">
                                <div class="row cardsection">
                                    <?php
                                    if ($shipping_class_products->have_posts()) {
                                        while ($shipping_class_products->have_posts()) {
                                            $shipping_class_products->the_post();
                                            $product_id = get_the_ID();
                                            $product_name = get_the_title();
                                            $product_url = get_permalink();
                                            $product_type_val = get_post_meta($product_id, 'product_type_fetch', true);
                                            $product = wc_get_product($product_id);
                                            if ($product->is_type('variable') && $product->get_shipping_class()) {
                                                $available_variations = $product->get_available_variations();

                                                foreach ($available_variations as $variation) {
                                                    $variation_id = $variation['variation_id'];
                                                    $variation_obj = wc_get_product($variation_id);

                                                    $min_qty = get_post_meta($variation_id, '_min_qty_', true);
                                                    $max_qty = get_post_meta($variation_id, '_max_qty_', true);
                                                    $global_counter++;
                                                    if ($min_qty && $max_qty) {
                                                        $weight = $variation['weight'];
                                                        $price = $variation['display_price'];
                                    ?>
                                                        <a href="<?php echo  $product_url; ?>" class="col-6 col-md-3">
                                                            <div class="cards-blk for-home">
                                                                <div class="cardsimg mb-4">
                                                                    <?php if (has_post_thumbnail()) : ?>
                                                                        <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
                                                                        <img src="<?php echo $image[0]; ?>" alt="<?php the_title_attribute(); ?>">
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div>
                                                                    <h6><?php echo $product_name; ?></h6>

                                                                    <p class="ash-color"><?php echo $weight; ?>gms<?php if (!empty($product_type_val)) { ?> / <?php echo $product_type_val; ?> 
                                                                <?php } ?></p> 
                                                                    <p class="amnt"><?php echo wc_price($price); ?></p>

                                                                </div>
                                                            </div>
                                                        </a>

                                    <?php
                                                        if (
                                                            $global_counter % 4 === 0
                                                        ) {
                                                            echo '</div></div><div class="col-md-12"><div class="row cardsection mb-40 mb-md-0">';
                                                        }
                                                        break; // Only display the first valid variation found for each product
                                                    }
                                                }
                                            }
                                        }
                                        wp_reset_postdata();
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="primary-dots v1 pb-80"></div> -->
                        <?php
                        // Add final primary dots if needed
                        if ($global_counter % 4 !== 0) {
                            echo '<div class="primary-dots v1 pb-80"></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- tab section end -->

<!-- section 4 start -->
<?php if (have_rows('home_page')) : the_row(); ?>
    <?php if (get_row_layout() == 'hero_section') :
        $heading = get_sub_field("heading");
        $title = get_sub_field("title");
        $description = get_sub_field("description");
        $primaryCta = get_sub_field("primary_cta");
        $Link = !empty(get_sub_field("primary_link")['url']) ? get_sub_field("primary_link")['url'] : "javascript:void(0):";
        $targetLink = !empty(get_sub_field("primary_link")['target']) ? get_sub_field("primary_link")['target'] : "_self";
        $Image = get_sub_field("image")['url'];
        $imageAlt = !empty(get_sub_field("image")['alt']) ? get_sub_field('image')['alt'] : get_sub_field('image')['name'];
    ?>
        <div class="white-bg veg-bg banner-blk v1 py-80">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row align-items-center flex-md-row-reverse">
                            <div class="col-md-4 mb-30 mb-md-0">
                                <div class="img-blk">
                                    <img src="<?php echo $Image; ?>" alt="<?php echo $imageAlt; ?>">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h1><?php echo $heading; ?><br><span><?php echo $title; ?></span></h1>
                                <?php echo $description; ?>
                                <div class="mb-30"></div>
                                <a href="<?php echo $Link; ?>" target="<?php echo $targetLink; ?>" class="button"><?php echo $primaryCta; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<!-- section 4 end -->
<!-- Most purchased products -->
<?php
$args = array(
    'post_type' => 'product',
    'meta_key' => 'total_sales',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
    // 'posts_per_page' => 4 // You can adjust the number of products to display here
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => 'global',
            'operator' => 'NOT IN',
        ),
    ),
);

$most_purchased = new WP_Query($args);
$displayed_product_names = array(); // Array to store displayed product names
$displayed_product_count = 0;

?>
<div class="white-bg pt-80">
    <div class="container">
        <div class="mb-30">
            <h5>MOST PURCHASED</h5>
        </div>
        <div class="row">
            <div class="col-xl-11">
                <div class="row cardsection mb-20 mb-md-0">
                    <?php
                    if ($most_purchased->have_posts()) {
                        while ($most_purchased->have_posts() && $displayed_product_count < 4) {
                            $most_purchased->the_post();
                            $product_id = get_the_ID();

                            // Exclude product with ID  
                            if ($product_id == 725) {
                                continue; // Skip to the next product if the ID 
                            }

                            $product_name = get_the_title();

                            // Check if product name is already displayed or is the same as a previously displayed product
                            if (in_array($product_name, $displayed_product_names)) {
                                continue; // Skip to the next product if the name is already displayed
                            }

                            $displayed_product_names[] = $product_name; // Add product name to displayed array
                            $displayed_product_count++;

                            $product_url = get_permalink();
                            $product = wc_get_product($product_id);
                    ?>
                            <a href="<?php echo $product_url; ?>" class="col-6 col-md-3">
                                <div class="cards-blk">
                                    <div class="cardsimg mb-4">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
                                            <img src="<?php echo $image[0]; ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h6><?php echo $product_name; ?></h6>
                                        <?php
                                        $available_variations = $product->get_available_variations();
                                        $variation_product_id = $available_variations[0]['variation_id'];
                                        if (count($available_variations) != 0) {
                                            $variation_product = new WC_Product_Variation($variation_product_id);
                                            $var_reg_prc = $variation_product->get_regular_price();
                                            $var_sal_prc = $variation_product->get_sale_price();
                                            $variationprice = ($var_sal_prc == '' ? $var_reg_prc : $var_sal_prc);
                                            $var_weight = $variation_product->get_weight();
                                        ?>
                                            <p class="ash-color">(<?php echo $var_weight; ?>gms)</p>
                                            <p class="amnt"><?php echo wc_price($variationprice); ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </a>
                    <?php
                        }
                    }
                    wp_reset_postdata(); // Reset the query
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
 

<?php get_footer(); ?>