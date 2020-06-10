<?php get_header(); ?>

	<div id="post_content">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
             <h1><?php the_title(); ?></h1>
             <?= the_post_thumbnail(); ?>
			 <?php the_content(); ?>
             
			 <?php //echo get_the_date(); ?>
            
            <?php endwhile; ?>
            <?php endif; ?>
	
    </div><!-- .content-area -->

<div class="bumper green">
    <?php echo get_the_post_thumbnail();?>
    <span>
        <h2>Next: <?php echo get_post_field('post_title', $post->ID); ?></h2>
    </span>
</div>

<?php get_footer(); ?>
