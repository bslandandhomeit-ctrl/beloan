$(document).on('click', '.addEm, .collap, .glyphicon-remove', function (e) {

    if ($(this).is('.addEm')) {

        var employerForm = $(this).closest('.cloneEm');
        reinitSelect2($(this).closest(employerForm).find('select'), 'destroy');
        var cloneDiv = employerForm.clone();

        cloneDiv.closest('.cloneEm').find('.glyphicon-plus').removeClass();
        cloneDiv.find('.secondary1').remove();

        cloneDiv.find('.btnEm').append(
            '<a class="btn btn-danger btn-xs glyphicon glyphicon-remove" style="margin-left: 5px !important;"> </a> '
        );

        reinitSelect2($(this).closest(employerForm).find('select'), '');
        reinitSelect2(cloneDiv.find('select'), '');
        cloneDiv.closest('.cloneEm').css({'background-color': 'rgb(250, 250, 250'});
        $('#employerList').append(cloneDiv);
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
    }
    if ($(this).is('.collap')) {

        if ($(this).hasClass('glyphicon-chevron-down')) {

            $(this).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
            $(this).closest('.cloneEm').find('.employerForm').collapse('show');
        } else {

            $(this).removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
            $(this).closest('.cloneEm').find('.employerForm').collapse('hide');
        }
    }
    if ($(this).is('.glyphicon-remove')) {

        var Em_id = parseInt($(this).closest('.cloneEm').find('.panel-body').attr('class').substring(10, 100));

        if (typeof Em_id == 'undefined' || isNaN(Em_id)) {
            Em_id = 0;
        }
        $('#clientForm').append('<input class="Em_id form-control" type="text" name="em_id[]" value="' + Em_id + '" style="display:none"/>');
        $(this).closest('.cloneEm').remove();
    }

});
function reinitSelect2(selectors, act) {
    "use strict";

    selectors.each(function () {
        var opt = act;
        if (!act) {
            var opt = {};
        }
        return $(this).select2(opt);
    });
}

$(document).on('select2:selecting change focus', '.em_country, .em_province, .em_district, .em_commune', function (e) {

    if(typeof countries === 'undefined')return;
    var opt = '<option value="">-</option>';
    var em_province = $(this).closest('.cloneEm').find('.em_province');
    var em_district = $(this).closest('.cloneEm').find('.em_district');
    var em_commune  = $(this).closest('.cloneEm').find('.em_commune');
    var em_village  = $(this).closest('.cloneEm').find('.em_village');
    if ($(this).is('.em_country')) {
        em_province.empty();
        em_district.empty();
        em_commune.empty();
        em_village.empty();

        $.each(countries.province, function (inx, vals) {
            if(vals.count_id)
            opt += '<option value="'+vals.prov_gis+'">'+' '+vals.prov_gis+' _ ' +vals.eng_name + '</option>';
        });
        em_province.append(opt);
    }
    if ($(this).is('.em_province')) {
        em_district.empty();
        em_commune.empty();
        em_village.empty();

        var provCode = em_province.select2("val").toString();
        $.each(countries.distric, function (inx, distr) {
            if(distr.distr_gis.substring(0,2) != provCode)return;
            opt += '<option value="' + distr.distr_gis + '" > ' + distr.distr_gis + ' - ' + distr.eng_name + ' </option>';
        });
        em_district.append(opt);
    }
    if ($(this).is('.em_district')) {

        em_commune.empty();
        em_village.empty();
        var districtCode = em_district.select2("val").toString();
        $.each(countries.commune, function (inx, vals) {
            if(vals.comm_gis.substring(0,4) != districtCode)return;
            opt += '<option value="' + vals.comm_gis + '"> ' + vals.comm_gis + ' - ' + vals.en_name + ' </option>';

        });
        em_commune.append(opt);
    }
    if ($(this).is('.em_commune')) {
        em_village.empty();
        var commCode = em_commune.select2("val").toString();
        $.each(countries.village, function (inx, vals) {
            if(vals.vill_gis.substring(0,6) != commCode)return;
            opt += '<option value="' + vals.vill_gis + '"> ' + vals.vill_gis + ' - ' + vals.en_name + ' </option>';
        });
        em_village.append(opt);
    }
    $('select').select2();
});