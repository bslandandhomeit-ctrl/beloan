@extends('layouts.app')

@section('css')
<link href="{{ asset('css/client.css',false) }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',false)}}" />
<link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/TableExport/5.0.5/css/tableexport.css" />

@endsection
<?php
    $currency = config('static_data.currency');
    $quote = config('static_data.quoteType');
?>
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('sidebar.sb_gl_report') }}</span>
        @foreach($branch as $b)
        @if(isset($branch_code))
        @if($branch_code==$b->branch_code)
        {{ trans('report.rpt_for_branch') }} {{ $b->branch_name }}
        @endif
        @endif
        @endforeach

        @foreach($currency as $key => $value)
        @if(isset($currency_id))
        @if($currency_id == $key)
        {{ trans('report.rpt_currency') }} ({{ $value }})
        @endif
        @endif
        @endforeach

        @if(isset($start) && isset($end))
        {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
        @else
        {{ isset($start)?'Report on'.date("d-M-Y", strtotime($start)):'' }}
        {{ isset($end)?'Report on '.date("d-M-Y", strtotime($end)):'' }}
        @endif
    </header>
    <div class="panel-body">
        <section id="unseen">
            <form role="form" method="get" action="{{ route('getGlReport') }}" id="getGlReportFrm">
                <table class="tb-search-box">
                    <tr>
                        @if(count($branch) > 1)
                        <td>
                            {{ trans('report.rpt_branch_name') }}<br/>
                            <select class="form-control" id="br" name="br">
                                <option value="">-</option>
                                @foreach($branch as $b)
                                <option value="{{ $b->branch_code }}"
                                        @if(isset($branch_code))
                                        @if($branch_code==$b->branch_code)
                                        selected
                                        @endif
                                        @endif>{{ $b->branch_name}}</option>
                                @endforeach
                            </select>
                        </td>
                        @endif

                        <td>
                            {{ trans('report.rpt_from') }}<br/>
                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpStart">
                                <input type="text" name="dpStart" size="16" class="form-control" value="{{ isset($start)?$start:old('dpStart') }}">
                                <span class="add-on birhtdateDatepicker ptl-3">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </td>

                        <td>
                            {{ trans('report.rpt_to') }}<br/>
                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpEnd">
                                <input type="text" name="dpEnd" size="16" class="form-control" value="{{ isset($end)?$end:old('dpEnd') }}">
                                <span class="add-on birhtdateDatepicker ptl-3">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </td>


                        @if(count($branch) > 0)
                        <td>
                            {{ trans('report.rpt_branch_name') }}<br/>
                            <select class="form-control" id="br" name="br">
                                <option value="">All</option>
                                @foreach($branch as $b)
                                <option value="{{ $b->branch_code }}"
                                        @if(isset($branch_code))
                                        @if($branch_code==$b->branch_code)
                                        selected
                                        @endif
                                        @endif>{{ $b->branch_name}}</option>
                                @endforeach
                            </select>
                        </td>
                        @endif
                        <td>
                            {{ trans('report.rpt_currency') }}<br/>
                            <select class="form-control" id="cur" name="cur">
                                <option value="">All</option>
                                @foreach($currency as $key => $value)
                                <option value="{{ $key }}"
                                        @if(isset($currency_id) && $currency_id == $key)
                                        selected
                                        @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <input type="checkbox" name="consolidate" value="1" {{ isset($consolidate)?$consolidate==1?'checked':'':'' }} />
                                   {{ trans('report.con') }} {{$consolidate}}<br/>

                            <input type="radio" name="exchange_rate" value="1" {{ isset($exchange_rate)?$exchange_rate==1?'checked':'':'checked' }} />
                                   {{ trans('report.rpt_khmer_riel') }}
                                   <input type="radio" name="exchange_rate" value="2" {{ isset($exchange_rate)?$exchange_rate==2?'checked':'':'checked' }} />
                                   {{ trans('report.rpt_us_dollar') }}<br/>

                            <input type="text" name="rate" size="16" class="form-control" value="{{ isset($rate)?$rate:old('rate') }}" placeholder="{{ trans('report.rpt_rate_usd_khr') }}" style="width:150px;" />

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>{{ trans('report.rpt_entry_id') }}</label>
                            <div>
                            </div>
                        </td>
                        <td>
                            <label>{{ trans('account.ref_id') }}</label>
                            <div>
                                <input type="text" name="ref_id" class="form-control" value="{{ isset($reference)?$reference:'' }}" />
                            </div>
                        </td>
                        <td>
                            <label>{{ trans('report.rpt_invoice_number') }}</label>
                            <div>
                                <input type="text" name="inv_no" class="form-control" value="{{ isset($inv_no)?$inv_no:'' }}" />
                            </div>
                        </td>
                        <td>
                            <label>{{ trans('account.desc') }}</label>
                            <select class="form-control" id="quote_id" name="quote_id">
                                <option value="">Operator</option>
                                @foreach($quote as $key => $value)
                                <option value="{{ $key }}"
                                        @if(isset($quote_id) && $quote_id == $key)
                                        selected
                                        @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                            <div>
                                <input type="text" name="note" class="form-control" value="{{ isset($note)?$note:'' }}" />
                            </div>
                        </td>

                    </tr>
                </table>

                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                        <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                        <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>                    </div>
                </div>
                <?php if (isset($_GET['nbc'])) { ?><input type="hidden" name='nbc' value="1" /><?php } ?>
            </form>
            <br><br>
            <div id="printArea">
                @include('api.report_header')
                <h4 class="sch_title" id="p-header">
                    {{ trans('sidebar.sb_trial_balance') }}
                    @foreach($branch as $b)
                    @if($branch_code==$b->branch_code)
                    <strong>{{ trans('report.rpt_for_branch') }}: {{ $b->branch_name }}</strong><br/>
                    @endif
                    @endforeach
                    @foreach($currency as $key => $value)
                    @if(isset($currency_id))
                    @if($currency_id == $key)
                    {{ trans('report.rpt_currency') }}: ({{ $value }})<br/>
                    @endif
                    @endif
                    @endforeach

                    @if(isset($consolidate))
                    / {{ trans('report.rpt_consolidate_to') }}
                    @if($exchange_rate==1)
                    {{ trans('report.rpt_million_riel') }}
                    @else
                    {{ trans('report.rpt_usd_dollar') }}
                    @endif

                    (Rate = @if(isset($rate)) {{$rate}} @else USDTOKHR @endif )
                    <br/>
                    @endif

                    @if($start)
                    {{ trans('report.from') }}: {{$start}}<br/>
                    @endif

                    @if($end)
                    {{ trans('report.to') }}: {{$end}}
                    @endif
                </h4>


                <table class="table table-bordered table-striped table-condensed gl_report" id="gl_report">
                    <thead class="cf" style="background: #ffffff;">
                        <tr>
                            <th rowspan="2" style="text-align: center">{{ trans('account.gl_code') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.parent_code') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.currency') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.nbc_code') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.gl_name') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('report.rpt_entry_id') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.ref_id') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('report.rpt_invoice_number') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.date') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.desc') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.a_debit') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.a_credit') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coa_arr as $coa)
                            <?php $to_credit = $to_debit = 0;?>
                            <tr class="warning">
                                <td colspan="12" style="text-align: left;; font-weight: bold">{{ $coa[0]->account_code }} ( {{$coa[0]->coa_name}} )</td>
                            </tr>
                            @foreach($coa as $c)
                            <tr>
                                <td style="text-align: center">{{ $c->account_code }}</td>
                                <td style="text-align: center">{{ $p_coa_arr[$c->parent_id]->account_code }}</td>
                                <td style="text-align: center">{{ $currency_arr[$c->currency] }}</td>
                                <td style="text-align: center">{{ $c->nbc_code }}</td>
                                <td>{{ $c->coa_name }}</td>
                                <td style="text-align: right">{{ $c->journal_id }}</td>
                                <td style="text-align: right">{{ $c->reference }}</td>
                                <td style="text-align: right">{{ $c->invoice_number }}</td>
                                <td style="text-align: right">{{ $c->entry_date }}</td>
                                <td style="text-align: right">{{ $c->desc }}</td>
                                <td style="text-align: right">{{ number_format($c->debit,4) }}</td>
                                <td style="text-align: right">{{ number_format($c->credit,4) }}</td>
                                <?php
                                    $to_credit += $c->credit;
                                    $to_debit += $c->debit;
                                ?>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="10" style="text-align: right; font-weight: bold">Total</td>
                                <td style="text-align: right; font-weight: bold">{{ number_format($to_debit, 4) }}</td>
                                <td style="text-align: right; font-weight: bold">{{ number_format($to_credit, 4) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </section>
    </div>
</section>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',false)}}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',false)}}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.floatThead.min.js',false)}}"></script>

<script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>

<script>
$(document).ready(function () {
    $(".sticky-header").floatThead({scrollingTop: 77});

    $('.dpStart').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        setDate: new Date()
    });
    $('.dpEnd').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        setDate: new Date()
    });

    $('tr.acc6').each(function(indexx){
        var this_ = $(this);
        sdata = $('#getGlReportFrm').serialize();
        setTimeout(function(){
            tr_id = this_.attr('id');
            tr_id = tr_id.split('_');
            tr_id = tr_id[1];
            $.ajax({
                url: '{{ route("getGlReportAjax") }}',
                type:'GET',
                data: sdata+'&coa_id='+tr_id,
                success:function(res){
                    $('tr.acc6_'+tr_id+' td').html(res);
                }
            });
        }, (indexx + 1) * 1000);
    });

    $("tr.acc6").on('click', function(){
       tr_id = $(this).attr('id');
       tr_id = tr_id.split('_');
       tr_id = tr_id[1];
       $('tr.acc6_'+tr_id).toggle();
    });

    // This must be a hyperlink
    $("#export").click(function (event) {
        // var outputFile = 'export'
        var con = confirm("Do you really want to export to CSV file?");
        if(con == true){
            new TableExport(document.getElementById('gl_report'), {
                formats: ['csv'],
                filename:'gl_report'
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }

    });

    $("#xexport").click(function (event) {
            var con = confirm("Do you really want to export to Excel file?");
            if(con == true){
                new TableExport(document.getElementById('gl_report'), {
                        formats: ['xlsx'],
                        filename: 'gl_report'
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });

});

/*
function exportTableToCSV($table, filename) {
    var $headers = $table.find('tr:has(th)')
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
    csv += formatRows($headers.map(grabRow));
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
            $text = $col.text();

        return $text.replace('"', '""'); // escape double quotes

    }
}*/
</script>
@endsection
