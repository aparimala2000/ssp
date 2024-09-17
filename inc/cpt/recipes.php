<?php add_action('init', 'create_recipes', 0);

function create_recipes() {
    $labels = array(
        'name' => _x('Recipes', 'post type general name'),
        'singular_name' => _x('Recipes', 'post type singular name'),
        'add_new' => _x('Add Recipes', 'Recipes'),
        'add_new_item' => __('Add Recipes'),
        'edit_item' => __('Edit Recipes'),
        'new_item' => __('New Recipes'),
        'view_item' => __('View Recipes'),
        'search_items' => __('Search Recipes'),
        'not_found' => __('No Recipes found'),
        'not_found_in_trash' => __('No Recipes found in Trash'),
        'parent_item_colon' => ''
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'recipes','with_front' => FALSE,),
        'capability_type' => 'post',
        'hierarchical' => true,
        'menu_icon' => 'dashicons-products',
        'menu_position' => 7,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes')
    );

    register_post_type('recipes', $args);
  }

?>