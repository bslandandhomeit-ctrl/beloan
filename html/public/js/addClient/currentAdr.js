$(document).on('select2:selecting change click', 'tbody.mbody tr td', function (e) {
    "use strict";

    if(typeof countries == 'undefined')return;
    var provinces =  $(this).closest('tbody').find('#provinces'),
        district = $(this).closest('tbody').find('#district'),
        commune  =  $(this).closest('tbody').find('#commune'),
        vill  =  $(this).closest('tbody').find('#vill');
    var pro = '<option value="">-</option>';
    if ($(this).children('select').hasClass('country')) {

        provinces.empty().select2('val','');
        district.empty().select2('val','');
        commune.empty().select2('val','');
        vill.empty().select2('val','');
        var countryId = parseInt($(this).children('select').val());
        $.each(countries.province, function (inx, vals) {

            if(parseInt(vals.count_id) != countryId)return;
            pro += '<option value="' + vals.prov_gis + '" > ' + vals.prov_gis + ' - ' + vals.eng_name + ' </option>';
        });
        provinces.empty().append(pro);
    }
    if ($(this).children('select').hasClass('provinces')) {

        var provCode = $(this).children('select').val().toString();
        commune.select2('val','');
        district.select2('val','');
        vill.select2('val','');

        $.each(countries.distric, function (inx, distr){

            if(distr.distr_gis.substring(0,2) != provCode)return;
            pro += '<option value="' + distr.distr_gis+ '"> ' + distr.distr_gis + ' - ' + distr.eng_name + ' </option>';
        });
        district.empty().append(pro);
    }
    if ($(this).children('select').hasClass('district')) {

        var distrCode = $(this).children('select').val().toString();
        vill.select2('val', '');
        commune.select2('val', '');
        $.each(countries.commune, function (inx, vals) {
            if (vals.comm_gis.substring(0,4) != distrCode)return;
            pro += '<option value="' + vals.comm_gis + '"> '  + vals.comm_gis + ' - '+vals.en_name +' </option>';
        });
        commune.empty().append(pro);
    }
    if ($(this).children('select').hasClass('commune')) {

        var commCode = $(this).children('select').val().toString();
        vill.select2('val','');
        $.each(countries.village, function (inx, vals) {
            if (vals.vill_gis.substring(0,6) != commCode)return;
            pro += '<option value="' + vals.vill_gis+ '"> ' + vals.vill_gis + ' - ' + vals.en_name + ' </option>';
        });
        vill.empty().append(pro);

    }if($(this).children('select').hasClass('addr_types')) {

        var addressTypes = $(this).find('.addr_types').select2('data').element[0].dataset['addtypes'];
        var countryCode = $(this).closest('tbody').find('.country').select2('data').id
        //$('#fields_kh').rules('remove');
        //$('#fields_en').rules('remove');

        if(addressTypes === 'POST' && countryCode === 'KHM') {

            $('.fields_kh').rules( "add", {
                required: true
            });

            $(this).find( ".fields_en" ).rules( "add", {
                required: true
            });
        }
    }if($(this).children('a').hasClass('glyphicon-plus')) {

        var tbodycopy = $(this).closest('.mbody');
        var destroySelect = tbodycopy.find('tr td select');
        destroySelect.each(function(){
            return $(this).select2("destroy");
        });
        var get_clones = tbodycopy.clone(true,true);
        var selectFromClon = get_clones.find('tr td select');
        selectFromClon.each(function() {
            return $(this).select2();
        });
        destroySelect.each(function(){
            return $(this).select2();
        });

        var findPlus = get_clones.find('tr td a.glyphicon-plus');
        if(findPlus.hasClass('glyphicon-plus')){
            get_clones.closest('.mbody').find('tr').removeClass();
            findPlus.removeClass('glyphicon-plus');
            findPlus.addClass('glyphicon-minus');

        }
        $(get_clones).insertAfter(tbodycopy);
    }
    if($(this).children('a').hasClass('glyphicon-minus')) {

        var tbodys = $(this).closest('.mbody');
        var Address_id = parseInt((tbodys.find('tr').attr('class'))?tbodys.find('tr').attr('class'):0);

        if(typeof Address_id != 'undefined'){
            $('#clientForm').append('<input class="addr_id" name="addr_id[]" value="'+Address_id+'" style="display:none"/>');
        }
        tbodys.remove();
    }
});

