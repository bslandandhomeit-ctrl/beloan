@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('css/popup.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('unit_status.list_unit_status') }}</span>
        <span style="float: right"><a href="{{route ('add_unit_status') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('unit_status.add_unit_status') }}</a></span>
    </header>
    <div class="panel-body">
        <div class="position-center">
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('list_unit_status') }}">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name" class="col-lg-4 control-label">{{ trans('unit_status.status') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="status" value="{{$status}}" name="status">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 text-right">
                        <input type="hidden" name="offset" />
                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                        <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                        <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a> 
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
                <table class="ddd table table-bordered table-striped table-condensed table-hover dealerTable" id="dealers_list">
                    <thead class="th-center">
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.id') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit_status.status') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit_status.description') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_status') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_action') }}</th>
                    </thead>
                    <tbody>
                        @forelse($lists as $type)
                            <tr>
                                <td class="isVerticalalign" align="center">{{ $type->id}}</td>
                                <td class="isVerticalalign">{{ $type->status}}</td>
                                <td class="isVerticalalign">{{ $type->descrition }}</td>
                                <td class="isVerticalalign" align="center">
                                    {{ $type->active? "Active":"Inactive" }}
                                </td>
                                <td class="isVerticalalign define-width" align="center">
                                    <a href="{{ route('unit_status_detail',[$type->id]) }}" class="btn btn-xs btn-default" title="Detail"><i class="fa fa-search-minus"></i></a>
                                    <a href="{{ route('edit_unit_status', [$type->id])}}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i></a>
                                    @if($type->active ==0)
                                    <a href="{{route('enable_unit_status', [$type->id])}}" class="btn btn-xs btn-default" title="Activate"><i class="fa fa-check-circle"></i></a>
                                    @else
                                    <a href="{{route('disable_unit_status', [$type->id])}}" class="btn btn-xs btn-default" title="Inactivate"><i class="fa fa-times-circle"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>
        <div class="page">
            <?PHP
            // echo $lists->appends([
            //     'name' => Input::get('name'),
            //     'phone' => Input::get('phone'),
            //     'offset' => Input::get('offset')
            // ])->render();
            ?>
        </div>
    </div>
</section>
@endsection


@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.popup.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript">
$(document).ready(function () {
    //pagination
    $('.custom-pagi a').on('click', function () {
    val = $(this).parent().find('input[name="set_offset"]').val();
            $('input[name="offset"]').val(val);
            $('#search_frm').submit();
            return false;
    });

    $("#export").click(function (event) {
        var con = confirm("Do you really want to export to CSV file?");
        if(con == true){
            new TableExport(document.getElementById('dealers_list'), {
                formats: ['csv'],
                filename:'dealers_list'
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
    });

    $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('dealers_list'), {
                    formats: ['xlsx'],
                    filename: 'dealers_list'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });
});
</script>
@endsection
