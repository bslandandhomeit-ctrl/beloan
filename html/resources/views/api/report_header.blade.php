@if(app()->getLocale() == 'kh')
     <div class="report_header">
            <div class="report_logo">
                <img src="{{ asset('images/header2.PNG', isset($secure)?false:false) }}" style="width: 100%">
            </div>
            <div class="company_address" style="font-size: 10px;">
                <p style="font-weight: bold;">ទីតាំងការិយាល័យ </p>
                <p>{{EN_HO_ADDRESS}}</p>
                <p>{{EN_HO_TEL}}</p>
                @if(!empty($co_phone))
                    <p>CO Tel : {{$co_phone}} </p>
                @endif
            </div>
        </div>
@else
    <div class="report_header">
        <div class="report_logo">
            <img src="{{ asset('images/header2.PNG', isset($secure)?false:false) }}" style="width:auto">
        </div>

        <div class="company_address" style="font-size: 10px;">
            <p style="font-weight: bold;">Office Address</p>
            <p>{{EN_HO_ADDRESS}}</p>
            <p>{{EN_HO_TEL}}</p>
            @if(!empty($co_phone))
                <p>CO Tel : {{$co_phone}} </p>
            @endif
        </div>
    </div>

@endif
