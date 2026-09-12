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
            {{ trans('sidebar.sb_reschedule_loans') }}
            @if($data['start'] && $data['end']){{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($data['start'])) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($data['end'])) }} @else Invalid date supplied. @endif
            @foreach($branch as $b)
                @if(isset($data['bn']))
                    @if($data['bn']==$b->id)
                        ({{ $b->branch_name }})
                    @endif
                @endif
            @endforeach
        </header>

        <div class="panel-body">
            <div class="position-center">
                <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('reschedule_repayment_report') }}">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="Name" class=" control-label">{{ trans('multiple.m_start_date') }}</label>
                                
                                    <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd" data-date="{{ date('Y-m-d',strtotime($data['start'])) }}" class="input-append date dpStart">
                                        <input type="text" name="dpStart" size="16" class="form-control" placeholder="Please select start date" value="{{ date('Y-m-d',strtotime($data['start'])) }}">
                                            <span class="add-on date">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                
                            </div>
                            <div class="form-group">
                                <label for="inputCardnumber" class="control-label">{{ trans('multiple.m_end_date') }}</label>
                                
                                    <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd" data-date="{{ date('Y-m-d',strtotime($data['end']))}}" class="input-append date dpEnd">
                                        <input type="text" name="dpEnd" size="16" class="form-control" placeholder="Please select end date" value="{{ date('Y-m-d',strtotime($data['end']))}}">
                                            <span class="add-on date">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="Phone" class="control-label">{{ trans('report.rpt_co_name') }}</label>
                                
                                    <select class="form-control" id="officer" name="officer">
                                        <option value="">-</option>
                                        @foreach($officer as $o)
                                            <option value="{{ $o->id }}"
                                                @if(isset($data['co']))
                                                    @if($data['co']==$o->id)
                                                        selected
                                                    @endif
                                                @endif>{{ $o->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                
                            </div>
                            <div class="form-group">
                                <label for="inputCardnumber" class="control-label">{{ trans('report.rpt_branch_name') }}</label>
                                
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
                    <div class="row">
                        <div class="col-lg-offset-4 col-lg-8">
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                            <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                            <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
                        </div>
                    </div>
                </form>
            </div>
            <br/><br/>
            <div id="printArea" style="clear: both">
                @include('api.report_header',['co_phone'=>!empty($co_id->co_user) ? $co_id->co_user->phone: ''])
                <h4 class="sch_title">
                    {{ trans('sidebar.sb_reschedule_loans') }}
                    @if($data['start'] && $data['end']){{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($data['start'])) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($data['end'])) }} @else Invalid date supplied. @endif
                    @foreach($branch as $b)
                        @if(isset($data['bn']))
                            @if($data['bn']==$b->id)
                                ({{ $b->branch_name }})
                            @endif
                        @endif
                    @endforeach
                </h4>
                <section id="unseen">
                    <table class="table table-striped table-hover table-bordered reschedule" id="resch">
                        <thead>
                            <tr>
                                <th class="bNone"></th>
                                <th class="bNone"></th>
                                <th class="bNone bShow"></th>
                                <th colspan="5" class="bText" style="background-color: lightyellow">{{ trans('report.rpt_old_schedule') }}</th>
                                <th colspan="11" class="bText" style="background-color: lightgreen">{{ trans('report.rpt_new_schedule') }}</th>

                            </tr>
                            <tr>
                                <th style="vertical-align: middle; text-align: center;">{{ trans('multiple.m_no') }}</th>
                                <th style="vertical-align: middle; text-align: center;">{{ trans('customer.cus_customer_name') }}</th>
                                <th style="vertical-align: middle; text-align: center;">{{ trans('report.rpt_contract_id') }}</th>
                                <th style="vertical-align: middle; text-align: center;">{{ trans('report.rpt_contract_date') }}</th>
                                <th style="text-align: center;vertical-align: middle">{{ trans('report.rpt_tenure') }}</th>
                                <th style="vertical-align: middle; text-align: center;">{{ trans('report.rpt_loan_amount') }}</th>
                                <th style="text-align: center;vertical-align: middle">{{ trans('report.rpt_int_rate') }}</th>
                                <th style="vertical-align: middle; text-align: center;">{{ trans('report.rpt_principal_balance') }}</th>
                                <th style="vertical-align: middle; text-align: center;">{{ trans('report.rpt_contract_date') }}</th>
                                <th style="text-align: center;vertical-align: middle">{{ trans('report.rpt_tenure') }}</th>
                                <th style="vertical-align: middle; text-align: center;">{{ trans('report.rpt_loan_amount') }}</th>
                                <th style="text-align: center;vertical-align: middle">{{ trans('report.rpt_int_rate') }}</th>
                                <th style="vertical-align: middle; text-align: center;">{{ trans('loan.l_last_paid_date') }}</th>
                                <th style="vertical-align: middle; text-align: center;">{{ trans('report.rpt_paid_amount') }}</th>
                                <th style="vertical-align: middle; text-align: center;">{{ trans('report.rpt_principal_balance') }}</th>
                                <th style="vertical-align: middle; text-align: center;">{{ trans('report.rpt_co_name') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($reports) && !empty($loans) && count($reports) > 0 && count($reports->items()) == count($loans))
                                <?php
                                    $n=1;
                                    $merge = $reports->getCollection()->merge($loans);
                                    $reschedule = $merge->groupBy('contract_id');
                                ?>
                            @forelse($reschedule as $r)
                                <?php
                                $balance = 0.0;
                                $principal = 0.0;
                                $new_balance = 0.0;
                                $new_principal = 0.0;
                                $total_amount_paid = 0.0;
                                $loan_amount = 0.0;
                                $new_loan_amount = 0.0;
                                if(count($r) == 2 ){
                                    $old = null;
                                    $new = null;
                                    $lastPayment = null;

                                    if($r[0]->status == 6){
                                        $old = $r[0];
                                        $new = $r[1];
                                    }else{
                                        $old = $r[1];
                                        $new = $r[0];
                                        }


                                    foreach($old->payment as $p){
                                        $principal += $p->paid_principal;
                                    }
                                    $loan_amount = $old->loan_amount;
                                    $balance = $loan_amount - $principal;

                                    foreach($new->payment as $paid){
                                        $new_principal += $paid->paid_principal;
                                        $total_amount_paid += $paid->paid_interest + $paid->paid_principal;
                                    }
                                    $new_loan_amount = $new->loan_amount;
                                    $new_balance = $new_loan_amount - $new_principal;

                                    $payment = $new->payment;
                                    if(!empty($payment) && count($payment) > 0){
                                        $lastPayment =  $payment->where('status',0,false)->first();
                                        if($lastPayment == null){
                                            $lastPayment = $payment->where('status', 1,false)->last();
                                        }
                                    }
                                ?>
                                <tr>
                                    <td>{{$n++}}</td>
                                    <td>{{$old->client->client_name}}</td>
                                    <td align="center">{{ date("d-M-Y", strtotime($old->contract_id)) }}</td>
                                    <td align="center"><a style="text-decoration: underline;" href="{{route('loan_detail',[$old->id])}}" title="Old Loan Detail">{{ Date("d-M-Y", strtotime($old->start_date))}}</a></td>
                                    <td align="center">{{$old->loan_duration}}</td>
                                    <td align="center">{{number_format($old->loan_amount,2,'.',',')}}</td>
                                    <td align="center">{{number_format($old->interest_rate,2)}}%</td>
                                    <td align="right">{{number_format($balance,2,'.',',')}}</td>
                                    <td align="center"><a style="text-decoration: underline;" href="{{route('loan_detail',[$new->id])}}" title="New Loan Detail">{{Date("d-M-Y", strtotime($new->start_date))}}</a></td>
                                    <td align="center">{{$new->loan_duration}}</td>
                                    <td align="right">{{number_format($new->loan_amount,2,'.',',')}}</td>
                                    <td align="center">{{$new->interest_rate}}%</td>
                                    <td align="center">{{$lastPayment != null  ? date("d-M-Y",strtotime($lastPayment->repayment_date)) : '-'}}</td>
                                    <td align="right">{{number_format($total_amount_paid,2,'.',',')}}</td>
                                    <td align="right">{{number_format($new_balance,2,'.',',')}}</td>
                                    <td align="center">{{$new->user->name}}</td>
                                </tr>
                                 <?php }?>
                            @empty
                                <tr><td colspan=16>{{ trans('multiple.m_no_result') }}</td></tr>
                            @endforelse
                            @else
                            <tr><td colspan=16>{{ trans('multiple.m_no_result') }}</td></tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pagination">
                        @include('partials.pagination',['results'=> $reports])
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/xlsx.full.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.dpStart').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            $('.dpEnd').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });
        $("#export").click(function (event) {
          var con = confirm("Do you really want to export to CSV file?");
          if(con == true){
              new TableExport(document.getElementById('resch'), {
                  formats: ['csv'],
                  filename:"reschedule_repayment_report"
              });
              $('button.csv').hide().click();
              $('.tableexport-caption').remove();
          }
          event.preventDefault();
      });
      $("#xexport").click(function (event) {
            var con = confirm("Do you really want to export to Excel file?");
            if(con == true){
                new TableExport(document.getElementById('resch'), {
                        formats: ['xlsx'],
                        filename: 'reschedule_repayment_report'
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });
    </script>
@endsection
