@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false)}}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
<!--<script src="{{ asset('theme/js/jquery.js',isset($secure) ? false : false)}}"></script>-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->

<style>
    @media print {
        a[href]:after {
            content: none !important;
        }
    }
    #unseen {
        height: calc(100vh - 366px);
        min-height: 294px;
    }
    section.panel {
        margin-bottom: 0px;
    }
    section.panel section.ox-scroll {
        height: calc(100vh - 368px);
        min-height: 200px;
    }
    .form-horizontal .form-group {
        margin-right: unset;
        margin-left: unset;
    }
    ::backdrop
    {
        background-color: white;
    }
    .panel-heading {
        margin-top: 6px;
    }
    .panel-heading, .panel-heading + .panel-collapse {
        border: 1px solid #e0e0e0;
    }
    .panel-heading .panel-title i.accordion_icon {
        float: right;
    }
    .panel-collapse .panel-body table th {
        min-width: 160px;
    }
    .datepicker td.disabled.day {
        color: #999999;
    }
    .datepicker td.active.day {
        pointer-events: none;
    }
    .form-group.required label::after {
        content: " *";
        color: red;
    }
    .tab-content {
        position: relative;
    }

    .tab-content .tab-pane {
        min-height: 200px;
    }

    .tab-content .loading {
        position: absolute;
        left: 50%;
        top: 20px;
        display: block;
        width: 40px;
        height: 40px;
        background: transparent url("{{ asset('images/loading.gif', isset($secure) ? false : false) }}") no-repeat scroll center center / contain;
    }

    .thumbnail {
        margin-bottom: 5px;
    }

    @media print {
        a[href]:after {
            content: none !important;
        }

        .hide-this {
            display: none !important;
        }

        .invisible_edit, .td-schedule-hide {
            display: none !important;
        }

        table td, table th {
            font-size: 1.4em;
        }
    }
    #printArea .panel-body.ox-scroll {
        height: calc(100vh - 104px);
    }
    #printArea .panel-body.ox-scroll.scroll-auto {
        height: calc(100vh - 458px);
        min-height: 200px;
    }
</style>
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('sidebar.sb_action_plan') }}</span>
        <div style="float:right;margin-top: -6px;">
            <span>
                <a id="btn-submit" class="btn btn-success">
                    <i class="fa fa-save"></i> {{ trans('multiple.m_save') }}
                </a>
            </span>
            <a href="{{route ('loan_recovery_action_plan') }}" class="btn btn-danger" onclick="return alertMessage('Are you sure you want to cancel?')">
                <i class="fa fa-time"></i> {{ trans('multiple.m_cancel') }}</a>
<!--            <button class="btn btn-primary" id="export"><i class="fa fa-sign-out"></i> {{ trans('multiple.export') }}</button>-->
<!--            <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>-->
        </div>
    </header>
    <div class="panel-body">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="position-center" style="width:100%;">
            <form class="form-horizontal" method="post" action="{{route ('loan_recovery_action_plan_form', [$master_loan_id]) }}" id="submit_form">
<!--            <form class="form-inline" method="get" action="{{ route('loan_status') }}" id="submit_form">-->
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="col-lg-12">
                    <div class="form-group col-lg-3 required">
                        <label for="action">{{ trans('multiple.m_action') }}</label>
                        <select name="action" id="action" class="form-control" required>
                            <option value="">--select--</option>
                            @foreach($action_items as $item)
                            <option value="{{ $item->code }}" {{ Request::get('action') == $item->code ? 'selected' : ($item->order_num == 1 ? 'selected' : '') }}>
                                {{ $item->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-3 required">
                        <!--                        <label for="whom">{{ trans('multiple.m_whom') }}</label>-->
                        <label for="whom">Whom</label>
                        <select name="whom" id="whom" class="form-control" required>
                            <option value="">--select--</option>
                            @foreach($action_whoms as $item)
                            <option value="{{ $item->code }}" {{ Request::get('whom') == $item->code ? 'selected' : ($item->order_num == 1 ? 'selected' : '') }}>
                            {{ $item->description }}</option>
                            @endforeach
                        </select>
                    </div>
<!--                    <div class="form-group col-lg-3 required">-->
<!--                        <label for="phone_number">{{ trans('multiple.phone') }}</label>-->
<!--                        <input type="text" class="form-control" value="{{ Request::get('phone_number') }}"-->
<!--                               id="phone_number" name="phone_number" value="{{$client_name}}" required/>-->
<!--                    </div>-->
                    <div class="form-group col-lg-3">
                        <label for="new_phone_number">New Phone Number</label>
                        <input type="text" class="form-control" value="{{ Request::get('new_phone_number') }}"
                               id="new_phone_number" name="new_phone_number" value="{{$client_name}}"/>
                    </div>
                    <div class="form-group col-lg-3 required">
<!--                        <label for="action_date">{{ trans('multiple.date_time') }}</label>-->
                        <label for="action_date">Date Time</label>
                        <input type="text" class="form-control" value="{{ Request::get('action_date') ? Request::get('action_date') :date('Y-m-d h:i:s A') }}"
                               id="action_date" name="action_date" value="{{$client_name}}" readonly required/>
                    </div>
                    <div class="form-group col-lg-3 required required" id="div_customer_response">
<!--                        <label for="customer_respond">{{ trans('multiple.customer_respond') }}</label>-->
                        <label for="customer_response">Customer Response</label>
                        <select name="customer_response" id="customer_response" class="form-control" required>
                            <option value="">--select--</option>
                            @foreach($action_customer_responses as $item)
                            <option value="{{ $item->code }}" {{ Request::get('customer_response') == $item->code ? 'selected' : '' }}>
                                {{ $item->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-3 required" id="div_promise_pay_date">
                        <label for="reason">Promise Pay Date</label>
                        <div data-date-viewmode="years" data-initialize="datepicker"  data-date="{{date('Y-m-d')}}" class="input-append date dpYears">
                            <input type="text" name="promise_pay_date" readonly
                                   value="{{ date('Y-m-d') }}" size="16" class="form-control" id="promise_pay_date">
                            <span class="add-on birhtdateDatepicker">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group col-lg-3 required" id="div_promise_amount">
                        <label for="promise_amount">Promise Amount</label>
                        <input type="text" class="form-control" value="{{ Request::get('promise_amount') }}"
                               id="promise_amount" name="promise_amount" required />
                    </div>
                    <div class="form-group col-lg-3 required" id="div_comment">
                        <label for="comment">Comment</label>
                        <select name="comment" id="comment" class="form-control">
                            <option value="">--select--</option>
                            @foreach($comment_items as $item)
                            <option value="{{ $item->code }}" {{ Request::get('comment') == $item->code ? 'selected' : '' }}>
                            {{ $item->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-3 required" id="div_capacity">
                        <label for="capacity">Capacity</label>
                        <select name="capacity" id="capacity" class="form-control">
                            <option value="">--select--</option>
                            @foreach($capacity_items as $item)
                            <option value="{{ $item->code }}" {{ Request::get('capacity') == $item->code ? 'selected' : '' }}>
                            {{ $item->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-3 required" id="div_construction_progress_note">
                        <label for="construction_progress_note">Construction Progress</label>
                        <input type="text" class="form-control" value="{{ Request::get('construction_progress_note') }}"
                               id="construction_progress_note" name="construction_progress_note" required />
                    </div>
                    <div class="form-group col-lg-3 required" id="div_solution">
                        <label for="solution">Solution</label>
                        <select name="solution" id="solution" class="form-control">
                            <option value="">--select--</option>
                            @foreach($solution_items as $item)
                            <option value="{{ $item->code }}" {{ Request::get('solution') == $item->code ? 'selected' : '' }}>
                            {{ $item->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-3 required" id="div_stop_pay_reason">
                        <label for="stop_pay_reason">Stop Pay Reason</label>
                        <select name="stop_pay_reason" id="stop_pay_reason" class="form-control">
                            <option value="">--select--</option>
                            @foreach($stop_pay_reasons as $item)
                            <option value="{{ $item->code }}" {{ Request::get('stop_pay_reason') == $item->code ? 'selected' : '' }}>
                                {{ $item->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-3 required" id="div_negotiation">
                        <label for="negotiation">Negotiation</label>
                        <select name="negotiation" id="negotiation" class="form-control">
                            <option value="">--select--</option>
                            @foreach($negotiation_items as $item)
                            <option value="{{ $item->code }}" {{ Request::get('negotiation') == $item->code ? 'selected' : '' }}>
                            {{ $item->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-3 required" id="div_reason_note">
<!--                        <label for="action_reason">{{ trans('multiple.action_reason') }}</label>-->
                        <label for="reason_note">Reason Note</label>
                        <input type="text" class="form-control" value="{{ Request::get('reason_note') }}"
                               id="reason_note" name="reason_note" required />
                    </div>
                    <div class="form-group col-lg-3 required" id="div_request_type">
                        <label for="request_type">Request Type</label>
                        <select name="request_type" id="request_type" class="form-control">
                            <option value="">--select--</option>
                            @foreach($request_type_items as $item)
                            <option value="{{ $item->code }}" {{ Request::get('request_type') == $item->code ? 'selected' : '' }}>
                            {{ $item->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-3 required" id="div_month_request">
                        <label for="month_request"># of Month Request</label>
                        <input type="text" class="form-control" value="{{ Request::get('month_request') }}"
                               id="month_request" name="month_request" required />
                    </div>
                    <div class="form-group col-lg-3 required" id="div_amount_request">
                        <label for="amount_request">Amount Request</label>
                        <input type="text" class="form-control" value="{{ Request::get('amount_request') }}"
                               id="amount_request" name="amount_request" required />
                    </div>
<!--                    <div class="form-group col-lg-3">-->
<!--                        <label for="remark">Remark</label>-->
<!--                        <input type="text" class="form-control" value="{{ Request::get('remark') }}"-->
<!--                               id="remark" name="remark" />-->
<!--                    </div>-->
                </div>
<!--                    <div class="col-lg-5">-->
<!--                        <div class="form-group">-->
<!--                            <input type="hidden" name="offset" />-->
<!--                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>-->
<!--                            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>-->
<!--                            <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>-->
<!--                            <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>-->
<!--                        </div>-->
<!--                    </div>-->
            </form>
        </div>
<!--        <div class="page">-->
<!--            <div class="custom-pagi custom">-->
<!--                <span class="pagi_label">Number of Rows:</span>-->
<!--                <input type="text" class="form-control" name="set_offset" value="{{ $offset }}" />-->
<!--                <a href="#" class="btn btn-danger">Go</a>-->
<!--            </div>-->
<!--        </div>-->
<!--        <br/><br/><br/><br/>-->
        <section id="unseen" class="col-lg-12 ox-scroll">
            <br/>
            <p>
                <a href="#" id="toggle_fullscreen">Toggle Fullscreen</a>
            </p>

            <div id="printArea">
                @include('api.report_header')

                <!--- start list loan information -->
                <div id="myprint_area">
                    @include('api.report_header')
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered table-striped table-condensed">
                                <tr>
                                    <th style="width: 25%;">{{ trans('report.rpt_office') }}</th>
                                    <td>{{ !empty($loan->branch->branch_name) ?$loan->branch->branch_name : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('report.rpt_contract_id') }}</th>
                                    <td>{{ $loan->contract_id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.credit_officer') }}</th>
                                    <td style="font-weight:bold">{{ !empty($loan->co_user->name) ? $loan->co_user->name : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('product.p_product_id') }}</th>
                                    <td>
                                        {{ !empty($loan->units->code) ? $loan->units->code: '-' }} ({{ !empty($loan->units->UnitType->name) ?$loan->units->UnitType->name: '-' }})
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_submitted_on') }}</th>
                                    <td>
                                        {{ date('d-M-Y',strtotime($loan->submitted_on)) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_approved_on') }}</th>
                                    <td>{{ !empty($loan->approval_date) ? date('d-M-Y',strtotime($loan->approval_date)): '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_disbursed_on') }}</th>
                                    <td>{{ !empty($loan->disburse_date) ? date('d-M-Y',strtotime($loan->disburse_date)): '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_settled_on') }}</th>
                                    <td>{{ (!empty($loan->settlement_date) && ($loan->settlement_date != "0000-00-00")) ? date("d-M-Y", strtotime($loan->settlement_date)): '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('report.rpt_contract_date') }}</th>
                                    <td>
                                        {{ !empty($loan->contract_date)  ? date('d-M-Y',strtotime($loan->contract_date)) : date('d-M-Y',strtotime($loan->start_date)) }}
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <th>{{ trans('loan.l_loan_purpose') }}</th>
                                    <td>{{ !empty($loan->loan_purpose) ? $loan->loan_purpose : '-'}}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_doc_location') }}</th>
                                    <td>{{ $loan->doc_location }}</td>
                                </tr>
                                <tr>
                                    <th>DSR</th>
                                    @if($loan->status == 1)
                                        <td style="color: #ff0000">
                                            {{number_format($loan->dsr,2,'.',',')}}%
                                        </td>
                                    @else
                                        <td>
                                            {{number_format($loan->dsr,2,'.',',')}}%
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                    <th>MoF</th>
                                    @if($loan->status == 1)
                                        <td style="color: #ff0000">
                                            {{number_format($loan->mof,2,'.',',')}}%
                                        </td>
                                    @else
                                        <td>
                                            {{number_format($loan->mof,2,'.',',')}}%
                                        </td>
                                    @endif
                                </tr> -->
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered table-striped table-condensed">
                                <tr>
                                    <th style="width: 25%;">{{ trans('loan.l_repayment_type') }}</th>
                                    <td>
                                        @if(!empty($static) && !empty($static['repayment_type']) &&
                                        array_key_exists($loan->repayment_type,$static['repayment_type']))
                                        {{ $static['repayment_type'][$loan->repayment_type] }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('report.rpt_loan_type') }}</th>
                                    <td>
                                        {{$product_type_arr[$loan->loan_type]}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('report.rpt_loan_amount') }}</th>
                                    <td>{{ ($loan->original_amount)? $loan->original_amount : $loan->loan_amount }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_down_payment') }}</th>
                                    <td>{{ $loan->down_payment }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('report.rpt_tenure') }}</th>
                                    <td>{{ $loan->loan_duration }} M</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_interest_rate') }}</th>
                                    <td>{{ number_format($loan->interest_rate,2) }}%
                                        (P.M), {{number_format(($loan->interest_rate*12),2)}}% (P.A)
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_penalty_rate',['num'=>'']) }}</th>
                                    <td>
                                        <?php
                                        if ($loan->penalty_rate_type == 1) {
                                            echo $loan->penalty_rate1 . ' % (for over ' . $loan->penalty_period1 . ' days)';
                                        } elseif ($loan->penalty_rate_type == 2) {
                                            echo $loan->penalty_rate1 . ' % (from ' . $loan->penalty_period1 . ' to 30 days)';
                                        } elseif ($loan->penalty_rate_type == 3) {
                                            echo $loan->penalty_rate1 . ' % ( from ' . $loan->penalty_period1 . ' to ' . $loan->penalty_period2 . ' days ), ';
                                            echo $loan->penalty_rate2 . ' % ( from ' . ($loan->penalty_period2 + 1) . ' to 30 days )';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_pay_off_rate',['num'=>'']) }}</th>
                                    <td>{{$loan->pay_off_rate1}}%(From 0 to {{ $loan->payoff_period1 }}
                                        months) {{$loan->pay_off_rate2}}%(From {{ $loan->payoff_period1 }}
                                        to {{ $loan->payoff_period2 }}months)
                                        {{ ($loan->pay_off_rate3 > 0) ? $loan->pay_off_rate3 .'%(From '. $loan->payoff_period2.' months)' : ''}}</td>
                                </tr>

                                <tr>
                                    <th>{{ trans('loan.l_currency') }}</th>
                                    <td>
                                        {{$loan->client_loan_account->currencies->code}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('report.rpt_outstanding_balance') }}</th>
                                    <td>
                                        <a href="{{ route('loan_account', [$loan->client_loan_account->id])}}">{{number_format($loan->client_loan_account->balance,2,'.',',')}} {{$loan->client_loan_account->currencies->code}}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('account.drawdown_acc') }}</th>

                                    <td>
                                        <?php
                                        $drawdow_acc = $loan->loan_drawdow_acc;
                                        ?>
                                        @if($drawdow_acc->currency == $loan->client_loan_account->currency)
                                        <!--<a href="{{ route('drawdown_account_detail', [$drawdow_acc->id])}}">{{number_format(balance_drawdown_acc($drawdow_acc->coa_id),2,'.',',')}} {{$loan->client_loan_account->currencies->code}}</a> -->
                                        <a href="{{ route('drawdown_account_detail', [$drawdow_acc->id])}}">{{number_format($drawdow_acc->balance,2,'.',',')}} {{$loan->client_loan_account->currencies->code}}</a>
                                        <br>
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_annual_yield') }}</th>
                                    @if($loan->status == 1)
                                    <td style="color: #ff0000">
                                        {{number_format($loan->annual_yield,2,'.',',')}}%
                                    </td>
                                    @else
                                    <td>
                                        {{number_format($loan->annual_yield,2,'.',',')}}%
                                    </td>
                                    @endif
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                <!--- end list loan information -->

                <!-- start tab -->

                <section>
                    <ul class="nav nav-tabs">
                        <li class="active" id="client_detail"><a data-toggle="tab"
                                                                 href="#client">{{ trans('customer.cus_customer') }}</a>
                        </li>
                        <li id="schedule_repayment"><a data-toggle="tab"
                                                       href="#schedule">{{ trans('loan.l_repayment_schedule') }}</a>
                        </li>
                        <li id="actual_repayment"><a data-toggle="tab"
                                                     href="#actual">{{ trans('loan.l_repayment_actual') }}</a></li>
                        <li id="transaction_detail"><a data-toggle="tab"
                                                       href="#transaction">{{ trans('loan.l_transaction') }}</a></li>
                        <li id="charge_detail"><a data-toggle="tab" href="#charge">{{ trans('loan.l_charge') }}</a></li>
                        <li id="guarantor_detail"><a data-toggle="tab"
                                                     href="#guarantor">{{ trans('loan.l_guarantor') }}</a></li>
                        <li id="co_borrower_detail"><a data-toggle="tab" href="#co_borrower">{{ trans('Co-Borrower') }}</a></li>
                        <li id="loandoc_detail"><a data-toggle="tab"
                                                   href="#loandoc">{{ trans('loan.l_loan_document') }}</a></li>
                        <li id="loanpay_detail"><a data-toggle="tab"
                                                   href="#loanpay">{{ trans('loan.l_detail_repayment_until_today') }}</a>
                        </li>
                        <!-- <li id="schedule_fee_"><a data-toggle="tab" href="#schedule_fee">{{ trans('loan.schedule_fee') }}</a></li> -->
                        <li id="do_audit"><a data-toggle="tab" href="#audit">{{ trans('multiple.audit') }}</a></li>
                    </ul>

                    <div class="panel-body">
                        <ul id="language" style="margin-top:1rem;padding-left:0;margin-bottom:2rem;">
                            <li><a class="btn btn-warning" id="printer"><i
                                            class="fa fa-print"></i> {{ trans('multiple.m_print') }}</a></li>
                            <!--<li><button class="btn btn-warning" id="customer_printer"><i class="fa fa-print"></i> {{trans('loan.l_customer_print')}}</button></li>-->
                            <!-- <li><a id="export" class="btn btn-primary" href="#"><i class="fa fa-print"></i> {{ trans('multiple.export') }}</a></li> -->
                            <li><a id="export" class="btn btn-primary"><i
                                            class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a></li>
                            <li><a id="xexport" class="btn btn-primary"><i
                                            class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a></li>
                        </ul>
                        <div id="printArea" class="printArea">
                            <div class="report_header">
                                <img src="{{ asset('images/header2.PNG') }}" style="width:100%;"/>
                            </div>
                            <!-- @include('api.report_header',['co_phone'=>!empty($loan->co_user) ? $loan->co_user->phone: '']) -->
                            <div class="tab-content">
                                <span class="loading" id="loading"></span>
                                <div id="client" class="tab-pane active">
                                    @if(!empty($loan->client))
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="fileupload-new thumbnail" style="width: 150px;">
                                                <img src="{{ $loan->client->general->first()->photo?asset('/data/clients/'.$loan->client->general->first()->photo, isset($secure) ? false : false):asset('images/noimage.gif', isset($secure) ? false : false) }}"
                                                     alt="Profile Picture"/>
                                            </div>
                                            @if(!empty($loan->client->location_latitude) && !empty($loan->client->location_longitude))
                                            <a href="javascript:;" class="client-view view-map"
                                               data-lat="{{ $loan->client->location_latitude }}"
                                               data-long="{{ $loan->client->location_longitude }}"
                                            >{{ trans('multiple.m_view_map') }}</a> &nbsp;
                                            @endif
                                            @if(!empty($loan->client->general->first()->signature))
                                            <a href="javascript:;" class="client-view"
                                               data-mfp-src="{{ asset('data/signatures/'.$loan->client->general->first()->signature, isset($secure) ? false : false)}}"
                                               id="client-signature">View Client Front</a>
                                            @endif
                                            <br/>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <table class="table-condensed">
                                                        <tr>
                                                            <th>{{ trans('customer.cus_customer_name') }} :</th>
                                                            <td>{{ $loan->client->client_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ trans('user.u_user_kh_name') }} :</th>
                                                            <td>{{ !empty($loan->client->general->first()->family_name_kh)?$loan->client->general->first()->family_name_kh .' '.$loan->client->general->first()->first_name_kh:'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ trans('multiple.m_gender') }} :</th>
                                                            <td>{{ !empty($loan->client->general->first()->gender)?$loan->client->general->first()->gender:'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ trans('customer.cus_nationality') }} :</th>
                                                            <td>{{ ($loan->client->general->first()->country != null && $loan->client->general->first()->country->description != null && !empty($loan->client->general->first()->country->description->first()->name))?$loan->client->general->first()->country->description->first()->name:'N/A' }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-4">
                                                    <table class="table-condensed">
                                                        <tr>
                                                            <th style="width: 30%;">{{ trans('customer.cus_birth_date') }}
                                                                :
                                                            </th>
                                                            <td>{{ !empty($loan->client->general->first()->date_of_birth)? date("d-M-Y", strtotime($loan->client->general->first()->date_of_birth)):'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ trans('multiple.m_phone',['num'=>'']) }} :</th>
                                                            <td>{{ $loan->client->phone1}}{{ !empty($loan->client->phone2)? ' / '. $loan->client->phone2:''}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ trans('multiple.m_address') }} :</th>
                                                            <td>{{ !empty($loan->client->address)? $loan->client->address:'N/A' }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-4">
                                                    <table class="table-condensed">
                                                        <tr>
                                                            <th>{{ trans('customer.cus_card_number') }} :</th>
                                                            <td>{{ !empty($loan->client->Identification->first()->id_number)?$loan->client->Identification->first()->id_number:'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ trans('customer.cus_issued_date') }} :</th>
                                                            <td>{{ !empty($loan->client->Identification->first()->issued_date) ? Date("d-M-Y", strtotime($loan->client->Identification->first()->issued_date)):'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ trans('customer.cus_issued_by') }} :</th>
                                                            <td>{{ !empty($loan->client->Identification->first()->issued_by) ? $loan->client->Identification->first()->issued_by:'N/A'}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{ trans('customer.cus_card_expired_date') }} :</th>
                                                            <td>{{ !empty($loan->client->Identification->first()->id_expiry_date) ?  date("d-M-Y", strtotime($loan->client->Identification->first()->id_expiry_date)):'N/A' }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @endif
                                    {{-- end client --}}
                                </div>
                                <div id="schedule" class="tab-pane">

                                </div>
                                <div id="actual" class="tab-pane">

                                </div>
                                <div id="transaction" class="tab-pane">

                                </div>
                                <div id="charge" class="tab-pane">

                                </div>
                                <div id="guarantor" class="tab-pane">

                                </div>
                                <div id="co_borrower" class="tab-pane">
                                </div>
                                <div id="loandoc" class="tab-pane">

                                </div>
                                <div id="loanpay" class="tab-pane">

                                </div>

                                <!-- <div id="schedule_fee" class="tab-pane"></div> -->

                                <div id="audit" class="tab-pane">
                                    <h5><strong>{{ trans('multiple.audit_title') }}</strong></h5>
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <th>{{ trans('multiple.action') }}</th>
                                            <th>{{ trans('multiple.by') }}</th>
                                            <th>{{ trans('multiple.date') }}</th>
                                            <th>{{ trans('multiple.status') }}</th>
                                        </tr>
                                        @foreach($audit as $au)
                                        <tr>
                                            <td>{{ trans('multiple.audit_created') }}</td>
                                            <td>{{$au->audit1->name}}</td>
                                            <td>{{$au->created_at}}</td>
                                            <td>{{ trans('multiple.unauthorized') }}</td>
                                        </tr>

                                        @if($au->audit2)
                                        <tr>
                                            <td>{{ trans('multiple.audit_updated') }}</td>
                                            <td>{{$au->audit2->name}}</td>
                                            <td>{{$au->updated_at}}</td>
                                            <td>{{ trans('multiple.authorized') }}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- end tab  -------------->
                <div class="modal fade" id="map" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog md-modify">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title">{{ trans('multiple.m_map') }}</h4>
                            </div>
                            <div class="modal-body">
                                <div id="googleMap" style="width:100%;height:500px;"></div>
                            </div>
                            <div class="modal-footer">
                                <button data-dismiss="modal" class="btn btn-default"
                                        type="button">{{ trans('multiple.m_close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $repay = [];
                if (!empty($loan)) {
                    $start_date = $loan->start_date;
                    $repay = [
                        'loan_id' => $loan->id,
                        'client_name' => $loan->client->client_name,
                        'start_date' => $start_date,
                        'loan_amount' => $loan->loan_amount,
                        'interest_rate' => $loan->interest_rate,
                        'loan_duration' => $loan->loan_duration,
                        'repayment_type' => $loan->repayment_type,
                        'num_balloon' => $loan->balloon,
                        'balloon_month' => $loan->balloon_month,
                        'balloon_amount' => $loan->balloon_amount_array,
                        'monthly_payment' => $loan->monthly_payment,
                        'loan_status' => $loan->status,
                        'disbursement_date' => $loan->disburse_date,
                        'admin_fee' => $loan->admin_fee,
                        'maintain_fee' => $loan->maintain_fee,
                        'admin_fee_opt' => $loan->admin_fee_opt,
                        'maintain_fee_opt' => $loan->maintain_fee_opt,
                        'other_fee' => $loan->other_fee
                    ];
                }
                ?>
                <input type="hidden" id="repayment-data" value="{{ json_encode($repay) }}"/>
                <input type="hidden" id="loan-id" value="{{ $loan->id }}"/>
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}"/>

            </div>


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
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.popup.min.js',isset($secure) ? false : false) }}"></script>
<script src="http://maps.google.com/maps/api/js"></script>
<script type="text/javascript" src="{{ asset('js/loans-detail.js',isset($secure) ? false : false) }}"></script>

<script type="text/javascript">
    function alertMessage(title) {
        if (confirm(title)) {
            disablePreventClose();
            return true;
        }
        initPreventClose();
        return false;
    }
    function initPreventClose() {
        window.onbeforeunload = function (e) {
            return "Please click 'Stay on this Page' if you did this unintentionally";
        };
    }
    initPreventClose();
    function disablePreventClose() {
        window.onbeforeunload = null;
    }
    // var isFirstOpenFullScreen = true;
    function openFullscreen(elem) {
        var content = $('#printArea .panel-body');
        // if (isFirstOpenFullScreen) {
        //     $(elem).bind('webkitfullscreenchange mozfullscreenchange fullscreenchange', function(e) {
        //         var state = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
        //         // var event = state ? 'FullscreenOn' : 'FullscreenOff';
        //         var isFullOn = state ? true : false;
        //
        //         if (isFullOn) {
        //             content.removeClass('scroll-auto');
        //         } else {
        //             content.addClass('scroll-auto');
        //         }
        //     });
        //     isFirstOpenFullScreen = false;
        // }
        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
    }
    $(document).ready(function () {

        $('#language').hide();
        $('#client_detail').on('click', function () {
            $('#language').hide();
        });
        $('#charge_detail').on('click', function () {
            $('#language').hide();
        });
        $('#loandoc_detail').on('click', function () {
            $('#language').hide();
        });
        $('#guarantor_detail').on('click', function () {
            $('#language').hide();
        });
        $('#co_borrower_detail').on('click', function () {
            $('#language').hide();
        });
        $('#schedule_repayment').on('click', function () {
            $('#language').show();
        });
        $('#actual_repayment').on('click', function () {
            $('#language').show();
        });
        $('#transaction_detail').on('click', function () {
            $('#language').show();
        });
        $('#loanpay_detail').on('click', function () {
            $('#language').show();
        });

        $("body").on("click", "a.edit_data", function (e) {
            e.preventDefault();
            var nRow = $(this).parents('tr')[0];
            var nCol = $(">td", nRow);
            $("#editSchModal input#m-interest").val(0);
            $("#editSchModal input#m-principal").val(0);
            $("#editSchModal input#rp-id").val($(this).attr("id"));
            for (var i = 0, iLen = nCol.length; i < iLen; i++) {
                if (nCol[i].className == 'interest') {
                    $("#editSchModal input#m-interest").val((nCol[i].innerHTML).replace(',', ''));
                } else if (nCol[i].className == 'principal') {
                    $("#editSchModal input#m-principal").val((nCol[i].innerHTML).replace(',', ''));
                } else if (nCol[i].className == 're_date') {
                    $("#editSchModal input#m-date").val(nCol[i].innerHTML);
                }

            }
            $("#editSchModal").modal("toggle");
        });
        $('body').on('click', '#editSchModal #save', function (e) {
            e.preventDefault();
            var loan_id = "{{ $loan->id }}";
            var schedule_id = "{{$schedule_id}}";
            var id = $("#editSchModal input#rp-id").val();
            var url = "{{ route('update_repayment_sch')}}";
            var int = $("#editSchModal input#m-interest").val();
            var pri = $("#editSchModal input#m-principal").val();
            var re_date = $("#editSchModal input#m-date").val();
            var repaydata = $("#repayment-data").val();
            if (repaydata != '') {
                var data = $.parseJSON(repaydata);
                $.extend(data, {
                    id: parseFloat(schedule_id) + parseFloat(id),
                    lid: loan_id,
                    interest: int,
                    principal: pri,
                    re_date: re_date
                });
                console.log(data);
                if (int != "" && pri != "") {
                    $.ajax({
                        url: url,
                        data: data,
                        success: function (d) {
                            if (d != null) {
                                $("#schedule").html(d);
                                $("#editSchModal").modal("toggle");
                            }
                        }
                    });
                }
            }
        });

        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight:'TRUE',
            startDate: '-0d',
        });

        var div_customer_response = $('#div_customer_response');
        var div_promise_pay_date = $('#div_promise_pay_date');
        var div_promise_amount = $('#div_promise_amount');
        var div_stop_pay_reason = $('#div_stop_pay_reason');

        var div_comment = $('#div_comment');
        var div_capacity = $('#div_capacity');
        var div_construction_progress_note = $('#div_construction_progress_note');
        var div_solution = $('#div_solution');
        var div_negotiation = $('#div_negotiation');
        var div_reason_note = $('#div_reason_note');
        var div_request_type = $('#div_request_type');
        var div_month_request = $('#div_month_request');
        var div_amount_request = $('#div_amount_request');

        function hideAllFields() {
            setHideField(div_customer_response);
            setHideField(div_promise_pay_date);
            setHideField(div_promise_amount);
            setHideField(div_stop_pay_reason);

            setHideField(div_comment);
            setHideField(div_capacity);
            setHideField(div_construction_progress_note);
            setHideField(div_solution);
            setHideField(div_negotiation);
            setHideField(div_reason_note);
            setHideField(div_request_type);
            setHideField(div_month_request);
            setHideField(div_amount_request);
        }
        hideAllFields();

        $('#action option').hide();
        var showActionArr = ['COL_ACTION_1', 'COL_ACTION_10', 'COL_ACTION_11'];
        for(var item of showActionArr) {
            $('#action option[value="'+ item +'"]').show();
        }
        $('#action').on('change', function() {
            var value = this.value || '';
            // - SMS or Letter Just Show Whon, Phone Number, Reason Note.
            var isSmsOrLetter = false;
            if (value != '{{ $call_action_code }}' && value != '{{ $walk_in_action_code }}' && value != '{{ $visit_action_code }}') {
                isSmsOrLetter = true;
            }
            // Todo: Hide request "Request Suspend" comment
            setVisibleCommentOption('COL_COMM_21', value != '{{ $call_action_code }}');

            onSmsLetterHideShowField(isSmsOrLetter);
            $('#customer_response').val('');
            if (value == '{{ $call_action_code }}') {
                whenSelectCallAction();
            } else if (value == '{{ $walk_in_action_code }}') {
                whenSelectWalkInAction();
            } else if (value == '{{ $visit_action_code }}') {
                whenSelectVisitAction();
            }
        });
        $('#action').change();
        $('#customer_response').on('change', function() {
            // setHideField(div_stop_pay_reason);
            // setHideField(div_promise_pay_date);
            // if (this.value == '{{ $promise_pay_date_code }}') {
            //     div_promise_pay_date.show();
            // } else if (this.value == '{{ $stop_pay_reason_code }}') {
            //     div_stop_pay_reason.show();
            // }

            hideAllFields();
            setShowField(div_customer_response);
            var actionCode = $('#action').val();
            var value = this.value;
            if (!value && !actionCode) {
                return;
            }

            if (actionCode == '{{ $call_action_code }}') {
                if (value == '{{ $stop_pay_code }}') {
                    // Todo: - Stop to Pay => Show Stop Pay Reason, Solution, Reason note.
                    setShowField(div_stop_pay_reason);
                } else if (value == '{{ $promise_pay_code }}') {
                    // Todo: - Promise to pay => Promise date, Promise Amount, Solution, Reason note.
                    setShowField(div_promise_pay_date);
                    setShowField(div_promise_amount);
                } else if (value == '{{ $request_restructure_code }}') {
                    // Todo: - Request Restructure​ => Request Type, # of Month Request, Amount Request, Reason note.
                    setShowField(div_request_type);
                    setShowField(div_month_request);
                    setShowField(div_amount_request);
                } else if (value == '{{ $other_code }}') {
                    // Todo: - Other => Comment, Capacity, Progress, Solution, Reason Note
                    setShowField(div_comment);
                    setShowField(div_capacity);
                    setShowField(div_construction_progress_note);
                } else {
                    // Todo: - Can't contact, No answer, customer cancel, No service, Number not in use, Wrong Number
                    //              =>  Solution, Reason note.
                }
                setShowField(div_solution);
                setShowField(div_reason_note);
            } else if (actionCode == '{{ $walk_in_action_code }}') {
                if (
                    [
                        '{{$request_restructure_code}}', '{{$request_partial_payment}}', '{{$request_waive_penalty}}',
                        '{{$request_partial_payment_waive_penalty}}', '{{$request_change_unit}}'
                    ].indexOf(value) >= 0
                ) {
                    // Todo: (Request Partial Payment, Request Waive Penalty, Request Partial Payment & Waive Penalty
                    //        Request Restructure, Request Change Unit): Show Promise Date, Promise Amount, Comment, Capacity to pay,
                    //        Construction Progress, Solution, Negotiation Progress, Reason note
                    setShowField(div_promise_pay_date);
                    setShowField(div_promise_amount);
                    setShowField(div_comment);
                    setShowField(div_capacity);
                    setShowField(div_construction_progress_note);
                    setShowField(div_negotiation);
                    setShowField(div_solution);
                    setShowField(div_reason_note);
                } else if (value == '{{$stop_pay_code}}') {
                    // Todo: Stop to Pay => Show Request Type, # of Month Request, Amount Request
                    setShowField(div_request_type);
                    setShowField(div_month_request);
                    setShowField(div_amount_request);
                } else if (value == '{{$request_suspend_payment}}') {
                    // Todo: Request Suspend Payment => Show Construction Progress, # of Month Request, Reason note.
                    setShowField(div_construction_progress_note);
                    setShowField(div_month_request);
                    setShowField(div_reason_note);
                } else if (value == '{{$customer_cancel}}' || value == '{{$other_code}}') {
                    // Todo: Customer Cancel, Other => Show only Reason note
                    setShowField(div_reason_note);
                }
            } else if (actionCode == '{{ $visit_action_code }}') {
                if (value == '{{$the_door_locked}}' || value == '{{$not_meet_customer}}') {
                    // Todo: The Door is Locked, Not meet customer: Show Solution, Reason note.
                    setShowField(div_solution);
                    setShowField(div_reason_note);
                } else if (value == '{{ $promise_pay_date_code }}') {
                    // Todo: Promise to Pay: Promise Date, Promise Amount, Capacity to Pay, Reason note.
                    setShowField(div_promise_pay_date);
                    setShowField(div_promise_amount);
                    setShowField(div_capacity);
                    setShowField(div_reason_note);
                } else if (value == '{{ $stop_pay_reason_code }}') {
                    // Todo: Stop to Pay : Capacity to Pay, Comment, Solution, Reason note
                    setShowField(div_capacity);
                    setShowField(div_solution);
                    setShowField(div_reason_note);
                } else if (value == '{{$customer_cancel}}' || value == '{{$other_code}}') {
                    // Todo: Customer Cancel, Other : Solution, Reason note.
                    setShowField(div_solution);
                    setShowField(div_reason_note);
                }
            }
        });
        function whenSelectCallAction() {
            // console.log('whenSelectCallAction');
            $('#customer_response option').hide();
            var showArr = ['COL_CUS_RESP_1', 'COL_CUS_RESP_2', 'COL_CUS_RESP_3', 'COL_CUS_RESP_4', 'COL_CUS_RESP_5', 'COL_CUS_RESP_6', 'COL_CUS_RESP_7', 'COL_CUS_RESP_8', 'COL_CUS_RESP_9', 'COL_CUS_RESP_10'];
            for(var item of showArr) {
                $('#customer_response option[value="'+ item +'"]').show();
            }
        }
        function whenSelectWalkInAction() {
            // console.log('whenSelectWalkInAction');
            $('#customer_response option').hide();
            var showArr = ['COL_CUS_RESP_11', 'COL_CUS_RESP_12', 'COL_CUS_RESP_13', 'COL_CUS_RESP_14', 'COL_CUS_RESP_15', 'COL_CUS_RESP_10', 'COL_CUS_RESP_8', 'COL_CUS_RESP_7', 'COL_CUS_RESP_2'];
            for(var item of showArr) {
                $('#customer_response option[value="'+ item +'"]').show();
            }
        }
        function whenSelectVisitAction() {
            // console.log('whenSelectVisitAction');
            $('#customer_response option').hide();
            var showArr = ['COL_CUS_RESP_2', 'COL_CUS_RESP_6', 'COL_CUS_RESP_8', 'COL_CUS_RESP_10', 'COL_CUS_RESP_16', 'COL_CUS_RESP_17'];
            for(var item of showArr) {
                $('#customer_response option[value="'+ item +'"]').show();
            }
        }
        $('#customer_response').change();

        function setHideField(field) {
            if (field && !field.hidden) {
                field.hide();
            }
        }
        function setShowField(field) {
            if (field && (field.hidden || typeof field.hidden == "undefined")) {
                field.show();
            }
        }
        function setVisibleCommentOption(optionKey, hide) {
            var requestSuspendOption = $('#comment option[value="'+ optionKey +'"]');
            if (hide) {
                requestSuspendOption.hide();
            } else {
                requestSuspendOption.show();
            }
        }
        function onSmsLetterHideShowField(hide = false) {
            hideAllFields();
            if (hide) {
                // div_reason_note.show();
                // setShowField(div_reason_note);
                // setHideField(div_promise_pay_date);
                // setHideField(div_stop_pay_reason);
                // setHideField(div_customer_response);
            } else {
                setShowField(div_customer_response);
            }
        }

        $('#toggle_fullscreen').on('click', function() {
            // if already full screen; exit
            // else go fullscreen
            var elem = document.getElementById('unseen');
            console.log(elem);
            openFullscreen(elem);
        });
        $('.panel-title > a').click(function() {
            $(this).find('i').toggleClass('fa-plus fa-minus')
                .closest('panel').siblings('panel')
                .find('i')
                .removeClass('fa-minus').addClass('fa-plus');
        });

//pagination
        $('#btn-submit').on('click', function () {
            if (alertMessage('Are you sure you want to save?')) {
                // val = $(this).parent().find('input[name="set_offset"]').val();
                // $('input[name="offset"]').val(val);
                $('#submit_form').submit();
                return false;
            }
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

    });
</script>
@endsection
