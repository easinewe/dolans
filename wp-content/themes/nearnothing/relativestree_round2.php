<?php
/**
 * Template Name: Relativestree_round2
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 ?>

<?php get_header(); ?>

<style>
	.tree-detail{
		display: none;
	}

    ul.roundtree .lin2{
        margin-left: 20px;
    }

    .roundtree li{
        position: fixed;
        top: 50%;
        left: 50%;
        width: 80px;
        height: 80px;
        cursor: pointer;
    }
    .roundtree li > div {
        opacity: 1.0;
        transition: opacity .25s linear;
    }

    .roundtree li label{
        opacity: 0;
        position: fixed;
        top: 50%;
        left: 50%;
    }

    body.connections .roundtree li > div{
        opacity: 0.2;
    }

    body.connections .roundtree li.linked > div{
        opacity: 1.0;
    }

    .roundtree li div{
        width: 80px;
        height: 80px;
        border-radius: 40px;
        overflow: hidden;
        background-color: black;
    }

    .roundtree li img{
        opacity: 0.9;
        width: 80px;
        height: 80px;
    }
    .roundtree li:hover img,
    .roundtree li:hover .spouse{
        opacity:1.0;
    }
    .spouse{
        opacity: 0.0;
        position: absolute;
        top: 25%;
        right: 25%;
        width: 40px;
        height: 40px;
        border-radius: 20px;
        overflow: hidden;
        z-index: 500;
    }

    .spouse img {
        width: 40px !important;
        height: 40px !important;
    }

    #rel_label{
        position: absolute;
        background-color: black;
        color: white;
        transform: translateY(-50%);
    }

    #rel_label span{
        padding: 10px;
        height: 40px;
        line-height: 40px;
    }

</style>	

<?php
    $args = array(
      'post_type'       => 'relative',
	  'posts_per_page'  => -1,
	  'numberposts'     => -1,
	  'orderby'         => 'meta_value',
	  'meta_key'        => 'relative_birth_dob',
	  'order'   		=> 'ASC',
    );

    $fam_list_1         = array();
	$non_blood          = array();
    $generation_totals  = array();


//start building the arrays
	$relatives = new WP_Query( $args );
	if( $relatives->have_posts() ) {
	  while( $relatives->have_posts() ) {
		$relatives->the_post();
		
		$relative_id 	= get_the_ID(); 
		$father 		= get_post_meta( get_the_ID(), 'parental_units_father', true ); 
		$mother 		= get_post_meta( get_the_ID(), 'parental_units_mother', true );
	
	  //no parents listed, keep track in the non-blood array	
	  if( $mother == 'none' && $father == 'none' ){
		array_push ($non_blood, $relative_id);
	  }
	  
	  //put everyone in these arrays
	  array_push ($fam_list_1, $relative_id);
	  }
	}
?>





	  <?php 
	  	$fam_multi = array();

	  	foreach ($fam_list_1 as $family_member){

            $first_name 	        = get_post_meta( $family_member, 'relative_name_first', true );
            $last_name 		        = get_post_meta( $family_member, 'relative_name_last', true );
            $dob 			        = get_post_meta( $family_member, 'relative_birth_dob', true );
            $father 				= get_post_meta( $family_member, 'parental_units_father', true );
            $mother 				= get_post_meta( $family_member, 'parental_units_mother', true );
            $spouse_id 				= get_post_meta( $family_member, 'spouse', true );
            $connected_by_marriage 	= 'no';
		  
			  //determine who the blood connection is inherited from
			  if( in_array($mother, $non_blood) ){  
				$parent_id = $father;
				$blood_relative_id = $father;
			  } else {
				$parent_id = $mother;
				$blood_relative_id = $mother;
			  }
			  //relatives who are tied to the family via marriage
			  if( ($father == 'none') && ($mother == 'none') && ($spouse !='none') ) {
				$parent_id = $spouse;
				$blood_relative_id = $spouse;
				$connected_by_marriage = 'yes';
			  }
			  //these people are relatives with no mother, perhaps from divorce
			  if( ($mother == 'none') && ($father != 'none') ) {
				$parent_id = $father;
				$blood_relative_id = $father;
			  }
			  //this is the oldest know Male relative: Gerald
			  if( $family_member == 70 ){
				$parent_id = 0;
				$blood_relative_id = 'none';
				$connected_by_marriage = 'no';
			  }

			$lineage    = $fam_multi[$blood_relative_id]['id'].','.$fam_multi[$blood_relative_id]['lineage'];
			$generation = count( array_filter( explode(',', $lineage ) ) ); //array filter removes empty values

            if( ($connected_by_marriage != 'yes') || ( $family_member == 70 ) ) {
                $fam_multi[$family_member] = array(
                    'id'                    => $family_member,
                    'full_name'             => $first_name . ' ' . $last_name,
                    'blood_relative_id'     => $blood_relative_id,
                    'parent_id'             => $parent_id,
                    'spouse_id'             => $spouse_id,
                    'dob'                   => $dob,
                    'lineage'				=> $lineage,
                    'generation'            => $generation,
                    'connected_by_marriage' => $connected_by_marriage,
                );
            }

		}

	  	//print_r($fam_multi);

	  ?>


<?php

    //count how many people in each generation
    foreach($fam_multi as $fam_member){
        $level = $fam_member['generation'];

        if($generation_totals[$level]){
            $generation_totals[$level]['count']++;
        }else{
            $generation_totals[$level] = array();
            $generation_totals[$level]['count'] = 0;
        }
    }


?>




<?php
//nest family in parent-child relationship
function buildTree($flat, $pidKey, $idKey = null)
{
    $grouped = array();
    foreach ($flat as $sub){
        $grouped[$sub[$pidKey]][] = $sub;
    }

    $fnBuilder = function($siblings) use (&$fnBuilder, $grouped, $idKey) {
        foreach ($siblings as $k => $sibling) {
            $id = $sibling[$idKey];
            if(isset($grouped[$id])) {
                $sibling['children'] = $fnBuilder($grouped[$id]);
            }
            $siblings[$k] = $sibling;
        }

        return $siblings;
    };

    $tree = $fnBuilder($grouped[0]);

    return $tree;
}

//make a copy for reference below
$generation_totals_copy = $generation_totals;

//recursive function to create diagram
function branchOut($branch){

    global $generation_totals;
    global $generation_totals_copy;

    $generation     = $branch['generation'];
    $generation_i   = $generation_totals_copy[$generation]['count'];

    //distribute angles based on total members in generation
    $angle              = 360/($generation_totals[$generation]['count'] + 1);
    $angle              = $angle * $generation_i;

    //determine generation circle width
    $gen_translation    = ( ($generation_totals[$generation]['count'] + 1) * 18.75 )/ $generation;

    //reduce by 1
    $generation_totals_copy[$generation]['count'] = $generation_totals_copy[$generation]['count']-1;

        //print out data
        echo '<li class="relative" data-order="'.$generation.'" data-lineage="'.$branch['blood_relative_id'].'" data-name="'.$branch['full_name'].'" data-id="'.$branch['id'].'" style="transform: rotate('.$angle.'deg) translate('.(($branch['generation']) * $gen_translation  ).'%) rotate(-'.$angle.'deg)">';
            echo '<label>'.$fam_member['full_name'].'</label>';
            if($branch['spouse_id']){
                echo '<span class="spouse" style="transform: rotate('.$angle.'deg) translate(40px) rotate(-'.$angle.'deg)">';
                echo get_the_post_thumbnail( $branch['spouse_id'], 'thumbnail' );
                echo '</span>';
            }
            echo '<div>'.get_the_post_thumbnail( $branch['id'], 'thumbnail' ).'</div>';
        echo '<hr style =style="transform: rotate('.$angle.'deg) translate('.(($branch['generation']) * $gen_translation  ).'%) rotate(-'.$angle.'deg)">';
        echo '</li>';


    //recursive
    if( isset($branch['children']) ) {
        foreach ($branch['children'] as $bc) {
            branchOut($bc);
        }
    }
}
?>


    <div id="family_tree">

        <ul class="roundtree">

            <?php
                $family_tree    = buildTree($fam_multi, 'parent_id', 'id');
                $branch_i       = 1;
                $lineage        = '0';

                foreach($family_tree as $branch){
                    branchOut($branch, $generation_totals);
                }
            ?>

        </ul>

    </div>

<div id="rel_label">Hello!</div>

<?php
    //var_dump($generation_totals_copy);
    //var_dump($generation_totals);
?>

<script>

    var relatives  = [].slice.call( document.getElementsByClassName( "relative" ) ),
        rel_label  = document.getElementById('rel_label');

    function showLineage(){
        id = this.getAttribute('data-id');

        for (var i = 0; i < relatives.length; i++) {
            lineage = relatives[i].getAttribute('data-lineage');

            //remove class
            relatives[i].classList.remove('linked');
            relatives[i].classList.remove('unlinked');
            document.body.classList.add('connections');

            //add class if related
            if( (lineage) && (lineage == id) ){
                relatives[i].classList.add('linked');
            }
        }

        //add class to this relative
        rel_label.innerHTML = '<span>'+(this.getAttribute('data-name'))+'</span>';
        this.classList.add('linked');
    }

    function hideLineage(){
        document.body.classList.remove('connections');
        rel_label.innerHTML = '';
        console.log('hide');
    }


    window.addEventListener('mousemove', function(event){
        x = event.clientX;
        y = event.clientY;
        if ( typeof x !== 'undefined' ){
            rel_label.style.left = x + 50 + "px";
            rel_label.style.top = y + 0 + "px";
        }
    }, false);

    //get relative info on hover
    for (var i = 0; i < relatives.length; i++) {
        relatives[i].addEventListener('mouseover', showLineage, false);
        relatives[i].addEventListener('mouseout', hideLineage, false);

    }

    document.body.addEventListener('click', hideLineage, true);


</script>

<?php get_footer(); ?>