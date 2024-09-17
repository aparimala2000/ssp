//  Billing address validation
$(".new_add_btn").on("click", function () { 
    var u_id = $('.user-info #updateuser_id').val();
    var uphone = $('.det-empty #phone').val();
    var ufname = $('.det-empty #first_name').val();
    var uemail = $('.det-empty #email').val();
    // alert(ufname);
    var regex = /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;
    var i = 0;
    if (uphone !== undefined) {
        if (uphone == "") {
            $(".det-empty #phone").parents(".floating-blk").addClass("err");
            if ($(".det-empty #phone").parents(".floating-blk").find(".floating-input-error").length != 0) {
                $(".det-empty #phone").parents(".floating-blk").find(".floating-input-error").remove();
            }
            $('<p class="floating-input-error">It’s important for us to know your phone number.</p>').appendTo($(".det-empty #phone").parents(".floating-blk")).slideDown();
            i++;
        } else if (uphone.length < 10 || uphone.length > 15) {
            $(".det-empty #phone").parents(".floating-blk").addClass("err");
            if ($(".det-empty #phone").parents(".floating-blk").find(".floating-input-error").length != 0) {
                $(".det-empty #phone").parents(".floating-blk").find(".floating-input-error").remove();
            }
            $('<p class="floating-input-error">We need a valid phone number.</p>').appendTo($(".det-empty #phone").parents(".floating-blk")).slideDown();
            i++;
        } else {
            $(".det-empty #phone").parents(".floating-blk").removeClass("err");
        }
    }
    if (ufname !== undefined) {
        if (ufname == "") {
            $(".det-empty #first_name").parents(".floating-blk").addClass("err");
            if ($(".det-empty #first_name").parents(".floating-blk").find(".floating-input-error").length != 0) {
                $(".det-empty #first_name").parents(".floating-blk").find(".floating-input-error").remove();
            }
            $('<p class="floating-input-error">Please provide your name.</p>').appendTo($(".det-empty #first_name").parents(".floating-blk")).slideDown();
            i++;
        } else {
            $(".det-empty #first_name").parents(".floating-blk").removeClass("err");
        }
    }
    if (uemail !== undefined) {
        if (uemail == "") {
            $(".det-empty #email").parents(".floating-blk").addClass("err");
            if ($(".det-empty #email").parents(".floating-blk").find(".floating-input-error").length != 0) {
                $(".det-empty #email").parents(".floating-blk").find(".floating-input-error").remove();
            }
            $('<p class="floating-input-error">Looks like you’ve missed entering your email.</p>').appendTo($(".det-empty #email").parents(".floating-blk")).slideDown();
            i++;
        } else if (uemail != '') {
            if (!regex.test(uemail)) {
                $('.det-empty #email').parents('.floating-blk').addClass('err');
                if ($(".det-empty #email").parents(".floating-blk").find(".floating-input-error").length != 0) {
                    $(".det-empty #email").parents(".floating-blk").find(".floating-input-error").remove();
                }
                $('<p class="floating-input-error">We need a valid email address.</p>').appendTo($(".det-empty #email").parents(".floating-blk")).slideDown();
                i++;
            } else {
                $('.det-empty #email').parents('.floating-blk').removeClass('err');
            }
        } else {
            $(".det-empty #email").parents(".floating-blk").removeClass("err");
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
                    $('.det-empty #email').parents('.floating-blk').addClass('err');
                    if ($(".det-empty #email").parents(".floating-blk").find(".floating-input-error").length != 0) {
                        $(".det-empty #email").parents(".floating-blk").find(".floating-input-error").remove();
                    }
                    $('<p class="floating-input-error">The email already associated with an another account.</p>').appendTo($(".det-empty #email").parents(".floating-blk")).slideDown();
                } else if (data == 2) {
                    $(".det-empty #phone").parents(".floating-blk").addClass("err");
                    if ($(".det-empty #phone").parents(".floating-blk").find(".floating-input-error").length != 0) {
                        $(".det-empty #phone").parents(".floating-blk").find(".floating-input-error").remove();
                    }
                    $('<p class="floating-input-error">The mobile number already associated with an another account.</p>').appendTo($(".det-empty #phone").parents(".floating-blk")).slideDown();
                    i++;
                } else {
                     $(".user-info .det-empty").removeClass("det-empty");
                    $(".user-info").hide();
                    $(document.body).trigger('update_checkout');
                    $('.new_add_btn').toggleClass("active"), $(".adrs-form").addClass("active"), $(".bg-layer").toggleClass("active");
                    var selectCnt = $(".woocommerce-checkout #country option:selected").val();
                    // alert(selectCnt);
                    jQuery('.floating-blk').removeClass('err');
                    jQuery('.woocommerce-checkout #billing_first_name').val("");
                    jQuery('.woocommerce-checkout #billing_last_name').val("");
                    jQuery('.woocommerce-checkout #billing_phone').val("");
                    jQuery('.woocommerce-checkout #billing_email').val("");
                    jQuery('.woocommerce-checkout #billing_address_1').val("");
                    jQuery('.woocommerce-checkout #billing_city').val("");
                    jQuery('.woocommerce-checkout #billing_state').val("");
                    jQuery('.woocommerce-checkout #billing_postcode').val("");
                    if (selectCnt != "" || selectCnt !=undefined) {
                    jQuery('#billingcountry_field #country').val(selectCnt);
                    jQuery('#billingcountry_field').addClass("active");
                    // alert(89);
                    }
                    else{
                      jQuery('#billingcountry_field #country').val("");
                      jQuery('#billingcountry_field #country').parents('floating-blk').removeClass("active");
                    }
                    jQuery('#billing_first_name_field').removeClass("active");
                    jQuery('#billing_last_name_field').removeClass("active");
                    jQuery('#billing_email_field').removeClass("active");
                    jQuery('#billing_phone_field').removeClass("active");
                    jQuery('#billing_postcode_field').removeClass("active");
                    jQuery('#billing_state_field').removeClass("active");
                    jQuery('#billing_address_1_field').removeClass("active");
                    jQuery('#billing_city_field').removeClass("active");
                }
            }
        });
    }
});
// New address
jQuery(document).on("click", "#continue_addr_checkout", function (e) {   
    // alert("new address"); 
    console.log("add address");
    var user_id = $("#billing_user_id").val();
    var billing_first_name = $("#billing_first_name_field #billing_first_name").val();
    var billing_last_name = $("#billing_last_name_field #billing_last_name").val();
    var billing_phone = $("#billing_phone_field #billing_phone").val();
    var billing_email = $("#billing_email_field #billing_email").val();
    var billing_address_1 = $("#billing_address_1_field #billing_address_1").val();
    var billing_state = $("#billing_state_field #billing_state").val();
    var billing_city = $("#billing_city_field #billing_city").val();
    var billing_postcode = $("#billing_postcode_field #billing_postcode").val();
    // var billing_country = $("#billingcountry_field #country").val(); 
    var billing_country = $("#billingcountry_field #country_add").find('option:selected').text();
    var address_group = ($("#address_group_field #address_group").val());
    var regex = /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i; 
    var i = 0;
     if (billing_first_name == "" || billing_first_name == undefined) {
         $("#billing_first_name_field #billing_first_name").parents(".floating-blk").addClass("err");
         if ($("#billing_first_name_field #billing_first_name").parents(".floating-blk").find(".floating-input-error").length != 0) {
             $("#billing_first_name_field #billing_first_name").parents(".floating-blk").find(".floating-input-error").remove();
         }
         $('<p class="floating-input-error">Please provide your first name.</p>').appendTo($("#billing_first_name_field #billing_first_name").parents(".floating-blk")).slideDown();
         i++;
     } else {
         $("#billing_first_name_field #billing_first_name").parents(".floating-blk").removeClass("err");
     }
     if (billing_last_name == "" || billing_last_name == undefined) {
         $("#billing_last_name_field #billing_last_name").parents(".floating-blk").addClass("err");
         if ($("#billing_last_name_field #billing_last_name").parents(".floating-blk").find(".floating-input-error").length != 0) {
             $("#billing_last_name_field #billing_last_name").parents(".floating-blk").find(".floating-input-error").remove();
         }
         $('<p class="floating-input-error">Please provide your last name.</p>').appendTo($("#billing_last_name_field #billing_last_name").parents(".floating-blk")).slideDown();
         i++;
     } else {
         $("#billing_last_name_field #billing_last_name").parents(".floating-blk").removeClass("err");
     }
     if (billing_phone == "" || billing_phone == undefined) {
         $("#billing_phone_field #billing_phone").parents(".floating-blk").addClass("err");
         if ($("#billing_phone_field #billing_phone").parents(".floating-blk").find(".floating-input-error").length != 0) {
             $("#billing_phone_field #billing_phone").parents(".floating-blk").find(".floating-input-error").remove();
         }
         $('<p class="floating-input-error">It’s important for us to know your phone number.</p>').appendTo($("#billing_phone_field #billing_phone").parents(".floating-blk")).slideDown();
         i++;
     } else if (billing_phone.length > 15 || billing_phone.length < 10) {
         if ($("#billing_phone_field #billing_phone").parents(".floating-blk").find(".floating-input-error").length != 0) {
             $("#billing_phone_field #billing_phone").parents(".floating-blk").find(".floating-input-error").remove();
         }
         $("#billing_phone_field #billing_phone").parents(".floating-blk").addClass("err");
         $('<p class="floating-input-error">We need a valid phone number.</p>').appendTo($("#billing_phone_field #billing_phone").parents(".floating-blk")).slideDown();
         i++;
     } else {
         $("#billing_phone_field #billing_phone").parents(".floating-blk").removeClass("err");
     }
     if (billing_email == "" || billing_email == undefined) {
         $("#billing_email_field #billing_email").parents(".floating-blk").addClass("err");
         if ($("#billing_email_field #billing_email").parents(".floating-blk").find(".floating-input-error").length != 0) {
             $("#billing_email_field #billing_email").parents(".floating-blk").find(".floating-input-error").remove();
         }
         $('<p class="floating-input-error">Looks like you’ve missed entering your email.</p>').appendTo($("#billing_email_field #billing_email").parents(".floating-blk")).slideDown();
         i++;
     } 
      else if (!regex.test(billing_email)) {
          if ($("#billing_email_field #billing_email").parents(".floating-blk").find(".floating-input-error").length != 0) {
              $("#billing_email_field #billing_email").parents(".floating-blk").find(".floating-input-error").remove();
          }
          $("#billing_email_field #billing_email").parents(".floating-blk").addClass("err");
          $('<p class="floating-input-error">We need a valid email address.</p>').appendTo($("#billing_email_field #billing_email").parents(".floating-blk")).slideDown();
          i++;
      }
     else {
         $("#billing_email_field #billing_email").parents(".floating-blk").removeClass("err");
     }
    if (billing_address_1 == "" || billing_address_1 == undefined) {
        $("#billing_address_1_field #billing_address_1").parents(".floating-blk").addClass("err");
        if ($("#billing_address_1_field #billing_address_1").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_address_1_field #billing_address_1").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $('<p class="floating-input-error">Oops! You’ve forgotten to enter your address.</p>').appendTo($("#billing_address_1_field #billing_address_1").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_address_1_field #billing_address_1").parents(".floating-blk").removeClass("err");
    }
    if (billing_city == "" || billing_city == undefined) {
        $("#billing_city_field #billing_city").parents(".floating-blk").addClass("err");
        if ($("#billing_city_field #billing_city").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_city_field #billing_city").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $('<p class="floating-input-error">Looks like you’ve forgotten to enter your city.</p>').appendTo($("#billing_city_field #billing_city").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_city_field #billing_city").parents(".floating-blk").removeClass("err");
    }
    if (billing_state == "" || billing_state == undefined) {
        $("#billing_state_field #billing_state").parents(".floating-blk").addClass("err");
        if ($("#billing_state_field #billing_state").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_state_field #billing_state").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $('<p class="floating-input-error">Your state is as important as your address.</p>').appendTo($("#billing_state_field #billing_state").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_state_field #billing_state").parents(".floating-blk").removeClass("err");
    }
      if (billing_country == "" || billing_country == undefined) {
          $("#billingcountry_field").addClass("err");
          if ($("#billingcountry_field").find(".floating-input-error").length != 0) {
              $("#billingcountry_field").find(".floating-input-error").remove();
          }
          $('<p class="floating-input-error">Please select your country</p>').appendTo($("#billingcountry_field")).slideDown();
          i++;
      } else {
          $("#billingcountry_field").removeClass("err");
      }
    if (billing_postcode == "" || billing_postcode == undefined) {
        $("#billing_postcode_field #billing_postcode").parents(".floating-blk").addClass("err");
        if ($("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $('<p class="floating-input-error">Seems like you’e forgotten to enter your postcode.</p>').appendTo($("#billing_postcode_field #billing_postcode").parents(".floating-blk")).slideDown();
        i++;
    } else if (billing_postcode.length > 12 ) {
        if ($("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $("#billing_postcode_field #billing_postcode").parents(".floating-blk").addClass("err");
        $('<p class="floating-input-error">We need a valid postcode.</p>').appendTo($("#billing_postcode_field #billing_postcode").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_postcode_field #billing_postcode").parents(".floating-blk").removeClass("err");
    }
    
  // Initialize hasErrors based on the initial state of error messages
var hasErrors = (
    $(".floating-input-error.post-error-code:visible").length > 0 ||
    $(".floating-input-error.post-error-code1:visible").length > 0 ||
    $(".floating-input-error.post-error-code-in:visible").length > 0 
);

// Function to update hasErrors based on error message visibility
function updateHasErrors() {
    hasErrors = (
        $(".floating-input-error.post-error-code:visible").length > 0 ||
        $(".floating-input-error.post-error-code1:visible").length > 0 ||
        $(".floating-input-error.post-error-code-in:visible").length > 0 
    );

    console.log("Updated hasErrors:", hasErrors);
}

$('#billing_postcode').on('keyup', function () {
    updateHasErrors();
});
 
    console.log(i);
    if (!hasErrors && i == 0) {
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
               
                $(document.body).trigger('update_checkout');
                location.reload();
                $('.add-adrs-close').trigger('click');
                // alert(i);
            }
        });
    }else{
       var scroll_height = $('.adrs-form').scrollTop();
       var err = $(this).parent().find('.floating-blk.err').position().top;
       if (scroll_height > err) {
           $('.adrs-form').animate({
               scrollTop: (err + scroll_height - 150)
           }, 500);
       }
    }
}); 

$(".address_choose").on("click", function () {
         var selected_id = $('input[name="radio-group"]:checked').attr('data-id');
         var selected_add_val = $('input[name="radio-group"]:checked').attr('addrs-val');
         var checkout_add_val = $('input[name="radio-group"]:checked').attr('checkout-addrs-val');
         var checkoutVal = checkout_add_val;
             const selectVal = selected_add_val;
            //  alert(selectVal);
             if (selectVal != undefined) {
                //  const [first_name, last_name, email_address, phone_number, address, city, state, postcode, country, address_key] = selectVal.split('~');
                  const [first_name, last_name, email_address, phone_number, address, city, state, postcode, country, address_key] = checkoutVal.split('~');
                 $("#place_order").addClass('pointer-disab');
                 if (country != "" || country != undefined) {
                     $.ajax({
                         type: "POST",
                         url: blogUri + "/wp-admin/admin-ajax.php",
                         data: {
                             action: 'checkout_country_shipping',
                             country_code: country,
                         },
                         success: function (data) {
                            console.log(data);
                            var succVal = data.split('|');
                            var successRet = succVal[0];
                            var shippingVal = succVal[1];
                            $('#checkout_total').html(successRet);
                            $('#checkout_total1').html(successRet);
                            $('#chkout-shipping-tot').html(shippingVal);
                            $('#chkout-shipping-tot-mob').html(shippingVal);
                            $(document.body).trigger('update_checkout');
                            $("#place_order").removeClass('pointer-disab');
    
                         }
                     });
                 }
                 jQuery('.woocommerce-checkout #billing_first_name').val(first_name);
                 jQuery('.woocommerce-checkout #billing_last_name').val(last_name);
                 jQuery('.woocommerce-checkout #billing_email').val(email_address);
                 jQuery('.woocommerce-checkout #billing_address_1').val(address);
                 jQuery('.woocommerce-checkout #billing_city').val(city);
                 jQuery('.woocommerce-checkout #billing_state').val(state);
                 jQuery('.woocommerce-checkout #billing_postcode').val(postcode);
                 jQuery('.woocommerce-checkout #billing_country').val(country);
                 jQuery('.woocommerce-checkout #billing_phone').val(phone_number);
                //  jQuery('.woocommerce-checkout #addresskey').val(address_key);
                jQuery('.woocommerce-checkout #country').val(country);
              
                jQuery('.woocommerce-checkout #billing_country').val(country);
                jQuery('.woocommerce-checkout #address_key').val(address_key);
    
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

// Switch address for checkout
// $(".address_choose").on("click", function () {
//      var selected_id = $(this).attr('data-id');
//      var selected_add_val = $(this).attr('addrs-val');
//      var checkout_add_val = $(this).attr('checkout-addrs-val');
//      var checkoutVal = checkout_add_val;
//          const selectVal = selected_add_val;
//         //  alert(selectVal);
//          if (selectVal != undefined) {
//             //  const [first_name, last_name, email_address, phone_number, address, city, state, postcode, country, address_key] = selectVal.split('~');
//               const [first_name, last_name, email_address, phone_number, address, city, state, postcode, country, address_key] = checkoutVal.split('~');
//              $("#place_order").addClass('pointer-disab');
//              if (country != "" || country != undefined) {
//                  $.ajax({
//                      type: "POST",
//                      url: blogUri + "/wp-admin/admin-ajax.php",
//                      data: {
//                          action: 'checkout_country_shipping',
//                          country_code: country,
//                      },
//                      success: function (data) {
//                         console.log(data);
//                         var succVal = data.split('|');
//                         var successRet = succVal[0];
//                         var shippingVal = succVal[1];
//                         $('#checkout_total').html(successRet);
//                         $('#checkout_total1').html(successRet);
//                         $('#chkout-shipping-tot').html(shippingVal);
//                         $('#chkout-shipping-tot-mob').html(shippingVal);
//                         $(document.body).trigger('update_checkout');
//                         $("#place_order").removeClass('pointer-disab');

//                      }
//                  });
//              }
//              jQuery('.woocommerce-checkout #billing_first_name').val(first_name);
//              jQuery('.woocommerce-checkout #billing_last_name').val(last_name);
//              jQuery('.woocommerce-checkout #billing_email').val(email_address);
//              jQuery('.woocommerce-checkout #billing_address_1').val(address);
//              jQuery('.woocommerce-checkout #billing_city').val(city);
//              jQuery('.woocommerce-checkout #billing_state').val(state);
//              jQuery('.woocommerce-checkout #billing_postcode').val(postcode);
//              jQuery('.woocommerce-checkout #billing_country').val(country);
//              jQuery('.woocommerce-checkout #billing_phone').val(phone_number);
//             //  jQuery('.woocommerce-checkout #addresskey').val(address_key);
//             jQuery('.woocommerce-checkout #country').val(country);
          
//             jQuery('.woocommerce-checkout #billing_country').val(country);
//             jQuery('.woocommerce-checkout #address_key').val(address_key);

//          }
//       $(document).find(".show_div").each(function () {
//           var show_id = $(this).attr('data-val');
//         if (selected_id == show_id) {
//             $(this).show();
//         } else {
//             $(this).hide();
//         }
//     });
//     var t = $(this),
//         e = t.parents(".accord-content"),
//         i = t.parents(".accord-blk").next(),
//         s = t.parents(".accord-blk");
//     s.addClass("finish"), s.removeClass("active"), s.find(".before").hide(), s.find(".after").show(), e.hide(), i.addClass("active"), i.find(".accord-content").show();
  
// });
// Edit existing address
$('.checkout_edit_address').click(function () {
    // alert("edit address"); 
    var $this = $(this).attr('addrs-val');
    $('.new_add_btn').toggleClass("active"), $(".adrs-form").addClass("active"), $(".bg-layer").toggleClass("active");
    $('#continue_addr_checkout').attr('id', 'continue_update_checkout');
    // alert($this);
    const selectVal = $this;
    if (selectVal != undefined) {
        const [first_name, last_name, email_address, phone_number, address, city, state, postcode, country, address_key, address_group] = selectVal.split('~');
        jQuery('.floating-blk').removeClass('err');
        jQuery('#billing_first_name_field #billing_first_name').parents('.floating-blk').addClass('active');
        jQuery('#billing_last_name_field #billing_last_name').parents('.floating-blk').addClass('active');
        jQuery('#billing_email_field #billing_email').parents('.floating-blk').addClass('active');
        jQuery('#billing_phone_field #billing_phone').parents('.floating-blk').addClass('active');
        jQuery('#billing_state_field #billing_state').parents('.floating-blk').addClass('active');
        jQuery('#billing_postcode_field #billing_postcode').parents('.floating-blk').addClass('active');
        jQuery('#billingcountry_field #country').parents('.floating-blk').addClass('active');
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
        jQuery('#billingcountry_field #country').val(country);
        jQuery('#address_key_field #address_key').val(address_key);
        $("#address_group_field #address_group").val(address_group);
        var checked_value = address_group;
          if (checked_value === "Home") {
             $('.other-form-field').hide();
             $('.other-form-field').removeClass('active');
             $('#other_label').val(address_group);
              $('input:radio[name="address-group-name"][value=' + checked_value + ']').prop('checked', true);
          } else if (checked_value === "Office") {
              $('.other-form-field').hide();
              $('.other-form-field').removeClass('active');
              $('#other_label').val(address_group);
              $('input:radio[name="address-group-name"][value=' + checked_value + ']').prop('checked', true);
          }
           else if (checked_value === "Other") {
               $('.other-form-field').hide();
               $('.other-form-field').removeClass('active');
               $('#other_label').val(address_group);
               $('input:radio[name="address-group-name"][value=' + checked_value + ']').prop('checked', true);
           }
          else if (checked_value !== "Other" ) {
              $('.other-form-field').show();
              $('.other-form-field').addClass('active');
              $('#other_label').val(address_group);
              $('input:radio[name="address-group-name"][value=Other]').prop('checked', true);
          }
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
    var billing_country = $("#billingcountry_field #country").val();
    // var billing_country = $("#billingcountry_field #country_add").find('option:selected').text();
    var address_group = ($("#address_group_field #address_group").val());
    var address_key = $('#address_key_field #address_key').val();
    var regex = /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;
    var i = 0;
     if (billing_first_name == "" || billing_first_name == undefined) {
         $("#billing_first_name_field #billing_first_name").parents(".floating-blk").addClass("err");
         if ($("#billing_first_name_field #billing_first_name").parents(".floating-blk").find(".floating-input-error").length != 0) {
             $("#billing_first_name_field #billing_first_name").parents(".floating-blk").find(".floating-input-error").remove();
         }
         $('<p class="floating-input-error">Please provide your first name.</p>').appendTo($("#billing_first_name_field #billing_first_name").parents(".floating-blk")).slideDown();
         i++;
     } else {
         $("#billing_first_name_field #billing_first_name").parents(".floating-blk").removeClass("err");
     }
     if (billing_last_name == "" || billing_last_name == undefined) {
         $("#billing_last_name_field #billing_last_name").parents(".floating-blk").addClass("err");
         if ($("#billing_last_name_field #billing_last_name").parents(".floating-blk").find(".floating-input-error").length != 0) {
             $("#billing_last_name_field #billing_last_name").parents(".floating-blk").find(".floating-input-error").remove();
         }
         $('<p class="floating-input-error">Please provide your last name.</p>').appendTo($("#billing_last_name_field #billing_last_name").parents(".floating-blk")).slideDown();
         i++;
     } else {
         $("#billing_last_name_field #billing_last_name").parents(".floating-blk").removeClass("err");
     }
     if (billing_phone == "" || billing_phone == undefined) {
         $("#billing_phone_field #billing_phone").parents(".floating-blk").addClass("err");
         if ($("#billing_phone_field #billing_phone").parents(".floating-blk").find(".floating-input-error").length != 0) {
             $("#billing_phone_field #billing_phone").parents(".floating-blk").find(".floating-input-error").remove();
         }
         $('<p class="floating-input-error">It’s important for us to know your phone number.</p>').appendTo($("#billing_phone_field #billing_phone").parents(".floating-blk")).slideDown();
         i++;
     } else if (billing_phone.length > 15 || billing_phone.length < 10) {
         if ($("#billing_phone_field #billing_phone").parents(".floating-blk").find(".floating-input-error").length != 0) {
             $("#billing_phone_field #billing_phone").parents(".floating-blk").find(".floating-input-error").remove();
         }
         $("#billing_phone_field #billing_phone").parents(".floating-blk").addClass("err");
         $('<p class="floating-input-error">We need a valid phone number.</p>').appendTo($("#billing_phone_field #billing_phone").parents(".floating-blk")).slideDown();
         i++;
     } else {
         $("#billing_phone_field #billing_phone").parents(".floating-blk").removeClass("err");
     }
     if (billing_email == "" || billing_email == undefined) {
         $("#billing_email_field #billing_email").parents(".floating-blk").addClass("err");
         if ($("#billing_email_field #billing_email").parents(".floating-blk").find(".floating-input-error").length != 0) {
             $("#billing_email_field #billing_email").parents(".floating-blk").find(".floating-input-error").remove();
         }
         $('<p class="floating-input-error">Looks like you’ve missed entering your email.</p>').appendTo($("#billing_email_field #billing_email").parents(".floating-blk")).slideDown();
         i++;
     } else if (!regex.test(billing_email)) {
         if ($("#billing_email_field #billing_email").parents(".floating-blk").find(".floating-input-error").length != 0) {
             $("#billing_email_field #billing_email").parents(".floating-blk").find(".floating-input-error").remove();
         }
         $("#billing_email_field #billing_email").parents(".floating-blk").addClass("err");
         $('<p class="floating-input-error">We need a valid email address.</p>').appendTo($("#billing_email_field #billing_email").parents(".floating-blk")).slideDown();
         i++;
     } else {
         $("#billing_email_field #billing_email").parents(".floating-blk").removeClass("err");
     }
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
    } else if (billing_postcode.length > 12) {
        if ($("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").length != 0) {
            $("#billing_postcode_field #billing_postcode").parents(".floating-blk").find(".floating-input-error").remove();
        }
        $("#billing_postcode_field #billing_postcode").parents(".floating-blk").addClass("err");
        $('<p class="floating-input-error">Please enter a valid pin code</p>').appendTo($("#billing_postcode_field #billing_postcode").parents(".floating-blk")).slideDown();
        i++;
    } else {
        $("#billing_postcode_field #billing_postcode").parents(".floating-blk").removeClass("err");
    }
     if (billing_country == "" || billing_country == undefined) {
         $("#billingcountry_field").addClass("err");
         if ($("#billingcountry_field").find(".floating-input-error").length != 0) {
             $("#billingcountry_field").find(".floating-input-error").remove();
         }
         $('<p class="floating-input-error">Please select your country</p>').appendTo($("#billingcountry_field")).slideDown();
         i++;
     } else {
         $("#billingcountry_field").removeClass("err");
     }

     // Initialize hasErrors based on the initial state of error messages
var hasErrors = (
    $(".floating-input-error.post-error-code:visible").length > 0 ||
    $(".floating-input-error.post-error-code1:visible").length > 0 ||
    $(".floating-input-error.post-error-code-in:visible").length > 0 
);

// Function to update hasErrors based on error message visibility
function updateHasErrors() {
    hasErrors = (
        $(".floating-input-error.post-error-code:visible").length > 0 ||
        $(".floating-input-error.post-error-code1:visible").length > 0 ||
        $(".floating-input-error.post-error-code-in:visible").length > 0 
    );

    console.log("Updated hasErrors:", hasErrors);
}

$('#billing_postcode').on('keyup', function () {
    updateHasErrors();
});
   console.log(billing_country);
    console.log(i);
    if (!hasErrors && i == 0) {
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
                $(document.body).trigger('update_checkout');
                location.reload();
                $('.add-adrs-close').trigger('click');
            }
        });
    }else{
        console.log("I'm scrolling");
       var scroll_height = $('.adrs-form').scrollTop();
       var err = $(this).parent().find('.floating-blk.err').position().top;
       if (scroll_height > err) {
           $('.adrs-form').animate({
               scrollTop: (err + scroll_height - 150)
           }, 500);
       }
    }
});
// Update input value on change radio button
$('#other_label').blur(function (e) {
    var labelvalue = $(this).val();
    if (labelvalue!=""){
        $('#address_group_field #address_group').val(labelvalue);
    }else{
        $('#address_group_field #address_group').val('Other');
    }
});
$('input[name=address-group-name]').change(function (e) {
var chkboxValue = this.value;
var labelValue = $('#other_label').val();
if (chkboxValue!=""){
    $('#address_group_field #address_group').val(chkboxValue);
} else if (chkboxValue == "Other" && labelValue!="") {
    $('#address_group_field #address_group').val(labelValue);
}
});
// Checkout submit button
$("#place_order").on("click", function (e) {
    e.preventDefault();
//  alert(jQuery('.woocommerce-checkout #billing_first_name').val());
}); 


// Guest OTP Verification 
$(".chkout_otp_verify").on('click', function () {
    var otpVal = $('#chkout_user_otp').val();
    var userid = $('#user_customer_id').val();
    console.log(otpVal);
    console.log(userid);
    if (otpVal == "" || otpVal == undefined) {
        $('#chkout_user_otp').parents('.floating-blk').addClass('err');
    } else {
        $('#chkout_user_otp').parents('.floating-blk').removeClass('err');
        $.ajax({
            cache: false,
            async: true,
            type: "POST",
            url: templateUri + '/ajax/ajax-otp.php',
            data: {
                otpval: otpVal,
                userid: userid,
                action: "otp_checkout_gen_login"
            },
            success: function (data) {
                if (data == 1) {
                    $(document.body).trigger('update_checkout');
                    $('#chkout_user_otp').parents('.floating-blk').removeClass('err');
                    location.reload();
                } else if (data == 2) {
                    $('#chkout_user_otp').parents('.floating-blk').addClass('err');
                    $('.chkout_otp').html('Enter a valid OTP here.');
                } else if (data == 3) {
                    $('#chkout_user_otp').parents('.floating-blk').addClass('err');
                    $('.chkout_otp').html('Enter a valid OTP here.');
                }
            }
        });
        return false;
    }
});
// User Resend OTP - Checkout
$('.resendOtpuserLogin').on('click', function () {
    $('#chkout_user_otp').parents('.floating-blk').removeClass('err');
    $("#chkout_user_otp").trigger("focus");
    var lginUname = $('#user_customer_id').val();
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
// Maxlength attribute set for number fields 
$(document).ready(function () {
       $('#billing_phone').prop('maxlength', '15');
       $('#billing_postcode').prop('maxlength', '12');
});
// Name field only allow letters
$('#first_name').bind('keypress paste', function (evt) {
    var keyCode = (evt.which) ? evt.which : evt.keyCode
    if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
        return false;
    return true;
});
// // Prevent Windows from enter key
$(document).keypress(
    function (event) {
        if (event.which == '13') {
            event.preventDefault();
        }
  });

