<?php
function html_form($ilabel, $irequired_flg, $iname, $iid, $value)
{
    $value = $value ? $value : "";
    $str = '<div class="col-sm-5"><label class="control-label">' . $ilabel;
    if ($irequired_flg == true) {
        $str .= '<span class="red">*</span>';
    }

    $str .= '</label></div>
                <div class="col-sm-7">
                        <input type="text" name="' . $iname . '" id="' . $iid . '" class="form-control" value="' . $value . '">
                    </div>';
    return $str;
}
?>
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <link href="{{ asset('theme/js/iCheck/skins/flat/green.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <style type="text/css">
        td.none-border {
            border-top: none !important;
            border-bottom: none !important;
        }
        td.no-border {
            border-bottom: none !important;
        }
        .tbrepayment tr, .tbrepayment td {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')
    <section class="panel">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
        @endif
        <?php $static = config('static_data');
        $prod_array = array('' => '');
        ?>
        <header class="panel-heading">
            {{ trans('loan.l_loan_reschedule') }}
        </header>
        <div class="panel-body">
            @if(Session::has('msg'))
                <div class="alert alert-success fade in">
                    <button class="close close-md" data-dimdiss="alert">x</button>
                    {{ Session::get('msg') }}
                </div>
            @endif
            @if($errors->has())
                <div class="alert alert-danger fade in">
                    <button type="button" class="close" data-dimdiss="alert"></button>
                    {{ HTML::ul($errors->all()) }}
                </div>
            @endif
            @if(Session::has('danger'))
                <div class="alert alert-danger fade in">
                    <button class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('danger') }}
                </div>
            @endif
            <form action="{{route('loan_restructure',[$loan->id])}}" method="POST" class="cmxform form-horizontal" id="addloanForm" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="lid" value="{{ $loan->id }}"/>
                <fieldset>
                    <div class="row">
                        <!-- Grid to right  -->
                        <div class="col-sm-12">
                            <span>{{ trans('restructure.restructure_info') }}</span>
                            <hr/>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.restructure_date') }} <span class="red">*</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-append date dpYears" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}">
                                                <input type="text" value="{{($dpDateClone)?$dpDateClone:date('Y-m-d')}}" class="form-control" name="restructure_date" id="restructure_on">
                                                    <span class="add-on offonDatepicker">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('report.principal') }} <span class="red">*</span></label>
                                        </div>
                                        <?php 
                                            //$principal = $loan->client_loan_account->balance;
                                            $principal = $sum_principal;
                                        ?>
                                        <div class="col-sm-7">
                                            <input type="text" readonly value="{{ round($principal,2) }}" class="form-control" name="prin_amount" id="prin_amount" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.l_down_payment') }} <span class="red">*</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" value="{{round($sum_down_payment,2)}}" class="form-control" name="re_down_payment" id="re_down_payment" readonly />
                                        </div>
                                    </div>
                                    <?php 
                                        $air_arr = get_total_int_till_today_restructure($loan ,$loan->client_loan_account, $dpDateClone,$principal)['interest'];
                                        $air_amount = $air_arr;
                                    ?>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('report.interest') }} <span class="red">*</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" value="{{round($air_amount,2)}}" class="form-control" name="air_amount" id="air_amount" />
                                        </div>
                                    </div>
                                    <?php 
                                        foreach($sch_repay_arr as $ar){
                                            $t_sch_penal += $ar['penalty'];
                                            $t_sch_fee += $ar['fee'];
                                            $t_sch_other_fee += $ar['other_fee'];
                                        }
                                    ?>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('report.fee') }} <span class="red">*</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" value="{{round($t_sch_fee,2)}}" class="form-control" name="fee_amount" id="fee_amount" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('report.other_fee') }} <span class="red">*</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" value="{{round($t_sch_other_fee,2)}}" class="form-control" name="other_fee_amount" id="other_fee_amount" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('report.penalty') }} <span class="red">*</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" value="{{round($t_sch_penal,2)}}" class="form-control" name="penalty" id="penalty" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('multiple.m_note') }}</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <textarea class="form-control" id="note" name="note"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- Grid to left -->
                                <!-- Grid to right  -->
                                @if($sum_down_payment > 0)
                                <div class="col-sm-4">
                                    <div class="row" style="margin-bottom: 15px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">Selected Payment Option<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <select id="payment_option" name="payment_option" style="width: 100%">
                                                    <option value=""> - </option>
                                                </select>
                                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Has Down Payment ?<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <select id="hase_down_payment" name="hase_down_payment" class="form-control" readonly>
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Down Payment Duration<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="down_payment_duration" name="down_payment_duration" value="" readonly/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="input-group" style="margin-top: 10px;">
                                                <select id="down_payment_type" name="down_payment_type" class="form-control" readonly>
                                                    <option value="%">Down Payment %</option>
                                                    <option value="$">Down Payment $</option>
                                                </select>
                                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="down_payment_value" name="down_payment_value" value="" readonly/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Installment Duration (Months)<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="installment_duration" name="installment_duration" value="" readonly/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Annual Interest (%)<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="annual_interest" name="annual_interest" value="0" readonly/>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">Diposit Date<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}" class="input-append date dpYears">
                                                <input type="text" name="deposit_date" value="{{date('Y-m-d')}}" size="16" class="form-control" id="deposit_date">
                                                <span class="add-on birhtdateDatepicker">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">Start Payment Date<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}" class="input-append date dpYears">
                                                <input type="text" name="start_payment_date" value="{{date('Y-m-d')}}" size="16" class="form-control" id="start_payment_date">
                                                <span class="add-on birhtdateDatepicker">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-sm-4">
                                    <?php
                                        $total_amount_loan = $principal + $air_amount + $t_sch_fee + $t_sch_other_fee + $t_sch_penal + $sum_down_payment;
                                    ?>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.l_sell_price') }} ($)</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" name="sell_price" id="sell_price" readonly value="{{ $total_amount_loan?round($total_amount_loan,2):'' }}"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('restructure.drawdown_principal_amount') }} <span class="red">*</span></label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" name="drawdown_principal_amount" id="drawdown_principal_amount" class="form-control" value="0"/>
                                        </div>
                                    </div>
                                    @if($sum_down_payment > 0)
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.l_down_payment') }} <span class="red">*</span></label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" name="down_payment" id="down_payment" class="form-control" value="{{ round($sum_down_payment,2) }}"/>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.loan_amount') }} ($)<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" name="loan_amount" id="loan_amounts" readonly value="{{ round($total_amount_loan,2) }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('report.rpt_tenure') }}(M) <span class="red">*</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" name="loan_duration" id="loan_duration" class="form-control" value="{{ $loan_repay->loan_duration }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.l_interest_rate') }}(P.M) <span class="red">*</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" name="interest_rate" id="interest_rate" class="form-control" value="{{ $loan_repay->interest_rate }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.l_loan_penalty_type') }} <span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <select class="form-control" name="loan_penalty_type" id="loan_penalty_type">
                                                @foreach($static['loan_penalty_type'] as $key => $value)
                                                    <option value="{{ $key }}" {{ $loan_repay->loan_penalty_type==$key?'selected':''}}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.l_penalty_rate_type') }} <span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <select class="form-control" name="penalty_rate_type" id="penalty_rate_type">
                                                <option value="0">-</option>
                                                @foreach($static['penalty_rate_type'] as $key => $value)
                                                    <option value="{{ $key }}" {{ $loan_repay->penalty_rate_type==$key?'selected':''}}>{{ $value }}</option>
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
                                                    <input type="text" name="penalty_period1" id="penalty_period1" class="form-control" value="{{ $loan->penalty_period1 }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <label class="control-label">{{ trans('loan.l_penalty_rate',['num'=>1]) }} <span class="text_penalty_type"> (%)</span><span class="red"> *</span></label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" name="penalty_rate1" id="penalty_rate1" class="form-control" value="{{ $loan->penalty_rate1 }}">
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
                                                    <input type="text" name="penalty_period2" id="penalty_period2" class="form-control" value="{{ $loan->penalty_period2}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <label class="control-label">Penalty Rate2 <span class="text_penalty_type">(%)</span></label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" name="penalty_rate2" id="penalty_rate2" class="form-control" value="{{ $loan->penalty_rate2 }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                         <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <label class="control-label">{{ trans('loan.l_pay_off_period',['num'=>1]) }} (M)<span class="red">*</span></label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" name="payoff_period1" id="payoff_period1" class="form-control" value="{{ $loan->payoff_period1 }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <label class="control-label">{{ trans('loan.l_pay_off_rate',['num'=>1]) }} <span class="text_penalty_type"> (%)</span><span class="red"> *</span></label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" name="pay_off_rate1" id="pay_off_rate1" class="form-control" value="{{ $loan->pay_off_rate1 }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <label class="control-label">{{ trans('loan.l_pay_off_period',['num'=>2]) }} (M)<span class="red"> *</span></label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" name="payoff_period2" id="payoff_period2" class="form-control" value="{{ $loan->payoff_period2 }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <label class="control-label">{{ trans('loan.l_pay_off_rate',['num'=>2]) }} <span class="text_penalty_type"> (%)</span><span class="red"> *</span></label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" name="pay_off_rate2" id="pay_off_rate2" class="form-control" value="{{ $loan->pay_off_rate2 }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             @if($sum_down_payment > 0)   
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <span>{{ trans('loan.l_terms') }}</span>
                                    <hr/>
                                </div>
                            @endif
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4"></div>
                                
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-5">  
                                            <label class="control-label">{{ trans('loan.l_days_in_a_month') }}<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <select class="form-control" name="days_of_month" id="days_of_month">
                                                <option value="0">-</option>
                                                @foreach($static['days_of_month'] as $key => $value)
                                                    <option value="{{ $value }}" {{ $loan->days_of_month==$value?'selected':''}}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.frequency') }}<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <select class="form-control" name="frequency" id="frequency">
                                                <option value="0">-</option>
                                                @foreach($static['payment_frequency'] as $key => $value)
                                                    <option value="{{ $key }}" {{ $loan->frequency==$key?'selected':''}}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div id="annual_yield" name="annual_yield" value="{{$loan->annual_yield}}" class="hidden">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.l_exclude_holidays') }} <span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7 icheck ">
                                            <div class="flat-green single-row holiday">
                                                <div class="radio ">
                                                    <input type="checkbox" id="holiday_flag" name="holiday_flag" value="1" {{ ($loan->holiday_flag == 1) ? 'checked' : '' }}/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('multiple.m_expect_disburse_date') }}</label>
                                        </div>
                                        <div data-date-viewmode="years" data-initialize="datepicker" class="input-append date dpYears col-sm-7">
                                            <input type="text" name="disburse_date" value="{{ date('Y-m-d',strtotime($start_payment_date)) }}" size="16" class="form-control" id="disburse_date">
                                                <span class="add-on">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('multiple.m_start_date') }}<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div data-date-viewmode="years" data-initialize="datepicker" data-date="{{ $start_payment_date?date('Y-m-d',strtotime($start_payment_date.'+1 month')):date('Y-m-d')}}" class="input-append date dpYears">
                                                <input type="text" name="start_date"
                                                       value="{{ $start_payment_date?date('Y-m-d',strtotime($start_payment_date.'+1 month')):date('Y-m-d')}}"
                                                       size="16" class="form-control" id="start_date">
                                                <span class="add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.l_repayment_type') }}<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <select class="form-control" name="repayment_type" id="repayment_type">
                                                <option value="0">-</option>
                                                @foreach($static['repayment_type'] as $key => $value)
                                                    <option value="{{ $key }}" {{ $loan->repayment_type==$key?'selected':'' }}>{{ $value }}</option>
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
                                                <input type="text" name="balloon" id="balloon" class="form-control" value="{{$loan->balloon}}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label class="control-label">{{ trans('loan.l_custom_flag') }}</label>
                                            </div>
                                            <div class="col-sm-7 icheck ">
                                                <div class="flat-green single-row customize">
                                                    <div class="radio ">
                                                        <input type="checkbox" id="custom_flag"
                                                               name="custom_flag" {{ $loan->custom_flag==1?'checked':'' }}/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label class="control-label">{{ trans('loan.l_balloon_month') }} <span class="red"> *</span></label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="text" name="balloon_month" id="balloon_month" class="form-control" value="{{$loan->balloon_month}}">
                                            </div>
                                            <i class="control-label col-md-9 red">( {{ trans('loan.rpt_ballon_msg') }}
                                                <b> ","</b>. Ex: 12,24,36 )</i>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label class="control-label col-sm-3">{{ trans('loan.l_monthly_pay') }} ($)</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="text" name="monthly_payment" id="monthly_payment" class="form-control" readonly value="{{ $loan->monthly_payment?$loan->monthly_payment:0 }}">
                                            </div>
                                        </div>
                                        <div id="custom">
                                        </div>
                                        <div id="balloon_amount_array" name="balloon_amount_array">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  Repayment Info Button -->
                    <div class="row"></div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12" style="text-align: right;">
                                <span>
                                    <a class="btn btn-info" href="#mSchedule" data-toggle="modal" id="view_pay_schedule">
                                        <i class="fa fa-eye"></i>
                                        {{ trans('loan.l_repayment_schedule') }}
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"></div>
                            <label id="saving" class="" style="display: none;color: #8175C7;padding-left: 10px;">Saving...</label>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="text-align: right;">
                                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i>&nbsp;{{ trans('multiple.m_save') }}</button>
                                <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>&nbsp;{{ trans('multiple.m_reset') }}</button>
                                <button type="button" class="btn btn-danger" onclick="javascript:history.back();"><i class="fa fa-times-circle"></i>&nbsp;{{ trans('multiple.m_cancel') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
            <form role="form" class="cmxform form-inline text-center" method="get" action="{{ route('loan_restructure',[$loan_id])  }}">
                <input type = "hidden" name="ctr_id" id="ctr_id" >
                <input type = "hidden" name="dpDateClone" id="dpDateClone" />
                <div class="form-group">
                    <button type="submit" class="btn btn-info _search"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                </div>
            </form>
        </div>
        <div>
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="mSchedule" class="modal fade">
                <div class="modal-dialog md-modify">
                    <div class="modal-content">
                        <div id="printArea">
                            @include('api.report_header',['co_phone'=>!empty($co_id->co_user) ? $co_id->co_user->phone: ''])
                            <div class="modal-header bo-border">
                                <ul id="language" class="pull-right">
                                    <li>
                                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                                    </li>
                                    <li>
                                        <button aria-hidden="true" data-dismiss="modal" class="btn btn-danger" id="close">×
                                        </button>
                                    </li>
                                </ul>
                                <h4 class="modal-title schedule_title">{{ trans('loan.l_repayment_schedule') }}</h4>
                            </div>
                            <div class="modal-body" id="schedule-table"></div>
                            <div class="signature">
                                <div class="left_content">
                                    <p style="text-align: center;">ហត្ថលេខាគណនេយ្យករ</p>
                                    <br><br>
                                    <p>.............................................................</p>
                                    <p>ឈ្មោះ/Name:</p>
                                    <br>
                                    <p>ចុះថ្ងៃទី............/............./.....................</p>
                                </div>
                                <div class="right_content">
                                    <p style="text-align: center">ស្នាមមេដៃស្តំាកូនបំណុល</p>
                                    <br><br>
                                    <p>.............................................................</p>
                                    <p>ឈ្មោះ/Name:</p>
                                    <br>
                                    <p>ចុះថ្ងៃទី............/............./.....................</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/accounting.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.editloan.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/iCheck/jquery.icheck.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/restructure-loan.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
        $("#restructure_on").on("change", function () {
            //var loan_data = <?php echo json_encode($search_results);?>;
            var loan_id = "<?php echo $loan_id; ?>";
            $('#dpDateClone').val($("#restructure_on").val());
            $('._search').trigger('click');
        });
        $(document).ready(function () {
            var rate_type = <?php echo $loan->penalty_rate_type;?>;
            var repayment_type = <?php echo $loan->repayment_type;?>;
            var chck = $("#custom_flag").prop('checked');
            var penalty_type = $('#loan_penalty_type').val();
            $('.text_penalty_type').text('(' + penalty_type + ')');
            if (chck) {
                $("#monthly_payment").prop('readonly', false);
            }
            var unit_id = '{{ $loan->unit_id }}';
            @if($sum_down_payment > 0)
            if(unit_id > 0){
                getUnitInfo(unit_id);
            }
            @endif
            var data = <?php echo json_encode($loan->product);?>;
            new AddLoan(data);
            if (rate_type == 3) {
                $("#penal_rate2").removeClass('hidden');
            }
            if (repayment_type == 3 || repayment_type == 4 || repayment_type == 5) {
                $("#balloon_input").removeClass('hidden');
                $("#balloon").keyup();
            }
            $('#unit_id').select2();
            $("#project_id").select2();
            $("#unit_type_id").select2();
            $('#payment_option').addClass('required');
            $("#payment_option").select2();
            $('#loan_penalty_type').on('change',function(e){
                var penalty_type = $(this).val();
                $('.text_penalty_type').text('(' + penalty_type + ')');
            });
            $(document).on('click', '.t-generate', function () {
                f_this = $(this);
                f_this.attr('disabled', 'disabled');
                $('#t-result').html('');
                $('#t-result-hide').html('');
                var k = 0;
                disburse_on = [$('#start_date').val()];
                $('#t-disburse-hide span').each(function () {
                    disburse_on.push($(this).text());
                });
                $('#t-disburse-hide').text('');
                $('.t-row input[name="s_schedule_date[]"]').each(function (indexx) {
                    var this_ = $(this);
                    setTimeout(function () {
                        l_tenure = this_.parents('tr').find('input[name="s_term[]"]').val();
                        c_principal = this_.parents('tr').find('input[name="s_principal[]"]').val();
                        l_start_date = this_.parents('tr').find('input[name="s_schedule_date[]"]').val();
                        ppi = this_.parents('tr').find('select[name="s_ppi[]"]').val();
                        charge = this_.parents('tr').find('input[name="s_charge[]"]').val();
                        if ($('#t-result table').html() != '' && $('#t-result table').html() != null) {
                            l_amount = $('#t-result table tr.last_row:last td.col_5 .default-val').text();
                            l_amount = l_amount.replace(',', '');
                        } else {
                            l_amount = $("#loan_amounts").val();
                        }
                        var repay_type = $("#repayment_type").val();
                        var chck = $("#custom_flag").prop('checked');
                        var holiday_chck = $("#holiday_flag").prop('checked');
                        var round = $('#round').val();
                        var balloon_input = $(".balloon_input").map(function () {
                            return $(this).val();
                        }).get().join();
                        $("#balloon_amount_array").val(balloon_input);
                        var data = {
                            l_client_name: $("#client-name").val(),
                            l_days_of_month: $("#days_of_month").val(),
                            l_start_date: l_start_date,
                            l_amount: l_amount,
                            l_tenure: l_tenure,
                            l_rate: $("#interest_rate").val(),
                            l_repayment_type: repay_type,
                            l_balloon_num: $("#balloon").val(),
                            l_balloon_month: $("#balloon_month").val(),
                            l_monthly_pay: $("#monthly_payment").val(),
                            l_bal_amount_array: balloon_input,
                            custom_flag: chck ? 1 : 0,
                            holiday_flag: holiday_chck ? 1 : 0,
                            round,
                            c_principal: c_principal,
                            disburse_on: disburse_on[k],
                            ppi: ppi,
                            charge: charge,
                            frequency: $('#frequency').val()
                        };
                        k++;
                        $.ajax({
                            url: '/api/loan/repaymentinfo',
                            type: 'GET',
                            data: data,
                            success: function (data) {
                                if ($('#t-result table').length == 0) {
                                    $('#t-result').html(data);
                                    $('.tbrepayment').remove();
                                }else{
                                    var data_last = [0];
                                    $('#t-result table:last tr:last td').each(function () {
                                        val_ = $(this).text();
                                        val_ = val_.replace(',', '');
                                        data_last.push(val_);
                                    });

                                    $('#t-result table:last tr:last').remove();
                                    $('.last_row td.col_5').attr('bgcolor', '');
                                    $("#t-result-hide").html(data);
                                    $('.tbrepayment').remove();

                                    //prepare data table
                                    $("#t-result-hide table:last tbody tr:first").remove(); //remove 0
                                    tr_length = $('#t-result table tr').length - 2;
                                    i = 0;
                                    $("#t-result-hide table:last tbody tr").each(function () {
                                        i++;
                                        j = tr_length + i;
                                        //$(this).removeClass('row_'+i);
                                        //$(this).addClass('row_'+j);
                                        if ($(this).find('td').attr('colspan') != 2) $(this).find('td:first').text(j);
                                    });
                                    tbody = $("#t-result-hide table:last tbody").html();
                                    $('#t-result table tbody').append(tbody);

                                    index = 0;
                                    $('#t-result table:last tr:last td').each(function () {
                                        index++;
                                        val_ = $(this).text();
                                        val_ = val_.replace(',', '');
                                        if (index >= 2 && index < 6) val_ = parseFloat(val_) + parseFloat(data_last[index]);
                                        if (index == 2) {
                                            $(this).text(val_);
                                        } else if (index > 2 && val_ != '') {
                                            $(this).text(accounting.formatMoney(val_, ''));
                                        }
                                    });
                                }
                                $('#t-disburse-hide').append('<span id="disburse_' + k + '">' + $('.last_row:last td.col_0 input[name="repayment_date[]"]').val() + '</span>');
                                //remove first repayment_date, repayment_principal
                                $('.row_0 input[name="repayment_date[]"]').remove();
                                $('.row_0 input[name="repayment_principal[]"]').remove();
                                f_this.removeAttr('disabled');
                            }
                        });
                    }, (indexx + 1) * 2000);
                });
            });
            $(document).on('click', '.t-remove', function () {
                $(this).parents('tr').remove();
                $('#t-result').html('');
                $('.t-generate').trigger('click');
            });
            $(document).on('change', '#start_payment_date', function () {
               addFirstCollectionDate();
            });
            $(document).on('change', '#payment_option', function () {
                var payment_option = $(this).val();
                $('#down_payment_duration').val('');
                $('#down_payment_value').val('');
                $('#installment_duration').val('');
                $('#annual_interest').val(0);
                $('#loan_duration').val(0);
                $('#interest_rate').val(0);
                $('#discount_payment_option').val(0);
                // if(payment_option > 0){
                    getPaymentOption(payment_option);
                    calculateLoan();
                // }
            });
            $(document).on('input','#prin_amount, #re_down_payment, #air_amount, #fee_amount, #other_fee_amount, #penalty, #drawdown_principal_amount', function(){
                calculateLoan();
            });
            $(document).on('input','#down_payment, #drawdown_principal_amount', function(){
                calculateFinal();
            });
            $(document).on('input','#down_payment_duration,#down_payment_value,#installment_duration,#annual_interest,#discount_payment_option,#down_payment_type',function(){
                calculateLoan();
                calculateDownpayment();
            });
        });
        function getUnitInfo(unit_id){
            $('#down_payment_duration').val('');
            $('#down_payment_value').val('');
            $('#installment_duration').val('');
            $('#annual_interest').val(0);
            $('#loan_duration').val(0);
            $('#interest_rate').val(0);
            $('#discount_payment_option').val(0);
            $.ajax({
                url: '/getUnitInfo',
                type: 'GET',
                dataType: "json",
                data:{unit_id:unit_id},
                success: function (data) {
                    $('#payment_option').html(data.option);
                    $("#payment_option").select2();
                }
            });
        }
        function getPaymentOption(payment_option){
            var unit_id = $('#unit_id').val();
            var interest_per_year = 0;
            $.ajax({
                url: '/getPaymentOption',
                type: 'GET',
                dataType: "json",
                // processData: false,
                // contentType: false,
                data:{payment_option:payment_option,unit_id:unit_id},
                success: function (data) {
                    var down_payment_duration = $('#down_payment_duration');
                    var down_payment_value = $('#down_payment_value');
                    var installment_duration = $('#installment_duration');
                    var loan_duration = $('#loan_duration');
                    var annual_interest = $('#annual_interest');
                    var interest_rate = $('#interest_rate');
                    var discount_payment_option = $('#discount_payment_option');
                    if(data == null){
                        down_payment_duration.attr('readonly',false).val(0);
                        down_payment_value.attr('readonly',false).val(0);
                        installment_duration.attr('readonly',false).val(0);
                        loan_duration.attr('readonly',false).val(0);
                        annual_interest.attr('readonly',false).val(0);
                        interest_rate.attr('readonly',false).val(0);
                        discount_payment_option.attr('readonly',false).val(0);
                    }else{
                        down_payment_duration.attr('readonly',true);
                        down_payment_value.attr('readonly',true);
                        installment_duration.attr('readonly',true);
                        loan_duration.attr('readonly',true);
                        annual_interest.attr('readonly',true);
                        interest_rate.attr('readonly',true);
                        discount_payment_option.attr('readonly',true);
                        if(data.first_payment_plan == 1){
                            $('#hase_down_payment').val('yes');
                        }else{
                            $('#hase_down_payment').val('no');
                        }
                        down_payment_duration.val(data.first_payment_duration_month);
                        down_payment_value.val(data.first_payment_per);
                        installment_duration.val(data.loan_duration_month);
                        loan_duration.val(data.loan_duration_month);
                        annual_interest.val(data.interest_per_year);
                        interest_per_year = data.interest_per_year / 12;
                        interest_rate.val(parseFloat(interest_per_year).toFixed(4));
                        discount_payment_option.val(data.special_discount);
                    }
                    calculateLoan();
                    calculateDownpayment();
                    addFirstCollectionDate();
                    // $('#payment_option').html(data.option);
                    // $('#unit_sale_price').val(data.unitInfo.price);
                }
            });
        }
        function calculateDownpayment(){
            var total_amoount = 0;
            var down_payment_type = $("#down_payment_type").val();
            var sell_price = $('#sell_price').val();
            var down_payment_value = $('#down_payment_value').val();
            var drawdown_principal_amount = $('#drawdown_principal_amount').val();
            var total_down_payment = 0;
            if(sell_price == null || sell_price == ''){
                sell_price = 0;
            }
            if(drawdown_principal_amount == null || drawdown_principal_amount == ''){
                drawdown_principal_amount = 0;
            }
            // total_down_payment = parseFloat(sell_price) * (parseFloat(down_payment_value) / 100);
            if(down_payment_type == '%'){
                total_down_payment = parseFloat(sell_price) * (parseFloat(down_payment_value) / 100);
            }else{
                total_down_payment = parseFloat(down_payment_value);
            }
            $('#down_payment').val(parseFloat(total_down_payment).toFixed(2));
            $("#loan_amounts").val((parseFloat(sell_price) - (parseFloat(total_down_payment)  + parseFloat(drawdown_principal_amount))).toFixed(2));
        }

        function calculateLoan(){
            var total_amoount = 0;
            var prin_amount = $('#prin_amount').val();
            var re_down_payment = $('#re_down_payment').val();
            var air_amount = $('#air_amount').val();
            var fee_amount = $('#fee_amount').val();
            var other_fee_amount = $('#other_fee_amount').val();
            var penalty = $('#penalty').val();
            var drawdown_principal_amount = $('#drawdown_principal_amount').val();
            var down_payment = $('#down_payment').val();
            var total_down_payment = 0;
            if(prin_amount == null || prin_amount == ''){
                prin_amount = 0;
            }
            if(re_down_payment == null || re_down_payment == ''){
                re_down_payment = 0;
            }
            if(air_amount == null || air_amount == ''){
                air_amount = 0;
            }
            if(fee_amount == null || fee_amount == ''){
                fee_amount = 0;
            }
            if(other_fee_amount == null || other_fee_amount == ''){
                other_fee_amount = 0;
            }
            if(penalty == null || penalty == ''){
                penalty = 0;
            }
            if(!drawdown_principal_amount){
                drawdown_principal_amount = 0;
            }
            if(!down_payment){
                down_payment = 0;
            }
            total_amoount = parseFloat(prin_amount) + parseFloat(re_down_payment) + parseFloat(air_amount) + parseFloat(fee_amount) + parseFloat(other_fee_amount) + parseFloat(penalty);

            drawdown_principal_amount = parseFloat(drawdown_principal_amount).toFixed(0);

            $('#sell_price').val(parseFloat(total_amoount).toFixed(2));
            $("#loan_amounts").val((parseFloat(total_amoount) - (parseFloat(down_payment)  + parseFloat(drawdown_principal_amount))).toFixed(2));
        }
        function calculateFinal(){
            var total_amoount = 0;
            var prin_amount = $('#prin_amount').val();
            var re_down_payment = $('#re_down_payment').val();
            var down_payment_value = $('#down_payment_value').val();
            var air_amount = $('#air_amount').val();
            var fee_amount = $('#fee_amount').val();
            var other_fee_amount = $('#other_fee_amount').val();
            var penalty = $('#penalty').val();
            var down_payment = $('#down_payment').val();
            var drawdown_principal_amount = $('#drawdown_principal_amount').val();
            var total_down_payment = 0;
            if(prin_amount == null || prin_amount == ''){
                prin_amount = 0;
            }
            if(down_payment == null || down_payment == ''){
                down_payment = 0;
            }
            if(re_down_payment == null || re_down_payment == ''){
                re_down_payment = 0;
            }
            if(air_amount == null || air_amount == ''){
                air_amount = 0;
            }
            if(fee_amount == null || fee_amount == ''){
                fee_amount = 0;
            }
            if(other_fee_amount == null || other_fee_amount == ''){
                other_fee_amount = 0;
            }
            if(penalty == null || penalty == ''){
                penalty = 0;
            }
            if(!down_payment_value){
                down_payment_value = 0;
            }
            if(!drawdown_principal_amount){
                drawdown_principal_amount = 0;
            }
            total_amoount = parseFloat(prin_amount) + parseFloat(re_down_payment) + parseFloat(air_amount) + parseFloat(fee_amount) + parseFloat(other_fee_amount) + parseFloat(penalty);

            drawdown_principal_amount = parseFloat(drawdown_principal_amount).toFixed(2);

            $('#sell_price').val(parseFloat(total_amoount).toFixed(2));

            $("#loan_amounts").val((parseFloat(total_amoount) - (parseFloat(down_payment) + parseFloat(drawdown_principal_amount))).toFixed(2));
        }
        function addFirstCollectionDate(){
            var start_payment_date = $('#start_payment_date').val();
            var down_payment_duration = $('#down_payment_duration').val();
            $.ajax({
                url: '/addFirstCollectionDate',
                type: 'GET',
                dataType: "json",
                data:{start_payment_date:start_payment_date,down_payment_duration:down_payment_duration},
                success: function (data) {
                    $('#start_date').datepicker({
                            format: 'yyyy-mm-dd',
                            autoclose: true,
                            changeMonth:true,
                            changeYear:true,
                    }).datepicker("update", data.start_payment_date);
                    $('#disburse_date').datepicker({
                            format: 'yyyy-mm-dd',
                            autoclose: true,
                            changeMonth:true,
                            changeYear:true,
                    }).datepicker("update", data.disburse_date);
                }
            }); 
        }        
    </script>
@endsection