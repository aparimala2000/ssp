<?php
ob_start();
add_theme_support('woocommerce');
// To add navigation menu widget
add_theme_support('menus');
include('inc/logo.php');
include('inc/additional_functions.php');
include('inc/cpt/recipes.php');
include('inc/pagination.php');
include('inc/list/newsletter.php');
include('inc/list/contact_list.php');
include('inc/ajax/newsletter_ajax.php');
include('inc/ajax/contact_ajax.php');
include('inc/template.php');
include('inc/metaboxes/custom_shipping.php');
// Shipping values
include('inc/metaboxes/global-country-charge.php');
include('inc/metaboxes/special-shipping-options.php');
include('inc/metaboxes/special-shipping-price.php');
// Shipping Calculation
include('inc/shipping-calc.php');
// SMS code	
include('inc/sms-functions.php');
// Visitor Country
include('inc/visitor-cnt.php');
include('inc/metaboxes/product_type.php');
include('inc/metaboxes/offer_content.php');

// function add_cors_headers() {
//     header("Access-Control-Allow-Origin: http://localhost/ssp");
//     header("Access-Control-Allow-Headers: Content-Type");
// }

// add_action('init', 'add_cors_headers');

// Redirect to homepage if cart is empty on checkout page
add_action('template_redirect', 'redirect_empty_cart_checkout');
function redirect_empty_cart_checkout()
{
    if (is_cart() && WC()->cart->is_empty()) {
        wp_redirect(home_url());
        exit;
    } elseif (is_cart() && !(WC()->cart->is_empty())) {
        $prev_url = home_url() . '/home-use';
        wp_redirect($prev_url);
        exit;
    }
}
//To show admin icons
function replace_admin_menu_icons_css()
{
?>
    <style>
        #adminmenu #toplevel_page_logo-settings .menu-icon-generic div.wp-menu-image:before {
            font-family: dashicons !important;
            content: '\f128' !important;
            font-size: 1.3em !important;
        }

        #adminmenu #toplevel_page_newsletter .menu-icon-generic div.wp-menu-image:before {
            font-family: dashicons !important;
            content: '\f12e' !important;
            font-size: 1.3em !important;
        }

        #adminmenu #toplevel_page_email-settings .menu-icon-generic div.wp-menu-image:before {
            font-family: dashicons !important;
            content: '\f465' !important;
            font-size: 1.3em !important;
        }

        #adminmenu #toplevel_page_contactList .menu-icon-generic div.wp-menu-image:before {
            font-family: dashicons !important;
            content: '\f12e' !important;
            font-size: 1.3em !important;
        }
    </style>
    <?php
}

add_action('admin_head', 'replace_admin_menu_icons_css');
// only show parent category in url
add_filter('woocommerce_product_post_type_link_parent_category_only', '__return_true');
//post type product and page backend hide star ratings
function hide_ratings_column($columns)
{
    unset($columns['ratings']);
    return $columns;
}
add_filter('manage_product_posts_columns', 'hide_ratings_column');
add_filter('manage_page_posts_columns', 'hide_ratings_column');
function remove_select2_styles()
{
    wp_dequeue_style('select2');
}
add_action('wp_enqueue_scripts', 'remove_select2_styles', 100);
// To hide Dashboard in frontend
show_admin_bar(false);
//Header cart count update without reload
function update_cart_count_ajax_handler()
{
    $cart_count = WC()->cart->get_cart_contents_count();
    echo $cart_count;
    die();
}
add_action('wp_ajax_update_cart_count', 'update_cart_count_ajax_handler');
add_action('wp_ajax_nopriv_update_cart_count', 'update_cart_count_ajax_handler');

// Add a custom metabox for the free product option
add_action('add_meta_boxes', 'add_custom_metabox');

function add_custom_metabox()
{
    add_meta_box(
        'custom_metabox',
        __('Free Product Option', 'ssp'),
        'render_custom_metabox',
        'product',
        'normal',
        'high'
    );
}

function render_custom_metabox()
{
    global $post;

    // Get the saved checkbox value
    $checkbox_value = get_post_meta($post->ID, 'custom_checkbox', true);

    // Output the custom checkbox field
    echo '<label for="custom_checkbox">';
    echo '<input type="checkbox" id="custom_checkbox" name="custom_checkbox" value="yes" ' . checked($checkbox_value, 'yes', false) . '/>';
    echo __('Check this box if the product is eligible for a free product.', 'ssp');
    echo '</label>';
}

// Save the custom checkbox field value when the product is saved
add_action('save_post_product', 'save_custom_checkbox_field');

function save_custom_checkbox_field($post_id)
{
    if (isset($_POST['custom_checkbox'])) {
        update_post_meta($post_id, 'custom_checkbox', 'yes');
    } else {
        update_post_meta($post_id, 'custom_checkbox', 'no');
    }
}

function add_free_product_to_cart()
{
    // Check if we are on the checkout page
    if (is_checkout()) {
        // Specify the ID of the free product to be added
        $free_product_id = 725;

        // Check if the free product is already in the cart
        $free_product_in_cart = false;

        foreach (WC()->cart->get_cart() as $cart_item) {
            if ($cart_item['product_id'] === $free_product_id) {
                $free_product_in_cart = true;
                break;
            }
        }

        // If the free product is not in the cart, add it
        if (!$free_product_in_cart) {
            // Get the checkbox value from the post meta for the cart item product ID
            $cart_item_product_id = $cart_item['product_id'];
            $checkbox_value = get_post_meta($cart_item_product_id, 'custom_checkbox', true);

            // Check if the checkbox value is 'yes'
            if ($checkbox_value === 'yes') {
                // Add the free product with a quantity of 1
                WC()->cart->add_to_cart($free_product_id, 1);
            }
        }
    }
}

add_action('template_redirect', 'add_free_product_to_cart');


// remove free product from cart using product status
add_action('wp', 'remove_draft_free_product_row_on_single_product');

function remove_draft_free_product_row_on_single_product()
{
    // Check if it is the single product page
    if (is_product()) {
        // Specify the ID of the free product
        $free_product_id = 725; // Replace with your free product ID
        $status_to_remove = 'draft';

        $free_product_key = null;
        $free_product_found = false;

        // Get the global cart object
        global $woocommerce;

        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
            $product_id = $cart_item['product_id'];
            if ($product_id === $free_product_id) {
                $free_product_key = $cart_item_key;
                $free_product_found = true;
            }
        }

        if ($free_product_found) {
            $product_status = get_post_status($free_product_id);
            if ($product_status === $status_to_remove) {
                $woocommerce->cart->remove_cart_item($free_product_key);
            }
        }
    }
}
// Add the AJAX action for emptying the cart because free product
add_action('wp_ajax_empty_cart', 'empty_cart_callback');
add_action('wp_ajax_nopriv_empty_cart', 'empty_cart_callback');

// Callback function to empty the cart
function empty_cart_callback()
{
    if (WC()->cart->is_empty()) {
        // Cart is already empty
        wp_send_json_success();
    } else {
        WC()->cart->empty_cart();
        wp_send_json_success();
    }
}


// My Account Information hide dispaly name field
add_filter('woocommerce_save_account_details_required_fields', 'remove_required_fields');
function remove_required_fields($required_fields)
{
    unset($required_fields['account_display_name']);
    return $required_fields;
}


add_filter('woocommerce_add_message', 'remove_save_account_details_success_message');
// Success msg hide
function remove_save_account_details_success_message($message)
{
    if (strpos($message, 'Account details changed successfully.') !== false) {
        return '';
    }
    return $message;
}


// Add Address Book

function add_user_address($user_id, $address)
{
    $addresses = get_user_meta($user_id, 'user_addresses', true);
    if (!$addresses) {
        $addresses = array();
    }
    $addresses[] = $address;
    update_user_meta($user_id, 'user_addresses', $addresses);
}
add_action('wp_ajax_update_billing_address', 'update_billing_address_callback');
add_action('wp_ajax_nopriv_update_billing_address', 'update_billing_address_callback');
function update_billing_address_callback()
{
    if (!did_action('woocommerce_init')) {
        do_action('woocommerce_init');
    }

    $user_id = get_current_user_id();

    if ($user_id && current_user_can('edit_user', $user_id)) {
        $billing_address = array(
            'first_name' => sanitize_text_field($_POST['billing_first_name']),
            'last_name' => sanitize_text_field($_POST['billing_last_name']),
            'email' => sanitize_email($_POST['billing_email']),
            'phone' => sanitize_text_field($_POST['billing_phone']),
            'address_1' => sanitize_text_field($_POST['billing_address_1']),
            'city' => sanitize_text_field($_POST['billing_city']),
            'state' => sanitize_text_field($_POST['billing_state']),
            'postcode' => sanitize_text_field($_POST['billing_postcode']),
            'country' => sanitize_text_field($_POST['billing_country']),
            'address_group' => sanitize_text_field($_POST['address_group']),
        );

        add_user_address($user_id, $billing_address);
        $addresses = get_user_meta($user_id, 'user_addresses', true);
        ob_start(); ?>
        <div class="row mb-30">
            <?php
            foreach ($addresses as $index => $address) {
                $Fname = $address["first_name"];
                $Lname = $address["last_name"];
                $email = $address["email"];
                $phone = $address["phone"];
                $address_1 = $address["address_1"];
                $city = $address["city"];
                $state = $address["state"];
                $postcode = $address["postcode"];
                $address_group = $address["address_group"];
                $country = $address["country"]; ?>
                <div class="col-sm-6 mb-30" id="address-list">
                    <div class="with-card">
                        <div class="normal-cards">
                            <div>
                                <div class="d-flex justify-content-between">
                                    <h6><?php echo $address_group; ?></h6>
                                    <div class="d-flex">
                                        <a href="javascript:void(0)" title="Edit" class="circle-icon type2 mr-1 edit_address" id="edit_address" data-index="<?php echo $index; ?>" data-fname=" <?php echo $Fname; ?>" data-lname="<?php echo $Lname; ?>" data-email="<?php echo $email; ?>" data-phone="<?php echo $phone; ?>" data-address1="<?php echo $address_1; ?>" data-city="<?php echo $city; ?>" data-state="<?php echo $state; ?>" data-postcode="<?php echo $postcode; ?>" data-country="<?php echo $country; ?>" data-address_group="<?php echo $address_group; ?>"><i class="las la-pencil"></i></a>
                                        <a href="#" title="Remove" class="circle-icon type2 remove-address" data-index="<?php echo $index; ?>"><i class="las la-trash"></i></a>
                                    </div>
                                </div>
                                <div class="ash-color">
                                    <p><?php echo $Fname . ' ' . $Lname . ','; ?></p>
                                    <p><?php echo $email . ','; ?></p>
                                    <p><?php echo $phone . ','; ?></p>
                                    <p><?php echo $address_1 . ' ' . $city . ','; ?></p>
                                    <p><?php echo $state . '-' . $postcode . ','; ?></p>
                                    <p><?php echo $country . '.'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="col-sm-6 mb-30">
                <div class="with-card add-adrs-btn  add-adrs-btn1">
                    <div class="normal-cards d-flex primary">
                        <span class="mr-2"><i class="fa fa-plus" aria-hidden="true"></i></span>
                        <h6 class="pb-0">Add Address</h6>
                    </div>
                </div>
            </div>
        </div>

    <?php
        $address_list_html = ob_get_clean();
        echo $address_list_html;
        // echo "Address added successfully.";
    } else {
        echo "You do not have permission to update the address.";
    }

    wp_die();
}

// Update address book
function update_user_address()
{

    $user_id = get_current_user_id();

    // Get the updated address data from the AJAX request
    $address = array(
        'first_name' => sanitize_text_field($_POST['first_name']),
        'last_name' => sanitize_text_field($_POST['last_name']),
        'email' => sanitize_text_field($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
        'address_1' => sanitize_text_field($_POST['address_1']),
        'city' => sanitize_text_field($_POST['city']),
        'state' => sanitize_text_field($_POST['state']),
        'postcode' => sanitize_text_field($_POST['postcode']),
        'country' => sanitize_text_field($_POST['country']),
        'address_group' => sanitize_text_field($_POST['address_group']),
    );

    // Get the current user addresses
    $addresses = get_user_meta($user_id, 'user_addresses', true);

    // Get the index of the address to be updated
    $address_index = intval($_POST['address_index']);

    // Update the address data
    $addresses[$address_index] = $address;

    // Save the updated addresses
    update_user_meta($user_id, 'user_addresses', $addresses);

    ob_start(); ?>
    <div class="row mb-30">
        <?php
        foreach ($addresses as $index => $address) {
            $Fname = $address["first_name"];
            $Lname = $address["last_name"];
            $email = $address["email"];
            $phone = $address["phone"];
            $address_1 = $address["address_1"];
            $city = $address["city"];
            $state = $address["state"];
            $postcode = $address["postcode"];
            $country = $address["country"];
            $address_group = $address["address_group"];
        ?>
            <div class="col-sm-6 mb-30" id="address-list">
                <div class="with-card">
                    <div class="normal-cards">
                        <div>
                            <div class="d-flex justify-content-between">
                                <h6><?php echo $address_group; ?></h6>
                                <div class="d-flex">
                                    <a href="javascript:void(0)" title="Edit" class="circle-icon type2 mr-1 edit_address" id="edit_address" data-index="<?php echo $index; ?>" data-fname="<?php echo $Fname; ?>" data-lname="<?php echo $Lname; ?>" data-email="<?php echo $email; ?>" data-phone="<?php echo $phone; ?>" data-address1="<?php echo $address_1; ?>" data-city="<?php echo $city; ?>" data-state="<?php echo $state; ?>" data-postcode="<?php echo $postcode; ?>" data-country="<?php echo $country; ?>" data-address_group="<?php echo $address_group; ?>"><i class="las la-pencil"></i></a>
                                    <a href="#" title="Remove" class="circle-icon type2 remove-address" data-index="<?php echo $index; ?>"><i class="las la-trash"></i></a>
                                </div>
                            </div>
                            <div class="ash-color">
                                <p><?php echo $Fname . ' ' . $Lname . ','; ?></p>
                                <p><?php echo $email . ','; ?></p>
                                <p><?php echo $phone . ','; ?></p>
                                <p><?php echo $address_1 . ' ' . $city . ','; ?></p>
                                <p><?php echo $state . '-' . $postcode . ','; ?></p>
                                <p><?php echo $country . '.'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }  ?>
        <div class="col-sm-6 mb-30">
            <div class="with-card add-adrs-btn  add-adrs-btn1">
                <div class="normal-cards d-flex primary">
                    <span class="mr-2"><i class="fa fa-plus" aria-hidden="true"></i></span>
                    <h6 class="pb-0">Add Address</h6>
                </div>
            </div>
        </div>
    </div>

    <?php
    $html = ob_get_clean();
    // echo $html;
    wp_send_json_success(array(
        'message' => __('Address updated successfully.', 'textdomain'),
        'html' => $html,
    ));
    wp_die();
}
add_action('wp_ajax_update_user_address', 'update_user_address');
add_action('wp_ajax_nopriv_update_user_address', 'update_user_address');

// Remove Address book
add_action('wp_ajax_remove_address', 'remove_address_from_address_book');
add_action('wp_ajax_nopriv_remove_address', 'remove_address_from_address_book');

function remove_address_from_address_book()
{
    $address_index = $_POST['address_index'];
    $user_id = get_current_user_id();
    $addresses = get_user_meta($user_id, 'user_addresses', true);

    if (isset($addresses[$address_index])) {
        unset($addresses[$address_index]);
        update_user_meta($user_id, 'user_addresses', $addresses);
        ob_start(); ?>
        <div class="row mb-30">
            <?php
            foreach ($addresses as $index => $address) {
                $Fname = $address["first_name"];
                $Lname = $address["last_name"];
                $email = $address["email"];
                $phone = $address["phone"];
                $address_1 = $address["address_1"];
                $city = $address["city"];
                $state = $address["state"];
                $postcode = $address["postcode"];
                $country = $address["country"];
                $address_group = $address["address_group"];
            ?>
                <div class="col-sm-6 mb-30" id="address-list">
                    <div class="with-card">
                        <div class="normal-cards">
                            <div>
                                <div class="d-flex justify-content-between">
                                    <h6><?php echo $address_group; ?></h6>
                                    <div class="d-flex">
                                        <a href="javascript:void(0)" title="Edit" class="circle-icon type2 mr-1 edit_address" id="edit_address" data-index="<?php echo $index; ?>" data-fname="<?php echo $Fname; ?>" data-lname="<?php echo $Lname; ?>" data-email="<?php echo $email; ?>" data-phone="<?php echo $phone; ?>" data-address1="<?php echo $address_1; ?>" data-city="<?php echo $city; ?>" data-state="<?php echo $state; ?>" data-postcode="<?php echo $postcode; ?>" data-country="<?php echo $country; ?>" data-address_group="<?php echo $address_group; ?>"><i class="las la-pencil"></i></a>
                                        <a href="#" title="Remove" class="circle-icon type2 remove-address" data-index="<?php echo $index; ?>"><i class="las la-trash"></i></a>
                                    </div>
                                </div>
                                <div class="ash-color">
                                    <p><?php echo $Fname . ' ' . $Lname . ','; ?></p>
                                    <p><?php echo $email . ','; ?></p>
                                    <p><?php echo $phone . ','; ?></p>
                                    <p><?php echo $address_1 . ' ' . $city . ','; ?></p>
                                    <p><?php echo $state . '-' . $postcode . ','; ?></p>
                                    <p><?php echo $country . '.'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }  ?>
            <div class="col-sm-6 mb-30">
                <div class="with-card add-adrs-btn  add-adrs-btn1">
                    <div class="normal-cards d-flex primary">
                        <span class="mr-2"><i class="fa fa-plus" aria-hidden="true"></i></span>
                        <h6 class="pb-0">Add Address</h6>
                    </div>
                </div>
            </div>
        </div>

    <?php
        $html = ob_get_clean();
        wp_send_json_success(array(
            'message' => __('Address removed successfully.', 'textdomain'),
            'html' => $html,
        ));
        wp_die();
        // wp_send_json_success('Address removed successfully.');
    } else {
        wp_send_json_error('Address not found.');
    }
}

// Cancel order 
add_action('wp_ajax_cancel_order', 'cancel_order');
add_action('wp_ajax_nopriv_cancel_order', 'cancel_order');

function cancel_order()
{
    $order_id = $_POST['order_id'];
    $order = wc_get_order($order_id);

    if ($order) {
        $order->update_status('cancelled');
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }

    wp_die();
}


// Registration form
/*******
Register Extra Fields
 *******/
function wooc_extra_register_fields()
{
    ?>
    <div class="floating-blk">
        <label for="first_name" class="floating-row">
            <span class="floating-label">First Name</span>
            <input class="floating-input" type="text" id="first_name" value="<?php if (!empty($_POST['first_name'])) echo esc_attr($_POST['first_name']); ?>" onKeyPress="return isText(event);" />
            <p class="floating-input-error">Looks like you’ve not entered your first name.</p>
        </label>
    </div>
    <div class="floating-blk">
        <label for="last_name" class="floating-row">
            <span class="floating-label">Last Name</span>
            <input class="floating-input" type="text" id="last_name" value="<?php if (!empty($_POST['last_name'])) echo esc_attr($_POST['last_name']); ?>" onKeyPress="return isText(event);" />
            <p class="floating-input-error">It seems like you’ve forgotten to enter your last name.</p>
        </label>
    </div>
    <div class="floating-blk">
        <label for="phone" class="floating-row">
            <span class="floating-label">Phone Number</span>
            <input class="floating-input" type="text" name="phone" id="phone" value="<?php if (!empty($_POST['telephone'])) echo esc_attr($_POST['telephone']); ?>" maxlength="15" onKeyPress="return isNumber(event);" />
            <p class="floating-input-error" id="phone-err">Please enter your phone number. It's important to us.</p>
        </label>
    </div>
<?php
}
add_action('woocommerce_register_form_start', 'wooc_extra_register_fields');
add_action('woocommerce_created_customer', 'wooc_save_extra_register_fields');
function wooc_save_extra_register_fields($customer_id)
{
    if (isset($_POST['first_name'])) {
        // WordPress default first name field.
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['first_name']));
    }
    if (isset($_POST['last_name'])) {
        // WordPress default last name field.
        update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['last_name']));
    }
    // WooCommerce billing phone
    if (isset($_POST['telephone'])) {
        update_user_meta($customer_id, 'telephone', sanitize_text_field($_POST['telephone']));
    }
}
function new_user_reg_form($param)
{
?>
    <div class="mb-3 reg-form">
        <h3 class="mb-3">Sign up</h3>
        <?php
        $Introcopy = get_field('introcopy');
        echo $Introcopy;
        ?>
    </div>
    <div class="alert-msg reg-err mb-40" style="display:none;">
        <p>Oh no!! We couldn’t find your account. Check your details and retry.</p>
    </div>
    <form method="post" class="register bottom-line" id="registerform">


        <?php do_action('woocommerce_register_form_start'); ?>
        <div class="floating-blk">
            <label for="email" class="floating-row">
                <span class="floating-label">Email</span>
                <input class="floating-input" type="email" id="email" value="<?php if (!empty($_POST['email'])) echo esc_attr($_POST['email']); ?>" />
                <p class="floating-input-error" id="email-err">Please enter your email. It’s critical to contact you.</p>
            </label>
        </div>
        <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
        <ul class="mb-30">
            <li>
                <div class="checkbox-animate regular terms-err">
                    <label>
                        <input type="checkbox" name="" value="" id="checkbox2">
                        <span class="tick-anim"></span>
                        By registering, you agree to the &nbsp;<a class="link-anim" href="<?php echo get_bloginfo('url') . '/terms-and-conditions'; ?>">Terms & Conditions.</a>
                    </label>
                    <p class="floating-input-error">Please check this box, indicating your agreement with our Terms & Conditions.</p>
                </div>
            </li>
            <li>
                <div class="checkbox-animate regular">
                    <label>
                        <input type="checkbox" name="" value="checkbox">
                        <span class="tick-anim"></span>
                        I would like to opt in to receive your marketing communications.
                    </label>
                </div>
            </li>
        </ul>
        <a href="javascript:void(0);" class="button mb register_otp_btn">Submit</a>
    </form>
<?php
}
add_shortcode("new_user_reg_form", "new_user_reg_form");

/***** OTP Login registration verification *******/
function otp_verify_reg($param)
{
?>
    <form method="post" class="otp_reg_form" id="otp_reg_form" style="display:none">
        <div class="floating-blk">
            <label for="reg_otp_val" class="floating-row">
                <span class="floating-label">Enter the OTP</span>
                <input type="text" class="floating-input" name="reg_otp_val" id="reg_otp_val" value="<?php if (!empty($_POST['reg_otp_val'])) echo esc_attr($_POST['reg_otp_val']); ?>" onKeyPress="return isNumber(event);" maxlength="6" />
            </label>
            <p id="reg_otp_err" class="floating-input-error">Please enter the OTP sent to the registered mobile number/ email address</p>
        </div>
        <a href="javascript:void(0);" class="button mb register_btn">Submit</a>
        <div class="mb-40"></div>
        <p>If you haven't received OTP yet, please <a href="javascript:void(0)" class="link-anim resendOtpReg">click here.</a></p>
    </form>
<?php
}
add_shortcode('otp_verify_reg', 'otp_verify_reg');

/*
 * To check whether the phone number already exists.
 */
function phone_exists($phone)
{
    $users = get_users(array(
        'meta_key'     => 'phone',
        'meta_value'   => $phone,
        'meta_compare' => '==',
    ));
    $users = array_filter($users);
    if (empty($users)) {
        return false;
    } else {
        foreach ($users as $user) {

            $usrVerf = get_user_meta($user->ID, 'otp_verified', true);
            $user_nonverified_customer = get_user_role($user->ID);
            if (($usrVerf == 'No' || $usrVerf == '') && ($user_nonverified_customer != 'administrator')) {
                wp_delete_user($user->ID);
                return false;
            } elseif ($usrVerf == 'Yes') {
                $usrVerfId = $user->ID;

                return $usrVerfId;
            }
        }
    }
}
/*
 * Create New customer
 */
function extnd_create_new_customer($email, $phone, $username = '', $password = '')
{
    // Check the email address.
    if (empty($email) || !is_email($email)) {
        return new WP_Error('registration-error-invalid-email', __('Please provide a valid email address.', 'woocommerce'));
    }
    if (email_exists($email)) {

        return new WP_Error('registration-error-email-exists', __('An account is already registered with your email address. Please login.', 'woocommerce'));
    }

    if (phone_exists($phone)) {
        return new WP_Error('registration-error-phone-exists', __('An account is already registered with your phone number. Please login.', 'woocommerce'));
    }


    // Handle username creation.
    if ('no' === get_option('woocommerce_registration_generate_username') || !empty($username)) {
        $username = sanitize_user($username);
        if (empty($username) || !validate_username($username)) {
            return new WP_Error('registration-error-invalid-username', __('Please enter a valid account username.', 'woocommerce'));
        }
        if (username_exists($username)) {
            return new WP_Error('registration-error-username-exists', __('An account is already registered with that username. Please choose another.', 'woocommerce'));
        }
    } else {
        $username = sanitize_user(current(explode('@', $email)), true);
        // Ensure username is unique.
        $append     = 1;
        $o_username = $username;
        while (username_exists($username)) {
            $username = $o_username . $append;
            $append++;
        }
    }
    // Handle password creation.
    if ('yes' === get_option('woocommerce_registration_generate_password') && empty($password)) {
        $password           = wp_generate_password();
        $password_generated = true;
    } elseif (!empty($password)) {
        return new WP_Error('registration-error-missing-password', __('Please enter an account password.', 'woocommerce'));
    } else {
        $password_generated = false;
    }
    // Use WP_Error to handle registration errors.
    $errors = new WP_Error();
    do_action('woocommerce_register_post', $username, $email, $errors);
    $errors = apply_filters('woocommerce_registration_errors', $errors, $username, $email);
    if ($errors->get_error_code()) {
        return $errors;
    }
    $new_customer_data = apply_filters('woocommerce_new_customer_data', array(
        'user_login' => $username,
        'user_pass'  => $password,
        'user_email' => $email,
        'role'       => 'customer',
    ));
    $customer_id = wp_insert_user($new_customer_data);
    if (is_wp_error($customer_id)) {
        return new WP_Error('registration-error', '<strong>' . __('Error:', 'woocommerce') . '</strong> ' . __('Couldn&#8217;t register you&hellip; please contact us if you continue to have problems.', 'woocommerce'));
    }
    return $customer_id;
}
/*
 * OTP Curl api for wp
 */
function otp_snd($phone, $gen_otp)
{
    // $feedUrl = "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=55ac36b7-6881-11e7-94da-0200cd936042&to=$phone&from=sspotp&templatename=sspotp&var1=$gen_otp";
    $feedUrl = "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=55ac36b7-6881-11e7-94da-0200cd936042&to=$phone&from=SSPING&templatename=ssping&var1=$gen_otp";
    $ch = curl_init();
    $headers = array(
        'Content-Type: application/x-www-form-urlencoded',
        'charset: utf-8',
    );
    curl_setopt($ch, CURLOPT_URL, $feedUrl);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    //$header = substr($output, 0, $header_size);
    $body = substr($output, $header_size);
    //$myXMLData = new SimpleXMLElement($body);
    curl_close($ch);
    return $feedUrl;
}
/*Register form*/
function woocommerce_register_form($args = array())
{
    $defaults = array(
        'message'  => '',
        'redirect' => '',
        'hidden'   => false
    );
    $args = wp_parse_args($args, $defaults);

    wc_get_template('global/form-register.php', $args);
}

// OTP form
function otp_login_form()
{
    $idlgform = is_page_template('ST_Checkout.php') ? 'chkloginform' : 'loginform';
?>
    <div class="mb-3 reg-form">
        <h3 class="mb-3">Sign in</h3>
        <?php
        $Introcopy = get_field('introcopy');
        echo $Introcopy;
        ?>
    </div>
    <div class="alert-msg log-err mb-40" style="display:none;">
        <p>Oh no!! We couldn’t find your account. Check your details and retry.</p>
    </div>
    <input type="hidden" id="guest_customer_id">
    <input type="hidden" id="user_customer_id">
    <form method="post" class="login_otp" id="<?php echo $idlgform; ?>">
        <div class="floating-blk">
            <label for="uname" class="floating-row">
                <span class="floating-label">Email / Phone Number</span>
                <input type="text" class="floating-input" maxlength="100" name="email" id="username" value="<?php if (!empty($_POST['email'])) echo esc_attr($_POST['email']); ?>" />
                <p id="log_email" class="floating-input-error">Looks like you’ve forgotten to, or entered an invalid email or phone number.</p>
            </label>
        </div>
        <?php wp_nonce_field('woocommerce-login'); ?>
        <?php if (!is_page_template('ST_Checkout.php')) { ?>
            <a href="javascript:void(0);" class="button mb login_otp_func" id="otp_login">Sign in</a>
        <?php } else { ?>
            <a href="javascript:void(0);" class="button mb login_otp_func" id="otp_login">Proceed</a>
            <input type="hidden" name="redirectUrlOtp" id="redirectUrlOtp" value="/checkout" />
        <?php } ?>
        <?php do_action('woocommerce_login_form_end'); ?>
    </form>
<?php
}
add_shortcode('otp_login_form', 'otp_login_form');
/***** OTP Login verifcation *******/
function otp_verify_lgin($param)
{
?>
    <div class="alert-msg otp-err mb-40" style="display:none;">
        <p class="otp_err_txt">Oh no!! We couldn’t find your account. Check your details and retry.</p>
    </div>
    <form name="guest_checkout_otp" class="guest_checkout_otp" method="post" style="display:none;">
        <div class="floating-blk">
            <label for="guest_user" class="floating-row">
                <span class="floating-label">Enter the OTP</span>
                <input class="floating-input" type="text" id="guest_user_otp" maxlength="6">
                <p class="floating-input-error guest_otp">Here’s where you enter the OTP sent to your registered mobile number / email.</p>
            </label>
        </div>
        <a href="javascript:void(0);" class="button mb tb guest_otp_verify">Submit</a>
        <span class="md">If you haven't received OTP yet, please <a href="javascript:void(0)" class="link-anim resendOtpguestLogin">click here.</a></span>
    </form>
    <form name="user_checkout_otp" class="user_checkout_otp" method="post" style="display:none;">
        <div class="floating-blk">
            <label for="chkout_user" class="floating-row">
                <span class="floating-label">Enter the OTP</span>
                <input class="floating-input" type="text" id="chkout_user_otp" maxlength="6">
                <p class="floating-input-error chkout_otp">Here’s where you enter the OTP sent to your registered mobile number / email.</p>
            </label>
        </div>
        <a href="javascript:void(0);" class="button mb tb chkout_otp_verify">Submit</a>
        <span class="md">If you haven't received OTP yet, please <a href="javascript:void(0)" class="link-anim resendOtpuserLogin">click here.</a></span>
    </form>
<?php
}
add_shortcode('otp_verify_lgin', 'otp_verify_lgin');


// Cart

function ajax_qty_cart()
{

    // Set item key as the hash found in input.qty's name
    $cart_item_key = $_POST['hash'];

    // Get the array of values owned by the product we're updating
    $threeball_product_values = WC()->cart->get_cart_item($cart_item_key);

    // Get the quantity of the item in the cart
    $threeball_product_quantity = apply_filters('woocommerce_stock_amount_cart_item', apply_filters('woocommerce_stock_amount', preg_replace("/[^0-9\.]/", '', filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT))), $cart_item_key);

    // Update cart validation
    $passed_validation  = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $threeball_product_values, $threeball_product_quantity);

    // Update the quantity of the item in the cart
    if ($passed_validation) {
        WC()->cart->set_quantity($cart_item_key, $threeball_product_quantity, true);
    }

    // Refresh the page
    echo do_shortcode('[woocommerce_cart]');

    die();
}

// add_action('wp_ajax_qty_cart', 'ajax_qty_cart');
// add_action('wp_ajax_nopriv_qty_cart', 'ajax_qty_cart');
function get_variation_data_from_variation_id($itemId)
{
    $_product = new WC_Product_Variation($itemId);
    $variationData = $_product->get_variation_attributes();
    $variationDetail = woocommerce_get_formatted_variation($variationData, true);  // this will give all variation detail in one line
    return $variationDetail; // $variation_detail will return string containing variation detail which can be used to print on website
}

// Recently viewed products - start
add_shortcode('recently_viewed_products', 'bbloomer_recently_viewed_shortcode');
function bbloomer_recently_viewed_shortcode()
{

    $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed'])) : array();
    // $viewed_products = array_slice($viewed_products, 0, 30);
    $viewed_products = array_slice($viewed_products, 0, 20);
    if (empty($viewed_products)) return;
    $product_ids = implode(",", $viewed_products);
    return do_shortcode($product_ids);
}

// adds notice at single product page above add to cart
add_action('woocommerce_after_single_product', 'recviproducts', 31);
function recviproducts()
{
    echo do_shortcode('[recently_viewed_products]');
}
// https://github.com/woocommerce/woocommerce/issues/9724#issuecomment-160618200
function custom_track_product_view()
{
    if (!is_singular('product')) {
        return;
    }

    global $post;

    if (empty($_COOKIE['woocommerce_recently_viewed']))
        $viewed_products = array();
    else
        $viewed_products = (array) explode('|', $_COOKIE['woocommerce_recently_viewed']);

    if (!in_array($post->ID, $viewed_products)) {
        $viewed_products[] = $post->ID;
    }

    if (sizeof($viewed_products) > 15) {
        array_shift($viewed_products);
        // array_shift(array_reverse($viewed_products));
    }
    // Store for session only
    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
}

add_action('template_redirect', 'custom_track_product_view', 20);
// Recently viewed products - end

// Update cart on click
function update_item_from_cart()
{
    $cart_item_key = $_POST['cart_item_key'];
    $quantity = $_POST['qty'];
    $variation_id = $_POST['variation_id'];
    // Update quantity in the cart
    foreach (WC()->cart->get_cart() as $key => $cart_item) {
        if ($key === $cart_item_key) {
            WC()->cart->set_quantity($key, $quantity, $refresh_totals = true);
            break;
        }
    }

    // Recalculate totals
    WC()->cart->calculate_totals();
    WC()->cart->maybe_set_cart_cookies();

    // Get updated quantity, price, and total
    $updated_quantity = 0;
    $updated_price = 0;
    $updated_total = 0;
    $product_id = 0;

    foreach (WC()->cart->get_cart() as $key => $cart_item) {
        if ($key === $cart_item_key) {
            $updated_quantity = $cart_item['quantity'];
            $updated_price = wc_price($cart_item['data']->get_price() * $cart_item['quantity']);
            $product_id = $cart_item['product_id'];
            break;
        }
    }

    // Calculate updated total
    $updated_total = wc_price(WC()->cart->subtotal + WC()->cart->shipping_total);
    $updated_total_price = wc_price(WC()->cart->subtotal);
    // Return updated data
    $response = array(
        'product_id' => $product_id,
        'quantity' => $updated_quantity,
        'price' => $updated_price,
        'total' => $updated_total,
        'updated_price' => $updated_total_price,
        'variation_id' => $cart_item['variation_id'],
    );


    wp_send_json_success($response);
}

add_action('wp_ajax_update_item_from_cart', 'update_item_from_cart');
add_action('wp_ajax_nopriv_update_item_from_cart', 'update_item_from_cart');

function update_item_from_minicart()
{
    $cart_item_key = $_POST['cart_item_key'];
    $quantity = $_POST['qty'];
    $variation_id = $_POST['variation_id'];
    // Update quantity in the cart
    foreach (WC()->cart->get_cart() as $key => $cart_item) {
        if ($key === $cart_item_key) {
            WC()->cart->set_quantity($key, $quantity, $refresh_totals = true);
            break;
        }
    }

    // Recalculate totals
    WC()->cart->calculate_totals();
    WC()->cart->maybe_set_cart_cookies();

    // Get updated quantity, price, and total
    $updated_quantity = 0;
    $updated_price = 0;
    $updated_total = 0;
    $product_id = 0;

    foreach (WC()->cart->get_cart() as $key => $cart_item) {
        if ($key === $cart_item_key) {
            $updated_quantity = $cart_item['quantity'];
            $updated_price = wc_price($cart_item['data']->get_price() * $cart_item['quantity']);
            $product_id = $cart_item['product_id'];
            break;
        }
    }

    // Calculate updated total
    $updated_total = wc_price(WC()->cart->get_cart_contents_total());
    // $updated_total = wc_price(WC()->cart->get_total('edit'));

    // Return updated data
    $response = array(
        'product_id' => $product_id,
        'quantity' => $updated_quantity,
        'price' => $updated_price,
        'total' => $updated_total,
        'variation_id' => $cart_item['variation_id'],
    );

    wp_send_json_success($response);
}

add_action('wp_ajax_update_item_from_minicart', 'update_item_from_minicart');
add_action('wp_ajax_nopriv_update_item_from_minicart', 'update_item_from_minicart');

// Remove product from cart
add_action('wp_ajax_remove_from_cart', 'remove_from_cart_ajax_callback');
add_action('wp_ajax_nopriv_remove_from_cart', 'remove_from_cart_ajax_callback');

function remove_from_cart_ajax_callback()
{
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);

        // Get the cart items
        $cart_items = WC()->cart->get_cart();

        // Loop through the cart items
        foreach ($cart_items as $cart_item_key => $cart_item) {
            if ($cart_item['variation_id'] === $product_id) {
                // Remove the item from the cart
                WC()->cart->remove_cart_item($cart_item_key);

                // Recalculate all cart totals
                WC()->cart->calculate_totals();

                $cart_total_with_shipping = wc_price(WC()->cart->subtotal + WC()->cart->shipping_total);
                $cart_total = wc_price(WC()->cart->subtotal);

                // Return the updated cart total in the response
                wp_send_json_success(array(
                    'cart_total' => $cart_total,
                    'cart_total_with_shipping' => $cart_total_with_shipping,
                    'cart_count' => count(WC()->cart->get_cart())
                ));
            }
        }

        // If the product ID is not found in the cart
        wp_send_json_error(array('message' => 'Item not found in cart'));
    } else {
        wp_send_json_error(array('message' => 'Invalid request'));
    }

    wp_die();
}

// Coupon function
function implement_ajax()
{
    if (isset($_POST['couponcode'])) {
        apply_coupon($_POST['couponcode']);
    }
}
add_action('wp_ajax_my_special_action', 'implement_ajax');
add_action('wp_ajax_nopriv_my_special_action', 'implement_ajax');
function rem_coup_action()
{
    WC()->cart->remove_coupons();
    wc_clear_notices();
    $update = 1;
    if ($update == 1) {
        echo wc_cart_totals_order_total_html();
    }
    exit();
}
add_action('wp_ajax_rem_coup_action', 'rem_coup_action');
add_action('wp_ajax_nopriv_rem_coup_action', 'rem_coup_action');

function apply_coupon($couponcode)
{
    global $woocommerce;
    WC()->cart->remove_coupons();
    $ret = WC()->cart->add_discount($couponcode);
    error_log('Coupon code: ' . $couponcode . ', Result: ' . ($ret ? 'Success' : 'Failure'));

    if ($ret == 1) {
        echo wc_cart_totals_order_total_html();
        exit;
    } else {
        echo 1;
        exit;
    }
}
// Hook the AJAX function
add_action('wp_ajax_apply_coupon', 'apply_coupon');
add_action('wp_ajax_nopriv_apply_coupon', 'apply_coupon');

//form text coupon dfetch coupon amount
function get_coupon_amount()
{
    if (isset($_POST['couponcode'])) {
        $couponcode = $_POST['couponcode'];
        $coupon_amount = WC()->cart->get_coupon_discount_amount($couponcode);

        wp_send_json(array('coupon_amount' => $coupon_amount));
    }
}
add_action('wp_ajax_get_coupon_amount', 'get_coupon_amount');
add_action('wp_ajax_nopriv_get_coupon_amount', 'get_coupon_amount');


//coupon checkout 
//      function apply_coupon_checkout()
// {
//     if (isset($_POST['couponcode'])) {
//         $coupon_code = sanitize_text_field($_POST['couponcode']);
//         $result = apply_coupon_to_cart($coupon_code);
//         wp_send_json($result);
//     }
// }

// function apply_coupon_to_cart($coupon_code)
// {
//     if (WC()->cart->apply_coupon($coupon_code)) {
//         // Coupon applied successfully
//         return true;
//     } else {
//         // Coupon application failed
//         return false;
//     }
// }

add_action('wp_ajax_coupon_checkout', 'apply_coupon_checkout');
add_action('wp_ajax_nopriv_coupon_checkout', 'apply_coupon_checkout');

function country_shipping()
{
    $countryCode = $_POST['country_code'];
    WC()->customer->set_shipping_country(sanitize_text_field($countryCode));
    WC()->customer->set_billing_country(sanitize_text_field($countryCode));
    WC()->cart->calculate_totals();
    exit();
}
add_action('wp_ajax_country_shipping', 'country_shipping');
add_action('wp_ajax_nopriv_country_shipping', 'country_shipping');


// Checkout start
add_action('wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99);

function child_manage_woocommerce_styles()
{
    //remove generator meta tag
    remove_action('wp_head', array($GLOBALS['woocommerce'], 'generator'));

    //first check that woo exists to prevent fatal errors
    if (function_exists('is_woocommerce')) {
        //dequeue scripts and styles
        if (!is_woocommerce() && !is_cart() && !is_checkout()) {
            wp_dequeue_script('wc-checkout');

            wp_dequeue_style('woocommerce_frontend_styles');
            wp_dequeue_style('woocommerce_fancybox_styles');
            wp_dequeue_style('woocommerce_chosen_styles');
            wp_dequeue_style('woocommerce_prettyPhoto_css');

            wp_dequeue_script('wc_price_slider');
            wp_dequeue_script('wc-single-product');
            wp_dequeue_script('wc-add-to-cart');
            wp_dequeue_script('wc-cart-fragments');
            wp_dequeue_script('wc-add-to-cart-variation');
            wp_dequeue_script('wc-single-product');
            wp_dequeue_script('wc-cart');
            wp_dequeue_script('wc-chosen');
            wp_dequeue_script('woocommerce');
            wp_dequeue_script('prettyPhoto');
            wp_dequeue_script('prettyPhoto-init');
            wp_dequeue_script('jquery-blockui');
            wp_dequeue_script('jquery-placeholder');
            wp_dequeue_script('fancybox');
            wp_dequeue_script('jqueryui');
        }
    }
}

if (!is_woocommerce()) {
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}
if (!is_cart()) {
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}
if (is_checkout()) {
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}
if (!function_exists('woocommerce_form_field')) {

    /**
     * Outputs a checkout/address form field.
     *
     * @subpackage  Forms
     * @param string $key
     * @param mixed $args
     * @param string $value (default: null)
     * @todo This function needs to be broken up in smaller pieces
     */
    function woocommerce_form_field($key, $args, $value = null)
    {
        $defaults = array(
            'type'              => 'text',
            'label'             => '',
            'description'       => '',
            'placeholder'       => '',
            'maxlength'         => false,
            'required'          => false,
            'autocomplete'      => false,
            'id'                => $key,
            'class'             => array(),
            'label_class'       => array('floating-row'),
            'input_class'       => array(),
            'return'            => false,
            'options'           => array(),
            'custom_attributes' => array(),
            'validate'          => array(),
            'default'           => '',
        );

        $args = wp_parse_args($args, $defaults);
        $args = apply_filters('woocommerce_form_field_args', $args, $key, $value);

        if ($args['required']) {
            // $args['class'][] = 'validate-required';
            $args['class'][] = '';
            $required = '';
        } else {
            $required = '';
        }

        $args['maxlength'] = ($args['maxlength']) ? 'maxlength="' . absint($args['maxlength']) . '"' : '';

        $args['autocomplete'] = ($args['autocomplete']) ? 'autocomplete="' . esc_attr($args['autocomplete']) . '"' : '';

        if (is_string($args['label_class'])) {
            $args['label_class'] = array($args['label_class']);
        }

        if (is_null($value)) {
            $value = $args['default'];
        }

        // Custom attribute handling
        $custom_attributes = array();

        if (!empty($args['custom_attributes']) && is_array($args['custom_attributes'])) {
            foreach ($args['custom_attributes'] as $attribute => $attribute_value) {
                $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($attribute_value) . '"';
            }
        }

        if (!empty($args['validate'])) {
            foreach ($args['validate'] as $validate) {
                $args['class'][] = 'validate-' . $validate;
            }
        }

        $field = '';
        $label_id = $args['id'];
        $field_container = '<div class="floating-blk %1$s" id="%2$s">%3$s</div>';

        switch ($args['type']) {
            case 'country':
                $countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_shipping_countries();
                $country_code = WC()->customer->get_billing_country();
                if ($country_code == 'IN') {
                    $country_opt = 'disabled';
                } else {
                    $country_opt = "";
                }
                $field = '<label for="billing-country" class="floating-row">
                   <span class="arrow-btn"><i class="las la-angle-down"></i></span><select ' . esc_attr($country_opt) . ' name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" class="floating-input' . esc_attr(implode(' ', $args['input_class'])) . '" ' . implode(' ', $custom_attributes) . ' data-placeholder="">';
                foreach ($countries as $ckey => $cvalue) {
                    $field .= '<option value="' . esc_attr($ckey) . '" ' . selected($value, $ckey, false) . '>' . $cvalue . '</option>';
                }
                $field .= '</select></label>';

                break;
            case 'state':
                $countyArray = ["Andra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jammu and Kashmir", "Jharkhand", "Karnataka", "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Orissa", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", "Tripura", "Uttaranchal", "Uttar Pradesh", "West Bengal", "Andaman and Nicobar Islands", "Chandigarh", "Dadar and Nagar Haveli", "Daman and Diu", "Delhi", "Lakshadeep", "Pondicherry"];
                $field .= '<span class="arrow-btn"><i class="las la-angle-down"></i></span><select name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" class="floating-input">
                          <option hidden></option>';
                foreach ($countyArray as $countykey => $county) {
                    $field .= '<option value="' . esc_attr($county) . '" ' . selected($value, $countykey, false) . '>' . $county . '</option>';
                }
                $field .= '</select>';
                break;
            case 'textarea':
                $field .= '<textarea name="' . esc_attr($key) . '" class="floating-input' . esc_attr(implode(' ', $args['input_class'])) . '" id="' . esc_attr($args['id']) . '" placeholder="' . esc_attr($args['placeholder']) . '" ' . $args['maxlength'] . ' ' . $args['autocomplete'] . ' ' . (empty($args['custom_attributes']['rows']) ? ' rows="5"' : '') . (empty($args['custom_attributes']['cols']) ? ' ' : '') . implode(' ', $custom_attributes) . '>' . esc_textarea($value) . '</textarea>';
                break;
            case 'checkbox':
                $field = '<label class="checkbox ' . implode(' ', $args['label_class']) . '" ' . implode(' ', $custom_attributes) . '>
            <input type="' . esc_attr($args['type']) . '" class="input-checkbox ' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="1" ' . checked($value, 1, false) . ' /> '
                    . $args['label'] . $required . '</label>';
                break;
            case 'email':
                $field .= '<input type="' . esc_attr($args['type']) . '" class="input-text floating-input input-item validate' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" placeholder="' . esc_attr($args['placeholder']) . '" ' . $args['maxlength'] . ' ' . $args['autocomplete'] . ' value="' . esc_attr($value) . '" ' . implode(' ', $custom_attributes) . ' />';
                break;
            case 'text':
                $field .= '<input type="' . esc_attr($args['type']) . '" class="input-text floating-input input-item  ' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" placeholder="' . esc_attr($args['placeholder']) . '" ' . $args['maxlength'] . ' ' . $args['autocomplete'] . ' value="' . esc_attr($value) . '" ' . implode(' ', $custom_attributes) . ' />';
                break;
            case 'tel':
            case 'number':
                $field .= '<div class= "floating-item"><input type="text" class="input-text floating-input input-item validate' . esc_attr(implode(' ', $args['input_class'])) . '" onkeypress="return isNumber(event)" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="' . esc_attr($value) . '" ' . implode(' ', $custom_attributes) . ' /></div>';
                break;
                // case 'select':

                //     $options = $field = '';

                //     if (!empty($args['options'])) {
                //         foreach ($args['options'] as $option_key => $option_text) {
                //             if ('' === $option_key) {
                //                 // If we have a blank option, select2 needs a placeholder
                //                 if (empty($args['placeholder'])) {
                //                     $args['placeholder'] = $option_text ? $option_text : __('Choose an option', 'woocommerce');
                //                 }
                //                 $custom_attributes[] = 'data-allow_clear="true"';
                //             }
                //             $options .= '<option value="' . esc_attr($option_key) . '" ' . selected($value, $option_key, false) . '>' . esc_attr($option_text) . '</option>';
                //         }

                //         $field .= '<div class="floating-item"><span class="arrow-btn"><i class="las la-angle-down"></i></span><span class="floating-label">"' . esc_attr($args['placeholder']) . '"</span><select name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" class=" select ' . esc_attr(implode(' ', $args['input_class'])) . '" ' . implode(' ', $custom_attributes) . ' data-placeholder="' . esc_attr($args['placeholder']) . '" ' . $args['autocomplete'] . '>
                //   ' . $options . '
                // </select></div>';
                //     }

                //     break;
            case 'radio':

                $label_id = current(array_keys($args['options']));

                if (!empty($args['options'])) {
                    foreach ($args['options'] as $option_key => $option_text) {
                        $field .= '<input type="radio" class="input-radio ' . esc_attr(implode(' ', $args['input_class'])) . '" value="' . esc_attr($option_key) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '_' . esc_attr($option_key) . '"' . checked($value, $option_key, false) . ' />';
                        $field .= '<label for="' . esc_attr($args['id']) . '_' . esc_attr($option_key) . '" class="radio ' . implode(' ', $args['label_class']) . '">' . $option_text . '</label>';
                    }
                }

                break;
        }
        if (!empty($field)) {
            $field_html = '';

            if ($args['label'] && 'checkbox' !== $args['type']) {
                $field_html .= '<label for="' . esc_attr($label_id) . '" class="' . esc_attr(implode(' ', $args['label_class'])) . '">';
            }

            $field_html .= $field . '<span class="floating-label">' . $args['label'] . $required;

            if ($args['description']) {
                $field_html .= '<span class="description" id="' . esc_attr($args['id']) . '-description" aria-hidden="true">' . wp_kses_post($args['description']) . '</span>';
            }

            $field_html .= '</span></label>';

            $container_class = esc_attr(implode(' ', $args['class']));
            $container_id    = esc_attr($args['id']) . '_field';
            $field           = sprintf($field_container, $container_class, $container_id, $field_html);
        }

        /**
         * Filter by type.
         */
        $field = apply_filters('woocommerce_form_field_' . $args['type'], $field, $key, $args, $value);

        /**
         * General filter on form fields.
         *
         * @since 3.4.0
         */
        $field = apply_filters('woocommerce_form_field', $field, $key, $args, $value);
        // $field = apply_filters( 'woocommerce_form_field_' . $args['type'], $field, $key, $args, $value );

        if ($args['return']) {
            return $field;
        } else {
            echo $field;
        }
    }
}

function custom_override_checkout_fields_ek($fields)
{
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_address_1']['placeholder']);
    unset($fields['billing']['billing_address_2']['label']);
    $fields['billing']['billing_state']['label'] = 'State';
    $fields['billing']['billing_city']['label'] = 'City';
    $fields['billing']['billing_address_1']['label'] = 'Address';
    $fields['billing']['billing_postcode']['label'] = 'PIN Code';
    $fields['billing']['billing_first_name']['label'] = 'First Name';
    $fields['billing']['billing_last_name']['label'] = 'Last Name';
    $fields['billing']['billing_first_name']['priority'] = 1;
    $fields['billing']['billing_last_name']['priority'] = 2;
    $fields['billing']['billing_email']['priority'] = 3;
    $fields['billing']['billing_phone']['priority'] = 4;
    $fields['billing']['billing_address_1']['priority'] = 5;
    $fields['billing']['billing_postcode']['priority'] = 6;
    $fields['billing']['billing_state']['priority'] = 7;
    $fields['billing']['billing_city']['priority'] = 8;
    $fields['billing']['billing_country']['priority'] = 9;
    $fields['billing']['billing_address_1']['type'] = 'textarea';
    $fields['billing']['billing_state']['type'] = 'text';
    unset($fields['billing']['billing_postcode']['validate']);
    unset($fields['billing']['billing_state']['validate']);
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields_ek', 99);

/*****
 * Guest user account automatically created
 *****/

function wc_register_guests($order_id)
{
    // get all the order data
    $order = new WC_Order($order_id);

    //get the user email from the order
    $order_email = $order->billing_email;

    // check if there are any users with the billing email as user or email
    $email = email_exists($order_email);
    $user = username_exists($order_email);

    // if the UID is null, then it's a guest checkout
    if ($user == false && $email == false) {

        // random password with 12 chars
        $random_password = wp_generate_password();

        // create new user with email as username & newly created pw
        $user_id = wp_create_user($order_email, $random_password, $order_email);

        //WC guest customer identification
        update_user_meta($user_id, 'guest', 'yes');
        update_user_meta($user_id, 'otp_verified', 'yes');

        //user's billing data
        update_user_meta($user_id, 'billing_address_1', $order->billing_address_1);
        update_user_meta($user_id, 'billing_address_2', $order->billing_address_2);
        update_user_meta($user_id, 'billing_city', $order->billing_city);
        update_user_meta($user_id, 'billing_company', $order->billing_company);
        update_user_meta($user_id, 'billing_country', $order->billing_country);
        update_user_meta($user_id, 'billing_email', $order->billing_email);
        update_user_meta($user_id, 'billing_first_name', $order->billing_first_name);
        update_user_meta($user_id, 'billing_last_name', $order->billing_last_name);
        update_user_meta($user_id, 'billing_phone', $order->billing_phone);
        update_user_meta($user_id, 'billing_postcode', $order->billing_postcode);
        update_user_meta($user_id, 'billing_state', $order->billing_state);

        // user's shipping data
        update_user_meta($user_id, 'shipping_address_1', $order->shipping_address_1);
        update_user_meta($user_id, 'shipping_address_2', $order->shipping_address_2);
        update_user_meta($user_id, 'shipping_city', $order->shipping_city);
        update_user_meta($user_id, 'shipping_company', $order->shipping_company);
        update_user_meta($user_id, 'shipping_country', $order->shipping_country);
        update_user_meta($user_id, 'shipping_first_name', $order->shipping_first_name);
        update_user_meta($user_id, 'shipping_last_name', $order->shipping_last_name);
        update_user_meta($user_id, 'shipping_method', $order->shipping_method);
        update_user_meta($user_id, 'shipping_postcode', $order->shipping_postcode);
        update_user_meta($user_id, 'shipping_state', $order->shipping_state);

        // link past orders to this newly created customer
        wc_update_new_customer_past_orders($user_id);
    }
}

//add this newly created function to the thank you page
add_action('woocommerce_thankyou', 'wc_register_guests', 10, 1);
function ajax_apply_coupon()
{
    $coupon_code = null;
    if (!empty($_POST['coupon_code'])) {
        $coupon_code = sanitize_key($_POST['coupon_code']);
    }
    $coupon_id = wc_get_coupon_id_by_code($coupon_code);
    if (empty($coupon_id)) {
        echo 3;
        exit();
    }
    if (!WC()->cart->has_discount($coupon_code)) {
        WC()->cart->add_discount($coupon_code);
    }
    echo 1;
    exit();
}

add_action('wp_ajax_ajax_apply_coupon', 'ajax_apply_coupon');
add_action('wp_ajax_nopriv_ajax_apply_coupon', 'ajax_apply_coupon');
function ajax_remove_coupon()
{
    $applied_coupons = WC()->cart->get_applied_coupons();
    if (count($applied_coupons) > 0) {
        WC()->cart->remove_coupons();
        WC()->cart->calculate_totals();
    }
    echo 1;
    exit();
}

add_action('wp_ajax_ajax_remove_coupon', 'ajax_remove_coupon');
add_action('wp_ajax_nopriv_ajax_remove_coupon', 'ajax_remove_coupon');

// add_filter('woocommerce_enable_order_notes_field', '__return_false');
add_action('wp_footer', 'enqueue_smartcod_js', 100);
function enqueue_smartcod_js()
{
    ob_start(); ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(document).on('change', 'input[name=payment_method]', function() {
                console.log("payment_chng");

                $(this).parents('.checkboxradio-row').next('.wc_payment_method').find('input[name=payment_method]').click();

                var currentId = '#' + $(this).parents('.checkboxradio-row').next('.wc_payment_method').find('input[name=payment_method]').attr('id');
                jQuery(currentId).trigger('click');
                setTimeout(function() {
                    $('.payment-mode, .woocommerce-checkout-payment').removeClass('pointer-disab');
                    $('.checkboxradio, .woocommerce-checkout-payment').removeClass('pointer-disab');

                }, 800);
            });
        });
    </script>
<?php
    ob_end_flush();
}
// Checkout end

// Guest user register
function guest_create_new_customer($email = '', $phone, $username = '', $password = '', $guest = '')
{
    if ($guest == '') {
        if (phone_exists($phone)) {
            return new WP_Error('registration-error-phone-exists', __('An account is already registered with your phone number. Please login.', 'woocommerce'));
        }
    }
    if (phone_exists($phone) && $guest != '') {

        return new WP_Error('registration-error-email-exists-phone', __('Sorry! This phone number already is associate with another Email address.', 'woocommerce'));
    }


    // Handle username creation.
    if ('no' === get_option('woocommerce_registration_generate_username') || !empty($username)) {
        $username = sanitize_user($username);
        if (empty($username) || !validate_username($username)) {
            return new WP_Error('registration-error-invalid-username', __('Please enter a valid account username.', 'woocommerce'));
        }
        if (username_exists($username)) {
            return new WP_Error('registration-error-username-exists', __('An account is already registered with that username. Please choose another.', 'woocommerce'));
        }
    }
    // Handle password creation.
    if ('yes' === get_option('woocommerce_registration_generate_password') && empty($password)) {
        $password           = wp_generate_password();
        $password_generated = true;
    } elseif (!empty($password)) {
        return new WP_Error('registration-error-missing-password', __('Please enter an account password.', 'woocommerce'));
    } else {
        $password_generated = false;
    }
    // Use WP_Error to handle registration errors.
    $errors = new WP_Error();
    do_action('woocommerce_register_post', $username, $phone, $errors);
    $errors = apply_filters('woocommerce_registration_errors', $errors, $username, $phone);
    if ($errors->get_error_code()) {
        return $errors;
    }
    $new_customer_data = apply_filters('woocommerce_new_customer_data', array(
        'user_login' => $phone,
        'user_phone' => $phone,
        'user_pass'  => $password,
        'role'       => 'customer',
    ));

    if (($guest == '') || ($guest == 'guest' && (!phone_exists($phone)))) {
        $customer_id = wp_insert_user($new_customer_data);
        $updateMetaphone = update_user_meta($customer_id, 'phone', $phone);
    } else {
        $customer_id = phone_exists($phone);
    }
    if (is_wp_error($customer_id)) {
        return new WP_Error('registration-error', '<strong>' . __('Error:', 'woocommerce') . '</strong> ' . __('Couldn&#8217;t register you&hellip; please contact us if you continue to have problems.', 'woocommerce'));
    }
    return $customer_id;
}
function guest_create_new_customer_email($email = '', $phone, $username = '', $password = '', $guest = '')
{
    if ($guest == '') {
        if (email_exists($email)) {
            return new WP_Error('registration-error-phone-exists', __('An account is already registered with your phone number. Please login.', 'woocommerce'));
        }
    }
    if (email_exists($email) && $guest != '') {

        return new WP_Error('registration-error-email-exists-phone', __('Sorry! This phone number already is associate with another Email address.', 'woocommerce'));
    }


    // Handle username creation.
    if ('no' === get_option('woocommerce_registration_generate_username') || !empty($username)) {
        $username = sanitize_user($username);
        if (empty($username) || !validate_username($username)) {
            return new WP_Error('registration-error-invalid-username', __('Please enter a valid account username.', 'woocommerce'));
        }
        if (username_exists($username)) {
            return new WP_Error('registration-error-username-exists', __('An account is already registered with that username. Please choose another.', 'woocommerce'));
        }
    }
    // Handle password creation.
    if ('yes' === get_option('woocommerce_registration_generate_password') && empty($password)) {
        $password           = wp_generate_password();
        $password_generated = true;
    } elseif (!empty($password)) {
        return new WP_Error('registration-error-missing-password', __('Please enter an account password.', 'woocommerce'));
    } else {
        $password_generated = false;
    }
    // Use WP_Error to handle registration errors.
    $errors = new WP_Error();
    do_action('woocommerce_register_post', $username, $email, $errors);
    $errors = apply_filters('woocommerce_registration_errors', $errors, $username, $email);
    if ($errors->get_error_code()) {
        return $errors;
    }
    $new_customer_data = apply_filters('woocommerce_new_customer_data', array(
        'user_login' => $email,
        'user_email' => $email,
        'user_phone' => $phone,
        'user_pass'  => $password,
        'role'       => 'customer',
    ));

    if (($guest == '') || ($guest == 'guest' && (!email_exists($email)))) {
        $customer_id = wp_insert_user($new_customer_data);
        $updateMetaphone = update_user_meta($customer_id, 'phone', $phone);
    } else {
        $customer_id = email_exists($email);
    }
    if (is_wp_error($customer_id)) {
        return new WP_Error('registration-error', '<strong>' . __('Error:', 'woocommerce') . '</strong> ' . __('Couldn&#8217;t register you&hellip; please contact us if you continue to have problems.', 'woocommerce'));
    }
    return $customer_id;
}

// Check user by id
function get_user_role($user_id)
{
    $user_data = get_userdata($user_id);
    if (!empty($user_data->roles))
        return $user_data->roles[0];

    return false;
}


function countryToCountryCode($code)
{
    // $code = strtoupper($code);
    if ($code == 'Australia') return 'AU';
    if ($code == 'Bahrain') return 'BH';
    if ($code == 'Canada') return 'CA';
    if ($code == 'France') return 'FR';
    if ($code == 'Germany') return 'DE';
    if ($code == 'Indonesia') return 'ID';
    if ($code == 'India') return 'IN';
    if ($code == 'Italy') return 'IT';
    if ($code == 'Kuwait') return 'KW';
    if ($code == 'Malaysia') return 'MY';
    if ($code == 'Myanmar') return 'MM';
    if ($code == 'Netherlands') return 'NL';
    if ($code == 'Oman') return 'OM';
    if ($code == 'Philippines') return 'PH';
    if ($code == 'Qatar') return 'QA';
    if ($code == 'Saudi Arabia') return 'SA';
    if ($code == 'Singapore') return 'SG';
    if ($code == 'Sri Lanka') return 'LK';
    if ($code == 'Trinidad and Tobago') return 'TT';
    if ($code == 'United Arab Emirates') return 'AE';
    if ($code == 'United Kingdom (UK)') return 'GB';
    if ($code == 'United States (US)') return 'US';
}
// Thank you order
add_action('woocommerce_thankyou', 'bbloomer_checkout_save_user_meta');
function bbloomer_checkout_save_user_meta($order_id)
{
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();
    //get the user email from the order
    update_user_meta($user_id, 'billing_address_1', $order->billing_address_1);
    update_user_meta($user_id, 'billing_city', $order->billing_city);
    update_user_meta($user_id, 'billing_company', $order->billing_company);
    update_user_meta($user_id, 'billing_country', $order->billing_country);
    update_user_meta($user_id, 'billing_email', $order->billing_email);
    update_user_meta($user_id, 'billing_first_name', $order->billing_first_name);
    update_user_meta($user_id, 'billing_last_name', $order->billing_last_name);
    update_user_meta($user_id, 'billing_phone', $order->billing_phone);
    update_user_meta($user_id, 'billing_postcode', $order->billing_postcode);
    update_user_meta($user_id, 'billing_state', $order->billing_state);
}
//add this newly created function to the thank you page
remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
// add_action('woocommerce_review_order_and_proceed', 'woocommerce_order_review', 20);
// Update missed user data on checkout page
function ajax_update_user()
{
    $user_id = $_POST['user_id'];
    $uphone = $_POST['uphone'];
    $ufname = $_POST['ufname'];
    $uemail = $_POST['uemail'];
    if (!empty($_POST['ufname'])) {
        update_user_meta($user_id, 'billing_first_name', $ufname);
        update_user_meta($user_id, 'first_name', $ufname);
    }
    if (isset($_POST['uphone'])) {
        if (phone_exists($_POST['uphone'])) {
            echo 2;
            exit();
        } else {
            update_user_meta($user_id, 'phone', $uphone);
            update_user_meta($user_id, 'billing_phone', $uphone);
        }
    }
    if (isset($uemail)) {
        if (email_exists($uemail)) {
            echo 3;
            exit();
        } else {
            $args = array(
                'ID'         => $user_id,
                'user_email' => $uemail
            );
            wp_update_user($args);
        }
    }
}

add_action('wp_ajax_ajax_update_user', 'ajax_update_user');
add_action('wp_ajax_nopriv_ajax_update_user', 'ajax_update_user');

// Add new address from checkout
function add_user_chkoutaddress()
{
    $user_id = $_POST['user_id'];
    $billing_address = array(
        'first_name' => sanitize_text_field($_POST['billing_first_name']),
        'last_name' => sanitize_text_field($_POST['billing_last_name']),
        'email' => sanitize_email($_POST['billing_email']),
        'phone' => sanitize_text_field($_POST['billing_phone']),
        'address_1' => sanitize_text_field($_POST['billing_address_1']),
        'city' => sanitize_text_field($_POST['billing_city']),
        'state' => sanitize_text_field($_POST['billing_state']),
        'postcode' => sanitize_text_field($_POST['billing_postcode']),
        'country' => sanitize_text_field($_POST['billing_country']),
        'address_group' => sanitize_text_field($_POST['address_group']),
    );
    add_user_address($user_id, $billing_address);
    echo 1;
    exit();
}
add_action('wp_ajax_add_user_chkoutaddress', 'add_user_chkoutaddress');
add_action('wp_ajax_nopriv_add_user_chkoutaddress', 'add_user_chkoutaddress');

//         add_filter('woocommerce_default_address_fields', 'filter_default_address_fields', 20, 1);
// Update address from checkout
function update_user_chkoutaddress()
{
    $user_id = get_current_user_id();
    // Get the updated address data from the AJAX request
    $address = array(
        'first_name' => sanitize_text_field($_POST['billing_first_name']),
        'last_name' => sanitize_text_field($_POST['billing_last_name']),
        'email' => sanitize_text_field($_POST['billing_email']),
        'phone' => sanitize_text_field($_POST['billing_phone']),
        'address_1' => sanitize_text_field($_POST['billing_address_1']),
        'city' => sanitize_text_field($_POST['billing_city']),
        'state' => sanitize_text_field($_POST['billing_state']),
        'postcode' => sanitize_text_field($_POST['billing_postcode']),
        'country' => sanitize_text_field($_POST['billing_country']),
        'address_group' => sanitize_text_field($_POST['address_group']),
    );
    // Get the current user addresses
    $addresses = get_user_meta($user_id, 'user_addresses', true);
    // Get the index of the address to be updated
    $address_index = intval($_POST['address_key']);

    // Update the address data
    $addresses[$address_index] = $address;
    // Save the updated addresses
    update_user_meta($user_id, 'user_addresses', $addresses);
    echo 1;
    exit();
}
add_action('wp_ajax_update_user_chkoutaddress', 'update_user_chkoutaddress');
add_action('wp_ajax_nopriv_update_user_chkoutaddress', 'update_user_chkoutaddress');
// Checkout shipping charge
function checkout_country_shipping()
{
    $countryCode = $_POST['country_code'];
    WC()->customer->set_shipping_country(sanitize_text_field($countryCode));
    WC()->customer->set_billing_country(sanitize_text_field($countryCode));
    $cartTotal = WC()->cart->calculate_totals();
    if ($countryCode == "IN") {
        $shippingCharge = 'Free!';
        $ctTotal = wc_cart_totals_order_total_html();
        echo $ctTotal . '|' . $shippingCharge;
        exit();
    } else {
        $shippingTotal = wc_price(WC()->cart->shipping_total);
        // $shippingCharge = '₹' . get_option($countryCode . '_five') . '.00';
        $ctTotal = wc_cart_totals_order_total_html();
        echo $ctTotal . '|' . $shippingTotal;
        exit();
    }
    // Return updated data
}
add_action('wp_ajax_checkout_country_shipping', 'checkout_country_shipping');
add_action('wp_ajax_nopriv_checkout_country_shipping', 'checkout_country_shipping');
//return to shop url restrict
add_action('template_redirect', 'disable_woocommerce_shop_redirect');
function disable_woocommerce_shop_redirect()
{
    if (is_shop()) {
        wp_redirect(home_url());
        exit;
    }
}
// To clear checkout field value data stored on default 
function clear_checkout_fields($input)
{
    return '';
}
add_filter('woocommerce_checkout_get_value', 'clear_checkout_fields', 1);
// Remove Unused Menu pages is admin Dashboard
function remove_unused_admin_menu()
{
    remove_menu_page('index.php'); // To remove dashboard tab in menu section
    remove_menu_page('edit-comments.php'); // To remove comments tab in menu section
    remove_menu_page('edit.php'); // To remove default post tab in menu section
}
add_action('admin_menu', 'remove_unused_admin_menu');

//variation gallery image

function get_variation_gallery_images($variation_id)
{
    $variation_image_ids = get_post_meta($variation_id, 'rtwpvg_images', true);
    $gallery_images = [];
    foreach ($variation_image_ids as $image_id) {
        $image = wp_get_attachment_image_src($image_id, 'large');
        // $image = wp_get_attachment_image_src( $image_id);
        if (empty($image[0])) {
            continue;
        }
        $gallery_images[] = $image[0];
    }
    return $gallery_images;
}

add_action('wp_ajax_get_variation_images', 'get_variation_images');
add_action('wp_ajax_nopriv_get_variation_images', 'get_variation_images');

function get_variation_images()
{
    if (isset($_POST['variation_id'])) {
        $variation_id = intval($_POST['variation_id']);
        $gallery_images = get_variation_gallery_images($variation_id);

        wp_send_json(['images' => $gallery_images]);
    }
    wp_die();
}


// coupon display user made a purchase
// function has_bought($user_id) {
//     // Check if the user has made a purchase
//     $customer_orders = wc_get_orders(array(
//         'limit' => 1,
//         'customer' => $user_id,
//         'status' => 'completed',
//     ));

//     return count($customer_orders) > 0;
// }

// // coupon usage limit set 1
// function set_usage_limit_for_all_coupons() {
//     $coupons = get_posts(array(
//         'post_type' => 'shop_coupon', // Coupons are a custom post type in WooCommerce
//         'posts_per_page' => -1, // Get all coupons
//     ));

//     foreach ($coupons as $coupon) {
//         $usage_limit = 1; // Change this value to set your desired usage limit
//         update_post_meta($coupon->ID, 'usage_limit', $usage_limit);
//     }
// }
// add_action('admin_init', 'set_usage_limit_for_all_coupons');

//coupon custom for 3 month once 

function add_customer_eligibility_field()
{
    add_meta_box(
        'customer_eligibility',
        'Customer Eligibility',
        'render_customer_eligibility_field',
        'shop_coupon',
        'side',
        'default'
    );
}

function render_customer_eligibility_field($post)
{
    $customer_eligibility = get_post_meta($post->ID, 'customer_eligibility', true);
?>
    <label for="customer_eligibility">
        <input type="checkbox" name="customer_eligibility" id="customer_eligibility" <?php checked($customer_eligibility, true); ?>>
        Customer Eligibility
    </label>
<?php
}

function save_customer_eligibility_field($post_id)
{
    if (isset($_POST['customer_eligibility'])) {
        update_post_meta($post_id, 'customer_eligibility', true);
    } else {
        delete_post_meta($post_id, 'customer_eligibility');
    }
}

// Hook functions to appropriate actions
add_action('add_meta_boxes_shop_coupon', 'add_customer_eligibility_field');
add_action('save_post_shop_coupon', 'save_customer_eligibility_field');
//multiple coupons
// Add meta box for 'Multiple Coupons'
function add_multiple_coupons_field()
{
    add_meta_box(
        'multiple_coupons',
        'Multiple Coupons',
        'render_multiple_coupons_field',
        'shop_coupon',
        'side',
        'default'
    );
}

// Render the 'Multiple Coupons' field
function render_multiple_coupons_field($post)
{
    $multiple_coupons = get_post_meta($post->ID, 'multiple_coupons', true);
?>
    <label for="multiple_coupons">
        <input type="checkbox" name="multiple_coupons" id="multiple_coupons" <?php checked($multiple_coupons, true); ?>>
        Allow Multiple Coupons
    </label>
<?php
}

// Save the 'Multiple Coupons' field value
function save_multiple_coupons_field($post_id)
{
    if (isset($_POST['multiple_coupons'])) {
        update_post_meta($post_id, 'multiple_coupons', true);
    } else {
        delete_post_meta($post_id, 'multiple_coupons');
    }
}

// Hook functions to appropriate actions
add_action('add_meta_boxes_shop_coupon', 'add_multiple_coupons_field');
add_action('save_post_shop_coupon', 'save_multiple_coupons_field');





//coupon for pending payment customer
function generate_custom_coupon_code($length = 10)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $coupon_code = '';

    // Loop to generate the coupon code
    for ($i = 0; $i < $length; $i++) {
        $coupon_code .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $coupon_code;
}

// Define a function to send a coupon when a new order is created with 'Pending Payment' status

// Hook the function to the 'woocommerce_new_order' action
add_action('woocommerce_new_order', 'schedule_coupon_for_pending_payment', 10, 1);

// Define a function to send a coupon after 20 minutes if the order is still 'Pending Payment'
function schedule_coupon_for_pending_payment($order_id)
{
    // Schedule an event to send the coupon after 20 minutes
    wp_schedule_single_event(time() + 300, 'send_coupon_after_delay', array($order_id));
}

// Define a function to be called after the delay
function send_coupon_after_delay($order_id)
{
    // Get the order object
    $order = wc_get_order($order_id);

    // Check if the order is still in 'Pending Payment' status and not paid
    if ($order->get_status() === 'pending' && !$order->is_paid()) {
        // Generate a unique coupon code
        $coupon_code = generate_custom_coupon_code(10);

        // Set up coupon data
        $coupon = array(
            'post_title' => $coupon_code,
            'post_content' => 'Discount for pending payment',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'shop_coupon',
        );

        // Insert the coupon
        $new_coupon_id = wp_insert_post($coupon);

        // Optionally, you can set coupon usage limits, expiry date, etc.
        update_post_meta($new_coupon_id, 'discount_type', 'percent');
        update_post_meta($new_coupon_id, 'coupon_amount', 10); // 10% discount
        update_post_meta($new_coupon_id, 'individual_use', 'yes');

        // Get the customer email
        $customer_email = $order->get_billing_email();

        // Send email with coupon code
        $subject = 'Your Coupon Code for Pending Payment';
        $message = "Dear customer, your coupon code is: $coupon_code";

        // Attempt to send email
        $email_sent = wp_mail($customer_email, $subject, $message);

        // Log whether the email was sent successfully or not
        error_log('Email Sent: ' . ($email_sent ? 'Yes' : 'No'));
    }
}

// Hook the function to the custom action after the delay
add_action('send_coupon_after_delay', 'send_coupon_after_delay', 10, 1);


//coupon remove incre decre
add_action('woocommerce_after_cart_item_quantity_update', 'remove_invalid_coupons_on_qty_change', 10, 2);

function remove_invalid_coupons_on_qty_change($cart_item_key, $quantity)
{
    WC()->cart->calculate_totals(); // Ensure totals are up-to-date

    foreach (WC()->cart->get_applied_coupons() as $code) {
        $coupon = new WC_Coupon($code);

        if (! $coupon->is_valid()) {
            WC()->cart->remove_coupon($code);

            // Prevent page reload and display a clearer message
            wc_add_notice(sprintf(__('The coupon "%s" has been removed as it is no longer valid.', 'woocommerce'), $code), 'notice');
        }
    }
}
//checkout total round

//session expired issue 

add_filter('wc_session_expiring', 'custom_session_expiring');
add_filter('wc_session_expiration', 'custom_session_expiration');

function custom_session_expiring($seconds)
{
    return 31536000;
}

function custom_session_expiration($seconds)
{
    return 31536000;
}

function auth_cookie_expiration_oneyear($seconds, $user_id)
{
    return 31536000;
}
add_filter('auth_cookie_expiration', 'auth_cookie_expiration_oneyear', 10, 2);


// coupon email and phone number retriction
function custom_woocommerce_coupon_validation($valid, $coupon, $discount)
{
    $target_coupon_code = 'test_co';

    if ($coupon->get_code() !== $target_coupon_code) {
        return $valid;
    }

    // Get the current user
    $current_user = wp_get_current_user();
    $user_email = $current_user->user_email;
    $user_phone = get_user_meta($current_user->ID, 'phone', true);
    // error_log($user_email);
    // error_log($user_phone);

    $allowed_combinations = array(
        'parimala@madebyfire.com' => '9524704591',
        'test@gmail.com' => '98766543210',
    );

    // Check if the current user's email and phone number match any allowed combination
    if (isset($allowed_combinations[$user_email]) && $allowed_combinations[$user_email] === $user_phone) {
        // Allow the coupon if the combination is valid
        return true;
    } else {
        // Deny the coupon if the combination is not valid
        wc_add_notice(__('This coupon is not valid for your account.', 'woocommerce'), 'error');
        return false;
    }
}

add_filter('woocommerce_coupon_is_valid', 'custom_woocommerce_coupon_validation', 10, 3);


// SEO Breadcrumb - Schema

function add_breadcrumb_schema()
{
    if (is_singular('product') || is_page()) {
        global $post;

        // Initialize breadcrumb items array
        $breadcrumb_items = array();

        // Home page
        $breadcrumb_items[] = array(
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home Page',
            'item' => home_url('/')
        );

        // Add breadcrumbs for products
        if (is_singular('product')) {
            $product = wc_get_product($post->ID);
            $categories = get_the_terms($post->ID, 'product_cat');

            if ($categories && !is_wp_error($categories)) {
                $category = $categories[0];
                $parent_cat = get_term_by('id', $category->parent, 'product_cat');

                // Check if there is a parent category
                if ($parent_cat) {
                    $breadcrumb_items[] = array(
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => $parent_cat->slug,
                        'item' => get_term_link($parent_cat->term_id, 'product_cat')
                        // 'item' => str_replace('/product-category/', '/', get_term_link($parent_cat->term_id, 'product_cat'))
                    );
                }

                // Current category
                $breadcrumb_items[] = array(
                    '@type' => 'ListItem',
                    'position' => $parent_cat ? 3 : 2,
                    'name' => $category->slug,
                    'item' => get_term_link($category->term_id, 'product_cat')
                    // 'item' => str_replace('/product-category/', '/', get_term_link($category->term_id, 'product_cat'))
                );
            }

            // Product title
            $breadcrumb_items[] = array(
                '@type' => 'ListItem',
                'position' => $parent_cat ? 4 : 3,
                'name' => get_the_title(),
                'item' => get_permalink()
            );

            // Add breadcrumbs for product categories
        } elseif (is_page()) {
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);

            foreach ($ancestors as $key => $ancestor_id) {
                $breadcrumb_items[] = array(
                    '@type' => 'ListItem',
                    'position' => $key + 2,
                    'name' => get_the_title($ancestor_id),
                    'item' => get_permalink($ancestor_id)
                );
            }

            $breadcrumb_items[] = array(
                '@type' => 'ListItem',
                'position' => count($ancestors) + 2,
                'name' => get_the_title(),
                'item' => get_permalink()
            );
        }

        // Output the schema
        echo '<script type="application/ld+json">' . json_encode(array(
            '@context' => 'https://schema.org/',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumb_items
        ), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }
}
add_action('wp_head', 'add_breadcrumb_schema');

// SEO Product - Schema

function add_product_schema() {
    if (is_singular('product')) {
        global $post;

        // Get product object
        $product = wc_get_product($post->ID);

        if ($product) {
            // Prepare product details
            $product_name = $product->get_name();
            $product_image = wp_get_attachment_url($product->get_image_id());
            $product_description = $product->get_short_description();
            $currency = get_woocommerce_currency();

            // Initialize schema data
            $schema = array(
                '@context' => 'https://schema.org/',
                '@type' => 'Product',
                'name' => $product_name,
                'image' => $product_image,
                'description' => $product_description,
                'brand' => array(
                    '@type' => 'Brand',
                    'name' => 'SSPandian' 
                ),
                'offers' => array(
                    '@type' => 'AggregateOffer',
                    'url' => get_permalink($post->ID),
                    'priceCurrency' => $currency,
                    "lowPrice" => $product->get_price(),
                    "highPrice" => $product->get_price(),
                    "offerCount" => "1" 
                )
            );

            // Check if the product is variable
            if ($product->is_type('variable')) {
                // Get all variations
                $variations = $product->get_available_variations();
                $prices = array();

                foreach ($variations as $variation) {
                    $variation_obj = wc_get_product($variation['variation_id']);
                    if ($variation_obj) {
                        $prices[] = $variation_obj->get_price();
                    }
                }

                // Ensure prices are sorted correctly
                if (!empty($prices)) {
                    $schema['offers']['lowPrice'] = min($prices);
                    $schema['offers']['highPrice'] = max($prices);
                    $schema['offers']['offerCount'] = count($prices); 
                }
            }

            // Output the schema as JSON-LD
          echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
        }
    }
}
add_action('wp_head', 'add_product_schema');
