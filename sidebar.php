<?php
/**
 * Sidebar
 *
 * Content for our sidebar, provides prompt for logged in users to create widgets
 *
 * @package WordPress
 */
?>

<!-- Sidebar -->
<aside class="three columns">

<?php if ( dynamic_sidebar('Sidebar Right') ) : elseif( current_user_can( 'edit_theme_options' ) ) : ?>

	<h5><?php _e( 'No widgets found.', 'wpds' ); ?></h5>
	<p><?php printf( __( 'It seems you don\'t have any widgets in your sidebar! Would you like to %s now?', 'wpds' ), '<a href=" '. get_admin_url( '', 'widgets.php' ) .' ">populate your sidebar</a>' ); ?></p>

<?php endif; ?>

</aside>
<!-- End Sidebar -->
