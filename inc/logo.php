 <?php  // create custom plugin settings menu
	add_action('admin_menu', 'logo_template');
	function logo_template()
	{
		$themepage = add_menu_page('Site Options', 'Site Options', 'moderate_comments', 'logo-settings', 'logo_settings_form');

		//call register settings function
		add_action('admin_init', 'logo_site_settings');
		add_action('admin_print_styles-' . $themepage, 'logo_admin_styles');
	}
	function logo_site_settings()
	{
		$settings_val = array('Logo_image', 'cancel_option', 'popup_message','popup_icon', 'cookie_title', 'cookie_content');

		foreach ($settings_val as $set)
			register_setting('logo-settings-group', $set);
	}

	function logo_admin_styles()
	{
		wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('farbtastic');
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_media();
	}

	function logo_settings_form()
	{
		get_template_part('inc/upload-scripts'); ?>
 	<div class="wrap">
 		<p style="text-align: center;">
 		<h2>Site Options</h2>
 		</p>
 		<form class="site-setting-form" method="post" id="point-settings" name="logo-settings" action="options.php">
 			<?php settings_fields('logo-settings-group'); ?>
 			<div class="settings-container">
 				<ul class='k2b-tabs'>
 				</ul>
 				<div class="set_tab">
 					<div class="tab-wrapper">
 						<table class="form-table">
 							<?php
								echo get_admin_input('up_image', 'Logo_image', 'Logo Image', get_option('Logo_image'), ''); ?>
 						</table>
 					</div>
 					<div class="tab-wrapper">
 						<table class="form-table">
 							<?php
								echo get_admin_input('text', 'cancel_option', 'Order Cancel Duration (In Days)', get_option('cancel_option'), ''); ?>
 						</table>
 					</div>
 					<div class="tab-wrapper">
 						<table class="form-table">
 							<?php
								echo get_admin_input('editor', 'popup_message', 'Popup Message', get_option('popup_message'), ''); ?>
 						</table>
 					</div>
 					<div class="tab-wrapper">
 						<table class="form-table">
 							<?php
								echo get_admin_input('up_image', 'popup_icon', 'Popup Icon', get_option('popup_icon'), ''); ?>
 						</table>
 					</div>
 					<div class="tab-wrapper">
 						<table class="form-table">
 							<?php
								echo get_admin_input('text', 'cookie_title', 'Cookie Title', get_option('cookie_title'), ''); ?>
 						</table>
 					</div>
 					<div class="tab-wrapper">
 						<table class="form-table">
 							<?php
								echo get_admin_input('editor', 'cookie_content', 'Cookie Content', get_option('cookie_content'), ''); ?>
 						</table>
 					</div>
 					<br />
 					<p class="submit" style=" text-align: center;"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" name="submit-settings" /></p>
 				</div>
 				<!-- settings-container -->
 		</form>
 	</div><!-- wrap -->
 <?php }
