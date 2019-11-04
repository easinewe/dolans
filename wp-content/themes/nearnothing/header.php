<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>
<?php wp_title(' - ', true, 'right'); ?>
<?php bloginfo('name'); ?>
</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='https://fonts.googleapis.com/css?family=Lato:400,900,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!--Uncomment this to use a favicon.ico in the theme directory: -->
<!--<link rel="SHORTCUT ICON" href="<?php bloginfo('template_directory'); ?>/favicon.ico"/>-->
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>

<!--assign classes to different sections // maybe use case here-->
<?php
$pagename = get_query_var('pagename');
$body_class;
	if( is_front_page() ){
		$body_class = 'body_home';
	} else if( $pagename == 'relatives' ){
		$body_class = 'body_relatives';
	} else if($pagename == 'photos'){
		$body_class = 'body_photos';
	} else if($pagename == 'map'){
		$body_class = 'body_map';
	} else if($pagename == 'reunion'){
		$body_class = 'body_reunion';
	} else{
		$body_class = '';
	}

?>

<!--if we are on the map page we need to adjust the width of the slider Content-->
<?php
$pagename = get_query_var('pagename');
if($pagename == 'map'){
	  echo '<style type="text/css"> #slide_content{ height: 100%; width:100%;}</style>';
  }
?>


<body <?php body_class($body_class); ?>>
  
  <div id="navigation">
    <a href="<?php echo get_home_url(); ?>">
      <div id="logo"><?php bloginfo('name'); ?></div>
    </a>
    <div id="site_title_desktop">The Dolan Family</div>
    <div id="site_title"><?php echo ( ( is_front_page() )? 'Dolan' : $pagename ); ?></div>
    <div id="hamburger_menu"></div>
    <ul>
	  <?php wp_nav_menu(array('theme_location' => 'header_nav')); ?>
    </ul>
  </div>
  <div id="cover-up"></div>
  
  
  
  
