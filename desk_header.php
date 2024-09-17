 <?php
    $args = array(
        'order' => 'ASC',
        'post_type' => 'nav_menu_item',
        'post_status' => 'publish'
    );
    $menu_list = wp_get_nav_menu_items('Mainmenu', $args);
    foreach ($menu_list as $key => $menu) {
        $menutitle = $menu->title;
        $menuurl = $menu->url;
        $icon = get_field('global_icon', $menu->ID); // Assuming $item holds the menu item ID
        $text = get_field('global_text', $menu->ID);
        $link_text = get_field('link_text', $menu->ID);
        $link = get_field('global_link', $menu->ID);

        $text_url = '';
        $link_target = '';
        if ($link) {
            $text_url = $link['url'];
            $link_target =  $link['target'] ? $link['target'] : "_self";
        }
        if ($icon) {
    ?>
         <li class="only-desk header-nav-list">
             <a href="<?php echo $text_url; ?>" class="button sm-btn v1 d-flex align-items-center global" target="<?php echo $link_target; ?>">
                 <img src="<?php echo $icon; ?>" alt="Global Image">
                 <span class="md"><?php echo $text; ?> <?php echo $link_text; ?></span>
             </a>
         </li>
 <?php
        }
    }
    ?>