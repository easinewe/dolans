<?php
/**
 * Template Name: Memories
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 ?>

<?php get_header(); ?>

<div id="post_content">
    
    <?php
	  // The Query
	  
	  $args = array(
        'numberposts' => -1,
        'offset' => 0,
        'category' => 0,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'post_type' => 'post',
        'post_status' => 'publish',
        'suppress_filters' => true );
	  
	  query_posts( $args );
	  
	  // The Loop
	  while ( have_posts() ) : the_post();
		$post_link = get_permalink($post);
		  echo '<a href='.$post_link.'>';
			echo the_date('F j, Y', '<div class="post_date">', '</div>'); 
			echo '<h1>';
				the_title();
			echo '</h1>';
		  echo '</a>';

	  endwhile;
	  
	  // Reset Query
	  wp_reset_query();
	  ?>

</div>

<?php /* footer */ get_footer(); ?>