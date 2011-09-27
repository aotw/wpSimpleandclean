

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>">
		<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen">
		<link rel="alternate" type="text/xml" title="<?php bloginfo('name'); ?> RSS 0.92 Feed" href="<?php bloginfo('rss_url'); ?>">
		<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>">
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS 2.0 Feed" href="<?php bloginfo('rss2_url'); ?>">
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php wp_enqueue_script('jquery'); ?>
		<?php wp_head(); ?>

		

	</head>
	<body <?php body_class(); ?>>

		<div id="header">

<?php
// Check if this is a post or page, if it has a thumbnail, and if it's a big one
if ( is_singular() &&
has_post_thumbnail( $post->ID ) &&
( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail') ) &&
$image[1] >= HEADER_IMAGE_WIDTH ) :
// We have a new header image!
echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
else : ?>
<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
<?php endif; ?>

			<h1><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
			<h2><?php bloginfo('description'); ?></h2>
		</div>

		<div id="navi">
<!--Custom Menu-->
<?php
if(function_exists('wp_nav_menu')) {
wp_nav_menu(array(
'fallback_cb' => 'topnav_fallback',
));
} else {
?>
<!-- Hard-coded menu -->
<ul id="top-nav" role="navigation">
<?php
}
?>		</div>

