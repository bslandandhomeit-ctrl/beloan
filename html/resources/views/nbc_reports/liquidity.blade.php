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
                <span>{{ trans('sidebar.sb_liquidity') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4>NATIONAL BANK OF CAMBODIA</h4>
                        <h4>LIQUIDITY RATIO FOR MICROFINANCE INSTITUTIONS</h4><br />

                        <p style="text-align: left;">Institution's Name: ORO Financecorp Plc</p><br />
                        <p style="text-align: left;">Report as at(Date) Fri 03-Mar-2017</p>
                        <p style="text-align: right;"><b>4008</b></p>
                    </div>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td rowspan="2">PRUDENTIAL REQUIREMENT:</td>
                                <td colspan="3">LIQUID ASSETS</td>
                                <td rowspan="2"> OR > 100%</td>
                            </tr>
                            <tr>
                            	<td colspan="3">ADJUSTED AMOUNT OF DEPOSITS</td>
                            </tr>
                            <tr>
                            	<td colspan="5">-</td>
                            </tr>
                            <tr>
                            	<td colspan="5">-</td>
                            </tr>
                            <tr>
                            	<td colspan="5" style="text-align: right;">(Amounts in millions RIELS)</td>
                            </tr>
                            <tr>
                            	<td colspan="2">1- NUMERATOR: LIQUID ASSETS</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td></td>
                            	<td colspan="3">-</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>CASH IN HAND</td>
                            	<td colspan="3">3987.96</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>DEPOSIT WITH NBC</td>
                            	<td colspan="3">-</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>DEPOSIT WITH BANKS</td>
                            	<td colspan="3">396.79</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>SUB-TOTAL A</td>
                            	<td colspan="3">4384.75</td>
                            </tr>
                            <tr>
                            	<td colspan="2">MINUS</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>AMOUNTS OWED TO NBC*</td>
                            	<td colspan="3">-</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>AMOUNTS OWED TO BANKS*</td>
                            	<td colspan="3">-</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>SUB-TOTAL B</td>
                            	<td colspan="3">-</td>
                            </tr>
                            <tr>
                            	<td colspan="2">PLUS</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>PORTION OF LOANS MATURING</td>
                            	<td colspan="3">-</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>IN LESS THAN ONE MONTH</td>
                            	<td colspan="3">-</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>LIQUID ASSETS</td>
                            	<td colspan="3">4384.75</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td></td>
                            	<td colspan="3">-</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td></td>
                            	<td colspan="3">-</td>
                            </tr>
                            <tr>
                            	<td colspan="2">2-DENOMINATOR: ADJUSTED AMOUNT OF DEPOSITS</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td colspan="2">CATEGORY OF DEPOSITS</td>
                            	<td>0</td>
                            	<td>%</td>
                            	<td>ADJUSTED AMOUNT IN MILLIONS OF RIELS</td>
                            </tr>
                            <tr>
                            	<td colspan="2">VOLUNTARY SAVINGS</td>
                            	<td>0</td>
                            	<td>25</td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td colspan="2">-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td colspan="2">3- LIQUIDITY RATION</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td colspan="5">-</td>
                            </tr>
                            <tr>
                            	<td colspan="2">LIQUID ASSETS</td>
                            	<td rowspan="2">=</td>
                            	<td rowspan="2">4384.75<br /><hr />0.00</td>
                            	<td rowspan="2">0<span style="margin-left: 10em;">0.00 %</span></td>
                            </tr>
                            <tr>
                            	<td colspan="2">ADJUSTED AMOUNT OF DEPOSITS</td>
                            </tr>
                        </tbody>
                    </table>
                    <br /><br />
                    <p>Signature:</p><hr width="30%" align="left" /><br /><br />
                    <p>Date:</p><hr width="30%" align="left" />
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