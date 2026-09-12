@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <span>{{ trans('sidebar.sb_list_currency_rate') }}</span>
                    <span style="float: right"><a href="{{route ('set_currency_rate') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sidebar.sb_list_currency_rate') }}</a></span>
                </header>
                <div class="panel-body">
                    <section id="unseen" class="ox-scroll">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead class="cf">
                                <tr>
                                    <th style="text-align: center">{{ trans('multiple.m_no') }}</th>
                                    <th style="text-align: center">{{ trans('currency.c_currency') }}</th>
                                    <th style="text-align: center">{{ trans('currency.c_ask_rate') }}</th>
                                    <th style="text-align: center">{{ trans('currency.c_bid_rate') }}</th>
                                    <th style="text-align: center">{{ trans('currency.c_mid_rate') }}</th>
                                    <th style="text-align: center">{{ trans('product.p_date') }}</th>
                                </tr>
                            </thead>
                            <?php $n = 1;?>
                            <tbody>
                                 @forelse($cur_rate as $c)
                                    <tr>
                                         <td align="center">{{ $n }}</td>
                                         <td align="center">{{ $c->currency?$c->currency->name:'_' }}</td>
                                         <td align="center">{{ number_format($c->ask_rate,2,'.',',') }}</td>
                                         <td align="center">{{ number_format($c->bid_rate,2,'.',',') }}</td>
                                         <td align="center">{{ number_format($c->mid_rate,2,'.',',') }}</td>
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