/**
 * Created by hengsoheak on 6/21/2016.
 */
var allData = {};
var types = '';
var till_bal = 0;
var till_account = {};
function withdraw() {

    var withd_opt = '';
    var loan_admin_opt = '';
    Ajaxs('/teller/get_withdraw', 'get', 'json', {}, function (data, status) {

        var loadings = $('body').find('#loading');
        for(var i=0;i<loadings.length; i++){
            loadings.remove();
        }
        $('<div id="loading"></div>').appendTo('body');
        if (status !== 'success') {

            return imgLoading(true,'Please try again.',3,'wraning');
        } else {

            if(data.till_account === false){
                return imgLoading(true,'Please check your till acount balance.',9,'wraning');
            }
            var main_key = 0;
            for (var keys in data.withdraw) {
                var client = data.withdraw[keys].client;

                if (!$.isEmptyObject(client)) {
                    $.each(client.general, function(ins, vals){
                        withd_opt += '<option value="' + data.withdraw[keys].id + '"> ' + vals.family_name+'  '+vals.first_name+ ' - '+ data.currency_list[data.withdraw[keys].currency] + ' - '+ data.withdraw[keys].account_no +'</option>';
                    });

                   // withd_opt += '<option value="' + client.id + '"> ' + client.client_name + '</option>';
                }


            }

            for (var keys in data.loan_admin) {

                var loan_admin = data.loan_admin[keys];
                if (!$.isEmptyObject(loan_admin)) {
                    loan_admin_opt += '<option value="' + loan_admin.id + '"> ' + loan_admin.name + '</option>';
                }
            }
            allData = data.withdraw;
            till_account = data.withdraw.till_account;
            //console.log(till_account);
            //till_bal = 1;
            for (var keys in repay_type) {
                if(keys == 0) continue;
                types += '<option value="' + repay_type[keys] + '"> ' + repay_type[keys] + ' </option>';
            }
            var formElement = {
                date_picker:{
                  dpDate:{
                    type: 'text',
                    name:'till_date',
                    id: 'till_date',
                    value:getNowTime()
                  }
                },
                input: {
                    draw_acc: {
                        type: 'hidden',
                        name: 'client',
                        class: 'client',
                        Id: 'client',
                        placeholder: '',
                        style: 'border:unset !important;',
                        value: ''
                    },
                    selection: {
                        // draw_acc: {opt: withd_opt, class: 'select2', name: 'client', id: 'client'},
                        loan_admin: {opt: loan_admin_opt, class: 'select2', name: 'loan_admin', id: 'loan_admin'},
                        types: {opt: types, class: 'select2', name: 'types', id: 'types'}
                    }, withdraw: {
                        type: 'text',
                        name: 'customer',
                        class: 'form-control',
                        Id: 'customer',
                        placeholder: '',
                        style: '',
                        value: '',
                        disabled: true
                    },drawdown_acc: {
                        type: 'text',
                        name: 'drawdown_acc',
                        class: 'form-control',
                        Id: 'drawdown_acc',
                        placeholder: '',
                        style: '',
                        value: '',
                        disabled: true
                    }, currency: {
                        type: 'text',
                        name: 'currency',
                        class: 'form-control',
                        Id: 'currency',
                        placeholder: '',
                        style: '',
                        value: '',
                        disabled: true
                    }, amount: {
                        type: 'text',
                        name: 'amount',
                        class: 'form-control',
                        Id: 'amount',
                        placeholder: '',
                        style: '',
                        value: data.withdraw[main_key].balance,
                        disabled: true
                    }
                }, textarea: {
                    description: {class: 'form-control', name: 'descr', rows: 10, id: 'descr', text: ''}
                }
            };
            loadModale({
                idSelector: 'withdraw',
                title: 'Withdraw',
                labels: ['Date', 'To', 'Notify to','Withdraw type', 'Withdraw account','Drawdown Account', 'Currency type', 'Amount', 'Description'],
                loadType: 'withdraw',
                keyboard: false,
                backdrop: 'static',
                forms: formElement,
                script: [
                    '/theme/js/jquery.validate.min.js',
                    '/theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',
                    '/js/my_custom_js.js',
                    '/theme/js/select2/select2.js'
                ]
            });
            $('<span>Print</span><input name="if_print" id="printID" type="checkbox" value="0" class="btn btn-info" style="width: 30px;transform: scale(2.3);margin-right: 4px; margin-bottom: 4px;margin-left:11px" >').insertBefore('#tillSubmit');
            $('input[type=submit]').attr('disabled', true);
        }
        return submitD();
    });
}

function submitD() {

    var withd_acc = {};
    $(document).on('change', '#client', function (e) {
        var selc_dd_id = $(this).val() || null;//$('#client option:selected').val() || null;
        $.each(allData, function (inx, vals) {
          if(selc_dd_id == vals.id){
            $.each(till_account, function (i, val) {
              if(val.currency_id == vals.currency){
                till_bal = parseFloat(val.balance);
                return;
              }
            });
            return;
          }
        });
        if (selc_dd_id != null) {

            withd_acc = matchAndGetData(parseInt(selc_dd_id));
            $('#customer').val(withd_acc.account_name + ' ( ' + withd_acc.account_no + ' ) ');
            $('#amount').attr('disabled', false);
            $('#amount').val(withd_acc.balance);
            $('#currency').val(withd_acc.currency_tbl.code);
            $('#drawdown_acc').val(withd_acc.account_no);
            $('#drawdown_acc').attr('disabled', false);
            $('#drawdown_acc').attr('readonly', true);
            $('input[type=submit]').attr('disabled', false);
            $(document).on('keyup','#amount', function() {

                var amount_val = parseFloat($('#amount').val());
                if($(this).is('#amount')) {
                    $('#loading').remove();
                    $('input[type=submit]').attr('disabled',false);
                    $('#descr').attr('disabled', false);
                    if( ($('#types').val() === "Cash on Hand-Teller" && amount_val > till_bal) || parseFloat(withd_acc.balance) < amount_val) {
                        $('#descr').attr('disabled', true);
                        $('input[type=submit]').attr('disabled',true);
                        $('<div id="loading"></div>').appendTo('body');
                        imgLoading(true,'Your amount can\'t be over than your balance',6,'warning');
                    }
                }
            });

            $('#swithdraw').validate({
                rules: {
                    client: {
                        required: true
                    },
                    amount: {
                        required: true
                    }, descr: {
                        required: true
                    }, loan_admin: {
                        required: true
                    },types:{
                        required:true
                    }
                }, submitHandler: function () {

                    if (confirm('Are you sure?')) {

                        $('<div id="loading"></div>').appendTo('body');
//                        imgLoading(true, 'Loading....!!!', 60, 'warning');
                        $('input[type=submit]').attr('disabled', true);
                        var bank_name = $('input[name=bank_name]').val()?$('input[name=bank_name]').val():0;
                        var check_num = $('input[name=check_num]').val()?$('input[name=check_num]').val():0;

                        var remind_bal = (parseFloat(withd_acc.balance) - parseFloat($('#amount').val()));
                        var data = $('#swithdraw').serialize() + '&id=' + withd_acc.id + '&currency_id=' + withd_acc.currency + '&amount=' + parseFloat($('#amount').val());
                        data += '&remind_balance=' + remind_bal + '&by_cash_checque=' + $('.ch').val() + '&client_name=' + withd_acc.account_name +
                            '&user_id=' + $('#loan_admin').val() + '&description=' + $('#descr').val() + '&client_id=' + withd_acc.client_id + '&types=' +
                            $('#types option:selected').val()+'&bank_name='+bank_name+'&check_num='+check_num+'&till_date='+ $('#till_date').val() +'&if_print='+$('#printID').val();

                        Ajaxs('/teller/post_withdraw', 'post', 'json', data, function (data, status) {
                            var loadings = $('body').find('#loading');
                            for(var i=0;i<loadings.length; i++){
                                loadings.remove();
                            }
                            $('<div id="loading"></div>').appendTo('body');

                            if (status !== 'success') {

                                return imgLoading(true, 'We can\' save your data t!!!', 5, 'waring');
                            } else {

                                if(data.till_state === false) {

                                    return imgLoading(true, 'Please check your account balance and status.', 5, 'warning');
                                }
                                if (data.ins_notify === true) {

                                    imgLoading(true, 'successfully!!!', 5, status);
                                    $('#result').remove();
                                    if (!$.isEmptyObject(data.print_url)) {

                                        var redirectWindow = window.open(data.print_url, '') || {};
                                        redirectWindow.location;
                                    }
                                }
                            }
                        });
                    }
                }
            });
        }
    });
}

function matchAndGetData(selc_dd_id) {
    for (var keys in allData) {
        if (parseInt(allData[keys].id) === parseInt(selc_dd_id)) {
            return allData[keys];
        }
    }
}

$(document).on('change', '#types', function () {

    var del = $('#swithdraw').find('.appendData');
    if ($(this).is('#types')) {
        for(var i =0; i<=del.length; i++) {
            del.children().remove();
        }
        $('<div class="form-group">' +
            '<label class="control-label col-sm-4" for="print"> Check number : </label>' +
            '<div class="col-sm-8 form-checkbox"> ' +
            '   <input type="text" value="" name="check_num" class="form-control" />' +
            '</div>' +
            '</div>'+
            '<div class="form-group">' +
            '<label class="control-label col-sm-4" for="print"> Bank name: </label>' +
            '<div class="col-sm-8 form-checkbox"> ' +
            '   <input type="text" value="" name="bank_name" class="form-control" />' +
            '</div>' +
            '</div>').appendTo('.appendData');
    }
});

$(document).on('click', '#printID', function () {

    var check = $('#printID'), vals = 0;
    if ($(this).is(':checked')) {

        $('<div id="loading"></div>').appendTo('body');
        imgLoading(true, 'You will print data after submit', 5, 'warning');
        $('#printID').val(1);
    } else if ($(this).is(':not(:checked)')) {

        $('#loading').remove();
        $('#printID').val(0);
    }
});

function checkit(obj) {
    var cbs = document.getElementsByClassName("ch");
    for (var i = 0; i < cbs.length; i++) {
        cbs[i].checked = false;
    }
    obj.checked = true;
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
