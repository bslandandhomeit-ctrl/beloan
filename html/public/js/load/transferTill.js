/**
 * Created by hengsoheak on 7/4/2016.
 */

function transferTill(Selector) {

    var chief_opt, teller_opt;
    var chief_data, teller_data;
    Ajaxs('/teller/transfer_till', 'get', 'json', {}, function (data, status) {

        if (status === 'success') {

            if (!$.isEmptyObject(data.chief)) {
                $.each(data.chief, function (inx, chief_vals) {
                    chief_opt += '<option value="' + chief_vals.currency_id + '"> ' + chief_vals.username + ' ( ' + chief_vals.account_name + ' / ' + chief_vals.account_no + ' ) </option>';
                });
                $.each(data.teller, function(idx, teller_vals){
                  teller_opt += '<option value="' + teller_vals.id + '" data-assign_user_id="' + teller_vals.assign_user_id + '" data-currency = "'+ teller_vals.currency_id +'"> ' + teller_vals.name + ' ( ' + teller_vals.account_no + ' / ' + teller_vals.account_name + ') </option>';
                });
            }

        }

        loadModale({
            idSelector: Selector,
            title: 'Transfer Till',
            labels: ['Date', 'From', 'To', 'Amount', 'Description'],
            loadType: 'transferTill',
            forms: {
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
                        from: {option: chief_opt, class: 'select2', name: 'from', id: 'from'},
                        to: {option: teller_opt , class: 'select2', name: 'to', id: 'to'},
                    },

                    /*To: {
                        type: 'text',
                        name: 'to',
                        class: 'form-control',
                        Id: 'to',
                        placeholder: '',
                        style: '',
                        value: '',
                        disabled: true
                    },*/
                    Amount: {
                        type: 'text',
                        name: 'amount',
                        class: 'form-control',
                        Id: 'amount',
                        placeholder: '',
                        style: '',
                        value: ''
                    }
                },
                textarea: {
                    description: {class: 'form-control', name: 'tran_descr', rows: 10, id: 'tran_descr'}
                }
            }, script: [
                '/theme/js/jquery.validate.min.js',
                '/theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',
                '/js/my_custom_js.js',
                '/theme/js/select2/select2.js'
            ]
        });
        $('#loading').remove();
        $('#from').on('change', function () {
            var Sels_currency_id = $(this).attr('selected', true).val();

            var opt = '';
            $.each(data.teller, function (ins, teller_vals) {
                $.each(data.chief, function (inx, chief_val) {

                    if (parseInt(teller_vals.currency_id) === parseInt(Sels_currency_id) && parseInt(chief_val.currency_id) === parseInt(Sels_currency_id)) {
                        //$('#to').val(teller_vals.name + ' ( ' + teller_vals.account_name + ' / ' + teller_vals.account_no + ' )');
                        chief_data = chief_val;
                        teller_data = teller_vals;
                    }
                });
            });
        });
        // $('#to').on('change', function () {
        //     alert($('#to option:selected').attr('data-assign_user_id'));
        // });
        $('#amount').on('keyup', function (e) {

            $('#loading').remove()
            $('input[type=submit]').attr('disabled', true)
            if (parseFloat(chief_data.balance) < parseFloat($('#amount').val())) {
                $('<div id="loading"></div>').appendTo('body');
                imgLoading(true, 'You can\'t transfer amount over than your balance', 10, 'warning');
                $('input[type=submit]').attr('disabled', true)
                return;
            }
            $('input[type=submit]').attr('disabled', false);
        });

        $("#stransferTill").validate({
            rules: {
                from: {
                    required: true
                },
                to: {
                    required: true
                }, tran_descr: {
                    required: true
                }, amount: {
                    required: true
                }
            }, submitHandler: function () {

                if(confirm('Are you sure to submit with this amount?')) {
                    $('input[type=submit]').attr('disabled', true);
                    var obj = {};
                    obj.till_account_id     = parseInt(chief_data.id);
                    obj.from_account        = $('#from option:selected').text();
                    // obj.to_account          = $('#to').val();
                    obj.to_account          = $('#to option:selected').text();
                    obj.assign_user_id      = $('#to option:selected').attr('data-assign_user_id');
                    obj.branch_id           = parseInt(teller_data.branch_id);
                    obj.operate_by          = chief_data.created_by;
                    obj.type                = 'Transfer till';
                    obj.cash_out            = Number($('#amount').val());
                    obj.last_chief_balance  = parseFloat(chief_data.balance) - parseFloat($('#amount').val());
                    obj.description         = $("#tran_descr").val();
                    obj.tran_currency_id    = parseInt(chief_data.currency_id);
                    obj._token              = $('meta[name="_token"]').attr('content');

                    obj.chief_till_id = parseInt(chief_data.id);
                    // obj.teller_till_id = parseInt(teller_data.id);
                    obj.teller_till_id = $('#to').val();
                    obj.till_date = $("#till_date").val();
                    
                    $('<div id="result"></div>').appendTo('body');
                    Ajaxs('/teller/post_trans_till', 'post', 'json', obj, function (data, status) {
                        if (data.ins_notify === true) {
                            imgLoading(true, 'successfully', 6, status);
                            $('#result').remove();
                        }
                    });
                }
            }
        });
    });
}
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
