<?php $product = new WC_Product_Variable($post->ID);
$variations = $product->get_available_variations();
if (!defined('ABSPATH')) {
    exit;
}
session_start();
$cartSession = WC()->session->get('checkout_type');
if (isset($cartSession)) {
    $checkoutProType = $cartSession;
}

?>
<div class="full-width">
    <div style="background-color:#F8F2F2; margin-bottom: 30px;">
        <div class="container">
            <div class="accord d-block d-xl-none">
                <div>
                    <a href="javascript:void(0);" class="toggle_btn active">
                        <span class="mr-2"><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>Order Details
                    </a>
                    <div class="inner pt-3 show">
                        <div>
                            <div class="mb-50">
                                <?php
                                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                    $_product     = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                                    $var_id = $cart_item['variation_id'];
                                    $variation_product = new WC_Product_Variation($var_id);
                                    $product_image_url = get_the_post_thumbnail_url($product_id, 'thumbnail');
                                    $var_weight = $variation_product->weight;
                                    if ($var_weight >= 1000) {
                                        $var_weight_display = ($var_weight / 1000) . ' kg';
                                    } else {
                                        $var_weight_display = $var_weight . ' gms';
                                    }
                                    $pr_name = $_product->get_name();
                                    if (strpos($pr_name, ' - ') !== false) {
                                        $pr_name = substr($pr_name, 0, strpos($pr_name, ' - '));
                                    }

                                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                ?>
                                        <?php if ($product_id == 725) {
                                            $has_last_row = true;
                                            $free_product_id = $product_id;
                                            $free_variation_id = $var_id;
                                            $free_product_name = $pr_name;
                                            $free_product_image = $product_image_url;
                                            continue;
                                        ?>

                                        <?php     } else { ?>
                                            <div class="cart-row">
                                                <!-- <div class="d-flex align-items-start justify-content-between"> -->
                                                <div class="d-flex">
                                                    <div class="sm-icon"><img src="<?php echo $product_image_url; ?>"></div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="md px-3"><?php echo  $pr_name; ?></span>
                                                            <?php $qtyy = $cart_item['quantity'];
                                                            if (!empty($cart_item['variation_id'])) {
                                                                $var_id = $cart_item['variation_id'];
                                                                $var_val = wc_get_product($var_id);
                                                                $var_reg_prc = $var_val->get_regular_price();
                                                                $var_sal_prc = $var_val->get_sale_price();
                                                                $var_prc = $var_sal_prc == '' ? $var_reg_prc : $var_sal_prc;
                                                                if ($var_sal_prc != '') {
                                                            ?>
                                                                    <span class="md text-right" id="checkout_price1<?php echo $var_id; ?>"><?php echo wc_price($var_reg_prc * $qtyy); ?></span>
                                                                    <span class="md mb-1" style="display: none">&#x20B9;<?php echo $var_prc; ?></span>
                                                                    <input type="hidden" name="check-prc" class="check-prc" value="<?php echo $var_prc; ?>">
                                                                    <input type="hidden" name="unit-prc" class="unit-prc" value="<?php echo $var_reg_prc; ?>">
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <span class="md text-right" id="checkout_price1<?php echo $var_id; ?>"><?php echo  wc_price($var_reg_prc * $qtyy); ?></span>
                                                                    <span class="md mb-1" style="display: none"></span>
                                                                    <input type="hidden" name="check-prc" class="check-prc" value="<?php echo  $var_reg_prc; ?>">
                                                                <?php
                                                                }
                                                                ?>
                                                                <?php } else {
                                                                $simpleid = $product_id;
                                                                $regular_price = $_product->get_regular_price();
                                                                $sale_price = $_product->get_sale_price();
                                                                if ($sale_price != '') {
                                                                ?>
                                                                    <span class="md text-right" id="checkout_price1<?php echo $simpleid; ?>"><?php echo $regular_price * $qtyy; ?></span>
                                                                    <span class="md mb-1" style="display: none">&#x20B9;<?php echo $sale_price; ?></span>
                                                                    <input type="hidden" name="check-prc" class="check-prc" value="<?php echo $sale_price; ?>">
                                                                    <input type="hidden" name="unit-prc" class="unit-prc" value="<?php echo $regular_price; ?>">
                                                                <?php } else { ?>
                                                                    <span class="md text-right" id="checkout_price1<?php echo $simpleid; ?>"><?php echo wc_price($regular_price * $qtyy); ?></span>
                                                                    <span class="md mb-1" style="display: none"></span>
                                                                    <input type="hidden" name="check-prc" class="check-prc" value="<?php echo $regular_price; ?>">

                                                                <?php } ?>
                                                            <?php } ?>
                                                        </div>
                                                        <span class="sm mb-3 d-inline-block px-3">(<?php echo $var_weight_display; ?>)</span>
                                                        <div class="d-flex justify-content-between">
                                                            <div class="d-flex align-items-center px-3">
                                                                <?php if ($checkoutProType == "domestic") { ?>
                                                                    <?php $qty = $cart_item['quantity'];
                                                                    $product_quantity = sprintf('
									<div class="count" id="preload' . $product_id . '">
										<span class="domes_check_dec1 box dummy-box" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $var_id . '>-</span>
										<input style="display:none" onchange="quantityonchange(' . $product_id . ',this)" id="qty" type="text" class="textbox-small" name="cart[%s][qty]" value="%d" />
										<input id="domes_check_qty1' . $var_id . '" type="text" readonly class="box" value="%d" />
										<span class="domes_check_inc1 box active dummy-box" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $var_id . ' data-field="quantity">+</span>
									</div>', $cart_item_key, $qty, $qty);

                                                                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); ?>
                                                                <?php } else { ?>
                                                                    <?php $qty = $cart_item['quantity'];
                                                                    if ($cart_item['data']->get_shipping_class() === 'enable') {
                                                                        $variation = $cart_item['data'];
                                                                        $minqty = get_post_meta($variation->get_variation_id(), '_min_qty_', true);
                                                                        $maxqty = get_post_meta($variation->get_variation_id(), '_max_qty_', true);
                                                                    }
                                                                    $product_quantity = sprintf('
									<div class="count" id="preload' . $product_id . '">
										<span class="glob_check_dec1 box"  data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $var_id . ' data-min=' . $minqty . ' data-max=' . $maxqty . '>-</span>
										<input style="display:none" onchange="quantityonchange(' . $product_id . ',this)" id="qty" type="text" class="textbox-small" name="cart[%s][qty]" value="%d" />
										<input id="glob_check_qty1' . $var_id . '" type="text" readonly class="box" value="%d" />
										<span class="glob_check_inc1 box active" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $var_id . ' data-field="quantity" data-min=' . $minqty . ' data-max=' . $maxqty . '>+</span>
									</div>', $cart_item_key, $qty, $qty);

                                                                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); ?>
                                                                <?php } ?>
                                                                <a href="javascript:void(0);" class="trash-icon ml-5 checkout_product_remove" data-product-id="<?php echo $product_id; ?>" data-variation-id="<?php echo $var_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- </div> -->
                                            </div>
                                            <!-- <div class="cart-row">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="md"><?php echo  $pr_name; ?></span>
                                                    <a href="javascript:void(0);" class="circle-icon checkout_product_remove type2" data-product-id="<?php echo $product_id; ?>" data-variation-id="<?php echo $var_id; ?>"><i class="las la-times"></i></a>
                                                </div>
                                                <span class="sm mb-2 d-inline-block">(<?php echo $var_weight_display; ?>)</span>
                                                <div class="d-flex justify-content-between align-items-center">

                                                    <?php if ($checkoutProType == "domestic") { ?>
                                                        <?php $qty = $cart_item['quantity'];
                                                        $product_quantity = sprintf('
									<div class="count" id="preload' . $product_id . '">
										<span class="domes_check_dec1 box dummy-box" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $var_id . '>-</span>
										<input style="display:none" onchange="quantityonchange(' . $product_id . ',this)" id="qty" type="text" class="textbox-small" name="cart[%s][qty]" value="%d" />
										<input id="domes_check_qty1' . $var_id . '" type="text" readonly class="box" value="%d" />
										<span class="domes_check_inc1 box active dummy-box" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $var_id . ' data-field="quantity">+</span>
									</div>', $cart_item_key, $qty, $qty);

                                                        echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); ?>
                                                    <?php } else { ?>
                                                        <?php $qty = $cart_item['quantity'];
                                                        if ($cart_item['data']->get_shipping_class() === 'enable') {
                                                            $variation = $cart_item['data'];
                                                            $minqty = get_post_meta($variation->get_variation_id(), '_min_qty_', true);
                                                            $maxqty = get_post_meta($variation->get_variation_id(), '_max_qty_', true);
                                                        }
                                                        $product_quantity = sprintf('
									<div class="count" id="preload' . $product_id . '">
										<span class="glob_check_dec1 box"  data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $var_id . ' data-min=' . $minqty . ' data-max=' . $maxqty . '>-</span>
										<input style="display:none" onchange="quantityonchange(' . $product_id . ',this)" id="qty" type="text" class="textbox-small" name="cart[%s][qty]" value="%d" />
										<input id="glob_check_qty1' . $var_id . '" type="text" readonly class="box" value="%d" />
										<span class="glob_check_inc1 box active" data-key=' . $cart_item_key . ' data-qty=' . $qty . ' data-variation-id=' . $var_id . ' data-field="quantity" data-min=' . $minqty . ' data-max=' . $maxqty . '>+</span>
									</div>', $cart_item_key, $qty, $qty);

                                                        echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); ?>
                                                    <?php } ?>
                                                    <?php $qtyy = $cart_item['quantity'];
                                                    if (!empty($cart_item['variation_id'])) {
                                                        $var_id = $cart_item['variation_id'];
                                                        $var_val = wc_get_product($var_id);
                                                        $var_reg_prc = $var_val->get_regular_price();
                                                        $var_sal_prc = $var_val->get_sale_price();
                                                        $var_prc = $var_sal_prc == '' ? $var_reg_prc : $var_sal_prc;
                                                        if ($var_sal_prc != '') {
                                                    ?>
                                                            <span class="md" id="checkout_price1<?php echo $var_id; ?>"><?php echo wc_price($var_reg_prc * $qtyy); ?></span>
                                                            <span class="md" style="display: none">&#x20B9;<?php echo $var_prc; ?></span>
                                                            <input type="hidden" name="check-prc" class="check-prc" value="<?php echo $var_prc; ?>">
                                                            <input type="hidden" name="unit-prc" class="unit-prc" value="<?php echo $var_reg_prc; ?>">
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <span class="md" id="checkout_price1<?php echo $var_id; ?>"><?php echo  wc_price($var_reg_prc * $qtyy); ?></span>
                                                            <span class="md" style="display: none"></span>
                                                            <input type="hidden" name="check-prc" class="check-prc" value="<?php echo  $var_reg_prc; ?>">
                                                        <?php
                                                        }
                                                        ?>
                                                        <?php } else {
                                                        $simpleid = $product_id;
                                                        $regular_price = $_product->get_regular_price();
                                                        $sale_price = $_product->get_sale_price();
                                                        if ($sale_price != '') {
                                                        ?>
                                                            <span class="md" id="checkout_price1<?php echo $simpleid; ?>"><?php echo $regular_price * $qtyy; ?></span>
                                                            <span class="md" style="display: none">&#x20B9;<?php echo $sale_price; ?></span>
                                                            <input type="hidden" name="check-prc" class="check-prc" value="<?php echo $sale_price; ?>">
                                                            <input type="hidden" name="unit-prc" class="unit-prc" value="<?php echo $regular_price; ?>">
                                                        <?php } else { ?>
                                                            <span class="md" id="checkout_price1<?php echo $simpleid; ?>"><?php echo wc_price($regular_price * $qtyy); ?></span>
                                                            <span class="md" style="display: none"></span>
                                                            <input type="hidden" name="check-prc" class="check-prc" value="<?php echo $regular_price; ?>">

                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div> -->
                                        <?php } ?>


                                <?php
                                    }
                                } ?>
                                <?php if ($has_last_row) { ?>
                                    <div class="cart-row">
                                        <!-- <div class="d-flex align-items-start justify-content-between"> -->
                                        <div class="d-flex align-items-start">
                                            <div class="sm-icon"><img src="<?php echo $free_product_image; ?>"></div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <span class="md px-3"><?php echo $free_product_name; ?></span>
                                                    <span class="md  text-right"><?php echo wc_price("0.00"); ?></span>
                                                </div>
                                                <span class="sm mb-3 px-3 d-inline-block">Free Gift</span>
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
                                        <!-- <a href="javascript:void(0);" class="circle-icon checkout_product_remove type2" data-product-id="<?php echo $free_product_id; ?>" data-variation-id="<?php echo $free_variation_id; ?>"><i class="las la-times"></i></a> -->
                                        <!-- </div> -->
                                    </div>
                                    <!-- <div class="cart-row">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="md"><?php echo  $free_product_name; ?></span>
                                            <a href="javascript:void(0);" class="circle-icon checkout_product_remove type2" data-product-id="<?php echo $free_product_id; ?>" data-variation-id="<?php echo $free_variation_id; ?>"><i class="las la-times"></i></a>
                                        </div>
                                        <span class="md pt-2 pb-3 ash-color">Free Gift</span>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="count">
                                                <span class="box">-</span>
                                                <input class="box" type="text" name="" value="1" readonly>
                                                <span class="box">+</span>
                                            </div>
                                            <span class="md"><?php echo wc_price("0.00"); ?></span>
                                        </div>
                                    </div> -->

                                <?php } ?>
                            </div>
                            <!-- apply coppen start -->
                            <!-- <div class="coupon-success-blk mb-50">
                                <a href="javascript:void(0)" id="apply-coupon-btn1" class="button mb apply-coupon opn-coupon apply_coupon_check_btn">
                                    <div>
                                        <img src="<?php echo get_bloginfo('template_url'); ?>/lib/images/discount1.svg">
                                        View All Coupons
                                    </div>
                                    <span><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                                </a> -->
                            <!-- <a href="javascript:void(0)" id="apply-coupon-btn1" class="button mb tb apply-coupon mb-50 opn-coupon apply_coupon_check_btn">Apply Coupon</a> -->
                            <!-- <div class="success-msg">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="md" id="applied_coupon1"></span>
                                            <span class="sm m-font mt-1 d-block green ">Offer applied on the bill</span>
                                        </div>
                                        <a href="javascript:void(0);" class="link-anim md code-remove">Remove</a>
                                    </div>
                                </div> 
                            </div> -->

                            <!-- apply cuopon end -->
                            <table class="cart-table with-check mb-30">
                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="md">Price</span>
                                        </td>
                                        <td>
                                            <?php
                                            $total = array();
                                            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                                $_product     = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                                                $variation_id = $cart_item['variation_id'];
                                                $qtyy = $cart_item['quantity'];
                                                if (!empty($cart_item['variation_id'])) {
                                                    $var_id = $cart_item['variation_id'];
                                                    $var_val = wc_get_product($var_id);
                                                    $reg =  (int)(get_post_meta($var_id, '_regular_price', true)) * (int)$qtyy;
                                                } else {
                                                    $reg =  (int)(get_post_meta($product_id, '_regular_price', true)) * (int)$qtyy;
                                                }
                                                $total[] = $reg;
                                            }
                                            foreach ($total as $key => $totalval) {
                                                $sum += $totalval;
                                            }
                                            $subVal = WC()->cart->subtotal;
                                            // $discVal = $sum - $subVal;
                                            $discVal = (($sum * $perc_disc) / 100);
                                            $discVal = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $discVal);
                                            $sum = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $sum);
                                            ?>
                                            <span class="md" id="checkout_total_price1">&#x20B9;<?php echo $sum; ?>.00</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="md">Shipping</span>
                                        </td>
                                        <td>
                                            <?php $cc = WC()->customer->get_shipping_country();
                                            if ($checkoutProType == "domestic") {
                                                $shippingCharge = "FREE!";
                                            } else {
                                                $shippingCharge = '₹' . get_option($cc . '_five') . '.00';
                                            }
                                            ?>
                                            <span class="md" id="chkout-shipping-tot-mob"><?php echo $shippingCharge; ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="md">Discount</span>
                                        </td>
                                        <td>
                                            <?php
                                            // Get applied coupons
                                            $applied_coupons = WC()->cart->get_applied_coupons();

                                            // Check if any coupons are applied
                                            if (!empty($applied_coupons)) {
                                                // Initialize the total discount amount
                                                $total_discount = 0;

                                                // Loop through each applied coupon
                                                foreach ($applied_coupons as $applied_coupon) {
                                                    // Get the discount amount for the applied coupon
                                                    $coupon_discount = WC()->cart->get_coupon_discount_amount($applied_coupon);
                                                    // Accumulate the total discount
                                                    $total_discount += $coupon_discount;
                                                }
                                                $rounded_total_discount = floor($total_discount);
                                                $formatted_total_discount = wc_price($rounded_total_discount);
                                                // Output the total discount amount
                                                echo '<span class="md discount_amount">' . $formatted_total_discount . '</span>';
                                            } else {
                                                echo '<span class="md discount_amount">' . wc_price(0) . '</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="b-font md">Total</span>
                                        </td>
                                        <td>
                                            <?php
                                            if ($checkoutProType == "domestic") {
                                                $cart_total = WC()->cart->get_cart_contents_total(); ?>
                                                <span class="b-font md" id="checkout_total1"><?php echo wc_price($cart_total); ?></span>
                                            <?php    } else { ?>
                                                <span class="b-font md" id="checkout_total1"><?php wc_cart_totals_order_total_html(); ?></span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- mob coupon code start  -->
<div class="coupon-success-blk mb-5 d-block d-xl-none">
    <a href="javascript:void(0)" id="apply-coupon-btn1" class="button mb apply-coupon opn-coupon apply_coupon_check_btn justify-content-center" style="border: 2px solid #F8F2F2;max-width: 430px;">
        <div>
            <img src="<?php echo get_bloginfo('template_url'); ?>/lib/images/discount1.svg">
            Apply Coupons
        </div>
        <!-- <span><i class="fa fa-angle-right" aria-hidden="true"></i></span> -->
    </a>
    <div class="success-msg"  style="border: 2px solid #F8F2F2;">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <span class="md" id="applied_coupon1"></span>
                <span class="sm m-font mt-1 d-block green ">Offer applied on the bill</span>
            </div>
            <a href="javascript:void(0);" class="link-anim md code-remove">Remove</a>
        </div>
    </div>
</div>
<!-- mob coupon code end -->
<?php
                            function is_customer_last_purchase_3_months_ago_mobile()
                            {
                                // Check if the user is logged in
                                if (is_user_logged_in()) {
                                    $user_id = get_current_user_id();

                                    // Get all orders for the user
                                    $customer_orders = wc_get_orders(['customer' => $user_id]);

                                    // Check if there are at least 3 orders
                                    if (count($customer_orders) >= 2) {
                                        // Get the current date
                                        $current_date = new DateTime();

                                        // Count orders within the last 3 months
                                        $count_recent_orders = 0;
                                        foreach ($customer_orders as $order) {
                                            if ($order->get_status() === 'completed') {
                                                $order_date = $order->get_date_created();
                                                $interval = $current_date->diff($order_date);
                                                $months_difference = $interval->y * 12 + $interval->m;

                                                if ($months_difference <= 3) {
                                                    $count_recent_orders++;
                                                }
                                            }
                                        }

                                        // Check if the customer has at least 3 orders within the last 3 months
                                        if ($count_recent_orders >= 2) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    } else {
                                        return false; // Customer has fewer than 3 orders
                                    }
                                } else {
                                    return false; // User is not logged in
                                }
                            }

                            $is_qualifying_customer = is_customer_last_purchase_3_months_ago_mobile();
                            if ($checkoutProType == "domestic") {
                                $coupon_cart_total = WC()->cart->get_cart_contents_total();
                            } else {
                                $coupon_cart_total = WC()->cart->get_cart_contents_total();
                            }

                            // var_dump($coupon_cart_total);
                            $coupon_ids = array(982, 983, 1027, 1026);

                            $coupons = get_posts(
                                array(
                                    'post_type'   => 'shop_coupon',
                                    'numberposts' => -1,
                                    'post__in'       => $coupon_ids,
                                )
                            ); ?>
                            <!-- slide coupon start -->
                            <div class="coupon-detail-blk">
                                <div class="mb-30">
                                    <a href="javascript:void(0);" class="coupon-close-btn"><i class="las la-times"></i></a>
                                </div>
                                <!-- <form id="voucherCodeForm"> -->
                                <div class="position-relative mb-40">
                                    <div class="search-blk voucher-code">
                                        <input class="search-box w-100" type="text" id="apply_coupon_text1" placeholder="Voucher Code" style="background-color: #f8f2f285;">
                                        <a href="javascript:void(0);" class="search-btn button apply_coupon_text1">Apply</a>
                                    </div>
                                    <div class="err-msg">Please enter a valid coupon.</div>

                                </div>
                                <!-- </form> -->
                                <h6 class="md b-font mb-2">AVAILABLE COUPONS</h6>
                                <div class="coupon-content mr-auto mb-5">
                                    <?php
                                    $coupon_array_range = [];
                                    foreach ($coupons as $coupon_post) {
                                        $coupon = new WC_Coupon($coupon_post->ID);
                                        $coupon_code     = $coupon->get_code();
                                        $coupon_minimum_spend = $coupon->get_minimum_amount();
                                        $coupon_maximum_spend = $coupon->get_maximum_amount();
                                        $coupon_array_range[$coupon_post->ID]['code'] = $coupon_code;
                                        $coupon_array_range[$coupon_post->ID]['min_value'] = $coupon_minimum_spend;
                                        $coupon_array_range[$coupon_post->ID]['max_value'] = $coupon_maximum_spend;
                                    }
                                    ?>
                                    <?php
                                    $displayed_coupon = false;
                                    foreach ($coupons as $coupon_post) {
                                        $coupon = new WC_Coupon($coupon_post->ID);
                                        $coupon_code     = $coupon->get_code();
                                        $coupon_amount   = $coupon->get_amount();
                                        $coupon_description   = $coupon->get_description();
                                        $coupon_discount = $coupon->get_discount_type();
                                        $coupon_expiry   = $coupon->get_date_expires();
                                        $coupon_minimum_spend = $coupon->get_minimum_amount();
                                        $coupon_maximum_spend = $coupon->get_maximum_amount();
                                        // $is_customer_specific_coupon = get_post_meta($coupon_post->ID, 'customer_eligibility', true);
                                        $multiple_coupons_available = get_post_meta($coupon_post->ID, 'multiple_coupons', true);
                                    ?>
                                        <?php
                                        if (($coupon_minimum_spend <= $coupon_cart_total && $coupon_maximum_spend >= $coupon_cart_total) && $multiple_coupons_available && $is_qualifying_customer) {
                                            $displayed_coupon = true;
                                        ?>
                                            <div class="coupon-row" data-minimum-spend="<?php echo esc_attr($coupon_minimum_spend); ?>" data-maximum-spend="<?php echo esc_attr($coupon_maximum_spend); ?>" data-customer-specific="<?php echo esc_attr($is_customer_specific_coupon); ?>" data-coupon-code="<?php echo esc_attr($coupon->get_code()); ?>">

                                                <!-- <div class="coupon-row  <?php echo $enable_coupon . '>>>' . $coupon_minimum_spend . "<=" . $coupon_cart_total . "&&" . $coupon_maximum_spend . ">=" . $coupon_cart_total; ?>"> -->
                                                <div class="offer-label type1 mb-3">
                                                    <span><?php echo ucfirst($coupon_code); ?></span>
                                                </div>
                                                <div>
                                                    <span class="md mb-3">Get <?php echo $coupon_amount; ?>% off</span>
                                                    <span class="md ash-color b-font mb-2" style="font-size: 11px;"><?php echo $coupon_description; ?></span>
                                                </div>
                                                <a href="javascript:void(0)" class="button mb tb condition_coupon" data-coupon="<?php echo $coupon_code; ?>" data-percentage="<?php echo $coupon_amount; ?>" data-coupon_minimum="<?php echo $coupon_minimum_spend; ?>" data-coupon_maximum="<?php echo $coupon_maximum_spend; ?>">Apply Coupon</a>

                                            </div>
                                        <?php
                                            //  break;
                                        } ?>
                                    <?php } ?>
                                    <?php if (!$displayed_coupon) {
                                    ?>

                                        <?php foreach ($coupons as $coupon_post) {
                                            $coupon = new WC_Coupon($coupon_post->ID);
                                            $coupon_code     = $coupon->get_code();
                                            $coupon_amount   = $coupon->get_amount();
                                            $coupon_description   = $coupon->get_description();
                                            $coupon_discount = $coupon->get_discount_type();
                                            $coupon_expiry   = $coupon->get_date_expires();
                                            $coupon_minimum_spend = $coupon->get_minimum_amount();
                                            $coupon_maximum_spend = $coupon->get_maximum_amount();
                                            // $is_customer_specific_coupon = get_post_meta($coupon_post->ID, 'customer_eligibility', true);
                                            $multiple_coupons_available = get_post_meta($coupon_post->ID, 'multiple_coupons', true);
                                        ?>



                                            <?php
                                            // Display available coupons only
                                            if (($coupon_minimum_spend <= $coupon_cart_total && $coupon_maximum_spend >= $coupon_cart_total && !$multiple_coupons_available) || $is_qualifying_customer && $is_customer_specific_coupon && !$multiple_coupons_available) {

                                            ?>
                                                <div class="coupon-row" data-minimum-spend="<?php echo esc_attr($coupon_minimum_spend); ?>" data-maximum-spend="<?php echo esc_attr($coupon_maximum_spend); ?>" data-customer-specific="<?php echo esc_attr($is_customer_specific_coupon); ?>" data-coupon-code="<?php echo esc_attr($coupon->get_code()); ?>">

                                                    <!-- <div class="coupon-row  <?php echo $enable_coupon . '>>>' . $coupon_minimum_spend . "<=" . $coupon_cart_total . "&&" . $coupon_maximum_spend . ">=" . $coupon_cart_total; ?>"> -->
                                                    <div class="offer-label type1 mb-3">
                                                        <span><?php echo ucfirst($coupon_code); ?></span>
                                                    </div>
                                                    <div>
                                                        <span class="md mb-3">Get <?php echo $coupon_amount; ?>% off</span>
                                                        <span class="md ash-color b-font mb-2" style="font-size: 11px;"><?php echo $coupon_description; ?></span>
                                                    </div>
                                                    <a href="javascript:void(0)" class="button mb tb condition_coupon" data-coupon="<?php echo $coupon_code; ?>" data-percentage="<?php echo $coupon_amount; ?>" data-coupon_minimum="<?php echo $coupon_minimum_spend; ?>" data-coupon_maximum="<?php echo $coupon_maximum_spend; ?>">Apply Coupon</a>

                                                </div>
                                    <?php
                                            }
                                        }
                                    } ?>
                                </div>
                                <span class="md b-font mb-2">UNAVAILABLE COUPONS</span>
                                <div class="coupon-content mr-auto">
                                    <?php
                                    $displayed_coupon = false;
                                    foreach ($coupons as $coupon_post) {
                                        $coupon = new WC_Coupon($coupon_post->ID);
                                        $coupon_code     = $coupon->get_code();
                                        $coupon_amount   = $coupon->get_amount();
                                        $coupon_description   = $coupon->get_description();
                                        $coupon_discount = $coupon->get_discount_type();
                                        $coupon_expiry   = $coupon->get_date_expires();
                                        $coupon_minimum_spend = $coupon->get_minimum_amount();
                                        $coupon_maximum_spend = $coupon->get_maximum_amount();
                                        // $is_customer_specific_coupon = get_post_meta($coupon_post->ID, 'customer_eligibility', true);
                                        $multiple_coupons_available = get_post_meta($coupon_post->ID, 'multiple_coupons', true);

                                        if (!($coupon_minimum_spend <= $coupon_cart_total && $coupon_maximum_spend >= $coupon_cart_total) && $multiple_coupons_available && $is_qualifying_customer) {

                                            $displayed_coupon = true;
                                    ?>
                                            <div class="coupon-row">
                                                <div class="offer-label type1 mb-3">
                                                    <span><?php echo ucfirst($coupon_code); ?></span>
                                                </div>
                                                <div>
                                                    <span class="md mb-3">Get <?php echo $coupon_amount; ?>% off</span>
                                                    <span class="md ash-color b-font mb-2" style="font-size: 11px;"><?php echo $coupon_description; ?></span>
                                                </div>
                                                <a href="javascript:void(0)" class="button mb tb disable">Apply Coupon</a>
                                            </div>
                                    <?php
                                            // break;
                                        }
                                    } ?>
                                    <?php if (!$displayed_coupon) {
                                    ?>
                                        <?php foreach ($coupons as $coupon_post) {
                                            $coupon = new WC_Coupon($coupon_post->ID);
                                            $coupon_code     = $coupon->get_code();
                                            $coupon_amount   = $coupon->get_amount();
                                            $coupon_description   = $coupon->get_description();
                                            $coupon_discount = $coupon->get_discount_type();
                                            $coupon_expiry   = $coupon->get_date_expires();
                                            $coupon_minimum_spend = $coupon->get_minimum_amount();
                                            $coupon_maximum_spend = $coupon->get_maximum_amount();
                                            // $is_customer_specific_coupon = get_post_meta($coupon_post->ID, 'customer_eligibility', true);
                                            $multiple_coupons_available = get_post_meta($coupon_post->ID, 'multiple_coupons', true);


                                            if (!($coupon_minimum_spend <= $coupon_cart_total && $coupon_maximum_spend >= $coupon_cart_total) && !$multiple_coupons_available && !($is_qualifying_customer && $is_customer_specific_coupon)) {

                                        ?>
                                                <div class="coupon-row">
                                                    <div class="offer-label type1 mb-3">
                                                        <span><?php echo ucfirst($coupon_code); ?></span>
                                                    </div>
                                                    <div>
                                                        <span class="md mb-3">Get <?php echo $coupon_amount; ?>% off</span>
                                                        <span class="md ash-color b-font mb-2" style="font-size: 11px;"><?php echo $coupon_description; ?></span>
                                                    </div>
                                                    <a href="javascript:void(0)" class="button mb tb disable">Apply Coupon</a>
                                                </div>
                                    <?php
                                            }
                                        }
                                    } ?>
                                </div>
                            </div>
                            <script>
                                // Convert PHP array to JavaScript array
                                var defaultCoupons = <?php echo json_encode($coupon_array_range); ?>;
                                console.log(defaultCoupons);
                            </script>
                            <!-- slide coupon end -->
                             <!-- popup massege start -->
                            <div class="popup-box-blk d-xl-none" style="background-color: #242222e0;">
                                <div class="popup-box text-center type1">
                                    <div class="coupon-tick"><img src="<?php echo get_bloginfo('template_url'); ?>/lib/images/discount1.svg" alt="coupon"></div>
                                    <div class="saving_amount px-4">
                                        <!-- <span class="md">5% OFF APPLIED</span> -->
                                        <h1 class="mb-1" id="coupon_saving_amount1">₹250</h1>
                                        <span class="md mb-4">savings with this coupon</span>
                                        <!-- <span class="md b-font">Happy to have you back! Look out for great offers on every order</span> -->
                                    </div>
                                    <a href="javascript:void(0);" class="button tb mb popup-close yay mt-4">YAY!</a>

                                </div>
                            </div>

                            <!-- popup massege end -->