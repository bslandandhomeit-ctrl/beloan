<?php
//$nf = new NumberFormatter("km", NumberFormatter::SPELLOUT );
?>
@extends('contract.payoff_letter_template')
@section('contract_type')
@if($loan->branch_id===1)
<h3 class="khmer-title text-left mt-3 mb-3 company_{{$loan->branch_id}}" style="margin: 7px;margin-left: 0px;">ក្រុមហ៊ុន អ៊ីស្ត លែន អេន ហូម ឯ.ក</h3>
@else
<h3 class="khmer-title text-left mt-3 mb-3 company_{{$loan->branch_id}}" style="margin: 7px;margin-left: 0px;">ក្រុមហ៊ុន ប៊ីអេស លែន & ហូម ឯ.ក</h3>
@endif
<h3 class="khmer-title text-left mt-3 mb-3 company_{{$loan->branch_id}}" style="margin: 7px;margin-left: 0px;">នាយកដ្ឋានផ្នែកកិច្ចសន្យា</h3>
<h3 class="khmer-title text-left mt-3 mb-3 company_{{$loan->branch_id}}" style="margin: 7px;margin-left: 0px;">លេខៈ<input class="company_{{$loan->branch_id}}" type="text" style="border: none;
    margin: 0px;
    width: 70px;
    font-family: 'Khmer os Muol light';" />នផក</h3>



<h3 class="khmer-title text-center mt-3 mb-3 company_{{$loan->branch_id}}" style="text-decoration: underline;">លិខិតបញ្ជាក់ការបង់ប្រាក់ផ្តាច់</h3>
<br/>
@endsection
