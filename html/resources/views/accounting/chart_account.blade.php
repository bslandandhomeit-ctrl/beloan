@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/client.css',false) }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',false) }}"/>
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            <span>{{ trans('sidebar.sb_chart_of_account') }}</span>
            <div class="pull-right">
            	<a href="{{route ('acc_add_account') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sidebar.sb_add_account') }}</a>
	            <a href="{{route ('chart_of_accounts_detail') }}" class="btn btn-success"><i class="glyphicon glyphicon-zoom-in"></i> {{ trans('account.chart_off_account_detail') }}</a>
              <a class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</a>
              <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
	        </div>
        </header>
        <div class="panel-body">
            <div id="printArea">
                @include('api.report_header',['co_phone'=>!empty($co_id->co_user) ? $co_id->co_user->phone: ''])
                <h4 class="sch_title" id="p-header">{{ trans('sidebar.sb_chart_of_account') }}</h4>
                <section id="unseen" style="clear: both">
                    <div id="divTab">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead class="cf" style="background: #ffffff;">
                            	<th style="text-align: center">{{ trans('account.a_gl_nbc_code') }}</th>
                                <th style="text-align: center" >{{ trans('account.a_gl_code') }}</th>
                                <th style="text-align: center">{{ trans('account.a_categories') }}</th>
                                <th class="na" style="text-align: center">{{ trans('account.a_remarks') }}</th>
                            </thead>
                            <tbody>
                            	<?php foreach ($account as $acc){
                            	    $acc_class = "acc". $acc->type; ?>
                            		<tr>
                            			<td align="left" class="{{$acc_class}}">{{ $acc->nbc_code }}</td>
										<td align="left" class="{{$acc_class}}">{{ $acc->account_code }}</td>
										<td align="left" class="{{$acc_class}}">{{ $acc->name }}</td>
										<td align="center" class="{{$acc_class}}">{{ $acc->description }}</td>
									</tr>
                            	<?php } //end type 1 ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('js/print.js',false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.floatThead.min.js',false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/xlsx.full.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
                // This must be a hyperlink
                $("#export").click(function (event) {
                    // var outputFile = 'export'
                    var con = confirm("Do you really want to export to CSV file?");
                    if(con == true){
                        new TableExport($("#divTab>table"), {
                                formats: ['csv'],
                                filename: 'chart_of_account'
                            });
                            $('button.csv').hide().click();
                            $('.tableexport-caption').remove();
                    }

                });

                $("#xexport").click(function (event) {
                    var con = confirm("Do you really want to export to Excel file?");
                    if(con == true){
                        new TableExport($("#divTab>table"), {
                                formats: ['xlsx'],
                                filename: 'chart_of_account'
                            }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                            $('button.xlsx').hide().click();
                            $('.tableexport-caption').remove();
                    }
                });

                $(".sticky-header").floatThead({scrollingTop:77});
        });
    </script>
@endsection
