<?php
/**
 * Template Name: Front Page Scroll
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


    <div id="waterfall">
        <div class=titlebox>
            <h1 id="title">DOLAN</h1>
        </div>
        <?php
        $images = get_images_from_media_library(300);
        $count  = 0;

        foreach($images as $image){

            $random		= rand(0,1);
            $rand_size  = ($random)?'medium':'large';
            $rand_z     = ($random)?100:-100;
            $src 		= wp_get_attachment_image_src((int)$image,$rand_size);

            if($src){
                //ensure first photo is always behind logo
                $rand_z = ($count == 0)?-1:$rand_z;
                echo '<img data-count="'.$count.'" src="'.$src[0].'" style="left:50%; z-index:'.$rand_z.'">';
                $count++;
            }

        }
        ?>
    </div>
		
	<script>

        //VARIABLES
        var screen_x  = screen.width,
            screen_y  = screen.height,
            waterfall = document.querySelector('#waterfall'),
            photos    = Array.prototype.slice.call(waterfall.querySelectorAll('img'));

        //FUNCTIONS

        //wait until are images are loaded
        document.addEventListener("DOMContentLoaded", function(){
            // Images loaded is zero because we're going to process a new set of images.
            var imagesLoaded = 0;
            // Total images is still the total number of <img> elements on the page.
            var totalImages = photos.length;

            // Step through each image in the DOM, clone it, attach an onload event listener, then set its source to the source of the original image.
            // When that new image has loaded, fire the imageLoaded() callback.

            photos.forEach(function(photo){

                // img is a generic <img> element that is not rendered to the DOM.
                var newImg = document.createElement("img");

                // When the image is loaded, call imageLoaded() function.
                newImg.addEventListener('load', imageLoaded);

                // Set the source of the new image to match that of the <img> element that has been rendered to the DOM.
                var src = photo.getAttribute('src');
                newImg.setAttribute('src', src);

            });

            // Increment the loaded count and if all are loaded, call the allImagesLoaded() function.
            function imageLoaded() {
                imagesLoaded++;
                if (imagesLoaded == totalImages) {
                    allImagesLoaded();
                }
            }

            function allImagesLoaded() {
                console.log('ALL IMAGES LOADED');
                arrangePhotos();
            }
        });

        //get random number between to set numbers
		function generateRandom(min, max){
            var random = Math.floor(Math.random() * (+max - +min)) + +min;
			return random
		}

        //set random coordinates for image without displaying offscreen
        function arrangePhotos(){
            var top_start   = screen_y * 0.2, //distance from top to start photos
                overlap_y   = 0.2;

            photos.forEach(function(photo) {
                var photo_x = photo.offsetWidth,
                    photo_y = photo.offsetHeight,
                    x_max   = (screen_x/2) - photo_x, //furthest left image can be placed without being offscreen
                    rand_x  = generateRandom(-(screen_x/2), x_max), //random placement from left
                    overlap = photo.offsetHeight * overlap_y;

                    console.log("screen_x:" + screen_x + ", photo_x:" + photo_x + ", x_max:" + x_max);

                //set photo position
                photo.style.top         = top_start + 'px';
                photo.style.marginLeft  = rand_x + 'px';
                photo.style.opacity     = 1;
                top_start               = (photo_y + top_start) - overlap;
            });

		}


	</script>	

	

	<?php /* footer */ get_footer(); ?>