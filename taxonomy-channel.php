<?php
/**
 * Standard loop for the archive page
 *
 * @package WordPress
*/

get_header(); ?>

		<!-- Main Content -->
		<?php
			if ( have_posts() ) : while ( have_posts() ) : the_post();
				if ( show_post_today( $post->ID ) && show_post_at_this_time( $post->ID ) ) {
					print_post_html($post);
				}
			endwhile; endif;
		?>
		<!-- End Main Content -->

<?php get_footer(); ?>
