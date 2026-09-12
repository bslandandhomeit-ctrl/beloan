@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        {{ trans("report.rpt_total_cash_collection_on" )}} from {{date("Y-M-d",strtotime($sdate))}} to {{date("Y-M-d",strtotime($edate))}}
    </header>

    <div class="panel-body">
        <div class="position-center" style="width:100%;">
            <form role="form" method="get" action="{{ route('rpt_collection') }}" id="search_frm">
                <div class="row">
                  
                    <label class="control-label col-md-1" style="padding-top:10px;">{{ trans('report.rpt_branch_name') }}</label>
                    <div class="col-md-2">
                        <select class="form-control" id="br" name="br">
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
                    <label class="control-label col-md-1" style="padding-top:10px;">{{ trans('report.rpt_currency') }}</label>
                    <div class="col-md-2">
                      <select class="form-control" id="cur" name="cur">
                        <option value="100">-</option>
                            @foreach($currency as $key => $value)
                                <option value="{{ $key }}"
                                @if(isset($currency_id) && $currency_id == $key)
                                selected
                                @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                      
                    <label class="control-label col-md-1" style="padding-top:10px;">{{ trans('report.rpt_from') }}</label>
                    <div class="col-md-2">
                        <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpStart">
                            <input type="text" name="dpStart" size="16" class="form-control" value="{{ isset($sdate)?$sdate:old('dpStart') }}">
                            <span class="add-on birhtdateDatepicker">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                    <label class="control-label col-md-1" style="padding-top:10px;">{{ trans('report.rpt_to') }}</label>
                    <div class="col-md-2">
                        <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpEnd">
                            <input type="text" name="dpEnd" size="16" class="form-control" value="{{ isset($edate)?$edate:old('dpEnd') }}">
                            <span class="add-on birhtdateDatepicker">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-8" style="padding-top:5px;">
                        <input type="hidden" name="offset" />
                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                        <button class="btn btn-primary" id="export"><i class="fa fa-sign-out"></i> {{ trans('multiple.export') }}</button>
                    </div>
            <div class="page">
                <div class="custom-pagi">
                    <span class="pagi_label">Number of Rows:</span>
                    <select class="form-control" name="set_offset" id="set_offset">
                            <option value="15">-</option>
                            @foreach(config('static_data.selectRows') as $s)
                            <option value="{{ $s }}"
                                    @if(isset($offset))
                                    @if($offset==$s)
                                    selected
                                    @endif
                                    @endif>{{$s}}</option>
                            @endforeach
                    </select>
                    <a href="#" class="btn btn-danger">Go</a>
                </div>
            </div>
                </div>
            
                        </form>

        </div>
        <br/><br/>
        <div id="printArea">
            @include('api.report_header',['co_phone'=>!empty($co_id->co_user) ? $co_id->co_user->phone: ''])
            <h4 class="sch_title">{{ trans("report.rpt_total_cash_collection_on" )}} {{date("Y-M-d",strtotime($sdate))}}</h4>
            <section id="unseen">
                <table class="table table-bordered table-striped table-condensed collection" id="col">
                    <thead class="th-center">
                        <tr>
                            <th>{{ trans('multiple.m_no') }}</th>
                            <th>{{ trans('report.rpt_contract_id') }}</th>
                            <th>{{ trans('customer.cus_customer_name') }}</th>
                            <th>{{ trans('report.rpt_branch') }}</th>
                            <th>{{ trans('loan.l_currency') }}</th>
                            <th>{{ trans('report.rpt_co_name') }}</th>
                            <th>{{ trans('product.p_productsTypes') }}</th>
                            <th>new {{ trans('product.p_productsTypes') }}</th>
                            <th>{{ trans('multiple.m_status') }}</th>
                            <th>{{ trans('multiple.pl_npl') }}</th>
                            <th>{{ trans('loan.l_disbursed_on') }}</th>
                            <th>{{ trans('report.rpt_maturity_date') }}</th>
                            <th>{{ trans('report.disburse_amount') }}</th>
                            <th>{{ trans('report.rpt_outstanding_balance') }}</th>
                            <th>{{ trans('multiple.repay_date') }}</th>
                            <th>{{ trans('report.rpt_principal') }}</th>
                            <th>{{ trans('report.rpt_interest') }}</th>
                            <th>{{ trans('report.rpt_fee') }}</th>
                            <th>{{ trans('report.rpt_other_fee') }}</th>
                            <th>{{ trans('report.rpt_penalty') }}</th>
                            <th>{{ trans('report.rpt_total_amount') }}</th>
                            <th>{{ trans('multiple.m_note') }}</th>
                            <th>{{ trans('multiple.m_phone',['num'=>'']) }}</th>
                            <th>{{ trans('multiple.m_address') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 1;
						$account_status = config('static_data.client_loan_account_status');
						$new_loanCategory = config('static_data.new_loanCategory');
                        //$days = cal_days_in_month(CAL_GREGORIAN, date("m", strtotime($sdate)), date("Y", strtotime($sdate)));
                        $days = date_dif(date('Y-m-d', strtotime($sdate)), date('Y-m-d', strtotime($edate)), 1, false);
                        $grouped = $trans->groupBy(function ($item, $key) {
                            return date('Y-m-d', strtotime($item->trans_date));
                        });

                        $total_amount = array_fill(0, 5, 0.0);
                        $sub_total_amount = 0.0;
						$old_contact_id_array = [];
						$old_date_array = [];
                        $no = 0;
                        ?>
                        @for($i=1;$i<=$days+1;$i++)
                        <?php 
                            $start_i = date('Y-m-d',strtotime($sdate."+ ".($i-1)." days"));
                        ?>

                        @if(count($grouped[$start_i]))
                        <?php
                        if ($j != $i) {
                            $sub_total_amount = 0;
                        }
						array_push($old_date_array, $i);
						$old_contact_id_array = [];
						?>
                        @foreach($grouped[$start_i] as $key => $tran)
                        <?php
                            $currency = $tran->loan->client_loan_account->currency;
                            $br_id = $tran->loan->company_branch_id;
                            if($currency_id != "100" && $currency != $currency_id) continue;
                            if($branch_id != "" && $br_id != $branch_id) continue;
							$total_horizontal = 0.0;
                            $principal = $interest = $other_fee = $fee = $penalty = 0.0;
                            if($tran->amount == 0) continue;
							if(in_array($i, $old_date_array, TRUE) && in_array($tran->loan->contract_id, $old_contact_id_array, TRUE)) continue;
							$no++;
							$old_contact_id = $tran->loan->contract_id;
							$old_tran_id = $tran->id;
							$principal = floatval($tran->principal);
							$interest = floatval($tran->interest);
							$fee = floatval($tran->fee);
							$other_fee = floatval($tran->other_fee);
							$penalty = floatval($tran->penalty);
//							if($old_contact_id == "LC18050200001") dd()
                            // get total_amount
							foreach($grouped[$start_i] as $key1 => $tran1){
							
								if($old_tran_id != $tran1->id && $old_contact_id == $tran1->loan->contract_id){
									$principal += floatval($tran1->principal);
									$interest += floatval($tran1->interest);
									$fee += floatval($tran1->fee);
									$other_fee += floatval($tran1->other_fee);
									$penalty += floatval($tran1->penalty);
								}
							}
							$acc_type = explode("-", $tran->loan->client_loan_account->acc_key)[0];
							$pl_status = ($tran->loan->client_loan_account->status <= GENERAL_LC_STATUS)?"PL":"NPL";
                        ?>
                        <tr>
                            <td>{{ $no }}</td>
							<td><a href="{{ route('loan_detail',[$tran->loan->id]) }}">{{ $tran->loan->contract_id }}</a></td>
                            <td>{{ $tran->loan->client->client_name }}</td>
                            <td>{{ $tran->loan->client_loan_account->get_branch->short_name}}</td>
                            <td>{{ $tran->loan->client_loan_account->currencies->code}}</td>
							<td>{{ $tran->loan->co_user->name}}</td>
                            <td>{{ $acc_type }}</td>
                            <td>{{ $new_loanCategory[$acc_type] }}</td>
                            <td>{{ $account_status[$tran->loan->client_loan_account->status] }}</td>
                            <td>{{ $pl_status }}</td>
                            <td>{{ $tran->loan->disburse_date }}</td>
							<td>{{ $tran->loan->schedule->last()->schedule_date }}</td>
                            <td>{{ number_format($tran->loan->loan_amount,2) }}</td>
                            <td>{{ number_format($tran->loan->client_loan_account->balance, 2) }}</td>
							<td>{{ $tran->trans_date }}</td>
                            <td style="text-emphasis: right;">
                                <?php
                                if ($principal != 0) {
                                    $total_amount[0] += $principal;
                                    $total_horizontal += $principal;
                                    echo number_format($principal, 2);
                                } else {
                                    echo "0.00";
                                }
                                ?>
                            </td>
                            <td style="text-emphasis: right;">
                                <?php
                                if ($interest != 0) {
                                    $total_amount[1] += floatval($nterest);
                                    $total_horizontal += $interest;
                                    echo number_format($interest, 2);
                                } else {
                                    echo "0.00";
                                }
                                ?>
                            </td>
                            <td style="text-emphasis: right;">
                                <?php
                                if ($fee != 0) {
                                    $total_amount[2] += floatval($fee);
                                    $total_horizontal += $fee;
                                    echo number_format($fee, 2);
                                } else {
                                    echo "0.00";
                                }
                                ?>
                            </td>
                            <td style="text-emphasis: right;">
                                <?php
                                if ($other_fee != 0) {
                                    $total_amount[2] += floatval($other_fee);
                                    $total_horizontal += $other_fee;
                                    echo number_format($other_fee, 2);
                                } else {
                                    echo "0.00";
                                }
                                ?>
                            </td>
							<td style="text-emphasis: right;">
                                <?php
                                if ($penalty != 0) {
                                    $total_amount[3] += floatval($penalty);
                                    $total_horizontal += $penalty;
                                    echo number_format($penalty, 2);
                                } else {
                                    echo "0.00";
                                }
                                ?>
                            </td>
                            <td style="text-emphasis: right;">{{ number_format($total_horizontal,2,'.',',') }}</td>
                            <td>{{ $tran->description }}</td>
                            <td>{{ $tran->loan->client->phone1 }}</td>
                            <td>{{ $tran->loan->client->address }}</td>
                        </tr>
                        <?php 
							$sub_total_amount += $total_horizontal; 
							array_push($old_contact_id_array, $old_contact_id);
                            $j = $i;
						?>
                        @endforeach
                        @endif
                        @endfor

                    </tbody>
                </table>
                <br/>
                <table class="table-striped table-condensed toto-cash" style="font-size:1.2em;font-weight:bold;">
                    <tr><td>- {{ trans('report.rpt_total_principal') }} :</td><td style="text-emphasis: right;">{{ number_format($total_amount[0],2,'.',',') }}</td></tr>
                    <tr><td>- {{ trans('report.rpt_total_interest') }} :</td><td style="text-emphasis: right;">{{ number_format($total_amount[1],2,'.',',') }}</td></tr>
                    <tr><td>- {{ trans('loan.l_total_fee') }} :</td><td style="text-emphasis: right;">{{ number_format($total_amount[2],2,'.',',') }}</td></tr>
                    <tr><td>- {{ trans('report.rpt_total_penalty') }} :</td><td style="text-emphasis: right;">{{ number_format($total_amount[3],2,'.',',') }}</td></tr>
                    <tr style="background-color:#1FB5AD;color:#FFF;"><td>{{ trans('report.total_cash_collection') }} :</td><td style="text-emphasis: right;">{{ number_format(array_sum($total_amount),2,'.',',') }}</td></tr>
                </table>
            </section>
        </div>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('.dpStart').datepicker({
        format: 'yyyy-mm-dd',
        viewMode: 'years',
        minViewMode: "dates",
        autoclose: true
    });
    $('.dpEnd').datepicker({
        format: 'yyyy-mm-dd',
        viewMode: 'years',
        minViewMode: "dates",
        autoclose: true
    });

    $('.custom-pagi a').on('click', function () {
        val = $(this).parent().find('input[name="set_offset"]').val();
        $('input[name="offset"]').val(val);
        $('#search_frm').submit();
        return false;
    });
});
  $("#export").click(function (event) {
      var con = confirm("Do you really want to export to CSV file?");
      if(con == true){
          new TableExport(document.getElementById('col'), {
              formats: ['csv'],
              filename:"collection"
          });
          $('button.csv').hide().click();
          $('.tableexport-caption').remove();
      }
  });
</script>
@endsection
