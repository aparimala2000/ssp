 <?php
    $load_url = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
    include $load_url[0] . 'wp-load.php';
    require_once(ABSPATH . 'wp-admin/includes/user.php');
    global $wpdb;
    global $woocommerce;
    $username = $_POST['lginUname'];
    $password = $_POST['lginPwd'];
    $email = $_POST['regEmail'];
    $phone = $_POST['regPhone'];
    $creds['user_login'] = $username;
    $creds['user_password'] = $password;
    $creds['remember'] = isset($_POST['rememberme']);
    $secure_cookie = is_ssl() ? true : false;
    if (is_numeric($username)) {
            $users_phone_args = get_users(array(
                'meta_key' => 'phone',
                'meta_value' => $username,
                'meta_compare' => '==',
            ));
            foreach ($users_phone_args as $key => $users_phone) {
                $userPhoneId = $users_phone->ID;
            }
                if(!empty($userPhoneId)){
                $usrVerf = get_user_meta($userPhoneId, 'otp_verified', true);
                $adminuser = get_user_role($userPhoneId);
                $secure_cookie = is_ssl() ? true : false;
                $otpDate = date("Y-m-d H:i:s");
                $randOtpLogin = rand(0, 999999);
                $login_otp = $randOtpLogin . '|' . $otpDate;
                if ($usrVerf == 'Yes' || ($adminuser == 'administrator')) {
                // wp_set_auth_cookie($userPhoneId);
                // do_action('wp_login', $username, $userPhoneId);
                update_user_meta($userPhoneId, 'login_otp', $login_otp);
                $retOtpSuc = otp_snd($username, $randOtpLogin);
                $user_info = get_userdata($userPhoneId);
                $user_email = $user_info->user_email;
                $message = '<html>
                                <body>
                                    <div style="max-width:500px">
                                        <p>Hello,<br /><br />
                                            Your OTP for your transaction at SSP is ' . $randOtpLogin . '. Use this within the next 20 minutes to register/log-in to the SSP website and continue your purchase. Bon Appetit!<br /><br />
                                            Please do not share this OTP with anyone.<br /><br />
                                            Warm Regards<br />
                                            S S Pandian & Sons<br />
                                        </p>
                                    </div>
                                </body>';
                $subject = "SSP - OTP for Login";
                $to = $user_email;
                $from = "SSP-HING <info@ssp.com>";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                $headers .= "From: " . $from . "\r\n";
                wp_mail($to, $subject, $message, $headers);
                 echo '2|' . $userPhoneId;
                    exit();
                } 
                elseif (($usrVerf == 'No') && ($adminuser!='administrator')) {
                    wp_delete_user($userPhoneId);
                    delete_user_meta($userPhoneId, 'first_name');
                    delete_user_meta($userPhoneId, 'last_name');
                    delete_user_meta($userPhoneId, 'phone');
                    delete_user_meta($userPhoneId, 'reg_otp');
                    delete_user_meta($userPhoneId, 'otp_verified');
                $customer_id = guest_create_new_customer($email, $phone, $username, NULL, 'guest');
                if (isset($_POST['fname'])) {
                    // WordPress default first name field.
                    update_user_meta(
                        $customer_id,
                        'first_name',
                        sanitize_text_field($fname)
                    );
                }
                if (isset($_POST['lname'])) {
                    // WordPress default last name field.
                    update_user_meta(
                        $customer_id,
                        'last_name',
                        sanitize_text_field($lname)
                    );
                }
                if (isset($_POST['regPhone'])) {
                    // WooCommerce billing phone
                    update_user_meta(
                        $customer_id,
                        'phone',
                        sanitize_text_field($phone)
                    );
                }
                $otpDate = date("Y-m-d H:i:s");
                $randOtpReg = rand(100000, 999999);
                $reg_otp = $randOtpReg . '|' . $otpDate;
                if ($reg_otp != '') {
                    update_user_meta($customer_id, 'reg_otp', $reg_otp);
                    update_user_meta($customer_id, 'otp_verified', 'No');
                    // update_user_meta($customer_id, 'guest_flag', '1');
                } else {
                    echo "Cannot register otp";
                }
                if (!is_wp_error($customer_id)) {
                    $retOtpSuc = otp_snd($phone, $randOtpReg);
                    $subject = "SSP - OTP for Registration";
                    $to = 'mekala@madebyfire.com';
                    $from = "SSP-HING <info@ssp.com>";
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                    $headers .= "From: " . $from . "\r\n";
                    $message = '<html>
                    <body>
                        <div style="max-width:500px">
                            <p>Hello,<br /><br />
                                Your OTP for your transaction at SSP is ' . $randOtpReg . '. Use this within the next 20 minutes to register/log-in to the SSP website and continue your purchase. Bon Appetit!<br /><br />
                                Please do not share this OTP with anyone.<br /><br />
                                Warm Regards<br />
                                S S Pandian & Sons<br />
                            </p>
                        </div>
                    </body></html>';
                    wp_mail($to, $subject, $message, $headers);
                    echo '1|' . $customer_id;
                    do_action('woocommerce_created_customer', $customer_id);
                    // exit();
                }
                }
                exit();
            }
            else{
            $customer_id = guest_create_new_customer($email, $phone, $username, NULL, 'guest');
            if (isset($_POST['fname'])) {
                // WordPress default first name field.
                update_user_meta(
                    $customer_id,
                    'first_name',
                    sanitize_text_field($fname)
                );
            }
            if (isset($_POST['lname'])) {
                // WordPress default last name field.
                update_user_meta(
                    $customer_id,
                    'last_name',
                    sanitize_text_field($lname)
                );
            }
            if (isset($_POST['regPhone'])) {
                // WooCommerce billing phone
                update_user_meta(
                    $customer_id,
                    'phone',
                    sanitize_text_field($phone)
                );
            }
            $otpDate = date("Y-m-d H:i:s");
            $randOtpReg = rand(100000, 999999);
            $reg_otp = $randOtpReg . '|' . $otpDate;
            if ($reg_otp != '') {
                update_user_meta($customer_id, 'reg_otp', $reg_otp);
                update_user_meta($customer_id, 'otp_verified', 'No');
                // update_user_meta($customer_id, 'guest_flag', '1');
            } else {
                echo "Cannot register otp";
            }
            // echo 'Not an account - A user create with this phone number.';
            if (!is_wp_error($customer_id)) {
                $retOtpSuc = otp_snd($phone, $randOtpReg);
                $subject = "SSP - OTP for Registration";
                $to = 'mekala@madebyfire.com';
                $from = "SSP-HING <info@ssp.com>";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                $headers .= "From: " . $from . "\r\n";
                $message = '<html>
                    <body>
                        <div style="max-width:500px">
                            <p>Hello,<br /><br />
                                Your OTP for your transaction at SSP is ' . $randOtpReg . '. Use this within the next 20 minutes to register/log-in to the SSP website and continue your purchase. Bon Appetit!<br /><br />
                                Please do not share this OTP with anyone.<br /><br />
                                Warm Regards<br />
                                S S Pandian & Sons<br />
                            </p>
                        </div>
                    </body></html>';
                wp_mail($to, $subject, $message, $headers);
                echo '1|' . $customer_id;
                do_action('woocommerce_created_customer', $customer_id);
                // exit();
            }
            }
            exit();
    } 
    else if(!is_numeric($username)){
        $userArgs = get_user_by('email', $username);
        $notVerAcc = email_exists($username);
        $userPhoneId = $notVerAcc;
        if (!empty($userPhoneId)) {
            $usrVerf = get_user_meta($userPhoneId, 'otp_verified', true);
            $adminuser = get_user_role($userPhoneId);
            $secure_cookie = is_ssl() ? true : false;
            $otpDate = date("Y-m-d H:i:s");
            $randOtpLogin = rand(0, 999999);
            $login_otp = $randOtpLogin . '|' . $otpDate;
            if ($usrVerf == 'Yes' || ($adminuser == 'administrator')) {
                // wp_set_auth_cookie($userPhoneId);
                // do_action('wp_login', $username, $userPhoneId);
                update_user_meta($userPhoneId, 'login_otp', $login_otp);
                $retOtpSuc = otp_snd($username, $randOtpLogin);
                $user_info = get_userdata($userPhoneId);
                $user_email = $user_info->user_email;
                $message = '<html>
                                <body>
                                    <div style="max-width:500px">
                                        <p>Hello,<br /><br />
                                            Your OTP for your transaction at SSP is ' . $randOtpLogin . '. Use this within the next 20 minutes to register/log-in to the SSP website and continue your purchase. Bon Appetit!<br /><br />
                                            Please do not share this OTP with anyone.<br /><br />
                                            Warm Regards<br />
                                            S S Pandian & Sons<br />
                                        </p>
                                    </div>
                                </body>';
                $subject = "SSP - OTP for Login";
                $to = $user_email;
                $from = "SSP-HING <info@ssp.com>";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                $headers .= "From: " . $from . "\r\n";
                wp_mail($to, $subject, $message, $headers);
                echo '2|' . $userPhoneId;
                exit();
            } elseif (($usrVerf == 'No') && ($adminuser != 'administrator')) {
                wp_delete_user($userPhoneId);
                delete_user_meta($userPhoneId, 'first_name');
                delete_user_meta($userPhoneId, 'last_name');
                delete_user_meta($userPhoneId, 'phone');
                delete_user_meta($userPhoneId, 'reg_otp');
                delete_user_meta($userPhoneId, 'otp_verified');
                $customer_id = guest_create_new_customer_email($email, $phone, $username, NULL, 'guest');
                if (isset($_POST['fname'])) {
                    // WordPress default first name field.
                    update_user_meta(
                        $customer_id,
                        'first_name',
                        sanitize_text_field($fname)
                    );
                }
                if (isset($_POST['lname'])) {
                    // WordPress default last name field.
                    update_user_meta(
                        $customer_id,
                        'last_name',
                        sanitize_text_field($lname)
                    );
                }
                if (isset($_POST['regPhone'])) {
                    // WooCommerce billing phone
                    update_user_meta(
                        $customer_id,
                        'phone',
                        sanitize_text_field($phone)
                    );
                }
                $otpDate = date("Y-m-d H:i:s");
                $randOtpReg = rand(100000, 999999);
                $reg_otp = $randOtpReg . '|' . $otpDate;
                if ($reg_otp != '') {
                    update_user_meta($customer_id, 'reg_otp', $reg_otp);
                    update_user_meta($customer_id, 'otp_verified', 'No');
                    // update_user_meta($customer_id, 'guest_flag', '1');
                } else {
                    echo "Cannot register otp";
                }
                if (!is_wp_error($customer_id)) {
                    $subject = "SSP - OTP for Registration";
                    $to = $email;
                    $from = "SSP-HING <info@ssp.com>";
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                    $headers .= "From: " . $from . "\r\n";
                    $message = '<html>
                    <body>
                        <div style="max-width:500px">
                            <p>Hello,<br /><br />
                                Your OTP for your transaction at SSP is ' . $randOtpReg . '. Use this within the next 20 minutes to register/log-in to the SSP website and continue your purchase. Bon Appetit!<br /><br />
                                Please do not share this OTP with anyone.<br /><br />
                                Warm Regards<br />
                                S S Pandian & Sons<br />
                            </p>
                        </div>
                    </body></html>';
                    wp_mail($to, $subject, $message, $headers);
                    echo '1|' . $customer_id;
                    do_action('woocommerce_created_customer', $customer_id);
                    // exit();
                }
            }
            exit();
        } else {
            $customer_id = guest_create_new_customer_email($email, $phone, $username, NULL, 'guest');
            if (isset($_POST['fname'])) {
                // WordPress default first name field.
                update_user_meta(
                    $customer_id,
                    'first_name',
                    sanitize_text_field($fname)
                );
            }
            if (isset($_POST['lname'])) {
                // WordPress default last name field.
                update_user_meta(
                    $customer_id,
                    'last_name',
                    sanitize_text_field($lname)
                );
            }
            if (isset($_POST['regPhone'])) {
                // WooCommerce billing phone
                update_user_meta(
                    $customer_id,
                    'phone',
                    sanitize_text_field($phone)
                );
            }
            $otpDate = date("Y-m-d H:i:s");
            $randOtpReg = rand(100000, 999999);
            $reg_otp = $randOtpReg . '|' . $otpDate;
            if ($reg_otp != '') {
                update_user_meta($customer_id, 'reg_otp', $reg_otp);
                update_user_meta($customer_id, 'otp_verified', 'No');
                // update_user_meta($customer_id, 'guest_flag', '1');
            } else {
                echo "Cannot register otp";
            }
            // echo 'Not an account - A user create with this Email address.';
            if (!is_wp_error($customer_id)) {
                $to = $email;
                $subject = "SSP - OTP for Registration";
                $from = "SSP-HING <info@ssp.com>";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                $headers .= "From: " . $from . "\r\n";
                $message = '<html>
                    <body>
                        <div style="max-width:500px">
                            <p>Hello,<br /><br />
                                Your OTP for your transaction at SSP is ' . $randOtpReg . '. Use this within the next 20 minutes to register/log-in to the SSP website and continue your purchase. Bon Appetit!<br /><br />
                                Please do not share this OTP with anyone.<br /><br />
                                Warm Regards<br />
                                S S Pandian & Sons<br />
                            </p>
                        </div>
                    </body></html>';
                (wp_mail($to, $subject, $message, $headers));
                echo '1|' . $customer_id;
                do_action('woocommerce_created_customer', $customer_id);
                // exit();
            }
        }
        exit();
    }

