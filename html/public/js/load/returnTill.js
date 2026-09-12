function returnTill(returnTills) {

    $.ajax({
        url: '/teller/return_till_data',
        method: 'get',
        dataType: 'json',
        timeout: 4000,
        async: false,
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        },
        success: function (data, status) {

            if (!$.isEmptyObject(data.chief) && !$.isEmptyObject(data.coa)) {

                chief_return_to_coa(data);
            }

            if (!$.isEmptyObject(data.chief) && !$.isEmptyObject(data.teller)) {

                teller_return_to_chief(data);
            } else {
                //imgLoading(true, 'No account', 5, status);
            }
        }
    });
}

function chief_return_to_coa(data) {

    var chief_opt = '';
    var chief_data = null;
    var coa_data = null;

    for (var key in data.chief) {

        var vals = data.chief[key];
        chief_opt += '<option value="' + vals.currency_id + '" data-assign_user_id="' + vals.assign_user_id + '" > ' + vals.username + ' ( ' + vals.account_no + ' / ' + vals.account_name + ') </option>';
    }
    var form_input = {
      date_picker:{
        dpDate:{
          type: 'text',
          name:'till_date',
          id: 'till_date',
          value:getNowTime()
        }
      },
      input: {
              selection: {
                  From: {option: chief_opt, class: 'select2', name: 'from', id: 'from'},
              },
              To: {
                  type: 'text',
                  name: 'to',
                  class: 'form-control',
                  Id: 'to',
                  placeholder: 'No account',
                  style: '',
                  value: '',
                  disabled: true
              },
              Amount: {
                  type: 'text',
                  name: 'amount',
                  class: 'form-control',
                  Id: 'amount',
                  placeholder: '',
                  style: '',
                  value: '',
                  disabled: 'disabled'
              }
      },
      textarea: {
          description: {class: 'form-control', name: 'retn_descr', rows: 10, id: 'retn_descr'}
      }
    };
    loadModale({
        idSelector: 'returnTill',
        title: 'Return Till',
        labels: ['Date', 'From', 'To', 'Amount', 'Description'],
        loadType: 'returnTill',
        keyboard: false,
        backdrop: 'static',
        forms: form_input,
        script: [
            '/theme/js/jquery.validate.min.js',
            '/theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',
            '/js/my_custom_js.js',
            '/theme/js/select2/select2.js'
        ]
    });
    //alert("ok");
    $('#loading').remove();
    var Sel_assign_user_id = parseInt($('#from option:selected').attr('data-assign_user_id'));

    $('#sreturnTill #from').on('change', function () {

        $('#to').val('');
        $('<div id="loading"></div>').appendTo('body');
        var Sel_currency_id = parseInt($(this).val());

        for (var key in data.coa) {

            var vals = data.coa[key];
            if (parseInt(vals.currency) === parseInt(Sel_currency_id)) {
                for (var keys in data.chief) {
                    if (parseInt(data.chief[keys].currency_id) === parseInt(Sel_currency_id)) {
                        if (parseFloat(data.chief[keys].balance) === 0) {
                            $('#amount').val(data.chief[keys].symbol + 0);
                            imgLoading(true, 'Balance is empty', 5, 'warning');
                        } else {
                            chief_data = data.chief[keys];
                            coa_data = vals;
                            $('#loading').remove()
                            $('#to').val(vals.name + ' / ' + vals.account_code);
                            $('#amount').val(data.chief[keys].symbol + ' ' + data.chief[keys].balance);
                        }
                    }
                }
            }
        }
    });
    $("#sreturnTill").validate({
        rules: {
            from: {
                required: true
            },
            to: {
                required: true
            }, retn_descr: {
                required: true
            }, amount: {
                required: true
            }
        }, submitHandler: function () {
            var data = {};
            data.till_account_id = parseInt(chief_data.chief_till_id);
            data.from_account = $('#from option:selected').attr('selected', true).text().trim();
            data.to_account = $("#to").val().trim(); // chief
            data.branch_id = parseInt(vals.branch_id);
            data.operate_by = parseInt(chief_data.assign_user_id);// this is used for transaction and search
            data.type = 'Return Till';
            data.cash_out = chief_data.balance;
            data.description = $('#retn_descr').val();
            data.tran_currency_id = chief_data.currency_id;
            data._token = $('meta[name="_token"]').attr('content');
            data.till_date = $('#till_date').val();

            if (confirm('Are you sure?')) {
                $('input[type=submit]').attr('disabled', true);
                Ajaxs('/teller/post_return_till', 'post', 'Json', data, function (data, status) {
                    if (status == 'success') {
                        if (data.res == false) {
                            imgLoading(true, "We can't save your info", 8, 'no');
                        } else {

                            imgLoading(true, "Successfully !!!", 2, status);
                            $("#result").empty();
                            location.reload();
                        }
                    }
                });
            }
        }
    });

}

function teller_return_to_chief(data) {

    var teller_opt = '';
    var chief_data = null;
    var teller_data = null;

    for (var key in data.teller) {

        var vals = data.teller[key];
        teller_opt += '<option value="' + vals.currency_id + '" data-assign_user_id="' + vals.assign_user_id + '" > ' + vals.username + ' ( ' + vals.account_no + ' / ' + vals.account_name + ') </option>';
    }
    var form_input = {
      date_picker:{
        dpDate:{
          type: 'text',
          name:'till_date',
          id: 'till_date',
          value:getNowTime()
        }
      },
      input: {
            selection: {
                From: {1: teller_opt, class: 'form-control', name: 'from', id: 'from'},
            },
            To: {
                type: 'text',
                name: 'to',
                class: 'form-control',
                Id: 'to',
                placeholder: 'No account',
                style: '',
                value: '',
                disabled: true
            },
            Amount: {
                type: 'text',
                name: 'amount',
                class: 'form-control',
                Id: 'amount',
                placeholder: '',
                style: '',
                value: '',
                disabled: 'disabled'
            }
        },
         textarea: {
            description: {class: 'form-control', name: 'retn_descr', rows: 10, id: 'retn_descr'}
        }
    };
    loadModale({
        idSelector: 'returnTill',
        title: 'Return Till',
        labels: ['Date', 'From', 'To', 'Amount', 'Description'],
        loadType: 'returnTill',
        keyboard: false,
        backdrop: 'static',
        forms:form_input,
        script: [
            '/theme/js/jquery.validate.min.js',
            '/theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',
            '/js/my_custom_js.js',
            '/theme/js/select2/select2.js'
        ]
    });
    $('#loading').remove();
    var Sel_assign_user_id = parseInt($('#from option:selected').attr('data-assign_user_id'));

    $('#sreturnTill #from').on('change', function () {

        $('#to').val('');
        $('<div id="loading"></div>').appendTo('body');
        var Sel_currency_id = parseInt($(this).val());
        if(data.notification > 0){
            $('<div id="loading"></div>').appendTo('body');
            imgLoading(true, 'Please Check Your Notification First !', 5, 'warning');
        }else{
            for (var key in data.chief) {

                var chief_vals = data.chief[key];

                if (parseInt(chief_vals.currency_id) === parseInt(Sel_currency_id)) {

                    //console.log(Sel_currency_id + '=' + chief_vals.currency_id);
                    for (var keys in data.teller) {
                        var teller_vals = data.teller[keys];

                        if (parseInt(teller_vals.currency_id) === parseInt(Sel_currency_id)) {

                            if (parseFloat(teller_vals.balance) === 0) {
                                imgLoading(true, 'Balance is empty', 5, 'warning');
                            } else {

                                chief_data = chief_vals;
                                teller_data = teller_vals;
                                $('#loading').remove();
                                $('#to').val(chief_data.name + ' ( ' + chief_data.account_no + '/' + chief_data.account_name + ' ) ');
                                $('#amount').val(teller_vals.symbol + ' ' + teller_vals.balance);
                            }
                        }
                    }
                }
            }
        }
    });

    $('#sreturnTill').validate({
        rules: {
            from: {
                required: true
            },
            to: {
                required: true,
                number: true
            },
            amount: {
                required: true
            }, retn_descr: {
                required: true
            }
        },
        submitHandler: function () {
            var data = {};
            data.till_account_id = parseInt(teller_data.till_account_id);
            data.from_account = $('#from option:selected').attr('selected', true).text().trim();
            data.to_account = $("#to").val().trim(); // chief
            data.branch_id = parseInt(teller_data.branch_id);
            data.operate_by = parseInt(chief_data.assign_user_id);// this is used for transaction and search
            data.type = 'Return Till';
            data.cash_out = teller_data.balance;
            data.description = $('#retn_descr').val();
            data.currency_id = teller_data.currency_id;
            data.chief_till_id = chief_data.chief_till_id;
            data._token = $('meta[name="_token"]').attr('content');
            data.till_date = $('#till_date').val();
            if (confirm('Are you sure!!!')) {
                Ajaxs('/teller/post_return_till', 'post', 'Json', data, function (data, status) {
                    if (status == 'success') {
                        if (data.res == false) {
                            imgLoading(true, "We can't save your info", 8, 'no');
                        } else {
                            imgLoading(true, "Successfully !!!", 2, status);
                            $("#result").empty();
                            location.reload();
                        }
                    }
                });
            }
        }
    });
}

$(document).on("click", ".mclose", function () {
    $("#result").remove();
    $("#loading").remove();
});

function leftPad(number, targetLength) {
    var output = number + '';
    while (output.length < targetLength) {
        output = '0' + output;
    }
    return output;
}
function getNowTime(){
    var now = new Date();
    return now.getFullYear() + "/" + leftPad(now.getMonth()+1,2) + "/" + leftPad(now.getDate(),2) + " " + leftPad(now.getHours(),2) + ":" + leftPad(now.getMinutes(),2) + ":" 
        + leftPad(now.getSeconds(), 2);
}
