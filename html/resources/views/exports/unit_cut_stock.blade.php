<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                            <tr>
                            <th style="text-align: center;">Customer Name</th>
                                <th style="text-align: center;">Drawdown account</th>
                                <th style="text-align: center;">Cut stock date</th>
                                <th style="text-align: center;">Project</th>
                                <th style="text-align: center;">Unit Type</th>
                                <th style="text-align: center;">Unit</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: center;">GL Code</th>     
                                <th style="text-align: center;">GL Name</th>
                                <th style="text-align: center;">Credit From DD</th>                               

                            </tr>
                        </thead>
                        <?php                         
                         $n = 1;$total=0;?>
                        <tbody>
                            @forelse($drawdown_accounts as $das)
                            <?php  $total=floatval($total) + floatval($das->balance)?>
                            <tr>
                                <td><a href="{{ route('drawdown_account_detail', $das->id) }}">{{$das->account_name}}</a></td>
                                <td>{{$das->account_no}}</td>
                                <td style="vertical-align: middle;text-align: center">{{ !empty($das->created_at)?date('d-M-Y',strtotime($das->created_at)):"-" }}</td>   
                                <td>{{$das->project}}</td>
                                <td>{{$das->unit_type}}</td>
                                <td>{{$das->unit}}</td>
                                <td>{{$das->status}}</td>
                                <td>573102-01-0000</td>
                                <td>Non Settle Drawdown Account</td>
                                <td>{{$currency[$das->currency].number_format($das->balance, 2)}}</td>
                                
                            </tr>
                            <?php $n++;?>
                            @empty
                            <tr><td colspan=12>{{ trans('multiple.m_no_result')}}</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                        <td style="text-align: right;font-weight: bold;" colspan=7>Total:</td>
                        <td style="text-align: center; font-weight: bold;">${{number_format($total, 2)}}</td>
                        </tr>
                    </tfoot>
                    </table>