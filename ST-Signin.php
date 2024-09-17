<?php

/**********
Template Name:Sign-In Form 
 **********/
if (is_user_logged_in()) {
  wp_redirect('my-account');
}
get_header('sub');
$id = get_the_ID();
$template = get_post_meta($id, '_wp_page_template', true);
?>
<div class="container">
  <div class="row mb-5">
    <div class="col-xl-9 mx-auto">
      <div class="row">
        <div class="col-md-7 mx-auto">
          <?php echo apply_filters('the_content', $post->post_content); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>

