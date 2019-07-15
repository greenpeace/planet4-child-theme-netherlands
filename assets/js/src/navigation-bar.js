// Display an error message when user uses IE10 or below.
function isIE() {
  var ua = window.navigator.userAgent; //Check the userAgent property of the window.navigator object
  var msie = ua.indexOf('MSIE '); // IE 10 or older
  var trident = ua.indexOf('Trident/'); //IE 11

  return (msie > 0 || trident > 0);
}


//function to show alert if it's IE
function ShowIEAlert(){
  if(isIE()){
    $(".old-ie-browser-notification").show();
  }
}
