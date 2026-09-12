@extends('contract.template')
@section('contract_type')
<h3 class="khmer-title text-center mt-3 mb-3">កិច្ចសន្យាលក់ ទិញដីឡូតិ៍ ក្នុងគម្រោង</h3>
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
    	<td><span class="text-bold english">{{ $loan->UnitTypes->name }}</span></td>
    	<td width="100px" class="text-bold">លេខ</td>
    	<td width="30px" class="text-center">៖</td>
    	<td width="250px" class="english text-bold">{{ $loan->units->code }}</td>
    </tr>
    <tr>
    	<td width="180px" class="text-bold">ទំហំដី</td>
    	<td width="30px" class="text-center">៖</td>      
    	<td width="310px">
    		<p class="mb-0 pb-0">
		    @if($loan->units->land_size_width)
            ទទឹង {{ khmerNumber($loan->units->land_size_width) }} ម៉ែត្រ និង បណ្តោយ {{ khmerNumber($loan->units->land_size_length) }} ម៉ែត្រ
            @else
            សុរប {{ khmerNumber($loan->units->land_area) }} ម៉ែត្រការ៉េ
            @endif     
    		</p>
            <span><small><i>(ដែល​អាច​មាន​ការ​ប្រែប្រួល​បន្តិច​បន្ទួច​ជាលក្ខណៈ​បច្ចេកទេស អាស្រ័យ​លើ​ការ​កំណត់​ព្រុំ​របស់​មន្ត្រី​សុរិយោដី)</i></small></span>

    	</td>
    	<td width="120px" class="text-bold">សេវា និង​ថ្លៃ​គ្រប់គ្រង​ថែទាំ</td>
    	<td width="30px" class="text-center">៖</td>
    	<td width="250px"> 
    	   ដូច​ចែង​ក្នុង​<span class="text-bold">ប្រការ៦</span> នៃ​កិច្ច​សន្យា​លក់​ទិញនេះ។
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
    		{{ khmerNumber(number_format($loan->final_price)) }} ({{ khNumberWord($loan->final_price) }})
			ដុល្លារ​​​អាមេរិច
		</td>
    </tr>
</table>

<p class="khmer-title pb-0 mb-0">ប្រការ២៖ ការទូទាត់ប្រាក់ថ្លៃលក់ទិញអចលនវត្ថុ</p>
<p><span class="text-bold">២.១.</span> ដោយ​មាន​ការ​ព្រមព្រៀង​គ្នា​ក្នុង​ការ​លក់​ទិញ នូវ​អចលនវត្ថុ​ដូច​បាន​ចែង​ក្នុង​ <span class="text-bold">ប្រការ១ “អត្តសញ្ញាណ​នៃ​អចលនវត្ថុ​លក់​ទិញ និងតម្លៃ​លក់​ទិញ”</span> ខាងលើ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​ទូទាត់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ​ខាង​លើ​ទៅ​តាម​ដំណាក់កាល​នៃ​ការ​ទូទាត់​ឲ្យ​បាន​ត្រឹមត្រូវ គ្រប់​ចំនួន​ និង​ទាន់​ពេល​វេលា​ជា​បន្ត​បន្ទាប់​ដូច​បាន​ចែង​ក្នុង​ឧបសម្ព័ន្ធទី១ <span class="text-bold">“តារាងកាលវិភាគ​នៃ​ការ​ទូទាត់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ”</span>។ ភាគី <span class="text-bold">“អ្នកទិញ”</span> អាច​ទូទាត់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ​ដោយ​ផ្ទាល់​នៅ​ការិយាល័យ​របស់​ភាគី <span class="text-bold">“អ្នកលក់”</span> ។
    {{-- ឬ​តាម​រយៈ​គណនី​ធនាគារ <span class="text-bold">{{ $bank->short_name }}</span> ដូច​មាន​ព័ត៌មាន​លម្អិត​ខាង​ក្រោមនេះ៖</p>

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

<div style="page-break-before: always;"></div>

<p class="khmer-title pb-0 mb-0">ប្រការ៣៖ ការលក់ ឬផ្ទេរសិទ្ធិលើការលក់ទិញអចលនវត្ថុបន្តទៅតតិយជន និងឥណទានពីធនាគារ</p>
<p><span class="text-bold">៣.១.</span> ក្នុង​កំឡុង​ពេល​នៃ​ការ​អនុវត្ត​កិច្ចសន្យា​លក់​ទិញ​នេះ ភាគី <span class="text-bold">“អ្នកទិញ”</span> អាច​លក់ ឬ​ផ្ទេរ​សិទ្ធិ​លើ​ការ​លក់​ទិញ​អចលនវត្ថុ​ទៅ​ តតិយជន​ណា​មួយ​បាន​ដោយ​ការ​ស្នើ​សុំ​ជា​លាយ​លក្ខណ៍​អក្សរ ហើយ​ទទួល​បាន​ការ​អនុញ្ញាត​យល់​ព្រម​ពី​ភាគី <span class="text-bold">“អ្នកលក់”</span> ដោយភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​បង់​ថ្លៃ​សេវា​រដ្ឋបាល​លើ​ការ​លក់ ឬ​ផ្ទេរ​សិទ្ធិ​លើ​ការ​លក់​ទិញ អចលនវត្ថុ​នេះ​ស្មើ​នឹង <span class="text-bold">{{ khmerNumber($loan->unittypes->contract_transfer_fee) }} ({{ khNumberWord($loan->unittypes->contract_transfer_fee) }}) ដុល្លារ​អាមេរិក</span> ជូន​ភាគី <span class="text-bold">“អ្នកលក់”</span>។ មុន​នឹង​អាច​លក់ ឬ​ផ្ទេរ​សិទ្ធិ​លើ​ការ​លក់​ទិញ​អចលនវត្ថុ​ទៅ​តតិយជន​ណា​មួយ​បាន ប្រសិន​បើ​ភាគី <span class="text-bold">“អ្នកទិញ”</span> យឺតយ៉ាវ ឬ​ខក​ខន​មិន​បាន​បំពេញ​កាតព្វកិច្ច​ទូទាត់​បង់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ​ជូន​ភាគី <span class="text-bold">“អ្នកលក់”</span> ទៅ​តាម​ដំណាក់​កាល​នៃ​ការ​ទូទាត់​ឲ្យ​បាន​ត្រឹមត្រូវ គ្រប់ចំនួន និងទាន់​ពេល​វេលា​ដូច​ចែង​ក្នុង​ <span class="text-bold">ប្រការ២</span> នោះ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​ទូទាត់​ប្រាក់​ដែល​នៅ​ជំពាក់ និង​ប្រាក់​ពិន័យ​លើ​ការ​យឺត​យ៉ាវ ឬ​ខក​ខាន​នោះ​ឲ្យ​រួច​រាល់​ជា​មុន​សិន។ តតិយជន​ដែល​ទទួល​ទិញ ឬ​ទទួល​ការ​ផ្ទេរ​សិទ្ធិ​លើ​ការ​លក់​ទិញ​អចលនវត្ថុ​នេះ ត្រូវ​គោរព​តាម​ទាំង​ស្រុង​នូវ​ខ្លឹម​សារ​នៃ​កិច្ចសន្យា​លក់​ទិញ​នេះ ហើយ​អនុវត្ត​តាម​បែប​បទ​រដ្ឋបាល​មួយ​ចំនួន​ដែល​ភាគី <span class="text-bold">“អ្នកលក់”</span> តម្រូវ។</p>
<p><span class="text-bold">៣.២.</span> ចំពោះ​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ដែល​ជ្រើស​រើស​ជម្រើស​បង់រម្លស់ <span class="english">(Loan)</span> ដែល​ភ្ជាប់​នឹង​អត្រា​ការ​ប្រាក់​ជាមួយ​ភាគី <span class="text-bold">“អ្នកលក់”</span> នៅ​ពេល​ដែល​ដី​ត្រូវបានចាក់​បំពេញ​បាន​ចាប់ពី៧០% (ចិតសិបភាគរយ) ឡើង​ទៅ ភាគី <span class="text-bold">“អ្នកលក់”</span> សូម​រក្សា​សិទ្ធិ​ក្នុង​ការ​ចាត់​ចែង​បញ្ជូន​សិទ្ធិ​លើ​បំណុល​ដែល​ភាគី <span class="text-bold">“អ្នកទិញ”</span> មាន​ដោយ​ការ​បង់​រម្លស់​ជាមួយ ភាគី <span class="text-bold">“អ្នកលក់”</span> ឲ្យ​ទៅ​ធនាគារ​ណា​មួយ​ដែល​សហការ​ជាមួយ​ភាគី <span class="text-bold">“អ្នកលក់”</span> ហើយ​សេវា​ក្នុង​ការ​សិក្សា​ជា​មួយ​ធនាគារ​ទាំងនោះ គឺ​ជា​បន្ទុក​របស់​ភាគី <span class="text-bold">“អ្នកទិញ”</span>។</p>

<p class="khmer-title pb-0 mb-0">ប្រការ៤៖ ការសន្យាប្រគល់អចលនវត្ថុ</p>
<p>ភាគី <span class="text-bold">“អ្នកលក់”</span> សន្យា​នឹង​ប្រគល់​អចលនវត្ថុ​ជូន​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ក្នុង​រយៈ​ពេល <span class="text-bold">{{ khmerNumber(number_format($loan->unittypes->deadline,0)) }} ({{ khNumberWord($loan->unittypes->deadline) }}) ខែ​</span> ដោយ​រាប់​ចាប់​ពី​កាលបរិច្ឆេទ​ចុះ​កិច្ចសន្យា​លក់ទិញ​នេះ។ ក្នុង​ករណី​ដែល​មាន​ការ​យឺត​យ៉ាវ ឬ​ខកខាន​ក្នុង​​ការ​ប្រគល់​អចលនវត្ថុ​ជូន ភាគី <span class="text-bold">“អ្នកទិញ”</span> ភាគី <span class="text-bold">“អ្នកលក់”</span> សូម​រក្សា​សិទ្ធិ​ពន្យារ​ពេល​ថែម <span class="text-bold">{{ khmerNumber(number_format($loan->unittypes->extended_deadline,0)) }} ({{ khNumberWord($loan->unittypes->extended_deadline) }}) ខែ​</span> បន្ថែម​ទៀត ជា​រយៈ​ពេល​អនុគ្រោះ ក្នុង​ការ​សន្យា​ប្រគល់​អចលនវត្ថុ​ជូន ភាគី <span class="text-bold">“អ្នកទិញ”</span>។ បើ​ផុត​រយៈ​ពេល​អនុគ្រោះ​ខាង​លើ​ហើយ ភាគី <span class="text-bold">“អ្នកលក់”</span> នៅ​តែ​មិន​ទាន់​មាន​អចលនវត្ថុ ប្រគល់​ជូន​ភាគី “អ្នកទិញ” ទេ  ភាគី <span class="text-bold">“អ្នកលក់”</span> សូម​ទទួល​ខុស​ត្រូវ​ក្នុង​ការ​បង់​សំណង​ពិន័យ​ជូន ភាគី “អ្នកទិញ” ស្មើនឹងអត្រា ១% (មួយភាគរយ) ក្នុង​មួយ​ខែ នៃ​ប្រាក់​ដែល​ភាគី <span class="text-bold">“អ្នកទិញ”</span> បាន​ទូ​ទាត់​បង់​ជូន​ភាគី <span class="text-bold">“អ្នកលក់”</span> រាប់​ចាប់​ពី​កាលបរិច្ឆេទ​ដែល​ផុត​កំណត់​ត្រូវ​ប្រគល់​អចលនវត្ថុ​ជូនភាគី <span class="text-bold">“អ្នកទិញ”</span>។ ទាក់​ទង​នឹង​ការ​ប្រគល់​ទទួល​អចលនវត្ថុនេះ ភាគី <span class="text-bold">“អ្នកទិញ”</span> អាច​ទទួល​អចលនវត្ថុ​បាន​លុះ​ត្រាតែ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​ទូទាត់​ប្រាក់​ដែល​នៅ​ជំពាក់ និង​ប្រាក់​ពិន័យ​លើ​ការ​យឺត​យ៉ាវ ឬ​ខក​ខាន​នោះ​ឲ្យ​រួច​រាល់​ជា​មុន​សិន។ ចំពោះ​អចលនវត្ថុ​ដែល​ជា​កម្មវត្ថុ​នៃ​ការ​លក់​ទិញ​ណា​មួយ ដែល​ភាគី <span class="text-bold">“អ្នកទិញ”</span> មិន​ទាន់​បាន​ទូទាត់​ថ្លៃលក់ទិញ​រួច​រាល់​ទាំង​ស្រុង​ជូន ភាគី <span class="text-bold">“អ្នកលក់”</span> ទេ ភាគី <span class="text-bold">“អ្នកទិញ”</span> នោះ​មិន​ទាន់​ក្លាយ​ជា​ម្ចាស់​អចលនវត្ថុ​ពេញ​លេញ និង​ស្រប​ច្បាប់​ឡើយ ទោះ​បី​ជា​ភាគី <span class="text-bold">“អ្នកលក់”</span> បាន​ប្រគល់​អចលនវត្ថុ​នោះជូន ភាគី <span class="text-bold">“អ្នកទិញ”</span> ក៏ដោយ។</p>

<p class="khmer-title pb-0 mb-0">ប្រការ៥៖ ការសន្យា និងការទទួលខុសត្រូវក្នុងការអភិវឌ្ឍន៍អចលនវត្ថុរវាងភាគី “អ្នកលក់” និងភាគី “អ្នកទិញ”</p>

<p class="pb-0"><span class="text-bold">៥.១.</span> ភាគី <span class="text-bold">“អ្នកលក់”</span> សន្យា​ធ្វើ​ការ​អភិវឌ្ឍន៍​អចលនវត្ថុ​ជូន​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ដូច​ខាង​ក្រោម​ទៅ​តាម​កាល​វិភាគ​ពេលវេលា និង​ដំណាក់​កាល​ការងារ​អភិវឌ្ឍន៍​របស់​គម្រោង​ខ្លួន៖</p>
<ul class="ml-4">
    <li>- សាង​សង់​ផ្លូវ​បេតុង​ក្នុង​បរិវេណ​គម្រោង​ដែល​ភាគី <span class="text-bold">“អ្នកលក់”</span> បាន​លក់​ជូន​ភាគី <span class="text-bold">“អ្នកទិញ”</span>។</li>
    <li>- សាង​សង់​ប្រព័ន្ធ​លូ​ក្នុង​បរិវេណ​គម្រោង​ដែល​ភាគី <span class="text-bold">“អ្នកលក់”</span> បាន​លក់​ជូន​ភាគី <span class="text-bold">“អ្នកទិញ”</span>។</li>
    <li>- ត​ភ្ចាប់​ប្រព័ន្ធ​ទឹក​ស្អាត​ក្នុង​បរិវេណ​គម្រោង​ដែល​ភាគី <span class="text-bold">“អ្នកលក់”</span> បាន​លក់​ជូន​ភាគី <span class="text-bold">“អ្នកទិញ”</span>។</li>
    <li>- សាង​សង់ និង​ភ្ជាប់​បណ្តាញ​ប្រព័ន្ធ​អគ្គិសនី និង​ចាក់​ដី​បំពេញ​មិន​ឲ្យ​លិច​ទឹក​ក្នុង​បរិវេណ​គម្រោង​ដែល​ភាគី <span class="text-bold">“អ្នកលក់”</span> បាន​លក់​ជូន​ភាគី <span class="text-bold">“អ្នកទិញ”</span>។</li>
</ul>
<p><span class="text-bold">៥.២.</span> ភាគី <span class="text-bold">“អ្នកលក់”</span> សន្យា​ថា​បើ​ថ្ងៃ​ក្រោយ​មាន​តតិយជន​ណា​មួយ​ចេញ​មក​តវ៉ា ឬ​ទាម​ទារ​សិទ្ធិ​លើ​អចលនវត្ថុ​ខាង​លើ ហើយ​អះអាង​ថា​អចលនវត្ថុ​នេះមិ​នមែន​ស្ថិត​ក្រោម​កម្មសិទ្ធិ​របស់ ភាគី <span class="text-bold">“អ្នកលក់”</span> ឬ​ថា​ជា​អចលនវត្ថុ​មិន​ស្រប​ច្បាប់ ភាគី <span class="text-bold">“អ្នកលក់”</span> សុខ​ចិត្ត​ទទួល​ខុស​ត្រូវ​ដោះស្រាយទាំង​ស្រុង​ចំពោះ​មុខច្បាប់ ហើយ​ការ​ចំណាយ​នា​នា​ទាក់ទង​នឹង​បញ្ហា​ខាងលើ ជា​ការ​ទទួល​ខុសត្រូវ​ទាំង​ស្រុង​របស់​ភាគី <span class="text-bold">“អ្នកលក់”</span>។</p>
<p><span class="text-bold">៥.៣.</span> ភាគី <span class="text-bold">“អ្នកទិញ”</span> ក្រោយ​ពេល​ទទួល​បាន​អចលនវត្ថុ​ពី​ភាគី <span class="text-bold">“អ្នកលក់”</span> ហើយ​នៅ​ពេល​ណា​ដែល​ខ្លួន​មាន​ចេតនា​សាង​សង់​សំណង់​លំនៅឋាន ឬ​សំណង់​ផ្សេងៗ​នោះ ត្រូវ​បន្សល់​ទុក​ដី​ខាង​មុខ​សំណង់​របស់​ខ្លូន​ចំនួន <span class="text-bold">០៤(បួន) ម៉ែត្រ</span> ដោយ​វាស់​ចេញ​ពី​ផ្នែក​មុខ​នៃ​សំណង់​របស់​ខ្លួន ហើយ​ទុក​ខាង​ក្រោយ​សល់ <span class="text-bold">០១ (មួយ) ម៉ែត្រ</span> ដោយ​វាស់​ចេញ​ពី​ផ្នែក​ខាង​ក្រោយ​នៃ​សំណង់​របស់​ខ្លួន។</p>

<div style="page-break-before: always;"></div>

<p class="khmer-title pb-0 mb-0">ប្រការ៦៖ ថ្លៃសេវាថែទាំគ្រប់គ្រងក្នុងបរិវេណគម្រោងអភិវឌ្ឍន៍</p>
<p>ភាគី <span class="text-bold">“អ្នកទិញ”</span> យល់​ព្រម​បង់ថ្លៃ អនាម័យ សណ្ដាប់ធ្នាប់ សន្តិសុខ សោភ័ណ្ឌភាព ភ្លើងបំភ្លឺសាធារណៈ និង​ការ​ជួសជុល​ផ្លូវ​ខូចខាត​ជូន​មក ភាគី <span class="text-bold">“អ្នកលក់”</span> សម្រាប់​មួយ​ឆ្នាំ​ម្តង ទៅ​តាម​តម្លៃ​ទីផ្សារ​ជាក់​ស្តែង​ដែល ភាគី <span class="text-bold">“អ្នកលក់”</span> នឹង​កំណត់​នៅ​ពេល​អនាគត ហើយ​ត្រូវ​បង់​មួយ​ឆ្នាំ​ទុក​ជា​មុន នៅ​ពេល​ដែល​ភាគី <span class="text-bold">“អ្នកទិញ”</span> ចាប់​ផ្ដើម​ធ្វើ​ការ​សាង​សង់​សំណង់​លំនៅឋាន ឬ​សំណង់​ផ្សេងៗ​លើ​អចលនវត្ថុ។ ក្នុង​ករណី​ភាគី <span class="text-bold">“អ្នកទិញ”</span> មាន​បំណង តភ្ជាប់​បណ្តាញ​ប្រព័ន្ធ​ទឹកស្អាត និង​ចរន្ត​អគ្គិសនី​ចូល​ទៅ​ក្នុង​លំនៅឋាន​របស់​ខ្លួន ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​ទទួល​ខុស​ត្រូវ​ទៅ​លើ​ការ​ចំណាយ​ទាំងស្រុង។</p>

<p class="khmer-title pb-0 mb-0">ប្រការ៧៖ ប័ណ្ណបញ្ជាក់សិទ្ធិលើកម្មសិទ្ធិនៃអចលនវត្ថុនៃការលក់ទិញ</p>
<p>ភាគី <span class="text-bold">“អ្នកលក់”</span> សន្យា​ថា​នឹង​ប្រគល់​ប័ណ្ណ​បញ្ជាក់​សិទ្ធិ​លើ​កម្មសិទ្ធិ​នៃ​អចលនវត្ថុ​នៃ​ការ​លក់ទិញ ជា​ប្រភេទ​ដូច​ចែង​ក្នុង​ <span class="text-bold">ប្រការ១៖ អត្តសញ្ញាណ​នៃ​អចលនវត្ថុ​លក់ទិញ និង​តម្លៃ​លក់​ទិញ</span> ជូនភាគី <span class="text-bold">“អ្នកទិញ”</span> បន្ទាប់​ពី​ភាគី <span class="text-bold">“អ្នកទិញ”៖</span> <span class="text-bold">(១).</span> បាន​ទទួល​យក​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញ​ពី​ភាគី <span class="text-bold">“អ្នកលក់”</span> រួច​រាល់​ហើយ <span class="text-bold">(២).</span> បាន​ទូ​ទាត់​បង់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ​រួច​រាល់​គ្រប់​ចំនួន​ជូនមក ភាគី <span class="text-bold">“អ្នកលក់”</span> ដូច​បាន​ចែង​ក្នុង​ឧបសម្ព័ន្ធទី១ <span class="text-bold">“តារាង​កាល​វិភាគ​នៃ​ការ​ទូទាត់​ប្រាក់​ថ្លៃ​លក់​ទិញ​អចលនវត្ថុ” (៣).</span> មក​បំពេញ​បែប​បទ និង​ឯកសារ​គ្រប់គ្រាន់​ជូន​ភាគី <span class="text-bold">“អ្នកលក់”</span> ដើម្បី​រៀប​ចំ​ដាក់​ស្នើ ឬ​រៀប​ចំ​បែបបទ​រដ្ឋបាល​ជា​មួយ​មន្ត្រី​ជំនាញ​សុរិយោ​ដី​ដើម្បី​ស្នើ​ទៅ​តាម​នីតិ​វិធី​ច្បាប់ឲ្យ​បាន​ទាន់​ពេល​វេលា និង​រលូន​ជូន​ភាគី <span class="text-bold">“អ្នកទិញ”</span> (ប្រសិន​បើ​ត្រូវ​ការ)។ ទោះ​ជា​យ៉ាង​ណា​ក៏​ដោយ​ ប្រសិន​បើ​មាន​ការ​យឺត​យ៉ាវ​ក្នុង​ការ​សម្រេច និង​អនុញ្ញាត​ចេញ​ប័ណ្ណ​បញ្ជាក់​សិទ្ធិ​លើ​កម្មសិទ្ធិ​លើ​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញ ដោយ​សារ​តែ​បញ្ហា​បច្ចេកទេស ឬ​រដ្ឋបាល​របស់​មន្ត្រី​សុរិយោដី ឬ​មន្ត្រី​មាន​សមត្ថកិច្ច​នោះ ភាគី <span class="text-bold">“អ្នកទិញ”</span> មិន​អាច​តវ៉ា ឬ​ទំលាក់​កំហុស​លើ​ភាគី <span class="text-bold">“អ្នកលក់”</span> ដែល​បាន​ប្រឹងប្រែង​អស់​ពី​សមត្ថភាព​ហើយ​នោះទេ។ ការ​ចំណាយ​លើ​ការ​រៀប​ចំ​ឯកសារ​ផ្ទេរ​សិទ្ធិ​លើ​កម្មសិទ្ធិ ដូច​ចែងក្នុង <span class="text-bold">ប្រការ១</span> បូក​រួម​នឹង​ការ​បង់​ពន្ធ​ប្រថាប់​ត្រា <span class="text-bold">៤% (បូនភាគរយ)</span> គឺ​ជាការ​ទទួល​ខុស​ត្រូវ​ដោយ​ផ្ទាល់​របស់ ភាគី <span class="text-bold">“អ្នកទិញ”</span> ដោយ​ភាគី <span class="text-bold">“អ្នកលក់”</span> ជួយ​សម្រួល និង​រៀបចំ​ឯក​សារ​ពាក់​ព័ន្ធ​ជូន​ទៅ​តាម​ស្ថាន​ភាព​ជាក់ស្តែង។</p>

<p class="khmer-title pb-0 mb-0">ប្រការ៨៖ ការទទួលខុសត្រូវបង់ពន្ធអចលនវត្ថុប្រចាំឆ្នាំរបស់គូភាគី</p>
<p><span class="text-bold">៨.១</span> ភាគី <span class="text-bold">“អ្នកលក់”</span> ទទួល​ខុស​ត្រូវ​ទាំង​ស្រុង​ចំពោះ​ការ​បង់​ពន្ធ​អចលនវត្ថុ​ប្រចាំ​ឆ្នាំ ឬ​បង់​ពន្ធ​ដី​មិន​បាន​ប្រើ​ប្រាស់​ឬ​ពន្ធ​នានា​ពាក់​ព័ន្ធ​នឹង​អាជីវកម្ម​សាង​សង់​លំនៅឋាន​របស់​ខ្លួន​មុន​កាលបរិច្ឆេទ​នៃ​ការ​ប្រគល់​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញ​ជូន​ទៅ​ភាគី <span class="text-bold">“អ្នកទិញ”</span>។</p>
<p><span class="text-bold">៨.២</span> ភាគី <span class="text-bold">“អ្នកទិញ”</span> ត្រូវ​ទទួល​ខុស​ត្រូវ​លើ​ការ​បង់​ពន្ធ​អចលវត្ថុ​ប្រចាំ​ឆ្នាំ និង​ពន្ធ​ផ្សេង​ទៀត​ពាក់​ព័ន្ធ​នឹង​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញ​ខាង​លើ​បន្ទាប់​ពី​កាល​បរិច្ឆេទ​ទទួល​យក​អចលនវត្ថុ​នៃ​ការ​លក់​ទិញ ពីភាគី <span class="text-bold">“អ្នកលក់”</span>។</p> 

<p class="khmer-title pb-0 mb-0">ប្រការ៩៖ ទាយាទ អ្នកស្នងមរតក និងអ្នកទទួលសិទ្ធិស្របច្បាប់</p>
<p>ក្នុង​ករណី​ដែល​មាន​ភាគី​ណា​មួយ មិន​អាច​អនុវត្ត​សិទ្ធិ និង​កាតព្វកិច្ច​របស់​ខ្លួន​បាន​ដោយ​មូលហេតុ​ណា​មួយ​បណ្តាល​មក​ពី មរណភាព ពិការភាព ឬ​អវត្តមាន​ជា​បណ្តោះ​អាសន្ន ឬ​អចិន្ត្រៃយ៍​នៅ​ក្នុង​ប្រទេស​នោះ រាល់​សិទ្ធិ និង​កាតព្វកិច្ច​ទាំង​អស់​របស់​ភាគី​នោះ​ដែល​ស្ថិត​ក្រោម​ខ្លឹមសារ​នៃ​កិច្ចសន្យា​នេះ ត្រូវ​បន្ត​ទៅ​ទាយាទ និង/ឬ អ្នក​ស្នង​មរតក និង/ឬអ្នកតំណាង​ស្រប​ច្បាប់​ណា​មួយ​របស់​ខ្លួន​ដោយ​ស្វ័យប្រវត្តិ។</p>

<p class="khmer-title pb-0 mb-0">ប្រការ១០៖ គោលការណ៍ស្ម័គ្រចិត្ត និងការព្រមព្រៀងនៃកិច្ចសន្យា</p>
<p>កិច្ចសន្យា​លក់​ទិញ​អចលនវត្ថុ​នេះ ត្រូវ​ធ្វើ​ឡើង​ដោយ​គ្មាន​ការ​គំរាម​កំហែង បង្ខិតបង្ខំ ឬ​មាន​បញ្ហា​ខុស​ច្បាប់​ណា​មួយ​ឡើយ ហើយ <span class="text-bold">គូភាគី</span> មាន​សមត្ថភាព​គ្រប់គ្រាន់​ក្នុង​ការ​ដឹង​លឺ ឬ​យល់​ដឹង បាន​អាន បានស្តាប់ និង​យល់​ព្រម​តាម​លក្ខខណ្ឌ​ទាំងឡាយ​នៃ​កិច្ចសន្យា​នេះ។ កិច្ចសន្យា​លក់​ទិញ​អចលនវត្ថុ​នេះ មាន​សុពលភាព​នា​កាលបរិច្ឆេទ​ដែល <span class="text-bold">គូភាគី</span> បាន​ផ្តិត​ស្នាម​មេដៃ និង​ចុះ​ត្រា​ក្រុមហ៊ុន​ដូច​ខាង​ក្រោម។ កិច្ចសន្យា​លក់ទិញ​អចលនវត្ថុ​នេះ​ធ្វើ​ឡើង​ជាភាសាខ្មែរ​ចំនួន <span class="text-bold">០២ (ពីរ)</span> ឯកសារ​ច្បាប់​ដើម ក្នុងនោះ ភាគី <span class="text-bold">“អ្នកលក់”</span> រក្សាទុក <span class="text-bold">០១ (មួយ)</span> ឯកសារ​ច្បាប់ដើម ហើយភាគី <span class="text-bold">“អ្នកទិញ”</span> រក្សាទុក <span class="text-bold">០១ (មួយ)</span> ឯកសារ​ច្បាប់​ដើម។ ឯកសារ​នីមួយៗ​មាន​សុពលភាព និង​អានុភាព​គតិយុត្តិ​ស្មើៗ និងដូចគ្នា។</p>
@endsection

@section('first')

<h4 class="khmer-title text-center">ឧបសម្ព័ន្ធទី៣៖</h4>  
<h4 class="khmer-title text-center mb-4">“អត្តសញ្ញាណប័ណ្ណ ឬលិខិតឆ្លងដែនរបស់ភាគី អ្នកលក់ និងភាគី អ្នកទិញ”</h4>
<?php
    $attachment_array = $loan->client->ClientCbcGeneral->toArray();
?>
@if(array_key_exists('photo',$attachment_array) || array_key_exists('signature',$attachment_array))  
<p class="text-bold">- អត្តសញ្ញាណប័ណ្ណ អ្នកទិញ</p>
@endif
<div class="row my-4">
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
    <div class="row my-4">
        <div class="col-lg-6" style="height: 253px !important;">      
            <img src="{{ $national_front }}" width="100%" class="img-fluid d-block" style="height: 253px !important;object-fit: contain;">       
        </div>
        <div class="col-lg-6" style="height: 253px !important;">        
            <img src="{{ $national_back }}" width="100%" class="img-fluid d-block" style="height: 253px !important;object-fit: contain;">
        </div>
    </div>
@endif
@endsection

