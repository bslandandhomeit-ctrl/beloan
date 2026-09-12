@extends('layouts.app')

@section('css')
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
    <?php
        $currency = config('static_data.currency_symbol');
        $status = config('static_data.client_loan_account_status');
        $drawdown_status = config('static_data.drawdown_status');
    ?>

@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                <span>Sale Order Detail</span>               
            </header>
            <div class="panel-body">

                <table class="table table-bordered table-striped table-condensed">
                	<tr>
                        <th>Customer</th>
                        <td><?php echo $listSale->client_name; ?></td>
                        <th>Order No#</th>
                        <td><?php echo $listSale->order_no; ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $listSale->address;?></td>
                        <th>Order Date</th>
                        <td><?php echo date('d-M-Y',strtotime($listSale->created_on)); ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?php echo $listSale->phone1; ?> / <?php echo $listSale->phone2; ?></td>
                </tr>

                </table>
                <h5><strong>Item Detail</strong></h5>
                <table  class="table table-bordered table-striped table-condensed table-hover clientTable">
                    <thead>
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.id') }}</th>
                    <th style="vertical-align: middle;text-align: center;">Company</th>
                    <th style="vertical-align:middle; text-align: center;">Project</th>
                    <th style="vertical-align: middle;text-align: center;">Unit Types</th>
                    <th style="vertical-align:middle; text-align: center;">Unit</th>
                    <th style="vertical-align:middle; text-align: center;">Sale Team L1</th>
                    <th style="vertical-align:middle; text-align: center;">Sale Team L2</th>
                    <th style="vertical-align:middle; text-align: center;">Sale Team L3</th>
                    <th style="vertical-align:middle; text-align: center;">Total Commission</th>
                    <th style="vertical-align:middle; text-align: center;">Commission Paid</th>
                    <th style="vertical-align:middle; text-align: center;">Commission Oustanding</th>
                    <th style="vertical-align:middle; text-align: center;">Unit Sale Price</th>
                    <th style="vertical-align:middle; text-align: center;">Dis.Promotion</th>
                    <th style="vertical-align:middle; text-align: center;">Dis.Other</th>
                    <th style="vertical-align:middle; text-align: center;">Dis.Payment Option</th>
                    <th style="vertical-align:middle; text-align: center;">Net Selling Price</th>
                    <th style="vertical-align:middle; text-align: center;">Diposit</th>
                    <th style="vertical-align:middle; text-align: center;">Total</th>
                    <th style="vertical-align:middle; text-align: center;">Status</th>
                    <th style="vertical-align:middle; text-align: center;">Invoice Status</th>
                    <th style="vertical-align:middle; text-align: center;">Remark</th>
                    </thead>
                    <tbody>
                            <tr>
                                <td style="text-align:center;">1</td>
                                <td style="text-align:center;"><?php echo $listSale->company;?></td>
                                <td class="isVerticalalign">{{ $listSale->short_code }}</td>
                                <td class="isVerticalalign" >{{ $listSale->unit_type }}</td>
                                <td class="isVerticalalign">{{ $listSale->unit }}</td>
                                <td class="isVerticalalign" >{{ $listSale->sale_person_parent_lavel1?$listSale->sale_person_parent_lavel1->name:'-' }}</td>
                                <td class="isVerticalalign" >{{ $listSale->sale_person_parent_lavel2?$listSale->sale_person_parent_lavel2->name:'-' }}</td>
                                <td class="isVerticalalign" >{{ $listSale->sale_persons?$listSale->sale_persons->name:'-' }}</td>
                                <td class="isVerticalalign" align="center">-</td>
                                <td class="isVerticalalign" align="center">-</td>
                                <td class="isVerticalalign" align="center">-</td>
                                <td class="isVerticalalign">${{ $listSale->unit_sale_price }}</td>
                                <td class="isVerticalalign">${{ $listSale->discount_promotion }}</td>
                                <td class="isVerticalalign">${{ $listSale->discount_other }}</td>
                                <td class="isVerticalalign">${{ $listSale->amount_discount_payment_option }}</td>
                                <td class="isVerticalalign">${{ $listSale->price_after_discount }}</td>
                                <td class="isVerticalalign">${{ $listSale->diposit_amount }}</td>
                                <td class="isVerticalalign">${{ $listSale->final_price }}</td>                              
                                <td class="isVerticalalign">
                                    @if($listSale->status =='Ordered')
                                    <span class="btn btn-info" title="{{$listSale->status}}">{{$listSale->status}}</span>
                                    @else
                                    {{$listSale->status}}
                                    @endif                                   
                                </td>
                            <td class="isVerticalalign">
                                    @if($listSale->invoice_status =='First Created')
                                        <span class="btn btn-success" title="{{$listSale->invoice_status}}">{{$listSale->invoice_status}}</span>
                                    @elseif($listSale->invoice_status =='Modified')
                                        <span class="btn btn-warning" title="{{$listSale->invoice_status}}">{{$listSale->invoice_status}}</span>
                                    @else
                                    {{$listSale->invoice_status}}
                                    @endif  
                                </td> 
                                <td class="isVerticalalign">{{ $listSale->remark }}</td> 
                                
                            </tr>
                    </tbody>
                      <td colspan="11" style="text-align: right;font-weight: bold;">{{ trans('loan.total') }}</td>
                      <td style="text-align: right;font-weight: bold;">{{number_format($listSale->price_after_discount, 2)}}</td>
                      <td style="text-align: right;font-weight: bold;">{{number_format($listSale->diposit_amount, 2)}}</td>
                      <td style="text-align: right;font-weight: bold;">{{number_format($listSale->final_price, 2)}}</td>
                </table>

            </div>




        </section>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js', isset($secure) ? false : false) }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
    //pagination
        $('.custom-pagi a').on('click', function () {
            val = $(this).parent().find('input[name="set_offset"]').val();
            $('input[name="offset"]').val(val);
            $('#search_frm').submit();
            return false;
        });
    });
</script>
@endsection
