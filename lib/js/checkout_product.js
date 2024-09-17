// Domestic checkout qty incre decre
$(document).ready(function () { 
    $('.domes_check_dec1').each(function () {
        var qty = parseInt($(this).attr('data-qty'));
        if (qty > 1) {
            $(this).addClass('active');
            // $(this).hide();
        }
    });
    $('.domes_check_inc1').each(function () {
        var qty = parseInt($(this).attr('data-qty'));
        if (qty == 20) {
            $(this).removeClass('active');
        }
    });
 
    $('.glob_check_dec1').each(function () {
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
    $('.glob_check_inc1').each(function () {
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
$(document).on('click', '.domes_check_inc1 , .domes_check_dec1', function () {
    var cartQtyMinus = $(this).closest('.count').find('.domes_check_dec1');
    var cartQtyPlus = $(this).closest('.count').find('.domes_check_inc1');
    var cart_item_key = $(this).attr('data-key');
    var qty = $(this).attr('data-qty');
    var variation_id = $(this).attr('data-variation-id');
    if ($(this).hasClass('domes_check_inc1')) {
        if (qty < 20) {
            qty++;
            cartQtyMinus.addClass('active');
        } if (qty == 20) {
            $(this).removeClass('active');
        }

    } else if ($(this).hasClass('domes_check_dec1')) {
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
            console.log(response);
            updateheaderCartCount(); 
            if (response.success) {
                location.reload();
                var updatedQty = response.data.quantity;
                var updatedPrice = response.data.price;
                var updatedTotal = response.data.total;
                var productID = response.data.product_id;
                var variationID = response.data.variation_id;
                $('#domes_check_qty1' + variationID).val(updatedQty);
                $('#checkout_price1' + variationID).html(updatedPrice);

                $('#checkout_total1').html(updatedTotal);
                $('#checkout_total_price1').html(updatedTotal);
                $('.domes_check_inc1[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);
                $('.domes_check_dec1[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);
            }
        }
    });
});

$(document).on('click', '.glob_check_inc1 , .glob_check_dec1', function () {
     var cartQtyMinus = $(this).closest('.count').find('.glob_check_dec1');
    var cartQtyPlus = $(this).closest('.count').find('.glob_check_inc1');
    var cart_item_key = $(this).attr('data-key');
    var qty = parseInt($(this).attr('data-qty'));
    var variation_id = $(this).attr('data-variation-id');
    var minqty = parseInt($(this).attr('data-min'));
    var maxqty = parseInt($(this).attr('data-max'));
    if ($(this).hasClass('glob_check_inc1')) {
        if (qty < maxqty) {
            qty++;
            cartQtyMinus.addClass('active');
        }
        if (qty == maxqty) {
            $(this).removeClass("active");
        }
    } else if ($(this).hasClass('glob_check_dec1')) {
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
                location.reload();
                var updatedQty = response.data.quantity;
                var updatedPrice = response.data.price;
                var updatedTotal = response.data.total;
                var productID = response.data.product_id;
                var variationID = response.data.variation_id;
                var updated_total_price = response.data.updated_price;

                $('#glob_check_qty1' + variationID).val(updatedQty);
                $('#checkout_price1' + variationID).html(updatedPrice);
                $('#checkout_total1').html(updatedTotal);
                $('#checkout_total_price1').html(updated_total_price);
                $('.glob_check_inc1[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);
                $('.glob_check_dec1[data-key="' + cart_item_key + '"]').attr('data-qty', updatedQty);

            }
        }
    });
});


$(document).on('click', '.checkout_product_remove', function () {
    var productId = $(this).data('product-id');
    var variationId = $(this).data('variation-id');

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


                }
                window.location.reload();
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ': ' + errorThrown);
        }
    });
});
