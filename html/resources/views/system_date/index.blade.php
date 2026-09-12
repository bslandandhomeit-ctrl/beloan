@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('css/popup.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
<style type="text/css">
    .unit_status{
        background: #ababab;
        padding: 5px 10px;
        border-radius: 5px
    }
</style>
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('system_date.list_system_dates') }}</span>
        {{-- <span style="float: right"><a href="{{route ('add_unit') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('unit.add_unit') }}</a></span> --}}
    </header>

    <div class="panel-body">
        @if(Session::has('error'))
            <div class="alert alert-danger fade in">
                <button class="close close-sm" data-dismiss="alert">x</button>
                {{ Session::get('error') }}
            </div>
        @endif
        @if(Session::has('msg'))
            <div class="alert alert-success fade in">
                <button class="close close-sm" data-dismiss="alert">x</button>
                {{ Session::get('msg') }}
            </div>
        @endif
        <div class="position-center">
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('system_date') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="t_from">{{trans('teller.t_from')}}</label>
                            <input type="text" name="from_date" class="form-control" id="t_from" value="{{ $from_date }}"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="t_to">{{trans('teller.t_to')}}</label>
                            <input type="text" name="to_date" class="form-control" id="t_to" value="{{ $to_date }}"/>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <input type="hidden" name="offset" value="<?php echo $offset ?>"/>
                        <br>
                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                        {{-- <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button> --}}
                        {{-- <button class="btn btn-primary" type="submit" name="is_excel" value="1"><i class="fa fa-download"></i> {{ trans('report.xrpt_export') }}</button> --}}
                        {{-- <button class="btn btn-primary" type="submit" name="is_csv" value="1"><i class="fa fa-download"></i> {{ trans('report.rpt_export') }}</button> --}}
                        {{-- <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a> --}}
                        {{-- <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>     --}}
                    </div>
                </div>
            </form>
        </div>
        <div class="page">
            <div class="custom-pagi">
                <span class="pagi_label">Number of Rows:</span>
                <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                <a href="#" class="btn btn-danger">Go</a>
            </div>
        </div>
        <br/><br/><br/><br/>
        <div id="printArea" class="ox-scroll">
            @include('api.report_header')
            <h4 class="sch_title">{{ trans('sidebar.sb_dealer_summary') }}</h4>
            <section id="unseen" >
                <table class="table table-bordered table-striped table-condensed table-hover">
                    <thead class="th-center">
                    <th style="vertical-align:middle; text-align: center;white-space:nowrap;">{{ trans('system_date.no') }}</th>
                    <th style="vertical-align:middle; text-align: center;white-space:nowrap;">{{ trans('system_date.id') }}</th>
                    <th style="vertical-align:middle; text-align: center;white-space:nowrap;">{{ trans('system_date.previous_date') }}</th>
                    <th style="vertical-align:middle; text-align: center;white-space:nowrap;">{{ trans('system_date.current_date') }}</th>
                    <th style="vertical-align:middle; text-align: center;white-space:nowrap;">{{ trans('system_date.next_date') }}</th>
                    <th style="vertical-align:middle; text-align: center;white-space:nowrap;">{{ trans('system_date.is_accrued_interest') }}</th>
                    <th style="vertical-align:middle; text-align: center;white-space:nowrap;">{{ trans('system_date.is_auto_payment') }}</th>
                    <th style="vertical-align:middle; text-align: center;white-space:nowrap;">{{ trans('system_date.accrued_interest_by') }}</th>
                    <th style="vertical-align:middle; text-align: center;white-space:nowrap;">{{ trans('system_date.accrued_interest_date') }}</th>
                    <th style="vertical-align:middle; text-align: center;white-space:nowrap;">{{ trans('system_date.auto_payment_by') }}</th>
                    <th style="vertical-align:middle; text-align: center;white-space:nowrap;">{{ trans('system_date.auto_payment_date') }}</th>
                    </thead>
                    <tbody>
                        @forelse($rows as $key => $row)
                        <tr>
                            <td class="isVerticalalign" align="center">{{ ++$key}}</td>
                            <td class="isVerticalalign" align="center">{{ $row->id}}</td>
                            <td class="isVerticalalign" align="center">{{ date('d-m-Y',strtotime($row->previous_date)) }}</td>
                            <td class="isVerticalalign" align="center">{{ date('d-m-Y',strtotime($row->corrent_date)) }}</td>
                            <td class="isVerticalalign" align="center">{{ date('d-m-Y',strtotime($row->next_date)) }}</td>
                            <td class="isVerticalalign define-width" align="center">
                                @if($row->is_accrued_interest == 1)
                                    <a href="#" onclick="return()" class="btn btn-xs btn-default bg-green" style="cursor: not-allowed;"><i class="fa fa-check-circle"></i></a>
                                @else
                                    <a href="{{ route('system_date.accrued_interest', [$row->id])}}" onclick="return confirm('Are you sure you want to Accrued Interest?');" class="btn btn-xs btn-default bg-red"><i class="fa fa-times-circle"></i></a>
                                @endif
                            </td>
                            <td class="isVerticalalign define-width" align="center">
                                @if($row->is_auto_payment)
                                    <a href="#" onclick="return()" class="btn btn-xs btn-default bg-green" style="cursor: not-allowed;"><i class="fa fa-check-circle"></i></a>
                                @else
                                    <a href="{{ route('system_date.autopayment', [$row->id])}}" class="btn btn-xs btn-default bg-red" onclick="return confirm('Are you sure you want to Auto-Payment?');"><i class="fa fa-times-circle"></i></a>
                                @endif
                            </td>
                            <td class="isVerticalalign" align="center">{{ $row->accrued_interest_by }}</td>
                            <td class="isVerticalalign" align="center">{{ isset($row->accrued_interest_date)? date('d-m-Y',strtotime($row->accrued_interest_date)):'' }}</td>
                            <td class="isVerticalalign" align="center">{{ $row->auto_payment_by}}</td>
                            <td class="isVerticalalign" align="center">{{ isset($row->auto_payment_date)? date('d-m-Y',strtotime($row->auto_payment_date)):'' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>
        <div class="page">
            {!! str_replace('?page', '&page', $rows->appends(\Request::except('page'))->render()) !!}
        </div>
    </div>
</section>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.popup.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script> --}}
<script type="text/javascript">
$(document).ready(function () {
    $('#t_from, #t_to').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        setDate: new Date()
    });
    //pagination
    $('.custom-pagi a').on('click', function () {
    val = $(this).parent().find('input[name="set_offset"]').val();
            $('input[name="offset"]').val(val);
            $('#search_frm').submit();
            return false;
    });

    // $("#export").click(function (event) {
    //     var con = confirm("Do you really want to export to CSV file?");
    //     if(con == true){
    //         new TableExport(document.getElementById('dealers_list'), {
    //             formats: ['csv'],
    //             filename:'dealers_list'
    //         });
    //         $('button.csv').hide().click();
    //         $('.tableexport-caption').remove();
    //     }
    // });

    // $("#xexport").click(function (event) {
    //     var con = confirm("Do you really want to export to Excel file?");
    //     if(con == true){
    //         new TableExport(document.getElementById('dealers_list'), {
    //                 formats: ['xlsx'],
    //                 filename: 'dealers_list'
    //             }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    //             $('button.xlsx').hide().click();
    //             $('.tableexport-caption').remove();
    //     }
    // });
});
</script>
@endsection
