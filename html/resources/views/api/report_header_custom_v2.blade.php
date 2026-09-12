<?php
        $logo = asset('images/login_logo.jpg',false);
        if($company_branch->id ===1){
                $logo = asset('images/east_land.jpg',false);

        }else{
            $logo = asset('images/bs_land.jpg/',false);
        }
        $branch_name =$company_branch->branch_name?$company_branch->branch_name: 'ប៊ីអេស លែន & ហូម ខូ អិលធីឌី';

        $address_one =$company_branch->address_one?$company_branch->address_one: 'អគារលេខ B2-109, B2-110';
        $address_two=$company_branch->address_two?$company_branch->address_two:'សង្កាត់​ទន្លេបាសាក់ ខណ្ឌចំការមន រាជធានីភ្នំពេញ';
        $contact_number=$company_branch->contact_number?$company_branch->contact_number:'069 455555/ 099 788883';



    ?>

    @if(app()->getLocale() == 'kh')
     <div class="report_header">
    <img style="float: left;width: 120px;display:block;" src="{{ $logo }}" class="project-logo"/>
 
                <h1 class="title text-center brach_name">{{ $branch_name }}</h1>
                <h1 class="title text-center brach_name">{{ ($projects_row->dealer != '')?$projects_row->dealer:'&nbsp;' }}</h1>
                       
        </div>
@else

    <div class="report_header">   
    <img style="float: left;width: 120px;display:block;"  src="{{ $logo }}" class="project-logo"/>
            <h1 class="title text-center brach_name">{{ $branch_name }}</h1>
            <h1 class="title text-center brach_name">{{ ($projects_row->dealer != '')?$projects_row->dealer:'&nbsp;' }}</h1>
           
        </table>
 
    </div>

@endif


