<div class = "tab-pane active" id = "1">
    <br/>
    <div class = "table-responsive">

        <table class = "table table-bordered table-striped" id="addressList">
            <thead>
            <tr>
                <th>{{ trans('multiple.m_address') }} {{trans('multiple.m_type')}}</th>
                <th>{{ trans('multiple.country') }}</th>
                <th>{{ trans('multiple.province') }}</th>
                <th>{{ trans('multiple.district') }}</th>
                <th>{{ trans('multiple.commune') }}</th>
                <th>{{ trans('multiple.village') }}</th>
                <th>{{ trans('multiple.house_no') }} </th>
                <th>{{ trans('multiple.street_no') }} </th>
            </tr>
            </thead>
            <tbody>
        @foreach($client->Address as $Address)
            <tr>
                <td>
                    <?PHP
                    $address_type = config('static_data.address_type');
                    echo $address_type[$Address->address_type];
                    ?>
                </td>
                <td>
                   @foreach($Address->country->description as $de)
                       @if($de->language_id == 2)
                           {{$de->name}}
                       @endif
                   @endforeach
                </td>
                <td>{{$Address->province->prov_gis}} - {{$Address->province->eng_name}}</td>
                <td>{{$Address->District->distr_gis}} - {{$Address->District->eng_name}}</td>
                <td>{{$Address->Commune->comm_gis}} - {{$Address->Commune->en_name}}</td>
                <td>{{$Address->Village->vill_gis}} - {{$Address->Village->en_name}}</td>

                <td>{{$Address->address_en1}}</td>
                <td>{{$Address->address_kh1}}</td>
            </tr>

        @endforeach
            </tbody>
        </table>
    </div>
</div>