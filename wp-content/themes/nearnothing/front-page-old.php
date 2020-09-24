<?php
/**
 * Template Name: Front Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
<?php get_header(); ?>
<?php $bg_image =  dolan_get_images(1,1,'rand'); ?>
    <div id=fp_fullscreen_image style="background-image:url(<?= $bg_image[0]['url_full']; ?>)"></div>
    <?php if($bg_image[0]['description']): ?>
        <span><?= $bg_image[0]['description']; ?></span>
    <?php endif; ?>

<?php get_footer(); ?>