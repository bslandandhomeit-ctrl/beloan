

var till_acc_id = null;
var turnOffDate = false;

function loadModale(obj) {
    var data = '<form class="cmxform form-horizontal ' + obj.idSelector + '" id="s' + obj.idSelector + '" method="post" action="" enctype="form-data" onsubmit="return false;">';
    data += '<div id="' + obj.idSelector + '" class="modal fade out" role="dialog">';
    data += '<div class="modal-dialog" style="width: auto;max-width:1100px; min-width: 900px;">';
    data += '<div class="modal-content">';
    data += '<div class="modal-header">';
    data += '<div><button type="button" class="close mclose" data-dismiss="modal" style="margin-top:-16.5px;font-size: 41px;margin-right:-6px;">&times;</button></div>';
    data += '<h4 class="modal-title">' + obj.title + '</h4>';
    data += '</div>';
    data += '<div class="modal-body">';
    data += '   <div class="container">';
    data += '       <div class="row">';
    data += '           <div class="col-lg-2 col-sm-2 col-xs-2" style="padding:14px 0px 0px 22px;">';
    data += labels(obj);
    data += '           </div>';
    data += '           <div class="col-lg-4 col-md-4 col-xs-5 " style="padding-right: 36px;">';
    data += formelement(obj,'');
    data += '           </div>';
    data += '           <div class="col-lg-5 col-md-5 col-xs-5" style="padding-right: 36px;"><div class="appendData"></div>'+formelement('', obj);+'</div>';
    data += '       </div>';
    data += '   </div>';
    data += '</div>';
    data += '<div class="modal-footer"><input type="submit" name="submit" id="tillSubmit" value="Submit" class="btn btn-info"> </form>';
    data += '<button type="button" class="btn btn-default mclose" data-dismiss="modal">Close</button>';
    data += '</div>';
    data += '</div>';
    data += '</div>';
    data += '</div>';
    $(data).appendTo('#result');
    $('#' + obj.idSelector).modal({
        keyboard: (obj.keyboard) ? obj.keyboard : false,
        backdrop: (obj.backgrop) ? obj.backgrop : 'static'
    });
    require(obj.script);
    $('.select2').select2();

    $(function(){
        $('.client').select2({
            minimumInputLength: -1,
            placeholder: "Choose one",
            ajax:{
                url: '/teller/get_withdraw',
                dataType: 'json',
                type: "GET",
                quietMillis: 50,
                timeout: 3000,
                data: function (term) {
                    return {term: term};
                },
                results: function (data) {
                    if(data.withdraw){
                        allData = data.withdraw;
                        return {
                            results: 
                            $.map(data.withdraw, function (vals,keys) {
                                return {
                                    text: vals.account_name+' - '+ data.currency_list[data.withdraw[keys].currency] + ' - '+ data.withdraw[keys].account_no,
                                    slug: vals.account_name,
                                    id: data.withdraw[keys].id,
                                    name:'client'
                                }
                            })
                        }; 
                    }else{
                        $('<div id="loading"></div>').appendTo('body');
                        imgLoading(true,'Permission denied!!!',4,'warning');
                        return
                    }
                }
            }
        });
    });

    $(function(){
        $('.drawdown_acc_from').select2({
            minimumInputLength: -1,
            placeholder: "Choose one",
            ajax:{
                url: '/teller/deposit',
                dataType: 'json',
                type: "GET",
                quietMillis: 50,
                timeout: 3000,
                data: function (term) {
                    return {term: term};
                },
                results: function (data) {
                    if(data.withdraw){
                        alldata = data.withdraw;
                        return {
                            results: 
                            $.map(data.withdraw, function (vals,keys) {
                                return {
                                    text: vals.account_name+' ( ' + data.withdraw[keys].projects.dealer + ' - ' + data.withdraw[keys].unit_type.name + ' - ' + data.withdraw[keys].units.code + ') - ' + data.currency_list[data.withdraw[keys].currency],
                                    slug: vals.account_name,
                                    id: data.withdraw[keys].id,
                                    name:'from'
                                }
                            })
                        }; 
                    }else{
                        $('<div id="loading"></div>').appendTo('body');
                        imgLoading(true,'No Data!!!',4,'warning');
                        return
                    }
                }
            }
        });
    });

    $('#loading').remove();
}

function messages(sms) {
    if (typeof sms != 'undefined' && Object.keys(sms).length != 0) {
        return '<div>' + sms + '</div>';
    }
    return;
}

function formelement(data, data2) {

    var items = '';
    if (!$.isEmptyObject(data.forms)) {

        for (var key in data.forms) {

          if (key == 'date_picker'){
              if(!turnOffDate){
                var dpDate = data.forms[key];
                for (var subKey in dpDate){
                    var val = dpDate[subKey];
                    items += '<div id="start_date" class="input-append date dpYears form-group" data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd">';
                        items += '<input type="' + val.type + '" value="' + val.value + '" class="form-control" name="' + val.name + '" id="' + val.id + '">';
                        items += '<span class="add-on offonDatepicker"><button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button></span>';
                    items += '</div>';
                }
              }
          }else if (key == 'input') {
                var inputFields = data.forms[key];
                for (var subKey in inputFields) {

                    if (subKey === 'selection') {

                        var sels = inputFields[subKey];

                        for (var inkey in sels) {
                            items += '<div class="form-group"><select class="' + sels[inkey].class + '"  name="' + sels[inkey].name + '" id="' + sels[inkey].id + '"><option value=""> Choose one </option>';
                            var opt = sels[inkey];
                            for (var ok in opt) {
                                items += opt[ok];
                            }
                            items += '</select></div>';
                        }

                    }else if (subKey === 'checkbox') {

                        var sels = inputFields[subKey];

                        for (var inkey in sels) {

                            items += '<div class="checkbox-inline">' +
                                '<input type="checkbox" name="'+sels[inkey].name+'" value="'+sels[inkey].value+'" id="'+sels[inkey].Id+'" class="ch" onchange="checkit(this)">'+
                                '<label style="padding-right: 12px;padding-left:12px">'+inkey+'</label></div>';
                        }
                        items += '<div class="form-group" id="n_inputs"></div>';
                    }
                    else {
                        var vals = inputFields[subKey];
                        items += '<div class="form-group">';
                        if (vals.disabled) {
                            items += '<input type="' + vals.type + '"  name="' + vals.name + '"  class="' + vals.class + '"  id="' + vals.Id + '" placeholder="' + vals.placeholder + '" style="' + vals.style + '" value="' + vals.value + '"  disabled />';
                        } else {
                            items += '<input type="' + vals.type + '"  name="' + vals.name + '"  class="' + vals.class + '"  id="' + vals.Id + '" placeholder="' + vals.placeholder + '" style="' + vals.style + '" value="' + vals.value + '"/>';
                        }
                        items += '</div>';
                    }
                }
            } else if (key == 'selection') {

                var sels = inputFields[subKey];

                for (var inkey in sels) {
                    items += '<div class="form-group"><select style="width:100%;" class="' + sels[inkey].class + '"  name="' + sels[inkey].name + '" id="' + sels[inkey].id + '"><option value=""> Choose one </option>';
                    var opt = sels[inkey];
                    for (var ok in opt) {
                        items += opt[ok];
                    }
                    items += '</select>';
                }
            } else if (key === 'textarea') {

                var textarea = data.forms[key];
                for (var subKey in textarea) {

                    var vals = textarea[subKey];
                    items += '<div class="form-group">';
                    items += '<textarea class="' + vals.class + '" name="' + vals.name + '" rows="' + vals.rows + '" id="' + vals.id + '"></textarea>';
                    items += '</div>';
                }
            }
        }
    }
    if(!$.isEmptyObject(data2.forms)) {
        $.each(data2.forms, function(inx, vals) {

            if(inx === 'right_side') {
                for(var keys in vals){
                    var val = vals[keys];
                    items +='<div class="checkbox-inline ">' +
                            '<input type="'+val.type+'" name="'+val.name+'" value="'+val.value+'" id="'+val.Id+'" class="'+val.class+'" />' +
                            '<label for="'+keys+'">'+keys+':</label>'+
                        '</div>';
                }
            }
        });
    }
    else {
        if (!items) {
            return messages(data.sms);
        }
    }
    return items;
}

function checkFields(data){

    var htmls = '';
    if(!$.isEmptyObject(data.radio)) {

        var htmls = '<input type="radio" name="gender" value="male"> Male<br>';

    }else if(!$.isEmptyObject(data.checkbox)) {

            htmls += '<input type="checkbox" name="vehicle" value="Bike"> I have a bike<br>';

    }
    //console.log(htmls);
    if(htmls){
        return htmls;
    }
}

function labels(data) {

    var items = '';
    if (!data.labels) {
        return '';
    } else {
        $.each(data.labels, function (ins, vals) {
            if(turnOffDate){
              if(vals=="Date") {return true};
            }
            items += '<div class="form-group" style="height:42px;"> <label style="height:30px;" for="' + vals + '">' + vals + '</label> <label style="float: right; margin-right:69px;">:</label></div>';
        });
    }
    return items;
}

function require(script) {
    if (!script) {
        return false;
    }
    $.each(script, function (ins, val) {
        $.ajax({
            url: val,
            dataType: "script",
            async: false,
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            },
            success: function (data, status) {
            },
            error: function () {
                throw new Error("Could not load script " + script);
            }
        });
    });
}
/*
 * tellers  is global variabels which declare in telloperation.blade.php for initialing teller data as json types
 * */

$(document).on("click", ".mclose", function () {

    var allresult = $('body').find('#result');
    var allLoading = $('body').find('#loading');

    for(var i=0;i<allresult.length;i++){
        allresult.remove();
    }
    for(var i=0;i<allLoading.length;i++){
        allLoading.remove;
    }
});

function errorCallback(jqXHR, textStatus, errorThrown) {

    if (textStatus == "timeout" || textStatus == "error" || errorThrown == "Internal Server Error") {
         imgLoading(true, 'Please reload your page!!! ', 7, textStatus);
    }
    return false;
}

function validate(target) {

    $(target).validate({
        rules: {
            amount: {
                required: true,
                number: true
            },
            descr: {required: true},
        }
    });
}
function Ajaxs(url, method, dataTypes, data, callBack) {

    $('<div id="loading"></div>').appendTo('body');
    var postData = {};
    if (!$.isEmptyObject(data)) {
        postData = data;
    }
    //console.log("URL = ", url);
    //console.log("----",postData);
    $.ajax({
        url: url,
        method: method,
        dataType: dataTypes,
        timeout: 10000,
        cache: false,
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        },
        data: postData,
        BeforeSend:function (){
            imgLoading(true,'Loading....',3,'warning');
        },
        success: function (data, status) {
            if (status === 'success') {
                return callBack(data, status);
            }
        },
        error:function(err){
          window.err = err;
          console.log("errr = ",err);
        }
    }).done(function(){
        setTimeout(function() {
            $('#loading').remove();
        }, 6000);
    });
}

function disableBtn(val) {

    var check = false;
    if (typeof val != 'undefined' && val === true) {
        check = val;
    }
    ($('input[name=amount]').attr('disabled', check), $('input[type=submit]').attr('disabled', check))
    if (check === false) {
        return $('#loading').remove();
    }
}
