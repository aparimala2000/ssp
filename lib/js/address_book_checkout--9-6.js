//  Billing address validation
$(".new_add_btn").on("click", function () {
    var u_id = $('.user-info #updateuser_id').val();
    var uphone = $('.user-info #phone').val();
    var ufname = $('.user-info #first_name').val();
    var uemail = $('.user-info #email').val();
    var regex = /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;
    var i = 0;
    if (uphone !== undefined) {
        if (uphone == "") {
            $(".user-info #phone").parents(".floating-blk").addClass("err");
            if ($(".user-info #phone").parents(".floating-blk").find(".floating-input-error").length != 0) {
                $(".user-info #phone").parents(".floating-blk").find(".floating-input-error").remove();
            }
            $('<p class="floating-input-error">Please enter your phone number</p>').appendTo($(".user-info #phone").parents(".floating-blk")).slideDown();
            i++;
        } else if (uphone.length < 10 || uphone.length > 15) {
            $(".user-info #phone").parents(".floating-blk").addClass("err");
            if ($(".user-info #phone").parents(".floating-blk").find(".floating-input-error").length != 0) {
                $(".user-info #phone").parents(".floating-blk").find(".floating-input-error").remove();
            }
            $('<p class="floating-input-error">Please enter a valid phone number</p>').appendTo($(".user-info #phone").parents(".floating-blk")).slideDown();
            i++;
        } else {
            $(".user-info #phone").parents(".floating-blk").removeClass("err");
        }
    }
    if (ufname !== undefined) {
        if (ufname == "") {
            $(".user-info #first_name").parents(".floating-blk").addClass("err");
            if ($(".user-info #first_name").parents(".floating-blk").find(".floating-input-error").length != 0) {
                $(".user-info #first_name").parents(".floating-blk").find(".floating-input-error").remove();
            }
            $('<p class="floating-input-error">Please enter your name.</p>').appendTo($(".user-info #first_name").parents(".floating-blk")).slideDown();
            i++;
        } else {
            $(".user-info #first_name").parents(".floating-blk").removeClass("err");
        }
    }
    if (uemail !== undefined) {
        if (uemail == "") {
            $(".user-info #email").parents(".floating-blk").addClass("err");
            if ($(".user-info #email").parents(".floating-blk").find(".floating-input-error").length != 0) {
                $(".user-info #email").parents(".floating-blk").find(".floating-input-error").remove();
            }
            $('<p class="floating-input-error">Please enter your email</p>').appendTo($(".user-info #email").parents(".floating-blk")).slideDown();
            i++;
        } else if (uemail != '') {
            if (!regex.test(uemail)) {
                $('.user-info #email').parents('.floating-blk').addClass('err');
                if ($(".user-info #email").parents(".floating-blk").find(".floating-input-error").length != 0) {
                    $(".user-info #email").parents(".floating-blk").find(".floating-input-error").remove();
                }
                $('<p class="floating-input-error">Please enter a valid email</p>').appendTo($(".user-info #email").parents(".floating-blk")).slideDown();
                i++;
            } else {
                $('.user-info #email').parents('.floating-blk').removeClass('err');
            }
        } else {
            $(".user-info #email").parents(".floating-blk").removeClass("err");
        }
    }
    // console.log(i+"Error value");
    if (i == 0) {
        $.ajax({
            type: "POST",
            url: blogUri + "/wp-admin/admin-ajax.php",
            data: {
                user_id: u_id,
                uphone: uphone,
                ufname: ufname,
                uemail: uemail,
                action: 'ajax_update_user',
            },
            success: function (data) {
                console.log(data);
                if (data == 3) {
                    $('.user-info #email').parents('.floating-blk').addClass('err');
                    if ($(".user-info #email").parents(".floating-blk").find(".floating-input-error").length != 0) {
                        $(".user-info #email").parents(".floating-blk").find(".floating-input-error").remove();
                    }
                    $('<p class="floating-input-error">The email already associated with an another account.</p>').appendTo($(".user-info #email").parents(".floating-blk")).slideDown();
                } else if (data == 2) {
                    $(".user-info #phone").parents(".floating-blk").addClass("err");
                    if ($(".user-info #phone").parents(".floating-blk").find(".floating-input-error").length != 0) {
                        $(".user-info #phone").parents(".floating-blk").find(".floating-input-error").remove();
                    }
                    $('<p class="floating-input-error">The mobile number already associated with an another account.</p>').appendTo($(".user-info #phone").parents(".floating-blk")).slideDown();
                    i++;
                } else {
                    $('.new_add_btn').toggleClass("active"), $(".adrs-form").addClass("active"), $(".bg-layer").toggleClass("active");
                    jQuery('.woocommerce-checkout #billing_address_1').val("");
                    jQuery('.woocommerce-checkout #billing_city').val("");
                    jQuery('.woocommerce-checkout #billing_state').val("");
                    jQuery('.woocommerce-checkout #billing_postcode').val("");
                    // jQuery('.woocommerce-checkout #billing_phone').val("");
                    jQuery('.woocommerce-checkout .floating-blk').removeClass("active");
                    jQuery('.woocommerce-checkout #billing_country').parents('floating-blk').removeClass("active");

                }
            }
        });
    }
});
// New address
jQuery(document).on("click", "#continue_addr_checkout", function (e) {
    var user_id = $("#billing_user_id").val();
    var billing_first_name = $("#billing_first_name_field #billing_first_name").val();
    var billing_last_name = $("#billing_last_name_field #billing_last_name").val();
    var billing_phone = $("#billing_phone_field #billing_phone").val();
    var billing_email = $("#billing_email_field #billing_email").val();
    var billing_address_1 = $("#billing_address_1_field #billing_address_1").val();
    var billing_state = $("#billing_state_field #billing_state").val();
    var billing_city = $("#billing_city_field #billing_city").val();
    var billing_postcode = $("#billing_postcode_field #billing_postcode").val();
    var billing_country = $("#billing_country_field #billing_country").val();
    var address_group = ($("input[type=radio][name='address-group-name']:checked").val());
    var i = 0;
    if (billing_address_1 == "" || billing_address_1 == undefined) {
        $("#billing_address_1_field #billing_address_1").parents(".floating-blk").addClass("err");
        if ($("#billing_address_1_field #billing_address_1").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_address_1_field #billing_address_1").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $('<p class="floating-input-error">Please enter your address</p>').appendTo($("#billing_address_1_field #billing_address_1").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_address_1_field #billing_address_1").parents(".floating-blk").removeClass("err");
    }
    if (billing_city == "" || billing_city == undefined) {
        $("#billing_city_field #billing_city").parents(".floating-blk").addClass("err");
        if ($("#billing_city_field #billing_city").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_city_field #billing_city").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $('<p class="floating-input-error">Please enter your city</p>').appendTo($("#billing_city_field #billing_city").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_city_field #billing_city").parents(".floating-blk").removeClass("err");
    }
    if (billing_state == "" || billing_state == undefined) {
        $("#billing_state_field #billing_state").parents(".floating-blk").addClass("err");
        if ($("#billing_state_field #billing_state").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_state_field #billing_state").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $('<p class="floating-input-error">Please enter your state</p>').appendTo($("#billing_state_field #billing_state").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_state_field #billing_state").parents(".floating-blk").removeClass("err");
    }
    if (billing_postcode == "" || billing_postcode == undefined) {
        $("#billing_postcode_field #billing_postcode").parents(".floating-blk").addClass("err");
        if ($("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $('<p class="floating-input-error">Please enter your pin code</p>').appendTo($("#billing_postcode_field #billing_postcode").parents(".floating-blk")).slideDown();
        i++;
    } else if (billing_postcode.length < 4 || billing_postcode.length > 6) {
        if ($("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $("#billing_postcode_field #billing_postcode").parents(".floating-blk").addClass("err");
        $('<p class="floating-input-error">Please enter a valid pin code</p>').appendTo($("#billing_postcode_field #billing_postcode").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_postcode_field #billing_postcode").parents(".floating-blk").removeClass("err");
    }
    $("html, body").animate({
        scrollTop: $(".checkout").offset().top - 20
    }, "slow");
    console.log(i);
    if (i == 0) {
        $.ajax({
            type: "POST",
            url: blogUri + "/wp-admin/admin-ajax.php",
            data: {
                action: 'add_user_chkoutaddress',
                user_id: user_id,
                billing_first_name: billing_first_name,
                billing_last_name: billing_last_name,
                billing_email: billing_email,
                billing_phone: billing_phone,
                billing_address_1: billing_address_1,
                billing_city: billing_city,
                billing_state: billing_state,
                billing_postcode: billing_postcode,
                billing_country: billing_country,
                address_group: address_group,
            },
            success: function (data) {
                console.log(data);
                $('html, body').animate({
                    scrollTop: 0
                }, 1000);
                $(document.body).trigger('update_checkout');
                location.reload();
                $('.add-adrs-close').trigger('click');
                // alert(i);
            }
        });
    }
});
// Switch address for checkout
$(".address_choose").on("click", function () {
     var selected_id = $(this).attr('data-id');
     var selected_add_val = $(this).attr('addrs-val');
         const selectVal = selected_add_val;
         if (selectVal != undefined) {
             const [first_name, last_name, email_address, phone_number, address, city, state, postcode, country, address_key] = selectVal.split('~');
            //  jQuery('.woocommerce-checkout #billing_first_name').val(first_name);
            //  jQuery('.woocommerce-checkout #billing_last_name').val(last_name);
            //  jQuery('.woocommerce-checkout #billing_email').val(email_address);
             jQuery('.woocommerce-checkout #billing_address_1').val(address);
             jQuery('.woocommerce-checkout #billing_city').val(city);
             jQuery('.woocommerce-checkout #billing_state').val(state);
             jQuery('.woocommerce-checkout #billing_postcode').val(postcode);
             jQuery('.woocommerce-checkout #billing_country').val(country);
            //  jQuery('.woocommerce-checkout #billing_phone').val(phone_number);
             jQuery('.woocommerce-checkout #shipping_address_1').val(address);
             jQuery('.woocommerce-checkout #shipping_city').val(city);
             jQuery('.woocommerce-checkout #shipping_state').val(state);
             jQuery('.woocommerce-checkout #shipping_postcode').val(postcode);
             jQuery('.woocommerce-checkout #shipping_country').val(country);
             jQuery('.woocommerce-checkout #addresskey').val(address_key);
         }
      $(document).find(".show_div").each(function () {
          var show_id = $(this).attr('data-val');
        if (selected_id == show_id) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    var t = $(this),
        e = t.parents(".accord-content"),
        i = t.parents(".accord-blk").next(),
        s = t.parents(".accord-blk");
    s.addClass("finish"), s.removeClass("active"), s.find(".before").hide(), s.find(".after").show(), e.hide(), i.addClass("active"), i.find(".accord-content").show();
  
});
// Edit existing address
$('.checkout_edit_address').click(function () {
    var $this = $(this).attr('addrs-val');
    $('.new_add_btn').toggleClass("active"), $(".adrs-form").addClass("active"), $(".bg-layer").toggleClass("active");
    $('#continue_addr_checkout').attr('id', 'continue_update_checkout');
    // alert($this);
    const selectVal = $this;
    if (selectVal != undefined) {
        const [first_name, last_name, email_address, phone_number, address, city, state, postcode, country, address_key, address_group] = selectVal.split('~');
        jQuery('#billing_state_field #billing_state').parents('.floating-blk').addClass('active');
        jQuery('#billing_postcode_field #billing_postcode').parents('.floating-blk').addClass('active');
        jQuery('#billing_city_field #billing_city').parents('.floating-blk').addClass('active');
        jQuery('#billing_address_1_field #billing_address_1').parents('.floating-blk').addClass('active');
        jQuery('#billing_first_name_field #billing_first_name').val(first_name);
        jQuery('#billing_last_name_field #billing_last_name').val(last_name);
        jQuery('#billing_email_field #billing_email').val(email_address);
        jQuery('#billing_address_1_field #billing_address_1').val(address);
        jQuery('#billing_city_field #billing_city').val(city);
        jQuery('#billing_state_field #billing_state').val(state);
        jQuery('#billing_postcode_field #billing_postcode').val(postcode);
        jQuery('#billing_phone_field #billing_phone').val(phone_number);
        jQuery('#billing_country_field #billing_country').val(country);
        jQuery('#address_key_field #address_key').val(address_key);
        // jQuery('#addresskey').val(address_key);
        $("input[type=radio][name='address-group-name']:checked").val(address_group);

    }
});


// Update address checkout

jQuery(document).on("click", "#continue_update_checkout", function (e) {
    var user_id = $("#billing_user_id").val();
    var billing_first_name = $("#billing_first_name_field #billing_first_name").val();
    var billing_last_name = $("#billing_last_name_field #billing_last_name").val();
    var billing_phone = $("#billing_phone_field #billing_phone").val();
    var billing_email = $("#billing_email_field #billing_email").val();
    var billing_address_1 = $("#billing_address_1_field #billing_address_1").val();
    var billing_state = $("#billing_state_field #billing_state").val();
    var billing_city = $("#billing_city_field #billing_city").val();
    var billing_postcode = $("#billing_postcode_field #billing_postcode").val();
    var billing_country = $("#billing_country_field #billing_country").val();
    var address_group = ($("input[type=radio][name='address-group-name']:checked").val());
    var address_key = $('#address_key_field #address_key').val();
    // alert(address_key);
    var i = 0;
    if (billing_address_1 == "" || billing_address_1 == undefined) {
        $("#billing_address_1_field #billing_address_1").parents(".floating-blk").addClass("err");
        if ($("#billing_address_1_field #billing_address_1").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_address_1_field #billing_address_1").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $('<p class="floating-input-error">Please enter your address</p>').appendTo($("#billing_address_1_field #billing_address_1").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_address_1_field #billing_address_1").parents(".floating-blk").removeClass("err");
    }
    if (billing_city == "" || billing_city == undefined) {
        $("#billing_city_field #billing_city").parents(".floating-blk").addClass("err");
        if ($("#billing_city_field #billing_city").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_city_field #billing_city").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $('<p class="floating-input-error">Please enter your city</p>').appendTo($("#billing_city_field #billing_city").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_city_field #billing_city").parents(".floating-blk").removeClass("err");
    }
    if (billing_state == "" || billing_state == undefined) {
        $("#billing_state_field #billing_state").parents(".floating-blk").addClass("err");
        if ($("#billing_state_field #billing_state").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_state_field #billing_state").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $('<p class="floating-input-error">Please enter your state</p>').appendTo($("#billing_state_field #billing_state").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_state_field #billing_state").parents(".floating-blk").removeClass("err");
    }
    if (billing_postcode == "" || billing_postcode == undefined) {
        $("#billing_postcode_field #billing_postcode").parents(".floating-blk").addClass("err");
        if ($("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $('<p class="floating-input-error">Please enter your pin code</p>').appendTo($("#billing_postcode_field #billing_postcode").parents(".floating-blk")).slideDown();
        i++;
    } else if (billing_postcode.length < 4 || billing_postcode.length > 6) {
        if ($("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $("#billing_postcode_field #billing_postcode").parents(".floating-blk").addClass("err");
        $('<p class="floating-input-error">Please enter a valid pin code</p>').appendTo($("#billing_postcode_field #billing_postcode").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_postcode_field #billing_postcode").parents(".floating-blk").removeClass("err");
    }
    $("html, body").animate({
        scrollTop: $(".checkout").offset().top - 20
    }, "slow");
    console.log(i);
    if (i == 0) {
        $.ajax({
            type: "POST",
            url: blogUri + "/wp-admin/admin-ajax.php",
            data: {
                action: 'update_user_chkoutaddress',
                user_id: user_id,
                billing_first_name: billing_first_name,
                billing_last_name: billing_last_name,
                billing_email: billing_email,
                billing_phone: billing_phone,
                billing_address_1: billing_address_1,
                billing_city: billing_city,
                billing_state: billing_state,
                billing_postcode: billing_postcode,
                billing_country: billing_country,
                address_group: address_group,
                address_key: address_key,
            },
            success: function (data) {
                console.log(data);
                $('html, body').animate({
                    scrollTop: 0
                }, 1000);
                $(document.body).trigger('update_checkout');
                location.reload();
                $('.add-adrs-close').trigger('click');
                // alert(i);
            }
        });
    }
});
// Checkout submit button
$("#place_order").on("click", function (e) {
    e.preventDefault();
//  alert(jQuery('.woocommerce-checkout #billing_first_name').val());
});

