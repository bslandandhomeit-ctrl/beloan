/**
 * Created by hengsoheak on 6/21/2016.
 */
function issueTill(n_source_id, types){

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
                if (typeof data.chief != 'undefined' && Object.keys(data.teller).length != 0) {
                    GetNotificationData(data.teller, data.chief, n_source_id);
                }
            }
        }
    });
}


function GetNotificationData(teller, chief, n_source_id) {
    var chief_vals = null;
    if (Object.keys(chief).length != 0) {
        for (var keys = 0; keys < Object.keys(chief).length; keys ++) {
            var vals = chief[keys];
            chief_vals = vals;
        }
    }
    if (Object.keys(teller).length != 0) {
        for (var key = 0; key < Object.keys(teller).length; key ++) {
            var vals = teller[key];
            if (Number(vals.n_source_id) == Number(n_source_id)) {
                return loadModel(vals, chief_vals);
            }
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
                        value: obj.cashInorCashOut + obj.symbol
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
            $('#loading').remove();
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
            if (obj.n_activity_type === 'Return Till') {
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
    $(this).attr('disabled', true);
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
        data.type = obj.n_activity_type;

        return NotificationSubmit('#snotifications', data);
    }
    if (that.is('#reject')) {

        data.url = '/teller/notification_action';
        data.action = 2;
        data.last_chief_blance = Number(data.chief_balance) + Number(data.cash_out);
        data.last_teller_balance = Number(data.balance) - Number(data.cash_out);
        data.n_source_id = obj.n_source_id;
        data.type = obj.type;
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
        data.currency_id = obj.currency_id;
        return NotificationSubmit('#snotifications', data);
    }
    if (that.is('#reject_return')) {

        data.url = '/teller/returnTillNotification';
        data.action = 2;
        data.cash_in = data.cash_out;
        data.currency_id = obj.currency_id;
        return NotificationSubmit('#snotifications', data);
    }
});