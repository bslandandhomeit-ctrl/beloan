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
                <span>{{ trans('sidebar.sb_solvency') }}</span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    <div style="text-align: center; text: bold;">
                        <h4>NATIONAL BANK OF CAMBODIA</h4><br />
                        <h4>SOLVENCY RATION FOR MICROFINANCE INSTITUTION</h4>
                    </div><br /><br />
                    <table class="table">
                        <tbody>
                             <tr>
                                 <td colspan="4">INSTITUTION'S NAME: ORO FINANCECORP PLC</td>
                             </tr>
                             <tr>
                                 <td colspan="4">REPORT AS AT(DATE) FRI 03-MAR-2017</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td></td>
                                 <td>Exchange Rate: </td>
                                 <td>4,008.00</td>
                             </tr>
                             <tr>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td>In Million Riels</td>
                             </tr>
                             <tr>
                                 <td colspan="4"><b>I. Sub-total A: Items to be added</b></td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Capital or endowment</td>
                                 <td></td>
                                 <td>4408.80</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Reserve, other than revaluation reserves</td>
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Premium related to capital (share premiums)</td>
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Provision for general banking risks</td>
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Retained earrings</td>
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Audited net profit for the last financial year</td>
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Other items</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td>-</td>
                                 <td></td> 
                                 <td></td>
                                 <td></td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Sub-total A</td> 
                                 <td></td>
                                 <td>4408.80</td>
                             </tr>
                             <tr>
                                 <td colspan="4"><b>II. Sub-total B: Items to be deducted</b></td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>For shareholders, directors, managers and their next of kin</td>
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td></td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>- Unpaid portion of capital</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>- Unpaid portion of capital</td> 
                                 <td></td>
                                 <td></td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td></td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td></td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Holding of own shares at their book value</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Accumulated losses</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Formation expenses</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Losses determined on dates</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td></td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Sub-total B</td> 
                                 <td></td>
                                 <td></td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td></td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td colspan="3"><b>III. Total C: BASE NET WORTH = A-B</b></td>
                                 <td>4408.80</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td></td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td colspan="4"><b>IV. Sub-Total D: Items to be added</b></td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Revaluation reserves</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Subordinated debt (up to 100% of base net worth)</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Other items (not more than base net worth)</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td></td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Sub-total D</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td></td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td colspan="4"><b>V. Sub-total E: Items to be deducted</b></td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Equity participation in banking and financial institutions</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Other items</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td></td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td>Sub-total E</td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td></td>
                                 <td></td> 
                                 <td></td>
                                 <td>-</td>
                             </tr>
                             <tr>
                                 <td colspan="3"><b>Total F: TOTAL NET WORTH = C+D-E</b></td>
                                 <td>4408.80</td>
                             </tr>
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