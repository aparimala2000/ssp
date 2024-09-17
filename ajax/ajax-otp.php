<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$load_url = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
include $load_url[0] . 'wp-load.php';
//ini_set("display_errors", 1);
require_once(ABSPATH . 'wp-admin/includes/user.php');
$req_action = filter_input(INPUT_POST, 'action');
switch ($req_action) {
    case "ajax_regg";
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $notVerAcc = email_exists($email);
        $phoneChk = phone_exists($phone);

        $usrVerf_email = $notVerAcc == false ? $notVerAcc : get_user_meta($notVerAcc, 'otp_verified', true);
        $usrVerf_phone = $phoneChk == false ? $phoneChk : get_user_meta($phoneChk, 'otp_verified', true);

        if (($usrVerf_email == false || $usrVerf_phone == false) && ($usrVerf_email != 'Yes' && $usrVerf_phone != 'Yes')) {
            $adminuser = get_user_role($notVerAcc);

            if ($notVerAcc != false && ($adminuser!= 'administrator')) {
                wp_delete_user($notVerAcc);
                delete_user_meta($notVerAcc, 'first_name');
                delete_user_meta($notVerAcc, 'last_name');
                delete_user_meta($notVerAcc, 'phone');
                delete_user_meta($notVerAcc, 'reg_otp');
                delete_user_meta($notVerAcc, 'otp_verified');
            } elseif ($phoneChk != false && ($adminuser != 'administrator')) {

                wp_delete_user($phoneChk);
                delete_user_meta($phoneChk, 'first_name');
                delete_user_meta($phoneChk, 'last_name');
                delete_user_meta($phoneChk, 'phone');
                delete_user_meta($phoneChk, 'reg_otp');
                delete_user_meta($phoneChk, 'otp_verified');
            }
            $customer_id = extnd_create_new_customer($email, $phone, $username, NULL);
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
            if (isset($_POST['phone'])) {
                // WooCommerce billing phone
                update_user_meta(
                    $customer_id,
                    'phone',
                    sanitize_text_field($phone)
                );
            }
            $otpDate = date("Y-m-d H:i:s");
            $randOtpReg = rand(0, 999999);
            $login_otp = $randOtpLogin . '|' . $otpDate;

            $reg_otp = $randOtpReg . '|' . $otpDate;
            if ($reg_otp != '') {
                update_user_meta($customer_id, 'reg_otp', $reg_otp);
                update_user_meta($customer_id, 'otp_verified', 'No');
            } else {
                echo "Cannot register otp";
            }
        } else {
            $customer_id = extnd_create_new_customer($email, $phone, $username, NULL);
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



            if (isset($_POST['phone'])) {
                // WooCommerce billing phone
                update_user_meta($customer_id, 'phone', sanitize_text_field($phone));
            }
            $otpDate = date("Y-m-d H:i:s");
            $randOtpReg = rand(0, 999999);
            $login_otp = $randOtpLogin . '|' . $otpDate;

            $reg_otp = $randOtpReg . '|' . $otpDate;
            if ($reg_otp != '') {
                update_user_meta($customer_id, 'reg_otp', $reg_otp);
                update_user_meta($customer_id, 'otp_verified', 'No');
            } else {
                echo "Cannot register otp";
            }
        }

        if (!is_wp_error($customer_id)) {
            $retOtpSuc = otp_snd($phone, $randOtpReg);
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
                    </body>';
            $subject = "SSP - OTP for Registration";
            $to = $email;
            $from = "SSP-HING <info@ssp.com>";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
            $headers .= "From: " . $from . "\r\n";
            wp_mail($to, $subject, $message, $headers);
            echo '1|' . $customer_id;
            do_action('woocommerce_created_customer', $customer_id);
        } else {
            echo $customer_id->get_error_message();
        }
        die();
        break;
    case "ajax_login":
        $username = $_POST['lginUname'];
        $password = $_POST['lginPwd'];
        $creds['user_login'] = $username;
        $creds['user_password'] = $password;
        $creds['remember'] = isset($_POST['rememberme']);
        $secure_cookie = is_ssl() ? true : false;
        if (is_numeric($username)) {
            if (phone_exists($username)) {
                $users_phone_args = get_users(array(
                    'meta_key' => 'phone',
                    'meta_value' => $username,
                    'meta_compare' => '==',
                ));
                foreach ($users_phone_args as $key => $users_phone) {
                    $userPhoneId = $users_phone->ID;
                    $usrVerf = get_user_meta($userPhoneId, 'otp_verified', true);
                    $adminuser = get_user_role($userPhoneId);
                    $otpDate = date("Y-m-d H:i:s");
                    $randOtpLogin = rand(0, 999999);
                    $login_otp = $randOtpLogin . '|' . $otpDate;
                    $secure_cookie = is_ssl() ? true : false;
                    if ($usrVerf == 'Yes') {
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
                        // $message = "Your OTP is " . $randOtpLogin . ". Use this within the next 20 minutes to register/log-in to the SSP website and continue your purchase. Bon Appetit!";
                        $subject = "SSP - OTP for Login";
                        $to = $user_email;
                        $from = "SSP-HING <info@ssp.com>";
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                        $headers .= "From: " . $from . "\r\n";
                        wp_mail($to, $subject, $message, $headers);
                        echo '1|' . $userPhoneId;
                    } elseif ($usrVerf == 'No' && ($adminuser != 'administrator')) {
                        // wp_delete_user($notVerAcc);
                        echo 'A user could not be found with this phone number.';
                    }
                }
            } else {
                echo 'A user could not be found with this phone number.';
            }
        } else { 
            $userArgs = get_user_by('email', $username);
            $notVerAcc = email_exists($username);
            if (!empty($notVerAcc)) {
                $usrVerf = get_user_meta($notVerAcc, 'otp_verified', true);
                $adminuser = get_user_role($notVerAcc);
                $secure_cookie = is_ssl() ? true : false;
                if (!empty($usrVerf) && ($usrVerf== 'Yes')) {
                    $randOtp = rand(0, 999999);
                    $otpDate = date("Y-m-d H:i:s");
                    $login_otp = $randOtp . '|' . $otpDate;
                    $phone = get_user_meta($notVerAcc, 'phone', true);
                    $user_info = get_userdata($notVerAcc);
                    $user_email = $user_info->user_email;
                    update_user_meta($notVerAcc, 'login_otp', $login_otp);
                    $message = '<html>
                                <body>
                                    <div style="max-width:500px">
                                        <p>Hello,<br /><br />
                                            Your OTP for your transaction at SSP is ' . $randOtp . '. Use this within the next 20 minutes to register/log-in to the SSP website and continue your purchase. Bon Appetit!<br /><br />
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
                    $retOtpSuc = otp_snd($phone, $randOtp);
                    echo '1|' . $notVerAcc;
                } elseif ($usrVerf == 'No' && $adminuser!="administrator") {
                    // wp_delete_user($notVerAcc);
                    echo 'A User could not be found with this email address.';
                    exit();
                } elseif ($adminuser == "administrator") {
                    $randOtp = rand(0, 999999);
                    $otpDate = date("Y-m-d H:i:s");
                    $login_otp = $randOtp . '|' . $otpDate;
                    $phone = get_user_meta($notVerAcc, 'phone', true);
                    $user_info = get_userdata($notVerAcc);
                    $user_email = $user_info->user_email;
                    update_user_meta($notVerAcc, 'login_otp', $login_otp);
                    $message = '<html>
                                <body>
                                    <div style="max-width:500px">
                                        <p>Hello,<br /><br />
                                            Your OTP for your transaction at SSP is ' . $randOtp . '. Use this within the next 20 minutes to register/log-in to the SSP website and continue your purchase. Bon Appetit!<br /><br />
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
                    $retOtpSuc = otp_snd($phone, $randOtp);
                    echo '1|' . $notVerAcc;
                    exit();
                }
            } else {
                echo 'A User could not be found with this email address.';
                exit();
            }
        }
        break;
    case 'otp_regen_login':
        $logUserID = $_POST['log_user_id'];
        $randOtp = rand(0, 999999);
        $otpDate = date("Y-m-d H:i:s");
        $login_otp = $randOtp . '|' . $otpDate;
        $phone = get_user_meta($logUserID, 'phone', true);
        update_user_meta($logUserID, 'login_otp', $login_otp);
        $retOtpSuc = otp_snd($phone, $randOtp);
        $user_info = get_userdata($logUserID);
        $user_email = $user_info->user_email;
        $message = '<html>
                    <body>
                        <div style="max-width:500px">
                            <p>Hello,<br /><br />
                                Your OTP for your transaction at SSP is ' . $randOtp . '. Use this within the next 20 minutes to register/log-in to the SSP website and continue your purchase. Bon Appetit!<br /><br />
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
        echo '1';
        break;
        // Resent OTP for
    case 'otp_regen_reg':
        $logUserID = $_POST['reg_user_id'];
        $randOtp = rand(0, 999999);
        $otpDate = date("Y-m-d H:i:s");
        $login_otp = $randOtp . '|' . $otpDate;
        $phone = get_user_meta($logUserID, 'phone', true);
        update_user_meta($logUserID, 'reg_otp   ', $login_otp);
        $user_info = get_userdata($logUserID);
        $user_email = $user_info->user_email;
        $message = '<html>
                        <body>
                            <div style="max-width:500px">
                                <p>Hello,<br /><br />
                                    Your OTP for your transaction at SSP is ' . $randOtp . '. Use this within the next 20 minutes to register/log-in to the SSP website and continue your purchase. Bon Appetit!<br /><br />
                                    Please do not share this OTP with anyone.<br /><br />
                                    Warm Regards<br />
                                    S S Pandian & Sons<br />
                                </p>
                            </div>
                        </body>';
        $subject = "SSP - Resend OTP for Registration";
        $to = $user_email;
        $from = "SSP-HING <info@ssp.com>";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= "From: " . $from . "\r\n";
        wp_mail($to, $subject, $message, $headers);
        $retOtpSuc = otp_snd($phone, $randOtp);
        echo '1';
        break;

    case "otp_gen_reg":
        $otpVal = $_POST['otpval'];
        $userid = $_POST['userid'];
        $otpToDate = date("Y-m-d H:i:s");
        $otpToDate = strtotime($otpToDate);
        if ($otpVal != '') {
            $otpnew = get_user_meta($userid, 'reg_otp', true);
            $otpNewVal = explode("|", $otpnew);
            $otpnew = $otpNewVal[0];
            $otpFromDate = $otpNewVal[1];
            $otpFromDate = strtotime($otpFromDate);
        }
        $diffTime = round(abs($otpToDate - $otpFromDate) / 60, 2);
        if ($otpVal == $otpnew && $diffTime < 20) {
            update_user_meta($userid, 'otp_verified', 'Yes');
            $secure_cookie = is_ssl() ? true : false;
            wp_set_auth_cookie($userid, true, $secure_cookie);
            echo 1;
        } elseif ($diffTime > 20) {
            echo 3;
        } else {
            echo 2;
        }
        break;
    case "otp_gen_login":
        $username = $_POST['lginUname'];
        $password = $_POST['lginPwd'];
        $otpVal = $_POST['otpval'];
        $otpToDateLogin = date("Y-m-d H:i:s");
        $otpToDateLogin = strtotime($otpToDateLogin);
        $creds['user_login'] = $username;
        $creds['remember'] = isset($_POST['rememberme']);
        $secure_cookie = is_ssl() ? true : false;
        $usrPhoneID = $_POST['userid'];
        if ($usrPhoneID != '') {
            if ($otpVal != '') {
                $otpnew_login = get_user_meta($usrPhoneID, 'login_otp', true);
                $otpNewValLogin = explode("|", $otpnew_login);
                $otpnewLogin = $otpNewValLogin[0];
                $otpFromDateLogin = $otpNewValLogin[1];
                $otpFromDateLogin = strtotime($otpFromDateLogin);
            }
            $diffTimeLogin = round(
                abs($otpToDateLogin - $otpFromDateLogin) / 60,2
            );
            if ($otpVal == $otpnewLogin && $diffTimeLogin < 20) {
                $usrVerf = get_user_meta($usrPhoneID, 'otp_verified', true);
                $secure_cookie = is_ssl() ? true : false;
                if ($usrVerf == 'Yes') {
                    wp_set_auth_cookie($usrPhoneID, true, $secure_cookie);
                    echo 1;
                }
            } elseif ($diffTimeLogin > 20) {
                echo 2;
            } else {
                echo 3;
            }
        } else {
            $userArgs = get_user_by('email', $username);
            $userId = $userArgs->ID;
            if ($otpVal != '') {
                $otpnew_login = get_user_meta($userId, 'login_otp', true);
                $otpNewValLogin = explode("|", $otpnew_login);
                $otpnewLogin = $otpNewValLogin[0];
                $otpFromDateLogin = $otpNewValLogin[1];
                $otpFromDateLogin = strtotime($otpFromDateLogin);
            }
            $diffTimeLogin = round(
                abs($otpToDateLogin - $otpFromDateLogin) / 60,2
            );
            if ($otpVal != '') {
                $otpnew_login = get_user_meta($userId, 'login_otp', true);
                echo $otpnew_login;
                $otpNewValLogin = explode("|", $otpnew);
                $otpnewLogin = $otpNewVal[0];
                $otpFromDateLogin = $otpNewVal[1];
                $otpFromDateLogin = strtotime($otpFromDateLogin);
            }
            if ($otpVal == $otpnew_login && $diffTimeLogin < 20) {
                $usrVerf = get_user_meta($userId, 'otp_verified', true);
                $secure_cookie = is_ssl() ? true : false;
                if ($usrVerf == 'Yes') {
                    wp_set_auth_cookie($userId, true, $secure_cookie);
                    echo 1;
                }
            } else {
                echo 2;
            }
        }
        break;
    case "otp_guest_gen_login":
        $otpVal = $_POST['otpval'];
        $userid = $_POST['userid'];
        $otpToDate = date("Y-m-d H:i:s");
        $otpToDate = strtotime($otpToDate);
        if ($otpVal != '') {
            $otpnew = get_user_meta($userid, 'reg_otp', true);
            $otpNewVal = explode("|", $otpnew);
            $otpnew = $otpNewVal[0];
            $otpFromDate = $otpNewVal[1];
            $otpFromDate = strtotime($otpFromDate);
        }
        $diffTime = round(abs($otpToDate - $otpFromDate) / 60, 2);
        if ($otpVal == $otpnew && $diffTime < 20) {
            update_user_meta($userid, 'otp_verified', 'Yes');
            $secure_cookie = is_ssl() ? true : false;
            wp_set_auth_cookie($userid, true, $secure_cookie);
            echo 1;
        } elseif ($diffTime > 20) {
            echo 3;
        } else {
            echo 2;
        }
        break;
        case "otp_checkout_gen_login":
        $otpVal = $_POST['otpval'];
        $userid = $_POST['userid'];
        $otpToDate = date("Y-m-d H:i:s");
        $otpToDate = strtotime($otpToDate);
        if ($otpVal != '') {
            $otpnew = get_user_meta($userid, 'login_otp', true);
            $otpNewVal = explode("|", $otpnew);
            $otpnew = $otpNewVal[0];
            $otpFromDate = $otpNewVal[1];
            $otpFromDate = strtotime($otpFromDate);
        }
        $diffTime = round(abs($otpToDate - $otpFromDate) / 60, 2);
        if ($otpVal == $otpnew && $diffTime < 20) {
            update_user_meta($userid, 'otp_verified', 'Yes');
            $secure_cookie = is_ssl() ? true : false;
            wp_set_auth_cookie($userid, true, $secure_cookie);
            echo 1;
        } elseif ($diffTime > 20) {
            echo 3;
        } else {
            echo 2;
        }
        break;

        }
wc_clear_notices();
