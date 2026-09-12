@extends('layouts.app')

@section('css')

<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link rel = "stylesheet" type = "text/css" href = "{{ asset('css/loan-style.css',false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/select2_v4.1.0/select2.min.css',isset($secure) ? false : false) }}"/>
<link rel = "stylesheet" type = "text/css" href = "{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',false)}}"/>

    <style>
        .col-md-3.control-label.text-left {
            text-align: left !important;
        }
        #customer_types, #guarantor {
            max-height: 300px;
        }
        .table-primary {
    background-color: #b8daff;
}
.table-danger {
    background-color: #f5c6cb;
}
.table-dark {
    background-color: #c6c8ca;
}

.table td, .table th {
    padding: .75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
    </style>
@endsection

@section('content')
    <section class = "panel">
        <div class = "panel-heading">
        Transfer balance
        </div>
        <div class = "panel-body">
            <div class = "col-sm-12">
                
            @if (\Session::has('error'))
            <div class="alert alert-danger">
                <ul>
                    <li>{!! \Session::get('error') !!}</li>
                </ul>
            </div>
        @endif
                <label class = "col-md-2"></label>
                <div class = "col-md-10">
                    @if (count($errors) > 0)
                        <div class = "alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @elseif(session('msg_success'))
                        <div class = "alert alert-success">
                            <ul>
                                <li>{{ session('msg_success') }}</li>
                            </ul>
                        </div>
                    @endif
                    @if(Session::has('msg'))
                        <div class = "alert alert-danger fade in">
                            <button class = "close close-sm" data-dismiss = "alert">x</button>
                            {{ Session::get('msg') }}
                        </div>
                    @endif
                </div>
                <?php
                $static = config('static_data');
                $select_branch_id = 0;
                $select_currency_id = 0;
                $auto_selected = $static['auto_select_option'];

                ?>
            </div>
            <form role="form" class="cmxform form-horizontal" id="search_frm" method="post" action="{{route('postTransferInterProject')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                <div class="col-md-4"> 
                        <label for="unit_id">From {{ trans('unit.unit') }}</label>
                        <select name="unit_id" class="form-control" id="unit_id" required></select>

                </div>
                <div class="col-md-4"> 
                        <label for="unit_id">To {{ trans('unit.unit') }}</label>
                        <select name="to_unit_id" class="form-control" id="to_unit_id" required></select>

                </div>

                    <div class="col-md-2">
                        <div class="form-group" style="margin-top: 20px;">
                            <input type="hidden" name="offset" value="<?php echo $offset ?>" />
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                        </div>
                    </div>
                </div>
                </div>
            </form>
            <form class = "cmxform form-horizontal" method = "post" action = "{{route('postTransferBalance')}}" id = "frmCoaAccount">
                <div class = "col-lg-12">
                    <input type = "hidden" name = "_token" value = "{{ csrf_token() }}"/>
                    <input type="hidden" name="from_drawdown_id" value="{{ $old_drawdown->id }}">
                    <input type="hidden" name="from_account_name" value="{{ $old_drawdown->account_name }}">
                    <input type="hidden" name="from_account_no" value="{{ $old_drawdown->account_no }}">

                    <input type="hidden" name="to_drawdown_id" value="{{ $new_drawdown->id }}">
                    <input type="hidden" name="to_account_name" value="{{ $new_drawdown->account_name }}">
                    <input type="hidden" name="to_account_no" value="{{ $new_drawdown->account_no }}">

                    


                    </div>

                        <div class="col-lg-12">
                        <table class="table table-sm">
  <thead>
    <tr>
      <th colspan="2" scope="coll" class="table-danger">ផ្ទេរពី - {{$old_drawdown->account_name}} - {{$old_drawdown->units->code}}</th>
      <th scope="coll" class="table-dark"><i class="fa fa-exchange" aria-hidden="true"></i></th>
      <th colspan="2" scope="col"  class="table-primary">ទៅ - {{$new_drawdown->account_name}} -  {{$new_drawdown->units->code}}</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">Account Number</th>
      <td>{{$old_drawdown->account_no}}</td>
      <td><i class="fa fa-exchange" aria-hidden="true"></i></td>
      <th scope="row">Account Number</th>
      <td>{{$new_drawdown->account_no}}</td>
    </tr>
    <tr>
    <th scope="row">Project</th>
      <td>{{$old_drawdown->projects->short_code}}</td>
      <td><i class="fa fa-exchange" aria-hidden="true"></i></td>
      <th scope="row">Project</th>
      <td>{{$new_drawdown->projects->short_code}}</td>
    </tr>
    <tr>
    <th scope="row">Unit Type</th>
      <td>{{$old_drawdown->unitType->name}}</td>
      <td><i class="fa fa-exchange" aria-hidden="true"></i></td>
      <th scope="row">Unit Type</th>
      <td>{{$new_drawdown->unitType->name}}</td>
    </tr>
    <tr>
    <th scope="row">Unit</th>
      <td>{{$old_drawdown->units->code}}</td>
      <td><i class="fa fa-exchange" aria-hidden="true"></i></td>
      <th scope="row">Unit</th>
      <td>{{$new_drawdown->units->code}}</td>
    </tr>

    <th scope="row">Total Principal Paid</th>
      <td>
     
      <input type="text" class="form-control" name="total_principal_paid" value="{{ number_format($loan->payment->sum('paid_principal'),2,'.','') }}">
    </td>
      <td><i class="fa fa-exchange" aria-hidden="true"></i></td>
      <th scope="row">Total Principal Paid</th>
      <td>0</td>
    </tr>
    <th scope="row">Total Interest Paid</th>
      <td>
      <input type="text" class="form-control" name="total_interest_paid" value="{{ number_format($loan->payment->sum('paid_interest'),2,'.','') }}">
    </td>
      <td><i class="fa fa-exchange" aria-hidden="true"></i></td>
      <th scope="row">Total Interest Paid</th>
      <td>0</td>
    </tr>


    

                   
    
  </tbody>
</table>
                        </div>

                    <div class = "form-group pull-right">                      
                        <div class = "col-md-12">
                        <br/><br/><br/>
                            <a href="/sale/list"  class = "btn btn-secondary pull-right"> Cancel </a>
                            <button class = "btn btn-primary">Transfer</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
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

    var unit_id = '<?php echo $unit_id;?>';


    $("#unit_id").select2({
        minimumInputLength: 0,
        placeholder: "Select Unit",
        data:[{id:unit_id,text:'<?php echo $unit->code.'('.$unit->price.')';?>'}],
         allowClear: true,
        ajax:{
            url: '{{ route('get_project_unit_unittype_with_loan') }}',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            timeout: 3000,
            data: function (term) {
                return {
                    search_unit: term.term
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

    
    var to_unit_id = '<?php echo $to_unit_id;?>';
    $("#unit_id").val(to_unit_id).trigger('change');
    $("#to_unit_id").select2({
        minimumInputLength: 0,
        placeholder: "Select Unit",
        data:[{id:unit_id,text:'<?php echo $to_unit->code.'('.$to_unit->price.')';?>'}],
         allowClear: true,
        ajax:{
            url: '{{ route('get_project_unit_unittype_with_loan') }}',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            timeout: 3000,
            data: function (term) {
                return {
                    search_unit: term.term
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
    $("#to_unit_id").val(to_unit_id).trigger('change');





   
});


    
   </script>
@endsection
