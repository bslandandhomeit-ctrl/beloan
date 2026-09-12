@extends('layouts.app')
<?php
    $errors = Session::get('error');
?>
@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/jquery-multi-select/css/multi-select.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/jquery-tags-input/jquery.tagsinput.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
@endsection
<style type="text/css">
    .form-horizontal .form-group {
        margin-right: 0px !important;
        margin-left: 0px !important;
    }
    .form-group {
        margin-bottom: 5px !important;
    }
    .red {
        color: red;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    {{ trans('unittype_config.add_unit_type_config') }}
                </header>
                <div class="panel-body">
                    @if($errors)
                        @foreach ($errors as $key => $err)
                            <div class="alert alert-danger fade in">
                                <button class="close close-sm" data-dismiss="alert">x</button>
                                {{ $err[0] }}
                            </div>
                        @endforeach
                    @endif
                    @if(Session::has('msg'))
                        <div class="alert alert-success fade in">
                            <button class="close close-sm" data-dismiss="alert">x</button>
                            {{ Session::get('msg') }}
                        </div>
                    @endif
                    <form class="cmxform form-horizontal" method="post" action="{{route('add_unittype_config')}}" id="frmDealer" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-sm-12">
                                        <label class="control-label">{{ trans('unit.unit_type') }} <span class="red"> *</span></label>
                                        <div class="input-group">
                                            <select id="unit_type_id" style="width: 100%" name="unit_type_id[]" multiple>
                                                @foreach($unit_type as $unit_types)
                                                    <option value="{{ $unit_types->id }}">{{ $unit_types->name }} ({{ isset($unit_types->Projects->dealer)?$unit_types->Projects->dealer:''}} - {{  isset($unit_types->Projects->short_code)?$unit_types->Projects->short_code:'' }} )</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-sm-12">
                                        <label class="control-label">{{ trans('report.rpt_loan_type') }}<span class="red"> *</span></label>
                                        <div class="input-group">
                                            <select id="selCate" name="loan_type" style="width: 100%">
                                                <option value="">-</option>
                                                @foreach($productsTypes as $prod)
                                                    <option value="{{$prod->id}}">{{$prod->products_type_name}}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="control-label">{{ trans('loan.l_loan_penalty_type') }} <span class="red"> *</span></label>
                                    </div>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="loan_penalty_type" id="loan_penalty_type">
                                            @foreach($static['loan_penalty_type'] as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="control-label">{{ trans('loan.l_penalty_rate_type') }} <span class="red"> *</span></label>
                                    </div>
                                    <div class="col-md-7">
                                        <select class="form-control" name="penalty_rate_type" id="penalty_rate_type">
                                            <option value="0">-</option>
                                            @foreach($static['penalty_rate_type'] as $key => $value)
                                                <option value="{{ $key }}"
                                                        @if($sacc->penalty_rate_type==$key) selected="selected" @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <label class="control-label">{{ trans('loan.l_penalty_period',['num'=>1]) }} (D)</label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="penalty_period1" id="penalty_period1" class="form-control" value="{{ $sacc->penalty_period1?$sacc->penalty_period1:7 }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <label class="control-label">{{ trans('loan.l_penalty_rate',['num'=>1]) }} <span class="text_penalty_type">(%)</span>  <span class="red">*</span></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="penalty_rate1" id="penalty_rate1" class="form-control" value="{{ $sacc->penalty_rate1?$sacc->penalty_rate1:0.70 }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row hidden" id="penal_rate2">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <label class="control-label">Penalty Period2(D)</label>
                                            </div>
                                            <div class="col-sm-4">
                                                 <input type="text" name="penalty_period2" id="penalty_period2" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <label class="control-label">Penalty Rate2 <span class="text_penalty_type">(%)</span> </label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="penalty_rate2" id="penalty_rate2" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <label class="control-label">{{ trans('loan.l_pay_off_period',['num'=>1]) }}(M) <span class="red">*</span></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="payoff_period1" id="payoff_period1" class="form-control" value="{{ $sacc->payoff_period1?$sacc->payoff_period1:6 }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <label class="control-label">{{ trans('loan.l_pay_off_rate',['num'=>1]) }} <span class="text_penalty_type"> (%) </span><span class="red"> *</span></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="pay_off_rate1" id="pay_off_rate1" class="form-control" value="{{ $sacc->pay_off_rate1?$sacc->pay_off_rate1:10.00 }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <label class="control-label">{{ trans('loan.l_pay_off_period',['num'=>2]) }} (M) <span class="red">*</span></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="payoff_period2" id="payoff_period2" class="form-control" value="{{ $sacc->payoff_period2?$sacc->payoff_period2:15 }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <label class="control-label">{{ trans('loan.l_pay_off_rate',['num'=>2]) }} <span class="text_penalty_type">(%)</span>  <span class="red"> *</span></label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="pay_off_rate2" id="pay_off_rate2" class="form-control" value="{{ $sacc->pay_off_rate2?$sacc->pay_off_rate2:5.00 }}">
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="control-label">{{ trans('loan.l_days_in_a_month') }} <span class="red"> *</span></label>
                                    </div>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="days_of_month" id="days_of_month">
                                            <option value="0">-</option>
                                            @foreach($static['days_of_month'] as $key => $value)
                                                <option value="{{ $value }}"
                                                        @if($sacc->days_of_month==$value) selected="selected" @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="control-label">{{ trans('loan.frequency') }} <span class="red"> *</span></label>
                                    </div>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="frequency" id="frequency">
                                            <option value="0">-</option>
                                            @foreach($static['payment_frequency'] as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="control-label">{{ trans('multiple.m_admin_fee') }}</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="admin_fee" id="admin_fee" class="form-control">
                                    </div>
                                    <span style="display: inline;padding-top: 15px;position: absolute;right: 3px;">%</span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="control-label">{{ trans('multiple.admin_fee_opt') }}</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="admin_fee_opt" id="admin_fee_opt">
                                            <option value='0'>One Time</option>
                                            <option value='1'>Monthly</option>
                                            <option value='2'>Yearly</option>
                                            <option value='3'>With Outstanding Balance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="control-label">{{ trans('multiple.m_maintain_fee') }}</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="maintain_fee" id="maintain_fee" class="form-control">
                                    </div>
                                    <span style="display: inline;padding-top: 15px;position: absolute;right: 3px;">%</span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="control-label">{{ trans('multiple.m_maintain_fee_opt') }}</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="maintain_fee_opt" id="maintain_fee_opt">
                                            <option value='0'>One Time</option>
                                            <option value='1'>Monthly</option>
                                            <option value='2'>Yearly</option>
                                            <option value='3'>With Outstanding Balance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="control-label">{{ trans('multiple.m_other_fee') }}</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="other_fee" id="other_fee" class="form-control">
                                    </div>
                                    <span style="display: inline;padding-top: 15px;position: absolute;right: 3px;"></span>
                                </div>

                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="control-label">{{ trans('loan.l_repayment_type') }} <span  class="red"> *</span></label>
                                    </div>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="repayment_type" id="repayment_type">
                                            <option value="0">-</option>
                                            @foreach($static['repayment_type'] as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="balloon_input" class="hidden">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.l_number_balloon') }} <span class="red">*</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" name="balloon" id="balloon" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <br>
                        <div class="row">
                            <div class="col-sm-12" style="text-align: right;">
                                <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_save') }} </button>
                                <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>&nbsp;&nbsp;{{ trans('multiple.m_reset') }} </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript"
            src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/jquery-multi-select/js/jquery.multi-select.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/jquery-multi-select/js/jquery.quicksearch.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select-init.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
    <script>
        $(document).ready(function () {
            var penalty_type = $('#loan_penalty_type').val();
            $('.text_penalty_type').text('(' + penalty_type + ')');
            $('#e9').addClass('required');
            $('#e9').on('change', function (e) {
                if ((e.val).length > 0) {
                    $(this).parent().find('label.error').css({'display': 'none'});
                    $(this).parent().find('.error').removeClass('error').addClass('valid');
                }
            });
            $('#loan_penalty_type').on('change',function(e){
                var penalty_type = $(this).val();
                $('.text_penalty_type').text('(' + penalty_type + ')');
            });
            $('#selCate').select2();
            $("#unit_type_id").select2();
            $("#penalty_rate_type").on('change',function(e){
                e.preventDefault();
                var value = $(this).val();
                if(value == 3){
                    $("#penal_rate2").removeClass('hidden');
                }else{
                    $("#penal_rate2").addClass('hidden');
                }
            });
            $("#repayment_type").on('change',function(e){
                e.preventDefault();
                var value = $(this).val();
                if(value == 3 || value == 4 || value == 5 || value == 9){
                    $("#balloon_month").prop("readonly",true);
                    $("#balloon_input").removeClass('hidden');
                    $("#monthly_payment").val(0);
                }else{
                    if(value == 7){
                        $("#monthly_amount_cl").removeClass('hidden');
                    }
                    $('#balloon').val('');
                    $("#balloon_month").val('');
                    $("#custom").html('');
                    $("#balloon_input").addClass('hidden');
                }

            });
        });
    </script>
@endsection