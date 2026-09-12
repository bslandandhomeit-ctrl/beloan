@extends('layouts.app')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
<style>
    .loading{
        position: absolute;
        left: 100%;
        top: 20px;
        display: block;
        width: 40px;
        height: 40px;
        background: transparent url("{{ asset('images/loading.gif', isset($secure)?false:false) }}") no-repeat scroll center center / contain;
    }
    @media print {
      a[href]:after {
        content: none !important;
      }
    }
</style>
@endsection
@section('content')
<section class="panel">
    <header class="panel-heading header-title">
        @if(isset($date_search))
        {{ trans('loan.l_schedule_for') }} {{ date('Y-m',strtotime($date_search)) }}
        @endif
    </header>
    <div class="panel-body">
        <div >
            <form role="form" class="cmxform" method="get" action="{{ route('repayment_summary') }}" id="search_frm">
                <input type="hidden" id="url" value="{{ route('repayment_summary') }}">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="Contract ID" class="control-label">{{ trans('report.rpt_contract_id') }}</label>
                           
                                <input type="text" id="contract_id" name="contract_id" class="form-control">
                            
                        </div>
                        <div class="form-group">
                            <label for="Client Name" class="control-label">{{ trans('customer.cus_customer_name') }}</label>
                            
                                <input type="text" id="client_name" name="client_name" class="form-control">
                            
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ trans('loan.l_monitor_date') }}</label>
                            
                                <div data-date-viewmode="months" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpStart">
                                    <input type="text" name="date"  id="date" size="16" class="form-control" value="{{ date('Y-m') }}" placeholder="{{date('Y-m')}}">
                                    <span class="add-on birhtdateDatepicker">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            
                        </div>

                        <div class="form-group">
                            <div class="col-lg-offset-3 col-lg-7">
                                <span class="loading" id="loading"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="Phone" class="control-label">{{ trans('multiple.m_phone',['num'=>'']) }}</label>
                            
                                <input type="text" id="phone" name="phone" class="form-control" data-mask="999-999-999?9">
                            
                        </div>
                        <div class="form-group">
                            <label for="Address" class="control-label">{{ trans('customer.cus_city_province') }}</label>
                            
                                <select class="form-control" name="city" id="address">
                                    <option value="">-</option>
                                    <?php $branch = config('static_data.branch'); ?>
                                    @foreach($branch as $key => $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                           
                        </div>
                        <div class="form-group">
                                <input type="hidden" name="offset" value="{{$offset}}" />
                                <button type="button" id="schedule-loan" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                                <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                                <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                                <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
                            
                        </div>
                    
                </div>
            </form>
        </div>
        <div style="float:right;">
            <div class="custom-pagi">
                <span class="pagi_label">Number of Rows:</span>
                <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                <a href="#" class="btn btn-danger">Go</a>
            </div>
        </div>
        <br>
        <section>
            <div id="printArea">
              @include('api.report_header')
              <br /><br />
              <h4 class="sch_title" id="page_header">Schedule Monitor</h4>
              @include('partials.detail_summary_schedule',['loans'=>$loans])
            </div>
        </section>
    </div>
</section>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.floatThead.min.js',isset($secure) ? false : false)}}"></script>
<script src="{{ asset('js/schedule-monitor.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>

<script type="text/javascript">
$('#report_header_kh').hide();
$('.custom-pagi a').on('click', function () {
    val = $(this).parent().find('input[name="set_offset"]').val();
    $('input[name="offset"]').val(val);
    $('#search_frm').submit();
    return false;
});

$(document).ready(function(){
  $(".sticky-header").floatThead({scrollingTop:77});
  $('.dpStart').datepicker({
      format: 'yyyy-mm',
      viewMode: 'months',
      minViewMode: "months",
      autoclose: true
  });

  $('.custom-pagi a').on('click', function () {
      val = $(this).parent().find('input[name="set_offset"]').val();
      $('input[name="offset"]').val(val);
      $('#search_frm').submit();
      return false;
  });
});
  $("#export").click(function (event) {
      var con = confirm("Do you really want to export to CSV file?");
      if(con == true){
          new TableExport(document.getElementById('editable-sample'), {
              formats: ['csv'],
               filename: 'schedule_monitor'
          });
          $('button.csv').hide().click();
          $('.tableexport-caption').remove();
      }
  });

  $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('editable-sample'), {
                    formats: ['xlsx'],
                    filename: 'schedule_monitor'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });

</script>
@endsection
