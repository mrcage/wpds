<?php
/**
 * Standard loop for the front-page
 *
 * @package WordPress
*/

get_header(); ?>

	<!-- Main Content -->
	<?php
		if (wpds_get_index_behaviour() == 'channels' && !is_customize_preview() ) {
	?>
			<div class="overview-container">
				<h1><?=__('Digital Signage', 'wpds')?></h1>
				<table class="overview-channels">
					<thead>
						<tr>
							<th><?=__('Channel', 'wpds')?></th>
							<th><?=__('URL', 'wpds')?></th>
							<th><?=__('Slides', 'wpds')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$terms = get_terms( 'channel', array(
								'hide_empty' => true,
								'public' => true ,
							) );
							if (count($terms) > 0) {
								foreach ($terms as $term) {
									$slides = get_posts(array(
									  'post_type' => 'slide',
									  'numberposts' => -1,
									  'tax_query' => array(
										array(
										  'taxonomy' => 'channel',
										  'field' => 'id',
										  'terms' => $term->term_taxonomy_id, // Where term_id of Term 1 is "1".
										  'include_children' => false
										)
									  )
									));
									$active_slides = 0;
									foreach ($slides as $slide) {
										if ( show_post_today( $slide->ID ) && show_post_at_this_time( $slide->ID ) && get_post_status ( $slide->ID ) == 'publish' ) {
											$active_slides++;
										}
									}
									?>
										<tr>
											<td><?=$term->name?></td>
											<td><a href="<?=get_term_link( $term )?>"><?=get_term_link( $term )?></a></td>
											<td><?=($active_slides . '/' . count($slides))?></td>
										</tr>
									<?php
								}
							} else {
								?><tr><td colspan="3"><em><?=__('No Channels found', 'wpds')?>!</em></td></tr><?php
							}
						?>
					</tbody>
				</table>
				<p class="overview-login"><a href="<?=admin_url()?>"><?=__( 'Login', 'wpds' )?></a></p>
			</div>
			<?php
		} else if (wpds_get_index_behaviour() == 'slides' || is_customize_preview()) {
			$args = array(
				'post_type' => 'slide',
				'post_status' => 'publish',
				'orderby' => wpds_get_slide_order(),
			);
			$the_query = new WP_Query($args);
			if ($the_query->have_posts()) : while ( $the_query->have_posts() ) : $the_query->the_post();
				if (show_post_today( $post->ID ) && show_post_at_this_time( $post->ID ) ) {
					print_post_html($post);
				}
			endwhile; endif;
			wp_reset_query();
		}

	?>
	<!-- End Main Content -->

<?php get_footer(); ?>
