@extends('layouts.app')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/data-tables/dataTablesStyle.css',false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/data-tables/TableTools.css',false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',false)}}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',false) }}" />

@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
        <section class="panel">
                <div class="panel-body">
                    <ul class="nav nav-pills">
                        <li role="" class="writeCheck"><a href="#writeCheck"> Write Check </a></li>
                        <li role="" class="enterBill"><a href="#enterBill"> Enter Bill </a></li>
                        <li role="" class="deposit"><a href="#deposit"> Make Deposit </a></li>
                        <li role="" class="trans_funds"><a href="#trans_funds"> Transfer Funds </a></li>
                    </ul>
                </div>
        </section>
    </div>
</div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/accounting.min.js',false)}}"></script>
    <script src="{{ asset('theme/js/data-tables/jquery.dataTables.js',false) }}"></script>
    <script src="{{ asset('theme/js/data-tables/TableTools.min.js',false) }}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',false) }}"></script>


    <script>
         $(document).ready(function(){

             $('.nav-pills').on('click', '.writeCheck, .enterBill, .deposit,.trans_funds', function () {

                 var $this = $(this);
                 $('.nav-pills').find('.active').each(function(){
                     if($(this).hasClass('active')) {
                         $(this).removeClass('active');
                     }
                 });
                 $this.addClass('active');
                 if($this.is('.writeCheck')){

                     return AjaxCallBack('{{route('write_check')}}', 'get', 'html', {}, 'write_check',function(){});

                 } if($this.is('.enterBill')) {

                     return AjaxCallBack('{{route('enterBill')}}', 'get', 'html', {},'enterBill',function(){});

                 } if($(this).is('.deposit')) {

                     return AjaxCallBack('{{route('make_deposit')}}', 'get', 'html', {}, 'deposit', function(){} );
                 }
                 if($(this).is('.trans_funds')) {

                     return AjaxCallBack('{{route('trans_funds')}}', 'get', 'html', {}, 'trans_funds',function(){} );
                 }
             });
         });

        function call_and_delete_Loading(loading,sms, status, types){
            $('body').find(loading).each(function(){
                $(this).remove();
            });
            var stat =  status?status:'warning';
            if(types != 'undefined'){
                types = 'danger';
            }
            if(sms != undefined) {
                $('<div id="loading"></div>').appendTo('body');
                imgLoading(true,sms, 9, stat,types); /// types=[danger,success,warning]
            }
        }

        function delete_allModel(models) {

            $('body').find(models).each(function (e) {
                $(this).remove();
            });
        }

        function AjaxCallBack(url, method, dataTypes, data, modals, callBack) {

            call_and_delete_Loading('#loading','Loading.....');
            var postData = {
                _token:$('meta[name="_token"]').attr('content')
            };
            if (!$.isEmptyObject(data)) {
                postData = data;
            }
            var request = $.ajax({
                url: url,
                method: method,
                dataType: dataTypes,
                timeout: 10000,
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                },
                data: postData,
                success: function (data, status) {

                    if(status !== 'success')return call_and_delete_Loading('#loading', 'Please try again', 'warning');

                    if($.isEmptyObject(data) === false && typeof data !== 'object') {//data parse as html

                        $(data).appendTo('body');
                        call_and_delete_Loading('#loading');
                        $('#'+modals).modal({
                            keyboard: false,
                            backdrop: 'static'
                        });
                        return call_and_delete_Loading('#loading', 'Successfully!!!', status);
                    }
                    return callBack(data, status);
                }
            });
            request.error(function(httpObj, textStatus) {
                if(httpObj.status == 401 || httpObj.responseText === 'Unauthorized.') {
                    return call_and_delete_Loading('#loading', 'Your session was expired. Please refresh this page to login again', 'warning');
                }
            });
        }
    </script>

@endsection

@endsection
