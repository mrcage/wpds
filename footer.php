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

	<?php if ( !isset( get_theme_mod( 'layout', [] )['show-dock'] ) || get_theme_mod( 'layout', [] )['show-dock'] ): ?>
		<?php 
			$dock_style = [];
			$color_opts = get_theme_mod( 'colors', [] ); 
			if (!empty($color_opts['dock-foreground-color'])) {
				$dock_style['color'] = '#' . $color_opts['dock-foreground-color'];
			}
			if (!empty($color_opts['dock-background-color'])) {
				$dock_style['background-color'] = '#' . $color_opts['dock-background-color'];
			}
			$layout_opts = get_theme_mod( 'layout', [] ); 
			if (!empty($layout_opts['dock-height']) && intval($layout_opts['dock-height']) > 0) {
				$dock_style['height'] = intval($layout_opts['dock-height']) . 'px';
			}
		?>
		<footer class="footer"<?php echo print_style($dock_style);?>>
			<div class="container-fluid">
				<div class="row">
					<?php dynamic_sidebar("Dock"); ?>
				</div>
			</div>
		</footer>
	<?php endif; ?>

	<?php
		$signage_opts = get_theme_mod( 'signage', [] );
		if (!empty($signage_opts['reload_interval']) && is_numeric($signage_opts['reload_interval'])) {
			echo '<script>var defaultReloadTimeout=1000 * 60 * ' . $signage_opts['reload_interval'] . ';</script>';
		}
	?>

	<?php wp_footer(); ?>

	</body>
</html>
