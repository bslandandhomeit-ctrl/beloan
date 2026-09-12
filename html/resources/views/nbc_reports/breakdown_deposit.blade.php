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
                <span>{{ trans('sidebar.sb_breakdown_deposit') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4><b>NATIONAL BANK OF CAMBODIA</b></h4><br />
                        <h4><b>BREAK-DOWN OF DEPOSITS</b></h4><br />

                        <p style="text-align: left;">INSTITUTION'S NAME: ORO FINANCECORP PLC</p><br />
                        <p style="text-align: left;">REPORT AS AT(DATE) FRI 03-MAR-2017</p><br />
                        <p style="text-align: right;">(Amounts in millions RIELS)</p>
                    </div>
                    <table class="table table-bordered table-striped table-condensed repayment-plan sticky-header">
                        <thead style="background-color: #ffffff">
                            <tr class="header">
                                <th rowspan="2">Category</th>
                                <th rowspan="2">Interest Rate Paid/Frequency</th>
                                <th colspan="2">Less Than 250,000 Riels</th>
                                <th colspan="2">250,000 to 1,000,000 Riels</th>
                                <th colspan="2">More Than 1,000,000 Riels</th>
                                <th colspan="2">Total</th>
                            </tr>
                            <tr class="head-1">
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
                             	<td>1-Voluntary</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             </tr>
                             <tr>
                             	<td>1-1-Demand</td>
                             	<td>-</td>
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
                             	<td>1-2-Savings</td>
                             	<td>-</td>
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
                             	<td>1-3-Term</td>
                             	<td>-</td>
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
                             	<td>1-4- Other</td>
                             	<td>-</td>
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
                             	<td>1-5- Total Reservable Deposits</td>
                             	<td>-</td>
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
                             	<td>2-Compulsory</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             	<td>0</td>
                             </tr>
                             <tr>
                             	<td>2-1-Program 1</td>
                             	<td>-</td>
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
                             	<td>2-2-Program 2</td>
                             	<td>-</td>
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
                             	<td>2-3-Program 3</td>
                             	<td>-</td>
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
                             	<td>2-4-Total Compulsory Savings</td>
                             	<td>-</td>
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
                             	<td>-</td>
                             </tr>
                        </tbody>
                	</table>
                	<br /><br />
                	<p>TOTAL RESERVABLE DEPOSITS <span style="margin-left: 40em;">5% RESERVE REQUIREMENT</span></p>
                    <br /><br />
                    <p>Signature: <span style="margin-left: 50em;">Date:</span></p>
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