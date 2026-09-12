<section id="unseen">

    <!--<h5><strong>{{ trans('customer.cus_loan_summary') }}</strong></h5>-->
    <hr/>
    <div class="row tb-row">
        <div class="col-md-8">
            {{ trans('customer.cus_num_loan') }}
        </div>
        <div class="col-md-1">
            {{ count($client['loan']) }}
        </div>
    </div>

    <table class="table table-bordered table-striped table-condensed">
        <thead>
        <th style="text-align: center">{{ trans('multiple.m_no')}}</th>
        <th style="text-align: center">{{ trans('multiple.account_no')}}</th>
        <th style="text-align: center">{{ trans('report.rpt_contract_id')}}</th>
        <th style="text-align: center">{{ trans('report.rpt_loan_amount') }}</th>
        <th style="text-align: center">{{ trans('report.rpt_total_interest') }}</th>
        <th style="text-align: center">{{ trans('report.rpt_paid_amount') }}</th>
        <th style="text-align: center">{{ trans('customer.cus_type') }}</th>
        <th style="text-align: center">{{ trans('multiple.m_status') }}</th>
        <th style="text-align: center">{{ trans('multiple.m_description') }}</th>
        <th style="text-align: center">{{ trans('multiple.m_action') }}</th>
        </thead>
        <tbody>
        <?php $n=1;?>
        @forelse($client['loan'] as $loan)
            <tr>
                <td align="center">{{$n++}}</td>
                <td align="center">{{$loan->client_loan_account->account_no}}</td>
                <td align="center"><a href="{{ route('loan_detail',[$loan->id]) }}">{{$loan->contract_id}}</a></td>
                <td align="right">{{number_format($loan->loan_amount,2,'.',',')}}</td>
                <td align="right">
                    <?php
                    if(empty($loan)) {
                        continue;
                    }
                    $repayment_schedule = LoanCalculate::loan_schedule($loan->schedule, $loan->start_date)[0];
                    $totalInterest = 0;
                    if(!empty($repayment_schedule)){
                        for($i = 1; $i <= $loan->loan_duration; $i++){
                            $totalInterest += $repayment_schedule[$i][2];
                        }
                    }
                    echo number_format($totalInterest,2,'.',',');

                    ?>
                </td>
                <td align="right">
                    <?php
                    $total_amount_paid = 0.0;
                    foreach($loan->payment as $p){
                        $total_amount_paid += $p->paid_interest + $p->paid_principal;
                    }
                    echo number_format($total_amount_paid,2,'.',',');
                    ?>
                </td>
                <td style="text-align:center;">
                    @if($client->client_type == "Individual")
                        <i class="fa fa-user font-17"></i>
                    @elseif($client->client_type == "Group")
                        <i class="fa fa-group font-17"></i>
                    @elseif($client->client_type == "Organization")
                        <i class="fa fa-home font-17"></i>
                    @endif
                </td>
                <?php
                $status = config('static_data.loan_status')[$loan->status];
                ?>
                <td align="center">{{ $status }}</td>

                <td>
                    {{ $status == "Unauthorized"?"Submitted":$status }} on
                    <?php
                    $date = null;
                    switch($loan->status){
                        case 1: $date = $loan->submitted_on; break;
                        case 2: $date = !empty($loan->approval) ?$loan->approval->approval_date : null; break;
                        case 3: $date = $loan->disburse_date; break;
                        case 4: $date = $loan->rejected_date; break;
                        case 5: $date = !empty($loan->writeoff) ? $loan->writeoff->write_off_date :null; break;
                        case 6: $date = !empty($loan->close)  ? $loan->close->closed_date : null; break;
                        case 7:
                        case 8: $date = $loan->submitted_on; break;
                        case 9: $date = !empty($loan->payoff) ? $loan->payoff->payoff_date :null; break;
                        case 10: {
                            $last_payment = $loan->payment->last();
                            if($last_payment->status == 1) $date = $last_payment->repayment_date;
                            break;
                        }
                        default: break;
                    }
                    if($date != null){
                        echo date_format(date_create($date),"d-M-Y");
                    }else{
                        echo "<label style='color:red'> Unknown</label>";
                    }
                    ?>
                </td>
                <td style="text-align:center;">
                    <?php
                    switch($loan->status){
                        case 1:
                        case 7: {
                            echo '<a href="'. route('loan_approval',[$loan->id]) .'" class="fa  fa-check-circle font-17" title="Approve"></a>';
                            echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
                            echo '<a href="'. route('loan_edit',[$loan->id]) .'" class="fa fa-pencil font-17" title="Edit this loan"></a>';
                            break;
                        }

                        case 2: echo '<a href="'. route('loan_disburse',[$loan->id]) .'" class="fa fa-flag font-17" title="Disburse"></a>'; break;

                        case 3:
                        case 8: echo '<a href="'.route('add_loan_repayment',[$loan->id]).'" class="fa fa-dollar font-17" title="Make repayment"></a>'; break;

                        case 4:
                        case 5:
                        case 6:
                        case 9:
                        case 10: echo '<a href="'.route('loan_detail',[$loan->id]).'" class="fa fa-file font-17" title="View Details"></a>'; break;
                    }
                    ?>

                </td>
            </tr>
        @empty
            <tr><td colspan=9>{{ trans('multiple.m_no_result') }}</td></tr>
        @endforelse
        </tbody>
    </table>
    <div>
{{--        @include('partials.pagination',['results'=>$loans])--}}
    </div>


    {{--<h5><strong>{{ trans('multiple.audit_title') }}</strong></h5>--}}
    {{--<table class="table table-bordered table-condensed">--}}
        {{--<tr>--}}
            {{--<th>{{ trans('multiple.action') }}</th>--}}
            {{--<th>{{ trans('multiple.by') }}</th>--}}
            {{--<th>{{ trans('multiple.date') }}</th>--}}
            {{--<th>{{ trans('multiple.status') }}</th>--}}
        {{--</tr>--}}

        {{--@foreach($audit as $au)--}}
            {{--<tr>--}}
                {{--<td>{{ trans('multiple.audit_created') }}</td>--}}
                {{--<td>{{$au->audit1->name}}</td>--}}
                {{--<td>{{$au->created_at}}</td>--}}
                {{--<td>{{ trans('multiple.unauthorized') }}</td>--}}
            {{--</tr>--}}

            {{--@if($au->audit2)--}}
                {{--<tr>--}}
                    {{--<td>{{ trans('multiple.audit_updated') }}</td>--}}
                    {{--<td>{{$au->audit2->name}}</td>--}}
                    {{--<td>{{$au->updated_at}}</td>--}}
                    {{--<td>{{ trans('multiple.authorized') }}</td>--}}
                {{--</tr>--}}
            {{--@endif--}}
        {{--@endforeach--}}
    {{--</table>--}}
</section>