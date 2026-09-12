<?php $trans_type = config('static_data.trans_type'); ?>
<table class="table table-bordered table-striped table-condensed">
    <thead class="cf">
        <tr>
            <th style="text-align: center">{{ trans('report.rpt_transaction_type') }}</th>
            <th style="text-align: center">{{ trans('multiple.date') }}</th>
            <th style="text-align: center">{{ trans('account.a_entry_id') }}</th>
            <th style="text-align: center">{{ trans('report.rpt_memo') }}</th>
            <th style="text-align: center">{{ trans('account.a_debit') }}</th>
            <th style="text-align: center">{{ trans('account.a_credit') }}</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($journals as $journal):?>
            <?php
                $debit = $journal->debit;
                $credit = $journal->credit;
                //exchange currency
                if($consolidate==1){
                    if($exchange_rate==1){
                        if($in->currency==2){ //if USD change to KHR mill....
                            $debit = ($debit*$rate);
                            $credit = ($credit*$rate);
                        }

                        $debit = ($debit)/KHRM;
                        $credit = ($credit)/KHRM;

                    }elseif ($exchange_rate==2){
                        if($in->currency==1){ //if KHR change to USD
                            $debit = $debit/$rate;
                            $credit = $credit/$rate;
                        }
                    }
                
                }else{ //no consolidate
                    if($in->currency==1 && $currency_id==100){
                        $debit = $debit/USDTOKHR;
                        $credit = $credit/USDTOKHR;
                    }
                }
            ?>
            <tr>
                <td style="text-align: center">{{$trans_type[$journal->trans_type]}}</td>
                <td style="text-align: center">{{$journal->entry_date}}</td>
                <td style="text-align: center">{{$journal->journal_id}}</td>
                <td>{{$journal->description}}</td>
                <td style="text-align: right">{{number_format($debit, 2, '.', ',')}}</td>
                <td style="text-align: right">{{number_format($credit, 2, '.', ',')}}</td>
            </tr>
        @endforeach
    </tbody>

</table>