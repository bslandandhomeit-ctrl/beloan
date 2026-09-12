$(document).ready(function(){
    $('a.active-user').on('click',function(e){
        e.preventDefault();
        var self = $(this);
        if(confirm("Are you sure?")){
            var id = $(this).attr("data-id");
            var status = $(this).attr("data-status");
            var token = $('meta[name="_token"]').attr('content');
            var data = {
                id: id,
                status: status,
                _token: token
            };
            $.ajax({
                url:'/user/active/'+data.id,
                type: 'POST',
                data: data,
                success:function(data){
                    if(data.success == true){
                        var s = self.attr("data-status");
                        if(s == 0){
                            self.attr("data-status",1);
                            self.children("i").removeClass('fa-times-circle').addClass('fa-check-circle');
                            self.attr('title','Activate');
                            $("td#"+id).html('Inactive');
                        }else{
                            self.attr("data-status",0);
                            self.children("i").removeClass('fa-check-circle').addClass('fa-times-circle');
                            self.attr('title','Inactivate');
                            $("td#"+id).html('Active');
                        }
                    }
                }
            });
        }
    });
    function ajaxSet(url, data){
       $.ajax({
            url: url,
            type: 'POST',
            dataType: "json",
            data: data,
            success: function(data)
            {
                if(data.status == true){
                    //console.log("status: "+data.status);
                    $.toast({
                        text: 'Already saved it.',
                        heading: 'Success',
                        showHideTransition: 'fade',
                        allowToastClose: true,
                        hideAfter: 5000,
                        stack: 5,
                        position: 'bottom-right',
                        bgColor: '#DFF0D8',
                        textColor:'#3C763D'
                    });
                }else{
                    $.toast({
                        text: 'Save failed.',
                        heading: 'Error',
                        showHideTransition: 'fade',
                        allowToastClose: true,
                        hideAfter: 5000,
                        stack: 5,
                        position: 'bottom-right',
                        bgColor: '#F2DEDE',
                        textColor: '#A94442'
                    });
                }
                //console.log("status: "+data.status);
            },
            error: function(xhr, textStatus, errorThrown)
            {
                $.toast({
                    ext: 'Save failed.',
                    heading: 'Error',
                    showHideTransition: 'fade',
                    allowToastClose: true,
                    hideAfter: 5000,
                    stack: 5,
                    position: 'bottom-right',
                    bgColor: '#F2DEDE',
                    textColor: '#A94442'
                });
            }
        });
    }
    $("#othersave").on('click',function(e){
        e.preventDefault();
        var phone  = $("#phone").val();
        var address = $('#address').val();
        var token = $('meta[name="_token"]').attr('content')
        var data = {
            phone:  phone,
            address: address,
            _token: token
        };
        if(confirm("Are you sure to save it?")){
            ajaxSet('/user/setting',data);
        }
    });

    $("#emailForm").validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages:{
            email: {
                required: "Please enter a email"
            }
        },
        submitHandler:function(){
            var token = $('meta[name="_token"]').attr('content')
            var email = $("#email").val();
            var data = {
                email: email,
                _token: token
            }
            if(email != ''){
                if(confirm("Are you sure to save it?")){
                    ajaxSet('/user/setting',data);
                }
            }
            return false;
        }
    });

    $("#passwordForm").validate({
        rules:{
            old_password:{
                required:true
            },
            password:{
                required:true,
                minlength: 5,
                passValid: true,
                noSpace:true
            },
            con_password:{
                equalTo: "#password"
            }
        },
        messages:{
            old_password:{
                required:"Please enter a password"
            },
            password:{
                required:"Please enter a password"
            },
            con_password:{
                equalTo: "Password not match"
            }
        },
        submitHandler:function(){
            if(confirm("Are you sure to save it?")){
                var token = $('meta[name="_token"]').attr('content')
                var data = {
                    _token : token,
                    oldPassword: $("#old_password").val(),
                    password: $("#password").val()
                };
                ajaxSet('/user/password',data);
            }
            return false;
        }
    });

    $("#nameForm").validate({
        rules:{
            name:{
                required: true,
                minlength: 3
            }
        },
        messages:{
            name:{
                required: "Please enter a name"
            }
        },
        submitHandler: function(){
            var token = $('meta[name="_token"]').attr('content')
            var data = {
                _token : token,
                name: $("#name").val(),
                kh_name: $("#kh_name").val()
            };
            if(confirm("Are you sure to save it?")){
                ajaxSet('/user/setting',data);
            }
            return false;
        }
    });
    var files;
    $('input[type=file]').on('change', prepareUpload);

// Grab the files and set them to our variable
    function prepareUpload(event)
    {
        files = event.target.files;
    }
    $("#photoForm").on('submit',function(e){
        e.preventDefault();
        var data = new FormData();
        $.each(files, function(key, value)
        {
            data.append("photo", value);
        });
       var token = $('meta[name="_token"]').attr('content')
        data.append("_token",token);

        $.ajax({
            url: '/user/upload',
            type: 'POST',
            dataType: "json",
            data: data,
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(data)
            {
                if(data.status == true){
                    //console.log("status: "+data.status);
                    window.location.href = '/user/setting';
                }
            },
            error: function(xhr, textStatus, errorThrown)
            {
                //console.log("err: "+errorThrown);
            }
        });

        return false;
    });
});
$.validator.addMethod("noSpace", function(value, element) {
    return value.indexOf(" ") < 0;
}, "No space please");
$.validator.addMethod('passValid',function(value,element){
    var pattern = /^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/;
    return this.optional(element) || pattern.test(value);
},'At least 1 number, 1 lowercase, 1 uppercase letter');