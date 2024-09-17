 <?php

    /******
Template Name: My Account
     ******/  
if (!is_user_logged_in()) {
	wp_redirect('sign-in');
}
get_header('sub');
?>
 
	<?php echo apply_filters('the_content', wpautop($post->post_content)); ?>
 
<?php get_footer(); ?>