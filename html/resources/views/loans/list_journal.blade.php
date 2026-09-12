@extends('layouts.app')

@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('report.rpt_journal_entry') }}
        </header>

        <div class="panel-body">
            <h5><strong>{{ trans('report.rpt_journal_entry_for') }} #{{$id}}</strong>
            @if(isset($no_journal) && $no_journal==true)
                <a href="{{ route('add_journal',[$id]) }}" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> {{ trans('sidebar.sb_add_journal') }}</a>
            @endif
            </h5>
            
            <br/>
            <section id="unseen">
                <table  class="table table-bordered table-striped table-condensed">
                    <thead>
                        <th>{{ trans('report.rpt_entry_id') }}</th>
                        <th>{{ trans('report.rpt_office') }}</th>
                        <th>{{ trans('report.rpt_transaction_date') }}</th>
                        <th>{{ trans('report.rpt_transaction_id') }}</th>
                        <th>{{ trans('report.rpt_created_by') }}</th>
                        <th style="text-align: center;">{{ trans('dealer.dl_dealer_account_name') }}<br>{{ trans('report.rpt_explanation') }}</th>
                        <th>{{ trans('account.ref_id') }}</th>
                        <th>{{ trans('report.rpt_debit') }}</th>
                        <th>{{ trans('report.rpt_credit') }}</th>
                        <th>{{ trans('multiple.m_note') }}</th>
                    </thead>
                    <tbody>
                        <?php $loan_id = 0;$i=0;?>
                        @if(!empty($journals))
                        @forelse($journals as $journal)
                            @var $i = 0
                            @foreach($journal->detail as $d)
                                <tr style="text-align:center">
                                    @if($i == 0)
                                        <td rowspan="{{ $i==0?count($journal->detail)+1:0}}">{{ $journal->id }}</td>
                                        <td rowspan="{{ $i==0?count($journal->detail)+1:0}}">{{ $journal->transaction->loan->branch->branch_name }}</td>
                                        <td rowspan="{{ $i==0?count($journal->detail)+1:0}}">{{ $journal->transaction->trans_date }}</td>
                                        <td rowspan="{{ $i==0?count($journal->detail)+1:0}}">{{ $journal->tran_id }}</td>
                                        <td rowspan="{{ $i==0?count($journal->detail)+1:0}}">{{ $journal->user->name }}</td>
                                    @endif
                                    <td style="text-align:left">{{ $d->account->name }}<br>({{$d->account->account_code}})</td>
                                    <td>{{ $d->reference }}</td>
                                    <td style="text-align:right">{{ $d->debit!=0?number_format($d->debit,2,'.',','):'' }}</td>
                                    <td style="text-align:right">{{ $d->credit!=0?number_format($d->credit,2,'.',','):'' }}</td>
                                    <td style="text-align:left">{{ $d->description }}</td>
                                </tr>
                                <?php $i+=1; ?>
                            @endforeach
                            <tr>
                                    <td><i><strong>Note: </strong>{{ $journal->description }}</i></td>
                                    <td colspan=5></td>
                            </tr>
                        @empty
                        <tr><td colspan=11>{{ trans('multiple.m_no_result') }}</td></tr>
                        @endforelse
                        @else
                            <tr><td colspan=11>{{ trans('multiple.m_no_result') }}</td></tr>
                        @endif
                    </tbody>
                </table>
                <div>
                    @if(Session::has('back_saved'))
                        <a href="{{ route('loan_detail',[$loan_id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i>&nbsp; {{ trans('multiple.m_back') }}</a>
                    @else
                        <a href="#" onclick="closeMe();return false" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i>&nbsp; {{ trans('multiple.m_back') }}</a>
                    @endif
                </div>
            </section>
        </div>
    </section>
@endsection