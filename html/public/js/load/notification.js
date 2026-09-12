/**
 * Created by hengsoheak on 4/1/2016.
 * notfication
 * call ajax->query data if user currently active in browser within 3 seconds.
 */
//
$('#notes').click(function(e) {
    loadajax();
});

$(document).ready(function() {

    loadajax();

    function setTime() {
        callFun = setInterval(function () {
            loadajax();
        }, 1200000);
    }
    setTime();
    $(window).blur(function () {
        clearInterval(callFun);
    }).focus(setTime);

});

 function loadajax () {

     $("#number").html('');
     $("#notification").fadeOut(500);

     $.ajax({
         url:'/notification/get_not',
         method:'get',
         dataType:'json',
         timeout:10000,
         success: function (data, status) {

             $("#notification").html('');
             if(status === 'success') {

                 if(typeof data.data.length != 'undefined') {

                     $("#number").html(data.data.length);
                     $("#notes").addClass('open');
                     setdata(data);
                     $("#notification").fadeIn(500);
                     $("#removable").css({'display':''});
                 } else{

                     $("#removable").css({'display':'none'});
                 }
             } else{

             }
         }, error: errorCallback,
     });
}

function setdata (data) {

    var htmls = '';
    if(data.data !== false && typeof data.data.length  != 'undefined') {

        htmls += '<div class="col-lg-12">';
        htmls += '<table class="table-responsive">';
        htmls += '<thead><tr><th width="100"> user name</th><th width="100px">Typs</th><th width="300px">Description</th></tr>';
        htmls += '</thead>';
        $.each(data.data, function(inx, vals) {
            console.log(vals.n_group_code_id);
            if(vals.length !== 0)
            {
                htmls += '<tbody><tr>';
                htmls += '<td>'+vals.username+':</td>';
                htmls += '<td>'+vals.n_activity_type+'</td>';
                htmls += '<td><a href="/notification/index/">'+vals.n_description+'</a></td>';
                htmls += '</tr></tbody>';
            }
        });
        htmls += '</table>';
        htmls += '</div>';
        $(htmls).appendTo("#notification");
        //$("#removable").css({'display':'block'});
    }else{
        //$("#removable").css({'display':'none'});
        $("#notification").html('');
    }
}

function errorCallback(jqXHR, textStatus, errorThrown) {

    if (textStatus == "timeout" || textStatus == "error" || errorThrown == "Internal Server Error") {
        imgLoading(true, 'please reload your page!!!', 7, textStatus);
    }
    return false;
}