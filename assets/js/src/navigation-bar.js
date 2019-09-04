// If the browser is IE 10 or below show a notification
if (navigator.userAgent.indexOf('MSIE') >= 0) {
  jQuery('.old-ie-browser-notification').show();
}

// This will remove the direct link on the donate button on devices with touchscreens.
// On touch or click the dropdown menu will show instead of following the link.
jQuery( document ).ready(function() {
  var donateButton = jQuery('.btn-donate');
  if ('ontouchstart' in window === true) {
    donateButton.removeAttr("href");
    donateButton.attr('data-toggle', 'dropdown');
  }
});
