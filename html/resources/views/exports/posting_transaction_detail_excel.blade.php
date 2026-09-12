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
        <th>{{ trans('multiple.m_no')  }}</th>
        <th>{{ trans('Transaction Date' ) }}</th>
        <th>{{ trans('customer.cus_customer_id') }}</th>
        <th>{{ trans('customer.cus_customer_name') }}</th>
        <th>Project</th>
        <th>{{ trans('unit.unit') }}</th>
        <th>{{ trans('multiple.methode') }}</th>                                     
        <th>Payment Type</th>
        <th>{{ trans('teller.description')}}</th>  
        <th>Posting Amount</th>                                
        <th>Total Interest</th>
        <th>Total Principal</th>
        <th>Deposit</th>
        <th>Other Fee Charge</th>
        <th>Paid Amount</th>
        <th>{{ trans('teller.teller_status')}}</th>

        </tr>
    </thead>
    <tbody id="trans_results">
        <?php
         $total = 0;
         $total_paid=0;
         ?>
        @forelse($teller_transaction as $key => $val)
        <?php
                                $admin_fee=0;
                                $title_transfer_fee=0;
                                $stamp_tax_fee=0;
                                $water_fee=0;
                                $electricity_fee=0;
                                $maintenance_fee=0;
                                $renovation_fee=0;
                                $sport_club_fee=0;
                                $rental_fee=0;
                                $entrance_card_fee=0;
                                $internet_service_fee=0;
                                $CCTV_Fee=0;
                                $Other_Fee_Charge=0;
                                $isOther_Fee_Charge=true; 

                                $total_interest=$val->total_interest?$val->total_interest:0; 
                                $total_principal=$val->total_principal?$val->total_principal:0; 
                                $deposit=$val->trans_type==='Deposit'?$val->amount:0;  
                                $total_principal=$val->trans_type==='Down-Payment'?$val->amount:$total_principal; 
                                // $total_principal=$val->trans_type==='Pay-Off'?$total_principal:$val->amount;  
                                $Other_Fee_Charge=$val->trans_type==='Penalty Fee'?$val->amount:$Other_Fee_Charge; 
                                $Other_Fee_Charge=$val->trans_type==='Fee Charge'?$val->amount:$Other_Fee_Charge; 

                                $PaidAmount=floatval($total_interest) + floatval($total_principal)  + floatval($deposit) + floatval($Other_Fee_Charge);
                                 $total=floatval($total) + floatval($val->amount); 

                                 $total_paid=floatval($total_paid) + floatval($PaidAmount);                        
                                                             
                                ?>
            <tr>
            <td>{{ ++$key }}</td>
                                        <td>{{ !empty($val->posting_date)?date('d-M-Y',strtotime($val->posting_date)):"" }}</td>
                                        <td>{{ !empty($val->cus_acc)?$val->cus_acc:"" }}</td>
                                        <td>{{ !empty($val->client_name)?$val->client_name:"" }}</td>
                                        <td>{{ !empty($val->project_name)?$val->project_name:"" }}</td>
                                        <td>{{ !empty($val->unit_code)?$val->unit_code:"" }}</td>
                                        <td>{{$val->methode}}</td>                                     
                                        <td>{{$val->trans_type}}</td>
                                        <td>{{ !empty($val->description)?$val->description:"" }}</td>
                                        <td >{{ $val->amount}}</td>
                                        <td >{{ $total_interest}}</td>
                                        <td >{{ $total_principal }}</td>
                                        <td >{{number_format($deposit,2,'.','')}}</td>                           
                                        <td >{{number_format($Other_Fee_Charge,2,'.','')}}</td>
                                        <td >{{number_format($PaidAmount,2,'.','')}}</td>               
                                        <td>{{ $val->status == 1 ? "Authorized" : "Unauthorized"}}</td>
            </tr>
        @empty
            <tr><td colspan="12" class="text-center">No data found.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
    <tr>
                                    <th colspan="9">Total:</th>
                                    <th >{{ number_format($total, 2, '.', '') }}</th>                                    
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th >{{ number_format($total_paid, 2, '.', '') }}</th>
                                </tr>
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