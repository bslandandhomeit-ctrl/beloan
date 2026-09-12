/**
 * Created by hengsoheak on 6/21/2016.
 */
function fee_charge_repayment(n_source_id, types) {

    $('<div id="results"></div>').appendTo('body');
    $('<div id="loading"></div>').appendTo('body');

    $.ajax({
        url:'/notification/fee_charge_repayment/' + n_source_id +'/'+ types,
        method  : 'GET',
        dataType: 'Json',
        timeout : 1000000,
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }, beforeSend:function() {
            imgLoading(true, 'Loading...', 15, 'warning');
        },
        data: {id:n_source_id},
        success: function (data, status) {

            if (status === 'success') {
                $('#loading').remove();
                return LoanRepayment(data);
            }
        }
    });
}

function LoanRepayment(data) {

    var tills = null;
    var fee_charge_repayment = null;

    if (!$.isEmptyObject(data.fee_charge_repayment) && !$.isEmptyObject(data.till)) {

        $.each(data.fee_charge_repayment, function (ins, vals) {
            fee_charge_repayment = vals;
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
                    value: fee_charge_repayment.username,
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
                    value: fee_charge_repayment.amount + tills.symbol,
                    disabled:true
                },
            }, textarea: {
                description: { class: 'form-control', name: 'descr', rows: 10, id: 'descr', text: fee_charge_repayment.description }
            }
        };
        Notfification_Modal({
            idSelectors: 'notifications',
            title: 'Notification Types : ' + '<i>' + fee_charge_repayment.n_activity_type + '</i>',
            labels: ['From','To', 'Amount', 'Description'],
            loadType: fee_charge_repayment.n_activity_type,
            keyboard: true,
            backdrop: 'dynamic',
            forms: formElement
        });

        $('form#snotifications .modal-footer').html('<button type="button" class="btn btn-default mclose" data-dismiss="modal">Close</button>');

            $('form#snotifications .modal-footer').prepend('<input type="submit" id="reject_fcrepayment" name="reject_fcrepayment" value="Reject" class="btn btn-warning">');
            $('form#snotifications .modal-footer').prepend('<input type="submit" id="approve_fcrepayment" name="approve_fcrepayment" value="Approve" class="btn btn-success">');

            $('#approve_fcrepayment').click(function () {
                $this = $(this);

                if ($this.is('#approve_fcrepayment')) {
                    $('#loading').remove();
                    if(parseFloat(tills.balance) < parseFloat(fee_charge_repayment.amount) && tills.balance <= 0) {

                        $('<div id="loading"></div>').appendTo('body');
                        imgLoading(true, 'Check your balance', 5, 'warning');

                    }else{

                        var data = {

                            url: '/notification/approve_fee_charge_repayment/' + fee_charge_repayment.teller_till_account_id,
                            till_account_id: fee_charge_repayment.teller_till_account_id,
                            from_account: fee_charge_repayment.username,
                            till_user_id: fee_charge_repayment.n_user_id,
                            branch_id: fee_charge_repayment.branch_id,
                            operate_by: fee_charge_repayment.n_user_id,
                            amount: Number(fee_charge_repayment.amount),
                            type: fee_charge_repayment.n_activity_type,
                            not_id: fee_charge_repayment.not_id,
                            description: $('#descr').val(),
                            currency_id: parseInt(tills.currency_id),
                        };
                        if (confirm("Are you sure to approve?")) {
                            return NotificationSubmit('#snotifications', data);
                        }
                    }
                }
            });

            $("#reject_fcrepayment").click(function () {

                if (confirm("Are you sure to reject?")) {

                    $('#results').remove();
                    $('<div id="loading"></div>').appendTo('body');
                    imgLoading(true,'Loading....',18, 'wraning');
                    ScriptRequire(['/js/load/loan/reject_fee_charge.js'], function (status) {

                        $('<div id="results"></div>').appendTo('body');
                        if (status === 'success') {
                            return reject_fee_charge(fee_charge_repayment, tills);
                        }
                    });
                }
            })
        }
}