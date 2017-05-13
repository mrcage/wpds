jQuery(document).ready(function ($) {
	Reveal.initialize({
		controls: false,
		progress: false,
		slideNumber: showSlideNumber,
		center: false,
		loop: true,
		autoSlide: autoPlaySpeed,
		autoSlideStoppable: true,
		transition: transitionStyle,
		transitionSpeed: transitionSpeed
	});
});

// Periodic page reload (disabled by default)
jQuery(document).ready(function ($) {
	var reloadTimeout = typeof defaultReloadTimeout !== 'undefined' ? defaultReloadTimeout : 0 * 60 * 1000;
	doReloadPage(reloadTimeout);
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

// Page reload on content change
jQuery(document).ready(function ($) {
	var contentChangeCheckInterval = typeof defaultContentChangeCheckInterval !== 'undefined' ? defaultContentChangeCheckInterval : 10 * 1000;
	wpdsCheckModifiedContent(contentChangeCheckInterval);
	setStatusText('OK');
});

function wpdsCheckModifiedContent(modifiedContentCheckInterval) {
	window.setTimeout(function(){
		//console.log('Checking for content change...');
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
					setStatusText('Reloading');
					location.reload();
				})
				.fail(function( jqXHR, textStatus, errorThrown ) {
					setStatusText('HEAD check failed: ' + textStatus);
					console.log('Unable to reload: ' + textStatus + ', trying again in ' + modifiedContentCheckInterval + " ms");
					wpdsCheckModifiedContent(modifiedContentCheckInterval);
				});
			} else {
				setStatusText('OK');
				wpdsCheckModifiedContent(modifiedContentCheckInterval)
			}
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {
			setStatusText('Status check failed: ' + textStatus);
			wpdsCheckModifiedContent(modifiedContentCheckInterval)
		});
	}, modifiedContentCheckInterval);
}

function setStatusText(messageText, type) {
    var d = new Date();
    var datestring = d.getFullYear() + "-" + d.getDate() + "-" + (d.getMonth()+1) + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
    jQuery('.net-status-infobox').html(messageText + ' [' + datestring + ']');
}

// Message for watchdog page (optional)
try {
    window.top.postMessage('tyrp', '*');
} catch(e){}
