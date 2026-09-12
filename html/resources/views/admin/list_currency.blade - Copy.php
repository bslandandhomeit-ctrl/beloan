@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <span>{{ trans('sidebar.sb_currency_exchange_list') }}</span>
                    <span style="float: right"><a href="{{route ('currency_exchange') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sidebar.sb_currency_exchange') }}</a></span>
                </header>
                <div class="panel-body">
                    <section id="unseen" class="ox-scroll">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead class="cf">
                                <tr>
                                    <th>{{ trans('multiple.m_no') }}</th>
                                    <th>{{ trans('currency.c_from_currency') }}</th>
                                    <th>{{ trans('currency.c_to_currency') }}</th>
                                    <th>{{ trans('report.rpt_amount') }}</th>
                                    <th>{{ trans('currency.c_exchange_amount') }}</th>
                                    <th>{{ trans('currency.c_gain_lose') }}</th>
                                    <th style="text-align: center">{{ trans('report.rpt_date') }}</th>
                                </tr>
                            </thead>
                            <?php $n = 1;dd($cur_re);?>
                            <tbody>
                                 @forelse($cur_re as $c)
                                    <tr>
                                         <td>{{ $n }}</td>
                                         <td>{{ $c->currency?$c->currency->name:'_' }}</td>
                                         <td>{{ $c->currencies?$c->currencies->name:'_' }}</td>
                                         <td>{{ number_format($c->amount,2,'.',',') }} {{ $c->symbol?$c->symbol->symbol:'_' }}</td>
                                         <td>{{ number_format($c->exchange_amount,2,'.',',') }} {{ $c->symbols?$c->symbols->symbol:'_' }}</td>
                                         <td>{{ number_format($c->gain_loss,2,'.',',') }}</td>
                                         <td align="center">{{ $c->created_at }}</td>
                                     </tr>
                               <?php $n++; ?>
                               @empty
                               	<tr><td colspan=12>{{ trans('multiple.m_no_result')}}</td></tr>
                               @endforelse
                            </tbody>
                        </table>
                    </section>
                </div>
            </section>
        </div>
 	</div>
@endsection