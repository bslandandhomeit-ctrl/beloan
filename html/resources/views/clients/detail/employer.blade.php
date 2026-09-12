<style>
    .table>tbody+tbody {
        border-top: 21px solid #ddd !important;
    }
</style>

<div class="tab-pane" id="employer">
    <div class = "panel-body table-responsive">
        <table class = "table table-bordered _table table-striped">

            @foreach(($employer)?$employer:[] as $employ)
{{--            {{dd($employ->EconomicSector->first()->english)}}--}}
                <tbody  data-toggle="collapse" data-target=".demo1">
                <tr>
                    <td>{{ trans('employer.employer')}} {{trans('employer.type')}}</td>
                    <td>{{config('static_data.employer_type')[$employ->employer_type]}}</td>
                    <td>{{trans('employer.selfEmployed')}}</td>
                    <td>
                        @if($employ->self_employed != "N")
                            YES
                        @else
                            NO
                        @endif
                        {{--<a style="float: right;;" class="btn btn-danger btn-xs glyphicon glyphicon-plus"></a>--}}
                    </td>
                </tr>
                <tr>
                    <td>{{ trans('multiple.en')}} {{ trans('employer.name') }}</td>
                    <td>{{$employ->employer_name}}</td>
                    <td>{{ trans('multiple.kh')}} {{ trans('employer.name') }}</td>
                    <td>{{$employ->employer_name_kh}}</td>
                </tr>
                <tr>
                    <td>{{ trans('employer.bizType') }}</td>
                    <td>{{$employ->business_type}}</td>
                    <td>{{ trans('employer.economic_sector') }}</td>
                    <td>
                        @if(count($employ->economic_id)>0)
                            {{$employ->EconomicSector->khmer}}
                        @endif
                        N/A
                    </td>
                </tr>
                <tr>
                    <td>{{ trans('employer.date_of_employment') }}</td>
                    <td>{{UnEmptyDate($employ->date_of_employment)}}</td>
                    <td>{{ trans('employer.lengtd_of_service')}}(M) </td>
                    <td>{{$employ->length_of_service}}</td>
                </tr>
                <tr>
                    <td>{{ trans('employer.contract_exp_date') }}</td>
                    <td>{{UnEmptyDate($employ->contract_exp_date)}}</td>
                    <td>{{trans('currency.c_currency')}}</td>
                    <td>{{$employ->currency->name}}</td>
                </tr>
                <tr>
                    <td>{{ trans('employer.monthly_basic_salary') }}</td>
                    <td>{{$employ->monthly_basic_salary?$employ->monthly_basic_salary:''}}</td>
                    <td>{{ trans('employer.total_monthly_salary') }}</td>
                    <td>{{$employ->total_monthly_salary}}</td>
                </tr>
                <tr>
                    <td>{{ trans('multiple.country') }}</td>
                    <td>
                        @foreach($employ->country->description as $de)
                            @if((int)$de->language_id == 2)
                                {{$de->name}}
                            @endif
                        @endforeach
                    </td>
                    <td>{{ trans('multiple.province') }}</td>
                    <td>{{$employ->province->prov_gis}} - {{$employ->province->eng_name}}</td>
                </tr>

                <tr>
                    <td>{{ trans('multiple.district') }} </td>
                    <td>{{$employ->District->distr_gis}} - {{$employ->District->eng_name}}</td>

                    <td>{{ trans('multiple.commune') }}</td>
                    <td>{{$employ->Commune->comm_gis}} - {{$employ->Commune->en_name}}</td>
                </tr>

                <tr>
                    <td>{{ trans('multiple.village') }}</td>
                    <td>{{$employ->Village->vill_gis}} - {{$employ->Village->en_name}}</td>
                    <td> {{trans('multiple.en')}} {{ trans('multiple.m_address') }}</td>
                    <td>{{$employ->employer_address}}</td>
                </tr>

                <tr>
                    <td>{{trans('multiple.kh')}} {{trans('multiple.m_address') }}</td>
                    <td>{{$employ->employer_address_kh}}</td>
                    <td>{{ trans('multiple.postalCode') }}</td>
                    <td>{{($employ->postal_code)?$employ->postal_code:'N/A'}}</td>
                </tr>
                <tr>
                    <td> {{trans('multiple.en')}} {{ trans('multiple.occupation') }}</td>
                    <td>{{$employ->occupation}}</td>
                    <td>{{trans('multiple.kh')}} {{ trans('multiple.occupation') }}</td>
                    <td>{{$employ->occupation_kh}}</td>
                </tr>
                </tbody>
            @endforeach

        </table>

    </div>
</div>
