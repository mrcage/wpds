jQuery(document).ready(function ($) {
	$('#slider').slick({
		autoplay: true,
		arrows: false,
		fade: true,
		pauseOnFocus: false,
		pauseOnHover: false
	});
	$('#slider article').height($(window).height());
	$(window).resize(function(){
		$('#slider article').height($(window).height());
	});
});

// Page reload (disabled by default)
jQuery(document).ready(function ($) {
	var reloadTimeout = typeof defaultReloadTimeout !== 'undefined' ? defaultReloadTimeout : 0 * 60 * 1000;
	doReloadPage(reloadTimeout);
	wpdsCheckModifiedContent();
});

function doReloadPage(reloadTimeout) {
	if (reloadTimeout > 0) {
		window.setTimeout(function() {
			console.log('Trying to reload...');
			jQuery.ajax({
				url  : "/",
				type : "HEAD"
			})
			.done(function() {
				location.reload();
			})
			.fail(function( jqXHR, textStatus, errorThrown ) {
				console.log('Unable to reload: ' + textStatus + ', trying again in ' + reloadTimeout + " ms");
				doReloadPage(reloadTimeout);
			});
		}, reloadTimeout);
	}
}

function wpdsCheckModifiedContent() {
	var modifiedContentCheckInterval = 10 * 1000;
	window.setTimeout(function(){
		console.log('Checking for content change...');
		jQuery.get({
			url  : "/wpds-status"
		})
		.done(function(data) {
			if (data != postModified) {
				console.log('Content has changed, reloading page...');
				jQuery.ajax({
					url  : "/",
					type : "HEAD"
				})
				.done(function() {
					location.reload();
				})
				.fail(function( jqXHR, textStatus, errorThrown ) {
					console.log('Unable to reload: ' + textStatus + ', trying again in ' + modifiedContentCheckInterval + " ms");
					wpdsCheckModifiedContent();
				});
			} else {
				wpdsCheckModifiedContent()
			}
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {
			wpdsCheckModifiedContent()
		});
	}, modifiedContentCheckInterval);
}