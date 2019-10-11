<?php
/**
 * Template Name: Relatives
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 ?>

<?php get_header(); ?>

<?php
    $args = array(
      'post_type' => 'relative',
	  "posts_per_page" => -1,
	  'numberposts' => -1,
	  'orderby' 		=> 'title',
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
		
		$relative_id = get_the_ID(); 
		$father = get_post_meta( get_the_ID(), 'parental_units_father', true ); 
		$mother = get_post_meta( get_the_ID(), 'parental_units_mother', true );
	
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

	//build ul/li relationship from array
	function make_list($arr)
		{
			$return = '<ul id="'.$arr[full_name].'" class="'.$arr[id].' array">';
			foreach ($arr as $key => $value)
			
			{
				if($key == 'full_name' || is_array($value)){
					$return .= '<li class="'.( ($key == 'full_name')? $arr[connected_by_marriage]: '' ).'">'; 
					$return .= (is_array($value) ? make_list($value) : ( ($key == 'full_name')? $value: '') );
					$return .= '</li>';
				}
			}
			$return .= '</ul>';
			return $return;
		}
		
	//build ul/li relationship from array
	function make_list_divs($arr)
		{
			$return = '<ul id="'.$arr[full_name].'" class="'.$arr[id].' array"><div class="p1">';
			foreach ($arr as $key => $value)
			
			{
				if($key == 'full_name' || is_array($value)){
					$return .= '<div class="children '.( ($key == 'full_name')? $arr[connected_by_marriage]: '' ).'">'; 
					$return .= (is_array($value) ? make_list($value) : ( ($key == 'full_name')? $value: '') );
					$return .= '</div>';
				}
			}
			$return .= '</ul>';
			return $return;
		}

?>
<style type="text/css">
ul {
 padding: 5px; !important;	
}
li.yes,
li.no {
	margin-bottom: 0px;
	margin-top: 0px;
	width: 200px;
	height: 10px;
	background-color: rgb(102, 209, 239);
	padding: 10px;
}
.yes{
	margin-left: -10px !important;	
	margin-top: -10px !important;
	padding-top: 0px !important;

}
</style>



  <div id='content'>
    <div id="media-gallery">
	  


	  <?php 
	  	$fam_multi = array();
		foreach ($fam_list_1 as $family_member){

	
		  $father = get_post_meta( $family_member, 'parental_units_father', true ); 
		  //$father = ($father == 'none')? 'no father': $father;
		  $mother = get_post_meta( $family_member, 'parental_units_mother', true );
		  //$mother = ($mother == 'none')? 'no mother': $mother;
		  $spouse = get_post_meta( $family_member, 'spouse', true );
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
			  }	
			  //this is the oldest known female relative: Fannie
			  if( $family_member == 69 ){
				$parent_id = '70';
				$blood_relative_id = '70';
				$connected_by_marriage = 'yes';
			  }	
		  
		  $first_name = get_post_meta( $family_member, 'relative_name_first', true );
		  $last_name = get_post_meta( $family_member, 'relative_name_last', true );
		  $full_name = $first_name.' '.$last_name;
		  $lat = get_post_meta( $family_member, 'relative_location_lat', true );
		  $long = get_post_meta( $family_member, 'relative_location_long', true );

		  
				$fam_multi[$family_member] = array(
					'id' => $family_member,
					'first' => $first_name,
					'last' => $last_name,
					'full_name' => $full_name,
					'blood_relative_id' => $blood_relative_id, 
					'parent_id' => $parent_id, 
					'father_id' => $father,           
					'mother_id' => $mother,           
					'spouse' => $spouse,
					'connected_by_marriage' => $connected_by_marriage, 
					'lat' => $lat, 
					'long' => $long, 
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
           
      <?php
		$fam_tree = buildTree_blood($fam_multi_sorted);
		//print_r( $fam_multi );
		echo make_list($fam_tree);
	  ?>

    </div>
  </div>
<?php get_footer(); ?>