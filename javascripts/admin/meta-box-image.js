/*
 * Attaches the image uploader to the input field
 */
jQuery(document).ready(function($){

	// Instantiates the variable that holds the media library frame.
	var meta_image_frame;

	// Runs when the image button is clicked.
	$('#background-image-button').click(function(e){

		// Prevents the default action from occuring.
		e.preventDefault();

		// If the frame already exists, re-open it.
		if ( meta_image_frame ) {
			meta_image_frame.open();
			return;
		
		}
		// Sets up the media library frame
		meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
			title: meta_image.title,
			button: { text:  meta_image.button },
			library: { type: 'image' }
		});

		// Runs when an image is selected.
		meta_image_frame.on('select', function(){

			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

			// Sends the attachment URL to our custom image input field.
			$('#background-image').val(media_attachment.url).trigger("change");
		});

		// Opens the media library frame.
		meta_image_frame.open();
	});
	
	$( '#background-image' ).on('propertychange change keyup paste input', function() {
		showImagePreview(  $(this).val() );
	});
	$( '#background-image-remove a' ).click( function(){
		$( '#background-image' ).val('').trigger( "change" );
	});
	showImagePreview( $( '#background-image' ).val() );
});

function showImagePreview(url) {
	var container = jQuery( '#background-image-preview' );
	container.empty();
	if (url) {
		var img = jQuery( '<img>' )
			.attr('src', url)
			.attr('alt', url)
			.click(function(){
				jQuery('#background-image-button').click();
			});
		container.append(img);
		jQuery('#background-image-remove').show();
		jQuery('#background-image-button').hide();
	} else {
		jQuery('#background-image-remove').hide();
		jQuery('#background-image-button').show();
	}
}
