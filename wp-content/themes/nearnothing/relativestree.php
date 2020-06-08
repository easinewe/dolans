<?php
/**
 * Template Name: Relativestree
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 ?>

<?php get_header(); ?>

<link href="<?php echo get_template_directory_uri(); ?>/relatives_css/tree.css" rel="stylesheet" type="text/css">

<?php
    $args = array(
      'post_type'       => 'relative',
	  'posts_per_page'  => -1,
	  'numberposts'     => -1,
	  'orderby'         => 'meta_value',
	  'meta_key'        => 'relative_birth_dob',
	  'order'   		=> 'ASC',
    );

    $fam_list_1 = array();
	$fam_list_2 = array();
	$non_blood = array();
  
    //start building the arrays
	$relatives = new WP_Query( $args );
	if( $relatives->have_posts() ) {
	  while( $relatives->have_posts() ) {
		$relatives->the_post();
		
		$relative_id    = get_the_ID();
		$father         = get_post_meta( get_the_ID(), 'parental_units_father', true );
		$mother         = get_post_meta( get_the_ID(), 'parental_units_mother', true );
	
	  //no parents listed, keep track in the non-blood array	
	  if( $mother == 'none' && $father == 'none' ){
		array_push ($non_blood, $relative_id);
	  }
	  
	  //put everyone in these arrays
	  array_push ($fam_list_1, $relative_id);
	  array_push ($fam_list_2, $relative_id);
	  }
	}
?>

<?php
      
	  //build family tree from array by blood
	  function buildTree_blood(array $elements, $bloodRelativeId = 0) {
          $branch = array();
      
          foreach ($elements as $element) {
			  //check if child is connected by marriage
			  if ( $element['blood_relative_id'] == $bloodRelativeId ) {
                  $children = buildTree_blood($elements, $element['id']);
                  if ($children) {
						$element['children'] = $children;
                  }
                  $branch[] = $element;
              } 
		  }
      
          return $branch;
      }


?>





	  <?php $fam_multi = array();

	    foreach ($fam_list_1 as $family_member){

	      $id               = get_the_ID();
		  $father           = get_post_meta( $family_member, 'parental_units_father', true );
		  //$father         = ($father == 'none')? 'no father': $father;
		  $mother           = get_post_meta( $family_member, 'parental_units_mother', true );
		  //$mother         = ($mother == 'none')? 'no mother': $mother;
		  $spouse_id        = get_post_meta( $family_member, 'spouse', true );
		  $spouse_first     = get_post_meta( $spouse_id, 'relative_name_first', true );
		  $spouse_last      = get_post_meta( $spouse_id, 'relative_name_last', true );
		  $spouse_name      = $spouse_first.' '.$spouse_last;
		  $spouse_gender    = get_post_meta( $spouse_id, 'relative_name_gender', true );

		  $connected_by_marriage = 'no';
		  
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
				$parent_id = 'none';
				$blood_relative_id = $father;
			  }
			  //this is the oldest know Male relative: Gerald
			  if( $family_member == 70 ){
				$parent_id = 'none';
				$blood_relative_id = 'none';
				$connected_by_marriage = 'no';

			  }	
			  //this is the oldest known female relative: Fannie
			  if( $family_member == 69 ){
				$parent_id = '70';
				$blood_relative_id = '70';
				$connected_by_marriage = 'yes';
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
		

	  ?>


      <?php
	    //push the married relatives to the front
		$fam_multi_sorted = array();
		foreach ($fam_multi as $family_member){
		  if($family_member['connected_by_marriage'] == 'yes'){
		      array_unshift($fam_multi_sorted, $family_member);
		  } else {
		      array_push($fam_multi_sorted, $family_member);
		  }
		}
	  ?>




        <?php ////learn from this
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
//		   ?>




        <?php function make_list_final4($arr){
				$return .= empty($arr['id'])?'<ul class=c>':'';
				$return .= ( !empty($arr['id']) && ($arr['connected_by_marriage']=='no') )?'<li>':'';

				foreach ($arr as $key => $value){
				
				  //case 1: anyone who is a blood relative, we print out their information
				  $return .= ( !is_array($value) && $key=='full_name' 
												&& ($arr['connected_by_marriage']=='no') )? 
				  '<a href="'.get_the_permalink($arr['id']).'" id="'.$arr['id'].'" class="'.$arr['gender'].'" rel="content">
                      <div class="tree-thumbnail">'.
                          get_the_post_thumbnail( $arr['id'], 'thumbnail' )
                      .'</div>
                      <div class="tree-detail">'.$arr['first'].'<br/>'.$arr['last'].'</div>
				  </a>':
				  '';
				  
				  //case 2: anyone who is a blood relative, AND has a spouse, we print out the SPOUSES information
				  $return .= ( !is_array($value) &&   $key=='full_name'
				  								 && ( $arr['connected_by_marriage']=='no')
												 && ( !empty($arr['spouse_id']) ) )
												 && ( $arr['spouse_id']!='none' )?
				  '<div class="p1">
					  <a href="'.get_the_permalink($arr['spouse_id']).'" id="'.$arr['id'].'" class="'.$arr['spouse_gender'].'" rel="content">
						<div class="tree-thumbnail">'.
						get_the_post_thumbnail( $arr['spouse_id'], 'thumbnail' )
						.'</div>						  
						<div class="tree-detail">'.$arr['spouse_first'].'<br/>'.$arr['spouse_last'].'</div>
					  </a>
				  </div>':
				  '';

				 //case 3: it is an array 
				  $return .= is_array($value)? make_list_final4($value): '';
				}
				
				$return .= empty($arr['id'])?'</ul>':'';
				$return .= ( !empty($arr['id']) && ($arr['connected_by_marriage']=='no') )?'</li>':'';

			return $return;
		  }
		   ?>


        <div id="family_tree">
            <div id="dynamic_family_tree" class="tree">
                <ul>
                    <?php
                        $fam_tree     = buildTree_blood($fam_multi_sorted);
                        $tree_output  = make_list_final4($fam_tree);
                        echo $tree_output;
                    ?>
                </ul>
            </div>
        </div>


<?php get_footer(); ?>



<script>

    //get the first ul with a class of c"
    var family_tree_ul = document.getElementsByClassName("c");
    var family_tree_ul = family_tree_ul[0];

    //get the family tree
    var family_tree_container = document.getElementById('family_tree');

    //scroll the family tree to the center to show first family member
    var family_tree_half_width = family_tree_ul.offsetWidth/3;
    family_tree_container.scrollLeft = family_tree_half_width;

</script>

