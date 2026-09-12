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
    .define-width a {
            margin-bottom: 3px !important;
            display: inherit;
            padding-right: 10px;
            border: 1px groove;
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
    .fa-check-circle{
        color: #1fb5ad;
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
    .btn-default:hover {
    background-color: #57c8f1;
    border-color: #57c8f1;
    color: #fff;
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
        <span>Request withdraw commission list</span>
        <!-- <span style="float: right"><a href="{{route ('add_sale') }}" class="btn btn-success"><i class="fa fa-plus"></i> Add New </a></span> -->
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

            <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('getCommissionSettingList') }}">
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
</div>
<div class="row">
            <div class="col-md-12">
                <div class="col-md-7 pull-right">    
                    <div class="col-md-3 pull-right">              
                    <button type="submit" style = "width: 100%;     margin-top: 7px;" class="btn btn-info searchs">{{trans('multiple.m_search')}}</button>
                </div>
                <div class="col-md-4 pull-right">  
                        <input type="text" class="form-control" placeholder="Search.." name="search" id="search" value="{{Request::get('search')}}"/>
                    </div>
                 </div>
            </div>
</div>        
            <br/>
            <div class="row" style="display:none">
                <div class="col-md-12">
                        <div class=" pull-right" style="margin-top: 7px;">                            
                            <button class="btn btn-default" id="sm_approve"><i class="fa fa-pencil"></i> Acknowledged By</button>
                            <button class="btn btn-default" id="acc_approve"><i class="fa fa-pencil"></i> Checked By</button>
                            <button class="btn btn-default" id="hof_approve"><i class="fa fa-pencil"></i> Verified By</button>
                            <button class="btn btn-default" id="chairman_approve"><i class="fa fa-pencil"></i> Approved By</button>
                        </div>                       
                    </div>
                </div>
            </form>
        </div>
        <br/>
        <div id="printArea" class="ox-scroll">
            @include('api.report_header')
            <h4 class="sch_title">Request withdraw commission list</h4>
            <section id="commission_list" >
            <form role="form_request_withdraw" class="cmxform form-horizontal" id="form_request_withdraw" method="post" action="{{ route('all_commission_approve') }}">
            <input type = "hidden" name = "_token" value = "{{ csrf_token() }}"/>
            <input type = "hidden" name = "company" value = "{{ Request::get('company') }}"/>
            <input type = "hidden" name = "project_id" value = "{{ Request::get('project_id') }}"/>
            <input type = "hidden" name = "unit_type_id" value = "{{ Request::get('unit_type_id') }}"/>
            <input type = "hidden" name = "sale_person" value = "{{ Request::get('sale_person') }}"/>

                <table class="table table-bordered table-striped table-condensed table-hover dealerTable" id="dealers_list">
                    <thead class="th-center">
                    <!-- <th  style="vertical-align:middle; text-align: center;"><input type="checkbox" id="selectAll" /></th> -->
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.id') }}</th>
                    <th style="vertical-align:middle; text-align: center;">Request Number</th>   
                    <th style="vertical-align:middle; text-align: center;">Company</th>  
                    <th style="vertical-align:middle; text-align: center;">Project</th>    
                    <th style="vertical-align: middle;text-align: center;">Unit Types</th>  
                    <th style="vertical-align:middle; text-align: center;">Sale Team</th>                 
                    <th style="vertical-align: middle;text-align: center;">Withdrawal Date</th>
                    <th style="vertical-align:middle; text-align: center;">Rate</th>
                    <th style="vertical-align:middle; text-align: center;">Withdrawal.Atm</th>
                    <th style="vertical-align:middle; text-align: center;">Payment Status</th>                   
                    <th style="vertical-align:middle; text-align: center;">SM.Approve</th>
                    <th style="vertical-align:middle; text-align: center;">Acc.Approve</th>
                    <th style="vertical-align:middle; text-align: center;">HOF.Approve</th>
                    <th style="vertical-align:middle; text-align: center;">Chairman.Approve</th>
                    <th style="vertical-align:middle; text-align: center;">Action</th>
                    </thead>

                    <tbody class="checkbox-group">
                        <?php $i=0;?>
                        @forelse($lists as $d)
                        <?php $i++;?>
                            <tr>
                            <!-- <td class="isVerticalalign" align="center"><input type="checkbox" id="{{ $i}}" name="comission_request[]" value="{{$d->id}}" /></td>  -->
                                <td class="isVerticalalign" align="center">{{ $i}}</td>                        
                                <td class="isVerticalalign">
                                <a style="text-decoration: underline" target="_blank" href="/commission/print_commission/{{$d->id}}?show=sum">{{ $d->withdrawal_number }}</a>
                                </td> 
                                <td class="isVerticalalign">{{ $d->company }}</td> 
                                <td class="isVerticalalign">{{ $d->project }}</td>
                                <td class="isVerticalalign" >{{ $d->unit_type }}</td>
                                <td class="isVerticalalign" >{{ $d->sale_person }}</td>      
                                <td class="isVerticalalign">{{ !empty($d->withdrawal_date)?date('d-M-Y',strtotime($d->withdrawal_date)):"N/A" }}</td> 
                                <td class="isVerticalalign">{{ $d->commission_rate }}</td> 
                                <td class="isVerticalalign">{{  number_format($d->commissionWithdrawalTransaction->sum('withdrawal_amount'),2,'.','') }}</td> 
                                <td class="isVerticalalign">
                                @if( $d->payment_status =='Due')
                                            <p class="btn btn-danger">{{ $d->payment_status}}</p>
                                            @else
                                            <p class="btn btn-xs btn-default">{{ $d->payment_status}}</p>
                                            @endif
                                </td>
                                <td class="isVerticalalign define-width" align="center">                                                                     
                                    @if(!empty($d->sales_manager_approval))                    
                                    <i class="fa fa-check-circle"></i>
                                    {{$d->sales_manager_approval}}
                                    @else
                                    <a href="{{ route('sm_commission_approve', [$d->id])}}" class="btn btn-xs btn btn-primary" title="Edit"><i class="fa fa-pencil"></i> Acknowledged By</a> 
                                    @endif
                                </td>
                                <td class="isVerticalalign define-width" align="center">                                                                     
                                    @if(!empty($d->accountant_approval))                    
                                    <i class="fa fa-check-circle"></i>
                                    {{$d->accountant_approval}}
                                    @else
                                        @if(!empty($d->sales_manager_approval)) 
                                        <a href="{{ route('accountant_commission_approve', [$d->id])}}" class="btn btn-xs btn btn-primary" title="Edit"><i class="fa fa-pencil"></i> Checked By</a> 
                                        @else
                                        <a href="#" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i> Checked By</a>
                                        @endif
                                    @endif
                                </td>
                                <td class="isVerticalalign define-width" align="center">                                                                     
                                    @if(!empty($d->hof_approval))                    
                                    <i class="fa fa-check-circle"></i>
                                    {{$d->hof_approval}}
                                    @else
                                        @if(!empty($d->accountant_approval)) 
                                        <a href="{{ route('hof_commission_approve', [$d->id])}}" class="btn btn-xs btn btn-primary" title="Edit"><i class="fa fa-pencil"></i> Verified By</a> 
                                        @else
                                        <a href="#" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i> Verified By</a>
                                        @endif 
                                    @endif
                                </td>  
                                <td class="isVerticalalign define-width" align="center">                                                                     
                                    @if(!empty($d->chairman_approval))                    
                                    <i class="fa fa-check-circle"></i>
                                    {{$d->chairman_approval}}
                                    @else
                                         @if(!empty($d->hof_approval)) 
                                        <a href="{{ route('chairman_commission_approve', [$d->id])}}" class="btn btn-xs btn btn-primary" title="Edit"><i class="fa fa-pencil"></i> Approved By</a> 
                                        @else
                                        <a href="#" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i> Approved By</a>
                                        @endif  
                                    @endif
                                </td>         
                                <td style="text-align: center">
                                @if(!empty($d->status=='Approved'))
                                    @if(!empty($d->payment_status=='Due'))
                                    <a href="{{ route('get_mark_commission_payment', [$d->id])}}" class="btn btn-danger" title="Mark Payment"><span class="glyphicon glyphicon-usd"></span> Mark Payment</a> 
        
                                    @endif
                                @endif
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
       

function ConfirmDialog(message) {
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
            $('#form_request_withdraw').submit();
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
        $('#selectAll').click(function (e) {
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);

        });
        $("#sm_approve").click(function(){
            var values = [];
            $('.checkbox-group').each(function() {
                $(this).find('input[type="checkbox"]:checked').each(function(i, v) {
                values.push($(v).val());
                });
              
            })
            if(values.length===0){
                // alert('Please select an item in the list');
                SelectItems('Please select an item in the list.');
                return false;
            }else{
                $('#form_request_withdraw').append('<input type="hidden" name="type" value="sm" />');
                ConfirmDialog("Would you approve this request?");
                return false;

                
            }

   });

   $("#acc_approve").click(function(){
            var values = [];
            $('.checkbox-group').each(function() {
                $(this).find('input[type="checkbox"]:checked').each(function(i, v) {
                values.push($(v).val());
                });
                console.log(values);
            })
            if(values.length===0){
                // alert('Please select an item in the list');
                SelectItems('Please select an item in the list.');
                return false;
            }else{
                $('#form_request_withdraw').append('<input type="hidden" name="type" value="acc" />');
                ConfirmDialog("Would you approve this request?");
                return false;
            }
   });
   $("#hof_approve").click(function(){
            var values = [];
            $('.checkbox-group').each(function() {
                $(this).find('input[type="checkbox"]:checked').each(function(i, v) {
                values.push($(v).val());
                });
                
            })
            if(values.length===0){
                // alert('Please select an item in the list');
                SelectItems('Please select an item in the list.');
                return false;
            }else{
                $('#form_request_withdraw').append('<input type="hidden" name="type" value="hof" />');
                ConfirmDialog("Would you approve this request?");
                return false;
            }
   });
   $("#chairman_approve").click(function(){
            var values = [];
            $('.checkbox-group').each(function() {
                $(this).find('input[type="checkbox"]:checked').each(function(i, v) {
                values.push($(v).val());
                });
                
            })
            if(values.length===0){
                // alert('Please select an item in the list');
                SelectItems('Please select an item in the list.');
                return false;
            }else{
                $('#form_request_withdraw').append('<input type="hidden" name="type" value="chairman" />');
                ConfirmDialog("Would you approve this request?");
                return false;
            }
      
   });

    </script>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/advanced-datatable/css/jquery.dataTables.css',isset($secure) ? false : false) }}"/>
    <script src="{{ asset('theme/js/advanced-datatable/js/jquery.dataTables.js',isset($secure) ? false : false) }}"></script>
@endsection
