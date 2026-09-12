<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
    <thead>
    <tr>
    <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_no') }}</th>
                <th style="text-align: center; vertical-align: middle;">Restructure Date</th>
                <th style="text-align: center; vertical-align: middle;">Customer</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_contract_id') }}</th>
                <th style="text-align: center; vertical-align: middle;">Account.No</th>
                <th style="text-align: center; vertical-align: middle;">Company</th>
                <th style="text-align: center; vertical-align: middle;">Project</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('unit.unit_type') }}</th>
                <th style="text-align: center; vertical-align: middle;">{{ trans('unit.unit') }}</th>
                <th style="text-align: center; vertical-align: middle;">Old Loan Amount</th>
                <th style="text-align: center; vertical-align: middle;">Principal</th>
                <th style="text-align: center; vertical-align: middle;">Re Down Payment</th>   
                <th style="text-align: center; vertical-align: middle;">Down Payment</th>
                <th style="text-align: center; vertical-align: middle;">Interest</th>
                <th style="text-align: center; vertical-align: middle;">Penalty</th>
                <th style="text-align: center; vertical-align: middle;">Fee</th>
                <th style="text-align: center; vertical-align: middle;">Other Fee</th>
                <th style="text-align: center; vertical-align: middle;">Amount</th>
                <th style="text-align: center; vertical-align: middle;">Drawdown Principal Amount</th>
                <th style="text-align: center; vertical-align: middle;">Loan Amount</th>
                <th style="text-align: center; vertical-align: middle;">Remark</th>  
    </tr>
    </thead>
    <tbody style="vertical-align: middle">
                    <?php $i = 0;//dd($loans);?>
                    @forelse($loans as $l)
                    <?php $i++;?>
                    <tr>
                            <td style="text-align: center">{{$i}}</td>
                            <td style="vertical-align: middle;text-align: center">{{ !empty($l->restructure_date)?date('d-M-Y',strtotime($l->restructure_date)):"N/A" }}</td>   
                            <td style="text-align: center">{{ !empty($l->client_name)?$l->client_name:'-' }}</td> 
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
                            <td style="text-align: center;">{{ $l->old_loan_amount }}</td>   
                            <td style="text-align: center;">{{ $l->principal }}</td>
                            <td style="text-align: center;">{{ $l->re_down_payment }}</td>
                            <td style="text-align: center;">{{ $l->down_payment }}</td> 
                            <td style="text-align: center;">{{ $l->interest }}</td>
                            <td style="text-align: center;">{{ $l->penalty }}</td>  
                            <td style="text-align: center;">{{ $l->fee }}</td>
                            <td style="text-align: center;">{{ $l->other_fee }}</td>
                            <td style="text-align: center;">{{ $l->amount }}</td>
                            <td style="text-align: center;">{{ $l->drawdown_principal_amount }}</td>   
                            <td style="text-align: center;">{{ $l->loan_amount }}</td>
                            <td style="text-align: right;">{{ $l->note }}</td>
                        </tr>
                    @empty
                    <tr><td colspan="24" class="text-center">{{ trans('multiple.m_no_result') }}</td></tr>
                    @endforelse
                </tbody>
</table>