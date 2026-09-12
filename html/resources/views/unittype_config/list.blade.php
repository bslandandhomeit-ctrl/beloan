@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('css/popup.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('unittype_config.list_unit_type_config') }}</span>
        <span style="float: right"><a href="{{ route('add_unittype_config') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('unittype_config.add_unit_type_config') }}</a></span>
    </header>
    <div class="panel-body">
        <div class="position-left">
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('list_unittype_config') }}">
                <div class="row">
                    <div class="col-sm-3">
                        <label class="control-label">{{ trans('unit.unit_type') }}</label>
                        <div class="input-group">
                            <select id="unit_type_id" style="width: 100%" name="unit_type_id">
                                <option value=""> - </option>
                                @foreach($unit_type as $unit_types)
                                    <?php 
                                        $sel = '';
                                        if($unit_type_id == $unit_types->id){
                                            $sel = 'selected';
                                        }
                                    ?>
                                    <option value="{{ $unit_types->id }}" {{ $sel }}>{{ $unit_types->name }} ({{ isset($unit_types->Projects->dealer)?$unit_types->Projects->dealer:''}} - {{  isset($unit_types->Projects->short_code)?$unit_types->Projects->short_code:'' }} )</option>
                                @endforeach
                            </select>
                            <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-3">
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
                <table class="table table-bordered table-striped table-condensed table-hover">
                    <thead class="th-center">
                        <tr>
                            <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.id') }}</th>
                            <th style="vertical-align:middle; text-align: center;">{{ trans('unit.unit_type') }}</th>
                            <th style="vertical-align: middle;text-align: center;">{{ trans('report.rpt_loan_type') }}</th>
                            <th style="vertical-align: middle;text-align: center;">{{ trans('loan.l_penalty_rate_type') }}</th>
                            <th style="vertical-align:middle; text-align: center;">{{ trans('loan.l_repayment_type') }}</th>
                            <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_status') }}</th>
                            <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lists as $d)
                        <?php
                            $unit_types = $d->UnitType;
                        ?>
                            <tr>
                                <td class="isVerticalalign" align="center">{{ $d->id}}</td>
                                <td class="isVerticalalign">{{ $unit_types->name }} ({{ isset($unit_types->Projects->dealer)?$unit_types->Projects->dealer:''}} - {{  isset($unit_types->Projects->short_code)?$unit_types->Projects->short_code:'' }} )</td>
                                <td class="isVerticalalign">{{ isset($d->product_type->products_type_name)?$d->product_type->products_type_name:'' }}</td>
                                <td class="isVerticalalign">{{ isset($static['penalty_rate_type'][$d->penalty_rate_type])?$static['penalty_rate_type'][$d->penalty_rate_type]:'' }}</td><td class="isVerticalalign">{{ isset($static['repayment_type'][$d->repayment_type])?$static['repayment_type'][$d->repayment_type]:'' }}</td>
                                <td class="isVerticalalign" align="center">
                                    {{ $d->active? "Active":"Inactive" }}
                                </td>
                                <td class="isVerticalalign define-width" align="center">
                                    <!-- <a href="{{ route('unittype_detail_config',[$d->id]) }}" class="btn btn-xs btn-default" title="Detail"><i class="fa fa-search-minus"></i></a> -->
                                    <a href="{{ route('edit_unittype_config', [$d->id])}}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i></a>
                                    @if($d->active ==0)
                                    <a href="{{route('unit_typeEnable_config', [$d->id])}}" class="btn btn-xs btn-default" title="Activate"><i class="fa fa-check-circle"></i></a>
                                    @else
                                    <a href="{{route('unit_typeDisable_config', [$d->id])}}" class="btn btn-xs btn-default" title="Inactivate"><i class="fa fa-times-circle"></i></a>
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
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery-multi-select/js/jquery.multi-select.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery-multi-select/js/jquery.quicksearch.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('theme/js/select-init.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    //pagination
    $('.custom-pagi a').on('click', function () {
    val = $(this).parent().find('input[name="set_offset"]').val();
            $('input[name="offset"]').val(val);
            $('#search_frm').submit();
            return false;
    });
    $("#unit_type_id").select2();
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
