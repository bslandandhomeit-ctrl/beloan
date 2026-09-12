@extends('layouts.app')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
<style>
    table tr td:not(:first-child) {
        text-align: right;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('loans.apply_loan') }}
        </header>
        <div class="panel-body">
            <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('apply_loan') }}">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">{{ trans('customer.cus_info')}}<span class="red-color">*</span></label>
                                <input id="searchterm" name="searchterm" class="form-control" value="{{ $searchterm }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" style="margin-top: 33px;text-align: right;">
                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('account.run') }}</button>
                                <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('account.print') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <div class="col-sm-12">
                <div class="row">
                  @if(!empty($search_results) && count($search_results) > 0)
                    <div class="row"  id="printArea">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-striped table-condensed income_statement_is acHis" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>{{ trans('account.no') }}</th>
                                        <th>{{ trans('customer_acc.account_name') }}</th>
                                        <th>{{ trans('customer.cus_customer_name') }}</th>
                                        <th>{{ trans('customer.cus_customer_id') }}</th>
                                        <th>{{ trans('unit.unit_code') }}</th>
                                        <th>{{ trans('multiple.m_address')}}</th>
                                        <th>{{ trans('multiple.m_phone',['num'=>""]) }}</th>
                                    </tr>
                                </thead>
                                <tbody id="result">
                                    @if(!empty($search_results) && count($search_results) > 0)
                                        <?php $no = 1;?>
                                        @foreach($search_results as $r)
                                            {{--<a href="{{route('loan_add',[$r->client_id,$r->id])}}">--}}
                                                <tr>
                                                    <td style="text-align: center;">{{$no}}</td>
                                                    <td style="text-align: center;"><a href="{{route('loan_add',[$r->client_id,$r->id])}}">{{$r->account_no}}</a></td>
                                                    <td style="text-align: center;">{{$r->account_name}}</td>
                                                    <td style="text-align: center;">{{$r->cus_acc}}</td>
                                                    <td style="text-align: center;">{{ $r->code }}</td>
                                                    <td style="text-align: left;">{{$r->address}}</td>
                                                    <td style="text-align: center;">{{$r->phone1}}{{ !empty($r->phone2) ? ' / '.$r->phone2:''}}</td>
                                                </tr>
                                            {{--</a>--}}
                                            <?php $no = $no + 1;?>
                                        @endforeach
                                    @else
                                        <tr><td colspan="6">{{ trans('multiple.m_no_result') }}</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div id="loading" style="display: none"></div>
                        <div class="prepare">
                            <span style="text-align: left">Prepared by : </span>
                            <span style="margin-left: 150px">Verified by : </span>
                            <span style="margin-left: 150px">Approved by : </span>
                        </div>
                    </div>
                  @endif
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript">

</script>
@endsection