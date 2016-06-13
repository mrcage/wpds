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

// Page reload
jQuery(document).ready(function ($) {
	var reloadTimeout = typeof defaultReloadTimeout !== 'undefined' ? defaultReloadTimeout : 5 * 60 * 1000;
	doReloadPage(reloadTimeout);
});

function doReloadPage(reloadTimeout) {
	if (reloadTimeout > 0) {
		window.setTimeout(function() {
			console.log('Trying to reload...');
			$.ajax({
				url  : "/",
				type : "HEAD"
			})
			.done(function() {
				location.reload();
			})
			.fail(function( jqXHR, textStatus, errorThrown ) {
				console.log('Unable to reload: ' + textStatus);
				window.setTimeout(function(){ doReloadPage(reloadTimeout) }, 5000);
			});
		}, reloadTimeout);
	}
}
