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
                <span>{{ trans('sidebar.sb_loan_breakdown_by_category') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4><b>NATIONAL BANK OF CAMBODIA</b></h4>
                        <h4><b>LOAN BREAKDOWN BY CATEGORY</b></h4><br />

                        <p style="text-align: left;">INSTITUTION'S NAME: ORO FINANCECORP PLC</p><br />
                        <p style="text-align: left;">REPORT AS AT(DATE) FRI 03-MAR-2017 <span class="pull-right"><b>4,008.00</b></span></p>
                        <p style="text-align: right;">(Amounts in millions RIELS)</p>
                    </div>
                    <table class="table table-bordered table-striped table-condensed repayment-plan sticky-header">
                        <thead style="background-color: #ffffff">
                            <tr class="header">
                                <th>TYPE OF BUSINESS</th>
                                <th colspan="2">GROUP LOANS</th>
                                <th colspan="2">INDIVIDUAL LOANS</th>
                                <th colspan="2">SMALL BUSINESS</th>
                                <th colspan="2">TOTAL LOANS</th>
                            </tr>
                            <tr>
                            	<th></th>
                            	<th>Number of Accounts</th>
                            	<th>Amount</th>
                            	<th>Number of Accounts</th>
                            	<th>Amount</th>
                            	<th>Number of Accounts</th>
                            	<th>Amount</th>
                            	<th>Number of Accounts</th>
                            	<th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr>
                             	<td>Agriculture</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             </tr>
                             <tr>
                             	<td>Trade and Commerce</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             </tr>
                             <tr>
                             	<td>Services</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             </tr>
                             <tr>
                             	<td>Transportation</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             </tr>
                             <tr>
                             	<td>Construction</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             </tr>
                             <tr>
                             	<td>Household/Family</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             </tr>
                             <tr>
                             	<td>Other Categories</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             </tr>
                             <tr>
                             	<td>Total</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             </tr>
                             <tr>
                             	<td>INTEREST RATE CHARGED (MONTHLY)</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>0.87% - 1.5% / Month</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>-</td>
                             	<td>0.87% - 1.5% / Month</td>
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