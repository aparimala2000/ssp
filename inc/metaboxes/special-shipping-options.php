<?php
function shipping_option_choose() {
add_meta_box('shipping_option_choose', 'Special Shipping', 'shipping_option_choose_meta', 'product' ); //you can change the 4th paramter i.e. post to custom post type name, if you want it for something else

}

add_action( 'add_meta_boxes', 'shipping_option_choose' );
function shipping_option_choose_meta( $post ) {
 wp_nonce_field( 'shipping_option_choose', 'shipping_option_choose_nonce' );
 $value = get_post_meta( $post->ID, 'shipping_option_choose', true ); //shipping_option_choose is a meta_key. Change it to whatever you want
?>
<input type="radio" name="special_ship" value="disable" <?php checked( $value, 'disable' ); ?> >
Disable
<input type="radio" name="special_ship" value="enable" <?php checked( $value, 'enable' ); ?> >
Enable<br>
<?php
}

function save_shipping_options( $post_id ) {

        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( !isset( $_POST['shipping_option_choose_nonce'] ) ) {
                return;
        }

        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['shipping_option_choose_nonce'], 'shipping_option_choose' ) ) {
                return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
        }

        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
                return;
        }


        // Sanitize user input.
        $new_meta_value = ( isset( $_POST['special_ship'] ) ? sanitize_html_class( $_POST['special_ship'] ) : '' );

        // Update the meta field in the database.
        update_post_meta( $post_id, 'shipping_option_choose', $new_meta_value );

}

add_action( 'save_post', 'save_shipping_options' );


