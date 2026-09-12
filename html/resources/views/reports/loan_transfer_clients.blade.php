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
            <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('rpt_loan_transfer_clients') }}" id="search_frm">
                <div class="row">
               
                <div class="col-md-4"> 
                <label for="project_id">Project</label>
                    {{-- <input type="text" class="form-control" id="project_id" name="project_id"/> --}}
                    <select class="form-control" id="project_id" name="project_id"></select>
                </div>
                <div class="col-md-4">
                    <label for="unit_type_id" >{{ trans('unit.unit_type') }}</label>
                        {{-- <input type="text" class="form-control" id="unit_type_id" name="unit_type_id"/> --}}
                        <select class="form-control" id="unit_type_id" name="unit_type_id">
                        </select>
                        
                </div>
                <div class="col-md-4"> 
                        <label for="unit_id">{{ trans('unit.unit') }}</label>
                        {{-- <input type="text" class="form-control" id="unit_id" name="unit_id"/> --}}
                        <select name="unit_id" class="form-control" id="unit_id"></select>

                </div>
                </div>
                <br/>
                <div class="row">                    
                    <div class="col-md-4">                                            
                        <label for="t_from">{{trans('teller.t_from')}}</label>
                        <input type="text" name="t_from" class="form-control" id="t_from" value="{{ $from_date }}"/>                                          
                    </div>
                    <div class="col-md-4">                                            
                        <label for="t_to">{{trans('teller.t_to')}}</label>
                        <input type="text" name="t_to" class="form-control" id="t_to" value="{{ $to_date }}"/>                                         
                    </div>
                    <div class="col-md-4"> 
                    <label for="inputCardnumber">{{ trans('multiple.m_status') }}</label>
                    <select name="transfer_type" id="transfer_type" class="form-control">
                        <option value="">-</option>
                        <option value="Sub Sale" {{ ("Sub Sale" == Request::get('transfer_type'))?'selected' : "" }}>Sub Sale</option>
                        <option value="Ownership" {{ ('Ownership' == Request::get('transfer_type'))?'selected' : "" }}>Ownership</option>
                                   
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
                <th style="text-align: center; vertical-align: middle;">Transfer Date</th>
                <th style="text-align: center; vertical-align: middle;">Transfer From</th>
                <th style="text-align: center; vertical-align: middle;">Transfer To</th>
                <th style="text-align: center; vertical-align: middle;">Transfer Type</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_contract_id') }}</th>
                <th style="text-align: center; vertical-align: middle;">Account.No</th>
                <th style="text-align: center; vertical-align: middle;">Company</th>
                <th style="text-align: center; vertical-align: middle;">Project</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('unit.unit_type') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('unit.unit') }}</th>
                <th style="text-align: center; vertical-align: middle;">Transfer Remark</th>               
                </thead>
                <tbody style="vertical-align: middle">
                    <?php $i = 0;//dd($loans);?>
                    @forelse($loans as $l)
                    <?php $i++;?>
                    <tr>
                            <td style="text-align: center">{{$i}}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->transfer_date)?date('d-M-Y',strtotime($l->transfer_date)):"N/A" }}</td>   
                            <td style="text-align: center">{{ !empty($l->Clients)?$l->Clients->client_name:'-' }}</td> 
                            <td style="text-align: center;">{{ !empty($l->newClients)?$l->newClients->client_name:'-' }}</td>
                            <td style="text-align: center;">{{ $l->transfer_type }}</td>
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
                            <td style="text-align: right;">{{ $l->Remark }}</td>
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
