@extends('layouts.app')

@section('css')
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                <span>{{ trans('sidebar.sb_loan_breakdown_by_currency') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4><b>NATIONAL BANK OF CAMBODIA</b></h4>
                        <h4><b>LOAN BREAKDOWN BY CURRENCY</b></h4><br />

                        <p style="text-align: left;">INSTITUTION'S NAME: ORO FINANCECORP PLC</p><br />
                        <p style="text-align: left;">REPORT AS AT(DATE) FRI 03-MAR-2017 <span class="pull-right"><b>4008</b></span></p>
                        <p style="text-align: right;">In Million Riels</p>
                    </div>
                    <table class="table table-bordered table-striped table-condensed repayment-plan sticky-header">
                        <thead style="background-color: #ffffff">
                            <tr class="header">
                                <th>Currency</th>
                                <th>Number of Accounts</th>
                                <th>Amount Outstanding</th>
                                <th>INTEREST RATE CHARGED (indicate whether per month, per year or otherwise)</th>
                                <th>Interest Rate</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr>
                             	<td>1- KHR</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td></td>
                             	<td></td>
                             </tr>
                             <tr>
                             	<td>2- USD</td>
                             	<td>9</td>
                             	<td>216,500.00</td>
                             	<td>867.73</td>
                             	<td>0.87% - 1.5% / Month</td>
                             	<td></td>
                             </tr>
                             <tr>
                             	<td>3- THB</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td></td>
                             	<td></td>
                             </tr>
                             <tr>
                             	<td>4- OTHER CURRENCY</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td></td>
                             	<td></td>
                             </tr>
                             <tr>
                             	<td>5- TOTAL LOANS</td>
                             	<td>9</td>
                             	<td>216,500.00</td>
                             	<td>867.73</td>
                             	<td></td>
                             	<td></td>
                             </tr>
                        </tbody>
                	</table>
                	<br /><br /><p>Signature:</p><hr width="30%" align="left" /><br /><br />
                    <p>Date:</p><hr width="30%" align="left" />
            </div>
        </section>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/jquery-1.11.1.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/additional-methods.min',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
@endsection