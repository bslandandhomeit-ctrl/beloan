@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/advanced-datatable/css/jquery.dataTables.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
<style type="text/css">
    .loading{
        position: absolute;
        left: 100%;
        top: 20px;
        display: block;
        width: 40px;
        height: 40px;
        background: transparent url("{{ asset('images/loading.gif',isset($secure) ? false : false) }}") no-repeat scroll center center / contain;
    }
</style>
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

@section('content')
<?php
    //$loan_type = config('static_data.loan_type');
?>
<section class="panel">
    <header class="panel-heading header-title">
        PAR REPORT
    </header>
    <div class="panel-body">
    	<form role="form" method="get" action="{{ route('parc_report') }}" id="search_frm">
                <table class="tb-search-box">
                	<tr>
                		@if(count($branch) > 1)
                		<td>
                			{{ trans('report.rpt_branch_name') }}<br/>
                			<select class="form-control" id="br" name="br">
                            <option value="">-</option>
                            @foreach($branch as $b)
                            <option value="{{ $b->id }}"
                                    @if(isset($company_branch_id))
                                    @if($company_branch_id==$b->id)
                                    selected
                                    @endif
                                    @endif>{{ $b->branch_name}}</option>
                            @endforeach
                        	</select>
                		</td>
                		@endif

                        <td>Loan Type<br/>
                            <select class="form-control" id="loan_type" name="loan_type">
                            <option value="">-</option>
                            @foreach($loan_types as $val)
                            <option value="{{ $val->id }}"  @if($loan_type_id==$val->id) selected @endif>{{$val->products_type_name}} ( {{$val->code}} )</option>
                            @endforeach
                            </select>
                        </td>

                		<td>
                			{{ trans('report.rpt_date_till') }}<br/>
                			<div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpStart">
                                <input type="text" name="dpStart" size="16" class="form-control" value="{{ $dpStart }}">
                                <span class="add-on birhtdateDatepicker ptl-3">
                                	<button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                		</td>

                		<td>
                			{{ trans('report.borrow_name') }}<br/>
                			<input type="text" name="borrow_name" size="16" class="form-control" value="{{ $borrow_name }}" />
                		</td>

                		<td>
                			{{ trans('report.rpt_loan_ref') }}<br/>
                			<input type="text" name="loan_ref" size="16" class="form-control" value="{{ $loan_ref }}" />
                		</td>

                		@if(count($co) > 1)
                		<td>
                			{{ trans('report.rpt_co_name') }}<br/>
                			<select class="form-control" id="co_id" name="co_id">
                            <option value="">-</option>
                            @foreach($co as $c)
                            <option value="{{ $c->id }}"
                                    @if(isset($co_id))
                                    @if($co_id==$c->id)
                                    selected
                                    @endif
                                    @endif>{{ $c->name}}</option>
                            @endforeach
                        	</select>
                		</td>
                		@endif

                		<td>
                			{{ trans('report.rpt_product_category') }}<br/>
                			<select class="form-control" id="category_id" name="category_id">
                            <option value="">-</option>
                            @foreach($product_categories as $p)
                            <option value="{{ $p->id }}"
                                    @if(isset($category_id))
                                    @if($category_id==$p->id)
                                    selected
                                    @endif
                                    @endif>{{ $p->category_name}}</option>
                            @endforeach
                        	</select>
                		</td>
                        <td>
                            {{ trans('report.rpt_exchange_rate') }}<br/>
                            <input type="text" name="exchange_rate" size="16" class="form-control" value="{{ $exchange_rate?$exchange_rate:4000 }}" />
                        </td>
                		<td>
                			{{ trans('report.overdue') }}<br/>
                			<input type="text" name="overdue" size="16" class="form-control" value="{{ $overdue || $overdue==0?$overdue:30 }}" />
                		</td>

                	</tr>
                </table>

                <div class="row">
                	<?php
                		$current_url = Request::fullUrl();
                		$boo = strpos($current_url, '?') ;
                		$exs = $boo?'&':'?';
                	?>
                    <div class="col-sm-offset-6 col-sm-6">
                    	<input type="hidden" name="offset" />
                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                        <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                        <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
                    </div>
                </div>
            </form>
    	<br/><br/><br/>

    	<div id="printArea">
                @include('api.report_header')
                <h4 class="sch_title" id="p-header">
                    {{ trans('sidebar.sb_parc_report') }}
                    @foreach($branch as $b)
                    @if($company_branch_id==$b->id)
                    <strong>{{ trans('report.rpt_for_branch') }}: {{ $b->branch_name }}</strong><br/>
                    @endif
                    @endforeach

                    @if($dpStart)
                    	{{ trans('report.rpt_date_till') }}: {{$dpStart}}<br/>
                    @endif

                    @if($borrow_name)
                    	{{ trans('report.borrow_name') }}: {{$borrow_name}}<br/>
                    @endif

                     @if($loan_ref)
                    	{{ trans('report.rpt_loan_ref') }}: {{$loan_ref}}<br/>
                    @endif

                     @if($co_id)
                    	{{ trans('report.rpt_co_name') }}: {{$co_id}}<br/>
                    @endif

                     @if($category_id)
                    	{{ trans('report.rpt_product_category') }}: {{$category_id}}<br/>
                    @endif

                     @if($overdue)
                    	{{ trans('report.overdue') }}: {{$overdue}}<br/>
                    @endif
                </h4>
            <div id="divTab" class="panel-body ox-scroll">
	        <table class="table table-striped table-hover table-bordered parc-table" id="parc-table">
                <thead style="background-color: #ffffff">
                    <tr class="data-center header">
                        <td>No.</td>
                        <td>Branch</td>
                        <td>Currency</td>
                        <td>Loan Ref.Number</td>
                        <td>Borrower's Name</td>
                        <td>CO</td>
                        <td>Phone</td>
                        <td>Address</td>
                        <td>Product Type/Model</td>
                        <td>SPA</td>
                        <td>Down Payment(%)</td>
                        <td>Down Payment($)</td>
                        <td>Disbursed Amount</td>
                        <td>O/S Principal</td>
                        <td>O/S Interest</td>
                        <td>Total Paid Principle</td>
                        <td>O/S Prin Balance</td>
                        <td>O/S Prin Balance (USD)</td>
                        <td>Overdue (Days)</td>
                        <td>{{ trans('report.rpt_principal') }} Due</td>
                        <td>{{ trans('report.rpt_interest') }} Due</td>
                        <td>{{ trans('report.rpt_fee') }} Due</td>
                        <td>{{ trans('report.rpt_other_fee') }} Due</td>
                        <td>{{ trans('report.rpt_penalty') }} Due</td>
                        <td>AIR Due</td>
                        <td>Total Pass Due</td>
                        <td>Total Pass Due (USD)</td>
                    </tr>

				</thead>

				<tbody>
					<?php
                        $n = 0;
                        $t_passdue_prin = 0;
                        $t_passdue_int = 0;
                        $t_passdue_penal = 0;
                        $t_passdue_total = 0;
                        $t_passdue_other_fee = 0;
                        $total_npl = 0;
                        $total_OS = 0;
			            $ex_rate = 1;
                        $test_arr = [];
                    ?>
					@foreach($loans as $l)
                    <?php
                        // Total OS
                        $loan_account_info = $l->client_loan_account;
                        if($loan_account_info->currency == 1) $ex_rate = $exchange_rate;
                        if($loan_account_info->currency == 2) $ex_rate = 1;
                        $total_OS += $loan_account_info->balance / $ex_rate;
                    //return [$result,$repayment_schedule, $overdue, $l_paid_date, $total_penalty, $next_schedule_date, $total_monthly_due, $to_prin_due, $to_int_due, $to_fee_due, $to_other_fee_due];
                        $penalty_arr = LoanCalculate::getTotalPenalty($l, $dpStart);
                        if($penalty_arr[2] < floatval($overdue) ) continue;
                        $overdue_val = $penalty_arr[2];
                        $to_prin_due  = $penalty_arr[7];
                        $to_int_due  = $penalty_arr[8];
                        $to_fee_due  = $penalty_arr[9];
                        $to_other_fee_due  = $penalty_arr[10];
                        $total_penalty = $penalty_arr[4];
                        $total_pass_due = $to_prin_due + $to_int_due + $to_fee_due + $to_other_fee_due + $total_penalty;
                        
                        $n++;
                        $client_info = $l->client;
                        $product_info = $l->product;
                        $trans_info = $l->transaction_req;


                        $addr = $client_info->Address;
                        $cbc_addr = "";
                        foreach($addr as $ad){
                            $cbc_addr .= ($ad->address_en1!="")?$ad->address_en1.",":"";
                            $cbc_addr .= $ad->Village->en_name .",".$ad->Commune->en_name.",".$ad->District->eng_name.",".$ad->province->eng_name; 
                            if(count($addr) > 1){
                                $cbc_addr .= "¥n";
                            }
                        }
                        
                        $product_str = $loan_types[$l->loan_type]->code;
                        if($product_info->category_name) $product_str .= ' / '.$product_info->category_name;
                        if($product_info->brand_name) $product_str .= ' / '.$product_info->brand_name;
                        if($product_info->product_type) $product_str .= ' / '.$product_info->product_type;
                        // AIR
                        if(AIR_SCH_FLG == "1"){
                            $s_interest = ($l->schedule[0]->s_interest - $l->transaction_req[0]->t_interest)/$ex_rate;
                        }else{
                            $s_interest = get_journal_bal($loan_account_info->air_id, $dpStart)['balance'];

                        }
                        $interest_due = $l->schedule->sum('interest') - $l->payment->sum('paid_interest');
                        
                    ?>
                		<tr>
	                		<td style="text-align: center">{{$n}}</td>
	                		<td style="text-align: center">{{$l->branch->short_name}}</td>
                            <td style="text-align: center">{{$loan_account_info->currencies->code}}</td>
					<td><a href="{{ route('loan_detail', [$l->id])}}">{{$l->contract_id}}</a></td>
	                		<td>{{$client_info->client_name}}</td>
					<td>{{$l->co_user->name}}</td>
                            <td>{{$client_info->phone1 . (($client_info->phone2!="")? (" / " . $client_info->phone2):"")}}</td>
                            <td>{{($client_info->address != "" && count($client_info->address) > 10) ? $client_info->address : $cbc_addr}}</td>
                            <td>{{$product_str}}</td>

	                		<td style="text-align: right">{{number_format($product_info->product_price,2,'.',',')}}</td>
	                		<td style="text-align: center">{{number_format(100*($l->down_payment/$product_info->product_price),2,'.',',')}}</td>
	                		<td style="text-align: right">{{number_format($l->down_payment,2,'.',',')}}</td>
	                		<td style="text-align: right">{{number_format($l->loan_amount,2,'.',',')}}</td>
	                		<td style="text-align: right">{{number_format($loan_account_info->balance,2,'.',',')}}</td>
                            <td style="text-align: right">{{number_format($s_interest,2,'.',',')}}</td>
	                		<td style="text-align: right">{{number_format($trans_info->sum('principal'),2,'.',',')}}</td>
	                		<td style="text-align: right">{{number_format($loan_account_info->balance,2,'.',',')}}</td>
	                		<td style="text-align: right">{{number_format($loan_account_info->balance / $ex_rate,2,'.',',')}}</td>
	                		<td style="text-align: center">{{$overdue_val}}</td>
                            <td align="right">{{ number_format($to_prin_due, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($interest_due, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($to_fee_due, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($to_other_fee_due, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($total_penalty, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($to_int_due - $interest_due, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($total_pass_due, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($total_pass_due / $ex_rate, 2, '.', ',') }}</td>
	                	</tr>
	                	<?php
	                	    $t_passdue_total += $total_pass_due / $ex_rate;
                            $total_npl += $loan_account_info->balance / $ex_rate;
                        ?>
			        
			        @endforeach
                </tbody>
                <tfoot>
			        <tr class="bolder" style="border-top: double; font-weight: bold;">
			        	<td colspan="8" class="text-right">Total:</td>
			        	<td style="text-align: right"></td>
			        	<td></td>
			        	<td style="text-align: right"></td>
			        	<td style="text-align: right"></td>
			        	<td style="text-align: right"></td>
			        	<td style="text-align: right"></td>
			        	<td style="text-align: right"></td>
			        	<td></td>
			        	<td></td>
			        	<td style="text-align: right">{{number_format($total_npl,2)}}</td>
                        <td style="text-align: right"></td>
                        <td style="text-align: right"></td>
                        <td style="text-align: right"></td>
                        <td style="text-align: right"></td>
            			<td style="text-align: right"></td>
                        <td style="text-align: right"></td>
                        <td style="text-align: right"></td>
                        <td style="text-align: right"></td>
                        <td style="text-align: right">{{number_format($t_passdue_total,2,'.',',')}}</td>
			        </tr>
                    <tr>
                        <td style="font-weight: bold; text-align: left;" colspan="27">Summary:</td>
                    </tr>
                    <tr class="bolder" style="border-top: double; font-weight: bold;">
                        <td style="font-weight: bold" colspan="2">Total NPL:</td>
                        <td style="font-weight: bold" colspan="2"> <p style="color:red;"><?php echo number_format($total_npl,2,'.',',')?></p></td>
                        <td colspan="25"></td>
                    </tr>
                    <tr class="bolder" >
                        <td style="font-weight: bold" colspan="2">Total OS Balance:</td>
                        <td style="font-weight: bold" colspan="2"><p style="color:red;"><?php echo number_format($total_OS,2,'.',',')?></p></td>
                        <td colspan="25"></td>
                    </tr>

                    <tr class="bolder">
                        <td style="font-weight: bold" colspan="2">Porfolio at Risk:</td>
                        <td style="font-weight: bold" colspan="2"><p style="color:red;"><?php echo number_format(($total_npl/$total_OS)*100,2,'.',',')?>%</p></td>
                        <td colspan="25"></td>
                    </tr>
    			 </tfoot>
			</table>

			<?php echo getFooter(true);?>
			</div>



		</div>

		<!-- <div class="page">
			<div class="custom-pagi">
				<input type="text" class="form-control" name="set_offset" value="<?php //echo $_GET['offset']?>" />
				<a href="#" class="btn btn-primary">Go</a>
            </div>
            <?php //echo $repayments->appends([
//             		'sort' =>'loans.id',
//             		'br'=>Input::get('br'),
//             		'dpStart'=>Input::get('dpStart'),
//             		'borrow_name'=>Input::get('borrow_name'),
//             		'loan_ref'=>Input::get('loan_ref'),
//             		'co_id'=>Input::get('co_id'),
//             		'category_id'=>Input::get('category_id'),
//             		'overdue'=>Input::get('overdue'),
//             		'offset'=>Input::get('offset')
//             ])->render();
			?>
        </div>-->
    </div>
</section>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.floatThead.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script src="{{ asset('js/dataTables.js',isset($secure) ? false : false) }}"></script>
    
    <script type="text/javascript" src="{{ asset('js/xlsx.full.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>

    <script type="text/javascript">
    // This must be a hyperlink

        TableExport.prototype.charset = "charset=utf-8";
        $("#export").click(function (event) {
            var con = confirm("Do you really want to export to CSV file?");
            if(con == true){
                new TableExport(document.getElementById('parc-table'), {
                        formats: ['csv'],
                        filename: 'parc_report'
                    });
                    $('button.csv').hide().click();
                    $('.tableexport-caption').remove();
            }
        });

        $("#xexport").click(function (event) {
            var con = confirm("Do you really want to export to Excel file?");
            if(con == true){
                new TableExport(document.getElementById('parc-table'), {
                        formats: ['xlsx'],
                        filename:'parc_report'
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });
        
        $(document).ready(function(){
          $(".sticky-header").floatThead({scrollingTop:77});

          $('#parc-table').dataTable({
            "paging":   true,
            "ordering": true,
            "info":     true,
            "iDisplayLength": 10000000,
            "pageLength": 1000
          });
		  
        	$('.dpStart').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });

            $('.custom-pagi a').on('click', function(){
        				val = $(this).parent().find('input[name="set_offset"]').val();
        				$('input[name="offset"]').val(val);
        				$('#search_frm').submit();
        				return false;
            });

            
           //  tss = $('.table-striped').attr('style');
          	// tss = tss.split('min-width: ');
          	// tss = tss[1].split(';');
          	// $('.prepare').css('min-width', tss[0]);
            dosorting();


        });

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
