<?php
/**
 * Header
 *
 * Setup the header for our theme
 *
 * @package WordPress
 * @subpackage Foundation, for WordPress
 * @since Foundation, for WordPress 1.0
 */
?>
<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?php language_attributes(); ?>" > <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	<title><?php bloginfo('name'); ?></title>

        <?php wp_head(); ?>

	<?php $color_opts = get_theme_mod( 'colors', [] ); ?>
	<?php if (!empty($color_opts['dock-foreground-color'])): ?>
	<style>
		.dock .columns {
			color: #<?=$color_opts['dock-foreground-color']?>;
		}
	</style>
	<?php endif; ?>
</head>

<body <?php body_class(); ?>>
	<div class="content">

		
