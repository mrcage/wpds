<?php
/**
 * Footer
 *
 * Displays content shown in the footer section
 *
 * @package WordPress
 * @subpackage Foundation, for WordPress
 * @since Foundation, for WordPress 1.0
 */
?>
	</div>

	<?php get_sidebar( 'dock' ); ?>

	<?php
		$signage_opts = get_theme_mod( 'signage', [] );
		if (!empty($signage_opts['reload_interval']) && is_numeric($signage_opts['reload_interval'])) {
			echo '<script>var defaultReloadTimeout=1000 * 60 * ' . $signage_opts['reload_interval'] . ';</script>';
		}
	?>

	<?php wp_footer(); ?>

	</body>
</html>
