@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('css/popup.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('sale_person.list_sale_person') }}</span>
        <span style="float: right"><a href="{{route ('add_saleperson') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sale_person.add_sale_person') }}</a></span>
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
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('list_saleperson') }}">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="sale_person" class="col-lg-4 control-label">{{ trans('multiple.m_search') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="sale_person" value="{{$search}}" name="search">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 text-right">
                        <input type="hidden" name="offset" value="<?php echo $offset ?>"/>
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
                <table class="table table-bordered table-striped table-condensed table-hover dealerTable" id="sale_person">
                    <thead class="th-center">
                        <th style="vertical-align:middle; text-align: center;">{{ trans('representative.id') }}</th>
                        <th style="vertical-align:middle; text-align: center;">{{ trans('sale_person.sale_team') }}</th>
                        <th style="vertical-align:middle; text-align: center;">{{ trans('sale_person.name') }}</th>
                        <th style="vertical-align:middle; text-align: center;">{{ trans('sale_person.gender') }}</th>
                        <th style="vertical-align:middle; text-align: center;">{{ trans('sale_person.dob') }}</th>
                        <th style="vertical-align:middle; text-align: center;">{{ trans('sale_person.nationality') }}</th>
                        <th style="vertical-align:middle; text-align: center;">{{ trans('sale_person.email') }}</th>
                        <th style="vertical-align:middle; text-align: center;">{{ trans('sale_person.phone') }}</th>
                        <th tyle="vertical-align:middle; text-align: center;">{{ trans('multiple.status') }}</th>
                        <th tyle="vertical-align:middle; text-align: center;">{{ trans('multiple.m_action') }}</th>
                    </thead>
                    <tbody>
                        @forelse($lists as $sale_person)
                            <tr>
                                <td class="isVerticalalign" align="center">{{ $sale_person->id}}</td>
                                <td class="isVerticalalign" align="center">{{ isset($sale_person->SaleTeam->name)?$sale_person->SaleTeam->name:"" }}</td>
                                <td class="isVerticalalign" align="center">{{ $sale_person->name }}</td>
                                <td class="isVerticalalign" align="center">{{ ucfirst($sale_person->gender)}}</td>
                                <td class="isVerticalalign" align="center">{{ $sale_person->dob}}</td>
                                <td class="isVerticalalign" align="center">{{ isset($sale_person->Nationalities->name)?$sale_person->Nationalities->name:"" }}</td>
                                <td class="isVerticalalign" align="center">{{ $sale_person->email}}</td>
                                <td class="isVerticalalign" align="center">{{ $sale_person->phone}}</td>
                                <td class="isVerticalalign" align="center">
                                    {{ $sale_person->active? "Active":"Inactive" }}
                                </td>
                                <td class="isVerticalalign define-width" align="center">
                                    <a href="{{ route('edit_sale_person', [$sale_person->id])}}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i></a>
                                    @if($sale_person->active ==0)
                                        <a href="{{route('saleperson_Enable', [$sale_person->id])}}" class="btn btn-xs btn-default" title="Activate"><i class="fa fa-check-circle"></i></a>
                                    @else
                                        <a href="{{route('saleperson_Disable', [$sale_person->id])}}" class="btn btn-xs btn-default" title="Inactivate"><i class="fa fa-times-circle"></i></a>
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
            new TableExport(document.getElementById('sale_person'), {
                formats: ['csv'],
                filename:'sale_person'
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
    });

    $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('sale_person'), {
                    formats: ['xlsx'],
                    filename: 'sale_person'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });
});
</script>
@endsection
