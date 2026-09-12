<h4 class="sch_title">{{ trans('loan.l_repayment_schedule') }}</h4>
<?php
$app_locale = app()->getLocale();
$repayment_array = [];
$downpayment_array = [];
$type = $repayment_type;
if (!empty($repayment_schedule) && count($repayment_schedule) > 0) {
    $schedule = LoanCalculate::loan_schedule_restructure($repayment_schedule, $disbursement_date ? $disbursement_date : $start_date)[0];
    $repayment_schedule = $repayment_schedule->toArray();
    $total_days = 0;
    $total_interest = 0;
    $total_principal = 0;
    $total_fee = 0;
    $total_admin_fee = 0;
    $total_maintain_fee = 0;
    $total_monthly = 0;
    $date_arr = [];
    $amount_arr = [];
    $balance = floatval($loan_amount);
    $total_balloon = 0;
    $total_regular = 0;
    $monthly_regular = 0;
    $amount_arr[0] = -$balance;
    $l_balloon_month = explode(",", $balloon_month);
    for ($i = 0; $i < count($schedule); $i++) {
        $repay = $schedule[$i];
        $monthly = $repay[2] + $repay[3] + $repay[4];
        $balance -= $repay[3];
        if ($admin_fee_opt == '3') $total_admin_fee += ROUND($balance * $admin_fee * floatval($repay[1]) / (30 * 100), 0);
        if ($maintain_fee_opt == '3') $total_maintain_fee += ROUND($balance * $maintain_fee * floatval($repay[1]) / (30 * 100), 0);
        $repayment_array[$i] = [$repay[0], $repay[1], $repay[2], $repay[3], $repay[4], $monthly, $balance, $repay[6], $repay[7],$repay[8]];
        $total_days += $repay[1];
        $total_interest += $repay[2];
        $total_principal += $repay[3];
        $total_fee += $repay[4];
        $total_other_fee += $repay[7];
        $total_monthly += $repay[5];
        $date_arr[$i] = strtotime($repay[0]);
        $amount_arr[$i] += $repay[5];
        if (!is_null($balloon_month)) {
            $monthly_regular = $repay[5];
            foreach ($l_balloon_month as $key => $val) {
                if ($i == $val) {
                    $total_balloon += $repay[5];
                    $monthly_regular = 0;
                }
            }
            $total_regular += $monthly_regular;
        }
    }
}
if ($admin_fee_opt != '3') $total_admin_fee = ROUND($loan_amount * $admin_fee / 100, 0);
if ($maintain_fee_opt != '3') $total_maintain_fee = ROUND($balance * $maintain_fee / 100, 0);
$table = '';
if (!empty($repayment_array)) {
    // Annual Yield
    $f = new App\Http\Controllers\FinancialClass\FinancialClassController();
    $annual_yield = $f->XIRR($amount_arr, $date_arr, 0.1) * 100;
    // Overview
    $table .= '<table  class="table table-bordered tbrepayment">';
    $table .= '<thead>';
    $table .= '</thead>';
    $table .= '<tbody>';
    $table .= '<tr>';
    $table .= '<td>' . trans('customer.cus_customer_name') . '</td><td colspan="2" style="text-align: center;">' . $client_name . '</td>';
    $table .= '<td>' . trans('report.rpt_loan_amount') . '</td><td style="text-align: center;">' . $currency_symbol . number_format($loan_amount, 2, '.', ',') . '</td>';
    if ($app_locale == 'kh') {
        if ("" != $disbursement_date) {
            $day = khmerShortDay(date('D', strtotime($disbursement_date)));
            $month = khmerMonth(date('M', strtotime($disbursement_date)));
            $table .= '<td>' . trans('loan.l_disburse_date') . '</td><td style="text-align: center;">' . date('' . $day . '. j ' . $month . ' Y', strtotime($disbursement_date)) . '</td>';
        } else {
            $day = khmerShortDay(date('D', strtotime($start_date)));
            $month = khmerMonth(date('M', strtotime($start_date)));
            $table .= '<td>' . trans('loan.l_disburse_date') . '</td><td style="text-align: center;">' . date('' . $day . '. j ' . $month . ' Y', strtotime($start_date)) . '</td>';
        }
    } else {
        $table .= '<td>' . trans('loan.l_disburse_date') . '</td><td style="text-align: center;">' . (!empty($disbursement_date) ? date("d-M-Y", strtotime($disbursement_date)) : date("d-M-Y", strtotime($start_date))) . '</td>';
    }
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td rowspan="3" style="vertical-align: middle; text-align: center;">' . trans('report.rpt_tenure') . '</td>';
    $table .= '<td>#' . trans('multiple.m_month') . '</td><td style="text-align: center;">' . $loan_duration . '</td >';

    $loan_amount = floatval($loan_amount);
    $monthly_payment = floatval($monthly_payment);
    $interest_rate = floatval($interest_rate);
    $loan_duration = floatval($loan_duration);


    $table .= '<td class="no-border">' . trans('loan.l_balloon_average') . '</td><td class="no-border" style="text-align: center;">' . (($total_balloon != 0) ? ($currency_symbol . number_format($total_balloon / $num_balloon, 2, '.', ',')) : "-") . '</td>';
    $table .= '<td rowspan="1" style="vertical-align: middle">' . trans('report.rpt_total_interest') . '</td><td  rowspan="1" style="vertical-align: middle; text-align: center;">' . $currency_symbol . number_format($total_interest, 2, '.', ',') . '</td>';
    $table .= '</tr>';

    $table .= '<tr>';
    $table .= '<td>#' . trans('loan.l_balloon_month') . '</td><td  style="text-align: center;">' . $num_balloon . '</td>';
    $table .= '<td class="none-border">' . trans('loan.l_total_payment') . '</td><td class="none-border" style="text-align: center;">' . $currency_symbol . number_format($total_monthly, 2, '.', ',') . '</td>';
    $table .= '<td rowspan="1">' . trans('report.upfront_charge') . '</td><td  rowspan="1" style="text-align: center;">' . $currency_symbol . number_format($total_admin_fee, 2, '.', ',') . '</td>';
    $table .= '</tr>';

    $table .= '<tr>';
    $table .= '<td>#' . trans('loan.l_sign_regular') . '</td><td  style="text-align: center;">' . ($loan_duration - $num_balloon) . '</td>';
    $table .= '<td  class="border">' . trans('loan.l_regular_average') . '</td><td class="border" style="text-align: center;">' . $currency_symbol . number_format($total_regular / ($loan_duration - $num_balloon), 2, '.', ',') . '</td>';
    $table .= '<td rowspan="1">' . trans('report.maintain_fee') . '</td><td  rowspan="1" style="text-align: center;">' . $currency_symbol . number_format($total_maintain_fee, 2, '.', ',') . '</td>';
    $table .= '</tr>';

    $table .= '<tr>';
    $table .= '<td colspan="2">' . trans('loan.l_interest_rate_pm') . '</td><td class="custom_display" style="text-align: center;">' . number_format($interest_rate, 2, '.', ',') . '%' . '</td><td class="custom_display remove_class"><span>' . trans('loan.l_pa_rate') . '</span></td><td class="custom_display remove_class"  style="text-align: center;"><span>' . number_format(($interest_rate * 12), 2, '.', ',') . '%' . '</span></td><td class="custom_display remove_class"><span>' . trans('loan.l_annual_yield') . '</span></td><td class="custom_display remove_class"  style="text-align: center;"><span>' . number_format($annual_yield, 2, '.', ',') . '%' . '</span></td>';
    $table .= '</tr>';

    $table .= '</tbody>';
    $table .= '</table>';

    echo $table;
}
?>
<table class="table table-bordered table-striped table-condensed tbrepayment_sch">
    <thead>
    <tr>
        <th style="text-align: center;" colspan="6">Down Payment Plan</th>
    </tr>
    <tr>
        <th style="text-align: center;">No</th>
        <th style="text-align: center;">Payment Date</th>
        <th style="text-align: center;">Beginning Balance</th>
        <th style="text-align: center;">Payment Amount</th>
        <th style="text-align: center;">Ending Balance</th>
        <th class="invisible_edit">Action</th>
    </tr>
    </thead>
    <tbody id="tbody">
        @include('reschedule.tb_repayment_schedule_downpayment',compact('downpayment'))
    </tbody>
</table>

<table class="table table-bordered table-striped table-condensed tbrepayment_sch">
    <thead>
    <tr>
        <th style="text-align: center;" rowspan="2">{{ trans('report.num_months') }}</th>
        <th style="text-align: center;" rowspan="2">{!! trans('loan.l_repayment_date') !!}</th>
        <th style="text-align: center;" rowspan="2">{{ trans('loan.l_day') }}</th>
        <th style="text-align: center;" colspan="5">{{ trans('report.cap') }}</th>
        <th style="text-align: center;" rowspan="2">{{ trans('report.rpt_principal_balance') }}</th>
        <th class="invisible_edit" rowspan="2">Action</th>
        <!-- <th colspan="2">Others</th> -->
    </tr>
    <tr>
        <th style="text-align: center;">{{ trans('report.rpt_interest') }}</th>
        <th style="text-align: center;">{{ trans('report.rpt_principal') }}</th>
        <th style="text-align: center;">{{ trans('report.rpt_fee') }}</th>

        <th style="text-align: center;">{{ trans('report.other_fee') }}</th>

        <th style="text-align: center;">{{ trans('loan.l_monthly_pay') }}</th>
    </tr>
    </thead>
    <tbody id="tbody">
    @include('reschedule.tb_repayment_schedule',compact('repayment_array'))
    </tbody>
</table>
<div class="signature_detail">
    <div class="left_content_detail" ​>
        <p style="text-align: center">ជ.នាយកដ្ឋានឥណទាន</p>
        <br><br><br><br><br>
        <p>.............................................................</p>
        <p>ឈ្មោះ/Name:</p>
        <p>ចុះថ្ងៃទី............/............./.....................</p>
    </div>
    <div class="right_content_detail">
        <p style="text-align: center">ស្នាមមេដៃស្តំាកូនបំណុល</p>
        <br><br><br><br><br>
        <p>.............................................................</p>
        <p>ឈ្មោះ/Name:</p>
        <p>ចុះថ្ងៃទី............/............./.....................</p>
    </div>
</div>
