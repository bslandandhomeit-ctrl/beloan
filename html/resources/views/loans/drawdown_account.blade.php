@extends('layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="_token" id="_token" value="{{csrf_token()}}" content="{{csrf_token()}}" />
@section('css')
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link rel = "stylesheet" type = "text/css" href = "{{ asset('css/loan-style.css',false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/select2_v4.1.0/select2.min.css',isset($secure) ? false : false) }}"/>
<link rel = "stylesheet" type = "text/css" href = "{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',false)}}"/>

<style>
    @media print {
      a[href]:after {
      content: none !important;
      }
    }
</style>
@endsection
    <?php
        $branch = config('static_data.branch');
        $currency = config('static_data.currency_symbol');
        $status = config('static_data.client_loan_account_status');
        $drawdown_status = config('static_data.drawdown_status');
    ?>

@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
              <span>{{ trans('sidebar.sb_drawdown_account') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    @if($errors->addCate->has('ipCate'))
                    <div class="alert alert-danger fade in">
                        <button class="close close-sm" type="button" data-dismiss="alert">x</button>
                        {{$errors->addCate->first('ipCate')}}
                    </div>
                    @endif
                </div>
                <form role="form" id="search_draw" method="post" action="{{ route('drawdown_account') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                <div class="col-md-3"> 
                    <label for="client_name">{{ trans('multiple.m_client_name') }}</label>
                    <select id="customer_id" name="customer_id" class="form-control"></select>  
                </div>
                <div class="col-md-3">                                            
                    <label for="Company">Company</label>
                    <select id="company" name="company" class="form-control">
                        <option value="">Select company</option>
                            <option value="01" {{ ('01' == Request::get('company'))?'selected' : "" }}>East Land and Home Co., Ltd</option>
                            <option value="02" {{ ('02' == Request::get('company'))?'selected' : "" }}>BS Land and Home Co., Ltd</option>
                
                    </select>                                           
                </div>
                <div class="col-md-3"> 
                <label for="project_id">Project</label>
                    {{-- <input type="text" class="form-control" id="project_id" name="project_id"/> --}}
                    <select class="form-control" id="project_id" name="project_id"></select>
                </div>
                <div class="col-md-3"> 
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
                    <select name="status" id="status" class="form-control">
                        <option value="">-</option>
                        <option value="0" {{ ('0' == Request::get('status'))?'selected' : "" }}>Unauthorized</option>
                        <option value="1" {{ ('1' == Request::get('status'))?'selected' : "" }}>Authorized</option>
                        <option value="2" {{ ('2' == Request::get('status'))?'selected' : "" }}>Rejected</option>
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
                </div>
                </form>
              </div>
                <div class="page">
                    <div class="custom-pagi">
                        <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('drawdown_account') }}">
                            <span class="pagi_label">{{ trans('sidebar.sb_number_of_rows') }}</span>
                            <input type="text" class="form-control" name="set_offset" value="{{ isset($set_offset)?$set_offset:15 }}" />
                            <button type="submit" class="btn btn-danger" style="margin-top:7px;">Go</button>
                        </form>
                    </div>
                </div>

                <br /><br />
                <div id="tabs">
                  <div id="printArea">
                    @include('api.report_header')
                    <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                            <tr>
                                <th style="text-align: center;">{{ trans('multiple.account_name') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.account_no') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.branch') }}</th>
                                <th style="text-align: center;">Project</th>
                                <th style="text-align: center;">Unit Type</th>
                                <th style="text-align: center;">Unit</th>
                                <th style="text-align: center;">{{ trans('multiple.last_update') }}</th>
                                <th style="text-align: center;">{{ trans('account.a_debit') }}</th>
                                <th style="text-align: center;">{{ trans('account.a_credit') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.balance') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.dd_balance') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.m_description') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.status') }}</th>
                            </tr>
                        </thead>
                        <?php $n = 1;?>
                        <tbody>
                            @forelse($drawdown_accounts as $das)
                            <?php 
                            $status= '';
                            $status_class="";
                            if($das->status==0){
                                $status="Unauthorized";
                                $status_class="badge btn-warning";
                            }else if($das->status==1){
                                $status="Authorized";
                                $status_class="badge btn-primary";
                            }else if($das->status==2){
                                $status="Rejected";
                                $status_class="badge btn-danger";
                            }
                            ?>
                            <tr>
                                <td><a href="{{ route('drawdown_account_detail', $das->id) }}">{{$das->account_name}}</a></td>
                                <td>{{$das->account_no}}</td>
                                <td>{{$das->company_branch->branch_name}}</td>
                                <td>{{$das->project}}</td>
                                <td>{{$das->unit_type}}</td>
                                <td>{{$das->unit}}</td>
                            <?php
                                $balance_arr = "";
                                $balance_arr = get_journal_bal_new($das->coa_id,$end);
                            ?>
                                <td>{{$balance_arr['last_acc_date']}}</td>
                                <td>{{$balance_arr['t_debit']}}</td>
                                <td>{{$balance_arr['t_credit']}}</td>
                                <td>{{$currency[$das->currency].number_format(-$balance_arr['balance'], 2)}}</td>
                                <td>{{$currency[$das->currency].number_format($das->balance, 2)}}</td>
                                <td>{{$desc}}</td>
                                <td>                                    
                                    <?php if($das->status!=2){?>
                                        <a class="pull-right btn btn-danger" href="{{ route('drawdown_account_reject', $das->id) }}"><i class="glyphicon glyphicon-remove"></i> Reject</a>                                       
                                 <?php   }?>
                               
                                <span class="<?php echo $status_class;?>">{{$status}}</span>
                            </td>
                            </tr>
                            <?php $n++;?>
                            @empty
                            <tr><td colspan=12>{{ trans('multiple.m_no_result')}}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                  </div>
                  <div class="page">
                      {!! $drawdown_accounts->render() !!}
                  </div>
                </div>
            </div>
            <!-- Blog Reject -->
            <div id="blog-rejected" style="display: none;">
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_close_date') }} <span class="red">*</span></label>
                    <div class="input-append date dpYears col-sm-6" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}">
                        <input type="text" value="{{($dpDateClone)?$dpDateClone:date('Y-m-d')}}" class="form-control" name="close_date" id="close_on">
                            <span class="add-on offonDatepicker">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <!-- End -->
        </section>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js', isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js', isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/select2_v4.1.0/select2.min.js',isset($secure) ? false : false)}}"></script>

<script type="text/javascript">
$(document).ready(function () {    
    $("#company").select2();
    var customer_id = '<?php echo $customer_id;?>';
    $("#customer_id").select2({
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
    $("#customer_id").val(customer_id).trigger('change');

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
            new TableExport(document.getElementById('drawdown_account'), {
                formats: ['csv'],
                filename:'drawdown_account'
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
    });

    $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('drawdown_account'), {
                    formats: ['xlsx'],
                    filename: 'drawdown_account'
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
