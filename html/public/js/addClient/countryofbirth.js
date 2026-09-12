
$(document).on('change focus', '#country_of_birth, #province_of_birth, #district_of_birth, #commune_of_birth', function (e) {


    var provinces = $('#province_of_birth'),
        district = $('#district_of_birth'),
        commune = $('#commune_of_birth'),
        village = $('#village_of_birth');
    var opt = '<option value="">-</option>';
    var countryCode = $('#country_of_birth').select2('data').element[0].dataset['code'];
    $('input[name=place_of_birth_adds]').attr('disabled', true);

    if ($(this).is('#country_of_birth')) {

        provinces.select2('val', '');
        district.select2('val', '');
        commune.select2('val', '');
        village.select2('val', '');

        $.each(countries.province, function (inx, vals) {
            opt += '<option value="'+vals.prov_gis+'">'+' '+vals.prov_gis+' _ ' +vals.eng_name + '</option>';
        });
        if(countryCode != 'KHM') {
            $('input[name=place_of_birth_adds]').attr('disabled', false);
        }
        provinces.empty().append(opt);
    }
    if($(this).is('#province_of_birth')){

        district.select2('val', '');
        commune.select2('val', '');
        village.select2('val', '');
        var provCode = $("#province_of_birth").select2("val").toString();
        $.each(countries.distric, function (inx, vals) {
            if(provCode != vals.distr_gis.substring(0,2))return;
            opt += '<option value="'+vals.distr_gis+'">  '+''+vals.distr_gis+' - ' +vals.eng_name + '</option>';
        });
        district.empty().append(opt);
    }
    if ($(this).is('#district_of_birth')) {

        village.select2('val', '');
        var dis_code = $("#district_of_birth").select2("val").toString();
        $.each(countries.commune, function (inx, vals) {
            if(dis_code != vals.comm_gis.substring(0,4))return;
            opt += '<option value="'+vals.comm_gis+'"> '+' '+vals.comm_gis+' - ' +vals.en_name + '</option>';
        });
        commune.empty().append(opt);
    }
    if ($(this).is('#commune_of_birth')) {

        village.select2('val', '');
        var comm_code = $("#commune_of_birth").select2("val").toString();
        $.each(countries.village, function (inx, vals) {

            if(comm_code != vals.vill_gis.substring(0,6))return;
            opt += '<option value="'+vals.vill_gis+'">'+'  '+vals.vill_gis+' - ' +vals.en_name + '</option>';
        });
        village.empty().append(opt);
    }
});



