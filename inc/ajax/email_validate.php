<?php
// Assuming you have included the necessary WordPress files
$load_url = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
include $load_url[0] . 'wp-load.php';

 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $account_email = sanitize_email($_POST['account_email']);
    // $telephone = sanitize_text_field($_POST['telephone']);

    // Perform database query to check if email or phone number exists
    $user_by_email = get_user_by('email', $account_email);
    // $user_by_phone = get_users(array('meta_key' => 'phone', 'meta_value' => $telephone, 'meta_compare' => '='));


    if ($user_by_email) {
        $current_user = wp_get_current_user();
        $current_user_id = $current_user->ID;

        if ($user_by_email->ID !== $current_user_id) {
            echo 'exists'; // Account with the provided email already exists
            exit; // Exit the script after finding a match
        }
    } else {
        echo 'not-exists'; // No account exists with the provided email or phone number
    }
} else {
    echo 'invalid-request'; // Invalid request method
}
?>
