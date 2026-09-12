@extends('layouts.app')
@section('css')
    <link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/css/teller.css',isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
    <style>
      @media print {
       .hide-this{
         display: none !important;
       }
      }
    </style>

@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    <span>{{ trans('teller.till_account_summary') }}</span>
                </header>
                <div class="panel-body">
                    <div style="float:right;">
                        <button type="button" class="btn btn-info btn-sm " data-toggle="modal" id="createTill" data-target="#createtill"> Create Till </button>
                        <button type="button" class="btn btn-info btn-group-lg btn-md" onclick="callBacks()"> Reload </button>
                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                        <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                        <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>                    </div>
                    <br/><br/><br/>
                  <div id="printArea">
                      @include('api.report_header')
                    <table class="table table-bordered table-striped table-condensed teller" id="teller">
                        <thead class="cf">
                        <tr>
                            <th style="text-align: center;">{{ trans('multiple.m_no')  }}</th>
                            <th style="text-align: center;">{{ trans('dealer.dl_dealer_account_name' ) }}</th>
                            <th style="text-align: center;">{{ trans('account.a_account_code') }}</th>
                            <th style="text-align: center;">{{ trans('staff.s_staff_branch') }}</th>
                            <th style="text-align: center;">{{ trans('teller.t_teller_name')}}</th>
                            <th style="text-align: center;">{{ trans('sidebar.sb_currency',['num'=>''])}}</th>
                            <th style="text-align: center;">{{ trans('teller.t_balance')}}</th>
                            <th style="text-align: center;">{{ trans('teller.t_max_balance')}}</th>
                            <th style="text-align: center;">{{ trans('multiple.status')}}</th>
                            <th style="text-align: center;" class="hide-this">{{ trans('multiple.m_action')}}</th>
                            {{--<th style="text-align: center;">{{ trans('multiple.m_close')}}</th>--}}
                        </tr>
                        </thead>
                        <tbody id="listOfAccount"></tbody>
                    </table>
                  </div>
                </div>
            </section>
        </div>
    </div>
    <div id="result"></div>
    <div id="loading" style="display: none"></div>

@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/accounting.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>

    <script type="text/javascript">
    $("#export").click(function (event) {
        var con = confirm("Do you really want to export to CSV file?");
        if(con == true){
            new TableExport(document.getElementById('teller'), {
                formats: ['csv'],
                filename:"chief_of_teller"
            });
            $('button.csv').hide().click();
            $('.tableexport-caption').remove();
        }
      });
      $("#xexport").click(function (event) {
            var con = confirm("Do you really want to export to Excel file?");
            if(con == true){
                new TableExport(document.getElementById('teller'), {
                        formats: ['xlsx'],
                        filename: 'chief_of_teller'
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });

        $(document).ready(function () {

            listOfTillAccount(2)

            $('#createTill').click(function (e) {

                var url = "{{asset("js/libs.js",isset($secure)?false:false)}}";
                var id = $(this).data("target").replace('#', '');

                $.ajax({
                    url: url,
                    dataType: "script",
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    },
                    success: function (data, status) {

                        if (status == 'success') {
                            myload({
                                id: id,
                                title: 'Create Till Account',
                                inputs: [
                                    'Account No <input type="text" class="form-control" name="account_no" value="" placeholder="" disabled /> ', ' Account Name',
                                    '<input type="text" class="form-control" name="account_name" placeholder="" disabled />Branch',
                                    '<input type="text" class="form-control branchs" name="branchs" disabled /> Currency',
                                    '<select name="currency" id="currency" class="form-control " ><option value="0">-</option></select>' + 'Min Balance',
                                    '<input type="text" class="form-control" name="min_balance" placeholder="min balance"  />Max Balance',
                                    '<input type="text" class="form-control" name="max_balance" placeholder="Max balance" />Created By',
                                    '<input type="text" class="form-control" name="created_by" placeholder="Create By" />Assigned to',
                                    '<select name="assign_user_id" id="assign_user" class="form-control notCheck" ><option value="0">-</option></select> Create Date',
                                    '<input type="text" id="create_date" class="form-control dpYears" value="{{ date('Y-m-d') }}" name="create_date" placeholder="" />Note',
                                    '<textarea rows="4" class="form-control" name="note" cols="7" ></textarea>',
                                    '<input type="hidden" name="_token" id="token" value="{{csrf_token()}}">'
                                ],
                                btn: '{{ trans('teller.Create') }}',
                                script: [
                                    '{{asset('theme/js/jquery.validate.min.js',isset($secure)?false:false)}}',
                                    '{{asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure)?false:false)}}',
                                    '{{asset('theme/js/select2/select2.js',isset($secure)?false:false)}}',
                                    '{{asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure)?false:false)}}',
                                ]
                            });
                            imgLoading(true, "Form was loaded ", 1, status);
                        }
                        dateCallback(".dpYears");
                    }
                });
            });

            $("table.teller").on("click", ".openTillId", function () {
                var ids = $(this).siblings("span").text();
                var selecBy = $('.openTillId').data("target").replace('.', '');

                return openTill(selecBy, ids);
            });
        });

        function callBacks() {

            var calls = listOfTillAccount(2);
            if (calls) {

                $("#listOfAccount").empty();
                imgLoading(false, '', 0, 0);
            }
        }
        function listOfTillAccount(mtimesout) {

            $("#listOfAccount").html('');
            var setTimeOut = 1000 * mtimesout;
            var tables = '';

            $.ajax({
                url: '{{ asset('teller/till_account_summary',isset($secure) ? false : false) }}',
                method: 'get',
                dataType: 'Json',
                timeout: setTimeOut,
                beforeSend:function(){
                    imgLoading(true, "data is loading...", 1);
                },
                success: function (data, status) {
                    account = data.account;
                    if (data.res == true && status =='success') {
                        var i = 1;
                        $.each(data.account, function (key, vals) {
                            console.log(vals);
                            if (typeof data.account === 'object' && Object.keys(data.account).length !== 0) {
                                if (parseInt(vals.till_id) !== null && vals.till_id !== '') {

                                    tables += '<tr>';
                                    tables += '<td style="text-align: center">' + i + '</td>';
                                    tables += '<td style="text-align: center">' + vals.account_name + '</td>';
                                    tables += '<td style="text-align: center">' + vals.account_no + '</td>';
                                    tables += '<td style="text-align: center">' + vals.branch_name + '</td>';
                                    tables += '<td style="text-align: center">' + vals.username + '</td>';
                                    tables += '<td style="text-align: center">' + vals.currency_name + '</td>';
                                    tables += '<td style="text-align: right">' + fomart_all_currency(vals.balance, vals.symbol)+ ' </td>';
                                    tables += '<td style="text-align: right">' + fomart_all_currency(vals.max_balance, vals.symbol) + '</td>';
                                    if (parseInt(vals.status) == 1) {
                                        tables += '<td style="text-align: center;"> closed </span></td>';
                                    } else {
                                        tables += '<td style="text-align: center;"> opened </span></td>';
                                    }
                                    if (parseInt(vals.status) == 1) {
                                        tables += '<td style="color:red; text-align: center;" class="hide-this"><button type="button"  class="fa fa-check btn-sm openTillId" data-toggle="modal" data-target="openTill"> </button><span style="display:none;">' + vals.till_id + '</span></td>';
                                    } else {
                                        tables += '<td style="color:limegreen; text-align: center;" class="hide-this">  <button type="button" class="fa fa-times btn-sm openTillId" data-toggle="modal" data-target="openTill">  </button><span style="display:none;">' + vals.till_id + '</span></td>';
                                    }
                                    tables += '</tr>';
                                }
                            }
                            i++;
                        });
                    } else {
                        imgLoading(true, "data is loading...", 1);
                    }
                    $(tables).appendTo("#listOfAccount");

                }, error: function (jqXHR, textStatus, errorThrown) {

                    if (textStatus == "timeout" || textStatus == "error" || errorThrown == "Internal Server Error") {
                        imgLoading(true, 'Errors ( jqXHR: ' + jqXHR + ' textStatus: ' + textStatus + ' errorThrown: ' + errorThrown + ' )', 7, textStatus);
                    }
                }
            });

            return true;
        }


        function CurNames(id) {

            var test = '<?PHP $static = config('static_data'); echo json_encode($static['currency']);  ?>';
            var Obj = JSON.parse(test);
            for (var key in Obj) {
                if (parseInt(key) == parseInt(id)) {
                    return Obj[key];
                }
            }
        }

        function openTill(selecBy, id) {

            var url = '{{asset('js/load/openTill.js',isset($secure)?false:false)}}';
            $.ajax({
                url: url,
                method: 'get',
                dataType: 'script',
                success: function (data, status) {
                    if (status == 'success') {
                        $('<div id="loading"></div>').appendTo('body');
                        imgLoading(true, "Loading....",5, status);
                        TillForEdit(selecBy, id);
                    }
                }, error: function (jgxht, status, errorthrogh) {
                    imgLoading(true, "Fail!!!! Please feadback to your developer or refresh your page. Errors Type:" + status, 2, status);
                }
            });
        }

        function TillForEdit(selecBy, id) {

            $.ajax({
                url: '{{asset('teller/openTill',isset($secure)?false:false)}}/' + id,
                method: 'get',
                dataType: 'json',
                beforeSend:function(){
                    $('.openTillId').attr('disabled',true);
                    $('#loading').remove();
                },
                success: function (data, status) {

                    if (status == 'success') {
                        $('.openTillId').attr('disabled',false);
                        if (data.res == false && data.permis == false) {
                            imgLoading(true, "Permission denied!!!", 5, status);
                        } else {

                            $.each(data.acc, function (ins, vals) {
                                var titles = 'Open Till Account';
                                if (parseInt(vals.tillstatus) == 0) {
                                    var titles = 'Close Till Account';
                                }if(parseFloat(vals.balance) > 0){
                                    $('#loading').remove();
                                    return alert(vals.account_name+ '  Account can be closed unless your balance equal to zero')
                                }

                                loadModale({
                                    byId: id,
                                    idSelector: selecBy,
                                    title: titles,
                                    labels: ['Account No /Acount Name', 'Branch', 'Currency', 'Balance', 'Value Date'],
                                    forms: {
                                        input: [
                                            {
                                                type: 'text',
                                                name: 'acc_no_name',
                                                class: 'form-control',
                                                Id: 'accNoName',
                                                placeholder: '',
                                                style: '',
                                                value: vals.account_no + '/' + vals.account_name
                                            },
                                            {
                                                type: 'text',
                                                name: 'branch_id',
                                                class: 'form-control',
                                                Id: 'bid',
                                                placeholder: 'Branch Id',
                                                style: '',
                                                value: vals.branch_name
                                            },
                                            {
                                                type: 'text',
                                                name: 'currency_id',
                                                class: 'form-control',
                                                Id: 'bid',
                                                placeholder: 'Currency',
                                                style: '',
                                                value: vals.currency_name
                                            },
                                            {
                                                type: 'text',
                                                name: 'balance',
                                                class: 'form-control',
                                                Id: '',
                                                placeholder: 'balance',
                                                style: '',
                                                value: accounting.formatMoney(vals.balance)
                                            },
                                            {
                                                type: 'text',
                                                name: 'vals_date',
                                                class: 'form-control',
                                                Id: 'vals_date',
                                                placeholder: '= Open Date',
                                                style: '',
                                                value: '<?PHP echo date("Y-m-d H:m:s", time()) ?>'
                                            },
                                            {
                                                type: 'hidden',
                                                name: 'status',
                                                class: 'form-control',
                                                Id: 'status',
                                                placeholder: '=',
                                                style: '',
                                                value: vals.tillstatus
                                            },
                                            {
                                                type: 'hidden',
                                                name: '_token',
                                                class: 'form-control',
                                                Id: 'token',
                                                placeholder: '',
                                                style: '',
                                                value: '{{csrf_token()}}'
                                            }
                                        ]
                                    },
                                    script: [
                                        '{{asset('theme/js/jquery.validate.min.js',isset($secure)?false:false)}}',
                                        '{{asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure)?false:false)}}',
                                    ]
                                });
                                $('#loading').remove()
                            });
                        }
                    }
                }, error: function (jgxht, status, errorthrogh) {
                    imgLoading(true, "Fail!!!! Please feadback to your developer or refresh your page. Errors Type:" + status, 12, status);
                }
            });
        }
        function fomart_all_currency(x, symbol){
            return accounting.formatMoney(x, symbol);
        }
    </script>
@endsection
