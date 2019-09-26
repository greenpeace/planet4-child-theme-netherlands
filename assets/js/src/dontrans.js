
$(document).ready(function() {
  let params = new URLSearchParams(document.location.search.substring(1));
  let donation_transaction = params.has('don_trans');

  if(donation_transaction) {
    let donation_transaction = params.get("don_trans");

    $.ajax({
      type:    'POST',
      url:     window['p4_vars'].ajaxurl,
      data:    {'action' : 'request_id'},
      success: function(response) {

        let request = {
          'action' : 'get_donation',
          'nonce'  : response.data.nonce,
          'transaction' : donation_transaction
        };

        $.ajax({
          method: 'POST',
          url: window['p4_vars'].ajaxurl,
          data: request,
          success: function(response) {
            let data = JSON.parse(response.data.data);
            if (typeof dataLayer !== 'undefined') {
              // Google Tag Manager E-commerce */
              // Build product array
              let gtm_products = [];

              gtm_products.push({
                'name': 'machtiging',
                'sku': data.frequency,
                'category': 'donatie',
                'price': data.amount,
                'quantity': 1
              });
              // Optional repeat for each additional product to fill gtm_products array


              /** Build an event send to the Datalayer, which needs to trigger the E-commerce transaction in the GTM backend
               *  Additional datalayer items are send to the datalayer and processed by the GTM as an transaction
               */
              dataLayer.push({
                'event': 'trackTrans',
                'transactionId': donation_transaction,
                'transactionAffiliation': data.frequency,
                'transactionTotal': data.amount,
                'transactionTax': '',
                'transactionShipping': '',
                'transactionPaymentType': 'ideal',
                'transactionCurrency': 'EUR',
                'transactionPromoCode': '',
                'transactionProducts': gtm_products
              });
            }
          },
          error: function() {
            console.log('Nooo....');
          }
        });
      }
    });
  }
});
