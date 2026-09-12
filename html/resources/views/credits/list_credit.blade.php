@extends('layouts.app')

@section('css')
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<style>
	@media print {
		a[href]:after {
		content: none !important;
		}
	}
</style>
@endsection
<?php   $account_status = config('static_data.client_loan_account_status');
    $loan_status = config('static_data.loan_status');
?>
@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                <span>{{ trans('sidebar.sb_credit_summary') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    @if($errors->addCate->has('ipCate'))
                    <div class="alert alert-danger fade in">
                        <button class="close close-sm" type="button" data-dismiss="alert">x</button>
                        {{$errors->addCate->first('ipCate')}}
                    </div>
                    @endif

                    <form role="form" id="search_frm" method="get" action="{{ route('creditSummary') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-lg-6">
                                <?php if (count($branch) > 1) { ?>
                                    
                                        <div class="form-group">
                                            <select class="form-control" name="branch">
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
                                        </div>
                                <?php } //dd($account_status);
                                ?>
                                    <div class="form-group">
                                        <select name="status" id="status" class="form-control">
                                            <option value="">-</option>
                                            @foreach($account_status as $key => $value)
                                            @if(array_key_exists($status,$account_status))
                                            <option value="{{ $key }}"
                                                    @if(isset($status))
	                                                    @if($status == $key)
	                                                    	selected
	                                                    @endif
                                                    @endif>{{ $value }}</option>
                                            @else
                                            <option value="{{ $key }}">{{ $value }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                            </div>
                            <div class="col-lg-6">
                                    <div class="form-group">
                                        <!-- <label for="overdue">Overdue(days):</label> -->
                                        <input type="text" class="form-control" id="overdue" name="overdue" value='{{($set_overdue)?$set_overdue:''}}' placeholder="Overdue(days)" />
                                    </div>
                               
                                    <div class="form-group">
                                        <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpDate">
                                          <input type="text" name="dpDate" size="16" class="form-control" value="{{ ($dpDate)?$dpDate: date('Y-m-d')}}">
                                          <span class="add-on birhtdateDatepicker ptl-3">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                          </span>
                                        </div>
                                    </div>
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col-sm-offset-6 col-sm-6">
                                <div class="form-group">
                                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Search</button>
                                        <button type="submit" class="btn btn-warning" id="printer"><i class="fa fa-print"></i> Print</button>
                                        <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                                        <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="page">
                            <div class="custom-pagi">
                                <span class="pagi_label">{{ trans('sidebar.sb_number_of_rows') }}:</span>
                                <input type="text" class="form-control" name="offset" value="{{ isset($offset)? $offset : ''}}" />
                                <button class="btn btn-danger" type="submit" style="margin-top:10px;">Go</button>
                            </div>
                        </div>
                    </form>

                </div>
                <br/><br/>
                <div id="printArea">
                    @include('api.report_header')
                    <h4 class="sch_title">
                        {{ trans('sidebar.sb_credit_summary') }}
                        @foreach($branch as $b)
                        @if(isset($branch_id))
                        @if($branch_id==$b->id)
                        For Branch {{ $b->branch_name }}
                        @endif
                        @endif
                        @endforeach
                    </h4>

                    <table class="table table-bordered table-striped table-condensed cf" id="credit_summary">
                        <thead class="cf">
                            <tr>
                                <th style="text-align: center;">{{ trans('multiple.m_no') }}</th>
                                <th style="text-align: center;">{{ trans('customer.cus_customer_name') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_loan_account_number') }}</th>
                                <th style="text-align: center;">{{ trans('product.p_loan_reference') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_loan_type') }}</th>
								<th style="text-align: center;">{{ trans('report.rpt_co_name') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.branch') }}</th>
                                <th style="text-align: center;">{{ trans('account.currency') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.country') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.province') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.district') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.commune') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.village') }}</th>
                                <th style="text-align: center;">{{ trans('loan.l_disburse_date') }}</th>
                                <th style="text-align: center;">{{ trans('loan.l_last_paid_date') }}</th>
                                <th style="text-align: center;">{{ trans('loan.l_next_schedule') }}</th>
                                <th style="text-align: center;">{{ trans('loan.l_tenure') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_overdue') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_outstanding_balance') }}</th>
                                <th style="text-align: center;">{{ trans('account.air_amount') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_total_pass_due') }}</th>
                                <th style="text-align: center;">{{ trans('report.fee') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.m_status') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.exp_status') }}</th>
                                <!-- <th style="text-align: center;">{{ trans('multiple.m_action') }}</th> -->
                            </tr>
                        </thead>
                        <form role="form" id="submit_frm" method="get" action="{{ route('creditSummary')}}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <?php $n = 1;//dd($summary);
                          $t_bal = $t_air = $t_pass_due = 0;
						?>
                        <tbody>
                            @forelse($summary as $sum)
                            <?php
                                $loan_st = $sum->loanstatus;
                                $acc_status = $account_status[$sum->status];
                                if($loan_st == 8) $acc_status .= " - " . $loan_status[$loan_st];
                                if(is_null($sum->loan)) continue;
								$penalty_arr = ($sum->status <= CLOSE_LC_STATUS+1)? LoanCalculate::getTotalPenalty($sum->loan, $dpDate):0;
								$i_overdue = is_null($penalty_arr)? 0 : $penalty_arr[2];
//                                if($sum->loan->id == 197) dd($penalty_arr);
                                //if($penalty_arr == -1 || $penalty_arr[2] == 0) continue;
                                
                                if($penalty_arr == -1) continue;
                                if(!is_null($set_overdue) && $i_overdue < $set_overdue) continue;
                                $exp_status = get_auto_provision_old($sum, $sum->loan, $i_overdue);
                                $air_amount = get_journal_bal($sum->air_id, $dpDate);
                                $coa_amount = get_journal_bal($sum->coa_id, $dpDate);
								if($loan_st == 6 && $sum->status <= CLOSE_LC_STATUS) continue;
                                $acc_type = explode("-", $sum->acc_key)[0]; // got from acc_key
                            ?>
                            <tr>
                                <td>{{$n}}</td>
                                <td class="acc_name">{{$sum->account_name}}</td>
                                <td class="acc_no">
                                  <a style="text-decoration: underline" href="{{ route('loan_account', [$sum->id])}}">{{(!empty($sum->account_no))? $sum->account_no : ""}}</a>

                                </td>
                                <td>
                                  <a style="text-decoration: underline" href="{{ route('loan_detail', [$sum->loan->id])}}">{{ $sum->loan_ref ? $sum->loan_ref : '-'  }}</a>
                                </td>
                                <td align="center">{{$acc_type}}</td>
	                            <td align="center">{{$sum->loan->co_user->name }}</td>
                                <td align="center">{{$sum->branch}}</td>
                                <td align="center">{{$currency_list[$sum->currency]}}</td>
                                <td align="center">{{$sum->client->Address[0]->country->iso_code_3}}</td>
                                <td align="center">{{$sum->client->Address[0]->province->eng_name}}</td>
                                <td align="center">{{$sum->client->Address[0]->District->eng_name}}</td>
                                <td align="center">{{$sum->client->Address[0]->Commune->en_name}}</td>
                                <td align="center">{{$sum->client->Address[0]->Village->en_name}}</td>
                                <td align="center">{{$sum->disburse_date}}</td>
                                <td>{{($sum->status == 6)? $sum->loan->payment->last()->repayment_date : $coa_amount["last_acc_date"]}}</td>
                                <td>{{is_null($penalty_arr)? 0 : $penalty_arr[3]}}</td>
                                <td style="text-align: center">{{$sum->loan->loan_duration}}</td>
                                <td style="text-align: center">{{is_null($penalty_arr)? 0 : $penalty_arr[2]}}</td>
                                <td style="text-align: right">{{($sum->status == 6)? number_format($sum->loan->writeoff->write_off_outst_balance,2) : number_format($coa_amount["balance"],2)}}</td>
                                <td style="text-align: right">{{($sum->status == 6)? number_format($sum->loan->writeoff->wo_interest,2) : number_format($air_amount["balance"],4)}}</td>
                                <td style="text-align: right">{{is_null($penalty_arr)? 0 : number_format($penalty_arr[6] + $penalty_arr[4], 4)}}</td>
                                <td style="text-align: right">{{is_null($penalty_arr)? 0 : number_format($penalty_arr[9], 4)}}</td>
                                <td class="acc_status">{{$acc_status}}</td>
                                <td>
                                {{$account_status[$exp_status]}}
								@if($exp_status > 0)
                                    <div class="form-group">
                                        <select id = "set_status[{{$sum->id}}]" name="set_status[{{$sum->id}}]" class="form-control">
                                            <option value="{{$exp_status}}">{{$account_status[$exp_status]}}</option>
                                            @foreach($account_status as $key => $value)
                                            @if(array_key_exists($status,$account_status))
                                            <option value="{{ $key }}"
                                                    @if(isset($set_status))
                                                    @if($set_status == $key)
                                                    selected
                                                    @endif
                                                    @endif>{{ $value }}</option>
                                            @else
                                            <option value="{{ $key }}">{{ $value }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                </td>
                                <!-- <td>
                                    <?php if ($sum->status < 6) { ?>
                                        <a href="<?php if ($sum->status < 6) { ?>{{ route('exe_provision',$sum->id) }} <?php
                                        } else {
                                            echo "#";
                                        }
                                        ?>" class="getUp btn btn-default btn-xs" title="{{$account_status[$sum->status + 1]}}">Provision</a>
                                    <?php } else { ?>
                                        <input type="submit" class="btn btn-default btn-xs" disabled="disabled " value="Provision" style="color: #229AEA;" />
                            <?php } ?>
                                </td> -->
                            </tr>
                            <?php $n++;
                              $t_bal += $sum->balance;
                              $t_air += $air_amount["balance"];
                              $t_pass_due += $penalty_arr[6] + $penalty_arr[4];
                            ?>
                            @empty
                            <tr><td colspan=12>{{ trans('multiple.m_no_result')}}</td></tr>
                            @endforelse
                        </tbody>
                        <tr>
                          <td colspan=9 style="text-align: right; font-weight: bold">{{ trans('loan.total')}}</td>
                          <td style="text-align: right; font-weight: bold">{{ number_format($t_bal, 2) }}</td>
                          <td style="text-align: right; font-weight: bold">{{ number_format($t_air,4) }}</td>
                          <td style="text-align: right; font-weight: bold">{{ number_format($t_pass_due,2) }}</td>
                        </tr>
                    </table>

                </div>
                    <input type="hidden" name="flag" value=1 />
	                <input type="hidden" name="date" value="{{$dpDate}}" />
				@if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
	                <div class="text-center">
	                    <input type="submit" class="btn btn-success" value="{{ trans('multiple.auto_pro') }}">
	                    <!-- <a href="{{route('creditSummary')}}?flag=1&date={{$dpDate}}" class="btn btn-success" id="submit">{{ trans('multiple.auto_pro') }}</a> -->
	                </div>
				@endif
                </form>
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
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>

<script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>

<script>

$(".getUp").click("#getUp", function () {
    this_ = $(this);
    var $row = $(this).closest("tr");    // Find the row
    var $acc_no = $row.find(".acc_no").text(); // Find the text
    var $acc_name = $row.find(".acc_name").text();
    var $acc_status = $row.find(".acc_status").text();
    var p;
    if (confirm("Do you want to do provision for account " + $acc_no + " ( " + $acc_name + " ) from " + $acc_status + " -> " + this_.attr('title')) == true) {
        window.location = this_.attr('href');
    } else {
        return false;
    }
    //$('#getUp').html('');
});

$(document).ready(function () {
    $('.dpDate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        setDate: new Date()
    });
    /*$('.custom-pagi a').on('click', function () {
        val = $(this).parent().find('input[name="set_offset"]').val();
        $('input[name="offset"]').val(val);
        $('#search_frm').submit();
        return false;
    });
		*/
    $("#submit").click(function() {
        //var set_status = $(this).parents('tr').find('select[type="adjust"]').val();

        var set_status = $('#set_status').val();
        console.log(set_status);
        //location.href = '{{route('creditSummary')}}?flag=1&date={{$dpDate}}&setStatus=' + set_status;
        //location.href = '{{route('creditSummary')}}?flag=1&date={{$dpDate}}';

    });
		// This must be a hyperlink
    $("#export").click(function (event) {
			event.preventDefault();
        var con = confirm("Do you really want to export to CSV file?");
        if(con == true){
            new TableExport(document.getElementById('credit_summary'), {
                formats: ['csv'],
                filename: 'credit_summary'
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
    });

    $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('credit_summary'), {
                    formats: ['xlsx'],
                    filename: 'credit_summary'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });

});

</script>
@endsection
