
@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
    <style>
    	@media print {
    	   .hide-this{
    	     display: none !important;
    	   }
    	}
    </style>
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('sidebar.sb_loan_repayment_draft') }}
            <div style="float:right;">
              <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
              <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>            </div>
        </header>
        <br/><br/>
        <div class="panel-body">
            <div class="col-md-10">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @elseif(session('msg_success'))
                     <div class="alert alert-success">
                        <ul>
                            <li>{{ session('msg_success') }}</li>
                        </ul>
                     </div>
                @endif
                @if(Session::has('msg'))
                    <div class="alert alert-danger fade in">
                        <button class="close close-sm" data-dismiss="alert">x</button>
                        {{ Session::get('msg') }}
                    </div>
                @endif
            </div>

            <section id="unseen">
                <?php $repayment_draft_type = config('static_data.repayment_draft_type');?>
              <div id="printArea">
                @include('api.report_header')
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed table-hover loan-list" id="repay">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ trans('loan.contract_id') }}</th>
                            <th>{{ trans('loan.disburse_date') }}</th>
                            <th>{{ trans('loan.amount') }}</th>
                            <th>{{ trans('loan.loan_amount') }}</th>
                            <th>{{ trans('loan.account') }}</th>
                            <th>{{ trans('multiple.m_note') }}</th>
                            <th>{{ trans('multiple.type') }}</th>
                            <th>{{ trans('loan.repayment_updated_at') }}</th>
                            <th>{{ trans('multiple.status') }}</th>
                            <th class="hide-this">{{ trans('multiple.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($drafts as $d)
                            <tr @if($d->status==2) class="bg-danger" @endif @if($d->status==1) class="bg-success" @endif>
                                <td>{{$d->id}}</td>
                                <td>{{$d->contract_id}}</td>
                                <td>{{$d->disburse_date}}</td>
                                <td>{{$d->amount}}</td>
                                <td>{{$d->loan_amount}}</td>
                                <td>@if($d->account_no) {{$d->account_name}} ({{$d->account_no}}) @endif</td>
                                <td>{{$d->note}}</td>
                                <td>@if($d->type==2) Pay off @else Repayment @endif</td>
                                <td>{{$d->updated_at}}</td>
                                <td>
                                    <?php
                                        if($d->audit[0]->audit_id){
                                            $tt = 'By '.display_name($d->audit[0]->audit_id).' at '.$d->audit[0]->updated_at;
                                        }else{
                                            $tt = 'By '.display_name($d->user_id).' at '.$d->updated_at;
                                        }
                                    ?>
                                    <a href="#" class="a-tooltip" data-toggle="tooltip" data-placement="top" title="<?php echo $tt?>">{{$repayment_draft_type[$d->status]}}</a>
                                </td>
                                <td class="hide-this">
                                    @if($d->type==2)
                                    <a href="{{ route('loan_payoff',[$d->loan_id])}}?draft_id={{$d->id}}" class="btn btn-xs btn-success">+</a>
                                    @else
                                    <a href="{{ route('add_loan_repayment',[$d->loan_id])}}?key={{$d->contract_id}}&draft_id={{$d->id}}" class="btn btn-xs btn-success">+</a>
                                    @endif
                                    <a href="{{ route('getRepaymentDraftReject',[$d->id])}}" class="btn btn-xs btn-danger">-</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="17">{{ trans('multiple.m_no_result') }}</td></tr>
                        @endforelse

                    </tbody>
                </table>
              </div>
                <div>
                    @include('partials.pagination',['results'=> $drafts])
                </div>
            </section>
        </div>
    </section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript">
        $(function () {
          $('[data-toggle="tooltip"]').tooltip();
        });
        $("#export").click(function (event) {
          var con = confirm("Do you really want to export to CSV file?");
          if(con == true){
              new TableExport(document.getElementById('repay'), {
                  formats: ['csv'],
                  filename:"repayment_draft"
              });
              $('button.csv').hide().click();
              $('.tableexport-caption').remove();
          }
      });

      $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('repay'), {
                    formats: ['xlsx'],
                    filename: 'repayment_draft'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });
    </script>
@endsection
