<?php
//$nf = new NumberFormatter("km", NumberFormatter::SPELLOUT );
?>
@extends('contract.template')
@section('contract_type')
<h3 class="khmer-title text-center mt-3 mb-3">កិច្ចសន្យាលក់ ទិញ ខុនដូ</h3>
@endsection
@section('project_suffix')
<span class="text-bold">“អគារសហកម្មសិទ្ធិ”</span>
@endsection
@section('praka')
<p class="khmer-title pb-0 mb-0">ប្រការ១៖ អត្តសញ្ញាណនៃអចលនវត្ថុលក់ទិញ និងតម្លៃលក់ទិញ</p>
<p><span class="text-bold">១.១.</span> ភាគី <span class="text-bold">“អ្នកលក់”</span> គឺ​ជា​កម្មសិទ្ធិករ​ស្រប​ច្បាប់​លើ​អចលនវត្ថុ យល់​ព្រម​លក់ ហើយ ភាគី <span class="text-bold">“អ្នកទិញ”</span> 
យល់​ព្រម​ទិញ​នូវ​អចលនវត្ថុ​ដូច​មាន​ចែង​ក្នុង​តារាង​អត្តសញ្ញាណ​នៃ​អចលនវត្ថុ​លក់ទិញ និង​តម្លៃ​លក់​ទិញ​នេះ។</p>
<p><span class="text-bold">១.២.</span> តារាងអត្តសញ្ញាណនៃអចលនវត្ថុលក់ទិញ និងតម្លៃលក់ទិញ (“អចលនវត្ថុ”)៖</p>
<table class="table table-p-1 table-contract-bordered">
    <tr>
    	<td width="180px" class="text-bold">ប្រភេទ</td>
    	<td width="30px" class="text-center">៖</td>      
    	<td width="135px"><span class="text-bold english">{{ $loan->UnitTypes->name }}</span></td>
    	<td width="135px" class="text-bold">ចំណែកឯកជនលេខ</td>
    	<td width="30px" class="text-center">៖</td>
    	<td width="250px" class="english text-bold">{{ $loan->units->code }}</td>
    </tr>
    <tr>
    	<td width="180px" class="text-bold">ជាន់ទី</td>
    	<td width="30px" class="text-center">៖</td>      
    	<td>
    		<p>{{ $loan->units->floor }}</p>
    	</td>
    	<td width="100px" class="text-bold">
            <p class="mb-0 pb-0">ទំហំសំណង់</p>
            <small><i>(ជាទំហំក្រឡាផ្ទៃចំណែកឯកជន និង ទំហំក្រឡាផ្ទៃប្រើប្រាស់រួម)</i></small>
        </td>
    	<td width="30px" class="text-center">៖</td>
    	<td width="250px">
    		<p class="pb-0">
    		  {{ $loan->units->building_area }} ម៉ែត្រការ៉េ
    		</p>
    		<small><i>ដែល​អាច​មាន​ការ​ប្រែប្រួល​ជា​លក្ខណៈ​បច្ចេកទេស​ត្រឹម ៣ម៉ែត្រក្រឡា លើស ឬ ខ្វះ អាស្រ័យ​លើ​ការ​កំណត់​ព្រុំ​របស់​មន្ត្រី​សុរិយោដី ហើយ​គូភាគី​នឹង​មិន​តវ៉ា​ទាមទារ​សំណង​ពីគ្នា​ឡើយ)។</i></small>
    	</td>
    </tr>
    <tr>
        <td width="180px" class="text-bold">ទីតាំងគម្រោង</td>
        <td width="30px" class="text-center">៖</td>      
        <td colspan="4">
            {{ $loan->projects->address }}
        </td>
    </tr>
    <tr>
    	<td width="180px" class="text-bold">សេវា និងថ្លៃគ្រប់គ្រងថែទាំ</td>
    	<td width="30px" class="text-center">៖</td>      
    	<td colspan="4">
    		<p class="pb-0">
                {{ khmerNumber($loan->UnitTypes->annual_management_fee) }} ({{khNumberWord($loan->UnitTypes->annual_management_fee) }})
                ដុល្លារអាមេរិកក្នុងមួយខែ 
    		</p>
    		<small><i>ដោយ​ត្រូវ​បង់​សរុប​មួយ​ឆ្នាំ​ម្តងៗ  ដោយ​គិត​លើ​មូលដ្ឋាន ស្មើ​នឹង​ចំនួន {{ khmerNumber($loan->UnitTypes->management_fee_per_square) }} ({{ khNumberWord($loan->UnitTypes->management_fee_per_square) }}) ក្នុង​មួយ​ម៉ែត្រ​ក្រឡា​ក្នុង​មួយ​ខែ នៃ​ទំហំ​ក្រឡា​ផ្ទៃ​ចំណែក​ឯកជន និង ទំហំ​ក្រឡា​ផ្ទៃ​ប្រើ​ប្រាស់​រួម​សរុប ដូច​មាន​ចែង​លម្អិត​ក្នុង​ប្រការ ៦ នៃ​កិច្ចសន្យា​នេះ។</i></small>
    	</td>
    </tr>
    <tr>
    	<td width="180px" class="text-bold">ការផ្តល់ជូនសេវាបន្ថែម</td>
    	<td width="30px" class="text-center">៖</td>      
    	<td colspan="4">
    		<p>{!! $loan->unittypes->management_service_kh !!}</p>
    	</td>
    </tr>
    <tr>
    	<td width="180px" class="text-bold">ប្រភេទ​នៃ​ប័ណ្ណ​កម្មសិទ្ធិ​ដែល​ប្រគល់​ជូន​ភាគី “អ្នកទិញ”</td>
    	<td width="30px" class="text-center">៖</td>      
    	<td colspan="4">
    		{{ $loan->unittypes->title_clause_kh }}
    	</td>
    </tr>  
    <tr>
    	<td width="180px" class="text-bold">តម្លៃលក់ទិញ</td>
    	<td width="30px" class="text-center">៖</td>
    	<td class="text-bold" colspan="4">
            <p class="mb-0 pb-0">
    		{{ khmerNumber(number_format($loan->final_price)) }} ({{ khNumberWord($loan->final_price) }})
            ដុល្លារអាមេរិក
            </p>
            <small><i>តម្លៃ​នេះ ជា​តម្លៃ​ដែល​មាន​ការ​តុប​តែង​រួម​បញ្ជូល​ទាំង​សម្ភារៈ​ប្រើប្រាស់​ដូច​មាន​ចែង​ក្នុង​ឧបសម្ព័ន្ធ៣។ ក្នុង​ករណី​ដែល រោង​ចក្រ​ដែល​អ្នក​ផ្គត់ផ្គង់​សម្ភារៈ​ឈប់​ផលិត​នូវ​ផលិតផលដែល ភាគី ”អ្នកលក់” បានជ្រើសរើស ភាគី អ្នកលក់” សូម​រក្សា​សិទ្ធិ​ក្នុង​ការ​ផ្លាស់ប្តូរ​បំពាក់​នូវ​សម្ភារៈ​ផ្សេង​ជំនួស​វិញ​ដោយ​រក្សា​នូវ​គុណភាព​ដដែល។</i></small>
    	</td>    	
    </tr>
</table>
<p><span class="text-bold">១.៣.</span> ភាគី <span class="text-bold">“អ្នកទិញ”</span> យល់​ព្រម​ទទួល​យក​ទាំង​ស្រុង​នូវ​ប្លង់​រចនា​ម៉ូដ​សំណង់ ព្រម​ទាំង​សន្យា​ថា​មិន​កែប្រែ​រចនាបទ ឬទ្រង់ទ្រាយ ឬគ្រោង​សំណង់​ខាងក្នុង និង​ខាង​ក្រៅ​អចលនវត្ថុ​ដោយ​ខ្លួនឯង ឬ​តាម​រយៈ​ការ​ជួល​បុគ្គល​ដទៃ​មក​ធ្វើការ​កែប្រែ​រចនាបទ ឬទ្រង់ទ្រាយ ឬ​គ្រោង​សំណង់​នៃ​អចលនវត្ថុ​នោះ​ទេ។ ប្រសិន​បើ​មាន​ការ​ចាំបាច់ ភាគី <span class="text-bold">“អ្នកទិញ”</span> អាច​ដាក់​លិខិត​ស្នើ​សុំ​មក​ភាគី <span class="text-bold">“អ្នកលក់”</span> ដោយ​មាន​បញ្ជាក់​ពី​មូលហេតុ​អោយ​បាន​ត្រឹមត្រូវ ច្បាស់លាស់ និងសមរម្យ។ ក្នុង​ករណី​ដែល​ភាគី <span class="text-bold">“អ្នកលក់”</span> ពិចារណា​ហើយ​អនុញ្ញាត និង​យល់​ព្រម​តាម​ការ​ស្នើ​សុំ​នោះ ភាគី <span class="text-bold">“អ្នកលក់”</span> គឺ​ជា​អ្នក​កំណត់ និង​ជ្រើសរើស​បុគ្គល​ណាមួយ ដែល​ខ្លួន​គិត​ថា​មាន​លក្ខណៈ​សម្បត្តិ​គ្រប់គ្រាន់​ក្នុង​ការ​ទទួល​យក​ការងារ​នេះ ដោយ​ការ​ចំណាយ​ទាំងអស់​ជា​បន្ទុក​របស់​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ដែល​បាន​ស្នើសុំ។</p>
<div style="page-break-before: always;"></div>
<p class="khmer-title pb-0 mb-0">ប្រការ២៖ ការទូទាត់ប្រាក់ថ្លៃលក់ទិញអចលនវត្ថុ</p>
<p><span class="text-bold">២.១.</span> ដោយ​មាន​ការ​ព្រមព្រៀង​គ្នា​ក្នុង​ការ​លក់​ទិញ នូវ​អចលនវត្ថុ​ដូច​បាន​ចែង​ក្នុង​ប្រការ១ <span class="text-bold">“អត្តសញ្ញាណ​នៃ​អចលនវត្ថុ​លក់​ទិញ និងតម្លៃ​លក់​ទិញ”</span> ខាងលើ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​ទូទាត់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ​ខាង​លើ​ទៅ​តាម​ដំណាក់កាល​នៃ​ការ​ទូទាត់​ឲ្យ​បាន​ត្រឹមត្រូវ គ្រប់​ចំនួន​ និង​ទាន់​ពេល​វេលា​ជា​បន្ត​បន្ទាប់​ដូច​បាន​ចែង​ក្នុង​ឧបសម្ព័ន្ធទី១ <span class="text-bold">“តារាងកាលវិភាគ​នៃ​ការ​ទូទាត់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ”</span>។ ភាគី <span class="text-bold">“អ្នកទិញ”</span> អាច​ទូទាត់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ​ដោយ​ផ្ទាល់​នៅ​ការិយាល័យ​របស់​ភាគី <span class="text-bold">“អ្នកលក់”</span> ។ 
{{-- ឬ​តាម​រយៈ​គណនី​ធនាគារ <span class="text-bold">{{ $loan->projects->Banks->bank_name }}</span> ដូច​មាន​ព័ត៌មាន​លម្អិត​ខាង​ក្រោមនេះ៖</p>

<table class="table table-p-1 table-bordered ">
	<tr>
		<th class="text-center">ឈ្មោះគណនីធនាគារ</th>
		<th class="text-center">លេខគណនីធនាគារ</th>
	</tr>	
	<tr>
		<td class="text-center">{{ $loan->projects->Banks->account_name }}</td>
		<td class="text-center">{{ $loan->projects->Banks->account_number }}</td>
	</tr>
</table> --}}

<p><span class="text-bold">២.២.</span> ភាគី <span class="text-bold">“អ្នកលក់”</span> អនុគ្រោះ​ជូនភាគី <span class="text-bold">“អ្នកទិញ”</span> ក្នុង​ការ​យឺតយ៉ាវ ឬ​ខក​ខាន​ទូទាត់​ប្រាក់ថ្លៃ​លក់ទិញ​អចលនវត្ថុ​ត្រឹមរយៈពេល <span class="text-bold">០៧ (ប្រាំពីរ) ថ្ងៃប្រតិទិន</span> ដោយ​មិន​បង់​ប្រាក់​ពិន័យ។ ផុត​ពី​រយៈ​ពេល​អនុគ្រោះ​នេះ ក្នុង​ករណី​ដែល​ភាគី <span class="text-bold">“អ្នកទិញ”</span> យឺតយ៉ាវ ឬ​ខកខាន​មិន​បាន​បំពេញ​កាតព្វកិច្ច​ទូទាត់​បង់ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ​ជូន​ភាគី <span class="text-bold">“អ្នកលក់”</span> ទៅ​តាម​ដំណាក់​កាល​នៃ​ការ​ទូទាត់​ឲ្យ​បាន​ត្រឹមត្រូវ គ្រប់ចំនួន និង​ទាន់​ពេល​វេលា​ទេ​នោះ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​បង់​ប្រាក់​ពិន័យ​លើ​ការ​យឺតយ៉ាវ ឬ​ខក​ខាន​នោះ​ក្នុង​មួយថ្ងៃ <span class="text-bold">០៥ (ប្រាំ) ដុល្លារ​អាមេរិក</span>។ ក្នុង​ករណី​ដែល​ការ​យឺតយ៉ាវ ឬ​ខក​ខាន​ក្នុង​ការ​ទូទាត់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ​នោះ​នៅ​តែ​បន្ត​លើសពី រយៈពេល <span class="text-bold">០២ (ពីរ) ខែ</span>​ជាប់ៗ​គ្នា ឬ​ក្នុង​ចន្លោះ​រយៈ​ពេល​ណា​មួយ​ដែល​ផល​បូក​នៃ​រយៈ​ពេល​ទាំង​នោះ​លើស​ពី <span class="text-bold">០២ (ពីរ) ខែ</span> នោះ​ត្រូវ​បាន​សន្មត់ទុក​ជា​មុន និង​ចាត់​ទុក​ថា ភាគី <span class="text-bold">“អ្នកទិញ”</span> រំលាយ​កិច្ចសន្យា​ដោយ​ឯកតោ​ភាគី ដោយ​បោះបង់​ចោល​នូវ​សិទ្ធិ​ទាំង​ឡាយ​ដែល​មាន​លើអចលនវត្ថុ ការ​លក់​ទិញ​អចលនវត្ថុ និង​ចំនួន​ទឹក​ប្រាក់​ដែល​ខ្លួន​បាន​ទូទាត់​មក​ឲ្យ​ភាគី <span class="text-bold">“អ្នកលក់”</span>។ ក្នុង​ករណី​នេះទឹក​ប្រាក់​ដែល​បាន​ទូទាត់​ហើយ​ទាំងអស់​ត្រូវ​ក្លាយ​ជា​ប្រយោជន៍​សម្រាប់​ភាគី <span class="text-bold">“អ្នកលក់”</span> ហើយ ភាគី <span class="text-bold">“អ្នកលក់”</span> មាន​សិទ្ធិ​ពេញ​លេញ​ដោយ​ស្រប​ច្បាប់​ក្នុង​ការ​លក់​អចលនវត្ថុ​ខាង​លើ​ឲ្យ​ទៅ​អតិថិជន ឬ​អ្នក​ទិញ​ផ្សេង​ទៀត​បាន ដោយ​ភាគី <span class="text-bold">“អ្នកទិញ”</span> សន្យា​ថា​នឹង​មិន​តវ៉ា ឬ​ជំទាស់​ឡើយ។ ក្នុង​ករណី​នេះ ប្រសិន​បើ ភាគី <span class="text-bold">“អ្នកទិញ”</span> បាន​ទទួល​យក​អចលនវត្ថុ​ពី ភាគី <span class="text-bold">“អ្នកលក់”</span> ហើយនោះ ភាគី <span class="text-bold">“អ្នកលក់”</span> ទុក​រយៈ​ពេល​ឲ្យ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ដែល​បោះបង់​សិទ្ធិ​លើ​ការ​លក់​ទិញ និង​អចលនវត្ថុ​នោះ <span class="text-bold">០១ (មួយ) ខែ</span> សម្រាប់​រើចេញ​ពី​អចលនវត្ថុ។</p>

<p class="khmer-title pb-0 mb-0">ប្រការ៣៖ ការលក់ ឬផ្ទេរសិទ្ធិលើការលក់ទិញអចលនវត្ថុបន្តទៅតតិយជន និងឥណទានពីធនាគារ</p>
<p><span class="text-bold">៣.១.</span> ក្នុង​កំឡុង​ពេល​នៃ​ការ​អនុវត្ត​កិច្ចសន្យា​លក់​ទិញ​នេះ ភាគី <span class="text-bold">“អ្នកទិញ”</span> អាច​លក់ ឬ​ផ្ទេរ​សិទ្ធិ​លើ​ការ​លក់​ទិញ​អចលនវត្ថុ​ទៅ​ តតិយជន​ណា​មួយ​បាន​ដោយ​ការ​ស្នើ​សុំ​ជា​លាយ​លក្ខណ៍​អក្សរ ហើយ​ទទួល​បាន​ការ​អនុញ្ញាត​យល់​ព្រម​ពី​ភាគី <span class="text-bold">“អ្នកលក់”</span> ដោយភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​បង់​ថ្លៃ​សេវា​រដ្ឋបាល​លើ​ការ​លក់ ឬ​ផ្ទេរ​សិទ្ធិ​លើ​ការ​លក់​ទិញ អចលនវត្ថុ​នេះ​ស្មើ​នឹង <span class="text-bold">{{ khmerNumber($loan->unittypes->contract_transfer_fee) }} ({{ khNumberWord($loan->unittypes->contract_transfer_fee) }}) ដុល្លារ​អាមេរិក</span> ជូន​ភាគី <span class="text-bold">“អ្នកលក់”</span>។ មុន​នឹង​អាច​លក់ ឬ​ផ្ទេរ​សិទ្ធិ​លើ​ការ​លក់​ទិញ​អចលនវត្ថុ​ទៅ​តតិយជន​ណា​មួយ​បាន ប្រសិន​បើ​ភាគី <span class="text-bold">“អ្នកទិញ”</span> យឺតយ៉ាវ ឬ​ខក​ខន​មិន​បាន​បំពេញ​កាតព្វកិច្ច​ទូទាត់​បង់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ​ជូន​ភាគី <span class="text-bold">“អ្នកលក់”</span> ទៅ​តាម​ដំណាក់​កាល​នៃ​ការ​ទូទាត់​ឲ្យ​បាន​ត្រឹមត្រូវ គ្រប់ចំនួន និងទាន់​ពេល​វេលា​ដូច​ចែង​ក្នុង​ <span class="text-bold">ប្រការ២</span> នោះ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​ទូទាត់​ប្រាក់​ដែល​នៅ​ជំពាក់ និង​ប្រាក់​ពិន័យ​លើ​ការ​យឺត​យ៉ាវ ឬ​ខក​ខាន​នោះ​ឲ្យ​រួច​រាល់​ជា​មុន​សិន។ តតិយជន​ដែល​ទទួល​ទិញ ឬ​ទទួល​ការ​ផ្ទេរ​សិទ្ធិ​លើ​ការ​លក់​ទិញ​អចលនវត្ថុ​នេះ ត្រូវ​គោរព​តាម​ទាំង​ស្រុង​នូវ​ខ្លឹម​សារ​នៃ​កិច្ចសន្យា​លក់​ទិញ​នេះ ហើយ​អនុវត្ត​តាម​បែប​បទ​រដ្ឋបាល​មួយ​ចំនួន​ដែល​ភាគី <span class="text-bold">“អ្នកលក់”</span> តម្រូវ។</p>
<p><span class="text-bold">៣.២.</span> ចំពោះ​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ដែល​ជ្រើស​រើស​ជម្រើស​បង់រម្លស់ <span class="english">(Loan)</span> ដែល​ភ្ជាប់​នឹង​អត្រា​ការ​ប្រាក់​ជាមួយ​ភាគី <span class="text-bold">“អ្នកលក់”</span> នៅ​ពេល​ដែល​សំណង់​អចលនវត្ថុ​សាងសង់​បាន​ចាប់ពី៧០% (ចិតសិបភាគរយ) ឡើង​ទៅ ភាគី <span class="text-bold">“អ្នកលក់”</span> សូម​រក្សា​សិទ្ធិ​ក្នុង​ការ​ចាត់​ចែង​បញ្ជូន​សិទ្ធិ​លើ​បំណុល​ដែល​ភាគី <span class="text-bold">“អ្នកទិញ”</span> មាន​ដោយ​ការ​បង់​រម្លស់​ជាមួយ ភាគី <span class="text-bold">“អ្នកលក់”</span> ឲ្យ​ទៅ​ធនាគារ​ណា​មួយ​ដែល​សហការ​ជាមួយ​ភាគី <span class="text-bold">“អ្នកលក់”</span> ហើយ​សេវា​ក្នុង​ការ​សិក្សា​ជា​មួយ​ធនាគារ​ទាំងនោះ គឺ​ជា​បន្ទុក​របស់​ភាគី <span class="text-bold">“អ្នកទិញ”</span>។</p>

<p class="khmer-title pb-0 mb-0">ប្រការ៤៖ ការសន្យាប្រគល់អចលនវត្ថុ</p>
<p>ភាគី <span class="text-bold">“អ្នកលក់”</span> សន្យា​នឹង​ប្រគល់​អចលនវត្ថុ​ជូន​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ក្នុង​រយៈ​ពេល <span class="text-bold">{{ khmerNumber(number_format($loan->unittypes->deadline,0)) }} ({{ khNumberWord($loan->unittypes->deadline) }}) ខែ​</span> ដោយ​រាប់​ចាប់​ពី​កាលបរិច្ឆេទ​ចុះ​កិច្ចសន្យា​លក់ទិញ​នេះ។ ក្នុង​ករណី​ដែល​មាន​ការ​យឺត​យ៉ាវ ឬ​ខកខាន​ក្នុង​​ការ​ប្រគល់​អចលនវត្ថុ​ជូន ភាគី <span class="text-bold">“អ្នកទិញ”</span> ភាគី <span class="text-bold">“អ្នកលក់”</span> សូម​រក្សា​សិទ្ធិ​ពន្យារ​ពេល​ថែម <span class="text-bold">{{ khmerNumber(number_format($loan->unittypes->extended_deadline,0)) }} ({{ khNumberWord($loan->unittypes->extended_deadline) }}) ខែ​</span> បន្ថែម​ទៀត ជា​រយៈ​ពេល​អនុគ្រោះ ក្នុង​ការ​សន្យា​ប្រគល់​អចលនវត្ថុ​ជូន ភាគី <span class="text-bold">“អ្នកទិញ”</span>។ បើ​ផុត​រយៈ​ពេល​អនុគ្រោះ​ខាង​លើ​ហើយ ភាគី <span class="text-bold">“អ្នកលក់”</span> នៅ​តែ​មិន​ទាន់​មាន​អចលនវត្ថុ​គ្រប់លក្ខណៈ​បច្ចេកទេស​សំណង់​ជា​អចលនវត្ថុ​រួចរាល់ ឬ​សម្រេច (មានទ្វារ បង្អួច កញ្ចក់ លាបថ្នាំពណ៌ និងគ្រឿង​បង្គុំ​ផ្សេងៗ​រួចរាល់) ប្រគល់​ជូន​ភាគី “អ្នកទិញ” ទេ  ភាគី <span class="text-bold">“អ្នកលក់”</span> សូម​ទទួល​ខុស​ត្រូវ​ក្នុង​ការ​បង់​សំណង​ពិន័យ​ជូន ភាគី “អ្នកទិញ” ស្មើនឹងអត្រា ១% (មួយភាគរយ) ក្នុង​មួយ​ខែ នៃ​ប្រាក់​ដែល​ភាគី <span class="text-bold">“អ្នកទិញ”</span> បាន​ទូ​ទាត់​បង់​ជូន​ភាគី <span class="text-bold">“អ្នកលក់”</span> រាប់​ចាប់​ពី​កាលបរិច្ឆេទ​ដែល​ផុត​កំណត់​ត្រូវ​ប្រគល់​អចលនវត្ថុ​ជូនភាគី <span class="text-bold">“អ្នកទិញ”</span>។ គូ​ភាគី​បាន​សន្មត់ និង​ព្រម​ព្រៀង​គ្នា​ជា​មុន​ថា​នៅ​កាលបរិច្ឆេទ​ដែល​ភាគី <span class="text-bold">“អ្នកលក់”</span> បាន​ជូន​ដំណឹង​ស្តី​ពី​ការ​ត្រួត​ពិនិត្យ និងទទួល​អចលនវត្ថុ​ដែល​គ្រប់​លក្ខណៈបច្ចេកទេស​សំណង់​ជា​អចលនវត្ថុ​រួចរាល់ ឬសម្រេច ទោះ​បី​ជា​តាម​រយៈ​ទូរស័ព្ទ​ក្តី សារ​អេឡិចត្រូនិច​ក្តី លិខិត​ជូន​ផ្ទាល់​ដៃ​ក្តី ការ​ជូន​ដំណឹង​ទៅ​លំនៅឋានក្តី ឬលិខិត​ជូន​តាម​ប្រៃសណីយ៍ក្តី ពោល​គឺ​តាម​វិធីសាស្ត្រ​ណា​មួយ​ដែល​សមស្រប​ដែលភាគី <span class="text-bold">“អ្នកទិញ”</span> អាច​ជ្រាប​ជា​ដំណឹង​បាន គឺ​មាន​ន័យ​ថា ភាគី <span class="text-bold">“អ្នកលក់”</span> មាន​អចលនវត្ថុ​ប្រគល់​ជូន ភាគី <span class="text-bold">“អ្នកទិញ”</span> នៅ​កាលបរិច្ឆេទ​នោះ​តែម្តង (ក្នុង​ករណី​ដែល​មាន​ការ​ស្នើ​សុំ​កែ​លម្អ ឬ​ជួស​ជុល​ការងារ​បច្ចេកទេស​បន្តិច​បន្ទួច​ផ្នែក​ខាង​ក្នុង ឬ​ខាង​ក្រៅ​អចលនវត្ថុ ដូច​ជាកា​រ៉ូ ថ្នាំ​លាប ប្រឡាក់​កញ្ចក់ ប្រេះ​ស៊ីម៉ងត៍បៀក ។ល។ មិន​ត្រូវ​បាន​ចាត់​ទុក​ថា ភាគី <span class="text-bold">“អ្នកលក់”</span> មិន​ទាន់​មាន​អចលនវត្ថុ​សម្រាប់​ប្រគល់​ជូន ភាគី <span class="text-bold">“អ្នកទិញ”</span> ឡើយ)។ ដោយ​ឡែក ប្រសិន​បើ​ភាគី <span class="text-bold">“អ្នកលក់”</span> បាន​បញ្ចប់​ការ​សាង​សង់​អចលនវត្ថុ​មុន​កាល​កំណត់ ភាគី <span class="text-bold">“អ្នកលក់”</span> នឹង​ធ្វើ​ការ​ជូន​ដំណឹង​ទៅ​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ស្តី​ពី​ការ​ត្រួត​ពិនិត្យ និង​ទទួល​អចលនវត្ថុ​មុន​កាល​កំណត់ ហើយ​ភាគី <span class="text-bold">“អ្នកទិញ”</span> មិន​ត្រូវ​យឺត​យ៉ាវ​ក្នុង​ការ​មក​ពិនិត្យ និងទទួល​ឡើយ បើ​មិន​ដូច​នោះ​ទេ ភាគី <span class="text-bold">“អ្នកលក់”</span> នឹង​មិន​ទទួល​ខុសត្រូវ ចំពោះ​ការ​យឺត​យ៉ាវ ឬខក​ខាន​ណា​មួយ​ជាយថាហេតុ​នៅ​ពេល​បន្ទាប់​ពីនោះ​ឡើយ។ ទាក់​ទង​នឹង​ការ​ប្រគល់​ទទួល​អចលនវត្ថុនេះ ភាគី <span class="text-bold">“អ្នកទិញ”</span> អាច​ទទួល​អចលនវត្ថុ​បាន​លុះ​ត្រាតែ <span class="text-bold">(១).</span> ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​ទូទាត់​ប្រាក់​ដែល​នៅ​ជំពាក់ និង​ប្រាក់​ពិន័យ​លើ​ការ​យឺត​យ៉ាវ ឬ​ខក​ខាន​នោះ​ឲ្យ​រួច​រាល់​ជា​មុន​សិន និង <span class="text-bold">(២).</span> ត្រូវ​បង់​ប្រាក់​ថ្លៃ​សេវា​គ្រប់​គ្រង​ថែ​ទាំ​បុរី​ដូច​មាន​ចែង​ក្នុង​ <span class="text-bold">ប្រការ៦</span> ជូន​ភាគី <span class="text-bold">“អ្នកលក់”</span> សម្រាប់​រយៈ​ពេល​មួយ​ឆ្នាំ​ពេញ។ ចំពោះ​អចលនវត្ថុ​ដែល​ជា​កម្មវត្ថុ​នៃ​ការ​លក់​ទិញ​ណា​មួយ ដែល​ភាគី <span class="text-bold">“អ្នកទិញ”</span> មិន​ទាន់​បាន​ទូទាត់​ថ្លៃលក់ទិញ​រួច​រាល់​ទាំង​ស្រុង​ជូន ភាគី <span class="text-bold">“អ្នកលក់”</span> ទេ ភាគី <span class="text-bold">“អ្នកទិញ”</span> នោះ​មិន​ទាន់​ក្លាយ​ជា​ម្ចាស់​អចលនវត្ថុ​ពេញ​លេញ និង​ស្រប​ច្បាប់​ឡើយ ទោះ​បី​ជា​ភាគី <span class="text-bold">“អ្នកលក់”</span> បាន​ប្រគល់​អចលនវត្ថុ​នោះជូន ភាគី <span class="text-bold">“អ្នកទិញ”</span> ក៏ដោយ។</p>

<p class="khmer-title pb-0 mb-0">ប្រការ៥៖ ការធានាគុណភាពសំណង់ ការកែប្រែសោភ័ណភាព និងប្លង់គោលលើអចលនវត្ថុនៃការលក់ទិញ</p>
<p><span class="text-bold">៥.១.</span> ដោយ​រាប់​ចាប់​ពី​កាលបរិច្ឆេទ​ដែល​ភាគី <span class="text-bold">“អ្នកលក់”</span> ប្រគល់​អចលនវត្ថុ​នៃ​ការ​លក់ទិញ​ជូន​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ភាគី <span class="text-bold">“អ្នកលក់”</span> សូម​ធានា​វិការៈ (លើ​គុណភាព​គ្រោង​សំណង់ និង​ការ​បាក់​ស្រុត​គ្រឹះ​សំណង់) សម្រាប់​រយៈពេល <span class="text-bold">០៣ (បី) ឆ្នាំ</span> និង​ធានា​វិការៈ​បច្ចេកទេស​សំណង់​ទូទៅ​ដូច​ជា​ការ​ប្រះ​ស្រាំ​ស៊ីម៉ងត៍បៀក ឬជញ្ជាំង ការជ្រាបទឹក សម្រាប់​រយៈពេល <span class="text-bold">០១ (មួយ) ឆ្នាំ</span> (លើក​លែង​តែ​គ្រឿង​បំពាក់​ឈើ បើ​សិន​ជា​មាន​ផ្តល់​ជូន គឺ​មិន​មាន​ការ​ធានា​ជួស​ជុល ឬ​ប្តូរ​ថ្មី​ជូន​ឡើយ​)។ បរិក្ខា​អគ្គិសនី​ដែល​បំពាក់​ក្នុង​អចលនវត្ថុ ត្រូវ​បាន​ធានា​ដោយ​យោង​ទៅ​តាម​លក្ខខណ្ឌ​ដែល​បាន​កំណត់ ដោយ​រោង​ចក្រ​ផលិត ឬ​អ្នក​លក់​បរិក្ខា​នោះ។ ផ្ទុយ​ទៅ​វិញ ប្រសិន​បើ​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ធ្លាប់​បាន​ធ្វើ​ការ​ជួសជុល ឬ​ផ្លាស់​ប្តូរ​ទ្រង់​ទ្រាយ​សំណង់​លើ​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញ​ដោយ​ខ្លួនឯង ឬ​មិន​សម្អាត​លើ​វ៉េរ៉ងដា ឬ​ឥដ្ឋ​កន្សែង​ជាន់​ដំបូល ឬ​រុះ​រើ​ដំបូល​សំណង់ ដែល​បណ្តាល​ឲ្យ​មាន​ការ​ជ្រាប​ទឹកនោះ ភាគី <span class="text-bold">“អ្នកលក់”</span> មិន​ធានា​វិការៈជូនឡើយ។</p>
<p><span class="text-bold">៥.២.</span> ភាគី <span class="text-bold">“អ្នកទិញ”</span> មិន​មាន​សិទ្ធិ​កែ​ប្រែ​សោភ័ណភាព​សំណង់ និង/ឬប្លង់គោល​លើ​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញឡើយ លុះត្រា​តែ​មាន​ការ​អនុញ្ញាត​ពី ភាគី <span class="text-bold">“អ្នកលក់”</span>។ ការ​កែប្រែ​សោភ័ណភាព​សំណង់ និង/ឬប្លង់គោល​លើ​អចលនវត្ថុ​នៃ​ការ​លក់ទិញ​ដោយ​គ្មាន​ការ​អនុញ្ញាត​ពី​ភាគី <span class="text-bold">“អ្នកលក់”</span> ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​ទទួល​ខុស​ត្រូវ​ចំពោះ​មុខ​ច្បាប់​ជាធរមាន ហើយ​ការ​ធានា​លើវិការៈ​សំណង់​ដូច​មាន​ចែង​ក្នុង​ប្រការ <span class="text-bold">៥.១</span> ខាងលើ លែង​មាន​អនុភាព​សម្រាប់ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ទៀតដែរ។</p>

<p class="khmer-title pb-0 mb-0">ប្រការ៦៖ ការគ្រប់គ្រងថែទាំ ថ្លៃសេវាថែទាំគ្រប់គ្រង បទបញ្ជាផ្ទៃ​ក្នុង​នៃ​អគារសហកម្មសិទ្ធិ និង​ការ​ជួល​អចលនវត្ថុ​ទៅ​ឲ្យ​តតិយជន</p>
<p><span class="text-bold">៦.១.</span> បន្ទាប់​ពី​ប្រគល់​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញ​ជូន​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ភាគី <span class="text-bold">“អ្នកលក់”</span> មាន​កាតព្វកិច្ច​ក្នុង​ការ​ផ្តល់​ជូន​នូវ​សេវា​គ្រប់គ្រង​ថែទាំ​ក្នុង​បរិវេណ​នៃ​អគារសហកម្មសិទ្ធិ​ទាំងមូល ដែល​មាន​ដូច​ជា​សេវា​សន្តិសុខ​សណ្តាប់ធ្នាប់ ២៤​ម៉ោងលើ​២៤ម៉ោង ជណ្តើរយន្ត  អនាម័យសាធារណៈ ភ្លើងបំភ្លឺផ្លូវ អាងហែលទឹក ការថែទាំសួនច្បារ និង​ទ្រព្យ​សម្បត្តិ​សាធារណៈ​ទាំង​ឡាយ​ផ្សេង​ទៀត។ ក្នុង​ករណី​នេះ ភាគី <span class="text-bold">“អ្នកទិញ”</span> មាន​កាតព្វកិច្ច​ទូទាត់​បង់​ថ្លៃ​សេវា​គ្រប់គ្រង​ថែទាំ​បុរី​ដូច​ចែង​ក្នុង​ <span class="text-bold">ប្រការ១​៖​ អត្តសញ្ញាណ​នៃ​អចលនវត្ថុ​លក់ទិញ និង​តម្លៃ​លក់ទិញ</span> សម្រាប់​រយៈ​ពេល​មួយ​ឆ្នាំ​ម្តង ហើយ​ត្រូវ​បង់​មួយ​ឆ្នាំ​ទុក​ជា​មុន​ជូន​ទៅ ភាគី <span class="text-bold">“អ្នកលក់”</span>  មុន​ពេល​ទទួល​អចលនវត្ថុ​ពី ភាគី <span class="text-bold">“អ្នកលក់”</span> ហើយ​សេវា​នេះ​គិត​ចាប់​ពី​កាលបរិច្ឆេទ​ដែល ភាគី <span class="text-bold">“អ្នកទិញ”</span> ទទួល​យក​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញពី ភាគី <span class="text-bold">“អ្នកលក់”</span>។ ថ្លៃ​សេវា​ថែទាំ​គ្រប់គ្រង់​នេះ អាច​ត្រូវ​បាន​កែប្រែ​នា​ពេល​ណា​មួយ​ខាង​មុខ​ដោយ​ស្រប​ទៅ​តាម​តម្លៃ​ទីផ្សារ​ជាក់ស្តែង ហើយ​ភាគី <span class="text-bold">“អ្នកលក់”</span> នឹង​ជូន​ដំណឹង​ជា​សាធារណៈ​ទៅ​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ទាំងអស់​ក្នុងរយៈពេល <span class="text-bold">១ (មួយ) ខែ​</span>មុន​ការ​កែប្រែ​តម្លៃ​នេះ​ចូល​ជាធរមាន។</p>
<p><span class="text-bold">៦.២.</span> បន្ទាប់​ពី​ទទួល​អចលនវត្ថុ​ពីភាគី <span class="text-bold">“អ្នកលក់”</span> ហើយ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​គោរព​យ៉ាង​ម៉ឺងម៉ាត់​នូវ​ខ្លឹមសារ​ទាំងឡាយ​នៃ​បទបញ្ជាផ្ទៃក្នុង​នៃ​អគារសហកម្មសិទ្ធិ​ដែល​រៀបចំ​ឡើង​ដោយភាគី <span class="text-bold">“អ្នកលក់”</span> ដោយ​ស្រប​ទៅ​តាម​ច្បាប់  បទបញ្ញត្តិនានា និង​ផល​ប្រយោជន៍​រួម​នៃ​ម្ចាស់​ចំណែក​ឯកជន​គ្រប់​រូប​នៅ​ក្នុង​អគារ​សហកម្មសិទ្ធិ​ដូចជា គោលការណ៍ សណ្តាប់ធ្នាប់ (មិន​ត្រូវ​មាន​សំលេង​ឡូឡា​ដែល​នាំ​ឲ្យ​រំខាន​ដល់​អ្នក​ជិតខាង) សុវត្ថិភាព សុខភាពអនាម័យ (មិន​ត្រូវ​ចោល​សំរាម មិន​ត្រូវ​ចិញ្ចឹម​សត្វ រួម​ទាំង​ឆ្កែ​ឆ្មា បក្សី ឬសត្វ​ផ្សេងៗទៀត) មិន​ត្រូវ​បង្ក​សកម្មភាព​ទាំងឡាយ​ណា​ដែល​នាំ​ដល់​ការ​បំពុល​បរិស្ថាន មិន​ត្រូវ​នាំ​អាវុធ​ជាតិផ្ទុះ ឬសារធាតុ​រំសេវ ឬ​គ្រឿង​ញៀន​ចូល​ក្នុង​បរិវេណ​អគារ​សហកម្មសិទ្ធិ មិន​អនុញ្ញាត​ឲ្យ​បើក​ទីតាំង​ល្បែង​ស៊ីសង​ខុសច្បាប់ ឬហាង​ទំនិញ ឬក្រុមហ៊ុន។ ទីតាំង និង​បរិវេណ​នៃ​អចលនវត្ថុ និង​អគារ​សហកម្មសិទ្ធិ គឺ​ត្រូវ​បាន​ប្រើប្រាស់​សម្រាប់​តែ​កម្មវត្ថុ​នៃ​ការ​ស្នាក់​នៅប៉ុណ្ណោះ។</p>
<p><span class="text-bold">៦.៣.</span> បន្ទាប់​ពី​ទទួល​អចលនវត្ថុ​ពីភាគី <span class="text-bold">“អ្នកលក់”</span> ហើយ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​ទិញ​ធានា​រ៉ាប់រង​អគ្គីភ័យ​ដោយ​ខ្លួនឯង ហើយ​ថ្លៃ​ជួសជុល​ផ្សេងៗ​ត្រូវ​ស្ថិត​ក្រោម​ការ​ចំណាយ​ផ្ទាល់​របស់ ភាគី <span class="text-bold">“អ្នកទិញ”</span> បន្ទាប់​ពី​រយៈពេល​ធានា​លើ​វិការៈ​ក្នុង​ការ​ជួសជុល​របស់ ភាគី <span class="text-bold">“អ្នកលក់”</span> ដូច​ចែង​ក្នុង​ <span class="text-bold">ប្រការ៥.១</span> ត្រូវ​បាន​បញ្ចប់។</p>
<p><span class="text-bold">៦.៤.</span> បន្ទាប់​ពី​ទទួល​អចលនវត្ថុ​ពី​ភាគី <span class="text-bold">“អ្នកលក់”</span> ហើយ​ក្នុង​ករណី​ដែល​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ជួល​អចលនវត្ថុ​ឲ្យទៅ តតិយជន​ណា​ម្នាក់ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​ជូន​ដំណឹង​ជា​លាយ​លក្ខណ៍​អក្សរ​មកភាគី <span class="text-bold">“អ្នកលក់”</span> អំពី​ការ​ជួល​នោះ និង​អត្តសញ្ញាណ​នៃ​អ្នក​ជួល​អចលនវត្ថុ​ដើម្បី​ឲ្យ​ភាគី <span class="text-bold">“អ្នកលក់”</span> មាន​ភាព​ងាយ​ស្រួល​ក្នុង​ប្រតិបត្តិការថែទាំ និង​គ្រប់គ្រង​អគារ​សហកម្មសិទ្ធិ​នេះ​ឲ្យ​មាន​ប្រសិទ្ធិភាព​ខ្ពស់ ហើយ​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​តែ​ធានា​ថា​ខ្លួន​បាន​ពន្យល់​តតិយជន​ដែល​ជួល​នោះ​អំពី​បទបញ្ជាផ្ទៃក្នុង​នៃ​អគារសហកម្មសិទ្ធិ ហើយ​តតិយជន​នោះ ត្រូវ​គោរព​ឲ្យ​បាន​ត្រឹមត្រូវ​នូវ​រាល់​ខ្លឹមសារ​ទាំង​ឡាយ​ក្នុង​បទបញ្ជាផ្ទៃក្នុង​នៃ​អគារសហមកម្មសិទ្ធិ​នេះ។</p>

<p class="khmer-title pb-0 mb-0">ប្រការ៧៖ ប័ណ្ណបញ្ជាក់សិទ្ធិលើកម្មសិទ្ធិនៃអចលនវត្ថុនៃការលក់ទិញ</p>
<p>ភាគី <span class="text-bold">“អ្នកលក់”</span> សន្យា​ថា​នឹង​ប្រគល់​ប័ណ្ណ​បញ្ជាក់​សិទ្ធិ​លើ​កម្មសិទ្ធិ​នៃ​អចលនវត្ថុ​នៃ​ការ​លក់ទិញ ជា​ប្រភេទ​ដូច​ចែង​ក្នុង​ប្រការ ១៖<span class="text-bold">អត្តសញ្ញាណ​នៃ​អចលនវត្ថុ​លក់ទិញ និង​តម្លៃ​លក់​ទិញ</span> ជូនភាគី <span class="text-bold">“អ្នកទិញ”</span> បន្ទាប់​ពី​ភាគី <span class="text-bold">“អ្នកទិញ”៖</span> <span class="text-bold">(១).</span> បាន​ទទួល​យក​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញ​ពី​ភាគី <span class="text-bold">“អ្នកលក់”</span> រួច​រាល់​ហើយ <span class="text-bold">(២).</span> បាន​ទូ​ទាត់​បង់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ​រួច​រាល់​គ្រប់​ចំនួន​ជូនមក ភាគី <span class="text-bold">“អ្នកលក់”</span> ដូច​បាន​ចែង​ក្នុង​ឧបសម្ព័ន្ធទី១ <span class="text-bold">“តារាង​កាល​វិភាគ​នៃ​ការ​ទូទាត់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ” (៣).</span> មក​បំពេញ​បែប​បទ និង​ឯកសារ​គ្រប់គ្រាន់​ជូន​ភាគី <span class="text-bold">“អ្នកលក់”</span> ដើម្បី​រៀប​ចំ​ដាក់​ស្នើ ឬ​រៀប​ចំ​បែបបទ​រដ្ឋបាល​ជា​មួយ​មន្ត្រី​ជំនាញ​សុរិយោ​ដី​ដើម្បី​ស្នើ​ទៅ​តាម​នីតិ​វិធី​ច្បាប់ឲ្យ​បាន​ទាន់​ពេល​វេលា និង​រលូន​ជូន​ភាគី <span class="text-bold">“អ្នកទិញ”</span> (ប្រសិន​បើ​ត្រូវ​ការ)។ ទោះ​ជា​យ៉ាង​ណា​ក៏​ដោយ​ ប្រសិន​បើ​មាន​ការ​យឺត​យ៉ាវ​ក្នុង​ការ​សម្រេច និង​អនុញ្ញាត​ចេញ​ប័ណ្ណ​បញ្ជាក់​សិទ្ធិ​លើ​កម្មសិទ្ធិ​លើ​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញ ដោយ​សារ​តែ​បញ្ហា​បច្ចេកទេស ឬ​រដ្ឋបាល​របស់​មន្ត្រី​សុរិយោដី ឬ​មន្ត្រី​មាន​សមត្ថកិច្ច​នោះ ភាគី <span class="text-bold">“អ្នកទិញ”</span> មិន​អាច​តវ៉ា ឬ​ទំលាក់​កំហុស​លើ​ភាគី <span class="text-bold">“អ្នកលក់”</span> ដែល​បាន​ប្រឹងប្រែង​អស់​ពី​សមត្ថភាព​ហើយ​នោះទេ។ ការ​ចំណាយ​លើ​ការ​រៀប​ចំ​ឯកសារ​ផ្ទេរ​សិទ្ធិ​លើ​កម្មសិទ្ធិ ដូច​ចែងក្នុង <span class="text-bold">ប្រការ ១</span> បូក​រួម​នឹង​ការ​បង់​ពន្ធ​ប្រថាប់​ត្រា <span class="text-bold">៤% (បូនភាគរយ)</span> គឺ​ជាការ​ទទួល​ខុស​ត្រូវ​ដោយ​ផ្ទាល់​របស់ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ដោយ​ភាគី <span class="text-bold">“អ្នកលក់”</span> ជួយ​សម្រួល និង​រៀបចំ​ឯក​សារ​ពាក់​ព័ន្ធ​ជូន​ទៅ​តាម​ស្ថាន​ភាព​ជាក់ស្តែង។</p>

<p class="khmer-title pb-0 mb-0">ប្រការ៨៖ ការទទួលខុសត្រូវបង់ពន្ធអចលនវត្ថុប្រចាំឆ្នាំរបស់គូភាគី</p>
<p><span class="text-bold">៨.១</span> ភាគី <span class="text-bold">“អ្នកលក់”</span> ទទួល​ខុស​ត្រូវ​ទាំង​ស្រុង​ចំពោះ​ការ​បង់​ពន្ធ​អចលនវត្ថុ​ប្រចាំ​ឆ្នាំ ឬ​បង់​ពន្ធ​ដី​មិន​បាន​បើ​ប្រាស់​ឬ​ពន្ធ​នានា​ពាក់​ព័ន្ធ​នឹង​អាជីវកម្ម​សាង​សង់​លំនៅឋាន​របស់​ខ្លួន​មុន​កាលបរិច្ឆេទ​នៃ​ការ​ប្រគល់​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញ​ជូន​ទៅ​ភាគី <span class="text-bold">“អ្នកទិញ”</span>។</p>
<p><span class="text-bold">៨.២</span> ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​ទទួល​ខុស​ត្រូវ​លើ​ការ​បង់​ពន្ធ​អចលវត្ថុ​ប្រចាំ​ឆ្នាំ និង​ពន្ធ​ផ្សេង​ទៀត​ពាក់​ព័ន្ធ​នឹង​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញ​ខាង​លើ​បន្ទាប់​ពី​កាល​បរិច្ឆេទ​ទទួល​យក​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញ ពីភាគី <span class="text-bold">“អ្នកលក់”</span>។</p> 

<p class="khmer-title pb-0 mb-0">ប្រការ៩៖ ទាយាទ អ្នកស្នងមរតក និងអ្នកទទួលសិទ្ធិស្របច្បាប់</p>
<p>ក្នុង​ករណី​ដែល​មាន​ភាគី​ណា​មួយ មិន​អាច​អនុវត្ត​សិទ្ធិ និង​កាតព្វកិច្ច​របស់​ខ្លួន​បាន​ដោយ​មូលហេតុ​ណា​មួយ​បណ្តាល​មក​ពី មរណភាព ពិការភាព ឬ​អវត្តមាន​ជា​បណ្តោះ​អាសន្ន ឬ​អចិន្ត្រៃយ៍​នៅ​ក្នុង​ប្រទេស​នោះ រាល់​សិទ្ធិ និង​កាតព្វកិច្ច​ទាំង​អស់​របស់​ភាគី​នោះ​ដែល​ស្ថិត​ក្រោម​ខ្លឹមសារ​នៃ​កិច្ចសន្យា​នេះ ត្រូវ​បន្ត​ទៅ​ទាយាទ និង/ឬ អ្នក​ស្នង​មរតក និង/ឬអ្នកតំណាង​ស្រប​ច្បាប់​ណា​មួយ​របស់​ខ្លួន​ដោយ​ស្វ័យប្រវត្តិ។</p>

{{-- <div style="page-break-before: always;"></div> --}}

<p class="khmer-title pb-0 mb-0">ប្រការ១០៖ គោលការណ៍ស្ម័គ្រចិត្ត និងការព្រមព្រៀងនៃកិច្ចសន្យា</p>
<p>កិច្ចសន្យា​លក់​ទិញ​អចលនវត្ថុ​នេះ ត្រូវ​ធ្វើ​ឡើង​ដោយ​គ្មាន​ការ​គំរាម​កំហែង បង្ខិតបង្ខំ ឬ​មាន​បញ្ហា​ខុស​ច្បាប់​ណា​មួយ​ឡើយ ហើយ <span class="text-bold">គូភាគី</span> មាន​សមត្ថភាព​គ្រប់គ្រាន់​ក្នុង​ការ​ដឹង​លឺ ឬ​យល់​ដឹង បាន​អាន បានស្តាប់ និង​យល់​ព្រម​តាម​លក្ខខណ្ឌ​ទាំងឡាយ​នៃ​កិច្ចសន្យា​នេះ។ កិច្ចសន្យា​លក់​ទិញ​អចលនវត្ថុ​នេះ មាន​សុពលភាព​នា​កាលបរិច្ឆេទ​ដែល <span class="text-bold">គូភាគី</span> បាន​ផ្តិត​ស្នាម​មេដៃ និង​ចុះ​ត្រា​ក្រុមហ៊ុន​ដូច​ខាង​ក្រោម។ កិច្ចសន្យា​លក់ទិញ​អចលនវត្ថុ​នេះ​ធ្វើ​ឡើង​ជាភាសាខ្មែរ​ចំនួន <span class="text-bold">០២ (ពីរ)</span> ឯកសារ​ច្បាប់​ដើម ក្នុងនោះ ភាគី <span class="text-bold">“អ្នកលក់”</span> រក្សាទុក <span class="text-bold">០១ (មួយ)</span> ឯកសារ​ច្បាប់ដើម ហើយភាគី <span class="text-bold">“អ្នកទិញ”</span> រក្សាទុក <span class="text-bold">០១ (មួយ)</span> ឯកសារ​ច្បាប់​ដើម។ ឯកសារ​នីមួយៗ​មាន​សុពលភាព និង​អានុភាព​គតិយុត្តិ​ស្មើៗ និងដូចគ្នា។</p>
@endsection

@section('third')
<h4 class="khmer-title text-center">ឧបសម្ព័ន្ធទី៣៖</h4>
<h4 class="khmer-title text-center mb-4">“សម្ភារៈដែលភ្ជាប់បញ្ជូលក្នុងអចលនវត្ថុ”</h4>
{!! $loan->unittypes->equipment_text !!}
<div style="page-break-after: always;"></div>
@endsection

@section('forth')
<h4 class="khmer-title text-center">ឧបសម្ព័ន្ធទី៤៖</h4>  
<h4 class="khmer-title text-center mb-4">“អត្តសញ្ញាណប័ណ្ណ ឬលិខិតឆ្លងដែនរបស់ភាគី អ្នកលក់ និងភាគី អ្នកទិញ”</h4>  
<?php
    $attachment_array = $loan->client->ClientCbcGeneral->toArray();
?>

@if(array_key_exists('photo',$attachment_array) || array_key_exists('signature',$attachment_array))  
<p class="text-bold">- អត្តសញ្ញាណប័ណ្ណ អ្នកទិញ</p>
@endif
<div class="row">
    <?php
        $photo = '';
        $signature = '';
        $no_image = asset('images/noimage.gif',false);
        if(array_key_exists('photo',$attachment_array)){
            if(file_exists('data/clients/'.$attachment_array['photo']) && $attachment_array['photo']){
                $photo = asset('data/clients/'.$attachment_array['photo']);
            }else{
                $photo = $no_image;
            }
        }else{
            $photo = $no_image;
        }
        if(array_key_exists('signature',$attachment_array)){
            if(file_exists('data/signatures/'.$attachment_array['signature']) && $attachment_array['signature']){
                $signature = asset('data/signatures/'.$attachment_array['signature']);
            }else{
                $signature = $no_image;
            }
        }else{
            $signature = $no_image;
        }
    ?>
    <div class="col-lg-6" style="height: 253px !important;">
        <img src="{{ $photo }}" width="100%" class="img-fluid d-block" style="height: 253px !important;object-fit: contain;">
    </div>
    <div class="col-lg-6" style="height: 253px !important;">
        <img src="{{ $signature }}" width="100%" class="img-fluid d-block" style="height: 253px !important;object-fit: contain;">
    </div>
</div><br>
<div class="row my-4">
    <?php
        //$customer2_id_front ='';
        //$customer2_id_back = '';
        //if(array_key_exists('customer2_id_front',$attachment_array)){
        //    //$customer2_id_front = asset($attachment_array['customer2_id_front']['path']);
        //}else{
            //$customer2_id_front = asset('images/noimage.gif',false);
        //}
        //if(array_key_exists('customer2_id_back',$attachment_array)){
            //$customer2_id_back = asset($attachment_array['customer2_id_back']['path']);
        //}else{
            //$customer2_id_back = asset('images/noimage.gif',false);
        //}
    ?>
    {{-- <div class="col-md-6">
        <img src="{{ $customer2_id_front }}" width="100%" class="img-fluid d-block">
    </div>
    <div class="col-md-6">
        <img src="{{ $customer2_id_back }}" width="100%" class="img-fluid d-block">
    </div> --}}
</div>
@if(array_key_exists('customer1_passort',$attachment_array) || array_key_exists('customer2_passort',$attachment_array))
<p class="text-bold">- លិខិតឆ្លងដែន អ្នកទិញ</p>
@endif
<div class="container">
    <div class="row my-4">      
    @if(array_key_exists('customer1_passort',$attachment_array))
        <div class="col-6" style="height: 253px !important;">       
            <img src="{{ $attachment_array['customer1_passort']['path'] }}" width="100%" class="img-fluid" style="height: 253px !important;object-fit: contain;">        
        </div>   
    @endif     
    @if(array_key_exists('customer2_passort',$attachment_array))
        <div class="col-6" style="height: 253px !important;">
            <img src="{{ $attachment_array['customer2_passort']['path'] }}" width="100%" class="img-fluid" style="height: 253px !important;object-fit: contain;">        
        </div>
    @endif
    </div>
</div> 

@if(count($co_borrower) > 0)
    <p class="text-bold">- អត្តសញ្ញាណប័ណ្ណ អ្នកចូលរួមទិញ</p>
    @foreach($co_borrower as $val)
        <?php
            $co_general = $val->Clients->general->first();
        ?>
        <div class="row my-4">
            <?php
                $cobo_photo = '';
                $cobo_signature = '';
                $no_image = asset('images/noimage.gif',false);
                if(file_exists('data/clients/'.$co_general->photo) && $co_general->photo){
                    $cobo_photo = asset('data/clients/'.$co_general->photo);
                }else{
                    $cobo_photo = $no_image;
                }
                if(file_exists('data/signatures/'.$co_general->signature) && $co_general->signature){
                    $cobo_signature = asset('data/signatures/'.$co_general->signature);
                }else{
                    $cobo_signature = $no_image;
                }
            ?>
            <div class="col-lg-6" style="height: 253px !important;">
                <img src="{{ $cobo_photo }}" width="100%" class="img-fluid d-block" style="height: 253px !important;object-fit: contain;">
            </div>
            <div class="col-lg-6" style="height: 253px !important;">
                <img src="{{ $cobo_signature }}" width="100%" class="img-fluid d-block" style="height: 253px !important;object-fit: contain;">  
            </div>
        </div>
    @endforeach
@endif

<p class="text-bold">- អត្តសញ្ញាណប័ណ្ណ អ្នកលក់</p> 
<?php
    //$saler = $loan->projects->Representative;
    $national_front = '';
    $national_back = '';
    if(file_exists('data/representative/national_front/'.$loan->national_front) && $loan->national_front){
        $national_front = asset('data/representative/national_front/'.$loan->national_front);
    }else{
        $national_front = asset('images/noimage.gif',false);
    }
    if(file_exists('data/representative/national_back/'.$loan->national_back) && $loan->national_back){
        $national_back = asset('data/representative/national_back/'.$loan->national_back);
    }else{
        $national_back = asset('images/noimage.gif',false);
    }
?>
@if($loan)
<div class="row">
    <div class="col-lg-6" style="height: 253px !important;">      
        <img src="{{ $national_front }}" width="100%" class="img-fluid d-block" style="height: 253px !important;object-fit: contain;">       
    </div>
    <div class="col-lg-6" style="height: 253px !important;">        
        <img src="{{ $national_back }}" width="100%" class="img-fluid d-block" style="height: 253px !important;object-fit: contain;">
    </div>
</div>
@endif
@endsection