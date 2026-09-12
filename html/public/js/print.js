$(document).on('click', '#printer, #printdOut, #myprint', function (event) {

    if($(this).is('#printdOut')){

        var data = {
            descr : document.getElementById("descr").value.toUpperCase(),
            amount_char : document.getElementById('amount_char').value.toUpperCase(),
            bank : document.getElementById('bank').value.toUpperCase()
        };
        $.each(data, function(ins, vals){

            if(!$.isEmptyObject()){
                document.getElementById(ins).innerHTML = vals;
            }
        });
        $('.tbrepayment tbody tr .remove_class').removeClass('custom_display');
        $('table.repayment-plan').removeAttr('style');
    }

    event.preventDefault();

    if ($(this).is('#myprint')){
      var $print = $('#myprint_area');
    }else{
      var $print = $("#printArea");
    }

    $(document.body).wrapInner('<div style="display: none"></div>');
    var $div = $('<div/>').append($print.clone().contents()).appendTo(document.body);
    //console.log($print.clone().contents());
    window.print();
    $div.remove();
    $(document.body).children().contents().unwrap();

});

$(document).on('click', '#printDebit', function (event) {

    if($(this).is('#printer')){

        var data = {
            descr : document.getElementById("descr").value.toUpperCase(),
            amount_char : document.getElementById('amount_char').value.toUpperCase(),
            bank : document.getElementById('bank').value.toUpperCase()
        };
        $.each(data, function(ins, vals){

            if(!$.isEmptyObject()){
                document.getElementById(ins).innerHTML = vals;
            }
        });
        var $print = $("#printArea");
    }   if($(this).is('#printDebit')) {

        var data = {
            descr : document.getElementById("descr").value.toUpperCase(),
            amount_char : document.getElementById('amount_char').value.toUpperCase(),
            bank : document.getElementById('bank').value.toUpperCase()
        };
        $.each(data, function(ins, vals){

            if(!$.isEmptyObject()){
                document.getElementById(ins).innerHTML = vals;
            }
        });
        var $print = $("#printDebitData");
    }

    event.preventDefault();
    $('.tbrepayment tbody tr .remove_class').removeClass('custom_display');
    $('table.repayment-plan').removeAttr('style');

    $(document.body).wrapInner('<div style="display: none"></div>');
    var $div = $('<div/>').append($print.clone().contents()).appendTo(document.body);
    console.log($div);
    window.print();
    $div.remove();
    $(document.body).children().contents().unwrap();

});

$(document).on('click', '#customer_printer', function (event) {
    event.preventDefault();
    $('.tbrepayment tbody tr .remove_class').addClass('custom_display');
    var $print = $(".printArea");
    $(document.body).wrapInner('<div style="display: none"></div>');
    var $div = $('<div />').append($print.clone().contents()).appendTo(document.body);
    console.log($div);
    window.print();
    $div.remove();
    $(document.body).children().contents().unwrap();
});


$(document).on('click', '#transaction_printer', function (event) {
    event.preventDefault();
    $('.transaction tr .remove_class').addClass('remove-column');
    var $print = $("#printArea");
    $(document.body).wrapInner('<div style="display: none"></div>');
    var $div = $('<div />').append($print.clone().contents()).appendTo(document.body);
    console.log($div);
    window.print();
    $div.remove();
    $(document.body).children().contents().unwrap();
});
