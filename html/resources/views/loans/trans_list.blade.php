@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css"
      href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false)}}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
@endsection

@section('content')
<section class="panel">
    <header class="panel-heading">
        {{ trans('sidebar.sb_transaction_summary') }}
    </header>
    <div class="panel-body">
        <form class="form-horizontal" action="{{ route('loan_trans') }}" id="search_frm">
            <div class="form-group">
                <div class="col-sm-12">
                    @if($chk == 1)
                    <input type="radio" name="search" id="enable-search1" value="0"
                           class="radio-inline"/> {{ trans('loan.l_default') }}
                    <input type="radio" name="search" id="enable-search2" checked value="1"
                           class="radio-inline"/> {{ trans('loan.l_by_id') }}
                    <input type="radio" name="search" id="enable-search3" value="2"
                           class="radio-inline"/> {{ trans('loan.l_by_contract_id') }}
                    @elseif($chk == 2)
                    <input type="radio" name="search" id="enable-search1" value="0"
                           class="radio-inline"/> {{ trans('loan.l_default') }}
                    <input type="radio" name="search" id="enable-search2" value="1"
                           class="radio-inline"/> {{ trans('loan.l_by_id') }}
                    <input type="radio" name="search" id="enable-search3" checked value="2"
                           class="radio-inline"/> {{ trans('loan.l_by_contract_id') }}
                    @else
                    <input type="radio" name="search" id="enable-search1" checked value="0"
                           class="radio-inline"/> {{ trans('loan.l_default') }}
                    <input type="radio" name="search" id="enable-search2" value="1"
                           class="radio-inline"/> {{ trans('loan.l_by_id') }}
                    <input type="radio" name="search" id="enable-search3" value="2"
                           class="radio-inline"/> {{ trans('loan.l_by_contract_id') }}
                    @endif
                </div>
                <div class="col-sm-12" id="contract_id">
                    <label class="control-label"></label>
                    <input type="text" name="id" class="form-control" value="{{ $id }}"/>
                </div>
                <div class="col-sm-12" id="tran_id">
                    <label class="control-label"></label>
                    <input type="text" name="t" class="form-control" value="{{ $tid }}"/>
                </div>
            </div>
            <div class="form-group" id="more-search">
                <div class="col-md-2">
                    <label class="control-label">{{ trans('multiple.m_start_date') }}</label>
                    <div id="start-date" data-date-viewmode="years" data-initialize="datepicker"
                         data-date-format="yyyy-mm-dd" data-date="{{ $start }}" class="input-append date"
                         style="width: 95%;">
                        <input type="text" name="start" size="16" class="form-control" value="{{ $start }}"/>
                        <span class="input-group-btn add-on">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{ trans('multiple.m_end_date') }}</label>
                    <div id="end-date" data-date-viewmode="years" data-initialize="datepicker"
                         data-date-format="yyyy-mm-dd" class="input-append date" style="width: 95%;">
                        <input type="text" name="end" size="16" class="form-control" value="{{ $end }}"/>
                        <span class="input-group-btn add-on">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{ trans('multiple.m_client_name') }}</label>
                    <input type="text" name="client_name" class="form-control" value="{{ $client_name }}" />
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{ trans('report.rpt_contract_id') }}</label>
                    <input type="text" class="form-control" value="{{$contract_id}}" name="contract_id" />
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{ trans('report.rpt_pro_type') }}</label>
                    <input type="text" name="category_name" class="form-control" value="{{ $category_name }}"/>
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{ trans('report.rpt_transaction_type') }}</label>
                    <select class="form-control" name="type" id="type">
                        <option value="">-</option>
                        <?php 
                            $transaction_type = config('static_data.transaction_type'); 
                        ?>
                        @foreach($transaction_type as $key => $value)
                        <option value="{{ $value }}"
                                @if(isset($type) && $type == $value)
                                selected
                                @endif
                                >{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{ trans('report.rpt_breakdown_type') }}</label>
                    <select class="form-control" name="breakdown_type" id="breakdown_type">
                        <option value="">-</option>
                        <?php $breakdown_types = config('static_data.breakdown_type'); ?>
                        @foreach($breakdown_types as $key => $value)
                        <option value="{{ $value }}"
                                @if(isset($breakdown_type) && $breakdown_type == $value)
                                selected
                                @endif
                                >{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{ trans('report.rpt_co_name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ $name }}"/>
                </div>
                <div class="col-md-2">
                    <label class="control-label">{{ trans('report.rpt_invoice_number') }}</label>
                    <input type="text" name="by" class="form-control" value="{{ $by }}"/>
                </div>
                <div class="col-lg-2">
                    <label class="control-label">{{ trans('account.branch_name') }}</label><br/>
                    <select class="form-control" id="selBrand" name="selBrand">
                        <option value="">-</option>
                        @foreach($branch as $b)
                        <option value="{{ $b->id }}"
                                @if(isset($branch_id))
                                @if($branch_id==$b->id)
                                selected
                                @endif
                                @endif>{{ $b->branch_name}}</option>
                        @endforeach
                    </select>
                </div>
                
            </div>
            <div class="row">
                <div class="col-lg-12 text-right">
                    <input type="hidden" name="offset" />
                    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i>{{ trans('multiple.m_search') }}</button>
                    <button class="btn btn-warning" id="transaction_printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                    <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                    <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
                    <a class="btn btn-info" href="get_all_trans" target="_blank">{{ trans('multiple.showall') }}</a>
                </div>
            </div>
        </form>
        <br><br><br>

        <table class="table table-bordered" style="width:35%">
            <thead>
                <tr>
                    <th style="text-align: center;">{{ trans('loan.l_total_paid_amount') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_total_principal') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_total_interest') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_total_penalty') }}</th>
                    <th style="text-align: center;">{{ trans('loan.l_total_fee') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    $to_amount = 0.0;
                    $to_prin = 0.0;
                    $to_interest = 0.0;
                    $to_penalty = 0.0;
                    $to_fee = 0.0;
                    ?>
                    @if(!empty($trans) && count($trans))

                    @foreach($trans as $tran)
                    <?php
                    $to_prin += $tran->principal;
                    $to_interest += $tran->interest;
                    $to_penalty += $tran->penalty;
                    $to_fee += $tran->fee;
                    ?>
                    @endforeach
                    <?php $to_amount += $to_prin + $to_interest + $to_penalty + $to_fee; ?>
                    @endif
                    <td align="center">{{number_format($to_amount,2,'.',',')}}</td>
                    <td align="center">{{number_format($to_prin,2,'.',',')}}</td>
                    <td align="center">{{number_format($to_interest,2,'.',',')}}</td>
                    <td align="center">{{number_format($to_penalty,2,'.',',')}}</td>
                    <td align="center">{{number_format($to_fee,2,'.',',')}}</td>
                </tr>
            </tbody>
        </table>

        <div class="ox-scroll">
          <div class="page">
              <div class="custom-pagi">
                  <span class="pagi_label">Number of Rows:</span>
                  <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                  <a href="#" class="btn btn-danger">Go</a>
              </div>
          </div>
          <br/><br/><br/>
            <div id="printArea">
                <h3><b>Land Home</b></h3>
                <h4>
                    @foreach($branch as $b)
                    @if(isset($branch_id))
                    @if($branch_id==$b->id)
                    <b>{{ trans('multiple.m_branch') }}</b> : {{ $b->branch_name }}
                    @endif
                    @endif
                    @endforeach
                </h4>

                <h4>
                    @if(!empty($start) && !empty($end))
                    <b>{{ trans('multiple.m_from') }} </b>
                    {{ date("d-M-Y", strtotime($start)) }} <b>{{ trans('multiple.m_to') }}</b>
                    {{ date("d-M-Y", strtotime($end)) }}
                    @endif
                </h4>
                <h4 class="sch_title" id="p-header">Transaction Summary </h4>
                <div id="divTab">
                    <table class="table table-striped table-hover table-bordered transaction" id="editable-sample">
                        <thead>
                            <tr>
                                <th style="text-align: center;">{{ trans('multiple.m_no') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.m_client_name') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_contract_id') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.branch') }}</th>
                                <th style="text-align: center;">{{ trans('account.currency') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_pro_type') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_co_name') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_transaction_date') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_transaction_type') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_amount') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_principal') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_interest') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_penalty') }}</th>
                                <th style="text-align: center;">{{ trans('loan.l_fee') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_principal_balance') }}</th>
                                <th style="text-align: center;">
                                    <a href="{{ $order_url['invoice_number'] }}" style="display: block;">
                                        {{ trans('report.rpt_invoice_number') }}<i class="fa {{ $order_class['invoice_number'] }} pull-right"></i>
                                    </a>
                                </th>
                                <th style="text-align: center;">{{ trans('multiple.m_note') }}</th>
                                <th style="text-align: center;" class="remove_class">{{ trans('multiple.m_action') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.audit') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($trans) && count($trans))
                            @foreach($trans as $tran)
                            <tr>
                                <td align="center">{{ str_pad($tran->id,6,'0',STR_PAD_LEFT)}}</td>
                                <td>{{ $tran->loan->client_name }}</td>
                                <td align="center">
                                    <a href="{{route('loan_detail',[$tran->loan->id])}}">{{ $tran->loan->contract_id }}</a>
                                </td>
                                <td>{{ $tran->loan->branch_name }}</td>
                                <td align="center">{{ $currency_list[$tran->loan->client_loan_account->currency] }}</td>
                                <td>{{ $tran->loan->category_name }}</td>
                                <td>{{ $tran->loan->co_user->name }}</td>
                                <td align="center">{{ date('Y-m-d',strtotime($tran->trans_date))}}</td>
                                <td>{{ $tran->trans_type }}</td>
                                <td align="right">{{ number_format($tran->amount, 2, '.', ',') }}</td>
                                <td align="right">{{ ($tran->principal > 0) ? number_format($tran->principal, 2, '.', ',') : '-' }}</td>
                                <td align="right">{{ ($tran->interest > 0) ? number_format($tran->interest, 2, '.', ',') : '-' }}</td>
                                <td align="right">{{ ($tran->penalty > 0) ? number_format($tran->penalty, 2, '.', ',') : '-' }}</td>
                                <td align="right">{{ ($tran->fee > 0) ? number_format($tran->fee, 2, '.', ',') : '-' }}</td>
                                <td align="right">{{ ($tran->balance > 0) ? number_format($tran->balance, 2, '.', ',') : '-' }}</td>
                                <td align="center">{{ $tran->invoice_number or '-' }}</td>
                                <td>{{ !empty($tran->description) ? $tran->description : '' }}</td>
                                <td align="center" class="remove_class">
                                    <a target="_blank" href="{{ route('journal_entry',[$tran->id]) }}" class="btn btn-xs btn-primary"
                                       title="View Journal Entry"><i class="fa fa-arrow-circle-right"></i></a>
                                    @if($tran->flag == 1)
                                    <span>&nbsp;<i class="fa fa-check" style="color:#00FF00;"></i></span>
                                    @endif
                                </td>

                                <td style="text-align:left">
                                    @if($tran->is_audit==0)
                                        <a href="{{route('get_audit')}}?tbl=transactions_requiry&id={{$tran->id}}&action=1&user_id={{$tran->user_id}}"><i class="glyphicon glyphicon-ok"></i></a>&nbsp;&nbsp;&nbsp;
                                        <a href="{{route('get_audit')}}?tbl=transactions_requiry&id={{$tran->id}}&action=0&user_id={{$tran->user_id}}"><i class="glyphicon glyphicon-remove"></i></a>
                                    @else
                                        <?php
                                            if($tran->audit[0]->audit_id){
                                                $tt = 'By '.display_name($tran->audit[0]->audit_id).' at '.$tran->audit[0]->updated_at;
                                            }else{
                                                $tt = 'By '.display_name($tran->user_id).' at '.$tran->updated_at;
                                            }
                                        ?>
                                        <a href="#" class="a-tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $tt?>">{{ trans('multiple.approved') }}</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="14">{{ trans('multiple.m_no_result') }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="page">
                <?PHP
                echo $trans->appends([
                    'start' => Input::get('start'),
                    'end' => Input::get('end'),
                    'client_name' => Input::get('client_name'),
                    'contract_id' => Input::get('contract_id'),
                    'category_name' => Input::get('category_name'),
                    'type' => Input::get('type'),
                    'breakdown_type' => Input::get('breakdown_type'),
                    'username' => Input::get('username'),
                    'by' => Input::get('by'),
                    'selBrand' => Input::get('selBrand'),
                    'offset' => Input::get('offset')
                ])->render();
                ?>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')

<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript"
src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript">
$(document).ready(function () {
    function hsSearch(b1, b2) {
        if (b1) {
            $("#tran_id").show();
            $("#contract_id").hide();
            $("#contract_id").find('input').val('');
            $("#more-search").hide();
            $("#more-search").find('input').val('');
        } else if (b2) {
            $("#contract_id").show();
            $("#tran_id").hide();
            $("#tran_id").find('input').val('');
            $("#more-search").hide();
            $("#more-search").find('input').val('');
        } else {
            $("#contract_id").hide();
            $("#contract_id").find('input').val('');
            $("#tran_id").hide();
            $("#tran_id").find('input').val('');
            $("#more-search").show();
        }
    }
    hsSearch($("#enable-search2").prop('checked'), $("#enable-search3").prop('checked'));
    $('#start-date').datepicker({
        autoclose: true
    });
    $('#end-date').datepicker({
        autoclose: true
    });
    $("#enable-search1").change(function () {
        hsSearch(false, false);
    });
    $("#enable-search2").change(function () {
        hsSearch(true, false);
    });
    $("#enable-search3").change(function () {
        hsSearch(false, true);
    });


    /*function exportTableToCSV($table, filename) {
        var $headers = $table.find('tr:has(th)')
                , $rows = $table.find('tr:has(td)')

                // Temporary delimiter characters unlikely to be typed by keyboard
                // This is to avoid accidentally splitting the actual contents
                , tmpColDelim = String.fromCharCode(11) // vertical tab character
                , tmpRowDelim = String.fromCharCode(0) // null character

                // actual delimiter characters for CSV format
                , colDelim = '","'
                , rowDelim = '"\r\n"';

        // Grab text from table into CSV formatted string
        var csv = '"';
        csv += ($('#p-header').html()).trim();
        csv += rowDelim;
        csv += formatRows($headers.map(grabRow));
        csv += rowDelim;
        csv += formatRows($rows.map(grabRow)) + '"';
        // Data URI
        var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

        $(this)
                .attr({
                    'download': filename
                    , 'href': csvData
                            //,'target' : '_blank' //if you want it to open in a new window
                });

        //------------------------------------------------------------
        // Helper Functions
        //------------------------------------------------------------
        // Format the output so it has the appropriate delimiters
        function formatRows(rows) {
            return rows.get().join(tmpRowDelim)
                    .split(tmpRowDelim).join(rowDelim)
                    .split(tmpColDelim).join(colDelim);
        }
        // Grab and format a row from the table
        function grabRow(i, row) {

            var $row = $(row);
            //for some reason $cols = $row.find('td') || $row.find('th') won't work...
            var $cols = $row.find('td');
            if (!$cols.length)
                $cols = $row.find('th');

            return $cols.map(grabCol)
                    .get().join(tmpColDelim);
        }
        // Grab and format a column from the table
        function grabCol(j, col) {
            var $col = $(col),
                    $text = $col.text().trim();

            return $text.replace('"', '""'); // escape double quotes

        }
    }*/


// This must be a hyperlink
    $("#export").click(function (event) {
        // var outputFile = 'export'
        var con = confirm("Do you really want to export to CSV file?");
        if (con == true) {
            //var outputFile = 'Transaction Summary.csv';
            // CSV
            //exportTableToCSV.apply(this, [$('#divTab>table'), outputFile]);

            // IF CSV, don't do event.preventDefault() or return false
            // We actually need this to be a typical hyperlink

            new TableExport($('#divTab>table'), {
                    formats: ['csv'],
                    filename: 'transaction_summary'
                });
                $('button.csv').hide().click();
                $('.tableexport-caption').remove();
        }

    });

    $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport($('#divTab>table'), {
                    formats: ['xlsx'],
                    filename: 'transaction_summary'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });

//pagination
    $('.custom-pagi a').on('click', function () {
        val = $(this).parent().find('input[name="set_offset"]').val();
        $('input[name="offset"]').val(val);
        $('#search_frm').submit();
        return false;
    });

});

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
})
</script>
@endsection
