<div class="tab-pane" id="gIn">
    <br/>
    <div class="col-lg-12 table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>{{ trans('customer.identification')}} {{trans('customer.type')}}</th>
                <th>{{ trans('customer.identification')}} Number</th>
                <th> Issued date </th>
                <th> Issued By </th>
                <th> Expired date </th>
            </tr>
            </thead>
            <tbody>
            @foreach($client->Identification as $iden)
                <tr>
                    <td>{{$iden->types->description}}</td>
                    <td>{{$iden->id_number}}</td>
                    <td>{{UnEmptyDate($iden->issued_date)}}</td>
                    <td>{{$iden->issued_by}}</td>
                    <td>{{UnEmptyDate($iden->id_expiry_date)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>