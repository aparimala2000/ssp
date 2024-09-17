 <?php get_header('sub');
    ?>
 <?php if (have_rows('recipes')) : ?> 
     <div class="container">
         <div class="mb-30">
             <a href="<?php echo get_bloginfo('url'); ?>/recipes">Recipes</a> /
         </div>
         <div class="mb-50">
             <div class="mid-container">
                 <?php while (have_rows('recipes')) : the_row(); ?>
                     <?php if (get_row_layout() == 'spacer') :
                            $space_val =  get_sub_field('space_value'); ?>
                         <div class="spacer" data-space="<?php echo $space_val; ?>"></div>
                     <?php endif; ?>
                     <?php if (get_row_layout() == 'recipe_info') :
                            $Image =  get_sub_field('image')['url'];
                            $Alt_image = !empty(get_sub_field("image")['alt']) ? get_sub_field('image')['alt'] : get_sub_field('image')['name'];
                            $Preparation_time =  get_sub_field('preparation_time');
                            $Cook_time =  get_sub_field('cook_time');
                            $Servings =  get_sub_field('servings');
                        ?>
                         <div class="row mb-20">
                             <div class="col-md-6 mb-30">

                                 <div class="img-blk">
                                     <img src="<?php echo $Image; ?>" alt="<?php echo $Alt_image; ?>">
                                 </div>
                             </div>

                             <div class="col-md-6">
                                 <h3 class="mb-4"><?php echo $post->post_title; ?></h3>
                                 <div>
                                     <div class="row">
                                         <div class="col-6 col-md-5 mb-20">
                                             <h6>Preparation Time</h6>
                                             <span class="ld ash-color"><?php echo $Preparation_time; ?></span>
                                         </div>
                                         <div class="col-6 col-md-5 mb-20">
                                             <h6>Cook Time</h6>
                                             <span class="ld ash-color"><?php echo $Cook_time; ?></span>
                                         </div>
                                         <div class="col-6 col-md-5 mb-20">
                                             <h6>Servings</h6>
                                             <span class="ld ash-color"><?php echo $Servings; ?></span>
                                         </div>

                                         <div class=" col-6 col-md-5 mb-20">
                                             <h6>Rate Me</h6>
                                             <?php echo apply_filters('the_content', wpautop('[ratings id="' . $recipe->ID . '"]'));
                                                ?>
                                             <!-- <ul class="ratting-stars d-flex">
                                                 <li class="mr-2"><i class="fa fa-star" aria-hidden="true"></i></li>
                                                 <li class="mr-2"><i class="fa fa-star" aria-hidden="true"></i></li>
                                                 <li class="mr-2"><i class="fa fa-star" aria-hidden="true"></i></li>
                                                 <li class="mr-2"><i class="fa fa-star" aria-hidden="true"></i></li>
                                                 <li class="mr-2"><i class="fa fa-star" aria-hidden="true"></i></li>
                                             </ul> -->
                                         </div>
                                     </div>
                                 </div>
                             </div>

                         </div>
                     <?php endif; ?>
                     <?php if (get_row_layout() == 'bullet_section') :
                            $Title =  get_sub_field('title');
                            $Bullets =  get_sub_field('bullets');
                        ?>
                         <h5><?php echo $Title; ?></h5>
                         <div class="row new-bullet mb-4">
                             <?php foreach ($Bullets as $bullet) {
                                    $Bullet_content =  $bullet['bullet_content']; ?>
                                 <div class="col-md-4">
                                     <?php foreach ($Bullet_content as $content) { ?>
                                         <div class="point">
                                             <p><?php echo $content['point']; ?></p>
                                         </div>
                                     <?php } ?>
                                 </div>
                             <?php } ?>
                         </div>
                     <?php endif; ?>
                     <?php if (get_row_layout() == 'arrow_section') :
                            $Title =  get_sub_field('title');
                            $Arrow_content =  get_sub_field('arrow_content');
                        ?>
                         <h5><?php echo $Title; ?></h5>
                         <div class="row">
                             <div class="col-md-10">
                                 <div class="point-arrow">
                                     <?php echo $Arrow_content; ?>
                                 </div>
                             </div>
                         </div>
                     <?php endif; ?>
                 <?php endwhile; ?>
             </div>
         </div>
     </div>

 <?php endif; ?>
 <?php
    get_footer();
    ?>