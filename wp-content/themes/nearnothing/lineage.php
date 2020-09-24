<?php
/**
* Template Name: Lineage
*
* @package WordPress
* @subpackage Twenty_Fourteen
* @since Twenty Fourteen 1.0
*/
?>

<?php get_header(); ?>

    <?php
        $family_tree = get_relatives_by_id();

        function build_lineage($relative_id){
            $branch = array();
            $relative = get_relatives_by_id($relative_id);
            if($relative['children']){
                foreach($relative['children'] as $child){
                    $branch[$child] = build_lineage($child);
                }
            }
            $branch[] = $relative;
            return $branch;
        }

        $tree = build_lineage(70);
        var_dump($tree);
    ?>

<?php get_footer(); ?>

