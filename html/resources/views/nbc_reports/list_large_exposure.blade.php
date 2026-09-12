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
                <span>{{ trans('sidebar.sb_list_large_exposure') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4><b>NATIONAL BANK OF CAMBODIA</b></h4><br />
                        <h5><b>LIST OF LARGE EXPOSURES</b></h5>
                        <p>(Loans to borrowers exceeding 5% of Institutions Net Worth)</p><br />

                        <p style="text-align: left;">INSTITUTION'S NAME: ORO FINANCECORP PLC</p><br />
                        <p style="text-align: left;">REPORT AS AT(DATE) FRI 03-MAR-2017</p>
                    </div>
                    <table class="table table-bordered table-striped table-condensed repayment-plan sticky-header">
                         <thead style="background-color: #ffffff">
                            <tr class="header">
                                <th colspan="3">BORROWERS</th>
                                <th rowspan="2">Number of loans</th>
                                <th rowspan="2">Amounts in Riels (Millions)</th>
                                <th rowspan="2">Other currencies translated into Riels</th>
                                <th rowspan="2">Total in millions of Riels</th>
                            </tr>
                            <tr class="head-1">
                                <th>No.</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            	<td>-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>1-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>2-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>3-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>4-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>5-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>6-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>7-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>8-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>9-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>10-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>-</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>Total</td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            	<td></td>
                            </tr>
                        </tbody>
                    </table>
                    <br /><br />
                    <p>Signature:</p><hr width="30%" align="left" /><br /><br />
                    <p>Date:</p><hr width="30%" align="left"/>
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