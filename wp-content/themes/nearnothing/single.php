<?php get_header(); ?>
<?php
    $post_info = dolan_get_posts($post->ID);
    $next = dolan_get_next_post($post->ID);
?>

	<div id="post_content">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
             <h1><?php the_title(); ?></h1>

                <?php if($post_info[0]['author_id']): ?>
                    <a href="<?= $post_info[0]['author_link']; ?>" class="author"><?= $post_info[0]['author']; ?></a>
                <?php endif; ?>

                <?php if($post_info[0]['image']): ?>
                    <div id="featured_image">
                        <div>
                            <img src="<?= $post_info[0]['image']; ?>">
                            <?php if($post_info[0]['image_caption']): ?>
                                <figcaption>
                                    <?= $post_info[0]['image_caption']; ?>
                                </figcaption>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div id="content">
                    <p class="post_date"><?= date('F Y', strtotime($post_info[0]['date'])); ?></p>
                    <?php the_content(); ?>
                </div>

            <?php endwhile; ?>
            <?php endif; ?>
	
    </div>


    <!--NEXT-->
    <a class="next_post green" href="<?= get_the_permalink($next); ?>">
        <div class="bumper green">
            <?= get_the_post_thumbnail($next); ?>
            <span>
                <h4><?= get_the_title($next); ?></h4>
            </span>
        </div>
    </a>

<?php get_footer(); ?>
