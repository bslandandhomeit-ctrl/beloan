/**
 * Created by hengsoheak on 6/22/2016.
 */
function reject_repayments(repayments, tills) {

    //settingForm(repayments, tills);
    //
    //$('#reject_rep').click(function () {

    //if (confirm("Are you sure to reject?")) {
    var data = {
        url: '/notification/rejects_repayment/' + tills.id,
        n_user_id: parseInt(repayments.user_id),
        n_source_id: parseInt(repayments.n_source_id),
        chief_till_account_id: parseInt(repayments.user_id),
        n_activity_type: 'Reject Repayment',
        n_amount: parseFloat(repayments.n_amount),
        n_description: repayments.description,
        teller_till_account_id: parseInt(tills.teller_till_account_id),
        not_id: parseInt(repayments.not_id)
    };
    $('<div id="loading"></div>').appendTo('body');
    return NotificationSubmit('#snotifications', data);
    //}
    //});
}
//function settingForm(repayments, tills) {
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
//                value: repayments.username,
//                disabled: true
//            },
//            To: {
//                type: 'text',
//                name: 'commission_fee',
//                class: 'form-control',
//                Id: 'commission_fee',
//                placeholder: 'commission_fee',
//                style: '',
//                value: tills.name + ' ( ' + tills.account_no + '/' + tills.account_name + ' ) ',
//                disabled: true
//            },
//            Amount: {
//                type: 'text',
//                name: 'amount',
//                class: 'form-control',
//                Id: 'amount',
//                placeholder: '',
//                style: '',
//                value: repayments.amount + '$',
//                disabled: true
//            },
//        }, textarea: {
//            description: {class: 'form-control', name: 'descr', rows: 10, id: 'descr', text: repayments.n_description}
//        }
//    };
//    Notfification_Modal({
//        idSelectors: 'reject_repayment',
//        title: 'Notification Types : ' + '<i>Reject ' + repayments.n_activity_type + '</i>',
//        labels: ['From', 'To', 'Amount', 'Description'],
//        loadType: '',
//        keyboard: false,
//        backdrop: 'dynamic',
//        forms: formElement
//    });
//
//    $('form#sreject_repayment .modal-footer').html('<button type="button" class="btn btn-default mclose" data-dismiss="modal">Close</button>');
//    $('form#sreject_repayment .modal-footer').prepend('<input type="submit" id="reject_rep" name="reject_rep" value="Reject" class="btn btn-warning">');
//    $('#loading').remove();
//}