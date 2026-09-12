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
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>Sale Order List</span>
        <span style="float: right"><a href="{{route ('add_sale') }}" class="btn btn-success"><i class="fa fa-plus"></i> Add New </a></span>
    </header>
    <div class="panel-body">
        <div>
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('sale_report_detail') }}">
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
                            <select id="project_id" name="project_id" class="form-control">
                                <option value="">Select Project</option>
                                @foreach ($projects as $vals)
                                    <option value="{{ $vals->id }}" {{ ($vals->id == $project_id)?'selected' : "" }}>{{ $vals->dealer.' - '.$vals->dealer }}</option>
                                @endforeach
                            </select>                                            
                    </div>
                    <div class="col-md-3">                                            
                        <label for="Status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="">Select status</option>
                                <option value="Ordered" {{ ('Ordered' == Request::get('status'))?'selected' : "" }}>Ordered</option>
                                <option value="Accepted" {{ ('Accepted' == Request::get('status'))?'selected' : "" }}>Accepted</option>
                                <option value="Canceled" {{ ('Canceled' == Request::get('status'))?'selected' : "" }}>Canceled</option>
                    
                        </select>                                           
                    </div>
                    
            </div><br/>
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group pull-right" style="margin-top: 7px;">
                            <button type="submit" class="btn btn-info searchs">{{trans('multiple.m_search')}}</button>
                            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                            <button type="submit" class="btn btn-primary" name="is_excel" id="is_excel" value="1"><i class="fa fa-download"></i> {{ trans('report.xrpt_export') }}</button>
                            <button type="submit" class="btn btn-primary" name="is_csv" id="is_csv" value="1"><i class="fa fa-download"></i> {{ trans('report.rpt_export') }}</button>
                        </div>
                        <div class="col-md-7 pull-right">
                            <div class="col-md-3">   
                                <input type="text" name="t_from" class="form-control" id="t_from" value="<?php echo $from_date?>"/>                                          
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="t_to" class="form-control" id="t_to" value="<?php echo $to_date?>"/>                                         
                            </div>
                            <div class="col-md-6">  
                                <input type="text" class="form-control" placeholder="Search.." name="search" id="search" value="{{Request::get('search')}}"/>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
        $loan_status = config('static_data.loan_status');
        ?>
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
                    <th style="vertical-align:middle; text-align: center;">Main Project Code</th>
                    <th style="vertical-align: middle;text-align: center;">Item No.</th>
                    <th style="vertical-align:middle; text-align: center;">Variant Code</th>
                    <th style="vertical-align:middle; text-align: center;">Contract No</th>
                    <th style="vertical-align:middle; text-align: center;">Cust. No.</th>
                    <th style="vertical-align: middle;text-align: center;">Customer Name</th>
                    <th style="vertical-align: middle;text-align: center;">Customer Phone No.</th>
                    <th style="vertical-align:middle; text-align: center;">Status</th>
                    <th style="vertical-align: middle;text-align: center;">Deposit Date</th>
                    <th style="vertical-align: middle;text-align: center;">Contract Sign Date</th>
                    <th style="vertical-align: middle;text-align: center;">Contract Deadline</th>
                    <th style="vertical-align:middle; text-align: center;">Remark</th>
                    <th style="vertical-align:middle; text-align: center;">Unit Price</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('loan.down_payment') }}</th>
                    <th style="vertical-align:middle; text-align: center;">Down Payment Plan</th>
                     <th style="vertical-align:middle; text-align: center;">Monthly Payment</th>
                    <th style="vertical-align:middle; text-align: center;">Payment Option Name</th>
                    <th style="vertical-align:middle; text-align: center;">Total Discount Amount</th>
                    <th style="text-align: center; vertical-align: center;">Net Selling Price</th>
                    <th style="text-align: center; vertical-align: middle;">Total Interest</th>
                    <th style="vertical-align:middle; text-align: center;">Sale Level1</th> 
                    <th style="vertical-align:middle; text-align: center;">Sale Level2</th> 
                    <th style="vertical-align:middle; text-align: center;">Sale Person</th> 
                    <th style="vertical-align:middle; text-align: center;">Created On</th>
                    <th style="vertical-align:middle; text-align: center;">Created by</th>                     
                    </thead>

                    <tbody>
                        <?php $i=0?>
                        @forelse($lists as $d)
                        <?php
                         $i++;
                                $net_selling_price=0;
                                $totalall_discount=floatval($d->discount_promotion) + floatval($d->discount_other) + floatval($d->amount_discount_payment_option);
                                $net_selling_price=floatval($d->unit_sale_price) - floatval($totalall_discount);
                                $total_interest=0;
                                if(!empty($d->RepaymentSchedules) && count($d->RepaymentSchedules) > 0){
                                    $total_interest=floatval($d->RepaymentSchedules->sum('interest'));  
                                }
                                 $downPayment = \App\Models\RepaymentSchedule::where('loan_id', $d->id)->where('type','downpayment')->first();
                                 $schdulePayment = \App\Models\RepaymentSchedule::where('loan_id', $d->id)->where('type','loan')->first();
                        ?>
                            <tr>
                                <td class="isVerticalalign" align="center">{{ $i}}</td>
                                <td class="isVerticalalign">{{ $d->short_code }}</td>
                                <td class="isVerticalalign" >{{ $d->unit_type }}</td>
                                <td class="isVerticalalign">{{ $d->unit }}</td>
                                <td style="vertical-align: middle;text-align: center">
                                <a style="text-decoration: underline" href="{{ route('loan_detail', [$d->id])}}">{{ $d->contract_id ? $d->contract_id : '-'  }}</a>
                                </td>
                                <td style="vertical-align: middle;text-align: center;">
                                <a style="text-decoration: underline" href="{{ route('loan_account', [$d->loan_account_id])}}">
                                    {{ !empty($d->drawdown_acc)?$d->drawdown_acc:"-" }}
                                </a>
                                </td>
                                <td class="isVerticalalign">{{ $d->client_name}}</td>
                                <td style="vertical-align: middle">{{$d->phone1}}</td>
                                <td style="text-align: center">{{$loan_status[$d->status]}}</td>
                                <td class="isVerticalalign">{{ !empty($d->created_on)?date('d-M-Y',strtotime($d->created_on)):"N/A" }}</td>
                                <td class="isVerticalalign">{{ !empty($d->contract_date)?date('d-M-Y',strtotime($d->contract_date)):"N/A" }}</td>
                                <td class="isVerticalalign">{{ !empty($d->contract_deadline)?date('d-M-Y',strtotime($d->contract_deadline)):"N/A" }}</td>
                                <td class="isVerticalalign">{{ $d->remark }}</td>
                                <td class="isVerticalalign">${{ $d->unit_sale_price }}</td>
                                <td class="isVerticalalign">{{ !empty($d->down_payment)?number_format($d->down_payment,2):0 }}</td>
                                <td class="isVerticalalign">{{ !empty($downPayment)?number_format($downPayment->principal,2):0 }}</td>
                                <td class="isVerticalalign">{{ !empty($schdulePayment)?number_format($schdulePayment->interest + $schdulePayment->principal + $schdulePayment->fee + $schdulePayment->other_fee,2):0 }}</td>
                                <td style="text-align: center">
                                {{ !empty($d->PaymentOptions) ? $d->PaymentOptions->name : 'Other' }}
                                </td> 
                                <td style="text-align: justify">{{ number_format(($d->discount_promotion + $d->discount_other + $d->amount_discount_payment_option),2) }}</td>
                                <td style="text-align: right;">{{ number_format($net_selling_price,2,'.','') }}</td>
                                <td style="text-align: right;">{{ number_format($total_interest,2,'.','') }}</td>
                                <td class="isVerticalalign" >{{ $d->sale_persons?$d->sale_persons->parent->parent->name:'-' }}</td>
                                <td class="isVerticalalign" >{{ $d->sale_persons?$d->sale_persons->parent->name:'-' }}</td>
                                <td class="isVerticalalign" >{{ $d->sale_persons?$d->sale_persons->name:'-' }}</td>  
                                <td class="isVerticalalign">{{ !empty($d->created_on)?date('d-M-Y',strtotime($d->created_on)):"N/A" }}</td>
                                <td class="isVerticalalign">{{ !empty($d->created_by)?$d->created_by:"N/A" }}</td>                         
                                
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
            $("#project_id").select2();
            $("#company").select2();
            $("#status").select2();
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
            var company = '<?php echo $company;?>';
            if(company){
                getProjectByCompany();
                $("#project_id").val(company).trigger('change');
                
            }
           
        $("#company").on('change', function () {
            getProjectByCompany();           
        });

        });
        function getProjectByCompany(){
            var company_id = $('#company option:selected').val();
            $.ajax({
                url: '/getProjectByCompany',
                type: 'GET',
                dataType: "json",
                // processData: false,
                // contentType: false,
                data:{company_id:company_id},
                success: function (data) {
                    $('#project_id').html(data.option);
                    $("#project_id").select2();
                    var project_id = '<?php echo $project_id;?>';
                    if(project_id){
                        $("#project_id").val(project_id).trigger('change');
                    }
                }
            });
        }
    </script>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/advanced-datatable/css/jquery.dataTables.css',isset($secure) ? false : false) }}"/>
    <script src="{{ asset('theme/js/advanced-datatable/js/jquery.dataTables.js',isset($secure) ? false : false) }}"></script>
@endsection
