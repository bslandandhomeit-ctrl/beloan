@if(!empty($loan))
    <h4 class="sch_title">Statement</h4>
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

    $net_selling_price=0;
    $totalall_discount=floatval($loan->discount_promotion) + floatval($loan->discount_other) + floatval($loan->amount_discount_payment_option);
    $net_selling_price=floatval($loan->unit_sale_price) - floatval($totalall_discount);

    $paid=floatval($net_selling_price) / floatval($loan->payment->sum('paid_principal'));
    ?>
    <p style="float: right;font-weight: 700;width: 45%;">Issue date: <?php echo date('Y-m-d');?></p>
    <table class="table table-bordered actual_repayment">
        <thead>
        <tr>
            <th colspan="4" style="text-align: center; background-color: lightyellow;">Bill To : </th>
            <th colspan="7" style="text-align: center; background-color: palegreen;    width: 50%;">Account Summary</th>
        </tr>
        <tr>
            <th colspan="4">Customer Name : <span>{{$loan->client->client_name}}</span></th>
            <th colspan="7">Unit Price <span style="float: right;">{{number_format($loan->unit_sale_price,2,'.',',') }}</span></th>
        </tr>
        <tr>
            <th colspan="4">Unit No : <span>{{$loan->units->code}}</span></th>
            <th colspan="7">Discount (Promotion) <span style="float: right;">{{number_format($loan->discount_promotion,2,'.',',') }}</span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7">Discount (Other) <span style="float: right;">{{number_format($loan->discount_other,2,'.',',') }}</span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7">Discount payment option <span style="float: right;">{{number_format($loan->amount_discount_payment_option,2,'.',',') }}</span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7">Net Selling Price: <span style="float: right;">{{number_format($net_selling_price,2,'.',',') }}</span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7">Re-Structure Loan: <span style="float: right;"></span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7">Clearance Amount: <span style="float: right;">{{number_format($loan->clearance_amount,2,'.',',') }}</span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7">Down Payment: <span style="float: right;">{{number_format($loan->down_payment_value,2,'.',',') }}</span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7">Loan Amount: <span style="float: right;">{{number_format($loan->loan_amount,2,'.',',') }}</span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7">Previously Paid: <span style="float: right;">{{number_format($loan->payment->sum('paid_principal') + $loan->payment->sum('paid_interest'),2,'.',',') }}</span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7">Principal Paid: <span style="float: right;">{{number_format($loan->payment->sum('paid_principal'),2,'.',',') }}</span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7">Interest Paid: <span style="float: right;">{{number_format($loan->payment->sum('paid_interest'),2,'.',',') }}</span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7">Interest Accrual: <span style="float: right;">{{$loan->annual_interest }}</span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7">Paid %: <span style="float: right;">{{number_format($paid,2,'.',',') }}</span></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="7"><b>Outstanding Balance:</b> <span style="float: right;font-weight: 700;">{{number_format($loan->client_loan_account->balance,2,'.',',')}} {{$loan->client_loan_account->currencies->code}}</span></th>
        </tr>
</table>
<table class="table table-bordered fixed">
        <tr>
            <th style="text-align: center;width: 50px;">{{ trans('multiple.m_no') }}</th>
            <th style="text-align: center;width: 120px;">Payment Date</th>
            <th style="text-align: center;width: 120px;">{{ trans('report.rpt_paid_date') }}</th>  
            <th style="text-align: center;width: 30%;">Description</th>  
            <th style="text-align: center;width: 120px;">{{ trans('report.rpt_invoice_number') }}</th>  
            <th style="text-align: center;width: 120px;">Amount Paid</th>      
            <th style="text-align: center;width: 120px;">{{ trans('report.rpt_interest') }}</th>
            <th style="text-align: center;width: 120px;">{{ trans('report.rpt_principal') }}</th>
            <th style="text-align: center;width: 120px;">{{ trans('report.rpt_principal_balance')  }}</th>
        </tr>
        </thead>
        <?php
        // dd($data);
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

                $interest_amount= $data['interest']?$data['interest']:0;
                $dd_amount=$data['drawdown_acc']?$data['drawdown_acc']->balance:0;
                ?>
                <tr>
                    <td {{ $rowspan }} align="center">{{ $no }}</td>
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
                            <td align="center">{{ $pay->repayment_date }}</td>
                            <td> {{ $pay->note}}</td>
                            <td align="center"> {{ $pay->invoice_number or '-' }}</td>
                            <td align="right" style="font-weight:bold">
                                {{ number_format($tr_sub_total, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($pay->paid_interest, 2, '.', ',') }}</td>
                            <td align="right">{{ number_format($pay->paid_principal, 2, '.', ',') }}</td>
                            
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
     
                                <td align="right">{{ number_format($loan_amount, 2, '.', ',') }}</td>
                            

                           
                        </tr>
                        <?php
                        $c++;
                        ?>
                        @endforeach
                        @endforeach
                        <tr style="font-weight: bold; text-align: right;">
                            <td colspan="2">{{ trans('report.rpt_total') }}</td>
                           
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ number_format($tr_total, 2, '.', ',')  }}</td>
                            <td>{{ number_format($tr_interest, 2, '.', ',')  }}</td>
                            <td>{{ number_format($tr_principal, 2, '.', ',') }}</td>
                            <td>{{ number_format($loan_amount, 2, '.', ',')  }}</td>
                        </tr>
                        <tr>
                                                     
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td  colspan="2" style="font-weight: bold; text-align: right;  background-color: palegreen">Outstanding Balance</td>
                            <td style="font-weight: bold; text-align: right;  background-color: palegreen">${{ number_format($loan_amount, 2, '.', ',')  }}
                            <input type="hidden" id="outstanding_balance" value="{{$loan_amount}}">
                            
                            </td>
                        </tr>
                        <tr>
                        <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        <td  colspan="2" style="font-weight: bold; text-align: right;  background-color: palegreen;    vertical-align: middle;">Discount %</td>
                        <td><input type="number" id="discount" name="discount"  onchange="discount()" class="form-control">
                        </tr>
                        <tr>
                          
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td  colspan="2" style="font-weight: bold; text-align: right;  background-color: palegreen">Total Discount</td>
                          <td style="font-weight: bold; text-align: right;  background-color: palegreen"><p>$<span  id="total_discount">0</span></p></td>
                      </tr>

                    <tr>
                          
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td  colspan="2" style="font-weight: bold; text-align: right;  background-color: palegreen">Interest Accrual</td>
                          <td style="font-weight: bold; text-align: right;  background-color: palegreen"><p>$<span>{{number_format($data['interest'], 2, '.', ',') }}</span></p>
                          <input type="hidden" value="{{$data['interest']?$data['interest']:0 }}"  id="interest_accrual">
                        </td>
                      </tr>
                      <tr>
                          
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td  colspan="2" style="font-weight: bold; text-align: right;  background-color: palegreen">Drawdown Amount</td>
                          <td style="font-weight: bold; text-align: right;  background-color: palegreen"><p>$<span>{{number_format($dd_amount, 2, '.', ',') }}</span></p>
                          <input type="hidden" value="{{$dd_amount }}"  id="drawdown_amount">
                        </td>
                      </tr>

                        <tr>
                          
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td  colspan="2" style="font-weight: bold; text-align: right;  background-color: palegreen">Net Outstanding Balance</td>
                            <td style="font-weight: bold; text-align: right;  background-color: palegreen"><p>$<span  id="net_outstanding_balance">{{ number_format(($loan_amount + $interest_amount - $dd_amount),2,'.','') }}</span></p></td>
                        </tr>
                    </table>
                    <table class="fixed">
                        <tr>
                            <td colspan="18" style="border: none;"><p><u>ចំណាំ</u> ៖គ្រប់អតិថិជនទាំងអស់ដែលបានបង់ផ្តាច់ជាមួយធនាគារដៃគូរពុំទទួលបានការបញ្ចុះតម្លៃទេ</p></td>
                        </tr>
                        <tr>
                            <td colspan="18"  style="border: none;"><p>All payment shall be made in Cash, Cheque or TT to bank accounts with the following details:</p></td>
                        </tr>
                        <tr>
                            <td style="border: none;width: 30%;"><b>Currency: </b>
                            <div style="position: absolute;right: 10px;border: 1px solid #999;padding: 5px;width: 250px;margin-top: -85px;">
                                <p>Confirm By: </p>
                                <br/>
                                <p>Name:  </p>
                                <p>Position: </p>
                                <p>Date: </p>
                            </div>
                        </td>
                            <td style="border: none;width: 70%;"><b>US Dollar </b></td>
                        </tr>
                        <tr>
                                <td   style="border: none; white-space: nowrap !important;"><b>Bank Name:  </b></td>
                                <td   style="border: none; white-space: nowrap !important;"><b>ABA Bank </b></td>
                        </tr>
                    @if($loan->projects->short_code==='EKC')
                           
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none; white-space: nowrap !important;"><b>EAST KEAN SVAY CITY </b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>001 160 513 </b></td>
                            </tr>
                        @elseif($loan->projects->short_code==='ELH')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>EAST LAND AND HOME </b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>000 815 888 </b></td>
                            </tr>
                            @elseif($loan->projects->short_code==='DH Land')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>EAST LAND SVAY CHRUM </b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>000 868 966 </b></td>
                            </tr>
                      
                        @elseif($loan->projects->short_code==='EMC')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>EAST MINI CONDOMINIUM 1 2 3 </b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>000 815 888 </b></td>
                            </tr>

                        @elseif($loan->projects->short_code==='ENC')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>EAST NATURAL CITY </b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>000 815 895 </b></td>
                            </tr>
                                                
                        @elseif($loan->projects->short_code==='EPL')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>EAST PRIME LAND </b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>002 262 407 </b></td>
                            </tr>
                        
                        @elseif($loan->projects->short_code==='ESS')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>EAST SENSOK CONDOMINIUM </b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>000 815 761 </b></td>
                            </tr>
                        @elseif($loan->projects->short_code==='ESC')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>EAST SIHANOUK CITY </b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>000 815 978 </b></td>
                            </tr>

                        @elseif($loan->projects->short_code==='ESP')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>EAST SIHANOUK PARK </b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>000 815 756 </b></td>
                            </tr>
                        
                        @elseif($loan->projects->short_code==='BCC1')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>BS LAND AND HOME CO LTD</b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>000 816 490 </b></td>
                            </tr>
                        @elseif($loan->projects->short_code==='BCC2')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>BS LAND AND HOME CO LTD</b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>000 816 490</b></td>
                            </tr>
                        
                        @elseif($loan->projects->short_code==='SCC')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>BS LAND AND HOME CO LTD</b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>000 816 491</b></td>
                            </tr>

                        @elseif($loan->projects->short_code==='CCC')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>BS LAND AND HOME CO LTD</b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>000 826 822</b></td>
                            </tr>
                        
                            
                        @elseif($loan->projects->short_code==='BHC')
                        <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Name:  </b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>BS LAND AND HOME CO LTD</b></td>
                            </tr>
                            <tr>
                                <td   style="border: none;white-space: nowrap !important;"><b>Account Number:</b></td>
                                <td   style="border: none;white-space: nowrap !important;"><b>001 036 662</b></td>
                            </tr>

                        @endif   
                        
                        
                    @else
                        <tr>
                            <td colspan="18">No results...</td>
                        </tr>
                    @endif
        </tbody>
    </table>
@endif
