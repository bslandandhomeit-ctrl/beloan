<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
    <thead>
    <tr>
        <th>{{ trans('multiple.m_no') }}</th>
        <th>{{ trans('customer.cus_customer_name') }}</th>
        <th>{{ trans('report.rpt_contract_id') }}</th>
        <th>{{ trans('report.rpt_loan_account_number') }}</th>
        <th>{{ trans('loan.main_project_code') }}</th>
        <th>{{ trans('unit.unit_type') }}</th>
        <th>{{ trans('unit.unit') }}</th>
        <th>{{ trans('loan.l_contact_date') }}</th>
        <th>{{ trans('report.rpt_loan_type') }}</th>
        <th>{{ trans('account.currency') }}</th>
        <th>{{ trans('report.rpt_disbursement_date') }}</th>
        <th>{{ trans('report.rpt_maturity_date') }}</th>
        <th>{{ trans('loan.l_tenure') }}</th>
        <th>{{ trans('Unit Price') }}</th>
        <th>{{ trans('Discount Amount') }}</th>
        <th>{{ trans('Discount (Payment Option)') }}</th>
        <th>{{ trans('Others Discount') }}</th>
        <th>{{ trans('Down Payment') }}</th>
        <th>{{ trans('report.rpt_loan_amount') }}</th>
        <th>{{ trans('report.rpt_int_rate') }}</th>
        <th>{{ trans('report.rpt_int_rate_type') }}</th>
        <th>{{ trans('Penalty ($/%)') }}</th>
        <th>{{ trans('multiple.m_status') }}</th>
        <th>{{ trans('multiple.m_note') }}</th>
    </tr>
    </thead>
    <tbody style="vertical-align: middle">
        <?php $i = 0;
        $loan_status = config('static_data.loan_status');
        $loan_type = $product_type;
        ?>
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
                <td style="vertical-align: middle;">{{$l->client_name}}</td>
                <td style="vertical-align: middle;text-align: center">{{ $l->contract_id ? $l->contract_id : '-'  }}</td>
                <td style="vertical-align: middle;text-align: center;">{{ !empty($l->drawdown_acc)?$l->drawdown_acc:"N/A" }}</td>
                <td style="vertical-align: middle;text-align: center">{{ !empty($l->short_code)?$l->short_code:"N/A" }}</td>
                <td style="vertical-align: middle;text-align: center">{{ !empty($l->name)?$l->name:"N/A" }}</td>
                <td style="vertical-align: middle;text-align: center">{{ !empty($l->code)?$l->code:"N/A" }}</td>
                <td style="vertical-align: middle;text-align: center">{{ !empty($l->contract_date)?date('d-M-Y',strtotime($l->contract_date)):"N/A" }}</td>
                <td style="text-align: justify">{{$loan_type[$l->loan_type - 1]->code}}</td>
                <td style="text-align: justify">{{$l->currency_code}}</td>
                <td style="text-align: center">{{$l->disburse_date ? date("d-M-Y", strtotime($l->disburse_date)) : '-'}}</td>
                <td style="text-align: center">{{$l->schedule_date ? date("d-M-Y", strtotime($l->schedule_date)) : '-'}}</td>
                <td style="text-align: right">{{$l->loan_duration}}</td> 
                <td style="text-align: right;">{{ number_format($l->unit_sale_price,2,'.',',') }}</td>
                <td style="text-align: right;">{{ number_format($l->amount_discount_payment_option,2,'.',',') }}</td>
                <td style="text-align: right;">{{ number_format($l->discount_payment_option,2,'.',',') }}</td>
                <td style="text-align: right;">{{ number_format($l->discount_other,2,'.',',') }}</td>
                <td style="text-align: right;">{{ number_format($l->down_payment_value,2,'.',',') }}</td>
                <td style="text-align: right">{{$l->original_amount ? number_format($l->original_amount,2,'.',',') : number_format($l->loan_amount,2,'.',',')}}</td>
                <td style="text-align: center">{{$l->interest_rate ? number_format($l->interest_rate, 2) : ''}}%</td>
                <td style="text-align: center">{{$l->rate_type}}</td>
                <td style="text-align: center;white-space: nowrap;">{{ number_format($l->penalty_rate1,2) }} {{$l->loan_penalty_type}}</td>
                <td align="justify" style="white-space: nowrap;">{{$loan_status[$l->status]}}</td>
                <td style="text-align: left;white-space: nowrap;">
                    <?php
                        $status = $l->status == 1 ? "Submitted": $loan_status[$l->status];
                        $status .= " on ";
                        if ($date != null) {
                            $status .= date_format(date_create($date), "d-M-Y");
                            echo $status;
                        } else {
                            echo "<label style='color:red'> Unknown</label>";
                        }
                    ?>
                </td>
            </tr>
        @empty
        <tr><td colspan="24" class="text-center">{{ trans('multiple.m_no_result') }}</td></tr>
        @endforelse
    </tbody>
</table>