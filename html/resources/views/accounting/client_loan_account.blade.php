@extends('layouts.app')

@section('css')
<link href="{{ asset('css/loan-style.css',false) }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',false) }}"/>
<style>
    @media print {
      a[href]:after {
      content: none !important;
      }
    }
</style>
@endsection
@section('content')

<?php
    $branch = config('static_data.branch');
    $currency = config('static_data.currency_symbol');
    $status = config('static_data.client_loan_account_status');
?>
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('sidebar.sb_client_loan_account') }}</span>
        <div style="float:right;">
            <a class="btn btn-info" id="create_loan_account" href="{{route('add_client_loan_account',[0])}}"><i class="fa fa-plus"></i> {{ trans('account.a_new_cus_loan_account') }}</a>
            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
            <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
<a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>        </div>
    </header>
    <?php
        isset($offset)?"yep":"noo";
     ?>
     <div class="panel-body">
         <div class="text-right">
             <form role="form" class="cmxform form-inline" method="get" action="{{ route('client_loan_account') }}" id="search_frm">
                 <div class="form-inline">
                     <span class="control-label">Number of Rows:</span>
                     <input type="text" class="form-control input-sm" name="set_offset" value="{{ isset($set_offset)?$set_offset:15 }}" />
                     <button type="submit" class="btn btn-danger">Go</button>
                 </div>
             </form>
         </div>

        <section id="unseen" class="ox-scroll">
          <div id="printArea">
            @include('api.report_header')
            <table  class="table table-bordered table-striped table-condensed table-hover clientTable">
                <thead>
                <th>#</th>
                <th style="text-align: center;">{{ trans('multiple.account_name') }}</th>
                <th style="text-align: center;">{{ trans('multiple.account_no') }}</th>
                <th style="text-align: center;">{{ trans('multiple.branch') }}</th>
                <th style="text-align: center;">{{ trans('multiple.balance') }}</th>
                <th style="text-align: center;">{{ trans('multiple.status') }}</th>
                </thead>
                <tbody>
                    @forelse($client_loans as $list)
                    <?php $i++?>
                    <tr>
                        <td><a style="text-decoration: underline" href="{{ route('loan_account', [$list->id])}}">{{$i}}</a></td>
                        <td>{{$list->account_name}}</td>
                        <td>{{$list->account_no}}</td>
                        <td>{{$branch[$list->branch]}}</td>
                        <td>{{$currency[$list->currency].number_format($list->balance)}}</td>
                        <td>{{$status[$list->status]}}</td>
                    </tr>
                    @empty
                    <tr><td colspan=9>{{ trans('multiple.m_no_result') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
          </div>
            <div class="page">
                <?php echo $client_loans->appends()->render(); ?>
            </div>
        </section>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js', false) }}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',false)}}"></script>
<script type="text/javascript" src="{{ asset('js/xlsx.full.min.js',isset($secure) ? false : false)}}"></script>
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
            new TableExport(document.getElementsByTagName('table'), {
                formats: ['csv'],
                filename:"client_loan_account"
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
        event.preventDefault();
    });

    $("#xexport").click(function (event) {
            var con = confirm("Do you really want to export to Excel file?");
            if(con == true){
                new TableExport(document.getElementsByTagName('table'), {
                        formats: ['xlsx'],
                        filename: 'client_loan_account'
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });

});
</script>
@endsection
