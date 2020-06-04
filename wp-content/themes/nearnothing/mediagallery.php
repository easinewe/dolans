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

    <h1><?php echo get_the_title();?></h1>

    <!--build a Masonry layout-->
    <div id="media-gallery" class="grid">
        <div class="grid-sizer"></div><!--needed for sizing-->
        <div class="gutter-sizer"></div><!--needed for gutter sizing-->

        <?php
            $gallery = dolan_get_images();
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


<div class="bumper green">
    <?php echo get_the_post_thumbnail();?>
    <span>
        <?php echo get_post_field('post_content', $post->ID); ?>
    </span>
</div>

<?php /* footer */ get_footer(); ?>