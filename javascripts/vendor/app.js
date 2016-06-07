jQuery(document).ready(function ($) {
	var reloadTimeout = typeof defaultReloadTimeout !== 'undefined' ? defaultReloadTimeout : 5 * 60 * 1000;
/* REFRESH PAGE EVERY FIVE MINUTES ----*/
window.setTimeout('location.reload(true)', reloadTimeout);
/* /REFRESH PAGE EVERY FIVE MINUTES ---- */

});
