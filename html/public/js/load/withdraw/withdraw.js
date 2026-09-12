/**
 * Created by hengsoheak on 7/8/2016.
 */
function withdraw(not_id, flag) {
    $('#result').remove();
    $('#loading').remove();
    $('<div id="result"></div>').appendTo('body');
    $('<div id="loading"></div>').appendTo('body');

    Notfification_Modal({
        idSelectors: 'expense',
        title: '',
        labels: [],
        loadType: 'withdraw',
        keyboard: true,
        backdrop: 'dynamic',
        //forms: formElement,
        sms: 'Are you sure?'
    });
    $('form#sexpense .modal-footer').prepend('<input type="submit" id="ok" name="ok" value="OK" class="btn btn-warning">');
            
    $('#ok').on("click", function (e) {
        if ($(this).is('#ok')) {
            $(this).attr('disabled',true);
            var input = '?not_id=' + not_id;
            input += '&flag_button=' + flag;
            $.ajax({
                url: '/teller/post_withdraw'+input,
                method: 'post',
                dataType: 'json',
                cache: false,
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                },
                success: function (data, status) {
                    if (status === 'success') {
                        var data = {
                            url: '/teller/delete_notification/' + not_id
                        };
                        NotificationSubmit('', data);
                        return status;
                    }
                }, error: errorCallback
            });
        }
    });
}