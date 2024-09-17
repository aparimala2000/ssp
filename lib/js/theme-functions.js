$(document).ready(function () {
    $(".register_otp_btn").on('click', function (e) {
        var regFname = $('#first_name').val();
        var regLname = $('#last_name').val();
        var regEmail = $('#email').val();
        var regPhone = $("#phone").val();
        var regex = /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;
        var x = 0;
        if (regFname == '' || regFname == undefined) {
            $('#first_name').parents('.floating-blk').addClass('err');
            x++;
        } else {
            $('#first_name').parents('.floating-blk').removeClass('err');
        }
        if (regLname == '' || regLname == undefined) {
            $('#last_name').parents('.floating-blk').addClass('err');
            x++;
        } else {
            $('#last_name').parents('.floating-blk').removeClass('err');
        }
        if (regEmail == '' || regEmail == undefined) {
            $('#email').parents('.floating-blk').addClass('err');
            x++;
        }
        else if (regEmail != '') {
            if (!regex.test(regEmail)) {
                $('#email').parents('.floating-blk').addClass('err');
                $('#email-err').html('We need a valid email address.')
                x++;
            }
            else {
                $('#email').parents('.floating-blk').removeClass('err');
            }
        }
        else {
            $('#email').parents('.floating-blk').removeClass('err');
        }
        if (regPhone == '' || regPhone == undefined) {
            $('#phone').parents('.floating-blk').addClass('err');
            x++;
        }
        else if (regPhone != '') {
            if ((regPhone.length < 10) || (regPhone.length > 15)) {
                $('#phone').parents('.floating-blk').addClass('err');
                $('#phone-err').html('We need a valid phone number.')
                x++;
            } else {
                $('#phone').parents('.floating-blk').removeClass('err');
            }
        } else {
            $('#phone').parents('.floating-blk').removeClass('err');
        }
        if (jQuery("#checkbox2").is(":checked")) {
            $('#checkbox2').parents('.terms-err').removeClass('err');
        }
        else {
            $('#checkbox2').parents('.terms-err').addClass('err');
            x++;
        }
        //  alert(x);
        if (x > 0) {
            return false;
        } else {
            // alert(8);
            $.ajax({
                type: "POST",
                cache: false,
                async: true,
                url: templateUri + '/ajax/ajax-otp.php',
                data: {
                    fname: regFname,
                    lname: regLname,
                    email: regEmail,
                    phone: regPhone,
                    action: 'ajax_regg'
                },
                success: function (data) {
                    var reg_val = data.split('|');
                    var reg_val_ret = reg_val[0];
                    var reg_val_usrid = reg_val[1];
                    $("#reg_customer_id").val(reg_val_usrid);
                    if (reg_val_ret == 1) {
                        $('#registerform').hide();
                        // $('.reg-form').hide();
                        $('#otp_reg_form').show();
                        $('#reg_otp_val').trigger("focus");
                        $('#otp_reg_form').addClass('otp_reg_show');
                        $('.reg-err').hide();
                    } else {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 1000);
                        $('.reg-err').html(data).css('display', 'block');
                        console.log(data);
                    }
                }
            });
            return false;
        }
    });
});
$('.register_btn').on('click', function () {
    var otpVal = $('#reg_otp_val').val();
    var userid = $('#reg_customer_id').val();
    var redirectUrl = $('#redirectUrlRegOtp').val();
    var x = 0;
    if (otpVal == '' || otpVal == undefined) {
        $('#reg_otp_val').parents('.floating-blk').addClass('err');
        x++;
    } else {
        $('#reg_otp_val').parents('.floating-blk').removeClass('err');
    }
    if (x > 0) {
        return false
    } else {
        $.ajax({
            type: "POST",
            cache: false,
            async: true,
            url: templateUri + '/ajax/ajax-otp.php',
            data: {
                otpval: otpVal,
                userid: userid,
                action: "otp_gen_reg"
            },
            success: function (data) {
                if (data == 1) {
                    $('#reg_otp_val').parents('.floating-blk').removeClass('err');
                    if ((redirectUrl == '') || (redirectUrl == undefined)) {
                        window.location.href = blogUri + "/my-account";
                    } else {
                        window.location.href = blogUri + '/' + redirectUrl;
                    }
                } else
                    if (data == 2) {
                        $('#reg_otp_val').parents('.floating-blk').addClass('err');
                        $('#reg_otp_err').html('Please enter a valid OTP');
                    } else if (data == 3) {
                        $('#reg_otp_val').parents('.floating-blk').addClass('err');
                        $('#reg_otp_err').html('Your OTP has expired. Please generate new OTP.');
                    }
            }
        });
        return false;
    }
});

$('.resendOtpReg').on('click', function () {
    // alert(90);
    $('#reg_otp_val').trigger("focus");
    var regUname = $('#reg_customer_id').val();
    $.ajax({
        type: "POST",
        url: templateUri + '/ajax/ajax-otp.php',
        data: {
            reg_user_id: regUname,
            action: "otp_regen_reg"
        },
        success: function (data) {
            console.log(data);
        }
    });
    return false;
});

function isInteger(x) {
    return x % 1 === 0;
}

function validateEmail(strValue) {
    var objRegExp = /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;
    return objRegExp.test(strValue);
}


$('#login_otp').on('click', function () {
    var otpVal = $('#login_otp_val').val();
    var userid = $('#logUserID').val();
    var lginUname = $('#username').val();
    var lginPwd = $('#password').val();
    var redirectUrl = $('#redirectUrlOtp').val();
    var x = '';
    // console.log(otpVal);
    // console.log(userid + 'userid');
    // console.log(lginUname + 'lginUname');
    // console.log(lginPwd + 'lginPwd');
    // console.log(redirectUrl + 'redirectUrl');
    if (otpVal == '') {
        $('#login_otp_val').parents('.floating-blk').addClass('err');
        return false;
    } else {
        $('#login_otp_val').parents('.floating-blk').removeClass('err');
        $.ajax({
            cache: false,
            async: true,
            type: "POST",
            url: templateUri + '/ajax/ajax-otp.php',
            data: {
                otpval: otpVal,
                userid: userid,
                lginUname: lginUname,
                lginPwd: lginPwd,
                action: "otp_gen_login"
            },
            success: function (data) {
                if (data == 1) {
                    $('#login_otp_val').parents('.floating-blk').removeClass('err');
                    if ((redirectUrl == '') || (redirectUrl == undefined)) {
                        window.location.href = blogUri + "/my-account";
                    } else {
                        window.location.href = blogUri + "/" + redirectUrl;
                    }
                } else if (data == 2) {
                    $('#login_otp_val').parents('.floating-blk').addClass('err');
                    $('#login_otp_err').html('Please enter a valid OTP.');
                } else if (data == 3) {
                    $('#login_otp_val').parents('.floating-blk').addClass('err');
                    $('#login_otp_err').html('Please enter a valid OTP.');
                }
            }
        });
        return false;
    }
});
var count = 0;
$('.resendOtpLogin').on('click', function () {
    $('#login_otp_val').parents('.floating-blk').removeClass('err');
    $("#login_otp_val").trigger("focus");
    var lginUname = $('#logUserID').val();
    count++;
    $.ajax({
        type: "POST",
        url: templateUri + '/ajax/ajax-otp.php',
        data: {
            log_user_id: lginUname,
            action: "otp_regen_login"
        },
        success: function (data) {
            console.log(data);
        }
    });
    return false;
});


// Cart update
$('.add_to_cart_btn').on('click', function () {
    var qty = $('.prd_qty').val();
    var product_id = $('#product_id').val();
    var product_type = $('#product_type').val();
    var variation_id = $('.pro_weight').find(":selected").attr('var-id');
   console.log(product_type);
    $.ajax({
        type: 'GET',
        url: templateUri + '/ajax/ajax_add_to_cart.php',
        data: {
            qty: qty,
            product_id: product_id,
            product_type: product_type,
            variation_id: variation_id,
        },
        success: function (response) {
            if (response.status === 'success') {
                // Handle the success response, e.g., update the mini cart
                $('.mini_cart_box').html(response.mini_cart);
                location.reload();
            } else if (response.status === 'different') {
               if(response.Product_type === 'domestic'){
                  $.ajax({
                    type: 'GET',
                    url: templateUri + '/ajax/ajax_check_cart_items.php',
                    success: function (itemCountResponse) {
                        if (parseInt(itemCountResponse) > 0) {
                            // Cart has items, product type is different, show the popup
                            $('.cart_popup_global').show();
                         } else {
                            $.ajax({
                                type: 'GET',
                                url: templateUri + '/ajax/ajax_add_to_cart.php',
                                data: {
                                    qty: qty,
                                    product_id: product_id,
                                    product_type: product_type,
                                    variation_id: variation_id,
                                },
                                success: function (response) {
                                    if (response.status === 'success') {
                                        // Handle the success response, e.g., update the mini cart
                                        $('.mini_cart_box').html(response.mini_cart);
                                        location.reload();
                                    }
                                }
                            });
                        }
                    }
                });
               }else{
                $.ajax({
                    type: 'GET',
                    url: templateUri + '/ajax/ajax_check_cart_items.php',
                    success: function (itemCountResponse) {
                        if (parseInt(itemCountResponse) > 0) {
                            // Cart has items, product type is different, show the popup
                            $('.cart_popup').show();
                         } else {
                            $.ajax({
                                type: 'GET',
                                url: templateUri + '/ajax/ajax_add_to_cart.php',
                                data: {
                                    qty: qty,
                                    product_id: product_id,
                                    product_type: product_type,
                                    variation_id: variation_id,
                                },
                                success: function (response) {
                                    if (response.status === 'success') {
                                        // Handle the success response, e.g., update the mini cart
                                        $('.mini_cart_box').html(response.mini_cart);
                                        location.reload();
                                    }
                                }
                            });
                        }
                    }
                });
            }
            }
         
        }
    });
});

$('.clear_cart').on('click', function () {
    var qty = $('.prd_qty').val();
    var product_id = $('#product_id').val();
    var product_type = $('#product_type').val();
    var variation_id = $('.pro_weight').find(":selected").attr('var-id');
    $.ajax({
        type: 'GET',
        url: templateUri + '/ajax/ajax_clear_cart_and_add.php',
        data: {
            qty: qty,
            product_id: product_id,
            product_type: product_type,
            variation_id: variation_id,
        },
        success: function (response) {
            if (response) {
                location.reload();
            }
        }
    });
});
 
function setProductType() {
    var product_type = $('#product_type').val();
    var currentProductType = product_type;
    console.log(currentProductType);
    var newProductType = (currentProductType === "global") ? "domestic" : "global"; // Toggle the product type
    console.log(newProductType);
    // Make an AJAX request to set the new product type in the session
    $.ajax({
        type: 'GET',
        url: templateUri + '/ajax/set_product_type.php',
        data: { product_type: newProductType },
        success: function (response) {
            if (response) {
                console.log('Product type set to: ' + newProductType);
            } else {
                console.log('Error setting product type');
            }
        }
    });
}

$('.not_clear_cart, .popup_outside_click').on('click', function () {
    setProductType();
});

// Function to update the cart count dynamically
function updateheaderCartCount() {
    $.ajax({
        url: blogUri + "/wp-admin/admin-ajax.php",
        type: 'POST',
        data: {
            action: 'update_cart_count'
        },
        success: function (response) {
            $('.header_cart_count').text(response); // Update the cart count text
            if (response > 0) {
                $('.header_cart_count').show(); // Show the cart count if it's greater than 0
            } else {
                $('.header_cart_count').hide(); // Hide the cart count if it's 0
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}

// Update the cart count initially
updateheaderCartCount();
// Domestic cart qty incre decre
$(document).ready(function () {
    $('.cart-qty-minus').each(function () {
        var qty = parseInt($(this).attr('data-qty'));
        if (qty > 1) {
            $(this).addClass('active');
        }
    });
    $('.cart-qty-plus').each(function () {
        var qty = parseInt($(this).attr('data-qty'));
        if (qty == 20) {
            $(this).removeClass('active');
        }
    });
});
$('.cart-qty-plus, .cart-qty-minus').on('click', function () {
   
    var cartQtyMinus = $(this).closest('.count').find('.cart-qty-minus');
    var cartQtyPlus = $(this).closest('.count').find('.cart-qty-plus');
    var cart_item_key = $(this).attr('data-key');
    var qty = $(this).attr('data-qty');
    var variation_id = $(this).attr('data-variation-id');
    if ($(this).hasClass('cart-qty-plus')) {
        if (qty < 20) {
            qty++;
            cartQtyMinus.addClass('active');
        } if (qty == 20) {
            $(this).removeClass('active');
        }

    } else if ($(this).hasClass('cart-qty-minus')) {
        if (qty > 1) {
            qty--;
            cartQtyPlus.addClass("active");
        }
        if (qty == 1) {
            $(this).removeClass('active');
        }
    }
    $.ajax({
        type: "POST",
        url: blogUri + "/wp-admin/admin-ajax.php",
        data: {
            action: "update_item_from_minicart",
            cart_item_key: cart_item_key,
            qty: qty,
            variation_id: variation_id
        },
        success: function (response) {
            updateheaderCartCount();
            if (response.success) {
                var updatedQty = response.data.quantity;
                var updatedPrice = response.data.price;
                var updatedTotal = response.data.total;
                var productID = response.data.product_id;
                var variationID = response.data.variation_id;
                // console.log(variationID);
                // console.log(updatedQty);
                console.log(updatedTotal);

                $('#dummyQty' + variationID).val(updatedQty);
                $('#cart_price' + variationID).html(updatedPrice);

                $('#cart_total').html(updatedTotal);
                $('.cart-qty-plus[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);
                $('.cart-qty-minus[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);

            }
        }
    });
});

//Global cart qty incre decre
$(document).ready(function () {

    $('.cart-global-qty-minus').each(function () {
        var qty = parseInt($(this).attr('data-qty'));
        var minqty = parseInt($(this).attr('data-min'));
        var maxqty = parseInt($(this).attr('data-max'));
        if (qty > minqty) {
            $(this).addClass('active');
        }
        if (minqty == maxqty) {
            $(this).removeClass('active');
        }
    });
    $('.cart-global-qty-plus').each(function () {
        var qty = parseInt($(this).attr('data-qty'));
        var maxqty = parseInt($(this).attr('data-max'));
        var minqty = parseInt($(this).attr('data-min'));
        if (qty == maxqty) {
            $(this).removeClass('active');
        }
        if (minqty == maxqty) {
            $(this).removeClass('active');
        }
    });

});
$('.cart-global-qty-plus, .cart-global-qty-minus').on('click', function () {
    var cartQtyMinus = $(this).closest('.count').find('.cart-global-qty-minus');
    var cartQtyPlus = $(this).closest('.count').find('.cart-global-qty-plus');
    var cart_item_key = $(this).attr('data-key');
    var qty = parseInt($(this).attr('data-qty'));
    var variation_id = $(this).attr('data-variation-id');
    var minqty = parseInt($(this).attr('data-min'));
    var maxqty = parseInt($(this).attr('data-max'));
    // console.log(qty);
    // console.log(typeof minqty);
    // console.log(typeof maxqty);
    if ($(this).hasClass('cart-global-qty-plus')) {
        if (qty < maxqty) {
            qty++;
            cartQtyMinus.addClass('active');
        }
        if (qty == maxqty) {
            $(this).removeClass("active");
        }
    } else if ($(this).hasClass('cart-global-qty-minus')) {
        if (qty > minqty) {
            qty--;
            cartQtyPlus.addClass("active");
        }
        if (qty == minqty) {
            $(this).removeClass('active');
        }
    }
    $.ajax({
        type: "POST",
        url: blogUri + "/wp-admin/admin-ajax.php",
        data: {
            action: "update_item_from_cart",
            cart_item_key: cart_item_key,
            qty: qty,
            variation_id: variation_id
        },
        success: function (response) {
            updateheaderCartCount();
            if (response.success) {
                var updatedQty = response.data.quantity;
                var updatedPrice = response.data.price;
                var updatedTotal = response.data.total;
                var productID = response.data.product_id;
                var variationID = response.data.variation_id;
                // console.log(variationID);
                // console.log(updatedQty);
                // console.log(updatedPrice);

                $('#global_qty' + variationID).val(updatedQty);
                $('#cart_price' + variationID).html(updatedPrice);

                $('#cart_total').html(updatedTotal);
                $('.cart-global-qty-plus[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);
                $('.cart-global-qty-minus[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);

            }
        }
    });
});
// Coupon code function - start
$('.apply_coupon').on('click', function (e) {
    var coupon = $("#apply_coupon").val();
    
    if (coupon == '' || coupon == undefined) {
        $('.err-msg').addClass('active');
    } else {
        $('.err-msg').removeClass('active');
        $.ajax({
            type: "POST",
            url: blogUri + "/wp-admin/admin-ajax.php",
            data: {
                action: 'my_special_action',
                couponcode: coupon
            },
            success: function (data) {
                console.log(data);
                if (data != 1) {
                    // alert("test"); 
                    // location.reload();
                } else { 
                    $('.coupon-succ').css('display', 'none');
                    $('.voucher-code').css('display', 'block');
                    $('.voucher-code').after('<div class="err-msg active">Please enter a valid coupon.</div>');
                }
            }
        });
    }
});

$('.country_select').on('change', function (e) {
    var country_code = $(this).find('option:selected').attr('data-code');
    var shipping_cost = $(this).find('option:selected').attr('data-val');
    e.preventDefault();
    if (country_code != "" || country_code != undefined) {
        $.ajax({
            type: "POST",
            url: blogUri + "/wp-admin/admin-ajax.php",
            data: {
                action: 'country_shipping',
                country_code: country_code,
                shipping_cost: shipping_cost,
            },
            success: function (data) {
                location.reload();
            }
        });
    }
});
// Coupon code function end

// Remove product to cart
$('.product_remove_cart').on('click', function (e) {
    e.preventDefault();
    var productId = $(this).data('product-id');
    var cartItemRow = $(this).closest('.t-row');
    $.ajax({
        url: blogUri + "/wp-admin/admin-ajax.php",
        type: 'POST',
        data: {
            action: 'remove_from_cart',
            product_id: productId,
        },
        success: function (response) {
            if (response.success) {
                // Remove the cart item row
                cartItemRow.remove();

                var cartTotal = response.data.cart_total;
                // Check if the cart total is zero
                if (cartTotal === "<span class=\"woocommerce-Price-amount amount\"><bdi><span class=\"woocommerce-Price-currencySymbol\">&#8377;</span>0.00</bdi></span>") {
                    // Reload the page if the cart total matches
                    location.reload();

                } else {
                    // Reload the page if the cart total is zero
                    $('#cart_total').html(cartTotal);
                }
            } else {
                console.log(response.data);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
        }
    });
});
$('.mini_cart_product_remove').on('click', function (e) {
    e.preventDefault();
    var productId = $(this).data('product-id');
    var variationId = $(this).data('variation-id');
    var cartremove = $(this).closest('.cartremove');
    var globcheck = $(".mini_add_to_cart").data("cart-type");
    $.ajax({
        url: blogUri + "/wp-admin/admin-ajax.php",
        type: 'POST',
        data: {
            action: 'remove_from_cart',
            product_id: variationId,
        },
        success: function (response) {
            updateheaderCartCount();
            if (response.success) {
                // Remove the cart item row
                cartremove.remove();
                var cart_total_with_shipping = response.data.cart_total_with_shipping;
                var cartTotal = response.data.cart_total;
                console.log(cartTotal);
                // Check if the cart total is zero
                if (cartTotal === "<span class=\"woocommerce-Price-amount amount\"><bdi><span class=\"woocommerce-Price-currencySymbol\">&#8377;</span>0.00</bdi></span>") {
                    $.ajax({
                        url: blogUri + "/wp-admin/admin-ajax.php",
                        type: 'POST',
                        data: {
                            action: 'empty_cart',
                        },
                        success: function (response) {
                            if (response.success) {
                                // Cart is emptied, reload the page
                                location.reload();
                            }
                        }
                    });

                } else {
                    var var_id_plus = $('#pro_weight option:selected').attr('var-id');
                    var var_idglo_plus = $('#glo_weight option:selected').attr('var-id');
                    if (variationId == var_id_plus || variationId == var_idglo_plus) {
                        var addTocartBtn = $('.quantity-blk .add_to_cart_btn');
                        addTocartBtn.css({
                            'pointer-events': 'auto',
                            'opacity': '1'
                        });
                    }
                    var itemCount = response.data.cart_count;
                    updateCartCount(itemCount);
                    // Reload the page if the cart total is zero
                    if (globcheck == "domestic") {
                        $('.without_ship_remove_tot').html(cartTotal);
                    } else {
                        $('#cart_total').html(cart_total_with_shipping);
                    }
                }
            } else {
                console.log(response.data);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
        }
    });
});
function updateCartCount(count) {
    var cartCountElement = $('.mini_cart_count');
    if (count === 1) {
        cartCountElement.text('(' + count + ' Item)');
    } else {
        cartCountElement.text('(' + count + ' Items)');
    }
}
// Checkout JS
$(".proceed_checkout").on("click", function () {
    console.log("Login Button");
    // alert("User Id");
    $(this).addClass("pointer-disab");
    var lginUname = $('.username').val();
    var lginPwd = $('.password').val();
    var x = 0;
    if (lginUname == '') {
        $('.username').parents('.floating-blk').addClass('err');
        x++;
    } else {
        $('.username').parents('.floating-blk').removeClass('err');
    }
    if (!isInteger(lginUname) && lginUname != '') {
        var regEmail = lginUname;
        if (lginUname != '' && validateEmail(lginUname) === false) {
            $('.username').parents('.floating-blk').addClass('err');
            $('.log_email').html('Do enter a valid email address here.');
            x++;
        } else {
            $('.username').parents('.floating-blk').removeClass('err');
        }
    }
    if (isInteger(lginUname) && lginUname != '') {
        var regPhone = lginUname;
        if (lginUname != '' && lginUname.length < 10) {
            $('.username').parents('.floating-blk').addClass('err');
            $('.log_email').html('Please give us a valid phone number.');
            x++;
        } else {
            $('.username').parents('.floating-blk').removeClass('err');
        }
    }
    if (x == 0) {
        $.ajax({
            type: "POST",
            cache: false,
            async: true,
            url: templateUri + '/ajax/guest-ajax.php',
            data: {
                lginUname: lginUname,
                regPhone: regPhone,
                regEmail: regEmail,
            },
            success: function (data) {
                console.log(data);
                var reg_val = data.split('|');
                var reg_val_ret = reg_val[0];
                var reg_val_usrid = reg_val[1];
                if (reg_val_ret == 1) {
                    $("#guest_customer_id").val(reg_val_usrid);
                    $(".guest_checkout").css('display', 'none'); 
                    $(".login_india_text").hide();
                    $(".login_outside_india_text").hide();
                    $(".guest_checkout_otp").css('display', 'block');
                } else if (reg_val_ret == 2) {
                    $("#user_customer_id").val(reg_val_usrid);
                    $(".guest_checkout").css('display', 'none');
                    $(".login_outside_india_text").hide();
                    $(".login_india_text").hide();
                    $(".user_checkout_otp").css('display', 'block');
                }
            }
        });
    }else{
        $(this).removeClass("pointer-disab");
    }

});
/* global error meage hide */
$(document).on('click', '.close-button', function () {
    $('.alert-msg').hide();
});

$(document).on('keyup keypress', '.login_otp #username', function () {
    var lginUname = $('.login_otp #username').val();
    if (lginUname.length > 1) {
        $('#username').parents('.floating-blk').removeClass('err');
        $('.alert-msg').hide();
    }
});
$(document).on('keyup keypress', '.otp_lgin_show #login_otp_val', function () {
    var otpVal = $('.otp_lgin_show #login_otp_val').val();
    if (otpVal.length > 1) {
        $('.otp_lgin_show #login_otp_val').parents('.floating-blk').removeClass('err');
        $('.alert-msg').hide();
    }
});
$(document).on('keyup keypress', '.otp_reg_form #reg_otp_val', function () {
    var otpVal = $('.otp_reg_form #reg_otp_val').val();
    if (otpVal.length > 1) {
        $('.otp_reg_form #reg_otp_val').parents('.floating-blk').removeClass('err');
        $('.alert-msg').hide();
    }
});
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function isText(evt) {
    var keyCode = (evt.which) ? evt.which : evt.keyCode
    if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)

        return false;
    return true;
}

// $(document).ready(function () {
//     var country_val = ($("#country_id").val());
//     if (country_val == "" || country_val == "Select Country" || country_val == undefined) {
//         $('.cart_chkout').addClass('pointer-disab');
//         $('.country_select').parents('.floating-blk').addClass('err');
//     }else{
//     	 $('.country_select').parents('.floating-blk').removeClass('err');
//         $('.cart_chkout').removeClass('pointer-disab');
//     }
// });
$(document).ready(function () {
    var $input = $('#registerform .floating-input');
    $(document).on('keyup keypress', '#registerform .floating-input', function () {
        var $inputVal = $('#registerform .floating-input').val();
        $input.each(function () {
            var $thisValue = $(this).val();
            if ($thisValue.length != 0) {
                $(this).parents('.floating-blk').removeClass('err');
            }
        });
    });
});
$("#registerform #checkbox2").on("click", function () {
    if ($("#registerform #checkbox2").is(":checked")) {
        $('#registerform #checkbox2').parents('.terms-err').removeClass('err');
    } else {
        $(' #registerform #checkbox2').parents('.terms-err').addClass('err');
        x++;
    }
});

// Checkout JS
$(document).on('click', '#checkout_apply_coupon', function () {
    $(".success-msg").removeClass('active');
    // Get the coupon code
    var code = $('#checkout_coupon_code').val();
    if (code == '' || code == undefined) {
        $(".success-msg").removeClass('active');
        $('.err-msg').addClass('active');
    }
    else {
        $('.err-msg').removeClass('active');
        // Send it over to WordPress.
        $.ajax({
            type: "POST",
            url: blogUri + "/wp-admin/admin-ajax.php",
            data: {
                action: 'ajax_apply_coupon',
                coupon_code: code
            },
            success: function (data) {
                console.log(data);
                if (data == 3) {
                    $(".success-msg").removeClass('active');
                    $('.err-msg').addClass('active');
                    $(document.body).trigger('update_checkout');
                } else {
                    $('.applied_txt').text("#" + code);
                    $(".success-msg").addClass('active');
                    $('.err-msg').removeClass('active');
                    $(document.body).trigger('update_checkout');
                    location.reload();
                }
            }
        });
    }
});
$(document).on('click', '.ccode-remove', function (e) {
    // Get the coupon code
    $.ajax({
        type: "POST",
        url: blogUri + "/wp-admin/admin-ajax.php",
        data: {
            action: 'ajax_remove_coupon',
            coupon_code: "remove"
        },
        success: function (data) {
            console.log(data);
            $(".success-msg").removeClass('active');
            jQuery(document.body).trigger('update_checkout');
            location.reload();

        }
    });
});
$(document).on('click', '.code-remove', function (e) {
    // Get the coupon code
    $.ajax({
        type: "POST",
        url: blogUri + "/wp-admin/admin-ajax.php",
        data: {
            action: 'ajax_remove_coupon',
            coupon_code: "remove"
        },
        success: function (data) {
            console.log(data);
            jQuery(document.body).trigger('update_checkout');
            // $('#coupon-success-container').removeClass('active');
            // $('.apply_coupon_check_btn').show();
            location.reload();
        }
    });
});
//  Billing address validation

$(document).ready(function () {
    var $input = $('.checkout .floating-input');
    $(document).on('keyup keypress', '.checkout .floating-input', function () {
        var $inputVal = $('.checkout .floating-input').val();
        $input.each(function () {
            var $thisValue = $(this).val();
            if ($thisValue.length != 0) {
                $(this).parents('.floating-blk').removeClass('err');
            }
        });
    });
});
//only input type number & only input type text
$('#billing_phone').bind('keypress paste', function (evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode != 46 && charCode > 31 &&
        (charCode < 48 || charCode > 57))
        return false;
    return true;

});
$('#billing_first_name,#billing_last_name,#billing_state,#billing_city').bind('keypress paste', function (evt) {
    var keyCode = (evt.which) ? evt.which : evt.keyCode
    if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
        return false;
    return true;

});
// Guest OTP Verification 
$(".guest_otp_verify").on('click', function () {
    var otpVal = $('#guest_user_otp').val();
    var userid = $('#guest_customer_id').val();
    console.log(otpVal);
    console.log(userid);
    if (otpVal == "" || otpVal == undefined) {
        $('#guest_user_otp').parents('.floating-blk').addClass('err');
    } else {
        $('#guest_user_otp').parents('.floating-blk').removeClass('err');
        $.ajax({
            cache: false,
            async: true,
            type: "POST",
            url: templateUri + '/ajax/ajax-otp.php',
            data: {
                otpval: otpVal,
                userid: userid,
                action: "otp_guest_gen_login"
            },
            success: function (data) {
                if (data == 1) {
                    $(document.body).trigger('update_checkout');
                    //                $('#guest_user_otp').parents('.floating-blk').removeClass('err');
                    //                $(".guest_checkout").css('display', 'block');
                    //                $(".guest_checkout_otp").css('display', 'none');
                    location.reload();
                } else if (data == 2) {
                    $('#guest_user_otp').parents('.floating-blk').addClass('err');
                    $('.guest_otp').html('Enter a valid OTP here.');
                } else if (data == 3) {
                    $('#guest_user_otp').parents('.floating-blk').addClass('err');
                    $('.guest_otp').html('Enter a valid OTP here.');
                }
            }
        });
        return false;
    }

});
$('#checkout-form #billing_country').on('change', function (e) {
    // alert(2);
    var country_code = $(this).find('option:selected').val();
    // var shipping_cost = $(this).find('option:selected').attr('data-val');
    e.preventDefault();
    if (country_code != "" || country_code != undefined) {
        $.ajax({
            type: "POST",
            url: blogUri + "/wp-admin/admin-ajax.php",
            data: {
                action: 'country_shipping',
                country_code: country_code,
            },
            success: function (data) {
                console.log(data);
                $('html, body').animate({
                    scrollTop: 0
                }, 1000);
                $(document.body).trigger('update_checkout');
                location.reload();
            }
        });
    }
});
// Guest Resend OTP
$('.resendOtpguestLogin').on('click', function () {
    $('#guest_user_otp').parents('.floating-blk').removeClass('err');
    $("#guest_user_otp").trigger("focus");
    var lginUname = $('#guest_customer_id').val();
    count++;
    $.ajax({
        type: "POST",
        url: templateUri + '/ajax/ajax-otp.php',
        data: {
            reg_user_id: lginUname,
            action: "otp_regen_reg"
        },
        success: function (data) {
            console.log(data);
        }
    });
    return false;
});
// Sign in New requirement like checkout
$(".login_otp_func").on("click", function () {
    // alert("User Id");
    var lginUname = $('#username').val();
    var lginPwd = $('#password').val();
    var x = 0;
    if (lginUname == '') {
        $('#username').parents('.floating-blk').addClass('err');
        x++;
    } else {
        $('#username').parents('.floating-blk').removeClass('err');
    }
    if (!isInteger(lginUname) && lginUname != '') {
        var regEmail = lginUname;
        if (lginUname != '' && validateEmail(lginUname) === false) {
            $('#username').parents('.floating-blk').addClass('err');
            $('#log_email').html('Do enter a valid email address here.');
            x++;
        } else {
            $('#username').parents('.floating-blk').removeClass('err');
        }
    }
    if (isInteger(lginUname) && lginUname != '') {
        var regPhone = lginUname;
        if (lginUname != '' && lginUname.length < 10 || lginUname.length > 15) {
            $('#username').parents('.floating-blk').addClass('err');
            $('#log_email').html('Please give us a valid phone number.');
            x++;
        } else {
            $('#username').parents('.floating-blk').removeClass('err');
        }
    }
    if (x == 0) {
        $.ajax({
            type: "POST",
            cache: false,
            async: true,
            url: templateUri + '/ajax/guest-ajax.php',
            data: {
                lginUname: lginUname,
                regPhone: regPhone,
                regEmail: regEmail,
            },
            success: function (data) {
                console.log(data);
                var reg_val = data.split('|');
                var reg_val_ret = reg_val[0];
                var reg_val_usrid = reg_val[1];
                if (reg_val_ret == 1) {
                    $("#guest_customer_id").val(reg_val_usrid);
                    $(".login_otp").css('display', 'none');
                    $(".guest_checkout_otp").css('display', 'block');
                } else if (reg_val_ret == 2) {
                    $("#user_customer_id").val(reg_val_usrid);
                    $(".login_otp").css('display', 'none');
                    $(".user_checkout_otp").css('display', 'block');
                }
            }
        });
    }

});