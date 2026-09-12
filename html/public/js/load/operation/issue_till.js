/**
 * Created by hengsoheak on 6/28/2016.
 */
function issueTill_for_chief(issueTill) {

    $('#loading').remove();
    Ajaxs('/teller/issueTill', 'get', 'json', {}, function (data) {

        if (!$.isEmptyObject(data.coa) && !$.isEmptyObject(data.loginchief)) {
            chief(data);
        } else if (!$.isEmptyObject(data.nologchief) && !$.isEmptyObject(data.teller)) {
            teller(data);
        } else {
            imgLoading(true, 'Till account was closed', 4, 'warning');
        }
    });
}

function chief(data) {

    var coa = '';
    var ch_vals = {};
    var chief_opt = '';

    $.each(data.loginchief, function (inx, vals) {
        chief_opt += '<option value="' + vals.currency_id + '"> ' + vals.username + ' ( ' + vals.account_no + '/' + vals.account_name + ') </option>';
    });

    var forms = {
        date_picker:{
          dpDate:{
            type: 'text',
            name:'till_date',
            id: 'till_date',
            value:getNowTime()
          }
        },
        input: {

            selection: {
                'Till account': {opt: chief_opt, class: 'select2', name: 'chief', id: 'chief'},
            },
            coa: {
                type: 'text',
                name: 'coa',
                class: 'form-control',
                Id: 'coa',
                placeholder: '',
                style: '',
                value: '',
                disabled: true
            },
            Amounts: {
                type: 'text',
                name: "amount",
                class: 'form-control',
                Id: 'amount',
                placeholder: "",
                style: "",
                value: ""
            }
        },
        textarea: {
            description: {class: 'form-control', name: 'descr', rows: 10, id: 'descr'}
        }
    };
    createForm(data, forms);

    $(document).on('change', '#chief', function (e) {
        e.preventDefault();

        var currency_id = parseInt($(this).val());
        $('#coa').val('There is no account');
        disableBtn(true);
        $.each(data.coa, function (inx, vals) {
            if (parseInt(currency_id) === parseInt(vals.currency)) {
                $.each(data.loginchief, function (inx, ch_val) {
                    if (parseInt(currency_id) === parseInt(ch_val.currency_id)) {
                        if (parseInt(ch_val.tillstatus) === 1) {
                            imgLoading(true, 'This till account was closed', 5, 'warning');
                            return;
                        }
                        ch_vals = ch_val;
                        $('#coa').val(vals.name + ' / ' + vals.account_code);
                        disableBtn(false);
                    }
                });
            }
        });
    });
    $("#sissueTill").validate({

        rules: {
            chief: {
                required: true
            },
            to_teller: {
                required: true
            }, amount: {
                required: true
            }
        }, submitHandler: function () {

            if (confirm('Are you sure to issue\n till with amount ' + parseFloat($('input[name=amount]').val()) + '$ ?')) {
                $('<div id="result"></div>').appendTo('body');
                $('<div id="loading"></div>').appendTo('body');
                $('input[type=submit]').attr('disabled',true);
                var data = {
                    till_account_id: parseInt(ch_vals.till_account_id),
                    from_acc: $('input[name=coa]').val(), /// cash in vault and on hand
                    to_acc: $('#chief option:selected').attr('selected', true).text(), // from chief
                    branch_id: parseInt(ch_vals.branch_id),
                    operate_by: parseInt(ch_vals.created_by),
                    type: 'Issue Till',
                    cash_in: parseFloat($('input[name=amount]').val()),
                    balance: (parseFloat(ch_vals.balance) + parseFloat($('input[name=amount]').val())),
                    currency_id: parseInt(ch_vals.currency_id),
                    descr: $("#descr").val(),
                    _token: $('meta[name="_token"]').attr('content'),
                    till_date: $('#till_date').val()
                };
                //console.log(data);
                Ajaxs('/teller/issue_till_post', 'Post', 'json', data, function (data, status) {

                    if (status == 'success') {
                        if (data.res == false) {
                            imgLoading(true, "We can't save your info", 8, 'no');
                        }
                        if (data.in == false && data.up == false) {
                            imgLoading(true, "We can't save your info", 8, 'no');
                        } else {
                            imgLoading(true, "Successfully !!!", 2, status);
                            $("#result").empty();
                            setTimeout(function(){ 
                                location.reload();
                            }, 500);
                        }
                    }
                });
            }
        }
    });

    $('#amount').on('keyup', function () {

        var amount = parseFloat($('#amount').val());
        $('#loading').remove();
        if (parseFloat(amount) > parseFloat(ch_vals.max_balance)) {

            $('<div id="loading"></div>').appendTo('body');
            imgLoading(true, 'You can\' issue over your maximum balance!!!', 5, 'warning');
            return $('input[type=submit]').attr('disabled', true)
        } else {

            $('input[type=submit]').attr('disabled', false)
        }
    });
}

function teller(data) {

    var chief_opt = '';
    var teller_data = null;
    var chief_data = null;
    $.each(data.nologchief, function (inx, vals) {
        chief_opt += '<option value="' + vals.currency_id + '"> ' + vals.username + ' ( ' + vals.account_no + '/' + vals.account_name + ') </option>';
    });

    var forms = {
      date_picker:{
        dpDate:{
          type: 'text',
          name:'till_date',
          id: 'till_date',
          value:""
        }
      },
        input: {
            selection: {
                from: {opt: chief_opt, class: 'select2', name: 'chief', id: 'chief'}
            },
            To: {
                type: 'text',
                name: "to_teller",
                class: 'form-control',
                Id: 'to_teller',
                placeholder: "",
                style: "",
                value: "",
                disabled: true
            },
            Amounts: {
                type: 'text',
                name: "amount",
                class: 'form-control',
                Id: 'amount',
                placeholder: "",
                style: "",
                value: ""
            }
        },
        textarea: {
            description: {class: 'form-control', name: 'descr', rows: 10, id: 'descr'}
        }

    };
    loadModale({
        idSelector: 'issueTill',
        title: 'Issue Till',
        labels: ['Date', 'From', 'To', 'Amount', 'description'],
        forms: forms,
        script: [
            '/theme/js/jquery.validate.min.js',
            '/theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',
            '/js/my_custom_js.js',
            '/theme/js/select2/select2.js'
        ]
    });
    $('#loading').remove();
    $(document).on('change', '#chief', function (e) {

        var currency_id = parseInt($(this).val());
        $('#to_teller').val('There is no account');
        disableBtn(true);

        $.each(data.teller, function (inx, vals) {

            if (parseInt(currency_id) === parseInt(vals.currency_id)) { // if teller currency_id equal to chief currency_id

                teller_data = vals;
                $('#to_teller').val(vals.name + ' ( ' + vals.account_name + ' / ' + vals.account_no + ' ) '); //send teller account to form
                $('#amount').val(accounting.formatMoney(vals.balance));
                disableBtn(false);
            }
        });
        $.each(data.nologchief, function (inx, vals) {

            if (parseInt(currency_id) === parseInt(vals.currency_id)) {
                chief_data = vals;
            }
        });
    });

    $('#sissueTill').validate({
        rules: {
            chief: {
                required: true
            },
            to_teller: {
                required: true,
                number: true
            },
            amount: {
                required: true
            }, descr: {
                required: true
            }
        },
        submitHandler: function () {
            if (confirm('Are you sure to issue\n till with amount ' + accounting.formatMoney($('input[name=amount]').val()) + ' ?')) {

                var data = {
                    chief_user_id: parseInt(chief_data.chief_user_id),
                    teller_till_account_id: parseInt(teller_data.tell_till_account_id),
                    chief_till_account_id: parseInt(chief_data.chief_till_id),
                    from_account: $('#chief option:selected').text(),
                    to_account: $('#to_teller').val(),
                    branch_id: teller_data.branch_id,
                    type: 'Issue Till',
                    cash_in: parseFloat($('#amount').val()),
                    tell_balance: parseFloat($('#amount').val()) + parseFloat(teller_data.balance),
                    currency_id: parseInt(teller_data.currency_id),
                    description: $('#descr').val(),
                    _token: $('meta[name="_token"]').attr('content'),
                    till_date: $('#till_date').val()
                };

                Ajaxs('/teller/issue_till_post', 'Post', 'json', data, function (data, status) {

                    if (status == 'success') {
                        if (data.res == false) {
                            imgLoading(true, "We can't save your info", 8, 'no');
                        }
                        if (data.in == false && data.up == false) {
                            imgLoading(true, "We can't save your info", 8, 'no');
                        } else {
                            imgLoading(true, "Successfully !!!", 2, status);
                            $("#result").empty();
                        }
                    }
                });
            }
        }
    });
    $('#amount').on('keyup', function () {

        var amount = parseFloat($('#amount').val());
        $('#loading').remove();
        if (parseFloat(amount) > parseFloat(teller_data.max_balance)) {

            $('<div id="loading"></div>').appendTo('body');
            imgLoading(true, 'You can\' issue over your maximum balance!!!', 5, 'warning');
            return $('input[type=submit]').attr('disabled', true)
        } else {

            $('input[type=submit]').attr('disabled', false)
        }
    });
}

function createForm(data, forms) {

    loadModale({
        idSelector: 'issueTill',
        title: 'Issue Till',
        labels: ['Date', 'Till Account', 'From', 'Amount', 'description'],
        forms: forms,
        script: [
            '/theme/js/jquery.validate.min.js',
            '/theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',
            '/js/my_custom_js.js',
            '/theme/js/select2/select2.js',
        ]
    });
    $('#loading').remove();
}
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
