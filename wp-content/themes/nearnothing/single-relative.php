<?php get_header(); ?>

	<div id="post_content">
    <header>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <div class="relative_profile">
                <?php echo get_the_post_thumbnail();?>
                <a href="/family_tree"></a>
            </div>

            <h1><?php the_title(); ?></h1>
            <h2><a href="/map/">Brooklyn, NY</a></h2>
            <h3>2000 -</h3>
            <?php the_content(); ?>


        <?php endwhile; ?>
        <?php endif; ?>
	</header>
    </div><!-- .content-area -->

<?php get_footer(); ?>
