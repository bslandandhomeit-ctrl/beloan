<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="">

    <meta name="author" content="ThemeBucket">

     <link rel="icon" href="{{ asset('favicon.png', isset($secure)?false:false) }}" type="image/png" sizes="20x20">

    <title>Loan System</title>

    <!--Core CSS -->

    <link href="{{ asset('theme/bs3/css/bootstrap.min.css',isset($secure) ? false : false) }}" rel="stylesheet">

    <link href="{{ asset('theme/css/bootstrap-reset.css',isset($secure) ? false : false) }}" rel="stylesheet">

    <link href="{{ asset('theme/font-awesome/css/font-awesome.css',isset($secure) ? false : false) }}" rel="stylesheet">

    <!-- Custom styles for this template -->

    <link href="{{ asset('theme/css/style.css',isset($secure) ? false : false) }}" rel="stylesheet">

    <link href="{{ asset('theme/css/style-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet"/>

    <!-- Just for debugging purposes. Don't actually copy this line! -->

    <!--[if lt IE 9]><script src="{{ asset('theme/js/ie8-responsive-file-warning.js',isset($secure) ? false : false) }}"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>

    <script src="http://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

    <script src="http://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>

    <![endif]-->
    <style type="text/css">
        .cmxform .form-group label.error-result {
            margin: 0 5px;
            color: #B94A48;
            font-weight:400;
            }
        .remain-delay{
            width: 400px;
            margin: 50px auto;
            height: 200px;
            text-align: center;
            font-size: 15px;
        }
        .form-signin h2.form-signin-heading {
            margin: 0;
            padding: 15px 15px;
            text-align: center;
            background: #002959;
            border-radius: 5px 5px 0 0;
            -webkit-border-radius: 5px 5px 0 0;
            color: #fff;
            font-size: 18px;
            text-transform: uppercase;
            font-weight: 300;
            font-family: 'Open Sans',sans-serif;
            border-bottom: 10px solid #002959;
        }
    </style>
	<!--Core js-->
<script src="{{ asset('theme/js/jquery.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('theme/bs3/js/bootstrap.min.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
{{-- <script src="{{ asset('js/login.js',isset($secure) ? false : false) }}"></script> --}}
</head>
<body class="login-body">
<div>
  @if(isset($delay) && $delay < 0)
      <div class="remain-delay">
           <p class="text-danger">You must wait {{ abs(round($delay)) }} minutes before your next login attempt.</p>
      </div>
  @else
      <form class="form-signin cmxform " action="{{ route('login') }}" method="post" id="frmLogin" style=" max-width:330px" autocomplete="off">
        <h2 class="form-signin-heading"><img src="{{ asset('images/login_logo.png') }}" style="width: 100%"></h2>
         <div class="login-wrap">
                <div class="form-group user-login-info">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                    <input type="text" name="username" id="username" class="form-control" placeholder="User ID" autofocus autocomplete="off">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off">
                    <input type="hidden" name="pw" id="pw" class="form-control" placeholder="Secret Code" value="Admin" autocomplete="off"/>
                    <label class="error-result" id="result"></label>
                </div>

                <button class="btn btn-lg btn-login btn-block" type="submit" style="background: #002959;">Sign in</button>

                <div class="registration">
                     <div style="text-align: right;">
                        &copy; 2021 <a href="#" target="_blank">BS LOAN</a> <b>Version: </b> 1.0.0
                    </div>
                </div>
            </div>
      </form>
  @endif
</div>
</body>
<script type="text/javascript">
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
                    url: '{{ url('user/login') }}',
                    type: 'POST',
                    data: data,
                    success:function(data){
                       if(data.status == true){
                           window.location.href = data.url;
                       }else if(data.hit >= 3){
                           window.location.href = '{{ url('user/login') }}';
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
</script>
</html>
