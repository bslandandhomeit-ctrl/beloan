<?php
    function setAction($type,$relation)
    {
        $action = '';
        switch($type){
            case 1: $action = '';break; // company branch
            case 2: $action = route('user_detail',[$relation]);break; // user
            case 3: $action = '';break; // role
            case 4: $action = route('client_detail',[$relation]);break; // client
            case 5: $action = route('dealer_detail',[$relation]);break; // dealer
            case 6: $action = route('loan_detail',[$relation]);break; // loan
            case 7: $action = '';break; // product
            default:
            break;
        }
        return $action;
    }
?>
@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
    <style>
        @media print {
          a[href]:after {
          content: none !important;
          }
          .hide-this{
      	     display: none !important;
      	   }
        }
    </style>

@endsection

@section('content')
<section class="panel">
    <header class="panel-heading">
         {{ trans('company.com_user_activity') }}
    </header>
    <div class="panel-body">
        <form class="form-horizontal" action="{{ route('user_activity') }}">
            <div class="form-group">
                <div class="col-md-3">
                    <label class="control-label">{{ trans('multiple.m_start_date') }}</label>
                    <div id="start-date" data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd" data-date="{{ $start }}" class="input-append date" style="width: 95%;">
                        <input type="text" name="start" size="16" class="form-control" value="{{ $start }}"/>
                            <span class="input-group-btn add-on">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                          </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="control-label">{{ trans('multiple.m_end_date') }}</label>
                    <div id="end-date" data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd" class="input-append date" style="width: 95%;">
                        <input type="text" name="end" size="16" class="form-control" value="{{ $end }}"/>
                            <span class="input-group-btn add-on">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                          </span>
                    </div>
                </div>
                 <div class="col-md-3">
                    <label class="control-label">{{ trans('user.u_user_by') }}</label>
                    <input type="text" name="by" class="form-control" value="{{ $by }}"/>
                </div>
                <div class="col-md-3">
                     <label class="control-label"> &nbsp;</label>
                     <div>
                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                        <a class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</a>
                        <a class="btn btn-primary" id="export"><i class="fa fa-sign-out"></i> {{ trans('multiple.export') }}</a>
                     </div>

                </div>
            </div>
        </form>
        <div class="ox-scroll">
          <div id="printArea">
            @include('api.report_header')
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="text-align: center;">{{ trans('user.u_user_by') }}</th>
                        <th style="text-align: center;">{{ trans('company.com_activity') }}</th>
                        <th style="text-align: center;">{{ trans('report.rpt_date') }}</th>
                        <th style="text-align: center;">{{ trans('multiple.m_note') }}</th>
                        <th style="text-align: center;" class="hide-this">{{ trans('multiple.m_action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($activity) && count($activity))
                       @foreach($activity as $act)
                            <tr>
                                <td>{{ $act->name }}</td>
                                <td>{{ $act->activity }}</td>
                                <td>{{ date('F j, Y, g:i A',strtotime($act->activity_date)) }}</td>
                                <td>{{ !empty($act->note) ? $act->note : '-' }}</td>
                                <td align="center" class="hide-this">
                                    <?php
                                        $action = setAction($act->relation_type,$act->relation_id);
                                        if(!empty($action)){
                                            echo '<a href="'.$action.'"><i class="fa  fa-arrow-circle-right" style="font-size:15px;"></i></a>';
                                        }
                                    ?>
                                </td>
                            </tr>
                       @endforeach
                    @else
                       <tr><td colspan="5">{{ trans('multiple.m_no_result') }}</td></tr>
                    @endif
                </tbody>
            </table>
          </div>
             @if(!empty($activity) && count($activity) > 0)
                @include('partials.pagination',['results'=> $activity])
             @endif
         </div>
    </div>
</section>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript">
    $("#export").click(function (event) {
      var con = confirm("Do you really want to export to CSV file?");
      if(con == true){
          new TableExport(document.getElementsByTagName('table'), {
              formats: ['csv'],
              filename:"user_activity"
          });
          $('button.csv').hide().click();
          $('.tableexport-caption').remove();
      }
      event.preventDefault();
      });

        $(document).ready(function(){
            $('#start-date').datepicker({
               autoclose: true
            });
            $('#end-date').datepicker({
              autoclose: true
            });
        });
    </script>
@endsection
