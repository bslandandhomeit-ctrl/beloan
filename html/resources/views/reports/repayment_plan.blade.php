@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
@endsection

@section('content')
    <section class="panel">
        <header class="panel-heading">
           {{ $product_type_label }} {{ trans('sidebar.sb_repayment_plan') }} {{ $company_branch_label }}
        </header>
        <div class="panel-body" style="overflow-x:auto">
            <section id="unseen">
                <form action="{{ route('rpt_repayment_plan') }}" method="get">
                <div class="row">
                    <div class="col-lg-3">
                        <label class="control-label">{{ trans('report.rpt_loan_type') }}</label><br/>
                        <select name="loan_type" class="form-control">
                            <option value="">-</option>
                            @foreach(Config::get('static_data.loan_type') as $key=>$type)
                                <option value="{{ $key }}" {{ isset($t_id) && $t_id==$key ? 'selected':'' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="control-label">Product Type</label><br/>
                        <select name="product_type" class="form-control">
                            <option value="">-</option>
                            @foreach($product_type as $pt)
                                <option value="{{$pt->id}}" {{isset($p_type) &&  $p_type == $pt->id ? 'selected':'' }}>{{$pt->products_type_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="control-label">Branch Name</label><br/>
                        <select class="form-control" name="company_branch_id" id="company_branch_id">
                            <option value="">-</option>
                            @foreach($company_branch as $br)
                                <option value="{{ $br->id }}">{{ $br->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">{{ trans('multiple.m_start_date') }}</label>
                        <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date from_date">
                            <input type="text" name="from_date" size="16" class="form-control" value="{{ isset($from_date)?$from_date:old('from_date') }}">
                            <span class="add-on birhtdateDatepicker ptl-3">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">{{ trans('multiple.m_end_date') }}</label>
                        <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date to_date">
                            <input type="text" name="to_date" size="16" class="form-control" value="{{ isset($to_date)?$to_date:old('to_date') }}">
                            <span class="add-on birhtdateDatepicker ptl-3">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-4" style="padding-top:5px;">
                        <label>&nbsp;</label><br/>
                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                        <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                        <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                    </div>
                </div><br/>
                </form>
                <div class="row">
                    <div class="col-lg-12">
                    <?php
                        $repayment_schedule = [];
                        $repayment_actual = [];
                        $total_borrower = 0;
                        $num_borrower = 0;
                        $total_sch_principal = 0.00;
                        $total_sch_interest = 0.00;
                        $total_sch_fee = 0.00;
                        $total_sch_outstanding = 0.00;
                        $total_disburse = 0.00;
                        $total_act_principal = 0.00;
                        $total_act_interest = 0.00;
                        $total_act_fee = 0.00;
                        $total_act_payoff_principal = 0.00;
                        $total_act_payoff_interest = 0.00;
                        $total_writeoff = 0.00;
                        $total_close = 0.00;
                        $total_completed_borrower = 0;
                        $exchange_rate_arr = [];

                        foreach($loans as $loan){
                            $exchange_rate = ($loan->client_loan_account->currency == 2)? 1:4000;
                            $exchange_rate_arr[$loan->id] = $exchange_rate;
                            $schedule = LoanCalculate::loan_schedule($loan->schedule,$loan->start_date)[0];
                            if(!empty($schedule)){
                                $lyear_month = date('Ym',strtotime($loan->disburse_date));
                                $end_schedule_date = $loan->schedule[count($loan->schedule) - 1]->schedule_date	; /* get last schedule date of loan  */
                                $lschedule = date('Ym',strtotime($end_schedule_date));
                               // Disburse
                                if(!isset($repayment_schedule[$lyear_month])) $repayment_schedule[$lyear_month] = [];
                                if(!isset($repayment_schedule[$lyear_month][1])) $repayment_schedule[$lyear_month][1] = 0.00;
                                $repayment_schedule[$lyear_month][1] += $loan->loan_amount / $exchange_rate;
                                $total_disburse += $loan->loan_amount / $exchange_rate;

                                for($i=0;$i<count($schedule);$i++){
                                    $year_month = date('Ym',strtotime($schedule[$i][0]));

                                    if(!isset($repayment_schedule[$year_month])) $repayment_schedule[$year_month] = [];

                                    // principal
                                    if(!isset($repayment_schedule[$year_month][0])) $repayment_schedule[$year_month][0] = 0.00;
                                    $repayment_schedule[$year_month][0] += $schedule[$i][3] / $exchange_rate;
                                    $total_sch_principal += $schedule[$i][3] / $exchange_rate;
                                    //interest
                                    if(!isset($repayment_schedule[$year_month][3])) $repayment_schedule[$year_month][3] = 0.00;
                                    $repayment_schedule[$year_month][3] += $schedule[$i][2] / $exchange_rate;
                                    $total_sch_interest += $schedule[$i][2] / $exchange_rate;
                                    //fee
                                    if(!isset($repayment_schedule[$year_month][5])) $repayment_schedule[$year_month][5] = 0.00;
                                    $repayment_schedule[$year_month][5] += $schedule[$i][4] / $exchange_rate;
                                    $total_sch_fee += $schedule[$i][4] / $exchange_rate;
                                    // Outstanding
                                    if(!isset($repayment_schedule[$year_month][2])) $repayment_schedule[$year_month][2] = 0.00;
                                    // Disburse
                                    if(!isset($repayment_schedule[$year_month][1])) $repayment_schedule[$year_month][1] = 0.00;
                                }

                                if(!isset($repayment_actual[$lyear_month])) $repayment_actual[$lyear_month] = [];
                                if(!isset($repayment_actual[$lyear_month][1])) $repayment_actual[$lyear_month][1] = 0.00;
                                $repayment_actual[$lyear_month][1] += $loan->loan_amount / $exchange_rate;
                                if(!isset($repayment_actual[$lyear_month][0])) $repayment_actual[$lyear_month][0] = 0.00;
                                if(!isset($repayment_actual[$lyear_month][2])) $repayment_actual[$lyear_month][2] = 0.00;
                                if(!isset($repayment_actual[$lyear_month][3])) $repayment_actual[$lyear_month][3] = 0.00;
                                if(!isset($repayment_actual[$lyear_month][5])) $repayment_actual[$lyear_month][5] = 0.00;
                                // number of borrowers
                                if(!isset($repayment_schedule[$lyear_month][4])) $repayment_schedule[$lyear_month][4] = 0;
                                $repayment_schedule[$lyear_month][4] += 1;

                                // number of complete loan
                                if(!isset($repayment_schedule[$lschedule][5])) $repayment_schedule[$lschedule][5] = 0;
                                if($loan->status == 10){
                                     $completed_borrower = $repayment_schedule[$lschedule][5] += 1;
                                     $total_completed_borrower += $completed_borrower;
                                }
                                $total_borrower ++;

                                if(count($loan->payment) > 0){
                                    foreach($loan->payment as $p){
                                        $year_month = date('Ym',strtotime($p->repayment_date));
                                        if(!isset($repayment_actual[$year_month])) $repayment_actual[$year_month] = [];

                                        // principal
                                        if(!isset($repayment_actual[$year_month][0])) $repayment_actual[$year_month][0] = 0.00;
                                        $repayment_actual[$year_month][0] += $p->paid_principal / $exchange_rate;
                                        $total_act_principal += $p->paid_principal / $exchange_rate;
                                        //interest
                                        if(!isset($repayment_actual[$year_month][3])) $repayment_actual[$year_month][3] = 0.00;
                                        $repayment_actual[$year_month][3] += $p->paid_interest / $exchange_rate;
                                        $total_act_interest += $p->paid_interest / $exchange_rate;
                                        //fee
                                        if(!isset($repayment_actual[$year_month][5])) $repayment_actual[$year_month][5] = 0.00;
                                        $repayment_actual[$year_month][5] += $p->paid_fee / $exchange_rate;
                                        $total_act_fee += $p->paid_fee / $exchange_rate;
                                        // Outstanding
                                        if(!isset($repayment_actual[$year_month][2])) $repayment_actual[$year_month][2] = 0.00;

                                        // Disburse
                                        if(!isset($repayment_actual[$year_month][1])) $repayment_actual[$year_month][1] = 0.00;
                                    }
                                }
                                // in case there is payoff
                                foreach($pay_offs as $payoff){
                                    if($loan->id == $payoff->loan_id){
                                        $year_month = date('Ym',strtotime($payoff->payoff_date));
                                        $repayment_actual[$year_month][0] += $payoff->principal / $exchange_rate;
                                        $total_act_principal += $payoff->principal / $exchange_rate;
                                        $repayment_actual[$year_month][3] += $payoff->interest / $exchange_rate;
                                        $total_act_interest += $payoff->interest / $exchange_rate;
                                        $repayment_actual[$year_month][5] += $payoff->fee / $exchange_rate;
                                        $total_act_fee += $payoff->payoff_fee / $exchange_rate;
                                    }
                                }
                            }
                        }

                    ?>
                    <br/><br/>
                    <div id="printArea">
                        @include('api.report_header',['co_phone'=>''])
                        <h4 class="sch_title" id="p-header">{{ trans('sidebar.sb_repayment_plan') }}</h4>
                        <div id="divTab">
                            <table class="ddd table table-bordered table-striped table-condensed repayment-plan">
                                <thead style="background-color: #ffffff">
                                    <tr class="header">
                                        <th></th>
                                        <th colspan="6" style="text-align:center">{{ trans('report.rpt_balance_sheet_repayment_schedule') }}</th>
                                        <th colspan="5" style="text-align:center">{{ trans('report.rpt_balance_sheet_repayment_actual') }}</th>
                                        <th colspan="5" style="text-align:center">{{ trans('multiple.m_note') }}</th>
                                    </tr>
                                    <tr class="head-1">
                                        <th style = "text-align: center" >{{ trans('report.rpt_date') }}</th>
                                        <th style = "text-align: center;width: 2px;">{{ trans('report.rpt_number_of_borrower') }}</th>
                                        <th style = "text-align: center">{{ trans('loan.l_pri_repayment') }}</th>
                                        <th style = "text-align: center">{{ trans('loan.l_disburse_loan') }}</th>
                                        <th style = "text-align: center">{{ trans('report.rpt_outstanding_balance') }}</th>
                                        <th style = "text-align: center">{{ trans('report.rpt_interest_revenue') }}</th>
                                        <th style = "text-align: center">{{ trans('loan.l_fee') }}</th>

                                        <th style = "text-align: center">{{ trans('loan.l_pri_repayment') }}</th>
                                        <th style = "text-align: center">{{ trans('loan.l_disburse_loan') }}</th>
                                        <th style = "text-align: center">{{ trans('report.rpt_outstanding_balance') }}</th>
                                        <th style = "text-align: center;" class="th-revenue">{{ trans('report.rpt_interest_revenue') }}</th>
                                        <th style = "text-align: center">{{ trans('loan.l_fee') }}</th>

                                        <th style = "text-align: center">{{ trans('report.rpt_paid_off_principal') }}</th>
                                        <th style = "text-align: center">{{ trans('report.rpt_paid_off_interest') }}</th>
                                        <th style = "text-align: center">{{ trans('report.rpt_write_off') }}</th>
                                        <th style = "text-align: center">{{ trans('loan.l_close_loan') }}</th>
                                        <th style="text-align: center">{{ trans('report.rpt_completed_borrower') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        $temp1 = 0.00;
                                        $repayment_schedule = array_sort($repayment_schedule,function($value,$key){
                                            return $key;
                                        });

                                        $temp2 = 0.00;
                                        $repayment_actual = array_sort($repayment_actual,function($value,$key){
                                            return $key;
                                        });

                                     ?>
                                    @if(!empty($repayment_schedule))
                                    <?php
                                        $arr_keys = array_keys($repayment_schedule);
                                        $last_key = end($arr_keys);
                                    ?>

                                    @foreach($repayment_schedule as $key=>$value)


                                       <?php

                                       $number0 = isset($value[0]) ? $value[0] : 0; $number1 = isset($value[1]) ? $value[1] : 0; $number3 = isset($value[3]) ? $value[3] : 0;?>
                                        <tr>
                                            <!-- Repayment Schedule -->
                                            <td style = "text-align: center">{{ date('M-Y',strtotime(date('Ymd',strtotime($key.'01')))) }}</td>
                                            <td style = "text-align: center">{{ (!isset($value[4]))? 0: $value[4] }}</td>

                                            <td style = "text-align: right">{{ number_format($value[0],2,'.',',') }}</td>
                                            <td style = "text-align: right">{{ number_format($value[1],2,'.',',') }}</td>
                                            <td style = "text-align: right">
                                                <?php
                                                    $repayment_schedule[$key][2] = $value[1] + $temp1 - $value[0];
                                                    $temp1 = $repayment_schedule[$key][2];
                                                    if($key == $last_key){
                                                        echo number_format(floor($temp1),2,'.',',');
                                                    }else{
                                                        echo number_format($temp1,2,'.',',');
                                                    }
                                                ?>
                                            </td>
                                            <td style = "text-align: right">{{ number_format($value[3],2,'.',',') }}</td>
                                            <td style = "text-align: right">{{ number_format($value[5],2,'.',',') }}</td>

                                            <!-- Repayment Actual -->

                                            @if(array_key_exists($key,$repayment_actual))

                                                <td style = "text-align: right;">{{ number_format($repayment_actual[$key][0],2,'.',',') }}</td>
                                                <td style = "text-align: right">{{ number_format($repayment_actual[$key][1],2,'.',',') }}</td>

                                                <td style = "text-align: right">
                                                    <?php

                                                    $repayment_actual[$key][2] = $repayment_actual[$key][1] + $temp2 - $repayment_actual[$key][0];
                                                    $temp2 = $repayment_actual[$key][2];
                                                    echo number_format($temp2,2,'.',',');
                                                    ?>
                                                </td>
                                                <td style = "text-align: right">{{ number_format($repayment_actual[$key][3],2,'.',',') }}</td>
                                                <td style = "text-align: right;">{{ number_format($repayment_actual[$key][5],2,'.',',') }}</td>

                                            @else

                                                <td style = "text-align: right">0.00</td>
                                                <td style = "text-align: right">0.00</td>
                                                <td style = "text-align: right">{{ number_format($temp2,2,'.',',') }}</td>
                                                <td style = "text-align: right;">0.00</td>
                                                <td style = "text-align: right;">0.00</td>
                                            @endif

                                            @var $payoff_cnt = 0
                                            @var $write_off_cnt = 0
                                            @var $close_cnt = 0
                                            @var $payoff_sum = 0
                                            @var $payoff_int_sum = 0
                                            @if(!empty($pay_offs) && count($pay_offs) > 0)
                                                @foreach($pay_offs as $payoff)
                                                    @if(date('mY',strtotime(date('Ymd',strtotime($key.'01')))) == date('mY',strtotime($payoff['payoff_date'])))
                                                        <?php
                                                        $payoff_sum += $payoff['principal'] / $exchange_rate_arr[$payoff->loan_id];
                                                        $payoff_int_sum += $payoff['interest'] / $exchange_rate_arr[$payoff->loan_id];
                                                        $payoff_cnt++;
                                                        $total_act_payoff_principal += $payoff['principal'] / $exchange_rate_arr[$payoff->loan_id];
                                                        $total_act_payoff_interest += $payoff['interest'] / $exchange_rate_arr[$payoff->loan_id];
                                                        ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <td style = "text-align: right">{{ ($payoff_cnt > 0)? number_format($payoff_sum,2,'.',',') . " ( " . $payoff_cnt . " )" : number_format($payoff_sum,2,'.',',')}}</td>
                                            <td style = "text-align: right">{{ number_format($payoff_int_sum,2,'.',',') }}</td>
                                            @var  $write_off_sum = 0
                                            @if(!empty($write_offs) && count($write_offs) > 0)
                                                @foreach($write_offs as $writeoff)
                                                    @if(date('mY',strtotime(date('Ymd',strtotime($key.'01')))) == date('mY',strtotime($writeoff['write_off_date'])))
                                                        <?php
                                                        $write_off_sum += $writeoff['amount'] / $exchange_rate_arr[$writeoff->loan_id];
                                                        $write_off_cnt++;
                                                        $total_writeoff += $writeoff['amount'] / $exchange_rate_arr[$writeoff->loan_id];
                                                        ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <td style = "text-align: right">{{ ($write_off_cnt > 0)? number_format($write_off_sum,2,'.',',') . " ( " . $write_off_cnt . " )" : number_format($write_off_sum,2,'.',',') }}</td>
                                            @var $close_sum = 0
                                            @if(!empty($closes) && count($closes) > 0)
                                                @foreach($closes as $close)
                                                    @if(date('mY',strtotime(date('Ymd',strtotime($key.'01')))) == date('mY',strtotime($close['closed_date'])))
                                                        <?php
                                                        $close_sum += $close['amount'] / $exchange_rate_arr[$close->loan_id];
                                                        $close_cnt++;
                                                        $total_close += $close['amount'] / $exchange_rate_arr[$close->loan_id];
                                                        ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <td style = "text-align: right">{{ ($close_cnt > 0) ? number_format($close_sum,2,'.',','). " ( " . $close_cnt . " )" : number_format($close_sum,2,'.',',') }}</td>
                                            <td style="text-align: right;">{{ (!isset($value[5]))? 0: $value[5] }}</td>
                                        </tr>

                                    @endforeach
                                    @endif
                                    <tr style="text-align: right; border-top: double; font-weight: bold;">
                                        <td>{{ trans('report.rpt_total') }}</td>
                                        <td style="text-align: center">{{$total_borrower}}</td>
                                        <td>{{number_format($total_sch_principal,2,'.',',')}}</td>
                                        <td>{{number_format($total_disburse,2,'.',',')}}</td>
                                        <td>-</td>
                                        <td>{{number_format($total_sch_interest,2,'.',',')}}</td>
                                        <td>{{number_format($total_sch_fee,2,'.',',')}}</td>
                                        <td>{{number_format($total_act_principal,2,'.',',')}}</td>
                                        <td>{{number_format($total_disburse,2,'.',',')}}</td>
                                        <td>-</td>
                                        <td>{{number_format($total_act_interest,2,'.',',')}}</td>
                                        <td>{{number_format($total_act_fee,2,'.',',')}}</td>
                                        <td>{{number_format($total_act_payoff_principal,2,'.',',')}}</td>
                                        <td>{{number_format($total_act_payoff_interest,2,'.',',')}}</td>
                                        <td>{{number_format($total_writeoff,2,'.',',')}}</td>
                                        <td>{{number_format($total_close,2,'.',',')}}</td>
                                        <td>{{$total_completed_borrower}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.floatThead.min.js',isset($secure) ? false : false)}}"></script>
    
    <script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
    
    <script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>

    <script type="text/javascript">
	    /*function exportTableToCSV($table, filename) {
	        var $headers = $table.find('tr.header:has(th)')
	            ,$rows = $table.find('tr:has(td)')

	            // Temporary delimiter characters unlikely to be typed by keyboard
	            // This is to avoid accidentally splitting the actual contents
	            ,tmpColDelim = String.fromCharCode(11) // vertical tab character
	            ,tmpRowDelim = String.fromCharCode(0) // null character

	            // actual delimiter characters for CSV format
	            ,colDelim = '","'
	            ,rowDelim = '"\r\n"';

	        var $header1 = $table.find('tr.head-1:has(th)')
	            ,$rows = $table.find('tr:has(td)')

	            // Temporary delimiter characters unlikely to be typed by keyboard
	            // This is to avoid accidentally splitting the actual contents
	            ,tmpColDelim = String.fromCharCode(11) // vertical tab character
	            ,tmpRowDelim = String.fromCharCode(0) // null character

	            // actual delimiter characters for CSV format
	            ,colDelim = '","'
	            ,rowDelim = '"\r\n"';

            	// Grab text from table into CSV formatted string
	            var csv = '"';
	            csv += ($('#p-header').html()).trim();
	            csv += rowDelim;
	            var csvx = formatRows($headers.map(grabRow));
				csvx = csvx.split(',');
				csv += csvx[0]+','+csvx[1]+','+','+','+','+','+csvx[2]+','+','+','+','+csvx[3]+'';

				csv += rowDelim;
                            	csv += formatRows($header1.map(grabRow));
				//console.log(aaa); return false;


	            csv += rowDelim;
	            csv += formatRows($rows.map(grabRow)) + '"';
	            // Data URI
	            var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);


	        $(this)
	            .attr({
	            'download': filename
	                ,'href': csvData
	                //,'target' : '_blank' //if you want it to open in a new window
	        });

	        //------------------------------------------------------------
	        // Helper Functions
	        //------------------------------------------------------------
	        // Format the output so it has the appropriate delimiters
	        function formatRows(rows){
	            return rows.get().join(tmpRowDelim)
	                .split(tmpRowDelim).join(rowDelim)
	                .split(tmpColDelim).join(colDelim);
	        }
	        // Grab and format a row from the table
	        function grabRow(i,row){
	            var $row = $(row);
	            //for some reason $cols = $row.find('td') || $row.find('th') won't work...
	            var $cols = $row.find('td');
	            if(!$cols.length) $cols = $row.find('th');

	            return $cols.map(grabCol)
	                        .get().join(tmpColDelim);
	        }
	        // Grab and format a column from the table
	        function grabCol(j,col){
	            var $col = $(col),
	                $text = $col.text().trim();

	            return $text.replace('"', '""'); // escape double quotes

	        }
        }*/
        $(document).ready(function(){
            $(".sticky-header").floatThead({scrollingTop:77});
         	// This must be a hyperlink
            $("#export").click(function (event) {
                //$('#divTab table.repayment-plan').removeClass('sticky-header'); return false;
                // var outputFile = 'export'
                var con = confirm("Do you really want to export to CSV file?");
                if(con == true){
                    //var outputFile = 'repayment_plan.csv';
                    // CSV
                    //exportTableToCSV.apply(this, [$('#divTab>table'), outputFile]);
                    //TableExport.prototype.charset = "charset=utf-8";
                    var tb = new TableExport($('#divTab>table'), {
                            formats: ['csv'],
                            filename:'repayment_plan'
                        });//.formatConfig.csv.mimeType = "text/csv;charset=utf-8";
                        tb.formatConfig.csv.mimeType = "application/csv;charset=utf-8";
                        //tb.formatConfig.csv.enforceStrictRFC4180 = false;
                    $('button.csv').hide().click();
                    $('.tableexport-caption').remove();
                
                }

            });
            $("#xexport").click(function (event) {
                var con = confirm("Do you really want to export to Excel file?");
                if(con == true){
                    new TableExport($('#divTab>table'), {
                            formats: ['xlsx'],
                            filename:'repayment_plan'
                        });//.formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
                }
            });
            $('.from_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
            $('.to_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
        });
    </script>
@endsection
