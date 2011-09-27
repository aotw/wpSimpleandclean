<?php

//define some constant paths
define('ADMIN_PATH', STYLESHEETPATH . '/admin/');
define('FUNCTIONS_PATH', STYLESHEETPATH . '/functions/');

//define some constants
define('CHILDTHEME', get_bloginfo('stylesheet_directory') . '/');
define('ADMIN', CHILDTHEME . 'admin/');
define('FUNCTIONS', CHILDTHEME . 'functions/');
define('LAYOUTS', CHILDTHEME . 'layouts/');
define('STYLES', STYLESHEETPATH . '/styles/');

// You can mess with these 2 if you wish.
$themedata = get_theme_data(STYLESHEETPATH . '/style.css');
define('THEMENAME', $themedata['Name']);
define('OPTIONS', 'of_options'); //name of entry into database - will break DB if this has spaces!


/* These files build out the options interface.  Likely won't need to edit these. */

require_once (ADMIN_PATH . 'admin-setup.php');		// Custom functions and plugins
require_once (ADMIN_PATH . 'admin-interface.php');	// Admin Interfaces 

/* These files build out the admin specific options and associated functions. */

require_once (ADMIN_PATH . 'theme-options.php'); 	// Options panel settings and custom settings
require_once (ADMIN_PATH . 'admin-functions.php'); 	// Theme actions based on options settings

//Thematic 0.9.7.6 compatible
define('THEMATIC_COMPATIBLE_BODY_CLASS', true);
define('THEMATIC_COMPATIBLE_POST_CLASS', true);
define('THEMATIC_COMPATIBLE_COMMENT_HANDLING', true);
define('THEMATIC_COMPATIBLE_COMMENT_FORM', true);
define('THEMATIC_COMPATIBLE_FEEDLINKS', true);





// REAL CODE
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));
}






//Enable custom menus
add_action( 'init', 'register_my_menu' );
//Custom Menu name
function register_my_menu() {
	register_nav_menu( 'primary-menu', __( 'Primary Menu' ) );
}

//Enable Custom Background
add_custom_background();

/** Tell WordPress to run yourtheme_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'yourtheme_setup' );

if ( ! function_exists('yourtheme_setup') ):
/**
* @uses add_custom_image_header() To add support for a custom header.
* @uses register_default_headers() To register the default custom header images provided with the theme.
*
* @since 3.0.0
*/
function yourtheme_setup() {

// This theme uses post thumbnails
add_theme_support( 'post-thumbnails' );

// Your changeable header business starts here
define('HEADER_TEXTCOLOR', '');
// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
define( 'HEADER_IMAGE', '%s/images/headers/defaultBG.png' );

// The height and width of your custom header. You can hook into the theme's own filters to change these values.
// Add a filter to yourtheme_header_image_width and yourtheme_header_image_height to change these values.
define( 'HEADER_IMAGE_WIDTH', apply_filters( 'yourtheme_header_image_width', 940 ) );
define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'yourtheme_header_image_height',	198 ) );

// We'll be using post thumbnails for custom header images on posts and pages.
// We want them to be 940 pixels wide by 198 pixels tall (larger images will be auto-cropped to fit).
set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

// Don't support text inside the header image.
define( 'NO_HEADER_TEXT', false );

// Add a way for the custom header to be styled in the admin panel that controls
// custom headers. See yourtheme_admin_header_style(), below.
add_custom_image_header( '', 'yourtheme_admin_header_style' );

// … and thus ends the changeable header business.

// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
register_default_headers( array (
'berries' => array (
'url' => '%s/images/headers/afro.jpg',
'thumbnail_url' => '%s/images/headers/afro-thumb.jpg',
'description' => __( 'Afro', 'yourtheme' )
),
'cherryblossom' => array (
'url' => '%s/images/headers/aviators.jpg',
'thumbnail_url' => '%s/images/headers/aviators-thumb.jpg',
'description' => __( 'Aviators', 'yourtheme' )
),
'concave' => array (
'url' => '%s/images/headers/bowtie.jpg',
'thumbnail_url' => '%s/images/headers/bowtie-thumb.jpg',
'description' => __( 'Bowtie', 'yourtheme' )
),
'fern' => array (
'url' => '%s/images/headers/colors.jpg',
'thumbnail_url' => '%s/images/headers/colors-thumb.jpg',
'description' => __( 'Spectrum', 'yourtheme' )
),
'forestfloor' => array (
'url' => '%s/images/headers/dots.jpg',
'thumbnail_url' => '%s/images/headers/dots-thumb.jpg',
'description' => __( 'Dots', 'yourtheme' )
),
'inkwell' => array (
'url' => '%s/images/headers/freakazoid.jpg',
'thumbnail_url' => '%s/images/headers/freakazoid-thumb.jpg',
'description' => __( 'Freakazoid', 'yourtheme' )
),
'path' => array (
'url' => '%s/images/headers/goro.jpg',
'thumbnail_url' => '%s/images/headers/goro-thumb.jpg',
'description' => __( 'Goro', 'yourtheme' )
),
'sunset' => array (
'url' => '%s/images/headers/option.jpg',
'thumbnail_url' => '%s/images/headers/option-thumb.jpg',
'description' => __( 'Mac Option', 'yourtheme' )
)
) );
}
endif;

if ( ! function_exists( 'yourtheme_admin_header_style' ) ) :

function yourtheme_admin_header_style() {
?>
<style type="text/css">
#headimg {
height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}
#headimg h1, #headimg #desc {

}
</style>



<?php
}
endif;


?>