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
                <span>{{ trans('sidebar.sb_off_balanch') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4><b>NATIONAL BANK OF CAMBODIA</b></h4>
                        <h4><b>OFF BALANCH SHEET NBC</b></h4><br />

                        <p style="text-align: left;">INSTITUTION'S NAME: ORO FINANCECORP PLC</p><br />
                        <p style="text-align: left;">REPORT AS AT(DATE) FRI 03-MAR-2017</p>
                        <p style="text-align: right;"><b>4,008.00</b></p>
                    </div>
                    <table class="table table-bordered table-striped table-condensed repayment-plan sticky-header">
                        <tbody>
                             <tr>
                             	<td></td>
                             	<td>Riels</td>
                             	<td>Other Currencies Translated into Riels</td>
                             	<td>Total in Million Riels</td>
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