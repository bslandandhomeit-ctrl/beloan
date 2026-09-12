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
    <header class="panel-heading">
        {{ trans('sidebar.sb_loan_repayment') }}
        @if(!empty($data['start']) && !empty($data['end'])){{ trans('multiple.m_from') }} {{ $data['start'] }} {{ trans('multiple.m_to') }} {{ $data['end'] }} @else Invalid date supplied. @endif
        @foreach($branch as $b)
        @if(isset($data['bn']))
        @if($data['bn']==$b->id)
        ({{ $b->branch_name }})
        @endif
        @endif
        @endforeach
    </header>
    <?php $currency = config('static_data.currency'); ?>

    <div class="panel-body">
        <div>
            <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('loan_repayment_report') }}" id="search_frm">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="Name" class="col-lg-3 control-label">{{ trans('multiple.m_start_date') }}</label>
                            <div class="col-lg-7">
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd" data-date="{{$data['start']}}" class="input-append date dpStart">
                                    <input type="text" name="dpStart" size="16" class="form-control" value = "{{ $data['start'] }}">
                                    <span class="add-on date">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputCardnumber" class="col-lg-3 control-label">{{ trans('multiple.m_end_date') }}</label>
                            <div class="col-lg-7">
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd" data-date="{{$data['end']}}" class="input-append date dpEnd">
                                    <input type="text" name="dpEnd" size="16" class="form-control" value="{{ $data['end'] }}">
                                    <span class="add-on date">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="co_name" class="col-lg-3 control-label">{{ trans('report.rpt_co_name') }}</label>
                            <div class="col-lg-7">
                                <select class="form-control" id="co_name" name="co_name">
                                    <option value="">-</option>
                                    @foreach($co as $c)
                                    <option value="{{ $c->id }}"
                                            @if(isset($data['co']))
                                            @if($data['co']==$c->id)
                                            selected
                                            @endif
                                            @endif>{{ $c->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputCardnumber" class="col-lg-3 control-label">{{ trans('report.rpt_branch_name') }}</label>
                            <div class="col-lg-7">
                                <select class="form-control" id="selBrand" name="selBrand">
                                    <option value="">-</option>
                                    @foreach($branch as $b)
                                    <option value="{{ $b->id }}"
                                            @if(isset($data['bn']))
                                            @if($data['bn']==$b->id)
                                            selected
                                            @endif
                                            @endif>{{ $b->branch_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="inputCardnumber" class="col-lg-3 control-label">{{ trans('account.currency')}}</label>
                            <select class="form-control" id="cur" name="cur">
                                <option value="2">{{$currency[$cur]}}</option>
                                @foreach($currency as $key => $value)
                                <option value="{{ $key }}"
                                        @if(isset($cur) && $cur == $key)
                                        selected
                                        @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                  </div>
                  <div class="row">
                    <div class="col-sm-offset-6 col-sm-6">
                    <div class="form-group">
                                <input type="hidden" name="offset" />
                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                                <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                                <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                                <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>                        </div>
                    </div>
                    </div>
                  </div>
            </form>
    </div>
    <div class="page">
        <div class="custom-pagi">
            <span class="pagi_label">Number of Rows:</span>
            <input type="text" class="form-control" name="offset" value="<?php echo $offset ?>" />
            <a href="#" class="btn btn-danger">Go</a>
        </div>
    </div>
    <br/><br/>
    <section id="unseen" class="ox-scroll" style="width:100% !important;">
        <div id="printArea" style="width:100%;">
            @include('api.report_header',['co_phone'=>!empty($loan->co_user) ? $loan->co_user->phone: ''])
            <h4 class="sch_title">
                {{ trans('sidebar.sb_loan_repayment') }}
                @if(!empty($data['start']) && !empty($data['end'])){{ trans('multiple.m_from') }} {{ $data['start'] }} {{ trans('multiple.m_to') }} {{ $data['end'] }} @else Invalid date supplied. @endif
                @foreach($branch as $b)
                    @if(isset($data['bn']))
                        @if($data['bn']==$b->id)
                        ({{ $b->branch_name }})
                        @endif
                    @endif
                @endforeach
            </h4>
            <table class="table table-bordered table-striped table-condensed" style="width:100%;" id="loan_repayment">
                <thead class="th-center" style = "background : #1fb5ad">
                    <tr>
                        <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.m_no') }}</th>
                        <th rowspan=2 style="vertical-align:middle;">{{ trans('customer.cus_customer_name') }}</th>
                        <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_contract_id') }}</th>
                        <th rowspan=3 style="vertical-align:middle;">{{ trans('report.rpt_loan_account_number') }}</th>
                        <th rowspan=2 style="vertical-align:middle;">{{ trans('loan.l_disburse_date') }}</th>
                        <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_loan_amount') }}</th>
                        <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_int_rate') }}</th>
                        <th colspan=4 style="vertical-align:middle;">{{ trans('report.rpt_amount_to_be_paid') }}</th>
                        <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_principal_balance') }}</th>
                        <th colspan=6 style="vertical-align:middle;">{{ trans('report.rpt_paid_amount') }}</th>
                        <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_arrears') }}</th>
                        <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.m_note') }}</th>
                    </tr>
                    <tr>
                        <th>{{ trans('report.schedule_date') }}</th>
                        <th>{{ trans('report.rpt_interest') }}</th>
                        <th>{{ trans('report.rpt_principal') }}</th>
                        <th>{{ trans('report.rpt_fee') }}</th>
                        <th>{{ trans('report.rpt_paid_date') }}</th>
                        <th>{{ trans('report.rpt_interest') }}</th>
                        <th>{{ trans('report.rpt_principal') }}</th>
                        <th>{{ trans('report.rpt_fee') }}</th>
                        <th>{{ trans('report.rpt_penalty') }}</th>
                        <th>{{ trans('report.rpt_sub_total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $n = 1;
                    $t_sch_prin = $t_sch_int = $t_sch_fee = $t_act_prin = $t_act_int = $t_act_fee = $t_act_penalty = $t_act_arr = $t_os = $t_paid_sub_total = 0;
                    ?>
                    @forelse($loans as $loan)
                    @if(count($loan->payment ) > 0)
                    <?php
                    $c = 'C';
                    $lastPayment = null;
                    $interest_sum = 0;
                    $principal_sum = 0;
                    $fee_sum = 0;
                    $penalty_sum = 0;
                    $arrears_sum = 0;
                    $total_prin = 0;
                    $note = "";
                    $date_start = $data['start'];
                    $date_end = $data['end'];
                    $interest_original_sum = 0.00;
                    $principal_original_sum = 0.00;
                    $fee_original_sum = 0.00;
                    $lastPayment = $loan->payment->last();
                    $groupPaymentArray = [];
                    $groupPayment = $loan->payment->groupBy('payment_month');
                    $groupPaymentArray = $groupPayment->toArray();


                    $payment_schedule = LoanCalculate::loan_schedule($loan->schedule, $loan->disburse_date)[0];

                    $total_prin = 0;
                    foreach($loan->transaction as $tr){
                      if(date_dif($tr->trans_date, $date_start, 1, false) < 0) break;
                      $total_prin += $tr->principal;
                    }
                    $schedule_arr = [];
                    $prin_balance = $loan->loan_amount - $total_prin;

                      $cnt = 0;
                      $os_balance = 0;
                      $paid_sub_total = 0;
                      foreach($loan->payment as $repay){
                        $schedule_arr = schedule_sort_by_no($loan->schedule)[$repay->payment_month];
                        $os_balance = $prin_balance - $repay->paid_principal;
                        ?>
                        <tr>
                          @if($cnt == 0)
                            <td>{{$n}}</td>
                            <td>{{$loan->client ? $loan->client->client_name: '-'}}</td>
                            <td align="center"><a href="{{ route('loan_detail', [$loan->id])}}">{{ $loan->contract_id ? $loan->contract_id : '-'  }}</a></td>
                            <td align="center"><a href="{{ route('loan_account', [$loan->loan_account_id])}}">{{ $loan->client_loan_account->account_no }}</a></td>
                            <td align="center">{{ date("d-M-Y", strtotime($loan->disburse_date))}}</td>
                            <td align="right">{{number_format($loan->loan_amount, 2, '.', ',')}}</td>
                            <td align="center">{{$loan->interest_rate}}%</td>
                          @else
                            <td colspan="7"></td>
                          @endif
                            <?php $paid_sub_total = $repay->paid_interest + $repay->paid_principal + $repay->paid_fee + $repay->penalty_amount;?>
                            <td align="center" style = "background : yellow">{{$schedule_arr->schedule_date}}</td>
                            <td align="right" style = "background : yellow">{{number_format($schedule_arr->interest, 2, '.', ',')}}</td>
                            <td align="right" style = "background : yellow">{{number_format($schedule_arr->principal, 2, '.', ',')}}</td>
                            <td align="right" style = "background : yellow">{{number_format($schedule_arr->fee, 2, '.', ',')}}</td>
                            <td align="right">{{number_format($prin_balance - $repay->paid_principal, 2, '.', ',') }}</td>
                            <td align="center" style = "background : orange">{{date("Y-m-d",strtotime($repay->repayment_date))}}</td>
                            <td align="right" style = "background : orange">{{number_format($repay->paid_interest, 2, '.', ',')}}</td>
                            <td align="right" style = "background : orange">{{number_format($repay->paid_principal, 2, '.', ',')}}</td>
                            <td align="right" style = "background : orange">{{number_format($repay->paid_fee, 2, '.', ',')}}</td>
                            <td align="right" style = "background : orange">{{number_format($repay->penalty_amount,2,'.',',')}}</td>
                            <td align="right" style = "background : orange; font-weight: bold;">{{number_format($paid_sub_total,2,'.',',')}}</td>
                            <td align="right" style = "background : orange">{{number_format($repay->repayment_owed, 2, '.', ',')}}</td>
                            <td style = "background : orange">{{$repay->note}}</td>
                        </tr>
                        <?php
                        $n += 1;
                        $t_sch_prin += $schedule_arr->principal;
                        $t_sch_int += $schedule_arr->interest;
                        $t_sch_fee += $schedule_arr->fee;
                        $t_act_prin += $repay->paid_principal;
                        $t_act_int += $repay->paid_interest;
                        $t_act_fee += $repay->paid_fee;
                        $t_act_penalty += $repay->penalty_amount;
                        $t_paid_sub_total += $paid_sub_total;
                        $t_act_arr += $repay->repayment_owed;
                        $cnt += 1;
                      }
//                    }
                    $t_os += $os_balance;
                    ?>
                    @endif
                    @empty
                    <tr><td colspan=15>{{ trans('multiple.m_no_result') }}</td></tr>
                    @endforelse
                    <tr style = "font-weight: bold;">
                      <td colspan=8 align="right">Total</td>
                      <td colspan=1 align="right">{{ number_format($t_sch_int, 2) }}</td>
                      <td colspan=1 align="right">{{ number_format($t_sch_prin, 2) }}</td>
                      <td colspan=1 align="right">{{ number_format($t_sch_fee, 2) }}</td>
                      <td colspan=1 align="right">{{ number_format($t_os, 2) }}</td>
                      <td colspan=1 align="right"></td>
                      <td colspan=1 align="right">{{ number_format($t_act_int, 2) }}</td>
                      <td colspan=1 align="right">{{ number_format($t_act_prin, 2) }}</td>
                      <td colspan=1 align="right">{{ number_format($t_act_fee, 2) }}</td>
                      <td colspan=1 align="right">{{ number_format($t_act_penalty, 2) }}</td>
                      <td colspan=1 align="right">{{ number_format($t_paid_sub_total, 2) }}</td>
                      <td colspan=1 align="right">{{ number_format($t_act_arr, 2) }}</td>
                      <td></td>
                    </tr>

                </tbody>

            </table>
        </div>
        <div class="page">
            <?PHP
            echo $loans->appends([
                'dpStart' => Input::get('dpStart'),
                'dpEnd' => Input::get('dpEnd'),
                'co_name' => Input::get('co_name'),
                'selBrand' => Input::get('selBrand'),
                'offset' => Input::get('offset')
            ])->render();
            ?>
        </div>
    </section>
</div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.floatThead.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/xlsx.full.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>

<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript">
  $("#export").click(function (event) {
      var con = confirm("Do you really want to export to CSV file?");
      if(con == true){
          new TableExport(document.getElementById('loan_repayment'), {
              formats: ['csv'],
              filename:"loan_repayment"
          });
          $('button.csv').hide().click();
          $('.tableexport-caption').remove();
      }
  });
  $("#xexport").click(function (event) {
            var con = confirm("Do you really want to export to Excel file?");
            if(con == true){
                new TableExport(document.getElementById('loan_repayment'), {
                        formats: ['xlsx'],
                        filename: 'loan_repayment'
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });
$(document).ready(function () {
    $(".sticky-header").floatThead({scrollingTop: 77});
    $('.dpStart').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $('.dpEnd').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $('.custom-pagi a').on('click', function () {
        val = $(this).parent().find('input[name="set_offset"]').val();
        $('input[name="offset"]').val(val);
        $('#search_frm').submit();
        return false;
    });

});
</script>
@endsection
