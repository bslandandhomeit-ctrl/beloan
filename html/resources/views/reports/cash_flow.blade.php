@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
             {{ trans('report.rpt_cash_on_hand') }}
             @if(isset($start) && isset($end))
                 {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
             @else
                 {{ isset($start)?'Cash On Hand '.date("d-M-Y", strtotime($start)):'' }}
                 {{ isset($end)?'Cash On Hand '.date("d-M-Y", strtotime($end)):'' }}
             @endif
        </header>
        <div class="panel-body">
            <div class="position-center">
                <form role="form" method="get" action="{{ route('rpt_cash_flow') }}">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label col-lg-4">{{ trans('multiple.m_start_date') }}</label>
                                <div class="col-lg-7">
                                    <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" class="input-append date start">
                                        <input type="text" name="start" size="16" class="form-control" value="{{ $start }}">
                                            <span class="add-on birhtdateDatepicker ptl-3">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                          </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label col-lg-4">{{ trans('multiple.m_end_date') }}</label>
                                <div class="col-lg-7">
                                    <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" class="input-append date end">
                                        <input type="text" name="end" size="16" class="form-control" value="{{ $end }}">
                                            <span class="add-on birhtdateDatepicker ptl-3">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                          </span>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <div class="col-lg-offset-4 col-lg-7">
                                    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                                    <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <br><br>
            <div id="printArea">
                @include('api.report_header')
                <h4 class="sch_title">{{ trans('report.rpt_cash_on_hand') }}
                    @if(isset($start) && isset($end))
                        {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
                    @else
                        {{ isset($start)?'Cash On Hand '.date("d-M-Y", strtotime($start)):'' }}
                        {{ isset($end)?'Cash On Hand '.date("d-M-Y", strtotime($end)):'' }}
                    @endif
                </h4>
                <section id="unseen" style="clear: both">
                    <table class="table table-bordered table-striped table-condensed balance_sheet">
                        <thead>
                            <tr>
                                <th>{{ trans('multiple.m_no') }}</th>
                                <th>{{ trans('report.rpt_date') }}</th>
                                <th>{{ trans('report.rpt_cash_in') }}</th>
                                <th>{{ trans('report.rpt_cash_out') }}</th>
                                <th>{{ trans('report.rpt_total') }}</th>
                                <th>{{ trans('report.rpt_contract_id') }}</th>
                                <th>{{ trans('multiple.m_description') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @var $i = 1
                            @if(!empty($requiry) && count($requiry) > 0)
                                @var $temp = 0.0
                                @var $total = 0.0
                                @forelse($requiry as $r)
                                    @if(!empty($r->transaction))
                                    @foreach($r->detail as $d)
                                        @var $debit = $d->debit
                                        @var $credit = $d->credit
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $r->entry_date }}</td>
                                            <td>{{ number_format($debit,2,'.',',') }}</td>
                                            <td>{{ number_format($credit,2,'.',',') }}</td>
                                            @var $total = ($d->debit + $temp) - $d->credit
                                            <td>{{ number_format($total,2,'.',',') }}</td>
                                            @var $temp = $total
                                            <td>{{ $r->transaction->loan->contract_id }}</td>
                                            <td>{{ $r->description }}</td>
                                        </tr>
                                    @endforeach
                                    @endif
                                @empty
                                    <tr><td colspan=7>{{ trans('multiple.m_no_result') }}</td></tr>
                                @endforelse
                                <tr style="font-weight: bold">
                                    <td colspan="4">{{ trans('report.rpt_total') }}</td>
                                    <td>{{ number_format($total,2,'.',',') }}</td>
                                </tr>
                            @else
                                <tr><td colspan=7>{{ trans('multiple.m_no_result') }}</td></tr>
                            @endif
                        </tbody>
                    </table>
                    <div>
                        @include('partials.pagination',['results'=> $requiry])
                    </div>
                </section>
            </div>
        </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
        <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
        <script type="text/javascript">
        $('.start').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
        $('.end').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
        </script>
@endsection