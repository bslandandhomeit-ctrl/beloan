/**
 * Created by hengsoheak on 6/22/2016.
 */
function change_till_account(n_source_id, not_id) {

    CallFormAndTellerData(function (data, status) {

        $('<div id="results"></div>').appendTo('body');
        if (status === 'success') {
            $('#loading').remove();

            var teller = {};
            $(document).on('change', '#tellerSel', function (event) {
                event.preventDefault();

                var Seld_currency_id = parseInt($('#tellerSel option:selected').attr('data-currency'))
                var Seld_user_id = Number($('#tellerSel option:selected').attr('selected', true).val());

                for (var keys in data.teller) {
                    var vals = data.teller[keys];
                    if (parseInt(vals.currency_id) === parseInt(Seld_currency_id) && parseInt(Seld_user_id) === parseInt(vals.assign_user_id)) {
                        teller = vals;
                    }
                }
            });

            $('#change').click(function (e) {
                e.preventDefault();

                if (!$.isEmptyObject(teller)) {

                    var data = {
                        url: '/notification/change_till_account/' + teller.assign_user_id,
                        teller_till_account_id  : parseInt(teller.till_id),
                        n_user_id               : parseInt(teller.assign_user_id),
                        not_id                  : parseInt(not_id),
                        n_source_id             : parseInt(n_source_id),
                        discription             : $('#descr').val(),
                    };

                    if (confirm("Are you sure to change?")) {
                        $('<div id="loading"></div>').appendTo('body');
                        return NotificationSubmit('#snotifications', data);
                    }
                }
            });
        }
    });
}

function CallFormAndTellerData(callback) {

    $('<div id="loading"></div>').appendTo('body');
    $.ajax({
        url: '/teller/getTeller',
        method: 'get',
        dataType: 'json',
        timeout: 10000,
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        },
        beforeSend: function () {
            imgLoading(true, 'Loading!!!', 3, 'waring');
        },
        success: function (data, status) {

            var teller = '';
            if (!$.isEmptyObject(data.teller) && status === 'success') {

                $.each(data.teller, function (ins, vals) {

                    teller += '<option value="' + vals.assign_user_id + '" data-currency="' + vals.currency_id + '"> ' + vals.username + ' ( ' + vals.account_no + ' / ' + vals.account_name + ') </option>';
                });

                CallForm(teller);
                return callback(data, status);
            }
        }, error: errorCallback
    });
}

function CallForm(teller) {

    var formElement = {
        input: {
            selection: {
                Teller: {
                    option: teller,
                    class: 'form-control',
                    name: 'tellerSel',
                    id: 'tellerSel'
                }
            },
        },
        textarea: {
            description: {class: 'form-control', name: 'descr', rows: 10, id: 'descr', text: ''}
        }
    };
    Notfification_Modal({
        idSelectors: 'reject_disburse',
        title: 'Notification Types : ' + '<i> CHANGE TILL ACCOUNT </i>',
        labels: ['Till Account', 'Description'],
        loadType: '',
        keyboard: false,
        backdrop: 'dynamic',
        forms: formElement
    });
    $('form#sreject_disburse .modal-footer').html('<button type="button" class="btn btn-default mclose" data-dismiss="modal">Close</button>');
    $('form#sreject_disburse .modal-footer').prepend('<input type="submit" id="change" name="change" value="Change" class="btn btn-warning">');
}