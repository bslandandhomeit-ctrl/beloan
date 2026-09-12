<br/>

<div class="tab-pane" id="contact">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th> {{ trans('multiple.contact_info') }}</th>
                    <th> Area Code</th>
                    <th> Country Code</th>
                    <th> Number</th>
                    <th> Extension Code</th>
                    <th> Email address </th>
                </tr>
                </thead>
                <tbody>
                {{--ContactNumberType--}}
                @foreach($client->Contact as $cont)
                    <tr>
                        <td>
                            <?PHP $ContactNumberType = config('static_data.ContactNumberType') ?>
                            {{$ContactNumberType[$cont->contact_number_type]}}

                        </td>
                        <td>{{$cont->contact_number_country_code}}</td>
                        <td>{{$cont->contact_number_area}}</td>
                        <td>{{$cont->contact_number_number}}</td>
                        <td>{{$cont->contact_number_extension}}</td>
                        <td>{{$cont->email_address}}</td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>