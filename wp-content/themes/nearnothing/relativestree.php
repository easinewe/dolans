<?php
/**
 * Template Name: Relativestree
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 ?>
<!--
functions used
buildTree_blood
make_list_final4

-->

<?php get_header(); ?>

<link href="<?php echo get_template_directory_uri(); ?>/relatives_css/tree.css" rel="stylesheet" type="text/css">

<?php

    $fam_list_1         = array();
    $non_blood          = array();
    $fam_multi_sorted   = array();



//separate non-blood relatives
    $args = array(
      'post_type'       => 'relative',
	  'posts_per_page'  => -1,
	  'numberposts'     => -1,
	  'orderby'         => 'meta_value',
	  'meta_key'        => 'relative_birth_dob',
	  'order'   		=> 'ASC', /*oldest to youngest*/
    );

    //start building the arrays
	$relatives = get_posts( $args );
	//echo '<pre>'.var_dump($relatives).'</pre>';

	foreach($relatives as $relative){

	    $relative_id    = $relative->ID;
        $father         = get_post_meta( $relative_id, 'parental_units_father', true );
        $mother         = get_post_meta( $relative_id, 'parental_units_mother', true );

        //no parents listed, keep track in the non-blood array
        if( $mother == 'none' && $father == 'none' ){
            array_push ($non_blood, $relative_id);
        }

        //put everyone in these arrays
        array_push ($fam_list_1, $relative_id);
	}



	$fam_multi = array();

	    foreach ($fam_list_1 as $family_member){

	      $id               = get_the_ID();
		  $father           = get_post_meta( $family_member, 'parental_units_father', true );
		  $mother           = get_post_meta( $family_member, 'parental_units_mother', true );
		  $spouse_id        = get_post_meta( $family_member, 'spouse', true );
		  $spouse_first     = get_post_meta( $spouse_id, 'relative_name_first', true );
		  $spouse_last      = get_post_meta( $spouse_id, 'relative_name_last', true );
		  $spouse_name      = $spouse_first.' '.$spouse_last;
		  $spouse_gender    = get_post_meta( $spouse_id, 'relative_name_gender', true );

		  $oldest_male_id   = 1284;
          $oldest_female_id = 1285;

		  $connected_by_marriage = 'no';
		  
			  //determine who the blood connection is inherited from
			  if( in_array($mother, $non_blood) ){  
				$parent_id              = $father;
				$blood_relative_id      = $father;
			  } else {
				$parent_id              = $mother;
				$blood_relative_id      = $mother;
			  }
			  //relatives who are tied to the family via marriage
			  if( ($father == 'none') && ($mother == 'none') && ($spouse !='none') ) {
				$parent_id              = $spouse;
				$blood_relative_id      = $spouse;
				$connected_by_marriage  = 'yes';
			  }
			  //relatives who mothered child but are separated
//            if( ($father == 'none') && ($mother == 'none') && ($spouse ='none') && ($id != $oldest_male_id || $oldest_female_id) ) {
//                $parent_id              =  61; //search all for mother with their id /
//                $blood_relative_id      =  61;
//                $connected_by_marriage  = 'no';
//            }

			  //these people are relatives with no mother, perhaps from divorce
			  if( ($mother == 'none') && ($father != 'none') ) {
				$parent_id              = 'none';
				$blood_relative_id      = $father;
			  }
			  //these people are relatives with no father, perhaps from divorce
              if( ($father == 'none') && ($mother != 'none') ) {
                $parent_id              = 'none';
                $blood_relative_id      = $mother;
              }
			  //this is the oldest know Male relative: James
			  if( $family_member == $oldest_male_id ){
				$parent_id              = 'none';
				$blood_relative_id      = 'none';
				$connected_by_marriage  = 'no';
			  }	
			  //this is the oldest known female relative: Fannie
			  if( $family_member == $oldest_female_id ){
				$parent_id              = $oldest_male_id;
				$blood_relative_id      = $oldest_male_id;
				$connected_by_marriage  = 'yes';
			  }	
		  
		  $first_name       = get_post_meta( $family_member, 'relative_name_first', true );
		  $last_name        = get_post_meta( $family_member, 'relative_name_last', true );
		  $full_name        = $first_name.' '.$last_name;
		  $gender           = get_post_meta( $family_member, 'relative_name_gender', true );
		  $lat              = get_post_meta( $family_member, 'relative_location_lat', true );
		  $long             = get_post_meta( $family_member, 'relative_location_long', true );
		  $dob              = get_post_meta( $family_member, 'relative_birth_dob', true );
		  $single_page      = $id;

		  
				$fam_multi[$family_member] = array(
					'id'                    => $family_member,
					'first'                 => $first_name,
					'last'                  => $last_name,
					'full_name'             => $full_name,
					'gender'                => $gender,
					'blood_relative_id'     => $blood_relative_id,
					'parent_id'             => $parent_id,
					'father_id'             => $father,
					'mother_id'             => $mother,
					'spouse_id'             => $spouse_id,
					'spouse_first'          => $spouse_first,
					'spouse_last'           => $spouse_last,
					'spouse_name'           => $spouse_name,
					'spouse_gender'         => $spouse_gender,
					'connected_by_marriage' => $connected_by_marriage,
					'dob'                   => $dob,
				);
		}
		



	    //push the married relatives to the front
		foreach ($fam_multi as $family_member){
		  if($family_member['connected_by_marriage'] == 'yes'){
		      array_unshift($fam_multi_sorted, $family_member); //move to beginning of array
		  } else {
		      array_push($fam_multi_sorted, $family_member); //move to end of array
		  }
		}

		//var_dump($fam_multi_sorted);

//		  function make_list_final2($arr){
//				$return  = empty($arr['id'])?'container<br/>':'';
//				$return .= !empty($arr['id'])?'begin'.$arr['id'].'<br/>':'';
//
//				foreach ($arr as $key => $value){
//						$return .= !is_array($value)? $key.': '.$value.'<br>':'';
//						$return .= is_array($value)? '<br/> '.make_list_final2($value): '';
//				}
//
//				$return .= empty($arr['id'])?'/container<br/>':'';
//				$return .= !empty($arr['id'])?'end'.$arr['id'].'<br/>':'';
//
//			return $return;
//		  }


        //build family tree from array by blood - recursive
        function buildTree_blood(array $elements, $bloodRelativeId = 0) {
            //we are starting with default value of zero / zero is equal to null

		    //create new empty array
		    $branch = array();
		    //loop through each array element
            foreach ($elements as $element) {
                //check if child is connected by marriage
                if ( $element['blood_relative_id'] == $bloodRelativeId ) {
                    //looping through everything again looking for blood relative id == this id/ may return value
                    $children = buildTree_blood($elements, $element['id']);
                    if ($children) {
                        $element['children'] = $children;
                    }
                    $branch[] = $element;
                }
            }

            return $branch;
        }

		//echo (0=='null')?'yes':'no';

        function make_list_final4($arr){

		        $nonBloodParentId = '';

				$output  = empty($arr['id'])?'<ul class=c>':'';
                $output .= ( !empty($arr['id']) && ($arr['connected_by_marriage']=='no') )?'<li>':'';

				foreach ($arr as $key => $value){

                //get non-blood relative
                    if($arr['blood_relative_id'] == $arr['mother_id']){
                        $nonBloodParentId =  $arr['father_id'];
                    }else{
                        $nonBloodParentId =  $arr['mother_id'];
                    }



				
              //case 1: anyone who is a blood relative, we print out their information
                $output .= ( !is_array($value) && $key=='full_name'
												&& ($arr['connected_by_marriage']=='no') )?

                    '<div class="p1">
					  <a href="'.get_the_permalink($nonBloodParentId).'" id="'.$nonBloodParentId.'" class="'.$arr['spouse_gender'].'" rel="content">
						<div class="tree-thumbnail">'.
                    get_the_post_thumbnail( $nonBloodParentId, 'thumbnail' )
                    .'</div>						  
						<div class="tree-detail">'.$arr['mother_first'].'<br/>'.$arr['mother_last'].'</div>
					  </a>
				  </div>
				  <a href="'.get_the_permalink($arr['id']).'" id="'.$arr['id'].'" class="'.$arr['gender'].'" rel="content">
                      <div class="tree-thumbnail">'.
                          get_the_post_thumbnail( $arr['id'], 'thumbnail' )
                      .'</div>
                      <div class="tree-detail">'.$arr['first'].'<br/>'.$arr['last'].'</div>
				  </a>':
				  '';
				  
				  //case 2: anyone who is a blood relative, AND has a spouse, we print out the SPOUSES information
//                    $output .= ( !is_array($value) &&   $key=='full_name'
//				  								 && ( $arr['connected_by_marriage']=='no')
//												 && ( !empty($arr['spouse_id']) ) )
//												 && ( $arr['spouse_id']!='none' )?
//				  '<div class="p1">
//					  <a href="'.get_the_permalink($arr['spouse_id']).'" id="'.$arr['id'].'" class="'.$arr['spouse_gender'].'" rel="content">
//						<div class="tree-thumbnail">'.
//						get_the_post_thumbnail( $arr['spouse_id'], 'thumbnail' )
//						.'</div>
//						<div class="tree-detail">'.$arr['spouse_first'].'<br/>'.$arr['spouse_last'].'</div>
//					  </a>
//				  </div>':
//				  '';



				 //case 3: it is an array 
                    $output .= is_array($value)? make_list_final4($value): '';
				}

                $output .= empty($arr['id'])?'</ul>':'';
                $output .= ( !empty($arr['id']) && ($arr['connected_by_marriage']=='no') )?'</li>':'';

			return $output;
		  }

?>

        <div id="family_tree">
            <div id="dynamic_family_tree" class="tree">
                <ul>
                    <?php
                        $fam_tree     = buildTree_blood($fam_multi_sorted);
                        $tree_output  = make_list_final4($fam_tree);
                        echo '<pre>';
                            echo var_dump($fam_tree);
                        echo '</pre>';
                        echo $tree_output;
                    ?>
                </ul>
            </div>
        </div>


<?php get_footer(); ?>



<script>

    //get variables
    var family_tree_ul = document.getElementsByClassName("c"),
        family_tree_ul = family_tree_ul[0],
        family_tree_container = document.getElementById('family_tree'),
        family_tree_half_width = family_tree_ul.offsetWidth/3;

    //scroll to center of tree
    family_tree_container.scrollLeft = family_tree_half_width;

</script>

