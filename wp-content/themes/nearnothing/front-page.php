<?php
/**
 * Template Name: Front Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 ?>
	
	<style type="text/css">
	  #fp_fullscreen_image{
		 background-image: url('<?php echo display_frontpage_images_from_media_library('full'); ?>') !important; 
	  }
    </style>

    <?php get_header(); ?>


	<style>
		body{
			background-color: black;
			height: 3000vh;
		}
		div.titlebox {
    		height: 100vh;
			width: 100vw;
    		position: fixed 
		}
		div.titlebox h1 {
			margin: 0;
			color: white;
			font-size: 10vw;
			position: absolute;
			top: 50%;
			left: 50%;
			margin-right: -50%;
			transform: translate(-50%, -100%); 
			z-index: 1;
			opacity: 1;
		}
		#counter{
			position: fixed;
			top: 50%;
			color: white;
		}
	</style>	
	
	<div class=titlebox>
	  <h1 id="title">DOLAN</h1>
	</div>
	
	<?php
	$images = get_images_from_media_library(300);

	foreach($images as $image){
		$rand_top 	= rand(100,3000);
		$rand_left 	= rand(10,90);
		$rand_z		= rand(-1,1);
		$src 		= wp_get_attachment_image_src((int)$image,'full')[0];
		if($src){
			echo '<img class="face" src="'.$src.'" style="top:'.$rand_top.'vh; left:'.$rand_left.'vw; z-index:'.$rand_z.';" >';
		}

	}
	?>
		
	<script>
	
		//get random number
		function generateRandom(){
			var min=50; 
    		var max=80;  
    		var random = Math.floor(Math.random() * (+max - +min)) + +min; 
			
			return random
		}
		
		function bringToFront(){
			this.style.zIndex = 100;
			console.log(this);
		}
				
				
	</script>	

	

	<?php /* footer */ get_footer(); ?>