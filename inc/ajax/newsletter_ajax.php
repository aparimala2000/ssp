
<?php
// Newsletter ajax
function newsletter_ajax()
{
    global $wpdb;
    $email = (isset($_POST['email']) ? $_POST['email'] : '');

    $query =  "INSERT INTO `wp_newsletter`( `email`,
  `postdate`) VALUES ('" . $email . "','" . date('Y-m-d H:i:s') . "')";
    $row =  $wpdb->query($query);
    if ($row) {

        echo 1;
        exit();
    } else {
        echo 2;
        exit();
    }
    exit();
}
add_action('wp_ajax_newsletter_ajax', 'newsletter_ajax');
add_action('wp_ajax_nopriv_newsletter_ajax', 'newsletter_ajax');
