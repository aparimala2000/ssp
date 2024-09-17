// Newsletter validation

$(document).ready(function () {
    $(".newsletter").on('click', function (e) {
        // alert("Newsletter");
        var email = $("#email_footer").val();
        console.log(email);
        // alert(email);
        var regex = /\S+@\S+\.\S+/;
        var x = 0;
        if (email == "" || email == undefined) {
            $(".err-msg").html('Enter your email address here.').addClass('active').show();
            x++;
        } else {
            if (!regex.test(email)) {
                $(".err-msg").html('We need a valid email address.').addClass('active').show();

                x++;
            } else {
                $(".err-msg").removeClass('active').hide();
            }
        }
        if (x == 0) {
            $.ajax({
                type: "POST",
                url: blogUri + "/wp-admin/admin-ajax.php",
                data: {
                    action: "newsletter_ajax",
                    email: email,

                },
                success: function (data) {
                    setTimeout(function () {
                        $("#email").val("");
                        $(".s-msg").addClass('active').show(0).delay(3000).hide(0);

                    }, 1500);
                }
            });

        }



    });

});
$("#email_footer").on("keyup", function () {
    if ($(this).val().length > 0) {
        console.log("subs");
        $(".err-msg").removeClass('active').hide();
    }
});
$('#pro_weight').on('change', function () {
    var weightId = $(this).find('option:selected').val();
    var price = $(this).find('option:selected').attr('price-id');
    var var_id_plus = $(this).find('option:selected').attr('var-id');
    // console.log(price);
    $(".price").text('₹ ' + price.toLocaleString());
    $(".mini_cart_product_remove").each(function (index) {
        var var_id = $(this).attr("data-variation-id");
        // var var_id_plus = $('#pro_weight option:selected').attr('var-id');
        var product_qty = +$(".domesticbox").val();
        var cart_var_id_qty = +$('#dummyQty' + var_id).val();
        var addTocartBtn = $('.add_to_cart_btn');
        var total_qty = cart_var_id_qty + product_qty;

        if (var_id == var_id_plus) {
            if (total_qty > 20) {
                addTocartBtn.css({
                    'pointer-events': 'none',
                    'opacity': '0.5'
                });
            } else {
                addTocartBtn.css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });
            }
        } else {
            addTocartBtn.css({
                'pointer-events': 'auto',
                'opacity': '1'
            });
        }
    });
});


// Increment decrement product detail page


$(document).ready(function () {
    $(".mini_cart_product_remove").each(function (index) {
        var var_id = $(this).attr("data-variation-id");
        var var_id_plus = $('#pro_weight option:selected').attr('var-id');
        var product_qty = +$(".domesticbox").val();
        var cart_var_id_qty = +$('#dummyQty' + var_id).val();
        var addTocartBtn = $('.add_to_cart_btn');
        console.log(cart_var_id_qty);
        console.log(product_qty);
        var total_qty = cart_var_id_qty + product_qty;
        console.log(total_qty);
        if (var_id == var_id_plus) {
            if (total_qty > 20) {
                addTocartBtn.css({
                    'pointer-events': 'none',
                    'opacity': '0.5'
                });
            } else {
                addTocartBtn.css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });
            }
        }
    });
    var defaultPrice = parseInt($(".price").text().replace("₹ ", "").replace(",", ""));
    var quantity = 1;
    var totalPrice = defaultPrice;

    $(".minus").click(function () {
        if (quantity > 1) {
            quantity--;
            $(".plus").addClass("active");
            totalPrice = defaultPrice * quantity;
            $(".domesticbox").val(quantity);
            $(".price").html("₹ " + totalPrice.toLocaleString());
        }
        if (quantity == 1) {
            $(".minus").removeClass("active");
        }
        $(".mini_cart_product_remove").each(function (index) {
            var var_id = $(this).attr("data-variation-id");
            var var_id_plus = $('#pro_weight option:selected').attr('var-id');
            var product_qty = +$(".domesticbox").val();
            var cart_var_id_qty = +$('#dummyQty' + var_id).val();
            var addTocartBtn = $('.add_to_cart_btn');
            var total_qty = cart_var_id_qty + product_qty;
            if (var_id == var_id_plus) {
                if (total_qty > 20) {
                    addTocartBtn.css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    });
                } else {
                    addTocartBtn.css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    });
                }
            }
        });
    });

    $(".plus").click(function () {
        if (quantity < 20) {
            quantity++;
            $(".minus").addClass("active");
            totalPrice = defaultPrice * quantity;
            $(".domesticbox").val(quantity);
            $(".price").html("₹ " + totalPrice.toLocaleString());
        }
        if (quantity == 20) {
            $(".plus").removeClass("active");
        }
        $(".mini_cart_product_remove").each(function (index) {
            var var_id = $(this).attr("data-variation-id");
            var var_id_plus = $('#pro_weight option:selected').attr('var-id');
            var product_qty = +$(".domesticbox").val();
            var cart_var_id_qty = +$('#dummyQty' + var_id).val();
            var addTocartBtn = $('.add_to_cart_btn');
            console.log(var_id);
            console.log(var_id_plus);
            var total_qty = cart_var_id_qty + product_qty;
            console.log(total_qty);
            if (var_id == var_id_plus) {
                if (total_qty > 20) {
                    addTocartBtn.css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    });
                } else {
                    addTocartBtn.css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    });
                }
            }
        });
    });

    $("#pro_weight").on("change", function () {
        var price = parseInt($(this).find("option:selected").attr("price-id"));
        var var_id_plus = $(this).find('option:selected').attr('var-id');
        defaultPrice = price;
        totalPrice = defaultPrice * quantity;
        $(".price").html("₹ " + totalPrice.toLocaleString());
        $(".mini_cart_product_remove").each(function (index) {
            var var_id = $(this).attr("data-variation-id");
            // var var_id_plus = $('#pro_weight option:selected').attr('var-id');
            var product_qty = +$(".domesticbox").val();
            var cart_var_id_qty = +$('#dummyQty' + var_id).val();
            var addTocartBtn = $('.add_to_cart_btn');
            var total_qty = cart_var_id_qty + product_qty;
            if (var_id == var_id_plus) {
                if (total_qty > 20) {
                    addTocartBtn.css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    });
                } else {
                    addTocartBtn.css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    });
                }
            } else {
                addTocartBtn.css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });
            }
        });
    });
});

//Global Increment decrement product detail page

$(document).ready(function () {
    // Get the initial price and quantity for the selected option
    var price = parseInt($('option:selected', '#glo_weight').attr('price-id'));
    var min_qty = parseInt($('option:selected', '#glo_weight').attr('data-min-qty'));
    var max_qty = parseInt($('option:selected', '#glo_weight').attr('data-max-qty'));
    // console.log(price);
    $(".mini_cart_product_remove").each(function (index) {
        var var_id = $(this).attr("data-variation-id");
        var var_id_plus = $('#glo_weight option:selected').attr('var-id');
        var product_qty = +$(".boxglobal").val();
        var cart_var_id_qty = +$('#global_qty' + var_id).val();
        var addTocartBtn = $('.add_to_cart_btn');
        var total_qty = cart_var_id_qty + product_qty;
        if (var_id == var_id_plus) {
            if (total_qty > max_qty) {
                addTocartBtn.css({
                    'pointer-events': 'none',
                    'opacity': '0.5'
                });
            } else {
                addTocartBtn.css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });
            }
        }
    });
    var total_price = price * min_qty;
    // Set the initial global price
    $('.globalprice').text('₹ ' + total_price.toLocaleString());

    // Handle the change event of the select dropdown
    $('#glo_weight').on('change', function () {
        // Get the updated price and quantity for the selected option
        price = parseInt($('option:selected', this).attr('price-id'));
        var var_id_plus = $(this).find('option:selected').attr('var-id');
        min_qty = parseInt($('option:selected', this).attr('data-min-qty'));
        max_qty = parseInt($('option:selected', this).attr('data-max-qty'));
        var foundMatchingVariation = false;
        var addTocartBtn = $('.add_to_cart_btn');
        // Calculate the updated total price based on the new quantity
        total_price = price * min_qty;
        $('.globalprice').text('₹ ' + total_price.toLocaleString());
        // Reset the quantity to the minimum quantity for the selected option
        // console.log(min_qty +  "test min qty");
        $('.packglobal').text('( Pack of ' + min_qty + ' )');
        $('.boxglobal').val(min_qty);
        $(".mini_cart_product_remove").each(function (index) {
            var var_id = $(this).attr("data-variation-id");
            // var var_id_plus = $('#glo_weight option:selected').attr('var-id');
            var product_qty = +$(".boxglobal").val();
            var cart_var_id_qty = +$('#global_qty' + var_id).val();

            var total_qty = cart_var_id_qty + product_qty;
            if (var_id == var_id_plus) {
                if (total_qty > max_qty) {
                    addTocartBtn.css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    });
                } else {
                    addTocartBtn.css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    });
                }
                foundMatchingVariation = true;
            }
        });
        if (!foundMatchingVariation) {
            addTocartBtn.css({
                'pointer-events': 'auto',
                'opacity': '1'
            });
        }
    });
    if (min_qty == max_qty) {
        $(".incre").removeClass("active");
    }
    // Handle the click event of the increment button
    $('.incre').on('click', function () {
        var current_qty = parseInt($('.boxglobal').val());
        if (current_qty < max_qty) {
            current_qty++;
            $(".decre").addClass("active");
            console.log(price);
            var glo_total_price = price * current_qty;
            $('.boxglobal').val(current_qty);
            $(".minqty").text('( Pack of ' + current_qty + " )");
            $('.globalprice').text('₹ ' + glo_total_price.toLocaleString());
        }
        if (current_qty == max_qty) {
            $(".incre").removeClass("active");
        }
        $(".mini_cart_product_remove").each(function (index) {
            var var_id = $(this).attr("data-variation-id");
            var var_id_plus = $('#glo_weight option:selected').attr('var-id');
            var product_qty = +$(".boxglobal").val();
            var cart_var_id_qty = +$('#global_qty' + var_id).val();
            var addTocartBtn = $('.add_to_cart_btn');
            // console.log(cart_var_id_qty);
            // console.log(product_qty);
            var total_qty = cart_var_id_qty + product_qty;
            // console.log(total_qty);
            if (var_id == var_id_plus) {
                if (total_qty > max_qty) {
                    addTocartBtn.css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    });
                } else {
                    addTocartBtn.css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    });
                }
            }
        });
    });

    // Handle the click event of the decrement button
    $('.decre').on('click', function () {
        var current_qty = parseInt($('.boxglobal').val());
        if (current_qty > min_qty) {
            current_qty--;
            $(".incre").addClass("active");
            var glo_total_price = price * current_qty;
            $(".minqty").text('( Pack of ' + current_qty + " )");
            $('.boxglobal').val(current_qty);
            $('.globalprice').text('₹ ' + glo_total_price.toLocaleString());
        }
        if (current_qty == min_qty) {
            $(".decre").removeClass("active");
        }
        $(".mini_cart_product_remove").each(function (index) {
            var var_id = $(this).attr("data-variation-id");
            var var_id_plus = $('#glo_weight option:selected').attr('var-id');
            var product_qty = +$(".boxglobal").val();
            var cart_var_id_qty = +$('#global_qty' + var_id).val();
            var addTocartBtn = $('.add_to_cart_btn');
            // console.log(cart_var_id_qty);
            // console.log(product_qty);
            var total_qty = cart_var_id_qty + product_qty;
            // console.log(total_qty);
            if (var_id == var_id_plus) {
                if (total_qty > max_qty) {
                    addTocartBtn.css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    });
                } else {
                    addTocartBtn.css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    });
                }
            }
        });
    });
});


//filter function for listing page
$(document).ready(function () {
    $('input[name="subcategory"]').change(function () {
        var subcategories = $('input[name="subcategory"]:checked').map(function () {
            return $(this).val();
        }).get();
        $.get(window.location.href, {
            'subcategory': subcategories
        }, function (data) {
            $('.filter').html($(data).find('.filter').html());
        });
    });
});

// Contact Form - JS
$(document).ready(function () {
    $("#contact_btn").on('click', function (e) {
        // alert("");
        var fname = $("#fname").val();
        var lname = $("#lname").val();
        var phonenumber = $("#phonenumber").val();
        var email = $("#email").val();
        var message = $("#message").val();
        var nonce = $("#contact_form_nonce").val();
        var regex = /\S+@\S+\.\S+/;
        var x = 0;
        if (fname == "" || fname == undefined) {
            $("#err_name").show();
            // alert($("#fname"));
            $("#fname").parent().closest('div').addClass("err");
            x++;
        } else {
            $("#err_name").hide();
            $("#fname").parent().closest('div').removeClass("err");
        }
        if (lname == "" || lname == undefined) {
            $("#err_lname").show();
            // alert($("#fname"));
            $("#lname").parent().closest('div').addClass("err");
            x++;
        } else {
            $("#err_lname").hide();
            $("#lname").parent().closest('div').removeClass("err");
        }
        if (phonenumber == "" || phonenumber == undefined) {
            $("#err_phone").show();
            $("#phonenumber").parent().closest('div').addClass("err");
            x++;
        } else {
            if (phonenumber.length < 10) {
                $("#err_phone").html("We need a valid phone number.").show();
                $("#phonenumber").parent().closest('div').addClass("err");
                x++;
            } else {
                $("#err_phone").hide();
                $("#phonenumber").parent().closest('div').removeClass("err");
            }
        }
 
        if (email == "" || email == undefined) {
            $("#err_email").show();
            $("#email").parent().closest('div').addClass("err");
            x++;
        } else {
            if (!regex.test(email)) {
                $('#err_email').html('We need a valid email address.').show();
                $("#email").parent().closest('div').addClass("err");
                x++;
            } else {
                $("#err_email").hide();
                $("#email").parent().closest('div').removeClass("err");
            }
        }

        if (message == "" || message == undefined) {
            $("#err_msg").show();
            $("#message").parent().closest('div').addClass("err");
            x++;
        } else {
            $("#err_msg").hide();
            $("#message").parent().closest('div').removeClass("err");
        }


        if (x == 0) {
            grecaptcha.ready(function () {
                grecaptcha.execute("6Ld4usImAAAAAMyMuOmge7lXZ_WNcqaLQUM73tpP", {

                }).then(function (s) {
                    $.ajax({
                        type: "POST",
                        url: blogUri + "/wp-admin/admin-ajax.php",
                        data: {
                            action: "contact_ajax",
                            contact_form_nonce: nonce,
                            email: email,
                            fname: fname,
                            lname: lname,
                            phonenumber: phonenumber,
                            message: message,
                            gRecaptchaResponse: s,
                            contact_us_form: "contact_us"
                        },
                        dataType: "json",
                        encode: !0,
                        success: function (data) {
                            if (data == 1) {
                                $("#contact_form")[0].reset();
                                window.location.href = blogUri + "/contact-us/thank-you";
                                console.log("Data submitted");
                            } else {
                                $('body,html').animate({
                                    scrollTop: 0
                                }, 1000);
                                x++;

                            }
                        }
                    })
                })
            })

        }
        else {
            $('html, body').animate({
                scrollTop: $("#contact_form").offset().top
            }, 1000);
            x++;
            console.log("ohh no failed!");
        }
    });

});

// On blur validation
function validateField(inputElement, errorMessageElement, validationFunction, errorMessage, invalidErrorMessage) {
    inputElement.blur(function () {
        const inputValue = $(this).val().trim();

        if (inputValue === "") {
            errorMessageElement.html(errorMessage).show();
            inputField.parent().closest('div').addClass("err");
        } else {
            if (!validationFunction(inputValue)) {
                errorMessageElement.html(invalidErrorMessage).show();
                inputField.parent().closest('div').addClass("err");
            } else {
                errorMessageElement.hide();
                inputField.parent().closest('div').removeClass("err");
            }
        }
    });
}

// Validation functions
function isValidPhoneNumber(phoneNumber) {
    return phoneNumber.length >= 10;
}

function isValidEmail(email) {
    var regex = /\S+@\S+\.\S+/;
    return regex.test(email);
}

// Validate each field on blur
validateField($("#fname"), $("#err_name"), function (value) {
    return value !== "";
}, "Please tell us your first name.");
validateField($("#lname"), $("#err_lname"), function (value) {
    return value !== "";
}, "Please tell us your last name.");
validateField(
    $("#phonenumber"),
    $("#err_phone"),
    function (value) {
        return value !== "" && isValidPhoneNumber(value);
    },
    "Please let us have your phone number.",
    "We need a valid phone number."
);
// Attach blur event for email field  
validateField(
    $("#email"),
    $("#err_email"),
    function (value) {
        return value !== "" && isValidEmail(value);
    },
    "Please provide your email.",
    "We need a valid email address."
);

// Attach blur event for message field
validateField($("#message"), $("#err_msg"), function (value) {
    return value !== "";
}, "Here’s where you enter your message to us.");

// Hide error messages on input keyup
$("#fname, #lname,#phonenumber,#email,#message").on("keyup", function () {
    if ($(this).val().length > 0) {
        $(this).parent().closest('div').removeClass("err");
        $(this).siblings('.floating-input-error').hide();
    }
});
//only input type number & only input type text
$('#phonenumber').bind('keypress paste', function (evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode != 46 && charCode > 31 &&
        (charCode < 48 || charCode > 57))
        return false;
    return true;

});
$('#fname').bind('keypress paste', function (evt) {
    var keyCode = (evt.which) ? evt.which : evt.keyCode
    if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
        return false;
    return true;

});
$('#lname').bind('keypress paste', function (evt) {
    var keyCode = (evt.which) ? evt.which : evt.keyCode
    if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
        return false;
    return true;

});

// Update Account

$("#update_account").on("click", function (event) {
    event.preventDefault(); // Prevent form submission

    var account_first_name = $("#account_first_name").val();
    var account_last_name = $("#account_last_name").val();
    var account_email = $("#account_email").val();
    var telephone = $("#telephone").val();
    var regex = /\S+@\S+\.\S+/;
    var x = 0;

    if (account_first_name == "" || account_first_name == undefined) {
        $("#err_account_first_name").show();
        $("#account_first_name").parent().closest('div').addClass("err");
        x++;
    } else {
        $("#err_account_first_name").hide();
        $("#account_first_name").parent().closest('div').removeClass("err");
    }

    if (account_last_name == "" || account_last_name == undefined) {
        $("#err_account_last_name").show();
        $("#account_last_name").parent().closest('div').addClass("err");
        x++;
    } else {
        $("#err_account_last_name").hide();
        $("#account_last_name").parent().closest('div').removeClass("err");
    }

    if (telephone == "" || telephone == undefined) {
        $("#err_telephone").show();
        $("#telephone").parent().closest('div').addClass("err");
        x++;
    } else {
        if (telephone.length < 10) {
            $("#err_telephone").html("Please enter a valid phone number.").show();
            $("#telephone").parent().closest('div').addClass("err");
            x++;
        } else {
            $.ajax({
                url: templateUri + "/inc/ajax/phone_validate.php",
                method: "POST",
                data: {
                    telephone: telephone
                },
                success: function (response) {
                    if (response.trim() === "exists") {
                        // Account with the provided email or phone number already exists
                        $("#err_telephone").html("An account is already registered with your phone number. Please enter another phone number.").show();
                        $("#telephone").parent().closest('div').addClass("err");
                        x++;
                    } else if (response.trim() === "not-exists") {
                        // No account exists with the provided email or phone number
                        $("#err_telephone").hide();
                        $("#telephone").parent().closest('div').removeClass("err");
                    } else {
                        console.log(response); // Handle other responses if needed
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    }

    if (account_email == "" || account_email == undefined) {
        $("#err_account_email").show();
        $("#account_email").parent().closest('div').addClass("err");
        x++;
    } else {
        if (!regex.test(account_email)) {
            $('#err_account_email').html('Please enter a valid email address.').show();
            $("#account_email").parent().closest('div').addClass("err");
            x++;
        } else {
            // Perform an AJAX request to check if the email and phone number are already registered
            $.ajax({
                url: templateUri + "/inc/ajax/email_validate.php", // Replace with the actual URL to your PHP file for checking account details
                method: "POST",
                data: {
                    account_email: account_email
                },
                success: function (response) {
                    if (response.trim() === "exists") {
                        // Account with the provided email or phone number already exists
                        $("#err_account_email").html("An account is already registered with your Email ID. Please enter another Email ID.").show();
                        $("#account_email").parent().closest('div').addClass("err");
                        x++;
                    } else if (response.trim() === "not-exists") {
                        // No account exists with the provided email or phone number
                        $("#err_account_email").hide();
                        $("#account_email").parent().closest('div').removeClass("err");
                    } else {
                        console.log(response); // Handle other responses if needed
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    }
    console.log(x);
    if (x === 0) {
        // Validation passed, make AJAX request to update account profile
        updateAccountProfile();
    } else {
        return false;
    }
});

function updateAccountProfile() {
    var account_first_name = $("#account_first_name").val();
    var account_last_name = $("#account_last_name").val();
    var account_email = $("#account_email").val();
    var telephone = $("#telephone").val();

    $.ajax({
        url: templateUri + "/inc/ajax/update_account_profile.php", // Replace with the actual URL to your PHP file for updating account profile
        method: "POST",
        data: {
            account_first_name: account_first_name,
            account_last_name: account_last_name,
            telephone: telephone,
            account_email: account_email
        },
        success: function (response) {
            console.log(response);
            if (response.trim() === 'Profile updated successfully.') {
                window.location.href = blogUri + '/my-account'; // Redirect to the "my-account" page
            } else {
                // Handle other responses or errors if needed
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
};

// Hide error messages on input keyup
$("#account_first_name, #account_last_name,#telephone,#account_email").on("keyup", function () {
    if ($(this).val().length > 0) {
        $(this).parent().closest('div').removeClass("err");
        $(this).siblings('.floating-input-error').hide();
    }
});
// Add Address book  
$("#add_address").on("click", function () {
    var billing_first_name = $("#billing_first_name").val();
    var billing_last_name = $("#billing_last_name").val();
    var billing_email = $("#billing_email").val();
    var billing_phone = $("#billing_phone").val();
    var billing_address_1 = $("#billing_address_1").val();
    var billing_city = $("#billing_city").val();
    var billing_state = $("#billing_state").val();
    var billing_postcode = $("#billing_postcode1").val();
    var billing_country = $("#billing_country").val();
    var address_group = $("#address_group_field_ad .address_group").val();
    var regex = /\S+@\S+\.\S+/;
    var x = 0;

    // First Name Validation
    if (billing_first_name == "" || billing_first_name == undefined) {
        $("#billing_first_name").parent().closest('div').addClass("err");
        $("#err_billing_first_name").show();

        x++;
    } else {
        $("#billing_first_name").parent().closest('div').removeClass("err");
        $("#err_billing_first_name").hide();

    }

    // Last Name Validation
    if (billing_last_name == "" || billing_last_name == undefined) {
        $("#billing_last_name").parent().closest('div').addClass("err");
        $("#err_billing_last_name").show();
        x++;
    } else {
        $("#billing_last_name").parent().closest('div').removeClass("err");
        $("#err_billing_last_name").hide();
    }

    // Email Validation
    if (billing_email == "" || billing_email == undefined) {
        $("#billing_email").parent().closest('div').addClass("err");
        $("#err_billing_email").show();
        x++;
    } else {
        if (!regex.test(billing_email)) {
            $('#err_billing_email').html('We need a valid email address.').show();
            $("#billing_email").parent().closest('div').addClass("err");
            x++;
        } else {
            $("#billing_email").parent().closest('div').removeClass("err");
            $("#err_billing_email").hide();
        }
    }

    // Phone Number Validation
    if (billing_phone == "" || billing_phone == undefined) {
        $("#billing_phone").parent().closest('div').addClass("err");
        $("#err_billing_phone").show();
        x++;
    }
    else {
        if (billing_phone.length < 10) {
            $("#err_billing_phone").html("We need a valid phone number.").show();
            $("#billing_phone").parent().closest('div').addClass("err");
            x++;
        } else {
            $("#billing_phone").parent().closest('div').removeClass("err");
            $("#err_billing_phone").hide();
        }
    }

    // Address Validation
    if (billing_address_1 == "" || billing_address_1 == undefined) {
        $("#billing_address_1").parent().closest('div').addClass("err");
        $("#err_billing_address_1").show();
        x++;
    } else {
        $("#billing_address_1").parent().closest('div').removeClass("err");
        $("#err_billing_address_1").hide();
    }

    // City Validation
    if (billing_city == "" || billing_city == undefined) {
        $("#billing_city").parent().closest('div').addClass("err");
        $("#err_billing_city").show();
        x++;
    } else {
        $("#billing_city").parent().closest('div').removeClass("err");
        $("#err_billing_city").hide();
    }

    // State Validation
    if (billing_state == "" || billing_state == undefined) {
        $("#billing_state").parent().closest('div').addClass("err");
        $("#err_billing_state").show();
        x++;
    } else {
        $("#billing_state").parent().closest('div').removeClass("err");
        $("#err_billing_state").hide();
    }

    // Postcode Validation
    if (billing_postcode == "" || billing_postcode == undefined) {
        $("#billing_postcode").parent().closest('div').addClass("err");
        $("#err_billing_postcode").show();
        x++;
    } else {
        $("#billing_postcode").parent().closest('div').removeClass("err");
        $("#err_billing_postcode").hide();
    }
    if (billing_country == "" || billing_country == undefined) {
        $("#billing_country").parent().closest('div').addClass("err");
        $("#err_billing_country").show();
        x++;
    } else {
        $("#billing_country").parent().closest('div').removeClass("err");
        $("#err_billing_country").hide();
    }
    if (x === 0) {
        $.ajax({
            url: blogUri + "/wp-admin/admin-ajax.php",
            type: 'POST',
            data: {
                'action': 'update_billing_address',
                'billing_first_name': billing_first_name,
                'billing_last_name': billing_last_name,
                'billing_email': billing_email,
                'billing_phone': billing_phone,
                'billing_address_1': billing_address_1,
                'billing_city': billing_city,
                'billing_state': billing_state,
                'billing_postcode': billing_postcode,
                'billing_country': billing_country,
                'address_group': address_group
            },
            success: function (response) {
                if (response) {
                    $('.bg-layer').removeClass('active');
                    $('#address-list').html(response);
                    $('.adrs-form').removeClass('active');
                    $('#address-list').show();

                    $('.add-adrs-close').on('click', function () {
                        $('.adrs-form').removeClass('active');
                        $('.add-adrs-btn1').removeClass('active');
                    })
                    $(".add-adrs-btn").on("click", function () {
                        $('.bg-layer').addClass('active');
                        $('.adrs-form').addClass('active');
                        $('#Adress_form')[0].reset(); // reset the form
                        $('.floating-blk').removeClass('active');
                        $('#Adress_form').show(); // show the form
                        $('.edit_address_form').removeClass('active');
                        $('form select.floating-input').each(function () {
                            if ($(this).val().trim() !== '') {
                                $(this).closest('.floating-blk').addClass('active');
                            } else {
                                $(this).closest('.floating-blk').removeClass('active');
                            }
                        });
                    });
                    $('.edit_address').each(function () {
                        $(this).on('click', function (e) {
                            e.preventDefault();

                            // $('.floating-blk').addClass('active');
                            $(".edit_address_form").show();
                            $('.bg-layer-edit').addClass('active');
                            $('.edit_address_form').addClass('active');
                            $('.adrs-form').removeClass('active');
                            var fname = $(this).data('fname');
                            var lname = $(this).data('lname');
                            var email = $(this).data('email');
                            var phone = $(this).data('phone');
                            var address1 = $(this).data('address1');
                            var city = $(this).data('city');
                            var state = $(this).data('state');
                            var postcode = $(this).data('postcode');
                            var country = $(this).data('country');
                            var address_group = $(this).data('address_group'); // define address_index variable
                            var address_index = $(this).data('index'); // define address_index variable
                            // console.log(fname, lname, email, phone, address1, city, state, postcode, country, address_index);
                            $('.floating-input[name="first_name"]').val(fname);
                            $('.floating-input[name="last_name"]').val(lname);
                            $('.floating-input[name="email"]').val(email);
                            $('.floating-input[name="phone"]').val(phone);
                            $('.floating-input[name="address_1"]').val(address1);
                            $('.floating-input[name="city"]').val(city);
                            $('.floating-input[name="state"]').val(state);
                            $('.floating-input[name="postcode"]').val(postcode);
                            $('.floating-input[name="country"]').val(country);
                            $('.floating-input[name="address_group"]').val(address_group);
                            $('.edit_address_form').val(address_index);
                            $('.floating-input').each(function () {
                                if ($(this).val() !== '') {
                                    $(this).closest('.floating-blk').addClass('active');
                                }
                            });

                        });
                    });

                }
            }
        });
    }
    else {
        console.log("add adderss validation fail");
        var scroll_height = $('.adrs-form').scrollTop();
        var err = $(this).parent().find('.floating-blk.err').position().top;
        if (scroll_height > err) {
            $('.adrs-form').animate({
                scrollTop: (err + scroll_height - 150)
            }, 500);
        }
    }
});
// Hide error messages on input keyup
$("#billing_first_name, #billing_last_name,#billing_email,#billing_phone,#billing_address_1,#billing_city,#billing_state,#billing_postcode,#billing_country").on("keyup", function () {
    if ($(this).val().length > 0) {
        $(this).parent().closest('div').removeClass("err");
        $(this).siblings('.floating-input-error').hide();
    }
});

// Edit Address Book  
$(document).ready(function () {
    // select all edit buttons with class edit_address
    $('.edit_address').each(function () {
        $(this).on('click', function (e) {
            $('.edit_address_form').addClass('active');
            e.preventDefault();

            // $('.floating-blk').addClass('active');
            $(".edit_address_form").show();
            $('.adrs-form').removeClass('active');
            var fname = $(this).data('fname');
            var lname = $(this).data('lname');
            var email = $(this).data('email');
            var phone = $(this).data('phone');
            var address1 = $(this).data('address1');
            var city = $(this).data('city');
            var state = $(this).data('state');
            var postcode = $(this).data('postcode');
            var country = $(this).data('country');
            var address_group = $(this).data('address_group');
            var address_index = $(this).data('index'); // define address_index variable
            // console.log(fname, lname, email, phone, address1, city, state, postcode, country, address_index,address_group);
            $('.floating-input[name="first_name"]').val(fname);
            $('.floating-input[name="last_name"]').val(lname);
            $('.floating-input[name="email"]').val(email);
            $('.floating-input[name="phone"]').val(phone);
            $('.floating-input[name="address_1"]').val(address1);
            $('.floating-input[name="city"]').val(city);
            $('.floating-input[name="state"]').val(state);
            $('.floating-input[name="postcode"]').val(postcode);
            $('.floating-input[name="country"]').val(country);
            $('.floating-input[name="address_group"]').val(address_group);
            $('.edit_address_form').val(address_index);
            var checked_value = address_group;
            if (checked_value === "Home") {
                $('.other-form-field').hide();
                $('.other-form-field').removeClass('active');
                $('.other_label2').val(address_group);
                $('input:radio[name="address-group-name-update"][value=' + checked_value + ']').prop('checked', true);
            } else if (checked_value === "Office") {
                $('.other-form-field').hide();
                $('.other-form-field').removeClass('active');
                $('.other_label2').val(address_group);
                $('input:radio[name="address-group-name-update"][value=' + checked_value + ']').prop('checked', true);
            } else if (checked_value === "Other") {
                $('.other-form-field').hide();
                $('.other-form-field').removeClass('active');
                $('.other_label2').val(address_group);
                $('input:radio[name="address-group-name-update"][value=' + checked_value + ']').prop('checked', true);
            } else if (checked_value !== "Other") {
                $('.other-form-field').show();
                $('.other-form-field').addClass('active');
                $('.other_label2').val(address_group);
                $('input:radio[name="address-group-name-update"][value=Other]').prop('checked', true);
            }
            $('.floating-input').each(function () {
                if ($(this).val() !== '') {
                    $(this).closest('.floating-blk').addClass('active');
                }
            });

        });
    });

    // handle the Save button click event
    $('#edit_address_form').on('click', function (e) {
        e.preventDefault();
        var address_index = $('.edit_address_form').val();
        var first_name = $('.floating-input[name="first_name"]').val();
        var last_name = $('.floating-input[name="last_name"]').val();
        var email = $('.floating-input[name="email"]').val();
        var phone = $('.floating-input[name="phone"]').val();
        var address_1 = $('.floating-input[name="address_1"]').val();
        var city = $('.floating-input[name="city"]').val();
        var state = $('.floating-input[name="state"]').val();
        var postcode = $('.floating-input[name="postcode"]').val();
        var country = $('.floating-input[name="country"]').val();
        var address_group = $('#address_group_field_up .address_group').val();
        console.log(address_index, first_name, last_name, email, phone, address_1, city, state, postcode, country, address_group);
        var regex = /\S+@\S+\.\S+/;
        var x = 0;
        // First Name Validation
        if (first_name == "" || first_name == undefined) {
            $("#first_name").parent().closest('div').addClass("err");
            $("#err_first_name").show();

            x++;
        } else {
            $("#first_name").parent().closest('div').removeClass("err");
            $("#err_first_name").hide();

        }

        // Last Name Validation
        if (last_name == "" || last_name == undefined) {
            $("#last_name").parent().closest('div').addClass("err");
            $("#err_last_name").show();
            x++;
        } else {
            $("#last_name").parent().closest('div').removeClass("err");
            $("#err_last_name").hide();
        }

        // Email Validation
        if (email == "" || email == undefined) {
            $("#email").parent().closest('div').addClass("err");
            $("#err_email").show();
            x++;
        } else {
            if (!regex.test(email)) {
                $('#err_email').html('We need a valid email address.').show();
                $("#email").parent().closest('div').addClass("err");
                x++;
            } else {
                $("#email").parent().closest('div').removeClass("err");
                $("#err_email").hide();
            }
        }

        // Phone Number Validation
        if (phone == "" || phone == undefined) {
            $("#phone").parent().closest('div').addClass("err");
            $("#err_phone").show();
            x++;
        }
        else {
            if (phone.length < 10) {
                $("#err_phone").html("We need a valid phone number.").show();
                $("#phone").parent().closest('div').addClass("err");
                x++;
            } else {
                $("#phone").parent().closest('div').removeClass("err");
                $("#err_phone").hide();
            }
        }

        // Address Validation
        if (address_1 == "" || address_1 == undefined) {
            $("#address_1").parent().closest('div').addClass("err");
            $("#err_address_1").show();
            x++;
        } else {
            $("#address_1").parent().closest('div').removeClass("err");
            $("#err_address_1").hide();
        }

        // City Validation
        if (city == "" || city == undefined) {
            $("#city").parent().closest('div').addClass("err");
            $("#err_city").show();
            x++;
        } else {
            $("#city").parent().closest('div').removeClass("err");
            $("#err_city").hide();
        }

        // State Validation
        if (state == "" || state == undefined) {
            $("#state").parent().closest('div').addClass("err");
            $("#err_state").show();
            x++;
        } else {
            $("#state").parent().closest('div').removeClass("err");
            $("#err_state").hide();
        }

        // Postcode Validation
        if (postcode == "" || postcode == undefined) {
            $("#postcode").parent().closest('div').addClass("err");
            $("#err_postcode").show();
            x++;
        } else {
            $("#postcode").parent().closest('div').removeClass("err");
            $("#err_postcode").hide();
        }
        if (country == "" || country == undefined) {
            $("#country").parent().closest('div').addClass("err");
            $("#err_country").show();
            x++;
        } else {
            $("#country").parent().closest('div').removeClass("err");
            $("#err_country").hide();
        }
        if (x === 0) {
            $.ajax({
                type: "POST",
                url: blogUri + "/wp-admin/admin-ajax.php",
                dataType: "json",
                data: {
                    'action': 'update_user_address',
                    'address_index': address_index,
                    'first_name': first_name,
                    'last_name': last_name,
                    'email': email,
                    'phone': phone,
                    'address_1': address_1,
                    'city': city,
                    'state': state,
                    'postcode': postcode,
                    'country': country,
                    'address_group': address_group,
                },
                success: function (response) {
                    $('.bg-layer-edit').removeClass('active');
                    //    console.log(response);
                    var update_address = response.data.html;
                    $('#address-list').html(update_address);
                    $('.edit_address_form').removeClass('active');
                    $('#address-list').show();

                    $('.edit_address').each(function () {
                        $(this).on('click', function (e) {
                            e.preventDefault();
                            //   $('.floating-blk').addClass('active');
                            $('.bg-layer-edit').addClass('active');
                            $('.edit_address_form').addClass('active');
                            $('.adrs-form').removeClass('active');
                            var fname = $(this).data('fname');
                            var lname = $(this).data('lname');
                            var email = $(this).data('email');
                            var phone = $(this).data('phone');
                            var address1 = $(this).data('address1');
                            var city = $(this).data('city');
                            var state = $(this).data('state');
                            var postcode = $(this).data('postcode');
                            var country = $(this).data('country');
                            var address_group = $(this).data('address_group');
                            var address_index = $(this).data('index'); // define address_index variable
                            // console.log(fname, lname, email, phone, address1, city, state, postcode, country, address_index);
                            $('.floating-input[name="first_name"]').val(fname);
                            $('.floating-input[name="last_name"]').val(lname);
                            $('.floating-input[name="email"]').val(email);
                            $('.floating-input[name="phone"]').val(phone);
                            $('.floating-input[name="address_1"]').val(address1);
                            $('.floating-input[name="city"]').val(city);
                            $('.floating-input[name="state"]').val(state);
                            $('.floating-input[name="postcode"]').val(postcode);
                            $('.floating-input[name="country"]').val(country);
                            $('.floating-input[name="address_group"]').val(address_group);
                            $('.edit_address_form').val(address_index);
                            $('.floating-input').each(function () {
                                if ($(this).val() !== '') {
                                    $(this).closest('.floating-blk').addClass('active');
                                }
                            });
                        });
                    });

                    $('.add-adrs-close').on('click', function () {
                        $('.adrs-form').removeClass('active');
                        $('.add-adrs-btn1').removeClass('active');
                    })
                    $(".add-adrs-btn").on("click", function () {
                        $('.bg-layer').addClass('active');
                        $('.adrs-form').addClass('active');
                        $('#Adress_form')[0].reset();
                        $('.other-form-field').hide();
                        $('.floating-blk').removeClass('active');
                        $('.edit_address_form').removeClass('active');
                        $('form select.floating-input').each(function () {
                            if ($(this).val().trim() !== '') {
                                $(this).closest('.floating-blk').addClass('active');
                            } else {
                                $(this).closest('.floating-blk').removeClass('active');
                            }
                        });
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("Error updating user address: " + textStatus + " - " + errorThrown);
                }
            });
        } else {
            console.log("Edit address validation Fail");
            var scroll_height = $('.edit_address_form').scrollTop();
            var err = $(this).parent().find('.floating-blk.err').position().top;
            if (scroll_height > err) {
                $('.edit_address_form').animate({
                    scrollTop: (err + scroll_height - 150)
                }, 500);
            }
        }

    });
});

// Hide error messages on input keyup
$("#first_name, #last_name,#telephone,#email,#phone,#address_1,#city,#state,#postcode,#country").on("keyup", function () {
    if ($(this).val().length > 0) {
        $(this).parent().closest('div').removeClass("err");
        $(this).siblings('.floating-input-error').hide();
    }
});
// Remove address book

$(document).ready(function ($) {
    $(document).on('click', '.remove-address', function (event) {
        event.preventDefault();
        var index = $(this).data('index');
        var $addressItem = $(this).closest('.mb-30');
        $.ajax({
            url: blogUri + "/wp-admin/admin-ajax.php",
            type: 'POST',
            data: {
                action: 'remove_address',
                address_index: index,
            },
            success: function (response) {
                if (response.success) {
                    var update_address = response.data.html;
                    $('#address-list').html(update_address);
                    $addressItem.remove();

                    $('.add-adrs-close').on('click', function () {
                        $('.adrs-form').removeClass('active');
                        $('.add-adrs-btn1').removeClass('active');
                    })
                    $(".add-adrs-btn").on("click", function () {
                        $('.bg-layer').addClass('active');
                        $('.adrs-form').addClass('active');
                        $('#Adress_form')[0].reset();
                        $('.other-form-field').hide();
                        $('.floating-blk').removeClass('active');
                        $('#Adress_form').show();
                        $(".edit_address_form").removeClass('active');
                        $('form select.floating-input').each(function () {
                            if ($(this).val().trim() !== '') {
                                $(this).closest('.floating-blk').addClass('active');
                            } else {
                                $(this).closest('.floating-blk').removeClass('active');
                            }
                        });
                    });
                    $('.edit_address').each(function () {
                        $(this).on('click', function (e) {
                            e.preventDefault();
                            //    $('.floating-blk').addClass('active');
                            $(".edit_address_form").show();
                            $('.bg-layer-edit').addClass('active');
                            $('.edit_address_form').addClass('active');
                            $('.adrs-form').removeClass('active');
                            var fname = $(this).data('fname');
                            var lname = $(this).data('lname');
                            var email = $(this).data('email');
                            var phone = $(this).data('phone');
                            var address1 = $(this).data('address1');
                            var city = $(this).data('city');
                            var state = $(this).data('state');
                            var postcode = $(this).data('postcode');
                            var country = $(this).data('country');
                            var address_group = $(this).data('address_group');
                            var address_index = $(this).data('index'); // define address_index variable
                            // console.log(fname, lname, email, phone, address1, city, state, postcode, country, address_index);
                            $('.floating-input[name="first_name"]').val(fname);
                            $('.floating-input[name="last_name"]').val(lname);
                            $('.floating-input[name="email"]').val(email);
                            $('.floating-input[name="phone"]').val(phone);
                            $('.floating-input[name="address_1"]').val(address1);
                            $('.floating-input[name="city"]').val(city);
                            $('.floating-input[name="state"]').val(state);
                            $('.floating-input[name="postcode"]').val(postcode);
                            $('.floating-input[name="country"]').val(country);
                            $('.floating-input[name="address_group"]').val(address_group);
                            $('.edit_address_form').val(address_index);
                            $('.floating-input').each(function () {
                                if ($(this).val() !== '') {
                                    $(this).closest('.floating-blk').addClass('active');
                                }
                            });

                        });
                    });
                } else {
                    console.log(response.data);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    });
});

$('.edit_address').on('click', function () {
    $(this).toggleClass('active');
    var form = $('.edit_address_form');
    form.addClass('active');
    $('.bg-layer-edit').toggleClass('active')
})
$('.add-adrs-close').on('click', function () {
    $('.edit_address_form').removeClass('active');
    $('.bg-layer-edit').removeClass('active')
})
$('.bg-layer-edit').on('click', function () {
    $('.edit_address_form').removeClass('active');
    $(this).removeClass('active');
})

// cancel order 

$('.popup-open').click(function () {
    var order_id = $(this).data('order-id');
    $('#confirm-cancel-order').click(function () {
        console.log(order_id);
        alert(order_id);
        $.ajax({
            url: blogUri + "/wp-admin/admin-ajax.php",
            type: 'POST',
            data: {
                action: 'cancel_order',
                order_id: order_id
            },
            success: function (response) {
                console.log("successfully cancelled");
                window.location.href = blogUri + '/my-account/';
            }
        });
    });
});

// form lable name form select option

$('form select.floating-input').each(function () {
    if ($(this).val().trim() !== '') {
        $(this).closest('.floating-blk').addClass('active');
    } else {
        $(this).closest('.floating-blk').removeClass('active');
    }
});
// order history pagination
$(document).ready(function () {
    $('.pagination-arrows .right').on('click', function () {
        var parent = $(this).closest('.pagination-blk');
        var start = parseInt(parent.find('.start-page').text());
        var end = parseInt(parent.find('.end-page').text());
        if (start < end) {
            start++;
            parent.find('.start-page').text(start);
            parent.find('.left').addClass('active');
            if (start >= end) {
                $(this).removeClass('active');
            }
        }
    });

    $('.pagination-arrows .left').on('click', function () {
        var parent = $(this).closest('.pagination-blk');
        var start = parseInt(parent.find('.start-page').text());
        var end = parseInt(parent.find('.end-page').text());
        if (start > 1) {
            start--;
            parent.find('.start-page').text(start);
            parent.find('.right').addClass('active');
            if (start <= 1) {
                $(this).removeClass('active');
            }
        }
    });

    // add this code to initialize the active class on page load
    var parent = $('.pagination-blk');
    var start = parseInt(parent.find('.start-page').text());
    var end = parseInt(parent.find('.end-page').text());
    if (start <= 1) {
        parent.find('.left').removeClass('active');
    }
    if (start > 1) {
        parent.find('.left').addClass('active');
    }
    if (start >= end) {
        parent.find('.right').removeClass('active');
    }
});

// cookie content 
// $(document).ready(function () {
//     var cookie_name = "ssp_cookie";

//     // Check if the cookie exists
//     if (document.cookie.indexOf(cookie_name) === -1) {
//         $(".popup-card").show();
//     }

//     $(".cookie_content").click(function () {
//         var cookie_value = "1";
//         $.ajax({
//             url: templateUri + '/inc/ajax/cookie_ajax.php',
//             data: {
//                 cookie_name: cookie_name,
//                 cookie_value: cookie_value
//             },
//             type: "POST",
//             success: function (response) {
//                 $(".popup-card").hide();
//                 console.log(response);
//                 console.log("cookie stored");
//             },
//             error: function (xhr, status, error) {
//                 console.log("Error storing cookie:", error);
//             }
//         });
//     });
//     if (document.cookie.indexOf("ssp_cookie=1") >= 0) {
//         $(".popup-card").hide();
//     }
// });
//global cookie
// $(document).ready(function () {
//     var cookie_name = "global_popup_cookie";

//     // Check if the cookie exists
//     if (document.cookie.indexOf(cookie_name) === -1) {
//         $("#global_popup").show();
//     }

//     $(".gobal_popup_content").click(function () {
//         var cookie_value = "1";
//         $.ajax({
//             url: templateUri + '/inc/ajax/global_cookie_ajax.php',
//             data: {
//                 cookie_name: cookie_name,
//                 cookie_value: cookie_value
//             },
//             type: "POST",
//             success: function (response) {
//                 $("#global_popup").hide();
//                 console.log(response);
//                 console.log("global cookie stored");
//             },
//             error: function (xhr, status, error) {
//                 console.log("Error storing cookie:", error);
//             }
//         });
//     });
//     if (document.cookie.indexOf("global_popup_cookie=1") >= 0) {
//         $("#global_popup").hide();
//     }
// });

// Update input value on change radio button - Edit and update address
$('.other_label1').blur(function (e) {
    // alert("Add label");
    var labelvalue = $(this).val();
    if (labelvalue != "") {
        $('#address_group_field_ad .address_group').val(labelvalue);
    } else {
        $('#address_group_field_ad .address_group').val('Other');
    }
});
$('.other_label2').blur(function (e) {
    // alert("Update label");
    var labelvalue = $(this).val();
    if (labelvalue != "") {
        $('#address_group_field_up .address_group').val(labelvalue);
    } else {
        $('#address_group_field_up .address_group').val('Other');
    }
});
$('input[name=address-group-name-update]').change(function (e) {
    var chkboxValue = this.value;
    if (chkboxValue === "Home") {
        $('#address_group_field_up .address_group').val("Home");
    } else if (chkboxValue === "Office") {
        $('#address_group_field_up .address_group').val("Office");
    }
    else if (chkboxValue === "Other") {
        var labelValue = $('.other_label2').val();
        if (labelValue == "Home" || labelValue == "Office") {
            var labelValue = $('.other_label2').val(' ');
            $('#address_group_field_up .address_group').val("Other");
        } else {
            $('#address_group_field_up .address_group').val(labelValue);
        }
    }
});
$('input[name=address-group-name-add]').change(function (e) {
    var chkboxValue = this.value;
    var labelValue = $('.other_label1').val();
    if (chkboxValue != "") {
        $('#address_group_field_ad .address_group').val(chkboxValue);
    } else if (chkboxValue == "Other" && labelValue != "") {
        $('#address_group_field_ad .address_group').val(labelValue);
    }
});

// Function to fetch location details based on PIN code
function fetchLocationDetails(pincode, countryId, stateId, cityId) {
    // Make an AJAX request to fetch the location details
    $.ajax({
        url: `https://maps.googleapis.com/maps/api/geocode/json?address=${pincode}&sensor=true&key=AIzaSyAc62qivSjeF4i_xSWblDeoo5681E4PQFM`,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data);
            // Check if the API response contains valid data
            if (data && data.results && data.results.length > 0) {
                const addressComponents = data.results[0].address_components;
                let country, state, city;

                // Loop through the address components to find country, state, and city
                for (let i = 0; i < addressComponents.length; i++) {
                    const component = addressComponents[i];
                    const types = component.types;
                    if (types.includes('country')) {
                        country = component.long_name;
                    } else if (types.includes('administrative_area_level_1')) {
                        state = component.long_name;
                    } else if (types.includes('locality') || types.includes('administrative_area_level_3') || types.includes('administrative_area_level_2')) {
                        city = component.long_name;
                    }
                }
                $('#continue_addr_checkout').prop('disabled', false);
                $(`#${countryId}, #${stateId}, #${cityId}`).parents('.floating-blk').addClass('active');
                const optionElement = $(`#${countryId} option:contains(${country})`);
                if (optionElement.length > 0) {
                    optionElement.prop('selected', true);
                    // $('#continue_addr_checkout').prop('disabled', false);
                    $(`#${stateId}`).val(state);
                    $(`#${cityId}`).val(city);
                    $(document).find('.post-error-code').hide();
                    $(document).find('.post-error-code-in').hide();
                    $(document).find('.post-error-code1').hide();
                } else {
                    $(document).find('.post-error-code').hide();
                    // $('<p class="floating-input-error">Please enter valid postcode.</p>').appendTo($("#billing_postcode_field #billing_postcode").parents(".floating-blk")).slideDown();
                    // alert(`Country '${country}' not found in the options.`);
                    // console.log(`Country '${country}' not found in the options.`);
                    $(`#${stateId}, #${cityId}`).parents('.floating-blk').removeClass('active');
                    $(`#${stateId}`).val("");
                    $(`#${cityId}`).val("");
                    // if (!postcodeErrorDisplayed) {
                    // $('#continue_addr_checkout').prop('disabled', true);
                    var internationalCountries = [
                        'Australia', 'Bahrain', 'Canada', 'France', 'Germany', 'Indonesia',
                        'Italy', 'Kuwait', 'Malaysia', 'Myanmar', 'Netherlands', 'Oman',
                        'Philippines', 'Qatar', 'Saudi Arabia', 'Singapore', 'Sri Lanka',
                        'Trinidad and Tobago', 'United Arab Emirates', 'United Kingdom',
                        'United States'
                    ];
                    if (internationalCountries.includes(country)) {
                        $(document).find('.post-error-code1').hide();
                        if ($(document).find('.post-error-code-in').length > 0) {
                            $(document).find('.post-error-code-in').text('The entered pincode appears to be of international origin. Please provide a different one, as this checkout process is for domestic orders.').css('display', 'block');
                        } else {
                            $('<p class="floating-input-error post-error-code-in">The entered pincode appears to be of international origin. Please provide a different one, as this checkout process is for domestic orders.</p>')
                                .appendTo($("#billing_postcode_field #billing_postcode").parents(".floating-blk"))
                                .show();
                        }

                    }
                    else if (country == "India") {
                        $(document).find('.post-error-code-in').hide();
                        if ($(document).find('.post-error-code1').length > 0) {
                            $(document).find('.post-error-code1').text('The entered pincode appears to be domestic in nature. Please provide a global pincode, as this checkout process is for international orders.').css('display', 'block');
                        } else {
                            $('<p class="floating-input-error post-error-code1">The entered pincode appears to be domestic in nature. Please provide a global pincode, as this checkout process is for international orders.</p>')
                                .appendTo($("#billing_postcode_field #billing_postcode").parents(".floating-blk"))
                                .show();
                        }
                    } else {
                        $(document).find('.post-error-code1').hide();
                        $(document).find('.post-error-code-in').hide();
                        console.log("Not shipping that country");
                        $(document).find('.post-error-code').show();
                    }

                    console.log(`Country '${country}' not found in the options.`);
                    // postcodeErrorDisplayed = true;
                    // }
                }

                // $(`#${stateId}`).val(state);
                // $(`#${cityId}`).val(city);

            } else {
                $(`#${stateId}, #${cityId}`).parents('.floating-blk').removeClass('active');
                $(`#${stateId}`).val("");
                $(`#${cityId}`).val("");
                $(document).find('.post-error-code-in').hide();
                $(document).find('.post-error-code1').hide();
                // if (!postcodeErrorDisplayed) {  
                // $('#continue_addr_checkout').prop('disabled', true);
                if ($(document).find('.post-error-code').length > 0) {
                    // $(document).find('.post-error-code').text('We need a valid postcode.');
                    $(document).find('.post-error-code').text('We need a valid postcode.').css('display', 'block');
                } else {
                    $('<p class="floating-input-error post-error-code">We need a valid postcode.</p>')
                        .appendTo($("#billing_postcode_field #billing_postcode").parents(".floating-blk"))
                        .show();
                }
                //    postcodeErrorDisplayed = true;
                //    }
                console.error('Invalid API response');
            }
        },
        error: function (error) {
            console.error('Error:', error);
        }
    });
}


// Event listener for the PIN code field
$('#billing_postcode1').on('keyup', function () {
    const pincode = $(this).val();
    fetchLocationDetails(pincode, 'billing_country', 'billing_state', 'billing_city');
});

$('#postcode').on('keyup', function () {
    const pincode = $(this).val();
    fetchLocationDetails(pincode, 'country', 'state', 'city');
});

// let postcodeErrorDisplayed = false;
$('#billing_postcode').on('keyup', function () {
    const pincode = $(this).val();
    if (pincode == "") {

        $(document).find('.post-error-code').hide();
    }
    // if (postcodeErrorDisplayed) {
    //     // $('.floating-input-error').remove();
    //     postcodeErrorDisplayed = false;
    // }
    fetchLocationDetails(pincode, 'country', 'billing_state', 'billing_city');
});
$('#billing_postcode').on('keyup', function () {
    const pincode = $(this).val();
    if (pincode == "") {
        $(document).find('.post-error-code').hide();
    }
    // if (postcodeErrorDisplayed) {
    //     // $('.floating-input-error').remove();
    //     postcodeErrorDisplayed = false;
    // }
    fetchLocationDetails(pincode, 'country_add', 'billing_state', 'billing_city');
});



// Sub Menu active 
$(document).ready(function () {
    var link = document.location.href.split('/');
    var menu_title = link[4];
    var menu_titl = link[3];
    var url_title = link[2];
    var prod_title = link[5];
    $('.header-nav li a').each(function (i) {
        const title = $(this).attr("title");

        const value = (title || '').toLowerCase();
        if (value == menu_title) {
            $(this).addClass("active");
        } else if (value == url_title) {
            $(this).addClass("active");
        } else if (value == menu_titl) {
            $(this).addClass("active");
        }
        else if (value == prod_title) {
            $(this).addClass("active");
        }
    });
});


$('#pro_weight').on('change', function () {
    var var_id_plus = $(this).find('option:selected').attr('var-id');


    $(".variation_image").each(function () {
        var varId = $(this).attr('var_id');

        if (var_id_plus == varId) {
            $(this).show(); // Show the matching variation image
        } else {
            $(this).hide(); // Hide non-matching variation images
        }
    });
    $(".variation_thumimage").each(function () {
        var varId = $(this).attr('var_id');

        if (var_id_plus == varId) {
            $(this).show(); // Show the matching variation thumbnail image
        } else {
            $(this).hide(); // Hide non-matching variation thumbnail images
        }
    });
    // Additional actions based on the match status
    if ($(".variation_image:visible").length > 0) {
        $(".gallery_img").hide();
        $(".gallery_thumimg").hide();
    } else {
        $(".gallery_img").show();
        $(".gallery_thumimg").show();
    }
});

// variation gallery images
// $(document).ready(function ($) {
//     // Attach a change event listener to the select element
//     $('#pro_weight').change(function () {
//         var selectedVarId = $(this).find('option:selected').attr('var-id');

//         // Make an AJAX request to fetch the variation gallery images based on selectedVarId
//         $.ajax({
//             type: 'POST',
//             url: blogUri + "/wp-admin/admin-ajax.php", // Replace with the actual endpoint
//             data: {
//                 action: 'get_variation_images', // This should match your WordPress AJAX action name
//                 variation_id: selectedVarId,
//             },
//             success: function (response) {
//                 if (response && response.images) {
//                     var variationGalleryImages = response.images;
//                     // console.log(variationGalleryImages);
//                     // Clear the existing gallery and thumbnail images
//                     $('#variation-gallery').empty();
//                     $('#thumbnail-gallery').empty();
//                   // Append the new variation images to the gallery
//                   variationGalleryImages.forEach(function (imageUrl) {
//                     $('#variation-gallery').append('<div class="product-card dynamic-gallery"><img src="' + imageUrl + '" alt="Variation Image"></div>');
//                     $('#thumbnail-gallery').append('<div class="product-thum dynamic-thumbnail"><img src="' + imageUrl + '" alt="Main Image Thumbnail"></div>');
//                 });
//                 $('.product-slider-nav,.product-slider-for').slick('reinit');
//                 }
//             },
//             error: function (error) {
//                 console.log(error);
//             }
//         });
//     });
// });

$(document).ready(function ($) {
    // Attach a change event listener to the select element
    $('#pro_weight').change(function () {
        var selectedVarId = $(this).find('option:selected').attr('var-id'); 
        $('.detail-slider-con').removeClass('active'); 
        $('#variation-' + selectedVarId).addClass('active');
        $('.product-slider-nav').slick('reinit'); 
        $('.product-slider-for').slick('refresh');
     
    });
});

$(document).ready(function ($) {
    // Attach a change event listener to the select element
    $('#glo_weight').change(function () {
        var selectedVarId = $(this).find('option:selected').attr('var-id'); 
        $('.detail-slider-con').removeClass('active'); 
        $('#variation-' + selectedVarId).addClass('active');
        $('.product-slider-nav').slick('reinit');
        $('.product-slider-for').slick('refresh');
    });
});

// Hide dropdown for single variation product
$(document).ready(function(){
    var options = $('#pro_weight option');
    var numberOfOptions = options.length;
    console.log(typeof numberOfOptions);
    // alert("Number of options: " + numberOfOptions);
    if(numberOfOptions > 1 ){
        $(".arrow_drop_down").show();
        // $('select').css({"cursor": "pointer"});
        // alert("Number of options: " + numberOfOptions);
    }else{
        $(".arrow_drop_down").hide();
        $('#pro_weight').prop('disabled', true); 
        // $('select').css({"cursor": "auto"});
    }
    
    var options1 = $('#glo_weight option');
    var numberOfOptions1 = options1.length;
    if(numberOfOptions1 > 1 ){
        $(".arrow_drop_down_glo").show();
        // $('select').css({"cursor": "pointer"});
        // alert("Number of options: " + numberOfOptions);
    }else{
        $(".arrow_drop_down_glo").hide();
        $('#glo_weight').prop('disabled', true); 
        // $('select').css({"cursor": "auto"});
    }
});
 
//chcekout total ceil value
  $(document).ready(function($) { 
    function roundAndUpdateTotal(selector) { 
        setTimeout(function() { 
            var checkoutTotal = $(selector + ' .woocommerce-Price-amount bdi');
 
            var currentTotalText = checkoutTotal.text(); 
            var currentTotalValue = parseFloat(currentTotalText.replace(/[^\d.]/g, ''));
 
            var roundedTotalValue = Math.ceil(currentTotalValue);
 
            checkoutTotal.text('₹' + roundedTotalValue.toFixed(2));
        }, 100); // Adjust the delay as needed
    } 
    roundAndUpdateTotal('td #checkout_total');
 
    roundAndUpdateTotal('td #checkout_total1'); 
    $(window).on('load', function() { 
        roundAndUpdateTotal('td #checkout_total');
 
        roundAndUpdateTotal('td #checkout_total1');
    });
 
    $(document).ajaxComplete(function() {
         roundAndUpdateTotal('td #checkout_total');

         roundAndUpdateTotal('td #checkout_total1');
    });
}); 

