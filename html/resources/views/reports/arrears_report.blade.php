@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
<style>
  @media print {
      a[href]:after {
        content: none !important;
      }
  }
</style>
@endsection

@section('content')
<section class="panel">
    <header class="panel-heading header-title">
        {{ trans('report.rpt_arrears_rep_for') }} {{ !empty($arrears_date)? $arrears_date : date('l, F d, Y') }}
    </header>
    <div class="panel-body">
        <div class="position-center">
            <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('rpt_arrears') }}" id="search_frm">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="control-label">{{ trans('report.rpt_arrears_rep') }}</label>
                            
                                <div data-date-viewmode="months" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{ $arrears_date }}" class="input-append date arrears_date">
                                    <input type="text" name="arrears_date"  id="arrears_date" size="16"  class="form-control" value="{{ $arrears_date }}">
                                        <span class="add-on birhtdateDatepicker">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                </div>
                            
                        </div>
                        <div class="form-group">
                            
                            	<input type="hidden" name="offset" />
                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                                <a href="{{$set_url_print}}" class="btn btn-warning"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</a>
                                <?php if($_GET['is_print']){?><a class="hide is_print" id="printer"></a><?php }?>
                                
                                <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                                <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="page">
            <div class="custom-pagi">
                <span class="pagi_label">Number of Rows:</span>
                <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                <a href="#" class="btn btn-danger">Go</a>
            </div>
        </div>
        <div class="ox-scroll" style="width:100%;">
        <div id="printArea" style="width:100%;">
        <?php
        	$count_ex = 1;

        	if($_GET['is_print']){
        		$count_ex = count($odata) / MODE_PAGI;
        	}
        	for($ii=0; $ii<$count_ex; $ii++){
        		$odata_s = $odata;
        		if (Request::has('is_print')) $odata_s = array_slice($odata, $ii, MODE_PAGI);
        ?>
        		<div style="page-break-after:always" class="area_report_breaker">

        <table class="table table-striped table-hover table-bordered">
            <thead class="th-center">
                <tr>
                    <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.m_no') }}</th>
                    <th rowspan="2" style="vertical-align: middle;">{{ trans('report.rpt_id') }}</th>
                    <th rowspan=2 style="vertical-align:middle;">{{ trans('customer.cus_customer_name') }}</th>
                    <th rowspan="2" style="vertical-align: middle;">{{ trans('report.rpt_disbursement_date') }}</th>
                    <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_tenure') }}</th>
                    <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_spa_price') }}</th>
                    <th colspan=2 style="vertical-align:middle;">{{ trans('report.rpt_down_payment') }}</th>
                    <th colspan=2 style="vertical-align:middle;">{{ trans('report.rpt_repayment_plan') }}</th>
                    <th colspan=2 style="vertical-align:middle;">{{ trans('report.rpt_actual_paid') }}</th>
                    <th colspan=2 style="vertical-align:middle;">{{ trans('report.rpt_outstanding_balance') }}</th>
                </tr>
                <tr>
                    <th style="vertical-align: middle;">{{ trans('report.rpt_amount') }}</th>
                    <th style="vertical-align: middle;">%</th>
                    <th style="vertical-align: middle;">{{ trans('report.rpt_principal') }}</th>
                    <th style="vertical-align: middle;">{{ trans('report.rpt_interest') }}</th>
                    <th style="vertical-align: middle;">{{ trans('report.rpt_principal') }}</th>
                    <th style="vertical-align: middle;">{{ trans('report.rpt_interest') }}</th>
                    <th style="vertical-align: middle;">①{{ trans('report.rpt_principal') }}</th>
                    <th style="vertical-align: middle;">②{{ trans('report.rpt_interest') }}</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $n = $pagi;
                    $os_balance = 0.0;
                    //$date = date('Y-m-d');
                    $date = $arrears_date?$arrears_date:date('Y-m-d');
                    $date = date('Y-m-d', strtotime($date));
                    $report_date = new DateTime($date);
                    foreach ($odata_s as $od){
                    	$s = $od[1];
                ?>
                @foreach($loans[$s->loan_id] as $loan)
                        <?php
                            $c = 'C';
                            $lastPayment = null;
                            $interest_original_sum = 0.00;
                            $principal_original_sum = 0.00;
                            $principal_outstanding = 0.00;
                            $interest_outstanding = 0.00;
                            $interest_sum = 0;
                            $principal_sum = 0;
                            $outstanding_sum = 0.0;
                            $percent = 0;
                            $down_payment = 0;
                            $product_price = 0;
                            $schedule_interest = 0;
                            $payment_schedule = LoanCalculate::loan_schedule($loan->schedule, $loan->start_date)[0];
                            if(!empty($payment_schedule) && count($payment_schedule) > 0){
                                foreach($payment_schedule as $key => $v){
                                    $each_date = date('Y-m-d',strtotime($v[0]));
                                    if($each_date <= $date){
                                       $schedule_interest += $v[2];
                                    }
                                }
                            }
                            foreach ($payment_schedule as $p) {
                                //$interest_original_sum += $p[2];
                                $principal_original_sum += $p[3];
                            }
                            if(!empty($loan->payment) && count($loan->payment) > 0){
                                $repayment = $loan->payment;
                                foreach($repayment as $pay){
                                    $interest_sum += $pay->paid_interest;
                                    $principal_sum += $pay->paid_principal;
                                }
                            }
                            //Principle 1
                            $principal_outstanding = $principal_original_sum - $principal_sum;
                            //Interest 2
                            $interest_outstanding = $schedule_interest  - $interest_sum;
                            // 1+2 outstanding_sum
                            $outstanding_sum = $principal_outstanding + $interest_outstanding;
                            $down_payment = $loan->down_payment;
                            $product_price = $loan->product->product_price;
                            $percent = $down_payment * 100/ $product_price;
                        ?>
                    <tr>
                        <td align="center">{{ $n }}</td>
                        <td align="center"><a href="{{ route('loan_detail', [$loan->id])}}">{{ $loan->contract_id ? $loan->contract_id : '-'  }}</a></td>
                        <td align="left">{{$loan->client ? $loan->client->client_name: '-'}}</td>
                        <td align="center">{{ date("d-M-Y", strtotime($loan->start_date))}}</td>
                        <td align="center">{{ $loan->loan_duration }} M</td>
                        <td align="right">${{number_format($loan->product ? $loan->product->product_price : '',2,'.',',')}}</td>
                        <td align="right">${{number_format($loan->down_payment,2,'.',',')}}</td>
                        <td align="center">{{ number_format((float)$percent,2, '.', '')}}</td>
                        <td align="right">${{number_format($principal_original_sum, 2, '.', ',')}}</td>
                        <td align="right">${{number_format( $schedule_interest, 2, '.', ',')}}</td>
                        <td align="right">${{number_format($principal_sum,2,'.',',')}}</td>
                        <td align="right">${{number_format($interest_sum,2,'.',',')}}</td>

                        <!--outstanding principle-->
                        <td align="right">${{number_format($principal_outstanding,2,'.',',')}}</td>
                        <!--outstanding interest-->
                        <td align="right">${{number_format($interest_outstanding,2,'.',',')}}</td>
                    </tr>
                    <?php $n++; ?>
                @endforeach
                <?php }?>
            </tbody>
        </table>
        <table class="table table-striped table-hover table-bordered">
            <thead class="th-center">
                <tr>
                    <th rowspan=4 style="vertical-align:middle;">{{ trans('multiple.m_no') }}</th>
                    <th rowspan=4 style="vertical-align: middle;">{{ trans('report.rpt_id') }}</th>
                    <th colspan=5 style="vertical-align:middle;">{{ trans('report.rpt_arrear_information') }}</th>
                    <th colspan=2 style="vertical-align:middle;">③{{ trans('report.rpt_fee_charge') }}</th>
                    <th colspan=2 style="vertical-align:middle;">④{{ trans('report.rpt_hunting_fixing') }}</th>
                    <th colspan=4 style="vertical-align: middle">{{ trans('report.rpt_surplus_deficit') }}</th>
                    <th style="vertical-align: middle">{{ trans('report.rpt_strategy') }}</th>
                </tr>
                <tr>
                    <th rowspan=3 style="vertical-align: middle;">{{ trans('report.rpt_arrear_date') }}</th>
                    <th rowspan=3 style="vertical-align: middle;">
                    	<a href="{{ $order_url['overdue'] }}" style="display: block">
                        	{{ trans('report.rpt_overdue') }}<i class="fa {{ $order_class['overdue'] }} pull-right"></i>
                        </a>
                    </th>
                    <th colspan=3 style="vertical-align: middle;">{{ trans('report.rpt_arrear_amount') }}</th>
                    <th rowspan=3 style="vertical-align: middle;">{{ trans('report.rpt_penalty') }}</th>
                    <th rowspan=3 style="vertical-align: middle;">{{ trans('report.rpt_pay_off') }}</th>
                    <th rowspan=3 style="vertical-align: middle;">{{ trans('report.rpt_hunting_cost') }}</th>
                    <th rowspan=3 style="vertical-align: middle;">{{ trans('report.rpt_fixing_cost') }}</th>
                    <th rowspan=3 style="vertical-align: middle;">⑤{{ trans('report.rpt_estimated_price') }}</th>
                    <th rowspan=3 style="vertical-align: middle;">⑤-(①+②)</th>
                    <th rowspan=3 style="vertical-align: middle;">①+②+③+④</th>
                    <th rowspan=3 style="vertical-align: middle;">⑤-①</th>
                    <th style="vertical-align: middle; text-align: left">1. {{ trans('report.rpt_get_back') }}</th>
                </tr>
                <tr>
                    <th rowspan="2">{{ trans('report.rpt_arr_prin') }}</th>
                    <th rowspan="2">{{ trans('report.rpt_arr_int') }}</th>
                    <th rowspan="2">{{ trans('report.rpt_arr_total') }}</th>
                    <th style="vertical-align: middle; text-align: left">2. {{ trans('report.rpt_res') }}</th>

                </tr>
                <tr>
                    <th style="vertical-align: middle; text-align: left">3. {{ trans('report.rpt_other') }}</th>
                </tr>
            </thead>
            <tbody>
                <?php
                	$n = $pagi;
                	foreach ($odata_s as $od){
                		$s = $od[1];
                ?>
                @foreach($loans[$s->loan_id] as $loan_arr)
                    <?php
                        $a = 0;
                        $interest_original_sum = 0.00;
                        $principal_original_sum = 0.00;
                        $principal_outstanding = 0.00;
                        $interest_outstanding = 0.00;
                        $interest_sum = 0;
                        $principal_sum = 0;
                        $outstanding_sum = 0.0;
                        $repayment_date = null;
                        $arrears_principal = 0;
                        $arrears_interest = 0 ;
                        $actual_interest = 0 ;
                        $actual_principal = 0 ;
                        $schedule_principal = 0 ;
                        $schedule_interest = 0 ;
                        $total_fee_charge = 0.0;
                        $total_cost = 0.0;
                        $os_left_sum = 0.0;
                        $prin_left = 0.0;
                        $last_pro_record = [];

                        $hunting_cost = 0;
                        $fixing_cost = 0;
                        $resale_price = 0.0;
                        $payoff_fee = 0.0;
                        $cost_amount = 0;
                        $total_arrears = 0 ;
                        $payment_schedule = LoanCalculate::loan_schedule($loan_arr->schedule, $loan_arr->start_date)[0];
                        //pay off fee
                        if(!empty($payment_schedule) && count($payment_schedule) > 0){
                            foreach ($payment_schedule as $p)
                            {
                                //$interest_original_sum += $p[2];
                                $principal_original_sum += $p[3];
                            }
                            $repayment = $loan_arr->payment;
                            foreach($repayment as $pay){
                                $interest_sum += $pay->paid_interest;
                                $principal_sum += $pay->paid_principal;
                            }
                        }

                        if(!empty($payment_schedule) && count($payment_schedule) > 0){
                            foreach($payment_schedule as $key => $v){
                                $each_date = date('Y-m-d',strtotime($v[0]));
                                if($each_date <= $date){
                                   $schedule_interest += $v[2];
                                }
                            }
                        }
                        //Principle 1
                        $principal_outstanding = $principal_original_sum - $principal_sum;
                        //Interest 2
                        $interest_outstanding = $schedule_interest  - $interest_sum;
                        // 1+2 outstanding_sum
                        $outstanding_sum = $principal_outstanding + $interest_outstanding;
                        $start_date = new DateTime($payment_schedule[0][0]);
                        $interval = $start_date->diff($report_date);
                        $interval_month = intval($interval->format('%y')*12) + intval($interval->format('%m'));
                        if($interval_month > intval($loan_arr->payoff_period2)){
                            $payoff_fee = 0;
                        }elseif($interval_month > $loan_arr->payoff_period1){
                            $payoff_fee = $principal_outstanding * $loan_arr->pay_off_rate2 / 100;
                        }else{
                            $payoff_fee = $principal_outstanding * $loan_arr->pay_off_rate1 / 100;
                        }
                        //Penalty
                        $result_total_penalty =  LoanCalculate::getTotalPenalty($loan_arr);
                        $result = $result_total_penalty[0];
                        $overdue = $result_total_penalty[2];
                        $l_paid_date = $result_total_penalty[3];
                        $total_penalty = 0;
                        for($i = 1; $i < $loan_arr->loan_duration; $i++){
                            if(empty($result[$i])){
                                continue;
                            }
                            $total_penalty += $result[$i][8];
                        }
                        // 3 (penalty + payoff_fee)
                        $total_fee_charge = $total_penalty + $payoff_fee;
                        //arrears
                        if(!empty($payment_schedule) && count($payment_schedule) > 0){
                            foreach($payment_schedule as $key => $v){
                                $each_date = date('Y-m-d',strtotime($v[0]));
                                if($each_date <= $date){
                                   $schedule_interest += $v[2];
                                   $schedule_principal += $v[3];

                                }
                            }
                        }
                        $repayment = $loan_arr->payment;
                        if(!empty($repayment) && count($repayment) > 0){
                            foreach($repayment as $pay){
                                $actual_interest += $pay->paid_interest;
                                $actual_principal += $pay->paid_principal;
                            }
                        }
                        $arrears_principal = $schedule_principal - $actual_principal;
                        $arrears_interest = $schedule_interest - $actual_interest;
                        $total_arrears = $arrears_principal + $arrears_interest;
                    ?>
                    <tr>
                        <td align="center">{{ $n }}</td>
                        <td align="center"><a href="{{ route('loan_detail', [$loan_arr->id])}}">{{ $loan_arr->contract_id ? $loan_arr->contract_id : '-'  }}</a></td>
                        <?php
                            $last_pay_date = null;
                            $arrears_date = null;
                            $payments = $loan_arr->payment;
                            $current_date = date_create(date('Y-m-d'));
                            if(!empty($payments) && count($payments) > 0){
                                foreach($payments as $p){
                                    $status = $p->status;
                                    $condition_id = $p->condition_id;
                                    if($status == 0 && $condition_id == 1){
                                        $last_pay_date = date('Y-m-d',strtotime($p->repayment_date));
                                        //if($loan_arr->id == 4) dd($p);
                                        $arrears_date = date_create($p->repayment_date);
                                        break;
                                    }
                                }
                                //if($loan_arr->id == 4) dd($last_pay_date);
                                if($last_pay_date == null){
                                    $last_pays = $payments[count($payments)-1];
                                    //if($loan_arr->id == 4) dd($last_pays);
                                    $last_pay_index = $last_pays->payment_month;
                                    //$last_pay_date = date('Y-m-d', strtotime($last_pays->repayment_date));
                                    $arrears_date = date_create($loan_arr->schedule[$last_pay_index+1]->schedule_date);
                                }

                            }else{
                                $arrears_date = date_create($loan_arr->schedule[0]->schedule_date);
                            }
                            $diff = date_diff($arrears_date, $current_date)->days;
                            //if($loan_arr->id == 4) {
                            //var_dump($arrears_date);
                            //dd($diff);
                            //}
                            $cost_amount = $loan_arr->costfee;
                            // 4
                            if(!empty($cost_amount) && count($cost_amount) > 0){
                                foreach($cost_amount as $c){
                                    $cost_type = $c->cost_type;
                                    if($cost_type==7){ // hunting cost
                                        $hunting_cost += $c->cost_amount;
                                    }elseif($cost_type==8){ // fixing cost
                                        $fixing_cost  += $c->cost_amount;
                                    }
                                }
                            }
                            // 4 = ($hunting_cost + $fixing_cost)
                            $total_cost = $hunting_cost + $fixing_cost;
                            // 5 Estimate Price (40%)
                            $resale_price = $loan_arr->loan_amount * 0.4;
                            !empty($loan_arr->product->record)? $product_record = $loan_arr->product->record : $product_record = [];
                            $last_pro_record = [];
                            if(!empty($product_record) && count($product_record) > 0 ){
                                foreach($product_record as $p){
                                    if($p->action_type == "Resold"){
                                        $resale_price = $p->price;
                                        $last_pro_record = $p;
                                        break;
                                    }
                                    $last_pro_record = $p;
                                }
                            }
                            // 5 - (1+2)
                            $est_os = $resale_price - $outstanding_sum;
                            // 1+2+3+4
                            $os_left_sum = $outstanding_sum + $total_fee_charge + $total_cost;
                            // 5-1
                            $prin_left = $resale_price - $principal_outstanding;
                        ?>
                        <td align="center">{{ $l_paid_date }}</td>
                        <td align="center">{{ $overdue }}</td>
                        <td align="right">${{($arrears_principal >= 0)? number_format($arrears_principal,2,'.',',') : '('.number_format(abs($arrears_principal),2,'.',',').')'}}</td>
                        <td align="right">${{($arrears_interest >= 0 )? number_format($arrears_interest,2,'.',',') : '('.number_format(abs($arrears_interest),2,'.',',').')'}}</td>
                        <td align="right">${{number_format($total_arrears,2,'.',',')}}</td>
                        <td align="right">${{number_format($total_penalty,2,'.',',')}}</td>
                        <td align="right">${{number_format($payoff_fee,2,'.',',')}}</td>
                        <td align="right">${{number_format($hunting_cost,2,'.',',')}}</td>
                        <td align="right">${{number_format($fixing_cost,2,'.',',')}}</td>
                        <td align="right">${{number_format($resale_price,2,'.',',')}}</td>
                        <td align="right">${{($est_os >= 0) ? number_format($est_os,2,'.',',') : '('.number_format(abs($est_os),2,'.',',').')'}}</td>
                        <td align="right">${{number_format($os_left_sum,2,'.',',')}}</td>
                        <td align="right">${{($prin_left >= 0) ? number_format($prin_left,2,'.',',') : '('.number_format(abs($prin_left),2,'.',','). ')'}}</td>
                        <td></td>
                    </tr>
                    <?php $n++;?>
                @endforeach
                <?php }?>
            </tbody>
        </table>
        <table class="table table-striped table-hover table-bordered" id="arrears">
            <thead class="th-center">
                <tr>
                    <th style="vertical-align:middle;">{{ trans('multiple.m_no') }}</th>
                    <th style="vertical-align: middle;">{{ trans('report.rpt_id') }}</th>
                    <th style="vertical-align:middle;">{{ trans('report.rpt_address') }}</th>
                    <th style="vertical-align:middle;">{{ trans('report.rpt_pro_type') }}</th>
                    <th style="vertical-align:middle;">{{ trans('report.rpt_pro_location') }}</th>
                    <th style="vertical-align: middle">{{ trans('report.rpt_dealer') }}</th>
                    <th style="vertical-align: middle">{{ trans('report.rpt_co') }}</th>
                    <th style="vertical-align: middle">{{ trans('report.rpt_date') }}</th>
                    <th style="vertical-align: middle"> {{ trans('report.rpt_status') }} </th>
                    <th style="vertical-align: middle">{{ trans('report.rpt_remark') }}</th>
                </tr>
            </thead>
            <tbody>
            	<?php
            		$n = $pagi;
            		foreach ($odata_s as $od){
            			$s = $od[1];
            	?>
            	@foreach($loans[$s->loan_id] as $l)
                <?php

                    !empty($l->product->record)? $product_record = $l->product->record : $product_record = [];
                    $last_pro_record = [];
                    if(!empty($product_record) && count($product_record) > 0 ){
                        foreach($product_record as $p){
                            if($p->action_type == "Resold"){
                                $resale_price = $p->price;
                                $last_pro_record = $p;
                                break;
                            }
                            $last_pro_record = $p;
                        }
                    }
                ?>
                <tr>
                    <td align="center">{{ $n }}</td>
                    <td align="center"><a href="{{ route('loan_detail', [$l->id])}}">{{ $l->contract_id ? $l->contract_id : '-'  }}</a></td>
                    <td align="left">{{$l->client ? $l->client->address: '-'}}</td>
                    <td align="center">{{$l->product ? $l->product->product_type : '-'}}</td>
                    <td align="center">{{!empty($last_pro_record) && count($last_pro_record) > 0 ? ($last_pro_record->location) : '-'}}</td>
                    <td align="center">{{$l->dealer ? $l->dealer->dealer : '-'}}</td>
                    <td align="center">{{!empty($l->co_user) && count($l->co_user) > 0 ? ($l->co_user->name) : '-'}}</td>
                    <td align="center">{{!empty($last_pro_record) && count($last_pro_record) > 0 ? ($last_pro_record->date) : '-'}}</td>
                    <td align="center">{{!empty($last_pro_record) && count($last_pro_record) > 0 ? ($last_pro_record->action_type) : '-'}}</td>
                    <td align="left">{{!empty($last_pro_record) && count($last_pro_record) > 0 ? ($last_pro_record->remark) : '-'}}</td>
                </tr>
                <?php $n++; ?>
                @endforeach
                <?php } ?>
            </tbody>
        </table>
        </div>
        </div>
        <?php }  // end $ii?>

        <?php if(!isset($_GET['is_print'])){ ?>
        <div class="page">
	            <?php
	               	$url= Request::input('arrears_date');
	                if(!empty($url)){
	                    $sort = $url;
	                }else{
	                    $sort =  Request::input('sort');
	                }
	                echo $loans_->appends(['sort' =>$sort])->render();
	            ?>
	        </div>
	    <?php    } ?>
        </div>
    </div>

</section>
@endsection
@section('js')
	<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/xlsx.full.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
    <script>
        $('.arrears_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $(document).ready(function () {
            $('.custom-pagi a').on('click', function () {
                val = $(this).parent().find('input[name="set_offset"]').val();
                $('input[name="offset"]').val(val);
                $('#search_frm').submit();
                return false;
            });

            setTimeout(function(){
 	           if($('.is_print').length==1){
 	                $('.is_print').trigger('click');
 	           }
         	}, 1000);
        });
        $("#export").click(function (event) {
            var con = confirm("Do you really want to export to CSV file?");
            if(con == true){
                new TableExport(document.getElementsByTagName("table"), {
                    formats: ['csv'],
                    filename:"arrears_report"
                });
                $('button.csv').hide().click();
                $('.tableexport-caption').remove();
            }
            event.preventDefault();
        });

        $("#xexport").click(function (event) {
            var con = confirm("Do you really want to export to Excel file?");
            if(con == true){
                new TableExport(document.getElementsByTagName("table"), {
                        formats: ['xlsx'],
                        filename: 'arrears_report'
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });
    </script>
@endsection
