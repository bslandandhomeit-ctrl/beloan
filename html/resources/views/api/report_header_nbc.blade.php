<div class="report_header">
    <div class="text-center">
        <img src="{{ asset('images/tgo.jpg', isset($secure)?false:false) }}" height ="70" />
        <h3>{{$report_title}}</h3>
        <p class="">
            @if($start)
            From: {{$start}}
            @endif

            @if($end)
            To: {{$end}}
            @endif
        </p>
    </div>

    <p class="table-smaller">Printed by: {{Auth::user()->name}}</p>
    <p class="table-smaller">Printed date: {{date('Y-m-d')}}</p>
    @if($currency_id) <p class="table-smaller">Currency: @if($consolidate) Consolidated @endif {{$currency[$currency_id]}} (base)</p> @endif
</div>
