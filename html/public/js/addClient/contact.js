$(document).on('click', '.glyphicon-plus,.glyphicon-minus', function () {

    if ($(this).is('.glyphicon-plus')) {
        var copyDiv = $(this).closest('.copy');
        var destroySelect2 = copyDiv.find('select');
        reinitSelect2(destroySelect2,'destroy');

        var cloneDiv = copyDiv.clone();
        var selections = cloneDiv.find('i.glyphicon-plus');
        var findSelect = cloneDiv.find('select');

        reinitSelect2(destroySelect2,'');
        reinitSelect2(findSelect,'');

        if (selections.hasClass('glyphicon-plus')) {
            selections.removeClass('glyphicon-plus').removeClass('btnCopy');
            cloneDiv.closest('.copy').find('tr').removeClass();
            selections.addClass('glyphicon-minus');
        }
        $(cloneDiv).insertAfter(copyDiv);
    }
    if ($(this).is('.glyphicon-minus')) {
        $(this).closest('.copy').remove();

        var cnt_id = parseInt($(this).closest('.copy').find('tr').attr('class'));
        if(typeof cnt_id === 'undefined' || isNaN(cnt_id))return
        $('#clientForm').append('<input class="cnt_id" name="cnt_id[]" value="'+cnt_id+'" style="display:block"/>');
        console.log(cnt_id)
    }
});

function reinitSelect2(selectors,act) {
    "use strict";

    selectors.each(function(){
        var opt = act;
        if(!act) {
            var opt =  {};
        }
        return $(this).select2(opt);
    });
}

$(document).on('select2:selecting change focus', '.contact_number_type', function () {

    $(this).closest('tbody.copy').find('.ext_code').prop('disabled', true);
    if ($(this).is('.contact_number_type') && $(this).select2('val') == 'O') {

        $(this).closest('tbody.copy').find('.ext_code').prop('disabled', false)
    }

});