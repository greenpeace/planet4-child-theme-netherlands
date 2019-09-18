
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
          },
          error: function() {
            console.log('Nooo....');
          }
        });
      }
    });
  }
});
