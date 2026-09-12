<?php
$type = $l_repayment_type;
$holiday = [];
if(!empty($holidays)){
    $holiday = $holidays;
}
$repayment_array = LoanCalculate::monthly_loan_schedule($l_repayment_type,$l_start_date,$l_tenure,$l_amount,$l_rate,$l_balloon_num, $l_balloon_month, $l_monthly_pay, $l_bal_amount_array,$custom_flag,$l_days_of_month,$holiday_flag, $holiday)[0];
$table = '';
if(!empty($repayment_array))
{
    $no = 0;
    $total_days = 0;
    $total_interest = 0;
    $total_principal = 0;
    $total_monthly = 0;
    $total_principal_bal = 0;
    $app_locale = app()->getLocale();

    if($type == 3 ||  $type == 4 || $type == 5){
        $isBalloon = true;
    }else{
        $isBalloon = false;
    }
    for($i = 1; $i < count($repayment_array); $i++){
        $total_days = $total_days +  $repayment_array[$i][1];
        $total_interest += $repayment_array[$i][2];
        $total_principal += $repayment_array[$i][3];
        $total_monthly += $repayment_array[$i][4];
        $total_principal_bal += $repayment_array[$i][5];
    }
    // Average Balance
    $average_bal = $total_principal_bal / $l_tenure;
    $av_amount = $average_bal * 12;
    $av_amount = ($av_amount > 0) ? $av_amount : 1;
    // Annual Yield
    $annual_yield = 100*($total_interest / $l_tenure / $average_bal * 12);

    // Overview
    $table .= '<table  class="table table-bordered tbrepayment">';
    $table .= '<thead>';
    $table .= '</thead>';
    $table .= '<tbody>';
    $table .= '<tr>';
    $table .= '<td>'.trans('customer.cus_customer_name').'</td><td colspan="2" style="text-align: center;">'.$l_client_name.'</td>';
    $table .= '<td>'.trans('report.rpt_loan_amount').'</td><td style="text-align: center;">$ '.number_format($l_amount, 2, '.', ',').'</td>';
    if($app_locale == 'kh'){
        $day = khmerShortDay(date('D',strtotime($l_start_date)));
        $month = khmerMonth(date('M',strtotime($l_start_date)));
        $table .='<td>'.trans('loan.l_disburse_date').'</td><td style="text-align: center;">'. date(''.$day.'. j '.$month .' Y',strtotime($l_start_date)) .'</td>';
    }else{
        $table .= '<td>'.trans('loan.l_disburse_date').'</td><td style="text-align: center;">'.date("d-M-Y", strtotime($l_start_date)).'</td>';
    }
    $table .= '</tr>';

    $table .= '<tr>';
    $table .='<td rowspan="3">'.trans('report.rpt_tenure').'</td>';
    $table .='<td>#'.trans('multiple.m_month').'</td><td style="text-align: center;">'.$l_tenure.'</td >';

    $l_amount = floatval($l_amount);
    $l_monthly_pay = floatval($l_monthly_pay);
    $l_rate = floatval($l_rate);
    $l_tenure = floatval($l_tenure);
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


    $table .='<td class="no-border">'.trans('loan.l_balloon_average').'</td><td class="no-border" style="text-align: center;">'.(($isBalloon)?('$ '.number_format($balloon_amount, 2, '.', ',')):"-").'</td>';
    $table .='<td rowspan="3">'.trans('report.rpt_total_interest').'</td><td  rowspan="3" style="text-align: center;">'.'$ '.number_format($total_interest, 2, '.', ',').'</td>';
    $table .= '</tr>';

    $table .= '<tr>';
    $table .='<td>#'.trans('loan.l_balloon_month').'</td><td style="text-align: center;">'.$l_balloon_num.'</td>';
    $table .='<td class="none-border">'.trans('loan.l_total_payment').'</td><td class="none-border" style="text-align: center;">'.'$ '.number_format($total_monthly, 2, '.', ',').'</td>';
    $table .= '</tr>';

    $table .= '<tr>';
    $table .='<td>#'.trans('loan.l_sign_regular').'</td><td style="text-align: center;">'.($l_tenure - $l_balloon_num).'</td>';
    $table .='<td  class="border">'.trans('loan.l_regular_average').'</td><td class="border" style="text-align: center;">'.'$ '.number_format(($l_amount*$l_rate/100), 2, '.', ',').'</td>';
    $table .= '</tr>';

    $table .= '<tr>';
    $table .='<td colspan="2">'.trans('loan.l_interest_rate_pm').'</td><td class="custom_display" style="text-align: center;">'.number_format($l_rate, 2, '.', ',').'%'.'</td><td class="custom_display remove_class"><span>'.trans('loan.l_pa_rate').'</span></td><td class="custom_display remove_class" style="text-align: center;"><span>'.number_format(($l_rate*12), 2, '.', ',').'%'.'</span></td><td class="custom_display remove_class"><span>'.trans('loan.l_annual_yield').'</span></td><td class="custom_display remove_class" style="text-align: center;"><span>'.number_format($annual_yield, 2, '.', ',').'%'.'</span></td>';
    $table .= '</tr>';

    $table .= '</tbody>';
    $table .= '</table>';


    $table .='<table class="table table-bordered tbrepayment_sch" >';
    $table .= '<thead>';
    $table .= '<tr style="text-align: center;">';
    $table .= '<th style="text-align: center;">#</th>';
    $table .= '<th style="text-align: center;">'.trans('loan.l_repayment_date').'</th>';
    $table .= '<th style="text-align: center;">'.trans('loan.l_day').'</th>';
    $table .= '<th style="text-align: center;">'.trans('report.rpt_interest').'</th>';
    $table .= '<th style="text-align: center;">'.trans('report.rpt_principal').'</th>';
    $table .= '<th style="text-align: center;">'.trans('loan.l_monthly_pay').'</th>';
    $table .= '<th style="text-align: center;">'.trans('report.rpt_principal_balance').'</th>';
    $table .= '<th class="td-schedule-hide">'.trans('report.rpt_others').'</th>';
    $table .= '</tr>';
    $table .= '</thead>';

    $no = 0;
    $app_locale = app()->getLocale();
    foreach($repayment_array as $value){
    	if($no == count($repayment_array)-1) $no_cl = ' last_row';
        $table .= '<tr class="row_'.$no.$no_cl.'">';
            $table .= '<td style="text-align: center;">'.$no.'</td>';
            for($i =0 ;$i<count($value) ;$i++){
                if($i == 6){
                    continue;
                }
                
                if($i >= 2 && is_numeric($value[$i])){
                	if( $no == count($repayment_array)-1 && $i == 5){
                		if(0 == round($value[$i],2) ){
                			$table .= '<td style="text-align: right;" class="col_'.$i.'">'."0.00".'</td>';
                		}else{
                			$table .= '<td style="text-align: right"; bgcolor="red" class="col_'.$i.'"><span class="default-val">'.number_format($value[$i], 2, '.', ',').'</span>';
                			$table .='<span class="can-edit"></span>';
                			$table .='</td>';
                		}
                	}else{
                		$table .= '<td  style="text-align: right;" class="col_'.$i.'"><span class="default-val">'.number_format($value[$i], 2, '.', ',').'</span>';
                		$table .= '<span class="can-edit"></span>';
                		$table .='</td>';
                	}
                	
                    
                    
//                     else{ //other columns
                    	$intraday = calc_rate($l_amount, $value[5], $l_rate, $type);
                    	$_rate = $intraday*$value[1];
                    	$_monthly_pay = $value[3] + $_rate;
                    	$_principal = $value[3];
                    	
                    	//check for int monthly pay
                    	if(!is_int($_monthly_pay)){
                    		$ex = explode('.', $_monthly_pay);
                    		$_monthly_pay = $ex[0];
                    		$_principal = $_monthly_pay - $_rate;
                    	}
                    	
                    	if($i==2){
                    		$num0 = $_rate;
                    	}
                    	elseif($i==3) {
                    		$num0 = $value[3] = $_principal;
                    	}
                    	elseif($i==4) {
                    		$num0 = $_monthly_pay;
                    	}else{
                    		$num0 = $value[i];
                    	}
//                     	$num0 = $intraday;
//                         $table .= '<td  style="text-align: right;" class="col_'.$i.'"><span class="default-val">'.number_format($value[i], 2, '.', ',').'</span>';
//                         $table .= '<span class="can-edit"></span>';
//                         $table .='</td>';
//                     }
                }
                
                //$app_locale
                elseif($i == 0){
                    if($app_locale == 'kh'){
                        $day = khmerShortDay(date('D',strtotime($value[$i])));
                        $month = khmerMonth(date('M',strtotime($value[$i])));
                        $table .='<td style="text-align: center;" class="col_'.$i.'">';
                        if($is_disburse==1) $table .='<input type="hidden" name="repayment_date[]" value="'.$value[$i].'" />';
                        $table .=date(''.$day.'. j '.$month .' Y',strtotime($value[$i])) .'</td>';
                    }else{
                        $table .= '<td style="text-align: center;" class="col_'.$i.'">';
                        if($is_disburse==1) $table .='<input type="hidden" name="repayment_date[]" value="'.$value[$i].'" />';
                        $table .= date('d-M-Y',strtotime($value[$i])).'</td>';
                    }
                }else{
                    $table .= '<td style="text-align: center;" class="col_'.$i.'">'.$value[$i].'</td>';
                }
            }
            $table .= '<td class="td-schedule-hide"></td>';
        $table .= '</tr>';
        $no++;
    }
    //dd($table);
    // Total row
    $table .= '<tr style="border-top: double; font-weight: bold;">';
    $table .= '<td colspan="2" style="text-align: right;">'.trans('report.rpt_total').'</td>';
    $table .= '<td style="text-align: center;">' .$total_days.'</td>';
    $table .='<td style="text-align: right;">'.number_format($total_interest, 2, '.', ',').'</td>';
    $table .='<td style="text-align: right;">'.number_format($total_principal, 2, '.', ',').'</td>';
    $table .= '<td style="text-align: right";>'.number_format($total_monthly, 2, '.', ',').'</td>';
    $table .= '<td style="text-align: right";></td>';
    $table .= '<td class="td-schedule-hide"></td>';
    $table .= '</tr>';
    $table .= '</tbody>';
}
echo $table;
?>