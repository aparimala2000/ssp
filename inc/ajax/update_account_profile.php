<?php
// update_account_profile.php

// Include the WooCommerce functions
$load_url = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
include $load_url[0] . 'wp-load.php';

// Get the current user ID
$user_id = get_current_user_id();

// Check if the user is logged in and the user ID is valid
if ($user_id > 0) {
    // Check if the required fields are present in the POST data
    if (isset($_POST['account_first_name']) && isset($_POST['account_last_name']) && isset($_POST['telephone']) && isset($_POST['account_email'])) {
        // Sanitize the input data
        $account_first_name = sanitize_text_field($_POST['account_first_name']);
        $account_last_name = sanitize_text_field($_POST['account_last_name']);
        $telephone = sanitize_text_field($_POST['telephone']);
        $account_email = sanitize_email($_POST['account_email']);
        // Get the current user ID
        $current_user = wp_get_current_user();
        $current_user_id = $current_user->ID;

        // Perform database query to check if email or phone number exists
        $user_by_phone = get_users(array('meta_key' => 'phone', 'meta_value' => $telephone, 'meta_compare' => '='));
        $user_by_email = get_user_by('email', $account_email);

        foreach ($user_by_phone as $user) {
            $user_id = $user->ID;
            if ($user_id !== $current_user_id) {
                echo 'exists'; // Account with the provided phone number already exists
                exit; // Exit the script after finding a match
            }
        }

        if ($user_by_email && $user_by_email->ID !== $current_user_id) {
            echo 'exists'; // Account with the provided email already exists
            exit; // Exit the script
        }
        // Update user meta fields in WooCommerce
        update_user_meta($user_id, 'first_name', $account_first_name);
        update_user_meta($user_id, 'last_name', $account_last_name);
        update_user_meta($user_id, 'phone', $telephone);
        wp_update_user(array('ID' => $user_id, 'user_email' => $account_email)); // Update user email

        // Return a success response
        echo 'Profile updated successfully.';
    } else {
        // Return an error response if required fields are missing
        echo 'Missing required fields.';
    }
} else {
    // Return an error response if the user is not logged in
    echo 'User not logged in.';
}
