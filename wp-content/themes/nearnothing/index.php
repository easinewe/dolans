<?php get_header(); ?>
<?php $post_categories = dolan_get_categories(); ?>

    <div id="posts" class="wrap">
        <!--show each post by category-->
        <?php foreach($post_categories as $cat): ?>
            <?php
                $posts = dolan_get_posts($cat['slug']);
                if($posts):
            ?>
                <h2><?= $cat['name']; ?></h2>
                <ul>
                    <?php foreach ($posts as $post): ?>
                       <li>
                            <a href="<?= $post['link']; ?>">
                                <div class="post_date"><?= $post['date']; ?></div>
                                <img src="<?= $post['image']; ?>">
                                <h2><?= $post['title']; ?></h2>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

<?php get_footer(); ?>