@extends('layouts.app')
@section('css')
    <link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/css/teller.css',isset($secure) ? false : false)}}"/>
@endsection
<?php $currency = config('static_data.currency_symbol'); $flag = 1; ?>
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="panel reloaddive">
                <header class="panel-heading"><span>{{ trans('sidebar.sb_till_operation') }}</span></header>

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
                    <div class="panel-body" style="">
                        <div class="col-sm-12">
                            <div class="left_content">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <h4>{{ trans('report.rpt_cash_in') }}</h4>
                                        <div class="form-group">
                                            <ul class="list-unstyled list-group-item">
                                                <li><a class="btn btn-group btn-lg btn-primary issueTill "
                                                       data-toggle="modal"
                                                       data-target="issueTill">{{ trans('teller.t_issue_till') }}</a>
                                                </li>
                                                <br/>
                                            </ul>
                                        </div>
                                        <h4>{{ trans('report.rpt_cash_out') }}</h4>
                                        <ul class="list-unstyled list-group-item ">
                                            <li><a class="btn btn-group btn-lg btn-primary returnTill"
                                                   data-toggle="modal"
                                                   data-target="returnTill">{{ trans('teller.t_return_till') }}</a>
                                            </li>
                                            <br/>
                                            <li>
                                                <a class="btn btn-group btn-lg btn-primary transferTill">{{ trans('teller.t_transfer_till') }}</a>
                                            </li>
                                            <br/>
                                            <li><a class="btn btn-group btn-lg btn-primary DisburseLoan"
                                                   href="#">{{ trans('teller.t_disburse_loan') }}</a>
                                            </li>
                                            <br/>
                                            <li>
                                                <a class="btn btn-group btn-lg btn-primary expense">{{ trans('teller.t_expense') }}</a>
                                            </li>
                                            <br/>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>
    <div id="result"></div>
    <div id="loading"></div>
@endsection

@section('js')
    <script type="text/javascript">
        var tellers = null;
        var cheifs = null;
        var chiefdata = null;
       // var status_flag = <?php echo $flag;?>
        $(document).ready(function () {
//console.log(status_flag);
//            if(status_flag == 0){
//                $(".issueTill").setAttribute("disabled", "disabled");
//                $(".returnTill").setAttribute("disabled", "disabled");
//            }else{
//                $(".issueTill").setAttribute("disabled", "enabled");
//                $(".returnTill").setAttribute("disabled", "disabled");
//            }
            operationScript();

            $(".issueTill").click(function () {

                $('<div id="result"></div>').appendTo('body');
                $('<div id="loading"></div>').appendTo('body');
                ScriptRequire(['/js/load/operation/issue_till.js'], function(status) {
                    imgLoading(true,'Loading.....',15,'waring');
                    if(status === 'success'){
                        issueTill_for_chief('issueTill');
                    }
                });
            });

            $(".repayLoan").click(function () {
                RepayLoan('repayLoan');
            });
            $(".incomeFee").click(function () {
                IncomeFee('incomeFee');
            });
            $(".transferTill").click(function () {
                transferTill('transferTill');
            });
            $(".DisburseLoan").click(function () {
                $('#result').remove();
                getDisburseLoanlist('DisburseLoan');
            });
            $(".expense").click(function () {
                $('#result').remove();
                Expense('expense');
            });
            $(".returnTill").click(function () {
                call_returnTillScript();
            });
            $(document).on('change', '#tran_teller', function () {
                var id = $(this).attr('selected', true).val();
                checkTillAccountStatus(id);
            });
        });


        function RepayLoan(repayLoan) {

            loadModale({
                idSelector: repayLoan,
                title: 'Open Till Account',
                labels: ['From', 'To', 'Amount'],
                forms: {
                    input: {
                        from: {
                            type: 'text',
                            name: 'acc_no_name',
                            class: 'form-control',
                            Id: 'accNoName',
                            placeholder: '',
                            style: '',
                            value: ''
                        },
                        to: {
                            type: 'text',
                            name: 'acc_no_name',
                            class: 'form-control',
                            Id: 'accNoName',
                            placeholder: '',
                            style: '',
                            value: ''
                        },
                        amount: {
                            type: 'text',
                            name: 'acc_no_name',
                            class: 'form-control',
                            Id: 'accNoName',
                            placeholder: '',
                            style: '',
                            value: ''
                        }
                    }
                },
                script: [
                    '{{asset('theme/js/jquery.validate.min.js',isset($secure)?false:false)}}',
                    '{{asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure)?false:false)}}',
                ]
            });
        }

        function IncomeFee(incomeFee) {

            loadModale({
                idSelector: incomeFee,
                title: 'Open Till Account',
                labels: ['From', 'To', 'Amount'],
                forms: {
                    input: {
                        from: {
                            type: 'text',
                            name: 'acc_no_name',
                            class: 'form-control',
                            Id: 'accNoName',
                            placeholder: '',
                            style: '',
                            value: ''
                        },
                        to: {
                            type: 'text',
                            name: 'acc_no_name',
                            class: 'form-control',
                            Id: 'accNoName',
                            placeholder: '',
                            style: '',
                            value: ''
                        },
                        amount: {
                            type: 'text',
                            name: 'acc_no_name',
                            class: 'form-control',
                            Id: 'accNoName',
                            placeholder: '',
                            style: '',
                            value: ''
                        }
                    }
                }, script: [
                    '{{url('theme/js/jquery.validate.min.js')}}',
                    '{{url('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js')}}',
                ]
            });
        }
        //i used ajax for calling script, yes I see
        function operationScript() {

            var url = '{{ asset('js/load/operation.js',isset($secure) ? false : false) }}';
            $.ajax({
                url: url,
                method: 'get',
                dataType: 'script',
                success: function (data, status) {
                    if (status == 'success') {
                    }
                }, error: function (jgxht, status, errorthrogh) {
                    imgLoading(true, "Fail!!!! refresh your page. Errors Type:" + status, 5, status);
                }
            });
        }

        function transferTill(transferTill) {

            var res = null, permis = null;
            var $from_or_chief = null;
            var teller = '';
            var chief = '';
            var balance = null;
            var datas = null;
            var chief_balance = null, teller_balance = null;
            $.ajax({
                url: '{{asset('/teller/transfer_till',isset($secure)?$secure: false)}}',
                method: 'get',
                dataType: 'json',
                timeout: 4000,
                async: false,
                success: function (data, status) {

                    tellers = data.teller;    // initial global variable
                    cheifs = data.chief;      // initial global variable
                    var till_status;
                    for (var k in data.status) {
                        var val = data.status[k];
                        till_status = val;
                    }
                    if (till_status === 1) {
                        imgLoading(true, 'Till account was closed', 3, 'warning');
                    } else {

                        $.each(data, function (ins, vals) {

                            if (ins === 'teller') {
                                for (var key in vals) {

                                    teller += '<option value="' + vals[key].id + '"> ' + vals[key].username + ' ( ' + vals[key].account_name + ' / ' + vals[key].account_no + ' ) </option>';
                                }
                            } else if (ins === 'chief') {

                                for (var keys in vals) {
                                    $from_or_chief = vals[keys].username;
                                    balance = vals[keys].balance;
                                    if (balance <= 0) {
//                                    imgLoading(true,"Balance is empty!!!",19);
//                                    $("#result").remove();
//                                    location.reload('.reloaddive')
                                    }
                                    chief += '<option value="' + vals[keys].id + '"> ' + vals[keys].username + ' ( ' + vals[keys].account_name + ' / ' + vals[keys].account_no + ') </option>';
                                }
                                $('<div id="result"></div>').appendTo('body');
                            } else if (ins === 'permis' && data.permis == false) {
                                imgLoading(true, "Permission denied!!!", 19);
                                $("#result").remove()
                                location.reload();
                            }
                        });

                        loadModale({
                            idSelector: transferTill,
                            title: 'Transfer Till',
                            labels: ['From', 'To', 'Amount', 'Description'],
                            loadType: 'transferTill',
                            forms: {
                                input: {
                                    selection: {
                                        from: {1:chief, class: 'form-control', name: 'tran_chief', id: 'tran_idchief'},
                                        to: {1:teller, class: 'form-control', name: 'tran_teller', id: 'tran_teller'},
                                    },
                                    Amount: {
                                        type: 'text',
                                        name: 'tran_amount',
                                        class: 'form-control',
                                        Id: 'tran_amount',
                                        placeholder: '',
                                        style: '',
                                        value: ''
                                    },
                                    token: {
                                        type: 'hidden',
                                        name: '_token',
                                        class: 'form-control',
                                        Id: 'token',
                                        placeholder: '',
                                        style: '',
                                        value: '{{csrf_token()}}'
                                    },
                                },
                                textarea: {
                                    description: {class: 'form-control', name: 'tran_descr', rows: 10, id: 'tran_descr'}
                                }
                            }, script: [
                                '{{asset('theme/js/jquery.validate.min.js',isset($secure)?false:false)}}',
                                '{{asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure)?false:false)}}',
                            ]
                        });
                    }
                }, error: errorCallback,
            });
        }

        function checkTillAccountStatus(id) {

            if (typeof id != 'undefined') {

                for (var key in tellers) {
                    var val = tellers[key];
                    if (Number(id) === Number(val.id)) {
                        if (Number(val.tillstatus) === 1) {
                            imgLoading(true, 'This till account named ' + '<b><i> ' + val.name + ' </i></b>' + ' was closed', 4, 'warning');
                            $('input[type=submit]').attr('disabled', true);
                        } else {
                            imgLoading(true, 'Till account ' + ' <b><i> ' + val.name + ' </i></b> ' + ' is opening', 1, 'success');
                            $('input[type=submit]').attr('disabled', false);
                        }
                    }
                }
            } else {
                return false;
            }
        }

        function call_returnTillScript() {

            $.ajax({
                url: '{{ asset('/js/load/returnTill.js',isset($secure) ? false : false) }}',
                method: 'get',
                headers: {
                    '_TOKEN': $('meta[name="_token"]').attr('content')
                },
                dataType: 'script',
                success: function (data, status) {

                    if (status == 'success') {
                    } else {
                        imgLoading(true, "Fail!!!! refresh your page. Errors Type:" + status, 5, status);
                    }
                }, error: function (jgxht, status, errorthrogh) {

                    imgLoading(true, "Fail!!!! refresh your page. Errors Type:" + status, 5, status);
                }
            });
        }

        function getDisburseLoanlist(vals) {

            $('<div id="result"></div>').appendTo('body');

            loadModale({
                idSelector: vals,
                title: 'Disburse Loan list for tellers',
                keyboard: 'dynamic',
                backdrop: 'static',
                sms: '<div id="sms"></div>'
            });
            var $this = $('#' + vals);
            var removeBtnSubmit = $this.find($('input[type=submit]')).remove();
            $('<button type="button" class="btn btn-info" id="reload" >Reload</button>').insertBefore($this.find('.modal-footer').children());
            $('<div id="loading"></div>').appendTo('body');
            Pasthtmls($this, messages);
            $this.find('.modal-footer').children('#reload').click(function () {
                var check = $('.table').remove();
                Pasthtmls($this, messages);
            });
        }

        function Pasthtmls($this, messages) {

            var htmls = '';
            Getdata(messages, function (data) {
                htmls += '<table class="table-repsonsive table">';
                htmls += '<thead>';
                htmls += '<tr>';
                htmls += '<th>{{ trans('multiple.m_no') }}</th>';
                htmls += '<th>{{ trans('customer.cus_customer_name') }}</th>';
                htmls += '<th>{{ trans('report.rpt_office') }}</th>';
                htmls += '<th>{{ trans('loan.l_acc_name') }}</th>';
                htmls += '<th>{{ trans('report.rpt_contract_date') }}</th>';
                htmls += '<th>{{ trans('loan.l_approval_id') }}</th>';
                htmls += '<th>{{ trans('loan.l_approval_date') }}</th>';
                htmls += '<th>{{ trans('multiple.m_action') }}</th>';
                htmls += '</tr>';
                htmls += '</thead><tbody>';
                var i = 1;
                $.each(data, function (ins, vals) {
                    htmls += '<tr>';
                    htmls += '<td>' + i + '</td>';
                    htmls += '<td>' + vals.client.client_name + '</td>';
                    htmls += '<td>' + vals.branch.branch_name + '</td>';
                    htmls += '<td>' + vals.contract_id + '</td>';
                    htmls += '<td>' + vals.start_date + '</td>';
                    htmls += '<td>' + vals.approval.id + '</td>';
                    htmls += '<td>' + vals.approval.approval_date + '</td>';
                    htmls += '<td>m_action</td>';
                    htmls += '</tr>';
                    i++;
                });
                htmls += '</tbody></table>';
                return $(htmls).insertBefore($this.find('.container').children());
            });
        }
        ;

        function Getdata(messages, vals) {

            $.ajax({
                url: '{{asset('teller/disburse_list',isset($secure)?false:false)}}',
                method: 'get',
                dataType: "json",
                cache: false,
                headers: {
                    'token': $('meta[name="_token"]').attr('content')
                },
                success: function (data, status) {

                    if (status == 'success') {

                        if (data.res === true && typeof data.data != 'undefined' && Object.keys(data.data).length != 0) {
                            imgLoading(true, 'Data has been loaded!!!', 4, status);
                            return vals(data.data);
                        } else {
                            imgLoading(true, 'Data has been loaded!!!', 4, status);
                            $('#sms').html('');
                            $('<p>No result</p>').appendTo('#sms');
                        }
                    }
                }
            });
        }

        function Expense(selector) {

            $.ajax({
                url: '{{asset('js/load/expends.js',isset($secure)?false:false)}}',
                method: 'get',
                dataType: 'script',
                success: function (data, status) {
                    return;
                }, error: function (jgxht, status, errorthrogh) {
                    imgLoading(true, "Fail!!!! refresh your page. Errors Type:" + status, 5, status);
                }
            });
        }

        function ScriptRequire(script, retn) {

            $.each(script, function (inx, vals) {
                $.ajax({
                    url: vals,
                    dataType: "script",
                    cache: false,
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    },
                    success: function (data, status) {
                        return retn(status);
                    },
                    error: function () {
                        throw new Error("Could not load script " + script);
                    }
                });
            });
        }
    </script>
@endsection