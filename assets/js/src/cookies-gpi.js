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
let nro = 'https://www.greenpeace.org/nl';

if (cookie === null) {
  console.log("CDS: Domain: " + document.domain);
  console.log("CDS: cookie: " + cookie);
  $('.cookie-notice').css('display', 'flex');
} else {
  console.log("CDS: Found cookie; hiding banner")
  $('#hidecookie').click();
  window.createCookie('gp_nro', nro, 365);
}
