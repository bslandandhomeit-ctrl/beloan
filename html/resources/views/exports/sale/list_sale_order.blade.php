<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
    $loan_status = config('static_data.loan_status');
    ?>
<table>
    <thead>
    <tr>
    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.id') }}</th>
                    <th style="vertical-align:middle; text-align: center;">Main Project Code</th>
                    <th style="vertical-align: middle;text-align: center;">Item No.</th>
                    <th style="vertical-align:middle; text-align: center;">Variant Code</th>
                    <th style="vertical-align:middle; text-align: center;">Contract No</th>
                    <th style="vertical-align:middle; text-align: center;">Cust. No.</th>
                    <th style="vertical-align: middle;text-align: center;">Customer Name</th>
                    <th style="vertical-align: middle;text-align: center;">Customer Phone No.</th>
                    <th style="vertical-align:middle; text-align: center;">Status</th>
                    <th style="vertical-align: middle;text-align: center;">Deposit Date</th>
                    <th style="vertical-align: middle;text-align: center;">Contract Sign Date</th>
                    <th style="vertical-align: middle;text-align: center;">Contract Deadline</th>
                    <th style="vertical-align:middle; text-align: center;">Remark</th>
                    <th style="vertical-align:middle; text-align: center;">Unit Price</th>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('loan.down_payment') }}</th>
                    <th style="vertical-align:middle; text-align: center;">Down Payment Plan</th>
                     <th style="vertical-align:middle; text-align: center;">Monthly Payment</th>
                    <th style="vertical-align:middle; text-align: center;">Payment Option Name</th>
                    <th style="vertical-align:middle; text-align: center;">Total Discount Amount</th>
                    <th style="text-align: center; vertical-align: center;">Net Selling Price</th>
                    <th style="text-align: center; vertical-align: middle;">Total Interest</th>
                    <th style="vertical-align:middle; text-align: center;">Sale Level1</th> 
                    <th style="vertical-align:middle; text-align: center;">Sale Level2</th> 
                    <th style="vertical-align:middle; text-align: center;">Sale Person</th> 
                    <th style="vertical-align:middle; text-align: center;">Created On</th>
                    <th style="vertical-align:middle; text-align: center;">Created by</th>  
        </thead>
       
        <tbody>
        <?php $i=0?>
                        @forelse($lists as $d)
                        <?php $i++;
                            $net_selling_price=0;
                            $totalall_discount=floatval($d->discount_promotion) + floatval($d->discount_other) + floatval($d->amount_discount_payment_option);
                            $net_selling_price=floatval($d->unit_sale_price) - floatval($totalall_discount);
                            $total_interest=0;
                            if(!empty($d->RepaymentSchedules) && count($d->RepaymentSchedules) > 0){
                                $total_interest=floatval($d->RepaymentSchedules->sum('interest'));  
                            }
                            $downPayment = \App\Models\RepaymentSchedule::where('loan_id', $d->id)->where('type','downpayment')->first();
                            $schdulePayment = \App\Models\RepaymentSchedule::where('loan_id', $d->id)->where('type','loan')->first();
                            ?>
                            <tr>
                            <td class="isVerticalalign" align="center">{{ $i}}</td>
                                <td class="isVerticalalign">{{ $d->short_code }}</td>
                                <td class="isVerticalalign" >{{ $d->unit_type }}</td>
                                <td class="isVerticalalign">{{ $d->unit }}</td>
                                <td style="vertical-align: middle;text-align: center">
                                <a style="text-decoration: underline" href="{{ route('loan_detail', [$d->id])}}">{{ $d->contract_id ? $d->contract_id : '-'  }}</a>
                                </td>
                                <td style="vertical-align: middle;text-align: center;">
                                <a style="text-decoration: underline" href="{{ route('loan_account', [$d->loan_account_id])}}">
                                    {{ !empty($d->drawdown_acc)?$d->drawdown_acc:"-" }}
                                </a>
                                </td>
                                <td class="isVerticalalign">{{ $d->client_name}}</td>
                                <td style="vertical-align: middle">{{$d->phone1}}</td>
                                <td style="text-align: center">{{$loan_status[$d->status]}}</td>
                                <td class="isVerticalalign">{{ !empty($d->created_on)?date('d-M-Y',strtotime($d->created_on)):"N/A" }}</td>
                                <td class="isVerticalalign">{{ !empty($d->contract_date)?date('d-M-Y',strtotime($d->contract_date)):"N/A" }}</td>
                                <td class="isVerticalalign">{{ !empty($d->contract_deadline)?date('d-M-Y',strtotime($d->contract_deadline)):"N/A" }}</td>
                                <td class="isVerticalalign">{{ $d->remark }}</td>
                                <td class="isVerticalalign">{{ number_format($d->unit_sale_price,2,'.','') }}</td>
                                <td class="isVerticalalign">{{ !empty($d->down_payment)?number_format($d->down_payment,2):0 }}</td>
                                <td class="isVerticalalign">{{ !empty($downPayment)?number_format($downPayment->principal,2):0 }}</td>
                                <td class="isVerticalalign">{{ !empty($schdulePayment)?number_format($schdulePayment->interest + $schdulePayment->principal + $schdulePayment->fee + $schdulePayment->other_fee,2):0 }}</td>
                                <td style="text-align: center">
                                {{ !empty($d->PaymentOptions) ? $d->PaymentOptions->name : 'Other' }}
                                </td> 
                                <td style="text-align: justify">{{ number_format(($d->discount_promotion + $d->discount_other + $d->amount_discount_payment_option),2,'.','') }}</td>
                                <td style="text-align: right;">{{ number_format($net_selling_price,2,'.','') }}</td>
                                <td style="text-align: right;">{{ number_format($total_interest,2,'.','') }}</td>
                                <td class="isVerticalalign" >{{ $d->sale_persons?$d->sale_persons->parent->parent->name:'-' }}</td>
                                <td class="isVerticalalign" >{{ $d->sale_persons?$d->sale_persons->parent->name:'-' }}</td>
                                <td class="isVerticalalign" >{{ $d->sale_persons?$d->sale_persons->name:'-' }}</td>  
                                <td class="isVerticalalign">{{ !empty($d->created_on)?date('d-M-Y',strtotime($d->created_on)):"N/A" }}</td>
                                <td class="isVerticalalign">{{ !empty($d->created_by)?$d->created_by:"N/A" }}</td>                         
                    
                            </tr>
                        @endforeach
                    </tbody></table>