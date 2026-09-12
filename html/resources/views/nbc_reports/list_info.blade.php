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
                <span>{{ trans('sidebar.sb_list_info') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4>តារាងព័តមានបណ្តាញប្រតិបិត្តការ</h4><br />
                        <h4>ខែ មិនា ឆ្នាំ​ ២០១៧</h4><br />
                        <p style="text-align: left;">INSTITUTION'S NAME: ORO FINANCECORP PLC</p><br />
                    </div>
                    <table class="table table-bordered table-striped table-condensed repayment-plan sticky-header">
                         <thead style="background-color: #ffffff">
                            <tr class="header">
                                <th>ឈោ្មះសាខា</th>
                                <th>ឈោ្មះអនុសាខា</th>
                                <th>ឈោ្មះបុស្តិសេវា</th>
                                <th>ថែ្ង ខែ​ ឆ្នាំបងើ្កត</th>
                                <th>អាជ្ញាបណ្ណ MDI</th>
                                <th>ចំនួនអតិថិជន</th>
                                <th>សមតុល្យ</th>
                                <th>អាសយដ្ឋាន</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            	<td>Battabang Branch</td>
                            	<td></td>
                            	<td></td>
                            	<td>Wed 22-Jun-2016</td>
                            	<td></td>
                            	<td>0</td>
                            	<td>0</td>
                            	<td>No.136, Street No 3, Group 38, Phum 20 Ousaphea,Sangkat Svaypor, Krong Battambang, Battambang.</td>
                            </tr>
                            <tr>
                            	<td>Head Office Branch</td>
                            	<td></td>
                            	<td></td>
                            	<td>Wed 22-Jun-2016</td>
                            	<td></td>
                            	<td>9</td>
                            	<td>$ 216,500.00</td>
                            	<td>No.147, Monireth Blvd, Sangkat Boeung Salang, Khan Toul Kork, Phnom Penh, Cambodia.</td>
                            </tr>
                            <tr>
                            	<td>Seim Reap Branch</td>
                            	<td></td>
                            	<td></td>
                            	<td>Wed 22-Jun-2016</td>
                            	<td></td>
                            	<td>0</td>
                            	<td>0</td>
                            	<td>No.#0008, Group#2,Watbo Village, Salakamroek Commune,Siem Reap, Cambodia.</td>
                            </tr>
                        </tbody>
                    </table><br /><br />
                     <p style="text-align: left;">Signature:<span style="margin-left: 65em;">Date:</span></p><hr />
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