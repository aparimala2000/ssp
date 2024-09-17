<?php get_header('sub');
global $post;
$post_par = wp_get_post_parent_id(get_the_ID());
$parent_title = get_the_title($post_par);
$parent_link = get_permalink($post_par);
$post_id = $post->ID;
$post_excerpt = get_the_excerpt($post->ID);
$post_title = get_the_title($post->ID);
$post_type = get_post_type($post->ID);
?>

<?php if (have_rows('pattern_library')) : ?>
    <div class="container">
      
        <div class="shadow-bg">
        <?php if ($post_par != 0 && $parent_title == "About") { ?>
            <div class="mb-30">
                <a href="<?php echo get_bloginfo('url'); ?>/about">About</a> /
            </div>
        <?php } else if ($post_par == 0 && $parent_title != "About") { ?>
            <div class="mb-30">
                <a href="<?php echo get_bloginfo('url'); ?>/">Home</a> /
            </div>
        <?php } ?>
            <?php while (have_rows('pattern_library')) : the_row(); ?>
                <?php if (get_row_layout() == 'single_col_text') :
                    $single_content =  get_sub_field('single_column_text'); ?>
                    <section>
                        <div class="row">
                            <div class="col-lg-10">
                                <?php echo $single_content; ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
                <?php if (get_row_layout() == 'single_column_text_with_point_arrow') :
                    $single_content =  get_sub_field('single_column_text'); ?>
                    <section>
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="point-arrow">
                                    <?php echo $single_content; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
                <?php if (get_row_layout() == 'spacer') :
                    $space_val =  get_sub_field('space_value'); ?>
                    <section>
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="spacer" data-space="<?php echo $space_val; ?>"></div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
                <?php if (get_row_layout() == 'image_pattern') :
                    $_image =  get_sub_field('image');
                    $image_text =  get_sub_field('description');
                ?>
                    <section>
                        <div class="row">
                            <div class="col-lg-10">
                                <?php if ($_image != "") { ?>
                                    <div class="img-blk mb-3">
                                        <img src="<?php echo $_image; ?>">
                                    </div>
                                <?php } ?>
                                <?php if ($image_text != "") { ?>
                                    <p><?php echo $image_text; ?></p>

                                <?php } ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
                <?php if (get_row_layout() == 'accordion_pattern') :
                    $accordions =  get_sub_field('accordions'); ?>
                    <section>
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="accord">
                                            <?php $cnt = 1;
                                            foreach ($accordions as $accordion) {
                                                $accord_title = $accordion['title'];
                                                $accord_description = $accordion['description'];

                                            ?>
                                                <div>
                                                    <a href="javascript:void(0);" class="toggle_btn <?php if ($cnt == 1) {
                                                                                                        echo 'active';
                                                                                                    } ?>">
                                                        <h6><?php echo $accord_title; ?></h6>
                                                    </a>
                                                    <div class="inner <?php if ($cnt == 1) {
                                                                            echo 'show';
                                                                        } ?>">
                                                        <?php echo $accord_description; ?>
                                                    </div>
                                                </div>
                                            <?php $cnt++;
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
                <?php if (get_row_layout() == 'two_column_text') :
                    $column_one = get_sub_field('column_one');
                    $column_one_prime_cta = get_sub_field('two_col_primary_one');
                    $two_col_primary_one_link = !empty(get_sub_field("two_col_primary_one_link")['url']) ? get_sub_field("two_col_primary_one_link")['url'] : "javascript:void(0):";
                    $two_col_primary_one_link_target = !empty(get_sub_field("two_col_primary_one_link")['target']) ? get_sub_field("two_col_primary_one_link")['target'] : "_self";

                    // $column_one_second_cta = get_sub_field('two_col_secondary_one');
                    // $column_one_second_link = get_sub_field('two_col_secondary_one_link');
                    $column_two = get_sub_field('column_two');
                    $column_two_prime_cta = get_sub_field('two_col_primary_two');
                    $column_two_prime_link = !empty(get_sub_field("two_col_primary_two_link")['url']) ? get_sub_field("two_col_primary_two_link")['url'] : "javascript:void(0):";
                    $column_two_prime_link_target = !empty(get_sub_field("two_col_primary_two_link")['target']) ? get_sub_field("two_col_primary_two_link")['target'] : "_self";

                    // $column_two_second_cta = get_sub_field('two_col_secondary_two');
                    // $column_two_second_link = get_sub_field('two_col_secondary_two_link');
                ?>
                    <section>
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="row mx-md-n4">
                                    <div class="col-md-6 px-md-4">
                                        <?php echo $column_one; ?>

                                        <a href="<?php echo $two_col_primary_one_link; ?>" target="<?php echo $two_col_primary_one_link_target; ?>" class="button"><?php echo $column_one_prime_cta; ?></a>


                                    </div>
                                    <div class="col-md-6 px-md-4">
                                        <?php echo $column_two; ?>
                                        <a href="<?php echo $column_two_prime_link; ?>" target="<?php echo $column_two_prime_link_target; ?>" class="button"><?php echo $column_two_prime_cta; ?></a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                <?php endif; ?>
                <?php if (get_row_layout() == 'p2_image_with_text') :
                    $col_content =  get_sub_field('p2_content');
                    $image = get_sub_field("add_image_p2")['url'];
                    $Alt_image = !empty(get_sub_field("add_image_p2")['alt']) ? get_sub_field('add_image_p2')['alt'] : get_sub_field('add_image_p2')['name'];
                    $primary_btn_text =  get_sub_field('primary_cta_p2');
                    $primary_btn_link = !empty(get_sub_field("primary_cta_link")['url']) ? get_sub_field("primary_cta_link")['url'] : "javascript:void(0):";
                    $primary_btn_linktarget = !empty(get_sub_field("primary_cta_link")['target']) ? get_sub_field("primary_cta_link")['target'] : "_self";
                    // $secondary_btn_text =  get_sub_field('secondary_cta_p2');
                    // $secondary_btn_link =  get_sub_field('secondary_cta_link');
                    $image_align =  get_sub_field('image_align');
                    if ($image_align == "left") {
                        $align = "";
                    } else {
                        $align = "flex-row-reverse";
                    }
                    $bg_shade =  get_sub_field('background_shade');
                    if ($bg_shade == "light") {
                        $bg = "#F8F2F2";
                    } else {
                        $bg = "#fff";
                    } ?>
                    <section>
                        <div class="full-width">
                            <div style="background-color: <?php echo $bg; ?>; padding-bottom: 50px; padding-top: 50px;">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-10 mx-auto">
                                            <div class="row <?php echo $align; ?>">
                                                <div class="col-md-6">
                                                    <div class="img-blk">
                                                        <img src="<?php echo $image; ?>" alt="<?php echo $Alt_image; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php echo $col_content; ?>
                                                    <?php if ($primary_btn_text != "") { ?>
                                                        <a href="<?php echo $primary_btn_link; ?>" target="<?php echo $primary_btn_linktarget; ?>" class="button"><?php echo $primary_btn_text; ?></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
                <?php if (get_row_layout() == 'single_column_text_with_right_nav') :
                    $single_column_text = get_sub_field("single_column_text");
                    $right_nav = get_sub_field("right_nav");  ?>
                    <section>
                        <div class="row">
                            <div class="col-md-9 about-section">
                                <?php echo $single_column_text; ?>
                            </div>
                            <div class="col-md-2 ml-auto right-nav mb-md-0 mt-md-5 pt-4">
                                <?php foreach ($right_nav as $right_navs) {
                                    $text = $right_navs["text"];
                                    $Link = !empty($right_navs["link"]['url']) ? $right_navs["link"]['url'] : "javascript:void(0):";
                                    $LinkTarget = !empty($right_navs["link"]['target']) ? $right_navs["link"]['target'] : "_self";
                                ?>
                                    <a href="<?php echo $Link; ?>" target="<?php echo $LinkTarget; ?>"><?php echo $text; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
                <?php if (get_row_layout() == 'quote') :
                    $quotes_section = get_sub_field("quotes_section"); ?>
                    <section>
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="italic-txt">
                                    <?php echo $quotes_section; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
                <?php if (get_row_layout() == 'time_line') :
                    $blocks = get_sub_field("blocks"); ?>
                    <section>
                        <div class="full-width">
                            <div class="about-us" style="background-color:#404465; padding: 60px 0; overflow:hidden;">
                                <div class="container">
                                    <div class="x-auto-slider-blk">
                                        <div class="x-auto-slider">
                                            <?php foreach ($blocks as $block) {
                                                $first_section = $block["time_line_first_section"];
                                                $second_section = $block["time_line_second_section"];
                                                $time_image = isset($block["time_image"]['url']) ? $block["time_image"]['url'] : '';
                                                $time_image_alt = !empty($block["image"]['alt']) ? $block["image"]['alt'] : '';

                                                $section_align = $block['section_align'];
                                                if ($section_align == "first") {
                                                    $align = "";
                                                } else {
                                                    $align = "two";
                                                }
                                            ?>
                                                <div class="about-content <?php echo $align; ?>">
                                                    <div>
                                                        <?php echo $first_section; ?>
                                                        <?php if ($time_image) { ?>
                                                            <img src="<?php echo $time_image; ?>" alt="<?php echo $time_image_alt; ?>">
                                                        <?php } ?>
                                                    </div>
                                                    <?php if ($second_section) { ?>
                                                        <div>
                                                            <?php echo $second_section; ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>
    </div>
<?php endif; ?>
<?php get_footer(); ?>