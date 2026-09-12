@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false)}}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
<style>
    @media print {
        a[href]:after {
            content: none !important;
        }
    }
    .modal-overflow-scroll {
        overflow: auto;
        height: calc(100vh - 130px);
    }
    #modal_action_list .modal-dialog {
        width: 100%;
        max-width: 1200px;
    }
    #modal_assign .modal-body {
        display: grid;
    }
    #modal_assign .form-horizontal .form-group {
        margin-right: unset;
        margin-left: unset;
    }
    .form-group.required > label::after {
        content: " *";
        color: red;
    }
    .form-group.required label.error {
        color: red;
    }
    .action-box {
        margin: 0px 0px;
        border: 1px solid #a9a9a96b;
        border-radius: 18px;
        padding: 8px 0px;
        margin-bottom: 12px;
    }
    .datepicker td.disabled.day {
        color: #999999;
    }
    .datepicker td.active.day {
        pointer-events: none;
    }
    .modal-header h4.modal-title {
        font-weight: 600;
        font-size: 22px;
    }
    table.filter-full-width, table.filter-full-width input.form-control,
    table.filter-full-width select.form-control {
        width: 100%;
    }
    thead.center-middle-header th {
        text-align: center;
        vertical-align: middle;
    }
    .no-wrap {
        white-space: nowrap;
    }
    table td {
        vertical-align: middle !important;
    }
</style>
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('sidebar.sb_loan_action_plan') }}</span>
        <div style="float:right;margin-top: -6px;">
            @if($is_can_assign and !$is_summary_page)
                <span><a class="btn btn-success" href="#modal_assign" data-toggle="modal">
                        <i class="fa fa-users"></i> Assign </a>
                </span>
                <span><a class="btn btn-danger" href="{{route('loan_recovery_freeze_master_loan')}}" onclick="return confirm('Are you sure to Freeze Data?')">
                        <i class="fa fa-stack-overflow"></i> Freeze Data </a>
                </span>
            @endif

            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
            <button class="btn btn-primary" id="export"><i class="fa fa-sign-out"></i> {{ trans('multiple.export') }}</button>
            <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
        </div>
    </header>
    <div class="panel-body">

        <form class="form-inline" method="post" id="search_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input type="hidden" name="search_filter" value="true"/>
            <div class="row">
                <div class="form-group col-lg-4">
                    <table class="filter-full-width">
                        <tr>
                            <td><label for="input_search">{{ trans('multiple.m_search') }}</label></td>
                            <td>
                                <input type="text" class="form-control" value="{{ Request::get('input_search') }}" placeholder="Unit Code, LC, Name, Phone"
                                       id="input_search" name="input_search" value="{{ $input_search }}"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <label class="col-lg-12">Filter</label>
            </div>
            <div class="row">
                <div class="form-group col-lg-4">
                    <table class="filter-full-width">
                        <tr>
                            <td><label for="filter_payment_status">Payment Status</label></td>
                            <td>
                                <select name="filter_payment_status" id="filter_payment_status" class="form-control">
                                    <option value="">--select--</option>
                                    @foreach($action_payment_status as $item)
                                    <option value="{{ $item->code }}" {{ Request::get('filter_payment_status') == $item->code ? 'selected' : '' }}>
                                    {{ $item->description }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="filter_overdue_day">Overdue Day</label></td>
                            <td>
                                <input type="text" class="form-control" value="{{ Request::get('filter_overdue_day') }}"
                                       id="filter_overdue_day" name="filter_overdue_day" value="{{ $filter_overdue_day }}"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="form-group col-lg-4">
                    <table class="filter-full-width">
                        <tr>
                            <td><label for="filter_result">Result</label></td>
                            <td>
                                <select name="filter_result" id="filter_result" class="form-control">
                                    <option value="">--select--</option>
                                    @foreach($action_customer_responses as $item)
                                    <option value="{{ $item->code }}" {{ Request::get('filter_result') == $item->code ? 'selected' : '' }}>
                                    {{ $item->description }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="filter_promise_date">Promise Date</label></td>
                            <td>
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date="{{ Request::get('filter_promise_date') }}" class="input-append date dpYears">
                                    <input type="text" name="filter_promise_date"
                                           value="{{ Request::get('filter_promise_date') }}" size="16" class="form-control" id="filter_promise_date">
                                    <span class="add-on birhtdateDatepicker">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="form-group col-lg-4">
                    <table class="filter-full-width">
                        <tr>
                            <td><label for="filter_main_project">Main Project</label></td>
                            <td>
                                <select name="filter_main_project" id="filter_main_project" class="form-control">
                                    <option value="">--select--</option>
                                    @foreach($main_projects as $item)
                                    <option value="{{ $item->code }}" {{ Request::get('filter_main_project') == $item->code ? 'selected' : '' }}>
                                    {{ $item->description }}</option>
                                    @endforeach
                                </select>
<!--                                <input type="text" class="form-control" value="{{ Request::get('filter_main_project') }}"-->
<!--                                       id="filter_main_project" name="filter_main_project" value="{{ $filter_main_project }}"/>-->
                            </td>
                        </tr>
                        <tr>
                            <td><label for="filter_last_call_date">Last Calling Date</label></td>
                            <td>
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date="{{ Request::get('filter_last_call_date') }}" class="input-append date dpYears">
                                    <input type="text" name="filter_last_call_date"
                                           value="{{ Request::get('filter_last_call_date') }}" size="16" class="form-control" id="filter_last_call_date">
                                    <span class="add-on birhtdateDatepicker">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row col-lg-4">
                <button class="btn btn-success" type="submit"><i class="fa fa-search"></i> Filter</button>
                <a href="{{route('loan_recovery_action_plan')}}" class="btn btn-danger">Clear</a>
            </div>
        </form>
        <div class="page row">
            <div class="custom-pagi custom">
                <span class="pagi_label">Number of Rows:</span>
                <input type="text" class="form-control" name="set_offset" value="{{ $offset }}" />
                <a href="#" class="btn btn-danger">Go</a>
            </div>
        </div>

        <section id="unseen" class="ox-scroll">
            <div id="printArea">
                @include('api.report_header')
                <table class="table table-striped table-bordered" style="width:100%" id="loan_status_summary">
                    <thead class="center-middle-header no-wrap">
                        <th>{{ trans('multiple.m_no') }}</th>
                        <th>Loan ID</th>
                        <th>{{ trans('report.rpt_contract_id') }}</th>
                        <th>Main Project</th>
                        <th>Variance Code</th>
                        <th>{{ trans('customer.cus_customer_name') }}</th>
                        <th>Phone Number</th>
                        <th>{{ trans('report.rpt_disbursement_date') }}</th>
                        <th>{{ trans('report.rpt_loan_amount') }}</th>
                        <th>Outstanding Balance</th>
                        <th>DD Account</th>
                        <th>DD Balance</th>
                        <th>Pmt. No.</th>
                        <th>Schedule Date</th>
                        <th>Schedule Amount</th>
                        <th style="min-width: 106px;">Arrear Date</th>
                        <th>Overdue Day</th>
                        <th>Overdue Amount</th>
                        <th>Penalty Amount</th>
                        <th>Amount to be Collect</th>
                        <th>Payment Status</th>
                        <th>Last Calling Date</th>
                        <th>Negotiation Progress</th>
                        <th>Result</th>
                        <th>Customer Respond</th>
                        @if($is_summary_page)
                            <th style="min-width: 80px;">Assignee</th>
                        @else
                            <th style="min-width: 80px;">Actions</th>
                        @endif
                    </thead>
                    <tbody style="vertical-align: middle">
                    <?php $i = 0;?>
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
                        <!--{{ trans('multiple.m_no') }}-->
                        <td style="text-align: center">{{$i}}</td>

                        <!-- Loan ID -->
                        <td style="text-align: center">
                            {{ $l->loan_id }}
                        </td>

                        <!--{{ trans('report.rpt_contract_id') }}-->
                        <td style="text-align: center">
                            {{ $l->contract_id }}
                        </td>

                        <!--Main Project-->
                        <td>
                            {{ $l->main_project_name ? $l->main_project_name : '-' }}
<!--                            {{ $l->units->UnitType->Projects ? $l->units->UnitType->Projects->dealer_en : '-' }}-->
                        </td>

                        <!--Variance Code-->
                        <td>
                            {{ $l->variant_code ? $l->variant_code : '-' }}
<!--                            {{ !empty($l->units->code) ? $l->units->code: '-' }} ({{ !empty($l->units->UnitType->name) ?$l->units->UnitType->name: '-' }})-->
                        </td>

                        <!--{{ trans('customer.cus_customer_name') }}-->
                        <td>{{$l->client_name}}</td>

                        <!--Phone Number-->
                        <td>{{ $l->phone1 }}</td>

                        <!--Disbursement Date-->
                        <td style="text-align: center">
                            {{$l->disbursement_date ? date("d-M-Y", strtotime($l->disbursement_date)) : '-'}}</td>

                        <!--Loan Amount-->
                        <td style="text-align: right">
                            {{$l->loan_amount ? '$'.number_format($l->loan_amount,2,'.',',') : '$'.number_format($l->loan_amount,2,'.',',')}}</td>

                        <!--Outstanding Balance-->
                        <td style="text-align: right;">{{ '$'.number_format($l->outstanding_balance,2,'.',',') }}</td>

                        <!--DD Account-->
                        <td>
                            {{ $l->drawdown_account ? $l->drawdown_account : '-' }}
                        </td>

                        <!--DD Balance-->
                        <td style="text-align: right;">{{ '$'.number_format($l->drawdown_balance,2,'.',',') }}</td>

                        <!--Pmt. No.-->
                        <td style="text-align: center">
                            {{ $l->payment_no ? $l->payment_no : '-' }}
                        </td>

                        <!--Schedule Date-->
                        <td style="text-align: center">{{$l->schedule_date ? date("d-M-Y", strtotime($l->schedule_date)) : '-'}}</td>

                        <!--Schedule Amount-->
                        <td style="text-align: right">{{ '$'.number_format($l->schedule_amount,2,'.',',') }}</td>

                        <!--Arrear Date-->
                        <td style="text-align: center">
                            {{ $l->getArearDate() ? date("d-M-Y", strtotime($l->getArearDate())) : '' }}
                        </td>

                        <!--Overdue Day-->
                        <td style="text-align: center">
                            {{ $l->getOverdueDay() ? $l->getOverdueDay() : '' }}
                        </td>

                        <!--Overdue Amount-->
                        <td style="text-align: right">
                            {{ $l->getOverdueAmount() ? '$'.number_format($l->getOverdueAmount(),2,'.',',') : '' }}
                        </td>

                        <!--Penalty Amount-->
                        <td style="text-align: right">
                            {{ $l->getPenaltyAmount() ? '$'.number_format($l->getPenaltyAmount(),2,'.',',') : '' }}
                        </td>

                        <!--Amount to be Collect-->
                        <td style="text-align: right">
                            {{ '$'.number_format($l->getAmountToCollect(),2,'.',',') }}
                        </td>

                        <!--Payment Status-->
                        <td>
                            {{ $l->getPaymentStatus() ? $l->getPaymentStatus()->description_en : '-' }}
                        </td>

                        <!--Last Calling Date-->
                        <td>
                            {{ $l->getLastAction() ? date("d-M-Y G:i A", strtotime($l->getLastAction()->action_date)) : '' }}
                        </td>

                        <!--Negotiation Progress-->
                        <td>
                            {{ $l->getNegotiationProgressStatus() ? $l->getNegotiationProgressStatus()->description_en : '' }}
                        </td>

                        <!--Result-->
                        <td style="text-align: justify;">{{ $l->getLastAction() ? ($l->getLastAction()->action == $call_action_code ? $l->getLastAction()->customerResponseRef->description_en : $l->getLastAction()->actionRef->description_en) : '' }}</td>

                        <!--Customer Respond-->
                        <td style="text-align: justify;">{{ $l->getLastAction() ?
                            ($l->getLastAction()->stopPayReasonRef ? $l->getLastAction()->stopPayReasonRef->description_en : $l->getLastAction()->reason_note)
                            : '' }}</td>

                        @if($is_summary_page)
                            <!--Assignee-->
                            <td class="text-center">
                                {{ $l->getAssignee() ? $l->getAssignee()->name : '' }}
                            </td>
                        @else
                            <!--Actions-->
                            <td class="text-center">
                                <a href="{{ route('loan_recovery_action_plan_form',[$l->id]) }}" class="btn btn-default btn-xs" title="Make Action"><i class="fa fa-pencil"></i></a>
                                <a href="#modal_action_list" onclick="refreshActionListModal({{$l->loan_id}})" data-toggle="modal"
                                   class="btn btn-default btn-xs" title="View Actions"><i class="fa fa-eye"></i></a>
                            </td>
                        @endif

                    </tr>
                    @empty
                    <tr><td class="text-center" colspan="12">{{ trans('multiple.m_no_result') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>

            </div>



            <!-- start dialog  -------------->
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="modal_action_list" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">Áction List</h4>
                        </div>
                        <div class="modal-body">
                            <div class="modal-overflow-scroll">
<!--                                <table  class="table table-striped table-bordered" style="width:100%" id="loan_status_summary">-->
<!--                                    <thead>-->
<!--                                    <th style="text-align: center; vertical-align: middle;">ID</th>-->
<!--                                    <th style="text-align: center; vertical-align: middle;">Action Date</th>-->
<!--                                    <th style="text-align: center; vertical-align: middle;">Action</th>-->
<!--                                    <th style="text-align: center; vertical-align: middle;">Whom</th>-->
<!--                                    <th style="text-align: center; vertical-align: middle;">Phone Number</th>-->
<!--                                    <th style="text-align: center; vertical-align: middle;">Customer Response</th>-->
<!--                                    <th style="text-align: center; vertical-align: middle;">Promise Pay Date</th>-->
<!--                                    <th style="text-align: center; vertical-align: middle;">Stop Pay Reason</th>-->
<!--                                    <th style="text-align: center; vertical-align: middle;">Reason Note</th>-->
<!--                                    <th style="text-align: center; vertical-align: middle;">Remark</th>-->
<!--                                    <th style="text-align: center; vertical-align: middle;">Created By</th>-->
<!--                                    </thead>-->
<!--                                    <tbody style="vertical-align: middle">-->
<!--                                    </tbody>-->
<!--                                </table>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end dialog  -------------->

            <!-- start assign dialog  -------------->
            <div aria-hidden="true" aria-labelledby="modalAssign" role="dialog" tabindex="-1" id="modal_assign" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">Assign User</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" method="post" action="{{route ('loan_recovery_assign_loan') }}" id="assign_form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <input type="hidden" name="assign_option" id="assign_option" value="1">
                                <div class="col-lg-12">
                                    <ul class="nav nav-tabs">
                                        @foreach($assign_options as $item)
                                            <li id="assign_option_{{ $item['value'] }}" class="{{ $item['value'] == 1 ? 'active' : '' }}">
                                                <a data-toggle="tab" data-value="{{ $item['value'] }}"
                                                   href="#option_content_{{ $item['value'] }}">{{ $item['label'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="panel-body">
                                        <div class="tab-content">
                                            @foreach($assign_options as $item)
                                                @if($item['value'] == 1)
                                                <div class="tab-pane active" id="option_content_{{ $item['value'] }}">
                                                    <div class="form-group col-lg-12 required">
                                                        <label for="action">Assign User Profile</label>
                                                        <select name="assignee_role" id="action" class="form-control" required>
                                                            <option value="">--select--</option>
                                                            @foreach($assignee_roles as $item)
                                                            <option value="{{ $item->role }}">
                                                                {{ $item->role_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @elseif($item['value'] == 2)
                                                <div class="tab-pane" id="option_content_{{ $item['value'] }}">
                                                    <div class="col-lg-6">
                                                        <label>Download <strong><a href="/csvs/assign_sample.xlsx" class="text-primary">sample.xlsx</a></strong></label>
                                                    </div>
                                                    <div class="form-group col-lg-6 required">
                                                        <label for="assign_excel_file">Upload Excel(.xlsx)</label>
                                                        <input type="file" name="assign_excel_file" class="form-control-file" id="assign_excel_file" required>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
<!--                                <div class="col-lg-12">-->
<!---->
<!--                                    <div class="form-group col-lg-6 required">-->
<!--                                        <label for="action">Assign Option</label>-->
<!--                                        @foreach($assign_options as $item)-->
<!--                                        <div class="form-check col-lg-12">-->
<!--                                            <input class="form-check-input" type="radio" name="assign_option" id="assign_option{{ $item['value'] }}" value="{{ $item['value'] }}">-->
<!--                                            <label class="form-check-label" for="assign_option{{ $item['value'] }}">-->
<!--                                                {{ $item['label'] }}-->
<!--                                            </label>-->
<!--                                        </div>-->
<!--                                        @endforeach-->
<!--                                    </div>-->
<!--                                    <div class="form-group col-lg-6 required">-->
<!--                                        <label for="action">Assign User Profile</label>-->
<!--                                        <select name="assignee_role" id="action" class="form-control" required>-->
<!--                                            <option value="">--select--</option>-->
<!--                                            @foreach($assignee_roles as $item)-->
<!--                                            <option value="{{ $item->role }}">-->
<!--                                            {{ $item->role_name }}</option>-->
<!--                                            @endforeach-->
<!--                                        </select>-->
<!--                                    </div>-->
<!--                                </div>-->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" id="btn-summit-assign" value="Submit" class="btn btn-info"/>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end assign dialog  -------------->




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
        <div class="page pull-right">
            <?php
                echo $loans->appends(['offset' => $offset])->render();
            ?>
        </div>
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
<script src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false)}}"></script>

<script type="text/javascript">
    function refreshActionListModal(loan_id) {
        var table_body = $('#modal_action_list tbody');
        var modal_action_list_scroll = $('#modal_action_list .modal-body .modal-overflow-scroll');
        var modalTitle = $('#modal_action_list h4.modal-title');
        modalTitle.html('Action List of Loan #' + loan_id);
        modal_action_list_scroll.html('<p class="text-center">Loading...</p>');
        $.ajax({
            url: "{{ route('loan_recovery_action_list_loan_id', ['id' => '']) }}/" + loan_id,
            method: 'get',
            success: function (res_jd) {
                console.log(res_jd, !res_jd || res_jd.length <= 0);
                if (!res_jd || res_jd.length <= 0) {
                    // modal_action_list_scroll.html('');
                    modal_action_list_scroll.html('<p class="text-center">Not found!</p>');
                    // table_body.html('<p class="text-center">Not found!</p>');
                    return;
                }
                var rowsStr = '';
                for(var i = 0; i < res_jd.length; i++) {
                    var item = res_jd[i];
                    if (item) {
                        console.log('action_list_loan', item);
                        var isCallAction = item.action == '{{ $call_action_code }}';
                        var isWalkInAction = item.action == '{{ $walk_in_action_code }}';
                        var isVisitAction = item.action == '{{ $visit_action_code }}';
                        var row = '<div class="row action-box">';
                        row += '<div class="col-lg-4">';
                        row += '    <table class="table-condensed"><tbody>';
                        // row += '    <tr><th>ID :</th><td>'+ item.id +'</td></tr>';
                        row += '    <tr><th>Action :</th><td>'+ (item.action_ref ? item.action_ref.description_en : item.action) +'</td></tr>';
                        row += '    <tr><th>Whom :</th><td>'+ (item.whom_ref ? item.whom_ref.description_en : item.whom) +'</td></tr>';
                        row += '    <tr><th>Created By :</th><td>'+ item.created_by +'</td></tr>';
                        row += '    <tr><th>Action Date :</th><td>'+ (item.action_date_str ? item.action_date_str : item.action_date) +'</td></tr>';
                        row += '    </tbody></table>';
                        row += '</div>';

                        var isShowSolution = false;
                        var isShowReasonNote = false;
                        var isShowStopPayReason = false;
                        var isShowPromiseDate = false;
                        var isShowPromiseAmount = false;
                        var isShowRequestType = false;
                        var isShowMonthRequest = false;
                        var isShowAmountRequest = false;
                        var isShowComment = false;
                        var isShowCapacity = false;
                        var isShowConstructionProgress = false;
                        var isShowNegotiationProgress = false;
                        // console.log(item.customer_response);
                        if (isCallAction) {
                            if (item.customer_response == '{{ $stop_pay_reason_code }}') {
                                // Todo: - Stop to Pay => Show Stop Pay Reason, Solution, Reason note.
                                isShowStopPayReason = true;
                                isShowSolution = true;
                            } else if (item.customer_response == '{{ $promise_pay_date_code }}') {
                                // Todo: - Promise to pay => Promise date, Promise Amount, Solution, Reason note.
                                isShowPromiseDate = true;
                                isShowPromiseAmount = true;
                                isShowSolution = true;
                            } else if (item.customer_response == '{{ $request_restructure_code }}') {
                                // Todo: - Request Restructure​ => Request Type, # of Month Request, Amount Request, Reason note.
                                isShowRequestType = true;
                                isShowMonthRequest = true;
                                isShowAmountRequest = true;
                            } else if (item.customer_response == '{{ $other_code }}') {
                                // Todo: - Other => Comment, Capacity, Progress, Solution, Reason Note
                                isShowComment = true;
                                isShowCapacity = true;
                                isShowConstructionProgress = true;
                                isShowSolution = true;
                            } else {
                                // Todo: - Can't contact, No answer, customer cancel, No service, Number not in use, Wrong Number
                                //              =>  Solution, Reason note.
                                isShowSolution = true;
                            }
                            isShowReasonNote = true;
                        } else if (isWalkInAction) {
                            if (
                                [
                                    '{{$request_restructure_code}}', '{{$request_partial_payment}}', '{{$request_waive_penalty}}',
                                    '{{$request_partial_payment_waive_penalty}}', '{{$request_change_unit}}'
                                ].indexOf(item.customer_response) >= 0
                            ) {
                                // Todo: (Request Partial Payment, Request Waive Penalty, Request Partial Payment & Waive Penalty
                                //        Request Restructure, Request Change Unit): Show Promise Date, Promise Amount, Comment, Capacity to pay,
                                //        Construction Progress, Solution, Negotiation Progress, Reason note
                                isShowPromiseDate = true;
                                isShowPromiseAmount = true;
                                isShowComment = true;
                                isShowCapacity = true;
                                isShowConstructionProgress = true;
                                isShowSolution = true;
                                isShowNegotiationProgress = true;
                                isShowReasonNote = true;
                            } else if (item.customer_response == '{{$stop_pay_code}}') {
                                // Todo: Stop to Pay => Show Request Type, # of Month Request, Amount Request
                                isShowRequestType = true;
                                isShowMonthRequest = true;
                                isShowAmountRequest = true;
                            } else if (item.customer_response == '{{$request_suspend_payment}}') {
                                // Todo: Request Suspend Payment => Show Construction Progress, # of Month Request, Reason note.
                                isShowConstructionProgress = true;
                                isShowMonthRequest = true;
                                isShowReasonNote = true;
                            } else if (item.customer_response == '{{$customer_cancel}}' || item.customer_response == '{{$other_code}}') {
                                // Todo: Customer Cancel, Other => Show only Reason note
                                isShowReasonNote = true;
                            }
                        } else if (isVisitAction) {
                            if (item.customer_response == '{{$the_door_locked}}' || item.customer_response == '{{$not_meet_customer}}') {
                                // Todo: The Door is Locked, Not meet customer: Show Solution, Reason note.
                                isShowSolution = true;
                                isShowReasonNote = true;
                            } else if (item.customer_response == '{{ $promise_pay_date_code }}') {
                                // Todo: Promise to Pay: Promise Date, Promise Amount, Capacity to Pay, Reason note.
                                isShowPromiseDate = true;
                                isShowPromiseAmount = true;
                                isShowCapacity = true;
                                isShowReasonNote = true;
                            } else if (item.customer_response == '{{ $stop_pay_reason_code }}') {
                                // Todo: Stop to Pay : Capacity to Pay, Comment, Solution, Reason note
                                isShowCapacity = true;
                                isShowSolution = true;
                                isShowReasonNote = true;
                            } else if (item.customer_response == '{{$customer_cancel}}' || item.customer_response == '{{$other_code}}') {
                                // Todo: Customer Cancel, Other : Solution, Reason note.
                                isShowSolution = true;
                                isShowReasonNote = true;
                            }
                        }
                        row += '<div class="col-lg-4">';
                        row += '    <table class="table-condensed"><tbody>';
                        // row += '    <tr><th>Phone Number :</th><td>'+ item.phone_number +'</td></tr>';
                        row += '    <tr><th>New Phone Number :</th><td>'+ (item.new_phone_number ? item.new_phone_number : '') +'</td></tr>';
                        row += '    <tr><th>Customer Response :</th><td>'+ (item.customer_response_ref ? item.customer_response_ref.description_en : item.customer_response) +'</td></tr>';
                        if (isShowSolution) {
                            row += '    <tr><th>Solution :</th><td>'+ (item.solution_ref ? item.solution_ref.description_en : item.solution) +'</td></tr>';
                        }
                        if (isShowReasonNote) {
                            row += '    <tr><th>Reason Note :</th><td>'+ item.reason_note +'</td></tr>';
                        }
                        row += '    </tbody></table>';
                        row += '</div>';

                        row += '<div class="col-lg-4">';
                        row += '    <table class="table-condensed"><tbody>';
                        if (isShowStopPayReason) {
                            row += '    <tr><th>Stop Pay Reason :</th><td>'+ (item.stop_pay_reason_ref ? item.stop_pay_reason_ref.description_en : '') +'</td></tr>';
                        }
                        if (isShowPromiseDate) {
                            row += '    <tr><th>Promise date :</th><td>'+ (item.promise_pay_date_str ? item.promise_pay_date_str : (item.promise_pay_date || '')) +'</td></tr>';
                        }
                        if (isShowPromiseAmount) {
                            row += '    <tr><th>Promise Amount :</th><td>'+ item.promise_amount +'</td></tr>';
                        }
                        if (isShowRequestType) {
                            row += '    <tr><th>Request Type :</th><td>'+ (item.request_type_ref ? item.request_type_ref.description_en : '') +'</td></tr>';
                        }
                        if (isShowMonthRequest) {
                            row += '    <tr><th># of Month Request :</th><td>'+ item.month_request +'</td></tr>';
                        }
                        if (isShowAmountRequest) {
                            row += '    <tr><th>Amount Request :</th><td>'+ item.amount_request +'</td></tr>';
                        }
                        if (isShowComment) {
                            row += '    <tr><th>Comment :</th><td>'+ (item.comment_ref ? item.comment_ref.description_en : '') +'</td></tr>';
                        }
                        if (isShowCapacity) {
                            row += '    <tr><th>Capacity :</th><td>'+ (item.capacity_ref ? item.capacity_ref.description_en : '') +'</td></tr>';
                        }
                        if (isShowConstructionProgress) {
                            row += '    <tr><th>Construction Progress :</th><td>'+ (item.construction_progress_note ? item.construction_progress_note : '') +'</td></tr>';
                        }
                        if (isShowNegotiationProgress) {
                            row += '    <tr><th>Negotiation Progress :</th><td>'+ (item.negotiation_ref ? item.negotiation_ref.description_en : '') +'</td></tr>';
                        }
                        row += '    </tbody></table>';
                        row += '</div>';
                        row += '</div>';
                        rowsStr += row;
                    }
                }
                modal_action_list_scroll.html(rowsStr);
            }
        });
    }

    parseExcelToJson = function(file, callBack) {
        var reader = new FileReader();

        reader.onload = function(e) {
            var data = e.target.result;
            var workbook = XLSX.read(data, {
                type: 'binary'
            });

            workbook.SheetNames.forEach(function(sheetName) {
                // Here is your object
                var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                // var json_object = JSON.stringify(XL_row_object);
                // console.log(json_object, XL_row_object);
                if (callBack) {
                    callBack(XL_row_object);
                }
            })

        };

        reader.onerror = function(ex) {
            console.log(ex);
        };
        reader.readAsBinaryString(file);
    };

    $(document).ready(function () {
//pagination

        $('.nav-tabs li a[data-toggle="tab"]').on('click', function () {
            $('#assign_option').val($(this).data('value'));
        });
        var assignFormCanSubmit = false;
        $('#assign_form').submit(function(event) {
            if (assignFormCanSubmit) {
                return true;
            }
            // const excelFile = $('#assign_excel_file').val();
            const input = document.getElementById('assign_excel_file');
            if (input && input.files[0]) {
                parseExcelToJson(input.files[0], function(data) {
                    for (const index in data) {
                        const row = data[index];
                        if (row) {
                            if (row.loan_id) {
                                $("<input />").attr("type", "hidden")
                                    .attr("name", "assign_list["+ index +"][loan_id]")
                                    .attr("value", row.loan_id)
                                    .appendTo("#assign_form");
                            }
                            if (row.user_id) {
                                $("<input />").attr("type", "hidden")
                                    .attr("name", "assign_list["+ index +"][user_id]")
                                    .attr("value", row.user_id)
                                    .appendTo("#assign_form");
                            } else if (row.user_name) {
                                $("<input />").attr("type", "hidden")
                                    .attr("name", "assign_list["+ index +"][user_name]")
                                    .attr("value", row.user_name)
                                    .appendTo("#assign_form");
                            }
                        }
                    }
                    $('#assign_form').validate();
                    if (!$('#assign_form').valid()) {
                        alert('Please input required field!');
                        assignFormCanSubmit = false;
                        return false;
                    }
                    if (confirm('Are you sure you want to submit assign?')) {
                        assignFormCanSubmit = true;
                        $('#assign_form').submit();
                    }
                });
                return false;
            } else {
                // var form = $(this).serialize();
                $('#assign_form').validate();
                if (!$(this).valid()) {
                    alert('Please input required field!');
                    assignFormCanSubmit = false;
                    return false;
                }
                return confirm('Are you sure you want to submit assign?');
            }
        });
        $('#btn-summit-assign').on('click', function () {
            $('#assign_form').submit();
        });

        $('.custom-pagi a').on('click', function () {
            var offset = $(this).parent().find('input[name="set_offset"]').val();
            window.history.pushState({}, null, updateQueryStringParameter(window.location.href, 'offset', offset));
            $('#search_form').submit();
            return false;
        });
        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else {
                return uri + separator + key + "=" + value;
            }
        }
        function getFileName() {
            var fileName = '{{ !$is_summary_page ? 'action_plain' : 'loan_summary' }}';
            return fileName + '-' + new Date().toJSON().slice(0,10);
        }
        $("#export").click(function (event) {
            var con = confirm("Do you really want to export to CSV file?");
            if(con == true){
                new TableExport(document.getElementById('loan_status_summary'), {
                    formats: ['csv'],
                    filename: getFileName()
                });
                $('button.csv').hide().click();
                $('.tableexport-caption').remove();
            }
        });

        $("#xexport").click(function (event) {
            var con = confirm("Do you really want to export to Excel file?");
            if(con == true){
                var form = $('#search_form');
                var me = $(this);
                me.html('<i class="fa  fa-sign-out"></i> Loading...');
                me.addClass('disabled');
                $.ajax({
                    url: "{{ route('loan_recovery_action_plan_json') }}?offset=90000",
                    method: 'post',
                    data: form.serialize(),
                    success: function (json) {
                        var fileType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8'; // 'application/octet-stream'
                        var wb = XLSX.utils.book_new();
                        var ws = XLSX.utils.json_to_sheet(json);
                        XLSX.utils.sheet_add_aoa(ws, [[
                            'Loan ID', 'Contract ID', 'Main Project', 'Variance Code', 'Customer Name', 'Phone Number', 'Disbursement Date', 'Loan Amount', 'Outstanding Balance', 'DD Account', 'DD Balance', 'Pmt. No.', 'Schedule Date', 'Schedule Amount', 'Arrear Date', 'Overdue Day', 'Overdue Amount', 'Penalty Amount', 'Amount to be Collect', 'Payment Status', 'Last Calling Date', 'Negotiation Progress', 'Result', 'Customer Respond', 'Assignee'
                        ]]);
                        XLSX.utils.book_append_sheet(wb, ws, getFileName());
                        var wbout = XLSX.write(wb, {bookType:'xlsx', type:'array'});
                        saveAs(new Blob([wbout],{type:fileType}), getFileName() + '.xlsx');
                        me.removeClass('disabled');
                        me.html('<i class="fa  fa-sign-out"></i> to Excel');
                    }
                });
                return;
                // new TableExport(document.getElementById('loan_status_summary'), {
                //     formats: ['xlsx'],
                //     filename: getFileName(),
                //     sheetname: false
                // }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                // $('button.xlsx').hide().click();
                // $('.tableexport-caption').remove();
            }
        });

        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight:'TRUE',
            // startDate: '-0d',
        });

    });
</script>
@endsection
