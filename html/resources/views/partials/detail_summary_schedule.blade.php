<?php
$results = [];
if (!empty($loans) && count($loans) > 0) {
    foreach ($loans as $l) {
        $repayment_array = LoanCalculate::loan_schedule($l->schedule, $l->start_date)[0];
        $principal = 0.0;
        $interest = 0.0;
        $current_month = $date_search;
        if (!empty($repayment_array) && count($repayment_array) > 0) {
            foreach ($repayment_array as $key => $v) {
                if ($key === 0)
                    continue;
                $each_date = date('Y-m', strtotime($v[0]));
                if ($current_month == $each_date) {
                    $interest = $v[2];
                    $principal = $v[3];
                    $date = date('Y-m-d', strtotime($v[0]));
                    if (!isset($results[$date]))
                        $results[$date] = [];
                    $results[$date][] = [$l, $interest, $principal];
                    break;
                }
            }
        }
        //if($cnt == 2) dd($results);
    }
}
ksort($results);
?>
<table class="table table-bordered table-striped table-condensed" id="editable-sample">
        <thead style="background-color: #FFD433">
            <tr>
                <th style="vertical-align:middle; text-align:center;">{{ trans('multiple.m_no') }}</th>
                <th style="vertical-align:middle; text-align:center;">{{ trans('report.rpt_contract_id') }}</th>
                <th style="vertical-align:middle; text-align:center;">{{ trans('customer.cus_customer_name') }}</th>
                <th style="vertical-align: middle; text-align:center;">{{ trans('multiple.m_phone',['num'=>'']) }}</th>
                <th style="vertical-align:middle; text-align:center;">{{ trans('report.rpt_co_name') }}</th>
                <th style="vertical-align:middle; text-align:center;">{{ trans('multiple.m_address') }}</th>
                <th style="vertical-align:middle; text-align:center;">{{ trans('multiple.balance') }}</th>
                <th style="vertical-align:middle; text-align:center;">{{ trans('report.rpt_principal') }}</th>
                <th style="vertical-align:middle; text-align:center;">{{ trans('report.rpt_interest') }}</th>
                <th style="vertical-align:middle; text-align:center;">{{ trans('report.rpt_fee') }}</th>
                <th style="vertical-align:middle; text-align:center;">{{ trans('report.rpt_penalty') }}</th>
                <th style="vertical-align:middle; text-align:center;">{{ trans('report.rpt_total') }}</th>
            </tr>
        </thead>
    <tbody>
        <?php
          $n = 0;
          $n+=0;
          $all_total = 0.0;
  	      $today = date("Y-m-d");
        ?>
        @if(!empty($results))
        @foreach($results as $key => $r)
        <tr>
            <td colspan="12" style="background-color:#33CCCC; color: #000000;">{{ date('l F d, Y',strtotime($key)) }}</td>
        </tr>
        <?php
          $n = 0;
          $total_amount = 0.0;
          $total_principal = 0.0;
          $total_interest = 0.0;
          $total_fee = 0.0;
          $total_penalty = 0.0;
          $total_paid_interest = 0.0;
          $total_paid_principal = 0.0;
          $total = 0.0;
  		    $total_pass_due = 0;
        ?>
        @foreach($r as $l)
          <?php
          $l = $l[0];
          $principal = 0.0;
          $interest = 0.0;
          $arr_principal = 0.0;
          $arr_interest = 0.0;
          $arr_penalty = 0.0;
          $arr_fee = 0.0;
          $sub_total = 0;
  		    $pass_due = 0;
          $current_month = $date_search;
          $repayment_array = LoanCalculate::loan_schedule($l->schedule, $l->start_date)[0];

          if(date_dif($key, $today,1, false)> 0) $key = $today;
          $result_total_penalty = LoanCalculate::getTotalPenalty($l,$key);

          //if($l->client_name == 'Pov Cheng') dd($result_total_penalty);
          $result = $result_total_penalty[0];
          foreach($result as $res){
            $arr_principal += $res[1];
            $arr_interest += $res[2];
            $arr_fee += $res[10];
          }
          // penalty compared to today
          $arr_penalty = LoanCalculate::getTotalPenalty($l,$today)[4];
  	      $sub_total += $arr_principal + $arr_interest + $arr_fee + $arr_penalty;
          $total_amount += $sub_total;
          $total_principal += $arr_principal;
          $total_interest += $arr_interest;
          $total_fee += $arr_fee;
          $total_penalty += $arr_penalty;
          /*
          for($i = 0; $i < $l->loan_duration; $i++){
              if(empty($result[$i])){
                  continue;
              }
              $pass_due += $result[$i][9];
          }
  		$total_pass_due += $pass_due;

  		$result_total_penalty = LoanCalculate::getTotalPenalty($l);
          $result = $result_total_penalty[0];
          for($i = 0; $i < $l->loan_duration; $i++){
              if(empty($result[$i])){
                  continue;
              }
              $total_payment += $result[$i][9];
          }

          if (!empty($repayment_array) && count($repayment_array) > 0) {
              $month_idx = 1;
              foreach ($repayment_array as $key => $v) {
                  if ($key === 0)
                      continue;
                  $each_date = date('Y-m', strtotime($v[0]));
                  $paid_interest = 0.0;
                  $paid_principal = 0.0;
                  if ($current_month == $each_date) {
                      $group_pays = [];
                      $group_pays = $l->payment->whereLoose('payment_month', $month_idx);
                      if (count($group_pays) == 0) {
                          $principal = $v[3];
                          $interest = $v[2];
                      } else {
                          $total_penalty = 0.0;
                          foreach ($group_pays as $k => $p) {
                              $paid_interest += floatval($p->paid_interest);
                              $paid_principal += floatval($p->paid_principal);
                              if ($p->status == 0) {
                                  $arr_interest = $v[2] - $paid_interest;
                                  $arr_principal = $v[3] - $paid_principal;
                                  $arr_penalty = floatval($p->repayment_owed) - ($arr_interest + $arr_principal);
                                  (round($arr_interest * 100) == 0) ? $interest = 0 : $interest = $arr_interest;
                                  (round($arr_principal * 100) == 0) ? $principal = 0 : $principal = $arr_principal;
                              } else {
                                  continue 3;
                              }
                          }
                      }
                      $total_paid_interest += $paid_interest;
                      $total_paid_principal += $paid_principal;
                      $total_amount += $principal + $interest;
                      $total_principal += $principal;
                      $total_interest += $interest;
                      $total += $total_amount;
                      break;
                  } else {
                      $month_idx++;
                  }
              }
          }
          */

          if (round(floatval($result_total_penalty[4])) == 0 && round(floatval($result_total_penalty[6])) == 0) {
              continue;
          }
          $n++;
          $all_total += $total_amount;
          ?>
        <tr>
            <td style="text-align: center;">{{$n}}</td>
            <td style="text-align: center;"><a href="{{ route('loan_detail', [$l->id])}}">{{$l->contract_id ? $l->contract_id: '-'}}</a></td>
            <td>{{  $l->client_name }}</td>
            <td>{{$l->phone1}}{{ !empty($l->phone2) ? '/'.$l->phone2  : ''}}</td>
            <td style="text-align: center;">{{!empty($l->co_user) && count($l->co_user) > 0 ? ($l->co_user->name) : '-'}}</td>
            <td style="text-align: center;">{{$l->address ? $l->address : '-'}}</td>
            <td style="text-align: right;">{{number_format($l->balance,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($arr_principal,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($arr_interest,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($arr_fee,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($arr_penalty,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($sub_total,2,'.',',')}}</td>
        </tr>
        @endforeach
        <tr style="font-weight: bold">
            <td colspan="7" style="text-align: right;">{{ trans('report.rpt_total') }}</td>
            <td style="text-align: right;">{{number_format($total_principal,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($total_interest,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($total_fee,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($total_penalty,2,'.',',')}}</td>
            <td style="text-align: right;">{{number_format($total_amount,2,'.',',')}}</td>
        </tr>
        <?php $all_total += $total; ?>
        @endforeach
        <tr>
            <td></td>
            <td colspan="10" style="text-align: right;font-weight: bold;">{{ trans('report.rpt_total') }}</td>
            <td style="text-align: right; font-weight: bold;">{{ number_format($all_total,2,'.',',') }}</td>
        </tr>
        @else
        <tr><td colspan="10">{{ trans('multiple.m_no_result') }}</td></tr>
        @endif
    </tbody>
</table>
<div class="page">
    <?PHP
    echo $loans->appends([
        'contract_id' => Input::get('contract_id'),
        'phone' => Input::get('phone'),
        'client_name' => Input::get('client_name'),
        'city' => Input::get('city'),
        'city' => Input::get('city'),
        'date' => Input::get('date'),
        'offset' => Input::get('offset')
    ])->render();
    ?>
</div>
