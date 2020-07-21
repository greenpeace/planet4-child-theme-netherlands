window.$ = $ || jQuery;
window.createCookie = function(name, value, days) {
  let date = new Date();
  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
  document.cookie = encodeURI(name) + '=' + encodeURI(value) + ';domain=.' + document.domain + ';path=/;' + '; expires=' + date.toGMTString()+';SameSite=None; Secure';
};

window.readCookie = function(name) {
  const nameEQ = name + '=';
  const ca = document.cookie.split(';');
  let c;
  for (let i = 0; i < ca.length; i++) {
    c = ca[i];
    while (c.charAt(0) === ' ') {
      c = c.substring(1, c.length);
    }
    if (c.indexOf(nameEQ) === 0) {
      return c.substring(nameEQ.length, c.length);
    }
  }
  return null;
};

const cookie = window.readCookie('greenpeace');
const nro = $('body').data('nro');

if (cookie == null) {
  $('.cookie-notice').css('display', 'flex');
} else {
  window.createCookie('gp_nro', nro, 365);
}

$('#hidecookie').click(function () {
  window.createCookie('greenpeace', '2', 365);

  // Remove the 'no_track' cookie, if user accept the cookies consent.
  window.createCookie('no_track', '0', -1);

  // Create cookie to store last visited nro.
  window.createCookie('gp_nro', nro, 365);

  // DataLayer push event on cookies consent.
  window.dataLayer = window.dataLayer || [];
  dataLayer.push({
    'event' : 'cookiesConsent'
  });

  $('.cookie-notice').fadeOut('slow');
});
