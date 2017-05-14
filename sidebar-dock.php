
	<?php if ( wpds_show_dock() ): ?>
		<?php
			$dock_style = [];
			$dock_style['color'] = wpds_get_dock_foreground_color();
			$dock_style['background-color'] = wpds_get_dock_background_color();
		?>
		<footer class="footer"<?php echo print_style($dock_style);?>>
			<div class="dock">
				<?php if ( is_active_sidebar( 'dock' ) ) : ?>
				<div class="dock-container">
					<?php dynamic_sidebar("dock"); ?>
				</div>
				<?php else : ?>
					<p class="lead"><?=__('Please add some widgets to the dock, or disable the dock in the customizer.', 'wpds')?></p>
				<?php endif; ?>
			</div>
		</footer>
	<?php endif; ?>
