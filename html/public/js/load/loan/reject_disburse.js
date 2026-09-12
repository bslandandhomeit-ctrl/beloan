/**
 * Created by hengsoheak on 6/22/2016.
 */
function reject_disburse(disburse, commissions, till) {

    var data = {
        url: '/notification/RejectsDisburse/' + till.teller_till_account_id, //till
        id: parseInt(till.id),
        remind_balance: Number(till.balance) - Number(disburse.loan_amount)
    };

    data.n_user_id = disburse.user_id;
    data.n_source_id = disburse.loans_id;
    data.chief_till_account_id = disburse.user_id;
    data.n_activity_type = 'reject_disburse';
    data.n_amount = disburse.n_amount;
    data.n_description = commissions.description;
    data.teller_till_account_id = till.teller_till_account_id;
    data.not_id = disburse.not_id;
    $('<div id="loading"></div>').appendTo('body');
    return NotificationSubmit('#snotifications', data);
}
//
//function settingForm(disburse, commissions, till) {
//
//    var formElement = {};
//    formElement = {
//        input: {
//            from: {
//                type: 'text',
//                name: 'tran_amount',
//                class: 'form-control',
//                Id: 'tran_amount',
//                placeholder: '',
//                style: '',
//                value: disburse.username,
//                disabled: true
//            },
//            Amount: {
//                type: 'text',
//                name: 'amount',
//                class: 'form-control',
//                Id: 'amount',
//                placeholder: '',
//                style: '',
//                value: disburse.loan_amount + '$',
//                disabled: true
//            },
//            commissionFee: {
//                type: 'text',
//                name: 'commission_fee',
//                class: 'form-control',
//                Id: 'commission_fee',
//                placeholder: 'commission_fee',
//                style: '',
//                value: commissions.amount + '$',
//                disabled: true
//            },
//            Till_account: {
//                type: 'text',
//                name: 'commission_fee',
//                class: 'form-control',
//                Id: 'commission_fee',
//                placeholder: 'commission_fee',
//                style: '',
//                value: till.name + ' ( ' + till.account_no + '/' + till.account_name + ' ) ',
//                disabled: true
//            },
//        }, textarea: {
//            description: {class: 'form-control', name: 'descr', rows: 10, id: 'descr', text: commissions.description}
//        }
//    };
//
//    Notfification_Modal({
//        idSelectors: 'reject_disburse',
//        title: 'Notification Types : ' + '<i>Reject ' + disburse.n_activity_type + '</i>',
//        labels: ['From', 'Loan Amount', 'Commission Fee', 'Till_account', 'Description'],
//        loadType: '',
//        keyboard: false,
//        backdrop: 'dynamic',
//        forms: formElement
//    });
//
//    $('form#sreject_disburse .modal-footer').html('<button type="button" class="btn btn-default mclose" data-dismiss="modal">Close</button>');
//    $('form#sreject_disburse .modal-footer').prepend('<input type="submit" id="reject_db" name="reject_db" value="Reject" class="btn btn-warning">');
//
//}