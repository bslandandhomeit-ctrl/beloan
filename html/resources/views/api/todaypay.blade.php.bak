<h4 class="sch_title">{{ trans('loan.l_detail_repayment_until_today') }}</h4>
<?php
    $date_set = date('Y-m-d');
    $result = LoanCalculate::getTotalPenalty($loan)[0];
    if($loan->status == 9){
        echo "The loan has been paid off.";
    }elseif($loan->status == 10){
        echo "The loan is completed.";
    }else{
        $table = '<table class="table table-bordered detail_repayment">';
           $table .= '<thead style="text-align: center">';
           $table .= '<th style="text-align: center">'.trans('multiple.m_no').'</th>';
           $table .= '<th style="text-align: center">'.trans('report.schedule_date').'</th>';
           $table .= '<th style="text-align: center">'.trans('report.rpt_interest').'</th>';
           $table .= '<th style="text-align: center">'.trans('report.rpt_principal').'</th>';
           $table .= '<th style="text-align: center">'.trans('report.rpt_fee').'</th>';
           $table .= '<th style="text-align: center">'.trans('report.rpt_other_fee').'</th>';
           $table .= '<th style="text-align: center">'.trans('multiple.penalty').'</th>';
           $table .= '<th style="text-align: center">'.trans('loan.l_rate').'(%)</th>';
           $table .= '<th style="text-align: center">'.trans('loan.l_rate').'($)</th>';
           $table .= '<th style="text-align: center">'.trans('report.num_days').'</th>';
           $table .= '<th style="text-align: center">'.trans('report.overdue').'</th>';
           $table .= '<th style="text-align: center">'.trans('report.rpt_total_penalty').'</th>';
           $table .= '<th style="text-align: center">'.trans('report.rpt_total_amount').'</th>';
           $table .='</thead>';

       $no = 0;
       $total_principal = $total_interest = $total_fee = $total_other_fee = 0;
        $total_penalty = 0;
        $total = 0;
        $arrear_date = 0;
        if(count($result) > 0){
            for($i = 1; $i <= $loan->loan_duration; $i++){
                if(empty($result[$i])){
                    continue;
                }
                //$arrear_date = ($result[$i][3] == 0)? date_dif($result[$i][0], $date_set,1,false) : $result[$i][7];
                $no++;
                $table .= '<tr>';
                 $table .= '<td align="center">'.$no.'</td>';
                 $table .= '<td align="center">'. date('d-M-Y',strtotime($result[$i][0])) .'</td>';
                 $table .= '<td align="right">'.(number_format($result[$i][2], 2, '.', ',')) .'</td>';
                 $table .= '<td align="right">'.(number_format($result[$i][1], 2, '.', ',')) .'</td>';
                 $table .= '<td align="right">'.(number_format($result[$i][10], 2, '.', ',')) .'</td>';
                 $table .= '<td align="right">'.(number_format($result[$i][11], 2, '.', ',')) .'</td>';
                 $table .= '<td align="right">'. (number_format($result[$i][4], 2, '.', ',')) .'</td>';
                 $table .= '<td align="right">'. number_format($result[$i][5], 2, '.', ',') .'</td>';
                 $table .= '<td align="right">'. number_format($result[$i][6], 2, '.', ',') .'</td>';
                 $table .= '<td align="center">'. $result[$i][7]  .'</td>';
                 $table .= '<td align="center">'. date_dif($result[$i][0], $date_set,1,false) .'</td>';
                 $table .= '<td align="right">'. number_format($result[$i][8], 2, '.', ',') .'</td>';
                 $table .= '<td align="right">'. number_format($result[$i][9], 2, '.', ',') .'</td>';
               $table .= '</tr>';
               $total_principal += $result[$i][1];
               $total_interest += $result[$i][2];
               $total_fee += $result[$i][10];
               $total_other_fee += $result[$i][11];
               $total_penalty += $result[$i][8];
               $total += $result[$i][9];
            }
        }

        $table .='<tr style="font-weight: bold"><td colspan="2" align="right">'.trans('report.rpt_total').'</td><td align="right">'. number_format($total_interest, 2, '.', ',') .'</td><td align="right">'. number_format($total_principal, 2, '.', ',') .'</td><td align="right">'. number_format($total_fee, 2, '.', ',') .'</td><td align="right">'. number_format($total_other_fee, 2, '.', ',') .'</td><td colspan="5"></td><td align="right">'. number_format($total_penalty, 2, '.', ',') .'</td><td align="right">'. number_format($total, 2, '.', ',') .'</td></tr>';
        $table .= '</table>';

        echo $table;
    }
?>
