@extends('layouts.app')

@section('css')
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
</style>
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>loan action summary</span>
        <div style="float:right;margin-top: -6px;">
            <!--            <span><a href="{{route ('loan_recovery_action_plan_form') }}" class="btn btn-success"><i class="fa fa-plus"></i>{{ trans('sidebar.sb_add_user') }}</a></span>-->
            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
            <button class="btn btn-primary" id="export"><i class="fa fa-sign-out"></i> {{ trans('multiple.export') }}</button>
            <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
        </div>
    </header>
    <div class="panel-body">
        <div class="page">
            <div class="custom-pagi custom">
                <span class="pagi_label">Number of Rows:</span>
                <input type="text" class="form-control" name="set_offset" value="{{ $offset }}" />
                <a href="#" class="btn btn-danger">Go</a>
            </div>
        </div>
        <br/><br/><br/><br/>
        <section id="unseen" class="ox-scroll">
            <div id="printArea">
                @include('api.report_header')
                <table  class="table table-striped table-bordered" style="width:100%" id="loan_status_summary">
                    <thead>
                    <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_no') }}</th>
                    <th style="text-align: center; vertical-align: middle;">{{ trans('customer.cus_customer_name') }}</th>
                    <th style="text-align: center; vertical-align: middle;">{{ trans('product.p_product_id') }}</th>
                    <th style="text-align: center; vertical-align: middle;">Phone Number</th>
                    <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_contract_id') }}</th>
                    <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_loan_amount') }}</th>
                    <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_disbursement_date') }}</th>
                    <th style="text-align: center; vertical-align: middle;">Outstanding Balance</th>
                    <th style="text-align: center; vertical-align: middle;">Overdue Day</th>
                    <th style="text-align: center; vertical-align: middle;">Result</th>
                    <th style="text-align: center; vertical-align: middle;">Customer Respond</th>
                    <th style="text-align: center; vertical-align: middle;">Actions</th>
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
                        <td style="text-align: center">{{$i}}</td>
                        <td style="vertical-align: middle">{{$l->client_name}}</td>
                        <td style="vertical-align: middle;text-align: center">
                            {{ !empty($l->units->code) ? $l->units->code: '-' }} ({{ !empty($l->units->UnitType->name) ?$l->units->UnitType->name: '-' }})
                        </td>
                        <td>{{ $l->phone1 }}</td>
                        <td style="vertical-align: middle;text-align: center">
                            {{ $l->contract_id }}
                        </td>
                        <td style="text-align: right">{{$l->original_amount ? number_format($l->original_amount,2,'.',',') : number_format($l->loan_amount,2,'.',',')}}</td>
                        <td style="text-align: center">{{$l->schedule->last()->schedule_date ? date("d-M-Y", strtotime($l->schedule->last()->schedule_date)) : '-'}}</td>
                        <th style="text-align: right; vertical-align: middle;">{{ number_format($l->getOutstandingBalance(),2,'.',',') }}</th>
                        <th style="text-align: justify; vertical-align: middle;">Overdue Day</th>
                        <th style="text-align: justify; vertical-align: middle;">{{ $l->getLastAction() ? $l->getLastAction()->customerResponseRef->description_en : '' }}</th>
                        <th style="text-align: justify; vertical-align: middle;">{{ $l->getLastAction() ?
                            ($l->getLastAction()->stopPayReasonRef ? $l->getLastAction()->stopPayReasonRef->description_en : $l->getLastAction()->reason_note)
                            : '' }}</th>
                        <td class="text-center">
                            <a href="#modal_action_list" onclick="refreshActionListModal({{$l->id}})" data-toggle="modal"
                               class="btn btn-default btn-xs" title="View Actions"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9">{{ trans('multiple.m_no_result') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>


            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="modal_action_list" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">Áction List</h4>
                        </div>
                        <div class="modal-body">
                            <div class="modal-overflow-scroll">
                                <table  class="table table-striped table-bordered" style="width:100%" id="loan_status_summary">
                                    <thead>
                                    <th style="text-align: center; vertical-align: middle;">ID</th>
                                    <th style="text-align: center; vertical-align: middle;">Action Date</th>
                                    <th style="text-align: center; vertical-align: middle;">Action</th>
                                    <th style="text-align: center; vertical-align: middle;">Whom</th>
                                    <th style="text-align: center; vertical-align: middle;">Phone Number</th>
                                    <th style="text-align: center; vertical-align: middle;">Customer Response</th>
                                    <th style="text-align: center; vertical-align: middle;">Promise Pay Date</th>
                                    <th style="text-align: center; vertical-align: middle;">Stop Pay Reason</th>
                                    <th style="text-align: center; vertical-align: middle;">Reason Note</th>
                                    <th style="text-align: center; vertical-align: middle;">Remark</th>
                                    <th style="text-align: center; vertical-align: middle;">Created By</th>
                                    </thead>
                                    <tbody style="vertical-align: middle">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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

<script type="text/javascript">
    function refreshActionListModal(loan_id) {
        var table_body = $('#modal_action_list tbody');
        var modalTitle = $('#modal_action_list h4.modal-title');
        modalTitle.html('Action List of Loan #' + loan_id);
        table_body.html('<tr><td class="text-center" colspan="11">Loading...');
        $.ajax({
            url: "{{ route('loan_recovery_action_list_loan_id', ['id' => '']) }}/" + loan_id,
            method: 'get',
            success: function (res_jd) {
                if (!res_jd || res_jd.length <= 0) {
                    table_body.html('<tr><td class="text-center" colspan="11">Not found!');
                    return;
                }
                var rowsStr = '';
                for(var i = 0; i < res_jd.length; i++) {
                    var item = res_jd[i];
                    if (item) {
                        var row = '<tr>';
                        row += '<td>' + item.id;
                        row += '<td>' + (item.action_date_str ? item.action_date_str : item.action_date);
                        row += '<td>' + (item.action_ref ? item.action_ref.description_en : item.action);
                        row += '<td>' + (item.whom_ref ? item.whom_ref.description_en : item.whom);
                        row += '<td>' + item.phone_number;
                        row += '<td>' + (item.customer_response_ref ? item.customer_response_ref.description_en : item.customer_response);
                        row += '<td>' + (item.promise_pay_date_str ? item.promise_pay_date_str : (item.promise_pay_date || ''));
                        row += '<td>' + (item.stop_pay_reason_ref ? item.stop_pay_reason_ref.description_en : '');
                        row += '<td>' + item.reason_note;
                        row += '<td>' + item.remark;
                        row += '<td>' + item.created_by;
                        row += '</tr>';
                        rowsStr += row;
                    }
                }
                table_body.html(rowsStr);
            }
        });
    }

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
