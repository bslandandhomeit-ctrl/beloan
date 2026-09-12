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
                <span>{{ trans('sidebar.sb_loan_classification') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4><b>NATIONAL BANK OF CAMBODIA</b></h4>
                        <h4><b>LOAN CLASSIFICATION, PROVISIONING AND DELINQUENCY RATIO</b></h4><br />

                        <p style="text-align: left;">INSTITUTION'S NAME: ORO FINANCECORP PLC</p><br />
                        <p style="text-align: left;">REPORT AS AT(DATE) FRI 03-MAR-2017 <span class="pull-right"><b>4008.00</b></span></p>
                        <p style="text-align: left; margin-left: 73em;">(Amounts in millions RIELS)</p>
                    </div>
                    <table class="table table-bordered table-striped table-condensed repayment-plan sticky-header">
                         <thead style="background-color: #ffffff">
                            <tr class="header">
                                <th rowspan="2">CLASSIFICATION</th>
                                <th rowspan="2">NUMBER OF LOANS</th>
                                <th rowspan="2">AMOUNT OUTSTANDING</th>
                                <th rowspan="2">ACCRUED INTEREST</th>
                                <th colspan="3">PROVISIONS</th>
                            </tr>
                            <tr class="head-1">
                                <th colspan="2">REQUIRED</th>
                                <th>ACTUAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            	<td>1- LOANS OF ONE YEAR OR LESS</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>1.1 STANDARD</td>
                            	<td>4</td>
                            	<td>0.10</td>
                            	<td>-</td>
                            	<td>0%</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>1.2 SUB-STANDARD PAST DUE > 30 DAYS</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>10 %</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>1.3 DOUBTFUL PAST DUE > 60 DAYS</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>30%</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>1.4 LOSS PAST DUE > 90 DAYS</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>100%</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>SUB-TOTAL 1</td>
                            	<td>4</td>
                            	<td>0.10</td>
                            	<td>-</td>
                            	<td></td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>2- LOANS OF MORE THAN ONE YEAR</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td></td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>2.1 STANDARD</td>
                            	<td>5</td>
                            	<td>0.12</td>
                            	<td>0%</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>2.2 LOSS PAST DUE > 30 DAYS</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>10%</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>2.3 DOUBTFUL PAST DUE > 180 DAYS</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>30%</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>2.4 LOSS PAST DUE > 360 DAYS</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>100%</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>SUB-TOTAL 2</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td></td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>TOTAL</td>
                            	<td>9</td>
                            	<td>0.22</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td colspan="7">-</td>
                            </tr>
                            <tr>
                            	<td rowspan="2">DELIQUENCY RATIO=</td>
                            	<td colspan="2">ALL LOANS PAST DUE > 30 DAYS</td>
                            	<td>-</td>
                            	<td></td>
                            	<td rowspan="2">0.00 %</td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td colspan="2">TOTAL LOANS OUTSTANDING</td>
                            	<td>0.22</td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td colspan="7">-</td>
                            </tr>
                            <tr>
                            	<td>LOW WRITE OFFS:</td>
                            	<td>CURRENT PERIOD</td>
                            	<td>-</td>
                            	<td colspan="2">YEAR TO DATE</td>
                            	<td colspan="2">-</td>
                            </tr>
                        </tbody>
                    </table>
                    <br /><br /><p>Signature: <span style="margin-left: 45em;">Date:</span></p><hr />
                </div>
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