
@extends('layouts.app')

@section('css')
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
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
    <header class="panel-heading">
        {{ trans('sidebar.sb_loan_payment_summary') }} <?php echo date('F-Y'); ?>
    </header>

    <div class="panel-body">
        <div class="position-center" style="width:90%;">
            <form role="form" class="cmxform" method="get" action="{{ route('loan_list')}}" id="search_frm">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group ">
                      <label for="Contract ID" class="control-label">{{ trans('report.rpt_contract_id') }}</label>
                        <input type="text" class="form-control" id="contract_id" value="{{$contract_id}}" name="contract_id" />
                  </div>

                  <div class="form-group ">
                      <label for="Approval" class="control-label">{{ trans('customer.cus_customer_name') }}</label>
                      <input type="text" class="form-control" value="{{$client_name}}" id="customer_name" name="customer_name" />
                     
                  </div>
     
                  <div class="form-group ">
                      <label for="Approval" class="control-label">{{ trans('loan.l_approval_id') }}</label>
                      
                          <input type="text" class="form-control" value="{{$approval_id}}" id="approval_id" name="approval_id" />
                      
                  </div>

                    <div class="form-group ">
                        
                            <input type="hidden" name="offset" />
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                            <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                            <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>                        </div>
                    
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
        <br/><br/>
        <section id="unseen">
            <div id="printArea">
              @include('api.report_header')
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed table-hover loan-list" id="loan_payment_summary">
                <thead>
                    <tr>
                        <th class="bNone"></th>
                        <th class="bNone"></th>
                        <th class="bNone"></th>
                        <th class="bNone"></th>
                        <th class="bNone"></th>
                        <th class="bNone"></th>
                        <th class="bNone"></th>
                        <th class="bNone bShow"></th>
                        <th colspan="4" class="bText" style="background-color: lightyellow;">{{ trans('report.rpt_amount_to_be_paid') }}</th>
                        <th class="bNone bShow"></th>
                        <th colspan="5" class="bText" style="background-color: palegreen">{{ trans('report.rpt_paid_amount') }}</th>
                        <th class="bNone bShow"></th>
                        <th class="bNone"></th>
                        <th class="bNone"></th>
                        <th class="bNone"></th>
                    </tr>
                    <tr>
                        <th style="text-align: center; vertical-align: middle;" >{{ trans('multiple.m_no') }}</th>
                        <th style="text-align: center; vertical-align: middle">
                            <a href="{{ $order_url['contract_id'] }}" style="display: block;">
                                {{ trans('report.rpt_contract_id') }}<i class="fa {{ $order_class['contract_id'] }} pull-right"></i>
                            </a>
                        </th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('loan.l_approval_id') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('customer.cus_customer_name') }} </th>
                        <th style="text-align: center; vertical-align: middle;">
                            <a href="{{ $order_url['start_date'] }}" style="display: block">
                                {{ trans('multiple.m_start_date') }}<i class="fa {{ $order_class['start_date'] }} pull-right"></i>
                            </a>
                        </th>
                        <th style="text-align: center; vertical-align: middle;">
                            <a href="{{ $order_url['amount'] }}" style="display: block">
                                {{ trans('report.rpt_loan_amount') }}<i class="fa {{ $order_class['amount'] }} pull-right"></i>
                            </a>
                        </th>
                        <th style="text-align: center; vertical-align: middle;">
                            <a href="{{ $order_url['interest_rate'] }}" style="display: block">
                                {{ trans('report.rpt_int_rate') }}<i class="fa {{ $order_class['interest_rate'] }} pull-right"></i>
                            </a>
                        </th>
                        <th style="text-align: center; vertical-align: middle;">
                            <a href="{{ $order_url['tenure'] }}" style="display: block">
                                {{ trans('report.rpt_tenure') }}<i class="fa {{ $order_class['tenure'] }} pull-right"></i>
                            </a>
                        </th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_interest') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_principal') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_fee') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_penalty') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_principal_balance') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_paid_date') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_interest') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_principal_balance') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_penalty') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_arrears') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('loan.l_last_paid_date') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_overdue') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('loan.l_step',['num'=>'']) }} </th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_note') }} </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $n = 1;?>
                    <tr>
                        @forelse ($loans as $loan)
                        <?php
                        //$loan = $l[0];
                        $c = 'C';
                        $today = date("d-M-Y", strtotime('today'));
                        $todays = date("Y-m-d", strtotime('today'));
                        $loan_date = $loan->start_date;
                        $result = null;
                        $paid_flag = 0;
                        $lastPayment = null;
                        $payment = $loan->payment;
                        $interest_sum = 0;
                        $principal_sum = 0;
                        $total_prin = 0;
                        $late_day = 0;
                        $note = "";
                        if (($month_index = get_month_idx($loan->disburse_date, $todays, $loan->schedule)) < 0) {
                            continue;
                        }
                        $sch_repay_arr = get_auto_repay_array($loan, $todays);
                        //dd($sch_repay_arr);

                        $result_total_penalty = LoanCalculate::getTotalPenalty($loan);
                        $result = $result_total_penalty[0];
                        $overdue = $result_total_penalty[2];
                        $repayment_array_global = $result_total_penalty[1];
                        if (count($repayment_array_global) <= 0) {
                            continue;
                        }
                        if ($month_index >= count($repayment_array_global)) {
                            $month_index = count($repayment_array_global) - 1;
                        }
                        $start_index = 0;
                        $fee_amount = 0;
                        $total_penalty = 0;

                        $flg = 0;
                        for ($i = 1; $i < $loan->loan_duration; $i++) {
                            if (empty($result[$i])) {
                                continue;
                            }
                            //$arrear_amount += $result[$i][3] + $result[$i][4];
                            $fee_amount += $result[$i][10];
                            $total_penalty += $result[$i][8];
                            if ($flg != 1) {
                                $start_index = $i;
                                $flg = 1;
                            }
                        }
                        if (!empty($payment) && count($payment) > 0) {
                            $paid_payment = $payment->where('status', 0, false)->first();
                            $lastPayment = $payment->sortByDesc(function($pay, $key) {
                                        return $pay->id;
                                    })->first();
                            if ($paid_payment == null) {
                                $paid_payment = $payment->where('status', 1, false)->first();
                            }
                            //echo '<br>'.$paid_payment->payment_month.'='.count($repayment_array_global);
                            if ($paid_payment != null) {
                                $paid_index = $paid_payment->payment_month;
                                $count_re_gl = count($repayment_array_global);
                                if ($paid_payment->condition_id == 1) { // pay next time
                                    if ($paid_index >= $count_re_gl) {
                                        $paid_index = $count_re_gl - 1;
                                    }
                                } else {
                                    $paid_index = $paid_index + 1;
                                    if ($paid_index >= $count_re_gl) {
                                        $paid_index = $count_re_gl - 1;
                                    }
                                }
                            }
                            foreach ($payment as $p) {
                                $total_prin += $p->paid_principal;
                                if ($p->note != "") {
                                    $note .=$p->repayment_date . ' : ' . $p->note . "<br/>";
                                }
                                if ($month_index <= $p->payment_month) {
                                    $interest_sum +=$p->paid_interest;
                                    $principal_sum += $p->paid_principal;
                                    $paid_flag = 1;
                                }
                            }
                            if ($month_index > 0 && $repayment_array_global[$month_index][4] > ($interest_sum + $principal_sum)) {
                                $paid_flag = 0;
                            }
                        }

                        //PARC Level
                        $parc_lvl = $loan->parc_step > 0 ? $loan->parc_step : '-';
                        // Balance
                        $prin_balance = $loan->loan_amount - $total_prin;
                        ?>
                        <td align="center">{{$n++}}</td>
                        <td align="center"><a href="{{ route('loan_detail', [$loan->id])}}">{{ $loan->contract_id ? $loan->contract_id : '-'  }}</a></td>
                        <td align="center">{{$loan->loanapproval?$c:''}}{{ $loan->loanapproval ? str_pad($loan->loanapproval->id, 6, '0', STR_PAD_LEFT): '-' }}</td>
                        <td align="left">{{$loan->client ? $loan->client->client_name: '-'}}</td>
                        <td align="center">{{date("Y-m-d", strtotime($loan->start_date))}}</td>
                        <td align="right">{{number_format($loan->loan_amount, 2, '.', ',')}}</td>
                        <td align="center">{{$loan->interest_rate}}%</td>
                        <td align="center">{{$loan->loan_duration}} M</td>
                        <td align="right">{{($month_index>0) ? number_format($repayment_array_global[$month_index][2], 2, '.', ','):"-"}}</td>
                        <td align="right">{{($month_index>0) ? number_format($repayment_array_global[$month_index][3], 2, '.', ','):"-"}}</td>
                        <td align="right">{{($month_index>0) ? number_format($fee_amount, 2, '.', ',') : "-"}}</td>
                        <td align="right">{{ ($paid_flag > 0) ? 0 : number_format($total_penalty, 2, '.', ',')}}</td>
                        <td align="right">{{number_format($prin_balance, 2, '.', ',') }}</td>
                        <td align="center">{{(($paid_flag > 0) && ($lastPayment != null)) ? date("Y-m-d",strtotime($lastPayment->repayment_date)) : '-'}}</td>
                        <td align="right">{{(($paid_flag > 0) && (!empty($lastPayment))) ? number_format($interest_sum, 2, '.', ',') : '-'}}</td>
                        <td align="right">{{(($paid_flag > 0) && (!empty($lastPayment))) ? number_format($principal_sum, 2, '.', ',') : '-'}}</td>
                        <td align="right">{{(($paid_flag > 0) && (!empty($lastPayment))) ? number_format($lastPayment->penalty_amount, 2, '.', ',') : '-'}}</td>
                        <td align="right">{{(($paid_flag > 0) && (!empty($lastPayment))) ? number_format($lastPayment->repayment_owed, 2, '.', ',') : '-'}}</td>
                        <td align="center">{{ $lastPayment != null  ? $result_total_penalty[3] : '-'}}</td>
                        <td align="center">{{ $overdue }}</td>
                        <td align="center">{{ $parc_lvl}}</td>
                        <td align="center"><div class="textWrapper"><a href="#" data-toggle="modal" data-target="#myModal{{ $loan->id }}">{{$note}}</a></div></td>
                    </tr>
                    <!-- Modal -->
                  <div class="modal fade" id="myModal{{$loan->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-sm">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <h5 class="modal-title" id="myModalLabel">Note</h5>
                              </div>
                              <div class="modal-body">
                                  <div>{!! $note !!}</div>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                          </div>
                      </div>
                  </div>
                @empty
                  <tr><td colspan=22>{{ trans('multiple.m_no_result') }}</td></tr>
                @endforelse

                </tbody>
            </table>
          </div>
        </section>
        <div class="page pull-right" style="margin-left: 1200px;">
            <?PHP
            echo $loans->appends([
                'contract_id' => Input::get('contract_id'),
                'customer_name' => Input::get('customer_name'),
                'approval_id' => Input::get('approval_id'),
                'offset' => Input::get('offset')
            ])->render();
            ?>
        </div>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        //pagination
        $('.custom-pagi a').on('click', function () {
            val = $(this).parent().find('input[name="set_offset"]').val();
            $('input[name="offset"]').val(val);
            $('#search_frm').submit();
            return false;
        });

    });

    $("#export").click(function (event) {
        var con = confirm("Do you really want to export to CSV file?");
        if(con == true){
            new TableExport(document.getElementById('loan_payment_summary'), {
                formats: ['csv'],
                filename:'loan_payment_summary'
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
        event.preventDefault();
    });

    $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('loan_payment_summary'), {
                    formats: ['xlsx'],
                    filename: 'loan_payment_summary'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });

</script>
@endsection
