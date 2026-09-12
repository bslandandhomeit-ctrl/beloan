@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',false) }}"/>
<style>
	@media print {
	   .hide-this{
	     display: none !important;
	   }
      a[href]:after {
      content: none !important;
      }
	}
</style>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <span>{{ trans('sidebar.sb_list_currencies') }}</span>
                    <div class="pull-right">
                        <span><a href="{{route ('add_currencie') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sidebar.sb_add_currency') }}</a></span>
                        <a class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</a>
                        <a class="btn btn-primary" id="export"><i class="fa fa-sign-out"></i> {{ trans('multiple.export') }}</a>
                    </div>
                </header>
                <div class="panel-body">
                    <section id="unseen" class="ox-scroll">
                      <div id="printArea">
                        @include('api.report_header')
                        <table class="table table-bordered table-striped table-condensed">
                            <thead class="cf">
                                <tr>
                                    <th>{{ trans('multiple.m_no') }}</th>
                                    <th>{{ trans('currency.c_set_currency_name') }}</th>
                                    <th>{{ trans('user.u_user_code') }}</th>
                                    <th>{{ trans('currency.c_symbol') }}</th>
                                    <th>{{ trans('currency.c_type') }}</th>
                                    <th>{{ trans('multiple.m_status') }}</th>
                                    <th style="text-align: center" class="hide-this">{{ trans('multiple.m_action') }}</th>
                                </tr>
                            </thead>
                            <?php $n = 1;?>
                            <tbody>
                                 @forelse($cur as $c)
                                    <tr>
                                         <td>{{ $c->id }}</td>
                                         <td>{{ $c->name }}</td>
                                         <td>{{ $c->code }}</td>
                                         <td>{{ $c->symbol }}</td>
                                         <td>{{ $c->type }}</td>
                                         <td>{{ $c->status? "Active":"Inactive" }}</td>
                                         <td align="center" class="hide-this">
                                             <a href="{{ route('edit_currencie',[$c->id]) }}" class="btn btn-default btn-xs" title="Edit"><i class="fa fa-pencil"></i></a>
                                             @if($c->status == 0)
                                                 <a href="{{route('enable_currency',[$c->id])}}" class="btn btn-default btn-xs" title="Activate"><i class="fa fa-check-circle"></i></a>
                                             @else
                                                 <a href="{{route('disable_currency',[$c->id])}}" class="btn btn-default btn-xs" title="Inactivate"><i class="fa fa-times-circle"></i></a>
                                             @endif
                                         </td>
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
          new TableExport(document.getElementsByTagName('table'), {
              formats: ['csv'],
              filename:"currency_summary",
              ignoreCols:6
          });
          $('button.csv').hide().click();
          $('.tableexport-caption').remove();
      }
      event.preventDefault();
  });
</script>
@endsection
