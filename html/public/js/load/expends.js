/**
 * Created by heng soheak on 5/30/2016.
 */
function expense(){

    $('<div id="result"></div>').appendTo('body');
    $('<div id="loading"></div>').appendTo('body');
    loadModale({
        idSelector: 'expense',
        title: 'Expense ',
        keyboard: 'dynamic',
        backdrop: 'static',
        labels: ['Expense type ', "Till Account", 'Amount', 'Description'],
        forms: {
            input: {
                expense_Type: {
                    type: 'text',
                    name: 'exp_type',
                    class: 'form-control',
                    Id: 'exp_type',
                    placeholder: '',
                    style: '',
                    value: ''
                },
                selection: {
                    till_account: {
                        class: 'form-control',
                        name: 'till_account',
                        id: 'till_account'
                    }
                },
                amount: {
                    type: 'text',
                    name: 'exp_amount',
                    class: 'form-control',
                    Id: 'exp_amount',
                    placeholder: '',
                    style: '',
                    value: ''
                }

            }, textarea: {
                description: {class: 'form-control', name: 'exp_descr', rows: 10, id: 'exp_descr'}
            }
        }, script: [
            '/theme/js/jquery.validate.min.js'
        ]
    });
    AjaxCallback('/teller/get_till_account', '', '', function (data) {
        var items = '';
        var till_data = null;
        $.each(data.till, function (ins, vals) {
            items += '<option value="' + vals.till_id + '">' + vals.name + ' ( ' + vals.account_name + ' / ' + vals.account_no + ' ) </option>';
        });
        $('#till_account').on('change', function () {

            var till_id = $('#till_account option:selected').val();
            $.each(data.till, function (ins, vals) {

                if(parseInt(till_id) === parseInt(vals.till_id)){
                    till_data = vals;
                }
            });
        });
        $('#exp_amount').on('keyup', function () {
            var amount = $('#exp_amount').val();

            if (parseFloat(till_data.balance) < parseFloat(amount)) {
                alert('You don\'t have enough balance !!!');
                $(this).val(0)
            }
        });
        $(items).appendTo('#till_account');
    });
    $('#loading').remove()


    var $this = $('#expend');
    $this.find('.modal-footer').children('input[type=submit]').remove();

        var addSubmit = $('#expend').find('.modal-footer').children();
        $('<input type="submit" value="Submit" class="btn btn-info exp_submit" >').insertBefore(addSubmit);
        $('#sexpense').validate({
            rules: {
                exp_type: {
                    required: true
                },
                exp_amount: {
                    required: true,
                    number: true
                },
                exp_descr: {
                    required: true
                }, till_account: {
                    required: true
                }
            },
            submitHandler: function () {

                $('#loading').remove();
                if (confirm('Are you sure?')) {
                    $.ajax({
                        url: '/teller/post_expense',
                        method: 'post',
                        dataType: 'json',
                        timeout: 100000,
                        data: {
                            _token: $('meta[name="_token"]').attr('content'),
                            type: $('input[name=exp_type]').val(),
                            cash_out: parseFloat($('input[name=exp_amount]').val()),
                            description: parseFloat($('#exp_descr').val()),
                            till_account_id: $('#till_account option:selected').val(),
                        },
                        success: function (data, status) {

                            if (status === 'success') {
                                $('<div id="loading"></div>').appendTo('body');
                                if(data.up_bal){

                                    imgLoading(true,'successfully!!!', 5, status);
                                    $('#result').remove()
                                }else{
                                    imgLoading(true,'Please try again!!!', 5, 'warning');
                                }
                            }
                        }
                    });
                }
            }

        });
}


function AjaxCallback(url, id, currency_id, callback) {
    $.ajax({
        url: url,
        method: 'get',
        dataType: 'json',
        timeout: 10000,
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        },
        success: function (data, status) {
            return callback(data);
        }
    });
}
$(document).on("click", ".mclose,.modal-backdrop", function () {
    return clearLoad();
});
function clearLoad() {
    $("#result").remove();
}