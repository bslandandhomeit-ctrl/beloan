<?php
function findStatusBykey($status,$array){

    foreach ( $array as $element ) {
        if ( $status == $element->status ) {
            return $element->status;
        }
    }
    
    return false;
    }
?>
@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/select2_v4.1.0/select2.min.css',isset($secure) ? false : false) }}"/>
    <link rel="" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css', isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
    <style>

    #printArea{
        font-family: 'Times New Roman','Khmer OS Battambang';
    }
    h1.title{
        font-size: 25pt;
        margin: 0 !important;
        font-weight: bold;
    }
    h2.title{
        font-weight: bold;
        font-size: 15pt;
        margin: 3px !important;
    }
    h2.khmer-title{
        font-family: 'Khmer os muol Light';
        font-size: 15pt !important;
    }
    .khmer-title{
        font-family: 'Khmer os muol Light';
        font-size: 11pt !important;
    }
    .color-red{
        color: red;
    }
    h3.title{
        font-size: 12pt;
        margin: 3px !important;
        font-weight: bold;
    }

    p{
        font-family: 'Times New Roman','Khmer OS Battambang';
        font-size: 11pt !important;
    }
    .horizontal_dotted_lines{
        /*padding: 0px 9px;*/
        color: #252be8 !important;
        position: relative;
        font-family: 'Khmer OS Battambang';
        text-align: left;
        display: inline-table;
        /*font-weight: 600;*/
        text-transform: capitalize !important;
    }
    .horizontal_dotted_lines::before {
        content: '\0000a0';
        position: absolute;
        width: 100%;
        bottom: 2px !important;
        border-bottom: 0.1px dotted #bdbdbd;
    }
    .box-img{
        margin: 250px 90px auto;
        width: 535px;
        height: 370px;
        position: absolute;
    }
    .no{
        
    }
    .p-english{
        font-weight: bold;
    }
    .address{
        position: absolute !important;
        left: 57% !important;
        top: 76% !important;
    }
    .posting_date{
        margin-top: 10px;
        margin-bottom: 5px !important;
        color: #252be8;
        font-weight: bold;
    }
    .receipt_time{
        width: 300px;
        position: absolute;
        font-weight: bold;
        color: #252be8;
    }
    .am_pm{
        float: right;
        margin-right: 9px;
        margin-top: -5px !important;
    }
    .cust_no{
        margin-left: 95px !important;
        color: #252be8;
    }
    .table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td {
        padding: 5px;
    }
    .tab-content {
            position: relative;
        }
        .tab-content .tab-pane {
            min-height: 200px;
        }
        .tab-content .loading {
            position: absolute;
            left: 50%;
            top: 20px;
            display: block;
            width: 40px;
            height: 40px;
            background: transparent url("{{ asset('images/loading.gif', isset($secure) ? false : false) }}") no-repeat scroll center center / contain;
        }
        .thumbnail {
            margin-bottom: 5px;
        }
        .total_comission ul{
            margin: 0 auto;
        }
        .total_comission ul li{
            box-shadow: 1px 1px 2px 2px #999;
    list-style: none;
    padding: 10px;
    width: 220px;
    height: 200px;
    margin: 20px;
    float: left;
    font-size: 18px;
    text-align: center;
    background: #fff;
    padding-top: 5%;
        }
    
    <style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="profile-nav alt">
                <section class="panel">
                     <div class="user-heading alt clock-row terques-bg">
                        <h1>{{ date('M d') }}</h1>
                        <p class="text-left">{{ date('Y, l') }}</p>
                     </div>

                    <div class="profile">
                        <?php 
                            $url = '';
                            if($user->photo){
                                if(file_exists('data/users/'.$user->photo)){
                                    $url = asset('data/users/'.$user->photo);
                                }else{
                                    $url = asset('images/noimage.gif');
                                }
                            }else{
                                $url = asset('images/noimage.gif');
                            }

                        ?>
                         <img src="{{ $url }}" />
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-4">
                              <table>
                                <tr>
                                    <td><label>{{ trans('user.u_user_kh_name') }}</label></td>
                                    <td>&nbsp;&nbsp;<label>:</label>&nbsp;&nbsp;</td>
                                    <td valign="top">{{ !empty($user->kh_name)? $user->kh_name : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><label>{{ trans('user.u_user_name') }}</label></td>
                                    <td>&nbsp;&nbsp;<label>:</label>&nbsp;&nbsp;</td>
                                    <td valign="top">{{ $user->name }}</td>
                                </tr>
                              </table>
                            </div>
                            <div class="col-sm-4">
                                <table>
                                    <tr>
                                        <td><label>{{ trans('multiple.m_email') }}</label></td>
                                        <td>&nbsp;&nbsp;<label>:</label>&nbsp;&nbsp;</td>
                                        <td valign="top">{{ !empty($user->email)? $user->email : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><label>{{ trans('multiple.m_phone',['num'=>'']) }}</label></td>
                                        <td>&nbsp;&nbsp;<label>:</label>&nbsp;&nbsp;</td>
                                        <td valign="top">{{ !empty($user->phone)?$user->phone: '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table>
                                    <tr>
                                        <td><label>{{ trans('multiple.m_address') }}</label></td>
                                        <td>&nbsp;&nbsp;<label>:</label>&nbsp;&nbsp;</td>
                                        <td valign="top">{{ !empty($user->address)? $user->address : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><label>{{ trans('user.u_user_role') }}</label></td>
                                        <td>&nbsp;&nbsp;<label>:</label>&nbsp;&nbsp;</td>
                                        <td valign="top">{{ $user->role_name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <?php
    /*
        $loan_status = config('static_data.loan_status');
        ?>
                   
                    <div class="row total_comission">
                    <ul>
                    <li>
                    <p>កម្រៃជើងសារផ្ទាល់ខ្លួន</p>
                    <p style="color: #035056;font-size: 21px !important;padding: 20px;display: block;"><strong>$ {{ number_format($data['own_commission_sum'],2,'.','') }}</strong></p>
                    </li>
                    <li>កម្រៃជើងសារបានពីកូនក្រុម
                    <p style="color: #035056;font-size: 21px !important;padding: 20px;display: block;"><strong>$ {{ number_format($data['member_commission_sum'],2,'.','') }}</strong></p>
                    </li>
                    <li>សរុបកម្រៃជើងសារទាងអស់
                    <?php $total=floatval($data['own_commission_sum']) + floatval($data['member_commission_sum']);?>
                    <p style="color: #035056;font-size: 21px !important;padding: 20px;display: block;"><strong>$ {{$total}} </strong></p>
                    </li>
                    <li>កម្រៃជើងសារបានទូទាត់ហើយ
                    <p style="color: #035056;font-size: 21px !important;padding: 20px;display: block;"><strong>$ {{$data['total_commission_paid_sum']}}</strong></p>
                    </li>
                    <li>កម្រៃជើងសារនៅសល់
                    <?php $remining=floatval($total) - floatval($data['total_commission_paid_sum']);?>
                    <p style="color: #035056;font-size: 21px !important;padding: 20px;display: block;"><strong>$ {{$remining}}</strong></p>
                    </li>
                    </ul>
                    </div>
                    <section>
                    <ul class="nav nav-tabs">
                        <li class="active" id="client_detail">
                            <a data-toggle="tab" href="#commission">Comission</a>
                        </li>
                        <li id="membercommission"><a data-toggle="tab" href="#member_commission">Member Comission</a></li>
                        <li id="requestCommission"><a data-toggle="tab" href="#request_commission">Request Comission</a></li>
                        <li id="commissionPaid"><a data-toggle="tab" href="#commission_paid">Comission paid</a></li> 
                        <li id="pendingCommission"><a data-toggle="tab" href="#pending_commission">Pending Comission</a></li>                         
                    </ul>

                    <div class="panel-body">
                        <ul id="language" style="margin-top:1rem;padding-left:0;margin-bottom:2rem;">
                            <li><a class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</a></li>
                            <li><a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a></li>
                            <li><a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a></li>
                        </ul>
                        <div  class="printArea">
                          
                            <div class="tab-content">
                                <span class="loading" id="loading"></span>
                                <div id="commission" class="tab-pane active">
                                    @if(!empty($lists))
                                    <table class="table table-bordered table-striped table-condensed table-hover dealerTable" id="dealers_list">
                                        <thead class="th-center">
                                        <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.id') }}</th>
                                        <th style="vertical-align:middle; text-align: center;">Main Project</th>
                                        <th style="vertical-align: middle;text-align: center;">Item No.</th>
                                        <th style="vertical-align:middle; text-align: center;">Variant Code</th>
                                        <th style="vertical-align:middle; text-align: center;">Contract No</th>
                                        <th style="vertical-align: middle;text-align: center;">Cus.Name</th>
                                        <th style="vertical-align:middle; text-align: center;">Status</th>
                                        <th style="vertical-align: middle;text-align: center;">Con.Sign Date</th>
                                        <th style="vertical-align:middle; text-align: center;">Net Selling Price</th>
                                        <th style="vertical-align:middle; text-align: center;">Sale Person</th>
                                        <th style="vertical-align:middle; text-align: center;">Customer Paid</th>   
                                        <th style="vertical-align:middle; text-align: center;">Com.Type</th> 
                                        <th style="vertical-align:middle; text-align: center;">Com.Amount</th> 
                                        <th style="vertical-align:middle; text-align: center;">Total Com. Paid</th> 
                                        <th style="vertical-align:middle; text-align: center;">Com. Balance</th> 
                                        <th style="vertical-align:middle; text-align: center;">Status</th> 
                                        <th style="vertical-align:middle; text-align: center;">Action</th> 
                                                        
                                        </thead>

                                        <tbody>
                                            <?php $i=0;
                                            ?>
                                            @forelse($lists as $d)
                                            <?php $i++;
                                                $net_selling_price=0;
                                                $totalall_discount=floatval($d->discount_promotion) + floatval($d->discount_other) + floatval($d->amount_discount_payment_option);
                                                $net_selling_price=floatval($d->unit_sale_price) - floatval($totalall_discount);    
                                                $total_commission_paid=0;
                                                $commission_balance=0;
                                                $status='Balance';
                                                $status_btn='btn btn-danger';
                                                    if(!empty($d->commission_withdrawal_transaction) && count($d->commission_withdrawal_transaction) > 0){
                                                    $total_commission_paid=$d->commission_withdrawal_transaction->sum('received_amount');
                                                    $status=findStatusBykey('Withdrawal',$d->commission_withdrawal_transaction);
                                                    }
                                                    if($d->commission_type=='$'){
                                                        $commission_balance=floatval($d->commission_value)-floatval($total_commission_paid);
                                                    }else{
                                                        $commission_balance=floatval($net_selling_price * $d->commission_value/100)-floatval($total_commission_paid);
                                                    }
                                                    if($status!='Withdrawal'){
                                                        if($commission_balance>0){
                                                            $status='Balance';
                                                        }else{
                                                            $status='Paid';
                                                        
                                                            $status_btn=' btn btn-default';
                                                        }
                                                    }else{
                                                        $status_btn='btn btn-warning';
                                                    }

                                                    ?>
                                                <tr>
                                                    <td class="isVerticalalign" align="center">{{ $i}}</td>
                                                    <td class="isVerticalalign">{{ $d->short_code }}</td>
                                                    <td class="isVerticalalign" >{{ $d->unit_type }}</td>
                                                    <td class="isVerticalalign">{{ $d->unit }}</td>
                                                    <td style="vertical-align: middle;text-align: center">
                                                    <a style="text-decoration: underline" href="{{ route('loan_detail', [$d->id])}}">{{ $d->contract_id ? $d->contract_id : '-'  }}</a>
                                                    </td>
                                                    <td class="isVerticalalign">{{ $d->client_name}}</td>
                                                    <td style="text-align: center">{{$loan_status[$d->status]}}</td>
                                                    <td class="isVerticalalign">{{ !empty($d->contract_date)?date('d-M-Y',strtotime($d->contract_date)):"N/A" }}</td>
                                                    <td style="text-align: center">{{ number_format($net_selling_price,2,'.','') }}</td> 
                                                    <td class="isVerticalalign" >{{ $d->sale_persons?$d->sale_persons->name:'-' }}</td>  
                                                    <td style="text-align: center">{{ number_format($d->coa_journal_detail->sum('credit'),2,'.','') }}</td>   
                                                    
                                                    <td class="isVerticalalign" >{{$d->commission_type}}</td>   
                                                    <td class="isVerticalalign" >{{$d->commission_value}}</td> 
                                                    <td style="text-align: center">{{ number_format($total_commission_paid,2,'.','') }}</td> 
                                                    <td style="text-align: center">{{ number_format($commission_balance,2,'.','') }}</td> 
                                                    <td class="isVerticalalign define-width" align="center">
                                                        @if($status =='Balance')
                                                        <p class="{{$status_btn}}">{{$status}}</p>
                                                        @else
                                                        <p class="{{$status_btn}}">{{$status}}</p>
                                                        @endif
                                                    </td> 
                                                    <td class="isVerticalalign define-width" align="center">
                                                        <a href="{{ route('list_sale_commission_detail',[$d->id]) }}" class="btn btn-info searchs" title="Detail">Detail</a>
                                        
                                                    </td> 
                                                                
                                                    
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                    {{-- end client --}}
                                </div>
                                <div id="member_commission" class="tab-pane">

                                </div>
                                <div id="request_commission" class="tab-pane">

                                </div>
                                <div id="commission_paid" class="tab-pane">

                                </div>
                                <div id="pending_commission" class="tab-pane">

                                </div>
                            </div>
                        </div>
                    </div>
                    {!! str_replace('?page', '&page', $lists->appends(\Request::except('page'))->render()) !!}
                </section>
                <!-- end tab  -------------->
                */?>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/user-profile.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/iCheck/jquery.icheck.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/xlsx.full.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>

@endsection
