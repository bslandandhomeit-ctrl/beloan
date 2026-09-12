<?php
        $branch_name =$company_branch->branch_name?$company_branch->branch_name: 'ប៊ីអេស លែន & ហូម ខូ អិលធីឌី';

        $address_one =$company_branch->address_one?$company_branch->address_one: 'អគារលេខ B2-109, B2-110';
        $address_two=$company_branch->address_two?$company_branch->address_two:'សង្កាត់​ទន្លេបាសាក់ ខណ្ឌចំការមន រាជធានីភ្នំពេញ';
        $contact_number=$company_branch->contact_number?$company_branch->contact_number:'069 455555/ 099 788883'; 
        
        $projects = [];
        $locations=[];
        $users=[];
        $grand_total=0;
        $i=0;
        foreach($teller_transaction as $element) {
            $i++;
            $grand_total += round($element['cash_in'],2);
            $project = $element['project_name'];
            $projects[$project] = $element;
            $location = $element['location'];
            $locations[$location] = $element;
            $user = $element['username'];
            $users[$user] = $element;
        }
        
        $project_list = array_values($projects);
        $project_name=[];
        if(count($project_list)>0){
            foreach($project_list as $key) {
                $project_name[]=$key['project_name'];
            }  
        }

        $location_list = array_values($locations);
        $location_name=[];
        if(count($location_list)>0){
            foreach($location_list as $key) {
                $location_name[]=$key['location'];
            }  
        }

        $user_list = array_values($users);
        $user_name=[];
        if(count($user_list)>0){
            foreach($user_list as $key) {
                $user_name[]=$key['username'];
            }  
        }
    ?>
<table class="table table-bordered table-striped table-condensed tillTran" id="tran">
    <thead class="table-header">
        <tr>
            <th colspan="15" style="text-align: center;">
            {{ $branch_name }}
            </th>
        </tr>
        <?php if($projects_row->dealer != ''){?>
        <tr>
            <th colspan="15" style="text-align: center;">           
           {{ ($projects_row->dealer != '')?$projects_row->dealer:'&nbsp;' }}        
            </th>
        </tr>
        <?php } ?>
        <tr>
            <th colspan="15" style="text-align: center;">
           Head office: {{ $address_one }}        
            </th>
        </tr>
        <tr>
            <th colspan="15" style="text-align: center;">
            {{ ($address_two != '')?$address_two:'&nbsp;' }}           
            </th>
        </tr>
        <tr>
            <th colspan="15" style="text-align: center;">
           Tel: {{ $contact_number }}         
            </th>
        </tr>
        <tr>
            <th colspan="15" style="text-align: center;">
            E-mail:{{ $company_branch->email }} / Page:{{ $company_branch->website }}
            </th>
        </tr>
        <tr><th  colspan="15"></th></tr>
        <tr>
            <th  colspan="15" style="text-align: center;">Cashier Receipt Report - Details</th>
        </tr>
        <tr>
            <th  colspan="15" style="text-align: center;">(Print Date:<?php echo date("Y-m-d");?> / By <?php echo $teller[0]->name; ?>)</th>
        </tr>
        <tr><th  colspan="15"></th></tr>
        <tr>
            <th colspan="2">Project Name:</th>
            <th  colspan="13"><?php echo implode(", ", $project_name);?></th>
        </tr>
        <tr>
            <th  colspan="2">Receipt Location:</th>
            <th  colspan="13"><?php echo implode(", ", $location_name);?></th>
        </tr>
        <tr>
            <th  colspan="2">Report Date:</th>
            <th  colspan="13"><?php echo $from_date;?> to <?php echo $to_date;?></th>
        </tr>
        <tr>
            <th  colspan="2">Grand Total:</th>
            <th  colspan="13">$<?php echo number_format($grand_total, 2, '.', '');?></th>
        </tr>
        <tr>
            <th  colspan="2">No of receipt:</th>
            <th  colspan="13"><?php echo count($teller_transaction);?></th>
        </tr>
        <tr>
            <th>{{ trans('multiple.m_no')  }}</th>
            <th>{{ trans('Transaction Date' ) }}</th>
            <th>Receipt No</th>
            <th>{{ trans('customer.cus_customer_id') }}</th>
            <th>{{ trans('customer.cus_customer_name') }}</th>
            <th>Project</th>
            <th>{{ trans('unit.unit') }}</th>
            <th>{{ trans('multiple.methode') }}</th>
            <th>{{ trans('teller.payment_type') }}</th>
            <th>{{ trans('teller.description')}}</th>
            <th>PMT.No</th>
            <th>PMT Date</th>
            <th>Interest</th>
            <th>Principal</th>
            <th>Other Fee</th>
            <th>{{ trans('teller.paid_amount')}}</th>
            <th>{{ trans('teller.teller_status')}}</th>

        </tr>
    </thead>
    <tbody id="trans_results">
        <?php
         $total = 0;?>
        @forelse($teller_transaction as $key => $val)
            <?php
                $pmt_no=null;
                $pmt_date=null;
                $interest=0;
                $principal=0;
                $admin_fee=0;
                if(!empty($val->feecharge) && count($val->feecharge) > 0){
                    foreach($val->feecharge as $fc){                                     
                        $admin_fee_key='Admin Fee';
                        if(preg_match("/{$admin_fee_key}/i", $fc->note)) {
                            $admin_fee=floatval($admin_fee)+($fc->charge_amount);
                        }
                }
            }
                if($val->deposit_type=='Loan Installment' && $val->type == "Cash Deposit"){
                    foreach ($repayment as $re) {   
                                                   
                        if(!empty($val->principal)){
                            if($val->loan_id==$re['loan_id'] && floatval($re['no']) == floatval($val->pmt_no)){
                                $interest=round($re['interest'],2) ;
                                $principal=round($re['principal'],2) ;
                                $pmt_date=$re['schedule_date'];
                                $pmt_no=$re['no'];
                    
                            } 
                        }else{
                            if($val->loan_id==$re['loan_id'] && floatval($re['no']) == floatval($re['last_payment']->payment_month)){
                                $interest=round($re['interest'],2) ;
                                $principal=round($re['principal'],2) ;
                                $pmt_date=$re['schedule_date'];
                                $pmt_no=$re['no'];
                    
                            } 

                        }                                   
                    }
                }
                $class_paid_amount='cash_in';
                $paid_amount=!empty($val->cash_in)?number_format((float)round($val->cash_in,2), 2, '.', ''):0;
                if($val->type=='Withdraw'){
                    $paid_amount=!empty($val->cash_out)?number_format((float)round($val->cash_out,2), 2, '.', ''):0;
                    $paid_amount= $paid_amount*-1;
                    $class_paid_amount='cash_out';
                }
            ?>
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ !empty($val->tranx_time)?date('d-M-Y',strtotime($val->tranx_time)):"" }}</td>
                <td>{{ !empty($val->receipt_no)?$val->receipt_no:"" }}</td>
                <td>{{ !empty($val->cus_acc)?$val->cus_acc:"" }}</td>
                <td>{{ !empty($val->client_name)?$val->client_name:"" }}</td>
                <td>{{ !empty($val->project_name)?$val->project_name:"" }}</td>
                <td>{{ $val->unit_code }}</td>
                <td>{{ !empty($val->methode)?$val->methode:"" }}</td>
                <td>{{ !empty($val->deposit_type)?$val->deposit_type:"" }}</td>
                <td>{{ !empty($val->description)?$val->description:"" }}</td>
                <td>{{ !empty($pmt_no)?$pmt_no:"" }}</td>
                <td>{{ !empty($pmt_date)?date('d-M-Y',strtotime($pmt_date)):date('d-M-Y',strtotime($val->tranx_time)) }}</td>
                <td style="text-align:right;">${{ $interest}}</td>
                <td style="text-align:right;">${{ $principal }}</td>
                <td style="text-align:right;">${{ $admin_fee }}</td>                                        
                <td class={{$class_paid_amount}} style="text-align:right;">${{ $paid_amount }}</td>
                <td>{{ !empty($val->username)?$val->username:"" }}</td>
                <td>{{ $val->approve_status == 1 ? "Authorized" : "Unauthorized"}}</td>
                <?php $total += round($paid_amount,2);?>
            </tr>
        @empty
            <tr><td colspan="12" class="text-center">No data found.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="15">Total:</th>
            <th>{{ $total }}</th>
        </tr>
        <tr><th  colspan="15"></th></tr>
        <tr><th  colspan="15"></th></tr>
        <tr>
            <th colspan="10" style="text-align:center">
                <p style="padding-left:100px;">Prepared by:</p>
            </th>
            <th colspan="10" style="text-align:center">
                <p>Verified by:</p>
            </th>
        </tr>
    </tfoot>
</table>