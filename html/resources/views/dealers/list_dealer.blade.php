@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('css/popup.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('sidebar.sb_dealer_summary') }}</span>
        <span style="float: right"><a href="{{route ('add_dealer') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sidebar.sb_add_dealer') }}</a></span>
    </header>

    <div class="panel-body">
        <div class="position-center">
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('list_dealer') }}">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name" class="col-lg-4 control-label">{{ trans('dealer.dl_dealer_name') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="name" value="{{$name}}" name="name">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="Phone" class="col-lg-4 control-label">{{ trans('multiple.m_phone',['num'=>'']) }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="{{$phone}}" id="phone" name="phone" data-mask="999-999-999?9">
                            </div>
                        </div>
                        
                    </div>
                </div>
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
                    <th style="vertical-align:middle; text-align: center;">{{ trans('dealer.dl_dealer_id') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_photo') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('dealer.dl_dealer_name') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('dealer.dl_dealer_representative') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_location') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('dealer.dl_dealer_bank_name') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('dealer.dl_dealer_account_name') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('dealer.dl_dealer_account_number') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_phone',['num'=>'']) }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_status') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_action') }}</th>
                    </thead>
                    <tbody>
                        @forelse($lists as $d)
                        <?php
                        $count = count($d->bank);
                        $rowspan = ($count > 1) ? 'rowspan=' . $count : '';
                        $c = 0;
                        ?>
                        <tr>
                            <td {{ $rowspan}} class="isVerticalalign" align="center">{{ $d->id}}</td>
                            <?php 
                                $url = '';
                                if($d->photo){
                                    if(file_exists('data/dealers/'.$d->photo)){
                                        $url = asset('data/dealers/'.$d->photo);
                                    }else{
                                        $url = asset('images/noimage.gif');
                                    }
                                }else{
                                    $url = asset('images/noimage.gif');
                                }

                            ?>
                            <td {{ $rowspan}} class="toCenter" align="center"><img class="listPhoto" src="{{ $url }}"></td>
                            <td {{ $rowspan}} class="isVerticalalign">{{ $d->dealer}}</td>
                            <td {{ $rowspan}} class="isVerticalalign">{{ $d->representative?$d->representative:'N/A'}}</td>
                            <td {{ $rowspan}} class="isVerticalalign">{{ $d->location?$d->location:'N/A' }}</td>
                            @if($count > 0)
                            @foreach($d->bank as $b)
                            @if($c > 0)
                        <tr>
                            @endif
                            <td align="center">{{ $b->bank_name }}</td>
                            <td align="center">{{ $b->account_name }}</td>
                            <td align="center">{{ $b->account_number}}</td>

                            @if($c == 0)
                            <td {{ $rowspan}} class="isVerticalalign" align="center">
                                {{ $d->phone }}
                                {{ !empty($d->phone1) ? '/'.$d->phone1 : '' }}
                            </td>
                            <td {{ $rowspan}} class="isVerticalalign" align="center">
                                {{ $d->active? "Active":"Inactive" }}
                            </td>
                            <td {{ $rowspan}} class="isVerticalalign define-width" align="center">
                                <a href="{{ route('dealer_detail',[$d->id]) }}" class="btn btn-xs btn-default" title="Detail"><i class="fa fa-search-minus"></i></a>
                                <a href="{{ route('edit_dealer', [$d->id])}}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i></a>
                                @if($d->active ==0)
                                <a href="{{route('enable_dealer_bank', [$d->id])}}" class="btn btn-xs btn-default" title="Activate"><i class="fa fa-check-circle"></i></a>
                                @else
                                <a href="{{route('disable_dealer_bank', [$d->id])}}" class="btn btn-xs btn-default" title="Inactivate"><i class="fa fa-times-circle"></i></a>
                                @endif
                            </td>
                        </tr>
                        @endif
                        <?php
                        $c++;
                        ?>
                        @endforeach
                        @else
                    <td colspan="3"></td>
                    <td {{ $rowspan}}>
                        {{ $d->phone }}
                        {{ !empty($d->phone1) ? ' / '.$d->phone1 : '' }}
                    </td>
                    <td {{ $rowspan}}>
                        {{ $d->active? "Active":"Inactive" }}
                    </td>
                    <td {{ $rowspan}} class="define-width">
                        <a href="{{ route('dealer_detail',[$d->id]) }}" class="btn btn-xs btn-default" title="Detail"><i class="fa fa-search-minus"></i></a>
                        <a href="{{route('edit_dealer', [$d->id])}}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i></a>
                        @if($d->active ==0)
                        <a href="{{route('enable_dealer_bank', [$d->id])}}" class="btn btn-xs btn-default" title="Activate"><i class="fa fa-check-circle"></i></a>
                        @else
                        <a href="{{route('disable_dealer_bank', [$d->id])}}" class="btn btn-xs btn-default" title="Inactivate"><i class="fa fa-times-circle"></i></a>
                        @endif
                    </td>
                    </tr>
                    @endif
                    @empty
                    <tr><td colspan=11>{{ trans('multiple.m_no_result') }}</td></tr>
                    @endforelse
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
