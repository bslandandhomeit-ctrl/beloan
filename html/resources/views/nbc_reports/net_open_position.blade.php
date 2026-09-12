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
                <span>{{ trans('sidebar.sb_net_open_position') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4>NATIONAL BANK OF CAMBODIA</h4>
                        <h4>Net Open Position</h4>

                        <p style="text-align: right;">(Exchange Rate 1US$) = 4,008.00</p>
                        <p style="text-align: left;">INSTITUTION'S NAME: ORO FINANCECORP PLC <span class="pull-right">(Banks Net Worth) 4,408.80</span></p>
                        <p style="text-align: left;">REPORT AS AT(DATE) FRI 03-MAR-2017</p>
                    </div>
                    <table class="table table-bordered table-striped table-condensed repayment-plan sticky-header">
                         <thead style="background-color: #ffffff">
                            <tr class="header">
                                <th rowspan="4">Currency</th>
                                <th colspan="4">Elements after deduction of affected provisions</th>
                                <th>Net Open Position</th>
                                <th rowspan="4">Net Open Position Net Worth (%)</th>
                                <th rowspan="4">Limit %</th>
                                <th rowspan="4">Excess (1)</th>
                            </tr>
                            <tr class="head-1">
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                            </tr>
                            <tr class="head-1">
                                <th>Assets</th>
                                <th>Liabilities and Capital</th>
                                <th>Currencies receivable</th>
                                <th>Currencies payable</th>
                                <th>+(long) or -(short)</th>
                            </tr>
                            <tr class="head-1">
                                <th>+</th>
                                <th>-</th>
                                <th>Off Balance Sheet +</th>
                                <th>Off Balance Sheet -</th>
                                <th>(1+2+3+4)</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr>
                                 <td>USD</td>
                                 <td>4,424.83</td>
                                 <td>(4,424.83)</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>0.00 %</td>
                                 <td>20%</td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td>KHR</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>0.00 %</td>
                                 <td>20%</td>
                                 <td>-</td>
                             </tr> 
                             <tr>
                                 <td>EUR</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>0.00 %</td>
                                 <td>20%</td>
                                 <td>-</td>
                             </tr> 
                             <tr>
                                 <td>SGD</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>0.00 %</td>
                                 <td>20%</td>
                                 <td>-</td>
                             </tr> 
                             <tr>
                                 <td>HKD</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>0.00 %</td>
                                 <td>20%</td>
                                 <td>-</td>
                             </tr>       
                             <tr>
                                 <td>THB</td>
                                 <td>4,424.83</td>
                                 <td>(4,424.83)</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>0.00 %</td>
                                 <td>20%</td>
                                 <td>-</td>
                             </tr> 
                             <tr>
                                 <td>JPY</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>0.00 %</td>
                                 <td>20%</td>
                                 <td>-</td>
                             </tr> 
                             <tr>
                                 <td>VND</td>
                                 <td>4,424.83</td>
                                 <td>(4,424.83)</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>0.00 %</td>
                                 <td>20%</td>
                                 <td>-</td>
                             </tr> 
                             <tr>
                                 <td>Grand Total</td>
                                 <td>4,424.83</td>
                                 <td>(4,424.83)</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>0.00 %</td>
                                 <td></td>
                                 <td>-</td>
                             </tr> 
                             <tr>
                                 <td></td>
                                 <td>(២)</td>
                                 <td>(៣)</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>(៤)</td>
                                 <td>0.00 %</td>
                                 <td>20%</td>
                                 <td>-</td>
                             </tr> 
                        </tbody>
                    </table>
                    <br />
                    <p>(1) Where there is an excess, the bank shall submit a written explanation of the origin of each excess, and the measures taken to remedy the situation</p>
                    <p>(2) Total Equal to total assets on the balance sheet</p>
                    <p>(3) Total equal to total liabilities on the balance sheet</p>
                    <p>(4) Total = Zero</p>
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