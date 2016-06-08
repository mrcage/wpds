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
	<div class="row dock"<?php if (!empty($color_opts['dock-background-color'])) echo ' style="background-color:#'.$color_opts['dock-background-color'].';color:red"'; ?>>
		<?php dynamic_sidebar("Dock"); ?>
	</div>
	<?php endif; ?>
	<?php wp_footer(); ?>

	<!-- CDN Fallback -->
	<script type="text/javascript">
	if (typeof jQuery == 'undefined') {
	    document.write(unescape("%3Cscript src='<?php bloginfo('template_url'); ?>/javascripts/vendor/jquery.js' type='text/javascript'%3E%3C/script%3E"));
	}
	</script>




    <script src="<?php bloginfo('template_url'); ?>/javascripts/foundation/foundation.js"></script>
    <script>
       var mq = window.matchMedia( "(min-width: 960px)" );
	   if (mq.matches) {
		  document.write(unescape("%3Cscript src='<?php bloginfo('template_url'); ?>/javascripts/foundation/foundation.orbit.js' type='text/javascript'%3E%3C/script%3E"));
		}
	</script>
	<?php
		$signage_opts = get_theme_mod( 'signage', [] );
		if (!empty($signage_opts['reload_interval']) && is_numeric($signage_opts['reload_interval'])) {
			echo '<script>var defaultReloadTimeout=1000 * 60 * ' . $signage_opts['reload_interval'] . ';</script>';
		}

	?>
    <script src="<?php bloginfo('template_url'); ?>/javascripts/vendor/app.js"></script>
    <!-- <script src="<?php bloginfo('template_url'); ?>/javascripts/vendor/twitterFetcher_v10_min.js"></script> -->

  <script>
$(function() {
    $(document).foundation();
});

//    twitterFetcher.fetch('393025966789754880', 'tweets', 1, true, true, false);
  </script>

 </body>
</html>
