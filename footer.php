<?php
/**
 * Footer
 *
 * Displays content shown in the footer section
 *
 * @package WordPress
 */
?>
		<?php if (!is_front_page() || wpds_get_index_behaviour() == 'slides'): ?>
			</div>
		</div>

		<?php get_sidebar( 'dock' ); ?>

		<?php if ( wpds_show_net_status_info_box() ): ?>
			<div class="net-status-infobox"></div>
		<?php endif; ?>
		
		<?php
            $reload_interval = wpds_get_reload_interval();
            if ($reload_interval > 0) {
                echo '<script>var defaultReloadTimeout = ' . ( 1000 * 60 * $reload_interval ) . ';</script>';
			}
            $content_change_check_interval = wpds_get_content_change_check_interval();
			if ($content_change_check_interval > 0) {
				echo '<script>var defaultContentChangeCheckInterval = ' . ( 1000 * $content_change_check_interval ) . ';</script>';
			}
		?>
		<script>
				var postModified='<?=get_post_status_hash()?>';
				var layoutWidth=<?=wpds_get_layout_width()?>;
				var layoutMargin=<?=wpds_get_layout_margin()?>;
				var autoPlaySpeed=<?=wpds_get_auto_play_speed()?>;
				var transitionStyle='<?=wpds_get_transition_style()?>';
				var transitionSpeed='<?=wpds_get_transition_speed()?>';
				var showSlideNumber=<?=wpds_show_slide_number() ? 'true' : 'false'?>;
				var centerVertically=<?=wpds_center_vertically() ? 'true' : 'false'?>;
				var autoplayStoppable=<?=wpds_autoplay_stoppable() ? 'true' : 'false'?>;
				var statusUrl='<?=get_site_url(null, 'wpds-status')?>';
		</script>
		<?php endif; ?>

		<?php wp_footer(); ?>

	</body>
</html>
