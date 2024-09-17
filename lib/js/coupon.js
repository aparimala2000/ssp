
//coupon code

// Coupon code function - start 
$(document).ready(function () {
    var discountAmountText = $('.discount_amount').text();
    var discountAmount = parseFloat(discountAmountText.replace(/[^\d.-]/g, ''));
    // console.log('Discount Amount:', discountAmount);
    if (discountAmount == 0) {
        setTimeout(function () {
            $(".coupon-success-blk").removeClass("active");
        }, 2000);
        localStorage.removeItem('couponApplied');
        localStorage.removeItem('appliedCoupon');
    }
    // Check if a coupon is already applied on page load
    var isCouponApplied = localStorage.getItem('couponApplied');

    if (isCouponApplied === 'true') {
        $(".coupon-success-blk").addClass("active");
        //  $("#apply-coupon-btn1").hide();
        //  $(".success-msg").addClass("active");  
        $("#applied_coupon1").text(localStorage.getItem('appliedCoupon'));

    }
    $(document).on('click', '.condition_coupon', function (e) {
        var coupon = $(this).data('coupon'); 
        // var totalAmount = $("#checkout_total").text();
        var totalAmount = $("#checkout_total_price1").text();
        
        var checkoutcouponTotalAmount = parseFloat(totalAmount.replace(/[^0-9.-]+/g, ""));
        var couponPercentage = parseFloat($(this).data('percentage'));
        
        $.ajax({
            type: "POST",
            url: blogUri + "/wp-admin/admin-ajax.php",
            data: {
                // action: 'coupon_checkout',
                action: 'my_special_action',
                couponcode: coupon, 
            },
            success: function (data) {
                console.log(data);
                if (data != 1) {
                    // $(".success-msg").addClass("active");
                    $(".coupon-detail-blk").removeClass("active");
                    setTimeout(function () {
                        $('.popup-box-blk').addClass('active');
                    }, 300);
                    $("#apply-coupon-btn1").hide();
                    var applied_coupon_name = coupon.replace("get", "");

                    $("#applied_coupon1").text(applied_coupon_name); 
                    localStorage.setItem('couponApplied', 'true');
                    localStorage.setItem('appliedCoupon', applied_coupon_name);
                    var savingsAmount = (couponPercentage / 100) * checkoutcouponTotalAmount;
                    // var discountedAmount = checkoutcouponTotalAmount - savingsAmount; 
                    $("#coupon_saving_amount1").text("Save ₹" + Math.floor(savingsAmount));

                    // $("#coupon_saving_amount1").text("₹" + savingsAmount.toFixed(2));

                    // Coupon applied successfully
                    // alert('Coupon applied successfully!'); 

                } else {
                    // alert('Coupon application failed!');
                }
            }
        });

    });
//form text coupon 
$('.apply_coupon_text1').on('click', function (e) {
    var coupon_text = $("#apply_coupon_text1").val();
    // alert(coupon_text);
     if ((coupon_text == '' || coupon_text == undefined) || isDefaultCoupon(coupon_text)) {
        $('.err-msg').addClass('active');
    } else {
        $('.err-msg').removeClass('active');
        $.ajax({
            type: "POST",
            url: blogUri + "/wp-admin/admin-ajax.php",
            data: {
                action: 'my_special_action',
                couponcode: coupon_text
            },
            success: function (data) {
                console.log(data);
                if (data != 1) {
                    $.ajax({
                        type: "POST",
                        url: blogUri + "/wp-admin/admin-ajax.php",
                        data: {
                            action: 'get_coupon_amount',
                            couponcode: coupon_text
                        },
                        success: function (couponData) {
                            // Access the coupon amount from couponData.coupon_amount
                            var couponAmount = parseFloat(couponData.coupon_amount); 
                            $(".coupon-detail-blk").removeClass("active");
                            setTimeout(function () {
                                $('.popup-box-blk').addClass('active');
                            }, 300);
                            $("#apply-coupon-btn1").hide();
                            $("#applied_coupon1").text(coupon_text); 
                            localStorage.setItem('couponApplied', 'true');
                            localStorage.setItem('appliedCoupon', coupon_text);  
                            $("#coupon_saving_amount1").text("Save ₹" + Math.floor(couponAmount));

                        }
                    }); 
                  
                } else {  
                    $('.voucher-code').after('<div class="err-msg active">Please enter a valid coupon.</div>');
                }
            }
        });
    }
});
function isDefaultCoupon(couponCode) {
    var isDefault = false;

    Object.keys(defaultCoupons).forEach(function (id) {
        if (defaultCoupons[id].code === couponCode) {
            isDefault = true;
        }
    });

    return isDefault;
}


    $(".code-remove").on('click', function () {
        setTimeout(function () {
            $(".coupon-success-blk").removeClass("active");
        }, 2000);
                localStorage.removeItem('couponApplied');
        localStorage.removeItem('appliedCoupon'); // Remove stored coupon code

    });


    $(".popup-close, .popup-box-blk").on('click', function () {
        $(".bg-layer").removeClass('active');
        $('.popup-box-blk').removeClass('active');
        location.reload();
        $(".coupon-success-blk").addClass("active");

    });

}); 