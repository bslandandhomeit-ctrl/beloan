<?php
        $logo = asset('images/login_logo.jpg',false);
        if($company_branch->logo != ''){
            if(file_exists('data/company_logo/'.$company_branch->logo)){
                $logo = asset('data/company_logo/'.$company_branch->logo,false);
            }
        }else{
            $logo = asset('images/login_logo.jpg',false);
        }
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
            $grand_total += (round($element['cash_in'],2) - round($element['cash_out'],2));
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

    @if(app()->getLocale() == 'kh')
     <div class="report_header">
     <div class="box-img">
        <img src="{{ $logo }}" style="opacity: 0.1;object-fit: contain;width: 100%;height: 100%;z-index: -1;">
    </div>
 
                <h1 class="title text-center brach_name">{{ $branch_name }}</h1>
                <h2 class="title color-red text-center">{{ ($projects_row->dealer != '')?$projects_row->dealer:'&nbsp;' }}</h2>
                <h3 class="title text-center">Head office: {{ $address_one }}</h3>
                <h3 class="title text-center">{{ ($address_two != '')?$address_two:'&nbsp;' }}</h3>
                <h3 class="title color-red text-center">Tel: {{ $contact_number }}</h3>
                <h3 class="title text-center">E-mail:{{ $company_branch->email }} / Page:{{ $company_branch->website }}</h3>
                <img src="{{ $logo }}" class="project-logo"/>
        
        </div>
@else
    <div class="report_header">   
    <div class="box-img">
        <img src="{{ $logo }}" style="opacity: 0.1;object-fit: contain;width: 100%;height: 100%;z-index: -1;">
    </div>
            <h1 class="title text-center brach_name">{{ $branch_name }}</h1>
            <h2 class="title color-red text-center">{{ ($projects_row->dealer != '')?$projects_row->dealer:'&nbsp;' }}</h2>
            <h3 class="title text-center">Head office: {{ $address_one }}</h3>
            <h3 class="title text-center">{{ ($address_two != '')?$address_two:'&nbsp;' }}</h3>
            <h3 class="title color-red text-center">Tel: {{ $contact_number }}</h3>
            <h3 class="title text-center">E-mail:{{ $company_branch->email }} / Page:{{ $company_branch->website }}</h3>
            <img src="{{ $logo }}" class="project-logo"/>
            <hr/>
            <br/>
            <h1 class="title text-center brach_name">Cashier Receipt Report - Summary</h1>
            <p class="text-center">(Print Date:<?php echo date("Y-m-d");?> / By <?php echo $teller_user[0]->name; ?>)</p>
            <br/>
            <table class="table table-bordered table-striped table-condensed tillTran" id="tran">
            <tr>
                <th width="200px">Project Name:</th>
                <th><?php echo implode(", ", $project_name);?></th>
            </tr>
            <tr>
                <th  width="200px">Receipt Location:</th>
                <th ><?php echo implode(", ", $location_name);?></th>
            </tr>
            <tr>
                <th width="200px">Report Date:</th>
                <th ><?php echo $from_date;?> to <?php echo $to_date;?></th>
            </tr>
            <tr>
                <th width="200px">Grand Total:</th>
                <th >$<?php echo  number_format($grand_total, 2, '.', '');?></th>
            </tr>
            <tr>
                <th width="200px">No of receipt:</th>
                <th ><?php echo count($teller_transaction);?></th>
            </tr>
        </table>
 
    </div>

@endif


