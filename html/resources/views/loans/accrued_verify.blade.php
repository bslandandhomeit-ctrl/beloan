@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
@endsection

<form role="form" method="get" action="{{ route('accrued_verify') }}" id="search_frm">
    <input type="hidden" name="offset" />
    <input type="hidden" name="date" />
</form>

@section('content')
<section class="panel">
    <header class="panel-heading header-title">
        {{ trans('customer.a_acc_ver') }}
    </header>
    <form class="form-horizontal"  id="search_frm">
        <input type="hidden" name="offset" />
        <div class="page">
            <div class="row">
                <div class="col-sm-8">
                    <div class="custom-date pull-right">
                        <div class="form-group" style="padding-top: 15px;">
                            <input type="text" value="<?php if($verify_date){echo $verify_date;}?>" class="form-control" name="verify_date" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="custom-pagi">
                        <span class="pagi_label">{{ trans('sidebar.sb_number_of_rows') }}</span>
                        <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                        <a href="#" class="btn btn-danger">Go</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="panel-body">

        <div id="printArea">
            <div id="divTab">
                <table class="table table-bordered table-striped table-condensed sticky-header">
                    <thead style="background-color: #ffffff">
                        <tr class="data-center header">
                            <th rowspan="2" style="text-align: center">{{ trans('multiple.m_no') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('customer.cus_customer_name') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.a_customer_account_num') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.a_customer_reference') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('customer.a_last_acc_date') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('multiple.m_due_date') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('customer.a_day_diff') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('report.rpt_outstanding_balance') }}</th>
                            <th rowspan="2" colspan="1" style="text-align: center">{{ trans('loan.l_interest_rate') }}</th>
                            <th rowspan="2" colspan="1" style="text-align: center">{{ trans('customer.a_due_amount') }}</th>
                            <th rowspan="1" colspan="2" style="text-align: center">{{ trans('report.rpt_previous_balance') }}</th>
                            <th colspan="2" style="text-align: center">{{ trans('report.rpt_transaction') }}</th>
                            <th rowspan="1" colspan="2" style="text-align: center">{{ trans('account.a_bal') }}</th>
                        </tr>
                        <tr class="data-center header">
                            <th style="text-align: center">{{ trans('report.rpt_debit') }}</th>
                            <th style="text-align: center">{{ trans('report.rpt_credit') }}</th>
                            <th style="text-align: center">{{ trans('report.rpt_debit') }}</th>
                            <th style="text-align: center">{{ trans('report.rpt_credit') }}</th>
                            <th style="text-align: center">{{ trans('report.rpt_debit') }}</th>
                            <th style="text-align: center">{{ trans('report.rpt_credit') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $n = $pagi;
                        $current_date = !empty($verify_date) ? $verify_date :  date('Y-m-d');
                        $day = 0;
                        ?>
                        @foreach($loans as $l)
                        <?php
                        // find last accrued date
                        $entry_date = getLastAccruedDate($l->journal_detail_air, $l->disburse_date, $l->transfer_date);
                        //$entry_date = getLastAccruedDate($l->journal_detail_air, $l->start_payment_date, $l->transfer_date);
                        //dd($entry_date);
                        //dd('ddd');
                        //dd(date('Y-M-d', strtotime($entry_date)));
                        $balance = 0;
                        $day = date_dif($entry_date, $current_date ,1, false);
                        if($day <=0) continue;
                        $p_debit = 0; $p_credit = 0; $p_balance = 0;
                        foreach($l->journal_detail_air as $air){
                            $p_debit += floatval($air->debit);
                            $p_credit += floatval($air->credit);
                        }
                        $p_balance = $p_debit - $p_credit;
                        $c_balance = ($l->rate_type == "Flat")? $l->original_amount : $l->balance;
                        $air_amount = round($c_balance * $day * $l->interest_rate * 12 / 36000,2);

                        ?>
                        <tr>
                            <td style="text-align: center">{{ $n }}</td>
                            <td style="text-align: center">{{ $l->account_name }}</td>
                            <td style="text-align: center">{{ $l->account_no }}</td>
                            <td style="text-align: center">{{ $l->contract_id }}</td>
                            <td style="text-align: center">{{ date('Y-M-d', strtotime($entry_date)) }}</td>
                            <td style="text-align: center">{{ date('Y-M-d', strtotime($current_date)) }}</td>
                            <td style="text-align: center">{{ $day }}</td>
                            <td style="text-align: right">{{ number_format($l->balance,2,'.',',') }}</td>
                            <td style="text-align: center">{{ number_format($l->interest_rate,2) }}%</td>
                            <td style="text-align: right">{{ number_format($air_amount, 2) }} </td>
                            <td style="text-align: right">
                                <?php
                                    //!is_null($l->journal_detail_air[0]->b_debit) ? $p_debit = $l->journal_detail_air[0]->b_debit : $p_debit = 0;
                                    echo number_format($p_debit, 2);
                                ?>
                            </td>
                            <td style="text-align: right">
                                <?php
                                    //!is_null($l->journal_detail_air[0]->b_credit) ?  $p_credit = $l->journal_detail_air[0]->b_credit : $p_credit = 0;
                                    echo number_format($p_credit, 2);
                                ?>
                            </td>

                            <td style="text-align: right">
                                <?php
                                $debit = $debit_[] = $air_amount;
                                echo number_format($debit, 2);
                                ?>
                            </td>
                            <td style="text-align: right">
                                <?php $credit = 0.0; echo number_format($credit,2);?>
                            </td>
                            <td style="text-align: right">
                                <?php
                                 $b_debit = $p_debit + $debit;
                                 echo number_format($b_debit, 2);
                                ?>
                            </td>
                            <td>
                                <?php
                                 $b_credit = $p_credit + $credit;
                                 echo number_format($b_credit, 2);
                                ?>
                            </td>
                        </tr>
                        <?php $n++; ?>
                        @endforeach

                        <tr class="bolder">
                            <td colspan="12" align="right">{{ trans('report.rpt_total') }}</td>

                            <td style="text-align: right"><?php echo number_format(array_sum($debit_), 2) ?></td>
                            <td colspan="3"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="page">
            <?php
            echo $loans->appends([
                'sort' => 'loans.id',
                'offset' => Input::get('offset')
            ])->render();
            ?>
        </div>

        <div class="text-center">
            {{-- <a href="{{route('list_loan')}}?flag=2&date={{$current_date}}" class="btn btn-success">{{ trans('multiple.m_save') }}</a> --}}
            <button class="btn btn-success" id="btn_submit_autorepay"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
            <a href="{{route ('list_administration') }}" class="btn btn-info">{{ trans('multiple.m_cancel') }}</a>
            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
        </div>

    </div>
</section>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script>
    var flag = "{{ $flag }}";
    var page = "{{ $page }}";
    var date = "{{ $date }}";
    $(document).ready(function () {
        callAutoRepay(flag);
        // postAuto_repay();

        $('#btn_submit_autorepay').on('click', function () {
            flag = 2;
            callAutoRepay(flag);
        });
    });
    //  $(document).ajaxStart(function () {
    //     $('<div id="loading"></div>').appendTo('body');
    //     imgLoading(true, 'Loading....',500,'warning');
    //     $('#btn_submit_autorepay').disable(true);
    // }).ajaxStop(function () {
    //     setTimeout(function () { $('#loading').fadeOut(); }, 50);
    // });
    function callAutoRepay(flag){
        if(flag == 2){
            postAuto_repay();
        }

    }
    function postAuto_repay(){
        $.ajax({
            url:"{{ route('list_loan_accrued') }}",
            method:'get',
            dataType:'json',
            data:{
                'sort':'loans.id',
                'flag':flag,
                'page':page,
                'date':date,
            },
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            },
            success : function(data) {
                if(data.success == 1){
                    console.log(data.url);
                    // window.location.href = 'http://google.com';
                    window.location.href = data.url;
                    // window.location.reload();
                    // console.log(data.url)
                    // location.reload();
                }
                // console.log(data);
                // location.reload();
            }
        });
    }

</script>
<script type="text/javascript">
$(document).ready(function () {
    $('.custom-pagi a').on('click', function () {
        val = $(this).parent().find('input[name="set_offset"]').val();
        $('input[name="offset"]').val(val);
        var d = $('#verify_date').val();
        $('input[name="date"]').val(d);
        $('#search_frm').submit();
        return false;
    });
        $('.verify_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });

        $('#verify_date').on('change', function () {
            val = $(this).parent().find('input[name="set_offset"]').val();
            $('input[name="offset"]').val(val);
            var d = $('#verify_date').val();
            $('input[name="date"]').val(d);
            $('#search_frm').submit();
            return false;
        });
});
</script>
@endsection
