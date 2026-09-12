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
                <span>{{ trans('sidebar.sb_ngos_microfinance') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4>NATIONAL BANK OF CAMBODIA</h4>
                        <h4>NGOS/ MICRO-FINANCE INSTITUTIONS NETWORK INFORMATION</h4><br />

                        <p style="text-align: left;">INSTITUTION'S NAME: ORO FINANCECORP PLC</p><br />
                        <p style="text-align: left;">REPORT AS AT(DATE) FRI 03-MAR-2017</p><br />
                    </div>
                    <table class="table table-bordered table-striped table-condensed repayment-plan sticky-header">
                         <thead style="background-color: #ffffff">
                            <tr class="header">
                                <th rowspan="3">PROVINCE</th>
                                <th colspan="3">NUMBER OF</th>
                                <th colspan="4">LOANS OUTSTANDING</th>
                                <th colspan="4">DEPOSIT BALANCES</th>
                                <th colspan="3" rowspan="2">NUMBER OF EMPLOYEES</th>
                            </tr>
                            <tr class="head-1">
                                <th rowspan="2">DISTRICTS</th>
                                <th rowspan="2">COMMUNES</th>
                                <th rowspan="2">VILLAGES</th>
                                <th rowspan="2">AMOUNT</th>
                                <th colspan="3">NUMBER OF BORROWERS</th>
                                <th rowspan="2">AMOUNT</th>
                                <th colspan="3">NUMBER OF DEPOSITORS</th>
                            </tr>
                            <tr class="head-1">
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Banteay Meanchey</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Battambang</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Kampong Cham</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Kampong Chhnang</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Kampong Speu</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Kampong Thom</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Kampot</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Kandal</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Koh Kong</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Kracheh</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Mondul Kiri</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Phnom Penh</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Preah Vihear</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Prey Veng</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Pursat</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Ratanak Kiri</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Siem Reap</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Preah Sihanouk</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Stung Treng</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Svay Rieng</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Takeo</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Otdar Meanchey</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Kep</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr><tr>
                                <td>Pailin</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Tboung Khmum</td>
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
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
                                <td>1</td>
                                <td>2</td>
                                <td>2</td>
                                <td>280.56</td>
                                <td>1</td>
                                <td>1</td>
                                <td>2</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
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