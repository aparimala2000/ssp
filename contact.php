<?php

/*****
Template Name: Contact Us
 *****/
get_header('sub');
global $post;
$post_id = $post->ID;
$post_excerpt = get_the_excerpt($post->ID);
$post_content = get_the_content($post->ID);
$post_title = get_the_title($post->ID);
?>
<!-- form start -->
<script src="https://www.google.com/recaptcha/api.js?render=6Ld4usImAAAAAMyMuOmge7lXZ_WNcqaLQUM73tpP"></script> <!-- site key -->
<div class="container">
    <div class="mid-container mx-auto mb-5">
        <div>
            <div class="row justify-content-center">
                <div class="col-lg-6 mb-50">

                    <div class="mb-40">
                        <h3><?php echo $post_title; ?></h3>
                        <?php echo apply_filters('the_content', $post_content); ?>
                    </div>
                    <form method="POST" id="contact_form">
                    <?php wp_nonce_field('contact_form_submission', 'contact_form_nonce'); ?>
			 <input type="hidden" id="contact_form_nonce" name="contact_form_nonce" value="generated_nonce_value_here">
                        <div class="floating-blk">
                            <label for="fname" class="floating-row">
                                <span class="floating-label">First Name</span>
                                <input class="floating-input" type="text" id="fname" />
                                <p id="err_name" class="floating-input-error" style="display: none;">Please tell us your first name.</p>
                            </label>
                        </div>
                        <div class="floating-blk">
                            <label for="lname" class="floating-row">
                                <span class="floating-label">Last Name</span>
                                <input class="floating-input" type="text" id="lname" />
                                <p id="err_lname" class="floating-input-error" style="display: none;">Please tell us your last name.</p>
                            </label>
                        </div>
                        <div class="floating-blk">
                            <label for="email" class="floating-row">
                                <span class="floating-label">Email</span>
                                <input class="floating-input" type="Email" id="email" />
                                <p id="err_email" class="floating-input-error" style="display: none;">Please provide your email.</p>
                            </label>
                        </div>
                        <div class="floating-blk">
                            <label for="phonenumber" class="floating-row">
                                <span class="floating-label">Phone Number</span>
                                <input class="floating-input" type="number" id="phonenumber" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                                <p id="err_phone" class="floating-input-error" style="display: none;">Please let us have your phone number.</p>
                            </label>
                        </div>
                        <div class="floating-blk">
                            <label for="message" class="floating-row">
                                <span class="floating-label">Message</span>
                                <textarea class="floating-input" type="" name="message" id="message" rows="6"></textarea>
                                <p id="err_msg" class="floating-input-error" style="display: none;">Here’s where you enter your message to us.</p>
                            </label>
                        </div>

                    </form>
                    <a href="javascript:void(0);" class="button g-recaptcha mb mt-2" id="contact_btn">Submit</a>

                </div>
                <?php 
                $address = get_field("address");
                ?>
                <div class="col-lg-6">
                    <div class="cart-blk contact  mx-auto ld"> 
                        <?php echo $address; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- form end -->
<?php get_footer(); ?>