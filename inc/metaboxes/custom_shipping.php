<?php

// Add Variation Settings
add_action('woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3);

// Save Variation Settings
add_action('woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2);

/**
 * Create new fields for variations
 *
 */
function variation_settings_fields($loop, $variation_data, $variation)
{

    // Text Field
    woocommerce_wp_text_input(
        array(
            'id'          => '_min_qty_[' . $variation->ID . ']',
            'label'       => __('Min Qty', 'woocommerce'),
            'desc_tip'    => 'true',
            'description' => __('Minimum shipping quantity.', 'woocommerce'),
            'value'       => get_post_meta($variation->ID, '_min_qty_', true)
        )
    );
    // Text Field
    woocommerce_wp_text_input(
        array(
            'id'          => '_max_qty_[' . $variation->ID . ']',
            'label'       => __('Max Qty', 'woocommerce'),
            'desc_tip'    => 'true',
            'description' => __('Maximum shipping quantity.', 'woocommerce'),
            'value'       => get_post_meta($variation->ID, '_max_qty_', true)
        )
    );
}

/**
 * Save new fields for variations
 *
 */
function save_variation_settings_fields($post_id)
{

    // Text Field
    $text_field = $_POST['_min_qty_'][$post_id];
    if (!empty($text_field)) {
        update_post_meta($post_id, '_min_qty_', esc_attr($text_field));
    }
    // Text Field
    $text_field = $_POST['_max_qty_'][$post_id];
    if (!empty($text_field)) {
        update_post_meta($post_id, '_max_qty_', esc_attr($text_field));
    }
}
