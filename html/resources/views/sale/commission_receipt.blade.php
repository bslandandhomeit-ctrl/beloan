@extends('layouts.app')
@section('css')
<style>
    @font-face {
        src: url("{{ asset('publict/fonts/KhmerOSmuollight.ttf') }}") format("truetype");
        src: url("{{ asset('publict/fonts/KhmerOScontent.ttf') }}") format("truetype");
    }
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
    .project-logo{
        width: 160px !important;
        position: absolute !important;
        top: 105px !important;
    }
    p{
        font-family: 'Times New Roman','Khmer OS Battambang';
        font-size: 11pt !important;
    }
    .panel{
        width: 21cm;
        height: 14.8cm; 
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
        margin: 50px 90px auto;
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
    .panel {
    width: 100%;
    height: auto;
    }
    @page{
        size: A5 landscape;
    }
    @media print {
        .row{
            float: left;
            width: 100% !important; 
        }
        .col-sm-12{
            float: left;
            width: 100% !important;
        }
        .col-sm-6{
            float: left;
            width: 50% !important;
        }
        .brach_name{
            padding-top: 10px !important;
        }
        .col-sm-4{
            width: 33.33333333%;
            float: left;
        }
        .color-red{
            color: red !important;
        }
        .no{
            left: -15px !important;
        }
        span.dotted_width{
            width: 122px !important;
        }
        .project-logo{
            width: 160px !important;
            position: absolute !important;
            top: 20px !important;
        }
        h1.title{
            font-family: 'Times New Roman','Khmer OS Battambang' !important;
            font-size: 25pt;
            margin: 0 !important;
            font-weight: bold;
        }
        h2.title{
            font-family: 'Times New Roman','Khmer OS Battambang' !important;
            font-weight: bold;
            font-size: 15pt;
            margin: 5px !important;
        }
        h3.title{
            font-family: 'Times New Roman','Khmer OS Battambang' !important;
        }

        .address{
            position: absolute !important;
            right: 0px !important;
            bottom: 85px !important;
        }
        .posting_date{
            margin-top: 10px;
            margin-bottom: 5px !important;
            color: #252be8 !important;
            font-weight: bold;
        }
        .receipt_time{
            position: absolute;
            font-weight: bold;
            color: #252be8 !important;
        }
        .am_pm{
            float: right;
            margin-right: 20px !important;
            margin-top: -5px !important;
            color: #252be8 !important;
        }
        .cust_no{
            margin-left: 95px !important;
            color: #252be8 !important;
        }
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="print-btn" style="width: 21cm;">
            <header class="panel-heading">
                <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
            </header>
        </div>
        <section class="panel" style="margin: 0 auto;">
            <div id="printArea" class="panel-body" style="padding: 45px !important;">
                <?php
                    $logo = asset('images/no_logo.jpeg',false);
                    if($company_branch->logo != ''){
                        if(file_exists('data/company_logo/'.$company_branch->logo)){
                            $logo = asset('data/company_logo/'.$company_branch->logo,false);
                        }
                    }else{
                        $logo = asset('images/no_logo.jpeg',false);
                    }
                ?>
                <div class="box-img">
                    <img src="{{ $logo }}" style="opacity: 0.1;object-fit: contain;width: 100%;height: 100%;z-index: -1;">
                </div>
    
                <h1 class="title text-center brach_name">{{ $company_branch->branch_name }}</h1>
                <h2 class="title color-red text-center">{{ ($projects_row->dealer != '')?$projects_row->dealer:'&nbsp;' }}</h2>
                <h3 class="title text-center">COMMISSION REQUEST FORM</h3>
                <img src="{{ $logo }}" class="project-logo"/>
                <div style="border-bottom: ttom: 1px solid;margin-top: 10px !important;"></div>
                <div class="row">
                <table class="table table-bordered table-striped table-condensed table-hover dealerTable" id="dealers_list">
                    <tr>
                        <td>Request number: {{$listSale->withdrawal_number}}</td>
                        <td></td>
                        <td>Request date: {{$listSale->withdrawal_date}}</td>
                    </tr>
                    <tr>
                        <td>Status:
                         @if( $listSale->status =='Withdrawal')
                                    <p class="btn btn-danger">{{ $listSale->status}}</p>
                                    @else
                                    <p class="btn btn-xs btn-primary">{{ $listSale->status}}</p>
                                    @endif
                        </td>
                        <td></td>
                        <td>Payment Status:
                         @if( $listSale->payment_status =='Due')
                                    <p class="btn btn-danger">{{ $listSale->payment_status}}</p>
                                    @else
                                    <p class="btn btn-xs btn-default">{{ $listSale->payment_status}}</p>
                                    @endif
                        </td>
                    </tr>
                    <tr> 
                        <td>Company: {{$listSale->company}}</td>                       
                        <td>Project: {{$listSale->project}}</td>
                        <td>Unit type: {{$listSale->unit_type}}</td>
                    </tr>
                    <tr>                        
                        <td>Sale team leader: {{$listSale->sale_person}}</td>
                        <td>Sale team level: {{$listSale->lavel}}</td>
                        <td>Commission period: <b>{{ !empty($listSale->t_from)?date('d-M-Y',strtotime($listSale->t_from)):"" }}</b> - <b>{{ !empty($listSale->t_to)?date('d-M-Y',strtotime($listSale->t_to)):"" }}</b></td>
                </table>

                <table class="table table-bordered table-striped table-condensed table-hover dealerTable" id="dealers_list">
                    <thead class="th-center">
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.id') }}</th>
                    <th style="vertical-align:middle; text-align: center;">Main Project</th>
                    <th style="vertical-align: middle;text-align: center;">Item No.</th>
                    <th style="vertical-align:middle; text-align: center;">Variant Code</th>
                    <th style="vertical-align: middle;text-align: center;">Con.Sign Date</th>
                    <th style="vertical-align:middle; text-align: center;">Net Selling Price</th>
                    <th style="vertical-align:middle; text-align: center;">Customer Paid</th>   
                    <th style="vertical-align:middle; text-align: center;">Com.Amount</th> 
                    <th style="vertical-align:middle; text-align: center;">Total Com. Paid</th> 
                    <th style="vertical-align:middle; text-align: center;">Com. Balance</th> 
                    <th style="vertical-align:middle; text-align: center;">Rate</th> 
                    <th style="vertical-align:middle; text-align: center;">Withdrawal.Atm</th>
                    <th style="vertical-align:middle; text-align: center;">Payment status</th> 
                                       
                    </thead>

                    <tbody  class="checkbox-group">
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
                            $commission_withdrawal='Withdrawal';
                            $status_btn='btn btn-danger';
                                if(!empty($d->commission_withdrawal_transaction) && count($d->commission_withdrawal_transaction) > 0){
                                $total_commission_paid=$d->commission_withdrawal_transaction->sum('received_amount');
                                $status=findStatusBykey('Withdrawal',$d->commission_withdrawal_transaction);
                                $commission_withdrawal=findStatusBykey('Approved',$d->commission_withdrawal_transaction);
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
                                <td class="isVerticalalign">{{ !empty($d->contract_date)?date('d-M-Y',strtotime($d->contract_date)):"N/A" }}</td>
                                <td style="text-align: center">{{ number_format($net_selling_price,2,'.','') }}</td> 
                                <td style="text-align: center">{{ number_format($d->total_customer_paid,2,'.','') }}</td>   
                              
                                <td class="isVerticalalign" >{{$d->commission_value}}</td> 
                                <td style="text-align: center">{{ number_format($total_commission_paid,2,'.','') }}</td> 
                                <td style="text-align: center">{{ number_format($commission_balance,2,'.','') }}</td> 
                                <td class="isVerticalalign" >{{$d->commission_rate}}</td> 
                                <td class="isVerticalalign" >{{$d->withdrawal_amount}}</td> 
                                

                                <td class="isVerticalalign define-width" align="center">
                                    @if( $d->payment_status =='Due')
                                    <p class="btn btn-danger">{{ $d->payment_status}}</p>
                                    @else
                                    <p class="btn btn-xs btn-default">{{ $d->payment_status}}</p>
                                    @endif
                                </td>  
                          
                                            
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="row" style="margin: 0 20px;margin-top: 100px;">
                    <div class="col-sm-3" style="text-align: center;">                   
                    
                        <p class="khmer-title">Acknowledged By </p>
                        <p> @if(!empty($listSale->sales_manager_approval))                    
                                    <i class="fa fa-check-circle"></i>
                                    {{$listSale->sales_manager_approval}}
                                    @else
                                    <p class="btn btn-danger">Pending </p>
                                   
                                    @endif
                                </p>
                    </div>
                    <div class="col-sm-3" style="text-align: center;">
                        <p class="khmer-title"> Checked By </p>
                        <p> @if(!empty($listSale->accountant_approval))                    
                                    <i class="fa fa-check-circle"></i>
                                    {{$listSale->accountant_approval}}
                                    @else
                                    <p class="btn btn-danger">Pending </p>
                                   
                                    @endif
                                </p>
                    </div>
                    <div class="col-sm-3" style="text-align: center;">
                    <p class="khmer-title">Verified By </p>
                        <p> @if(!empty($listSale->hof_approval))                    
                                    <i class="fa fa-check-circle"></i>
                                    {{$listSale->hof_approval}}
                                    @else
                                    <p class="btn btn-danger">Pending </p>
                                   
                                    @endif
                                </p>
                    </div>
                    <div class="col-sm-3" style="text-align: center;">
                    <p class="khmer-title">Approved By </p>
                        <p> @if(!empty($listSale->chairman_approval))                    
                                    <i class="fa fa-check-circle"></i>
                                    {{$listSale->chairman_approval}}
                                    @else
                                    <p class="btn btn-danger">Pending </p>
                                   
                                    @endif
                                </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
@endsection