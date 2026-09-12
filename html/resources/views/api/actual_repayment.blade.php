@if(!empty($loan))
    <h4 class="sch_title">{{ trans('loan.l_repayment_actual') }}</h4>
    <?php
    $result = [];
    $loan_ref = App\Models\Loan::select('id', 'contract_id')->where('id', '=', $loan->id)->first()->contract_id;
    foreach ($loan->payment as $key => $p) {
        $month = $p->payment_month;
        //if ($p->payment_month <= $loan->loan_duration) {
            if (!isset($result[$month])) $result[$month] = [];
            $result[$month][] = $p;
        //}
    }
    $repayment = [];
    $repayment_arr = LoanCalculate::loan_schedule($loan->schedule, $loan->start_date)[0];
    // for ORO
    foreach ($repayment_arr as $r) {
        $repayment[$r[6]] = $r;
    }

    //
    $loan_amount = $loan->loan_amount;
    $down_payment = $loan->down_payment;
    $repayment_status = config('static_data.loan_payment_status');
    $t_interest = 0;
    $t_principal = 0;
    $t_fee = $t_other_fee = 0;
    $t_mpayment = 0;
    $tr_interest = 0;
    $tr_principal = 0;
    $tr_principal_down_pay = 0;
    $tr_fee = 0;
    $tr_other_fee = 0;
    $tr_penalty = 0;
    $tr_total = 0;
    $tr_sub_total = 0;
    $tr_payowed = 0;
    $result = array_sort($result, function ($v, $k) {
        return $k;
    });
    ?>
    <table class="table table-bordered actual_repayment">
        <thead>
        <tr>
            <th colspan="7"
                style="text-align: center; background-color: lightyellow;">{{ trans('loan.l_schedule') }}</th>
            <th colspan="10" style="text-align: center; background-color: palegreen">{{ trans('loan.l_repayment') }}</th>
            <th colspan="5" style="text-align: center; background-color: orange">{{ trans('report.rpt_pass_due') }}</th>
        </tr>
        <tr>
            <th style="text-align: center;">{{ trans('multiple.m_no') }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_date') }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_interest') }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_principal') }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_fee') }}</th>
            <th style="text-align: center;">{{ trans('multiple.other_fee') }}</th>
            <th style="text-align: center;">{{ trans('loan.l_monthly_pay') }}</th>
            <!----- Repayment --->
            <th style="text-align: center;">{{ trans('report.rpt_invoice_number') }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_paid_date') }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_interest') }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_principal') }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_fee') }}</th>
            <th style="text-align: center;">{{ trans('multiple.other_fee') }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_penalty') }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_total') }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_principal_balance')  }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_principal_balance_down_payment')  }}</th>
            <th style="text-align: center;">{{ trans('report.rpt_overdue') }}</th>
            <th style="text-align: center;">{{ trans('multiple.m_status') }}</th>
            <th style="text-align: center;">{{ trans('loan.l_condition') }}</th>
            <th style="text-align: center;">{{ trans('loan.l_repayment_owed') }}</th>
            <th style="text-align: center;">{{ trans('multiple.m_action') }}</th>
        </tr>
        </thead>
        <?php
        $static = config('static_data.loan_payment_condition');
        $n = 0;
        $no = 0;
        ?>
        <tbody>
        @if(!empty($result) && !empty($repayment))
            @foreach($result as $i => $res)

                <?php
                $count = count($res);
                $rowspan = ($count > 1) ? 'rowspan=' . $count : '';
                $t_interest += $repayment[$i][2];
                $t_principal += $repayment[$i][3];
                $t_fee += $repayment[$i][4];
                $t_other_fee += $repayment[$i][7];
                $t_mpayment += $repayment[$i][5] + $repayment[$i][7];
                $no = ($repayment[$i][6] != "") ? $repayment[$i][6] : $n++;
                ?>
                <tr>
                    <td {{ $rowspan }} align="center">{{ $no }}</td>
                    <td {{ $rowspan }} align="center">{{ $repayment[$i][0] }}</td>
                    <td {{ $rowspan }} align="right">{{ number_format($repayment[$i][2], 2, '.', ',') }}</td>
                    <td {{ $rowspan }} align="right">{{ number_format($repayment[$i][3], 2, '.', ',') }}</td>
                    <td {{ $rowspan }} align="right">{{ number_format($repayment[$i][4], 2, '.', ',') }}</td>
                    <td {{ $rowspan }} align="right">{{ number_format($repayment[$i][7], 2, '.', ',') }}</td>
                    <td {{ $rowspan }} align="right"
                        style="font-weight:bold">{{ number_format($repayment[$i][5]+$repayment[$i][7] , 2, '.', ',') }}</td>

                <?php $c = 0;?>
                @foreach($res as $pay)
                    @if($c > 0)
                        <tr>
                            @endif
                            <?php
                            $tr_sub_total = $pay->penalty_amount + $pay->paid_interest + $pay->paid_principal + $pay->paid_fee + $pay->paid_other_fee;
                            ?>
                            <td align="center"> {{ $pay->invoice_number or '-' }}</td>
                            <td align="center">{{ $pay->repayment_date }}</td>
                            <td align="right">{{ number_format($pay->paid_interest, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($pay->paid_principal, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($pay->paid_fee, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($pay->paid_other_fee, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($pay->penalty_amount, 2, '.', ',') }}</td>
                            <td align="right" style="font-weight:bold">
                                {{ number_format($tr_sub_total, 2, '.', ',') }}</td>
                            
                                <?php
                                if($repayment[$i][9] == 'downpayment'){
                                    $down_payment -= $pay->paid_principal;
                                    $tr_principal_down_pay += $pay->paid_principal;
                                }else{
                                    $loan_amount -= $pay->paid_principal;

                                }
                                $tr_principal += $pay->paid_principal;
                                $tr_interest += $pay->paid_interest;
                                $tr_fee += $pay->paid_fee;
                                $tr_other_fee += $pay->paid_other_fee;
                                $tr_penalty += $pay->penalty_amount;
                                $tr_total += $tr_sub_total;
                                $status_class = '';//($pay->status == 0)? 'label label-warning label-mini' : 'label label-success label-mini';
                                if ($pay->status == 0 && $pay->condition_id == 1) {
                                    $tr_payowed += $pay->repayment_owed;
                                }
                                ?>
                            @if($repayment[$i][9] == 'downpayment')
                                <td align="right">-</td>
                                <td align="right">{{ number_format($down_payment, 2, '.', ',') }}</td>
                            @else
                                <td align="right">{{ number_format($loan_amount, 2, '.', ',') }}</td>
                                <td align="right">-</td>
                            @endif

                            <td align="center">{{ $pay->late_day }}</td>
                            @if($count <= 1)
                                <td class="remove-status"><?php echo (is_array($repayment_status) && array_key_exists($pay->status, $repayment_status)) ? $repayment_status[$pay->status] : '-';?></td>
                            @elseif($c == $count -1)
                                <td class="none-border remove-status"><?php echo (is_array($repayment_status) && array_key_exists($pay->status, $repayment_status)) ? '<span class="' . $status_class . '">' . $repayment_status[$pay->status] . '</span>' : '-'; ?></td>
                            @elseif($c == 0)
                                <td class="no-border"></td>
                            @else
                                <td class="none-border no-border"></td>
                            @endif
                            <td align="center">{{ (array_key_exists($pay->condition_id,$static) && ($pay->status == 0))?$static[$pay->condition_id] : '-'}}</td>
                        <!-- <td align="center">{{ ($pay->todo_payment > $repayment[$i][0])? date("d-M-Y", strtotime($pay->todo_payment)) : '-'}}</td> -->
                            <td align="right">{{ number_format($pay->repayment_owed, 2, '.', ',') }}</td>
                            <td>
                                @if(!empty($pay->repayment_receipt))
                                    <?php
                                    $file_parts = pathinfo($pay->repayment_receipt);
                                    ?>
                                    @if(isset($file_parts['extension']) && in_array(strtotime($file_parts['extension']),['jpg', 'jpeg', 'bmp', 'png']) )
                                        &nbsp;&nbsp;<a href="javascript:;"
                                                       class="btn btn-primary btn-xs repayment-receipt"
                                                       data-mfp-src="{{ asset('data/loans/receipts/'.$pay->repayment_receipt,isset($secure) ? false : false) }}"><i
                                                    class="fa fa-file"></i></a>
                                    @else
                                        &nbsp;&nbsp;<a
                                                href="{{ asset('data/loans/receipts/'.$pay->repayment_receipt,isset($secure) ? false : false) }}"><i
                                                    class="fa fa-file"></i></a>
                                    @endif
                                @endif
                                @if($c == $count -1 && $pay->status == 0 && $pay->condition_id == 1 &&($loan->status < 9 ))
                                    &nbsp;&nbsp;<a href="{{ route('add_loan_repayment',[$loan->id])}}"
                                                   class="btn btn-info btn-xs" target="_blank"><i class="fa fa-usd"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        <?php
                        $c++;
                        ?>
                        @endforeach
                        @endforeach
                        <tr style="font-weight: bold; text-align: right;">
                            <td colspan="2">{{ trans('report.rpt_total') }}</td>
                            <td>{{ number_format($t_interest, 2, '.', ',')  }}</td>
                            <td>{{ number_format($t_principal, 2, '.', ',') }}</td>
                            <td>{{ number_format($t_fee, 2, '.', ',')}}</td>
                            <td>{{ number_format($t_other_fee, 2, '.', ',')}}</td>
                            <td>{{ number_format($t_mpayment, 2, '.', ',')  }}</td>
                            <td></td>
                            <td></td>
                            <td>{{ number_format($tr_interest, 2, '.', ',')  }}</td>
                            <td>{{ number_format($tr_principal, 2, '.', ',') }}</td>
                            <td>{{ number_format($tr_fee, 2, '.', ',')}}</td>
                            <td>{{ number_format($tr_other_fee, 2, '.', ',')}}</td>
                            <td>{{ number_format($tr_penalty, 2, '.', ',')  }}</td>
                            <td>{{ number_format($tr_total, 2, '.', ',')  }}</td>
                            <td>{{ number_format($loan_amount, 2, '.', ',')  }}</td>
                            <td>{{ number_format($down_payment, 2, '.', ',')  }}</td>
                            <td></td>
                            <td></td>
                            <td>{{ number_format($tr_payowed, 2, '.', ',')  }}</td>
                            <td colspan="4"></td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="18">No results...</td>
                        </tr>
                    @endif
        </tbody>
    </table>
@endif