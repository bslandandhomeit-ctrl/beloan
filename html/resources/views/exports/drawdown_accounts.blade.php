<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
    <thead>
    <tr>
    <th style="text-align: center;">{{ trans('multiple.account_name') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.account_no') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.branch') }}</th>
                                <th style="text-align: center;">Project</th>
                                <th style="text-align: center;">Unit Type</th>
                                <th style="text-align: center;">Unit</th>
                                <th style="text-align: center;">{{ trans('multiple.last_update') }}</th>
                                <th style="text-align: center;">{{ trans('account.a_debit') }}</th>
                                <th style="text-align: center;">{{ trans('account.a_credit') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.balance') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.dd_balance') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.m_description') }}</th>
                                <th style="text-align: center;">{{ trans('multiple.status') }}</th>
    </tr>
    </thead>
    <?php $n = 1;?>
                        <tbody>
                            @forelse($drawdown_accounts as $das)
                            <?php 
                            $status= '';
                            $status_class="";
                            if($das->status==0){
                                $status="Unauthorized";
                                $status_class="badge btn-warning";
                            }else if($das->status==1){
                                $status="Authorized";
                                $status_class="badge btn-primary";
                            }else if($das->status==2){
                                $status="Rejected";
                                $status_class="badge btn-danger";
                            }
                            ?>
                            <tr>
                                <td><a href="{{ route('drawdown_account_detail', $das->id) }}">{{$das->account_name}}</a></td>
                                <td>{{$das->account_no}}</td>
                                <td>{{$das->company_branch->branch_name}}</td>
                                <td>{{$das->project}}</td>
                                <td>{{$das->unit_type}}</td>
                                <td>{{$das->unit}}</td>
                            <?php
                                $balance_arr = "";
                                $balance_arr = get_journal_bal_new($das->coa_id,$end);
                            ?>
                                <td>{{$balance_arr['last_acc_date']}}</td>
                                <td>{{$balance_arr['t_debit']}}</td>
                                <td>{{$balance_arr['t_credit']}}</td>
                                <td>{{$currency[$das->currency].number_format(-$balance_arr['balance'], 2)}}</td>
                                <td>{{$currency[$das->currency].number_format($das->balance, 2)}}</td>
                                <td>{{$desc}}</td>
                                <td>                                    
                                    <?php if($das->status!=2){?>
                                        <a class="pull-right btn btn-danger" href="{{ route('drawdown_account_reject', $das->id) }}"><i class="glyphicon glyphicon-remove"></i> Reject</a>                                       
                                 <?php   }?>
                               
                                <span class="<?php echo $status_class;?>">{{$status}}</span>
                            </td>
                            </tr>
                            <?php $n++;?>
                            @empty
                            <tr><td colspan=12>{{ trans('multiple.m_no_result')}}</td></tr>
                            @endforelse
                        </tbody>
</table>