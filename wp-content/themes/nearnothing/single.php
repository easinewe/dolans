<?php get_header(); ?>

	<div id="post_content">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
             <h1><?php the_title(); ?></h1>
             
			 <?php the_content(); ?>
             
			 <?php //echo get_the_date(); ?>
            
            <?php endwhile; ?>
            <?php endif; ?>
	
    </div><!-- .content-area -->

<?php get_footer(); ?>
