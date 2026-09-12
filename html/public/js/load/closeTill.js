var turnOffDate = false;
function loadModale(obj) {

    var data = '<form class="cmxform form-horizontal ' + obj.idSelector + '" method="post" action="#" enctype="form-data">';
    data += '<div id="' + obj.idSelector + '" class="modal fade out" role="dialog">';
    data += '<div class="modal-dialog" style="width: auto; max-width: 900px; min-width: 900px;">';
    data += '<div class="modal-content">';
    data += '<div class="modal-header">';
    data += '<div><button type="button" class="close mclose" data-dismiss="modal" style="margin-top:-16.5px;font-size: 41px;margin-right:-6px;">&times;</button></div>';
    data += '<h4 class="modal-title">' + obj.title + '</h4>';
    data += '</div>';
    data += '<div class="modal-body">';
    data += '   <div class="container">';
    data += '       <div class="row">';
    data += '           <div class="col-xs-3" style="padding:14px 0px 0px 22px;">';
    data += labels(obj);
    data += '           </div>';
    data += '           <div class="col-xs-5" style="padding-right: 36px;">';
    data += formelements(obj);
    data += '           </div>';
    data += '       </div>';
    data += '   </div>';
    data += '</div>';
    data += '<div class="modal-footer"><input type="submit" value="Submit" class="btn btn-info"> </form>';
    data += '<button type="button" class="btn btn-default mclose" data-dismiss="modal">Close</button>';
    data += '</div>';
    data += '</div>';
    data += '</div>';
    data += '</div>';
    $(data).appendTo('#result');

    $('#' + obj.idSelector).modal({
        keyboard: false,
        backdrop: 'static'
    });

    require(obj.script);
    return submitpost("."+obj.idSelector, obj.byId);
}

function labels(data) {

    var items = '';
    if (data) {

        $.each(data.labels, function (ins, vals) {
            if(turnOffDate){
              if(vals=="Date"){
                return true;
              }
            }
            items += '<div class="form-group" style="height:46px;"> <label style="height:30px;" for="' + vals + '">' + vals + '</label> <label style="float: right; margin-right:69px;">:</label></div>';
        });
    }
    return items;
}

function formelements(data) {

    var values = 0;

    var items = '';
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
      }else if (key === 'input') {
            var inputs = data.forms[key];
            for (var i = 0; i < inputs.length; i++) {
                values = inputs[i].value;
                items += '<div class="form-group"> <input type="' + inputs[i].type + '" name="' + inputs[i].name + '" class="' + inputs[i].class + '" id="' + inputs[i].Id + '" placeholder="' + inputs[i].placeholder + '" style="' + inputs[i].stype + '" value="' + values + '" disabled ></div>';
            }
        } else if (key === 'textarea') {

            var trs = data.forms[key];
            for (var i = 0; i < trs.length; i++) {
                items += '<div class="form-group"> <textarea type="' + trs[i].type + '" name="' + trs[i].name + '" class="' + trs[i].class + '" id="' + trs[i].Id + '" placeholder="' + trs[i].placeholder + '" ></textarea></div>';
            }
        } else if (key === 'selection') {

            var sels = data.forms[key];
            for (var i = 0; i < sels.length; i++) {

                items += '<div class="form-group">';
                items += '<select name="' + sels[i].name + '" class="' + sels[i].class + '" style="' + sels[i].style + '" >';
                items += '</select></div>';
            }

        }
    }
    return items;
}

function require(script) {

    if (typeof script !== "undefined") {

        $.each(script, function (ins, val) {

            $.ajax({
                url: val,
                dataType: "script",
                async: false,
                success: function (data, status) {
                    // validate errors happy although success
                },
                error: function () {
                    throw new Error("Could not load script " + script);
                }
            });
        });
        return true;
    }else{
        return false;
    }
}

function submitpost(selector, byId) {

    $(selector).validate({

        submitHandler: function () {

            var url = '/teller/updateTill/'+byId;
            var datas = {
                _token:$("#token").val(),
                status              : 0
                //acc_name            : stringPart($('input[name=acc_no_name]').val(),1),
                //branch_id           : $('input[name=branch_id]').val(),
                //currency_id         : $('input[name=currency_id]').val(),
                //last_trans_amount   : $('input[name=last_trans_amount]').val(),
                //balance             : $('input[name=balance]').val(),
                //open_time           : $('input[name=open_time]').val(),
            };
            $.ajax({
                url: url,
                method: 'Post',
                dataType: "Json",
                data: datas,
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                },
                beforeSend:function(){
                    imgLoading(true, "Saving....",1,'no');
                },
                success: function (data, status) {

                    if(data.res == true && status == 'success'){
                        imgLoading(true, "Saved....",1,status);
                        $("#result").empty();
                    }else{
                        imgLoading(true, "Please try again",12,'no');
                    }
                }
            })
        }
    });

    $.validator.addMethod("valueNotEquals", function(value, element, arg){
        return arg != value;
    }, "Value must not equal arg.");
    $.validator.addMethod("noVals", function(value, element, arg){
        return arg != value;
    }, "please select it");
}

$(document).on("click", ".mclose", function () {
    $("#result").empty();
    $("#loading").css({"display":"none"});
    $("#loading").empty();

});
$(document).on("click", ".dpYears", function () {
    dateCallback(".dpYears");

});
function dateCallback(selector) {

    var retn = null;
    if (typeof selector !== 'undefined') {
        retn = $(selector).datepicker({
            format: 'yyyy-mm-dd hh:ii:ss',
            autoclose: true,
            setDate: getNowTime()
        });
    }
    return retn;
}
function stringPart(strings, parts){

    var lastString = 0;
    if(strings.indexOf('/') ) {

        var dev = strings.split('/');
        if(parts === 0){
            lastString = dev[0];
        }else{
            lastString = dev[1];
        }

    } else {
    }
    return lastString;
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
