@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false)}}"/>
@endsection

@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('sidebar.sb_transaction_summary') }}
        </header>
        <div class="panel-body">
            <div class="form-inline" style="text-align: center; float: right;position:absolute;right:2%;">
                <a class="btn btn-danger" href="#" onclick="closeMe();return false;"> {{trans('multiple.m_close')}}</a>
                <a href="#" id="export" class="btn btn-primary"> <i
                            class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }} </a>
            </div>
            <br/>
            <table class="table table-bordered" style="width:35%">
                <thead> <tr>
                    <th style="text-align: center;">{{ trans('loan.l_total_paid_amount') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_total_principal') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_total_interest') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_total_penalty') }}</th>
                    <th style="text-align: center; width: 119px !important;">{{ trans('loan.l_total_fee') }}</th>
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
                        <?php $to_amount += $to_prin + $to_interest + $to_penalty + $to_fee;?>
                    @endif
                    <td align="center">{{number_format($to_amount,2,'.',',')}}</td>
                    <td align="center">{{number_format($to_prin,2,'.',',')}}</td>
                    <td align="center">{{number_format($to_interest,2,'.',',')}}</td>
                    <td align="center">{{number_format($to_penalty,2,'.',',')}}</td>
                    <td align="center">{{number_format($to_fee,2,'.',',')}}</td>
                </tr>
                </tbody>
            </table>
            <section id="unseen" style="clear: both">
                <div id="divTab">
                    <table class="table income_statement table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th style="text-align: center;">{{ trans('multiple.m_no') }}</th>
                            <th style="text-align: center;">{{ trans('report.rpt_contract_id') }}</th>
                            <th style="text-align: center;">{{ trans('report.rpt_created_by') }}</th>
                            <th style="text-align: center;">{{ trans('report.rpt_office') }}</th>
                            <th style="text-align: center;">{{ trans('report.rpt_transaction_date') }}</th>
                            <th style="text-align: center;">{{ trans('report.rpt_transaction_type') }}</th>
                            <th style="text-align: center;">{{ trans('report.rpt_amount') }}</th>
                            <th style="text-align: center;">{{ trans('report.rpt_principal') }}</th>
                            <th style="text-align: center;">{{ trans('report.rpt_interest') }}</th>
                            <th style="text-align: center;">{{ trans('report.rpt_penalty') }}</th>
                            <th style="text-align: center;">{{ trans('loan.l_fee') }}</th>
                            <th style="text-align: center;">{{ trans('report.rpt_principal_balance') }}</th>
                            <th style="text-align: center;">{{ trans('report.rpt_invoice_number') }}</th>
                            <th style="text-align: center;">{{ trans('multiple.m_note') }}</th>
                            <th style="text-align: center;">{{ trans('multiple.m_action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($trans) && count($trans))
                            @foreach($trans as $tran)
                                <tr>
                                    <td align="center">{{ str_pad($tran->id,6,'0',STR_PAD_LEFT)}}</td>
                                    <td align="center"><a target="_blank" style="text-decoration:underline !important;" href="{{route('loan_detail',[$tran->loan->id])}}">{{ $tran->loan->contract_id }}</a></td>
                                    <td>{{ $tran->name }}</td>
                                    <td>{{ $tran->loan->branch_name }}</td>
                                    <td align="center">{{ date('Y-M-d',strtotime($tran->trans_date))}}</td>
                                    <td>{{ $tran->trans_type }}</td>
                                    <td align="right">{{ number_format($tran->amount, 2, '.', ',') }}</td>
                                    <td align="right">{{ ($tran->principal > 0) ? number_format($tran->principal, 2, '.', ',') : '-' }}</td>
                                    <td align="right">{{ ($tran->interest > 0) ? number_format($tran->interest, 2, '.', ',') : '-' }}</td>
                                    <td align="right">{{ ($tran->penalty > 0) ? number_format($tran->penalty, 2, '.', ',') : '-' }}</td>
                                    <td align="right">{{ ($tran->fee > 0) ? number_format($tran->fee, 2, '.', ',') : '-' }}</td>
                                    <td align="right">{{ ($tran->balance > 0) ? number_format($tran->balance, 2, '.', ',') : '-' }}</td>
                                    <td align="center">{{ $tran->invoice_number or '-' }}</td>
                                    <td>{{ !empty($tran->description) ? $tran->description : '' }}</td>
                                    <td align="center">
                                        <a target="_blank" href="{{ route('journal_entry',[$tran->id]) }}"
                                           class="btn btn-xs btn-primary" title="View Journal Entry"><i
                                                    class="fa fa-arrow-circle-right"></i></a>
                                        @if($tran->flag == 1)
                                            <span>&nbsp;<i class="fa fa-check" style="color:#00FF00;"></i></span>
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
            </section>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript"
            src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript">

        $(document).ready(function () {

            function exportTableToCSV($table, filename) {
                var $headers = $table.find('tr:has(th)')
                        , $rows = $table.find('tr:has(td)')
                        , tmpColDelim = String.fromCharCode(11)
                        , tmpRowDelim = String.fromCharCode(0)
                        , colDelim = '","'
                        , rowDelim = '"\r\n"';
                var csv = '"';
                csv += rowDelim;
                csv += formatRows($headers.map(grabRow));
                csv += rowDelim;
                csv += formatRows($rows.map(grabRow)) + '"';
                var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
                $(this).attr({
                    'download': filename ,
                    'href': csvData
                });
                function formatRows(rows) {
                    return rows.get().join(tmpRowDelim).split(tmpRowDelim).join(rowDelim).split(tmpColDelim).join(colDelim);
                }
                function grabRow(i, row) {

                    var $row = $(row);
                    var $cols = $row.find('td');
                    if (!$cols.length) $cols = $row.find('th');
                    return $cols.map(grabCol).get().join(tmpColDelim);
                }

                function grabCol(j, col) {
                    var $col = $(col), $text = $col.text();
                    return $text.replace('"', '""');
                }
            }
            $("#export").click(function (event) {
                var con = confirm("Do you really want to export to CSV file?");
                if (con == true) {
                    var outputFile = 'income_statement.csv';
                    exportTableToCSV.apply(this, [$('#divTab>table'), outputFile]);
                }
            });
        });
    </script>
@endsection
