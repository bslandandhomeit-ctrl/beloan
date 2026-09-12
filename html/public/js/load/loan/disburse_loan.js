function disbursement(n_source_id, types) {
    console.log(types);
    $.ajax({
        url: '/notification/get_disbursement/' + n_source_id + '/' + types,
        method: 'GET',
        dataType: 'Json',
        timeout: 1000000,
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        },
        success: function (data, status) {
            if (status == 'success') {
                return DisursModel(data);
            }
        }
    });
}
function DisursModel(data) {
    if (!$.isEmptyObject(data.disburse)) {

        for (var keys in data.disburse) {
            var disburse = data.disburse[keys];
        }
    }
    if (!$.isEmptyObject(data.till)) {

        for (var keys in data.till) {
            var till = data.till[keys];
        }
    }
    if (!$.isEmptyObject(data.commission)) {

        for (var keys in data.commission) {
            if(data.commission[keys].trans_type){
                if (data.commission[keys].trans_type.localeCompare('Disbursement') == 0) {
                    var commissions = data.commission[keys];
                }
            }
        }
    }



    if (!$.isEmptyObject(disburse) && !$.isEmptyObject(till) && !$.isEmptyObject(commissions)) {

        settingForm(disburse, commissions, till);
    }
    $("#approve_db").click(function () {

        var data = {
            url: '/notification/ApproveDisburse/' + disburse.teller_till_account_id, //till
            id: parseInt(till.id),
            remind_balance: Number(till.balance) - Number(disburse.loan_amount)
        };

        $('#loading').remove();
        // if (parseFloat(data.remind_balance) < 0 && parseFloat(till.balance) <= 0) {

        //     $('<div id="loading"></div>').appendTo('body')
        //      imgLoading(true, 'Check your balance', 5, 'warning');
        // }else {

            data.balancePlusCommision = Number(data.remind_balance) + Number((disburse.charge_amount)?disburse.charge_amount:0);
            //Insert data to Till Transaction
            data.till_account_id = parseInt(till.teller_till_account_id);
            data.from_account = disburse.name.trim();
            data.to_account = till.account_name.trim();
            data.till_user_id = parseInt(till.n_user_id);
            data.branch_id = parseInt(till.branch_id);
            data.operate_by = parseInt(till.assign_user_id);
            data.type = till.n_activity_type.trim();
            data.cash_in = Number(disburse.charge_amount);
            data.cash_out = Number(disburse.loan_amount);
            data.not_id = Number(disburse.not_id);
            data.currency_id = parseInt(till.currency_id);
            data.drawdown_acc = disburse.drawdown_acc.trim();

            if (confirm("Are you sure to approve?")) {
                $('<div id="loading"></div>').appendTo('body');
                return NotificationSubmit('#snotifications', data);
            }
        // }

    });
    $("#reject_db").click(function () {

        if (confirm("Are you sure to reject?")) {

            $('#results').remove();
            ScriptRequire(['/js/load/loan/reject_disburse.js'], function (status) {
                if (status === 'success') {

                    $('<div id="results"></div>').appendTo('body');
                    return reject_disburse(disburse, commissions, till);
                }
            });
        }
    })
}

function settingForm(disburse, commissions, till) {

    var formElement = {};
    formElement = {
        input: {
            from: {
                type: 'text',
                name: 'tran_amount',
                class: 'form-control',
                Id: 'tran_amount',
                placeholder: '',
                style: '',
                value: disburse.username
            },
            Amount: {
                type: 'text',
                name: 'amount',
                class: 'form-control',
                Id: 'amount',
                placeholder: '',
                style: '',
                value: disburse.loan_amount + '$'
            },
            commissionFee: {
                type: 'text',
                name: 'commission_fee',
                class: 'form-control',
                Id: 'commission_fee',
                placeholder: 'commission_fee',
                style: '',
                value: (commissions.charge_amount)?commissions.charge_amount:0 + '$'
            },
            Till_account: {
                type: 'text',
                name: 'commission_fee',
                class: 'form-control',
                Id: 'commission_fee',
                placeholder: 'commission_fee',
                style: '',
                value: till.name + ' ( ' + till.account_no + '/' + till.account_name + ' ) ',
                disabled: true
            },
            drawdown_acc: {
                type: 'hidden',
                name: 'drawdown_acc',
                class: 'form-control',
                Id: 'drawdown_acc',
                placeholder: 'Drawdown Account',
                style: '',
                value: disburse.drawdown_acc,
            }
        }, textarea: {
            description: {class: 'form-control', name: 'descr', rows: 10, id: 'descr', text: commissions.description}
        }
    };
    Notfification_Modal({
        idSelectors: 'notifications',
        title: 'Notification Types : ' + '<i>' + disburse.n_activity_type + '</i>',
        labels: ['From', 'Loan Amount', 'Commission Fee', 'Till_account', 'Description'],
        loadType: disburse.n_activity_type,
        keyboard: true,
        backdrop: 'dynamic',
        forms: formElement
    });
    $('form#snotifications .modal-footer').html('<button type="button" class="btn btn-default mclose" data-dismiss="modal">Close</button>');
    $('form#snotifications .modal-footer').prepend('<input type="submit" id="reject_db" name="reject_db" value="Reject" class="btn btn-warning">');
    $('form#snotifications .modal-footer').prepend('<input type="submit" id="approve_db" name="approve_db" value="Approve" class="btn btn-success">');
}