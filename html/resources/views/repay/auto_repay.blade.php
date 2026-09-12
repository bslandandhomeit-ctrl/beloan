@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
@endsection
<style type="text/css">
    
#loading{
      padding 2rem 4rem
      font-family monospace
      font-weight bold
      color hsl(0, 0%, 100%)
      border-style solid
      border-width 1vmin
      font-size 2rem
      --charge 'hsl(%s, 80%, 50%)' % var(--h, 0)
      border-image conic-gradient(var(--charge) var(--a), transparent calc(var(--a) + 0.5deg)) 30
      animation load 2s infinite ease-in-out
}

    @keyframes load
      0%, 10%
        --a 0deg
        --h 0
      100%
        --a 360deg
        --h 100

</style>
<form role="form" method="get" action="{{ route('auto_payment') }}" id="search_frm">
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
                            <th rowspan="2" style="text-align: center">{{ trans('account.a_customer_reference') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.drawdown_acc') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.balance') }}</th>
                            <th rowspan="2" style="text-align: center">{{ trans('account.type') }}</th>

                            <th rowspan="1" colspan="8" style="text-align: center">{{ trans('multiple.schedule') }}</th>
                            <th rowspan="1" colspan="7" style="text-align: center">{{ trans('multiple.actual') }}</th>
                        </tr>
                        <tr class="data-center header">
                            <th style="text-align: center">{{ trans('multiple.month_idx') }}</th>
                            <th style="text-align: center">{{ trans('multiple.schedule_date') }}</th>
                            <th style="text-align: center">{{ trans('multiple.interest') }}</th>
                            <th style="text-align: center">{{ trans('multiple.fee') }}</th>
                            <th style="text-align: center">{{ trans('multiple.other_fee') }}</th>
                            <th style="text-align: center">{{ trans('multiple.penalty') }}</th>
                            <th style="text-align: center">{{ trans('multiple.principal') }}</th>
                            <th style="text-align: center">{{ trans('multiple.total') }}</th>
                            <th style="text-align: center">{{ trans('multiple.interest') }}</th>
                            <th style="text-align: center">{{ trans('multiple.fee') }}</th>
							<th style="text-align: center">{{ trans('multiple.other_fee') }}</th>
                            <th style="text-align: center">{{ trans('multiple.penalty') }}</th>
                            <th style="text-align: center">{{ trans('multiple.principal') }}</th>
                            <th style="text-align: center">{{ trans('multiple.total') }}</th>
                            <th style="text-align: center">{{ trans('multiple.balance') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $n = 1;
                        $current_date = !empty($verify_date) ? $verify_date :  date('Y-m-d');
                        $day = 0;
                        ?>
                        @foreach($result as $r)
                        <?php
                        // find last accrued date
                        /*
                        $entry_date = getLastAccruedDate($l->journal_detail_air, $l->disburse_date);
                        $balance = 0;
                        $day = date_dif($entry_date, $current_date ,1, false);
                        if($day <=0) continue;
                        $p_debit = 0; $p_credit = 0; $p_balance = 0;
                        foreach($l->journal_detail_air as $air){
                            $p_debit += floatval($air->debit);
                            $p_credit += floatval($air->credit);
                        }
                        $p_balance = $p_debit - $p_credit;
                        $air_amount = round($l->balance * $day * $l->interest_rate * 12 / 36000,2);
*/
                        $i = 0; 
                        ?>
                        <tr>
                            <td style="text-align: center">{{$n}}</td>
                            <td style="text-align: center">{{$r["name"]}}</td>
                            <td align="center"> 
                              <a style="text-decoration: underline" href="{{ route('loan_detail', [$r['l_id']])}}">{{ $r["reference"] }}
                            </td>
                            <td style="text-align: center">{{$r["account_no"]}}</td>
                            <td style="text-align: right;font-weight:bold">{{number_format($r["balance"],2)}}</td>
                            <td style="text-align: center">{{ ucfirst($r["type"]) }}</td>
                            @foreach($r["repay"] as $pay)
                            @if($i >= 1) 
                                <tr rowspan="2"><td colspan="6"></td> 
                            @endif
                                <td style="text-align: center">{{$pay["month_idx"]}}</td>
                                <td style="text-align: center">{{$pay["schedule_date"]}}</td>
                                <td style="text-align: right">{{number_format($pay["interest"],2)}}</td>
                                <td style="text-align: right">{{number_format($pay["fee"],2)}}</td>
    							<td style="text-align: right">{{number_format($pay["other_fee"],2)}}</td>
                                <td style="text-align: right">{{number_format($pay["penalty"],2)}}</td>
                                <td style="text-align: right">{{number_format($pay["principal"],2)}}</td>
                                <td style="text-align: right; font-weight:bold">{{number_format($pay["total"],2)}}</td>
                                <td style="text-align: right">{{number_format($pay["act_interest"],2)}}</td>
                                <td style="text-align: right">{{number_format($pay["act_fee"],2)}}</td>
    							<td style="text-align: right">{{number_format($pay["act_other_fee"],2)}}</td>
                                <td style="text-align: right">{{number_format($pay["act_penalty"],2)}}</td>
                                <td style="text-align: right">{{number_format($pay["act_principal"],2)}}</td>
                                <td style="text-align: right; font-weight:bold">{{number_format($pay["act_total"],2)}}</td>
                                <td style="text-align: right; font-weight:bold">{{number_format($pay["balance"],2)}}</td>
                            @if($i >= 1) 
                                </tr> 
                            @endif
                            <?php $i++;?> 
                            @endforeach
                        </tr>
                        <?php $n++; ?>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center">
            {{-- <a href="{{route('auto_payment')}}?flag=1&date={{$current_date}}"  class="btn btn-success">{{ trans('multiple.m_save') }}</a> --}}
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
            flag = 1;
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
        if(flag == 1){
            postAuto_repay();
        }

    }
    function postAuto_repay(){
        $.ajax({
            url:"{{ route('auto_payment_ajax') }}",
            method:'get',
            dataType:'json',
            data:{
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
