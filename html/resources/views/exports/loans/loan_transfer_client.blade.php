<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
    <thead>
    <tr>
    <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_no') }}</th>
    <th style="text-align: center; vertical-align: middle;">Transfer Date</th>
    <th style="text-align: center; vertical-align: middle;">Transfer From</th>
    <th style="text-align: center; vertical-align: middle;">Transfer To</th>
    <th style="text-align: center; vertical-align: middle;">Transfer Type</th>
    <th style="text-align: center; vertical-align: middle;">{{ trans('report.rpt_contract_id') }}</th>
    <th style="text-align: center; vertical-align: middle;">Account.No</th>
    <th style="text-align: center; vertical-align: middle;">Company</th>
    <th style="text-align: center; vertical-align: middle;">Project</th>
    <th style="text-align: center; vertical-align: middle;">{{ trans('unit.unit_type') }}</th>
    <th style="text-align: center; vertical-align: middle;">{{ trans('unit.unit') }}</th>
    <th style="text-align: center; vertical-align: middle;">Transfer Remark</th>  
    </tr>
    </thead>
    <tbody style="vertical-align: middle">
        <?php $i = 0;
        $loan_status = config('static_data.loan_status');
        $loan_type = $product_type;
        ?>
        @forelse($loans as $l)
        <?php $i++;?>
            <tr>
                <td style="text-align: center">{{$i}}</td>
                <td style="vertical-align: middle;text-align: center">{{ !empty($l->transfer_date)?date('d-M-Y',strtotime($l->transfer_date)):"N/A" }}</td>   
                <td style="text-align: center">{{ !empty($l->Clients)?$l->Clients->client_name:'-' }}</td> 
                <td style="text-align: center;">{{ !empty($l->newClients)?$l->newClients->client_name:'-' }}</td>
                <td style="text-align: center;">{{ $l->transfer_type }}</td>
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
                <td style="text-align: right;">{{ $l->Remark }}</td>
            </tr>
        @empty
        <tr><td colspan="24" class="text-center">{{ trans('multiple.m_no_result') }}</td></tr>
        @endforelse
    </tbody>
</table>