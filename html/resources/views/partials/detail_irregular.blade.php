<table class="table table-striped table-hover table-bordered irregular" id="editable">
    <thead class="th-center">
        <tr class="header">
            <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.m_no') }}</th>
            <th rowspan=2 style="vertical-align:middle;">{{ trans('customer.cus_customer_name') }}</th>
            <th rowspan="2" style="vertical-align: middle;">{{ trans('multiple.m_phone',['num'=>'']) }}</th>
            <th colspan=4 style="vertical-align:middle;">{{ trans('report.rpt_loan_contract') }}</th>
            <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_principal_balance') }}</th>
            <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_contract_date') }}</th>
            <th rowspan=2 style="vertical-align:middle;">{{ trans('loan.l_last_paid_date') }}</th>
            <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_maturity_date') }}</th>
            <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_overdue') }}</th>
            <th colspan=4 style="vertical-align:middle;">{{ trans('report.rpt_pass_due') }}</th>
            <th rowspan=2 style="vertical-align: middle;">{{ trans('multiple.m_note') }}</th>
            <th rowspan=2 style="vertical-align: middle;">{{ trans('multiple.co') }}</th>
            <th rowspan=2 style="vertical-align: middle;">{{ trans('report.rpt_reason') }}</th>
            <th rowspan=2 style="vertical-align: middle;">{{ trans('report.rpt_action_taken') }}</th>
            <th rowspan=2 style="vertical-align: middle;">{{ trans('report.rpt_todo_payment') }}</th>
            <th rowspan=2 style="vertical-align: middle;" class="nb">{{ trans('multiple.m_action') }}</th>
        </tr>
        <tr class="head-1">
            <th style="vertical-align: middle;">{{ trans('report.rpt_contract_id') }}</th>
            <th style="vertical-align: middle;">{{ trans('report.rpt_loan_amount') }}</th>
            <th style="vertical-align: middle;">{{ trans('report.rpt_int_rate') }}</th>
            <th>{{ trans('report.rpt_tenure') }}</th>
            <th style="vertical-align: middle;">{{ trans('report.rpt_interest') }}</th>
            <th style="vertical-align: middle;">{{ trans('report.rpt_principal') }}</th>
            <th style="vertical-align: middle;">{{ trans('report.rpt_penalty') }}</th>
            <th style="vertical-align: middle;">{{ trans('report.rpt_total') }}</th>
        </tr>
	</thead>
	
    <tbody>
        <?php
        $n = 0;
        $sumLoan = 0.0;
        $sumBalance = 0.0;
        ?>
        @forelse($loans as $l)
        <tr>
            <?php
            $n++;
            $to_interest = 0;
            $to_principal = 0;
            $to_penalty = 0;
            $total_payment = 0;
            $last_payment = null;
            $reason = '';
            $todo_payment = '';
            $take_action = '';
            $id = 0;
            $payment = $l->payment;
            $today = date("Y-m-d", strtotime('today'));
            $loan_date = $l->start_date;
            $total_prin = 0;
            $prin_balance = 0;
            if (($month_index = date_dif($loan_date, $today, 2, false)) < 0) {
                continue;
            }
            $flg = 0;
            $result_total_penalty = LoanCalculate::getTotalPenalty($l);
            $result = $result_total_penalty[0];
            $overdue = $result_total_penalty[2];
            $repayment_array_global = $result_total_penalty[1];
            for ($i = 0; $i < $l->loan_duration; $i++) {
                if (empty($result[$i])) {
                    continue;
                }
                $to_principal += $result[$i][1];
                $to_interest += $result[$i][2];
                $to_penalty += $result[$i][8];
                $total_payment += $result[$i][9];
                if ($flg != 1) {
                    $start_index = $i;
                    $flg = 1;
                }
            }
            if (!empty($payment) && count($payment) > 0) {
                $last_payment = $payment->where('status', 0, false)->where('todo_payment', $today, false)->first();
                if ($last_payment == null) {
                    $last_payment = $payment->where('status', 1, false)->sortByDesc(function($pay, $key) {
                                return $pay->id;
                            })->where('todo_payment', $today, false)->first();
                    if ($last_payment == null) {
                        continue;
                    }
                }
                $last_payment_date = $last_payment->repayment_date;
                $note = $last_payment->note;
                $reason = $last_payment->reason;
                $todo_payment = $last_payment->todo_payment;
                $take_action = $last_payment->action_taken;
                $id = $last_payment->id;
                foreach ($payment as $p) {
                    $total_prin += $p->paid_principal;
                }
                //dd($result);
            }
            $maturity_date = $repayment_array_global[count($repayment_array_global) - 1][0];
            // Balance
            $prin_balance = $l->loan_amount - $total_prin;
            $sumLoan += $l->loan_amount;
            $sumBalance += $prin_balance;
            ?>
            <td>{{$n}}</td>
            <td>{{$l->client->client_name}}</td>
            <td>{{$l->client->phone1}}{{ !empty($l->client->phone2) ?'/'. $l->client->phone2 : '' }}</td>
            <td align="center"><a href="{{ asset('loans/'.$l->id)}}">{{$l->contract_id ? $l->contract_id : '-' }}</a></td>
            <td align="right">{{number_format($l->loan_amount,2,'.',',')}}</td>
            <td style="text-align: center;">{{number_format($l->interest_rate,2)}}%</td>
            <td style="text-align: center;">{{$l->loan_duration}}</td>
            <td align="right">{{ number_format($prin_balance,2,'.',',')}}</td>
            <td>{{ date("d-M-Y",strtotime($l->start_date)) }}</td>
            <td align="center">{{ !empty($last_payment_date) ? date("d-M-Y",strtotime($last_payment_date)) : '-'}}</td>
            <td align="center">{{ date("d-M-Y",strtotime($maturity_date)) }}</td>
            <td align="center">{{ $overdue }}</td>
            <td align="right">{{ number_format($to_interest, 2, '.', ',') }}</td>
            <td align="right">{{ number_format($to_principal, 2, '.', ',') }}</td>
            <td align="right">{{ number_format($to_penalty, 2, '.', ',') }}</td>
            <td align="right">{{ number_format($total_payment, 2, '.', ',') }}</td>
            <td>{{!empty ($note) ? $note : ''}}</td>
            <td align="right">{{$l->co_user->name}}</td>
            <td id="reason-{{$id}}">{{!empty($reason) ? $reason: ''}}</td>
            <td id="action_taken-{{$id}}">{{!empty($take_action) ? $take_action: ''}}</td>
            <?php
            if ($todo_payment == 0000 - 00 - 00) {
                $todo_payment = '';
            }
            ?>
            <td id="todo_payment-{{$id}}">{{!empty($todo_payment) ? date('Y-m-d',strtotime($todo_payment)) : ''}}</td>
            <td class="nb" align="center">@if($id > 0)<a href="javascript:;" class="btn btn-default btn-xs btn-action" data-id="{{$id}}" title="Edit"><i class="fa fa-pencil"></i></a>@endif</td>
        </tr>

        @empty
        <tr><td colspan=21>{{ trans('multiple.m_no_result') }}</td></tr>
        @endforelse
    </tbody>

	<tfoot>
		<tr style="font-weight: bold;">
            <td colspan="4" style="text-align: center;">{{ trans('report.rpt_total') }}</td>
            <td align="right">{{number_format($sumLoan,2,'.',',')}}</td>
            <td colspan=2></td>
            <td align="right">{{number_format($sumBalance,2,'.',',')}}</td>
            <td colspan="13"></td>
        </tr>
	</tfoot>
</table>
<div class="page">
    <?PHP
    echo $loans->appends([
        'contract_id' => Input::get('contract_id'),
        'phone' => Input::get('phone'),
        'name' => Input::get('name'),
        'selBrand' => Input::get('selBrand'),
        'offset' => Input::get('offset')
    ])->render();
    ?>
</div>