<?php
/**
 * Template Name: Relativestree_round
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
    .roundtree li label{
        opacity: 0;
        position: fixed;
        top: 50%;
        left: 50%;
    }

    .roundtree li div{
        width: 80px;
        height: 80px;
        border-radius: 40px;
        overflow: hidden;
        background-color: red;
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

    #relative_name{
        position: fixed;
        bottom: 50px;
        left: 50px;
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

    $fam_list_1 = array();
	$fam_list_2 = array();
	$non_blood = array();
  
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
	  array_push ($fam_list_2, $relative_id);
	  }
	}
?>





	  <?php 
	  	$fam_multi = array();

	  	foreach ($fam_list_1 as $family_member){

		  $father 					= get_post_meta( $family_member, 'parental_units_father', true ); 
		  $mother 					= get_post_meta( $family_member, 'parental_units_mother', true );
		  $spouse_id 				= get_post_meta( $family_member, 'spouse', true );
		  $spouse_first 			= get_post_meta( $spouse_id, 'relative_name_first', true );
		  $spouse_last 				= get_post_meta( $spouse_id, 'relative_name_last', true );
		  $spouse_name 				= $spouse_first.' '.$spouse_last;
		  $spouse_gender 			= get_post_meta( $spouse_id, 'relative_name_gender', true );
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
		  
			
			$first_name 	= get_post_meta( $family_member, 'relative_name_first', true );
			$last_name 		= get_post_meta( $family_member, 'relative_name_last', true );
			$gender 		= get_post_meta( $family_member, 'relative_name_gender', true );
			$lat 			= get_post_meta( $family_member, 'relative_location_lat', true );
			$long 			= get_post_meta( $family_member, 'relative_location_long', true );
			$dob 			= get_post_meta( $family_member, 'relative_birth_dob', true ); 

			$lineage        = $fam_multi[$blood_relative_id]['id'].','.$fam_multi[$blood_relative_id]['lineage'];

			$fam_multi[$family_member] = array(
				'id' 					=> $family_member,
				'first' 				=> $first_name,
				'last' 					=> $last_name,
				'full_name' 			=> $first_name.' '.$last_name,
				'gender' 				=> $gender,
				'blood_relative_id' 	=> $blood_relative_id, 
				'parent_id' 			=> $parent_id, 
				'father_id' 			=> $father,
				'mother_id' 			=> $mother,           
				'spouse_id' 			=> $spouse_id,
				'spouse_first' 			=> $spouse_first,
				'spouse_last' 			=> $spouse_last,
				'spouse_name' 			=> $spouse_name,
				'spouse_gender' 		=> $spouse_gender,
				'dob' 					=> $dob,
				'lineage'				=> $fam_multi[$blood_relative_id]['id'].','.$fam_multi[$blood_relative_id]['lineage'],
				'connected_by_marriage' => $connected_by_marriage,
			);


		}
				
	  ?>



	  <?php
		$fam_multi_sorted = array();
		foreach ($fam_multi as $family_member){
		
		//convert comma separated lineage to array
		$level = explode(',',$family_member['lineage']);
		$family_member['lineage'] = array_filter($level);


		//push the married relatives to the front
		if($family_member['connected_by_marriage'] == 'yes'){
			array_unshift($fam_multi_sorted, $family_member);
		  } else {
			   array_push($fam_multi_sorted, $family_member);
		  }
		
		}
      ?>

        <?php
            //count the groups
            $generation_totals = array();
            foreach($fam_multi_sorted as $fam_member){
                $level = count($fam_member['lineage']);
                if($generation_totals[$level]){
                    $generation_totals[$level]['count']++;
                }else{
                    $generation_totals[$level] = array();
                    $generation_totals[$level]['count'] = 0;
                }
            }
            //var_dump($generation_totals);
        ?>




    <div id="family_tree">

      <ul class="roundtree">
        <?php
        $i = 1;
        $lineage = '0';
        foreach($fam_multi_sorted as $fam_member){
		    if($fam_member['connected_by_marriage'] != 'yes'){

                //count out member of each generation
		        $fam_member_lineage = count($fam_member['lineage']);
                if($fam_member_lineage == $lineage){
                    $i++;
                }else{
                    //new generation
                    $i = 0;
                    $lineage = $fam_member_lineage;
                }

                //distribute angles based on total members in generation
                $angle_parsed   = 360/($generation_totals[$fam_member_lineage]['count']);
                $angle          = ($angle_parsed * $i);

                //print out data
                echo '<li data-order="'.$i.'" data-lineage="'.$fam_member['blood_relative_id'].'" data-name="'.$fam_member['full_name'].'" style="transform: rotate('.$angle.'deg) translate('.((count($fam_member['lineage']))*230).'%) rotate(-'.$angle.'deg)">';
                    echo '<label>'.$fam_member['full_name'].'</label>';
                    if($fam_member['spouse_id']){
                        echo '<span class="spouse" style="transform: rotate('.$angle.'deg) translate(40px) rotate(-'.$angle.'deg)">';
                            echo get_the_post_thumbnail( $fam_member['spouse_id'], 'thumbnail' );
                        echo '</span>';
                    }
                    echo '<div>'.get_the_post_thumbnail( $fam_member['id'], 'thumbnail' ).'</div>';
                echo '</li>';



            }
        }
	  ?>
      </ul>

        <div id="relative_name">the name</div>

    </div>



    <script>

    function getName(){
        var name = document.getElementById('relative_name');
        name.innerHTML = this.dataset.name;
    }

    [].slice.call( document.querySelectorAll('#family_tree li') ).forEach(function(relative){
        relative.addEventListener('mouseover', getName, false);
    });

</script>
	
        
<?php get_footer(); ?>