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
        <span>{{ trans('sidebar.schedule_eir') }}</span>
    </header>
    <div class="panel-body">
        <div class="position-center" style="width:90%;">
            <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('schedule_eir') }}" id="search_frm">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="contract_id" class="col-lg-3 control-label">{{ trans('report.rpt_contract_id') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="contract_id" name="contract_id" value="{{ $contract_id }}" />
                            </div>
                        </div>
                    </div>
                    <?php
                    $loan_type = $product_type;
                    $loan_status = config('static_data.loan_status');
                    //var_dump($status); dd(array_key_exists($status,$loan_status));
                    ?>
                    <div class="col-lg-5">
                        <div class="form-group">
                                <input type="hidden" name="offset" />
                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                                <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                                <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                                <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="page">
            <!-- <div class="custom-pagi">
                <span class="pagi_label">Number of Rows:</span>
                <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                <a href="#" class="btn btn-danger">Go</a>
            </div> -->
        </div>
        <br/><br/><br/><br/>
        <section id="unseen" class="ox-scroll">
          <div id="printArea">
            @include('api.report_header')
            <table  class="table table-striped table-bordered" style="width:100%" id="loan_status_summary">
                <thead>
                <th style="text-align: center; vertical-align: middle;">{{ trans('customer.cus_customer_name') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_contract_id') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_loan_account_number') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_loan_type') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('account.currency') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: lightyellow;">{{ trans('report.num_months') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: lightyellow;">{{ trans('loan.l_day') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: lightyellow;">{{ trans('loan.l_repayment_date') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: lightyellow;">{{ trans('report.loan') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: lightyellow;">{{ trans('report.rpt_fee') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: lightyellow;">{{ trans('report.monthly_fee') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: lightyellow;">{{ trans('report.rpt_interest') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: lightyellow;">{{ trans('multiple.os') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: palegreen;">{{ trans('multiple.eir_rate') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: palegreen;">{{ trans('multiple.cash_flow') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: palegreen;">{{ trans('multiple.net_loan') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: palegreen;">{{ trans('multiple.interest_irfs') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: palegreen;">{{ trans('multiple.os') }}</th>
                <th style="text-align: center; vertical-align: middle;background-color: palegreen;">{{ trans('multiple.amortize_fee') }}</th>
                
                </thead>
                <tbody style="vertical-align: middle">
                @forelse($loans as $l)

                    <?php
                        $eir_rate = round(LoanCalculate::get_xirr($l),2);
                        $date = null;
                        $os_bal_ifrs = $os_bal = -$l->loan_amount;
                    ?>
                    @foreach($l->schedule as $sch)
                        <?php
                            $interest_ifrs = $net_loan = $total_monthly = 0;
                        ?>
                    <tr>
                        <td style="vertical-align: middle">{{$l->client_loan_account->account_name}}</td>
                        <td style="vertical-align: middle;text-align: center">
                            <a style="text-decoration: underline" href="{{ route('loan_detail', [$l->id])}}">{{ $l->contract_id ? $l->contract_id : '-'  }}</a>
                        </td>
                        <td style="vertical-align: middle;text-align: center">
                            <a style="text-decoration: underline" href="{{ route('loan_account', [$l->loan_account_id])}}">{{(!empty($l->client_loan_account->account_no))? $l->client_loan_account->account_no : ""}}</a>
                        </td>
                        <td style="text-align: center">{{$loan_type[$l->loan_type - 1]->code}}</td>
                        <td style="text-align: center">{{$l->client_loan_account->currencies->code}}</td>
                        <td style="text-align: center">{{$sch->no}}</td>
                        <td style="text-align: center">{{$sch->date_num}}</td>
                        <td style="text-align: center">{{$sch->schedule_date}}</td>
                        <td style="text-align: right">{{number_format(($sch->no==0)? $os_bal : $sch->principal,2)}}</td>
                        <td style="text-align: right">{{number_format(($sch->no==0)?$sch->fee:0,2)}}</td>
                        <td style="text-align: right">{{number_format(($sch->no>0)?$sch->fee:0,2)}}</td>
                        <td style="text-align: right">{{number_format($sch->interest,2)}}</td>
                        <?php 
                            $os_bal += $sch->principal; 
                            $total_monthly = $sch->principal + $sch->interest + $sch->fee;
                            //$interest_ifrs = $sch->date_num * (-$os_bal_ifrs) * $eir_rate /36000;
                            $interest_ifrs = LoanCalculate::get_interest_ifrs($os_bal_ifrs, $eir_rate, $sch->date_num);
                            if($sch->no == 0){
                                $net_loan = $os_bal_ifrs;
                            }else{
                                $net_loan = $total_monthly - $interest_ifrs;
                                $os_bal_ifrs += $net_loan;
                            }
                            $amortize_fee = $interest_ifrs - $sch->interest;
                        ?>
                        <td style="text-align: right">{{number_format($os_bal,2)}}</td>
                        <td style="text-align: center">{{number_format($eir_rate,2)}}</td>
                        <td style="text-align: right">{{number_format(($sch->no==0)?$os_bal_ifrs:$total_monthly,2)}}</td>
                        <td style="text-align: right">{{number_format($net_loan,2)}}</td>
                        <td style="text-align: right">{{number_format($interest_ifrs,2)}}</td>
                        <td style="text-align: right">{{number_format($os_bal_ifrs,2)}}</td>
                        <td style="text-align: right">{{number_format($amortize_fee,2)}}</td>
                        @endforeach
                    </tr>
                    @empty
                    <tr><td colspan="9">{{ trans('multiple.m_no_result') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
          </div>
            <!-- <div class="page pull-right">
                <?PHP
                // echo $loans->appends([
                //     'contract_id' => Input::get('contract_id'),
                //     'status' => Input::get('status'),
                //     'offset' => Input::get('offset')
                // ])->render();
                ?>
            </div> -->
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
            new TableExport(document.getElementById('loan_status_summary'), {
                formats: ['csv'],
                filename:'loan_status_summary'
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
    });

    $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('loan_status_summary'), {
                    formats: ['xlsx'],
                    filename: 'loan_status_summary'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });

});
</script>
@endsection
