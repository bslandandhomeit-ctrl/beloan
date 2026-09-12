<?php
use App\Http\Controllers\FinancialClass\FinancialClassController;
use App\Models\RepaymentSchedule;
class LoanCalculate
{

    public static function equal_installment($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag=0,$holidays=[],
                                             $principal_input=null, $disburse_on=null, $round=null, $reschedule_edit=false,
                                             $c_principal=null, $frequency=null, $is_disburse=null, $loan_id = null,$change_digit = 0,$sche_type = 'loan')
    {
        $repayment_val = [];
        if($is_disburse == '1'){
            $loan = \App\Models\Loan::select('id')->where('id', intval($loan_id))->with('schedule')->first();
            $bal = $l_amount;
            $schedule_data = RepaymentSchedule::where('loan_id',intval($loan_id))->where('type',(string)$sche_type)->get();
            foreach($schedule_data as $sch) {
                $bal = $bal - $sch->principal;
                array_push($repayment_val, [$sch->schedule_date, $sch->date_num, $sch->interest, $sch->principal, $sch->fee, $sch->interest + $sch->principal + $sch->fee, $bal, $sch->intraday_rate]);
            }
        }else {
            $repayment_val = [
                [$disburse_on ? $disburse_on : $l_start_date, '-', '-', '-', '-', $l_amount]
            ];
            $sum_prin = 0.0;
            $diff_sum = 0.0;

            if ($reschedule_edit) {
                $l_tenure = 2;
                $l_start_date = $reschedule_edit['start_date'];
                $repayment_val = [[$reschedule_edit['prev_date'], '-', '-', '-', '-', $l_amount]];
            }

            for ($i = 1; $i <= $l_tenure; $i++) {
                if ($reschedule_edit && $i == $l_tenure && $reschedule_edit['next_date']) {
                    $date = add_month(date('Y-m-d', strtotime($reschedule_edit['next_date'])), $i - 2);
                } elseif ($disburse_on || $reschedule_edit) {
                    $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1, $frequency);
                } else {
                    $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i, $frequency);
                }
                $re_date = $date->format('Y-m-d');
                if ($holiday_flag == 1) {
                    $re_date = date_except($re_date, $holidays);
                }
//            else{
//                $re_date = date_except($re_date);
//            }
                $days = date_dif($repayment_val[$i - 1][0], $re_date, 1, false);
                $intradayRate = calc_rate($l_amount, $repayment_val[$i - 1][5], $l_rate, 1); //(($repayment_val[$i - 1][5] * $l_rate * 12) / 360) / 100;
                //$interest = round($intradayRate * $days, ROUND_DIGIT);
                $interest = round($intradayRate * $days, $change_digit, PHP_ROUND_HALF_UP);
                if ($principal_input) {
                    $exp = explode(',', $principal_input);
                    $principal = $exp[$i - 1];
                } else {
                    //$principal = round($l_amount / $l_tenure, ROUND_DIGIT);
                    $principal = round($l_amount / $l_tenure, $change_digit, PHP_ROUND_HALF_UP);
                }

                if ($i == $l_tenure) {
                    $principal = round($l_amount - $sum_prin, $change_digit);
                }

                if ($c_principal != null) {
                    $principal = $c_principal;
                }
                $monthly_pay = round($interest + $principal, $change_digit);
                $monthly_round = round_num($monthly_pay, $round);
                $diff = $monthly_round - $monthly_pay;
                if ($i != $l_tenure) {
                    if ($c_principal == null) $principal += $diff;
                    $diff_sum += $diff;
                    $monthly_pay = $monthly_round;
                }
                $principal_bal = $repayment_val[$i - 1][5] - $principal;
                $sum_prin += $principal;
                array_push($repayment_val, [$re_date, $days, $interest, $principal, $monthly_pay, $principal_bal, $intradayRate]);
            }
        }
        return $repayment_val;
    }

    public static function flat_equal_installment($l_start_date, $l_tenure, $l_amount, $l_rate, $l_days_of_month, $holiday_flag=0,$holidays=[], $principal_input=null, $disburse_on=null, $round=null)
    {
        $repayment_val = [
            [$disburse_on?$disburse_on:$l_start_date, '-', '-', '-', '-', $l_amount]
        ];
        $sum_prin = 0.0;
        $diff_sum = 0.0;
        $intradayRate = calc_rate($l_amount, 0, $l_rate, 2); //(($l_amount * $l_rate * 12) / 360) / 100;
        $interest_fixed = $intradayRate * 30;

        for ($i = 1; $i <= $l_tenure; $i++) {
            if(!is_null($disburse_on)){
                $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1);
            }else {
                $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i);//date('Y-m-d',strtotime("+1 months",strtotime($repayment_val[$i-1][0])));
            }
            $re_date = $date->format('Y-m-d');
            if($holiday_flag == 1){
                // $re_date = date_except($re_date, $holidays);
                $new_holiday = array_flip($holidays);
                if(isset($new_holiday[$re_date])){
                    $re_date = date_except($re_date, $holidays);
                }
            }else{
                // $re_date = date_except($re_date);
            }
            $days = date_dif($repayment_val[$i - 1][0], $re_date, 1, false);
            $interest = ($l_days_of_month == "Fixed" ? $interest_fixed : ($intradayRate * $days));

            if($principal_input){
                $exp = explode(',', $principal_input);
                $repayment_val[$i][3] = $exp[$i-1];
//              $repayment_val[$i][3] = round($exp[$i-1], 2);
            }else{
                $principal = round($l_amount / $l_tenure, 2);
            }

            if($i == $l_tenure){
                $principal = round($l_amount - $sum_prin, 2);
            }

            $monthly_pay = $interest + $principal;
            $monthly_round = round_num($monthly_pay, $round);
            $diff = $monthly_round - $monthly_pay;
            if($i != $l_tenure) {
                $principal += $diff;
                $diff_sum += $diff;
                $monthly_pay = $monthly_round;
            }
            $principal_bal = $repayment_val[$i - 1][5] - $principal;
            $sum_prin += $principal;
            array_push($repayment_val, [$re_date, $days, $interest, $principal, $monthly_pay, $principal_bal, $intradayRate]);
        }
        return $repayment_val;
    }


    public static function semi_balloon($l_start_date, $l_tenure, $l_amount, $l_rate, $l_balloon_num, $l_balloon_month,
                                        $l_monthly_pay, $l_bal_amount_array, $custom_flag, $holiday_flag=0,$holidays=[],
                                        $principal_input=null, $disburse_on=null, $round=null, $reschedule_edit=false,
                                        $frequency = null, $is_disburse, $loan_id,$admin_fee,
                                        $admin_fee_opt, $maintain_fee, $maintain_fee_opt, $change_digit = 0)
    {
        $repayment_val = [];
        if($is_disburse == '1'){
            $loan = \App\Models\Loan::select('id')->where('id', intval($loan_id))->with('schedule')->first();
            $bal = $l_amount;
            foreach($loan->schedule as $sch) {
                $bal = $bal - $sch->principal;
                array_push($repayment_val, [$sch->schedule_date, $sch->date_num, $sch->interest, $sch->principal, $sch->fee, $sch->interest + $sch->principal + $sch->fee, $bal, $sch->intraday_rate]);
            }
        }else {
            $digit_num = ROUND_DIGIT;
//        $const_prin= round($l_monthly_pay, $digit_num);
            $const_prin = round($l_monthly_pay, $change_digit, PHP_ROUND_HALF_UP);
            $total_prin = 0.0;
            $repayment_val = [
                [$disburse_on ? $disburse_on : $l_start_date, '-', '-', '-', '-', $l_amount]
            ];
            $balloon_month = explode(",", $l_balloon_month);
            $balloon_array = [];
            if ($custom_flag == 1 || $custom_flag == 'on') {
                $balloon_value = explode(",", $l_bal_amount_array);
                if (is_array($balloon_array)) {
                    for ($i = 0; $i < count($balloon_month); $i++) {
                        $balloon_array[$balloon_month[$i]] = $balloon_value[$i];
                    }
                }
            } else {
                $balloon_amount = $l_amount / $l_balloon_num;
                for ($i = 0; $i < count($balloon_month); $i++) {
                    $balloon_array[$balloon_month[$i]] = $balloon_amount;
                }
            }

            if ($reschedule_edit) {
                $l_tenure = 2;
                $l_start_date = $reschedule_edit['start_date'];
                $repayment_val = [[$reschedule_edit['prev_date'], '-', '-', '-', '-', '-', $l_amount, '-']];
            }

            for ($i = 1; $i <= $l_tenure; $i++) {
                // if(!is_null($disburse_on)){
                //     $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1);
                // }else {
                //     $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i);//date('Y-m-d',strtotime("+1 months",strtotime($repayment_val[$i-1][0])));
                // }

                if ($reschedule_edit && $i == $l_tenure && $reschedule_edit['next_date']) {
                    $date = add_month(date('Y-m-d', strtotime($reschedule_edit['next_date'])), $i - 2, $frequency);
                } elseif ($disburse_on || $reschedule_edit) {
                    $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1, $frequency);
                } else {
                    $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i, $frequency);
                }
                $re_date = $date->format('Y-m-d');
                if ($holiday_flag == 1) {
                    // $re_date = date_except($re_date, $holidays);
                    $new_holiday = array_flip($holidays);
                    if(isset($new_holiday[$re_date])){
                        $re_date = date_except($re_date, $holidays);
                    }
                }
//            else{
//                $re_date = date_except($re_date);
//            }
                $repayment_val[$i][0] = $re_date;
                $days = date_dif($repayment_val[$i - 1][0], $re_date, 1, false);
                $intradayRate = calc_rate($l_amount, $repayment_val[$i - 1][5], $l_rate, 3); //(($repayment_val[$i - 1][5] * $l_rate * 12) / 360) / 100;
                //$interest_permonth = round($intradayRate * $days, $digit_num);
                $interest_permonth = round($intradayRate * $days, $change_digit, PHP_ROUND_HALF_UP);
                $repayment_val[$i][1] = $days;
                $repayment_val[$i][2] = $interest_permonth;

                if ($principal_input) {
                    $exp = explode(',', $principal_input);
                    $repayment_val[$i][3] = $exp[$i - 1];
//              $repayment_val[$i][3] = round($exp[$i-1], 2);
                } else {
                    $repayment_val[$i][3] = $const_prin;
                    if (array_key_exists($i, $balloon_array)) {
                        $repayment_val[$i][3] = round($balloon_array[$i], $change_digit);
                    }
                }

                if ($i != $l_tenure) {
                    $total_prin += $repayment_val[$i][3];
                } else {
                    $repayment_val[$i][3] = $l_amount - $total_prin;
                }
                $repayment_val[$i][4] = $repayment_val[$i][2] + $repayment_val[$i][3];
                $repayment_val[$i][5] = $repayment_val[$i - 1][5] - $repayment_val[$i][3];
                $repayment_val[$i][6] = $intradayRate;
            }
        }
        return $repayment_val;
    }


    public static function flat_semi_balloon_fixed_monthly_payment($l_start_date, $l_tenure, $l_amount, $l_rate, $l_balloon_num, $l_balloon_month, $l_monthly_pay, $l_bal_amount_array, $custom_flag, $l_days_of_month, $holiday_flag=0,$holidays=[], $principal_input=null, $disburse_on=null, $round=null)
    {
        $flag = 0;
        $diff_sum = 0.0;
        $digit_num = 2;
        $l_monthly_pay = round($l_monthly_pay, $digit_num);
        $repayment_val = [
            [$disburse_on?$disburse_on:$l_start_date, '-', '-', '-', '-', $l_amount]
        ];
        $intradayRate = calc_rate($l_amount, 0, $l_rate, 4); //(($l_amount * $l_rate * 12) / 360) / 100;
        $prin_sum = 0.0;
        $balloon_month = explode(",", $l_balloon_month);
        $balloon_array = [];
        if ($custom_flag == 1 || $custom_flag == 'on') {
            $balloon_value = explode(",", $l_bal_amount_array);
            if (is_array($balloon_array)) {
                for ($i = 0; $i < count($balloon_month); $i++) {
                    $balloon_array[$balloon_month[$i]] = $balloon_value[$i];
                }
            }
        } else {
            $balloon_amount = $l_amount / $l_balloon_num;
            for ($i = 0; $i < count($balloon_month); $i++) {
                $balloon_array[$balloon_month[$i]] = $balloon_amount;
            }
        }

        for ($i = 1; $i <= $l_tenure; $i++) {
            if(!is_null($disburse_on)){
                $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1);
            }else {
                $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i);//date('Y-m-d',strtotime("+1 months",strtotime($repayment_val[$i-1][0])));
            }
            $re_date = $date->format('Y-m-d');
            if($holiday_flag == 1){
                // $re_date = date_except($re_date, $holidays);
                $new_holiday = array_flip($holidays);
                if(isset($new_holiday[$re_date])){
                    $re_date = date_except($re_date, $holidays);
                }
            }else{
                // $re_date = date_except($re_date);
            }
            $repayment_val[$i][0] = $re_date;
            $days = date_dif($repayment_val[$i - 1][0], $re_date, 1, false);
            $interest_permonth = ($l_days_of_month == "Fixed" ? round($intradayRate * 30, $digit_num) : round($intradayRate * $days,$digit_num));
            $repayment_val[$i][1] = $days;
            $principal_permonth = round($l_amount / $l_tenure, $digit_num);
            if (($l_monthly_pay > $interest_permonth) && ($l_monthly_pay < $interest_permonth + $principal_permonth)) {
                $flag = 1;
                $repayment_val[$i][2] = $interest_permonth;
                $repayment_val[$i][3] = $l_monthly_pay - $interest_permonth;
            } else {
                $repayment_val[$i][2] = $interest_permonth;
                $repayment_val[$i][3] = 0;
            }

            if (array_key_exists($i, $balloon_array)) {
                if($i != $l_tenure){
                    $repayment_val[$i][3] += round($balloon_array[$i], $digit_num);
                    if($flag == 1) {
                        $monthly_tmp = $repayment_val[$i][3] + $repayment_val[$i][2];
                        $monthly_round = round_num($monthly_tmp, $round);
                        $diff_sum += $monthly_round - $monthly_tmp;
                        $repayment_val[$i][3] = $monthly_round - $repayment_val[$i][2];
                    }
                }
            }

            if($principal_input){
                $exp = explode(',', $principal_input);
                $repayment_val[$i][3] = $exp[$i-1];
//              $repayment_val[$i][3] = round($exp[$i-1], 2);
            }

            if($i == $l_tenure){
                $repayment_val[$i][3] = $l_amount - $prin_sum;
            }else{
                $prin_sum += $repayment_val[$i][3];
            }
        }

        for ($i = 1; $i <= $l_tenure; $i++) {
            $repayment_val[$i][4] = $repayment_val[$i][2] + $repayment_val[$i][3];
            $repayment_val[$i][5] = $repayment_val[$i - 1][5] - $repayment_val[$i][3];
            $repayment_val[$i][6] = $intradayRate;
        }
        return $repayment_val;
    }

    public static function flat_semi_balloon_fixed_principal($l_start_date, $l_tenure, $l_amount, $l_rate, $l_balloon_num, $l_balloon_month, $l_monthly_pay, $l_bal_amount_array, $custom_flag, $l_days_of_month, $holiday_flag=0,$holidays=[], $principal_input=null, $disburse_on=null, $round=null)
    {
        $diff_sum = 0.0;
        $digit_num = 2;
        $const_prin = round($l_monthly_pay, $digit_num);
        $repayment_val = [
            [$disburse_on?$disburse_on:$l_start_date, '-', '-', '-', '-', $l_amount]
        ];
        $intradayRate = calc_rate($l_amount, 0, $l_rate, 5); //(($l_amount * $l_rate * 12) / 360) / 100;
        $prin_sum = 0.0;
        $balloon_month = explode(",", $l_balloon_month);
        $balloon_array = [];
        $balloon_value = explode(",", $l_bal_amount_array);
        if (is_array($balloon_array)) {
            for ($i = 0; $i < count($balloon_month); $i++) {
                $balloon_array[$balloon_month[$i]] = $balloon_value[$i];
            }
        }

        $regular_prin_permonth = $const_prin;
        for ($i = 1; $i <= $l_tenure; $i++) {
            if(!is_null($disburse_on)){
                $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1);
            }else {
                $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i);//date('Y-m-d',strtotime("+1 months",strtotime($repayment_val[$i-1][0])));
            }
            $re_date = $date->format('Y-m-d');
            if($holiday_flag == 1){
                // $re_date = date_except($re_date, $holidays);
                $new_holiday = array_flip($holidays);
                if(isset($new_holiday[$re_date])){
                    $re_date = date_except($re_date, $holidays);
                }
            }else{
                // $re_date = date_except($re_date);
            }
            $repayment_val[$i][0] = $re_date;
            $days = date_dif($repayment_val[$i - 1][0], $re_date, 1, false);
            $interest_permonth = ($l_days_of_month == "Fixed" ? round($intradayRate * 30, $digit_num) : round($intradayRate * $days, $digit_num));
            $repayment_val[$i][1] = $days;
            $repayment_val[$i][2] = $interest_permonth;
            $repayment_val[$i][3] = $const_prin;

            if (array_key_exists($i, $balloon_array)){
                $repayment_val[$i][3] = round($balloon_array[$i], $digit_num);
            }else {
                $repayment_val[$i][3] = $regular_prin_permonth;
            }

            if($principal_input){
                $exp = explode(',', $principal_input);
                $repayment_val[$i][3] = $exp[$i-1];
//              $repayment_val[$i][3] = round($exp[$i-1], 2);
            }

            if($i == $l_tenure){
                $repayment_val[$i][3] = $l_amount - $prin_sum - $diff_sum;
            }else{
                $prin_sum += $repayment_val[$i][3];
            }
        }



        for ($i = 1; $i <= $l_tenure; $i++) {
            $repayment_val[$i][4] = $repayment_val[$i][2] + $repayment_val[$i][3];
            $repayment_val[$i][5] = $repayment_val[$i - 1][5] - $repayment_val[$i][3];
            $repayment_val[$i][6] = $intradayRate;
        }
        return $repayment_val;
    }


    public static function flat_amortize($l_start_date, $l_tenure, $l_amount, $l_rate, $l_days_of_month, $holiday_flag=0,$holidays=[], $principal_input=null, $disburse_on=null, $round=null)
    {
        $diff_sum = 0.0;
        $sum_prin = 0.0;
        $repayment_val = [
                [$disburse_on?$disburse_on:$l_start_date, '-', '-', '-', '-', $l_amount]
        ];
        $intradayRate = calc_rate($l_amount, 0, $l_rate, 6); //(($l_amount * $l_rate * 12) / 360) / 100;
        $total_days = 0;
        $date = add_month(date('Y-m-d', strtotime($l_start_date)), $l_tenure);
        $re_date = $date->format('Y-m-d');
        if($holiday_flag == 1){
            // $re_date = date_except($re_date, $holidays);
            $new_holiday = array_flip($holidays);
            if(isset($new_holiday[$re_date])){
                $re_date = date_except($re_date, $holidays);
            }
        }else{
            // $re_date = date_except($re_date);
        }
        if ($l_days_of_month != "Fixed") {
            $total_days = date_dif($repayment_val[0][0], $re_date, 1, false);
        } else {
            $total_days = 30 * $l_tenure;
        }
        //total interest amount
        $total_interest_amount = $intradayRate * $total_days;
        //total installment
        $total_installment = $total_interest_amount + $l_amount;

        for ($i = 1; $i <= $l_tenure; $i++) {
            if(!is_null($disburse_on)){
                $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1);
            }else {
                $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i);//date('Y-m-d',strtotime("+1 months",strtotime($repayment_val[$i-1][0])));
            }
            $re_date = $date->format('Y-m-d');
            if($holiday_flag == 1){
                // $re_date = date_except($re_date, $holidays);
                $new_holiday = array_flip($holidays);
                if(isset($new_holiday[$re_date])){
                    $re_date = date_except($re_date, $holidays);
                }
            }else{
                // $re_date = date_except($re_date);
            }
            $days = date_dif($repayment_val[$i - 1][0], $re_date, 1, false);
            $interest = ($l_days_of_month == "Fixed" ? ($intradayRate * 30) : ($intradayRate * $days));
            $monthly_pay = $total_installment / $l_tenure;
            $monthly_round = round_num($monthly_pay, $round);
            $diff = $monthly_round - $monthly_pay;

            if($principal_input){
                $exp = explode(',', $principal_input);
                $principal = $exp[$i-1];
//                $principal = round($exp[$i-1], 2);
            }else{
                $principal = $monthly_pay - $interest;
            }

            if($i == $l_tenure){
                $principal = round($l_amount - $sum_prin, 2);
                $monthly_pay = $principal + $interest;
            }

            if($i != $l_tenure) {
                $principal += $diff;
                $diff_sum += $diff;
                $monthly_pay = $monthly_round;
            }
            $principal_bal = $repayment_val[$i - 1][5] - $principal;
            $sum_prin += $principal;
            array_push($repayment_val, [$re_date, $days, $interest, $principal, $monthly_pay, $principal_bal, $intradayRate]);
        }
        return $repayment_val;
    }

    public static function annuarity($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag=0,$holidays=[],
                                     $disburse_on=null, $round=null, $reschedule_edit=false, $frequency=null,
                                     $is_disburse = null, $loan_id = null, $admin_fee, $admin_fee_opt, $maintain_fee, $maintain_fee_opt, $change_digit = 0, $monthly_amount = 0)
    {
        $repayment_val = [];
        if($is_disburse == '1'){
            $loan = \App\Models\Loan::select('id')->where('id', intval($loan_id))->with('schedule')->first();
            $bal = $l_amount;
            foreach($loan->schedule as $sch) {
                $bal = $bal - $sch->principal;
                array_push($repayment_val, [$sch->schedule_date, $sch->date_num, $sch->interest, $sch->principal, $sch->fee, $sch->interest + $sch->principal + $sch->fee, $bal, $sch->intraday_rate]);
            }
        }else {

            $loan = \App\Models\Loan::select('id')->where('id', intval($loan_id))->with('schedule')->first();
            if (!is_null($loan) && $is_disburse == '1' && $disburse_on == $loan->schedule[0]->schedule_date && $l_start_date == $loan->schedule[1]->schedule_date) {
                $bal = $l_amount;
                foreach ($loan->schedule as $sch) {
                    $bal = $bal - $sch->principal;
                    array_push($repayment_val, [$sch->schedule_date, $sch->date_num, $sch->interest, $sch->principal, $sch->fee, $sch->interest + $sch->principal + $sch->fee, $bal, $sch->intraday_rate]);
                }
            } else {

                if ($round == 'DOWN') {
                    $round_opt = PHP_ROUND_HALF_DOWN;
                }elseif($round == 'UP'){
                    $round_opt = PHP_ROUND_HALF_UP;
                }else{
                    $round_opt = PHP_ROUND_HALF_DOWN;
                }
                $repayment_val = [
                    [$disburse_on ? $disburse_on : $l_start_date, '-', '-', '-', '-', $l_amount]
                ];
                $total_rate = $l_rate;
                if ($admin_fee_opt == '3') $total_rate += $admin_fee;
                if ($maintain_fee_opt == '3') $total_rate += $maintain_fee;

                if ($monthly_amount != 0.0) {
                    $monthly_pay = $monthly_amount;
                } else {
                    $monthly_pay = ROUND(-self::pmt($total_rate, $l_tenure, $l_amount), $change_digit);
                }
                switch ($frequency) {
                    case '':
                    case 'O': {
                        break;
                    }
                    case 'W': {
                        $monthly_pay = $monthly_pay / 4;
                        break;
                    }
                    case 'F': {
                        $monthly_pay = $monthly_pay / 2;
                        break;
                    }
                    case 'M': {
                        break;
                    }
                    case 'Q': {
                        $monthly_pay = $monthly_pay * 3;
                        break;
                    }
                    case 'H': {
                        $monthly_pay = $monthly_pay * 6;
                        break;
                    }
                    case 'Y': {
                        $monthly_pay = $monthly_pay * 12;
                        break;
                    }
                }
                $monthly_pay = ROUND($monthly_pay, $change_digit, $round_opt);

                if ($reschedule_edit) {
                    $l_tenure = 2;
                    $l_start_date = $reschedule_edit['start_date'];
                    $repayment_val = [[$reschedule_edit['prev_date'], '-', '-', '-', '-', $l_amount]];
                }

                for ($i = 1; $i <= $l_tenure; $i++) {
                    if ($reschedule_edit && $i == $l_tenure && $reschedule_edit['next_date']) {
                        $date = add_month(date('Y-m-d', strtotime($reschedule_edit['next_date'])), $i - 2);
                    } elseif (!is_null($disburse_on) || $reschedule_edit) {
                        $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1, $frequency);
                    } else {
                        $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i, $frequency);
                    }

                    $re_date = $date->format('Y-m-d');
                    if ($holiday_flag == 1) {
                        // $re_date = date_except($re_date, $holidays);
                        $new_holiday = array_flip($holidays);
                        if(isset($new_holiday[$re_date])){
                            $re_date = date_except($re_date, $holidays);
                        }
                    } else {
                        //$re_date = date_except($re_date);
                    }
                    $days = date_dif($repayment_val[$i - 1][0], $re_date, 1, false);
                    $intradayRate = (($repayment_val[$i - 1][5] * $l_rate * 12) / 360) / 100;
                    //$interest = round($intradayRate * 30, ROUND_DIGIT);  //1month = 30days fixed
                    $interest = ROUND($intradayRate * $days, $change_digit, $round_opt);
                    $fee = 0;
                    if ($admin_fee_opt == '3') $fee += ROUND($admin_fee * $repayment_val[$i - 1][5] * $days/ (30 * 100), $change_digit, $round_opt);
                    if ($maintain_fee_opt == '3') $fee += ROUND($maintain_fee * $repayment_val[$i - 1][5] * $days/ (30 * 100), $change_digit, $round_opt);
                    $principal = round($monthly_pay - $interest - $fee, $change_digit);
                    if ($i == $l_tenure) {
                        $principal = $repayment_val[$i - 1][5];
                        $monthly_pay = $principal + $interest + $fee;
                    }
                    $principal_bal = $repayment_val[$i - 1][5] - $principal;
                    array_push($repayment_val, [$re_date, $days, $interest, $principal, $monthly_pay, $principal_bal, $intradayRate, $fee]);
                }
            }
        }
        return $repayment_val;
    }
    public static function annuarity_bak($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag=0,$holidays=[], $disburse_on=null, $round=null, $reschedule_edit=false)
    {

        $repayment_val = [
            [$disburse_on?$disburse_on:$l_start_date, '-', '-', '-', '-', $l_amount]
        ];
        $monthly_pay = -self::pmt($l_rate, $l_tenure, $l_amount);
//        $monthly_pay = round_num($monthly_pay, $round);
        $monthly_pay = round($monthly_pay,0,PHP_ROUND_HALF_UP);

        if($reschedule_edit){
            $l_tenure = 2;
            $l_start_date = $reschedule_edit['start_date'];
            $repayment_val = [[$reschedule_edit['prev_date'], '-', '-', '-', '-', $l_amount]];
        }

        for ($i = 1; $i <= $l_tenure; $i++) {
            if($reschedule_edit && $i==$l_tenure && $reschedule_edit['next_date']){
                $date = add_month(date('Y-m-d', strtotime($reschedule_edit['next_date'])), $i - 2);
            }elseif(!is_null($disburse_on) || $reschedule_edit){
                $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1);
            }else{
                $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i);
            }

            $re_date = $date->format('Y-m-d');
            if($holiday_flag == 1){
                $new_holiday = array_flip($holidays);
                if(isset($new_holiday[$re_date])){
                    $re_date = date_except($re_date, $holidays);
                }

            }else{
                // $re_date = date_except($re_date);
            }
            $days = date_dif($repayment_val[$i - 1][0], $re_date, 1, false);
            $intradayRate = (($repayment_val[$i - 1][5] * $l_rate * 12) / 360) / 100;
            //$interest = round($intradayRate * 30, ROUND_DIGIT);  //1month = 30days fixed
            $interest = round(($intradayRate * 30),0,PHP_ROUND_HALF_UP);
            $principal = round($monthly_pay - $interest,ROUND_DIGIT);
            if($i == $l_tenure) {
                $principal = $repayment_val[$i - 1][5];
                $monthly_pay = $principal + $interest;
            }
            $principal_bal = $repayment_val[$i - 1][5] - $principal;
            array_push($repayment_val, [$re_date, $days, $interest, $principal, $monthly_pay, $principal_bal, $intradayRate]);
        }
        return $repayment_val;
    }
    public static function manual($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag=0,$holidays=[], $principal_input=null,
                                  $disburse_on=null, $round=null, $reschedule_edit=false, $c_principal=null, $ppi=null,
                                  $is_disburse=null, $loan_id = null,$admin_fee, $admin_fee_opt, $maintain_fee, $maintain_fee_opt, $c_fee, $change_digit = 2)
    {
        $change_digit = 2;
        $repayment_val = [];
        $sum_prin = 0.0;
        $diff_sum = 0.0;
        if($is_disburse == '1'){
            $loan = \App\Models\Loan::select('id')->where('id', intval($loan_id))->with('schedule')->first();
            $bal = $l_amount;
            foreach($loan->schedule as $sch) {
                $bal = $bal - $sch->principal;
                array_push($repayment_val, [$sch->schedule_date, $sch->date_num, $sch->interest, $sch->principal, $sch->fee, $sch->interest + $sch->principal + $sch->fee, $bal, $sch->intraday_rate]);
            }
        }else {
            $repayment_val = [
                [$disburse_on?$disburse_on:$l_start_date, '-', '-', '-', '-', $l_amount]
            ];
            if ($reschedule_edit) {
                $l_tenure = 2;
                $l_start_date = $reschedule_edit['start_date'];
                $repayment_val = [[$reschedule_edit['prev_date'], '-', '-', '-', '-', $l_amount]];
            }
            $principal_bal = $l_amount;
            for ($i = 1; $i <= $l_tenure; $i++) {
                $fee = 0;
                if ($reschedule_edit && $i == $l_tenure && $reschedule_edit['next_date']) {
                    $date = add_month(date('Y-m-d', strtotime($reschedule_edit['next_date'])), $i - 2);
                } elseif (!is_null($disburse_on) || $reschedule_edit) {
                    $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1);
                } else {
                    $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i);
                }

                $re_date = $date->format('Y-m-d');
                if ($holiday_flag == 1) {
                    // $re_date = date_except($re_date, $holidays);
                    $new_holiday = array_flip($holidays);
                    if(isset($new_holiday[$re_date])){
                        $re_date = date_except($re_date, $holidays);
                    }
                }
//            else{
//                $re_date = date_except($re_date);
//            }
                $days = date_dif($repayment_val[$i - 1][0], $re_date, 1, false);
                $intradayRate = calc_rate($l_amount, $repayment_val[$i - 1][5], $l_rate, 1); //(($repayment_val[$i - 1][5] * $l_rate * 12) / 360) / 100;
                $interest = $intradayRate * $days;
                $interest = round($interest, $change_digit, PHP_ROUND_HALF_UP);
                if ($principal_input) {
                    $exp = explode(',', $principal_input);
                    $principal = $exp[$i - 1];
                } else {
                    //$principal = round($l_amount / $l_tenure, ROUND_DIGIT);
                    $principal = round(($l_amount / $l_tenure), $change_digit, PHP_ROUND_HALF_UP);
                }

                if ($i == $l_tenure) {
                    $principal = round($l_amount - $sum_prin, $change_digit);
                }

                if ($c_principal != null) {
                    if ($ppi == 'p') {
                        $principal = $c_principal;
                    } elseif($ppi == 'pi') {
                        $principal = $c_principal - $interest;
                    }else {
                        if ($c_fee != null) {
                            $fee = $c_fee;
                        } else {
                            if ($admin_fee_opt == '3') $fee += ROUND($admin_fee * $repayment_val[$i - 1][5] * $days/ (30*100), $change_digit);
                            if ($maintain_fee_opt == '3') $fee += ROUND($maintain_fee * $repayment_val[$i - 1][5]  * $days / (30*100), $change_digit);
                            $fee = ROUND($fee, $change_digit);
                        }
                        $principal = $c_principal - $interest - $fee;
                    }
                }
                if(($admin_fee_opt != '3')&&($maintain_fee_opt != '3')) $fee = floatval($c_fee);
                $monthly_pay = round($interest + $principal, $change_digit);
                $monthly_round = round_num($monthly_pay, $round);
                $diff = $monthly_round - $monthly_pay;
                if ($i != $l_tenure) {
                    if ($c_principal == null) $principal += $diff;
                    $diff_sum += $diff;
                    $monthly_pay = $monthly_round;
                }
                $principal_bal = $repayment_val[$i - 1][5] - $principal;
                $sum_prin += $principal;
                array_push($repayment_val, [$re_date, $days, $interest, $principal, $monthly_pay, $principal_bal, $intradayRate, $fee]);
            }
        }
        return $repayment_val;
    }

    public static function semi_balloon_monthly($l_start_date, $l_tenure, $l_amount, $l_rate, $l_balloon_num, $l_balloon_month,
                                                $l_monthly_pay, $l_bal_amount_array, $custom_flag, $holiday_flag=0,$holidays=[],
                                                $principal_input=null, $disburse_on=null, $round=null, $reschedule_edit=false, $frequency = null,
                                                $is_disburse = 0, $loan_id = null, $admin_fee, $admin_fee_opt, $maintain_fee, $maintain_fee_opt, $change_digit = 0)
    {

        $digit_num = ROUND_DIGIT;
//        $const_prin= round($l_monthly_pay, $digit_num);
        //$const_prin= round($l_monthly_pay,0,PHP_ROUND_HALF_UP);
        $repayment_val = [];
        if($is_disburse == '1'){
            $loan = \App\Models\Loan::select('id')->where('id', intval($loan_id))->with('schedule')->first();
            $bal = $l_amount;
            foreach($loan->schedule as $sch) {
                $bal = $bal - $sch->principal;
                array_push($repayment_val, [$sch->schedule_date, $sch->date_num, $sch->interest, $sch->principal, $sch->fee, $sch->interest + $sch->principal + $sch->fee, $bal, $sch->intraday_rate]);
            }
        }else {
            if ($round == 'DOWN') {
                $round_opt = PHP_ROUND_HALF_DOWN;
            }elseif($round == 'UP'){
                $round_opt = PHP_ROUND_HALF_UP;
            }else {
                $round_opt = PHP_ROUND_HALF_DOWN;
            }
            $total_prin = 0.0;
            $repayment_val = [
                [$disburse_on ? $disburse_on : $l_start_date, '-', '-', '-', '-', $l_amount]
            ];
            $balloon_month = explode(",", $l_balloon_month);
            $balloon_array = [];
            if ($custom_flag == 1 || $custom_flag == 'on') {
                $balloon_value = explode(",", $l_bal_amount_array);
                if (is_array($balloon_array)) {
                    for ($i = 0; $i < count($balloon_month); $i++) {
                        $balloon_array[$balloon_month[$i]] = $balloon_value[$i];
                    }
                }
            } else {
                $balloon_amount = $l_amount / $l_balloon_num;
                for ($i = 0; $i < count($balloon_month); $i++) {
                    $balloon_array[$balloon_month[$i]] = $balloon_amount;
                }
            }

            if ($reschedule_edit) {
                $l_tenure = 2;
                $l_start_date = $reschedule_edit['start_date'];
                $repayment_val = [[$reschedule_edit['prev_date'], '-', '-', '-', '-', '-', $l_amount, '-']];
            }

            for ($i = 1; $i <= $l_tenure; $i++) {
                // if(!is_null($disburse_on)){
                //     $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1);
                // }else {
                //     $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i);//date('Y-m-d',strtotime("+1 months",strtotime($repayment_val[$i-1][0])));
                // }

                if ($reschedule_edit && $i == $l_tenure && $reschedule_edit['next_date']) {
                    $date = add_month(date('Y-m-d', strtotime($reschedule_edit['next_date'])), $i - 2, $frequency);
                } elseif ($disburse_on || $reschedule_edit) {
                    $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i - 1, $frequency);
                } else {
                    $date = add_month(date('Y-m-d', strtotime($l_start_date)), $i, $frequency);
                }
                $re_date = $date->format('Y-m-d');
                if ($holiday_flag == 1) {
                    // $re_date = date_except($re_date, $holidays);
                    $new_holiday = array_flip($holidays);
                    if(isset($new_holiday[$re_date])){
                        $re_date = date_except($re_date, $holidays);
                    }
                }
//            else{
//                $re_date = date_except($re_date);
//            }
                $repayment_val[$i][0] = $re_date;
                $days = date_dif($repayment_val[$i - 1][0], $re_date, 1, false);
                $intradayRate = calc_rate($l_amount, $repayment_val[$i - 1][5], $l_rate, 3); //(($repayment_val[$i - 1][5] * $l_rate * 12) / 360) / 100;
                //$interest_permonth = round($intradayRate * $days, $digit_num);

                $interest_permonth = ROUND($intradayRate * $days, $change_digit, $round_opt);
                // Fee per month
                $admin_fee_permonth = $maintain_fee_permonth = 0;
                // if ($admin_fee_opt == '3') {
                //     if ($admin_fee != null) {
                //         $admin_fee_permonth = ROUND($admin_fee * floatval($repayment_val[$i - 1][5]) * $days/ (30*100), $change_digit,$round_opt);
                //     }
                // }
                // if ($maintain_fee_opt == '3') {
                //     if ($maintain_fee != null) {
                //         $maintain_fee_permonth = ROUND($maintain_fee * floatval($repayment_val[$i - 1][5]) * $days / (30*100), $change_digit,$round_opt);
                //     }
                // }
                $admin_fee_permonth = LoanCalculate::fee_cal($admin_fee_opt, $admin_fee, $l_amount, $repayment_val[$i - 1][5], $i, $l_tenure,$round_opt,$change_digit,$days);
                $maintain_fee_permonth = LoanCalculate::fee_cal($maintain_fee_opt, $maintain_fee, $l_amount, $repayment_val[$i - 1][5], $i, $l_tenure,$round_opt,$change_digit,$days);
                $repayment_val[$i][7] = $admin_fee_permonth + $maintain_fee_permonth;
                $repayment_val[$i][1] = $days;
                $repayment_val[$i][2] = $interest_permonth;
                if ($principal_input) {
                    $exp = explode(',', $principal_input);
                    $repayment_val[$i][3] = $exp[$i - 1];
//              $repayment_val[$i][3] = round($exp[$i-1], 2);
                } else {
                    $repayment_val[$i][3] = $l_monthly_pay - $repayment_val[$i][2] - $repayment_val[$i][7];
                    if (array_key_exists($i, $balloon_array)) {
                        $repayment_val[$i][3] = round($balloon_array[$i], $change_digit)- $repayment_val[$i][2] - $repayment_val[$i][7];
                    }
                }

                if ($i != $l_tenure) {
                    $total_prin += $repayment_val[$i][3];
                } else {
                    $repayment_val[$i][3] = $l_amount - $total_prin;
                }
                $repayment_val[$i][4] = $repayment_val[$i][2] + $repayment_val[$i][3] + $repayment_val[$i][7];
                $repayment_val[$i][5] = $repayment_val[$i - 1][5] - $repayment_val[$i][3];
                $repayment_val[$i][6] = $intradayRate;
            }
        }
        return $repayment_val;
    }

    public static function monthly_loan_schedule($repay_type, $l_start_date, $l_tenure, $l_amount, $l_rate, $l_balloon_num,
                                                 $l_balloon_month, $l_monthly_pay, $l_bal_amount_array, $custom_flag,$l_days_of_month,
                                                 $holiday_flag=0, $holidays=array(), $month_index = 0, $principal_input = null,
                                                 $disburse_on=null, $round=null, $reschedule_edit=false, $c_principal=null,
                                                 $ppi=null, $charge=null, $frequency=null, $is_disburse=null, $loan_id = null,
                                                 $admin_fee = null, $admin_fee_opt = null, $maintain_fee = null, $maintain_fee_opt = null,
                                                 $c_fee=0, $change_digit = 0, $monthly_amount = 0,$sche_type = 'loan')
    {
        if($l_rate == 0){
            $l_rate = 0.00001;
        }
        $repayment_array = [];
        $tobe_paid_amount = [];
        if($repay_type != 8){
            $principal_input = null;
        }
        switch ($repay_type) {
            case 1:
                $repayment_array = self::equal_installment($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag,
                    $holidays, $principal_input, $disburse_on, $round, $reschedule_edit, $c_principal, $frequency, $is_disburse, $loan_id,$change_digit,$sche_type);
                break;
            case 2:
                $repayment_array = self::flat_equal_installment($l_start_date, $l_tenure, $l_amount, $l_rate,
                    $l_days_of_month, $holiday_flag,$holidays, $principal_input, $disburse_on, $round);
                break;
            case 3:
                $repayment_array = self::semi_balloon($l_start_date, $l_tenure, $l_amount, $l_rate, $l_balloon_num,
                    $l_balloon_month, $l_monthly_pay, $l_bal_amount_array, $custom_flag, $holiday_flag,$holidays,
                    $principal_input, $disburse_on, $round, $reschedule_edit, $frequency, $is_disburse, $loan_id,$admin_fee,
                    $admin_fee_opt, $maintain_fee, $maintain_fee_opt, $change_digit);
                break;
            case 4:
                $repayment_array = self::flat_semi_balloon_fixed_monthly_payment($l_start_date, $l_tenure, $l_amount,
                    $l_rate, $l_balloon_num, $l_balloon_month, $l_monthly_pay, $l_bal_amount_array, $custom_flag,
                    $l_days_of_month, $holiday_flag,$holidays, $principal_input, $disburse_on, $round);
                break;
            case 5:
                $repayment_array = self::flat_semi_balloon_fixed_principal($l_start_date, $l_tenure, $l_amount, $l_rate,
                    $l_balloon_num, $l_balloon_month, $l_monthly_pay, $l_bal_amount_array, $custom_flag, $l_days_of_month,
                    $holiday_flag,$holidays, $principal_input, $disburse_on, $round);
                break;
            case 6:
                $repayment_array = self::flat_amortize($l_start_date, $l_tenure, $l_amount, $l_rate, $l_days_of_month,
                    $holiday_flag,$holidays, $principal_input, $disburse_on, $round);
                break;
            case 7:
                $repayment_array = self::annuarity_bak($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag,$holidays,
                    $disburse_on, $round, $reschedule_edit, $frequency, $is_disburse, $loan_id,$admin_fee, $admin_fee_opt,
                    $maintain_fee, $maintain_fee_opt, $change_digit, $monthly_amount);
                // $repayment_array = self::annuarity($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag,$holidays,
                //     $disburse_on, $round, $reschedule_edit, $frequency, $is_disburse, $loan_id,$admin_fee, $admin_fee_opt,
                //     $maintain_fee, $maintain_fee_opt, $change_digit, $monthly_amount); 
                break;
            case 8:
                $repayment_array = self::manual($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag,$holidays,
                    $principal_input, $disburse_on, $round, $reschedule_edit, $c_principal, $ppi, $is_disburse, $loan_id,
                    $admin_fee, $admin_fee_opt, $maintain_fee, $maintain_fee_opt, $c_fee, $change_digit);
                break;
            case 9:
                $repayment_array = self::semi_balloon_monthly($l_start_date, $l_tenure, $l_amount, $l_rate, $l_balloon_num,
                    $l_balloon_month, $l_monthly_pay, $l_bal_amount_array, $custom_flag, $holiday_flag,$holidays,
                    $principal_input, $disburse_on, $round, $reschedule_edit, $frequency, $is_disburse, $loan_id,$admin_fee, $admin_fee_opt,
                    $maintain_fee, $maintain_fee_opt, $change_digit);
                break;
            default:
                break;
        }
        if (!empty($repayment_array) && $month_index > 0) {
            for ($i = 1; $i < count($repayment_array); $i++) {
                if ($month_index == $i) {
                    array_push($tobe_paid_amount, [$repayment_array[$i][0], $repayment_array[$i][2], $repayment_array[$i][3]]);
                    break;
                }
            }
        }
        return [$repayment_array, $tobe_paid_amount];
    }

    public static function monthly_loan_schedule_downPayment($repay_type = 1, $l_start_date, $l_tenure, $l_amount, $l_rate, $l_balloon_num,
                                                 $l_balloon_month, $l_monthly_pay, $l_bal_amount_array, $custom_flag,$l_days_of_month,
                                                 $holiday_flag=0, $holidays=array(), $month_index = 0, $principal_input = null,
                                                 $disburse_on=null, $round=null, $reschedule_edit=false, $c_principal=null,
                                                 $ppi=null, $charge=null, $frequency=null, $is_disburse=null, $loan_id = null,
                                                 $admin_fee = null, $admin_fee_opt = null, $maintain_fee = null, $maintain_fee_opt = null,
                                                 $c_fee=0, $change_digit = 0, $monthly_amount = 0,$sche_type = 'loan')
    {
        $repayment_array = [];
        $tobe_paid_amount = [];
        if($repay_type != 8){
            $principal_input = null;
        }
        switch ($repay_type) {
            case 1:
                $repayment_array = self::equal_installment($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag,
                    $holidays, $principal_input, $l_start_date, $round, $reschedule_edit, $c_principal, $frequency, $is_disburse, $loan_id,$change_digit,$sche_type);
                break;
            case 2:
                $repayment_array = self::flat_equal_installment($l_start_date, $l_tenure, $l_amount, $l_rate,
                    $l_days_of_month, $holiday_flag,$holidays, $principal_input, $disburse_on, $round);
                break;
            case 3:
                $repayment_array = self::semi_balloon($l_start_date, $l_tenure, $l_amount, $l_rate, $l_balloon_num,
                    $l_balloon_month, $l_monthly_pay, $l_bal_amount_array, $custom_flag, $holiday_flag,$holidays,
                    $principal_input, $disburse_on, $round, $reschedule_edit, $frequency, $is_disburse, $loan_id,$admin_fee,
                    $admin_fee_opt, $maintain_fee, $maintain_fee_opt, $change_digit);
                break;
            case 4:
                $repayment_array = self::flat_semi_balloon_fixed_monthly_payment($l_start_date, $l_tenure, $l_amount,
                    $l_rate, $l_balloon_num, $l_balloon_month, $l_monthly_pay, $l_bal_amount_array, $custom_flag,
                    $l_days_of_month, $holiday_flag,$holidays, $principal_input, $disburse_on, $round);
                break;
            case 5:
                $repayment_array = self::flat_semi_balloon_fixed_principal($l_start_date, $l_tenure, $l_amount, $l_rate,
                    $l_balloon_num, $l_balloon_month, $l_monthly_pay, $l_bal_amount_array, $custom_flag, $l_days_of_month,
                    $holiday_flag,$holidays, $principal_input, $disburse_on, $round);
                break;
            case 6:
                $repayment_array = self::flat_amortize($l_start_date, $l_tenure, $l_amount, $l_rate, $l_days_of_month,
                    $holiday_flag,$holidays, $principal_input, $disburse_on, $round);
                break;
            case 7:
                $repayment_array = self::annuarity($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag,$holidays,
                    $disburse_on, $round, $reschedule_edit, $frequency, $is_disburse, $loan_id,$admin_fee, $admin_fee_opt,
                    $maintain_fee, $maintain_fee_opt, $change_digit, $monthly_amount);
                break;
            case 8:
                $repayment_array = self::manual($l_start_date, $l_tenure, $l_amount, $l_rate, $holiday_flag,$holidays,
                    $principal_input, $disburse_on, $round, $reschedule_edit, $c_principal, $ppi, $is_disburse, $loan_id,
                    $admin_fee, $admin_fee_opt, $maintain_fee, $maintain_fee_opt, $c_fee, $change_digit);
                break;
            case 9:
                $repayment_array = self::semi_balloon_monthly($l_start_date, $l_tenure, $l_amount, $l_rate, $l_balloon_num,
                    $l_balloon_month, $l_monthly_pay, $l_bal_amount_array, $custom_flag, $holiday_flag,$holidays,
                    $principal_input, $disburse_on, $round, $reschedule_edit, $frequency, $is_disburse, $loan_id,$admin_fee, $admin_fee_opt,
                    $maintain_fee, $maintain_fee_opt, $change_digit);
                break;
            default:
                break;
        }
        if (!empty($repayment_array) && $month_index > 0) {
            for ($i = 1; $i < count($repayment_array); $i++) {
                if ($month_index == $i) {
                    array_push($tobe_paid_amount, [$repayment_array[$i][0], $repayment_array[$i][2], $repayment_array[$i][3]]);
                    break;
                }
            }
        }
        return [$repayment_array, $tobe_paid_amount];


    }

    public static function pmt($apr, $term, $loan)
    {
        $term = $term;
        $apr = $apr / 100;
        $amount = $apr * $loan * pow((1 + $apr), $term) / (1 - pow((1 + $apr), $term));
        return $amount;
    }


    public static function getTotalPenalty($loan, $selected_date = null, $air_sch_flag = AIR_SCH_FLG)
    {
        $result = [];
        $repayment_schedule = [];
        $overdue = 0;
        $to_prin_due = 0;
        $to_int_due = 0;
        $to_fee_due = 0;
        $to_fee_other_due = 0;
        !empty($selected_date)? $today = date('Y-m-d', strtotime($selected_date)) : $today = date('Y-m-d');
        // $month_idx = get_month_idx($loan->disburse_date, $today, $loan->schedule);
        $month_idx = get_month_idx($loan->stat_payment_date, $today, $loan->schedule);
        //if($loan->id == 237) dd($month_idx);
        if($month_idx == -1) return -1;
        $l_payment = $loan->payment;
        (!empty($l_payment) && count($l_payment) > 0)? $l_paid_date = $l_payment[count($l_payment) - 1]->repayment_date : $l_paid_date = null;
        $total_penalty = 0.0;
        $total_monthly_due = 0.0;
        $repayment_schedule = LoanCalculate::loan_schedule($loan->schedule,$loan->start_date, 0, $loan->tranfer_date)[0];
        // next_schedule_date
        $next_schedule_date = null;
        foreach($l_payment as $l_pay){
            if($l_pay->repayment_date === $l_paid_date && $l_pay->status == 0 && $l_pay->condition_id == 1) {
                //$next_schedule_date = $l_pay->repayment_date;
                if(is_null($next_schedule_date) || date_dif($next_schedule_date, $loan->schedule[$l_pay->payment_month - 1]->schedule_date, 1, false) < 0) {
//                    $next_schedule_date = $repayment_schedule[$l_pay->payment_month]->schedule_date;
                    $next_schedule_date = $repayment_schedule[$l_pay->payment_month][0];
                }
            }
        }
        if(is_null($next_schedule_date )){
            $idx = 1;
            $next_schedule_date = $loan->schedule[$idx]->schedule_date;
        }
        if(!empty($loan)) {
            // month_idx till today
            if (count($repayment_schedule) > 0) {
                $pay_next_month = [];
                $groupPaymentArray = [];
                $penalty_type = $loan->penalty_rate_type;
                $num_month = $month_idx;
//                if($loan->contract_id == 'R000003') dd($loan);
                if(!empty($loan->payment) && count($loan->payment) > 0 ){
                    $groupPayment = $loan->payment->groupBy('payment_month');
                    $groupPaymentArray = $groupPayment->toArray();
                    foreach($groupPayment as $gp){
                        $lp = $gp[count($gp) - 1];
                        //$lp = $gp[0];
                        if(date_dif($repayment_schedule[$lp->payment_month][0],$today,1,false) < 0) break;
                        $p_month = $lp->payment_month;
                        $ow_p_month = $p_month+1;
                        if($lp->condition_id == 1) { /* next time*/
                            $ow_p_month = $p_month;
                        }
                        if($lp->status == 0){
                            $principal_paid = 0;
                            $interest_paid = 0;
                            $fee_paid = 0;
                            $other_fee_paid = 0;
                            $prin_owed = 0;
                            $int_owed = 0;
                            $fee_owed = 0;
                            $penal_owed = 0;
                            $num_day = 0;
                            $pay_period = 0;
                            $prin_tobe_paid = floatval($repayment_schedule[$p_month][3]);
                            $int_tobe_paid = floatval($repayment_schedule[$p_month][2]);
                            $fee_tobe_paid = floatval($repayment_schedule[$p_month][4]);
                            $other_fee_tobe_paid = floatval($repayment_schedule[$p_month][7]);
                            $s_date = $repayment_schedule[$ow_p_month][0];
                            $sub_day = $loan->penalty_period1;
                            $last_paid_date = $s_date;
                            foreach ( $gp as $mpay) {
                                $principal_paid += floatval($mpay->paid_principal);
                                $interest_paid += floatval($mpay->paid_interest);
                                $fee_paid += floatval($mpay->paid_fee);
                                $other_fee_paid += floatval($mpay->paid_other_fee);
                                $prin_owed = $prin_tobe_paid - $mpay->paid_principal;
                                $int_owed = $int_tobe_paid - $mpay->paid_interest;
                                $fee_owed = $fee_tobe_paid - $mpay->paid_fee;
                                $other_fee_owed = $other_fee_tobe_paid - $mpay->paid_other_fee;
                                $penal_owed = floatval($mpay->repayment_owed) - ($prin_owed + $int_owed +$fee_owed + $other_fee_owed);
                                $prin_tobe_paid = $prin_owed;
                                $int_tobe_paid = $int_owed;
                                $fee_tobe_paid = $fee_owed;
                                $other_fee_tobe_paid = $other_fee_owed;
                                if($s_date < $mpay->repayment_date) {
                                    $pay_period = date_dif($s_date, $mpay->repayment_date, 1, false);
                                    $last_paid_date = $mpay->repayment_date;
                                }
                                $sub_day = $loan->penalty_period1;
                            }

                            $m_payment = round($prin_owed + $int_owed + $fee_owed,2);
                            $penalty_owed = $penal_owed;
                            $late_day = 0;
                            $p_rate = 0;
                            $p_rate_amount = 0;
                            $p_amount = 0;
                            $tmp_next_sch = null;
                            if($m_payment > 0){
                              if($overdue < date_dif($s_date, $today, 1, false)){
                                $overdue = date_dif($s_date, $today, 1, false);
                              }
                            }
                            if($m_payment == 0){
                              $result[$ow_p_month] =  [$s_date,$prin_owed, $int_owed,$m_payment,0,$p_rate, $p_rate_amount, date_dif($s_date, $today, 1, false), $penal_owed, $penal_owed, $fee_owed, $other_fee_owed];
                              $total_penalty += $penalty_owed;
                              $total_monthly_due += $m_payment;
                              continue;
                            }
                            if ($penalty_type == 1) { /* day */
                                if($pay_period <= $sub_day){
                                    $late_day = date_dif($s_date, $today, 1, false);
                                    $tmp_next_sch = $s_date;
                                    //$late_day = date_diff_except_holiday($s_date, $today, HOLIDAY_FLAG, $loan->holiday);
                                    if($late_day <= $sub_day){
                                        $num_day = 0;
                                    }else{
                                        $num_day = $late_day;
                                    }
                                }else{
                                    $late_day = date_dif($last_paid_date, $today, 1, false);
                                    $tmp_next_sch = $last_paid_date;
                                    //  $late_day = date_diff_except_holiday($last_paid_date, $today, HOLIDAY_FLAG, $loan->holiday);
                                    if($late_day < 0){
                                        $num_day = 0;
                                    }else{
                                        $num_day = $late_day;
                                    }
                                }
                                $p_rate = $loan->penalty_rate1;
                                $p_rate_amount = $m_payment * ($p_rate / 100);
                                // $p_amount = $p_rate_amount * $num_day;
                                if ($loan->loan_penalty_type == '$') {
                                    $p_amount += $num_day * $loan->penalty_rate1;
                                }elseif ($loan->loan_penalty_type == '%') {
                                    $p_amount = $p_rate_amount * $num_day;
                                }else{
                                    $p_amount = $p_rate_amount * $num_day;
                                }
                            } elseif ($penalty_type == 2) { /* month */
                                $sch_date = $repayment_schedule[$ow_p_month][0];
                                $late_day = date_dif($sch_date, $today, 1, false);
                                //$late_day = date_diff_except_holiday($sch_date, $today, HOLIDAY_FLAG, $loan->holiday);
                                if ($late_day > $loan->penalty_period1) {
                                    $p_rate = $loan->penalty_rate1;
                                    $p_rate_amount = $m_payment * ($p_rate / 100);
                                    $p_amount = $p_rate_amount;
                                }
                            } else {
                                $sch_date = $repayment_schedule[$ow_p_month][0];
                                $late_day = date_dif($sch_date, $today, 1, false);
                                //$late_day = date_diff_except_holiday($sch_date, $today, HOLIDAY_FLAG, $loan->holiday);
                                if ($late_day > $loan->penalty_period2) {
                                    $p_rate = $loan->penalty_rate2;
                                    $p_rate_amount = $m_payment * ($p_rate / 100);
                                    $p_amount = $p_rate_amount;
                                } elseif ($late_day > $loan->penalty_period1) {
                                    $p_rate = $loan->penalty_rate1;
                                    $p_rate_amount = $m_payment * ($p_rate / 100);
                                    $p_amount = $p_rate_amount;
                                }
                            }

                            if ($late_day < 0){
                                $late_day = 0;
                            }

                            //if($loan->contract_id == 'TGL2017/004') dd($overdue);
                            if($overdue <= $late_day && $m_payment > 0){
                                $overdue = $late_day;
                                if($l_paid_date != $last_paid_date){
                                    $l_paid_date = $last_paid_date;
                                }
                                if(date_dif($tmp_next_sch, $next_schedule_date, 1 , false) <= 0){
                                    $next_schedule_date = $tmp_next_sch;
                                }
                            }

                            /*$penalty_floor = floor($p_amount);
                            if(($p_amount - $penalty_floor) >= 0.45){
                                $p_amount = round($p_amount,2,PHP_ROUND_HALF_UP);
                            }else{
                                $p_amount = $penalty_floor;
                            }
                            */
                            $p_amount +=  $penalty_owed;
                            $to_amount = $p_amount + $m_payment + $other_fee_owed;
                            if($lp->condition_id == 1){
                                // $result[$ow_p_month] =  [$s_date,$principal_owed, $interest_owed,0,$m_payment,$p_rate, $p_rate_amount, $day, $p_amount, $to_amount];
                                $result[$ow_p_month] =  [$s_date,$prin_owed, $int_owed,$m_payment,$penalty_owed,$p_rate, $p_rate_amount, $late_day, $p_amount, $to_amount, $fee_owed, $other_fee_owed];
                                $total_penalty += $p_amount;
                                $total_monthly_due += $m_payment;
                                $to_prin_due += $prin_owed;
                                $to_int_due += $int_owed;
                                $to_fee_due += $fee_owed;
                                $to_other_fee_due += $other_fee_owed;
                            }else{
                                $pay_next_month[$ow_p_month] = [$s_date,$prin_owed, $int_owed,$penalty_owed,$m_payment,$p_rate, $p_rate_amount, $late_day, $p_amount, $to_amount, $fee_owed, $other_fee_owed];
                            }
                        }
                    }
                }
                //for ($i = 1; $i < count($repayment_schedule); $i++) {
                for ($i = 1; $i <= $loan->loan_duration; $i++) {
                    if(!array_key_exists($i,$groupPaymentArray)){
                        if(is_null($repayment_schedule[$i])) continue;
                        $date = $repayment_schedule[$i][0];
                        $day = date_dif($date, $today, 1, false);
                        // if($loan->client_name == 'Tuy Hongly'){
                        //     var_dump($date);
                        //     dd($day);
                        // } 
                        //  $day = date_diff_except_holiday($date, $today, HOLIDAY_FLAG, $loan->holiday);
                        if ($day < 0) {
                            break;
                        }
                        $sub_day = $loan->penalty_period1;
                        $p_rate = 0;
                        $p_amount = 0;
                        $m_payment = $repayment_schedule[$i][2] + $repayment_schedule[$i][3] + $repayment_schedule[$i][4];
                        $other_fee = $repayment_schedule[$i][7];
                        $p_rate_amount = 0;
                        if ($penalty_type == 1) { /* day */
                            $p_rate = $loan->penalty_rate1;
                            $p_rate_amount = $m_payment * ($p_rate / 100);
                            $num_day = $day;
                            if ($num_day > $sub_day) {
                                if ($loan->loan_penalty_type == '$') {
                                    $p_amount += $num_day * $loan->penalty_rate1;
                                }elseif ($loan->loan_penalty_type == '%') {
                                    $p_amount = $p_rate_amount * $num_day;
                                }else{
                                    $p_amount = $p_rate_amount * $num_day;
                                }
                            }
                        } elseif ($penalty_type == 2) { /* month */
                            $p_rate = $loan->penalty_rate1;
                            $p_rate_amount = $m_payment * ($p_rate / 100);
                            for ($j = $i; $j <= $num_month; $j++) {
                                if (array_key_exists($j, $repayment_schedule)) {
                                    $date = $repayment_schedule[$j][0];
                                    $day = date_dif($date, $today, 1, false);
                                    //  $day = date_diff_except_holiday($date, $today, HOLIDAY_FLAG, $loan->holiday);
                                    if ($day - $sub_day > 0) {
                                        $p_amount += $p_rate_amount;
                                        break;
                                    }
                                }
                            }
                        } else {
                            for ($j = $i; $j <= $num_month; $j++) {
                                if (array_key_exists($j, $repayment_schedule)) {
                                    $date = $repayment_schedule[$j][0];
                                    $day = date_dif($date, $today, 1, false);
                                    //  $day = date_diff_except_holiday($date, $today, HOLIDAY_FLAG, $loan->holiday);
                                    if ($day > $loan->penalty_period2) {
                                        $p_rate = $loan->penalty_rate2;
                                        $p_rate_amount = $m_payment * ($p_rate / 100);
                                        $p_amount = $p_rate_amount;
                                        break;
                                    } elseif ($day > $loan->penalty_period1) {
                                        $p_rate = $loan->penalty_rate1;
                                        $p_rate_amount = $m_payment * ($p_rate / 100);
                                        $p_amount = $p_rate_amount;
                                        break;
                                    }
                                }
                            }
                        }
                        
                        // $late_day = $day - $sub_day;
                        // if ($late_day < 0) $day = 0;
                        if($overdue < $day){
                            $overdue = $day;
                            if(!empty($loan->payment) && count($loan->payment) > 0 ) {

                            }else{
                                //  $l_paid_date = $date;
                                if(date_dif($date, $next_schedule_date, 1 , false) <= 0){
                                    $next_schedule_date = $date;
                                }
                            }
                        }
                        /*
                        $penalty_floor = floor($p_amount);
                        if(($p_amount - $penalty_floor) >= 0.45){
                            $p_amount = round($p_amount,0,PHP_ROUND_HALF_UP);
                        }else{
                            $p_amount = $penalty_floor;
                        }
                        */
                        $to_amount = $p_amount + $m_payment + $other_fee;
                        $principal = $repayment_schedule[$i][3];
                        $interest = $repayment_schedule[$i][2];
                        $fee = $repayment_schedule[$i][4];
                        $other_fee = $repayment_schedule[$i][7];
                        $arr_amount = 0;
                        if (count($pay_next_month) > 0 && array_key_exists($i, $pay_next_month)) {
                            $principal += $pay_next_month[$i][1];
                            $interest += $pay_next_month[$i][2];
                            $fee += $pay_next_month[$i][10];
                            $arr_amount += $pay_next_month[$i][4];
                            $p_rate_amount += $pay_next_month[$i][6];
                            $p_amount +=  $pay_next_month[$i][8];
                            $to_amount += $pay_next_month[$i][9];
                        }
                        $result[$i] = [$date, $principal, $interest,$m_payment,$arr_amount ,$p_rate, $p_rate_amount, $day, $p_amount, $to_amount, $fee, $other_fee];
                        //if($loan->client_name == 'Dos Ra') dd($p_amount);
                        $total_penalty += $p_amount;
                        $total_monthly_due += $m_payment;
                        $to_prin_due += $principal;
                        $to_int_due += $interest;
                        $to_fee_due += $fee;
                        $to_other_fee_due += $other_fee;
                    }
                }
            }
        }
        if($air_sch_flag == 0){
            $air_bal = get_journal_bal($loan->client_loan_account->air_id);
            $air_add = $air_bal['balance'] - $to_int_due;
        //  $air_add = get_intraday_rate($loan) * $la_til_today * $loan->client_loan_account->balance;
            // when fee is accrue, we will add
            //$fee = $loan-> * $la_til_today * $loan->client_loan_account->balance;
            if($air_add > 0)
            $result[$i] = [$today, 0, $air_add, $air_add, 0, 0, 0, 0, 0, $air_add, 0, 0];
            $to_int_due += $air_add;
            
        }
        //dd($result);
        // in case there is origin_days_due
        $overdue += $loan->origin_days_due;
        //if($total_monthly_due > $loan->loan_amount) $total_monthly_due = $loan->loan_amount;
        return [$result,$repayment_schedule, $overdue, $l_paid_date, $total_penalty, $next_schedule_date, $total_monthly_due, $to_prin_due, $to_int_due, $to_fee_due, $to_other_fee_due];
    }


    public static function getTotalPenalty_new($loan, $selected_date = null)
    {
        $result = [];
        $repayment_schedule = [];
        $overdue = 0;
        $l_paid_date = 0;
        $l_paid_month_idx = 0;
        $to_prin_due = 0;
        $to_int_due = 0;
        $to_fee_due = 0;
        !empty($selected_date)? $today = date('Y-m-d', strtotime($selected_date)) : $today = date('Y-m-d');
        $month_idx = get_month_idx($loan->disburse_date, $today, $loan->schedule);
        if($month_idx == -1) return -1;
        $l_payment = $loan->payment;
        // # (!empty($l_payment) && count($l_payment) > 0)? $l_paid_date = $l_payment[count($l_payment) - 1]->repayment_date : $l_paid_date = null;
        for($i = 0; $i < count($l_payment) - 1; $i++){
            if(date_dif($l_payment[$i]->repayment_date, $today, 1, false) >= 0){
                $l_paid_date =  $l_payment[$i]->repayment_date;
                $l_paid_month_idx = $l_payment[$i]->payment_month;
            }else{
                break;
            }
        }
        $total_penalty = 0.0;
        $total_monthly_due = 0.0;
        // next_schedule_date
        $next_schedule_date = null;
        foreach($l_payment as $l_pay){
            if($l_pay->repayment_date === $l_paid_date ) {
//                if($l_pay->repayment_date === $l_paid_date && $l_pay->status == 0 && $l_pay->condition_id == 1) {
                    //$next_schedule_date = $l_pay->repayment_date;
                if(is_null($next_schedule_date) || date_dif($next_schedule_date, $loan->schedule[$l_pay->payment_month - 1]->schedule_date, 1, false) < 0) {
                    $next_schedule_date = $loan->schedule[$l_pay->payment_month]->schedule_date;
                }
            }
        }
        if(is_null($next_schedule_date )){
            $idx = 1;
            $next_schedule_date = $loan->schedule[$idx]->schedule_date;
        }

        if(!empty($loan)) {
            $repayment_schedule = LoanCalculate::loan_schedule($loan->schedule,$loan->start_date)[0];
            // month_idx till today
            if (count($repayment_schedule) > 0) {
                $pay_next_month = [];
                $groupPaymentArray = [];
                $penalty_type = $loan->penalty_rate_type;
                $num_month = $month_idx;
//                if($loan->contract_id == 'R000003') dd($loan);
                if(!empty($loan->payment) && count($loan->payment) > 0 ){
                    $groupPayment = $loan->payment->groupBy('payment_month');
                    $groupPaymentArray = $groupPayment->toArray();
                    foreach($groupPayment as $gp){
                        $lp = $gp[count($gp) - 1];
                        //$lp = $gp[0];
                        if(($lp->payment_date > $l_paid_date) || ($lp->payment_month > $month_idx) || date_dif($repayment_schedule[$lp->payment_month][0],$today,1,false) < 0) break;
                        $p_month = $lp->payment_month;
                        $ow_p_month = $p_month+1;
                        if($lp->condition_id == 1) { /* next time*/
                            $ow_p_month = $p_month;
                        }
                        if($lp->status == 0){
                            $principal_paid = 0;
                            $interest_paid = 0;
                            $fee_paid = 0;
                            $prin_owed = 0;
                            $int_owed = 0;
                            $fee_owed = 0;
                            $penal_owed = 0;
                            $num_day = 0;
                            $pay_period = 0;
                            $prin_tobe_paid = floatval($repayment_schedule[$p_month][3]);
                            $int_tobe_paid = floatval($repayment_schedule[$p_month][2]);
                            $fee_tobe_paid = floatval($repayment_schedule[$p_month][4]);
                            $s_date = $repayment_schedule[$ow_p_month][0];
                            $sub_day = $loan->penalty_period1;
                            $last_paid_date = $s_date;
                            foreach ( $gp as $mpay) {
                                $principal_paid += floatval($mpay->paid_principal);
                                $interest_paid += floatval($mpay->paid_interest);
                                $fee_paid += floatval($mpay->paid_fee);
                                $prin_owed = $prin_tobe_paid - $mpay->paid_principal;
                                $int_owed = $int_tobe_paid - $mpay->paid_interest;
                                $fee_owed = $fee_tobe_paid - $mpay->paid_fee;
                                $penal_owed = floatval($mpay->repayment_owed) - ($prin_owed + $int_owed +$fee_owed);
                                $prin_tobe_paid = $prin_owed;
                                $int_tobe_paid = $int_owed;
                                $fee_tobe_paid = $fee_owed;
                                if($s_date < $mpay->repayment_date) {
                                    $pay_period = date_dif($s_date, $mpay->repayment_date, 1, false);
                                    $last_paid_date = $mpay->repayment_date;
                                }
                                $sub_day = $loan->penalty_period1;
                            }

                            $m_payment = round($prin_owed + $int_owed + $fee_owed,2);
                            $penalty_owed = $penal_owed;
                            $late_day = 0;
                            $p_rate = 0;
                            $p_rate_amount = 0;
                            $p_amount = 0;
                            $tmp_next_sch = null;
                            if($m_payment > 0){
                              if($overdue < date_dif($s_date, $today, 1, false)){
                                $overdue = date_dif($s_date, $today, 1, false);
                              }
                            }
                            if($m_payment == 0){
                              $result[$ow_p_month] =  [$s_date,$prin_owed, $int_owed,$m_payment,0,$p_rate, $p_rate_amount, date_dif($s_date, $today, 1, false), $penal_owed, $penal_owed, $fee_owed];
                              $total_penalty += $penalty_owed;
                              $total_monthly_due += $m_payment;
                              continue;
                            }
                            if ($penalty_type == 1) { /* day */
                                if($pay_period <= $sub_day){
                                    $late_day = date_dif($s_date, $today, 1, false);
                                    $tmp_next_sch = $s_date;
//                                    $late_day = date_diff_except_holiday($s_date, $today, HOLIDAY_FLAG, $loan->holiday);
                    if($late_day <= $sub_day){
                                        $num_day = 0;
                                    }else{
                                        $num_day = $late_day;
                                    }
                                }else{
                                    $late_day = date_dif($last_paid_date, $today, 1, false);
                                    $tmp_next_sch = $last_paid_date;
//                                    $late_day = date_diff_except_holiday($last_paid_date, $today, HOLIDAY_FLAG, $loan->holiday);
                                    if($late_day < 0){
                                        $num_day = 0;
                                    }else{
                                        $num_day = $late_day;
                                    }
                                }
                                $p_rate = $loan->penalty_rate1;
                                $p_rate_amount = $m_payment * ($p_rate / 100);
                                $p_amount = $p_rate_amount * $num_day;
                            } elseif ($penalty_type == 2) { /* month */
                                $sch_date = $repayment_schedule[$ow_p_month][0];
                                $late_day = date_dif($sch_date, $today, 1, false);
                                //$late_day = date_diff_except_holiday($sch_date, $today, HOLIDAY_FLAG, $loan->holiday);
                                if ($late_day > $loan->penalty_period1) {
                                    $p_rate = $loan->penalty_rate1;
                                    $p_rate_amount = $m_payment * ($p_rate / 100);
                                    $p_amount = $p_rate_amount;
                                }
                            } else {
                                $sch_date = $repayment_schedule[$ow_p_month][0];
                                $late_day = date_dif($sch_date, $today, 1, false);
                                //$late_day = date_diff_except_holiday($sch_date, $today, HOLIDAY_FLAG, $loan->holiday);
                                if ($late_day > $loan->penalty_period2) {
                                    $p_rate = $loan->penalty_rate2;
                                    $p_rate_amount = $m_payment * ($p_rate / 100);
                                    $p_amount = $p_rate_amount;
                                } elseif ($late_day > $loan->penalty_period1) {
                                    $p_rate = $loan->penalty_rate1;
                                    $p_rate_amount = $m_payment * ($p_rate / 100);
                                    $p_amount = $p_rate_amount;
                                }
                            }

                            if ($late_day < 0){
                                $late_day = 0;
                            }

                            //if($loan->contract_id == 'TGL2017/004') dd($overdue);
                            if($overdue <= $late_day && $m_payment > 0){
                                $overdue = $late_day;
                                if($l_paid_date != $last_paid_date){
                                    $l_paid_date = $last_paid_date;
                                }
                                if(date_dif($tmp_next_sch, $next_schedule_date, 1 , false) <= 0){
                                    $next_schedule_date = $tmp_next_sch;
                                }
                            }

/*                            $penalty_floor = floor($p_amount);
                            if(($p_amount - $penalty_floor) >= 0.45){
                                $p_amount = round($p_amount,2,PHP_ROUND_HALF_UP);
                            }else{
                                $p_amount = $penalty_floor;
                            }
*/

                            $p_amount +=  $penalty_owed;
                            $to_amount = $p_amount + $m_payment;
                            if($lp->condition_id == 1){
//                                $result[$ow_p_month] =  [$s_date,$principal_owed, $interest_owed,0,$m_payment,$p_rate, $p_rate_amount, $day, $p_amount, $to_amount];
                                $result[$ow_p_month] =  [$s_date,$prin_owed, $int_owed,$m_payment,0,$p_rate, $p_rate_amount, $late_day, $p_amount, $to_amount, $fee_owed];
                $total_penalty += $p_amount;
                                $total_monthly_due += $m_payment;
                                $to_prin_due += $prin_owed;
                                $to_int_due += $int_owed;
                                $to_fee_due += $fee_owed;
                            }else{
                                $pay_next_month[$ow_p_month] = [$s_date,$prin_owed, $int_owed,0,$m_payment,$p_rate, $p_rate_amount, $late_day, $p_amount, $to_amount, $fee_owed];
                            }
                        }
                    }
                }
                //if($loan->id == 114) dd($m_payment);

                for ($i = 1; $i < count($repayment_schedule); $i++) {
                    if(!array_key_exists($i,$groupPaymentArray)){
                        $date = $repayment_schedule[$i][0];
                        $day = date_dif($date, $today, 1, false);
                        // if($loan->client_name == 'Tuy Hongly'){
                        //     var_dump($date);
                        //     dd($day);
                        // } 

//                        $day = date_diff_except_holiday($date, $today, HOLIDAY_FLAG, $loan->holiday);
                        if ($day < 0) {
                            break;
                        }
                        $sub_day = $loan->penalty_period1;
                        $p_rate = 0;
                        $p_amount = 0;
                        $m_payment = $repayment_schedule[$i][2] + $repayment_schedule[$i][3] + $repayment_schedule[$i][4];
                        $p_rate_amount = 0;
                        if ($penalty_type == 1) { /* day */
                            $p_rate = $loan->penalty_rate1;
                            $p_rate_amount = $m_payment * ($p_rate / 100);
                            $num_day = $day;
                            if ($num_day > $sub_day) {
                                $p_amount = $p_rate_amount * $num_day;
                            }
                        } elseif ($penalty_type == 2) { /* month */
                            $p_rate = $loan->penalty_rate1;
                            $p_rate_amount = $m_payment * ($p_rate / 100);
                            for ($j = $i; $j <= $num_month; $j++) {
                                if (array_key_exists($j, $repayment_schedule)) {
                                    $date = $repayment_schedule[$j][0];
                                    $day = date_dif($date, $today, 1, false);
//                                    $day = date_diff_except_holiday($date, $today, HOLIDAY_FLAG, $loan->holiday);
                                    if ($day - $sub_day > 0) {
                                        $p_amount += $p_rate_amount;
                                        break;
                                    }
                                }
                            }
                        } else {
                            for ($j = $i; $j <= $num_month; $j++) {
                                if (array_key_exists($j, $repayment_schedule)) {
                                    $date = $repayment_schedule[$j][0];
                                    $day = date_dif($date, $today, 1, false);
//                                    $day = date_diff_except_holiday($date, $today, HOLIDAY_FLAG, $loan->holiday);
                                    if ($day > $loan->penalty_period2) {
                                        $p_rate = $loan->penalty_rate2;
                                        $p_rate_amount = $m_payment * ($p_rate / 100);
                                        $p_amount = $p_rate_amount;
                                        break;
                                    } elseif ($day > $loan->penalty_period1) {
                                        $p_rate = $loan->penalty_rate1;
                                        $p_rate_amount = $m_payment * ($p_rate / 100);
                                        $p_amount = $p_rate_amount;
                                        break;
                                    }
                                }
                            }
                        }
                        $late_day = $day - $sub_day;
                        if ($late_day < 0) $day = 0;
                        if($overdue < $day){
                            $overdue = $day;
                            if(!empty($loan->payment) && count($loan->payment) > 0 ) {

                            }else{
//                                $l_paid_date = $date;
                                if(date_dif($date, $next_schedule_date, 1 , false) <= 0){
                                    $next_schedule_date = $date;
                                }
                            }
                        }
/*
                        $penalty_floor = floor($p_amount);
                        if(($p_amount - $penalty_floor) >= 0.45){
                            $p_amount = round($p_amount,0,PHP_ROUND_HALF_UP);
                        }else{
                            $p_amount = $penalty_floor;
                        }
*/
                        $to_amount = $p_amount + $m_payment;
                        $principal = $repayment_schedule[$i][3];
                        $interest = $repayment_schedule[$i][2];
                        $fee = $repayment_schedule[$i][4];
                        $arr_amount = 0;
                        if (count($pay_next_month) > 0 && array_key_exists($i, $pay_next_month)) {
                            $principal += $pay_next_month[$i][1];
                            $interest += $pay_next_month[$i][2];
                            $fee += $pay_next_month[$i][10];
                            $arr_amount += $pay_next_month[$i][4];
                            $p_rate_amount += $pay_next_month[$i][6];
                            $p_amount +=  $pay_next_month[$i][8];
                            $to_amount += $pay_next_month[$i][9];
                        }
                        $to_prin_due += $principal;
                        $to_int_due += $interest;
                        $to_fee_due += $fee;
                        $result[$i] = [$date, $principal, $interest,$m_payment,$arr_amount ,$p_rate, $p_rate_amount, $day, $p_amount, $to_amount, $fee];
                        //if($loan->client_name == 'Dos Ra') dd($p_amount);
                        $total_penalty += $p_amount;
                        $total_monthly_due += $m_payment;
                    }
                }
            }
        }

        if($total_monthly_due > $loan->loan_amount) $total_monthly_due = $loan->loan_amount;
        return [$result,$repayment_schedule, $overdue, $l_paid_date, $total_penalty, $next_schedule_date, $total_monthly_due];
    }

    

    public static function loan_schedule($loan_schedule,$start_date ,$month_index = 0, $transfer_date = "0000-00-00")
    {
        $repayment_array = [];
        $tobe_paid_amount = [];
        if(count($loan_schedule) > 0){
            foreach($loan_schedule as $key=>$ls){
                $monthly_pay = $ls->interest + $ls->principal + $ls->fee;
                if($transfer_date != "0000-00-00"){
                    $repayment_array[$ls->no] = [$ls->schedule_date,$ls->date_num,$ls->interest,$ls->principal, $ls->fee,$monthly_pay, $ls->no, $ls->other_fee,$ls->loan_no,$ls->type];
                }else{
                    $repayment_array[] = [$ls->schedule_date,$ls->date_num,$ls->interest,$ls->principal, $ls->fee, $monthly_pay, $ls->no, $ls->other_fee,$ls->loan_no,$ls->type];
                }
            }
        }
        if (!empty($repayment_array) && $month_index > 0) {
            for ($i = 1; $i < count($repayment_array); $i++) {
                if ($month_index == $i) {
                    array_push($tobe_paid_amount, [$repayment_array[$i][0], $repayment_array[$i][1], $repayment_array[$i][2], $repayment_array[$i][3]]);
                    break;
                }
            }
        }
        return [$repayment_array, $tobe_paid_amount];
    }

    public static function loan_schedule_restructure($loan_schedule,$start_date ,$month_index = 0, $transfer_date = "0000-00-00"){
        $repayment_array = [];
        $tobe_paid_amount = [];
        if(count($loan_schedule) > 0){
            foreach($loan_schedule as $key=>$ls){
                $monthly_pay = $ls->interest + $ls->principal + $ls->fee;
                if($transfer_date != "0000-00-00"){
                    $repayment_array[$ls->no] = [$ls->schedule_date,$ls->date_num,$ls->interest,$ls->principal, $ls->fee,$monthly_pay, $ls->no, $ls->other_fee,$ls->loan_no,$ls->type];
                }else{
                    $repayment_array[] = [$ls->schedule_date,$ls->date_num,$ls->interest,$ls->principal, $ls->fee, $monthly_pay, $ls->no, $ls->other_fee,$ls->loan_no,$ls->type];
                }
            }
        }
        if (!empty($repayment_array) && $month_index > 0) {
            for ($i = 1; $i < count($repayment_array); $i++) {
                if ($month_index == $i) {
                    array_push($tobe_paid_amount, [$repayment_array[$i][0], $repayment_array[$i][1], $repayment_array[$i][2], $repayment_array[$i][3]]);
                    break;
                }
            }
        }
        return [$repayment_array, $tobe_paid_amount];
    }

    public static function getOverdue($loan_schedule, $loan_repayment, $select_date = null){
        $overdue_arr = [];
        $last_paid_date = null;
        if($select_date == null){
            $select_date = date('Y-m-d');
        }
        $overdue = 0;
        if(!empty($loan_repayment) && count($loan_repayment) > 0){
            $last_payment =  $loan_repayment->last();
            $last_month_idx = $last_payment->payment_month;
            $last_paid_date = $last_payment->repayment_date;
            $next_schedule = $loan_schedule[$last_month_idx]->schedule_date;
            if($last_payment->status == 0 && $last_payment->condition_id == 1){
                $overdue = date_dif($last_paid_date, $select_date);
            }else{
                $overdue = date_dif($next_schedule, $select_date);
            }
        }else{ // no repayment
            $next_schedule = $loan_schedule[0]->schedule_date;
            $overdue =  date_dif($next_schedule, $select_date);
        }
        array_push($overdue_arr, [$last_paid_date, $overdue]);
        return $overdue_arr;
    }

    public static function str2number($num, $st=null){
        $num = explode($st, $num);
        if($num[1]){
            $num = $num[1];
        }else{
            $num = $num[0];
        }

        $num = str_replace(',', '', $num);
        return trim($num);
    }

    public static function getDownpayment($repayment_array, $admin_fee = 0, $admin_fee_opt = '0', $maintain_fee = 0,
                                      $maintain_fee_opt = '0', $other_fee = 0, $l_repayment_type = 0, $c_fee = 0, $is_disburse, $count = 0,
                                      $disburse_on, $l_start_date, $loan, $change_digit = 0,$repayment_array_loan,$sche_type = 'loan'){
        $data = null;
        $total_days = 0;
        $total_interest = 0;
        $total_principal = 0;
        $total_admin_fee = 0;
        $total_maintain_fee = 0;
        $total_other_fee = 0;
        $total_fee = 0;
        $total_monthly = 0;
        $total_principal_bal = 0;
        $end_month = 0;
        $tb_sch = [];
        $f_admin_fee = $f_maintain_fee = 0;
        $amount_arr = $date_arr = [];
        $loan_amount = intval($repayment_array[0][5]);
        $amount_loans = intval($repayment_array_loan[0][5]);
        //other fee
        if(floatval($other_fee) > 0){
            $c_other_fee = round(floatval($other_fee) / (count($repayment_array) - 13) , $change_digit);
            $end_month = count($repayment_array) - 13;
        }else{
            $c_other_fee = 0;
        }

        if(!empty($repayment_array)) {
            // Special Case: Disburse and manua
            if(( $l_repayment_type == 8 && $is_disburse == '1') || ($is_disburse == '1') ||(!is_null($loan) && $is_disburse == '1' && $disburse_on == $loan->schedule[0]->schedule_date && $l_start_date == $loan->schedule[1]->schedule_date)){
                $amount_arr[0] = -1 * intval($repayment_array[0][6]);
                $date_arr[0] = strtotime($repayment_array[0][0]);
                // $schedule = $loan->schedule;
                $schedule = RepaymentSchedule::where('loan_id',intval($loan->id))->where('type',(string)$sche_type)->get();
                $balance = floatval($loan->loan_amount + $loan->loan_amount);
                for ($i = 0; $i < count($schedule); $i++) {
                    $tb_sch[$i]['date'] = $schedule[$i]['schedule_date'];
                    $tb_sch[$i]['day'] = $schedule[$i]['date_num'];
                    $tb_sch[$i]['int'] = $schedule[$i]['interest'];
                    $tb_sch[$i]['prin'] = $schedule[$i]['principal'];
                    $tb_sch[$i]['fee'] = $schedule[$i]['fee'];
                    $tb_sch[$i]['other_fee'] = $schedule[$i]['other_fee'];
                    $tb_sch[$i]['monthly'] = $tb_sch[$i]['int'] + $tb_sch[$i]['prin'] + $tb_sch[$i]['fee'] + $tb_sch[$i]['other_fee'] ;
                    $balance -= $tb_sch[$i]['prin'];
                    $tb_sch[$i]['balance'] = $balance;
                    $tb_sch[$i]['intra_rate'] = $schedule[$i]['intraday_rate'];
                    $date_arr[$i] = strtotime($schedule[$i]['schedule_date']);
                    $amount_arr[$i] += $tb_sch[$i]['monthly'];
                    $total_days += $tb_sch[$i]['day'];
                    $total_interest += $tb_sch[$i]['int'];
                    $total_principal += $tb_sch[$i]['prin'];
                    $total_fee += $tb_sch[$i]['fee'];
                    $total_monthly += $tb_sch[$i]['monthly'];
                }

            }else {
                $amount_arr[0] = -1 * intval($repayment_array[0][5]);
                $date_arr[0] = strtotime($repayment_array[0][0]);
                $c_maintain_fee = $c_admin_fee = 0;

                $tb_sch[0]['date'] = $repayment_array[0][0];
                $tb_sch[0]['day'] = '-';
                $tb_sch[0]['int'] = '-';
                $tb_sch[0]['prin'] = '-';
                $tb_sch[0]['other_fee'] = '-';

                if($l_repayment_type == 8){
                    if ($admin_fee_opt == '0') {
                        $f_admin_fee = round($admin_fee * $loan_amount / 100,$change_digit);
                    }
                    if ($maintain_fee_opt == '0') {
                        $f_maintain_fee = round($maintain_fee * $loan_amount / 100,$change_digit);
                    }
                    $tb_sch[0]['fee'] = $f_admin_fee + $f_maintain_fee;
                }else {
                    if ($admin_fee_opt == '0') {
                        if ($count == 0) {
                            $f_admin_fee = round($admin_fee * $loan_amount / 100,$change_digit);
                        } else {
                            $f_admin_fee = 0;
                        }
                        $tb_sch[0]['fee'] = $f_admin_fee;
                        $c_admin_fee = 0;
                    } elseif ($admin_fee_opt == '1') {
                        $f_admin_fee = 0;
                        $c_admin_fee = round(($admin_fee * $loan_amount / 100) / (count($repayment_array) - 1),$change_digit);
                        $tb_sch[0]['fee'] = $f_admin_fee;
                    } elseif ($admin_fee_opt == '2') {
                        $f_admin_fee = round(($admin_fee * $loan_amount / 100) / (1 + (count($repayment_array) - 1) / 12),$change_digit);
                        $c_admin_fee = $f_admin_fee;
                        $tb_sch[0]['fee'] = $c_admin_fee;
                    } else { // 3
                        $f_admin_fee = 0;
                        $c_admin_fee = $f_admin_fee;
                        $tb_sch[0]['fee'] = $c_admin_fee;
                    }
                    $total_admin_fee += $f_admin_fee;
                    if ($maintain_fee_opt == '0') {
                        if ($count == 0) {
                            $f_maintain_fee = round($maintain_fee * $loan_amount / 100,$change_digit);
                        } else {
                            $f_maintain_fee = 0;
                        }
                        $tb_sch[0]['fee'] += $f_maintain_fee;
                        $c_maintain_fee = 0;
                    } elseif ($maintain_fee_opt == '1') {
                        $f_maintain_fee = 0;
                        $c_maintain_fee = ROUND(($maintain_fee * $loan_amount / 100) / (count($repayment_array) - 1), $change_digit);
                        $tb_sch[0]['fee'] += $f_maintain_fee;
                    } elseif ($maintain_fee_opt == '2') {
                        $c_maintain_fee = ROUND(($maintain_fee * $loan_amount / 100) / (1 + (count($repayment_array) - 1) / 12), $change_digit);
                        $f_maintain_fee = $c_maintain_fee;
                        $tb_sch[0]['fee'] += $f_maintain_fee;
                    } else { // 3
                        $c_maintain_fee = 0;
                        $f_maintain_fee = $c_maintain_fee;
                        $tb_sch[0]['fee'] += $f_maintain_fee;
                    }
                }
                $total_maintain_fee += $f_maintain_fee;
                $amount_arr[0] += $tb_sch[0]['fee'];
                $total_fee += $tb_sch[0]['fee'];

                $tb_sch[0]['other_fee'] = 0;
                $tb_sch[0]['monthly'] = $tb_sch[0]['fee'];
                $total_monthly += $tb_sch[0]['monthly'];
                $tb_sch[0]['balance'] = floatval($repayment_array[0][5]);
                $tb_sch[0]['intra_rate'] = 0;
                
                for ($i = 1; $i < count($repayment_array); $i++) {
                    $tb_sch[$i]['date'] = $repayment_array[$i][0];
                    $tb_sch[$i]['day'] = $repayment_array[$i][1];
                    $total_days += $repayment_array[$i][1];
                    $tb_sch[$i]['int'] = $repayment_array[$i][2];
                    $total_interest += $repayment_array[$i][2];
                    $tb_sch[$i]['prin'] = $repayment_array[$i][3];
                    $total_principal += $repayment_array[$i][3];

                    if($l_repayment_type == 8){
                        $tb_sch[$i]['fee'] = $repayment_array[$i][7];
                        $total_maintain_fee += $tb_sch[$i]['fee'];
                    }else {
                        if ($admin_fee_opt == '0') {
                            $tb_sch[$i]['fee'] = 0;
                        } elseif ($admin_fee_opt == '2') {
                            if ($i % 12 == 0) {
                                $tb_sch[$i]['fee'] = $c_admin_fee;
                                $total_admin_fee += $c_admin_fee;
                            } else {
                                $tb_sch[$i]['fee'] = 0;
                                $total_admin_fee += 0;
                            }
                        } elseif ($admin_fee_opt == '3') {
                            if ($l_repayment_type == 9) {
                                $c_admin_fee = 0;
                            } else {
                                $c_admin_fee = ROUND(($admin_fee * $repayment_array[$i - 1][5] * $tb_sch[$i]['day']  / (30*100)), $change_digit);
                            }
                            $tb_sch[$i]['fee'] = $c_admin_fee;
                            $total_admin_fee += $c_admin_fee;
                        } else {
                            $tb_sch[$i]['fee'] = $c_admin_fee;
                            $total_admin_fee += $c_admin_fee;
                        }

                        if ($maintain_fee_opt == '0') {
                            $tb_sch[$i]['fee'] += 0;
                        } elseif ($maintain_fee_opt == '2') {
                            if ($i % 12 == 0) {
                                $tb_sch[$i]['fee'] += $c_maintain_fee;
                                $total_maintain_fee += $c_maintain_fee;
                            } else {
                                $tb_sch[$i]['fee'] += 0;
                                $total_maintain_fee += 0;
                            }
                        } elseif ($maintain_fee_opt == '3') {
                            if ($l_repayment_type == 9) {
                                $c_maintain_fee = $repayment_array[$i][7];
                            } else {
                                $c_maintain_fee = ROUND($maintain_fee * $repayment_array[$i - 1][5] * $tb_sch[$i]['day'] / (30*100), $change_digit);
                            }
                            $tb_sch[$i]['fee'] += $c_maintain_fee;
                            $total_maintain_fee += $c_maintain_fee;
                        } else {
                            $tb_sch[$i]['fee'] += $c_maintain_fee;
                            $total_maintain_fee += $c_maintain_fee;
                        }
                        // other fee
                        if($i <= $end_month){
                            $tb_sch[$i]['other_fee'] = $c_other_fee;
                            if($i == $end_month) $tb_sch[$i]['other_fee'] = floatval($other_fee) - $total_other_fee;
                        }else{
                            $tb_sch[$i]['other_fee'] = 0;
                        }
                        $total_other_fee += $tb_sch[$i]['other_fee'];
                    }
                    //if ($l_repayment_type == '8' && ($admin_fee_opt != '3' || $maintain_fee_opt != '3') && ($c_fee != '0' || $c_fee != '')) $tb_sch[$i]['fee'] = floatval($c_fee);
                    //if ($l_repayment_type == '8' && ($admin_fee_opt != '3' || $maintain_fee_opt != '3')) $tb_sch[$i]['fee'] = floatval($repayment_array[$i][7]);
                    $total_fee += $tb_sch[$i]['fee'];
                    $tb_sch[$i]['monthly'] = $repayment_array[$i][2] + $repayment_array[$i][3] + $tb_sch[$i]['fee'] + $tb_sch[$i]['other_fee'];
                    $total_monthly += $tb_sch[$i]['monthly'];
                    $tb_sch[$i]['balance'] = $repayment_array[$i][5];
                    $total_principal_bal += $repayment_array[$i][5];
                    $tb_sch[$i]['intra_rate'] = $repayment_array[$i][6];
                    if($l_repayment_type == 9){
                        $amount_arr[$i] = $repayment_array[$i][4];
                    }else {
                        $amount_arr[$i] = $repayment_array[$i][4] + $c_maintain_fee;
                    }
                    $date_arr[$i] = strtotime($repayment_array[$i][0]);
                }
            }
            if(!$loan){
                unset($tb_sch[0]);
            }
            $data['tb_sch'] = $tb_sch;
            if ($maintain_fee_opt == '0') $data['total_fee'] += $maintain_fee;
            // Annual Yield
            $f = new FinancialClassController();
            $data['anunal_yield'] = $f->XIRR($amount_arr, $date_arr, 0.1) * 100;
            $data['total_days'] = $total_days;
            $data['total_interest'] = $total_interest;
            $data['total_principal'] = $total_principal;
            $data['total_fee'] = $total_fee;
            $data['total_other_fee'] = $total_other_fee;
            $data['total_monthly'] = $total_monthly;
            $data['total_admin_fee'] = $total_admin_fee;
            $data['total_maintain_fee'] = $total_maintain_fee;
        }
        return $data;
    }

    public static function getLoanSch($repayment_array, $admin_fee = 0, $admin_fee_opt = '0', $maintain_fee = 0,
                                      $maintain_fee_opt = '0', $other_fee = 0, $l_repayment_type = 0, $c_fee = 0, $is_disburse, $count = 0,
                                      $disburse_on, $l_start_date, $loan, $change_digit = 0,$sche_type = 'loan'){
        $data = null;
        $total_days = 0;
        $total_interest = 0;
        $total_principal = 0;
        $total_admin_fee = 0;
        $total_maintain_fee = 0;
        $total_other_fee = 0;
        $total_fee = 0;
        $total_monthly = 0;
        $total_principal_bal = 0;
        $end_month = 0;
        $tb_sch = [];
        $f_admin_fee = $f_maintain_fee = 0;
        $amount_arr = $date_arr = [];
        $loan_amount = intval($repayment_array[0][5]);
        //other fee
        if(floatval($other_fee) > 0){
            $c_other_fee = round(floatval($other_fee) / (count($repayment_array) - 13) , $change_digit);
            $end_month = count($repayment_array) - 13;
        }else{
            $c_other_fee = 0;
        }

        if(!empty($repayment_array)) {
            // Special Case: Disburse and manua
            if(( $l_repayment_type == 8 && $is_disburse == '1') || ($is_disburse == '1') ||(!is_null($loan) && $is_disburse == '1' && $disburse_on == $loan->schedule[0]->schedule_date && $l_start_date == $loan->schedule[1]->schedule_date)){
                $amount_arr[0] = -1 * intval($repayment_array[0][6]);
                $date_arr[0] = strtotime($repayment_array[0][0]);
                // $schedule = $loan->schedule;
                $schedule = RepaymentSchedule::where('loan_id',intval($loan->id))->where('type',(string)$sche_type)->get();
                $balance = floatval($loan->loan_amount);
                for ($i = 0; $i < count($schedule); $i++) {
                    $tb_sch[$i]['date'] = $schedule[$i]['schedule_date'];
                    $tb_sch[$i]['day'] = $schedule[$i]['date_num'];
                    $tb_sch[$i]['int'] = $schedule[$i]['interest'];
                    $tb_sch[$i]['prin'] = $schedule[$i]['principal'];
                    $tb_sch[$i]['fee'] = $schedule[$i]['fee'];
                    $tb_sch[$i]['other_fee'] = $schedule[$i]['other_fee'];
                    $tb_sch[$i]['monthly'] = $tb_sch[$i]['int'] + $tb_sch[$i]['prin'] + $tb_sch[$i]['fee'] + $tb_sch[$i]['other_fee'] ;
                    $balance -= $tb_sch[$i]['prin'];
                    $tb_sch[$i]['balance'] = $balance;
                    $tb_sch[$i]['intra_rate'] = $schedule[$i]['intraday_rate'];
                    $date_arr[$i] = strtotime($schedule[$i]['schedule_date']);
                    $amount_arr[$i] += $tb_sch[$i]['monthly'];
                    $total_days += $tb_sch[$i]['day'];
                    $total_interest += $tb_sch[$i]['int'];
                    $total_principal += $tb_sch[$i]['prin'];
                    $total_fee += $tb_sch[$i]['fee'];
                    $total_monthly += $tb_sch[$i]['monthly'];
                }

            }else {
                $amount_arr[0] = -1 * intval($repayment_array[0][5]);
                $date_arr[0] = strtotime($repayment_array[0][0]);
                $c_maintain_fee = $c_admin_fee = 0;

                $tb_sch[0]['date'] = $repayment_array[0][0];
                $tb_sch[0]['day'] = '-';
                $tb_sch[0]['int'] = '-';
                $tb_sch[0]['prin'] = '-';
                $tb_sch[0]['other_fee'] = '-';

                if($l_repayment_type == 8){
                    if ($admin_fee_opt == '0') {
                        $f_admin_fee = round($admin_fee * $loan_amount / 100,$change_digit);
                    }
                    if ($maintain_fee_opt == '0') {
                        $f_maintain_fee = round($maintain_fee * $loan_amount / 100,$change_digit);
                    }
                    $tb_sch[0]['fee'] = $f_admin_fee + $f_maintain_fee;
                }else {
                    if ($admin_fee_opt == '0') {
                        if ($count == 0) {
                            $f_admin_fee = round($admin_fee * $loan_amount / 100,$change_digit);
                        } else {
                            $f_admin_fee = 0;
                        }
                        $tb_sch[0]['fee'] = $f_admin_fee;
                        $c_admin_fee = 0;
                    } elseif ($admin_fee_opt == '1') {
                        $f_admin_fee = 0;
                        $c_admin_fee = round(($admin_fee * $loan_amount / 100) / (count($repayment_array) - 1),$change_digit);
                        $tb_sch[0]['fee'] = $f_admin_fee;
                    } elseif ($admin_fee_opt == '2') {
                        $f_admin_fee = round(($admin_fee * $loan_amount / 100) / (1 + (count($repayment_array) - 1) / 12),$change_digit);
                        $c_admin_fee = $f_admin_fee;
                        $tb_sch[0]['fee'] = $c_admin_fee;
                    } else { // 3
                        $f_admin_fee = 0;
                        $c_admin_fee = $f_admin_fee;
                        $tb_sch[0]['fee'] = $c_admin_fee;
                    }
                    $total_admin_fee += $f_admin_fee;
                    if ($maintain_fee_opt == '0') {
                        if ($count == 0) {
                            $f_maintain_fee = round($maintain_fee * $loan_amount / 100,$change_digit);
                        } else {
                            $f_maintain_fee = 0;
                        }
                        $tb_sch[0]['fee'] += $f_maintain_fee;
                        $c_maintain_fee = 0;
                    } elseif ($maintain_fee_opt == '1') {
                        $f_maintain_fee = 0;
                        $c_maintain_fee = ROUND(($maintain_fee * $loan_amount / 100) / (count($repayment_array) - 1), $change_digit);
                        $tb_sch[0]['fee'] += $f_maintain_fee;
                    } elseif ($maintain_fee_opt == '2') {
                        $c_maintain_fee = ROUND(($maintain_fee * $loan_amount / 100) / (1 + (count($repayment_array) - 1) / 12), $change_digit);
                        $f_maintain_fee = $c_maintain_fee;
                        $tb_sch[0]['fee'] += $f_maintain_fee;
                    } else { // 3
                        $c_maintain_fee = 0;
                        $f_maintain_fee = $c_maintain_fee;
                        $tb_sch[0]['fee'] += $f_maintain_fee;
                    }
                }
                $total_maintain_fee += $f_maintain_fee;
                $amount_arr[0] += $tb_sch[0]['fee'];
                $total_fee += $tb_sch[0]['fee'];

                $tb_sch[0]['other_fee'] = 0;
                $tb_sch[0]['monthly'] = $tb_sch[0]['fee'];
                $total_monthly += $tb_sch[0]['monthly'];
                $tb_sch[0]['balance'] = floatval($repayment_array[0][5]);
                $tb_sch[0]['intra_rate'] = 0;
                
                for ($i = 1; $i < count($repayment_array); $i++) {
                    $tb_sch[$i]['date'] = $repayment_array[$i][0];
                    $tb_sch[$i]['day'] = $repayment_array[$i][1];
                    $total_days += $repayment_array[$i][1];
                    $tb_sch[$i]['int'] = $repayment_array[$i][2];
                    $total_interest += $repayment_array[$i][2];
                    $tb_sch[$i]['prin'] = $repayment_array[$i][3];
                    $total_principal += $repayment_array[$i][3];

                    if($l_repayment_type == 8){
                        $tb_sch[$i]['fee'] = $repayment_array[$i][7];
                        $total_maintain_fee += $tb_sch[$i]['fee'];
                    }else {
                        if ($admin_fee_opt == '0') {
                            $tb_sch[$i]['fee'] = 0;
                        } elseif ($admin_fee_opt == '2') {
                            if ($i % 12 == 0) {
                                $tb_sch[$i]['fee'] = $c_admin_fee;
                                $total_admin_fee += $c_admin_fee;
                            } else {
                                $tb_sch[$i]['fee'] = 0;
                                $total_admin_fee += 0;
                            }
                        } elseif ($admin_fee_opt == '3') {
                            if ($l_repayment_type == 9) {
                                $c_admin_fee = 0;
                            } else {
                                $c_admin_fee = ROUND(($admin_fee * $repayment_array[$i - 1][5] * $tb_sch[$i]['day']  / (30*100)), $change_digit);
                            }
                            $tb_sch[$i]['fee'] = $c_admin_fee;
                            $total_admin_fee += $c_admin_fee;
                        } else {
                            $tb_sch[$i]['fee'] = $c_admin_fee;
                            $total_admin_fee += $c_admin_fee;
                        }

                        if ($maintain_fee_opt == '0') {
                            $tb_sch[$i]['fee'] += 0;
                        } elseif ($maintain_fee_opt == '2') {
                            if ($i % 12 == 0) {
                                $tb_sch[$i]['fee'] += $c_maintain_fee;
                                $total_maintain_fee += $c_maintain_fee;
                            } else {
                                $tb_sch[$i]['fee'] += 0;
                                $total_maintain_fee += 0;
                            }
                        } elseif ($maintain_fee_opt == '3') {
                            if ($l_repayment_type == 9) {
                                $c_maintain_fee = $repayment_array[$i][7];
                            } else {
                                $c_maintain_fee = ROUND($maintain_fee * $repayment_array[$i - 1][5] * $tb_sch[$i]['day'] / (30*100), $change_digit);
                            }
                            $tb_sch[$i]['fee'] += $c_maintain_fee;
                            $total_maintain_fee += $c_maintain_fee;
                        } else {
                            $tb_sch[$i]['fee'] += $c_maintain_fee;
                            $total_maintain_fee += $c_maintain_fee;
                        }
                        // other fee
                        if($i <= $end_month){
                            $tb_sch[$i]['other_fee'] = $c_other_fee;
                            if($i == $end_month) $tb_sch[$i]['other_fee'] = floatval($other_fee) - $total_other_fee;
                        }else{
                            $tb_sch[$i]['other_fee'] = 0;
                        }
                        $total_other_fee += $tb_sch[$i]['other_fee'];
                    }
                    //if ($l_repayment_type == '8' && ($admin_fee_opt != '3' || $maintain_fee_opt != '3') && ($c_fee != '0' || $c_fee != '')) $tb_sch[$i]['fee'] = floatval($c_fee);
                    //if ($l_repayment_type == '8' && ($admin_fee_opt != '3' || $maintain_fee_opt != '3')) $tb_sch[$i]['fee'] = floatval($repayment_array[$i][7]);
                    $total_fee += $tb_sch[$i]['fee'];
                    $tb_sch[$i]['monthly'] = $repayment_array[$i][2] + $repayment_array[$i][3] + $tb_sch[$i]['fee'] + $tb_sch[$i]['other_fee'];
                    $total_monthly += $tb_sch[$i]['monthly'];
                    $tb_sch[$i]['balance'] = $repayment_array[$i][5];
                    $total_principal_bal += $repayment_array[$i][5];
                    $tb_sch[$i]['intra_rate'] = $repayment_array[$i][6];
                    if($l_repayment_type == 9){
                        $amount_arr[$i] = $repayment_array[$i][4];
                    }else {
                        $amount_arr[$i] = $repayment_array[$i][4] + $c_maintain_fee;
                    }
                    $date_arr[$i] = strtotime($repayment_array[$i][0]);
                }
            }
            if(!$loan){
                unset($tb_sch[0]);
            }
            $data['tb_sch'] = $tb_sch;
            if ($maintain_fee_opt == '0') $data['total_fee'] += $maintain_fee;
            // Annual Yield
            $f = new FinancialClassController();
            $data['anunal_yield'] = $f->XIRR($amount_arr, $date_arr, 0.1) * 100;
            $data['total_days'] = $total_days;
            $data['total_interest'] = $total_interest;
            $data['total_principal'] = $total_principal;
            $data['total_fee'] = $total_fee;
            $data['total_other_fee'] = $total_other_fee;
            $data['total_monthly'] = $total_monthly;
            $data['total_admin_fee'] = $total_admin_fee;
            $data['total_maintain_fee'] = $total_maintain_fee;
        }
        return $data;
    }

    public static function fee_cal($fee_opt, $fee_rate, $loan_amount, $os_bal, $month_idx, $tenure,$round_opt='UP',$change_digit=0,$days = 0){
        $fee = 0;
        if ($fee_opt == '0') { // One time
            if($month_idx == 0){
                $fee = $fee_rate * $loan_amount / 100;
            }else{
                $fee = 0;
            }
        } elseif ($fee_opt == '1') { // Monthly
            $fee = ($fee_rate  * $loan_amount / 100) / $tenure;
        } elseif ($fee_opt == '2') { // Yearly
            if($month_idx%12 == 0) $fee = ($fee_rate  * $loan_amount / 100)/ (1 + $tenure / 12);
        } else{ // With OS
            $fee = ($fee_rate  * $os_bal * $days / (30*100));
        }
        return ROUND($fee,$change_digit,$round_opt);
    }
    public static function get_xirr($loan){
        $amount_arr = $date_arr = [];
        $schedule = $loan->schedule;
        for($i = 0; $i < count($schedule); $i++){
            $sch = $schedule[$i];
            if($sch->no == 0){
                $amount_arr[$i] = -$loan->loan_amount;
            }else{
                $amount_arr[$i] = $sch->principal + $sch->interest + $sch->fee;
            }
            $date_arr[$i] = strtotime($sch->schedule_date);
        }
        // Annual Yield
        $f = new FinancialClassController();
        return $f->XIRR($amount_arr, $date_arr, 0.1) * 100;
    }
    public static function get_interest_ifrs($os_bal, $eir_rate, $date_num){
       return -$os_bal * ((1+$eir_rate/100)**($date_num/365)-1);
    }
    public static function addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
  }
}
