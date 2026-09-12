<br/>
<div class = "tab-pane table-responsive" id = "spouse">
    <div class="col-lg-10">
        <table class="table table-bordered table-striped">
            <tr>
                <td scope = "col">{{trans('multiple.en')}} {{trans('user.u_user_name')}}</td>
                <td scope = "col">{{$client->Spouse->first()->family_name_eng}} {{$client->Spouse->first()->first_name_eng}}</td>
                <td>{{trans('multiple.kh')}} {{trans('user.u_user_name')}}</td>
                <td scope = "col">{{$client->Spouse->first()->family_name_kh}} {{$client->Spouse->first()->first_name_kh}}</td>
            </tr>
            <tr>
                <td>{{ trans('customer.cus_birth_date') }}</td>
                <td scope = "col">{{$client->Spouse->first()->birthday}}</td>
                <td>{{ trans('customer.cus_nationality') }} </td>
                <td scope = "col">{{$client->Spouse->first()->nationality}}</td>
            </tr>
            </tr>
            <tr>
                <td>{{ trans('multiple.occupation') }}</td>
                <td>{{$client->Spouse->first()->occupation }}</td>

                <td>{{ trans('customer.identification')}} {{trans('customer.type')}}</td>
                <td>{{$client->Spouse->first()->id_type}}</td>
            </tr>
            <tr>
                <td>Id Number</td>
                <td>{{$client->Spouse->first()->id_number }}</td>

                <td>{{ trans('customer.cus_issued_date') }}</td>
                <td>{{$client->Spouse->first()->id_issued_date }}</td>
            </tr>
            <tr>
                <td>{{ trans('customer.cus_expired_date') }}</td>
                <td>{{$client->Spouse->first()->exp_date }}</td>

                <td></td>
                <td></td>
            </tr>

        </table>
    </div>
</div>