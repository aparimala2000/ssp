<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="user-scalable=no,initial-scale=1,maximum-scale=1.0" />
    <!-- <link rel="shortcut icon"href="img/nl-favicon.png"/> -->
    <title><?php bloginfo('name'); ?><?php wp_title('-'); ?></title>
    <!-- modernizr included -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <style>
        body {
            background-color: #fff;
        }

        .render-blk {
            opacity: 0;
        }
    </style>
    <link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/lib/css/app.css" />
    <link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/style.css" />
    <?php wp_head(); ?>
    <noscript>
        <style media="screen">
            .render-blk {
                opacity: 1;
            }
        </style>
    </noscript>

    <script>
        var blogUri = "<?php echo get_bloginfo('url'); ?>";
        var templateUri = "<?php echo get_bloginfo('template_url'); ?>";
    </script>
</head>

<body class="pro-list">
    <!-- alert message start -->
    <!-- <div class="popup-card" id="popup-card">
        <div class="container">
            <div class="mid-container">
                <div class="d-flex align-items-center justify-content-between mb-20">
                    <h5 class="mb-0"><?php echo get_option('cookie_title'); ?></h5>
                    <a href="javascript:void(0);" class="popup-card-close circle-icon cookie_content"><i class="las la-times"></i></a>
                </div>
                <?php $cookie_content = get_option('cookie_content');
                echo wpautop($cookie_content); ?>

            </div>
        </div>
    </div> -->
    <!-- alert message end -->
    <div class="render-blk">
        <?php $post_title = get_the_title($post->ID);
        //echo $post_title;
        if ($post_title != "Global") {
            include('mob_header.php');
        }
        ?>
        <!-- header start -->
        <header>
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="<?php echo get_bloginfo('url'); ?>"> <img src="<?php echo get_option('Logo_image'); ?>" alt="Logo Image"></a>
                    </div>
                    <ul class="header-nav d-flex">
                        <?php
                        $args = array(
                            'order' => 'ASC',
                            'post_type' => 'nav_menu_item',
                            'post_status' => 'publish'
                        );
                        $menu_list = wp_get_nav_menu_items('Mainmenu', $args);
                        $menu_count = count($menu_list);
                        foreach ($menu_list as $key => $menu) {
                            $menutitle = $menu->title;
                            $menuurl = $menu->url;
                            //  var_dump($menu_list);
                            if ($menuurl === get_permalink()) {
                                $activeclass = "active";
                            } else {
                                $activeclass = "";
                            }
                        ?>
                            <li class="header-nav-list <?php if ($key == $menu_count - 2) {
                                                            echo 'last-menu-item-hidden';
                                                        } ?>"><a href="<?php echo $menuurl; ?>" class="<?php echo $activeclass; ?> link-anim" title="<?php echo $menutitle; ?>"><?php echo $menutitle; ?></a></li>
                            <?php
                            if ($key == $menu_count - 2) {
                                include('desk_header.php');
                            } ?>
                        <?php } ?>
                    </ul>

                    <div class="d-flex align-items-center">
                        <ul class="header-icon d-flex">
                            <?php if (is_user_logged_in()) { ?>
                                <li><a href="<?php echo get_bloginfo('url'); ?>/my-account" class="user-icon"><i class="fa fa-user" aria-hidden="true"></i></a></li>
                            <?php } else { ?>
                                <li><a href="<?php echo get_bloginfo('url'); ?>/sign-in" class="user-icon"><i class="fa fa-user" aria-hidden="true"></i></a></li>
                            <?php } ?>

                            <li><a href=" <?php if (WC()->cart->get_cart_contents_count() > 0) {
                                                echo esc_url(get_permalink(wc_get_page_id('checkout')));
                                            } else {
                                                echo 'javascript:void(0);';
                                            } ?>" class="user-icon"><i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    <span class="circle-icon type2 header_cart_count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                                </a></li>

                        </ul>
                        <div class="ham ml-2">
                            <div class="ham-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- header end -->