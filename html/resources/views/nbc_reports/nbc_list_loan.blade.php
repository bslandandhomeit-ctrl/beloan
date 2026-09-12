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
                <span>{{ trans('sidebar.sb_nbc_list_loan') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4><b>ORO Financecorp Plc</b></h4>
                        <h4><b>LIST OF LOANS TO INSIDERS AND RELATED PARIES</b></h4><br />

                        <p style="text-align: left;">Institution's Name: ORO Financecorp Plc</p><br />
                        <p style="text-align: left;">Report as at(Date) Fri 03-Mar-2017</p>
                        <p style="text-align: right;">(Amounts in millions RIELS)</p>
                    </div>
                    <table class="table table-bordered table-striped table-condensed repayment-plan sticky-header">
                         <thead style="background-color: #ffffff">
                            <tr class="header">
                                <th>BORROWERS</th>
                                <th>Number of loans</th>
                                <th>Amounts in Riels (millions)</th>
                                <th>Other currencies translated</th>
                                <th>Total in millions of Riels</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            	<td>1- Shareholders</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>1.1 of which individuals</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>1.2 of which corporations</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>1.3 of which others</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>2. Managers</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>3. Employees</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>4. External Auditors</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            </tr>
                            <tr>
                            	<td>TOTAL</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
                            	<td>-</td>
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