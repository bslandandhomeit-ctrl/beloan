<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
    <thead>
    <tr>
        <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_no') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('customer.cus_customer_name') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_contract_id') }}</th>
                <th style="text-align: center; vertical-align: middle;">Account.No</th>
                <th style="text-align: center; vertical-align: middle;">Company</th>
                <th style="text-align: center; vertical-align: middle;">Project</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('unit.unit_type') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('unit.unit') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('loan.l_contact_date') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('loan.contract_deadline') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_loan_type') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_disbursement_date') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_maturity_date') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('loan.l_tenure') }}</th>
                <th style="text-align: center; vertical-align: middle;">Clearance Amount</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Unit Price') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Discount Amount') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Discount (Payment Option)') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Others Discount') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Down Payment') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_loan_amount') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_int_rate') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_int_rate_type') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('Penalty ($/%)') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_status') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_note') }}</th>
        </thead>
        <tbody style="vertical-align: middle">
                    <?php $i = 0;//dd($loans);?>
                    @forelse($loans as $l)
                        <tr>
                            <?php
                                $i++;
                                $date = null;
                                $note=$l->status_remark;
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
                                            $note=$l->write_off_remark;
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
                                    case 11: 
                                        $note=$l->write_off_remark;
                                    break;
                                    default: break;
                                }
                            ?>
                            <td style="text-align: center">{{$i}}</td>
                            <td style="vertical-align: middle">{{$l->client_name}}</td>
                            <td style="vertical-align: middle;text-align: center">
                                <a style="text-decoration: underline" href="{{ route('loan_detail', [$l->id])}}">{{ $l->contract_id ? $l->contract_id : '-'  }}</a>
                            </td>
                            {{-- <td style="vertical-align: middle;text-align: center">
                                <a style="text-decoration: underline" href="{{ route('loan_account', [$l->loan_account_id])}}">{{(!empty($l->client_loan_account->account_no))? $l->client_loan_account->account_no : ""}}</a>
                            </td> --}}
                            <td style="vertical-align: middle;text-align: center;">
                                <a style="text-decoration: underline" href="{{ route('loan_account', [$l->loan_account_id])}}">
                                    {{ !empty($l->drawdown_acc)?$l->drawdown_acc:"N/A" }}
                                </a>
                            </td>
                            <td style="vertical-align: middle;text-align: center">{{$l->company}}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->short_code)?$l->short_code:"N/A" }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->name)?$l->name:"N/A" }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->code)?$l->code:"N/A" }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->contract_date)?date('d-M-Y',strtotime($l->contract_date)):"N/A" }}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->contract_deadline)?date('d-M-Y',strtotime($l->contract_deadline)):"N/A" }}</td>                            
                            
                            <td style="text-align: justify">{{$loan_type[$l->loan_type - 1]->code}}</td>
                            <td style="text-align: center">{{$l->disburse_date ? date("d-M-Y", strtotime($l->disburse_date)) : '-'}}</td>
                            <td style="text-align: center">{{$l->schedule_date ? date("d-M-Y", strtotime($l->schedule_date)) : '-'}}</td>
                            <td style="text-align: right">{{$l->loan_duration}}</td> 
                            <td style="text-align: right;">{{ number_format($l->clearance_amount,2,'.',',') }}</td>
                            <td style="text-align: right;">{{ number_format($l->unit_sale_price,2,'.',',') }}</td>
                            <td style="text-align: right;">{{ number_format($l->amount_discount_payment_option,2,'.',',') }}</td>
                            <td style="text-align: right;">{{ number_format($l->discount_payment_option,2,'.',',') }}</td>
                            <td style="text-align: right;">{{ number_format($l->discount_other,2,'.',',') }}</td>
                            <td >{{ number_format($l->down_payment_value,2,'.',',') }}</td>
                            <td>{{$l->original_amount ? number_format($l->original_amount,2,'.',',') : number_format($l->loan_amount,2,'.',',')}}</td>
                            <td style="text-align: center">{{$l->interest_rate ? number_format($l->interest_rate, 2) : ''}}%</td>
                            <td style="text-align: center">{{$l->rate_type}}</td>
                            <td style="text-align: center;white-space: nowrap;">{{ number_format($l->penalty_rate1,2) }} {{$l->loan_penalty_type}}</td>
                            <td align="justify" style="white-space: nowrap;">{{$loan_status[$l->status]}}</td>
                            <td style="text-align: left;">{{$note}}</td>
                        </tr>
                    @empty
                    <tr><td colspan="24" class="text-center">{{ trans('multiple.m_no_result') }}</td></tr>
                    @endforelse
                </tbody>
</table>