<?php
// Shipping charge calclulation by flat rate
add_filter('woocommerce_package_rates', 'custom_shipping_rate_cost_calculation', 10, 2);
function custom_shipping_rate_cost_calculation($rates, $package)
{
    $products_ids_array=[];
    $weight = WC()->cart->get_cart_contents_weight();
    $countryCode = WC()->customer->get_shipping_country();
    $special_ship = 2;
    foreach (WC()->cart->get_cart() as $cart_item) {
        $product_in_cart = $cart_item['product_id'];
        $products_ids_array[] = $cart_item['product_id'];
        $ship_opt = get_post_meta($product_in_cart, 'shipping_option_choose', true);
        if ($ship_opt!=="" && $ship_opt === "enable") {
            $selectedPid = $product_in_cart;
            $special_ship = 1;
        }
    }
    // echo $selectedPid.":".$special_ship;
    if ($countryCode == 'IN') {
        $additional_cost = 0;
    } else { 
        if ($special_ship == 1) {
            $allCountries = WC()->countries->get_shipping_countries();
            $count = 0;
            foreach ($allCountries as $country_code => $country) {
                if ($country_code == $countryCode) {
                    $ship_cnt_val = get_post_meta($selectedPid, 'ship_cnt_val', true);
                    $shippingAmt =  $ship_cnt_val[$count];
                }
                $count++;
            }
            $additional_cost = $shippingAmt;
        } else {
           if ($weight <= 500) {
            $additional_cost = get_option($countryCode . "_five");
            } else {
                $additional_cost = (get_option($countryCode . "_five") + get_option($countryCode . "_more"));
            }
        }
     // echo "<br><br>Testing Cost :<br><br>";
     // echo "Additional Cost :".$additional_cost."<br>";
     // echo "Country Code :". $countryCode."<br>";
     // echo "Product weight :". $weight."<br>";
     // echo "Shipping Option :". $special_ship;
     // // echo "Product ID :". $selectedPid;
    }
    foreach ($rates as $rate_key => $rate) {
        if ('flat_rate' === $rate->method_id) {
            // Get rate cost and Custom cost
            $initial_cost = $rates[$rate_key]->cost;
            // Calculation
            $new_cost = $initial_cost + $additional_cost;
            // Set Custom rate cost
            $rates[$rate_key]->cost = round($new_cost, 2);
            // Taxes rate cost (if enabled)
            $new_taxes = array();
            $has_taxes = false;
            foreach ($rate->taxes as $key => $tax) {
                if ($tax > 0) {
                    // Calculating the tax rate unit
                    $tax_rate = $tax / $initial_cost;
                    // Calculating the new tax cost
                    $new_tax_cost = $tax_rate * $new_cost;
                    // Save the calculated new tax rate cost in the array
                    $new_taxes[$key] = round($new_tax_cost, 2);
                    $has_taxes = true;
                }
            }
            // Set new tax rates cost (if enabled)
            if ($has_taxes)
                $rate->taxes = $new_taxes;
        }
    }
    return $rates;
}
