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
    .total_result{
        padding-left: 0px;
        float: left;
        background: #f1f2f7;
    }
    .total_result li{
        list-style-type: none;
        width: 150px;
        padding: 20px;
        margin: 20px;
        text-align: center;
        border-radius: 4px;
    }
    .total_result .com_ELH{
        color: #fff;
    }
    .total_result .com_BS{
        color: #fff;

    }
    .total_result .total{
        color: #fff;
    }
    .total_result .amount{
        font-weight: bold;
        color: #d40303;
    }

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
                                <form  role="form" method="get" action="{{ route('sale_report_summary_by_seller') }}">
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
                    <label class="control-label">{{ trans('sale_person.sale_person') }}</label>
                            <select id="sale_person_id" style="width: 100%" name="sale_person" required>
                                <option value="">-</option>
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
                            <div class="col-md-6">   
                                    <?php
                                    $from_date=$from_date? $from_date:date('Y-m-d');
                                    $to_date=$to_date? $to_date:date('Y-m-d');
                                    ?>
                                <input type="text" name="t_from" class="form-control" id="t_from" value="<?php echo $from_date?>"/>                                          
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="t_to" class="form-control" id="t_to" value="<?php echo $to_date?>"/>                                         
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                                'company_branch' =>$company_branch,
                                'teller_user'=>$teller_user ,
                                'teller_transaction' => $teller_transaction,
                                 'from_date'=>$from_date,'to_date'=>$to_date,                           
                            ]  ?>
                            @include('api.report_header_custom_sum', $data)
                        </div>
                        <!-- <table class="table table-bordered table-striped table-condensed tillTran" id="tran">
                            <thead class="table-header">
                                <tr>
                                    <th style="text-align: center;">{{ trans('multiple.m_no')  }}</th>
                                    <th style="text-align: center;">Sale Representative</th>                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 0;
                                ?>
                                @forelse($teller_trans_arr as $key => $item)
                                <?php
                                        $i++;
                                        $j=0;
                                        $total = array_sum(array_column($rows,'cash_in'));
                                    ?>
                                    <tr>
                                    <td class="text-center" style="vertical-align: middle;" rowspan="{{ count($rows) }}">{{ $i++ }}</td>
                                        <td>{{$key}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table class="table table-bordered table-striped table-condensed tillTran">
                                                <thead class="table-header">
                                                        <tr>
                                                            <th style="text-align: center;">{{ trans('multiple.m_no')  }}</th>
                                                            <th style="text-align: center;">Company</th>                                   
                                                        </tr>
                                                    </thead>
                                                    <tr>
                                            @foreach($item as $vals =>$val)
                                                <?php
                                                $j++;
                                                $k=0;
                                                ?>                                                
                                                <td class="text-center" style="vertical-align: middle;" rowspan="{{ count($rows) }}">{{ $j++ }}</td>
                                                    <td>{{$vals}}</td>
                                                </tr>  
                                                <tr>
                                                    <td> 
                                                    <table class="table table-bordered table-striped table-condensed tillTran">
                                                        <thead class="table-header">
                                                            <tr>
                                                                <th style="text-align: center;">{{ trans('multiple.m_no')  }}</th>
                                                                <th style="text-align: center;">Project</th>                                   
                                                            </tr>
                                                        </thead>
                                                                                                                                                     
                                                    @foreach ($val as $data=>$v)
                                                    <?php                                                   
                                                    $k++;
                                                    ?>     
                                                        <tr>                                               
                                                        <td class="text-center" style="vertical-align: middle;">{{ $k++ }}</td>                                                                
                                                                <td class="text-center" style="vertical-align: middle;" >{{ $data }}</td>
                                                                <td class="text-center" style="vertical-align: middle;" rowspan="{{ count($rows) }}">{{ number_format($total,2) }} {{ !empty($data['symbol'])?$data['symbol']:"" }}</td>
                                            
                                                        </tr>
                                                        @endforeach
                                                        </table>
                                                    </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table> -->
                        <table class="table table-bordered table-striped table-condensed tillTran" id="tran">
                            <thead class="table-header">
                                <tr>
                                    <th style="text-align: center;">{{ trans('multiple.m_no')  }}</th>
                                    <th style="text-align: center;">Sale Representative</th>  
                                    <th style="text-align: center;">Total</th>                                  
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 0;
                                ?>
                                @forelse($teller_trans_arr as $key => $item)
                                <?php
                                        $i++;                                      
                                    ?>
                                    <tr>
                                    <td class="text-center" style="vertical-align: middle;" >{{ $i }}</td>
                                    <td>{{$key}}</td>                                                                     
                                    <td>
                                            @foreach($item as $vals)
                                               
                                                <?php                                               
                                                $total = array_sum(array_column($item,'price_after_discount'));
                                                ?>
                                            
                                               
                                            @endforeach
                                            ${{number_format($total,2)}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <table class="table table-bordered table-striped table-condensed result" style="width:300px;font-weight: bold;float: right;">    
                        <?php $grand_total=0;?>                       
                        @forelse($companies as $key => $item)
                        <?php $total_by_company=0;
                        ?>
                            @foreach ($item as $rows)
                            <?php                                               
                            $total_by_company = array_sum(array_column($item,'price_after_discount'));                      
                            ?>
                            @endforeach                        
                        <tr>
                        <td>Total Sale {{ $key}}</td>                       
                        <td class="amount">${{number_format($total_by_company,2)}}</td>                        
                        </tr>
                        <?php   $grand_total=$grand_total + $total_by_company?>
                        @endforeach
                        <tr class="total" style="color: red;">
                            <td>Grand Total</td>
                            <td class="amount">${{number_format($grand_total,2) }}</td>
                        </tr>
                        </table>
                        @include('api.report_footer_nbc')
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
        $("#export").click(function (event) {
        var con = confirm("Do you really want to export to CSV file?");
        if(con == true){
            new TableExport(document.getElementById('printArea'), {
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
                new TableExport(document.getElementById('printArea'), {
                        formats: ['xlsx'],
                        filename: 'teller_transaction'
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });
        $(document).ready(function () {
            $("#sale_person_id").select2();
            $("#project_id").select2();
            
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
