
@if(!empty($loan))
    <style>
        .tbrepayment_sch .td-schedule-hide {
            display: none;
        }
        .signature_detail{
            display: none;
        }
        .sch_title{
            display: none;
        }
    </style>
    <input type="hidden" id="loan-id" value="{{ $loan->id }}"/>
    <section>
        <div id="schedule">
        </div>
    </section>
@else
    {{"No data for this loan."}}
@endif
<?php
$repay = [];
if (!empty($loan)) {
    $start_date = $loan->start_date;
    $repay = [
        'loan_id' => $loan->id,
        'client_name' => $loan->client->client_name,
        'start_date' => $start_date,
        'loan_amount' => $loan->loan_amount,
        'interest_rate' => $loan->interest_rate,
        'loan_duration' => $loan->loan_duration,
        'repayment_type' => $loan->repayment_type,
        'num_balloon' => $loan->balloon,
        'balloon_month' => $loan->balloon_month,
        'balloon_amount' => $loan->balloon_amount_array,
        'monthly_payment' => $loan->monthly_payment,
        'loan_status' => $loan->status,
        'disbursement_date' => $loan->disburse_date,
        'admin_fee' => $loan->admin_fee,
        'maintain_fee' => $loan->maintain_fee,
        'admin_fee_opt' => $loan->admin_fee_opt,
        'maintain_fee_opt' => $loan->maintain_fee_opt,
        'other_fee' => $loan->other_fee,
        '_token' => csrf_token()
    ];
}
?>
<input type="hidden" id="repayment-data" value="{{ json_encode($repay) }}"/>
<script>
    $(document).ready(function(){
        var loan_id = $("#loan-id").val();
        var repay = $("#repayment-data").val();
        var data = $.parseJSON(repay);
        if(loan_id){
            $.ajax({
                url: '/api/loan/reschedule/' + loan_id,
                type:"POST",
                data:data,
                success:function(res){;
                    $("#schedule").html(res);
                }
            });
        }
    });
</script>
    
