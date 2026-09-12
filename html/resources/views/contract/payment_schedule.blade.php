<h4 class="khmer-title text-center">ឧបសម្ព័ន្ធ១</h4>
<h4 class="khmer-title text-center">“តារាងកាលវិភាគនៃការទូទាត់ប្រាក់ថ្លៃលក់ទិញអចលនវត្ថុ”</h4>
<p class="text-center"><i>(ដែល​អាច​កែប្រែ ផ្លាស់ប្តូរ​បាន​គ្រប់​ពេល​វេលា​អាស្រ័យ​លើ​ការ​ស្នើ​សុំ​របស់​ភាគី “អ្នកទិញ” ដោយ​មាន​ការ​យល់​ព្រម​ពីភាគី “អ្នកលក់” ដែល​តារាង​កាល​វិភាគ​នៃ​ការ​ទូទាត់​ប្រាក់​ថ្លៃ​​លក់​ទិញ​អចលនវត្ថុ​ថ្មី​នោះ និង​និរាករ​តារាង​កាលវិភាគ​នៃ​ការ​ទូទាត់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ​ចាស់​ចោល ហើយ​មាន​សុពលភាព​បន្ត​ពី​តារាង​ចាស់​នោះ​ដោយ​ភ្ជាប់​ជាមួយ​កិច្ចសន្យា​លក់​ទិញ ក្នុងឧបសម្ព័ន្ធ​ទី១​នេះ)</i></p>
<div class="row">
  <div class="col-lg-6">
    <table class="table table-sm table-bordered">
      <tr>
        <td class="title bg-grey" width="180px">{{ trans('Unit Code') }}:</td>    
        <td class="text-left">{{ $loan->units->code }}</td>
      </tr>
      <tr>
        <td class="title bg-grey" width="180px">{{ trans('Unit Sale Price') }}:</td>    
        <td class="text-left">{{ $loan->unit_sale_price }}</td>
      </tr>
      @if($loan->discount_promotion != 0)
      <tr>
        <td class="title bg-grey">{{ trans('Discount (Promotion)') }}:</td>    
        <td class="text-left">{{ $loan->discount_promotion }}</td>
      </tr>
      @endif
      @if($loan->discount_other != 0)
      <tr>
        <td class="title bg-grey">{{ trans('Discount (Other)') }}:</td>    
        <td class="text-left">{{ $loan->discount_other }}</td>
      </tr>
      @endif
      <tr class="hidden">
        <td class="title bg-grey">{{ trans('Discount Payment Option $') }}:</td>    
        <td class="text-left">{{ $loan->discount_payment_option }}</td>
      </tr>
      <tr>
        <td class="title bg-grey">{{ trans('Final Price') }}:</td>    
        <td class="text-left text-bold">{{ $loan->final_price }}</td>
      </tr>
      <tr>
        <td class="title bg-grey">{{ trans('Deposit Amount') }}:</td>    
        <td class="text-left">{{ $loan->diposit_amount }}</td>
      </tr>
      <tr>
        <td class="title bg-grey">{{ trans('Deposit Date') }}:</td>    
        <td class="text-left">{{ $loan->deposit_date }}</td>
      </tr>
      <tr>
        <td class="title bg-grey">{{ trans('Start Payment Date') }}:</td>    
        <td class="text-left">{{ $loan->start_payment_date }}</td>
      </tr>
    </table>
  </div>
  <div class="col-lg-6">
    @if($loan->hase_down_payment)
    <table class="table table-sm table-bordered">
      <tr>
        <td class="title bg-grey" width="200px">{{ trans('Has Down Payment?') }}:</td>    
        <td class="text-left" style="text-transform: capitalize;">{{ $loan->hase_down_payment }}</td>
      </tr>
      <tr>
        <td class="title bg-grey">{{ trans('Down Payment Duration') }}:</td>    
        <td class="text-left">{{ $loan->down_payment_duration }}</td>
      </tr>
      <tr>
        <td class="title bg-grey">{{ trans('Down Payment$') }}:</td>
        <td class="text-left">{{ $loan->down_payment }}</td>
      </tr>
    </table>
    @endif
    <table class="table table-sm table-bordered">
        <tr>
            <td class="title bg-grey" width="200px">{{ trans('Principal') }}:</td>    
            <td class="text-left">{{ $loan->loan_amount }}</td>
        </tr>
        <tr>
            <td class="title bg-grey" width="200px">{{ trans('Installment Duration (Months)') }}:</td>    
            <td class="text-left">{{ $loan->installment_duration }}</td>
        </tr>
        <tr>
            <td class="title bg-grey" width="200px">{{ trans('Annual Interest (%)') }}:</td>    
            <td class="text-left">{{ $loan->annual_interest }}</td>
        </tr>     
        <tr>
            <td class="title bg-grey" width="200px">{{ trans('Total Interest') }}:</td>    
            <td class="text-left">{{ number_format($loan->RepaymentSchedules->sum('interest'),2) }}</td>
        </tr>             
    </table>    
  </div>    
</div>
<?php
  $downpayment = $loan->RepaymentSchedules->where('type','downpayment')->toArray();
  $installment = $loan->RepaymentSchedules->where('type','loan')->toArray();
?>

@if(count($downpayment) > 0 )
  <table class="table table-sm table-bordered" id="first_payment_table">
    <thead>
      <tr class="bg-grey">
        <th colspan="6" class="text-center">{{ trans('Down Payment Plan') }}</th>
      </tr>
      <tr class="bg-grey">
        <th>{{ trans('No') }}</th>
        <th>{{ trans('Payment Date') }}</th>
        <th>{{ trans('Beginning Balance') }}</th>
        <th>{{ trans('Payment Amount') }}</th>
        <th>{{ trans('Ending Balance') }}</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $total_payment = 0 ;
      ?>
      @foreach($downpayment as $item)   
        <tr>
          <td>{{ $item['no'] }}</td>
          <td>{{ date('d-m-Y',strtotime($item['schedule_date'])) }}</td>
          <td>{{ number_format($item['beginning'],2) }}</td>
          <td>{{ number_format($item['principal'],2) }}</td>
          <td>{{ number_format($item['balance'],2) }}</td>
        </tr>
        <?php
          $total_payment += $item['principal'];
        ?>
      @endforeach
      <tr class="table-secondary">
        <td></td>
        <td></td>
        <td></td>
        <td><strong>{{ number_format($total_payment,2) }}</strong></td>
        <td></td>
      </tr>
    </tbody>
  </table>
@endif
@if(count($installment) > 0 )
    <table class="table table-sm table-bordered" id="payment_schedule_table">
        <thead>
            <tr class="bg-grey">
                <th colspan="7" class="text-center">{{ trans('Installment Plan') }}</th>
            </tr>
            <tr class="bg-grey">
                <th>{{ trans('No') }}</th>
                <th>{{ trans('Payment Date') }}</th>
                <th>{{ trans('Beginning Balance') }}</th>
                <th>{{ trans('Payment Amount') }}</th>
                <th>{{ trans('Principal') }}</th>
                <th>{{ trans('Interest') }}</th>
                <th>{{ trans('Ending Balance') }}</th>
           </tr>
        </thead>
        <tbody>
            <?php
                $total_payment_amount = 0;
                $total_principal = 0;
                $total_interest = 0;
                $beginning = $loan->loan_amount;
                $end_balance = $loan->loan_amount;
            ?>   
            @foreach($installment as $installments)
            <?php
              $end_balance -= $installments['principal'];
            ?>
                <tr>
                    <td>{{ $installments['loan_no'] }}</td>
                    <td>{{ !empty($installments['schedule_date'])?date('d-m-Y',strtotime($installments['schedule_date'])):"-" }}</td>
                    <td>{{ number_format($installments['beginning'],2) }}</td>
                    {{-- <td>{{ number_format($beginning,2) }}</td> --}}
                    <td>{{ number_format($installments['principal'] + $installments['interest'],2) }}</td>        
                    <td>{{ number_format($installments['principal'],2) }}</td>
                    <td>{{ number_format($installments['interest'],2) }}</td>
                    <td>{{ number_format($installments['balance'],2) }}</td>
                    {{-- <td>{{ number_format($end_balance,2) }}</td> --}}
                </tr>
                <?php
                    $beginning -= $installments['principal'];
                    $total_payment_amount += $installments['principal'] + $installments['interest'];
                    $total_principal =  $total_principal + $installments['principal'];
                    $total_interest =  $total_interest + $installments['interest'];
                ?>
            @endforeach
            <tr class="table-secondary">
                <td></td>
                <td></td>
                <td></td>
                <td><strong>{{ number_format($total_payment_amount,2) }}</strong></td>
                <td><strong>{{ number_format($total_principal,2) }}</strong></td>
                <td><strong>{{ number_format($total_interest,2) }}</strong></td>
                <td></td>
            </tr> 
        </tbody>
    </table>
    <table class="table table-sm contract-user-sign" style="margin-top: 30px;margin-bottom: 30px;">
      <tr>
        <th style="text-align: center;border: none; width: 50%;"><p>ស្នាមមេដៃអតិថិជន</p></th>
        <th style="text-align: center;border: none; width: 50%;"><p>រៀបចំដោយ</p></th>
      </tr>
      <tr style="height:100px;">
        <th style="text-align: center;border: none; width: 50%; font-weight: normal;vertical-align:bottom;">
        <?php
                $customer_name1 = $loan->client->ClientCbcGeneral->family_name_kh.' '.$loan->client->ClientCbcGeneral->first_name_kh;
                $customer_name2 = $loan->co_borrowers->Clients->ClientCbcGeneral->family_name_kh.' '.$loan->co_borrowers->Clients->ClientCbcGeneral->first_name_kh;
            ?> 
            <p>
            <span>{{ isset($customer_name1) ? $customer_name1:'____________' }}</span>
            <?php
            if(!empty($customer_name2) &&  trim($customer_name2)!==''){?>
              <span style="margin: 30px;"></span>
              <span>{{ isset($customer_name2)?$customer_name2:'____________' }}</span>

          <?php }?>
            

            </p>
        </th>
        <th style="text-align: center;border: none; width: 50%; font-weight: normal;vertical-align:bottom;"><p>
        {{!empty($teller_user->kh_name)?$teller_user->kh_name:$teller_user->name }}
        </p></th>
      </tr>
  </table>
@endif
<div style="page-break-after: always;"></div><br>