<?php
/**
 * Footer
 *
 * Displays content shown in the footer section
 *
 * @package WordPress
 */
?>
	</div>

	<?php get_sidebar( 'dock' ); ?>

	<?php if ( !isset( get_theme_mod( 'layout', [] )['show-net-status-infobox'] ) || get_theme_mod( 'layout', [] )['show-net-status-infobox'] ): ?>
		<div class="net-status-infobox"></div>
	<?php endif; ?>
    
	<?php
		$signage_opts = get_theme_mod( 'signage', [] );
		if (isset($signage_opts['reload_interval']) && is_numeric($signage_opts['reload_interval'])) {
			echo '<script>var defaultReloadTimeout = 1000 * 60 * ' . $signage_opts['reload_interval'] . ';</script>';
		}
		if (isset($signage_opts['content_change_check_interval']) && is_numeric($signage_opts['content_change_check_interval'])) {
			echo '<script>var defaultContentChangeCheckInterval = 1000 * ' . $signage_opts['content_change_check_interval'] . ';</script>';
		}
		echo '<script>var postModified=\'' . get_post_status_hash() . '\';</script>';
	?>

	<?php wp_footer(); ?>

	</body>
</html>
