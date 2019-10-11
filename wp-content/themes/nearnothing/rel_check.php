<?php
/**
 * Template Name: Rel_Check
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 ?>

<?php get_header(); ?>

<div id='content'>
<ul class="relatives_formation">
<?php
    $args = array(
      'post_type' => 'relative',
	  'numberposts' => -1
    );
    $relatives = new WP_Query( $args );
    
  // If we are in a loop we can get the post ID easily

	if( $relatives->have_posts() ) {
      while( $relatives->have_posts() ) {
        $relatives->the_post();
        ?>
          
              <? $first_name = get_post_meta( get_the_ID(), 'relative_name_first', true ); ?>
              <? $last_name = get_post_meta( get_the_ID(), 'relative_name_last', true ); ?>
			  <? $all_info = get_post_meta( get_the_ID() ); ?>
		  
           <input type='checkbox' value='<?php echo $all_info; ?>'
    		name='attachments[{$post->ID}][custom5]'
    		id='attachments[{$post->ID}][custom5]' />
		  <?php echo $first_name; ?> <?php echo $last_name; ?><br/>
     
        <?php
      }
    }
    else {
      echo 'Oh oh, no relatives!';
	  
    }
  ?>
</ul>  
  
 <?php get_fam_checkbox(); ?> 

</div>
<?php /* footer */ get_footer(); ?>