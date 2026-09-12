<?php
use App\Http\Controllers\FinancialClass\FinancialClassController;

$type = $l_repayment_type;
$holiday = [];
$admin_fee = floatval($admin_fee);
$maintain_fee = floatval($maintain_fee);
$other_fee = floatval($other_fee);
if(!empty($holidays)){
    $holiday = $holidays;
}
if(is_null($admin_fee_opt)){
    $loan = \App\Models\Loan::select("id", "admin_fee", "maintain_fee", "admin_fee_opt", "maintain_fee_opt", "other_fee", "loan_amount","down_payment")->where('id',$loan_id)->first();
    $admin_fee = floatval($loan->admin_fee);
    $maintain_fee = floatval($loan->maintain_fee);
    $admin_fee_opt = $loan->admin_fee_opt;
    $maintain_fee_opt = $loan->maintain_fee_opt;
    $other_fee = floatval($loan->other_fee);
    
}
//var_dump($down_payment_duration);
//var_dump($installment_duration);
$repayment_array = LoanCalculate::monthly_loan_schedule($l_repayment_type,$l_start_date,$l_tenure,$l_amount,$l_rate,
                    $l_balloon_num, $l_balloon_month, $l_monthly_pay, $l_bal_amount_array,$custom_flag,$l_days_of_month,
                    $holiday_flag, $holiday, 0, $principal_input, $disburse_on, $round, null, $c_principal, $ppi, null,
                    $frequency, $is_disburse, $loan_id,$admin_fee, $admin_fee_opt, $maintain_fee, $maintain_fee_opt, $c_fee, intval($change_digit), floatval($monthly_amount),'loan')[0];
$downPayment_arrray = LoanCalculate::monthly_loan_schedule_downPayment(1,$start_payment_date,$down_payment_duration,$down_payment,0,
                    $l_balloon_num, $l_balloon_month, $l_monthly_pay, $l_bal_amount_array,$custom_flag,$l_days_of_month,
                    $holiday_flag, $holiday, 0, $principal_input, $disburse_on, $round, null, $c_principal, $ppi, null,
                    $frequency, $is_disburse, $loan_id,$admin_fee, $admin_fee_opt, $maintain_fee, $maintain_fee_opt, $c_fee, intval($change_digit), floatval($monthly_amount),'downpayment')[0];

$table = '';
$total_fee1 = 0;
//var_dump($repayment_array);
$data = LoanCalculate::getLoanSch($repayment_array, $admin_fee, $admin_fee_opt, $maintain_fee, $maintain_fee_opt,$other_fee,
                                  $l_repayment_type, $c_fee, $is_disburse,$count, $disburse_on, $l_start_date, $loan, $change_digit,'loan');

$getDownpayment = LoanCalculate::getDownpayment($downPayment_arrray, $admin_fee, $admin_fee_opt, $maintain_fee, $maintain_fee_opt,$other_fee,
                                  $l_repayment_type, $c_fee, $is_disburse,$count, $disburse_on, $start_payment_date, $loan, $change_digit,$repayment_array,'downpayment');
$tb_sch = $data['tb_sch'];
$tb_sch_down = $getDownpayment['tb_sch'];
//var_dump($tb_sch_down);
// Annual Yield
$annual_yield = $data['annual_yield'];
$total_days = $data['total_days'];
$total_interest = $data['total_interest'];
$total_principal = $data['total_principal'];
$total_other_fee = $data['total_other_fee'];
//$total_fee1= $data['total_fee'];
$total_monthly = $data['total_monthly'];

if(!empty($repayment_array))
{
    $no = 0;
    $app_locale = app()->getLocale();

    if($type == 3 ||  $type == 4 || $type == 5 || $type == 9){
        $isBalloon = true;
    }else{
        $isBalloon = false;
    }
//    $annual_yield = 100*($total_interest / $l_tenure / $average_bal * 12);
    // Overview
    $table .= '<table  class="table table-bordered tbrepayment">';
    $table .= '<thead>';
    $table .= '</thead>';
    $table .= '<tbody>';
    $table .= '<tr>';
    $table .= '<td>'.trans('customer.cus_customer_name').'</td><td colspan="2" style="text-align: center;">'.$l_client_name.'</td>';
    $table .= '<td>'.trans('report.rpt_loan_amount').'</td><td style="text-align: center;">'.$currency.number_format($l_amount, 2, '.', ',').'</td>';
    if($app_locale == 'kh'){
        $day = khmerShortDay(date('D',strtotime($l_start_date)));
        $month = khmerMonth(date('M',strtotime($l_start_date)));
        $table .='<td>'.trans('loan.l_disburse_date').'</td><td style="text-align: center;">'. date(''.$day.'. j '.$month .' Y',strtotime($disburse_on?$disburse_on:$l_start_date)) .'</td>';
    }else{
        $table .= '<td>'.trans('loan.l_disburse_date').'</td><td style="text-align: center;">'.date("d-M-Y", strtotime($disburse_on?$disburse_on:$l_start_date)).'</td>';
    }
    $table .= '</tr>';

    $table .= '<tr>';
    $table .='<td rowspan="3">'.trans('report.rpt_tenure').'</td>';
    $table .='<td>#'.trans('multiple.m_month').'</td><td style="text-align: center;">'.$l_tenure.'</td >';

    $l_amount = floatval($l_amount);
    $l_monthly_pay = floatval($l_monthly_pay);
    $l_rate = floatval($l_rate);
    $l_tenure = floatval($l_tenure);
    $sell_price = $down_payment +  $l_amount;
    if($loan){
        $sell_price = $loan->down_payment +  $loan->loan_amount;
        $down_payment = $loan->down_payment;
    }
    if($isBalloon){
        $l_balloon_num = floatval($l_balloon_num);
        $intraday_rate = ($l_amount * ($l_rate/100) * 12 / 360);
        $const_interest = $intraday_rate * 30;
        $regular_prin_permonth = $l_monthly_pay - $const_interest;
        $regular_prin_sum = ($l_tenure - $l_balloon_num) * $regular_prin_permonth;
        $balloon_amount = ($l_amount - $regular_prin_sum)/$l_balloon_num;
        if($l_monthly_pay == 0){
            $balloon_amount = $l_amount / $l_balloon_num;
        }
    }


    $table .='<td class="no-border">'.trans('loan.l_balloon_average').'</td><td class="no-border" style="text-align: center;">'.(($isBalloon)?($currency.number_format($balloon_amount, 2, '.', ',')):"-").'</td>';
    $table .='<td rowspan="1">'.trans('report.rpt_total_interest').'</td><td  rowspan="1" style="text-align: center;">'.$currency.number_format($total_interest, 2, '.', ',').'</td>';
    $table .= '</tr>';

    $table .= '<tr>';
    $table .='<td>#'.trans('loan.l_balloon_month').'</td><td style="text-align: center;">'.$l_balloon_num.'</td>';
    $table .='<td class="none-border">'.trans('loan.l_total_payment').'</td><td class="none-border" style="text-align: center;">'.$currency.number_format($total_monthly, 2, '.', ',').'</td>';
    $table .='<td rowspan="1">'.trans('report.upfront_charge').'</td><td  rowspan="1" style="text-align: center;">'.$currency.number_format($data['total_admin_fee'], 2, '.', ',').'</td>';
    $table .= '</tr>';

    $table .= '<tr>';
    $table .='<td>#'.trans('loan.l_sign_regular').'</td><td style="text-align: center;">'.($l_tenure - $l_balloon_num).'</td>';
    if($maintain_fee_opt == '0'){
        $regular =(($l_amount - $balloon_amount)/($l_tenure - $l_balloon_num))+($total_interest/$l_tenure);
    }else{
        $regular = (($l_amount - $balloon_amount)/($l_tenure - $l_balloon_num))+($total_interest/$l_tenure)+$maintain_fee/$l_tenure;
    }
    if($isBalloon){
        $regular = ($total_interest/$l_tenure);
    }
    $table .='<td  class="border">'.trans('loan.l_regular_average').'</td><td class="border" style="text-align: center;">'.$currency.number_format($regular, 2, '.', ',').'</td>';
    $table .='<td rowspan="1">'.trans('report.maintain_fee').'</td><td  rowspan="1" style="text-align: center;">'.$currency.number_format($data['total_maintain_fee'], 2, '.', ',').'</td>';
    $table .= '</tr>';

    $table .= '<tr>';
    $table .='<td colspan="2">'.trans('loan.l_interest_rate_pm').'</td><td class="custom_display" style="text-align: center;">'
             .number_format($l_rate, 4, '.', ',').'%'.'</td><td class="custom_display remove_class"><span>'.trans('loan.l_pa_rate').'</span>
             </td><td class="custom_display remove_class" style="text-align: center;"><span>'.number_format(($l_rate*12), 2, '.', ',').'%'.'</span></td>
             <td class="custom_display remove_class"><span>'.trans('loan.l_annual_yield').'</span></td>
             <td class="custom_display remove_class" style="text-align: center;"><span>'.number_format($annual_yield, 2, '.', ',').'%'.'</span></td>';
    $table .= '</tr>';

    $table .= '</tbody>';
    $table .= '</table>';

    $table .='<table class="table table-bordered tbrepayment_sch" >';
    $table .= '<thead>';
    $table .= '<tr style="text-align: center;">';
        $table .= '<th style="text-align: center;"colspan="6">Down Payment Plan</th>';
    $table .= '</tr>';
    $table .= '<tr style="text-align: center;">';
    $table .= '<th style="text-align: center;">No</th>';
    $table .= '<th style="text-align: center;">Payment Date</th>';
    $table .= '<th style="text-align: center;">Beginning Balance</th>';
    $table .= '<th style="text-align: center;">Payment Amount</th>';
    $table .= '<th style="text-align: center;">Ending Balance</th>';
    $table .= '<th style="text-align: center;">Note</th>';
    // $table .= '<th class="td-schedule-hide" hidden = "true">'.trans('report.rpt_others').'</th>';
    $table .= '</tr>';
    $table .= '</thead>';

    $balance_loan = $down_payment;
    $no = 1;
    foreach ($tb_sch_down as $key=>$sch){
        if($sch['date']=='undefined') continue;
        if($no == count($tb_sch)-1) $no_cl = ' last_row';
        $desc = '';
        if($no > 0){
            $desc = LoanCalculate::addOrdinalNumberSuffix($no);
        }

        $table .= '<tr class="row_'.$no.$no_cl.'">';
            $table .= '<td style="text-align: center;">'.$no.'</td>';
            if($app_locale == 'kh'){
                $day = khmerShortDay(date('D',strtotime($sch['date'])));
                $month = khmerMonth(date('M',strtotime($sch['date'])));
                $table .='<td style="text-align: center;" class="col_'.'0'.'">';
                $table .=date(''.$day.'. j '.$month .' Y',strtotime($sch['date']));
            }else{
                $table .= '<td style="text-align: center;" class="col_'.'0'.'">';
                $table .= date('d-M-Y',strtotime($sch['date']));
            }
            $table .='<input type="hidden" name="repayment_date[]" value="'.$sch['date'].'" /></td>';
            //days
           // $table .= '<td style="text-align: center;">'.$sch['day'];
            $table .='<input type="hidden" name="total_d[]" value="'.$sch['day'].'" />';
           // $table .= '</td>';
            //interest
            //$table .= '<td style="text-align: right;">'.number_format($sch['int'],2);
            //$table .='<span class="can-edit"></span>';
            $table .='<input type="hidden" name="repayment_interest[]" value="'.$sch['int'].'" />';
            //$table .= '</td>';
            //principal
            $table .= '<td style="text-align: right;">'.number_format($balance_loan,2);
            $table .= '<td style="text-align: right;">'.number_format($sch['prin'],2);
            $balance_loan = $balance_loan - $sch['prin'];
            $table .='<span class="can-edit"></span>';
            $table .='<input type="hidden" name="repayment_principal[]" value="'.$sch['prin'].'" />';
            $table .= '</td>';
            //fee
            //$table .= '<td style="text-align: right;">'.number_format($sch['fee'],2);
            //$table .='<span class="can-edit"></span>';
            $table .='<input type="hidden" name="repayment_fee[]" value="'.$sch['fee'].'" />';
            //$table .= '</td>';
            //other fee
            //$table .= '<td style="text-align: right;">'.number_format($sch['other_fee'],2);
            //$table .='<span class="can-edit"></span>';
            $table .='<input type="hidden" name="repayment_other_fee[]" value="'.$sch['other_fee'].'" />';
            //$table .= '</td>';

            //$table .= '<td style="text-align: right;">'.number_format($sch['monthly'],2).'</td>';
            //$table .= '<td style="text-align: right;">'.number_format($sch['balance'],2).'</td>';
            $table .= '<td style="text-align: right;">'.number_format($balance_loan,2).'</td>';
             $table .= '<td class="td-schedule-hide" hidden = "true">'.$intraday_rate.'</td>';
             $table .= '<td style="text-align: center;">'.$desc.'</td>';

            $table .= '<td class="td-schedule-hide" style="text-align: center;" class="col_'.'5'.'">';
            $table .='<input type="hidden" name="balance[]" value="'.$sch['balance'].'" />';
            $table .='<input type="hidden" name="repayment_intraday_rate[]" value="'.$sch['intra_rate'].'" />';
            $table .='</td>';

            $table .= '</tr>';
            $no++;
    }
    $table .='<table class="table table-bordered tbrepayment_sch" >';
    $table .= '<thead>';
    $table .= '<tr style="text-align: center;">';
        $table .= '<th style="text-align: center;"colspan="9">Installment Plan</th>';
    $table .= '</tr>';
    $table .= '<tr style="text-align: center;">';
    $table .= '<th style="text-align: center;">#</th>';
    $table .= '<th style="text-align: center;">'.trans('loan.l_repayment_date').'</th>';
    $table .= '<th style="text-align: center;">'.trans('loan.l_day').'</th>';
    $table .= '<th style="text-align: center;">'.trans('report.rpt_interest').'</th>';
    $table .= '<th style="text-align: center;">'.trans('report.rpt_principal').'</th>';
    $table .= '<th style="text-align: center;">'.trans('report.rpt_fee').'</th>';
    $table .= '<th style="text-align: center;">'.trans('report.other_fee').'</th>';
    $table .= '<th style="text-align: center;">'.trans('loan.l_monthly_pay').'</th>';
    $table .= '<th style="text-align: center;">'.trans('report.rpt_principal_balance').'</th>';
    // $table .= '<th class="td-schedule-hide" hidden = "true">'.trans('report.rpt_others').'</th>';
    $table .= '</tr>';
    $table .= '</thead>';

    $app_locale = app()->getLocale();
    $total_fee1 = 0;
    $no = 1;
    foreach($tb_sch as $key=>$sch){
    $total_fee1 += $sch['fee'];
        if($sch['date']=='undefined') continue;
        if($no == count($tb_sch)-1) $no_cl = ' last_row';
        $table .= '<tr class="row_'.$no.$no_cl.'">';
            $table .= '<td style="text-align: center;">'.$no.'</td>';
            if($app_locale == 'kh'){
                $day = khmerShortDay(date('D',strtotime($sch['date'])));
                $month = khmerMonth(date('M',strtotime($sch['date'])));
                $table .='<td style="text-align: center;" class="col_'.'0'.'">';
                $table .=date(''.$day.'. j '.$month .' Y',strtotime($sch['date']));
            }else{
                $table .= '<td style="text-align: center;" class="col_'.'0'.'">';
                $table .= date('d-M-Y',strtotime($sch['date']));
            }
            $table .='<input type="hidden" name="repayment_date[]" value="'.$sch['date'].'" /></td>';
            //days
            $table .= '<td style="text-align: center;">'.$sch['day'];
            $table .='<input type="hidden" name="total_d[]" value="'.$sch['day'].'" />';
            $table .= '</td>';
            //interest
            $table .= '<td style="text-align: right;">'.number_format($sch['int'],2);
            $table .='<span class="can-edit"></span>';
            $table .='<input type="hidden" name="repayment_interest[]" value="'.$sch['int'].'" />';
            $table .= '</td>';
            //principal
            $table .= '<td style="text-align: right;">'.number_format($sch['prin'],2);
            $table .='<span class="can-edit"></span>';
            $table .='<input type="hidden" name="repayment_principal[]" value="'.$sch['prin'].'" />';
            $table .= '</td>';
            //fee
            $table .= '<td style="text-align: right;">'.number_format($sch['fee'],2);
            $table .='<span class="can-edit"></span>';
            $table .='<input type="hidden" name="repayment_fee[]" value="'.$sch['fee'].'" />';
            $table .= '</td>';
            //other fee
            $table .= '<td style="text-align: right;">'.number_format($sch['other_fee'],2);
            $table .='<span class="can-edit"></span>';
            $table .='<input type="hidden" name="repayment_other_fee[]" value="'.$sch['other_fee'].'" />';
            $table .= '</td>';

            $table .= '<td style="text-align: right;">'.number_format($sch['monthly'],2).'</td>';
            $table .= '<td style="text-align: right;">'.number_format($sch['balance'],2).'</td>';
            // $table .= '<td class="td-schedule-hide" hidden = "true">'.$intraday_rate.'</td>';

             $table .= '<td class="td-schedule-hide" style="text-align: center;" class="col_'.'5'.'">';
            $table .='<input type="hidden" name="balance[]" value="'.$sch['balance'].'" />';
            $table .='<input type="hidden" name="repayment_intraday_rate[]" value="'.$sch['intra_rate'].'" />';
            $table .='</td>';

            $table .= '</tr>';
            $no++;
    }
    $table .='<input type="hidden" name="annual_yield" value="'.$annual_yield.'" />';
    // Total row
    $table .= '<tr style="border-top: double; font-weight: bold;">';
    $table .= '<td colspan="2" style="text-align: right;">'.trans('report.rpt_total').'</td>';
    $table .= '<td style="text-align: center;">' .$total_days.'</td>';
    $table .='<td style="text-align: right;">'.number_format($total_interest, 2, '.', ',').'</td>';
    $table .='<td style="text-align: right;">'.number_format($total_principal, 2, '.', ',').'</td>';
    $table .='<td style="text-align: right;">'.number_format($total_fee1, 2, '.', ',').'</td>';
    $table .='<td style="text-align: right;">'.number_format($total_other_fee, 2, '.', ',').'</td>';
    $table .= '<td style="text-align: right";>'.number_format($total_monthly, 2, '.', ',').'</td>';
    $table .= '<td style="text-align: right";></td>';
    // $table .= '<td class="td-schedule-hide" hidden = "true"></td>';
    $table .= '</tr>';
    $table .= '</tbody>';
}
echo $table;
?>
