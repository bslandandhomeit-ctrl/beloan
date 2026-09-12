<h4 class="sch_title">{{ trans('loan.l_repayment_schedule') }}</h4>
    <?php
        $repayment_array = [];
        $type = $repayment_type;
        if(!empty($repayment_schedule) && count($repayment_schedule) > 0){
            $schedule = LoanCalculate::loan_schedule($repayment_schedule,$disbursement_date?$disbursement_date:$start_date)[0];
            $repayment_schedule = $repayment_schedule->toArray();
            $index = 0;
            for($i = 0; $i< count($schedule) ; $i++){
                $repay = $schedule[$i];
                $monthly  = $repay[2] + $repay[3];
                $amount = 0;
                $id = 0;
                if($i == 0){
                    $amount = $loan_amount;
                }else{
                    $amount =  $repayment_array[$index][5] - $repay[3];
                    $id = $repayment_schedule[$i - 1]["id"];
                }
                $index = $id;
                $repayment_array[$id] = [$repay[0],$repay[1],$repay[2],$repay[3],$monthly,$amount];
            }
        }
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
            foreach($repayment_array as $k => $rps){
                if($k > 0){
                    $total_days = $total_days +  $rps[1];
                    $total_interest +=$rps[2];
                    $total_principal += $rps[3];
                    $total_monthly += $rps[4];
                    $total_principal_bal += $rps[5];
                }
            }
            // Average Balance
            $average_bal = $total_principal_bal / $loan_duration;
            // Annual Yield
            $annual_yield = 100*($total_interest / $loan_duration / $average_bal * 12);

            // Overview

            $table .= '<table  class="table table-bordered tbrepayment">';
            $table .= '<thead>';
            $table .= '</thead>';
            $table .= '<tbody>';
            $table .= '<tr>';
            $table .= '<td>'.trans('customer.cus_customer_name').'</td><td colspan="2" style="text-align: center;">'.$client_name.'</td>';
            $table .= '<td>'.trans('report.rpt_loan_amount').'</td><td style="text-align: center;">$ '.number_format($loan_amount, 2, '.', ',').'</td>';
                if($app_locale == 'kh'){
                    if("" != $disbursement_date){
                        $day = khmerShortDay(date('D',strtotime($disbursement_date)));
                        $month = khmerMonth(date('M',strtotime($disbursement_date)));
                        $table .='<td>'.trans('loan.l_disburse_date').'</td><td style="text-align: center;">'. date(''.$day.'. j '.$month .' Y',strtotime($disbursement_date)) .'</td>';
                    }else{
                        $day = khmerShortDay(date('D',strtotime($start_date)));
                        $month = khmerMonth(date('M',strtotime($start_date)));
                        $table .='<td>'.trans('loan.l_disburse_date').'</td><td style="text-align: center;">'. date(''.$day.'. j '.$month .' Y',strtotime($start_date)) .'</td>';
                    }
                }else{
                    $table .='<td>'.trans('loan.l_disburse_date').'</td><td style="text-align: center;">'. (!empty($disbursement_date) ? date("d-M-Y",strtotime($disbursement_date)) : date("d-M-Y",strtotime($start_date))) .'</td>';
                }
            $table .= '</tr>';
            $table .= '<tr>';
            $table .='<td rowspan="3" style="vertical-align: middle; text-align: center;">'.trans('report.rpt_tenure').'</td>';
            $table .='<td>#'.trans('multiple.m_month').'</td><td style="text-align: center;">'.$loan_duration.'</td >';

            $loan_amount = floatval($loan_amount);
            $monthly_payment = floatval($monthly_payment);
            $interest_rate = floatval($interest_rate);
            $loan_duration = floatval($loan_duration);
            if($isBalloon){
                $num_balloon = floatval($num_balloon);
                $intraday_rate = ($loan_amount * ($interest_rate/100) * 12 / 360);
                $const_interest = $intraday_rate * 30;
                $regular_prin_permonth = $monthly_payment - $const_interest;
                $regular_prin_sum = ($loan_duration - $num_balloon) * $regular_prin_permonth;
                $balloon_amnt = ($loan_amount - $regular_prin_sum)/$num_balloon;
                if($monthly_payment == 0){
                    $balloon_amnt = $loan_amount / $num_balloon;
                }
            }

            $table .='<td class="no-border">'.trans('loan.l_balloon_average').'</td><td class="no-border" style="text-align: center;">'.(($isBalloon)?('$ '.number_format($balloon_amnt, 2, '.', ',')):"-").'</td>';
            $table .='<td rowspan="3" style="vertical-align: middle">'.trans('report.rpt_total_interest').'</td><td  rowspan="3" style="vertical-align: middle; text-align: center;">'.'$ '.number_format($total_interest, 2, '.', ',').'</td>';
            $table .= '</tr>';

            $table .= '<tr>';
            $table .='<td>#'.trans('loan.l_balloon_month').'</td><td  style="text-align: center;">'.$num_balloon.'</td>';
            $table .='<td class="none-border">'.trans('loan.l_total_payment').'</td><td class="none-border" style="text-align: center;">'.'$ '.number_format($total_monthly, 2, '.', ',').'</td>';
            $table .= '</tr>';

            $table .= '<tr>';
            $table .='<td>#'.trans('loan.l_sign_regular').'</td><td  style="text-align: center;">'.($loan_duration - $num_balloon).'</td>';
            $table .='<td  class="border">'.trans('loan.l_regular_average').'</td><td class="border" style="text-align: center;">'.'$ '.number_format(($loan_amount*$interest_rate/100), 2, '.', ',').'</td>';
            $table .= '</tr>';

            $table .= '<tr>';
            $table .='<td colspan="2">'.trans('loan.l_interest_rate_pm').'</td><td class="custom_display" style="text-align: center;">'.number_format($interest_rate, 2, '.', ',').'%'.'</td><td class="custom_display remove_class"><span>'.trans('loan.l_pa_rate').'</span></td><td class="custom_display remove_class"  style="text-align: center;"><span>'.number_format(($interest_rate*12), 2, '.', ',').'%'.'</span></td><td class="custom_display remove_class"><span>'.trans('loan.l_annual_yield').'</span></td><td class="custom_display remove_class"  style="text-align: center;"><span>'.number_format($annual_yield, 2, '.', ',').'%'.'</span></td>';
            $table .= '</tr>';

            $table .= '</tbody>';
            $table .= '</table>';

            echo $table;
        }
    ?>
    <table  class="table table-bordered table-striped table-condensed tbrepayment_sch">
        <thead>
            <tr>
                <th style="text-align: center;">#</th>
                <th style="text-align: center;">{{ trans('loan.l_repayment_date') }}</th>
                <th style="text-align: center;">{{ trans('loan.l_day') }}</th>
                <th style="text-align: center;">{{ trans('report.rpt_interest') }}</th>
                <th style="text-align: center;">{{ trans('report.rpt_principal') }}</th>
                <th style="text-align: center;">{{ trans('loan.l_monthly_pay') }}</th>
                <th style="text-align: center;">{{ trans('report.rpt_principal_balance') }}</th>
                <th class="invisible_edit"></th>
                <th class="td-schedule-hide">{{trans('report.rpt_others')}}</th>
            </tr>
        </thead>
        <tbody id="tbody">
            @include('partials.tb_repayment_schedule',compact('repayment_array'))
        </tbody>
    </table>
    <div class="signature_detail">
        <div class="left_content_detail">
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