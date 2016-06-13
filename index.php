<?php
/**
 * Index
 *
 * Standard loop for the front-page
 *
 * @package WordPress
 * @subpackage Foundation, for WordPress
 * @since Foundation, for WordPress 1.0
 */

get_header(); ?>
<div class="row">
    <!-- Main Content -->
    <div class="large-12 columns" role="content">
		<?php
			$args=array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'orderby' => 'modified',
			);
			$the_query = new WP_Query($args);

			if ($the_query->post_count > 1) {
				$options = [];
				foreach (get_theme_mod( 'signage', [] ) as $k => $v) {
					if (!empty($v)) {
						$val = !is_numeric($v) && !is_bool($v) ? '\'' . $v . '\'' : $v;
						$options[] = $k . ':'. $val;
					}
				}
				echo '<ul data-orbit' . ( !empty($options) ? ' data-options="' . implode(';', $options) . '"' : '') . '>' . "\n";
			} else {
				echo '<ul>';
			}

			if ($the_query->have_posts()) : while ( $the_query->have_posts() ) : $the_query->the_post();

				$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
				$background_image = get_post_meta($post->ID, 'background-image', true);
				$background_color = get_color_option($post->ID, 'background-color');
				$head_color = get_color_option($post->ID, 'headline-color');
				$subhead_color = get_color_option($post->ID, 'subhead-color');
				$copy_color = get_color_option($post->ID, 'copy-color');

				if($background_image != '') :
                			echo '<li class="post-box large-12 columns" style="background:url(' . $background_image . ') 0 0 no-repeat; width:100%; height:100%; background-size:contain;"></li>' . "\n";
				else :
					$thumb = get_the_post_thumbnail($post_id, 'large', array('class' => 'large-3 columns feature'));
					echo '<li class="post-box large-12 columns"' . ( !empty($background_color) ? ' style="background:#' . $background_color . ';"' : '' ) . '>',
          						'<h1' . ( !empty($head_color) ? ' style="color:#' . $head_color . ';"' : '' ) . '>' . get_the_title() . '</h1>' . "\n",
          						'<h2' . ( !empty($subhead_color) ? ' style="color:#' . $subhead_color . ';"' : '' ) . '>' . get_post_meta($post->ID, 'subtitle', true) . '</h2>' . "\n",
          						'<div class="row">',
          						$thumb,
          						'<p class="' . ( !empty($thumb) ? 'large-8' : 'large-11') . ' columns copy end"' . ( !empty($copy_color) ? ' style="color:#' . $copy_color . ';"' : '' ) . '>' . do_shortcode( nl2br(get_the_content()) ) . '</p>',
          						'</div>',
          						'</li>' ."\n";
		              endif;
	            endwhile;
			endif;
			wp_reset_query();
		?>
		</ul>
	</div>
</div>
    <!-- End Main Content -->

<?php get_footer(); ?>
