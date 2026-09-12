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
                <span>{{ trans('sidebar.sb_denominator') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <table class="table">
                         <thead style="background-color: #ffffff">
                            <tr class="header">
                                <th colspan="5"><b>DENOMINATOR : RISK-WEIGHTED ASSETS</b></th>
                            </tr>
                            <tr class="head-1">
                                <th></th>
                                <th></th>
                                <th>Amount in</th>
                                <th></th>
                                <th>Risk</th>
                            </tr>
                            <tr class="head-1">
                                <th></th>
                                <th></th>
                                <th>Millions of</th>
                                <th>Risk</th>
                                <th>Weighted</th>
                            </tr>
                            <tr class="head-1">
                                <th></th>
                                <th></th>
                                <th>Riels</th>
                                <th>Weighting</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr>
                                 <td colspan="5"><b>Zero weighting Asset</b></td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Cash</td>
                                 <td>3987.96</td>
                                 <td>0 %</td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Gold</td>
                                 <td>3987.96</td>
                                 <td>0 %</td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Claims on the NBC</td>
                                 <td>3987.96</td>
                                 <td>0 %</td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Assets collateralized by deposits lodged with the bank</td>
                                 <td>3987.96</td>
                                 <td>0 %</td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Claims on or guaranteed by sovereigns rated AAA to AA-</td>
                                 <td>3987.96</td>
                                 <td>0 %</td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td colspan="5"><b>20 percent weighting Asset</b></td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Claims on or guaranteed by sovereigns rated A+ to A-</td>
                                 <td>-</td>
                                 <td>20 %</td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Claims on or guaranteed by banks or corporations rated AAA+ to AA-</td>
                                 <td>-</td>
                                 <td>20 %</td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td colspan="5"><b>50 percent weighting Asset</b></td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Claims on or guaranteed by sovereigns rated BBB+ to BBB-</td>
                                 <td>-</td>
                                 <td>50 %</td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Claims on or guaranteed by banks or corporations rated A+ to A-</td>
                                 <td>-</td>
                                 <td>50 %</td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td colspan="5"><b>100 percent weighting Asset</b></td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>All other assets</td>
                                 <td>436.87</td>
                                 <td>100 %</td>
                                 <td>436.87</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>All off-balance sheet item</td>
                                 <td>-</td>
                                 <td>100 %</td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td colspan="4"><b>TOTAL</b></td>
                                 <td colspan="4"><b>437.00</b></td>
                             </tr>
                             <tr>
                                 <td colspan="4"><b>Total G : TOTAL RISK-WEIGHTED ASSETS</b></td>
                                 <td colspan="4"><b>437.00</b></td>
                             </tr>
                             <tr>
                                 <td colspan="4"><b>SOLVENCY RATIO = Total F / Total G</b></td>
                                 <td colspan="4"><b>1009.17 %</b></td>
                             </tr>
                             <tr><td colspan="5"></td></tr>
                        </tbody>
                    </table>
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