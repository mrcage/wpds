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

$color_opts = get_theme_mod( 'colors', [] );
?>
		<div class="content-dock"></div>
            </div> <!-- /.content -->

	<?php if ( !isset( get_theme_mod( 'layout', [] )['show-dock'] ) || get_theme_mod( 'layout', [] )['show-dock'] ): ?>
	<div class="row dock"<?php if (!empty($color_opts['dock-background-color'])) echo ' style="background-color:#'.$color_opts['dock-background-color'].';"'; ?>>
		<?php dynamic_sidebar("Dock"); ?>
	</div>
	<?php endif; ?>

	<?php wp_footer(); ?>

	<script src="<?php bloginfo('template_url'); ?>/javascripts/foundation/foundation.js"></script>
	<?php
		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
		);
		$the_query = new WP_Query($args);
                if ($the_query->post_count > 1) :
			?>
			<script>
				var mq = window.matchMedia( "(min-width: 960px)" );
				if (mq.matches) {
					document.write(unescape("%3Cscript src='<?php bloginfo('template_url'); ?>/javascripts/foundation/foundation.orbit.js' type='text/javascript'%3E%3C/script%3E"));
				}
			</script>
			<?php
		endif;
	?>
	<?php
		$signage_opts = get_theme_mod( 'signage', [] );
		if (!empty($signage_opts['reload_interval']) && is_numeric($signage_opts['reload_interval'])) {
			echo '<script>var defaultReloadTimeout=1000 * 60 * ' . $signage_opts['reload_interval'] . ';</script>';
		}
	?>
	<script src="<?php bloginfo('template_url'); ?>/javascripts/vendor/app.js"></script>
	<script>
		jQuery(function() {
			jQuery(document).foundation();
		});
	</script>
 </body>
</html>
