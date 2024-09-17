<?php // Add a custom metabox to the product editor screen
function product_type() {
    add_meta_box(
        'product_type', // Unique ID for the metabox
        'Product Type',   // Metabox title
        'display_product_type', // Callback function to display the metabox content
        'product', // The post type where the metabox should appear (assuming 'product' is the post type for products)
        'normal', // Metabox placement (options: 'normal', 'advanced', 'side')
        'high'    // Metabox priority (options: 'high', 'core', 'default', 'low')
    );
}
add_action('add_meta_boxes', 'product_type');

// Display the content of the custom metabox
function display_product_type($post) {
    // Retrieve the current value of the field (if any)
    $product_type_val = get_post_meta($post->ID, 'product_type_fetch', true);

    // Nonce field to validate form request
    wp_nonce_field('product_type_nonce', 'product_type_nonce_field');

    // Output the field
    ?> 
    <input type="text" id="product_type_field" name="product_type_field" value="<?php echo esc_attr($product_type_val); ?>" />
    <?php
}

// Save the custom field value when the product is saved
function save_product_type($post_id) {
    // Check if the user has permission to save the data
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Verify the nonce
    if (!isset($_POST['product_type_nonce_field']) || !wp_verify_nonce($_POST['product_type_nonce_field'], 'product_type_nonce')) {
        return;
    }

    // Save the data
    if (isset($_POST['product_type_field'])) {
        update_post_meta($post_id, 'product_type_fetch', sanitize_text_field($_POST['product_type_field']));
    }
}
add_action('save_post', 'save_product_type');