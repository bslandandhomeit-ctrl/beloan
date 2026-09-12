@extends('layouts.app')
@section('css')
    <link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/select2_v4.1.0/select2.min.css',isset($secure) ? false : false) }}"/>
    <link rel="" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css', isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
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
    .cash_out{
        color: red;
    }
    @page{
        size: A4 landscape;
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
<?php $currency_symbol = config('static_data.currency_symbol'); ?>
@section('content')
    <div class="row">
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <span>{{ trans('sidebar.sb_till_tra_sum')}}</span>
                </header>
                <div class="col-md-12">
                    <div class="row">
                        <div class="panel-body">
                            <section>
                                <form role="form" method="get" action="{{ route('teller_receipt_detail') }}">
                                    
                                    <div class="row">
                                        <div class="col-md-3">                                       
                                                <label for="customer"> {{trans('customer.cus_customer_id')}}</label>
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
                                        <div class="col-md-3">                                            
                                                <label for="project">Project</label>
                                                <select id="project" name="project_id" class="form-control">
                                                    <option value="">Select Project</option>
                                                    @foreach ($projects as $vals)
                                                        <option value="{{ $vals->id }}" {{ ($vals->id == $project_id)?'selected' : "" }}>{{ $vals->dealer.' - '.$vals->dealer }}</option>
                                                    @endforeach
                                                </select>                                            
                                        </div>
                                        <div class="col-md-3">                                            
                                            <label for="teller">Teller Name</label>
                                            <select id="teller" name="teller_id" class="form-control">
                                                <option value="">Select Teller</option>
                                                @foreach($teller as $item)
                                                    <option value="{{ $item->id }}" {{ ($item->id == $teller_id)?'selected' : '' }}>{{ $item->name }} ({{ $item->username }})</option>
                                                @endforeach
                                            </select>                                            
                                        </div>
                                    </div><br/>
                                    <div class="row">
                                        <div class="col-md-2">                                            
                                            <label for="t_from">{{trans('teller.t_from')}}</label>
                                            <input type="text" name="t_from" class="form-control" id="t_from" value="{{ $from_date }}"/>                                          
                                        </div>
                                        <div class="col-md-2">                                            
                                            <label for="t_to">{{trans('teller.t_to')}}</label>
                                            <input type="text" name="t_to" class="form-control" id="t_to" value="{{ $to_date }}"/>                                         
                                        </div>
                                        <div class="col-md-3">                                            
                                            <label for="Company">Company Type</label>
                                            <select id="company_type" name="company_type" class="form-control">
                                                <option value="">Select company type</option>                                            
                                                    <option value="Real Estate" {{ ("Real Estate" == Request::get('company_type'))?'selected' : "" }}>Real Estate</option>
                                                    <option value="Property" {{ ("Property" == Request::get('company_type'))?'selected' : "" }}>Property</option>
                                                    <option value="IIP" {{ ("IIP" == Request::get('company_type'))?'selected' : "" }}>IIP</option>
                                            
                                            </select>                                           
                                        </div>
                                        <div class="col-md-2">                                            
                                            <label for="methode">Methode</label>
                                            <!-- <select  name="methode" class="form-control">
                                                <option value="">Select Methode</option>
                                                @foreach (config('static_data.payment_type') as $key => $item)
                                                    <?php if($key==0) continue; ?>
                                                    <option value="{{ $item}}" {{ ($item == Request::get('methode'))?'selected' : "" }}>{{ $item}}</option>
                                                @endforeach
                                            </select> -->
                                            <select  name="methode" class="form-control">
                                                <option value="">Select Methode</option>
                                                <option value="Cash In Vault" {{ ("Cash In Vault" == Request::get('methode'))?'selected' : "" }}>Cash In Vault</option>
                                                <option value="Cash on Hand-Teller" {{ ("Cash on Hand-Teller" == Request::get('methode'))?'selected' : "" }}>Cash on Hand-Teller</option>
                                                <option value="Bank Transfer" {{ ("Bank Transfer" == Request::get('methode'))?'selected' : "" }}>Bank Transfer</option>
                                                <option value="Bank China" {{ ("Bank China" == Request::get('methode'))?'selected' : "" }}>Bank China</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">                                            
                                            <label for="deposit_type">Payment Type</label>
                                            <select id="deposit_type" name="deposit_type" class="form-control">
                                                <option value="">Select Payment Type</option>

                                                    <option value="Loan Installment" {{ ("Loan Installment" == Request::get('deposit_type'))?'selected' : "" }}>Loan Installment</option>
                                                    <option value="Deposit" {{ ("Deposit" == Request::get('deposit_type'))?'selected' : "" }}>Deposit</option>
                                                    <option value="Down-Payment" {{ ("Down-Payment" == Request::get('deposit_type'))?'selected' : "" }}>Down-Payment</option>
                                                    <option value="Pay-Off" {{ ("Pay-Off" == Request::get('deposit_type'))?'selected' : "" }}>Pay-Off</option>
                                                    <option value="Penalty Fee" {{ ("Penalty Fee" == Request::get('deposit_type'))?'selected' : "" }}>Penalty Fee</option>
                                                    <option value="Maintenance Fee" {{ ("Maintenance Fee" == Request::get('deposit_type'))?'selected' : "" }}>Maintenance Fee</option>
                                                    <option value="Admin Fee Sub Sale" {{ ("Admin Fee Sub Sale" == Request::get('deposit_type'))?'selected' : "" }}>Admin Fee Sub Sale</option>
                                                    <option value="Admin Fee Owner Ship" {{ ("Admin Fee Owner Ship" == Request::get('deposit_type'))?'selected' : "" }}>Admin Fee Owner Ship</option>
                                                    <option value="Admin Fee Reschdule" {{ ("Admin Fee Reschdule" == Request::get('deposit_type'))?'selected' : "" }}>Admin Fee Reschdule</option>
                                                    <option value="Admin Fee Change Unit" {{ ("Admin Fee Change Unit" == Request::get('deposit_type'))?'selected' : "" }}>Admin Fee Change Unit</option>
                                                    <option value="Tittle Transfer Fee" {{ ("Tittle Transfer Fee" == Request::get('deposit_type'))?'selected' : "" }}>Tittle Transfer Fee</option>
                                                    <option value="Stamp Tax Fee" {{ ("Stamp Tax Fee" == Request::get('deposit_type'))?'selected' : "" }}>Stamp Tax Fee</option>
                                                    <option value="Renovation Fee" {{ ("Renovation Fee" == Request::get('deposit_type'))?'selected' : "" }}>Renovation Fee</option>
                                                    <option value="Water Fee" {{ ("Water Fee" == Request::get('deposit_type'))?'selected' : "" }}>Water Fee</option>
                                                    <option value="Rental Fee" {{ ("Rental Fee" == Request::get('deposit_type'))?'selected' : "" }}>Rental Fee</option>
                                                    <option value="Entrance Card Fee" {{ ("Entrance Card Fee" == Request::get('deposit_type'))?'selected' : "" }}>Entrance Card Fee</option>
                                                    <option value="Internet Service Fee" {{ ("Internet Service Fee" == Request::get('deposit_type'))?'selected' : "" }}>Internet Service Fee</option>
                                                    <option value="CCTV Fee" {{ ("CCTV Fee" == Request::get('deposit_type'))?'selected' : "" }}>CCTV Fee</option>
                    
                                            </select>                                          
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group pull-right" style="margin-top: 7px;">
                                                <button type="submit" class="btn btn-info searchs">{{trans('multiple.m_search')}}</button>
                                                <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                                                <button type="submit" class="btn btn-primary" name="is_excel" id="is_excel" value="1"><i class="fa fa-download"></i> {{ trans('report.xrpt_export') }}</button>
                                                <button type="submit" class="btn btn-primary" name="is_csv" id="is_csv" value="1"><i class="fa fa-download"></i> {{ trans('report.rpt_export') }}</button>
                                            </div>
                                            <div class="col-md-3 pull-right">
                                                <input type="text" class="form-control" placeholder="Search.." name="search" id="search" value="{{Request::get('search')}}"/>
                                        </div>
                                        </div>
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="_token" id="token" value="{{csrf_token()}}">
                <div class="panel-body">
                    <section id="flip-scroll">
                      <div id="printArea">
                        <div id="header-report">                        
                            <!-- @include('api.report_header') -->
                            <?php $data=[
                                // 'company_branch' =>$company_branch                            
                            ]  ?>
                            @include('api.report_header_custom', $data)
                        </div>
                        <table class="table table-bordered table-striped table-condensed tillTran" id="tran">
                            <thead class="table-header">
                                <tr>
                                    <th>{{ trans('multiple.m_no')  }}</th>
                                    <th>{{ trans('Transaction Date' ) }}</th>
                                    <th>{{ trans('teller.contract_id' ) }}</th>
                                    <th>{{ trans('customer.cus_customer_id') }}</th>
                                    <th>{{ trans('customer.cus_customer_name') }}</th>
                                    <th>Project</th>
                                    <th>{{ trans('unit.unit_type') }}</th>
                                    <th>{{ trans('unit.unit') }}</th>
                                    <th>{{ trans('teller.to_account') }}</th>
                                    <th>{{ trans('multiple.methode') }}</th>
                                    <th>Type</th>
                                    <th>{{ trans('teller.company') }}</th>
                                    <th>{{ trans('teller.payment_type') }}</th>
                                    <th>{{ trans('teller.description')}}</th>
                                    <th>PMT.No</th>
                                    <th>PMT Date</th>
                                    <th>Interest</th>
                                    <th>Principal</th>
                                    <th>{{ trans('teller.paid_amount')}}</th>
                                    <th>{{ trans('teller.t_teller_name') }}</th>
                                    <th>{{ trans('teller.teller_status')}}</th>
                                </tr>
                            </thead>
                            <tbody id="trans_results">
                                <?php                              
                                $total = 0;?>
                                @forelse($teller_transaction as $key => $val)
                                <?php
                            //     $pmt_no=null;
                            //     $pmt_date=null;
                            //     $interest=0;
                            //     $principal=0;
                            //    if($val->deposit_type=='Loan Installment' && $val->type == "Cash Deposit"){
                            //     foreach ($repayment as $re) {   
                                                               
                            //         if(!empty($val->principal)){
                            //             if($val->loan_id==$re['loan_id'] && floatval($re['no']) == floatval($val->pmt_no)){
                            //                 $interest=round($re['interest'],2) ;
                            //                 $principal=round($re['principal'],2) ;
                            //                 $pmt_date=$re['schedule_date'];
                            //                 $pmt_no=$re['no'];
                                
                            //             } 
                            //         }else{
                            //             if($val->loan_id==$re['loan_id'] && floatval($re['no']) == floatval($re['last_payment']->payment_month)){
                            //                 $interest=round($re['interest'],2) ;
                            //                 $principal=round($re['principal'],2) ;
                            //                 $pmt_date=$re['schedule_date'];
                            //                 $pmt_no=$re['no'];
                                
                            //             } 

                            //         }                                   
                            //     }
                            // }
                            // $class_paid_amount='cash_in';
                            // $paid_amount=!empty($val->cash_in)?number_format((float)round($val->cash_in,2), 2, '.', ''):0;
                            // if($val->type=='Withdraw'){
                            //     $paid_amount=!empty($val->cash_out)?number_format((float)round($val->cash_out,2), 2, '.', ''):0;
                            //     $paid_amount= $paid_amount*-1;
                            //     $class_paid_amount='cash_out';
                            // }
                                ?>
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ !empty($val->posting_date)?date('d-M-Y',strtotime($val->posting_date)):"" }}</td>
                                        <!-- <td>{{ !empty($val->contract_id)?$val->contract_id:"" }}</td>
                                        <td>{{ !empty($val->cus_acc)?$val->cus_acc:"" }}</td>
                                        <td>{{ !empty($val->client_name)?$val->client_name:"" }}</td>
                                        <td>{{ !empty($val->dealer_en)?$val->dealer_en:"" }}</td>
                                        <td>{{ !empty($val->name)?$val->name.'('.$val->short_code.')':"" }}</td>
                                        <td>{{ !empty($val->unit_code)?$val->unit_code:"" }}</td>
                                        <td>{{ !empty($val->to_account)?$val->to_account:"" }}</td>
                                        <td>{{ !empty($val->methode)?$val->methode:"" }}</td>
                                        <td>{{ !empty($val->type)?$val->type:"" }}</td>
                                        <td>{{ !empty($val->deposit_company)?$val->deposit_company:"" }}</td>
                                        <td>{{ !empty($val->deposit_type)?$val->deposit_type:"" }}</td>
                                        <td>{{ !empty($val->description)?$val->description:"" }}</td>
                                        <td>{{ !empty($pmt_no)?$pmt_no:"" }}</td>
                                        <td>{{ !empty($pmt_date)?date('d-M-Y',strtotime($pmt_date)):"" }}</td>
                                        <td style="text-align:right;">${{ $interest}}</td>
                                        <td style="text-align:right;">${{ $principal }}</td>
                                        <td class={{$class_paid_amount}} style="text-align:right;">${{ $paid_amount }}</td>
                                        <td>{{ !empty($val->username)?$val->username:"" }}</td>
                                        <td>{{ $val->approve_status == 1 ? "Authorized" : "Unauthorized"}}</td> -->
                                        <?php /* $total += round($paid_amount,2); */?>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="text-center">No data found.</td></tr>
                                @endforelse
                                <tr>
                                    <th colspan="18">Total:</th>
                                    <th style="text-align:right;">${{ number_format($total, 2, '.', '') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                     
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th colspan="13">Total:</th>
                                    <th colspan="2">${{ number_format($total, 2, '.', '') }}</th>
                                </tr>
                            </tfoot> -->
                        </table>
                        @include('api.report_footer_nbc_custom')
                      </div>
                    </section>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('js/accounting.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/select2_v4.1.0/select2.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
    
    <script>
        $(document).keypress(function(e) {
            var keycode;
            if (window.event) keycode = window.event.keyCode;
            else if (e) keycode = e.which;
            else return true;

            if (keycode == 13)
            {
                $(".searchs").click();
            }
        });

        $("#export").click(function (event) {
        var con = confirm("Do you really want to export to CSV file?");
        if(con == true){
            new TableExport(document.getElementById('tran'), {
                formats: ['csv'],
                filename:"teller_transaction"
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
        event.preventDefault();
        });
        $("#xexport").click(function (event) {
            var con = confirm("Do you really want to export to Excel file?");
            if(con == true){
                new TableExport(document.getElementById('tran'), {
                        formats: ['xlsx'],
                        filename: 'teller_transaction'
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });
        $(document).ready(function () {
            $('#t_from, #t_to').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
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
            $("#project").select2();
            $("#teller").select2();
            $("#methode").select2();
            $("#company").select2();
        });
    </script>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/advanced-datatable/css/jquery.dataTables.css',isset($secure) ? false : false) }}"/>
    <script src="{{ asset('theme/js/advanced-datatable/js/jquery.dataTables.js',isset($secure) ? false : false) }}"></script>
@endsection
