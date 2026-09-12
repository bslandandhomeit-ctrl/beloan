@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <style>
        table tr th{
            text-align:center;
        }
        table tr td:not(.no){
            text-align:right;
        }
    </style>
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('sidebar.sb_cash_deposit') }}
            @if(isset($start) && isset($end))
                {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
            @else
                {{ isset($start)?'Report on'.date("d-M-Y", strtotime($start)):'' }}
                {{ isset($end)?'Report on '.date("d-M-Y", strtotime($end)):'' }}
            @endif
        </header>
        <div class="panel-body">
            <div class="position-center" style="width:100%;">
                <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('rpt_cash_deposit') }}">
                    <div class="row">
                        <label class="control-label col-md-1">{{ trans('multiple.m_start_date') }}</label>
                        <div class="col-md-3">
                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpStart">
                                <input type="text" name="dpStart" size="16" class="form-control" value="{{ isset($start)?$start:old('dpStart') }}">
                                <span class="add-on birhtdateDatepicker ptl-3">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                        <label class="control-label col-md-1">{{ trans('multiple.m_end_date') }}</label>
                        <div class="col-md-3">
                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpEnd">
                                <input type="text" name="dpEnd" size="16" class="form-control" value="{{ isset($end)?$end:old('dpEnd') }}">
                                <span class="add-on birhtdateDatepicker ptl-3">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div><br/>
                    <div class="row">
                        <div class="col-md-offset-4 col-md-5">
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <br/><br/><br/>
            <div id="printArea">
                @include('api.report_header')
                <h4 class="sch_title">
                    {{ trans('sidebar.sb_cash_deposit') }}
                    @if(isset($start) && isset($end))
                        {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
                    @else
                        {{ isset($start)?'Report on'.date("d-M-Y", strtotime($start)):'' }}
                        {{ isset($end)?'Report on '.date("d-M-Y", strtotime($end)):'' }}
                    @endif
                </h4>
                <section id="unseen">
                    <table class="table table-bordered table-striped table-condensed cash_deposit" style="width:100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th colspan="10">{{ trans('report.rpt_cash_deposit_to_raksmey') }}</th>
                            </tr>
                            <tr>
                                <th>{{ trans('multiple.m_no') }}</th>
                                <th>{{ trans('report.rpt_date') }}</th>
                                <th>{{ trans('report.rpt_cash_in') }}</th>
                                <th>{{ trans('report.rpt_principal') }}</th>
                                <th>{{ trans('report.rpt_interest') }}</th>
                                <th>{{ trans('report.rpt_penalty') }}</th>
                                <th>{{ trans('report.rpt_others') }}</th>
                                <th>{{ trans('report.rpt_difference') }}</th>
                                <th colspan="2">{{ trans('report.rpt_memo') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @var $i= 1
                            @var $row = array_fill(0,6,0.0)
                            @if(empty($transactions)))
                                No results
                            @else
                                @foreach($transactions as $tr)
                                    <tr>
                                        <td class="no">{{ $i }}</td>
                                        <td class="no">{{ $tr->trans_date }}</td>
                                        <td>
                                            @var $total = 0.0
                                            @foreach($tr->journal as $jr)
                                                @foreach($jr->detail as $d)
                                                    @var $total += $d->debit
                                                @endforeach
                                            @endforeach
                                            {{ number_format($total,2,'.',',') }}
                                        </td>
                                        <td>{{ number_format($tr->principal,2,'.',',') }}</td>
                                        <td>{{ number_format($tr->interest,2,'.',',') }}</td>
                                        <td>{{ number_format($tr->penalty,2,'.',',') }}</td>
                                        <td>{{ number_format(($tr->fee + $tr->overpaid_amount),2,'.',',') }}</td>
                                        @var $t = $total-($tr->principal + $tr->interest + $tr->penalty + $tr->fee + $tr->overpaid_amount);
                                        <td>{{ number_format($t,2,'.',',') }}</td>
                                        <td class="no">{{ $tr->loan->contract_id }}</td>
                                        <td class="no">{{ $tr->description }}</td>

                                        @var $row[0] += $total
                                        @var $row[1] += $tr->principal
                                        @var $row[2] += $tr->interest
                                        @var $row[3] += $tr->penalty
                                        @var $row[4] += $tr->fee
                                        @var $row[5] += $t
                                    </tr>
                                    @var $i+=1
                                @endforeach
                                <tr class="t-bold">
                                    <td colspan="2">{{ trans('report.rpt_total') }}</td>
                                    @var $total_val = 0.0
                                    @foreach($row as $key=>$value)
                                        @if($key!=0)
                                            @var $total_val += $value
                                        @endif
                                        <td>{{ number_format($value,2,'.',',') }}</td>
                                    @endforeach
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="t-bold">{{ number_format(($row[0] - $total_val),2,'.',',') }}</td>
                                </tr>
                            @endif
                       </tbody>
                    </table>
                </section>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
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
        });

        Number.prototype.formatMoney = function(c, d=".", t=","){
            var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
           return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
         };
    </script>
@endsection