<?php get_header(); ?>

    <?php
        $id         = get_the_ID();
        $relative   = get_relatives_by_id($id);
        $relative   = $relative[0];
    ?>

    <div>
        <header>

                <div class="relative_profile">
                    <?php echo $relative['thumbnail']; ?>
                    <a href="/family_tree"></a>
                </div>

                <h1>
                    <?php echo $relative['name_first']; ?>
                    <?php echo ($relative['name_middle'])?$relative['name_middle']:''; ?>
                    <?php echo $relative['name_last']; ?>
                </h1>

                <h2>
                    <a href="/family_tree/map/">
                        <?php echo $relative['location']?>
                    </a>
                </h2>

                <h3>
                    <?php
                    if($relative['dod']){
                        echo ($relative['dod'])?' – '.$relative['dod']:'';
                    }else{
                        echo 'b.'.$relative['dob'];
                    }
                    ?>
                </h3>
                <p>

                    <?php var_dump($relative['children']); ?>
            <p>

        </header>
    </div>

    <!--build a Masonry layout-->
    <div id="media-gallery" class="grid">
        <div class="grid-sizer"></div><!--needed for sizing-->
        <div class="gutter-sizer"></div><!--needed for gutter sizing-->

        <?php
        $gallery = dolan_get_images();
        foreach($gallery as $image):
            ?>
            <div class="grid-item">
                <a href="<?= $image['url_full']; ?>" title="<?= $image['description']; ?>" rel="lightbox">
                    <img src="<?= $image['url_lg']; ?>">
                </a>
            </div>
        <?php endforeach; ?>
    </div>

<?php get_footer(); ?>
