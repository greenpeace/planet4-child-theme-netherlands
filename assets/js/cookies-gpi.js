"use strict";window.$=$||jQuery,window.createCookie=function(e,o,n){var i=new Date;i.setTime(i.getTime()+24*n*60*60*1e3),document.cookie=encodeURI(e)+"="+encodeURI(o)+";domain=."+document.domain+";path=/;; expires="+i.toGMTString()+";SameSite=None; Secure"},window.readCookie=function(e){for(var o,n=e+"=",i=document.cookie.split(";"),t=0;t<i.length;t++){for(o=i[t];" "===o.charAt(0);)o=o.substring(1,o.length);if(0===o.indexOf(n))return o.substring(n.length,o.length)}return null};var cookie=window.readCookie("greenpeace"),nro=$("body").data("nro");null==cookie?$(".cookie-notice").css("display","flex"):window.createCookie("gp_nro",nro,365),$("#hidecookie").click(function(){window.createCookie("greenpeace","2",365),window.createCookie("no_track","0",-1),window.createCookie("gp_nro",nro,365),window.dataLayer=window.dataLayer||[],dataLayer.push({event:"cookiesConsent"}),$(".cookie-notice").fadeOut("slow")});
//# sourceMappingURL=maps/cookies-gpi.js.map
