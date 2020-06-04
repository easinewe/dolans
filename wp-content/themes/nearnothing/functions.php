<?php
global $hello;
$hello = 'hello world';


	function themeslug_enqueue_style() {
		if( is_page_template('Map') ){
			wp_enqueue_style('mapbox_style','https://api.tiles.mapbox.com/mapbox-gl-js/v0.16.0/mapbox-gl.css');
		}
	}
	
	function themeslug_enqueue_script() {
		if( is_page_template('Map') ){
			wp_enqueue_script('mapbox', 'https://api.tiles.mapbox.com/mapbox-gl-js/v0.16.0/mapbox-gl.js');
		}
	}
	
	function wpdocs_scripts_method() {
		wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/js/main.js', array( 'jquery' ) );
		wp_enqueue_script( 'masonry', get_stylesheet_directory_uri() . '/js/masonry.pkgd.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'images_loaded', get_stylesheet_directory_uri() . '/js/imagesloaded.pkgd.min.js', array( 'masonry' ) );
	}
	
	function load_mapboxjs_files() {
		//we can just enqueue the last one because it is dependent on the first
		wp_register_script('mapbox_js','https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.js');
		wp_register_script('marker_cluster','https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js', array('mapbox_js'));
	  	wp_enqueue_script( 'clusters', get_stylesheet_directory_uri() . '/js/clusterfuck.js', array( 'marker_cluster' ) );

		if( is_page_template('Map') ){
			wp_enqueue_script('clusters');
		}
	}

	function register_mapbox_js_stylesheets() {
		  wp_register_style('mapbox_js_css','https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.css');
		  wp_register_style('marker_cluster_css','https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css');
		  wp_register_style('marker_cluster_css_def','https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css');
	}
	function add_mapbox_js_stylesheets(){
		if ( is_page('map') ){ // using page slug
		  wp_enqueue_style('mapbox_js_css');
		  wp_enqueue_style('marker_cluster_css');
		  wp_enqueue_style('marker_cluster_css_def');
		}
	}

	add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_style' );
	add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_script' );
	add_action( 'wp_enqueue_scripts', 'wpdocs_scripts_method' );
	add_action( 'wp_enqueue_scripts', 'load_mapboxjs_files' );
	add_action( 'init', 'register_mapbox_js_stylesheets' ); // should I use wp_print_styles hook instead?
	add_action( 'wp_enqueue_scripts', 'add_mapbox_js_stylesheets' );

   

/* sidebar */
if ( function_exists('register_sidebar') )
    register_sidebar(array('id' => 'sidebar-1'));

/* nav menus */
if ( function_exists( 'register_nav_menu' ) ) {
	register_nav_menu('header_nav', __('Header Navigation Menu'));
	register_nav_menu('footer_nav', __('Footer Navigation Menu'));	
}

/* automatic feed links */
add_theme_support('automatic-feed-links');

/* featured image for post*/
add_theme_support( 'post-thumbnails' );


/* Media Tags for images */
function wptp_add_tags_to_attachments() {
        register_taxonomy_for_object_type( 'post_tag', 'attachment' );
    }
    add_action( 'init' , 'wptp_add_tags_to_attachments' );



/*GET THE IMAGES*/
function get_images_from_media_library($total) {
    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' =>'image',
        'post_status' => 'inherit',
		'meta_query' => array(
			array(
			 'key' 		=> '_fp_checkbox',
			 'value'   	=> 1,
			 'compare' 	=> '='
			)
		),
		'posts_per_page' => ($total)?$total:-1,
		'numberposts' => ($total)?$total:-1,
        'orderby' => 'random'
    );
    $query_images = new WP_Query( $args );
    $images = array();
    foreach ( $query_images->posts as $image) {
        $images[]= $image->ID;
    }
    return $images;
	//var_dump($images);
}

/*SHOW THE IMAGES*/
function display_images_from_media_library() {

	$imgs = get_images_from_media_library();
	$html = '';
	
	foreach($imgs as $img) {
		
		$image_categories = get_the_category_list( $img );
		$thumb_img = get_post( $img ); // Get post by ID
		$image_description = $thumb_img->post_content;
		$image_link = wp_get_attachment_link($img, 'medium');
		
		$image_link_description = str_replace('<a href', '<a class="grid-item" title="'.$image_description.'" href', $image_link);
		
		$exclusion_status = get_post_meta($img, '_exclusion_checkbox', true);
			
			//ONLY ADD IF THE EXCLUSION BOX IS NOT CHECKED
			if( $exclusion_status != 1){ 
				$html .= $image_link_description;
			}
	}
	

	//return $html;
	var_dump($imgs);
}

function dolan_get_images(){
	$output = array();
	$image_ids = get_images_from_media_library();

	foreach($image_ids as $img_id){
		$image 			= get_post($img_id);
		$image_src 	  	= wp_get_attachment_image_src($img_id, 'medium');
		$image_src_lg 	= wp_get_attachment_image_src($img_id, 'large');

		$output[] = array(
			'id' 			=> $img_id,
			'url'			=> $image_src[0],
			'url_lg'		=> $image_src_lg[0],
			'description'	=> $image->post_content,
		);
	}

	return $output;
}


/*SHOW THE FRONT PAGE IMAGES*/
function display_frontpage_images_from_media_library($image_size) {
	
	//example: display_frontpage_images_from_media_library('xlarge');
	
	$imgs = get_images_from_media_library();
	$html = '<div id="media-gallery">';
	
	$fp_image_array = array();			

	foreach($imgs as $img) {
		
		$fp_status = get_post_meta($img, '_fp_checkbox', true);
		if( $fp_status == 1){ //CHECK THE FRONT PAGE STATUS
			$html .= wp_get_attachment_link($img, $image_size);
			$img_link = wp_get_attachment_url($img);
			array_push($fp_image_array, $img_link);
		}
	}
	$html .= '</div>';
	//return $html;
	shuffle($fp_image_array);
	return $fp_image_array[0];
}


//creating a table to save 'who is in this picture' data

register_activation_hook( __FILE__, 'my_plugin_create_db' );

function my_plugin_create_db() {

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'photo_tagging';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		views smallint(5) NOT NULL,
		clicks smallint(5) NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}





function divide_the_genders($gender) {
$args = array(
      'post_type' 	=> 'relative',
	  'meta_key' 	=> 'relative_name_gender',                    
	  'meta_value' 	=> $gender    
       );
    $men = new WP_Query( $args );
	$gender_list = array();
  // If we are in a loop we can get the post ID easily
	if( $men->have_posts() ) {
      while( $men->have_posts() ) {
        $men->the_post();
		$gendered = get_post_meta( get_the_ID(), 'relative_name_first', true ); 
		array_push($gender_list, $gendered);          
      }
	}
	  return $gender_list; 
}

function divide_the_genders_by_id($gender) {
$args = array(
      'post_type' 	=> 'relative',
	  'meta_key' 	=> 'relative_name_gender',                    
	  'meta_value' 	=> $gender    
       );
    $men = new WP_Query( $args );
	$gender_list = array();
  // If we are in a loop we can get the post ID easily
	if( $men->have_posts() ) {
      while( $men->have_posts() ) {
        $men->the_post();
		$gendered['id'] = get_the_ID(); 
		$gendered['name'] = get_post_meta( get_the_ID(), 'relative_name_first', true );
		$gendered['name_last'] = get_post_meta( get_the_ID(), 'relative_name_last', true );
	  array_push($gender_list, $gendered);          
      }
	}
	  return $gender_list; 
}

//get category slugs
function dolan_get_categories(){

	$output = array();
	$categories = get_categories();

	foreach( $categories as $category ){
		$output[] = array(
			'name' => $category->name,
			'slug' => $category->slug
			);
	}

	return $output;

}

//get posts categorized
function dolan_get_posts($category_slug = null){

	$output = array();

	$args = array(
		'numberposts' => -1,
		'offset' => 0,
		'category_name' => $category_slug,
		'category' => 0,
		'orderby' => 'post_date',
		'order' => 'DESC',
		'post_type' => 'post',
		'post_status' => 'publish',
		'suppress_filters' => true,
	);

	$query = new WP_Query( $args );

	// fill the array
	while( $query->have_posts() ){
		$query->the_post();
		$id = get_the_ID();
		$output[] = array(
			'id' 		=> $id,
			'title'		=> get_the_title($id),
			'date'		=> get_the_date(),
			'author'	=> 'the author of this post',
			'category'  => $category[0]->name,
			'link'		=> get_the_permalink(),
		);
	}

	return $output;

}


function get_relatives_by_id() {
$args = array(
      'post_type' 	=> 'relative',
	  'orderby' 		=> 'title',
	  'order'   		=> 'ASC',
	  		'posts_per_page' => -1,
		'numberposts' => -1,

       );
    $relatives = new WP_Query( $args );
	$relatives_list = array();
  // If we are in a loop we can get the post ID easily
	if( $relatives->have_posts() ) {
      while( $relatives->have_posts() ) {
        $relatives->the_post();
		$relative['id'] = get_the_ID(); 
		$relative['name'] = get_post_meta( get_the_ID(), 'relative_name_first', true );
		$relative['name_last'] = get_post_meta( get_the_ID(), 'relative_name_last', true );
	  array_push($relatives_list, $relative);          
      }
	}
	  return $relatives_list; 
}

/////*GET MAP DATA*/////

function get_family_map_data() {
				  
				  $args = array(
					'post_type' => 'relative',
					'posts_per_page' => -1,
					'numberposts' => -1
				  );
				  $relatives = new WP_Query( $args );
				  
				  //start out the geojson
				  $maplocations  = '{"type": "FeatureCollection",';
            	  $maplocations .= '"features": [';
				
				//LOOP
				// If we are in a loop we can get the post ID easily
				  if( $relatives->have_posts() ) {
					while( $relatives->have_posts() ) {
					  $relatives->the_post();
						
							$profile_pic = get_the_post_thumbnail( get_the_ID(), 'thumbnail_s');
							$first_name = get_post_meta( get_the_ID(), 'relative_name_first', true ); 
							$last_name = get_post_meta( get_the_ID(), 'relative_name_last', true ); 
							$location = get_post_meta( get_the_ID(), 'relative_location_city', true ); 
							$latitude = get_post_meta( get_the_ID(), 'relative_location_lat', true ); 
							$longitude = get_post_meta( get_the_ID(), 'relative_location_long', true );
			  
						  	$maplocations .=		'{';
							$maplocations .= 	'"type": "Feature",';
							$maplocations .= 	'"geometry": {';
							$maplocations .= 		'"type": "Point",';
							$maplocations .= 		'"coordinates": ['.$longitude.','.$latitude.']';
							$maplocations .= 	'},';
							$maplocations .= 	'"properties": {';
							$maplocations .=  	'"description": "'.$profile_pic.'<h1>This is'.$first_name.' '.$last_name.'</h1>'.$location.'",';
							$maplocations .=  	'"marker-symbol": "camera"';
							$maplocations .= 	'}';
						  	$maplocations .= 	'},';
					}
				  }
				  //END LOOP
							//pad out the geojson
							$maplocations .=	 '{';
							$maplocations .=	 '"type": "Feature",';
							$maplocations .=	 '"geometry": {';
							$maplocations .=	 '"type": "Point",';
							$maplocations .=	 ' "coordinates": [-116.252960, 43.607930]'; 
							$maplocations .=	 '},';
							$maplocations .=	 '"properties": {';
							$maplocations .=	 '"description": "<h1>Sinead Roussev</h1>New York City",';
							$maplocations .=	 '"marker-symbol": "camera"';
							$maplocations .=	 ' }';
							$maplocations .=	 ' }]';
							$maplocations .=	 '}';
				  
				  
	return $maplocations;
}


function get_family_cluster_map_data() {
				  
				  $args = array(
					'post_type' => 'relative',
					'posts_per_page' => -1,
					'numberposts' => -1
				  );
				  $relatives = new WP_Query( $args );
				  
				  //start out the geojson
				  $maplocations  = 'var addressPoints = [';

				
				//LOOP
				// If we are in a loop we can get the post ID easily
				  if( $relatives->have_posts() ) {
					while( $relatives->have_posts() ) {
					  $relatives->the_post();
							$profile_pic = get_the_post_thumbnail( get_the_ID(), 'thumbnail_s');
							
							if (!empty($profile_pic)){
								$thumb_id = get_post_thumbnail_id();
								$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail_s', true);
								$profile_pic = $thumb_url[0];
								$profile_pic = '<div class=\"map_pic\"><img src=\"'.addslashes($profile_pic).'\"></div>';
							}else{
								$profile_pic = '<div class=\"map_pic\"><img src=\"http://dolansofcavan.com/wp-content/themes/nearnothing/images/blank_profile.png\"></div>';
							} 
							
							$first_name = get_post_meta( get_the_ID(), 'relative_name_first', true ); 
							$last_name = get_post_meta( get_the_ID(), 'relative_name_last', true ); 
							$location = get_post_meta( get_the_ID(), 'relative_location_city', true ); 
							
							$latitude = get_post_meta( get_the_ID(), 'relative_location_lat', true ); 
							$latitude = is_numeric($latitude)? $latitude: 0;
							
							$longitude = get_post_meta( get_the_ID(), 'relative_location_long', true );
							$longitude = is_numeric($longitude)? $longitude: 0;

							$dob = get_post_meta( get_the_ID(), 'relative_birth_dob', true ); 
							$dod = get_post_meta( get_the_ID(), 'relative_birth_dod', true );
							
							$maplocations .= 	'['.$latitude.','.$longitude.',';
							$maplocations .=  	'"'.$profile_pic.'<span><h1>'.$first_name.'<br/>'.$last_name.'</h1>'.$location.'</span>","'.$dob.'","'.$dod.'","'.$profile_pic.'"],';
					}
				  }
				  //END LOOP
							//pad out the geojson
							$maplocations .= 	'[-116.252960, 43.607930,';
							$maplocations .=  	'"<h1>Sinead Roussev</h1>New York City"]';
							$maplocations .=	 ']';
				  
				  
	return $maplocations;
}



function save_map_data() {
	$map_json = get_template_directory().'/mapfamdata.geojson';
	$fp = fopen( $map_json, 'w');
	$mapped_relatives = get_family_map_data();
	//$mapped_relatives = return_text();
	fwrite($fp, $mapped_relatives);
	fclose($fp);
}

function save_map_cluster_data() {
	$map_json = get_template_directory().'/js/clusterfuck.js';
	$fp = fopen( $map_json, 'w');
	$mapped_relatives = get_family_cluster_map_data();
	//$mapped_relatives = return_text();
	fwrite($fp, $mapped_relatives);
	fclose($fp);
}



/* CUSTOM POST TYPES */
function my_custom_post_relatives() {
  $labels = array(
    'name'               => _x( 'Relatives', 'post type general name' ),
    'singular_name'      => _x( 'Relative', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'book' ),
    'add_new_item'       => __( 'Add New Relative' ),
    'edit_item'          => __( 'Edit Relative' ),
    'new_item'           => __( 'New Relative' ),
    'all_items'          => __( 'All Relatives' ),
    'view_item'          => __( 'View Relatives' ),
    'search_items'       => __( 'Search Relatives' ),
    'not_found'          => __( 'No relatives found' ),
    'not_found_in_trash' => __( 'No relatives found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'Relatives'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds information for each relative',
    'public'        => true,
    'menu_position' => 5,
	'menu_icon' 		=> 'dashicons-admin-users',
    'supports'      => array( 'title', 'thumbnail', 'excerpt', 'comments' ),
    'has_archive'   => true,
  );
  register_post_type( 'relative', $args ); 
}

add_action( 'init', 'my_custom_post_relatives' );


/* CUSTOM TAXONOMIES */

function my_taxonomies_relatives() {
  $labels = array(
    'name'              => _x( 'Relative Categories', 'taxonomy general name' ),
    'singular_name'     => _x( 'Relative Category', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Relative Categories' ),
    'all_items'         => __( 'All Relative Categories' ),
    'parent_item'       => __( 'Parent Relative Category' ),
    'parent_item_colon' => __( 'Parent Relative Category:' ),
    'edit_item'         => __( 'Edit Relative Category' ), 
    'update_item'       => __( 'Update Relative Category' ),
    'add_new_item'      => __( 'Add New Relative Category' ),
    'new_item_name'     => __( 'New Relative Category' ),
    'menu_name'         => __( 'Relative Categories' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'relative_category', 'relative', $args );
}

add_action( 'init', 'my_taxonomies_relatives', 0 );
/* DEFINE META BOX: NAME */

add_action( 'add_meta_boxes', 'relative_name_box' );

function relative_name_box() {
    add_meta_box( 
        'relative_name_box',
        __( 'Relative Name', 'myplugin_textdomain' ),
        'relative_name_box_content',
        'relative',
        'side',
        'high'
    );
}

function relative_name_box_content( $post ) {
    wp_nonce_field( plugin_basename( __FILE__ ), 'relative_name_box_content_nonce' );
    $meta_values_first_name = get_post_meta($post->ID, 'relative_name_first', true);
    $meta_values_last_name = get_post_meta($post->ID, 'relative_name_last', true);
	$meta_values_gender = get_post_meta($post->ID, 'relative_name_gender', true);


    echo '<label for="relative_name"></label>';
    if($meta_values_first_name != '') {
        echo '<input type="text" id="relative_name_first" name="relative_name_first" placeholder="first name" value="' . $meta_values_first_name . '"/>';
        echo '<input type="text" id="relative_name_last" name="relative_name_last" placeholder="last name" value="' . $meta_values_last_name . '"/>';
		echo '<select name="relative_name_gender" id="relative_name_gender">';
		echo '<option value="male"'. ( $meta_values_gender == 'male' ? ' selected="selected"' : '').'>Male</option>';
		echo '<option value="female"'. ( $meta_values_gender == 'female' ? ' selected="selected"' : '').'>Female</option>';
		echo '</select>';

	} else {
        echo '<input type="text" id="relative_name_first" name="relative_name_first" placeholder="first name"/>';
        echo '<input type="text" id="relative_name_last" name="relative_name_last" placeholder="last name"/>';
		echo '<select name="relative_name_gender" id="relative_name_gender">';
		echo '<option value="male"'. ( $meta_values_gender == 'male' ? ' selected="selected"' : '').'>Male</option>';
		echo '<option value="female"'. ( $meta_values_gender == 'female' ? ' selected="selected"' : '').'>Female</option>';
		echo '</select>';
    }
}


add_action( 'save_post', 'relative_name_box_save' );

function relative_name_box_save( $post_id ) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
  return;

  if ( !wp_verify_nonce( $_POST['relative_name_box_content_nonce'], plugin_basename( __FILE__ ) ) )
  return;

  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
    return;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
    return;
  }

  $relative_name_first = $_POST['relative_name_first'];
  update_post_meta( $post_id, 'relative_name_first', $relative_name_first );
  
  $relative_name_last = $_POST['relative_name_last'];
  update_post_meta( $post_id, 'relative_name_last', $relative_name_last );
  
  $relative_name_gender = $_POST['relative_name_gender'];
  update_post_meta( $post_id, 'relative_name_gender', $relative_name_gender );

}



/* DEFINE META BOX: LOCATION */

add_action( 'add_meta_boxes', 'relative_location_box' );

function relative_location_box() {
    add_meta_box( 
        'relative_location_box',
        __( 'Relative Location', 'myplugin_textdomain' ),
        'relative_location_box_content',
        'relative',
        'side',
        'high'
    );
}

function relative_location_box_content( $post ) {
    wp_nonce_field( plugin_basename( __FILE__ ), 'relative_location_box_content_nonce' );
    $meta_values_city = get_post_meta($post->ID, 'relative_location_city', true);
    $meta_values_lat = get_post_meta($post->ID, 'relative_location_lat', true);
	$meta_values_long = get_post_meta($post->ID, 'relative_location_long', true);

    echo '<label for="relative_location"></label>';
    if($meta_values_city != '') {
        echo '<input type="text" id="relative_location_city" name="relative_location_city" placeholder="enter a city" value="' . $meta_values_city . '"/>';
		echo '<input type="text" id="relative_location_lat" name="relative_location_lat" placeholder="enter a latitude" value="' . $meta_values_lat . '"/>';
		echo '<input type="text" id="relative_location_long" name="relative_location_long" placeholder="enter a longitude" value="' . $meta_values_long . '"/>';

	} else {
        echo '<input type="text" id="relative_location_city" name="relative_location_city" placeholder="enter a city"/>';
		echo '<input type="text" id="relative_location_lat" name="relative_location_lat" placeholder="enter a latitude"/>';
		echo '<input type="text" id="relative_location_long" name="relative_location_long" placeholder="enter a longitude"/>';
    }
}


add_action( 'save_post', 'relative_location_box_save' );

function relative_location_box_save( $post_id ) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
  return;

  if ( !wp_verify_nonce( $_POST['relative_location_box_content_nonce'], plugin_basename( __FILE__ ) ) )
  return;

  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
    return;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
    return;
  }
  $relative_location_city = $_POST['relative_location_city'];
  update_post_meta( $post_id, 'relative_location_city', $relative_location_city );
  
  $relative_location_lat = $_POST['relative_location_lat'];
  update_post_meta( $post_id, 'relative_location_lat', $relative_location_lat );
 
  $relative_location_long = $_POST['relative_location_long'];
  update_post_meta( $post_id, 'relative_location_long', $relative_location_long );

}

/* DEFINE META BOX: BIRTHDATES-DEATHDATES */

add_action( 'add_meta_boxes', 'relative_birth_box' );

function relative_birth_box() {
    add_meta_box( 
        'relative_birth_box',
        __( 'Birth & Death Years', 'myplugin_textdomain' ),
        'relative_birth_box_content',
        'relative',
        'side',
        'high'
    );
}

function relative_birth_box_content( $post ) {
    wp_nonce_field( plugin_basename( __FILE__ ), 'relative_birth_box_content_nonce' );
    $meta_values_dob = get_post_meta($post->ID, 'relative_birth_dob', true);
	$meta_values_dod = get_post_meta($post->ID, 'relative_birth_dod', true);

    echo '<label for="relative_birth"></label>';
    if($meta_values_dob != '') {
		echo '<input type="text" id="relative_birth_dob" name="relative_birth_dob" placeholder="Year of Birth" value="' . $meta_values_dob . '"/>';
		echo '<input type="text" id="relative_birth_dod" name="relative_birth_dod" placeholder="Year of Death" value="' . $meta_values_dod . '"/>';

	} else {
		echo '<input type="text" id="relative_birth_dob" name="relative_birth_dob" placeholder="Year of Birth"/>';
		echo '<input type="text" id="relative_birth_dod" name="relative_birth_dod" placeholder="Year of Death"/>';
    }
}


add_action( 'save_post', 'relative_birth_box_save' );

function relative_birth_box_save( $post_id ) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
  return;

  if ( !wp_verify_nonce( $_POST['relative_birth_box_content_nonce'], plugin_basename( __FILE__ ) ) )
  return;

  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
    return;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
    return;
  }  
  $relative_birth_dob = $_POST['relative_birth_dob'];
  update_post_meta( $post_id, 'relative_birth_dob', $relative_birth_dob );
 
  $relative_birth_dod = $_POST['relative_birth_dod'];
  update_post_meta( $post_id, 'relative_birth_dod', $relative_birth_dod );

}




/* DEFINE META BOX: PARENT SELECTION */

add_action( 'add_meta_boxes', 'parental_units_box' );

function parental_units_box() {
    add_meta_box( 
        'parental_units_box',
        __( 'Parental Units', 'myplugin_textdomain' ),
        'parental_units_box_content',
        'relative',
        'side',
        'high'
    );
}

function parental_units_box_content( $post ) {
    wp_nonce_field( plugin_basename( __FILE__ ), 'parental_units_box_content_nonce' );
    $meta_values_mother = get_post_meta($post->ID, 'parental_units_mother', true);
    $meta_values_father = get_post_meta($post->ID, 'parental_units_father', true);
	
	//get the possible matches for parents based on gender
	$male_relatives = divide_the_genders_by_id(male);
	$female_relatives = divide_the_genders_by_id(female);

	echo '<label for="parental_units"></label>';	
	echo '<p>Mother</p>';			
	echo '<select name="parental_units_mother" id="parental_units_mother">';
		foreach($female_relatives as $female_relative){
		  echo '<option value="'. $female_relative['id'] .'"'. ( ($female_relative['id'] == $meta_values_mother)? 'selected="selected"' : '').'>'. $female_relative['name'].' '.$female_relative['name_last'] .'</option>';
		}
	echo '<option value="none"'. ( ($meta_values_mother == 'none')? 'selected="selected"' : '').'>NA</option>';
	echo'</select>';

	echo '<p>Father</p>';			
	echo '<select name="parental_units_father" id="parental_units_father">';
		foreach($male_relatives as $male_relative){
		  echo '<option value="'. $male_relative['id'] .'"'. ( ($male_relative['id'] == $meta_values_father)? ' selected="selected"' : '').'>'. $male_relative['name'].' '.$male_relative['name_last'].'</option>';
		}
	echo '<option value="none"'. ( ($meta_values_father == 'none')? 'selected="selected"' : '').'>NA</option>';
	echo'</select>';
}

add_action( 'save_post', 'parental_units_box_save' );

function parental_units_box_save( $post_id ) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
  return;

  if ( !wp_verify_nonce( $_POST['parental_units_box_content_nonce'], plugin_basename( __FILE__ ) ) )
  return;

  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
    return;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
    return;
  }
  
  $parental_units_mother = $_POST['parental_units_mother'];
  update_post_meta( $post_id, 'parental_units_mother', $parental_units_mother );

  $parental_units_father = $_POST['parental_units_father'];
  update_post_meta( $post_id, 'parental_units_father', $parental_units_father );

}



/* DEFINE META BOX: SPOUSE SELECTION */

add_action( 'add_meta_boxes', 'spouse_box' );

function spouse_box() {
    add_meta_box( 
        'spouse_box',
        __( 'Spouse', 'myplugin_textdomain' ),
        'spouse_box_content',
        'relative',
        'side',
        'high'
    );
}

// Get Spouse
function spouse_box_content( $post ) {
    wp_nonce_field( plugin_basename( __FILE__ ), 'spouse_box_content_nonce' );
    $meta_values_spouse = get_post_meta($post->ID, 'spouse', true);
	
	//get the possible matches for parents based on gender
	$relatives = get_relatives_by_id();

	echo '<label for="spouse"></label>';	
	echo '<select name="spouse" id="spouse">';
	echo '<option value="none"'. ( ($meta_values_spouse == 'none')? 'selected="selected"' : '').'>NA</option>';
		foreach($relatives as $relative){
		  echo '<option value="'. $relative['id'] .'"'. ( ($relative['id'] == $meta_values_spouse)? 'selected="selected"' : '').'>'. $relative['name'].' '.$relative['name_last'] .'</option>';
		}
	echo'</select>';

}

add_action( 'save_post', 'spouse_box_save' );

function spouse_box_save( $post_id ) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
  return;

  if ( !wp_verify_nonce( $_POST['spouse_box_content_nonce'], plugin_basename( __FILE__ ) ) )
  return;

  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
    return;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
    return;
  }
  
  $spouse = $_POST['spouse'];
  update_post_meta( $post_id, 'spouse', $spouse );

}



///*** FRONT PAGE IMAGES SELECT ***///
// Add select box to determine if image should be shown on front page...

function attachment_frontpage_checkbox($form_fields, $post) {
	
	// get the current value of our custom field
	$current_value = get_post_meta($post->ID, "_fp_checkbox", true);

	// if this value is the current_value we'll mark it selected
	$checked = ($current_value == 1) ? ' checked ' : '';

	//define the select box
	$FP_CheckBoxHtml  = "<input type='hidden' name='attachments[{$post->ID}][fp_checkbox]' value='null' />";
	$FP_CheckBoxHtml .= "<input type='checkbox' name='attachments[{$post->ID}][fp_checkbox]' id='attachments[{$post->ID}][fp_checkbox]'"; 
	$FP_CheckBoxHtml .= "value='1' {$checked} />";
	$FP_CheckBoxHtml .= "Show this on the front page";
	$FP_CheckBoxHtml .= "</input><br/>";

	//apply html from above to select
	$form_fields["fp_checkbox"]["label"] = __("Frontpage Image");
	$form_fields["fp_checkbox"]["input"] = "html";
	$form_fields["fp_checkbox"]["html"] = $FP_CheckBoxHtml;

	return $form_fields;
		
}

add_filter("attachment_fields_to_edit", "attachment_frontpage_checkbox", null, 2);

//save the selection
function attachment_frontpage_checkbox_save($post, $attachment) {
	if( isset($attachment['fp_checkbox']) ){
		update_post_meta($post['ID'], '_fp_checkbox', $attachment['fp_checkbox']);
	}
	return $post;
}
add_filter("attachment_fields_to_save", "attachment_frontpage_checkbox_save", null, 2);

///*** END FRONT PAGE IMAGES SELECT ***///


///*** EXCLUDE IMAGES FROM MEDIA GALLERY ***///
// Add select box to determine if image should be shown in gallery...

function attachment_exclusion_checkbox($form_fields, $post) {
	
	// get the current value of our custom field
	$current_value = get_post_meta($post->ID, "_exclusion_checkbox", true);

	// if this value is the current_value we'll mark it selected
	$checked = ($current_value == 1) ? ' checked ' : '';

	//define the select box
	$exclusion_CheckBoxHtml  = "<input type='hidden' name='attachments[{$post->ID}][exclusion_checkbox]' value='null' />";
	$exclusion_CheckBoxHtml .= "<input type='checkbox' name='attachments[{$post->ID}][exclusion_checkbox]' id='attachments[{$post->ID}][exclusion_checkbox]'"; 
	$exclusion_CheckBoxHtml .= "value='1' {$checked} />";
	$exclusion_CheckBoxHtml .= "Exclude this image from Photo Gallery?";
	$exclusion_CheckBoxHtml .= "</input><br/>";

	//apply html from above to select
	$form_fields["exclusion_checkbox"]["label"] = __("Exclude Image");
	$form_fields["exclusion_checkbox"]["input"] = "html";
	$form_fields["exclusion_checkbox"]["html"] = $exclusion_CheckBoxHtml;

	return $form_fields;
		
}

add_filter("attachment_fields_to_edit", "attachment_exclusion_checkbox", null, 2);

//save the selection
function attachment_exclusion_checkbox_save($post, $attachment) {
	if( isset($attachment['exclusion_checkbox']) ){
		update_post_meta($post['ID'], '_exclusion_checkbox', $attachment['exclusion_checkbox']);
	}
	return $post;
}
add_filter("attachment_fields_to_save", "attachment_exclusion_checkbox_save", null, 2);

///*** END IMAGE EXCLUSION SELECT ***///


function wptp_register_attachments_tax() {
 
 /* register the document catgeories taxonomy */
register_taxonomy( 'document-category', 'attachment',
    array(
        'labels' =>  array(
            'name'              => 'Document Categories',
            'singular_name'     => 'Document Category',
            'search_items'      => 'Search Document Categories',
            'all_items'         => 'All Document Categories',
            'edit_item'         => 'Edit Document Categories',
            'update_item'       => 'Update Document Category',
            'add_new_item'      => 'Add New Document Category',
            'new_item_name'     => 'New Document Category Name',
            'menu_name'         => 'Document Category',
        ),
        'hierarchical' => true,
        'sort' => true,
        'show_admin_column' => true
    )
);
 
 
}
add_action( 'init', 'wptp_register_attachments_tax', 0 );

	// Redirect to home after logout
	function go_home(){
		wp_redirect( home_url() );
		exit();
	}
	add_action('wp_logout','go_home');

/* ======================================================= 
	ADD SUPPORT
======================================================= */

// Add image sizes
	if ( function_exists( 'add_image_size' ) ) { 
		add_image_size( 'thumbnail_s', 108, 108, array( 'center', 'center' ) );
		add_image_size( 'thumbnail_l', 225, 225, array( 'center', 'center' ) );
		add_image_size( 'thumbnail_xl', 340, 340, array( 'center', 'center' ) );
	}

//add columns to wp list table:	https://wordpress.org/support/topic/manage_posts_custom_column-not-working
  
add_filter('manage_edit-relative_columns', 'relative_edit_columns');
function relative_edit_columns($columns){
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => 'Relative Name',
		'dob' => 'Birth Year',
		'dod' => 'DOD',
		'location' => 'Home'
	);
	return $columns;
}

add_action('manage_posts_custom_column', 'relative_custom_columns');
function relative_custom_columns($column){
	global $post;
	switch ($column){
		case 'dob':
			echo get_post_meta( $post->ID  , 'relative_birth_dob' , true );
			break;
		case 'dod':
			echo get_post_meta( $post->ID  , 'relative_birth_dod' , true );
			break;
		case 'location':
			echo get_post_meta( $post->ID  , 'relative_location_city' , true );
			break;
			
	}
}


?>
