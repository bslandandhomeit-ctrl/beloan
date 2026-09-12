@extends('layouts.app')
@section('css')
<style>
    @font-face {
        src: url("{{ asset('publict/fonts/KhmerOSmuollight.ttf') }}") format("truetype");
        src: url("{{ asset('publict/fonts/KhmerOScontent.ttf') }}") format("truetype");
        src: url("{{ asset('publict/fonts/wingding.ttf') }}") format("truetype");
    }
.company_2{
color: #337ab7;
}
.company_2{
    color: #337ab7;
}
.company_2 .font-wingding{
    color: #337ab7;
}
.company_1{
color: #16944a;
}
.company_1{
    color: #16944a;
}
.company_1 .font-wingding{
    color: #16944a;
}
    .font-family{
        font-family: 'khmer os muol light';
    }
    .font-wingding{
        font-family: 'Wingdings';
        font-size: 14pt;
    }
    h1.khmer-title{
        font-size: 16pt;
        margin: 0 !important;
    }
    h2.khmer-title{
        font-size: 14pt;
    }
    h3.khmer-title{
        font-size: 12pt;
    }
    .project-logo{
        width: 200px;
        position: absolute;
        top: 160px;
    }
    table.table-borderless td{
        font-family: 'Khmer OS Content';
        font-size: 9.5pt;
        border: unset !important;
        padding: 8px !important;
    }
    table.table-contract-bordered td{
        font-family: 'Khmer OS Content';
        font-size: 9.5pt;
        border: 1px solid #000 !important;
        padding: 8px !important;
    }
    table.table.table-sm.table-bordered {
        font-size: 8pt;
    }
    p{
        font-family: 'Khmer OS Content';
        font-size: 9.5pt;
        text-align: justify;
        line-height: 2;
    }
    .khmer-title{
        font-family: 'Khmer os Muol light';
    }
    p.khmer-title{
        font-family: 'Khmer os Muol light';
    }
    h4.khmer-title{
        font-size: 11pt;
    }
    .bg-grey{
        background: #eee;
    }
    tr.table-secondary{
        background: #bdbdbdb5 !important;
    }
    .text-bold{
        font-weight: bold;
    }
    .text-center{
        text-align: center;
    }
    .text-right{
        text-align: right;
    }
    .text-left{
        text-align: left;
    }
    #printArea{
        width: 21cm;
        min-height: 29.7cm;
        margin: 0 auto;
    }
    .table{
        margin: 10px 0px;
    }
    ol li{
        font-family: 'Khmer OS Content';
        font-size: 9.5pt; 
        line-height: 2;
    }
    ul.ml-4 li{
        font-family: 'Khmer OS Content';
        font-size: 9.5pt; 
        line-height: 2;
        list-style: none;
    }
    .pb-0{
        margin-bottom: 0 !important;
    }
    .contract-user-sign p{
        text-align: center;
    }
    .table tbody>tr>td, .table tfoot>tr>td {
        padding: 3px;
    }
p{
    font-family: 'Khmer OS Battambang';
        font-size: 11pt; 
        line-height: 2;
        list-style: none;
    
}
span{
    font-family: 'Khmer OS Battambang';
    
}
input {
    font-family: 'Khmer OS Battambang';
}
.project-logo {
    width: 200px;
    position: absolute;
    top: 80px;
}
.project-logo-1{
    top: 100px;
    margin-left: -10px;
}
.project-logo-2{
    margin-left: -20px;
}
    @media print {
        .col-lg-6{
            width: 50% !important;
            float: left !important;
        }
        .font-family{
            font-family: 'khmer os muol light';
        }
        .font-wingding{
            font-family: 'Wingdings';
            font-size: 14pt;
        }
        h1.khmer-title{
            font-size: 16pt;
            margin: 0 !important;
        }
        h2.khmer-title{
            font-size: 14pt;
        }
        h3.khmer-title{
            font-size: 12pt;
        }
        .project-logo{
            width: 200px !important;
            position: absolute !important;
            top: 35px !important;
        }
        .table td, .table th {
            background-color: unset !important; 
        }
        table.table-borderless td{
            font-family: 'Khmer OS Battambang';
            font-size: 12pt;
            border: unset !important;
            padding: 8px !important;
        }
        table.table-contract-bordered td{
            font-family: 'Khmer OS Battambang';
            font-size: 12pt;
            border: 1px solid #000 !important;
            padding: 8px !important;
        }
        table.table.table-sm.table-bordered {
            font-size: 8pt;
        }
        p{
            font-family: 'Khmer OS Battambang';
            font-size: 11pt !important; 
            text-align: justify;
            line-height: 2;
            margin: 0 0 10px;
        }
        .khmer-title{
            font-family: 'Khmer os Muol light';
        }
        p.khmer-title{
            font-family: 'Khmer os Muol light';
        }
        h4.khmer-title{
            font-size: 12pt;
        }
        .bg-grey{
            background: #eee !important;
        }
        tr.table-secondary{
            background: #bdbdbdb5 !important;
        }
        td.title.bg-grey {
            background: #eee !important;
        }
        .text-bold{
            font-weight: bold;
        }
        .text-center{
            text-align: center;
        }
        .text-right{
            text-align: right;
        }
        .text-left{
            text-align: left;
        }
        #printArea{
            width: 20cm !important;
            min-height: 29.7cm !important;
        }
        .table{
            margin: 10px 0px;
        }
        .company_2{
color: #337ab7;
}
.company_2{
    color: #337ab7 !important; 
}
.company_2 .font-wingding{
    color: #337ab7 !important; 
}
.company_1{
color: #16944a !important; 
}
.company_1{
    color: #16944a !important; 
}
.company_1 .font-wingding{
    color: #16944a !important; 
}
        p{
    font-family: 'Khmer OS Battambang';
        font-size: 11pt; 
        line-height: 2;
        list-style: none;
    
}
span{
    font-family: 'Khmer OS Battambang';
    
}
input {
    font-family: 'Khmer OS Battambang';
}
.project-logo {
    width: 200px;
    position: absolute;
    top: 60px;
}
.project-logo-1{
    top: 100px;
}
.project-logo {
    width: 200px;
    position: absolute;
    top: 60px;
}
.project-logo-2{
    top: -20px !important;
}
.note{
    width: 100% !important;
}
.blue{
    color:blue !important;
}
.note p{
    font-size: 6pt !important;
}
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="print-btn" style="width: 21cm;margin: 0 auto;">
            <header class="panel-heading">
                <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
            </header>
        </div>
        <section class="panel" style="width: 21cm !important;min-height: 29.7cm !important;margin: 0 auto;">
            <div id="printArea" class="panel-body" style="padding: 45px !important;">
                <div class="font-family">
                    <h1 class="khmer-title text-center company_{{$loan->branch_id}}">ព្រះរាជាណាចក្រកម្ពុជា</h1>
                    <h2 class="khmer-title text-center company_{{$loan->branch_id}}">ជាតិ សាសនា ព្រះមហាក្សត្រ</h2>
                    <p class="text-center font-wingding company_{{$loan->branch_id}}" style="margin-bottom: 40px;">
                        
                        {{-- <img src="{{ asset('img/kbach.png') }}"/> --}}
                    </p>
                    
                    <?php
                        $logo = '';
                        if($loan->branch_id===2){
                            $logo = asset('data/projects/1201202261dea27736ef0.jpg',false);
                        }else{
                            $logo = asset('data/projects/1201202261dea3d16c2f0.jpg',false);
                        }
                    ?>
                    <img width="60" src="{{ $logo }}" class="project-logo project-logo-{{$loan->branch_id}}"/>
                    @yield('contract_type')

                    <p><span style="margin-left: 60px;">ក្រុមហ៊ុន</span> <span> {{ $loan->branch_name }}</span> មានលេខចុះបញ្ជីពាណិជ្ជកម្ម {{$loan->company_branch_id===2?'០០០៤១៨៧៦ ចុះថ្ងៃទី០៥ ខែសីហា ឆ្នាំ២០១៥':'០០០៣៩០៩៨ ចុះថ្ងៃទី៣១ ខែធ្នូ ឆ្នាំ២០១៨ '}}  មានអាសយដ្ឋានស្ថិតនៅអាគារលេខ B2-109 B2-110 ក្នុងសង្កាត់ទន្លេបាសាក់ ខណ្ឌចំការមន  រាជធានីភ្នំពេញ ។</p>
                  
                        
                            <p><span style="text-decoration: underline;margin-left: 60px;">សូមបញ្ជាក់ថា៖</span> អតិថិជនឈ្មោះ&nbsp;<span>{{ $loan->client->ClientCbcGeneral->family_name_kh.' '.$loan->client->ClientCbcGeneral->first_name_kh }}</span>
                            <?php
                                $client_cbc = $loan->client->ClientCbcGeneral;
                                $gender = ['F' => 'ស្រី','M'=>'ប្រុស'];
                            ?>
                            <span>&nbsp;&nbsp;&nbsp;ភេទ៖ {{ $gender[$client_cbc->gender] }}&nbsp;</span>
    
                            <span>កើតនៅថ្ងៃទី{{ trim(khmerNumber(date('d',strtotime($client_cbc->date_of_birth)))) }} ខែ{{ trim(khmerMonth(date('M',strtotime($client_cbc->date_of_birth)))) }} ឆ្នាំ{{ trim(khmerNumber(date('Y',strtotime($client_cbc->date_of_birth)))) }}</span>


                            @if($loan->client->ClientCbcGeneral->country)
                                @if($loan->client->ClientCbcGeneral->country->iso_code_2 == "KH")
                                <span>សញ្ជាតិ៖ ខ្មែរ</span>
                                @else
                                    <span>សញ្ជាតិ៖ {{ $loan->client->ClientCbcGeneral->country->description->first()->name }}</span>
                                @endif
                            @else
                                <span>សញ្ជាតិ៖ </span>
                            @endif
                            @if($loan->client->ClientCbcGeneral->country)
                                @if($loan->client->ClientCbcGeneral->country->iso_code_2 == "KH")
                                <span>កាន់អត្តសញ្ញាណប័ណ្ណលេខ</span>
                                @else
                                <span>កាន់លិខិតឆ្លងដែនលេខ</span>
                                @endif
                            @else
                                <span>កាន់អត្តសញ្ញាណប័ណ្ណលេខ ឬលិខិតឆ្លងដែនលេខ</span>
                            @endif

                            
                            <span>{{ khmerNumber($loan->client->ClientCbcIdentifications->id_number) }}</span>
                            <span>ចុះថ្ងៃទី{{ trim(khmerNumber(date('d',strtotime($loan->client->ClientCbcIdentifications->issued_date)))) }} ខែ{{ trim(khmerMonth(date('M',strtotime($loan->client->ClientCbcIdentifications->issued_date)))) }} ឆ្នាំ{{ trim(khmerNumber(date('Y',strtotime($loan->client->ClientCbcIdentifications->issued_date)))) }}</span>



                            @if($loan->co_borrowers->Clients->ClientCbcGeneral)
                                <span>និងឈ្មោះ&nbsp;{{ $loan->co_borrowers->Clients->ClientCbcGeneral->family_name_kh.' '.$loan->co_borrowers->Clients->ClientCbcGeneral->first_name_kh }}</span>
                                <span>&nbsp;&nbsp;&nbsp;ភេទ៖ {{ $gender[$loan->co_borrowers->Clients->ClientCbcGeneral->gender] }}&nbsp;</span>
                                <span>កើតនៅថ្ងៃទី{{ trim(khmerNumber(date('d',strtotime($loan->co_borrowers->Clients->ClientCbcGeneral->date_of_birth)))) }} ខែ{{ trim(khmerMonth(date('M',strtotime($loan->co_borrowers->Clients->ClientCbcGeneral->date_of_birth)))) }} ឆ្នាំ{{ trim(khmerNumber(date('Y',strtotime($loan->co_borrowers->Clients->ClientCbcGeneral->date_of_birth)))) }}</span>

                                @if($loan->co_borrowers->Clients->ClientCbcGeneral->country)
                                @if($loan->co_borrowers->Clients->ClientCbcGeneral->country->iso_code_2 == "KH")
                                <span>សញ្ជាតិ៖ ខ្មែរ</span>
                                @else
                                    <span>សញ្ជាតិ៖ {{ $loan->co_borrowers->Clients->ClientCbcGeneral->country->description->first()->name }}</span>
                                @endif
                            @else
                                <span>សញ្ជាតិ៖ </span>
                            @endif
                            @if($loan->co_borrowers->Clients->ClientCbcGeneral->country)
                                @if($loan->co_borrowers->Clients->ClientCbcGeneral->country->iso_code_2 == "KH")
                                <span>កាន់អត្តសញ្ញាណប័ណ្ណលេខ</span>
                                @else
                                <span>កាន់លិខិតឆ្លងដែនលេខ</span>
                                @endif
                            @else
                                <span>កាន់អត្តសញ្ញាណប័ណ្ណលេខ ឬលិខិតឆ្លងដែនលេខ</span>
                            @endif

                            
                            <span>{{ khmerNumber($loan->co_borrowers->Clients->ClientCbcIdentifications->id_number) }}</span>
                            <span>ចុះថ្ងៃទី{{ trim(khmerNumber(date('d',strtotime($loan->co_borrowers->Clients->ClientCbcIdentifications->issued_date)))) }} ខែ{{ trim(khmerMonth(date('M',strtotime($loan->client->ClientCbcIdentifications->issued_date)))) }} ឆ្នាំ{{ trim(khmerNumber(date('Y',strtotime($loan->client->ClientCbcIdentifications->issued_date)))) }}</span>

                            @endif


                            <span>អាសយដ្ឋានបច្ចុប្បន្ននៅ </span>
                            <?php
                                $country_name = '';
                                $check_count_iso_code = '';
                                if(!empty($loan->client) || !empty($loan->client->ClientCbcGeneral) || !empty($loan->client->ClientCbcGeneral->country)){
                                    $country_name = !empty($loan->client->ClientCbcGeneral->country->description[0])?$loan->client->ClientCbcGeneral->country->description[0]->name:"N/A";
                                    if($loan->client->ClientCbcGeneral->country->iso_code_2 != "KH"){
                                        $check_count_iso_code = $loan->client->ClientCbcGeneral->country->iso_code_2;
                                    }
                                }
                            ?>
                            <span>
                            @foreach($client->Address as $Address)
                                ភូមិ/ក្រុម​{{$Address->Village->kh_name}} ឃុំ/សង្កាត់{{$Address->Commune->kh_name}} ស្រុក/ខណ្ឌ{{$Address->District->kh_name}} ខេត្ត/ក្រុង{{$Address->province->kh_name}}
                                

                            @endforeach
                            <span>&nbsp; ពិតជាបានគោរពកាតព្វកិច្ចយ៉ាងពេញលេញក្នុងការទូទាត់ប្រាក់បង្គ្រប់ទៅតាមតារាងបង់ប្រាក់ ដូចបានភ្ជាប់ក្នុងកិច្ចសន្យាទិញ-លក់ អចលនទ្រព្យលេខ៖ {{$loan->units->code }}</span>
                            </span>
                
                            
                            <span>ចុះថ្ងៃទី{{ trim(khmerNumber(date('d',strtotime($loan->disburse_date)))) }} ខែ{{ trim(khmerMonth(date('M',strtotime($loan->disburse_date)))) }} ឆ្នាំ{{ trim(khmerNumber(date('Y',strtotime($loan->disburse_date)))) }}</span>
                        <span>ក្នុងគម្រោង <span>{{ $loan->projects->dealer }}</span> របស់ក្រុមហ៊ុន {{ $loan->branch_name }}
                        នៅថ្ងៃទី<input type="text" value="{{ !empty($loan->payoff_date)?trim(khmerNumber(date('d',strtotime($loan->payoff_date)))):'' }}" style="border: none;margin: 0px;width: 27px;" /> ខែ<input type="text" value="{{ !empty($loan->payoff_date)? trim(khmerMonth(date('M',strtotime($loan->payoff_date)))):'' }}" style="border: none;margin: 0px;width: 37px;" /> ឆ្នាំ<input type="text" value="{{ !empty($loan->payoff_date)?trim(khmerNumber(date('Y',strtotime($loan->payoff_date)))) :'' }}" style="border: none;margin: 0px;width: 50px;" /> ប្រាកដមែន។</td>
                    </p>

                
                <p><span style="margin-left: 60px;">អាស្រ័យហេតុនេះ ក្រុមហ៊ុនចេញលិខិតនេះជូនអតិថិជន ដើម្បីអាចយកទៅប្រើប្រាស់តាមតម្រូវការស្របតាមច្បាប់៕</span></p>
                <div style="float: right;">
                    <p style="text-align: right;">រាជធានីភ្នំពេញ, ថ្ងៃទី<input type="text" style="border: none;margin: 0px;width: 27px;" /> ខែ<input type="text" style="border: none;margin: 0px;width: 37px;" /> ឆ្នាំ<input type="text" style="border: none;margin: 0px;width: 50px;" /></p>
                    @if($loan->branch_id===1)
                    <h4 class="khmer-title company_{{$loan->branch_id}}">ក្រុមហ៊ុន អ៊ីស្ត លែន អេន ហូម ឯ.ក</h4>
                    @else
                    <h4 class="khmer-title company_{{$loan->branch_id}}">ក្រុមហ៊ុន ប៊ីអេស លែន & ហូម ឯ.ក</h4>
                    @endif
                                    <div style="page-break-after: always;"></div>

                      
                    @yield('first')
                    @yield('second')
                    @yield('third')
                    @yield('forth')   
                </div>   
                <div class="note" style="position: absolute;bottom: 15px;width:50%;">
                    <p style="font-size: 6pt;margin: 3px;margin-left: 0px;">យោង៖</p>
                    <p style="font-size: 6pt;margin: 3px;margin-left: 0px;">១. វិក័យបត្របង់ប្រាក់ផ្ដាច់១០០%</p>
                    <p style="font-size: 6pt;margin: 3px;margin-left: 0px;">២.  របាយការណ៍បង់ផ្ដាច់របស់គណនេយ្យ</p>
                    <hr style="border-top: 3px solid #777;    margin-bottom: 10px;" />
                    <p style="font-size: 6pt;color: #777;text-align: center;margin: 0px;">{{ $loan->branch_name }}</p>
                     @if($loan->branch_id===2)
                    <p style="font-size: 6pt;color: #777;text-align: center;margin: 0px;margin-left: 0px;">អគារលេខB2-109, B2-110 សង្កាត់ទន្លេបាសាក់ ខណ្ឌចំការមន រាជធានីភ្នំពេញ</p>
                    <p class="blue" style="font-size: 6pt;color: blue;text-align: center;margin: 0px;margin-left: 0px;">Tel:+855 694 5555/Email: info@bslandhome.com/Website:bslandandhome.com</p>
                    @else
                    <p style="font-size: 6pt;color: #777;text-align: center;margin: 0px;margin-left: 0px;">អគារលេខB2-109, B2-110 សង្កាត់ទន្លេបាសាក់ ខណ្ឌចំការមន រាជធានីភ្នំពេញ</p>
                    <p class="blue" style="font-size: 6pt;color: blue;text-align: center;margin: 0px;margin-left: 0px;">Tel:+855 694 5555/Email: info@bslandhome.com/Website:bslandandhome.com</p>
                    @endif 
                </div>    
            </div>
        </section>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
@endsection