<?php
   add_action( 'add_meta_boxes', function() {
      add_meta_box( 'custom-metabox', 'Special shipping price', 'fill_metabox', 'product', 'normal' );
   });
   function fill_metabox( $post ) {
     wp_nonce_field( basename(__FILE__), 'mam_nonce' );
     $allCountries = WC()->countries->get_shipping_countries();
     $ship_cnt_val = get_post_meta($post->ID, 'ship_cnt_val', true );
   ?>
<table>
<tr>
   <?php   
      $allCountries = WC()->countries->get_shipping_countries();
      $count = 0;
      foreach ($allCountries as $country_code => $country) { 
        $ship_cnt_val = get_post_meta($post->ID, 'ship_cnt_val', true );
      ?>
        <tr>
           <td><?php echo $country;?></td><td><input  type="text" name="multval[]" value="<?php echo $ship_cnt_val[$count]; ?>"  /></td>
        </tr>
     <?php
       $count++;
      }
      ?>
    <?php }
       add_action( 'save_post', function( $post_id ) {
       $is_autosave = wp_is_post_autosave( $post_id );
       $is_revision = wp_is_post_revision( $post_id );
       $is_valid_nonce = ( isset( $_POST[ 'mam_nonce' ] ) && wp_verify_nonce( $_POST[ 'mam_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
       if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
           return;
       }
       // If the checkbox was not empty, save it as array in post meta
       if ( ! empty( $_POST['multval'] ) ) {
        $allCountries = WC()->countries->get_shipping_countries();
        foreach ($allCountries as $country_code => $country) { 
           update_post_meta( $post_id, 'ship_cnt_val', $_POST['multval'] );
        }
   
       // Otherwise just delete it if its blank value.
       }
   });
   
   ?>