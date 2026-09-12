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
        <div class="print-btn" style="width: 21cm;margin: 0 auto;">
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
                <h3 class="title text-center">Head office: {{ $company_branch->address_one }}</h3>
                <h3 class="title text-center">{{ ($company_branch->address_two != '')?$company_branch->address_two:'&nbsp;' }}</h3>
                <h3 class="title color-red text-center">Tel: {{ $company_branch->contact_number }}</h3>
                <h3 class="title text-center">E-mail:{{ $company_branch->email }} / Page:{{ $company_branch->website }}</h3>
                <img src="{{ $logo }}" class="project-logo"/>
                <div style="border-bottom: double;margin-top: 10px !important;"></div>
                <div class="row">
                    <div class="col-sm-4">
                        <p class="posting_date">Posting Date: {{ date('d-m-Y',strtotime($becash->issue_date)) }}</p>
                        <p class="receipt_time">Receipt Time: {{ date('d-m-Y h:i:s A',strtotime($becash->issue_date)) }}</p>
                    </div>
                    <div class="col-sm-4">
                        <h2 class="khmer-title text-center" style="margin-top: 10px !important;">បង្កាន់ដៃទទួលប្រាក់</h2>
                        <h2 class="title text-center">
                            <span style="border-bottom: 1px solid #000;">Receipt</span>
                        </h2>
                    </div>
                    <div class="col-sm-4 no" style="margin-top: 20px;">
                        <p style="text-align: left;">លេខ​ / <b>No</b> <span class="horizontal_dotted_lines dotted_width" style="width: 130px;">{{ $becash->receipt_no }}</span> </p>
                    </div>
                </div>
                <div style="margin: 0 20px;">
                    <?php
                        $currency_char = new \NumberFormatter("en", NumberFormatter::SPELLOUT); 
                        $name_kh = $drawdown->client->ClientCbcGeneral->family_name_kh.' '.$drawdown->client->ClientCbcGeneral->first_name_kh;

                        $customer_name1 = $loan->client->ClientCbcGeneral->family_name_kh.' '.$loan->client->ClientCbcGeneral->first_name_kh;
                        $customer_name2 = $loan->co_borrowers->Clients->ClientCbcGeneral->family_name_kh.' '.$loan->co_borrowers->Clients->ClientCbcGeneral->first_name_kh;
                        $opt=" & ";
                    ?>
                    <p>បានទទួលប្រាក់ពីឈ្មោះ <span class="horizontal_dotted_lines" style="width: 295px;">
                    {{ ($customer_name1 != ' ')?$customer_name1: $drawdown->account_name}}{{ ($customer_name2 != ' ')?$opt.$customer_name2: ''}}
                </span> ទឹកប្រាក់ <b style="border: 1px solid;padding: 15px !important;"> <span class="horizontal_dotted_lines" style="width: 138px;/*170px;*/">$ {{ number_format($becash->grand_total,2) }}</span></b></p>
                    <p class="p-english">Received From <span class="cust_no">{{ $drawdown->client->cus_acc }}</span></p>
                    <p>ទឹកប្រាក់សរសេរជាអក្សរ <span class="horizontal_dotted_lines" style="width: 519px;"> {{ $currency_char->format($becash->grand_total) }}</span></p>
                    <p class="p-english">Amount in Words</p>
                    <p>អត្ថន័យ <span class="horizontal_dotted_lines" style="width: 398px;">{{ $becash->description }}</span>Property <span class="horizontal_dotted_lines" style="width: 162px;">{{ $unit->code }}</span></p>
                </div>
                <div class="row" style="margin: 0 20px;">
                    <div class="col-sm-6" style="padding:0px !important;"><p>Description</p></div>
                    <div class="col-sm-6" style="padding-left:75px !important;"><p>Address</p></div>
                </div>
                <div class="row" style="margin: 0 20px;">
                    <div class="col-sm-4" style="text-align: center;">
                        <p class="khmer-title">អតិថិជន</p>
                        <p>Customer</p>
                    </div>
                    <div class="col-sm-4" style="text-align: center;">
                        <p class="khmer-title">អ្នកលក់</p>
                        <p>Seller</p>
                    </div>
                    <div class="col-sm-4" style="text-align: center;">
                        <p class="khmer-title">បេឡា</p>
                        <p>Cashier</p>
                        @if ($slips->is_signature == 1)
                            <?php 
                                $signature = '';
                                if($slips->signature){
                                    if(file_exists('data/users/'.$slips->signature)){
                                        $signature = asset('data/users/'.$slips->signature);
                                    }
                                }

                            ?>
                            @if ($signature != '')
                                <img style="margin-top: -13px; height: 49px;" src="{{ $signature }}">
                            @endif
                        @endif
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