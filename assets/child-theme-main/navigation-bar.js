const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
dropdownToggles.forEach(toggle =>(
  toggle.setAttribute('data-href', toggle.getAttribute('href'))
));

// If the browser is IE 10 or below show a notification
// This will remove the direct link on the donate button on devices with touchscreens.
if (navigator.userAgent.indexOf('MSIE') >= 0) {
  document.querySelector('.old-ie-browser-notification').style.display = 'block';

}
// On touch or click the dropdown menu will show instead of following the link.
if ( document.readyState === 'complete' ||  (document.readyState !== 'loading' && !document.documentElement.doScroll) ) {
  toggleDropdowns();
} else {
  document.addEventListener('DOMContentLoaded', toggleDropdowns, {passive: true});
}
function toggleDropdowns() {
  if ('ontouchstart' in window) {
    dropdownToggles.forEach(
      toggle => (
        toggle.addEventListener('touchstart', removeHref, {passive: true})
      ));
  }
}

function removeHref () {
  this.removeAttribute('href');
  this.setAttribute('data-bs-toggle', 'dropdown');
  this.addEventListener('touchend', addHref, {passive: true});
}

function addHref () {
  this.setAttribute('href', this.getAttribute('data-href'));
}

