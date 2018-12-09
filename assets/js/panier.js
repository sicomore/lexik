$(function () {
  $('.quantite-panier').change( function() {
    var quantite = $(this).val();
    var id = $(this).attr('id');
    if (quantite < 1) {
      quantite = 1;
      $(this).val(1);
    }

    $.ajax({
      url: 'ajax',
      method: 'post',
      data: {
        id: id,
        quantite: quantite
      },
      dataType: 'text',
      success: function (e, status) {
        $('#total').html(e + " €");
      },
    });
  });
});
