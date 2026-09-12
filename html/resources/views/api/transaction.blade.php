<h4 class="sch_title">{{ trans('loan.l_transaction') }}</h4>
    <table class="table table-bordered transaction">
        <thead>
            <tr>
                <th rowspan="2" style="text-align:center; vertical-align: middle;">{{ trans('loan.l_id') }}</th>
                <th rowspan="2" style="text-align:center; vertical-align: middle;">{{ trans('report.rpt_office') }}</th>
                <th rowspan="2" style="text-align:center; vertical-align: middle;">{{ trans('report.rpt_transaction_date') }}</th>
                <th rowspan="2" style="text-align:center; vertical-align: middle;">{{ trans('report.rpt_transaction_type') }}</th>
                <th rowspan="2" style="text-align:center; vertical-align: middle;">{{ trans('report.rpt_amount') }}</th>
                <th rowspan="2" style="text-align:center; vertical-align: middle;">{{ trans('account.type') }}</th>
                <th colspan="5" style="text-align:center; vertical-align: middle;">{{ trans('loan.l_break_down') }}</th>
                <th rowspan="2" style="text-align:center; vertical-align: middle;">{{ trans('report.rpt_principal_balance') }}</th>
                <th rowspan="2" style="text-align:center; vertical-align: middle;">{{ trans('multiple.m_description') }}</th>
                <th rowspan="2" style="text-align:center; vertical-align: middle;">{{ trans('multiple.accrued_air') }}</th>
                <th rowspan="2" style="text-align:center; vertical-align: middle;">{{ trans('multiple.air_balance') }}</th>
                <th rowspan="2" style="text-align:center; vertical-align: middle;" class="hide-this">{{ trans('multiple.m_action') }}</th>
            </tr>
            <tr>
                <th style="text-align:center;">{{ trans('report.rpt_principal') }}</th>
                <th style="text-align:center;">{{ trans('report.rpt_interest') }}</th>
                <th style="text-align:center;">{{ trans('report.rpt_penalty') }}</th>
                <th style="text-align:center;">{{ trans('loan.l_fee') }}</th>
                <th style="text-align:center;">{{ trans('multiple.other_fee') }}</th>
			</tr>
        </thead>
        <?php
            $prin_total = 0.0;
            $int_total = 0.0;
            $penal_total = 0.0;
            $fee_total = 0.0;
			$other_fee_total = 0.0;
            $old_balance = 0;
            $to_air = 0;
            $air_bal = 0;
        ?>
        <tbody>
            @if(!empty($loan))
                @if(!empty($loan->transaction) && count($loan->transaction) > 0)
                    @foreach($loan->transaction as $tran)
                        <?php
                            $prin_total += $tran->principal;
                            $int_total += $tran->interest;
                            $penal_total += $tran->penalty;
							$other_fee_total += $tran->other_fee;
                            $fee_total += $tran->fee;
                            $start_date = (substr($loan->contract_id, 0,3) != "TGL")? $loan->transfer_date : $loan->disburse_date;
                            if(date_dif($start_date, $tran->trans_date, 1, false) == 0){
                                $num_days = 0;
                            }else{
                                $num_days = date_dif($old_trx_date, $tran->trans_date, 1, false);
                            }
                            $old_trx_date = $tran->trans_date;
                            $air_amount = ($loan->rate_type != "Flat")? $old_balance* $num_days * $loan->interest_rate * 12/36000 : $loan->original_amount * $num_days * $loan->interest_rate * 12/36000;
                            $air_bal += $air_amount  - $tran->interest;
                            $to_air += $air_amount;
                        
                        ?>
                        <tr>
                            <td align="center">{{ str_pad($tran->id,8,'0',STR_PAD_LEFT) }}</td>
                            <td align="center">{{ $loan->branch_name }}</td>
                            <td align="center">{{ date('Y-M-d',strtotime($tran->trans_date))}}</td>
                            <td align="center">{{ $tran->trans_type }}</td>
                            <td align="right">{{ number_format($tran->amount, 2, '.', ',') }}</td>
                            <td align="center">{{ isset($tran->loan_repayment_type)?ucfirst($tran->loan_repayment_type):'N/A' }}</td>
                            <td align="right">{{ ($tran->principal > 0) ? number_format($tran->principal, 2, '.', ',') : 0.00 }}</td>
                            <td align="right">{{ ($tran->interest > 0) ? number_format($tran->interest, 2, '.', ',') : 0.00 }}</td>
                            <td align="right">{{ ($tran->penalty > 0) ? number_format($tran->penalty, 2, '.', ',') : 0.00 }}</td>
                            <td align="right">{{ ($tran->fee > 0) ? number_format($tran->fee, 2, '.', ',') : 0.00 }}</td>
                            <td align="right">{{ ($tran->fee > 0) ? number_format($tran->other_fee, 2, '.', ',') : 0.00 }}</td>
                            <td align="right">{{ ($tran->balance > 0) ? number_format($tran->balance, 2, '.', ',') : 0.00 }}</td>
                            <td align="right">
                                @if($tran->repayment_receipt)
                                <a href="{{ asset('data/loans/receipts/'.$tran->repayment_receipt, isset($secure)?false:false)}}" title="{{ trans('multiple.document') }}" target="_blank">
                                    <i class="glyphicon glyphicon-file"></i>
                                </a>
                                @endif

                                @if($tran->waive_panalty_receipt)
                                <a href="{{ asset('data/loans/documents/'.$tran->waive_panalty_receipt, isset($secure)?false:false)}}" title="{{ trans('multiple.receipt') }}" target="_blank">
                                    <i class="glyphicon glyphicon-file"></i>
                                </a>
                                @endif
                            </td>
                            <td align="right">
                                {{number_format($air_amount, 2)}}
                            </td>
                            <td align="right">
                                {{number_format($air_bal, 2)}}
                            </td>
                            <td align="center" class='hide-this'>
                            @if($tran->description!="")
                                <a href="#m{{$tran->id}}" data-toggle="modal" class="btn btn-xs btn-default" title="Description"><i class="fa fa-search-minus"></i></a>
                                &nbsp;&nbsp;
                            @endif
                            <a href="{{ route('journal_entry',[$tran->id]) }}" class="btn btn-xs btn-primary" title="View Journal Entry"><i class="fa  fa-arrow-circle-right"></i></a></td>
                            @if($tran->description!="")
                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="m{{$tran->id}}" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                <h4 class="modal-title">Description for transaction #{{$tran->id}}</h4>
                                            </div>
                                            <div class="modal-body">
                                                {{ $tran->description }}
                                            </div>
                                            <div class="modal-footer">
                                                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </tr>
                        <?php
                            $old_balance = $tran->balance;
                        ?>
                    @endforeach
                        <?php 
                            $last_num_days = date_dif($tran->trans_date, date('Y-M-d'), 1, false);
                            if( $last_num_days > 0){
                                $air_amount = ($loan->rate_type != "Flat")? $old_balance* $last_num_days * $loan->interest_rate * 12/36000 : $loan->original_amount * $last_num_days * $loan->interest_rate * 12/36000;
                                $air_bal += $air_amount;
                                $to_air += $air_amount;
                            }
                        ?>
                        <td align="right" colspan="13">Last AIR Till Today</td>
                        <td align="right">{{number_format($air_amount, 2)}}</td>
                        <td align="right">{{number_format($air_bal, 2)}}</td>
                    <tr style="font-weight: bold">
                        <td align="center" colspan="6">Total</td>
                        <td align="right">{{number_format($prin_total,2,'.',',')}}</td>
                        <td align="right">{{number_format($int_total,2,'.',',')}}</td>
                        <td align="right">{{number_format($penal_total,2,'.',',')}}</td>
                        <td align="right">{{number_format($fee_total,2,'.',',')}}</td>
                        <td align="right">{{number_format($other_fee_total,2,'.',',')}}</td>
                        <td align="right" colspan="2">Unpaid AIR</td>
                        <td align="right">{{number_format($to_air,2,'.',',')}}</td>
                        <td align="right">{{number_format($air_bal,2,'.',',')}}</td>
                    </tr>
                @endif
            @endif
        </tbody>
    </table>
