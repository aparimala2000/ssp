<?php

/*****
Template Name: sspandian
 *****/
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="user-scalable=no,initial-scale=1,maximum-scale=1.0" />
    <!-- <link rel="shortcut icon"href="img/nl-favicon.png"/> -->
    <title>
        <?php bloginfo('name'); ?>
        <?php
        $url_parts = explode('/', $_SERVER['REQUEST_URI']);
        $category = $url_parts[count($url_parts) - 3];
        $product_name = $url_parts[count($url_parts) - 2];
        if ($category && $product_name) {
            echo ' - ' . ucfirst($category) . ' - ' . ucfirst($product_name);
        }
        ?>
    </title>
    <!-- modernizr included -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <style>
        body {
            background-color: #fff;
        }

        .render-blk {
            opacity: 1;
        }
    </style>
    <link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/lib/css/app.css" />
    <noscript>
        <style media="screen">
            .render-blk {
                opacity: 1;
            }
        </style>
    </noscript>
</head>
<?php
$Name = get_field('name');
$phone_number = get_field('phone_number');
$whatsapp_number = get_field('whatsapp_number');
$gst_no = get_field('gst_no');
$pan_no = get_field('pan_no');
$address = get_field('address');
$account_name = get_field('account_name');
$account_no = get_field('account_no');
$city = get_field('city');
$bank_name = get_field('bank_name');
$branch_name = get_field('branch_name');
$account_type = get_field('account_type');
$ifsc_code = get_field('ifsc_code');

?>

<body class="pop-generic">
    <div class="render-blk">
        <div class="pop-blk d-flex justify-content-center align-items-center">
            <div class="pop-card-blk">
                <div class="pop-card">
                    <div class="d-flex justify-content-between align-items-center pb-4" style="border-bottom: 1.5px solid #0000001a;">
                        <div>
                            <h3 class="mb-0"><?php echo $Name; ?></h3>
                        </div>
                        <a href="javascript:void(0)" class="out-icon"><i class="las la-upload"></i></a>
                    </div>
                    <div class="mb-3"></div>
                    <div class="row pb-4">
                        <div class="col-sm-6 col-md-4 col-lg-5">
                            <div class="card-list ">
                                <div class="card-list-item">
                                    <a href="https://maps.app.goo.gl/BPJW9eu9pBEZ5C4s7" class="d-flex align-items-center" target="_blank">
                                        <span class="card-icon"><i class="fa fa-map-marker" aria-hidden="true"></i> </span>
                                        <div>
                                            <?php echo $address; ?>
                                        </div>
                                    </a>
                                </div>
                                <div class="card-list-item">
                                    <div class="d-flex align-items-center">
                                        <span class="card-icon" style="color:white;font-size: 11px;">GST</span>
                                        <span><?php echo $gst_no; ?></span>
                                    </div>
                                </div>
                                <div class="card-list-item">
                                    <div class="d-flex align-items-center">
                                        <span class="card-icon"><i class="fa fa-id-card-o" aria-hidden="true"></i></span>
                                        <span><?php echo $pan_no; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="card-list ">
                                <div class="card-list-item">
                                    <a href="tel:<?php echo $phone_number; ?>" class="d-flex align-items-center">
                                        <span class="card-icon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                                        <span><?php echo $phone_number; ?></span>
                                    </a>
                                </div>
                                <div class="card-list-item">
                                    <a href="<?php echo get_bloginfo('url'); ?>" class="d-flex align-items-center">
                                        <span class="card-icon"><i class="fa fa-globe" aria-hidden="true"></i></span>
                                        <span><?php echo str_replace(array('http://', 'https://'), '', get_bloginfo('url')); ?></span>
                                    </a>
                                </div>
                                <div class="card-list-item">
                                    <a href="https://wa.me/<?php echo $whatsapp_number; ?>" class="d-flex align-items-center" target="_blank">
                                        <span class="card-icon"><i class="fa fa-whatsapp" aria-hidden="true"></i></span>
                                        <span><?php echo $whatsapp_number; ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm col-md-4 col-lg-3">
                            <div class="map-location">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15551.642149660574!2d77.5473296!3d12.9775736!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bae3def752fb4df%3A0x7f5a7772ff71a15e!2sS.S.PANDIAN%20%26%20SONS!5e0!3m2!1sen!2sin!4v1702899619638!5m2!1sen!2sin" width="100%" height="150" style="border:1px solid #0000003b;padding: 5px; border-radius: 5px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                <span class="md ash-color b-font text-center">Factory Location</span>
                            </div>
                        </div>
                    </div>
                    <div style="border-bottom: 1.5px solid #0000001a;" class="mb-30"></div>
                    <!-- bank details started -->
                    <h6 class="mb-3">Bank Account Details</h6>
                    <div class="row mb-30">
                        <div class="col-sm-6">
                            <div class="card-list bank-details">
                                <div class="card-list-item d-flex">
                                    <div>
                                        <span>Account Name</span>
                                        <span>-</span>
                                    </div>
                                    <span><?php echo $account_name; ?></span>
                                </div>
                                <div class="card-list-item d-flex">
                                    <div>
                                        <span>Account No</span>
                                        <span>-</span>
                                    </div>
                                    <span><?php echo $account_no; ?></span>
                                </div>
                                <div class="card-list-item d-flex">
                                    <div>
                                        <span>Bank Name</span>
                                        <span>-</span>
                                    </div>
                                    <span><?php echo $bank_name; ?></span>
                                </div>
                                <div class="card-list-item d-flex">
                                    <div>
                                        <span>Branch Name</span>
                                        <span>-</span>
                                    </div>
                                    <span><?php echo $branch_name; ?></span>
                                </div>
                                <div class="card-list-item d-flex">
                                    <div>
                                        <span>City</span>
                                        <span>-</span>
                                    </div>
                                    <span><?php echo $city; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card-list bank-details">
                                <div class="card-list-item d-flex">
                                    <div>
                                        <span>Account Type</span>
                                        <span>-</span>
                                    </div>
                                    <span><?php echo $account_type; ?></span>
                                </div>
                                <div class="card-list-item d-flex">
                                    <div>
                                        <span>IFSC Code</span>
                                        <span>-</span>
                                    </div>
                                    <span><?php echo $ifsc_code; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $address = strip_tags($address);
                    $address = trim($address);
                    // Create a string containing the contact information
                    $contactData = "Name: $Name\nAddress: $address\nGST No: $gst_no\nPAN No: $pan_no\nPhone Number: $phone_number\nWhatsapp Number: $whatsapp_number\n";
                    $contactData .= "Account Name: $account_name\nAccount No: $account_no\nCity: $city\nBank Name: $bank_name\n";
                    $contactData .= "Branch Name: $branch_name\nAccount Type: $account_type\nIFSC Code: $ifsc_code\n";

                    ?>
                    <a href="data:text/plain;charset=utf-8,<?php echo rawurlencode($contactData); ?>" download="contact_info.txt" class="button pop-btn">Save To Contacts</a>
                </div>
                <!-- share popup started -->
                <div class="dub-layer"></div>
                <div class="share-popup">
                    <div class="text-right mb-4">
                        <a href="javascript:void(0)" class="popup-card-close"><i class="las la-times"></i></a>
                    </div>
                    <div class="share-list">
                        <div>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="social-share">
                                <div>
                                    <img class="social_icon" src="<?php echo get_bloginfo('template_url'); ?>/lib/images/s2.svg" alt="">
                                    <span>Share on Facebook</span>
                                </div>
                            </a>
                        </div>
                        <div>
                            <a href="https://www.linkedin.com/shareArticle?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="social-share">
                                <div>
                                    <img class="social_icon" src="<?php echo get_bloginfo('template_url'); ?>/lib/images/s3.png" alt="">
                                    <span>Share on LinkedIn</span>
                                </div>
                            </a>
                        </div>
                        <div>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="social-share">
                                <div>
                                    <img class="social_icon" src="<?php echo get_bloginfo('template_url'); ?>/lib/images/s4.jpeg" alt="">
                                    <span>Share on Twitter</span>
                                </div>
                            </a>
                        </div>
                        <div>
                            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" class="social-share">
                                <div>
                                    <img class="social_icon" src="<?php echo get_bloginfo('template_url'); ?>/lib/images/s5.svg" alt="">
                                    <span>Share on WhatsApp</span>
                                </div>
                            </a>
                        </div>
                        <div>
                            <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode("Hello,%0A%0AClick here to get further details about SSPandian: " . get_permalink()); ?>" class="social-share">
                                <div>
                                    <img class="social_icon" src="<?php echo get_bloginfo('template_url'); ?>/lib/images/s7.png" alt="">
                                    <span>Share via Mail</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <a href="javascript:void(0);" class="button copy-link-btn pop-btn mt-3 ml-2">Copy URL</a>
                    <div style="display: none;">
                        <span class="permalink"><?php echo str_replace(array('http://', 'https://'), '', get_permalink()); ?></span>
                    </div>
                    <!-- <div class="copy-link my-4 d-flex justify-content-between">

                        <div style="display: none;">
                            <span class="permalink"><?php echo str_replace(array('http://', 'https://'), '', get_permalink()); ?></span>
                        </div>
                        <span class="copy-link-btn">copy URL</span>
                    </div> -->
                </div>
                <!-- share popup eneded -->
            </div>
        </div>
    </div>
    <!-- <style>
        .copied {
            color: green;
        }
    </style> -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script>
        $(document).ready(function() {
            $('.copy-link-btn').click(function() {
                var linkText = $('.permalink').text();
                var tempInput = $('<input>');
                $('body').append(tempInput);
                tempInput.val(linkText).select();
                document.execCommand('copy');
                tempInput.remove();
                $('.copy-link-btn').text('copied!');
                // $('.copy-link-btn').text('copied!').addClass('copied');
                setTimeout(function() {
                    $('.copy-link-btn').text('copy URL');
                    // $('.copy-link-btn').text('copy URL').removeClass('copied');
                }, 2000);
            });
        });
    </script>
    <script src='<?php echo get_bloginfo('template_url'); ?>/lib/js/app.js'></script>
</body>

</html>