<?php

/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;
$current_user = get_current_user_id();
do_action('woocommerce_before_edit_account_form'); ?>
<div class="container">
	<div class="row mb-5">
		<div class="col-xl-9 mx-auto">
			<div class="row">
				<div class="col-md-7 mx-auto">
					<div class="mb-40">
						<h3>Account Information</h3>
						<p>You may edit your account here, if you need to.</p>
					</div>
					<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?>>

						<?php do_action('woocommerce_edit_account_form_start'); ?>

						<div class="floating-blk">
							<label for="account_first_name" class="floating-row">
								<span class="floating-label">First Name</span>
								<input class="floating-input validate" type="text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr($user->first_name); ?>" />
								<p id="err_account_first_name" class="floating-input-error">Please tell us your first name.</p>
							</label>
						</div>
						<div class="floating-blk">
							<label for="account_last_name" class="floating-row">
								<span class="floating-label">Last Name</span>
								<input class="floating-input validate" type="text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr($user->last_name); ?>" />
								<p id="err_account_last_name" class="floating-input-error">Please tell us your last name.</p>
							</label>
						</div>
						<div class="floating-blk">
							<label for="telephone" class="floating-row">
								<span class="floating-label">Phone Number</span>
								<input class="floating-input validate" type="number" name="telephone" id="telephone" value="<?php echo esc_attr(get_user_meta($current_user, 'phone', true)); ?>" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
								<p id="err_telephone" class="floating-input-error">Please let us have your phone number.</p>
							</label>
						</div>
						<div class="floating-blk">
							<label for="account_email" class="floating-row">
								<span class="floating-label">Email</span>
								<input class="floating-input" type="Email" name="account_email" id="account_email" value="<?php echo esc_attr($user->user_email); ?>" />
								<p id="err_account_email" class="floating-input-error">Please enter your email.</p>
							</label>
						</div>
						<div class="clear"></div>

						<?php do_action('woocommerce_edit_account_form'); ?>

						<p>
							<?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
							<button type="submit" id="update_account" class="woocommerce-Button button mb mt-2<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="save_account_details" value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>"><?php esc_html_e('Save changes', 'woocommerce'); ?></button>
							<input type="hidden" name="action" value="save_account_details" />
						</p>

						<?php do_action('woocommerce_edit_account_form_end'); ?>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php do_action('woocommerce_after_edit_account_form'); ?>