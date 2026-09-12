@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false)}}"/>
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading">
        <span>{{ trans('sidebar.sb_loan_co_detail') }}</span>
    </header>
    <div class="panel-body">
        <div class="position-center" style="width:90%;">
            <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('getLoanCo') }}" id="search_frm">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="contract_id" class="col-lg-3 control-label">{{ trans('report.rpt_contract_id') }}</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="contract_id" name="contract_id" value="{{ $contract_id }}" />
                            </div>
                        </div>

                        @if($role_id!=10)
                        <div class="form-group">
                            <label for="CO Name" class="col-lg-3 control-label">{{ trans('multiple.co') }}</label>
                            <div class="col-lg-7">
                                <select class="form-control" id="co" name="co">
                                    <option value="">-</option>
                                    @foreach($users as $u)
                                    <option value="{{ $u->id }}" @if($_GET['co']==$u->id) selected @endif>{{ $u->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                    <?php
                    $loan_type = config('static_data.loan_type');
                    $loan_status = config('static_data.loan_status');
                    ?>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="inputCardnumber" class="col-lg-3 control-label">{{ trans('multiple.m_status') }}</label>
                            <div class="col-lg-7">
                                <select name="status" id="status" class="form-control">
                                    <option value="">-</option>
                                    @foreach($loan_status as $key => $value)
                                    @if(array_key_exists($status,$loan_status))
                                    <option value="{{ $key }}"
                                            @if(isset($status))
                                            @if($status == $key)
                                            selected
                                            @endif
                                            @endif>{{ $value }}</option>
                                    @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12">
                                <input type="hidden" name="offset" />
                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                                <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                                <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>

                            </div>
                        </div>
                        <div class="page">
                            <div class="custom-pagi">
                                <span class="pagi_label">Number of Rows:</span>
                                <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                                <input type="hidden" class="form-control" name="status" value="<?php echo $_GET['status'] ?>" />
                                <a href="#" class="btn btn-danger">Go</a>
                            </div> 
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <br/><br/>
        <section id="unseen" class="ox-scroll">
            <table  class="table table-bordered table-striped table-condensed table-hover cf1" id="tb">
                <thead>
                    <tr class="header">
                        <th style="text-align: center; vertical-align: middle;" rowspan="2">{{ trans('multiple.m_no') }}</th>
                        <th style="text-align: center; vertical-align: middle;" rowspan="2">{{ trans('multiple.co') }}</th>
                        <th style="text-align: center; vertical-align: middle;" rowspan="2">{{ trans('customer.cus_customer_name') }}</th>
                        <th style="text-align: center; vertical-align: middle;" rowspan="2">{{ trans('report.rpt_contract_id') }}</th>
                        <th style="text-align: center; vertical-align: middle;" rowspan="2">{{ trans('report.rpt_loan_account_number') }}</th>
                        <th style="text-align: center; vertical-align: middle;" rowspan="2">{{ trans('report.rpt_loan_type') }}</th>
                        <th style="text-align: center; vertical-align: middle;" rowspan="2">{{ trans('report.rpt_contract_date') }}</th>
                        <th style="text-align: center; vertical-align: middle;" rowspan="2">{{ trans('report.rpt_loan_amount') }}</th>
                        <th style="text-align: center; vertical-align: middle;" rowspan="2">{{ trans('report.rpt_int_rate') }}</th>
                        <th style="text-align: center; vertical-align: middle;" rowspan="2">{{ trans('multiple.m_status') }}</th>
                        <th style="text-align: center; vertical-align: middle;" rowspan="2">{{ trans('multiple.m_phone',['num'=>'']) }}</th>
                        <th style="text-align: center; vertical-align: middle;" rowspan="2">{{ trans('report.rpt_overdue') }}</th>
                        <th style="text-align: center; vertical-align: middle;" colspan="4">{{ trans('report.rpt_pass_due') }}</th>
                    </tr>

                    <tr>
                        <th style="vertical-align: middle;">{{ trans('report.rpt_interest') }}</th>
                        <th style="vertical-align: middle;">{{ trans('report.rpt_principal') }}</th>
                        <th style="vertical-align: middle;">{{ trans('report.rpt_penalty') }}</th>
                        <th style="vertical-align: middle;">{{ trans('report.rpt_total') }}</th>
                    </tr>
                </thead>
                <tbody style="vertical-align: middle">
                    <?php 
                        $j = 0;
                    ?>
                    @forelse($loans as $l)
                    <?php
                        $to_interest = 0;
                        $to_principal = 0;
                        $to_penalty = 0;
                        $total_payment = 0;
                         
                        $result_total_penalty = LoanCalculate::getTotalPenalty($l);
                        $result = $result_total_penalty[0];
                        $overdue = $result_total_penalty[2];
                        $repayment_array_global = $result_total_penalty[1];

                        if (count($repayment_array_global) <= 0)
                            continue;
                        for ($i = 0; $i < $l->loan_duration; $i++) {
                            if (empty($result[$i])) {
                                continue;
                            }
                            $to_principal += $result[$i][1];
                            $to_interest += $result[$i][2];
                            $to_penalty += $result[$i][8];
                            $total_payment += $result[$i][9];
                        }
                    ?>
                    <tr>
                        <?php $j++;?>
                        <td style="text-align: center">{{$j}}</td>
                        <td style="vertical-align: middle">{{$l->co_user->name}}</td>
                        <td style="vertical-align: middle">{{$l->client_name}}</td>
                        <td style="vertical-align: middle;text-align: center">
                            <a style="text-decoration: underline" href="{{ route('loan_detail', [$l->id])}}">{{ $l->contract_id ? $l->contract_id : '-'  }}</a>
                        </td>
                        <td style="vertical-align: middle;text-align: center">
                            <a style="text-decoration: underline" href="{{ route('loan_account', [$l->loan_account_id])}}">{{(!empty($l->client_loan_account->account_no))? $l->client_loan_account->account_no : ""}}</a>
                        </td>
                        <td style="text-align: justify">{{$loan_type[$l->loan_type]}}</td>
                        <td style="text-align: center">{{$l->start_date ? date("d-M-Y", strtotime($l->start_date)) : '-'}}</td>
                        <td style="text-align: right">{{$l->loan_amount ? number_format($l->loan_amount,2,'.',',') : '-'}}</td>
                        <td style="text-align: center">{{$l->interest_rate ? number_format($l->interest_rate, 1) : ''}}%</td>

                        <td align="justify">{{$loan_status[$l->status]}}</td>
                        <td style="text-align: center">{{$l->phone1}}</td>
                        <td style="text-align: center">{{$l->status==3 || $l->status==8?$overdue:'-'}}</td>

                        <td align="right">{{ $l->status==3 || $l->status==8?number_format($to_interest, 2, '.', ','):'-' }}</td>
                        <td align="right">{{ $l->status==3 || $l->status==8?number_format($to_principal, 2, '.', ','):'-' }}</td>
                        <td align="right">{{ $l->status==3 || $l->status==8?number_format($to_penalty, 2, '.', ','):'-' }}</td>
                        <td align="right">{{ $l->status==3 || $l->status==8?number_format($total_payment, 2, '.', ','):'-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="11">{{ trans('multiple.m_no_result') }}</td></tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr style="font-weight: bold;">
                        <td colspan="12" class="text-right">{{ trans('report.rpt_total') }}</td>
                        <td class="text-right">{{number_format($t_principal, 2, '.', ',')}}</td>
                        <td class="text-right">{{number_format($t_interest, 2, '.', ',')}}</td>
                        <td class="text-right">{{number_format($t_penalty, 2, '.', ',')}}</td>
                        <td class="text-right">{{number_format($t_payment, 2, '.', ',')}}</td>
                    </tr>
                </tfoot>
            </table>
            <div class="page pull-right">
                <?PHP
                echo $loans->appends([
                    'contract_id' => Input::get('contract_id'),
                    'status' => Input::get('status'),
                    'offset' => Input::get('offset')
                ])->render();
                ?>
            </div>
        </section>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
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
            new TableExport(document.getElementById('tb'), {
                    formats: ['csv'],
                    filename: 'loan_co'
                });
                $('button.csv').hide().click();
                $('.tableexport-caption').remove();
        }
    });

    $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('tb'), {
                    formats: ['xlsx'],
                    filename: 'loan_co'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });

});
</script>
@endsection