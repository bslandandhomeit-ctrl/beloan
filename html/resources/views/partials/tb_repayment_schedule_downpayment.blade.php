<?php
if (!empty($downpayment)) {
    $editable = false;
    if (isset($loan_status) && in_array($loan_status, [1, 2, 3, 7])) {
        $upm = session('ROLE_PERMISSION');
        if ($upm != null) {
            foreach ($upm as $p) {
                if ($p->code == 'ALL_FUNCTIONS' || strtolower($p->action_name) == 'update_repayment_sch') {
                    $editable = true;
                    break;
                }
            }
        }
    }

    $no = 0;
    $table = ''; // should not use array to first initialize because this structure is string not array. []
    $app_locale = app()->getLocale();
    $loan_sch = \App\Models\RepaymentSchedule::where('loan_id', $loan_id)->where('type','downpayment')->get();
    $loan = \App\Models\Loan::where('id', $loan_id)->first();
    foreach ($downpayment as $key => $sch) {
        if ($sch->no != "") $no = $sch->loan_no;
        if ($no == count($downpayment)) $no_cl = ' last_row';
        $table .= '<tr class="row_' . $no . $no_cl . '">';
        $table .= '<td style="text-align: center;">' . $no . '</td>';
        if ($app_locale == 'kh') {
            $day = khmerShortDay(date('D', strtotime($sch->schedule_date)));
            $month = khmerMonth(date('M', strtotime($sch->schedule_date)));
            $table .= '<td class="re_date" style="text-align: center;" class="col_' . $key . '">';
            $table .= date('' . $day . '. j ' . $month . ' Y', strtotime($sch->schedule_date));
        } else {
            $table .= '<td class="re_date" style="text-align: center;" class="col_' . $key . '">';
            $table .= date('d-M-Y', strtotime($sch->schedule_date));
        }
        $table .= '<td class="interest hidden" style="text-align: right;">' . number_format(0, 2) . '</td>';
        $table .= '<td  style="text-align: right;">' . number_format($sch->beginning, 2) . '</td>';
        $table .= '<td class="principal" style="text-align: right;">' . number_format($sch->principal, 2) . '</td>';
        $table .= '<td style="text-align: right;">' . number_format($sch->balance, 2) . '</td>';
        //$table .= '<td style="text-align: right;">' . number_format($sch[8], 2) . '</td>';
       // $table .= '<td style="text-align: right;">' . number_format($sch[5] + $sch[8], 2) . '</td>';
        //$table .= '<td style="text-align: right;">' . number_format($sch[6], 2) . '</td>';
        if ($editable == true) { //if($key > 0 && $editable == true){
            //var_dump($key);
            //var_dump($loan_sch);

            if($sch->status == 1){
                $table .= '<td class="invisible_edit"> <a style ="color:limegreen"><i class="fa fa-check"></i></a> </td>';
            }else{
               // $table .= '<td class="invisible_edit"><a href="javascript:;" class="btn btn-default btn-xs edit_data" id="' . $key . '"><i class="fa fa-pencil" ></i> </a> ';
                $table .= '<td class="invisible_edit"> ';
                $table .= '</td>';
            }

            //foreach ($loan_sch as $k => $l_sch) {
            //    //var_dump($l_sch);
            //    if ($key == $k && $l_sch->status == 1) {
            //        $table .= '<a style ="color:limegreen"><i class="fa fa-check"></i> ssss</a> </td>';
            //        break;
            //    } else {
            //        $table .= '</td>';
            //    }
            //}
        } else {
            $table .= '<td class="invisible_edit"></td>';
        }
        $table .= '<td class="td-schedule-hide"></td>';
        $table .= '</tr>';
        $no++;
    }

    // Total row
    //$table .= '<tr style="border-top: double; font-weight: bold;">';
    //$table .= '<td colspan="2" style="text-align: right;">' . trans('report.rpt_total') . '</td>';
    //$table .= '<td style="text-align: center;">' . $total_days . '</td>';
    //$table .= '<td style="text-align: right;">' . number_format($total_interest, 2, '.', ',') . '</td>';
    //$table .= '<td style="text-align: right;">' . number_format($total_principal, 2, '.', ',') . '</td>';
    //$table .= '<td style="text-align: right;">' . number_format($total_fee, 2, '.', ',') . '</td>';
    // if($loan->other_fee > 0) 
    //$table .= '<td style="text-align: right;">' . number_format($total_other_fee, 2, '.', ',') . '</td>';
   // $table .= '<td style="text-align: right;">' . number_format($total_monthly + $total_other_fee, 2, '.', ',') . '</td>';
    //$table .= '<td style="text-align: right;"></td>';
    //$table .= '<td class="td-schedule-hide"></td>';
    //$table .= '</tr>';
    //$table .= '</tbody>';

}
echo $table;
?>