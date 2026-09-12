@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('css/popup.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('unit_type.list_unit_type') }}</span>
        <span style="float: right"><a href="{{route ('add_unit_type') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('unit_type.add_unit_type') }}</a></span>
    </header>
    <div class="panel-body">
        <div class="position-center">
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('list_unit_type') }}">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name" class="col-lg-4 control-label">{{ trans('dealer.name') }}</label>
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
            <h4 class="sch_title">{{ trans('sidebar.sb_dealer_summary') }}</h4>
            <section id="unseen" >
                <table class="table table-bordered table-striped table-condensed table-hover dealerTable" id="dealers_list">
                    <thead class="th-center">
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.id') }}</th>
                    <th style="vertical-align:middle; text-align: center;">Project Name</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('dealer.name') }}</th>
                    <th style="vertical-align: middle;text-align: center;">{{ trans('dealer.short_code') }}</th>
                    <th style="vertical-align: middle;text-align: center;">{{ trans('unit_type.contract_template') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit_type.contractable') }}</th>
                    <th style="vertical-align: middle;text-align: center;">{{ trans('unit_type.annual_management_fee') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit_type.contract_transfer_fee') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit_type.mgt_fee_per_square') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit_type.deadline') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit_type.extended_deadline') }}</th>
                    <th style="vertical-align:middle; text-align: center;">Com.Type</th>
                    <th style="vertical-align:middle; text-align: center;">Com.Amount</th>
                    <th style="vertical-align:middle; text-align: center;">Com.Approved</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit_type.payment_option_image') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('unit_type.feature_image') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_status') }}</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_action') }}</th>
                    </thead>
                    <tbody>
                        @forelse($lists as $d)
                        <?php
                            $status=null;
                            $status_btn=null;
                            $commission_type=null;
                            $commission_value=null;
                                if(!empty($d->activeSaleCommissionSetting) && count($d->activeSaleCommissionSetting) > 0){
                                $status=$d->activeSaleCommissionSetting->approval;
                                $commission_type=$d->activeSaleCommissionSetting->commission_type;
                                $commission_type=$commission_type=='$'?'Fixed ($)':'Percentage (%)';
                                $commission_value=$d->activeSaleCommissionSetting->commission_value;
                                if($status==='approved'){
                                    $status='Approved';               
                                    $status_btn=' btn btn-primary'; 
                            }elseif($status==='send_back'){
                                        $status='Send Back';               
                                        $status_btn=' btn btn-danger';           
                            }else{
                                $status_btn='btn btn-warning';
                                $status='Pending';
                            }
                                
                        
                                }
                                $project_name=null;
                                if(!empty($d->Projects) && count($d->Projects) > 0){
                                    $project_name=$d->Projects->short_code;
                                }
                        ?>
                            <tr>
                                <td class="isVerticalalign" align="center">{{ $d->id}}</td>                                
                                <td class="isVerticalalign">{{ $project_name}}</td>
                                <td class="isVerticalalign">{{ $d->name}}</td>
                                <td class="isVerticalalign">{{ $d->short_code }}</td>
                                <td class="isVerticalalign">{{ $contract_template[$d->contract_template_id] }}</td>
                                <td class="isVerticalalign">{{ $d->is_contractable ? "True":"False" }}</td>
                                <td class="isVerticalalign" align="center">{{ $d->annual_management_fee }}</td>
                                <td class="isVerticalalign">{{ $d->contract_transfer_fee }}</td>
                                <td class="isVerticalalign" align="center">{{ $d->management_fee_per_square }}</td>
                                <td class="isVerticalalign">{{ $d->deadline }}</td>
                                <td class="isVerticalalign">{{ $d->extended_deadline }}</td>
                                <td class="isVerticalalign">{{ $commission_type?$commission_type:'' }}</td>
                                <td class="isVerticalalign">{{ $commission_value?$commission_value:'' }}</td>
                                <td class="isVerticalalign">
                                <p class="{{$status_btn}}" >{{$status}}</p>
                                
                                </td>
                                <?php
                                    $payment_option_image = '';
                                    if($d->payment_option_image_url){
                                        if(file_exists('data/unit_type/payment_option_image/'.$d->payment_option_image_url)){
                                            $payment_option_image = asset('data/unit_type/payment_option_image/'.$d->payment_option_image_url);
                                        }else{
                                            $payment_option_image = asset('images/noimage.gif');
                                        }
                                    }else{
                                        $payment_option_image = asset('images/noimage.gif');
                                    }
                                ?>
                                <td class="isVerticalalign">
                                    <img src="{{ $payment_option_image }}" width="60">
                                </td>
                                <?php
                                    $feature_image = '';
                                    if($d->feature_image_url){
                                        if(file_exists('data/unit_type/feature_image/'.$d->feature_image_url)){
                                            $feature_image = asset('data/unit_type/feature_image/'.$d->feature_image_url);
                                        }else{
                                            $feature_image = asset('images/noimage.gif');
                                        }
                                    }else{
                                        $feature_image = asset('images/noimage.gif');
                                    }
                                ?>
                                <td class="isVerticalalign">
                                    <img src="{{ $feature_image }}" width="60">
                                </td>
                                <td class="isVerticalalign" align="center">
                                    {{ $d->active? "Active":"Inactive" }}
                                </td>
                                <td class="isVerticalalign define-width" align="center">
                                    <a href="{{ route('unit_type_detail',[$d->id]) }}" class="btn btn-xs btn-default" title="Detail"><i class="fa fa-search-minus"></i></a>
                                    <a href="{{ route('edit_unit_type', [$d->id])}}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i></a>
                                    @if($d->active ==0)
                                    <a href="{{route('unit_typeEnable', [$d->id])}}" class="btn btn-xs btn-default" title="Activate"><i class="fa fa-check-circle"></i></a>
                                    @else
                                    <a href="{{route('unit_typeDisable', [$d->id])}}" class="btn btn-xs btn-default" title="Inactivate"><i class="fa fa-times-circle"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! str_replace('?page', '&page', $lists->appends(\Request::except('page'))->render()) !!}
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
