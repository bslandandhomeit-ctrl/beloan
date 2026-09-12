<table class="table table-bordered table-striped table-condensed tillTran" id="tran">
    <thead class="table-header">
    <tr>
        <th style="text-align: center;">{{ trans('multiple.m_no')  }}</th>
        <th style="text-align: center;">{{ trans('teller.t_time' ) }}</th>
        <th style="text-align: center;">{{ trans('teller.t_from_acc') }}</th>
        <th style="text-align: center;">{{ trans('teller.contract_id') }}</th>
        <th style="text-align: center;">{{ trans('teller.to_account') }}</th>
        <th style="text-align: center;">{{ trans('multiple.m_type') }}</th>
        <th style="text-align: center;">{{ trans('multiple.methode') }}</th>
        <th style="text-align: center;">{{ trans('report.rpt_cash_in') }}</th>
        <th style="text-align: center;">{{ trans('report.rpt_cash_out') }}</th>
        <th style="text-align: center;">{{ trans('teller.t_balance')}}</th>
        <th style="text-align: center;">{{ trans('teller.status')}}</th>
        <th style="text-align: center;">{{ trans('teller.description')}}</th>
        <th style="text-align: center;">{{ trans('teller.action')}}</th>
    </tr>
    </thead>
    <tbody>
        @foreach($tran as $key => $row)
        <?php 
            $auth = "Authorized";
            if($row->approve_status == 0){
                if ($row->type === 'Withdraw') {
                    $auth = "Unauthorized";
                }elseif($row->type === 'Cash Deposit') {
                    $auth = "Unauthorized";
                }else{
                    $auth = "";
                }
            }
        ?>
            <tr>
                <td>{{ ++$key}}</td>
                <td>{{ date('d-m-Y H:i:s',strtotime($row->tranx_time)) }}</td>
                <td>{{ $row->from_account }}</td>
                <td>{{ $row->contract_id}}</td>
                <td>{{ $row->to_account}}</td>
                <td>{{ $row->type}}</td>
                <td>{{ $row->methode}}</td>
                <td>{{ $row->cash_in }}</td>
                <td>{{ $row->cash_out }}</td>
                <td>{{ $row->balance }}</td>
                <td>{{ $auth }}</td>
                <td>{{ $row->description }}</td>
                <td></td>
                
            </tr>
        @endforeach
    </tbody>
</table>