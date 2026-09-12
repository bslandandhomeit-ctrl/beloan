@extends('layouts.app')

@section('css')
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
<style type="text/css">
  @media print {
        a[href]:after {
          content: none !important;
        }
        a{
          text-decoration: none !important;
        }
  }
</style>
@endsection
<?php $currency = config('static_data.currency'); ?>
<?php $client_loan_account_status = config('static_data.client_loan_account_status'); ?>
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>
            @if($flag==1)
            {{ trans('sidebar.sb_accrued_loan_list') }}
            @else
            {{ trans('sidebar.sb_accrued_interest_list') }}
            @endif
        </span>
    </header>
    <div class="panel-body">
        <section id="unseen">
            <form role="form" method="get" action="{{ route('list_loan') }}" id="search_frm">
                <table class="tb-search-box">
                    <tr>
                        <td>
                            {{ trans('account.a_customer_account') }}/{{ trans('account.a_customer_reference') }}<br/>
                            <div>
                                <input type="text" class="form-control" value="{{$code}}" id="client_code" name="code" placeholder="Customer_Account/Reference">
                            </div>
                        </td>
                        <td>
                            {{ trans('multiple.m_start_date') }}<br/>
                            <div id="start-date" data-date-viewmode="years" data-initialize="datepicker"
                                 data-date-format="yyyy-mm-dd" data-date="{{ $start }}" class="input-append date"
                                 style="width: 95%;">
                                <input type="text" name="start" size="16" class="form-control" value="{{ $start }}"/>
                                <span class="input-group-btn add-on">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </td>
                        <td></td>
                        <td>
                            {{ trans('multiple.m_end_date') }}<br/>
                            <div id="end-date" data-date-viewmode="years" data-initialize="datepicker"
                                 data-date-format="yyyy-mm-dd" class="input-append date" style="width: 95%;">
                                <input type="text" name="end" size="16" class="form-control" value="{{ $end }}"/>
                                <span class="input-group-btn add-on">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </td>
                        <td></td>
                        <td>
                            {{ trans('report.rpt_branch_name') }}<br/>
                            <div>
                                <select name="company_branch_code" class="form-control">
                                    <option value="">-</option>
                                    @foreach($company_branch as $cb)
                                    <option value="{{ $cb->branch_code }}" {{ isset($company_branch_code) &&  $company_branch_code == $cb->branch_code ? 'selected':'' }}>{{ $cb->branch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td></td>
                        <td>
                            {{ trans('account.currency') }}<br/>
                            <div>
                                <select class="form-control" id="cur" name="cur">
                                    <option value="100">-</option>
                                    @foreach($currency as $key => $value)
                                    <option value="{{ $key }}"
                                            @if(isset($cur) && $cur == $key)
                                            selected
                                            @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>

                <?php
                if (isset($_GET['flag'])) {
                    $ex = '';
                    $exlang = trans('multiple.m_loan_list');
                    echo '<input type="hidden" name="flag" value="' . $flag . '" />';
                } else {
                    $ex = '?flag=1';
                    $exlang = trans('multiple.m_accrued');
                }
                ?>

                <div class="row">
                    <div class="col-lg-offset-1 col-lg-6">
                        <input type="hidden" name="offset" />
                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                        <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                        <a class="btn btn-default" href="{{ route('list_loan') }}{{$ex}}"><i class="fa  fa-exchange"></i> {{ $exlang }}</a>
                    </div>
                </div>

            </form>
            <div class="page">
                <div class="custom-pagi">
                    <span class="pagi_label">Number of Rows:</span>
                    <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                    <a href="#" class="btn btn-danger">Go</a>
                </div>
            </div>
        </section>
        <br/><br/>
        <div id="printArea" style="clear: both">
            <h2><strong>Land Home</strong></h2>
            <h4><strong> {{ trans('multiple.m_branch') }}:
                    {{ $branch_name }}
                </strong></h4>
            <h4><strong> {{ trans('multiple.m_period') }}:
                    @if(isset($start) && isset($end))
                    {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
                    @else
                    {{ isset($start)?'Report on'.date("d-M-Y", strtotime($start)):'' }}
                    {{ isset($end)?'Report on '.date("d-M-Y", strtotime($end)):'' }}
                    @endif
                </strong></h4>
            <h4><strong> {{ trans('account.currency') }}:
                    @foreach($currency as $key => $value)
                    @if(isset($cur))
                    @if($cur == $key)
                    ({{ $value }})
                    @endif
                    @endif
                    @endforeach
                </strong></h4>

        <div >
            <section id="unseen">
                @if($flag==1)
                <div align="center"><h4><strong>Accrued Loans Listing</strong></h4></div>
                @else
                <div align="center"><h4><strong>Accrued interest on Loans</strong></h4></div>
                @endif
                <table class="table table-bordered table-striped table-condensed sticky-header" id="list_loan_id" >
                    <thead class="th-center" style = "background : #1fb5ad">
                        <tr>
                            <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.m_no') }}</th>
                            <th rowspan=2 style="vertical-align:middle;">{{ trans('customer.cus_customer_name') }}</th>
                            <th rowspan=2 style="vertical-align:middle;">{{ trans('account.a_customer_account') }}</th>
			                <th rowspan=2 style="vertical-align:middle;">{{ trans('account.a_customer_reference') }}</th>
                            <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_loan_type') }}</th>
               		        <th rowspan=2 style="vertical-align:middle;">{{ trans('product.p_productsTypes') }}</th>
               		        <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.branch') }}</th>
			                <th rowspan=2 style="vertical-align:middle;">{{ trans('account.currency') }}</th>
			                <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.m_start_date') }}</th>
                            <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.m_due_date') }}</th>
                            <th rowspan=2 style="vertical-align:middle;">{{ trans('report.disburse_amount') }}</th>
                            <th rowspan=2 style="vertical-align:middle;">{{ trans('loan.l_interest_rate') }}</th>
                            <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.collateral') }}</th>
                            <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.collateral_val') }}</th>
                            <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.status') }}</th>
                            <th rowspan=2 style="vertical-align:middle;">{{ trans('customer.a_last_acc_date') }}</th>
                            <th rowspan=1 colspan=2 style="vertical-align:middle;">{{ trans('account.a_pre_balance') }}</th>
                            <th colspan=2 style="vertical-align:middle;">{{ trans('account.a_tran') }}</th>
                            <th rowspan=1 colspan=3 style="vertical-align:middle;">{{ trans('account.a_bal') }}</th>
                        </tr>
                        <tr>
                            <th>{{ trans('account.a_debit') }}</th>
                            <th>{{ trans('account.a_credit') }}</th>
                            <th>{{ trans('account.a_debit') }}</th>
                            <th>{{ trans('account.a_credit') }}</th>
                            <th>{{ trans('account.a_debit') }}</th>
                            <th>{{ trans('account.a_credit') }}</th>
                            <th>{{ trans('account.a_bal') }}</th>
                        </tr>
                    </thead>
                    <?php $n = 1;
                        $total_pre_dr  = 0;
                        $total_pre_cr = 0;
                        $total_cur_dr = 0;
                        $total_cur_cr = 0;
                        $total_bal = 0;
                    ?>
                    <tbody>
                        @forelse($result as $r)
                        <?php
                        $balance = get_balance($r->loan->loan_amount, $r->loan->transaction, $start);
                        if(floatval($dc_result['pre'][$r->id]['debit']) + floatval($dc_result['pre'][$r->id]['credit']) + floatval($dc_result['cur'][$r->id]['debit']) + floatval($dc_result['cur'][$r->id]['credit']) == 0) continue;?>
                        <tr>
                            <td align="center">{{ $n }}</td>
                            <td align="center">{{ $r->account_name }}</td>
                            <td align="center">{{ $r->account_no }}</td>
                            <td align="center">
                              <a style="text-decoration: underline" href="{{ route('loan_detail', [$r->loan->id])}}">{{ $r->loan_ref }}</a>
                            </td>
                            <td align="center">{{ explode("-", $r->acc_key)[0] }}</td>
                  			<td align="center">{{ $r->loan->product_type->code }}</td>
                  			<td align="center">{{ $r->branch }}</td>
                  			<td align="center">{{ $r->currencies->code }}</td>
                            <td align="center">{{$r->loan->schedule[0]->schedule_date }}</td>
                            <td align="center">{{$r->loan->schedule[sizeof($r->loan->schedule)-1]->schedule_date}}</td>
                            <td align="right">{{ number_format($r->loan->loan_amount, 2,'.',',') }}</td>
                            <td align="center">{{ $r->loan->interest_rate }}%</td>
                            <?php

                              $col_type = "";
                              $col_val_str = "";
                              $col_val = 0;
                              if(!is_null($r->loan->collateral)){
                                $col_type = $r->loan->collateral[0]->collateral_type;
                                foreach($r->loan->collateral as $col){
                                    $col_val += $col->collateral_value;
                                }
                              }else{
                                $col_type = "-";
                                $col_val = "-";
                              }
                            ?>
                            <td align="center">{{ $col_type}}</td>
                            <td align="right">{{ number_format($col_val,2) }}</td>
                            <td align="center">{{ $client_loan_account_status[$r->status] }}</td>
                            <td align="center">{{ date('Y-m-d', strtotime($dc_result['last_acc_date'][$r->id])) }}</td>
                            <td align="right">{{ number_format($dc_result['pre'][$r->id]['debit'], 2,'.',',') }}</td>
                            <td align="right">{{ number_format($dc_result['pre'][$r->id]['credit'], 2,'.',',') }}</td>
                            <td align="right">{{ number_format($dc_result['cur'][$r->id]['debit'], 2,'.',',') }}</td>
                            <td align="right">{{ number_format($dc_result['cur'][$r->id]['credit'], 2,'.',',') }}</td>
                            <td align="right">{{ number_format($dc_result['pre'][$r->id]['debit'] + $dc_result['cur'][$r->id]['debit'], 2,'.',',') }}</td>
                            <td align="right">{{ number_format($dc_result['pre'][$r->id]['credit'] + $dc_result['cur'][$r->id]['credit'], 2,'.',',') }}</td>
                            <td align="right">{{ number_format(($dc_result['pre'][$r->id]['debit'] + $dc_result['cur'][$r->id]['debit']) - ($dc_result['pre'][$r->id]['credit'] + $dc_result['cur'][$r->id]['credit']), 2,'.',',') }}</td>
                        </tr>
                        <?php $n++;
                            $total_pre_dr += $dc_result['pre'][$r->id]['debit'];
                            $total_pre_cr += $dc_result['pre'][$r->id]['credit'];
                            $total_cur_dr += $dc_result['cur'][$r->id]['debit'];
                            $total_cur_cr += $dc_result['cur'][$r->id]['credit'];
                            $total_bal += $balance;
                        ?>
                        @empty
                        <tr><td colspan=12>{{ trans('multiple.m_no_result')}}</td></tr>
                        @endforelse
                        <tr style = "font-weight: bold;">
                          <td align="right" colspan="8" font-weight="bold">Total</td>
                          <td align="right">{{number_format($total_bal, 2)}}</td>

                          <td align="right" colspan="5"></td>
                          <td align="right">{{number_format($total_pre_dr, 2)}}</td>
                          <td align="right">{{number_format($total_pre_cr, 2)}}</td>
                          <td align="right">{{number_format($total_cur_dr, 2)}}</td>
                          <td align="right">{{number_format($total_cur_cr, 2)}}</td>
                          <td align="right">{{number_format(($total_pre_dr + $total_cur_dr), 2)}}</td>
                          <td align="right">{{number_format(($total_pre_cr + $total_cur_cr), 2)}}</td>
                          <td align="right">{{number_format(($total_pre_dr + $total_cur_dr) - ($total_pre_cr + $total_cur_cr) , 2)}}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="page pull-right">
                   {{--{{dd($data)}}--}}
                   {{--@foreach($data)--}}
                   {{--@endforeach--}}

{{--                                       echo $result->appends([--}}
{{--                                           'code' => Input::get('code'),--}}
{{--                                           'start' => Input::get('start'),--}}
{{--                                           'end' => Input::get('end'),--}}
{{--                                           'company_branch_code' => Input::get('company_branch_code'),--}}
{{--                                           'cur' => Input::get('cur'),--}}
{{--                                           'offset' => Input::get('offset')--}}
{{--                                       ])->render();--}}
{{--                   --}}
{{--                                       ?>--}}
                </div>
            </section>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.floatThead.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>


<script>
  $("#export").click(function (event) {
        var con = confirm("Do you really want to export to CSV file?");
        if(con == true){
            new TableExport(document.getElementById('list_loan_id'), {
                formats: ['csv'],
                filename: 'list_loan'
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
    });
$(document).ready(function () {
    $(".sticky-header").floatThead({scrollingTop: 77});

    $('#start-date').datepicker({
        autoclose: true
    });
    $('#end-date').datepicker({
        autoclose: true
    });
    $('.custom-pagi a').on('click', function () {
        val = $(this).parent().find('input[name="set_offset"]').val();
        $('input[name="offset"]').val(val);
        $('#search_frm').submit();
        return false;
    });
});
</script>
@endsection
