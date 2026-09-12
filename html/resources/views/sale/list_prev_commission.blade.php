<?php
function findStatusBykey($status,$array){

    foreach ( $array as $element ) {
        if ( $status == $element->status ) {
            return $element->status;
        }
    }
    
    return false;
    }
    $commission_status=Request::get('commission_status');
    $isPending=false;
    $isWithdrawal=false;
    if(empty($commission_status) || $commission_status==='Balance'){
        $isPending=true;
    }
    if($commission_status==='Withdrawal'){
        $isWithdrawal=true;
    }
?>
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
    .select2-container{

        margin: 8px 0px 8px 0px;
    }
    tbody>tr>td{
    padding: 5px;
    vertical-align: middle !important;
    }
    tbody>tr>td p {
    margin: 0;
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
        <span> Previous Commission List</span>
        <span style="float: right"><a href="{{route ('add_sale') }}" class="btn btn-success"><i class="fa fa-plus"></i> Add New </a></span>
    </header>
    <div class="panel-body">
        <div>
            
    @if (\Session::has('error'))
    <div class="alert alert-danger">
        <ul>
            <li>{!! \Session::get('error') !!}</li>
        </ul>
    </div>
@endif
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('list_prev_commission') }}">
            <div class="row">
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
                        <label class = "control-label">{{ trans('loan.unit_type') }}</label>
                        <select id="unit_type_id" class = "select2_type customer_select1"  style = "width: 100%;" name="unit_type_id">
                                    <option value="">-</option>
                        </select>
            </div>  
            <div class="col-md-3">
                        <label class="control-label">Sale Person</label>
                        <select id="sale_person"  class = "select2_type customer_select1" style="width: 100%" name="sale_person">
                        <option value="">-</option>
                            @foreach($saleRepresentative as $c)                          
                                <option value={{$c['id']}} @if($sale_person==$c['id']) selected="selected" @endif>{{$c['name']}}</option>
                            @endforeach
                        </select>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group pull-right" style="margin-top: 7px;">
                            <button id="btn_search" type="submit" class="btn btn-info searchs">{{trans('multiple.m_search')}}</button>
                            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                            <button type="submit" class="btn btn-primary" name="is_excel" id="is_excel" value="1"><i class="fa fa-download"></i> {{ trans('report.xrpt_export') }}</button>
                            <button type="submit" class="btn btn-primary" name="is_csv" id="is_csv" value="1"><i class="fa fa-download"></i> {{ trans('report.rpt_export') }}</button>
                        </div>
                        <div class="col-md-7 pull-right"> 
                        <div class="col-md-3"> 
                        <label class="control-label">Commission Period</label>
                        </div>
                            <div class="col-md-3">   
                                <input type="text" name="t_from" class="form-control" id="t_from" placeholder="From date" value="<?php echo $from_date?>"/>                                          
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="t_to" class="form-control" id="t_to" placeholder="To date" value="<?php echo $to_date?>"/>                                         
                            </div>                            
                                              
                            <div class="col-md-3">  
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
            <!-- <button class="btn btn-danger" id="pending_commission"><i class="fa fa-pencil"></i> Pending</button> -->
            <!-- <button class="btn btn-warning" id="withdraw_commission"><i class="fa fa-refresh"></i> Request withdraw </button> -->
            <!-- <button class="btn btn-primary" id="history_commission"><i class="fa fa-check-circle-o"></i> History</button> -->
            
            @if($isPending)
            <a id="request_withdraw_button"  href="#" class="btn btn-danger pull-right" title="Request withdraw"><i class="fa fa-plus"></i> Request Withdraw</a> 
            @endif
            @if($isWithdrawal)                                              
            <a id="markPaymentAll" href="#" class="btn btn-danger pull-right" title="Mark Payment"><span class="glyphicon glyphicon-usd"></span> Mark Payment</a>
            <a style="margin-right: 7px;" id="printAll" class="btn btn-warning pull-right" href="#"  target="_blank"><span class="glyphicon glyphicon-print"></span> Print</a> 
            
            @endif

            
             
            <br/>            
            @if($isWithdrawal)    
            <form role="form_request_withdraw" class="cmxform form-horizontal" id="form_request_withdraw" method="post" action="{{ route('all_commission_PrintOrMarkPayment') }}">
            @else
            <form role="form_request_withdraw" class="cmxform form-horizontal" id="form_request_withdraw" method="post" action="{{ route('request_all_withdraw_loan_prev') }}">
            @endif           
            <input type = "hidden" name = "_token" value = "{{ csrf_token() }}"/>
            <input type = "hidden" name = "company" value = "{{ Request::get('company') }}"/>
            <input type = "hidden" name = "project_id" value = "{{ Request::get('project_id') }}"/>
            <input type = "hidden" name = "unit_type_id" value = "{{ Request::get('unit_type_id') }}"/>
            <input type = "hidden" name = "sale_person" value = "{{ Request::get('sale_person') }}"/>
            <input style="display: none;" type="text" name="t_from" class="form-control"  placeholder="From date" value="<?php echo $from_date?>"/>                                          
            <input type="text" style="display: none;" name="t_to" class="form-control"  placeholder="To date" value="<?php echo $to_date?>"/>                                         
        
                <table class="table table-bordered table-striped table-condensed table-hover dealerTable" id="dealers_list">
                    <thead class="th-center">
                    <th  style="vertical-align:middle; text-align: center;"><input type="checkbox" id="selectAll" /></th>
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
                                        if(floatval($d->commission_value)>0){
                                            $status='Paid';
                                            $status_btn=' btn btn-default';
                                        }else{
                                            $status='Unit not commission';
                                            $status_btn=' btn btn-success';
                                        }                                      
                                       
                                        
                                    }
                                }else{
                                    $status_btn='btn btn-warning';
                                }
                                ?>
                            <tr>
                            <td class="isVerticalalign" align="center">
                                <input type="checkbox" id="{{ $i}}" name="comission_request[]" value="{{$d->id}}" />                                
                                <input type = "hidden"  id="commission_withdrawal_{{ $d->id}}" name = "commission_withdrawal" value = "{{$commission_withdrawal}}"/>
                                
                            </td> 
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
                                
                                <td class="isVerticalalign" >{{ $d->commission_type=='$'?'Fixed ($)':'Percentage (%)' }}</td>   
                                <td class="isVerticalalign" >{{$d->commission_value}}</td> 
                                <td style="text-align: center">{{ number_format($total_commission_paid,2,'.','') }}</td> 
                                <td style="text-align: center">{{ number_format($commission_balance,2,'.','') }}</td> 
                                @if($isWithdrawal)    
                                <td class="isVerticalalign define-width" align="center">
                                    @if($commission_withdrawal =='Approved')
                                        <p class="{{$status_btn}}">{{$commission_withdrawal}}</p>
                                        @else
                                        <p class="{{$status_btn}}">Pending</p>
                                        @endif                       
                                </td> 
                                @else
                                <td class="isVerticalalign define-width" align="center">
                                    @if($status =='Balance')
                                    <p class="{{$status_btn}}">{{$status}}</p>
                                    @else
                                    <p class="{{$status_btn}}">{{$status}}</p>
                                    @endif
                                </td> 
                                @endif      
                                <td class="isVerticalalign define-width" align="center">
                                    <a href="{{ route('list_sale_commission_detail',[$d->id]) }}" class="btn btn-info searchs" title="Detail">Detail</a>
                    
                                </td> 
                                            
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </from>
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
    
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>
       

function MessageBox(message) {
  $('<div></div>').appendTo('body')
    .html('<div><h6>' + message + '</h6></div>')
    .dialog({
      modal: true,
      title: 'Info',
      zIndex: 10000,
      autoOpen: true,
      width: 'auto',
      resizable: false,
      buttons: {
        Okay: function() {
          $(this).dialog("close");
        },
      },
      close: function(event, ui) {
        $(this).remove();
      }
    });
};

function ConfirmDialog(fromID,message) {
  $('<div></div>').appendTo('body')
    .html('<div><h6>' + message + '</h6></div>')
    .dialog({
      modal: true,
      title: 'Delete message',
      zIndex: 10000,
      autoOpen: true,
      width: 'auto',
      resizable: false,
      buttons: {
        Yes: function() {           
            $('#'+fromID).submit();
          $(this).dialog("close");
        },
        No: function() {
          $(this).dialog("close");
        }
      },
      close: function(event, ui) {
        $(this).remove();
      }
    });
};
function SelectItems(message) {
  $('<div></div>').appendTo('body')
    .html('<div><h6>' + message + '?</h6></div>')
    .dialog({
      modal: true,
      title: 'Info',
      zIndex: 10000,
      autoOpen: true,
      width: 'auto',
      resizable: false,
      buttons: {
        Ok: function() {
          $(this).dialog("close");
        }
      },
      close: function(event, ui) {
        $(this).remove();
      }
    });
};
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
            $("#unit_type_id").select2();
            $("#sale_person").select2();
            
            $('#t_from, #t_to').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
           
           
            var company = '<?php echo $company;?>';
            if(company){
                getProjectByCompany();
                $("#project_id").val(company).trigger('change');
                
            }
            var project_id = '<?php echo $project_id;?>';
            if(project_id){
                getUnitType();
                $("#unit_type_id").val(project_id).trigger('change');
                
            }
           
        $("#company").on('change', function () {
            getProjectByCompany();           
        });
        $(document).on('change', '#project_id', function () {
            getUnitType();
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
        function getUnitType(){
            var project_id = $('#project_id').val();
            $.ajax({
                url: '/getUnitType',
                type: 'GET',
                dataType: "json",
                // processData: false,
                // contentType: false,
                data:{project_id:project_id},
                success: function (data) {
                    $('#unit_type_id').html(data.option);
                    $("#unit_type_id").select2();
                    var unit_type_id = '<?php echo $unit_type_id;?>';
                    if(unit_type_id){
                        $("#unit_type_id").val(unit_type_id).trigger('change');
                    }
                }
            });
        }
        // $("#sale_person").on('change', function () {
        //     $('#btn_search').click();                  
        // });
        $("#t_to").on('change', function () {
            $('#btn_search').click();                  
        });
        

        $('#selectAll').click(function (e) {
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);

        });
        $("#request_withdraw_button").click(function(){

            var company=$("#company").val();
            if(company===''){
                MessageBox('Please select company!');
                return;
            }
            var project_id=$("#project_id").val();
            if(project_id===''){
                MessageBox('Please select project!');
                return;
            }
            var unit_type_id=$("#unit_type_id").val();
            if(unit_type_id===''){
                MessageBox('Please select unit type!');
                return;
            }
            var sale_person=$("#sale_person").val();
            if(sale_person===''){
                MessageBox('Please select sale person!');
                return;
            }
            var t_from=$("#t_from").val();
            if(t_from===''){
                MessageBox('Please select from date!');
                return;
            }
            var t_to=$("#t_to").val();
            if(t_to===''){
                MessageBox('Please select to date!');
                return;
            }
            var values = [];
            $('.checkbox-group').each(function() {
                $(this).find('input[type="checkbox"]:checked').each(function(i, v) {
                values.push($(v).val());
                });
                console.log(values);
            })
            if(values.length===0){
                alert('Please select an item in the list');
                return;
            }
      if (confirm("Do you want request commission in the list?")){
         $('#form_request_withdraw').submit();
      }
   });

   $("#pending_commission").click(function(){
                $('#search_frm').append('<input type="hidden" name="commission_status" value="Balance" />');                
                $('#btn_search').click();        
      
   });
   $("#withdraw_commission").click(function(){
                $('#search_frm').append('<input type="hidden" name="commission_status" value="Withdrawal" />');                
                $('#btn_search').click();          
      
   });
   $("#history_commission").click(function(){
                $('#search_frm').append('<input type="hidden" name="commission_status" value="Paid" />');                
                $('#btn_search').click();           
      
   });

   
   $("#printAll").click(function(){
            var values = [];
            $('.checkbox-group').each(function() {
                $(this).find('input[type="checkbox"]:checked').each(function(i, v) {
                var commission_withdrawal=$("#commission_withdrawal_"+$(v).val()).val();
                if(commission_withdrawal!=='Approved'){
                    SelectItems('Have same commission items not yet approved. Please approved it.');
                    return false;
                }
                values.push($(v).val());
                });
              
            })
            if(values.length===0){
                // alert('Please select an item in the list');
                SelectItems('Please select an item in the list.');
                return false;
            }

                $('#form_request_withdraw').append('<input id="print_request" type="hidden" name="type" value="printAll" />');
                ConfirmDialog("form_request_withdraw","Would you want print this request?");               
            

   });

   $("#markPaymentAll").click(function(){
            var values = [];
            var isApproved=true;
            $('.checkbox-group').each(function() {
                $(this).find('input[type="checkbox"]:checked').each(function(i, v) {
                var commission_withdrawal=$("#commission_withdrawal_"+$(v).val()).val();
                if(commission_withdrawal!=='Approved'){
                    SelectItems('Have same commission items not yet approved. Please approved it.');
                    isApproved=false;
                    return false;
                }
                values.push($(v).val());
                });
              
            })
            if(isApproved){
                if(values.length===0){
                // alert('Please select an item in the list');
                SelectItems('Please select an item in the list.');
                return false;
            }

                $('#form_request_withdraw').append('<input id="print_request" type="hidden" name="type" value="markPaymentAll" />');
                ConfirmDialog("form_request_withdraw","Would you want print this request?");    
            }       
            

   });

        
    </script>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/advanced-datatable/css/jquery.dataTables.css',isset($secure) ? false : false) }}"/>
    <script src="{{ asset('theme/js/advanced-datatable/js/jquery.dataTables.js',isset($secure) ? false : false) }}"></script>
@endsection
