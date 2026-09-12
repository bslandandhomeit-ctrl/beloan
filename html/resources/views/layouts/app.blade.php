<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="content-type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8"> -->

    <meta name="description" content="">

    <meta name="author" content="FIGIX">
    <meta name="_token" id="_token" value="{{csrf_token()}}" content="{{csrf_token()}}" />
    <link rel="icon" href="{{ asset('favicon.png', $secure) }}" type="image/png" sizes="20x20">
    <title>Loan System</title>

    <!--Core CSS -->

    <link href="{{ asset('theme/bs3/css/bootstrap.min.css', $secure) }}" rel="stylesheet">
    <link href="{{ asset('theme/css/bootstrap-reset.css', $secure) }}" rel="stylesheet">
    <link href="{{ asset('theme/font-awesome/css/font-awesome.css', false) }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('theme/css/style.css', false) }}" rel="stylesheet">
    <link href="{{ asset('theme/css/style-responsive.css', false) }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.bootstrap.min.css') }}"/>
    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="{{ asset('theme/js/ie8-responsive-file-warning.js',$secure) }}"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>

    <script src="http://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

    <script src="http://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>

    <![endif]-->
    <style>
    @media print {
        a[href]:after {
            content: none !important;
        }
        a[href]{
            text-decoration:none !important;
        }
        div.dataTables_wrapper>.row:first-child, div.dataTables_wrapper>.row:last-child{
            display:none;
        }
        .sorting::after, .sorting_asc::after{
            content:none !important;
        }
        .hide-this, .fa-check{
            display:none;
        }
    }
    #loading {
        width: 200px;
        height: 67px;
        background-color: rgba(31, 181, 173, 0.45);
        position: absolute;
        right: 15px !important;
        text-align: center;
        border-radius: 9px;
        padding: 9px;
        top: 82px;
    }
    </style>
    @yield('css')
</head>
<body>
<?php
    $collapse = Session::get('collapse');
    $merge_left = '';
    if($collapse == 1){
        $merge_left = 'merge-left';
    }
?>
<section  id="container">
    <!-- header start -->
    @include('layouts.header')
    <!-- header end -->
    <!--sidebar start-->
     @include('layouts.sidebar')
    <!--sidebar end-->

    <!--main content start-->
    <section id="main-content" class="{{ $merge_left }}">
        <section class="wrapper">
         <!--main content goes here-->
         @yield('content')
        </section>
    </section>
    @include('layouts.notifybar')
    <!--main content end-->
</section>
<!--Core js-->

<script src="{{ asset('theme/js/jquery.js',$secure) }}"></script>

<script src="{{ asset('theme/bs3/js/bootstrap.min.js',$secure) }}"></script>

<script src="{{ asset('theme/js/jquery.dcjqaccordion.2.7.js',$secure) }}"></script>

<script src="{{ asset('theme/js/jquery.scrollTo/jquery.scrollTo.min.js',$secure) }}"></script>

<script src="{{ asset('theme/js/jquery.nicescroll.js',$secure) }}" type="text/javascript"></script>

<script src="{{ asset('theme/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js',$secure) }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
<!--common script init for all pages-->
<script src="{{ asset('theme/js/scripts.js',$secure) }}"></script>

<!-- CHUCH add event tracker -->
<script src="{{ asset('js/bootbox.js',$secure) }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('input').attr('autocomplete','off');
    $('table.ddd').DataTable( {
        //scrollY: '50vh',
        //scrollCollapse: false
    });
    $('.dataTables_filter').addClass('pull-right');

  $("#sb_export_db").click(function(){
    var con = confirm("You are about to export current database. Are you sure?");
    if(con){
      document.location.href="{!! route('export_db'); !!}";
    }
  });
	var IDLE_TIMEOUT = '<?php echo IDLE_TIMEOUT?>'; // in seconds
    var IDLE_ALERT = '<?php echo IDLE_ALERT?>';
	var i = 0;

	$(document).click(function(e) {

	  i = 0;
	});
	$(document).mousemove(function(e){
		i = 0;
	});
	$(document).keypress(function(e){
		i =0;
	});

	//setInterval(CheckIdleTime, 1000); // do it every second
	function CheckIdleTime() {
		if(i==IDLE_ALERT){
			bootbox.alert("You seem don't have action during "+IDLE_ALERT+" seconds!", function() {});
			i=IDLE_ALERT;
		}

		if (i >= IDLE_TIMEOUT) {
			$.ajax({
	            url: '/user/reset_session',
	            type: 'GET',
	            dataType: "json",
	            processData: false,
	            contentType: false,
	            data: 'u=1',
	            success: function(res){
	            	location.reload();
		        }
			});
		}
		i++;
	}


    $(document).on('click', '.do-delete', function () {
        this_link = $(this).attr('href');
        bootbox.confirm({
            message: "Are you sure, you want to delete?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-danger'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-success'
                }
            },
            callback: function (result) {
                if(result) window.location = this_link;
            }
        });

        return false;
    });
    
   
});
function change_collapse(){
    var collapse = "<?=Session::get('collapse')?>";
    if(collapse == 1){
        $.ajax({
            url: '/collapse/0',
            type: 'GET',
            dataType: "json",
            processData: false,
            contentType: false,
            data:{},
            success: function (data) {
            }
        });
    }else{
        $.ajax({
            url: '/collapse/1',
            type: 'GET',
            dataType: "json",
            processData: false,
            contentType: false,
            data:{},
            success: function (data) {
            }
        });
    }
}
</script>

<!--script for this page-->
@yield('js')

</body>
</html>
