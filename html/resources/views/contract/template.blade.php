@extends('layouts.app')
@section('css')
<style>
    @font-face {
        src: url("{{ asset('publict/fonts/KhmerOSmuollight.ttf') }}") format("truetype");
        src: url("{{ asset('publict/fonts/KhmerOScontent.ttf') }}") format("truetype");
        src: url("{{ asset('publict/fonts/wingding.ttf') }}") format("truetype");
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
        width: 160px;
        position: absolute;
        top: 130px;
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
            width: 160px !important;
            position: absolute !important;
            top: 35px !important;
        }
        .table td, .table th {
            background-color: unset !important; 
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
            margin: 0px !important;
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
            width: 21cm !important;
            min-height: 29.7cm !important;
        }
        .table{
            margin: 10px 0px;
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
                    <h1 class="khmer-title text-center">ព្រះរាជាណាចក្រកម្ពុជា</h1>
                    <h2 class="khmer-title text-center">ជាតិ សាសនា ព្រះមហាក្សត្រ</h2>
                    <p class="text-center font-wingding">
                        
                        {{-- <img src="{{ asset('img/kbach.png') }}"/> --}}
                    </p>
                    @yield('contract_type')
                    <?php //dd($loan->projects,$loan->units->UnitType->projects); ?>
                    <h3 class="khmer-title text-center">{{ isset($loan->units->UnitType->projects->dealer)?$loan->units->UnitType->projects->dealer:'' }}</h3>
                    <?php
                        $logo = '';
                        //if($loan->projects->Company->logo != ''){
                            //$logo = asset('data/company_logo/'.$loan->projects->Company->logo,false);
                        //}else{
                            //$logo = asset('images/noimage.gif',false);
                        //}
                        if(file_exists('data/projects/'.$loan->units->UnitType->projects->logo) && $loan->units->UnitType->projects->logo){
                            $logo = asset('data/projects/'.$loan->units->UnitType->projects->logo,false);
                        }else{
                            $logo = asset('images/noimage.gif',false);
                        }
                    ?>
                    <img width="60" src="{{ $logo }}" class="project-logo"/>
                    <table class="table table-p-1 table-borderless">
                        <tr>
                            <td>លេខ៖ {{ $loan->units->code }}/{{ $loan->units->UnitType->short_code }}</td>
                            <td class="text-right">ធ្វើនៅ ______________ ថ្ងៃទី _______________________</td>
                        </tr>
                    </table>
                    <p><span class="khmer-title">ភាគី “អ្នកលក់”</span> ដែលមានអត្តសញ្ញាណដូចរៀបរាប់ក្នុងតារាងខាងក្រោម</p>
                    <table class="table table-p-1 table-contract-bordered">
                        <tr>
                            <td width="180px" class="text-bold">នាមករណ៍</td>
                            <td width="30px" class="text-center">៖</td>
                            <td>ក្រុមហ៊ុន <span class="text-bold"> {{ $loan->branch_name }}</span> <span class="english text-bold">({{ $loan->branch_name_en }})</span></td>
                        </tr>
                        <tr>
                            <td class="text-bold">លេខបញ្ជីពាណិជ្ជកម្ម</td>
                            <td class="text-center">៖</td>
                            <?php
                                $company =$loan->projects->Company; 
                            ?>
                            <td>{{ khmerNumber($company->commercial_licence_no) }} ចុះថ្ងៃ​ទី {{ khmerNumber(date('d',strtotime($company->commercial_issued_date))) }} ខែ {{ khmerMonth(date('M',strtotime($company->commercial_issued_date))) }} ឆ្នាំ {{ khmerNumber(date('Y',strtotime($company->commercial_issued_date))) }}</td>
                        </tr>
                        <tr>
                            <td class="text-bold">ទីស្នាក់ការចុះបញ្ជី</td>
                            <td class="text-center">៖</td>
                            <td>{{ $loan->projects->Company->address_one }}, {{ $loan->projects->Company->address_two }}</td>
                        </tr>
                        <tr>
                            <td class="text-bold">ឈ្មោះគម្រោង</td>
                            <td class="text-center">៖</td>
                            <td><span class="text-bold">{{ $loan->projects->dealer }}</span> <span class="english text-bold">({{ $loan->projects->dealer_en }})</span> @yield('project_suffix')</td>
                        </tr>
                        <tr>
                            <td class="text-bold">តំណាងស្របច្បាប់ដោយ</td>
                            <td class="text-center">៖</td>
                            <?php
                                $sale_representative = $loan->projects->Representative;
                                $genders = ['female' => 'ស្រី','male'=>'ប្រុស'];
                            ?>
                            <td>ឈ្មោះ <span class="text-bold">{{ $sale_representative->name }}</span> ភេទ <span class="text-bold">{{ $genders[$sale_representative->gender] }}</span> កើត​ថ្ងៃ​ទី {{ khmerNumber(date('d',strtotime($sale_representative->dob))) }} ខែ {{ khmerMonth(date('M',strtotime($sale_representative->dob))) }} ឆ្នាំ {{ khmerNumber(date('Y',strtotime($sale_representative->dob))) }} សញ្ជាតិខ្មែរ កាន់​អត្ត​សញ្ញាណ​ប័ណ្ណ​លេខ​ <span class="text-bold">{{ khmerNumber($sale_representative->national_id) }}</span> ចុះ​ថ្ងៃ​ទី {{ khmerNumber(date('d',strtotime($sale_representative->national_issued_date))) }} ខែ {{ khmerMonth(date('M',strtotime($sale_representative->national_issued_date))) }} ឆ្នាំ {{ khmerNumber(date('Y',strtotime($sale_representative->national_issued_date))) }}</td>
                        </tr>
                        <tr>
                            <td class="text-bold">លេខទូរស័ព្ទទំនាក់ទំនង</td>
                            <td class="text-center">៖</td>
                            <td>{{ khmerNumber($sale_representative->phone) }}</td>
                        </tr>
                    </table>

                    <p><span class="khmer-title">ភាគី “អ្នកទិញ”</span> ដែលមានអត្តសញ្ញាណដូចរៀបរាប់ក្នុងតារាងខាងក្រោម</p>
                    <table class="table table-p-1 table-contract-bordered">
                        <tr>
                            <td width="180px" class="text-bold">១. នាម ឬនាមករណ៍</td>
                            <td width="30px" class="text-center">៖</td>
                            <td colspan="3"><span class="text-bold">{{ $loan->client->ClientCbcGeneral->family_name_kh.' '.$loan->client->ClientCbcGeneral->first_name_kh }}</span></td>
                        </tr>
                        <tr>
                            <td width="260" class="text-bold">ថ្ងៃខែឆ្នាំកំណើត</td>
                            <td class="text-center">៖</td>  
                            <?php
                                $client_cbc = $loan->client->ClientCbcGeneral;
                                $gender = ['F' => 'ស្រី','M'=>'ប្រុស'];
                            ?>  
                            <td width="270">ថ្ងៃទី {{ khmerNumber(date('d',strtotime($client_cbc->date_of_birth))) }} ខែ {{ khmerMonth(date('M',strtotime($client_cbc->date_of_birth))) }} ឆ្នាំ {{ khmerNumber(date('Y',strtotime($client_cbc->date_of_birth))) }}</td>
                            <td width="100px">ភេទ៖ {{ $gender[$client_cbc->gender] }}</td>
                            @if($loan->client->ClientCbcGeneral->country)
                                <td width="200px">សញ្ជាតិ៖ {{ $loan->client->ClientCbcGeneral->country->description->first()->name }}</td>
                            @else
                                <td width="200px">សញ្ជាតិ៖ </td>
                            @endif
                        </tr>
                        
                        <tr>
                            <td width="260" class="text-bold">កាន់អត្តសញ្ញាណប័ណ្ណលេខ ឬលិខិតឆ្លងដែនលេខ</td>
                            <td class="text-center">៖</td>
                            <td>{{ khmerNumber($loan->client->ClientCbcIdentifications->id_number) }}</td>
                            <td width="300px" colspan="2">ចុះថ្ងៃទី {{ khmerNumber(date('d',strtotime($loan->client->ClientCbcIdentifications->issued_date))) }} ខែ {{ khmerMonth(date('M',strtotime($loan->client->ClientCbcIdentifications->issued_date))) }} ឆ្នាំ {{ khmerNumber(date('Y',strtotime($loan->client->ClientCbcIdentifications->issued_date))) }}</td>
                        </tr>
                        @if(isset($loan->co_borrowers)) 
                            <tr>
                                <td width="180px" class="text-bold">២. នាម ឬនាមករណ៍</td>
                                <td width="30px" class="text-center">៖</td>
                                <td colspan="3"><span class="text-bold">_______________</span></td>
                            </tr>
                            <tr>
                                <td class="text-bold">ថ្ងៃខែឆ្នាំកំណើត</td>
                                <td class="text-center">៖</td>
                                <td>ថ្ងៃទី _____ ខែ _____ ឆ្នាំ _____</td>
                                <td width="150px">ភេទ៖ _____</td>
                                <td width="150px">សញ្ជាតិ៖ _____</td>
                            </tr>
                            <tr>
                                <td class="text-bold">កាន់អត្តសញ្ញាណប័ណ្ណលេខ ឬលិខិតឆ្លងដែនលេខ</td>
                                <td class="text-center">៖</td>
                                <td>_________</td>
                                <td width="300px" colspan="2">ចុះថ្ងៃទី _____ ខែ _____ ឆ្នាំ _____</td>
                            </tr>
                        @else
                            @if($loan->co_borrowers->Clients->ClientCbcGeneral)
                                <tr>
                                    <td width="180px" class="text-bold">២. នាម ឬនាមករណ៍</td>
                                    <td width="30px" class="text-center">៖</td>
                                    <td colspan="3"><span class="text-bold">{{ $loan->co_borrowers->Clients->ClientCbcGeneral->family_name_kh.' '.$loan->co_borrowers->Clients->ClientCbcGeneral->first_name_kh }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-bold">ថ្ងៃខែឆ្នាំកំណើត</td>
                                    <td class="text-center">៖</td>
                                    <td>ថ្ងៃទី {{ khmerNumber(date('d',strtotime($loan->co_borrowers->Clients->ClientCbcGeneral->date_of_birth))) }} ខែ {{ khmerMonth(date('M',strtotime($loan->co_borrowers->Clients->ClientCbcGeneral->date_of_birth))) }} ឆ្នាំ {{ khmerNumber(date('Y',strtotime($loan->co_borrowers->Clients->ClientCbcGeneral->date_of_birth))) }}</td>
                                    <td width="150px">ភេទ៖ {{ $gender[$loan->co_borrowers->Clients->ClientCbcGeneral->gender] }}</td>
                                    @if ($loan->co_borrowers->Clients->ClientCbcGeneral->country)
                                        <td width="150px">សញ្ជាតិ៖ {{ $loan->co_borrowers->Clients->ClientCbcGeneral->country->description->first()->name }}</td>
                                    @else
                                        <td width="150px">សញ្ជាតិ៖ </td>
                                    @endif

                                </tr>
                                <tr>
                                    <td class="text-bold">កាន់អត្តសញ្ញាណប័ណ្ណលេខ ឬលិខិតឆ្លងដែនលេខ</td>
                                    <td class="text-center">៖</td>
                                    <td>{{ khmerNumber($loan->co_borrowers->Clients->ClientCbcIdentifications->id_number) }}</td>
                                    <td width="300px" colspan="2">
                                    ចុះថ្ងៃទី {{ khmerNumber(date('d',strtotime($loan->co_borrowers->Clients->ClientCbcIdentifications->issued_date))) }} ខែ {{ khmerMonth(date('M',strtotime($loan->co_borrowers->Clients->ClientCbcIdentifications->issued_date))) }} ឆ្នាំ {{ khmerNumber(date('Y',strtotime($loan->co_borrowers->Clients->ClientCbcIdentifications->issued_date))) }}    
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td width="180px" class="text-bold">២. នាម ឬនាមករណ៍</td>
                                    <td width="30px" class="text-center">៖</td>
                                    <td colspan="3"><span class="text-bold"></span></td>
                                </tr>
                                <tr>
                                    <td class="text-bold">ថ្ងៃខែឆ្នាំកំណើត</td>
                                    <td class="text-center">៖</td>
                                    <td></td>
                                    <td width="150px"></td>
                                    <td width="150px"></td>
                                </tr>
                                <tr>
                                    <td class="text-bold">កាន់អត្តសញ្ញាណប័ណ្ណលេខ ឬលិខិតឆ្លងដែនលេខ</td>
                                    <td class="text-center">៖</td>
                                    <td></td>
                                    <td width="300px" colspan="2"></td>
                                </tr>
                            @endif
                        @endif
                        <tr>
                            <td width="180px" class="text-bold">អាសយដ្ឋាន</td>
                            <td width="30px" class="text-center">៖</td>
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
                            <td colspan="3">
                            @foreach($client->Address as $Address)
                                <p>{{$Address->Village->kh_name}} {{$Address->Commune->kh_name}} {{$Address->District->kh_name}} {{$Address->province->kh_name}}<span> @foreach($Address->country->description as $de)
                                        @if($de->language_id == 2)
                                            {{$de->name_kh}}
                                        @endif
                                    @endforeach</span>
                                </p>

                            @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td width="180px" class="text-bold">លេខទូរស័ព្ទទំនាក់ទំនង</td>
                            <td width="30px" class="text-center">៖</td>
                            <td colspan="3"><span class="text-bold">{{ khmerNumber($loan->client->phone1).' / '.khmerNumber($loan->client->phone2) }}</span></td>
                        </tr>
                    </table>
                    <p>ភាគីទាំងពីរ ហៅដោយឡែកថា ភាគី <span class="text-bold">“អ្នកលក់”</span> ឬ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ហើយហៅជារួមថា <span class="text-bold">“គូភាគី”</span> ដោយផ្អែកលើគោលការស្ម័គ្រចិត្ត និងស្មើភាព ភាគី <span class="text-bold">“អ្នកលក់”</span> និង ភាគី <span class="text-bold">“អ្នកទិញ”</span> បានព្រមព្រៀងគ្នាក្នុងការលក់ទិញនូវអចលនវត្ថុ ដែលមានអត្តសញ្ញាណ តម្លៃ និងខ្លឹមសារដូចខាងក្រោម៖</p>
                    <div style="page-break-after: always;"></div>

                    @yield('praka')

                    <table class="table table-p-1 table-contract-bordered">    
                        @if(isset($sale_representative->name) ) 
                            <tr>
                                <td class="text-center"><span class="khmer-title">ភាគី “អ្នកលក់”</span></td>
                                <td width="30px"></td>
                                <td class="text-center"><span class="khmer-title">សាក្សី</span></td>
                                <td width="30px"></td>
                                <td class="text-center" colspan="3"><span class="khmer-title">ភាគី “អ្នកទិញ”</span></td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold" height="120px" width="25%" style="vertical-align:bottom">{{ $sale_representative->name }}</td>
                                <td width="30px"></td>
                                <td class="text-center" width="25%"></td>
                                <td width="30px"></td>
                                <?php
                                    $customer_name1 = $loan->client->ClientCbcGeneral->family_name_kh.' '.$loan->client->ClientCbcGeneral->first_name_kh;
                                    $customer_name2 = $loan->co_borrowers->Clients->ClientCbcGeneral->family_name_kh.' '.$loan->co_borrowers->Clients->ClientCbcGeneral->first_name_kh;
                                ?> 
                                <td width="25%" class="text-center text-bold"  style="vertical-align:bottom">{{ isset($customer_name1) ? $customer_name1:'____________' }}</td>
                                <td width="1%"></td>
                                <td width="25%" class="text-center text-bold" style="vertical-align:bottom">
                                    {{ isset($customer_name2)?$customer_name2:'____________' }}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center"><span class="khmer-title">ភាគី “អ្នកលក់”</span></td>
                                <td width="30px"></td>
                                <td class="text-center"><span class="khmer-title">សាក្សី</span></td>
                                <td width="30px"></td>
                                <td class="text-center"><span class="khmer-title">ភាគី “អ្នកទិញ”</span></td>
                            </tr>
                            <tr>
                              <td class="text-center text-bold" height="120px" width="33%" style="vertical-align:bottom"></td>
                              <td width="30px"></td>
                              <td class="text-center" width="33%"></td>
                              <td width="30px"></td>
                              <td class="text-center text-bold" width="33%" style="vertical-align:bottom"></td>
                            </tr>
                        @endif
                    </table>
                    <div style="page-break-after: always;"></div>
                    @include('contract.payment_schedule')
                      
                    @yield('first')
                    @yield('second')
                    @yield('third')
                    @yield('forth')   
                </div>         
            </div>
        </section>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
@endsection