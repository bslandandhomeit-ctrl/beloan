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
                <span>{{ trans('sidebar.sb_deposit_breakdown') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4><b>NATIONAL BANK OF CAMBODIA</b></h4>
                        <h4><b>DEPOSIT BREAKDOWN BY CURRENCY</b></h4><br />

                        <p style="text-align: left;">INSTITUTION'S NAME: ORO FINANCECORP PLC</p><br />
                        <p style="text-align: left;">REPORT AS AT(DATE) FRI 03-MAR-2017</p><br />
                    </div>
                    <table class="table table-bordered table-striped table-condensed repayment-plan sticky-header">
                        <thead style="background-color: #ffffff">
                            <tr class="header">
                                <th>Currency</th>
                                <th>Number of Accounts</th>
                                <th>Total Deposits</th>
                                <th>Interest Rate</th>
                                <th>Other</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr>
                             	<td colspan="5">-</td>
                             </tr>
                             <tr>
                             	<td>-</td>
                             	<td></td>
                             	<td></td>
                             	<td></td>
                             	<td></td>
                             </tr>
                             <tr>
                             	<td>1- KHR</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td></td>
                             	<td></td>
                             </tr>
                             <tr>
                             	<td>2- USD</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td></td>
                             	<td></td>
                             </tr>
                             <tr>
                             	<td>3- THB</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td></td>
                             	<td></td>
                             </tr>
                             <tr>
                             	<td>4-OTHER CURRENCY</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td></td>
                             	<td></td>
                             </tr>
                             <tr>
                             	<td><b>5- TOTAL DEPOSIT</b></td>
                             	<td>-</td>
                             	<td>-</td>
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