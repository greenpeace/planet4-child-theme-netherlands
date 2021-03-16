const donateButton = document.querySelector('#donate-button-wrapper .dropdown .btn-donate');
const href = donateButton.getAttribute('href');

// If the browser is IE 10 or below show a notification
// This will remove the direct link on the donate button on devices with touchscreens.
if (navigator.userAgent.indexOf('MSIE') >= 0) {
  document.querySelector('.old-ie-browser-notification').style.display = 'block';

}
// On touch or click the dropdown menu will show instead of following the link.
if ( document.readyState === 'complete' ||  (document.readyState !== 'loading' && !document.documentElement.doScroll) ) {
  donateMenu();
} else {
  document.addEventListener('DOMContentLoaded', donateMenu, {passive: true});
}
function donateMenu() {
  if ('ontouchstart' in window) {
    donateButton.addEventListener('touchstart', removeHref, {passive: true});
  }
}

function removeHref () {
  donateButton.removeAttribute('href');
  donateButton.setAttribute('data-bs-toggle', 'dropdown');
  donateButton.addEventListener('touchend', addHref, {passive: true});
}

function addHref () {
  donateButton.setAttribute('href', href);
}

