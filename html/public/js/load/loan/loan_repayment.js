/**
 * Created by hengsoheak on 6/21/2016.
 */

function Repayment_loan(n_source_id, types) {

    $('<div id="results"></div>').appendTo('body');
    $('<div id="loading"></div>').appendTo('body');
    $.ajax({
        url: '/notification/repayment_loan_data/' + n_source_id + '/' + types,
        method: 'GET',
        dataType: 'Json',
        timeout: 1000000,
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        },beforeSend:function(){
            imgLoading(true, 'Loading...', 15, 'warning');
        },
        data: {id: n_source_id},
        success: function (data, status) {
            if (status === 'success') {
                $('#loading').remove();
                return LoanRepayment(data);
            }
        }
    });
}

function LoanRepayment(data) {

    var tills = data.till;
    var repayments = data.repayment;
    if (typeof repayments != 'undefined' && tills != 'undefined' && !$.isEmptyObject(repayments) && !$.isEmptyObject(tills)) {

        $.each(data.repayment, function (ins, vals) {
            repayments = vals;
        });
        $.each(data.till, function (ins, vals) {
                tills = vals;
        });
        formElement = {
            input: {
                from: {
                    type: 'text',
                    name: 'tran_amount',
                    class: 'form-control',
                    Id: 'tran_amount',
                    placeholder: '',
                    style: '',
                    value: repayments.username,
                    disabled:true
                },To : {
                    type: 'text',
                    name: 'tran_amount',
                    class: 'form-control',
                    Id: 'tran_amount',
                    placeholder: '',
                    style: '',
                    value: tills.name + ' ( ' + tills.account_no + '/' + tills.account_name + ' ) ',
                    disabled:true
                },
                Amount: {
                    type: 'text',
                    name: 'amount',
                    class: 'form-control',
                    Id: 'amount',
                    placeholder: '',
                    style: '',
                    value: repayments.amount + tills.symbol,
                    disabled:true
                },
            }, textarea: {
                description: { class: 'form-control', name: 'descr', rows: 10, id: 'descr', text: repayments.description }
            }
        };
        Notfification_Modal({
            idSelectors: 'notifications',
            title: 'Notification Types : ' + '<i>' + repayments.n_activity_type + '</i>',
            labels: ['From','To', 'Amount', 'Description'],
            loadType: repayments.n_activity_type,
            keyboard: true,
            backdrop: 'dynamic',
            forms: formElement
        });

        $('form#snotifications .modal-footer').html('<button type="button" class="btn btn-default mclose" data-dismiss="modal">Close</button>');
        if (repayments.n_activity_type === "Loan Repayment") {

            $('form#snotifications .modal-footer').prepend('<input type="submit" id="reject_repayment" name="reject_repayment" value="Reject" class="btn btn-warning">');
            $('form#snotifications .modal-footer').prepend('<input type="submit" id="approve_repayment" name="approve_repayment" value="Approve" class="btn btn-success">');
            $('#approve_repayment').click(function () {
                $this = $(this);

                if ($this.is('#approve_repayment')) {
                    $('#loading').remove();
                    if(parseFloat(tills.balance) < parseFloat(repayments.amount) && parseFloat(tills.balance) <= 0 ){

                        $('<div id="loading"></div>').appendTo('body')
                        imgLoading(true, 'Check your balance', 5, 'warning');
                    }else{
                        var data = {

                            url: '/notification/approve_repayments/' + repayments.teller_till_account_id,
                            till_account_id: repayments.teller_till_account_id,
                            from_account: repayments.username,
                            till_user_id: repayments.n_user_id,
                            branch_id: repayments.branch_id,
                            operate_by: repayments.n_user_id,
                            amount: Number(repayments.amount),
                            type: repayments.n_activity_type,
                            not_id: repayments.not_id,
                            description: $('#descr').val(),
                            currency_id: parseInt(tills.currency_id),
                        };
                        if (confirm("Are you sure to approve?")) {
                            return NotificationSubmit('#snotifications', data);
                        }
                    }

                }
            });
            $("#reject_repayment").click(function () {

                if (confirm("Are you sure to reject?")) {

                    $('#results').remove();
                    $('<div id="loading"></div>').appendTo('body');
                    imgLoading(true,'Loading....',18, 'wraning');
                    ScriptRequire(['/js/load/loan/reject_repayments.js'], function (status) {

                        $('<div id="results"></div>').appendTo('body');
                        if (status === 'success') {
                            return reject_repayments(repayments, tills);
                        }
                    });
                }
            })
        }
    }
}
