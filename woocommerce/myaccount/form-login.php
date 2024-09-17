<?php

/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 7.0.1
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>
<?php do_action('woocommerce_before_customer_login_form'); ?>

<?php if (get_option('woocommerce_enable_myaccount_registration') === 'yes') : ?>
	<div class="u-columns col2-set" id="customer_login">
		<div class="u-column1 col-1">
		<?php endif; ?>
		<?php
		$id = get_the_ID();
		$template = get_post_meta($id, '_wp_page_template', true);
		?>
		<?php if ($template == 'ST-Signin.php') {
		?>
			<?php
			echo do_shortcode('[otp_login_form]');
			?>
			<input type="hidden" id="logUserID" value="">
			<?php
			echo do_shortcode('[otp_verify_lgin]');
			?>
		</div>
		</form>
	<?php } ?>
	</div>
	<div class="u-column2">
		<?php
		if ($template == 'ST-Signup.php') {
		?>
			<?php
			echo do_shortcode('[new_user_reg_form]');
			?>
			<input type="hidden" value="" id="reg_customer_id" />
			<?php
			echo do_shortcode('[otp_verify_reg]');
			?>

		<?php } ?>
	</div>
	<?php do_action('woocommerce_after_customer_login_form'); ?>
