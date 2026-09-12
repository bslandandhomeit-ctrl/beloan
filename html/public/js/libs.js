/**
 * Created by Heng Soheak on 3/14/2016. Feature: Teller features myload: avoided
 * to write modal again  we can load it by myload({Objects})
 */
var userId = null;
var bId = null
function myload(option) {

    branch();
    var data = '<form class="cmxform form-horizontal" method="post" action="#" id="tills" enctype="form-data">';
    data += '<div id="' + option.id + '" class="modal fade" role="dialog">';
    data += '<div class="modal-dialog">';
    data += '<div class="modal-content">';
    data += '<div class="modal-header">';
    data += '<div><button type="button" class="close mclose" data-dismiss="modal" style="margin-top:-16.5px;font-size: 41px;margin-right:-6px;">&times;</button></div>';
    data += '<h4 class="modal-title">' + option.title + '</h4>';
    data += '</div>';
    data += '<div class="modal-body">';
    data += formElement(option.inputs);
    data += '</div>';
    data += '<div class="modal-footer"><input type="submit" value="Submit" class="btn btn-info"> </form>';
    data += '<button type="button" class="btn btn-default mclose" data-dismiss="modal">Close</button>';
    data += '</div>';
    data += '</div>';
    data += '</div>';
    data += '</div>';
    $(data).appendTo("#result");
    $('#' + option.id).modal({
        keyboard: false,
        backdrop: 'static'
    });
    require(option.script);
    submitpost("#tills");

}

function formElement(data) {

    var items = '';
    if ($.isArray([data])) {
        $.each(data, function (ins, val) {
            items += val;
        });
    }
    return items;
}

function branch() {

    var branchCode = null;
    var curIns = 0;
    var acNum = null;
    var accName = null;
    var provi = null;
    var branName = null;
    var plus = 1;
    var creBy = null
    var currency_id = null;

    $.ajax({
        url: '/teller/getBrand',
        dataType: "json",
        method: 'Get',
        success: function (data) {

            $.each(data.branchs, function (key, vals) {
                if (vals.currency_id !== null) {
                    curIns = vals.currency_id
                } else {
                    curIns = 0;
                }
                bId = vals.bId;
                branchCode = vals.branch_code;
                acNum = vals.till_id + plus;
                branName = vals.branch_name;
                provi = vals.short_name;
                creBy = vals.username;
                userId = vals.uid;
                currency_id = vals.currency_id;
            });
            var str = "" + acNum;
            var pad = "000000";
            var ans = pad.substring(0, pad.length - str.length) + str;

            $('input[name=account_no]').val(branchCode + curIns + '-' + ans);
            $('input[name=account_name]').val(provi + '-' + ans);
            $('input[name=branchs]').val(branName + ' ( ' + branchCode + ' ) ');
            $('input[name=created_by]').val(creBy);

            var items = '';
            var currency = $("#currency");
            AjaxCallback('/teller/currency','', '', function(data) {

                if(typeof data.currency ==='object' && Object.keys(data.currency).length === 0 ){
                    return alert('There are no enough currency type for you');
                }
                $.each(data.currency, function (ins, vals) {

                    items += '<option value="'+vals.id+'">'+vals.name+' ( '+vals.symbol+' ) </option>';
                });
                $(items).appendTo('#currency');
                currency.on("change", function () {

                    for(var key in data.currency) {
                        var vals = data.currency[key];

                        if(Number(vals.id) === Number($(this).val())){

                            $('input[name=account_no]').val(branchCode +currency.val()+ '-' + ans);
                            $('input[name=account_name]').val(vals.code.trim() + '-' + provi + '-' + ans);
                            AjaxCallback('/teller/assignTo/', bId, vals.id, function(data) {  // we select teller or chief of teller are not create yet
                                $('#assign_user').html('');
                                $('#assign_user').append($("<option></option>").attr("value", 0).text('Choose a teller'));
                                $.each(data.users, function (key, value) {
                                    $('#assign_user').append($("<option></option>").attr("value", value.id).text(value.name));
                                });
                            });
                        }
                    }

                });

            });
        }
    });
}

function AjaxCallback(url, id, currency_id, callback) {

    var new_url = url;
    if(typeof url != 'undefined'){
        if(typeof id != 'undefined' || !isNull(id) && typeof currency_id !='undefined' && !isNull(currency_id)) {
            new_url = url+id+'/'+currency_id;
        }
        $.ajax({
            url: new_url,
            method: 'get',
            dataType: 'json',
            timeout: 10000,
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            },
            success: function (data, status) {
                return callback(data);
            }
        });
    }
}

function submitpost(selector) {

    $(selector).validate({
        rules: {
            currency: {valueNotEquals: 0},
            assign_user_id: {noVals: 0},
            min_balance: {
                required: true,
                number: true
            },
            max_balance: {
                required: true,
                number: true
            },
            open_time: {
                required: true,
            },

        }, messages: {
            currency: {valueNotEquals: "Please select a currency"},
            assign_user_id: {noVals: "Please select a teller"}
        },
        submitHandler: function () {

            var url = '/teller/createtill';
            var datas = {
                _token: $("#token").val(),
                accNo: $('input[name=account_no]').val(),
                accName: $('input[name=account_name]').val(),
                branchs: bId,
                minBalance: $('input[name=min_balance]').val(),
                maxBalance: $('input[name=max_balance]').val(),
                createdBy: userId,
                assignUser: $('#assign_user').val(),
                create_date: $('input[name=create_date]').val(),
                note: $('textarea[name=note]').val(),
                currency: $('#currency').val()
            }

            $.ajax({
                url: url,
                method: 'Post',
                dataType: "Json",
                data: datas,
                headers: {
                    '_token': $('meta[name="_token"]').attr('content')
                },
                success: function (data, status) {
                    if (data.logout === true) {
                        return imgLoading(true, "Session has expired!!! please login again", 4.5);
                    }
                    if (data.res == true) {

                        listOfTillAccount(1);
                        $("#result").empty();
                        return imgLoading(true, 'Till account ('+datas.accName+') has been created', 5.5, status);
                    } else {
                        if (data.c_id === true) {
                            return imgLoading(true, "This till have already created", 6);
                        }
                    }
                }
            })
        }
    });

    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        return arg != value;
    }, "Value must not equal arg.");
    $.validator.addMethod("noVals", function (value, element, arg) {
        return arg != value;
    }, "please select it");

}

function clearLoad() {
    $("#result").empty();
    $("#loading").empty();
}
function require(script) {

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
}

$(document).on("click", ".mclose", function () {
    $("#result").empty();
    return clearLoad();
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
            setDate: new Date()
        });
    }
    return retn;
}