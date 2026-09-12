@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('loan.writeoff_pay') }}
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

            <form class="cmxform form-horizontal" method="post" action="{{ route('loan_writeoff_pay',[$loan_id]) }}" id="frmWriteOffPay" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.repayment_date') }} <span class="red">*</span></label>
                    <div class="input-append date dpYears col-sm-6" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}">
                        <input type="text" value="{{($dpDateClone)?$dpDateClone:date('Y-M-d')}}" class="form-control" name="repay_date" id="repay_date">
                            <span class="add-on offonDatepicker">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('report.remaining_wo_balance') }} <span class="red">*</span></label>
                    <div class="col-sm-6">
                        <p type="text">{{number_format($loan_writeoff->writeoff->write_off_outst_balance - $wo_prev_paid, 2)}}</p>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('report.dd_balance') }} <span class="red">*</span></label>
                    <div class="col-sm-6">
                        <p type="text">{{number_format($dd_balance, 2)}}</p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('report.repay_amount') }} <span class="red">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="repay_amount" id="repay_amount" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('multiple.m_note') }}</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="note" name="note"></textarea>
                    </div>
                    <input type="hidden" name="dd_balance" value="{{$dd_balance}}"/>

                </div>
                <div class="form-group">
                    <label class="col-sm-3"></label>
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                        <a href="javascript:history.back();" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
        /* 
        $("#close_on").on("change", function () {
            //var loan_data = <?php echo json_encode($search_results);?>;
            var loan_id = "<?php echo $loan_id; ?>";

            $('#dpDateClone').val($("#close_on").val());//Add value to dpDate after submit
        });
        var total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val()) + parseFloat($('#penalty').val())) *100)/100;
        $('#total_amount').val(total);
        $("#prin_amount").on("change", function () {
            var total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val()) + parseFloat($('#penalty').val())) *100)/100;
        $('#total_amount').val(total);
        });
        $("#air_amount").on("change", function () {
            var total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val()) + parseFloat($('#penalty').val())) *100)/100;
        $('#total_amount').val(total);
        });
        $("#fee_amount").on("change", function () {
            var total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val()) + parseFloat($('#penalty').val())) *100)/100;
        $('#total_amount').val(total);
        });
        $("#penalty").on("change", function () {
            var total = Math.round((parseFloat($('#prin_amount').val()) + parseFloat($('#air_amount').val()) + parseFloat($('#fee_amount').val()) + parseFloat($('#penalty').val())) *100)/100;
        $('#total_amount').val(total);
        });
        */
    </script>
 @endsection