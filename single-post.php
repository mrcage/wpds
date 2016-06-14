<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
*/

get_header(); ?>

    <!-- Main Content -->
		<div id="slider"<?=get_slider_args_html()?>>
			<?php
				while ( have_posts() ) : the_post();
					print_post_html($post);
				endwhile;
			?>
		</div>
    <!-- End Main Content -->

<?php get_footer(); ?>
