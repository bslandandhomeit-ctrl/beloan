var Login = function(){
    this.$frmLogin = $("#frmLogin");
    this.init();
};
$.extend(Login.prototype,{
    init:function(){
        var self =this;
        self.bind();
    },
    bind: function(){
        var self = this;
        self.$frmLogin.validate({
            rules:{
                username:{
                    required: true
                },
                password:{
                    required: true
                },
                pw:{
                    required: true
                }
            },
            messages:{
                username:{
                    required: "Username is required"
                },
                password:{
                    required: "Password is required"
                },
                pw:{
                    required: "Passcode is required"
                }
            },
            submitHandler: function(frm){
                var data ={
                    _token: $("#token").val(),
                    username: $("#username").val(),
                    password: $("#password").val(),
                    pw: $("#pw").val()
                }
                //console.log(data);
                $.ajax({
                    url: 'http://cbanking.develop/public/user/login',
                    type: 'POST',
                    data: data,
                    success:function(data){
                       if(data.status == true){
                           window.location.href = data.url;
                       }else if(data.hit >= 3){
                           window.location.href = 'http://cbanking.develop/public/user/login';
                       }
                       $("#result").html(data.msg);
                       if(data.is_active==0){
                         $("#result").html("User is blocked! Please contact admin.");
                       }
                    }
                });
            }
        });
    }
});
$(document).ready(function(){
    new Login();
});
