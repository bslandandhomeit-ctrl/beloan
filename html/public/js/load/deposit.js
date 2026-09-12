/**
 * Created by hengsoheak on 6/21/2016.
 */
var alldata = {};
function deposit(not_id = 0) {

    var client_opt = '';
    var loan_admins_opt = '';
    Ajaxs('/teller/deposit', 'get', 'json', {}, function (data, status) {
        var loadings = $('body').find('#loading');
        for(var i=0;i<loadings.length; i++){
            loadings.remove();
        }
        $('<div id="loading"></div>').appendTo('body');
        if (status !== 'success') {
            return imgLoading(true,'Please try again.',3,'warning');
        } else {

            if(data.till_account === false){
                return imgLoading(true,'Please check your till account balance.',9,'warning');
            }
            for (var keys in data.withdraw) {
                var client_data = data.withdraw[keys].client;
                $.each(client_data.general, function(ins, vals){
                    if (!$.isEmptyObject(client_data) && client_data != null && data.withdraw[keys].unit_type != null && data.withdraw[keys].units != null) {
                        if(data.withdraw[keys].projects){
                            client_opt += '<option value="' + data.withdraw[keys].id + '"> ' + vals.family_name+'  '+vals.first_name+' ( ' + data.withdraw[keys].projects.dealer + ' - ' + data.withdraw[keys].unit_type.name + ' - ' + data.withdraw[keys].units.code + ') - ' + data.currency_list[data.withdraw[keys].currency] +'</option>';
                        }else{
                            client_opt += '<option value="' + data.withdraw[keys].id + '"> ' + vals.family_name+'  '+vals.first_name+' ( N/A' + ' - ' + data.withdraw[keys].unit_type.name + ' - ' + data.withdraw[keys].units.code + ') - ' + data.currency_list[data.withdraw[keys].currency] +'</option>';
                        }
                    }
                });
            }
            for (var keys in data.loan_admin) {
                var loan_admins = data.loan_admin[keys];
                loan_admins_opt += '<option value="' + loan_admins.id + '"> ' + loan_admins.name + ' </option>';
            }
            alldata = data.withdraw;
            var types = '';
            for (var keys in repay_type) {
                if(keys == 0) continue;
                types += '<option value="' + repay_type[keys] + '"> ' + repay_type[keys] + ' </option>';
            }
            //var types = '<option value="1">Cash</option><option value="2">Check</option>';
            var formElement = {
                date_picker:{
                  dpDate:{
                    format: 'yyyy-mm-dd hh:ii:ss',
                    type: 'text',
                    name:'till_date',
                    id: 'till_date',
                    value: getNowTime()
                  }
                },
                input: {
                    drawdown_acc_id: {type: 'hidden', class: 'drawdown_acc_id', name: 'drawdown_acc_id', Id: 'drawdown_acc_id',placeholder: '', style: '',value: ''},
                    from: {type: 'hidden', class: 'drawdown_acc_from', name: 'from', Id: 'from',placeholder: '', style: '',value: ''},
                    selection: {
                        // from: {opt: client_opt, class: 'select2 drawdown_acc_from', name: 'from', id: 'from'},
                        loan_admin: {opt: loan_admins_opt, class: 'select2', name: 'loan_admin', id: 'loan_admin'},
                        types: {opt: types, class: 'select2', name: 'types', id: 'types'}
                    },
                    withdraw: {
                        type: 'text',
                        name: 'withd_acc',
                        class: 'form-control',
                        Id: 'withd_acc',
                        placeholder: '',
                        style: '',
                        value: '',
                        disabled: true,
                    },
                    drawdown_acc: {
                        type: 'text',
                        name: 'drawdown_acc',
                        class: 'form-control',
                        Id: 'drawdown_acc',
                        placeholder: '',
                        style: '',
                        value: '',
                        disabled: true
                    },
                    currency: {
                        type: 'text',
                        name: 'currency',
                        class: 'form-control',
                        Id: 'currency',
                        placeholder: '',
                        style: '',
                        value: '',
                        disabled: true
                    },
                    amount: {
                        type: 'text',
                        name: 'amount',
                        class: 'form-control',
                        Id: 'amount',
                        placeholder: '',
                        style: '',
                        value: '',
                        disabled: true
                    }
                }, textarea: {
                    description: {class: 'form-control', name: 'descr', rows: 10, id: 'descr', text: ''}
                }
            };
            loadModale({
                idSelector: 'deposit',
                title: 'Deposit',
                labels: ['Date', 'From', 'Notify to', 'Deposit type', 'To customer','Drawdown Account', ' currency type', 'Amount', 'Description'],
                loadType: 'deposit',
                keyboard: false,
                backdrop: 'static',
                forms: formElement,
                script: [
                    '/theme/js/jquery.validate.min.js',
                    '/theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',
                    '/js/my_custom_js.js',
                    '/theme/js/select2/select2.js',
                ]
            });
            $('<span><a class="btn btn-group btn-sm btn-primary view_schedule_deposit" href="#" style="margin: 0 10px;">View Schedules</a></span>').insertBefore('#tillSubmit');
            $(`<div class="modal fade" id="repayment_sch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog md-modify">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                            </button>
                            <h4 class="modal-title">Preview Repayment Schedule</h4>
                        </div>
                        <div class="modal-body" id="repayment_schedule">
                        </div>
                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        </div>
                    </div>
                </div>
            </div>`).insertBefore('#deposit');

            $('<span>Print</span><input name="if_print" id="printID" type="checkbox" value="0" class="btn btn-info" style="width: 30px;transform: scale(2.3);margin-right: 4px; margin-bottom: 4px;margin-left:11px" >').insertBefore('#tillSubmit');
            $('input[type=submit]').attr('disabled',true);
            $('.view_schedule_deposit').attr('disabled',true);
        }
    });
    return submitD(not_id);
}

function submitD(not_id = 0) {

    var withd_acc = {};
    var till_act_def_balance = {};
    var clicks = 1;
    $(document).on('change', '#from, #sp_user, #all', function (e) {
        var selc_dd_id = $(this).val() || null;//$('#from option:selected').val() || null;
        $('#amount').val('');
        if (selc_dd_id != null) {
            $.each(alldata, function (inx, vals) {
                till_act_def_balance  = parseFloat(vals.balance);
            });
            withd_acc = matchAndGetData(parseInt(selc_dd_id));
            if(withd_acc.projects){
                $('#withd_acc').val(withd_acc.account_name + ' ( ' + withd_acc.account_no + ' ) - '+'( '+ withd_acc.projects.dealer +' - '+  withd_acc.unit_type.name + ' - ' + withd_acc.units.code+')');
            }else{
                $('#withd_acc').val(withd_acc.account_name + ' ( ' + withd_acc.account_no + ' ) - '+'( N/A' +' - '+  withd_acc.unit_type.name + ' - ' + withd_acc.units.code+')');
            }
            $('#amount').attr('disabled', false);
            $('#drawdown_acc').val(withd_acc.account_no);
            $('#drawdown_acc').attr('disabled', false);
            $('#drawdown_acc').attr('readonly', true);
            $("#drawdown_acc_id").val(withd_acc.id);
            $('#currency').val(withd_acc.currency_tbl.code);
            $('input[type=submit]').attr('disabled',false);

            $(document).on('keyup','#amount', function() {
                var amount_val = parseFloat($('#amount').val());
                if($(this).is('#amount')) {

                    $('#loading').remove();
                    $('input[type=submit]').attr('disabled', false);
                  /*  if( amount_val > till_act_def_balance) {

                        $('input[type=submit]').attr('disabled',true);
                        $('<div id="loading"></div>').appendTo('body');
                        imgLoading(true,'Your amount can\'t be over than your balance',6,'warning');
                    }
                    */
                }
            });

            $('#sdeposit').validate({
                rules: {
                    client: {
                        required: true
                    }, amount: {
                        required: true, number: true
                    }, descr: {
                        required: true
                    }, loan_admin: {
                        required: true
                    }, types: {
                        required: true
                    }
                }, submitHandler: function () {

                    if (confirm('Are you sure?')) {
                        // console.log($('#types option:selected').val());
                        // $('<div id="loading"></div>').appendTo('body');
                        // imgLoading(true, 'Loading....!!!', 60, 'warning');
                        $('input[type=submit]').attr('disabled',true);
                        var bank_name = $('input[name=bank_name]').val() ? $('input[name=bank_name]').val() : 0;
                        var check_num = $('input[name=check_num]').val() ? $('input[name=check_num]').val() : 0;

                        var balance = (parseFloat(withd_acc.balance) + parseFloat($('#amount').val()));
                        var data = $('#sdeposit').serialize() + '&client_id=' + parseInt(withd_acc.client_id) + '&id='
                         + parseInt(withd_acc.id) + '&users_id=' + parseInt($('#loan_admin option:selected').val());
                        data += '&currency_id=' + parseInt(withd_acc.currency) + '' +
                            '&client_name=' + withd_acc.account_name + '&description=' + $('#descr').val() + '&amount='
                            + parseFloat($('#amount').val())
                            + '&balance=' + balance + '&types=' + $('#types option:selected').val() + '&bank_name='
                            + bank_name + '&check_num=' + check_num + '&till_date=' + $('#till_date').val()
                            + '&if_print='+ $('#printID').val() + '&not_id=' + not_id;

                            Ajaxs('/teller/deposit', 'post', 'json', data, function (data, status) {
                            $('#loading').remove();
                            //$('<div id="loading"></div>').appendTo('body');
                            //console.log("status = ",status); console.log("logos = ",data);
                            if (status !== 'success') {
                                return imgLoading(true, 'We can\' save your data t!!!', 5, 'warning');
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
                                    }else{
                                       // location.reload();
                                        // $.ajax({
                                        //     url: "https://contract.chaktomukcity.com/api/unit_actions/deposit",
                                        //     type: 'GET',
                                        //     headers: {
                                        //                  'Access-Control-Allow-Origin': '*',
                                        //                   'Content-Type':'application/json'
                                        //          },
                                        //     dataType: 'jsonp',
                                        //     data: {
                                                
                                        //            code : withd_acc.units.code,
                                        //     },
                                            

                                        //     error : function(err) {

                                        //           window.location.reload();
                                        //       },
                                        //     success: function(data) {
                                        //               window.location.reload();
                                                    
                                        //     }
                                            
                                            
                                        // }); 
                                    }
                                }
                                if (data.ins_notify === false) {
                                     imgLoading(true, 'Deposit Fail !!!', 5000, 'danger');
                                    $('#result').remove();
                                }
                            }
                        });
                    }
                }
            });
        }
    });
}
function refreshPage() {
    location.reload(true);
}
function matchAndGetData(dd_id) {

    for (var keys in alldata) {
        if (parseInt(alldata[keys].id) === parseInt(dd_id)) {
            return alldata[keys];
        }
    }
}

$(document).on('change', '#types', function () {

    var del = $('#sdeposit').find('.appendData');
    if ($(this).is('#types')) {
        for (var i = 0; i <= del.length; i++) {
            del.children().remove();
        }
        $('<div class="form-group">' +
            '<label class="control-label col-sm-4" for="print"> Check number : </label>' +
            '<div class="col-sm-8 form-checkbox"> ' +
            '   <input type="text" value="" name="check_num" class="form-control" />' +
            '</div>' +
            '</div>' +
            '<div class="form-group">' +
            '<label class="control-label col-sm-4" for="print"> Bank name: </label>' +
            '<div class="col-sm-8 form-checkbox"> ' +
            '   <input type="text" value="" name="bank_name" class="form-control" />' +
            '</div>' +
            '</div>').appendTo('.appendData');
    }
});

function checkit(obj) {

    var cbs = document.getElementsByClassName("ch");
    for (var i = 0; i < cbs.length; i++) {
        cbs[i].checked = false;
    }
    obj.checked = true;
}

$(document).on('change', '.drawdown_acc_from', function () {
    var drawdown_id = $(this).val();
    $('.view_schedule_deposit').attr('disabled',true);
    $.ajax({
        url: '/getdeposit_schedule',
        type: 'GET',
        dataType: "json",
        // processData: false,
        // contentType: false,
        data:{drawdown_acc_id:drawdown_id},
        success: function (data) {
            if(data.success == 1){
                $('.view_schedule_deposit').click(function(){
                    get_repayment_schedule(data.url);
                });
                // $('.view_schedule_deposit').attr('href',data.url);
                $('.view_schedule_deposit').attr('href','javascript:void(0);');
                $('.view_schedule_deposit').attr('disabled',false);
            }
        }
    });
});

function get_repayment_schedule(urls){
    if(urls){
        $.ajax({
            url: urls,
            type:'GET',
            dataType: "JSON",
            success:function(data){
                if(data){
                    $("#repayment_sch").css('z-index','2000');
                    $("#repayment_sch").modal({ backdrop: "static", keyboard: !1});
                    $("#repayment_sch").find('.modal-backdrop').css('opacity','0');
                    $("#repayment_schedule").html(data);
                }
            }
        })
    }
}

$(document).on('click', '#printID', function () {

    var check = $('#printID'), vals = 0;
    if ($(this).is(':checked')) {
        var loads = $('#loading');
        for (var i = 0; i <= loads.length; i++) {
            loads.children().remove();
        }
        $('<div id="loading"></div>').appendTo('body');
        imgLoading(true, 'You will print data after submit', 1, 'warning');
        $('#printID').val(1);
    } else if ($(this).is(':not(:checked)')) {
        $('#loading').remove();
        $('#printID').val(0);
    }
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