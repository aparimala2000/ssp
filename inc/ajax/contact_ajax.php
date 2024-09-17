<?php
// Conatct Ajax
function contact_ajax()
{
    global $wpdb;
    if (isset($_POST['contact_form_nonce']) && wp_verify_nonce($_POST['contact_form_nonce'], 'contact_form_submission')) {
    if ($_POST['contact_us_form'] != "" && $_POST['gRecaptchaResponse'] != "") {
        $captcha_response = $_POST['gRecaptchaResponse'];
        $gcaptcha_secret = '6Ld4usImAAAAAFJvm_JzMHsnJk31mzNZDwDW0EEU';
        $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $gcaptcha_secret . "&response=" . $captcha_response . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
        $firstname = (isset($_POST['fname']) ? sanitize_text_field($_POST['fname']) : '');
        $lastname = (isset($_POST['lname']) ? sanitize_text_field($_POST['lname']) : '');
        $email = (isset($_POST['email']) ? sanitize_email($_POST['email']) : '');
        $phone = (isset($_POST['phonenumber']) ? sanitize_text_field($_POST['phonenumber']) : '');
        $messages = (isset($_POST['message']) ? wp_kses($_POST['message'], 'post'): '');
        $admin_mail = get_option('admin_mail');
        $adminmsg = get_option('after_register_admin');
        if ($response['success'] == true && $response['score'] >= 0.7) {
            $query =  "INSERT INTO `wp_contact`( `firstname`,`lastname`, `email`, 
  `phone`, `messages`, 
  `posted_date`) VALUES ('" . $firstname . "','" . $lastname . "','" . $email . "','" . $phone . "','" . $messages . "','" . date('Y-m-d H:i:s') . "')";
            $row =  $wpdb->query($query);
            if ($row) {
                $admin_messages = '
    <html>
    <body>
      <div style="max-width:500px">
         <p>Dear Admin,<br /><br />
           An email messages has just been sent by (' . $email . ')<br />
           ---- messages ----<br /><br />
           Name - ' . $firstname . ' <br />
           Email - ' . $email . ' <br />
           Phone - ' . $phone . ' <br />
           Your messages - ' . $messages . ' <br />
       </p>
   </div>
</body>
</html>
';
                $sendermessages = '
<html>
<body>
    <div style="max-width:560px;font-size:14px;">
       <p style="text-transform: capitalize;">Dear ' . $firstname . ',
       </br/>
       ' . $adminmsg . '
   </br/>
</div>
</body>
</html>
';
                $subject = "We got your messages!";
                $sender = $email;
                $admin_subject = "New Customer Application";
                $from = "parimala@madebyfire.com";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                $headers .= "From: " . $from . "\r\n";
                //   Email Trigger After aa successful form submission
                (wp_mail($sender, $subject, $sendermessages, $headers));
                (wp_mail($admin_mail, $admin_subject, $admin_messages, $headers));
                echo 1;
                exit();
            }
        } else {
            echo 2;
            exit();
        }
        exit();
    }
     } else {
    // Nonce is not valid, handle the error
    echo 'Nonce verification failed. Please try again.';
}
}
add_action('wp_ajax_contact_ajax', 'contact_ajax');
add_action('wp_ajax_nopriv_contact_ajax', 'contact_ajax');
