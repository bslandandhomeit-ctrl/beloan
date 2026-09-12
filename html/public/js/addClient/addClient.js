/**
 * Created by hengsoheak on 11/10/2016.
 */

function call_and_delete_Loading(loading,sms, status){
    $('body').find(loading).each(function(){
        $(this).remove();
    });
    var stat =  (status)?status:'warning';
    if(sms != undefined) {
        $('<div id="loading"></div>').appendTo('body');
        imgLoading(true,sms, 9, stat);
    }
}

function AjaxCallBack(url, method, dataTypes, data, modals, callBack) {

    call_and_delete_Loading('#loading','Loading.....');
    var postData = {
        _token:$('meta[name="_token"]').attr('content')
    };
    if (!$.isEmptyObject(data)) {
        postData = data;
    }
    var request = $.ajax({
        url: url,
        method: method,
        dataType: dataTypes,
        timeout: 10000,
        cache: false,
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        },
        contentType: false,
        processData: false,
        data: postData,
        success: function (data, status) {

            if(status !== 'success')return call_and_delete_Loading('#loading', 'Please try again', 'warning');
            if($.isEmptyObject(data) === false && typeof data !== 'object') {//data parse as html
                $(data).appendTo('body');
                call_and_delete_Loading('#loading');

                return call_and_delete_Loading('#loading', 'Successfully!!!', status);
            }
            return callBack(data, status);
        }
    });
    request.error(function(httpObj, textStatus) {
        if(httpObj.status == 401 || httpObj.responseText === 'Unauthorized.') {
            return call_and_delete_Loading('#loading', 'Your session was expired. Please refresh this page to login again', 'warning');
        }
    });
}

$(document).ready(function(){

    $('select').select2({});
    $('.dpYears').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        setDate: new Date()
    });
    var dates = new Date();
    var getcurrYear = dates.getFullYear();
    $('input[name=date_of_birth]').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date(),
            // startDate: parseInt(getcurrYear)-70+'-'+'01'+'-'+01,
            // endDate: parseInt(getcurrYear)-18+'-'+'01'+'-'+01
    });


    $('ul.nav-tabs li a').click(function (e) {
        var scrollHeight = $(document).height();
        $(this).tab('show');
        setTimeout(function() {

            var body = $("html, body");
            body.stop().animate({scrollTop:scrollHeight}, '9000', 'linear', function() {//toggle,swing, linear
            });

        },1);
    });
});


$('#clientForm').validate({
     ignore: "",
     rules : {
         gender:{required:true},
         birth_date:{required:true},
         nationality:{required:true},
         family_name_kh:{required:true},
         salutation:{required:true},
         //resident:{required:true},
         country_id:{required:true},
         province_Id:{required:true},
         address_type:{required:true},
         employer_type:{required:true},
         employer_name:{required:true},
         employer_name_kh:{required:true},
         employer_address:{required:true},
         date_of_birth:{required:true},
         family_name_en:{required:true},
         first_name_en:{required:true},
         second_name:{required:true},
         first_name_kh:{required:true},
         marital_status:{required:true},
         national_code:{required:true},
         applicant_type:{required:true},
         id_type_id:{required:true},

         national_code:{required:true},
         country_of_birth:{required:true},
         province_of_birth:{required:false},
         district_of_birth:{required:false},
         commune_of_birth:{required:false},
         village_of_birth:{required:false},
        // 'province_of_birth': {
        //     required : function(e){
        //         if($('#country_of_birth').val() != 36){
        //             return false;
        //         } 
        //         return true;
        //     }
        // },
        // 'district_of_birth':{
        //     required : function(e){
        //         if($('#country_of_birth').val() != 36){
        //             return false;
        //         } 
        //         return true;
        //     }
        // },
        // 'commune_of_birth':{
        //     required : function(e){
        //         if($('#country_of_birth').val() != 36){
        //             return false;
        //         } 
        //         return true;
        //     }
        // },
        // 'village_of_birth':{
        //     required : function(e){
        //         if($('#country_of_birth').val() != 36){
        //             return false;
        //         } 
        //         return true;
        //     }
        // },
        officer_id:{required:true},
         // photo:{required:true},
         // signature:{required:true},

         'id_type_id[]':{required:true},
         'id_number[]':{required:true},
         // 'issued_date[]':{required:true},
         // 'issued_by[]':{required:true},
         // 'id_expiry_date[]':{required:true},
         //'self_employed[]':{required:true},
         'contact_number_type[]':{required:true},
         'contact_number_country_code[]':{required:true},
         'contact_number_number[]':{required:true},
         'country[]':{required:true},
         // 'provinces[]':{required:true},
         // 'district[]':{required:true},
         // 'commune[]':{required:true},
         // 'villages[]':{required:true},
        'provinces[]': {
            required : function(e){
                if($('#countryn').val() != 36){
                    return false;
                } 
                return true;
            }
        },
        'district[]':{
            required : function(e){
                if($('#countryn').val() != 36){
                    return false;
                } 
                return true;
            }
        },
        'commune[]':{
            required : function(e){
                if($('#countryn').val() != 36){
                    return false;
                } 
                return true;
            }
        },
        'villages[]':{
            required : function(e){
                if($('#countryn').val() != 36){
                    return false;
                } 
                return true;
            }
        },
        'addr_types[]':{required:true},
         // 'phone_number[]':{required:true}
     },
     messages:{

     },submitHandler:function() {
         var url = document.URL.split("/").splice(0, 6);
         var post_url = 'add';
         if(typeof url === 'object' && !jQuery.isEmptyObject(url) && typeof url != 'undefined' || url.isArray()) {

             if(typeof url['5'] !== 'undefined'){
                 var id = parseInt(url['5'].trim());
                 post_url = id;
             }
         }

         var form_data = new FormData();
         form_data.append('photo', $('input[name=photo]')[0].files[0]);
         form_data.append('signature', $('input[name=signature]')[0].files[0]);
         form_data.append('client_id', id);
         form_data.append('submit', true);
         var data = $('#clientForm').serializeArray();
         $.each(data, function(key, input) {
                form_data.append(input.name, input.value);
         });

         if(confirm('Are you sure?')) {

             return saveData(post_url, form_data, id);
         }
     }
 });


$('#id_type').on('change', function(e) {
    "use strict";

    var rules = {};
    $("#id_number").rules("remove", "");

    switch ($(this).val()) {

        case 'N':
        rules = {
            minlength : 9,
            maxlength : 9,
            required  :true,
            testing   :true
        };
        jQuery.validator.addMethod("testing", function(value, element) {
            // allow any non-whitespace characters as the host part
            //[\u0041-\u005A\u0061-\u007A\u00AA\u00B5\u00BA\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u02C1\u02C6-\u02D1\u02E0-\u02E4\u02EC\u02EE\u0370-\u0374\u0376\u0377\u037A-\u037D\u0386\u0388-\u038A\u038C\u038E-\u03A1\u03A3-\u03F5\u03F7-\u0481\u048A-\u0527\u0531-\u0556\u0559\u0561-\u0587\u05D0-\u05EA\u05F0-\u05F2\u0620-\u064A\u066E\u066F\u0671-\u06D3\u06D5\u06E5\u06E6\u06EE\u06EF\u06FA-\u06FC\u06FF\u0710\u0712-\u072F\u074D-\u07A5\u07B1\u07CA-\u07EA\u07F4\u07F5\u07FA\u0800-\u0815\u081A\u0824\u0828\u0840-\u0858\u08A0\u08A2-\u08AC\u0904-\u0939\u093D\u0950\u0958-\u0961\u0971-\u0977\u0979-\u097F\u0985-\u098C\u098F\u0990\u0993-\u09A8\u09AA-\u09B0\u09B2\u09B6-\u09B9\u09BD\u09CE\u09DC\u09DD\u09DF-\u09E1\u09F0\u09F1\u0A05-\u0A0A\u0A0F\u0A10\u0A13-\u0A28\u0A2A-\u0A30\u0A32\u0A33\u0A35\u0A36\u0A38\u0A39\u0A59-\u0A5C\u0A5E\u0A72-\u0A74\u0A85-\u0A8D\u0A8F-\u0A91\u0A93-\u0AA8\u0AAA-\u0AB0\u0AB2\u0AB3\u0AB5-\u0AB9\u0ABD\u0AD0\u0AE0\u0AE1\u0B05-\u0B0C\u0B0F\u0B10\u0B13-\u0B28\u0B2A-\u0B30\u0B32\u0B33\u0B35-\u0B39\u0B3D\u0B5C\u0B5D\u0B5F-\u0B61\u0B71\u0B83\u0B85-\u0B8A\u0B8E-\u0B90\u0B92-\u0B95\u0B99\u0B9A\u0B9C\u0B9E\u0B9F\u0BA3\u0BA4\u0BA8-\u0BAA\u0BAE-\u0BB9\u0BD0\u0C05-\u0C0C\u0C0E-\u0C10\u0C12-\u0C28\u0C2A-\u0C33\u0C35-\u0C39\u0C3D\u0C58\u0C59\u0C60\u0C61\u0C85-\u0C8C\u0C8E-\u0C90\u0C92-\u0CA8\u0CAA-\u0CB3\u0CB5-\u0CB9\u0CBD\u0CDE\u0CE0\u0CE1\u0CF1\u0CF2\u0D05-\u0D0C\u0D0E-\u0D10\u0D12-\u0D3A\u0D3D\u0D4E\u0D60\u0D61\u0D7A-\u0D7F\u0D85-\u0D96\u0D9A-\u0DB1\u0DB3-\u0DBB\u0DBD\u0DC0-\u0DC6\u0E01-\u0E30\u0E32\u0E33\u0E40-\u0E46\u0E81\u0E82\u0E84\u0E87\u0E88\u0E8A\u0E8D\u0E94-\u0E97\u0E99-\u0E9F\u0EA1-\u0EA3\u0EA5\u0EA7\u0EAA\u0EAB\u0EAD-\u0EB0\u0EB2\u0EB3\u0EBD\u0EC0-\u0EC4\u0EC6\u0EDC-\u0EDF\u0F00\u0F40-\u0F47\u0F49-\u0F6C\u0F88-\u0F8C\u1000-\u102A\u103F\u1050-\u1055\u105A-\u105D\u1061\u1065\u1066\u106E-\u1070\u1075-\u1081\u108E\u10A0-\u10C5\u10C7\u10CD\u10D0-\u10FA\u10FC-\u1248\u124A-\u124D\u1250-\u1256\u1258\u125A-\u125D\u1260-\u1288\u128A-\u128D\u1290-\u12B0\u12B2-\u12B5\u12B8-\u12BE\u12C0\u12C2-\u12C5\u12C8-\u12D6\u12D8-\u1310\u1312-\u1315\u1318-\u135A\u1380-\u138F\u13A0-\u13F4\u1401-\u166C\u166F-\u167F\u1681-\u169A\u16A0-\u16EA\u1700-\u170C\u170E-\u1711\u1720-\u1731\u1740-\u1751\u1760-\u176C\u176E-\u1770\u1780-\u17B3\u17D7\u17DC\u1820-\u1877\u1880-\u18A8\u18AA\u18B0-\u18F5\u1900-\u191C\u1950-\u196D\u1970-\u1974\u1980-\u19AB\u19C1-\u19C7\u1A00-\u1A16\u1A20-\u1A54\u1AA7\u1B05-\u1B33\u1B45-\u1B4B\u1B83-\u1BA0\u1BAE\u1BAF\u1BBA-\u1BE5\u1C00-\u1C23\u1C4D-\u1C4F\u1C5A-\u1C7D\u1CE9-\u1CEC\u1CEE-\u1CF1\u1CF5\u1CF6\u1D00-\u1DBF\u1E00-\u1F15\u1F18-\u1F1D\u1F20-\u1F45\u1F48-\u1F4D\u1F50-\u1F57\u1F59\u1F5B\u1F5D\u1F5F-\u1F7D\u1F80-\u1FB4\u1FB6-\u1FBC\u1FBE\u1FC2-\u1FC4\u1FC6-\u1FCC\u1FD0-\u1FD3\u1FD6-\u1FDB\u1FE0-\u1FEC\u1FF2-\u1FF4\u1FF6-\u1FFC\u2071\u207F\u2090-\u209C\u2102\u2107\u210A-\u2113\u2115\u2119-\u211D\u2124\u2126\u2128\u212A-\u212D\u212F-\u2139\u213C-\u213F\u2145-\u2149\u214E\u2183\u2184\u2C00-\u2C2E\u2C30-\u2C5E\u2C60-\u2CE4\u2CEB-\u2CEE\u2CF2\u2CF3\u2D00-\u2D25\u2D27\u2D2D\u2D30-\u2D67\u2D6F\u2D80-\u2D96\u2DA0-\u2DA6\u2DA8-\u2DAE\u2DB0-\u2DB6\u2DB8-\u2DBE\u2DC0-\u2DC6\u2DC8-\u2DCE\u2DD0-\u2DD6\u2DD8-\u2DDE\u2E2F\u3005\u3006\u3031-\u3035\u303B\u303C\u3041-\u3096\u309D-\u309F\u30A1-\u30FA\u30FC-\u30FF\u3105-\u312D\u3131-\u318E\u31A0-\u31BA\u31F0-\u31FF\u3400-\u4DB5\u4E00-\u9FCC\uA000-\uA48C\uA4D0-\uA4FD\uA500-\uA60C\uA610-\uA61F\uA62A\uA62B\uA640-\uA66E\uA67F-\uA697\uA6A0-\uA6E5\uA717-\uA71F\uA722-\uA788\uA78B-\uA78E\uA790-\uA793\uA7A0-\uA7AA\uA7F8-\uA801\uA803-\uA805\uA807-\uA80A\uA80C-\uA822\uA840-\uA873\uA882-\uA8B3\uA8F2-\uA8F7\uA8FB\uA90A-\uA925\uA930-\uA946\uA960-\uA97C\uA984-\uA9B2\uA9CF\uAA00-\uAA28\uAA40-\uAA42\uAA44-\uAA4B\uAA60-\uAA76\uAA7A\uAA80-\uAAAF\uAAB1\uAAB5\uAAB6\uAAB9-\uAABD\uAAC0\uAAC2\uAADB-\uAADD\uAAE0-\uAAEA\uAAF2-\uAAF4\uAB01-\uAB06\uAB09-\uAB0E\uAB11-\uAB16\uAB20-\uAB26\uAB28-\uAB2E\uABC0-\uABE2\uAC00-\uD7A3\uD7B0-\uD7C6\uD7CB-\uD7FB\uF900-\uFA6D\uFA70-\uFAD9\uFB00-\uFB06\uFB13-\uFB17\uFB1D\uFB1F-\uFB28\uFB2A-\uFB36\uFB38-\uFB3C\uFB3E\uFB40\uFB41\uFB43\uFB44\uFB46-\uFBB1\uFBD3-\uFD3D\uFD50-\uFD8F\uFD92-\uFDC7\uFDF0-\uFDFB\uFE70-\uFE74\uFE76-\uFEFC\uFF21-\uFF3A\uFF41-\uFF5A\uFF66-\uFFBE\uFFC2-\uFFC7\uFFCA-\uFFCF\uFFD2-\uFFD7\uFFDA-\uFFDC]+/g for Unicode but not nunber
            return this.optional(element) || [].test( value );
        }, 'Hello.');

            break;
        case 'F':
            rules = {
                minlength: 1,
                maxlength: 20,
                required:true
            };
            break;

        case 'P':
            rules = {
                minlength: 5,
                maxlength: 15,
                required:true
            };
            break;
        case 'D':
            rules = {
                minlength: 5,
                maxlength: 20,
                required:true
            };

            break;
        case 'G':
            rules = {
                minlength: 1,
                maxlength: 20,
                required:true
            };
            break;
        case 'B':

            rules = {
                minlength: 1,
                maxlength: 20,
                required:true
            };
            break;
        case 'V':

            rules = {
                minlength: 1,
                maxlength: 20,
                required:true
            };
            break;
        case 'T':

            rules = {
                minlength: 1,
                maxlength: 20,
                required:true
            };
            break;
        case 'R':

            rules = {
                minlength: 1,
                maxlength: 20,
                required:true
            };
            break;
        default:
            break;
    }
    $('#id_number').rules( "add", rules);

});

//function customizeMethods(els,rules, sms ){ /[\u1780-\u17FF]/
//    "use strict";
//
//    jQuery.validator.addMethod(els, function(value, element) {
//        return this.optional(element) || rules.test( value );
//    },sms);
//
//}

$('#saveDraft').click(function () {
    var urls = document.URL.split('/').splice(0,6);
    var form_data = new FormData();


    form_data.append('photo', $('input[name=photo]')[0].files[0]);
    form_data.append('signature', $('input[name=signature]')[0].files[0]);
    form_data.append('draft', true);
    var data = $('#clientForm').serializeArray();
    $.each(data, function(key, input) {
        form_data.append(input.name, input.value);
    });
    var setOrNot = typeof $('#draft_client_id').val() !== 'undefined'? $('#draft_client_id').val() : false;
    var id = setOrNot?setOrNot:urls[5];

    if(typeof id !='undefined') {
        var post_url = '/client/add/'+id;
    }else{
        post_url = '/client/add';
    }
    return saveData(post_url, form_data, id);

});

function saveData (post_url, form_data, id) {
    "use strict";
    $.ajax({
        url: post_url,
        data: form_data,
        type: 'POST',
        method:'post',
        headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
        contentType: false,
        cache: false,
        processData: false,
        beforeSend:function(){

            $('input[type=submit]').attr('disabled',true);
            $('#saveDraft').attr('disabled',true);
            call_and_delete_Loading('#loading', 'Loading...',  'info');
        },
        success: function (data, status) {
            if(status == 'success') {

                $('#saveDraft').attr('disabled',false);
                $('input[type=submit]').attr('disabled', false);
                if(!data.result) {
                    call_and_delete_Loading('#loading', 'Please Try again', 5 , status);
                }
                if(data.client_id != false) {

                    $('.mbody tr:first-child').each(function(e,a) {
                        $(this).removeClass();
                        if(typeof data.Address != 'undefined' || !$.isEmptyObject(data.Address)){

                            $(this).addClass(''+data.Address[e]+'');
                        }

                    });
                    $('.copy tr:first-child').each(function(e,a) {
                        $(this).removeClass();
                        if(typeof data.Contact != 'undefined'){
                            $(this).addClass(''+data.Contact[e]+'')
                        }

                    });
                    $('.copyId tr:first-child').each(function(e,a) {
                        $(this).removeClass();
                        if(typeof data.Iden != 'undefined'){
                            $(this).addClass(''+data.Iden[e]+'')
                        }

                    });

                    $('.cloneEm').find('.panel-body').each(function(e,a) {

                        $(this).removeClass();
                        if(typeof data.Employer != 'undefined'){

                            $(this).addClass('panel-body ' + data.Employer[e] + '');
                            $('.glyphicon-arrow-right').text(''+data.Employer[e]+'');
                        }
                    });

                    call_and_delete_Loading('#loading', 'Draft data was Save Successfully', 5 , status);
                    $('#draft_client_id').remove();
                    $('#clientForm').find('.addr_id').remove();
                    $('#clientForm').find('.cnt_id').remove();
                    $('#clientForm').find('.Iden_id').remove();
                    //$('#clientForm').find('.Em_id').remove();

                    $('#clientForm').append('<input id="draft_client_id" name="draft_client_id" value="'+data.client_id+'" style="display: none;" readonly />')

                }else {

                    call_and_delete_Loading('#loading', 'Successfully !!! Now We go to customer list', 5 , status);
                    var urls = '/client/list';
                    $('#clientForm').find('.addr_id').remove();
                    location.href = urls
                }
            }
        },error: function(requestObject, error, errorThrown) {

            switch (requestObject.status) {
                case 401:
                    if(confirm('User\'s session was expired, Please click OK to login again')){
                        window.location.href = '/user/login';
                    }
                    break;
                case 500:
                    if(confirm('Internal Server Error, Please contact your technical ')){
                        window.location.reload()
                    }
                    break;
                default:
                    console.log(requestObject);
            }
        }
    });
}

$('#checkUser').click(function() {
    "use strict";

    var checkUser = $("#ModelCheck");
    var forms = $('#form_user');

        checkUser.modal({
            keyboard: false,
            backdrop: 'static'
        });

        checkUser.on('shown.bs.modal', function (e) {
            e.preventDefault();
            forms.find('input:text').each(function(e) {
                return $(this).val('')
            });
            $('#resultS').children().remove();
        });
    checkUser.on('hidden.bs.modal', function (e) {
            $('#loading').css({'display':'none'});
        });
});

$('#form_user').validate({
    rules : {
        name_en:{
            required:false
        }
    },
    submitHandler : function(e) {
        "use strict";
        var urlAction = $('#form_user').attr('action');
        var name_en = $('input[name=name_en]').val();
        var name_kh = $('input[name=name_kh]').val();
        $.ajax({
            url: urlAction+'?name_en='+name_en+'&name_kh='+name_kh+'&db='+$('input[name=db]').val()+'&ident_id='+$('input[name=ident_id]').val(),
            datatype: 'json',
            method:'get',
            headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
            contentType: false,
            cache: false,
            processData: false,
            beforeSend : function() {
                call_and_delete_Loading('#loading', 'Searching...', 'info');
                $('.save').attr('disabled',true)
            },
            success: function (data, status) {
                $('#resultS').children().remove();
                $('.save').attr('disabled', false);
                var htmls = '';
                if($.isEmptyObject(data)){
                    htmls += '<div><a class="btn btn-info btn-lg" id="_copy"> Copy </a></div>';
                } else {
                    htmls +='<hr/><br/><table class="table table-bordered table-striped">';
                    htmls +='<thead>';
                    htmls +='<tr>';
                    htmls +='<th>ID</th><th>Name</th><th>Khmer Name</th><th>Date Of Birth (yyyy-mm-dd)</th><th>Identification Number</th>';
                    htmls +='</tr>';
                    htmls +='</thead>';
                    var i = 1;
                    $.each(data, function(k, vals) {
                        if(vals.general){
                            $.each(vals.general, function(keys, general){
                                var years = '';
                                var dates = '';
                                var months = '';  
                                if(general.date_of_birth){
                                    years = general.date_of_birth.substring(0, 4);
                                    dates = general.date_of_birth.substring(6, 4);
                                    months = general.date_of_birth.substring(8, 6);                                    
                                }
                                var dateOfBirth = Date();
                                htmls +='<tbody>';
                                htmls +='<tr>';
                                htmls +='<td>'+i+'</td>';
                                // htmls +='<td><a target="_blank" href="/client/getDetailn/'+general.client_id+'">'+general.family_name+'  '+general.first_name+'</a></td>';
                                htmls +='<td><a target="_blank" href="detail/'+general.client_id+'">'+general.family_name+'  '+general.first_name+'</a></td>';
                                htmls +='<td>'+general.family_name_kh+'  '+general.first_name_kh+'</td>';
                                // htmls +='<td>'+years+'-'+dates+'-'+months+'</td>';
                                htmls +='<td>'+general.date_of_birth+'</td>';
                                if(vals.identification){
                                    htmls += '<td>';
                                        $.each(vals.identification, function(key, Ident){
                                            htmls += Ident.id_number+' / ';
                                        });
                                    htmls += '</td>';
                                }
                                htmls +='</tr>';
                                htmls +='</tbody>';
                            })
                        }
                        i++;
                    });
                    htmls +='</table>';
                }
                $('#resultS').append(htmls);
                call_and_delete_Loading('#loading', 'Done', 'info');
            }, error:function(errors) {
                call_and_delete_Loading('#loading', 'Error occurred, Please try again', 'info');
                $('.save').attr('disabled', false)
                $('#resultS').children().remove();
            }
        });
    }
});

$(document).on('click','#_copy',function(){
    "use strict";

    if($(this).is('#_copy')){
        var nameEn = $('input[name=name_en]').val().replace(/\n/g, " " ).split( " " );
        var nameKH = $('input[name=name_kh]').val().replace(/\n/g, " " ).split( " " );
        console.log(nameKH)

        $('input[name=family_name_en]').val(nameEn[0])
        $('input[name=first_name_en]').val(nameEn[1])

        $('input[name=family_name_kh]').val(nameKH[0])
        $('input[name=first_name_kh]').val(nameKH[1])
        $('#ModelCheck').modal('hide');
    }
});