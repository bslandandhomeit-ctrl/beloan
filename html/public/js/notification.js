/**
 * Created by hengsoheak on 4/1/2016.
 * notfication
 * call ajax->query data if user currently active in browser within 3 seconds.
 */
$('#notes').click(function (e) {
    loadajax();
});
$(function () {
    function setTime() {
        callFun = setInterval(function () {
            loadajax();
        }, 110000);
    }
    setTime();
    $(window).blur(function () {
        clearInterval(callFun);
    }).focus(setTime);
});

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
                return SetData(data);
            }
        }
    });
}

function SetData(data) {
    console.log(data)
    var disburse = data.disburse;
    var repayment = data.repayment;
    var till = data.till;
    var notification_amount = 0;

    var htmls = '<div class="col-lg-12 table-responsive ">';
    htmls += '<table class="table table-hover"  id="tableid">';
    htmls += '<thead><tr>' +
        '<th width="100"> From </th>' +
        '<th width="200px">Types</th>' +
        '<th width="100px">Amount</th>' +
        '<th width="300px">Description</th>' +
        '<th width="200px">Date</th>' +
        '</tr>';
    htmls += '</thead><tbody>';

    if (!$.isEmptyObject(disburse)) {
        $.each(disburse, function (inx, vals) {

            notification_amount++;
            htmls += '<tr data-id="' + vals.n_source_id + '" datatype="' + vals.n_activity_type + '" >';
            htmls += '<td>' + vals.username + ':</td>';
            htmls += '<td>' + vals.n_activity_type + '</td>';
            htmls += '<td>' + vals.n_amount + '</td>';
            htmls += '<td>' + vals.n_description + '</td>';
            htmls += '<td>' + vals.n_create_times + '</td>';
            htmls += '</tr>';
        });
    }
    if (!$.isEmptyObject(repayment)) {
        $.each(disburse, function (inx, vals) {

            notification_amount++;
            htmls += '<tr data-id="' + vals.n_source_id + '" datatype="' + vals.n_activity_type + '" >';
            htmls += '<td>' + vals.username + ':</td>';
            htmls += '<td>' + vals.n_activity_type + '</td>';
            htmls += '<td>' + vals.n_amount + '</td>';
            htmls += '<td>' + vals.n_description + '</td>';
            htmls += '<td>' + vals.n_create_times + '</td>';
            htmls += '</tr>';
        });
    }
    if (!$.isEmptyObject(till)) {
        $.each(till, function (inx, vals) {

            notification_amount++;
            htmls += '<tr data-id="' + vals.n_source_id + '" datatype="' + vals.n_activity_type + '" >';
            htmls += '<td>' + vals.username + ':</td>';
            htmls += '<td>' + vals.n_activity_type + '</td>';
            htmls += '<td>' + vals.n_amount + '</td>';
            htmls += '<td>' + vals.n_description + '</td>';
            htmls += '<td>' + vals.n_create_times + '</td>';
            htmls += '</tr>';
        });
    }
    var rows = notification_amount++;
    htmls += '</tbody></table>';
    htmls += '</div>';
    $('#number').html(rows);
    if(parseInt(rows) === 0){
        return $("#notification").html('');
    }
    $(htmls).appendTo("#notification");
    $('table#tableid tbody tr td').css({'padding': '5px 5px'});
}

function errorCallback(jqXHR, textStatus, errorThrown) {

    if (textStatus == "timeout" || textStatus == "error" || errorThrown == "Internal Server Error") {
        return imgLoading(true, 'Errors ( jqXHR: ' + jqXHR + ' textStatus: ' + textStatus + ' errorThrown: ' + errorThrown + ' )', 7, textStatus);
    }
    return false;
}
$(document).on('click', 'table#tableid tbody tr', function () {
    var id = $(this).attr('data-id')
    var types = $(this).attr('datatype')

    GetNotScriptRequire([
        '/theme/js/jquery.validate.min.js',
        '/js/load/form_model_load.js',
    ]);
    fetchingData(id, types);
});
var obj = {};
var chief_obj = {};
var loan = null;
var teller_loan = null;

function fetchingData(n_source_id, types) {

    if (typeof types != 'undefined' && types === 'Loan Repayment') {

        $.ajax({
            url: '/notification/notification_data/' + n_source_id + '/' + types,
            method: 'GET',
            dataType: 'Json',
            timeout: 1000000,
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            },
            data: {id: n_source_id},
            success: function (data, status) {
                console.log(data)
                if (status === 'success' || typeof data.data != 'undefined' || Object.keys(data.data).length != 0) {
                    $('<div id="results"></div>').appendTo('body');
                    $('<div id="loading"></div>').appendTo('body');
                    $.each(data.data, function (ins, vals) {
                        return LoanRepayment(vals);
                    });
                }
            }
        });
        return;
    }

    $.ajax({
        url: '/notification/notification_data/' + n_source_id + '/' + types,
        method: 'GET',
        dataType: 'Json',
        timeout: 1000000,
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        },
        data: {id: n_source_id},
        success: function (data, status) {

            if (status !== 'success') {
                return imgLoading(true, '', 3, status);
            } else {
                $('<div id="loading"></div>').appendTo('body');
                $('<div id="results"></div>').appendTo('body');

                if (typeof data.chief != 'undefined' && Object.keys(data.teller).length != 0) {

                    GetNotificationData(data.teller, data.chief, n_source_id);

                } else if (typeof data.loan != 'undefined' && typeof data.loan === 'object' && Object.keys(data.loan).length > 0 && Object.keys(data.teller_loan).length != 0) {

                    for (var key in data.loan) {
                        loan = data.loan[key];
                    }
                    for (var key in data.teller_loan) {
                        teller_loan = data.teller_loan[key];
                    }
                    var loan_amount = loan.loan_amount;
                    var Teller_balance = teller_loan.balance;
                    return DisursModel(loan, teller_loan);
                }
            }
        }
    });
}

function GetNotScriptRequire(script) {

    $.each(script, function (inx, vals) {
        $.ajax({
            url: vals,
            dataType: "script",
            cache: false,
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            },
            success: function (data, status) {
            },
            error: function () {
                throw new Error("Could not load script " + script);
            }
        });
    });
}

/**
 * Created by hengsoheak on 4/25/2016.
 */

function GetNotificationData(teller, chief, n_source_id) {

    var chief_vals = null;
    if (Object.keys(chief).length != 0) {
        for (var keys in chief) {
            var vals = chief[keys];
            chief_vals = vals;
        }
    }
    if (Object.keys(teller).length != 0) {

        for (var key in teller) {

            var vals = teller[key];
            if (Number(vals.n_source_id) == Number(n_source_id)) {

                return loadModel(vals, chief_vals);
            }
        }
    }
}

function DisursModel(loans, teller_loan) {

    var formElement = {};

    if (typeof Object.keys(loans).length != 0 && typeof Object.keys(teller_loan).length != 0) {

        formElement = {
            input: {
                from: {
                    type: 'text',
                    name: 'tran_amount',
                    class: 'form-control',
                    Id: 'tran_amount',
                    placeholder: '',
                    style: '',
                    value: loans.username
                },
                Amount: {
                    type: 'text',
                    name: 'amount',
                    class: 'form-control',
                    Id: 'amount',
                    placeholder: '',
                    style: '',
                    value: loans.loan_amount + '$'
                },
                commissionFee: {
                    type: 'text',
                    name: 'commission_fee',
                    class: 'form-control',
                    Id: 'commission_fee',
                    placeholder: 'commission_fee',
                    style: '',
                    value: loans.loan_amount + '$'
                },
            }, textarea: {
                description: {class: 'form-control', name: 'descr', rows: 10, id: 'descr', text: loans.disburse_note}
            }
        };
        Notfification_Modal({
            idSelectors: 'notifications',
            title: 'Notification Types : ' + '<i>' + teller_loan.n_activity_type + '</i>',
            labels: ['From', 'Loan Amount', 'Commission Fee', 'Description'],
            loadType: teller_loan.n_activity_type,
            keyboard: true,
            backdrop: 'dynamic',
            forms: formElement
        });

        $('form#snotifications .modal-footer').html('<button type="button" class="btn btn-default mclose" data-dismiss="modal">Close</button>');
        if (teller_loan.n_activity_type === "Disburse Loan") {
            $('form#snotifications .modal-footer').prepend('<input type="submit" id="approve_db" name="approve_db" value="OK" class="btn btn-warning">');
        }
        return;
    }
}

$(document).on('click', '#approve_db', function () {

    var $this = $(this);
    if ($this.is('#approve_db')) {
        // Update Till account balance
        var data = {
            url: '/notification/ApproveDisburse/' + teller_loan.id,//teller_loan
            id: teller_loan.id,
            remind_balance: Number(teller_loan.balance) - Number(loan.loan_amount)
        };
        data.balancePlusCommision = Number(data.remind_balance) + Number(loan.charge_amount);
        //Insert data to Till Transaction
        data.till_account_id = teller_loan.id;
        data.from_account = loan.name;
        data.to_account = teller_loan.account_name;
        data.till_user_id = teller_loan.n_user_id;
        data.branch_id = teller_loan.branch_id;
        data.operate_by = teller_loan.assign_user_id;
        data.type = teller_loan.n_activity_type;
        data.cash_in = Number(loan.charge_amount);
        data.cash_out = Number(loan.loan_amount);
        data.not_id = Number(teller_loan.not_id);
        return NotificationSubmit('#snotifications', data);
    }

});
function LoanRepayment(data) {

    if (typeof data != 'undefined' || typeof data === 'object' || Object.keys(data).length != 0) {
        formElement = {
            input: {
                from: {
                    type: 'text',
                    name: 'tran_amount',
                    class: 'form-control',
                    Id: 'tran_amount',
                    placeholder: '',
                    style: '',
                    value: data.username
                },
                Amount: {
                    type: 'text',
                    name: 'amount',
                    class: 'form-control',
                    Id: 'amount',
                    placeholder: '',
                    style: '',
                    value: data.amount + '$'
                },
            }, textarea: {
                description: {class: 'form-control', name: 'descr', rows: 10, id: 'descr', text: data.id}
            }
        };
        Notfification_Modal({
            idSelectors: 'notifications',
            title: 'Notification Types : ' + '<i>' + data.n_activity_type + '</i>',
            labels: ['From', 'Amount', 'Description'],
            loadType: data.n_activity_type,
            keyboard: true,
            backdrop: 'dynamic',
            forms: formElement
        });

        $('form#snotifications .modal-footer').html('<button type="button" class="btn btn-default mclose" data-dismiss="modal">Close</button>');
        if (data.n_activity_type === "Loan Repayment") {
            $('form#snotifications .modal-footer').prepend('<input type="submit" id="approve_repayment" name="approve_repayment" value="OK" class="btn btn-warning">');
            $('#approve_repayment').click(function () {
                $this = $(this);
                if ($this.is('#approve_repayment')) {

                    var dataobj = {

                        url: '/notification/ApproveDisburse/' + data.teller_till_account_id,
                        till_account_id: data.teller_till_account_id,
                        from_account: data.username,
                        till_user_id: data.n_user_id,
                        branch_id: data.branch_id,
                        operate_by: data.n_user_id,
                        amount: Number(data.amount),
                        type: data.n_activity_type,
                        not_id: data.not_id,
                        description: $('#descr').val()
                    };
                    if (confirm("Sure!!!")) {
                        return NotificationSubmit('#snotifications', dataobj);
                    }
                }
            })
        }
    }
}


function loadModel(teller, chief_vals) {

    obj = teller;
    chief_obj = chief_vals;

    var formElement = {};
    if (typeof  obj != 'undefined' && typeof  chief_obj != 'undefined') {

        if (Object.keys(obj).length != 0 && Object.keys(chief_obj).length != 0) {


            obj.cashInorCashOut = 0;
            if (Number(obj.cash_out) === 0) {
                obj.cashInorCashOut = obj.cash_in;
            } else if (Number(obj.cash_in) === 0) {
                obj.cashInorCashOut = obj.cash_out;
            }
            formElement = {
                input: {
                    from: {
                        type: 'text',
                        name: 'tran_amount',
                        class: 'form-control',
                        Id: 'tran_amount',
                        placeholder: '',
                        style: '',
                        value: obj.from_account
                    },
                    Amount: {
                        type: 'text',
                        name: 'amount',
                        class: 'form-control',
                        Id: 'amount',
                        placeholder: '',
                        style: '',
                        value: obj.cashInorCashOut + '$'
                    }

                }, textarea: {
                    description: {class: 'form-control', name: 'descr', rows: 10, id: 'descr', text: obj.description}
                }
            };

            Notfification_Modal({
                idSelectors: 'notifications',
                title: 'Notification Types : ' + '<i>' + obj.n_activity_type + '</i>',
                labels: ['From', 'Amount', 'Description'],
                loadType: obj.n_activity_type,
                keyboard: true,
                backdrop: 'dynamic',
                forms: formElement,
            });
            $('form#snotifications .modal-footer').html('<button type="button" class="btn btn-default mclose" data-dismiss="modal">Close</button>');
            if (obj.n_activity_type === 'Transfer till') {

                $('form#snotifications .modal-footer').prepend(
                    '<input type="submit" id="ok" name="ok" value="OK" class="btn btn-warning">' +
                    '<input type="submit" id="reject"  name="reject" value="Reject" class="btn btn-info">'
                );
            }
            if (obj.n_activity_type === 'Issue Till') {

                $('form#snotifications .modal-footer').prepend(
                    '<input type="submit" id="approve" name="approve" value="Approve" class="btn btn-warning">' +
                    '<input type="submit" id="reject"  name="reject" value="Reject" class="btn btn-info">'
                );
            }
            if (obj.n_activity_type === 'return Till') {
                $('form#snotifications .modal-footer').prepend(
                    '<input type="submit" id="approve_return" name="approve_return" value="Approve" class="btn btn-warning">' +
                    '<input type="submit" id="reject_return"  name="reject_return" value="Reject" class="btn btn-info">'
                );
            }
        }
    }
}

$(document).on('click', '#approve, #reject, #ok, #approve_return, #reject_return', function (e) {
    e.preventDefault();

    var that = $(this);
    var data = {
        till_account_id: obj.till_account_id,
        chief_till_account_id: obj.chief_till_account_id,
        from_account: obj.from_account,
        to_account: obj.to_account,
        assign_user_id: obj.assign_user_id,
        balance: obj.balance,
        cash_out: obj.cashInorCashOut,
        cash_in: obj.cashInorCashOut,
        branch_id: obj.branch_id,
        operate_by: obj.created_by,
        description: obj.description,
        type: obj.type,
        not_id: obj.not_id,
        n_source_id: Number(obj.n_source_id),
        chief_balance: chief_obj.balance,

    };
    if (that.is('#approve')) {

        if (Number(data.chief_balance) < Number(data.cash_out)) {
            alert('your balance is not enough');
            $('#approve').attr('disabled', true);
            return
        }
        data.url = '/teller/notification_action';
        data.action = 1;
        data.tell_balance = Number(data.balance) + Number(data.cash_in);
        data.chief_balance = Number(chief_obj.balance) - Number(data.cash_out);
        data.currency_id = obj.currency_id;

        return NotificationSubmit('#snotifications', data);
    }
    if (that.is('#reject')) {

        data.url = '/teller/notification_action';
        data.action = 2;
        data.last_chief_blance = Number(data.chief_balance) + Number(data.cash_out);
        data.last_teller_balance = Number(data.balance) - Number(data.cash_out);
        data.n_source_id = obj.n_source_id;
        return NotificationSubmit('#snotifications', data);
    }
    if (that.is('#ok')) {

        data.url = '/teller/transfer_till_from_chief';
        data.action = 1;
        data.cash_in = data.cash_out;
        data.tell_balance = Number(obj.balance) + Number(data.cash_in);
        data.till_account_id = obj.chief_id;
        data.till_user_id = obj.till_user_id;
        data.currency_id = chief_obj.currency_id;

        return NotificationSubmit('#snotifications', data);
    }
    if (that.is('#approve_return')) {

        data.url = '/teller/returnTillNotification';
        data.action = 1;
        data.cash_in = data.cash_out;
        data.tell_balance = Number(obj.balance) + Number(data.cash_in);
        data.balance = data.chief_balance + data.cash_out;
        data.till_account_id = obj.chief_id;
        data.till_user_id = obj.till_user_id;
        return NotificationSubmit('#snotifications', data);
    }
    if (that.is('#reject_return')) {

        data.url = '/teller/returnTillNotification';
        data.action = 2;
        data.cash_in = data.cash_out;
        return NotificationSubmit('#snotifications', data);
    }
});

function NotificationSubmit(selector, objs) {

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
            imgLoading(true, 'Loadding..', 1, status);
        },
        success: function (data, status) {

            if (status === 'success') {

                var num = $("#number");
                var newNumber = Number(num.text()) - 1;
                num.text(newNumber);
                imgLoading(true, "Successful", 3, status);
                $('#results').remove();
                $('form#snotifications').remove();
                //return returnMessages(data,status);
            }
        }
    });
}

$(document).on("click", ".mclose, .modal-backdrop", function (e) {
    e.preventDefault();
    $('#loading').remove();
    $('#results').remove();
    $('form#snotifications').remove();
});

function returnMessages(data, status) {

    if (data.up === 1) {
        imgLoading(true, "Successful" + data.up, 23, status);
    }
    if (data.insCashIn) {
        imgLoading(true, "Successful add" + data.insCashIn, 23, status);
    }
    if (data.insCashOut) {
        imgLoading(true, "Successful" + data.insCashOut, 23, status);
    }
}
$(document).on('click', '#tableid thead tr th', function () {
    var parents = $(this).offsetParent().offsetParent().offsetParent();
    if (parents.hasClass('open')) {
        return false;
    }
});
