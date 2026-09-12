<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="">

    <meta name="author" content="ThemeBucket">

    <link rel="shortcut icon" href="images/favicon.png">

    <title>Loan System</title>

    <!--Core CSS -->

    <link href="{{ asset('theme/bs3/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('theme/css/bootstrap-reset.css') }}" rel="stylesheet">

    <link href="{{ asset('theme/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->

    <link href="{{ asset('theme/css/style.css?1') }}" rel="stylesheet">

    <link href="{{ asset('theme/css/style-responsive.css') }}" rel="stylesheet"/>

    <!-- Just for debugging purposes. Don't actually copy this line! -->

    <!--[if lt IE 9]><script src="{{ asset('theme/js/ie8-responsive-file-warning.js') }}"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>

    <script src="http://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

    <script src="http://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>

    <![endif]-->
</head>

<body class="lock-screen" onload="startTime()">

    <div class="lock-wrapper">

        <div id="time"></div>


        <div class="lock-box text-center">
            <div class="lock-name">{{ !empty($name) ? $name: $username }}</div>
            <img src="{{ asset('data/users/'.$photo)  }}" alt=""/>
            <div class="lock-pwd">
                <form role="form" class="form-inline" action="/user/login" method="post">
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="username" value="{{ $username }}"/>
                        <input type="password" name="password" placeholder="Password"  class="form-control lock-input">
                        <button class="btn btn-lock" type="submit">
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function startTime()
        {
            var today=new Date();
            var h=today.getHours();
            var m=today.getMinutes();
            var s=today.getSeconds();
            // add a zero in front of numbers<10
            m=checkTime(m);
            s=checkTime(s);
            document.getElementById('time').innerHTML=h+":"+m+":"+s;
            t=setTimeout(function(){startTime()},500);
        }

        function checkTime(i)
        {
            if (i<10)
            {
                i="0" + i;
            }
            return i;
        }
    </script>
</body>
</html>