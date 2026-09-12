@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<style>
      @media print {
           .hide-this{
             display: none !important;
           }
      }
</style>
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('sidebar.sb_bank_list') }}</span>
        <span style="float: right"><a href="{{route ('add_bank') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sidebar.sb_add_bank') }}</a></span>
    </header>

    <div class="panel-body">
        <div class="position-center" style="width:90%;">
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('list_bank') }}">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="Name" class="col-lg-3 control-label">{{ trans('dealer.dl_dealer_account_name') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="account_name" value="{{$name}}" name="name">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="Phone" class="col-lg-3 control-label">{{ trans('dealer.dl_dealer_account_number') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" value="{{$number}}" id="account_number" name="number">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-3 col-lg-7">
                                <input type="hidden" name="offset" />
                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                                <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                                <button class="btn btn-primary" id="export"><i class="fa fa-sign-out"></i> {{ trans('multiple.export') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <br><br>
        </div>
        <div class="page">
            <div class="custom-pagi">
                <span class="pagi_label">Number of Rows:</span>
                <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                <a href="#" class="btn btn-danger">Go</a>
            </div>
        </div>
        <br/><br/><br/><br/>
        <div id="printArea">
            @include('api.report_header')
            <h4 class="sch_title">{{ trans('sidebar.sb_bank_list') }}</h4>
            <section id="unseen" class="ox-scroll">
                <table class="table table-bordered table-striped table-condensed table-hover" id="list_bank">
                    <thead>
                    <th style="text-align: center">{{ trans('dealer.dl_dealer_account_id') }}</th>
                    <th style="text-align: center">{{ trans('dealer.dl_dealer_bank_name') }}</th>
                    <th style="text-align: center">{{ trans('dealer.dl_dealer_account_name') }}</th>
                    <th style="text-align: center">{{ trans('dealer.dl_dealer_account_number') }}</th>
                    <th style="text-align: center">{{ trans('multiple.m_status') }}</th>
                    <th style="text-align: center" class="hide-this">{{ trans('multiple.m_action') }}</th>
                    </thead>
                    <tbody>
                        @forelse($lists as $list)
                        <tr>
                            <td align="center">{{ str_pad($list->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td align="center">{{$list->bank_name}}</td>
                            <td>{{$list->account_name}}</td>
                            <td align="center">{{$list->account_number}}</td>
                            <td align="center">{{$list->active ? "Active":"Inactive"}}</td>
                            <td align="center" class="define-width hide-this">
                                <span class="pad-rl-5"></span>
                                <a href="{{ route('edit_bank',[$list->id])}}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i></a>
                                @if($list->active ==0)
                                <a href="{{route('enable_bank', [$list->id])}}" class="btn btn-xs btn-default" title="Activate"><i class="fa fa-check-circle"></i></a>
                                @else
                                <a href="{{route('disable_bank', [$list->id])}}" class="btn btn-xs btn-default" title="Inactivate"><i class="fa fa-times-circle"></i></a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan=6>{{ trans('multiple.m_no_result') }}.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        </div>
        <div class="page">
            <?PHP
            echo $lists->appends([
                'name' => Input::get('name'),
                'phone' => Input::get('phone'),
                'offset' => Input::get('offset')
            ])->render();
            ?>
        </div>
    </div>
</section>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
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
            new TableExport(document.getElementById('list_bank'), {
                formats: ['csv']
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
    });
});
</script>
@endsection
