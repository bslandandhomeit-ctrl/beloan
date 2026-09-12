@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('css/popup.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('theme/select2_v4.1.0/select2.min.css',isset($secure) ? false : false) }}"/>
<link rel="" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css', isset($secure) ? false : false)}}"/>
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
        <span>{{ trans('unit.list_unit') }}</span>
        <span style="float: right"><a href="{{route ('add_unit') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('unit.add_unit') }}</a></span>
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
        <div class="position-centerd">
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('list_unit') }}">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="col-lg-3">  
                            <label for="project" class="control-label">Project</label>                                          
                            <select id="project_id" name="project_id" class="form-control">
                                <option value="">Select Project</option>
                                @foreach ($projects as $vals)
                                    <option value="{{ $vals->id }}" {{ ($vals->id == Request::get('project_id'))?'selected' : "" }}>{{ $vals->dealer.' - '.$vals->short_code }}</option>
                                @endforeach
                            </select>                                            
                        </div>
                        <div class="col-lg-3">
                            <label for="unit_code" class="control-label">{{ trans('unit.unit_code') }}</label>
                                <input type="text" class="form-control" id="unit_code" value="{{$search}}" name="search">
                        </div>
                        
                    <div class="col-lg-6 text-right">
                    <label for="project" class="control-label"></label>   
                        <input type="hidden" name="offset" value="<?php echo $offset ?>"/>
                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                        <button class="btn btn-primary" type="submit" name="is_excel" value="1"><i class="fa fa-download"></i> {{ trans('report.xrpt_export') }}</button>
                        <button class="btn btn-primary" type="submit" name="is_csv" value="1"><i class="fa fa-download"></i> {{ trans('report.rpt_export') }}</button>
                        {{-- <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a> --}}
                        {{-- <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>     --}}
                    </div>
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
                    <th style="vertical-align:middle; text-align: center;">{{ trans('representative.id') }}</th>
                    <th style="vertical-align:middle; text-align: center;">Project</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit.unit_type') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit.zone') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit.unit_code') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit.price') }}</th>
                    <th style="vertical-align:middle; text-align: center;">Discount</th>
                    <th style="vertical-align:middle; text-align: center;">Net Selling Price</th>
                    <th style="vertical-align:middle; text-align: center;">Contract Price</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit.street') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit.street_corner') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit.street_size') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit.floor') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit.unit_status') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.status') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_action') }}</th>
                    </thead>
                    <tbody>
                        @forelse($lists as $unit)
                        <tr>
                            <td class="isVerticalalign" align="center">{{ $unit->id}}</td>
                            <td class="isVerticalalign" align="center">{{ isset($unit->project)?$unit->project:'N/A' }}</td>
                            <td class="isVerticalalign" align="center">{{ isset($unit->unit_type_name)?$unit->unit_type_name:'N/A' }}</td>
                            <td class="isVerticalalign" align="center">{{ $unit->zone_id }}</td>
                            <td class="isVerticalalign" align="center">{{ $unit->code}}</td>
                            <td class="isVerticalalign" align="center">{{ $unit->price}}</td>
                            <td class="isVerticalalign" align="center">{{ $unit->discount_amount?$unit->discount_amount:''}}</td>
                            <td class="isVerticalalign" align="center">{{ $unit->discount_amount?number_format(($unit->price - $unit->discount_amount),2,'.',''):$unit->price}}</td>
                            <td class="isVerticalalign" align="center">{{ $unit->unit_sale_price?$unit->unit_sale_price:''}}</td>
                            <td class="isVerticalalign" align="center">{{ $unit->street}}</td>
                            <td class="isVerticalalign" align="center">{{ $unit->street_corner}}</td>
                            <td class="isVerticalalign" align="center">{{ $unit->street_size }}</td>
                            <td class="isVerticalalign" align="center">{{ $unit->floor }}</td>
                            <td class="isVerticalalign" align="center"><span class="unit_status" style="color: {{ $unit_status_color[$unit->status] }}">{{ ucfirst($unit->status) }}</span></td>
                            <td class="isVerticalalign" align="center">
                                {{ $unit->active? "Active":"Inactive" }}
                            </td>
                            <td class="isVerticalalign define-width" align="center">
                                <a href="{{ route('unit_detail',[$unit->id]) }}" class="btn btn-xs btn-default" title="Detail"><i class="fa fa-search-minus"></i></a>
                                <a href="{{ route('edit_unit', [$unit->id])}}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i></a>
                                @if($unit->active ==0)
                                <a href="{{route('unit_Enable', [$unit->id])}}" class="btn btn-xs btn-default" title="Activate"><i class="fa fa-check-circle"></i></a>
                                @else
                                <a href="{{route('unit_Disable', [$unit->id])}}" class="btn btn-xs btn-default" title="Inactivate"><i class="fa fa-times-circle"></i></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>
        <div class="page">
            {!! str_replace('?page', '&page', $lists->appends(\Request::except('page'))->render()) !!}
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
{{-- <script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script> --}}
<script type="text/javascript" src="{{ asset('theme/select2_v4.1.0/select2.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $("#project_id").select2();
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
