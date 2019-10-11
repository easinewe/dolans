<?php
/**
 * Template Name: Content
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 ?>
 
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post();?>
  <div id="content">
    <?php the_content(); ?>
  </div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>