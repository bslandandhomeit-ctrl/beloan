@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('css/popup.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('promotion.list_promotion') }}</span>
        <span style="float: right"><a href="{{route ('add_promotion') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('promotion.create_promotion') }}</a></span>
    </header>

    <div class="panel-body">
        <div class="position-center">
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('list_promotion') }}">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name" class="col-lg-4 control-label">{{ trans('representative.name') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="name" value="{{$name}}" name="name">
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
            <h4 class="sch_title">{{ trans('promotion.list_promotion') }}</h4>
            <section id="unseen" >
                <table class="ddd table table-bordered table-striped table-condensed table-hover dealerTable" id="payment_option_list">
                    <thead class="th-center">
                    <th style="vertical-align:middle; text-align: center;">{{ trans('representative.id') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('promotion.name') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('promotion.start_date') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('promotion.end_date') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('promotion.discount_amount') }}</th>
                    <th tyle="vertical-align:middle; text-align: center;">{{ trans('multiple.status') }}</th>
                    <th tyle="vertical-align:middle; text-align: center;">{{ trans('multiple.m_action') }}</th>
                    </thead>
                    <tbody>
                        @forelse($lists as $key => $promotion)
                        <tr>
                            <td class="isVerticalalign" align="center">{{ ++$key }}</td>
                            <td class="isVerticalalign" align="center">{{ $promotion->name }}</td>
                            <td class="isVerticalalign" align="center">{{ $promotion->start_date }}</td>
                            <td class="isVerticalalign" align="center">{{ $promotion->end_date}}</td>
                            <td class="isVerticalalign" align="center">{{ $promotion->discount_amount}}</td>
                            <td class="isVerticalalign" align="center">
                                {{ $promotion->active ? "Active":"Inactive" }}
                            </td>
                            <td class="isVerticalalign define-width" align="center">
                                <a onclick="detail({{ $promotion->id }});" href="#" class="btn btn-xs btn-default" title="Detail"><i class="fa fa-search-minus"></i></a>
                                <a href="{{ route('edit_promotion', [$promotion->id])}}" class="btn btn-xs btn-warning" title="Edit"><i class="fa fa-pencil"></i></a>
                                @if($promotion->active ==0)
                                <a href="{{route('enable_promotion', [$promotion->id])}}" class="btn btn-xs btn-default bg-green" title="Activate"><i class="fa fa-check-circle"></i></a>
                                @else
                                <a href="{{route('disable_promotion', [$promotion->id])}}" class="btn btn-xs btn-default bg-red" title="Inactivate"><i class="fa fa-times-circle"></i></a>
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
<div id="promotion_detail" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 70%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{ trans('promotion.promotion_detail') }}</h4>
            </div>
            <div class="modal-body" id="content_detail">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
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
            new TableExport(document.getElementById('payment_option_list'), {
                formats: ['csv'],
                filename:'payment_option_list'
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
    });

    $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('payment_option_list'), {
                    formats: ['xlsx'],
                    filename: 'payment_option_list'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });
});
function detail(id){
    $.ajax({
        url: '{{ route('promotion_detail') }}',
        type: 'GET',
        dataType: 'html',
        data: {id: id},
        success:function(data){
            $('#promotion_detail').modal('show');
            $('#content_detail').html(data);
        }
    });
}
</script>
@endsection
