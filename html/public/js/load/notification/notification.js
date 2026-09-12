$(document).ready(function () {
    loadajax();
    $('#notes').click(function (e) {
        loadajax();
    });
});

//$(function () {
//    function setTime() {
//        callFun = setInterval(function () {
//            loadajax();
//        }, 8000);
//    }
//    setTime();
//    $(window).blur(function () {
//        clearInterval(callFun);
//    }).focus(setTime);
//});

function loadajax() {

    $("#number").html('');
    $.ajax({
        url: '/notification/get_not',
        method: 'get',
        dataType: 'json',
        timeout: 10000,
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        },
        success: function (data, status) {
            $("#notification").html('');
            if (status === 'success') {
//console.log(data);
                return SetData(data);
            }
        }, error: errorCallback
    });
}

function SetData(data) {

    var disburse = data.disburse;
    var reject_disburse = data.reject_disburse;
    var repayment = data.repayment;
    var till = data.till;
    var fee_charge_repayment = data.fee_charge_repayment;
    var reject_repayment = data.reject_repayment;
    var reject_fee_charge = data.reject_fee_charge;
    var notification_amount = 0;

    var htmls = '<div class="col-lg-12 table-responsive ">';
    htmls += '<table class="table table-hover"  id="tableid">';
    htmls += '<thead><tr>' +
        '<th width="100"> From </th>' +
        '<th width="200px">Types</th>' +
        '<th width="100px">Amount</th>' +
        '<th width="300px">Description</th>' +
        '<th width="200px">Date</th>' +
        '<th width="200px">Methode</th>' +
        '<th width="40px">action</th>' +
        '</tr>';
    htmls += '</thead><tbody>';

    if(!$.isEmptyObject(data.journal)){
        $.each(data.journal, function(inx, vals){
            notification_amount++;
            htmls += generateTR(vals);
        });
    }
    if (!$.isEmptyObject(disburse)) {
        $.each(disburse, function (inx, vals) {
            notification_amount++;
            htmls += generateTR(vals);
        });
    }
    if (!$.isEmptyObject(reject_disburse)) {
        $.each(reject_disburse, function (inx, vals) {
            notification_amount++;
            htmls += generateTR(vals);

        });
    }
    if (!$.isEmptyObject(till)) {
        $.each(till, function (inx, vals) {
            notification_amount++;
            htmls += generateTR(vals);
        });
    }
     if (!$.isEmptyObject(repayment)) {
        $.each(repayment, function (inx, vals) {
            notification_amount++;
            htmls += generateTR(vals);
        });
    }
    if (!$.isEmptyObject(reject_repayment)) {
        $.each(reject_repayment, function (inx, vals) {
            notification_amount++;
            htmls += generateTR(vals);
        });
    }
    if (!$.isEmptyObject(fee_charge_repayment)) {
        $.each(fee_charge_repayment, function (inx, vals) {

            notification_amount++;
            htmls += generateTR(vals);

        });
    }
    if (!$.isEmptyObject(reject_fee_charge)) {
        $.each(reject_fee_charge, function (inx, vals) {

            notification_amount++;
            htmls += generateTR(vals);

        });
    }
    var rows = notification_amount++;
    htmls += '</tbody></table>';
    htmls += '</div>';
    $('#number').html(rows);
    if (parseInt(rows) === 0) {
        return $("#notification").html('');
    }
    $(htmls).appendTo("#notification");
    $('table#tableid tbody tr td').css({'padding': '5px 5px'});
}

function generateTR(vals) {
    var name = (vals.username)?vals.username:vals.user.name;
    var notification_id = vals.not_id;
    if(typeof vals.not_id == 'undefined'){
        notification_id = vals.id;
    }
    if(vals.methode == 'undefined' || typeof vals.methode == 'undefined'){
        vals.methode = '-';
    }
    var htmls = '<tr data-id="' + vals.n_source_id + '" datatype="' + vals.n_activity_type + '" data-not_id="' +notification_id+ '">';
    htmls += '<td>' + name+ ':</td>';
    htmls += '<td>' + vals.n_activity_type.replace(/_/g," ") + '</td>';
    htmls += '<td>' + vals.n_amount+'</td>';
    htmls += '<td>' + vals.n_description + '</td>';
    htmls += '<td>' + vals.n_create_times + '</td>';
    htmls += '<td>' + vals.methode + '</td>';
    var arr = 'Cash Deposit,withdraw,Fee Charge Repayment,Loan Repayment,Issue Till,Transfer Till, Return Till'.split(',');
    var action = '';
    if ($.inArray(vals.n_activity_type, arr) >= 0) {
        action = '<button type="button" class="btn btn-info btn-sm ok" data-id="'+notification_id+'" datatype = "'+vals.n_activity_type+'">Yes</button>';
        action += '<button type="button" class="btn btn-info btn-sm ng" data-id="'+notification_id+'" datatype = "'+vals.n_activity_type+'">No</button>';
    }
    htmls += '<td style="white-space: nowrap;">' + action + '</td>';
    htmls += '</tr>';
    return htmls;
}

function errorCallback(jqXHR, textStatus, errorThrown) {

    if (textStatus == "timeout" || textStatus == "error" || errorThrown == "Internal Server Error") {
        imgLoading(true, 'Please reload your page!!! ', 7, textStatus);
    }
    return false;
}
var button_flag = "";
//var trans_type = "";
$(document).on('click', 'table#tableid tbody tr, .ok', function () {

    var id = $(this).attr('data-id');
    var trans_type = $(this).attr('datatype');
    var not_id = $(this).attr('data-not_id');

//console.log(trans_type);
    // console.log(id);
    // console.log(types);
    // console.log(not_id);
    ScriptRequire([
        '/theme/js/jquery.validate.min.js',
        '/js/load/form_model_load.js'
    ], function (status) {
    });

    if($(this).is('.ok')) {
        button_flag = "ok";
        var n_id = $(this).attr('data-id');
        var trans_type = $(this).attr('datatype');
// console.log(n_id); console.log(trans_type); 
        if(trans_type == 'withdraw'){
            ScriptRequire(['/js/load/withdraw/withdraw.js'], function (status) {
                if (status === 'success') {
                     withdraw(n_id, button_flag);
                }
            });
        }else if(trans_type == 'Cash Deposit'){
            ScriptRequire(['/js/load/deposit/deposit.js'], function (status) {
                if (status === 'success') {
                     deposit(n_id, button_flag);
                }
            });
        }
    }
    fetchingData(id, trans_type, not_id);
});
$(document).on('click', 'table#tableid tbody tr, .ng', function () {

    var id = $(this).attr('data-id');
    var types = $(this).attr('datatype');
    var not_id = $(this).attr('data-not_id');

    // console.log(id);
    // console.log(types);
    // console.log(not_id);
    ScriptRequire([
        '/theme/js/jquery.validate.min.js',
        '/js/load/form_model_load.js'
    ], function (status) {
    });

    if($(this).is('.ng')) {
        button_flag = "ng";
        var n_id = $(this).attr('data-id');
        var trans_type = $(this).attr('datatype');
        
        if(trans_type == 'withdraw'){
           ScriptRequire(['/js/load/withdraw/withdraw.js'], function (status) {
                if (status === 'success') {
                     withdraw(n_id, button_flag);
                }
            });
        }else if(trans_type == 'Cash Deposit'){
            ScriptRequire(['/js/load/deposit/deposit.js'], function (status) {
                if (status === 'success') {
                     deposit(n_id, button_flag);
                }
            });
        }
    }
    fetchingData(id, trans_type, not_id);
});
function fetchingData(n_source_id, types, not_id) {

    var data = {
        'Loan Repayment': {
           url: '/js/load/loan/loan_repayment.js',
           methods: function () {
               Repayment_loan(n_source_id, types)
           }
        },
        //'Reject Repayment': {
        //    url: '/js/load/loan/repay_change_till_acc.js',
        //    methods: function () {
        //        repay_change_till_acc(n_source_id, not_id)
        //    }
        //},
        //'Fee Charge Repayment': {
        //    url: '/js/load/loan/fee_charge_repayment.js',
        //    methods: function () {
        //        fee_charge_repayment(n_source_id, types);
        //    }
        //}, 'reject fee charge': {
        //    url: '/js/load/loan/change_till_acc.js',
        //    methods: function () {
        //        change_till_acc(n_source_id, not_id);
        //    }
        //}
        'Add_Journal':{
            url:'/js/load/Journal/journal.js',
            methods:function(){
                journal(n_source_id, types);
            }
        },
        'Disburse Loan': {
            url: '/js/load/loan/disburse_loan.js',
            methods: function () {
                disbursement(n_source_id, types);
            }
        },
        'reject_disburse': {
            url: '/js/load/loan/change_till_account.js',
            methods: function () {
                change_till_account(n_source_id, not_id);
            }
        }, 'Issue Till,Transfer till,Return Till': {//'Transfer till', 'Issue Till', 'return Till'
            url: '/js/load/issueTill.js',
            methods: function () {
                issueTill(n_source_id, types);
            }
        }
    };
    $.each(data, function (inx, vals) {

        var array = inx.split(',');
        if ($.inArray(types, array) >= 0) {
            var _data = vals;
            ScriptRequire([_data.url], function (status) {
                if (status === 'success') {
                    return _data.methods();
                }
            });
        }
    });
}

function NotificationSubmit(selector, objs) {

    $('#loading').remove();
    $('<div id="loading"></div>').appendTo('body');
    imgLoading(true, 'Loading...', 1, status);
    objs._token = $('meta[name=_token]').attr('content');
    $.ajax({
        url: objs.url,
        method: 'post',
        dataType: "json",
        data: objs,
        cache: false,
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (data, status) {

            if (status === 'success') {

                var num = $("#number");
                var newNumber = Number(num.text()) - 1;
                num.text(newNumber);
                imgLoading(true, "Successful", 3, status);
                $('#results').remove();
                $('form#snotifications').remove();
            }
        }
    }).done(function () {
        setTimeout(function () {
            $('#loading').remove();
        }, 100);
    });
}

$(document).on("click", ".mclose, .modal-backdrop", function (e) {
    e.preventDefault();
    $('#loading').remove();
    $('#results').remove();
    $('form#snotifications').remove();
});

$(document).on('click', '#tableid thead tr th', function () {
    var parents = $(this).offsetParent().offsetParent().offsetParent();
    if (parents.hasClass('open')) {
        return false;
    }
});

function ScriptRequire(script, retn) {

    $.each(script, function (inx, vals) {
        $.ajax({
            url: vals,
            dataType: "script",
            cache: false,
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            },
            success: function (data, status) {
                return retn(status);
            },
            error: function () {
                throw new Error("Could not load script " + script);
            }
        });
    });
}
