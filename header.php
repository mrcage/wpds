<?php
/**
 * Header
 *
 * Setup the header for our theme
 *
 * @package WordPress
 */

?>
<!DOCTYPE html>
<!--[if lte IE 9]><html class="no-js IE9 IE" lang="de-DE"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="de-DE"><!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width" />
		<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
			<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<div class="reveal">
			<div class="slides">
