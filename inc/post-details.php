<?php

/**
* Adds a subtitle text field below the post title
*/
add_action( 'edit_form_after_title', 'myprefix_edit_form_after_title' );
function myprefix_edit_form_after_title($post) {
	$wpds_stored_meta = get_post_meta( $post->ID );
	echo '<div id="subtitlediv">
		<input type="text" name="subtitle" id="subtitle" placeholder="' . __( 'Subtitle', 'wpds' ) . '" value="' . ( isset ( $wpds_stored_meta['subtitle'] ) ? $wpds_stored_meta['subtitle'][0] : '' ) . '" />
	</div>';
}

/**
 * Adds a meta box to the post editing screen
 */
function wpds_custom_meta() {
	add_meta_box( 'wpds', __( 'Style', 'wpds' ), 'wpds_meta_callback', 'slide' );
    add_meta_box( 'wpds_time_range', __( 'Display settings', 'wpds' ), 'wpds_meta_callback_time_range', 'slide', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'wpds_custom_meta' );

/**
 * Outputs the content of the meta box
 */
function wpds_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'wpds_nonce' );
	$wpds_stored_meta = get_post_meta( $post->ID );
    ?>
    <div style="display:table; width: 100%">
        <div style="display: table-cell; width: 45%; vertical-align: top; padding-right: 5%">
            <h3><?=__('Background', 'wpds')?></h3>
            <p>
                <label class="customize-control-title" for="background-image"><?php _e( 'Background Image', 'wpds' )?></label>
                <input type="button" id="background-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'wpds' )?>" />
                <input class="background-image-url" type="hidden" name="background-image" id="background-image" value="<?php if ( isset ( $wpds_stored_meta['background-image'] ) ) echo $wpds_stored_meta['background-image'][0]; ?>" />
                <div class="background-image-preview" id="background-image-preview"></div>
                <span class="background-image-remove" id="background-image-remove"><a href="javascript:;"><?=__('Remove background image', 'wpds')?></a></span>
            </p>
            <p>
                <label class="customize-control-title" for="background-video"><?php _e( 'Background video (URL)', 'wpds' )?></label>
                <input class="background-video-url" type="text" name="background-video" id="background-video" value="<?php if ( isset ( $wpds_stored_meta['background-video'] ) ) echo $wpds_stored_meta['background-video'][0]; ?>" />
            </p>
            <?php	
                $color_controls = array(
                    'background-color' => __( 'Background Color', 'wpds' ),
                );
                foreach ($color_controls as $key => $title) {
                    wpds_display_color_select($key, $title);
                }
            ?>
        </div>
        <div style="display: table-cell; width: 45%; vertical-align: top; padding-left: 5%">
            <h3><?=__('Foreground', 'wpds')?></h3>
            <?php	
                $color_controls = array(
                    'headline-color' => __( 'Headline Color', 'wpds' ),
                    'subhead-color' => __( 'Sub-headline Color', 'wpds' ),
                    'copy-color' => __( 'Copy Color', 'wpds' ),
                );
                foreach ($color_controls as $key => $title) {
                    wpds_display_color_select($key, $title);
                }
            ?>
        </div>
    </div>
    <?php
}

function wpds_display_color_select($key, $title) {
    ?>
    <p>
        <label class="customize-control-title" for="<?=$key?>"><?=$title?></label>
        <input type="text" class="color-field" name="<?=$key?>" id="<?=$key?>" value="<?= isset ( $wpds_stored_meta[$key] ) ? $wpds_stored_meta[$key][0] : '' ?>" />
    </p>
    <?php
}

function wpds_meta_callback_time_range( $post ) {
    $wpds_stored_meta = get_post_meta( $post->ID );
    ?>
	<input type="hidden" name="time_range_day_option_showed" value="1"/>
    <p><?=__('Only show on certain days', 'wpds')?>:
        <?php
            $selected_days = isset( $wpds_stored_meta['time_range_day'] ) && is_array( $wpds_stored_meta['time_range_day'] ) ? $wpds_stored_meta['time_range_day'] : [];
            $localizes_days = get_localized_days();
            for ($k = 1; $k < 8; $k++) {
              $day = $localizes_days[$k % 7];
              ?>
                <br/><label><input type="checkbox" name="time_range_day[]" value="<?=($k % 7)?>" <?php if ( in_array( $k % 7, $selected_days ) ) { echo ' checked="checked"'; } ?>/> <?=$day?></label>
              <?php
            }
        ?>
    </p>
    <p><?=__('Only show on certain time', 'wpds')?>:<br/>
		<?php
			$time_range_hour_from = isset( $wpds_stored_meta['time_range_hour_from'] ) ? intval($wpds_stored_meta['time_range_hour_from'][0]) : '';
			$time_range_minute_from = isset( $wpds_stored_meta['time_range_minute_from'] ) ? intval($wpds_stored_meta['time_range_minute_from'][0]) : '';
			$time_range_hour_to = isset( $wpds_stored_meta['time_range_hour_to'] ) ? intval($wpds_stored_meta['time_range_hour_to'][0]) : '';
			$time_range_minute_to = isset( $wpds_stored_meta['time_range_minute_to'] ) ? intval($wpds_stored_meta['time_range_minute_to'][0]) : '';
		?>
			<input type="number" name="time_range_hour_from" value="<?=$time_range_hour_from?>" min="0" max="23" style="width: 50px;" /> : <input type="number" name="time_range_minute_from" value="<?=$time_range_minute_from?>" min="0" max="59" size="2"  style="width: 50px;" /> - 
			<input type="number" name="time_range_hour_to" value="<?=$time_range_hour_to?>" min="0" max="23" style="width: 50px;" /> : <input type="number" name="time_range_minute_to" value="<?=$time_range_minute_to?>" min="0" max="59" size="2"  style="width: 50px;" /><br/>
		
	</p>
    <p><?=__('Override slide duration', 'wpds')?> (<span style="white-space: nowrap;"><?=round(wpds_get_auto_play_speed()/1000)?>  <?=__('seconds', 'wpds')?></span>):<br/>
		<?php
			$slide_duration = isset( $wpds_stored_meta['slide_duration'] ) ? intval($wpds_stored_meta['slide_duration'][0]) : '';
		?>
			<input type="number" name="slide_duration" value="<?=$slide_duration?>" min="1" step="1" style="width: 50px;" /> <?=__('seconds', 'wpds')?>
		
	</p>
    <?php
}

/**
 * Saves the custom meta input
 */
function wpds_meta_save( $post_id ) {

	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'wpds_nonce' ] ) && wp_verify_nonce( $_POST[ 'wpds_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}

	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'subtitle' ] ) ) {
		update_post_meta( $post_id, 'subtitle', sanitize_text_field( $_POST[ 'subtitle' ] ) );
	}

	// Checks for input and saves if needed
	if( isset( $_POST[ 'background-color' ] ) ) {
		update_post_meta( $post_id, 'background-color', $_POST[ 'background-color' ] );
	}
	if( isset( $_POST[ 'headline-color' ] ) ) {
		update_post_meta( $post_id, 'headline-color', $_POST[ 'headline-color' ] );
	}
	if( isset( $_POST[ 'subhead-color' ] ) ) {
		update_post_meta( $post_id, 'subhead-color', $_POST[ 'subhead-color' ] );
	}
	if( isset( $_POST[ 'copy-color' ] ) ) {
		update_post_meta( $post_id, 'copy-color', $_POST[ 'copy-color' ] );
	}

	// Checks for input and saves if needed
	if( isset( $_POST[ 'background-image' ] ) ) {
		update_post_meta( $post_id, 'background-image', $_POST[ 'background-image' ] );
	}
	if( isset( $_POST[ 'background-video' ] ) ) {
		update_post_meta( $post_id, 'background-video', $_POST[ 'background-video' ] );
	}

    // Time range
	if ( isset( $_POST['time_range_day_option_showed'] ) ) { // solves iussue with quick-edit
		delete_post_meta( $post_id, 'time_range_day' );
		if ( isset( $_POST[ 'time_range_day' ] ) && count( $_POST[ 'time_range_day' ] ) > 0 ) {
			foreach ($_POST[ 'time_range_day' ] as $day) {
				add_post_meta( $post_id, 'time_range_day', $day );
			}
		}
		
		if ( isset( $_POST['time_range_hour_from'] ) && is_numeric( $_POST['time_range_hour_from'] ) && $_POST['time_range_hour_from']  >= 0 && $_POST['time_range_hour_from']  < 24 ) {
			$time_range_hour_from = intval($_POST['time_range_hour_from']);
		}
		if ( isset( $_POST['time_range_minute_from'] ) && is_numeric( $_POST['time_range_minute_from'] ) && $_POST['time_range_minute_from']  >= 0 && $_POST['time_range_minute_from']  < 60 ) {
			$time_range_minute_from = intval($_POST['time_range_minute_from']);
		}
		if ( isset( $_POST['time_range_hour_to'] ) && is_numeric( $_POST['time_range_hour_to'] ) && $_POST['time_range_hour_to']  >= 0 && $_POST['time_range_hour_to']  < 24 ) {
			$time_range_hour_to = intval($_POST['time_range_hour_to']);
		}
		if ( isset( $_POST['time_range_minute_to'] ) && is_numeric( $_POST['time_range_minute_to'] ) && $_POST['time_range_minute_to']  >= 0 && $_POST['time_range_minute_to']  < 60 ) {
			$time_range_minute_to = intval($_POST['time_range_minute_to']);
		}
		if ( isset( $time_range_hour_from ) && !isset( $time_range_minute_from ) ) {
			$time_range_minute_from = 0;
		}
		if ( isset( $time_range_hour_to ) && !isset( $time_range_minute_to ) ) {
			$time_range_minute_to = 0;			
		}		
		if ( ( isset( $time_range_hour_from ) && isset( $time_range_minute_from ) && isset( $time_range_hour_to ) && isset( $time_range_minute_to ) ) && 
			( $time_range_hour_from < $time_range_hour_to || ( $time_range_hour_from == $time_range_hour_to && $time_range_minute_from < $time_range_minute_to ) )
		) {
			update_post_meta( $post_id, 'time_range_hour_from', $time_range_hour_from );
			update_post_meta( $post_id, 'time_range_minute_from', $time_range_minute_from );
			update_post_meta( $post_id, 'time_range_hour_to', $time_range_hour_to );
			update_post_meta( $post_id, 'time_range_minute_to', $time_range_minute_to );
		} else {
			delete_post_meta( $post_id, 'time_range_hour_from' );
			delete_post_meta( $post_id, 'time_range_minute_from' );
			delete_post_meta( $post_id, 'time_range_hour_to' );
			delete_post_meta( $post_id, 'time_range_minute_to' );
		}

		if ( isset( $_POST['slide_duration'] ) && is_numeric( $_POST['slide_duration'] ) && $_POST['slide_duration']  >= 1 ) {
			$slide_duration = intval($_POST['slide_duration']);
			update_post_meta( $post_id, 'slide_duration', $slide_duration );
		} else {
			delete_post_meta( $post_id, 'slide_duration' );
		}

	}
}
add_action( 'save_post', 'wpds_meta_save' );


/**
 * Adds the meta box stylesheet when appropriate
 */
function wpds_admin_styles(){
	global $typenow;
	if( $typenow == 'slide' ) {
		wp_enqueue_style( 'wpds_meta_box_styles', get_template_directory_uri() . '/stylesheets/admin/meta-box-styles.css' );
	}
}
add_action( 'admin_print_styles', 'wpds_admin_styles' );


/**
 * Loads the image management javascript
 */
function wpds_image_enqueue() {
	global $typenow;
	if( $typenow == 'slide' ) {
		wp_enqueue_media();

		// Registers and enqueues the required javascript.
		wp_register_script( 'meta-box-image', get_template_directory_uri() . '/javascripts/admin/meta-box-image.js', array( 'jquery' ) );
		wp_localize_script( 'meta-box-image', 'meta_image',
			array(
				'title' => __( 'Choose or Upload an Image', 'wpds' ),
				'button' => __( 'Use this image', 'wpds' ),
			)
		);
		wp_enqueue_script( 'meta-box-image' );
	}
}
add_action( 'admin_enqueue_scripts', 'wpds_image_enqueue' );

add_action( 'admin_enqueue_scripts', 'wpds_add_color_picker' );
function wpds_add_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
		
		wp_enqueue_script( 'admin-color-picker', get_template_directory_uri() . '/javascripts/admin/admin-color-picker.js', array( 'jquery' ) );
		
    }
}
