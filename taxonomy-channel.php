<?php
/**
 * Standard loop for the archive page
 *
 * @package WordPress
*/

get_header(); ?>

    <!-- Main Content -->
		<div id="slider"<?=get_slider_args_html()?>>
			<?php
				if ( have_posts() ) : while ( have_posts() ) : the_post();
                    if (show_post_today( $post->ID )) {
                        print_post_html($post);
                    }
				endwhile; endif;
			?>
		</div>
    <!-- End Main Content -->

<?php get_footer(); ?>
