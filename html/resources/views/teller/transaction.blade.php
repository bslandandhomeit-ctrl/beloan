@extends('layouts.app')
@section('css')
    <link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
    <link rel="" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css', isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
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
                            <section id="flip-scroll">
                                @foreach($tellers as $teller)
                                    <div class="row">
                                        <div class="col-lg-2">Till Account : <b>{{ $teller->account_name}}</b></div>
                                        <div class="col-lg-2">Branch : <b>{{ $teller->branch_name }}</b></div>
                                        <div class="col-lg-2">Balance : <b>{{ $currency[$teller->currency_id].number_format($teller->balance, 2)}} </b></div>
                                        <div class="col-lg-2">Status : <b>{{ ($teller->status==1)? 'Closed':'Opened'}} </b></div>
                                    </div>
                                @endforeach
                            </section>
                            <hr/>

                            <section id="flip-scroll">
                                <form class="form-inline" role="form" method="get" enctype="form-data">
                                    @if($role!='teller')
                                        <div class="form-group ">
                                            <label for="email"> {{trans('teller.t_acc')}}</label>
                                            <div id="tillaccount" style="width: 300px"></div>
                                            {{--<input type="text" name="tillaccount" class="form-control" id="tillaccount" style="width: 300px; height:0px">--}}
                                        </div>
                                    @endif
                                    <input type="hidden" name="till_account_id" class="form-control" id="hide_tillaccount" style="width: 300px; height:0px">
                                    <div class="form-group">
                                        <label for="t_from">{{trans('teller.t_from')}}</label>
                                        <input type="text" name="t_from" class="form-control" id="t_from" value="<?php echo date('Y-m-d')?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="t_to">{{trans('teller.t_to')}}</label>
                                        <input type="text" name="t_to" class="form-control" id="t_to" value="<?php echo date('Y-m-d')?>" />
                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-info searchs">{{trans('multiple.m_search')}}</button>
                                        <button type="button" class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                                        <button type="submit" class="btn btn-primary" name="is_excel" id="is_excel" value="1"><i class="fa fa-download"></i> {{ trans('report.xrpt_export') }}</button>
                                        <button type="submit" class="btn btn-primary" name="is_csv" id="is_csv" value="1"><i class="fa fa-download"></i> {{ trans('report.rpt_export') }}</button>
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
                        @include('api.report_header')
                        <table class="table table-bordered table-striped table-condensed tillTran" id="tran">
                            <thead class="table-header">
                            <tr>
                                <th style="text-align: center;">{{ trans('multiple.m_no')  }}</th>
                                <th style="text-align: center;">{{ trans('teller.t_time' ) }}</th>
                                <th style="text-align: center;">{{ trans('teller.t_from_acc') }}</th>
                                <th style="text-align: center;">{{ trans('teller.contract_id') }}</th>
                                <th style="text-align: center;">{{ trans('teller.to_account') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.m_type') }}</th>
                                <th style="text-align: center;">{{ trans('teller.payment_type') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.methode') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_cash_in') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_cash_out') }}</th>
                                <th style="text-align: center;">{{ trans('teller.t_balance')}}</th>
                                <th style="text-align: center;">{{ trans('teller.status')}}</th>
                                <th style="text-align: center;">{{ trans('teller.description')}}</th>
                                <th style="text-align: center;">{{ trans('teller.action')}}</th>
                            </tr>
                            </thead>
                            <tbody id="trans_results"></tbody>
                        </table>
                      </div>
                    </section>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('js/accounting.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
    <script>
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
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });
        $(document).ready(function () {
            search(2);
            select();
            $('#t_from, #t_to').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
            $(".searchs").click(function(){
                search(2);
            });
            // $("#is_excel").click(function(){
            //     search(2,'excel');
            // });
            // $("#is_csv").click(function(){
            //     search(2,'csv');
            // });
        });
        function select() {
            $("#tillaccount").select2({
                width: 'absolute',
                minimumInputLength: 3,
                placeholder: "select any till account or a user",
                ajax: {
                    url: '{{asset('teller/trans_searching',isset($secure)?false:false)}}',
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    timeout: 3000,
                    data: function (term) {
                        return {
                            term: term,
                        };
                    },
                    results: function (data) {
                        if(data.permis != false){
                            return {
                                results: $.map(data.data, function (item) {
                                    return {
                                        text: item.name+' ( '+item.account_name+' / '+item.account_no+')',
                                        slug: item.account_no,
                                        id: item.till_id
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
        }

        function search(mtimesout,type ='') {
            if(type == ''){
                $("#trans_results").html('');
            }
            var setTimeOut = 3000 * mtimesout;
            var tables = '';
            $("#hide_tillaccount").val($('#tillaccount').val());
            $.ajax({
                url: '{{asset('/teller/trans_result',isset($secure)?false:false)}}',
                method : 'get',
                dataType : 'json',
                timeout : 50000,
                data : {
                    _token      : $("#token").val(),
                    t_from      : $('input[name=t_from]').val(),
                    t_to        : $('input[name=t_to]').val(),
                    till_account_id : $('#tillaccount').val(),
                    type:type
                },
                success: function (data, status) {

                    $('#loading').remove();
                    $('<div id="loading"></div>').appendTo('body');
                    if(type == ''){
                        if (data.res == true && !$.isEmptyObject(data.data)) {
                            let i = 1;
                            if(data.permis === true){
                                var total_amount = 0;                                
                                $.each(data.data, function (key, vals) {
                                    var receipt_no=vals.receipt_no;
                                    var auth = "Authorized";
                                    if(vals.approve_status == 0){
                                        if (vals.type === 'Withdraw') {
                                            auth = "Unauthorized"
                                        }else if(vals.type === 'Cash Deposit') {
                                            auth = "Unauthorized"
                                        }else if(vals.type === 'Fee and Commission') {
                                            auth = "Unauthorized";
                                            receipt_no=vals.be_cash_receipt_no;
                                        }else{
                                            auth = ""
                                        }
                                    }
                                    var deposit_type=vals.deposit_type;
                                    if(deposit_type===null || deposit_type==='null' || deposit_type===''){
                                        deposit_type='N/A';
                                    }
                                    tables += '<tr >'; //data-toggle="tooltip" data-placement="top" title="Audit Authorized By: '+vals.from_account+' "
                                    tables += '<td style="text-align: center">' + i + '</td>';
                                    tables += '<td style="text-align: right">' + vals.tranx_time + '</td>';
                                    tables += '<td style="text-align: center">' + vals.from_account + '</td>';
                                    tables += '<td style="text-align: center">' + vals.contract_id + '</td>';
                                    tables += '<td style="text-align: center">' + vals.to_account + '</td>';
                                    tables += '<td style="text-align: center">' + vals.type + '</td>';
                                    tables += '<td style="text-align: center">' + deposit_type + '</td>';
                                    tables += '<td style="text-align: center">' + vals.methode + '</td>';                                   
                                    tables += '<td style="text-align: right">' + vals.cash_in + '</td>';
                                    tables += '<td style="text-align: right">' + vals.cash_out + '</td>';
                                    tables += '<td style="text-align: right">' + vals.balance + '' +'</td>';
                                    tables += '<td style="text-align: center">' + auth + '</td>';
                                    tables += '<td style="text-align: center">' + vals.description + '</td>';
                                    if(!$.isEmptyObject(vals.type)) {
                                        if (vals.type === 'Withdraw') {
                                            tables += '<td align="center" class="define-width">';
                                            if(vals.approve_status == 0){
                                                var flag_ok = "ok";
                                                var flag_ng = "ng";
                                                tables += '<button type="button" class="btn btn-dark ok" style="background-color:transparent" data-id="'+vals.not_id+'" datatype = "'+vals.type+'"><i class="glyphicon glyphicon-ok"></i></button>';
                                                tables += '|';
                                                if(data.role_name != 'teller'){
                                                    tables += '<button type="button" class="btn ng" style="background-color:transparent" data-id="'+vals.not_id+'" datatype = "'+vals.type+'"><i class="glyphicon glyphicon-remove"></i></button>';
                                                    tables += '|';
                                                }

                                            }
                                            tables += '<a href="'+'print/withdraw/'+vals.slips_id+'" target="_blank"><span class="glyphicon glyphicon-print"></span></a>';
                                            tables += '</td>';

                                        }else if(vals.type === 'Cash Deposit') {
                                            tables += '<td align="center" class="define-width">';
                                            if(vals.approve_status == 0){
                                                var flag_ok = "ok";
                                                var flag_ng = "ng";
                                                tables += '<button type="button" class="btn btn-dark ok" style="background-color:transparent" data-id="'+vals.not_id+'" datatype = "'+vals.type+'"><i class="glyphicon glyphicon-ok"></i></button>';
                                                tables += '|';
                                                if(data.role_name != 'teller'){
                                                    tables += '<button type="button" class="btn ng" style="background-color:transparent" data-id="'+vals.not_id+'" datatype = "'+vals.type+'"><i class="glyphicon glyphicon-remove"></i></button>';
                                                    tables += '|';
                                                }
                                            }
                                            tables += '<a href="'+'print/deposit/'+vals.slips_id+'" target="_blank"><span class="glyphicon glyphicon-print"></span></a>';
                                            // tables += '<a href="'+'print/receipt/'+vals.slips_id+'" target="_blank"><span class="glyphicon glyphicon-print"></span></a>';
                                            tables += '</td>';
                                        }else if(vals.type === 'Fee and Commission') {
                                            tables += '<td align="center" class="define-width">';
                                            if(vals.approve_status == 0){
                                                var flag_ok = "ok";
                                                var flag_ng = "ng";
                                                // tables += '<button type="button" class="btn btn-dark ok" style="background-color:transparent" data-id="'+vals.not_id+'" datatype = "'+vals.type+'"><i class="glyphicon glyphicon-ok"></i></button>';
                                                tables += '<a href="'+'/bcash/authorized/'+vals.id+'"><i class="glyphicon glyphicon-ok"></i></a>';
                                                tables += '|';
                                                if(data.role_name != 'teller'){
                                                    // tables += '<button type="button" class="btn ng" style="background-color:transparent" data-id="'+vals.not_id+'" datatype = "'+vals.type+'"><i class="glyphicon glyphicon-remove"></i></button>';
                                                    tables += '<a href="'+'/bcash/rejceted/'+vals.id+'"><i class="glyphicon glyphicon-remove"></i></a>';
                                                    tables += '|';
                                                }
                                            }
                                            tables += '<a href="'+'/bcash/printitem/'+vals.slips_id+'/'+vals.be_cash_item_note+'" target="_blank"><span class="glyphicon glyphicon-print"></span></a>';
                                            // tables += '<a href="'+'print/receipt/'+vals.slips_id+'" target="_blank"><span class="glyphicon glyphicon-print"></span></a>';
                                            tables += '</td>';
                                        }
                                    }
                                
                                    tables += '</tr>';
                                    i++;
                                });
                            }else{
                                imgLoading(true, "you have not permission", 3);
                            }
                            imgLoading(true, "Done!!!", 1);
                        } else {
                            imgLoading(true, "Date Empty", 3);
                        }
                        $(tables).appendTo("#trans_results");
                    }

//                    $(function () {
//                        $('[data-toggle="tooltip"]').tooltip()
//                    })

                }, error: function (jqXHR, textStatus, errorThrown) {

                    if (textStatus == "timeout" || textStatus == "error" || errorThrown == "Internal Server Error") {
                        imgLoading(true, 'Errors ( jqXHR: ' + jqXHR + ' textStatus: ' + textStatus + ' errorThrown: ' + errorThrown + ' )', 7, textStatus);
                    }
                }
            });
            return true;
        }

    </script>

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/advanced-datatable/css/jquery.dataTables.css',isset($secure) ? false : false) }}"/>
    <script src="{{ asset('theme/js/advanced-datatable/js/jquery.dataTables.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
    var button_flag = "";
    $(document).on('click', 'table#tableid tbody tr, .ok', function () {
        ScriptRequire([
            '/theme/js/jquery.validate.min.js',
            '/js/load/form_model_load.js'
        ], function (status) {
        });
        button_flag = "ok";
        var n_id = $(this).attr('data-id');
        $(this).prop('disabled',true);
        var trans_type = $(this).attr('datatype');
        if(trans_type == "Cash Deposit"){
            ScriptRequire(['/js/load/deposit/deposit.js'], function (status) {
                if (status === 'success') {
                     deposit(n_id, button_flag);
                }
            });
        }else if(trans_type == "Withdraw"){
            ScriptRequire(['/js/load/withdraw/withdraw.js'], function (status) {
                if (status === 'success') {
                     withdraw(n_id, button_flag);
                }
            });
        }
        //fetchingData(id, types, not_id);
    });
    $(document).on('click', 'table#tableid tbody tr, .ng', function () {
        ScriptRequire([
            '/theme/js/jquery.validate.min.js',
            '/js/load/form_model_load.js'
        ], function (status) {
        });
        button_flag = "ng";
        var n_id = $(this).attr('data-id');
        $(this).prop('disabled',true);
        var trans_type = $(this).attr('datatype');
        if(trans_type == "Cash Deposit"){
            ScriptRequire(['/js/load/deposit/deposit.js'], function (status) {
                if (status === 'success') {
                     deposit(n_id, button_flag);
                }
            });
        }else if(trans_type == "Withdraw"){
            ScriptRequire(['/js/load/withdraw/withdraw.js'], function (status) {
                if (status === 'success') {
                     withdraw(n_id, button_flag);
                }
            });
        }
        
        //fetchingData(id, types, not_id);
    });
    </script>
@endsection
