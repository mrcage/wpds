<?php
/**
 * Index
 *
 * Standard loop for the front-page
 *
 * @package WordPress
*/

get_header(); ?>

    <!-- Main Content -->
		<?php
			$args=array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'orderby' => 'modified',
			);
			$the_query = new WP_Query($args);

			$options = [];
			$signage_opts = get_theme_mod( 'signage', [] );
			if (!empty($signage_opts['timer_speed']) && intval($signage_opts['timer_speed']) > 0) {
				$options['autoplaySpeed'] = intval($signage_opts['timer_speed']);
			}
			if (!empty($signage_opts['animation_speed']) && intval($signage_opts['animation_speed']) > 0) {
				$options['speed'] = intval($signage_opts['animation_speed']);
			}
			$slider_args = ( !empty($options) ? ' data-slick=\'' . json_encode($options) . '\'' : '');
		?>
		<div id="slider"<?=$slider_args?>>
			<?php
				if ($the_query->have_posts()) : while ( $the_query->have_posts() ) : $the_query->the_post();

					$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
					$background_image = get_post_meta($post->ID, 'background-image', true);
					$background_color = get_color_option($post->ID, 'background-color');
					$head_color = get_color_option($post->ID, 'headline-color');
					$subhead_color = get_color_option($post->ID, 'subhead-color');
					$copy_color = get_color_option($post->ID, 'copy-color');

					$thumb = get_the_post_thumbnail($post_id, 'large', array('class' => 'img-responsive'));
					$article_style = [];
					if (!empty($background_color)) {
						$article_style['background-color'] = '#' . $background_color;
					}
					if (!empty($background_image)) {
						$article_style['background-image'] = 'url(' . $background_image . ')';
					}
					echo '<article class="container-fluid"' . print_style($article_style) . '>',
								'<h1' . ( !empty($head_color) ? ' style="color:#' . $head_color . ';"' : '' ) . '>' . get_the_title() . '</h1>' . "\n",
								'<h2' . ( !empty($subhead_color) ? ' style="color:#' . $subhead_color . ';"' : '' ) . '>' . get_post_meta($post->ID, 'subtitle', true) . '</h2>' . "\n",
								'<div class="row">',
							!empty($thumb) ? '<div class="col-md-4 col-sm-4 col-xs-4">' . $thumb . '</div>' : '',
							'<p class="' . ( !empty($thumb) ? 'col-md-8 col-sm-8 col-xs-8' : 'col-md-12 col-sm-12 col-xs-12') . ' lead"' . ( !empty($copy_color) ? ' style="color:#' . $copy_color . ';"' : '' ) . '>' . do_shortcode( nl2br(get_the_content()) ) . '</p>',
								'</div>',
								'</article>' ."\n";
				endwhile; endif;
				wp_reset_query();
			?>
		</div>
    <!-- End Main Content -->

<?php get_footer(); ?>
