<?php get_header(); ?>
<?php $post_categories = dolan_get_categories(); ?>

        <div id="posts" class="wrap full">
            <!--show each post by category-->
            <?php foreach($post_categories as $cat): ?>
                <?php
                    $posts = dolan_get_posts('',$cat['slug']);
                    if($posts):
                ?>
                        <section>
                            <h2><?= $cat['name']; ?></h2>
                            <ul>
                            <?php foreach ($posts as $post): ?>
                               <li>
                                    <a href="<?= $post['link']; ?>">
                                        <img src="<?= $post['image']; ?>">
                                        <h3><?= $post['title']; ?></h3>
                                        <div class="post_date"><?= date('F Y', strtotime($post['date'])); ?></div>
                                        <?php if($post['author_id']):?>
                                            <address class="author"><?= $post['author']; ?></address>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        </section>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

<?php get_footer(); ?>