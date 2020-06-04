// If the browser is IE 10 or below show a notification
if (navigator.userAgent.indexOf('MSIE') >= 0) {
  jQuery('.old-ie-browser-notification').show();
}

// This will remove the direct link on the donate button on devices with touchscreens.
// On touch or click the dropdown menu will show instead of following the link.
jQuery( document ).ready(function() {
  let donateButton = jQuery('#donate-button-wrapper .dropdown .btn-donate');
  if ('ontouchstart' in window) {
    donateButton[0].addEventListener("touchstart", removeHref);
  }
});

function removeHref (event) {
  let donateButton = $(event.target);
  let href = donateButton.attr("href");
  donateButton.removeAttr("href");
  donateButton.attr('data-toggle', 'dropdown');
  donateButton[0].addEventListener("touchend", addHref, href);
}

function addHref (event, href) {
  let button = $(event.target);
  button.attr("href", href);
  donateButton.attr('data-toggle', '');
}

