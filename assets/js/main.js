"use strict";$(document).ready(function(){var a=new URLSearchParams(document.location.search.substring(1));if(a.has("don_trans")){var o=a.get("don_trans");$.ajax({type:"POST",url:window.p4_vars.ajaxurl,data:{action:"request_id"},success:function(a){var n={action:"get_donation",nonce:a.data.nonce,transaction:o};$.ajax({method:"POST",url:window.p4_vars.ajaxurl,data:n,success:function(a){var n=JSON.parse(a.data.data);if("undefined"!=typeof dataLayer){var t=[];t.push({name:"machtiging",sku:n.frequency,category:"donatie",price:n.amount,quantity:1}),dataLayer.push({event:"trackTrans",transactionId:o,transactionAffiliation:"",transactionTotal:n.amount,transactionTax:"",transactionShipping:"",transactionPaymentType:"ideal",transactionCurrency:"EUR",transactionPromoCode:"",transactionProducts:t})}},error:function(){console.log("Nooo....")}})}})}});
//# sourceMappingURL=maps/main.js.map
