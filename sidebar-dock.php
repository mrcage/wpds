
	<?php if ( !isset( get_theme_mod( 'layout', [] )['show-dock'] ) || get_theme_mod( 'layout', [] )['show-dock'] ): ?>
		<?php
			$dock_style = [];
			$color_opts = get_theme_mod( 'colors', [] );
			if (!empty($color_opts['dock-foreground-color'])) {
				$dock_style['color'] = '#' . $color_opts['dock-foreground-color'];
			}
			if (!empty($color_opts['dock-background-color'])) {
				$dock_style['background-color'] = $color_opts['dock-background-color'];
			}
		?>
		<footer class="footer"<?php echo print_style($dock_style);?>>
			<div class="container-fluid">
				<?php if ( is_active_sidebar( 'dock' ) ) : ?>
				<div class="row">
					<?php dynamic_sidebar("dock"); ?>
				</div>
				<?php else : ?>
					<p class="lead"><?=__('Please add some widgets to the dock, or disable the dock in the customizer.', 'wpds')?></p>
				<?php endif; ?>
			</div>
		</footer>
	<?php endif; ?>
