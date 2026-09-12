@extends('layouts.app')

@section('css')

<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
<style>
	@media print {
	   .hide-this{
	     display: none !important;
	   }
	}
</style>
@endsection

@section('content')
<section class="panel">
    <div class="panel-heading">
       {{ trans('sidebar.sb_unauthorized_list') }}

       <div style="float:right;">
         <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
         <button class="btn btn-primary" id="export"><i class="fa fa-sign-out"></i> {{ trans('multiple.export') }}</button>
       </div>
    </div>
    <form role="form" class="cmxform form-horizontal" method="get" id="search_frm">
        <input type="hidden" name="offset" />
    </form>
    <div class="page">
        <div class="custom-pagi">
            <span class="pagi_label">Number of Rows:</span>
            <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
            <a href="#" class="btn btn-danger">Go</a>
        </div>
    </div>
    <br/><br/><br/><br/>
    <div class="panel-body ox-scroll">
      <div id="printArea">
        @include('api.report_header')
        <table class="table table-bordered" id="unauth">
            <thead>
                <tr>
                    <th style="text-align: center;">{{ trans('multiple.m_no') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_office') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_contract_id') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_contract_date') }}</th>
                    <th style="text-align: center;">{{ trans('product.p_product_id') }}</th>
                    <th style="text-align: center;">{{ trans('customer.cus_customer_name') }}</th>
                    <th style="text-align: center;" class="hide-this">{{ trans('multiple.m_action') }}</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($loans) && count($loans) > 0)
                   @foreach($loans as $l)
                        <tr>
                            <td align="center">{{ $l->id }}</td>
                            <td align="center">{{ !empty($l->branch) ? $l->branch->branch_name : ''}}</td>
                            <td align="center"><a href="{{route('loan_detail', [$l->id])}}">{{ $l->contract_id }}</a></td>
                            <td align="center">{{ $l->start_date }}</td>
                            <td align="center">{{ str_pad($l->product_id , 6, '0', STR_PAD_LEFT)}}</td>
                            <td>{{ !empty($l->client) ? $l->client->client_name : ''}}</td>
                            <td align="center" class="hide-this">
                                <a href="{{ route('loan_detail',[$l->id])}}"  class="btn btn-primary btn-xs"><i class="fa fa-search-minus"></i> {{ trans('multiple.m_detail') }}</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="7">{{ trans('multiple.m_no_result') }}</td></tr>
                @endif
            </tbody>
        </table>
      </div>
        <div class="page" style="margin-left: 1200px;">
            <?PHP
            echo $loans->appends([
                'offset' => Input::get('offset')
            ])->render();
            ?>
        </div>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script>
$("#export").click(function (event) {
    var con = confirm("Do you really want to export to CSV file?");
    if(con == true){
        new TableExport(document.getElementById('unauth'), {
            formats: ['csv'],
            filename: "unauthorized_list"
        });
        $('button.csv').hide().click();
        $('.tableexport-caption').remove();
    }
});
$(document).ready(function () {
    $('.custom-pagi a').on('click', function () {
        val = $(this).parent().find('input[name="set_offset"]').val();
        $('input[name="offset"]').val(val);
        $('#search_frm').submit();
        return false;
    });
});
</script>
@endsection
