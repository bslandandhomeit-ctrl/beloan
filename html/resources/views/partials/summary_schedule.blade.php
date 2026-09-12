<table class="table table-striped table-hover table-bordered summary sticky-header" id="editable-sample">
        <thead>
            <tr>
                <th>{{ trans('multiple.m_no') }}</th>
                <th style="vertical-align:middle; text-align: center;">{{ trans('report.rpt_contract_id') }}</th>
                <th style="vertical-align:middle; text-align: center;">{{ trans('customer.cus_customer_name') }}</th>
                <th style="vertical-align: middle; text-align: center;">{{ trans('report.rpt_contract_date') }}</th>
                <th style="vertical-align: middle; text-align: center;">{{ trans('multiple.m_phone',['num'=>'']) }}</th>
                <th style="vertical-align:middle; text-align: center;">{{ trans('report.rpt_co_name') }}</th><!--THEARY-->
                <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.m_address') }}</th>
				<th style="vertical-align:middle; text-align: center;">{{ trans('report.rpt_total_pass_due') }}</th>
                <th style="vertical-align:middle; text-align: center;">{{ trans('report.rpt_paid_principal') }}</th>
                <th style="vertical-align:middle; text-align: center;">{{ trans('report.rpt_paid_interest') }}</th>
                <th style="vertical-align:middle; text-align: center;">{{ trans('report.rpt_principal') }}</th>
                <th style="vertical-align:middle; text-align: center;">{{ trans('report.rpt_interest') }}</th>
                <th style="vertical-align:middle; text-align: center;">{{ trans('report.rpt_total') }}</th>
            </tr>
        </thead>
    <tbody>
        <?php
        $n = 0;
        $total_principal = 0.0;
        $total_interest = 0.0;
        $total_paid_interest = 0.0;
        $total_paid_principal = 0.0;
        $total = 0.0;
		$total_pass_due = 0;
        ?>
        @forelse($loans as $l)
        <?php
        $principal = 0.0;
        $interest = 0.0;
        $arr_principal = 0.0;
        $arr_interest = 0.0;
        $arr_penalty = 0.0;
        $total_amount = 0.0;
		$pass_due = 0;
        $current_month = $date_search;
        $repayment_array = LoanCalculate::loan_schedule($l->schedule, $l->start_date)[0];

		$result_total_penalty = LoanCalculate::getTotalPenalty($l);
		$result = $result_total_penalty[0];
		for($i = 0; $i < $l->loan_duration; $i++){
			if(empty($result[$i])){
				continue;
			}
			$pass_due += $result[$i][9];
		}
		$total_pass_due += $pass_due;

        if (!empty($repayment_array) && count($repayment_array) > 0) {
            $month_idx = 1;
            foreach ($repayment_array as $key => $v) {
                if ($key === 0) continue;
                $each_date = date('Y-m', strtotime($v[0]));
                $paid_interest = 0.0;
                $paid_principal = 0.0;
                if ($current_month == $each_date) {
                    $group_pays = [];
                    $group_pays = $l->payment->whereLoose('payment_month', $month_idx);
                    if (count($group_pays) == 0) {
                        $principal = floatval($v[3]);
                        $interest = floatval($v[2]);
                    } else {
                        $total_penalty = 0.0;
                        foreach ($group_pays as $k => $p) {
                            $paid_interest += floatval($p->paid_interest);
                            $paid_principal += floatval($p->paid_principal);
                            if ($p->status == 0) {
                                $arr_interest = $v[2] - $paid_interest;
                                $arr_principal = $v[3] - $paid_principal;
                                $arr_penalty = floatval($p->repayment_owed) - ($arr_interest + $arr_principal);
                                (round($arr_interest * 100) == 0) ? $interest = 0 : $interest = $arr_interest;
                                (round($arr_principal * 100) == 0) ? $principal = 0 : $principal = $arr_principal;
                            } else {
                                continue 3;
                            }
                        }
                    }
                    $total_paid_interest += $paid_interest;
                    $total_paid_principal += $paid_principal;
                    $total_amount += $principal + $interest;
                    $total_principal += $principal;
                    $total_interest += $interest;
                    $total += $total_amount;
                    break;
                } else {
                    $month_idx++;
                }
            }
        }
        if (round(floatval($principal)) == 0 && round(floatval($interest)) == 0) {
            continue;
        }
        $n++;
        ?>
        <tr>
            <td style="text-align: center;">{{$n}}</td>
            <td style="text-align: center;"><a href="{{ route('loan_detail', [$l->id])}}">{{$l->contract_id ? $l->contract_id: '-'}}</a></td>
            <td>{{  $l->client_name }}</td>
            <td style="text-align: center;">{{ date("d-M-Y", strtotime($l->start_date))}}</td>
            <td>{{$l->phone1}}{{ !empty($l->phone2) ? '/'.$l->phone2  : ''}}</td>
            <td style="text-align: center;">{{!empty($l->co_user) && count($l->co_user) > 0 ? ($l->co_user->name) : '-'}}</td>

            <td style="text-align: center;">{{$l->city ? $l->city : '-'}}</td>
			<td style="text-align: right;">{{number_format($pass_due,2,'.',',')}}</td>
            <td style="text-align: right;">{{$paid_principal? number_format($paid_principal,2,'.',','): '-'}}</td>
            <td style="text-align: right;">{{$paid_interest? number_format($paid_interest,2,'.',',') : '-'}}</td>
            <td style="text-align: right;">{{number_format($principal,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($interest,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($total_amount,2,'.',',')}}</td>
        </tr>
        @empty
        <tr><td colspan="9">{{ trans('multiple.m_no_result') }}</td></tr>
        @endforelse
        <tr style="font-weight: bold;">
            <td></td>
            <td colspan="6" style="text-align: right;">{{ trans('report.rpt_total') }}</td>
			<td style="text-align: right;">{{number_format($total_pass_due,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($total_paid_principal,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($total_paid_interest,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($total_principal,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($total_interest,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($total,2,'.',',')}}</td>
        </tr>
    </tbody>
</table>
<div class="page">
    <?PHP
    echo $loans->appends([
        'contract_id' => Input::get('contract_id'),
        'phone' => Input::get('phone'),
        'client_name' => Input::get('client_name'),
        'city' => Input::get('city'),
        'city' => Input::get('city'),
        'date' => Input::get('date'),
        'offset' => Input::get('offset')
    ])->render();
    ?>
</div>
