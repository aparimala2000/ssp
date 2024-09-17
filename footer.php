<?php $visitorCnt = do_shortcode('[user_country]');
if ($visitorCnt != "IN") {
    // include('popup.php');
} ?>
<!-- footer start -->
<footer class="py-80 mb-20">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 col-lg-6">
                <div class="row mb-50 mb-lg-0">
                    <?php
                    $args = array(
                        'order' => 'ASC',
                        'post_type' => 'nav_menu_item',
                        'post_status' => 'publish'
                    );
                    $menu_list = wp_get_nav_menu_items('Footer_menu', $args);
                    // var_dump($menu_list);
                    ?>
                    <div class="col-6 mb-3 mb-md-0">
                        <ul class="footer-lists">
                            <?php foreach ($menu_list as $menu_item) {
                                if ($menu_item->menu_item_parent == 0) {
                            ?>
                                    <li><a href="<?php echo $menu_item->url; ?>" class="link-anim"><?php echo $menu_item->title; ?></a></li>
                            <?php }
                            } ?>
                        </ul>
                        <?php if ($post = get_page_by_path('footer', OBJECT, 'page')) { ?>

                            <?php if (have_rows('footer_pattern')) {
                                  while (have_rows('footer_pattern')) {
                                the_row();
                                 ?>
                                 <?php 
                                 the_row();
                        if (get_row_layout() == 'fssai_number_and_veg_icon') :
                            $veg_icon = get_sub_field("veg_icon")['url'];
                            $Veg_Alt_image = !empty(get_sub_field("veg_icon")['alt']) ? get_sub_field('veg_icon')['alt'] : get_sub_field('veg_icon')['name'];
                            $fssai_logo = get_sub_field("fssai_logo")['url'];
                            $Fssai_Alt_image = !empty(get_sub_field("fssai_logo")['alt']) ? get_sub_field('fssai_logo')['alt'] : get_sub_field('fssai_logo')['name'];
                            $fssai_number = get_sub_field("fssai_number");
                        ?>
                                <div class="fssai-logo mt-5">
                                    <img src="<?php echo $fssai_logo; ?>" alt="<?php echo $Fssai_Alt_image; ?>">
                                    <span><?php echo $fssai_number; ?></span>
                                </div>
                                <?php endif; ?>
                        <?php } }
                        } ?>
                    </div>
                    <div class="col-6 mb-3 mb-md-0">
                        <ul class="footer-lists">
                            <?php foreach ($menu_list as $menu_item) {
                                if ($menu_item->menu_item_parent != 0) {
                            ?>
                                    <li><a href="<?php echo $menu_item->url; ?>" class="link-anim"><?php echo $menu_item->title; ?></a></li>
                            <?php }
                            } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-5 offset-xl-1">
                <form class="w-100">
                    <div class="search-blk">
                        <input class="search-box w-100" type="text" id="email_footer" placeholder="Subscribe to our newsletter" />
                        <a href="javascript:void(0);" class="search-btn button newsletter">Subscribe</a>
                    </div>
                    <div class="err-msg"></div>
                    <div class="s-msg">
                        <p>Thank you for the subscription</p>
                    </div>
                </form>
            </div>
        </div>
        <?php if ($post = get_page_by_path('footer', OBJECT, 'page')) { ?>

            <?php if (have_rows('footer_pattern')) {
                the_row(); ?>

                <div class=" footer-bottom d-lg-flex align-items-center justify-content-between text-center text-lg-left ">

                    <?php 
                    if (get_row_layout() == 'copy_rights') :
                        $copyrights = get_sub_field("content");
                    ?>
                        <p><?php echo $copyrights; ?> <a href="https://www.madebyfire.com/" target="_blank" class="link-anim primary">Made By Fire</a></p>
                    <?php endif; ?>

                    <!-- <div class="d-md-flex justify-content-center align-items-center">
                        <?php the_row();
                        if (get_row_layout() == 'fssai_number_and_veg_icon') :
                            $veg_icon = get_sub_field("veg_icon")['url'];
                            $Veg_Alt_image = !empty(get_sub_field("veg_icon")['alt']) ? get_sub_field('veg_icon')['alt'] : get_sub_field('veg_icon')['name'];
                            $fssai_logo = get_sub_field("fssai_logo")['url'];
                            $Fssai_Alt_image = !empty(get_sub_field("fssai_logo")['alt']) ? get_sub_field('fssai_logo')['alt'] : get_sub_field('fssai_logo')['name'];
                            $fssai_number = get_sub_field("fssai_number");
                        ?>
                            <div class="d-flex f-icon px-4 py-4 py-xl-0 align-items-center justify-content-center">
                                <img src="<?php echo $veg_icon; ?>" alt="<?php echo $Veg_Alt_image; ?>">
                                <div class="fssai-logo">
                                    <img src="<?php echo $fssai_logo; ?>" alt="<?php echo $Fssai_Alt_image; ?>">
                                    <span><?php echo $fssai_number; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                     
                    </div> -->
                    <?php the_row();
                    if (get_row_layout() == 'payment_gateway') :
                        $images = get_sub_field("images");
                    ?>
                       <div class="d-flex f-icon px-4 py-4 py-lg-0 justify-content-center">
                            <?php foreach ($images as $pay_images) {
                                $Image = $pay_images["image"]['url'];
                                $ImageAlt = !empty($pay_images["image"]['alt']) ? $pay_images["image"]['alt'] : $pay_images["image"]['name']; ?>
                                <img src="<?php echo $Image; ?>" alt="<?php echo $ImageAlt; ?>">
                            <?php } ?>
                        </div>
                    <?php endif; ?>
                    <?php the_row();
                    if (get_row_layout() == 'social_links') :
                        $fbLink = get_sub_field("facebook");
                        $twitterLink = get_sub_field("twitter");
                    ?>
                        <ul class="social-icons d-flex justify-content-center">
                            <li><a href="<?php echo !empty($fbLink) ? $fbLink : "javascript:void(0);"; ?>" target="_blank"><i class="fa fa-facebook-official" aria-hidden="true"></i></a></li>
                            <li><a href="<?php echo !empty($twitterLink) ? $twitterLink : "javascript:void(0);"; ?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        </ul>
                    <?php endif; ?>
                </div>

            <?php  } ?>
        <?php   } ?>

    </div>
</footer>
<!-- footer end -->
</div>
<?php wp_footer(); ?>
<script src='<?php echo get_bloginfo('template_url'); ?>/lib/js/app.js'></script>
<script src='<?php echo get_bloginfo('template_url'); ?>/lib/js/custom.js'></script>
<script src='<?php echo get_bloginfo('template_url'); ?>/lib/js/theme-functions.js'></script>
<script src='<?php echo get_bloginfo('template_url'); ?>/lib/js/address_book_checkout.js'></script>
<script src='<?php echo get_bloginfo('template_url'); ?>/lib/js/checkout_product.js'></script>
<script src='<?php echo get_bloginfo('template_url'); ?>/lib/js/coupon.js'></script>

<?php
$productType = WC()->session->get('checkout_type');
$defCountry = WC()->customer->get_shipping_country();
if ($productType == "global") {
    if ($defCountry == "IN" || $defCountry == "") {
        WC()->customer->set_shipping_country("US");
        $myCountry =   WC()->customer->get_shipping_country();
    } else {
        $myCountry = WC()->customer->get_shipping_country();
    }
}
if ($productType == "global" && !is_checkout()) {
?>
    <script>
        $(document).ready(function() {
            console.log("Mini Cart");
            var selectedCountry = "<?php echo $myCountry; ?>";
            console.log("Cart Country:" + selectedCountry);
            // Store the selected country in session storage
            sessionStorage.setItem("selectedCountry", selectedCountry);
            $(".country_select option").each(function() {
                //    console.log($(this).attr('data-code') + "i");
                if ($(this).attr('data-code') == selectedCountry) {
                    $(this).attr("selected", "selected");

                }
            });
            if (selectedCountry != "" || selectedCountry != undefined) {
                $.ajax({
                    type: "POST",
                    url: blogUri + "/wp-admin/admin-ajax.php",
                    data: {
                        action: 'checkout_country_shipping',
                        country_code: selectedCountry,
                    },
                    success: function(data) {

                        var succVal = data.split('|');
                        var totVal = succVal[0];
                        var shippingVal = succVal[1];
                        $('#check-pr').html(shippingVal);
                        $('#cart_total').html(totVal);
                    }
                });
            }
        });
    </script>
<?php } ?>
<!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> -->

<script>
    // Close global popup
    $(".popup-close-btn").on("click", function() {
        $(".popup-box1-blk").fadeOut(200);
    });
</script>

</body>

</html>