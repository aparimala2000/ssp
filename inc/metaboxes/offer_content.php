<?php 
// Add the metabox
function add_offer_content_metabox() {
    add_meta_box(
        'offer_content_metabox',
        'Offer Content',
        'render_offer_content_metabox',
        'product', // Adjust the post type as needed
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_offer_content_metabox');

// Render the metabox content
function render_offer_content_metabox($post) {
    // Retrieve the current value of the offer content
    $offer_content = get_post_meta($post->ID, 'offer_content', true);
    
    // Output the HTML for the metabox
    ?> 
    <input type="text" id="offer_content" name="offer_content" value="<?php echo esc_attr($offer_content); ?>" style="width: 100%;" />
    <?php
}

// Save the metabox data
function save_offer_content_metabox($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
        return $post_id;

    if (isset($_POST['offer_content'])) {
        $offer_content = sanitize_text_field($_POST['offer_content']);
        update_post_meta($post_id, 'offer_content', $offer_content);
    }
}
add_action('save_post', 'save_offer_content_metabox');
