/**
 * Created by hengsoheak on 4/25/2016.
 */
function Notfification_Modal(obj) {

    $('<div id="results"></div>').appendTo('body');

    var data = '<form class="cmxform form-horizontal ' + obj.idSelectors + '" id="s' + obj.idSelectors + '" method="post" action="" enctype="form-data" onsubmit="return false;">';
    data += '<div id="' + obj.idSelectors + '" class="modal fade out" role="dialog">';
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
    data += formelement(obj);
    data += '           </div>';
    data += '       </div>';
    data += '   </div>';
    data += '</div>';
    data += '<div class="modal-footer"> </form>';
    data += '</div>';
    data += '</div>';
    data += '</div>';
    data += '</div>';
    $(data).appendTo('#results');

    $('#' + obj.idSelectors).modal({
        keyboard: obj.keyboard,
        backdrop: obj.backdrop
    });
}
function messages(sms) {

    var html = '<div>' + sms + '</div>';
    return html;
}
function formelement(data) {

    var items = '';
    for (var key in data.forms) {

        if (key == 'input') {
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

                } else {
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
                items += '<div class="form-group"><select class="' + sels[inkey].class + '"  name="' + sels[inkey].name + '" id="' + sels[inkey].id + '"><option value=""> Choose one </option>';
                var opt = sels[inkey];
                for (var ok in opt) {
                    items += opt[ok];
                }
                items += '</select></div>';
            }
        } else if (key === 'textarea') {

            var textarea = data.forms[key];
            for (var subKey in textarea) {

                var vals = textarea[subKey];

                items += '<div class="form-group">';
                items += '<textarea class="' + vals.class + '" name="' + vals.name + '" rows="' + vals.rows + '" id="' + vals.id + '">' + vals.text + '</textarea>';
                items += '</div>';
            }
        }
    }
    if(!items) {
        return messages(data.sms);
    }
    return items;

}

function labels(data) {

    var items = '';
    if (!data.labels) {
        return '';
    } else {
        $.each(data.labels, function (ins, vals) {
            items += '<div class="form-group" style="height:46px;"> <label style="height:30px;" for="' + vals + '">' + vals + '</label> <label style="float: right; margin-right:69px;">:</label></div>';
        });
        return items;
    }
}