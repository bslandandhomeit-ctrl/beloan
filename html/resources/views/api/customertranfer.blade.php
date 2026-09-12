@if ($customertranfer->count() > 0)
    <h4 class="sch_title">{{ trans('loan.transfer_clients') }}</h4>
    <table class="table table-bordered">
        <thead style="text-align: center">
            <tr>
                <th tyle="text-align: center">No</th>
                <th>Transfer Date</th>
                <th>Transfer From</th>
                <th>Transfer To</th>
                <th>Transfer Type</th>
                <th>Transfer Remark</th>
            </tr>
        </thead>
        @foreach ($customertranfer as $key => $row)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ date('d-m-Y',strtotime($row->created_at)) }}</td>
                <td>{{ !empty($row->Clients)?$row->Clients->client_name:'-' }}</td>
                <td>{{ !empty($row->newClients)?$row->newClients->client_name:'-' }}</td>
                <td>{{ $row->transfer_type }}</td>
                <td>{{ $row->remark }}</td>
            </tr>
        @endforeach
    </table>
@endif
