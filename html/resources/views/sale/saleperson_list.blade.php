@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('css/popup.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('representative.list_representative') }}</span>
        <span style="float: right"><a href="{{route ('add_saleperson') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('representative.create_new') }}</a></span>
    </header>

    <div class="panel-body">
        <div class="position-center">
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('saleperson_list') }}">

                <div class="row">
                    <div class="col-lg-12 text-right">
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
                    <th style="vertical-align:middle; text-align: center;">{{ trans('representative.id') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('representative.name') }}</th>
                    <th style="vertical-align:middle; text-align: center;">Lavel</th>
                    <th tyle="vertical-align:middle; text-align: center;">Commission rate(%)</th>
                    <th tyle="vertical-align:middle; text-align: center;">Commission level type</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('representative.gender') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('representative.dob') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('representative.national_id') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('representative.contact_number') }}</th>
                    <th tyle="vertical-align:middle; text-align: center;">{{ trans('multiple.status') }}</th>
                    <th tyle="vertical-align:middle; text-align: center;">{{ trans('multiple.m_action') }}</th>
                    </thead>
                    <tbody>
                        @forelse($lists as $representatives)
                        <tr>
                            <td class="isVerticalalign" align="center">{{ $representatives->id}}</td>
                            <td class="isVerticalalign" align="center">{{ $representatives->name }}</td>
                            <td class="isVerticalalign" align="center">{{ $representatives->lavel }}</td>
                            <td class="isVerticalalign" align="center">{{ $representatives->com_rate }}</td>
                            <td class="isVerticalalign" align="center">{{ $representatives->com_level_type }}</td>
                            <td class="isVerticalalign" align="center">
                                @if($representatives->gender == 'male')
                                    Male
                                @else
                                    Female
                                @endif
                            </td>
                            <td class="isVerticalalign" align="center">{{ $representatives->dob}}</td>
                            <td class="isVerticalalign" align="center">{{ $representatives->national_id}}</td>
                            <td class="isVerticalalign" align="center">{{ $representatives->phone}}</td>
                            <td class="isVerticalalign" align="center">
                                {{ $representatives->active? "Active":"Inactive" }}
                            </td>
                            <td class="isVerticalalign define-width" align="center">
                                <!-- <a href="{{ route('dealer_detail',[$representatives->id]) }}" class="btn btn-xs btn-default" title="Detail"><i class="fa fa-search-minus"></i></a> -->
                                <a href="{{ route('edit_saleperson', [$representatives->id])}}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i></a>
                                @if(!$representatives->active)
                                <a href="{{route('enable_saleperson', [$representatives->id])}}" class="btn btn-xs btn btn-success" title="Activate"><i class="fa fa-check-circle"></i></a>
                                @else
                                <a href="{{route('disable_saleperson', [$representatives->id])}}" class="btn btn-xs btn-danger" title="Inactivate"><i class="fa fa-times-circle"></i></a>
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
