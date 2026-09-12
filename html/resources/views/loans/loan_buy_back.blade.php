@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            Loan Buy Back
        </header>

        <div class="panel-body">
            @if(Session::has('msg'))
                <div class="alert alert-success fade in">
                    <button class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('msg') }}
                </div>
            @endif
            @if($errors->has())
                <div class="alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    {{ HTML::ul($errors->all()) }}
                </div>
            @endif

            @if(Session::has('danger'))
                <div class="alert alert-danger fade in">
                    <button class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('danger') }}
                </div>
            @endif

            <form class="cmxform form-horizontal" method="post" action="{{ route('loan_buy_back',[$loan_id]) }}" id="frmCloseLoan" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('multiple.m_note') }}</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="note" name="note"></textarea>
                        <input type="hidden" class="form-control" id="unit_code" value="{{$unit_code->code}}" />
                        <input type="hidden" class="form-control" id="balance" value="{{$loan_account->balance}}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"></label>
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary" id="save"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                        <a href="javascript:history.back();" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
                    </div>
                </div>
                <input type = "hidden" value="{{round($air_arr["ex_air_amount"],2)}}" name="ex_air_amount" id="ex_air_amount" >
            </form>
            <form role="form" class="cmxform form-inline text-center" method="get" action="{{ route('loan_close',[$loan_id])  }}">
                
                <input type = "hidden" name="ctr_id" id="ctr_id" >
                <input type = "hidden" name="dpDateClone" id="dpDateClone" />
            </form>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/accounting.min.js')}}"></script>
    <script type="text/javascript">
         $("#save").click(function(){
        var tranType='Terminate';
        var prin_amount =parseInt($("#prin_amount").val());
        var balance =parseInt($("#balance").val());
        var code =$("#unit_code").val();
        var note=$("#note").val();           
        $.ajax({
            url:'https://contract.chaktomukcity.com/api/unit_actions/change_unit_statu_from_be_cash',
            type:'GET',
            headers: {
                'Access-Control-Allow-Origin': '*',
                'Content-Type':'application/json'
                    },
            dataType: 'jsonp',
            data:{'code':code,'note':note,type:tranType},
            success:function(data){                            
            },
            error: function (data) {
                console.log(data);
            }
        });


            
        });
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
        $("#close_on").on("change", function () {
            //var loan_data = <?php echo json_encode($search_results);?>;
            var loan_id = "<?php echo $loan_id; ?>";

            $('#dpDateClone').val($("#close_on").val());//Add value to dpDate after submit
            $('._search').trigger('click');
        }); 
        $("#transType").on("change", function () {
            if($("#transType").val() == "Write-Off" || $("#transType").val() == "Terminate"){
                var wo_total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val())) *100)/100;
                $("#total_wo_amount").val(accounting.formatNumber(wo_total,2));
                $("#wo_div").removeClass('hidden');
                $(".write-off-text").text($("#transType").val()+' Amount');
            }
            else{
                 $("#wo_div").addClass('hidden');
            }
        });
        var total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val()) + parseFloat($('#penalty').val())) *100)/100;
        $('#total_amount').val(total);
        $("#prin_amount").on("change", function () {
            var total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val()) + parseFloat($('#penalty').val())) *100)/100;
        $('#total_amount').val(total);
            var wo_total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val())) *100)/100;
            $("#total_wo_amount").val(accounting.formatNumber(wo_total,2));

        });
        $("#air_amount").on("change", function () {
            var total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val()) + parseFloat($('#penalty').val())) *100)/100;
        $('#total_amount').val(total);
            var wo_total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val())) *100)/100;
            $("#total_wo_amount").val(accounting.formatNumber(wo_total,2));
        });
        $("#fee_amount").on("change", function () {
            var total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val()) + parseFloat($('#penalty').val())) *100)/100;
        $('#total_amount').val(total);
            var wo_total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val())) *100)/100;
            $("#total_wo_amount").val(accounting.formatNumber(wo_total,2));

        });
        $("#penalty").on("change", function () {
            var total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val()) + parseFloat($('#penalty').val())) *100)/100;
        $('#total_amount').val(total);
        });
    </script>
 @endsection