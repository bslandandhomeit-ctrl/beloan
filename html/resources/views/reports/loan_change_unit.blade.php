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
        <span>{{ trans('sidebar.sb_loan_status_summary') }}</span>
    </header>
    <div class="panel-body">
        <div class="position-center" style="width:95%;">
            <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('rpt_loan_change_unit_list') }}" id="search_frm">
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
                            <label for="contact_date">{{ trans('loan.l_contact_date') }}</label>
                                <input type="text" name="contract_date" class="form-control" value="{{ $contract_date }}" id="contract_date">                                 
                    </div>
                    <div class="col-md-3"> 
                            <label for="date" >To Date</label>                               
                            <input type="text" name="date" class="form-control" value="{{ $date }}" id="date">
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
                <th style="text-align: center; vertical-align: middle;">{{ trans('customer.cus_customer_name') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_contract_id') }}</th>
                <th style="text-align: center; vertical-align: middle;">Account.No</th>
                <th style="text-align: center; vertical-align: middle;">Company</th>
                <th style="text-align: center; vertical-align: middle;">Project</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('unit.unit_type') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('unit.unit') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('loan.l_contact_date') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('loan.contract_deadline') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_loan_type') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_disbursement_date') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_maturity_date') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('loan.l_tenure') }}</th>
                <th style="text-align: center; vertical-align: middle;">Clearance Amount</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Unit Price') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Discount Amount') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Discount (Payment Option)') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Others Discount') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Down Payment') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_loan_amount') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_int_rate') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_int_rate_type') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Penalty ($/%)') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_status') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_note') }}</th>
                </thead>
                <tbody style="vertical-align: middle">
                    <?php $i = 0;//dd($loans);?>
                    @forelse($loans as $l)
                        <tr>
                            <?php
                                $i++;
                                $date = null;
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
                                        break;
                                    case 6: $date = !empty($l->settlement_date) ? $l->settlement_date : null;
                                        break;
                                    case 7: $date = $l->submitted_on;
                                        break;
                                    case 8: $date = !empty($l->approval->approval_date) ? $l->approval->approval_date : null;
                                        break;
                                    case 9: $date = !empty($l->settlement_date) ? $l->payoff->payoff_date : null;
                                        break;
                                    case 10:$date = !empty($l->settlement_date) ? $l->settlement_date : null;
                                        break;
                                    default: break;
                                }
                            ?>
                            <td style="text-align: center">{{$i}}</td>
                            <td style="vertical-align: middle">{{$l->client_name}}</td>
                            <td style="vertical-align: middle;text-align: center">
                                <a style="text-decoration: underline" href="{{ route('loan_detail', [$l->id])}}">{{ $l->contract_id ? $l->contract_id : '-'  }}</a>
                            </td>
                            {{-- <td style="vertical-align: middle;text-align: center">
                                <a style="text-decoration: underline" href="{{ route('loan_account', [$l->loan_account_id])}}">{{(!empty($l->client_loan_account->account_no))? $l->client_loan_account->account_no : ""}}</a>
                            </td> --}}
                            <td style="vertical-align: middle;text-align: center;">
                                <a style="text-decoration: underline" href="{{ route('loan_account', [$l->loan_account_id])}}">
                                    {{ !empty($l->drawdown_acc)?$l->drawdown_acc:"N/A" }}
                                </a>
                            </td>
                            <td style="vertical-align: middle;text-align: center">{{$l->company}}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->short_code)?$l->short_code:"N/A" }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->name)?$l->name:"N/A" }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->code)?$l->code:"N/A" }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->contract_date)?date('d-M-Y',strtotime($l->contract_date)):"N/A" }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->contract_deadline)?date('d-M-Y',strtotime($l->contract_deadline)):"N/A" }}</td>                            
                            
                            <td style="text-align: justify">{{$loan_type[$l->loan_type - 1]->code}}</td>
                            <td style="text-align: center">{{$l->disburse_date ? date("d-M-Y", strtotime($l->disburse_date)) : '-'}}</td>
                            <td style="text-align: center">{{$l->schedule_date ? date("d-M-Y", strtotime($l->schedule_date)) : '-'}}</td>
                            <td style="text-align: right">{{$l->loan_duration}}</td> 
                            <td style="text-align: right;">{{ number_format($l->clearance_amount,2,'.',',') }}</td>
                            <td style="text-align: right;">{{ number_format($l->unit_sale_price,2,'.',',') }}</td>
                            <td style="text-align: right;">{{ number_format($l->amount_discount_payment_option,2,'.',',') }}</td>
                            <td style="text-align: right;">{{ number_format($l->discount_payment_option,2,'.',',') }}</td>
                            <td style="text-align: right;">{{ number_format($l->discount_other,2,'.',',') }}</td>
                            <td style="text-align: right;">{{ number_format($l->down_payment_value,2,'.',',') }}</td>
                            <td style="text-align: right">{{$l->original_amount ? number_format($l->original_amount,2,'.',',') : number_format($l->loan_amount,2,'.',',')}}</td>
                            <td style="text-align: center">{{$l->interest_rate ? number_format($l->interest_rate, 2) : ''}}%</td>
                            <td style="text-align: center">{{$l->rate_type}}</td>
                            <td style="text-align: center;white-space: nowrap;">{{ number_format($l->penalty_rate1,2) }} {{$l->loan_penalty_type}}</td>
                            <td align="justify" style="white-space: nowrap;">{{$loan_status[$l->status]}}</td>
                            <td style="text-align: center">{{$l->status_remark}}/ {{$l->clearance_remark}}</td>
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

    $('#contract_date, #date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
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
