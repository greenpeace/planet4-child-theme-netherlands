// Display an error message when user uses IE10 or below.
// IF THE BROWSER IS INTERNET EXPLORER 10
if (navigator.appVersion.indexOf("MSIE 10") !== -1)
{
  $(".old-ie-browser-notification").show();
}
