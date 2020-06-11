<?php
/**
 * Template Name: Photos
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 ?>

<?php get_header(); ?>

    <div class="wrap">

        <header>
            <h1><?php echo get_the_title();?></h1>
        </header>

        <!--build a Masonry layout-->
        <div id="media-gallery" class="grid">
            <div class="grid-sizer"></div><!--needed for sizing-->
            <div class="gutter-sizer"></div><!--needed for gutter sizing-->

            <?php
                $gallery = dolan_get_images('',1,'');
                foreach($gallery as $image):
            ?>
                <div class="grid-item">
                    <a href="<?= $image['url_lg']; ?>" title="<?= $image['description']; ?>" rel="lightbox">
                        <img src="<?= $image['url']; ?>">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    </div><!-- end wrap -->

    <!--BUMPER-->
    <a class="next_post green" href="mailto:eamonnfitzmaurice@gmail.com">
        <div class="bumper green">
            <?php echo get_the_post_thumbnail();?>
            <span>
                <!--<h4><?php //get_post_field('post_content', $post->ID); ?></h4>-->
                <h4>Want to submit a photo?<br/>Log in or contact me here.</h4>
            </span>
        </div>
    </a>

<?php get_footer(); ?>