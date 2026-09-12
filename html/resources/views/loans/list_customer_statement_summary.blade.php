@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/advanced-datatable/css/jquery.dataTables.css',isset($secure) ? false : false) }}"/>
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />

<style>
    @media print {
      @page {size: landscape}
      a[href]:after {
        content: none !important;
      }
      .dataTables_length{
        display:none;
      }
      .dataTables_filter{
        display: none;
      }
    }
</style>
@endsection
<?php $loan_status = config('static_data.loan_status'); ?>
@section('content')
<div class="row">
    <div class="col-sm-12">

        <section class="panel ox-scroll">
            <header class="panel-heading">
                <span>{{ trans('sidebar.sb_customer_statement_summary') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    @if($errors->addCate->has('ipCate'))
                    <div class="alert alert-danger fade in">
                        <button class="close close-sm" type="button" data-dismiss="alert">x</button>
                        {{$errors->addCate->first('ipCate')}}
                    </div>
                    @endif

                    <form role="form" id="search_frm" method="get" action="{{ route('customer_statement_summary') }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="customer" name="customerRef" value='{{($search)?$search:""}}' placeholder="Customer Name or Loan Ref" />
                                    </div>
                                    <td>
                                        {{ trans('report.rpt_date_till') }}<br/>
                                        <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date till_date">
                                            <input type="text" name="till_date" size="16" class="form-control" value="{{($till_date)?$till_date : ''}}" placeholder="{{date('Y-m-d')}}">
                                            <span class="add-on birhtdateDatepicker ptl-3">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        {{ trans('report.rpt_exchange_rate') }}<br/>
                                        <input type="text" name="exchange_rate" size="16" class="form-control" value="{{ $exchange_rate?$exchange_rate:4000 }}" />
                                    </td>
                                    <div class="form-group">
                                        <input type="hidden" name="offset" />
                                        <button type="submit" class="btn btn-info" id="search"><i class="fa fa-search"></i> Search</button>
                                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> Print</button>
                                        <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                                        <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                        <div class="page">
                            <div class="custom-pagi">
                                <span class="pagi_label">{{ trans('sidebar.sb_number_of_rows') }}</span>
                                <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                                <a href="#" class="btn btn-danger">Go</a>
                            </div>
                        </div>
                </div>
                <br/><br/>
                
                <div id="printArea">
                    @include('api.report_header')
                    <h4 class="sch_title">
                        {{ trans('sidebar.sb_customer_statement_summary') }}
                        @foreach($branch as $b)
                          @if(isset($branch_id))
                            @if($branch_id==$b->id)
                            For Branch {{ $b->branch_name }}
                            @endif
                          @endif
                        @endforeach
                    </h4>
                    <br /><br />
                    <h4 class="sch_title" id="page_header">Customer Statement Summary </h4>
                    <div id="tabs">
                        <table class="ddd table table-striped table-bordered" style="width:100%" id="Customer_Statement_Summary">
                            <thead class="cf">
                                <tr>
                                    <th rowspan="2" style="vertical-align:middle;">No</th>
                                    <th rowspan="2" style="text-align: center;">Name</th>
                                    <th rowspan="2" style="text-align: center;">Branch</th>
                                    <th rowspan="2" style="text-align: center;">Currency</th>
                                    <th rowspan="2" style="text-align: center;">Loan Ref</th>
                                    <th rowspan="2" style="text-align: center;">Loan Acc. No</th>
                                    <th rowspan="2" style="text-align: center;">Status</th>
                                    <th rowspan="2" style="text-align: center;">Prod. Name</th>
                                    <th rowspan="2" style="text-align: center;">Prod. Type</th>
                                    <th rowspan="2" style="text-align: center;">CO's Name</th>
                                    <th rowspan="2" style="text-align: center;">Rate (P.M)</th>
                                    <th rowspan="2" style="text-align: center;">Start Date</th>
                                    <th rowspan="2" style="text-align: center;">End Date</th>
                                    <th rowspan="2" style="text-align: center;">Loan Amount</th>
                                    <th rowspan="2" style="text-align: center;">Overdue</th>
                                    <th rowspan="2" style="text-align: center;">Last Paid Date</th>
                                    <th colspan="4" style="text-align: center;">Schedule</th>
                                    <th colspan="4" style="text-align: center;">Actual</th>
                                    <th colspan="5" style="text-align: center;">Arrear</th>
                                    <th rowspan="2" style="text-align: center;">OS Balance</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;">Principal</th>
                                    <th style="text-align: center;">Interest</th>
                                    <th style="text-align: center;">Fee</th>
                                    <th style="text-align: center;">Air</th>
                                    <th style="text-align: center;">Principal</th>
                                    <th style="text-align: center;">Interest</th>
                                    <th style="text-align: center;">Fee</th>
                                    <th style="text-align: center;">Penalty</th>
                                    <th style="text-align: center;">Principal</th>
                                    <th style="text-align: center;">Interest</th>
                                    <th style="text-align: center;">Fee</th>
                                    <th style="text-align: center;">Penalty</th>
                                    <th style="text-align: center;">Air</th>
                                </tr>

                            </thead>
                            <tbody>
                              <?php $n = 1;?>
                                @forelse($customer_statement_summary as $css)
                                <tr>
                                    <?php
                                    $conv = 1;
                                    $till_date = date('Y-m-d', strtotime($till_date . ' +1 day'));
                                    if($css->client_loan_account->currency == 1) $conv = floatval($exchange_rate);
                                    $loan = App\Models\Loan::where('id', $css->id)
                                            ->with(['schedule' => function($p) use($till_date){
                                              $p->where('schedule_date', '<', $till_date)
                                              ->orderBy('schedule_date', 'ASC')->get();
                                            }])
                                            ->with(['payment' => function($p) use($till_date){
                                              $p->where('repayment_date', '<', $till_date)
                                              ->orderBy('repayment_date', 'ASC')->get();
                                            }])
                                            ->first();
                                    $penalty_arr = LoanCalculate::getTotalPenalty($loan, $till_date);
                                    $overdue = $penalty_arr[2];
                                    $last_pay_date = $penalty_arr[3];
                                    $penalty_amount = $penalty_arr[4] / $conv;

                                    $s_prin = $s_int = $s_fee = $s_other_fee = $s_penal = $s_air = 0;
                                    $t_prin = $t_int = $t_fee = $t_other_fee = $t_penal= 0;
                                    $a_prin = $a_int = $a_fee = $a_other_fee = $a_penal= 0;
                                    
                                    if($css->status == 3 || $css->status == 8){
                                        
                                        $s_prin += $css->schedule->sum('principal') / $conv;
                                        $s_fee += $css->schedule->sum('fee') / $conv;
                                        $s_int += $css->schedule->sum('interest') / $conv;
                                        //if($css->id == 44) dd($s_fee);
                                        // next schedule
                                        $next_schedule = App\Models\RepaymentSchedule::where('loan_id', $css->id)
                                                            ->where('no', $css->schedule[0]['no'] + 1)->first();
                                        $next_int = ($next_schedule->date_num - date_dif($till_date, $next_schedule->schedule_date, 1, false)) * $next_schedule->interest / $next_schedule->date_num;
                                        $s_int += $next_int;
                                        $val = 'Auto Accrued Interest-'. $css->contract_id;
                                        $s_int_arr = get_journal_bal_sp('journal_detail.description', '=', $val, $till_date);
                                        $s_air += ($s_int_arr['t_debit'] / $conv) - $s_int;
                                    }elseif($css->status == 5){
                                        $s_prin = $loan->writeoff->amount / $conv;
                                    }
                                    $principal[] = $s_prin;
                                    $interest[] = $s_int;
                                    $air[] = $s_air;
                                    $fee[] = $s_fee;
                                    if($css->status == 5){
                                        $t_principal[] = $t_prin = get_wo_paid($loan->contract_id, $till_date)/ $conv;
                                    }else{
                                        $t_principal[] = $t_prin = $css->transaction->sum('principal') / $conv;
                                        $t_interest[] = $t_int = $css->transaction->sum('interest') / $conv;
                                        $t_fee[] = $t_fee = $css->transaction->sum('fee') / $conv;
                                        $t_penalty[] = $t_penal = $css->transaction->sum('penalty') / $conv;
                                      //  if($css->id == 17) dd($css->transaction);
                                    }
                                    $a_principal[] =$a_prin =  ($s_prin - $t_prin) / $conv;
                                    $a_interest[] = $a_int =  ($s_int - $t_int) / $conv;
                                    $a_fee[] = $a_fee = ($s_fee - $t_fee) / $conv;
                                    $a_penalty[] = $a_penal = $penalty_amount / $conv;
                                    $t_os_bal[] = $css->client_loan_account->balance / $conv;
                                    ?>
                                    <td>{{$n}}</td>
                                    <td><a href="{{ route('detail_customer_statement',['loan_id'=>$css->id]) }}">{{$css->client_loan_account->account_name}}</a></td>
                                    <td style="text-align: center">{{$loan->branch->short_name}}</td>
                                    <td style="text-align: center">{{$css->client_loan_account->currencies->code}}</td>
                                    <td style="text-align: center"><a href="{{ route('loan_detail', [$css->id])}}">{{$css->contract_id}}</td>
                                    <td style="text-align: center">{{$css->client_loan_account->account_no}}</td>
                                    <td style="text-align: center">{{$loan_status[$css->status]}}</td>
                                    <td style="text-align: center">{{$css->product->product_name}}</td>
                                    <td style="text-align: center">{{$css->product_type->code}}</td>
                                    <td style="text-align: center">{{$css->co_user->name}}</td>
                                    <td style="text-align: center">{{number_format($css->interest_rate, 2, '.', ',')}}</td>
                                    <td style="text-align: center">{{$css->disburse_date}}</td>
                                    <td style="text-align: center">{{$css->schedule[0]->schedule_date}}</td>
                                    <td style="text-align: center">{{number_format($css->loan_amount/$conv, 2, '.', ',')}}</td>
                                    <td style="text-align: center">{{($css->status == 5)? "-":$overdue}}</td>
                                    <td style="text-align: center">{{$last_pay_date}}</td>

                                    <td style="text-align: center">{{number_format($s_prin, 2, '.', ',')}}</td>
                                    <td style="text-align: center">{{number_format($s_int, 2, '.', ',')}}</td>
                                    <td style="text-align: center">{{number_format($s_fee, 2, '.', ',')}}</td>
                                    <td style="text-align: center">{{number_format($s_air, 2, '.', ',')}}</td>
                                    <td style="text-align: center">{{number_format($t_prin, 2, '.', ',')}}</td>
                                    <td style="text-align: center">{{number_format($t_int, 2, '.', ',')}}</td>
                                    <td style="text-align: center">{{number_format($t_fee, 2, '.', ',')}}</td>
                                    <td style="text-align: center">{{number_format($t_penal, 2, '.', ',')}}</td>
                                    <td style="text-align: center">{{number_format($a_prin,2)}}</td>
                                    <td style="text-align: center">{{number_format($a_int,2)}}</td>
                                    <td style="text-align: center">{{number_format($a_fee,2)}}</td>
                                    <td style="text-align: center">{{number_format($a_penal,2)}}</td>
                                    <td style="text-align: center">{{number_format($s_air,2)}}</td>
                                    
                                    <td style="font-weight: bold;text-align: center">{{number_format($css->client_loan_account->balance / $conv, 2)}}</td>
                                </tr>
                                <?php $n++;?>
                                @empty
                                <tr><td colspan=12>{{ trans('multiple.m_no_result')}}</td></tr>
                                @endforelse
                                <tr>
                                    <td colspan="11" style="font-weight: bold; text-align: center;">Total</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($principal), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($interest), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($air), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($fee), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($t_principal), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($t_interest), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($t_fee), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($t_penalty), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($a_principal), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($a_interest), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($a_fee), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($a_penalty), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($air), 2, '.', ',')}}</td>
                                    <td style="font-weight: bold; text-align: center;">{{number_format(array_sum($t_os_bal), 2, '.', ',')}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="page">
                    <?PHP
                    echo $customer_statement_summary->appends([
                        'customerRef' => Input::get('customerRef'),
                        'offset' => Input::get('offset')
                    ])->render();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/jquery-1.11.1.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/additional-methods.min',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>

<!-- <script src="{{ asset('js/dataTables.js',isset($secure) ? false : false) }}"></script> -->
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript">

/*
function exportTableToCSV($table, filename) {
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
    csv += ($('#page_header').html()).trim();
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
}
*/

// This must be a hyperlink
$("#export").click(function (event) {
    var cons = confirm("Do you want to exprort to CVS file?");
    if (cons == true) {
        new TableExport(document.getElementById('Customer_Statement_Summary'), {
                formats: ['csv'],
                filename:'Customer_Statement_Summary'
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
    } else {
        return false;
    }
});

$("#xexport").click(function (event) {
    var con = confirm("Do you really want to export to Excel file?");
    if(con == true){
        new TableExport(document.getElementById('Customer_Statement_Summary'), {
                formats: ['xlsx'],
                filename: 'Customer_Statement_Summary'
            }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            $('button.xlsx').hide().click();
            $('.tableexport-caption').remove();
    }
});

$(document).ready(function () {
    //pagination
    $('.custom-pagi a').on('click', function () {
        val = $(this).parent().find('input[name="set_offset"]').val();
        $('input[name="offset"]').val(val);
        $('#search_frm').submit();
        return false;
    });
    $('.till_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        setDate: new Date()
    });

    //dosorting();
});
    //dosorting();

function dosorting(){
    //sorting *****************

    $('#listtable').DataTable( {
        "paging":   true,
        "ordering": true,
        "info":     true,
        "iDisplayLength": 10000000,
        "pageLength": 1000
    } );
}
</script>
@endsection
