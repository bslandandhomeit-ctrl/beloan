@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false)}}"/>
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
        <span>{{ trans('loan.loan_reschedule_summary') }}</span>
    </header>
    <div class="panel-body">
        <div class="position-center" style="width:90%;">
            <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('reschedule_to_approve') }}" id="search_frm">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <input type="hidden" name="offset" value="<?php echo $offset ?>" />
                            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                            <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                            <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="page">
                            <div class="custom-pagi">
                                <span class="pagi_label">Number of Rows:</span>
                                <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                                <a href="#" class="btn btn-danger">Go</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <section id="unseen" class="ox-scroll">
            <?php
                $loan_type = $product_type;
                $loan_status = config('static_data.loan_status');
            ?>
            <div id="printArea">
                @include('api.report_header')
                <table  class="table table-striped table-bordered" style="width:100%" id="waiting_to_approve">
                    <thead>
                    <tr style= "white-space: nowrap;">
                        <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_no') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('customer.cus_customer_name') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_contract_id') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_loan_account_number') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_loan_type') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('account.currency') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_disbursement_date') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_maturity_date') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('loan.l_tenure') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_loan_amount') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_int_rate') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_int_rate_type') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_status') }}</th>
                        <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_note') }}</th>
                    </tr>
                            
                    </thead>
                    <tbody style="vertical-align: middle">
                        <?php $i = 0;?>
                        @forelse($loans as $l)
                        <tr>
                            <?php
                                  $i++;
                                  $date = null;
                                  switch ($l->status) {
                                      case 1: $date = $l->submitted_on;
                                          break;
                                      case 2: $date = !empty($l->approval->approval_date) ? $l->approval->approval_date : null;
                                          break;
                                      case 3: $date = $l->disburse_date;
                                          break;
                                      case 4: $date = $l->rejected_date;
                                          break;
                                      case 5: $date = !empty($l->settlement_date) ? $l->settlement_date : null;
                                          break;
                                      case 6: $date = !empty($l->settlement_date) ? $l->settlement_date : null;
                                          break;
                                      case 7: $date = $l->submitted_on;
                                          break;
                                      case 8: $date = !empty($l->approval->approval_date) ? $l->approval->approval_date : null;
                                          break;
                                      case 9: $date = !empty($l->settlement_date) ? $l->payoff->payoff_date : null;
                                          break;
                                      case 10:$date = !empty($l->settlement_date) ? $l->settlement_date : null;
                                          break;
                                      default: break;
                                  }
                            ?>
                            <td style="text-align: center">{{$i}}</td>
                            <td style="vertical-align: middle">{{$l->client_name}}</td>
                            <td style="vertical-align: middle;text-align: center">
                                <a style="text-decoration: underline" href="{{ route('loan_reschedule_detail', [$l->id]) }}">{{ $l->contract_id ? $l->contract_id : '-'  }}</a>
                            </td>
                            <td style="vertical-align: middle;text-align: center">
                                {{(!empty($l->client_loan_account->account_no))? $l->client_loan_account->account_no : ""}}
                            </td>
                            <td style="text-align: justify">{{$loan_type[$l->loan_type - 1]->code}}</td>
                            <td style="text-align: justify">{{$l->client_loan_account->currencies->code}}</td>
                            <td style="text-align: center">{{$l->disburse_date ? date("d-M-Y", strtotime($l->disburse_date)) : '-'}}</td>
                            <td style="text-align: center">{{$l->schedule->last()->schedule_date ? date("d-M-Y", strtotime($l->schedule->last()->schedule_date)) : '-'}}</td>
                            <td style="text-align: right">{{$l->loan_duration}}</td> 
                            <td style="text-align: right">{{$l->original_amount ? number_format($l->original_amount,2,'.',',') : number_format($l->loan_amount,2,'.',',')}}</td>
                            <td style="text-align: center">{{$l->interest_rate ? number_format($l->interest_rate, 2) : ''}}%</td>
                            <td style="text-align: center">{{$l->rate_type}}</td>
                            <td align="justify">{{$loan_status[$l->status]}}</td>
                            <td style="text-align: left">
                                <?php
                                    $status = $l->status == 1 ? "Submitted": $loan_status[$l->status];
                                    $status .= " on ";
                                    if ($date != null) {
                                        $status .= date_format(date_create($date), "d-M-Y");
                                        echo $status;
                                    } else {
                                        echo "<label style='color:red'> Unknown</label>";
                                    }
                                ?>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="15">{{ trans('multiple.m_no_result') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {!! str_replace('?page', '&page', $loans->appends(\Request::except('page'))->render()) !!}
            </div>
        </section>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
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
    $("#export").click(function (event) {
        var con = confirm("Do you really want to export to CSV file?");
        if(con == true){
            new TableExport(document.getElementById('waiting_to_approve'), {
                formats: ['csv'],
                filename:'waiting_to_approve'
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
    });

    $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('waiting_to_approve'), {
                    formats: ['xlsx'],
                    filename: 'waiting_to_approve'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });

});
</script>
@endsection
