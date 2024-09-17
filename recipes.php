<?php

/******
Template Name:Recipe Page
 ******/
get_header('sub');
?>
<?php
$recipes_args = array(
    'orderby' => 'menu_order',
    'order' => 'DESC',
    'post_type' => 'recipes',
    'post_status' => 'publish',
    'numberposts' => -1,

);
$recipes = get_posts($recipes_args);

?>
<div>
    <div class="container">
        <div class="row">
            <div class="col-xl-11">
                <div class="row mb-5">
                    <div class="col-xl-7">
                        <h3><?php echo $post->post_title; ?></h3>
                        <?php
                        echo apply_filters('the_content', wpautop($post->post_content)); ?>
                    </div>
                </div>
                <div class=" row row-cols-lg-5 mb-40 recipe_cardsection">
                    <?php
                    foreach ($recipes as $recipe) {
                        $recipe_id = $recipe->ID;
                        $title = $recipe->post_title;
                        $content = $recipe->post_content;
                    ?>
                        <div class=" col-6 col-md-3 col-lg mb-30">
                            <a href="<?php echo get_permalink($recipe_id); ?>" class=" cards-blk">
                                <div class="card-img mb-20">
                                    <img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id($recipe_id)); ?>">
                                </div>
                                <h6><?php echo $title; ?></h6>
                                <?php echo apply_filters('the_content', wpautop('[ratings id="' . $recipe->ID . '"]'));
                                ?> 
                                    <?php $author_name = get_field('author_name', $recipe_id);
                                if($author_name){  ?>

                                    <p class="ash-color"><?php echo $author_name; ?></p>
                             <?php } ?>
                            </a>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
?>