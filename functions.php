<?php

/**
 * Functions
 *
 * Core functionality and initial theme setup
 *
 */

// Customizer
require(get_template_directory() . '/inc/customizer.php');
require(get_template_directory() . '/inc/plugins.php');

/**
* Theme setup
*/
function wpds_theme_setup() {

	// Language Translations
	load_theme_textdomain( 'wpds', get_template_directory() . '/languages' );
	load_theme_textdomain( 'tgmpa', get_template_directory() . '/languages' );

	// Custom Editor Style Support
	add_editor_style();

	// Support for Featured Images
	add_theme_support( 'post-thumbnails' );

	// Load post details extension
	locate_template( array( 'inc/post-details.php' ), true, true );

}
add_action( 'after_setup_theme', 'wpds_theme_setup' );

/*
* Creating a function to create our CPT
*/
add_action( 'init', function() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x( 'Slides', 'Post Type General Name', 'wpds' ),
		'singular_name'       => _x( 'Slide', 'Post Type Singular Name', 'wpds' ),
		'menu_name'           => __( 'Slides', 'wpds' ),
		'parent_item_colon'   => __( 'Parent Slide', 'wpds' ),
		'all_items'           => __( 'All Slides', 'wpds' ),
		'view_item'           => __( 'View Slide', 'wpds' ),
		'add_new_item'        => __( 'Add New Slide', 'wpds' ),
		'add_new'             => __( 'Add New', 'wpds' ),
		'edit_item'           => __( 'Edit Slide', 'wpds' ),
		'update_item'         => __( 'Update Slide', 'wpds' ),
		'search_items'        => __( 'Search Slide', 'wpds' ),
		'not_found'           => __( 'Not Found', 'wpds' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'wpds' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Slides', 'wpds' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'revisions' ),
		'taxonomies'          => array( 'channel' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	
	// Registering your Custom Post Type
	register_post_type( 'slide', $args );

});

add_action( 'init', function(){
	$labels = array(
		'name'                           => __( 'Channels', 'wpds' ),
		'singular_name'                  => __( 'Channel', 'wpds' ),
		'search_items'                   => __( 'Search Channels', 'wpds' ),
		'all_items'                      => __( 'All Channels', 'wpds' ),
		'edit_item'                      => __( 'Edit Channel', 'wpds' ),
		'update_item'                    => __( 'Update Channel', 'wpds' ),
		'add_new_item'                   => __( 'Add new Channel', 'wpds' ),
		'new_item_name'                  => __( 'New Channel name', 'wpds' ),
		'menu_name'                      => __( 'Channels', 'wpds' ),
		'view_item'                      => __( 'View Channel', 'wpds' ),
		'popular_items'                  => __( 'Popular Channels', 'wpds' ),
		'separate_items_with_commas'     => __( 'Separate Channels with commas', 'wpds' ),
		'add_or_remove_items'            => __( 'Add or remove Channels', 'wpds' ),
		'choose_from_most_used'          => __( 'Choose from the most used Channels', 'wpds' ),
		'not_found'                      => __( 'No Channels found', 'wpds' )
	);

	register_taxonomy(
		'channel',
		'slide',
		array(
			'label' => __( 'Channel', 'wpds' ),
			'hierarchical' => true,
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud' => false,
			'show_admin_column' => true,
			'rewrite' => array(
				'slug' => 'channels'
			)
		)
	);
});

function get_localized_days() {
    $timestamp = strtotime('next Saturday');
    $days = array();
    for ($i = 0; $i < 7; $i++) {
        $timestamp = strtotime('+1 day', $timestamp);
        $days[$i] = date_i18n('l', $timestamp);
    }
    return $days;
}

function show_post_today( $post_id ) {
    $time_range_days = get_post_meta( $post_id, 'time_range_day' );
    if ( count( $time_range_days ) > 0 ) {
        foreach ($time_range_days as $d) {
            $today_day = date('w');
            if ($today_day == $d) {
                return true;
            }
        }
    } else {
       return true;
    }
    return false;
}

function show_post_at_this_time( $post_id ) {
	$time_range_hour_from = get_post_meta( $post_id, 'time_range_hour_from' );
	$time_range_minute_from = get_post_meta( $post_id, 'time_range_minute_from' );
	$time_range_hour_to = get_post_meta( $post_id, 'time_range_hour_to' );
	$time_range_minute_to = get_post_meta( $post_id, 'time_range_minute_to' );
	if ( isset( $time_range_hour_from[0] ) && isset( $time_range_minute_from[0] ) && isset( $time_range_hour_to[0] ) && isset( $time_range_minute_to[0] ) ) {
		$current_hour = current_time("G");
		$current_minute = intval(current_time("i"));
		$current_time = mktime($current_hour, $current_minute);
		$from_time = mktime($time_range_hour_from[0], $time_range_minute_from[0]);
		$to_time = mktime($time_range_hour_to[0], $time_range_minute_to[0]);
		return $current_time >= $from_time && $current_time <= $to_time;
	}
	return true;
}

// Register Modified Date Column for both posts & pages
function modified_column_register( $columns ) {
	$columns['time_range_days'] = __( 'Show', 'wpds' );
	$columns['active_now'] = __('Active now', 'wpds');
	return $columns;
}
add_filter( 'manage_posts_columns', 'modified_column_register' );

function modified_column_display( $column_name, $post_id ) {
	switch ( $column_name ) {
        case 'time_range_days':
            $today_day = date('w');
            $time_range_days = get_post_meta( $post_id, 'time_range_day' );
            if ( count( $time_range_days ) > 0 ) {
                $get_localized_days = get_localized_days();
                $days = array();
                foreach ($time_range_days as $d) {
                    $day_name = $get_localized_days[$d];
                    if ($today_day == $d) {
                        $day_name = '<strong>' .$day_name . '</strong>';
                    }
                    $days[] = $day_name;
                }
                echo implode(', ', $days);
            } else {
                echo '<strong>' . __('Everyday', 'wpds') . '</strong>';
            }
			$time_range_hour_from = get_post_meta( $post_id, 'time_range_hour_from' );
			$time_range_minute_from = get_post_meta( $post_id, 'time_range_minute_from' );
			$time_range_hour_to = get_post_meta( $post_id, 'time_range_hour_to' );
			$time_range_minute_to = get_post_meta( $post_id, 'time_range_minute_to' );
			if ( isset( $time_range_hour_from[0] ) && isset( $time_range_minute_from[0] ) && isset( $time_range_hour_to[0] ) && isset( $time_range_minute_to[0] ) ) {
				$from_time = mktime($time_range_hour_from[0], $time_range_minute_from[0]);
				$to_time = mktime($time_range_hour_to[0], $time_range_minute_to[0]);
				$time_str = date("H:i", $from_time) . ' - ' . date("H:i", $to_time);
				echo ', ';
				if ( show_post_at_this_time( $post_id ) ) {
					echo '<strong>' . $time_str . '</strong>';
				} else {
					echo $time_str;
				}
			}
            break;
		case 'active_now':
			if ( show_post_today( $post_id ) && show_post_at_this_time( $post_id ) && get_post_status ( $ID ) == 'publish' ) {
				$term_list = wp_get_post_terms( $post_id, 'channel');
				if ( count( $term_list ) > 0) {
					$reason = __( 'Active', 'wpds' );
					echo '<span class="dashicons dashicons-yes" style="color:#46b450;" title="' . $reason . '"></span>';
				} else {
					$reason = __( 'Published, but not assigned to any channel', 'wpds' );
					echo '<span class="dashicons dashicons-no" style="color:orange;" title="' . $reason . '"></span>';
				}
			} else {
                if ( get_post_status ( $ID ) != 'publish' ) {
                    $reason = __( 'Not published', 'wpds' );
                } else if ( ! show_post_today( $post_id ) ) {
                    $reason = __( 'Not shown today', 'wpds' );
                } else if ( ! show_post_at_this_time( $post_id ) ) {
                    $reason = __( 'Not shown at this time', 'wpds' );
                } else {
                    $reason = 'Unknown';
                }
				echo '<span class="dashicons dashicons-no" style="color:lightgray;" title="' . $reason . '"></span>';
			}
			break;
	}
}
add_action( 'manage_posts_custom_column', 'modified_column_display', 10, 2 );

//Add the support for your cpt in the Widget Activity of the Admin Dashboard
if ( is_admin() ) {
	add_filter( 'dashboard_recent_posts_query_args', 'add_page_to_dashboard_activity' );
	function add_page_to_dashboard_activity( $query_args ) {
		if ( is_array( $query_args[ 'post_type' ] ) ) {
			//Set yout post type
			$query_args[ 'post_type' ][] = 'slide';
		} else {
			$temp = array( $query_args[ 'post_type' ], 'slide' );
			$query_args[ 'post_type' ] = $temp;
		}
		return $query_args;
	}
}

// Alter the loop such that posts are sorted by modified
add_action( 'pre_get_posts', function($query) {
	if ( $query->is_main_query() && ( $query->is_home() || $query->is_search() || $query->is_archive() )  )
	{
		$query->set( 'orderby', 'modified' );
		$query->set( 'order', 'desc' );
	}
});

// Statistics dashboard widget
// wp_dashboard_setup is the action hook
add_action('wp_dashboard_setup', function() { 
	// add dashboard widget
    wp_add_dashboard_widget('custom_stats_widget', __('Statistics', 'wpds'), function() {
		$args = array(
			'public' => true ,
			'_builtin' => false );
		$output = 'object';
		$operator = 'and';
		echo '<table>';
		//loop over all custom post types
		$post_types = get_post_types( $args , $output , $operator );
		foreach( $post_types as $post_type ) {
			$num_posts = wp_count_posts( $post_type->name );
			$num = number_format_i18n( $num_posts->publish );
			$text = _n( $post_type->labels->singular_name, $post_type->labels->name , intval( $num_posts->publish ) );
			if ( current_user_can( 'edit_posts' ) ) {
				$num = "<a href='edit.php?post_type=$post_type->name'>$num</a>";
				$text = "<a href='edit.php?post_type=$post_type->name'>$text</a>";
			}
			echo '<tr><td class="first b b-' . $post_type->name . '">' . $num . '</td>';
			echo '<td class="t ' . $post_type->name . '">' . $text . '</td></tr>';
		}

		//loop over all taxonomies
		$taxonomies = get_taxonomies( $args , $output , $operator ); 
		foreach( $taxonomies as $taxonomy ) {
			$num_terms  = wp_counT_terms( $taxonomy->name );
			$num = number_format_i18n( $num_terms );
			$text = _n( $taxonomy->labels->singular_name, $taxonomy->labels->name , intval( $num_terms ));
			if ( current_user_can( 'manage_categories' ) ) {
				$num = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$num</a>";
				$text = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$text</a>";
			}
			echo '<tr><td class="first b b-' . $taxonomy->name . '">' . $num . '</td>';
			echo '<td class="t ' . $taxonomy->name . '">' . $text . '</td></tr>';
		}
		echo '</table>';
	});

});

// Channels dashboard widget
// wp_dashboard_setup is the action hook
add_action('wp_dashboard_setup', function() {
	// add dashboard widget
    wp_add_dashboard_widget('channels_widget', __('Channels', 'wpds'), function() {
		$args = array(
			'public' => true ,
			'_builtin' => false );
		$output = 'object';
		$operator = 'and';
		echo '<ul>';
		$terms = get_terms( 'channel', array(
			'hide_empty' => true,
		) );
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
			echo '<li><a href="' . get_term_link( $term ) . '" target="_blank">' . $term->name . '</a> (' . $active_slides . '/' . count($slides) . ' ' . __('Slides', 'wpds') . ')</li>';
		}
		echo '</ul>';
	});

});

// Settings dashboard widget
// wp_dashboard_setup is the action hook
add_action('wp_dashboard_setup', function() { 
	// add dashboard widget
    wp_add_dashboard_widget('custom_settings_widget', __('Important Settings', 'wpds'), function() {
        //echo '<p><strong>' . __( 'Slider', 'wpds' ) . '</strong></p>';
		echo '<table style="width:100%">';
        echo wpds_dashboard_settings_item( __('Timer speed (s)', 'wpds'), round(wpds_get_auto_play_speed()/1000, 2), 'signage[timer_speed]' );
        echo wpds_dashboard_settings_item( __('Content change check interval (s)', 'wpds'), wpds_get_content_change_check_interval(), 'signage[content_change_check_interval]' );
        $themes = wpds_get_revealjs_themes();
        echo wpds_dashboard_settings_item( __('Theme', 'wpds'), isset($themes[wpds_get_theme()]) ? $themes[wpds_get_theme()] : '-', 'signage[theme]' );
        echo wpds_dashboard_settings_item( __('Show dock', 'wpds'), wpds_show_dock() ? __( 'Yes', 'wpds' ) : __( 'No', 'wpds' ), 'layout[show-dock]' );
		echo '</table>';
	});

});

function wpds_dashboard_settings_item($label, $value, $control_name) {
    return '<tr><td><a href="' . admin_url( 'customize.php?autofocus[control]=' . $control_name ) . '">' . $label . '</a></td><td>' . $value . '</td></tr>';    
}

//***********************
//
// SIMPLIFY UI
//
//***********************

add_action('admin_menu', 'my_remove_menu_pages');
if (!current_user_can('manage_options')) {
	add_action( 'admin_menu', 'my_remove_menu_pages' );
}
function my_remove_menu_pages() {
	remove_menu_page( 'link-manager.php' ); // Links
	remove_menu_page( 'edit-comments.php' ); // Comments
	remove_menu_page( 'edit.php' ); // Posts
	remove_menu_page( 'edit.php?post_type=page' ); // Pages
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
	remove_menu_page( 'tools.php' ); // Tools
	remove_submenu_page ( 'themes.php', 'nav-menus.php' ); // Appearance-->Menus
}

//***********************
//
// CUSTOMIZE UI
//
//***********************
	// remove some metaboxes
	function remove_post_custom_fields() {
		remove_meta_box('postexcerpt', 'post', 'normal'); // removes excerpt metabox
		remove_meta_box('trackbacksdiv', 'post', 'normal'); // removes trackbacks metabox
		remove_meta_box('commentstatusdiv', 'post', 'normal'); // removes discussion metabox
		remove_meta_box('postcustom', 'post', 'normal'); // removes custom metaboxes (other than defined here)
		remove_meta_box('commentsdiv', 'post', 'normal'); // removes comments metabox
		//remove_meta_box('revisionsdiv', 'post', 'normal'); // removes revision metabox
		remove_meta_box('authordiv', 'post', 'normal'); // removes author metabox
		remove_meta_box('sqpt-meta-tags', 'post', 'normal'); // removes  metabox
		remove_meta_box('categorydiv', 'post', 'normal'); // removes categories metabox
		remove_meta_box('slugdiv', 'post', 'normal'); // removes slugs metabox
		remove_meta_box('formatdiv', 'post', 'normal'); // removes formats metabox
		remove_meta_box('tagsdiv-post_tag', 'post', 'normal'); // removes tags metabox
		remove_meta_box('pageparentdiv', 'post', 'normal'); // removes attributes metabox
		
		remove_meta_box('authordiv', 'slide', 'normal'); // removes author metabox
	}
	add_action( 'admin_menu' , 'remove_post_custom_fields' );

	// remove some customization options for admins
	if (current_user_can('manage_options')) {
		add_action( 'admin_menu', 'admin_remove_menu_pages' );
	}
	function admin_remove_menu_pages() {
        remove_menu_page( 'link-manager.php' ); // Links
        remove_menu_page( 'edit-comments.php' ); // Comments
	}

	// disable default dashboard widgets
	function disable_default_dashboard_widgets() {

		remove_meta_box('dashboard_right_now', 'dashboard', 'core');
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
		remove_meta_box('dashboard_plugins', 'dashboard', 'core');

		remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
		remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
		remove_meta_box('dashboard_primary', 'dashboard', 'core');
		remove_meta_box('dashboard_secondary', 'dashboard', 'core');
	}
	add_action('admin_menu', 'disable_default_dashboard_widgets');

	// create custom dashboard widget
	/*
	function custom_dashboard_widget() {
			echo "<p>Please keep the title to no more than 22 characters.</p>",
				"<p>Please keep the body copy to no more than 27-30 words.</p>",
				"<p>If using a background image, a 16:9 ratio will do best to fill the screen (for example, 1920px wide x 1080px high). Remember that the information dock will cover approximately 200px of the bottom of the image.";
	}
	function add_custom_dashboard_widget() {
		wp_add_dashboard_widget('custom_dashboard_widget', 'Content Guidelines', 'custom_dashboard_widget');
	}
	add_action('wp_dashboard_setup', 'add_custom_dashboard_widget');
	*/

//***********************
//
// CUSTOMIZE WYSIWYG
//
//***********************
	if( !function_exists('base_extended_editor_mce_buttons') ){
		function base_extended_editor_mce_buttons($buttons) {
			// The settings are returned in this array. Customize to suite your needs.
			return array(
				'bold', 'italic', 'strikethrough', 'separator', 'bullist', 'numlist', 'blockquote', 'separator', 'charmap', 'removeformat'
			);
			/* WordPress Default
			return array(
				'bold', 'italic', 'strikethrough', 'separator',
				'bullist', 'numlist', 'blockquote', 'separator',
				'justifyleft', 'justifycenter', 'justifyright', 'separator',
				'link', 'unlink', 'wp_more', 'separator',
				'spellchecker', 'fullscreen', 'wp_adv'
			); */
		}
		add_filter("mce_buttons", "base_extended_editor_mce_buttons", 0);
	}


	// hide slugs
	function hide_all_slugs() {
	global $post;
	$hide_slugs = "<style type=\"text/css\"> #slugdiv, #edit-slug-box { display: none; }</style>";
	print($hide_slugs);
	}
	add_action( 'admin_head', 'hide_all_slugs'  );


	// customize backend footer
	function remove_footer_admin ($text) {
		return $text . ' &#x272D;&nbsp; ' . sprintf(__('Developed by <a href="%s" target="_blank">%s</a> based on work by <a href="%s" target="_blank">%s</a>.', 'wpds'), 'https://nicu.ch', 'Nicolas Perrenoud', 'http://pixelydo.com/', 'Nate Jones');
	}
	add_filter('admin_footer_text', 'remove_footer_admin');


//***********************
//
// REMOVE SOME WIDGETS
//
//***********************

function wpds_remove_some_wp_widgets () {
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Search');
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Archives');
	unregister_widget('WP_Widget_Links');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Categories');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Nav_Menu_Widget');
}

add_action('widgets_init','wpds_remove_some_wp_widgets', 1);



//***********************
//
// COUNT THE WIDGETS
//
//***********************

function count_sidebar_widgets( $sidebar_id) {
    $the_sidebars = wp_get_sidebars_widgets();
    if( !isset( $the_sidebars[$sidebar_id] ) )
        return __( 'Invalid sidebar ID', 'wpds' );
    if( $echo )
        echo count( $the_sidebars[$sidebar_id] );
    else
        return count( $the_sidebars[$sidebar_id] );
}

function get_grid_number_from_widgets( $sidebar_id ) {
	if (count_sidebar_widgets( $sidebar_id ) > 0){
		return (int) (100 / count_sidebar_widgets( $sidebar_id ));
	}
	else {
		return 100;
	}
}


//***********************
//
// CREATE DOCK WIDGET AREA
//
//***********************
function wpds_widgets_init() {
	$widget_width = get_grid_number_from_widgets( 'dock' );
	register_sidebar(array(
		'id' => 'dock',
		'name'=> __('Dock', 'wpds'),
		'description' => __('Widget area at the bottom of the page.', 'wpds'),
		'before_widget' => $widget_count . '<div id="%1$s" class="dock-element %2$s" style="width:' . $widget_width . '%%">',
		'after_widget' => '</div>'."\n",
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
}
add_action( 'widgets_init', 'wpds_widgets_init' );


//***********************
//
// CUSTOM LOGIN LOGO
//
//***********************

function wpds_custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url('.get_bloginfo('template_url').'/login_page_logo.png) !important; }
    </style>';
}
add_action('login_head', 'wpds_custom_login_logo');


// Disable the Admin Bar.
add_filter( 'show_admin_bar', '__return_false' );

function wpds_remove_admin_bar_links() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
	$wp_admin_bar->remove_menu('new-content');
	$wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'wpds_remove_admin_bar_links' );

/**
* Load style
*/
function wpds_theme_enqueue_styles() {
    wp_enqueue_style( 'wpds-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'wpds_theme_enqueue_styles' );

/**
* Load scripts
*/
function wpds_load_scripts() {
	wp_enqueue_style( 'reveal.js_css', get_template_directory_uri() . '/reveal.js/css/reveal.css' );
	wp_enqueue_style( 'reveal.js_theme', get_template_directory_uri() . '/'. wpds_get_theme_css() );

	wp_register_script( 'modernizr', get_template_directory_uri() . '/javascripts/vendor/custom.modernizr.js' );
	wp_enqueue_script( 'modernizr' );

	wp_register_script( 'reveal.js', get_template_directory_uri() . '/reveal.js/js/reveal.js' );
	wp_enqueue_script( 'reveal.js', false, array('jquery'), false, true );
	
	wp_register_script( 'app-js', get_template_directory_uri() . '/javascripts/app.js' );
	wp_enqueue_script( 'app-js', false, array('jquery'), false, true );
}
add_action( 'wp_enqueue_scripts', 'wpds_load_scripts' );

//***********************
//
// Theme helper functions
//
//***********************

/**
* Get a color option, either from post meta data or from theme options
*/
function get_color_option($post_id, $key) {
	$page_color = get_post_meta($post_id, $key, true);
	if (!empty($page_color)) {
		return $page_color;
	}
	return '';
}

function print_style($args) {
        if (count($args) > 0) {
                $style = [];
                foreach ($args as $k => $v) {
                        $style[] = $k . ':' . $v;
                }
                return ' style="' . implode('; ', $style) . '"';
        }
        return '';
}

function print_data_attrs($data) {
	if (count($data) > 0) {
		$attrs = [];
		foreach ($data as $k => $v) {
			$attrs[] = $k . '="' . $v . '"';
		}
		return ' ' . implode(' ', $attrs);
	}
	return '';
}

function print_post_html($post) {
	
	// Get the content
	ob_start(); the_content(); $content = ob_get_clean();

	// Layout block with featured image
	$thumb = get_the_post_thumbnail($post->ID, 'large');
	if (!empty($thumb)) {
		$content = '<div class="layout-container">' .
			'<div class="feature-img-container">' . $thumb . '</div>' .
			'<div class="content-container">' . $content . '</div>' .
			'</div>';
	}
	
	// Data attributes
	$data_attrs = [];
	
	// Background color
	$background_color = get_color_option($post->ID, 'background-color');
	if (!empty($background_color)) {
		$data_attrs['data-background-color'] = $background_color;
	}

	// Background image
	$background_image = get_post_meta($post->ID, 'background-image', true);
	if (!empty($background_image)) {
		$data_attrs['data-background-image'] = $background_image;
	}
	
	// Background video
	$background_video = get_post_meta($post->ID, 'background-video', true);
	if (!empty($background_video)) {
		$data_attrs['data-background-video'] = $background_video;
	}

	// Slide duration
	$slide_duration = get_post_meta($post->ID, 'slide_duration', true);
	if (!empty($slide_duration)) {
		$data_attrs['data-autoslide'] = $slide_duration * 1000;
	}

	// Text colors
	$head_color = get_color_option($post->ID, 'headline-color');
	$subhead_color = get_color_option($post->ID, 'subhead-color');
	$copy_color = get_color_option($post->ID, 'copy-color');
	
    if (wpds_hide_title_if_using_backgroun_image() && (!empty($background_image) || !empty($background_video))) {
        $title = $subtitle = '';
    } else {
        $title = '<h2' . ( !empty($head_color) ? ' style="color:' . $head_color . ';"' : '' ) . '>' . get_the_title() . '</h2>' . "\n";
        $subtitle = '<h3' . ( !empty($subhead_color) ? ' style="color:' . $subhead_color . ';"' : '' ) . '>' . get_post_meta($post->ID, 'subtitle', true) . '</h3>' . "\n";
    }
    $content_block = '<div' . ( !empty($copy_color) ? ' style="color:' . $copy_color . ';"' : '' ) . '>' . $content . '</div>';
	echo "\n" . '<section ' . print_data_attrs($data_attrs) . '>',
				$title,
				$subtitle,
				$content_block,
				'</section>' ."\n";
}

// WPDS status page
add_action('init', function() {
	$url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
	if ( $url_path === 'wpds-status' ) {
		// load the file if exists
		$load = locate_template('status.php', true);
		if ($load) {
			exit(); // just exit if template was found and loaded
		}
	}
});

function get_post_status_hash() {
	global $post;
	$data = [];
	$args = array(
		'post_type' => 'slide',
		'post_status' => 'publish',
		'orderby' => 'modified',
	);
	$the_query = new WP_Query($args);
	if ($the_query->have_posts()) : while ( $the_query->have_posts() ) : $the_query->the_post();
		if ( show_post_today( $post->ID ) && show_post_at_this_time( $post->ID ) ) {
			$data[] = $post->ID.":".$post->post_modified;
		}
	endwhile; endif;
	wp_reset_query();
    // Add theme settings
    $data[] = serialize(get_theme_mods());
	return md5(implode(";", $data));
}
?>
