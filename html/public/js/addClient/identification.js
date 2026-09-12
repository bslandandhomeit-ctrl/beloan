$(document).on('click', 'table.indent tbody tr td a', function () {

    if ($(this).hasClass('glyphicon-plus')) {

        var copyDiv = $(this).closest('.copyId');
        var destroySelect2 = copyDiv.find('select');
        reinitSelect2(destroySelect2,'destroy');

        var cloneDiv = copyDiv.clone();
        var selections = cloneDiv.find('.glyphicon-plus');
        var findSelect = cloneDiv.find('select');

        reinitSelect2(destroySelect2,'');
        reinitSelect2(findSelect,'');

        if (selections.hasClass('glyphicon-plus')) {
            cloneDiv.closest('.copyId').find('tr').removeClass();
            selections.removeClass('glyphicon-plus').removeClass('indent');
            selections.addClass('glyphicon-minus');
        }
        $(cloneDiv).insertAfter(copyDiv);
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
    }
    if ($(this).hasClass('glyphicon-minus')) {

        $(this).closest('.copyId').remove();
        var Iden_id = parseInt($(this).closest('.copyId').find('tr').attr('class'));
        if(typeof Iden_id === 'undefined'|| isNaN(Iden_id))return;
        $('#clientForm').append('<input class="Iden_id" name="iden_id[]" value="'+Iden_id+'" style="display:block"/>');
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
