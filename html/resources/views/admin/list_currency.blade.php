@extends('layouts.app')
@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',false) }}"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <span>{{ trans('sidebar.sb_currency_exchange_list') }}</span>
                    <div style="float:right">
                      <span><a href="{{route ('currency_exchange') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sidebar.sb_currency_exchange') }}</a></span>
                      <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                      <button class="btn btn-primary" id="export"><i class="fa fa-sign-out"></i> {{ trans('multiple.export') }}</button>
                    </div>
                </header>
                <div class="panel-body">
                    <br/><br/>
                    <section id="unseen" class="ox-scroll">
                      <div id="printArea">
                        @include('api.report_header')
                        <table class="table table-bordered table-striped table-condensed" id="list_cur">
                            <thead class="cf">
                                <tr>
                                    <th style="text-align: center">{{ trans('multiple.m_no') }}</th>
                                    <th style="text-align: center">{{ trans('currency.c_from_currency') }}</th>
                                    <th style="text-align: center">{{ trans('currency.c_to_currency') }}</th>
                                    <th style="text-align: center">{{ trans('report.rpt_amount') }}</th>
                                    <th style="text-align: center">{{ trans('currency.c_exchange_amount') }}</th>
                                    <th style="text-align: center">{{ trans('currency.c_gain_lose') }}</th>
                                    <th style="text-align: center">{{ trans('report.rpt_date') }}</th>
                                </tr>
                            </thead>
                            <?php $n = 1;?>
                            <tbody>
                                 @forelse($cur_re as $c)
                                    <tr>
                                         <td align="center">{{ $n }}</td>
                                         <td align="center">{{ $c->currency?$c->currency->name:'_' }}</td>
                                         <td align="center">{{ $c->currencies?$c->currencies->name:'_' }}</td>
                                         <td align="right">{{ number_format($c->amount,2,'.',',') }} {{ $c->symbol?$c->symbol->symbol:'_' }}</td>
                                         <td align="right">{{ number_format($c->exchange_amount,2,'.',',') }} {{ $c->symbols?$c->symbols->symbol:'_' }}</td>
                                         <td align="right">{{ number_format($c->gain_loss,2,'.',',') }} $</td>
                                         <td align="center">{{ $c->created_at }}</td>
                                     </tr>
                               <?php $n++; ?>
                               @empty
                               	<tr><td colspan=12>{{ trans('multiple.m_no_result')}}</td></tr>
                               @endforelse
                            </tbody>
                        </table>
                      </div>
                    </section>
                </div>
            </section>
        </div>
 	</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/print.js',false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',false)}}"></script>
<script>
  $("#export").click(function (event) {
      var con = confirm("Do you really want to export to CSV file?");
      if(con == true){
          new TableExport(document.getElementById('list_cur'), {
              formats: ['csv'],
              filename:"list_currency"
          });
          $('button.csv').hide().click();
          $('.tableexport-caption').remove();
      }
  });
</script>
@endsection
