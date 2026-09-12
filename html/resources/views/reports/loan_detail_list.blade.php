<?php 
function findObjectById($payment_month,$array){

foreach ( $array as $element ) {
    if ( $payment_month == $element->loan_no ) {
        return $element;
    }
}

return false;
}
function findObjectBykey($payment_month,$array){

    foreach ( $array as $element ) {
        if ( $payment_month == $element->payment_month ) {
            return $element;
        }
    }
    
    return false;
    }

    function coa_journal_detail_credit($array){
        $data=array();
            foreach ( $array as $element ) {
                if ($element->credit>0 ) {
                    array_push($data,$element);
                }
            }
            
            return $data;
        }

    

    function current_schedule_date($array){

        foreach ( $array as $element ) {
            $current_date=new DateTime();
            $current_year = date("Y"); 
            $current_month = date("m"); 

            $re_date = strtotime($element->schedule_date);
            $current_re_year = date("Y", $re_date ); 
            $current_re_month = date("m", $re_date ); 
            if ( ($current_month == $current_re_month) && ($current_year==$current_re_year) ) {
                return $element->schedule_date;
            }
        }
        
        return false;
        }
        function current_schedule($array){

            foreach ( $array as $element ) {
                $current_date=new DateTime();
                $current_year = date("Y"); 
                $current_month = date("m"); 
    
                $re_date = strtotime($element->schedule_date);
                $current_re_year = date("Y", $re_date ); 
                $current_re_month = date("m", $re_date ); 
                if ( ($current_month == $current_re_month) && ($current_year==$current_re_year) ) {
                    return $element;
                }
            }
            
            return false;
            }
        function get_schedule_date_by_payment_date($payment_date,$array){

            foreach ( $array as $element ) {
                $pay_date = strtotime($payment_date);
                $pay_year = date("Y", $pay_date ); 
                $pay_month = date("m", $pay_date ); 
    
                $re_date = strtotime($element->schedule_date);
                $re_year = date("Y", $re_date ); 
                $re_month = date("m", $re_date ); 
                if ( ($pay_month == $re_month) && ($pay_year==$re_year) ) {
                    return $element->schedule_date;
                }
            }
            
            return false;
            }



function findScheduleByType($type,$array){
    $data=array();
    foreach ( $array as $element ) {
        if ( $type == $element->type ) {
            array_push($data,$element);
        }
    }
    
    return $data;
    }

    function findScheduleBetweenNumber($from_num,$to_num,$array){
        $data=array();
        foreach ( $array as $element ) {
            if ( ($element->loan_no > $from_num ) && ( $element->loan_no <= $to_num) ) {
                array_push($data,$element);
            }
        }
        
        return $data;
        }

        function current_posting($array){
            $data=array();
            foreach ( $array as $element ) {
                $current_date=new DateTime();
                $current_year = date("Y"); 
                $current_month = date("m"); 
    
                $re_date = strtotime($element->entry_date);
                $current_re_year = date("Y", $re_date ); 
                $current_re_month = date("m", $re_date ); 
                if ( ($current_month == $current_re_month) && ($current_year==$current_re_year) ) {
                    array_push($data,$element);
                }
            }
            
            return $data;
            }
?>
@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false)}}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
<link href="{{ asset('theme/select2_v4.1.0/select2.min.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<style>
    @media print {
      a[href]:after {
        content: none !important;
      }
    }
    th{
        white-space: nowrap;
    }
    .form-group{
        margin-bottom: 0px !important;
    }
    span.select2.select2-container.select2-container--default {
        width: 100% !important;
    }
</style>
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>Master Loan List Report</span>
    </header>
    <div class="panel-body">
        <div class="position-center" style="width:95%;">
            <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('rpt_loan_detail_list') }}" id="search_frm">
                <div class="row">
                <div class="col-md-3"> 
                    <label for="client_name">{{ trans('multiple.m_client_name') }}</label>
                    <select id="customer" name="customer_id" class="form-control"></select>  
                </div>
                <div class="col-md-3">                                            
                    <label for="Company">Company</label>
                    <select id="company" name="company" class="form-control">
                        <option value="">Select company</option>
                            <option value="1" {{ (1 == Request::get('company'))?'selected' : "" }}>East Land and Home Co., Ltd</option>
                            <option value="2" {{ (2 == Request::get('company'))?'selected' : "" }}>BS Land and Home Co., Ltd</option>
                
                    </select>                                           
                </div>
                <div class="col-md-2"> 
                <label for="project_id">Project</label>
                    {{-- <input type="text" class="form-control" id="project_id" name="project_id"/> --}}
                    <select class="form-control" id="project_id" name="project_id"></select>
                </div>
                <div class="col-lg-2">
                    <label for="unit_type_id" >{{ trans('unit.unit_type') }}</label>
                        {{-- <input type="text" class="form-control" id="unit_type_id" name="unit_type_id"/> --}}
                        <select class="form-control" id="unit_type_id" name="unit_type_id">
                        </select>
                        
                </div>
                <div class="col-md-2"> 
                        <label for="unit_id">{{ trans('unit.unit') }}</label>
                        {{-- <input type="text" class="form-control" id="unit_id" name="unit_id"/> --}}
                        <select name="unit_id" class="form-control" id="unit_id"></select>

                </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-md-3"> 
                        <label for="contract_id">{{ trans('report.rpt_contract_id') }}</label>
                        <input type="text" class="form-control" id="contract_id" name="contract_id" value="{{ $contract_id }}" />
                    </div>
                    <div class="col-md-3">                                            
                        <label for="t_from">{{trans('teller.t_from')}}</label>
                        <input type="text" name="t_from" class="form-control" id="t_from" value="{{ $from_date }}"/>                                          
                    </div>
                    <div class="col-md-3">                                            
                        <label for="t_to">{{trans('teller.t_to')}}</label>
                        <input type="text" name="t_to" class="form-control" id="t_to" value="{{ $to_date }}"/>                                         
                    </div>
                    <?php
                    $loan_type = $product_type;
                    $loan_status = config('static_data.loan_status');
                    ?>
                    <div class="col-md-3"> 
                    <label for="inputCardnumber">{{ trans('multiple.m_status') }}</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">-</option>
                        @foreach($loan_status as $key => $value)
                        @if(array_key_exists($status,$loan_status))
                        <option value="{{ $key }}"
                                @if(isset($status))
                                @if($status == $key)
                                selected
                                @endif
                                @endif>{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group pull-right">
                            <input type="hidden" name="offset" value="<?php echo $offset ?>" />
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                            <button class="btn btn-primary" type="submit" name="is_excel" value="1"><i class="fa fa-download"></i> {{ trans('report.xrpt_export') }}</button>
                            <button class="btn btn-primary" type="submit" name="is_csv" value="1"><i class="fa fa-download"></i> {{ trans('report.rpt_export') }}</button>
                        
                            {{-- <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                            <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a> --}}
                        </div>
                    </div>
                </div>
            </form>
        {{-- </div> --}}
        <div class="page">
            <div class="custom-pagi">
                <span class="pagi_label">Number of Rows:</span>
                <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                <a href="#" class="btn btn-danger">Go</a>
            </div>
        </div>
        <br/><br/><br/><br/>
        <section id="unseen" class="ox-scroll">
          <div id="printArea">
            @include('api.report_header')
            <table  class="table table-striped table-bordered" style="width:100%">
                <thead>
                <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_no') }}</th>
                <th style="text-align: center; vertical-align: middle;">Payment Option</th>
                <th style="text-align: center; vertical-align: middle;">Company</th>
                <th style="text-align: center; vertical-align: middle;">Main Project</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('loan.l_contact_date') }}</th>
                <th style="text-align: center; vertical-align: middle;">ឈ្មោះជាភាសា​ KHMER (អតិថិជន)</th>
                <th style="text-align: center; vertical-align: middle;">ឈ្មោះជាភាសា ENGLISH (អតិថិជន)</th>
                <th style="text-align: center; vertical-align: middle;">ថ្ងៃខែឆ្នាំកំណើត</th>
                <th style="text-align: center; vertical-align: middle;">ភេទ/Gender</th>
                <th style="text-align: center; vertical-align: middle;">សញ្ជាតិ/Nationality</th>
                <th style="text-align: center; vertical-align: middle;">លេខអត្តសញ្ញាណ /Identification Number</th> 
                <th style="text-align: center; vertical-align: middle;">ថ្ងៃចេញប័ណ្ណ</th>  
                <th style="text-align: center; vertical-align: middle;">មានសុពលភាពដល់</th>
                <th style="text-align: center; vertical-align: middle;">ប័ណ្ណចេញដោយ</th>  
                <th style="text-align: center; vertical-align: middle;">អាស័យដ្ឋានអតិថិជន (Customer Address) </th>                       
                <th style="text-align: center; vertical-align: middle;">Customer Phone</th>
                <th style="text-align: center; vertical-align: middle;">ឈ្មោះជាភាសាKHMER (អ្នកចូលរួម)</th>
                <th style="text-align: center; vertical-align: middle;">ឈ្មោះជាភាសាENGLISH (អ្នកចូលរួម)</th>
                <th style="text-align: center; vertical-align: middle;">ថ្ងៃខែឆ្នាំកំណើត</th>
                <th style="text-align: center; vertical-align: middle;">ភេទ/Gender</th>
                <th style="text-align: center; vertical-align: middle;">សញ្ជាតិ/Nationality</th>
                <th style="text-align: center; vertical-align: middle;">លេខអត្តសញ្ញាណ /Identification Number</th> 
                <th style="text-align: center; vertical-align: middle;">ថ្ងៃចេញប័ណ្ណ</th>  
                <th style="text-align: center; vertical-align: middle;">មានសុពលភាពដល់</th>
                <th style="text-align: center; vertical-align: middle;">ប័ណ្ណចេញដោយ</th>  
                <th style="text-align: center; vertical-align: middle;">អាស័យដ្ឋានអ្នកចូលរួម (Co-borrower Address) </th>                       
                <th style="text-align: center; vertical-align: middle;">Co-Borrower Phone</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('unit.unit_type') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('unit.unit') }}</th>
                <th style="text-align: center; vertical-align: middle;">ទំហំសំណង់ Unit Size- ទទឹង<th>
                <th style="text-align: center; vertical-align: middle;">ទំហំសំណង់ Unit Size - បណ្តោយ<th>
                <th style="text-align: center; vertical-align: middle;">ទំហំដី Land Size - ទទឹង<th>
                <th style="text-align: center; vertical-align: middle;">ទំហំដី Land Size - បណ្តោយ<th>
                <th style="text-align: center; vertical-align: middle;">Admin Fee</th>
                <th style="text-align: center; vertical-align: middle;">Title Transfer Fee</th>
                <th style="text-align: center; vertical-align: middle;">Stamp Tax Fee</th>
                <th style="text-align: center; vertical-align: middle;">Renovation Fee</th>
                <th style="text-align: center; vertical-align: middle;">Electricity Fee</th>
                <th style="text-align: center; vertical-align: middle;">Sport Club Fee</th>
                <th style="text-align: center; vertical-align: middle;">Water Fee</th>
                <th style="text-align: center; vertical-align: middle;">Rental Fee</th>                               
                <th style="text-align: center; vertical-align: middle;">Maintenance Fee</th>
                <th style="text-align: center; vertical-align: middle;">Entrance Card Fee</th>
                <th style="text-align: center; vertical-align: middle;">Internet Service Fee</th>
                <th style="text-align: center; vertical-align: middle;">CCTV Fee</th>
                <th style="text-align: center; vertical-align: middle;">Other Fee Charge</th>
                <th style="text-align: center; vertical-align: middle;">Total Payment</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('loan.contract_deadline') }}</th>
                <th style="text-align: center; vertical-align: middle;">Deadline(Month)</th>
                <th style="text-align: center; vertical-align: middle;">Extended Deadline</th>
                <th style="text-align: center; vertical-align: middle;">Handover Deadline</th>
                <th style="text-align: center; vertical-align: middle;">Unit Selling Price</th>
                <th style="text-align: center; vertical-align: middle;"> Discount Promotion</th>   
                <th style="text-align: center; vertical-align: middle;">{{ trans('Discount (Payment Option)') }}</th>   
                <th style="text-align: center; vertical-align: middle;">{{ trans('Others Discount') }}</th>                                                    
                <th style="text-align: center; vertical-align: middle;">Total Discount</th>
                <th style="text-align: center; vertical-align: middle;">Net Selling Price</th>
                <th style="text-align: center; vertical-align: middle;">Clearance Amount</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_loan_amount') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_disbursement_date') }}</th>
                <th style="text-align: center; vertical-align: middle;">Deposit Date</th>
                <th style="text-align: center; vertical-align: middle;">Deposit Amount</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Down Payment') }}</th>
                <th style="text-align: center; vertical-align: middle;">First Down Payment Date</th>
                <th style="text-align: center; vertical-align: middle;">Last Down Payment Date</th>
                <th style="text-align: center; vertical-align: middle;">First Installment Date</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_maturity_date') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('loan.l_tenure') }}</th>
                <th style="text-align: center; vertical-align: middle;">Annual Interest</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_int_rate') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_int_rate_type') }}</th>
                <th style="text-align: center; vertical-align: middle;">Total Interest</th>
                <th style="text-align: center; vertical-align: middle;">Interest Paid</th>
                <th style="text-align: center; vertical-align: middle;">Outstanding Interest</th> 
                <th style="text-align: center; vertical-align: middle;">Principal Paid</th>
                <th style="text-align: center; vertical-align: middle;">Pmt.No</th>
                <th style="text-align: center; vertical-align: middle;">Total Customer Paid</th>   
                <th style="text-align: center; vertical-align: middle;">Outstanding Principle</th> 
                <th style="text-align: center; vertical-align: middle;">Total Outstanding</th> 
                <th style="text-align: center; vertical-align: middle;">{{ trans('Penalty ($/%)') }}</th>
                <th style="text-align: center; vertical-align: middle;">Schedule Date</th>     
                <th style="text-align: center; vertical-align: middle;">In.t</th> 
                <th style="text-align: center; vertical-align: middle;">Pri.</th> 
                <th style="text-align: center; vertical-align: middle;">Schdule Amount</th>
                <th style="text-align: center; vertical-align: middle;">Last Posting Date</th> 
                <th style="text-align: center; vertical-align: middle;">Last Payment Date</th>
                <th style="text-align: center; vertical-align: middle;">Arrear Days</th>
                <th style="text-align: center; vertical-align: middle;">Arrear Date</th>
                <th style="text-align: center; vertical-align: middle;">Installment Late</th>
                <th style="text-align: center; vertical-align: middle;">Total Overdue Amount</th>
                <th style="text-align: center; vertical-align: middle;">Overdue Interest</th>
                <th style="text-align: center; vertical-align: middle;">Overdue Principal</th>
                <th style="text-align: center; vertical-align: middle;">Penalty Amount</th>
                <th style="text-align: center; vertical-align: middle;">DD Balance</th>
                <th style="text-align: center; vertical-align: middle;">Posting Date</th>
                <th style="text-align: center; vertical-align: middle;">Installment Amount</th>
                <th style="text-align: center; vertical-align: middle;">Down-Payment</th>
                <th style="text-align: center; vertical-align: middle;">Pay-Off Amount</th>
                <th style="text-align: center; vertical-align: middle;">Penalty Fee</th>
                
                <th style="text-align: center; vertical-align: middle;">Loan Status</th>
                <th style="text-align: center; vertical-align: middle;">Submit by</th>
                <th style="text-align: center; vertical-align: middle;">Submit Date</th>
                									

                </thead>
                <tbody style="vertical-align: middle">
                    <?php $i = 0;//dd($loans);?>
                    @forelse($loans as $l)
                        <tr>
                            <?php
                            $total_interest=floatval($l->RepaymentSchedules->sum('interest'));                           
                            $paid_interest=floatval($l->payment->sum('paid_interest'));
                            $paid_principal= floatval($l->payment->sum('paid_principal'));
                            $total_customer_Paid=floatval($paid_principal) + floatval($paid_interest);
                            $outstanding_Interest=floatval($total_interest) - floatval($paid_interest);
                            if(!empty($l->transaction) && count($l->transaction) > 0){
                               if($l->transaction[0]->trans_type==='Pay-Off'){
                                $outstanding_Interest=floatval($l->transaction[0]->balance) > 0 ? floatval($l->transaction[0]->balance) : 0.00;
                               }
                                
                            }                            
                            $totalall_outstanding=floatval($l->client_loan_account->balance) + floatval($outstanding_Interest);
                            
                            $current_date=new DateTime();
                            $re_date=new DateTime($l->schedule_date);
                            $schedule_date=null;
                            $schdule_amount=0;
                            $paid_interest=0;
                            $paid_principal= 0;
            
                            $current_schedule=current_schedule($l->schedule);
                            if($current_schedule){
                                $schedule_date=$current_schedule->schedule_date;                     
                                $schdule_amount=floatval($current_schedule->total_payment);                                
                                $paid_interest=$l->payment?$l->payment[0]->paid_interest:0;
                                $paid_principal= $l->payment?$l->payment[0]->paid_principal:0;

                            }
                            $interest=$l->payment?floatval($l->payment[0]->paid_interest):0;
                            $principal=$l->payment?floatval($l->payment[0]->paid_principal):0;

                            $total_principal=floatval($l->RepaymentSchedules->sum('principal'));

                            $total=floatval($total_principal) + floatval($total_interest);


                            $admin_fee=0;
                            $title_transfer_fee=0;
                            $stamp_tax_fee=0;
                            $water_fee=0;
                            $electricity_fee=0;
                            $maintenance_fee=0;
                            $renovation_fee=0;
                            $sport_club_fee=0;
                            $rental_fee=0;
                            $entrance_card_fee=0;
                            $internet_service_fee=0;
                            $CCTV_Fee=0;
                            $Other_Fee_Charge=0;
                            $isOther_Fee_Charge=true;
                            if(!empty($l->feecharge) && count($l->feecharge) > 0){
                                foreach($l->feecharge as $fc){                                     
                                    $admin_fee_key='Admin Fee';
                                    if(preg_match("/{$admin_fee_key}/i", $fc->note)) {
                                        $admin_fee=floatval($admin_fee)+($fc->charge_amount);
                                        $isOther_Fee_Charge=false;
                                    }

                                    $title_transfer_fee_key='Tittle Transfer';
                                    if(preg_match("/{$title_transfer_fee_key}/i", $fc->note)) {
                                        $title_transfer_fee=floatval($title_transfer_fee)+($fc->charge_amount);
                                        $isOther_Fee_Charge=false;
                                    }

                                    $stamp_tax_fee_key='Stamp Tax';
                                    if(preg_match("/{$stamp_tax_fee_key}/i", $fc->note)) {
                                        $stamp_tax_fee=floatval($stamp_tax_fee)+($fc->charge_amount);
                                        $isOther_Fee_Charge=false;
                                    }

                                    $water_fee_key='Water Fee';
                                    if(preg_match("/{$water_fee_key}/i", $fc->note)) {
                                        $water_fee=floatval($water_fee)+($fc->charge_amount);
                                        $isOther_Fee_Charge=false;
                                    }

                                    $electricity_fee_key='Electricity Fee';
                                    if(preg_match("/{$electricity_fee_key}/i", $fc->note)) {
                                        $electricity_fee=floatval($electricity_fee)+($fc->charge_amount);
                                        $isOther_Fee_Charge=false;
                                    }

                                    
                                    $maintenance_fee_key='Maintenance Fee';
                                    if(preg_match("/{$maintenance_fee_key}/i", $fc->note)) {
                                        $maintenance_fee=floatval($maintenance_fee)+($fc->charge_amount);
                                        $isOther_Fee_Charge=false;
                                    }

                                    $renovation_fee_key='Renovation';
                                    if(preg_match("/{$renovation_fee_key}/i", $fc->note)) {
                                        $renovation_fee=floatval($renovation_fee)+($fc->charge_amount);
                                        $isOther_Fee_Charge=false;
                                    }                                    
                                    $sport_club_fee_key='Sport Club';
                                    if(preg_match("/{$sport_club_fee_key}/i", $fc->note)) {
                                        $sport_club_fee=floatval($sport_club_fee)+($fc->charge_amount);
                                        $isOther_Fee_Charge=false;
                                    }
                                    $rental_fee_key='Rental Fee';
                                    if(preg_match("/{$rental_fee_key}/i", $fc->note)) {
                                        $rental_fee=floatval($rental_fee)+($fc->charge_amount);
                                        $isOther_Fee_Charge=false;
                                    }
                                    $entrance_card_fee_key='Entrance Card';
                                    if(preg_match("/{$entrance_card_fee_key}/i", $fc->note)) {
                                        $entrance_card_fee=floatval($entrance_card_fee)+($fc->charge_amount);
                                        $isOther_Fee_Charge=false;
                                    }
                                    $internet_service_fee_key='Internet Service';
                                    if(preg_match("/{$internet_service_fee_key}/i", $fc->note)) {
                                        $internet_service_fee=floatval($internet_service_fee)+($fc->charge_amount);
                                        $isOther_Fee_Charge=false;
                                    }

                                    $CCTV_Fee_key='CCTV';
                                    if(preg_match("/{$CCTV_Fee_key}/i", $fc->note)) {
                                        $CCTV_Fee=floatval($CCTV_Fee)+($fc->charge_amount);
                                        $isOther_Fee_Charge=false;
                                    }

                                    if($isOther_Fee_Charge){
                                        $Other_Fee_Charge=floatval($Other_Fee_Charge)+($fc->charge_amount);
                                    }
                                }
                        
                            }
                            $deposit_date=null;
                            $deposit_amount=0;
                            $posting_Date=null;
                            $Installment_Amount=0;
                            $Post_Down_Payment=0;
                            $Post_Pay_Off=0;
                            $Post_Penalty_Fee=0;
                            $Total_Payment=0;
                            if(!empty($l->coa_journal_detail) && count($l->coa_journal_detail) > 0){
                                // dd($l->coa_journal_detail);
                                $deposit_date=$l->coa_journal_detail[0]->entry_date;
                                $deposit_amount=$l->coa_journal_detail[0]->credit;
                                $coa_journal_detail_credit=coa_journal_detail_credit($l->coa_journal_detail);   
                                $entry_date=$coa_journal_detail_credit[count($coa_journal_detail_credit)-1]->entry_date;
                                
                                $cu_date=new DateTime();
                                $cu_year = date("Y"); 
                                $cu_month = date("m");
                                
                                $_entry_date = strtotime($entry_date);
                                $entry_date_year = date("Y", $_entry_date ); 
                                $entry_date_month = date("m", $_entry_date );
                                if(($cu_month == $entry_date_month) && ($cu_year==$entry_date_year)){
                                    $posting_Date=$coa_journal_detail_credit[count($coa_journal_detail_credit)-1]->entry_date;
                                    $current_posting=current_posting($coa_journal_detail_credit);
                                    if(!empty($current_posting) && count($current_posting) > 0){
                                        foreach ( $current_posting as $element ) {
                                            if($element->trans_type=='Loan Installment'){
                                                $Installment_Amount=floatval($Installment_Amount)+floatval($element->credit);
                                            }
                                            if($element->trans_type=='Down-Payment'){
                                                $Post_Down_Payment=floatval($Post_Down_Payment)+floatval($element->credit);
                                            }
                                            if($element->trans_type=='Pay-Off'){
                                                $Post_Pay_Off=floatval($Post_Pay_Off)+floatval($element->credit);
                                            }
                                            if($element->trans_type=='Penalty Fee'){
                                                $Post_Penalty_Fee=floatval($Post_Penalty_Fee)+floatval($element->credit);
                                            }

                                            
                                        }
                                    }

                                    
                                }
                                
                                
                            }
                            $Total_Payment=floatval($Installment_Amount) + floatval($Post_Down_Payment) + floatval($Post_Pay_Off) + floatval($Post_Penalty_Fee) + floatval($admin_fee) + floatval($title_transfer_fee) + floatval($stamp_tax_fee) + floatval($water_fee) + floatval($electricity_fee) + floatval($maintenance_fee) + floatval($renovation_fee) + floatval($sport_club_fee) + floatval($rental_fee) + floatval($entrance_card_fee) + floatval($internet_service_fee) + floatval($CCTV_Fee) + floatval($Other_Fee_Charge); 

                            $First_Down_Payment_Date=null;
                            $Last_Down_Payment_Date=null;
                            $First_Installment_Date=null;
                            $Last_Payment_Date=null;
                            if(!empty($l->schedule) && count($l->schedule) > 0){
                                
                                $downpayment=findScheduleByType('downpayment',$l->schedule);
                                if(!empty($downpayment) && count($downpayment) > 0){
                                    $First_Down_Payment_Date=$downpayment['0']->schedule_date;
                                    $last_index=count($downpayment);
                                    $Last_Down_Payment_Date=$downpayment[$last_index-1]->schedule_date;

                                }
                                $repayment =findScheduleByType('loan',$l->schedule);
                                if(!empty($repayment) && count($repayment) > 0){
                                    $First_Installment_Date=$repayment['0']->schedule_date;

                                }
                                if(!empty($l->schedule) && count($l->schedule) > 0){
                                    $r=findObjectById($l->payment->max('payment_month'),$l->schedule);
                                    if(!empty($r) && count($r) > 0){
                                        $Last_Payment_Date=$r->schedule_date;                                        
                                        
                                    }
                                }

                            }

                            $End_Of_Cycle_Amount='-';
                            $payoff='-';
                            if($l->payoff){
                                $payoff=floatval($l->payoff->principal) + floatval($l->payoff->interest) + floatval($l->payoff->penalty);
                            }
                                        
                                     // move the internal pointer to the end of the array
                              // fetches the key of the element pointed to by the internal pointer
                        $Last_posting_Date=null;
                        $total_schedule_Paid=0;
                        $schedule_date_pay=null;
                        $late_day=0;
                        $total_overdue_amount=0;
                        $Interest_overdue_amount=0 ;
                        $principal_overdue_amount=0 ;
                        if($l->RepaymentSchedules){
                            $r=findObjectById($l->payment->max('payment_month'),$l->RepaymentSchedules);


                            // -------------
                            if($l->payment){
                                $cu_date=new DateTime();
                                $cu_year = date("Y"); 
                                $cu_month = date("m");

                                if(!empty($l->schedule) && count($l->schedule) > 0){
                                    $payment=findObjectBykey($l->payment->max('payment_month'),$l->payment);
                                    if(!empty($payment) && count($payment) > 0){                                       
                                        $s_date = strtotime($payment->repayment_date);
                                        $s_re_year = date("Y", $s_date ); 
                                        $s_re_month = date("m", $s_date ); 
            
                                        if ( ($cu_month == $s_re_month) && ($cu_year==$s_re_year) ) {
                                            $payment=findObjectBykey($l->payment->max('payment_month')-1,$l->payment);
                                            $Last_posting_Date=$payment->repayment_date;
                                        }else{                                            
                                            $Last_posting_Date=$payment->repayment_date;
                                        }

                                        
                                    }
                                    $repayment_schedule =findScheduleByType('loan',$l->schedule);
                                    if(!empty($repayment_schedule) && count($repayment_schedule) > 0){
                                        $repayment_schedule_date=$repayment_schedule[count($repayment_schedule)-1]->schedule_date;
                                        $re_schedule_date = new DateTime($repayment_schedule_date);        
                                        if ($re_schedule_date <= $cu_date ) {
                                            $last_schedule_number=$repayment_schedule[count($repayment_schedule)-1]->loan_no;
                                            $late_day=floatval($last_schedule_number) - floatval($l->payment->max('payment_month'));
                                            $cu_payment=findObjectById($l->payment->max('payment_month'),$l->schedule);
                                            $total_num_late=floatval($cu_payment->loan_no) + floatval($late_day);
                                            $schedule_late=findScheduleBetweenNumber($cu_payment->loan_no,$total_num_late,$l->schedule);
                                            if(!empty($schedule_late) && count($schedule_late) > 0){
                                                foreach ( $schedule_late as $element ) {
                                                    $total_overdue_amount= floatval($total_overdue_amount) + floatval($element->total_payment);
                                                    $Interest_overdue_amount= floatval($Interest_overdue_amount) + floatval($element->interest); 
                                                    $principal_overdue_amount=floatval($principal_overdue_amount) + floatval($element->principal);
                                                }
                                            }

                                        }else{
                                            $current_schedule=current_schedule($l->schedule);
                                            $last_schedule_number=$current_schedule->loan_no;
                                            $cu_schedule_date=$current_schedule->schedule_date;
                                            $cu_payment=findObjectById($l->payment->max('payment_month'),$l->schedule);
                                            $cu_payment_date=$cu_payment->schedule_date;

                                            $re_cu_schedule_date = new DateTime($cu_schedule_date); 
                                            $re_cu_payment_date = new DateTime(); 


                                            $_re_cu_schedule = strtotime($cu_schedule_date);
                                            $re_cu_schedule_year = date("Y", $_re_cu_schedule ); 
                                            $re_cu_schedule_month = date("m", $_re_cu_schedule );

                                            if ( ($re_cu_schedule_month == $cu_month) && ($re_cu_schedule_year==$cu_year) ) {
                                                
                                                if ($re_cu_schedule_date > $cu_date ) {
                                                    $late_day=floatval($last_schedule_number) - floatval($l->payment->max('payment_month')) -1;
                                                    $cu_payment=findObjectById($l->payment->max('payment_month'),$l->schedule);
                                                    $total_num_late=floatval($cu_payment->loan_no) + floatval($late_day);
                                                    $schedule_late=findScheduleBetweenNumber($cu_payment->loan_no,$total_num_late,$l->schedule);
                                                    if(!empty($schedule_late) && count($schedule_late) > 0){
                                                        foreach ( $schedule_late as $element ) {
                                                            $total_overdue_amount= floatval($total_overdue_amount) + floatval($element->total_payment);
                                                            $Interest_overdue_amount= floatval($Interest_overdue_amount) + floatval($element->interest); 
                                                            $principal_overdue_amount=floatval($principal_overdue_amount) + floatval($element->principal);
                                                        }
                                                    }
                                                }else{
                                                    $late_day=floatval($last_schedule_number) - floatval($l->payment->max('payment_month'));
                                                    $cu_payment=findObjectById($l->payment->max('payment_month'),$l->schedule);
                                                    $total_num_late=floatval($cu_payment->loan_no) + floatval($late_day);
                                                    $schedule_late=findScheduleBetweenNumber($cu_payment->loan_no,$total_num_late,$l->schedule);
                                                    if(!empty($schedule_late) && count($schedule_late) > 0){
                                                        foreach ( $schedule_late as $element ) {
                                                            $total_overdue_amount= floatval($total_overdue_amount) + floatval($element->total_payment);
                                                            $Interest_overdue_amount= floatval($Interest_overdue_amount) + floatval($element->interest); 
                                                            $principal_overdue_amount=floatval($principal_overdue_amount) + floatval($element->principal);
                                                        }
                                                    }
                                                }

                                            }else{
                                                $late_day=floatval($last_schedule_number) - floatval($l->payment->max('payment_month'));
                                                $cu_payment=findObjectById($l->payment->max('payment_month'),$l->schedule);
                                                $total_num_late=floatval($cu_payment->loan_no) + floatval($late_day);
                                                $schedule_late=findScheduleBetweenNumber($cu_payment->loan_no,$total_num_late,$l->schedule);
                                                if(!empty($schedule_late) && count($schedule_late) > 0){
                                                    foreach ( $schedule_late as $element ) {
                                                        $total_overdue_amount= floatval($total_overdue_amount) + floatval($element->total_payment);
                                                        $Interest_overdue_amount= floatval($Interest_overdue_amount) + floatval($element->interest); 
                                                        $principal_overdue_amount=floatval($principal_overdue_amount) + floatval($element->principal);
                                                    }
                                                }
                                            }
                                        }
    
                                    }
                                  
                           

                            }
                            } 
                            // -------------



                            $total_schedule_Paid=floatval($r->interest) + floatval($r->principal);
                            $schedule_date_pay=$r->schedule_date;
                        }
                        $total_payment_Paid=0;
                        if($l->payment){
                            $r=findObjectBykey($l->payment->max('payment_month'),$l->payment);
                            $total_payment_Paid=floatval($r->paid_interest) + floatval($r->paid_principal);
                        }
                        $Arrear_Date=null;
                        $Arrear_day=0;
                        if(!empty($l->schedule) && count($l->schedule) > 0){
                            $arrea_schedule=findObjectById($l->payment->max('payment_month') + 1,$l->schedule);
                            if(!empty($arrea_schedule) && count($arrea_schedule) > 0){
                                $arrea_schedule_date=$arrea_schedule->schedule_date;   
                                $rdate = new DateTime($arrea_schedule_date);
                                $next_schedule_date=$rdate;
                                $Arrear_Date=$next_schedule_date; 
                                $cu_date=new DateTime();
                                if($next_schedule_date>$cu_date){
                                    $Arrear_day = $next_schedule_date->diff($cu_date)->format("%a");
                                    $Arrear_day=floatval($Arrear_day) * -1;
                                }else{
                                    $Arrear_day = $next_schedule_date->diff($cu_date)->format("%a");
                                }                                
                            }
                        }
                        $net_selling_price=0;
                        $totalall_discount=floatval($l->discount_promotion) + floatval($l->discount_other) + floatval($l->amount_discount_payment_option);
                        $net_selling_price=floatval($l->unit_sale_price) - floatval($totalall_discount);

                    $time = strtotime($l->contract_deadline);
                    $contract_deadline_total = date("Y-m-d", strtotime("+".number_format($l->extended_deadline,0)." month", $time));

                                $i++;
                                $date = null;
                                $note=$l->status_remark;
                                switch ($l->status) {
                                    case 1: $date = $l->submitted_on;
                                        break;
                                    case 2: $date = !empty($l->approval->approval_date) ? $l->approval->approval_date : null;
                                        break;
                                    case 3: $date = $l->disburse_date;
                                        break;
                                    case 4: $date = $l->rejected_date;
                                        break;
                                    case 5: $date = !empty($l->settlement_date) ? $l->settlement_date : null;
                                            $note=$l->write_off_remark;
                                        break;
                                    case 6: $date = !empty($l->settlement_date) ? $l->settlement_date : null;
                                        break;
                                    case 7: $date = $l->submitted_on;
                                        break;
                                    case 8: $date = !empty($l->approval->approval_date) ? $l->approval->approval_date : null;
                                        break;
                                    case 9: $date = !empty($l->settlement_date) ? $l->payoff->payoff_date : null;
                                        $End_Of_Cycle_Amount='Payoff';
                                        break;
                                    case 10:$date = !empty($l->settlement_date) ? $l->settlement_date : null;
                                        $End_Of_Cycle_Amount='End Of Cycle Amount';
                                        break;
                                    case 11: 
                                        $note=$l->write_off_remark;
                                    break;
                                    default: break;
                                }
                            ?>
                            <td style="text-align: center">{{$i}}</td>
                            <td style="text-align: center">
                              {{ !empty($l->PaymentOptions) ? $l->PaymentOptions->name : 'Other' }}
                            </td>
                            <td style="vertical-align: middle;text-align: center">{{$l->company}}</td>
                            <td style="vertical-align: middle;text-align: center">
                            <a style="text-decoration: underline" href="{{ route('loan_detail', [$l->id])}}">{{ !empty($l->short_code)?$l->short_code:"-" }}</a>
                        </td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->contract_date)?date('d-M-Y',strtotime($l->contract_date)):"-" }}</td>
                            <td style="vertical-align: middle">{{ !empty($l->client->general->first()->family_name_kh)?$l->client->general->first()->family_name_kh .' '.$l->client->general->first()->first_name_kh:'N/A' }}</td>
                            <td style="vertical-align: middle">{{ !empty($l->client)  ? $l->client->client_name : "-" }}</td>
                            <td style="vertical-align: middle">{{ !empty($l->client->general->first()->date_of_birth)? date("d-M-Y", strtotime($l->client->general->first()->date_of_birth)):'N/A' }}</td>
                            <td style="vertical-align: middle">{{ !empty($l->client->general->first())?$l->client->general->first()->gender:'N/A' }}</td>
                            <td style="vertical-align: middle">{{ !empty($l->client->general->first())?$l->client->general->first()->national_code:'N/A' }}</td>
                            <td>{{ !empty($l->client->Identification->first())?$l->client->Identification->first()->id_number:'N/A' }}</td>
                            <td>{{ !empty($l->client->Identification->first()) ? Date("d-M-Y", strtotime($l->client->Identification->first()->issued_date)):'N/A' }}</td>
                            <td>{{ !empty($l->client->Identification->first()) ? $l->client->Identification->first()->issued_by:'N/A'}}</td>
                            <td>{{ !empty($l->client->Identification->first()) ?  date("d-M-Y", strtotime($l->client->Identification->first()->id_expiry_date)):'N/A' }}</td>
                            <td style="vertical-align: middle">{{ !empty($l->client->address)? $l->client->address:'N/A' }}</td>
                            <td style="vertical-align: middle">{{ $l->client->phone1}}{{ !empty($l->client->phone2)? ' / '. $l->client->phone2:''}}</td>

  
                            <td style="vertical-align: middle">{{ !empty($l->co_borrowers->Clients->ClientCbcGeneral->family_name_kh)?$l->co_borrowers->Clients->ClientCbcGeneral->family_name_kh.' '.$l->co_borrowers->Clients->ClientCbcGeneral->first_name_kh:'N/A'  }}</td>
                            <td style="vertical-align: middle">{{ !empty($l->co_borrowers->Clients->ClientCbcGeneral->family_name)?$l->co_borrowers->Clients->ClientCbcGeneral->family_name.' '.$l->co_borrowers->Clients->ClientCbcGeneral->first_name:'N/A'  }}</td>
                            <td style="vertical-align: middle">{{ !empty($l->co_borrowers->Clients->ClientCbcGeneral->date_of_birth)? date("d-M-Y", strtotime($l->co_borrowers->Clients->ClientCbcGeneral->date_of_birth)):'N/A' }}</td>
                            <td style="vertical-align: middle">{{ !empty($l->co_borrowers->Clients->ClientCbcGeneral->gender)?$l->co_borrowers->Clients->ClientCbcGeneral->gender:'N/A' }}</td>
                            <td style="vertical-align: middle">{{ !empty($l->co_borrowers->Clients->ClientCbcGeneral->national_code)?$l->co_borrowers->Clients->ClientCbcGeneral->national_code:'N/A' }}</td>
                            <td>{{ !empty($l->client->Identification->first())?$l->client->Identification->first()->id_number:'N/A' }}</td>
                            <td>{{ !empty($l->co_borrowers->Clients->ClientCbcGeneral->issued_date) ? Date("d-M-Y", strtotime($l->co_borrowers->Clients->ClientCbcGeneral->issued_date)):'N/A' }}</td>
                            <td>{{ !empty($l->co_borrowers->Clients->ClientCbcGeneral->issued_by) ? $l->co_borrowers->Clients->ClientCbcGeneral->issued_by:'N/A'}}</td>
                            <td>{{ !empty($l->co_borrowers->Clients->ClientCbcGeneral->id_expiry_date) ?  date("d-M-Y", strtotime($l->co_borrowers->Clients->ClientCbcGeneral->id_expiry_date)):'N/A' }}</td>
                            <td style="vertical-align: middle">{{ !empty($l->co_borrowers->Clients->ClientCbcGeneral->address)? $l->co_borrowers->Clients->ClientCbcGeneral->address:'N/A' }}</td>
                            <td style="vertical-align: middle">{{ $l->co_borrowers->Clients->ClientCbcGeneral->phone1}}{{ !empty($l->co_borrowers->Clients->ClientCbcGeneral->phone2)? ' / '. $l->co_borrowers->Clients->ClientCbcGeneral->phone2:''}}</td> 


                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->name)?$l->name:"-" }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->code)?$l->code:"-" }}</td>
                            <td style="vertical-align: middle;text-align: center">{{$l->building_size_width }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ $l->building_size_length}}</td>
                            <td style="vertical-align: middle;text-align: center">{{ $l->land_size_width}}</td>
                            <td style="vertical-align: middle;text-align: center">{{ $l->land_size_length}}</td>
                          

                            <td style="text-align: center">{{number_format($admin_fee,2)}}</td> 
                            <td style="text-align: center">{{ number_format($title_transfer_fee,2)}}</td>
                            <td style="text-align: center">{{number_format($stamp_tax_fee,2)}}</td>
                            <td style="text-align: center">{{number_format($renovation_fee,2)}}</td> 
                            <td style="text-align: center">{{number_format($electricity_fee,2)}}</td>
                            <td style="text-align: center">{{number_format($sport_club_fee,2)}}</td>
                            <td style="text-align: center">{{number_format($water_fee,2)}}</td>
                            <td style="text-align: center">{{number_format($rental_fee,2)}}</td>
                            <td style="text-align: center">{{number_format($maintenance_fee,2)}}</td>  
                            <td style="text-align: center">{{number_format($entrance_card_fee,2)}}</td>
                            <td style="text-align: center">{{number_format($internet_service_fee,2)}}</td> 
                            <td style="text-align: center">{{number_format($CCTV_Fee,2)}}</td>                            
                            <td style="text-align: center">{{number_format($Other_Fee_Charge,2)}}</td>                            
                            <td style="text-align: center">{{number_format($Total_Payment,2)}}</td>                        

                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->contract_deadline)?date('d-M-Y',strtotime($l->contract_deadline)):"-" }}</td>                            
                            <td style="text-align: center">{{$l->handover_dateline ? $l->handover_dateline : ''}} Month</td>
                            <td style="text-align: center">{{$l->extended_deadline ? number_format($l->extended_deadline,0) : ''}} Month </td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($contract_deadline_total)?date('d-M-Y',strtotime($contract_deadline_total)):"-" }}</td>
                            




                            <td style="text-align: right;">{{ number_format($l->unit_sale_price,2,'.',',') }}</td>

                            <td style="text-align: right;">{{ number_format($l->discount_promotion,2,'.',',') }}</td>
                            <td style="text-align: right;">{{ number_format($l->amount_discount_payment_option,2,'.',',') }}</td>
                            <td style="text-align: right;">{{ number_format($l->discount_other,2,'.',',') }}</td>
                            <td style="text-align: right;">{{ $l->status_remark }}</td>                           
                            <td style="text-align: justify">{{ number_format(($l->discount_promotion + $l->discount_other + $l->amount_discount_payment_option),2) }}</td>
                            <td style="text-align: right;">{{ number_format($net_selling_price,2,'.','') }}</td>
                            <td style="text-align: right;">{{ number_format($l->clearance_amount,2,'.',',') }}</td>
                            <td>{{$l->original_amount ? number_format($l->original_amount,2,'.',',') : number_format($l->loan_amount,2,'.',',')}}</td>
                            <td style="text-align: center">{{$l->disburse_date ? date("d-M-Y", strtotime($l->disburse_date)) : '-'}}</td>
                            <td style="text-align: center">{{$deposit_date ? date("d-M-Y", strtotime($deposit_date)) : '-'}}</td>
                            <td >{{ number_format($deposit_amount,2,'.',',') }}</td>     
                            <td >{{ number_format($l->down_payment_value,2,'.',',') }}</td>                            
                            <td style="text-align: center">{{$First_Down_Payment_Date ? date("d-M-Y", strtotime($First_Down_Payment_Date)) : '-'}}</td>
                            <td style="text-align: center">{{$Last_Down_Payment_Date ? date("d-M-Y", strtotime($Last_Down_Payment_Date)) : '-'}}</td>
                            <td style="text-align: center">{{$First_Installment_Date ? date("d-M-Y", strtotime($First_Installment_Date)) : '-'}}</td>                            
                            <td style="text-align: center">{{$l->schedule_date ? date("d-M-Y", strtotime($l->schedule_date)) : '-'}}</td>

                            <td style="text-align: right">{{$l->status===8?$l->installment_duration:$l->loan_duration}}</td> 
                            <td style="text-align: right">{{$l->annual_interest}}</td> 
                            
                            <td style="text-align: center">{{$l->interest_rate ? number_format($l->interest_rate, 2) : ''}}%</td>
                            <td style="text-align: center">{{$l->rate_type}}</td>
                            <td style="text-align: center">{{ number_format($l->RepaymentSchedules->sum('interest'),2) }}</td>
                            <td style="text-align: center">{{ number_format($l->payment->sum('paid_interest'),2) }}</td>
                            <td style="text-align: center">{{ ($outstanding_Interest > 0) ? number_format($outstanding_Interest, 2, '.', '') : 0.00 }}</td>
                            <td style="text-align: center">{{ number_format($l->payment->sum('paid_principal'),2) }}</td>
                            <td style="text-align: center">{{ $l->payment->max('payment_month') }}</td>
                            <td style="text-align: center">{{ number_format($total_customer_Paid,2) }}</td>
                            <td style="text-align: center">{{($l->client_loan_account->balance > 0) ? number_format($l->client_loan_account->balance, 2, '.', '') : 0.00 }} {{$l->client_loan_account->currencies->code}}</td>
                            <td style="text-align: center">{{ ($totalall_outstanding > 0) ? number_format($totalall_outstanding, 2, '.', '') : 0.00 }}</td>
                            <td style="text-align: center;white-space: nowrap;">{{ number_format($l->penalty_rate1,2) }} {{$l->loan_penalty_type}}</td>
                            <td style="text-align: center">{{$schedule_date ? date("d-M-Y", strtotime($schedule_date)) : '-'}}</td>
                            <td style="vertical-align: middle;text-align: center">{{ number_format($paid_interest,2) }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ number_format($paid_principal,2) }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ number_format($schdule_amount,2) }}</td>
                            <td style="text-align: center">{{$Last_posting_Date ? date("d-M-Y", strtotime($Last_posting_Date)) : '-'}}</td>
                            <td style="text-align: center">{{$Last_Payment_Date ? date("d-M-Y", strtotime($Last_Payment_Date)) : '-'}}</td>
                            <td style="text-align: center">{{$Arrear_day}}</td>
                            <td style="text-align: center">{{$Arrear_Date ? $Arrear_Date->format('d-M-Y') : '-'}}</td>                            
                            <td style="text-align: center">{{ $late_day }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ number_format($total_overdue_amount,2) }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ number_format($Interest_overdue_amount,2) }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ number_format($principal_overdue_amount,2) }}</td>
                            <td style="text-align: center">{{ number_format($l->payment->sum('repayment_owed'),2) }}</td> 
                            <td style="text-align: center"> {{number_format($l->loan_drawdow_acc->balance,2,'.',',')}} {{$loan->client_loan_account->currencies->code}}</td>
                            <td style="text-align: center">{{$posting_Date ? date("d-M-Y", strtotime($posting_Date)) : '-'}}</td>
                            <td style="text-align: center">{{ number_format($Installment_Amount,2)}}</td>
                            <td style="text-align: center">{{ number_format($Post_Down_Payment,2)}}</td>
                            <td style="text-align: center">{{ number_format($Post_Pay_Off,2)}}</td>
                            <td style="text-align: center">{{ number_format($Post_Penalty_Fee,2) }}</td>
                           
                            <td style="text-align: center">{{$loan_status[$l->status]}}</td>
                            <td style="text-align: center">{{$l->user ? $l->user->name : '-'}}</td> 
                            <td style="text-align: center">{{ date('d-M-Y',strtotime($l->submitted_on)) }}</td>
                            <td style="text-align: center">{{ !empty($loan->approval_date) ? date('d-M-Y',strtotime($loan->approval_date)): '-' }}</td>
                            <td style="text-align: center">{{$l->disburse_user ? $l->disburse_user->name : '-'}}</td>                            
                            <td style="text-align: center">{{$l->disburse_date ? date("d-M-Y", strtotime($l->disburse_date)) : '-'}}</td>
                            <td align="justify" style="white-space: nowrap;">{{$l->status_remark}}</td>
                            <td style="text-align: left;">{{$l->status_remark_2}}</td>
                        </tr>
                    @empty
                    <tr><td colspan="24" class="text-center">{{ trans('multiple.m_no_result') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            {!! str_replace('?page', '&page', $loans->appends(\Request::except('page'))->render()) !!}
          </div>
            <!-- <div class="page pull-right">
                <?PHP
                // echo $loans->appends([
                //     'contract_id' => Input::get('contract_id'),
                //     'status' => Input::get('status'),
                //     'offset' => Input::get('offset')
                // ])->render();
                ?>
            </div> -->
        </section>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script src="{{ asset('theme/select2_v4.1.0/select2.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>

<script type="text/javascript">
$(document).ready(function () {
    $("#company").select2();
    var customer_id = '<?php echo $customer_id;?>';
    $("#customer").select2({
        minimumInputLength: -1,
        placeholder: "Select Customer ID",
        data:[{id: customer_id,text:'<?php echo $client->client_name.'('.$client->cus_acc.')';?>'}],
        allowClear: true,
        ajax:{
            url: '/teller/get-client',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            timeout: 3000,
            data: function (term) {
                return {term: term.term}
            },
            processResults: function(data) {
                if(data){
                    return {
                        results: $.map(data, function (vals,keys) {
                            return {
                                text: vals.client_name+' ( ' + vals.cus_acc + ')',
                                slug: vals.client_name,
                                id: vals.id,
                                name:'customer_id'
                            }
                        })
                    }; 
                }else{
                    $('<div id="loading"></div>').appendTo('body');
                    imgLoading(true,'Permission denied!!!',4,'warning');
                    return
                }
            }
        }
    });
    $("#customer").val(customer_id).trigger('change');

    $('#t_from, #t_to').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                // setDate: new Date()
            });
    
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
            new TableExport(document.getElementById('loan_status_summary'), {
                formats: ['csv'],
                filename:'loan_status_summary'
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
    });

    $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('loan_status_summary'), {
                    formats: ['xlsx'],
                    filename: 'loan_status_summary'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });
    var project_id = '<?php echo $project_id;?>';
    var unit_type_id = '<?php echo $unit_type_id;?>';
    var unit_id = '<?php echo $unit_id;?>';
    $("#project_id").select2({
        minimumInputLength: 0,
        placeholder: "Select Project",
        data:[{id: project_id,text:'<?php echo $project->dealer.'('.$project->dynamic_code.'-'.$project->short_code.')';?>'}],
        allowClear: true,
        ajax:{
            url: '{{ route('get_project_unit_unittype') }}',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            timeout: 3000,
            data: function (term) {
                return {
                    search: term.term,
                }
            },
            processResults: function(data) {
                if(data.project){
                    return {
                        results: 
                        $.map(data.project, function (vals,keys){
                            return {
                                text: vals.dealer +'('+vals.dynamic_code+'-'+vals.short_code+')',
                                slug: vals.dealer,
                                id: vals.id,
                                name:'project_id'
                            }
                        })
                    }; 
                }else{
                    $('<div id="loading"></div>').appendTo('body');
                    imgLoading(true,'Permission denied!!!',4,'warning');
                    return
                }
            },
        }
    }); 
    $('#project_id').val(project_id).trigger('change');

    $("#unit_type_id").select2({
        minimumInputLength: 0,
        placeholder: "Select Unit Type",
        data:[{id: unit_type_id,text:'<?php echo $unit_type->name.'-'.$unit_type->short_code;?>'}],
        allowClear: true,
        ajax:{
            url: '{{ route('get_project_unit_unittype') }}',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            timeout: 3000,
            data: function (term) {
                return {
                    search_unit_type: term.term,
                }
            },
            processResults: function(data) {
                if(data.unit_type){
                    return {
                        results: 
                        $.map(data.unit_type, function (vals,keys){
                            return {
                                text: vals.name +'-'+vals.short_code,
                                slug: vals.name,
                                id: vals.id,
                                name:'unit_type_id'
                            }
                        })
                    }; 
                }else{
                    $('<div id="loading"></div>').appendTo('body');
                    imgLoading(true,'Permission denied!!!',4,'warning');
                    return
                }
            },
        }
    }); 
    $("#unit_type_id").val(unit_type_id).trigger('change');

    $("#unit_id").select2({
        minimumInputLength: 0,
        placeholder: "Select Unit",
        data:[{id:unit_id,text:'<?php echo $unit->code.'('.$unit->price.')';?>'}],
         allowClear: true,
        ajax:{
            url: '{{ route('get_project_unit_unittype') }}',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            timeout: 3000,
            data: function (term) {
                return {
                    search_unit: term.term,
                }
            },
            processResults: function(data) {
                if(data.unit){
                    return {
                        results: 
                        $.map(data.unit, function (vals,keys){
                            return {
                                text: vals.code+'('+vals.price+')',
                                slug: vals.code,
                                id: vals.id,
                                name:'unit_id'
                            }
                        })
                    }; 
                }else{
                    $('<div id="loading"></div>').appendTo('body');
                    imgLoading(true,'Permission denied!!!',4,'warning');
                    return
                }
            },
        }
    }); 
    $("#unit_id").val(unit_id).trigger('change');
});
</script>
@endsection
