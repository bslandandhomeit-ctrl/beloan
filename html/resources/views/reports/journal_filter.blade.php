@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}" />
<style>
    @media print {
      a[href]:after {
      content: none !important;
      }
    }
</style>
@endsection

@section('content')
<section class="panel">
    <header class="panel-heading">
        {{ trans('sidebar.sb_journal_history') }}
        @if(isset($start) && isset($end))
        {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
        @else
        {{ isset($start)?'Journal entried on '.date("d-M-Y", strtotime($start)):'' }}
        {{ isset($end)?'Journal Entried on '.date("d-M-Y", strtotime($end)):'' }}
        @endif
    </header>

    <div class="panel-body">
        <form role="form" action="{{ route('filter_journal') }}" id="search_frm">
            <div class="row">
                <label class="control-label col-md-1">{{ trans('multiple.m_start_date') }}</label>
                <div class="col-md-2">
                    <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpStart">
                        <input type="text" name="dpStart" size="16" class="form-control" value="{{ isset($start)?$start:old('start') }}">
                        <span class="add-on birhtdateDatepicker ptl-2">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <label class="control-label col-md-1">{{ trans('multiple.m_end_date') }}</label>
                <div class="col-md-2">
                    <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpEnd">
                        <input type="text" name="dpEnd" size="16" class="form-control" value="{{ isset($end)?$end:old('end') }}">
                        <span class="add-on birhtdateDatepicker ptl-3">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>


   

                <label class="control-label col-md-2">Coa Category</label>
                <div class="col-md-4">
                    <select class="form-control" id="coa_category" name="coa_category">
                        <option value="">-</option>
                        @foreach($account as $b)
                        <option value="{{ $b->id }}"
                                @if(isset($coa_category))
                                @if($coa_category==$b->id)
                                selected
                                @endif
                                @endif>{{ $b->branch_code}}-{{$b->account_code}}-{{$b->name}}</option>
                        @endforeach
                    </select>
                </div>
     
                <label class="control-label col-md-1">{{ trans('account.branch_name') }}</label><br/>
                <div class="col-md-2">
                    <select class="form-control" id="selBrand" name="selBrand">
                        <option value="">-</option>
                        @foreach($branch as $b)
                        <option value="{{ $b->id }}"
                                @if(isset($branch_id))
                                @if($branch_id==$b->id)
                                selected
                                @endif
                                @endif>{{ $b->branch_name}}</option>
                        @endforeach
                    </select>
                </div>
                <label class="control-label col-md-1">{{ trans('report.rpt_note') }}</label>
                <div class="col-md-2">
                    <input type="text" name="note" class="form-control" value="{{ isset($description)?$description:'' }}" />
                </div>

                <label class="control-label col-md-1">{{ trans('report.rpt_invoice_number') }}</label>
                <div class="col-md-2">
                    <input type="text" name="env_no" class="form-control" value="{{ isset($env_no)?$env_no:'' }}" />
                </div>

                <div class="col-md-offset-9 col-md-3">
                    <input name="set_color" value="{{Input::get('set_color')}}" style="display: none;" />
                    <input type="hidden" name="offset" />
                    <input type="hidden" name="status" id="status"/>
                    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                    <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                    <button class="btn btn-primary" id="export"><i class="fa fa-sign-out"></i> {{ trans('multiple.export') }}</button>
                </div>
            </div><br/>
        </form>
        <div class="page">
            <div class="custom-pagi">
                <span class="pagi_label">Number of Rows:</span>
                <input type="text" class="form-control" name="set_offset" id="set_offset" value="<?php echo $offset ?>" />
                <a href="#" class="btn btn-danger">Go</a>
            </div>
        </div>
        <br/><br/>
        <div id="printArea" style="clear: both">
            @include('api.report_header')
            <h4 class="sch_title">
                {{ trans('sidebar.sb_journal_history') }}
                @if(isset($start) && isset($end))
                {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
                @else
                {{ isset($start)?'Journal entried on '.date("d-M-Y", strtotime($start)):'' }}
                {{ isset($end)?'Journal Entried on '.date("d-M-Y", strtotime($end)):'' }}
                @endif
            </h4>
            <section id="unseen">
                <table  class="table table-bordered table-striped table-condensed journal">
                    <thead>
                    <th style="text-align: center;">{{ trans('report.rpt_entry_id') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_invoice_number') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_receipt') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_office') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_transaction_date') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_transaction_id') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_created_by') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_name_explain') }}</th>
                    <th style="text-align: center;">{{ trans('account.ref_id') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_code') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_debit') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_credit') }}</th>
                    <th style="text-align: center;">{{ trans('multiple.m_note') }}</th>
                    <th style="text-align: center;">{{ trans('multiple.action') }}</th>
                    <th style="text-align: center;">{{ trans('multiple.audit') }}</th>
                    </thead>
                    <tbody>
                        @forelse($jrs as $jr)
                        @var $i = 0
                        @if(!empty($jr->detail))
                        @var $c = count($jr->detail)
                        @foreach($jr->detail as $d)
                        <?PHP
                        if(!is_null($status) && $status == 0 && $d->is_audit != 0) continue;
                        $set_color = '';
                        if((int)$jr->id == (int)Input::get('set_color')){
                        $set_color = '#F6FF33';
                        }

                        ?>
                        <tr style="text-align:center; background: {{$set_color}}">
                            @if($i == 0)
                            <td rowspan="{{ $c+1 }}">{{ $jr->id }}</td>
                            <td rowspan="{{ $c+1 }}">{{ $jr->invoice_number or 'N/A' }}</td>
                            <td rowspan="{{ $c+1 }}">
                                @if($jr->receipt)
                                    <a href="{{ asset('data/loans/receipts/'.$jr->receipt, isset($secure)?false:false)}}" target="_blank"><i class="fa fa-file"></i></a>
                                @else
                                    N/A
                                @endif
                            </td>
                            @if(!empty($jr->transaction))
                            <td rowspan="{{ $c+1 }}">{{ $jr->transaction->loan->branch->branch_name }}</td>
                            <td rowspan="{{ $c+1 }}">{{ $jr->entry_date}}</td>
                            <td rowspan="{{ $c+1 }}">{{ $jr->tran_id }}</td>
                            @elseif(!empty($jr->detail))
                            <td rowspan="{{ $c+1 }}">{{ $jr->detail[0]->branch->branch_name }}</td>
                            <td rowspan="{{ $c+1 }}">{{ $jr->entry_date}}</td>
                            <td rowspan="{{ $c+1 }}">{{ ($jr->tran_id==0)? "N/A":$jr->tran_id }}</td>
                            @else
                            <td rowspan="{{ $c+1 }}">N/A</td>
                            <td rowspan="{{ $c+1 }}">N/A</td>
                            <td rowspan="{{ $c+1 }}">N/A</td>
                            @endif
                            <td rowspan="{{ $c+1 }}">{{ $jr->user->name }}</td>
                            @endif
                            <td style="text-align:left">{{ $d->account ? $d->account->name."\n"."(".$d->account->account_code.")" : 'N/A' }}</td>
                            <td>{{ $d ? $d->reference : 'N/A' }}</td>
                            <td>{{ $currency_arr[$d->account->currency] }}</td>
                            <td style="text-align:right">{{ $d->debit!=0?number_format($d->debit,2,'.',','):'' }}</td>
                            <td style="text-align:right">{{ $d->credit!=0?number_format($d->credit,2,'.',','):'' }}</td>
                            <td style="text-align:left">{{ $d->description ? $d->description : '-' }}</td>
                            <td style="text-align:left">
                                @if($d->debit!=0)
                                        <a target="_blank" href="{{route('print_debit',$jr->id)}}"> <span id="debit" class="glyphicon glyphicon-print"> </span> </a>
                                @endif
                                @if($d->credit!=0)
                                        <a target="_blank" href="{{route('print_credit', $jr->id)}}"><span id="debit" class="glyphicon glyphicon-print"></span></a>
                                @endif
                                @if($jr->is_audit!=1)
                                    &nbsp;&nbsp;
                                    <a href="{{route('edit_journal', [$jr->id])}}" class="" title="Edit"><i class="fa fa-pencil"></i></a>
                                @endif
                            </td>
                            <td style="text-align:left">
                                @if($d->is_audit == 0)
                                    <a href="{{route('get_audit')}}?tbl=journal_requiry&id={{$jr->id}}&action=1&user_id={{$jr->user_id}}"><i class="glyphicon glyphicon-ok"></i></a>&nbsp;&nbsp;&nbsp;
                                    <a href="{{route('get_audit')}}?tbl=journal_requiry&id={{$jr->id}}&action=0&user_id={{$jr->user_id}}"><i class="glyphicon glyphicon-remove"></i></a>
                                @else
                                    <?php
                                        if($jr->audit[0]->audit_id){
                                            $tt = 'By '.display_name($jr->audit[0]->audit_id).' at '.$jr->audit[0]->updated_at;
                                        }else{
                                            $tt = 'By '.display_name($jr->user_id).' at '.$jr->updated_at;
                                        }
                                    ?>
                                    <a href="#" class="a-tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $tt?>">{{ trans('multiple.approved') }}</a>
                                @endif
                            </td>

                        </tr>
                        @if($c == ($i+1))
                        <tr>
                            <td colspan=7><i><strong>Note: </strong>{{ $jr->description? $jr->description : 'NA' }}</i></td>
                        </tr>
                        @endif
                        @var $i+=1
                        @endforeach
                        @endif
                        @empty
                        <tr><td colspan=14>{{ trans('multiple.m_no_result') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="page">

                </div>
            </section>
        </div>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>

<script type="text/javascript">
    $("#export").click(function (event) {
      var con = confirm("Do you really want to export to CSV file?");
      if(con == true){
          new TableExport(document.getElementsByTagName('table'), {
              formats: ['csv'],
              filename:"journals"
          });
          $('button.csv').hide().click();
          $('.tableexport-caption').remove();
      }
      event.preventDefault();
    });
  $(document).ready(function () {
    $("#coa_category").select2();
      var status = <?php echo isset($status) ? $status:2;?>;
      $('#status').val(status)
      $('.dpStart').datepicker({
          format: 'yyyy-mm-dd',
          autoclose: true,
          setDate: new Date()
      });
      $('.dpEnd').datepicker({
          format: 'yyyy-mm-dd',
          autoclose: true,
          setDate: new Date()
      });
      $('.custom-pagi a').on('click', function () {
          val = $(this).parent().find('input[name="set_offset"]').val();
          $('input[name="offset"]').val(val);
          $('#search_frm').submit();
          return false;
      });
  });
  $("#selTran").select2();

  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
@endsection
