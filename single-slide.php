<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
*/

get_header(); ?>

		<!-- Main Content -->
		<?php
			while ( have_posts() ) : the_post();
				print_post_html($post);
			endwhile;
		?>
		<!-- End Main Content -->

<?php get_footer(); ?>
