@extends('layouts.app')

@section('css')
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
    <?php
        $currency = config('static_data.currency_symbol');
        $status = config('static_data.client_loan_account_status');
        $drawdown_status = config('static_data.drawdown_status');
        $lc = $loan_writeoff->client_loan_account;
    ?>

@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                <span>{{ trans('multi.writeoff_detail') }}</span>
            </header>
            <div class="panel-body">

                <table class="table table-bordered table-striped table-condensed">
                    <tr>
                        <th style="width: 25%;">{{ trans('multiple.account_name') }}</th>
                        <td><?php echo $lc->account_name; ?></td>
                    </tr>
                    <tr>
                        <th>{{ trans('product.p_loan_reference') }}</th>
                        <td>{{ $loan_writeoff->contract_id }}</td>
                    </tr>					
                    <tr>
                        <th>{{ trans('multiple.branch') }}</th>
                        <td>{{ $branch->where('branch_code', $lc->branch)->first()->branch_name }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('account.currency') }}</th>
                        <td>{{ $currency_list->where('id', $lc->currency)->first()->code }}</td>
                    </tr>
					 <tr>
                        <th>{{ trans('loan.l_write_off_date') }}</th>
                        <td>{{Date("Y-m-d", strtotime($loan_writeoff->writeoff->write_off_date))}}</td>
                    </tr>
					 <tr>
                        <th>{{ trans('report.rpt_wo_amount') }}</th>
                        <td>{{$currency[$lc->currency].number_format($loan_writeoff->writeoff->amount, 2)}}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('report.remaining_wo_balance') }}</th>
                        <td>{{$currency[$lc->currency].number_format($loan_writeoff->writeoff->amount - $wo_prev_paid, 2)}}</td>
                    </tr>
                </table>
                <h5><strong>{{ trans('multiple.transaction') }}</strong></h5>
                <table  class="table table-bordered table-striped table-condensed table-hover clientTable">
                    <thead>
                    <th style="text-align: center;">{{ trans('multiple.date') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_debit') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_credit') }}</th>
                    <th style="text-align: center;">{{ trans('multiple.balance') }}</th>
                    <th style="text-align: center;">{{ trans('multiple.m_description') }}</th>
                    </thead>
                    <tbody>
                        <td colspan="1" style="text-align:center; font-weight:bold; font-style:italic; background-color: sandybrown;">{{Date("Y-m-d", strtotime($loan_writeoff->writeoff->write_off_date))}}</td>
                        <td colspan="2" style="text-align:right; font-weight:bold; font-style:italic; background-color: sandybrown;">Beginning Balance</td>
                        <td colspan="1" style="text-align:right; font-weight:bold; font-style:italic;background-color: sandybrown;">{{number_format($loan_writeoff->writeoff->amount,2)}}</td>
                        <td colspan="1" style="background-color: sandybrown;"></td>
                    </tbody>
                    <tbody>
                        <?php 
                            $balance = $loan_writeoff->writeoff->amount;
                            $t_debit = $t_credit = 0;
                        ?>
                        @foreach($jd_wo_all_paid as $jd)
                            <?php
                                $balance += $jd->debit - $jd->credit;
                                $t_credit += $jd->credit;
                                $t_debit += $jd->debit;
                            ?>
                            <tr>
                            <td style="text-align:center;">{{Date("Y-m-d", strtotime($jd->journal->entry_date))}}</td>
                            <td style="text-align:right;">{{number_format($jd->debit, 2)}}</td>
                            <td style="text-align:right;">{{number_format($jd->credit, 2)}}</td>
                            <td style="text-align:right;">{{number_format($balance, 2)}}</td>
                            <td style="text-align:right;">{{$jd->description}}</td>
                        </tr>

                        @empty
                        <tr><td colspan=9>{{ trans('multiple.m_no_result') }}</td></tr>
                        @endforelse
                    </tbody>
                      <td colspan="1" style="text-align: right;font-weight: bold;">{{ trans('loan.total') }}</td>
                      <td style="text-align: right;font-weight: bold;">{{number_format($t_debit, 2)}}</td>
                      <td style="text-align: right;font-weight: bold;">{{number_format($t_credit, 2)}}</td>
                      <td colspan="2"></td>
                </table>

            </div>
        </section>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js', isset($secure) ? false : false) }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
    //pagination
        $('.custom-pagi a').on('click', function () {
            val = $(this).parent().find('input[name="set_offset"]').val();
            $('input[name="offset"]').val(val);
            $('#search_frm').submit();
            return false;
        });
    });
</script>
@endsection
