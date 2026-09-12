@extends('layouts.app')

@section('content')
<section class="panel">
    <header class="panel-heading">
        End Of Date
    </header>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                Are all till accounts closed?<br/>
            </div>
            <div class="col-md-3">
                <?php $check = true; ?>
                <?php foreach ($status as $s) { ?>
                    <?php if ($s->status == 0) $check = false; ?>
                <?php } ?>
                <input type="checkbox" class="account_close" name="name" value="check" <?php if ($check) echo 'checked="checked"' ?> /> Check
            </div>
            <div class="col-md-3">
                <span><a href="{{route ('chiefofteller') }}" class="btn btn-info">Verify</a></span>
                <span><a href="{{route('enable_till')}}" class="btn btn-success">Execute</a></span>
            </div>
        </div>

        <div class="clear-fix"></div>

        <div class="row">
            <div class="col-md-3">
                Are Accrued Interest execution done?<br/>
            </div>
            <div class="col-md-3">
                <input type="checkbox" name="name" value="check"  class="account_close"<?php if ($foo) echo 'checked="checked"'?> /> Check
            </div>
            <div class="col-md-3">
                <span><a href="{{route ('list_loan') }}?flag=1" class="btn btn-info">Verify</a></span>
                <span><a href="{{route('accrued_verify')}}" class="btn btn-success">Execute</a></span>
            </div>
        </div>

        <div class="clear-fix"></div>

        <div class="row">
            <div class="col-md-3">
                Auto repayment done?<br/>
            </div>
            <div class="col-md-3">
                <input type="checkbox" name="name" value="check"  class="account_close"<?php if ($foo) echo 'checked="checked"'?> /> Check
            </div>
            <div class="col-md-3">
                <span><a href="{{route ('auto_payment') }}" class="btn btn-info">Verify</a></span>
                <span><a href="{{route('auto_payment')}}" class="btn btn-success">Execute</a></span>
            </div>
        </div>

        <div class="clear-fix"></div>

        <div class="row">
            <div class="col-md-3">
                Are Fixed Asset accumulation is done?<br/>
            </div>
            <div class="col-md-3">
                <input type="checkbox" name="name" value="check"  class="access_close"<?php if ($asset) echo 'checked="checked"'?> /> Check
            </div>
            <div class="col-md-3">
                <span><a href="{{route ('asset_depreciation_execute') }}?flag=0" class="btn btn-info">Verify</a></span>
                <span><a href="{{route('asset_depreciation_execute')}}?flag=1" class="btn btn-success">Execute</a></span>
            </div>
        </div>

        <div class="clear-fix"></div>

        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="col-md-3">
                <span><a href="{{route('enable_till')}}" class="btn btn-success confirm_end_of_date">EOD</a></span>
            </div>
        </div>

    </div>
</section>
@endsection


@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',false) }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('.confirm_end_of_date').on('click', function () {
        bootbox.confirm("{{ trans('loan.l_customer_exec_eod') }}", function (result) {
            if (result) {
                //$('#addloanForm').submit();
                if($(".account_close").is(':checked')){
                    bootbox.alert("Not Success");
                }else{
                    bootbox.alert("Success");
                }
            }
        });

        return false;
    });
});
</script>
@endsection
