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
      <div id=fp_fullscreen_image></div>

      <div id="doormat"></div>
      
      <div class="down_arrow"></div>

      <div id="welcome_slide" class="fullscreen" style="background-color: rgba(30,30,30,0.82);">
            <div class="greeting_container">
              <h4>The Dolan Family Website</h4>
              <h1 id="galic_greeting" class="fp_greeting">Tráthnóna maith duit</h1>
              <span class="arrow arrow_down"><h1>&#8595;</h1></arrow>
            </div>
      </div>
      
      <div id="family_tree_slide" class="fullscreen" style="background-color: rgba(165, 38, 41, 0.73);">
            <a href="<? echo get_permalink( get_page_by_title( 'family tree' ) ); ?>">
              <div class="greeting_container">
                <h4>DOLAN FAMILY TREE</h4>
                <h1 class="fp_greeting">Your Roots are Showing...</h1>
                <span class="arrow arrow_right"><h1>&#8594;</h1></arrow>
              </div>
            </a>
      </div>
      
      <div id="reunion_slide" class="fullscreen" style="background-color: rgba(30,30,30,0.82);">
            <a href="<? echo get_permalink( get_page_by_title( 'reunion' ) ); ?>">
              <div class="greeting_container">
                <h4>Dolan Reunion in Ireland</h4>
                <h1>Sunday<br/>August 21<br/>2016</h1>
                <span class="arrow arrow_right"><h1>&#8594;</h1></arrow>
              </div>
            </a>  
		</div>

      <div id="photos_slide" class="fullscreen" style="background-color: rgba(165, 38, 41, 0.73);">
            <a href="<? echo get_permalink( get_page_by_title( 'photos' ) ); ?>">
              <div class="greeting_container">
                <h4>Photos of the Dolans</h4>
                <h1 class="fp_greeting">CHEESE!</h1>
                <span class="arrow arrow_right"><h1>&#8594;</h1></arrow>
              </div>
            </a>
      </div>
      
      <div id="memories_slide" class="fullscreen" style="background-color: rgba(30,30,30,0.82);">
            <a href="<? echo get_permalink( get_page_by_title( 'memories' ) ); ?>">
              <div class="greeting_container">
                <h4>DOLAN FAMILY Memories</h4>
                <h1 class="fp_greeting">READ ALL ABOUT IT</h1>
                <span class="arrow arrow_right"><h1>&#8594;</h1></arrow>
              </div>
            </a>
      </div>

      
      <div id="tree" class="fullscreen" style="background-color: rgba(165, 38, 41, 0.73);">
            <a href="<? echo get_permalink( get_page_by_title( 'map' ) ); ?>">
              <div class="greeting_container">
                <h4>DOLAN FAMILY MAP</h4>
                <h1 class="fp_greeting">THEY'RE EVERYWHERE!</h1>
                <span class="arrow arrow_right"><h1>&#8594;</h1></arrow>
              </div>
            </a>
      </div>
      
      <div id="tree" class="fullscreen" style="background-color: rgba(30,30,30,0.82);">
            <a href="http://dolansofcavan.com/wp-admin/">
              <div class="greeting_container">
                <h4>SIGN IN</h4>
                <h1 class="fp_greeting">ADD RELATIVES, PHOTOS, OR MEMORIES</h1>
                <span class="arrow arrow_right"><h1>&#8594;</h1></arrow>
              </div>
            </a>
      
      </div>
            <div id="tree" class="fullscreen" style="background-color: rgba(165, 38, 41, 0.73);">
            <a href="mailto:eamonnfitzmaurice@gmail.com">
              <div class="greeting_container">
                <h4>NEED MORE INFORMATION</h4>
                <h1 class="fp_greeting">CONTACT US</h1>
                <span class="arrow arrow_left"><h1>&#8592;</h1></arrow>
              </div>
            </a>
      </div>

	

	<?php /* footer */ get_footer(); ?>