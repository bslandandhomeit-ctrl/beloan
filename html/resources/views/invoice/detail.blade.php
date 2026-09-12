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
                        <th>Invoice No#</th>
                        <td><?php echo $listSale->invoice_no; ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $listSale->address;?></td>
                        <th>Order No#</th>
                        <td><?php echo $listSale->order_no; ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?php echo $listSale->phone1; ?> / <?php echo $listSale->phone2; ?></td>
                        <th>Order Date</th>
                        <td><?php echo date('d-M-Y',strtotime($listSale->created_on)); ?></td>
                </tr>

                </table>
                <ul class="nav nav-tabs">
                        <li class="active" id="detail"><a data-toggle="tab" href="#item_detail">Item Detai</a>
                        </li>
                        <li id="payment"><a data-toggle="tab" href="#item_payment">Payment</a>
                        </li>                        
                    </ul>
                    <div class="tab-content">                               
                                <div id="item_detail" class="tab-pane active">
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
                                                <td class="isVerticalalign">${{ number_format($listSale->unit_sale_price,2) }}</td>
                                                <td class="isVerticalalign">{{ $listSale->discount_promotion }}</td>
                                                <td class="isVerticalalign">{{ $listSale->discount_other }}</td>
                                                <td class="isVerticalalign">{{ $listSale->discount_payment_option }}</td>
                                                <td class="isVerticalalign">${{ number_format($listSale->price_after_discount,2) }}</td>
                                                <td class="isVerticalalign">{{ $listSale->diposit_amount }}</td>
                                                <td class="isVerticalalign">${{ number_format($listSale->final_price,2) }}</td>                              
                           
                                                <td class="isVerticalalign">
                                                    @if($listSale->invoice_status =='Invoice')
                                                        <span class="btn btn-default" title="{{$listSale->invoice_status}}">{{$listSale->invoice_status}}</span>                                        
                                                    @elseif($listSale->invoice_status =='Void')
                                                        <span class="btn btn btn-danger" title="{{$d->invoice_status}}">{{$listSale->invoice_status}}</span>
                                                    @else
                                                    <span class="btn btn-dark" title="{{$listSale->invoice_status}}">{{$listSale->invoice_status}}</span>                                    
                                                    @endif  
                                                </td> 
                                                <td class="isVerticalalign">{{ $listSale->remark }}</td> 
                                                
                                            </tr>
                                    </tbody>
                                    <td colspan="10" style="text-align: right;font-weight: bold;">{{ trans('loan.total') }}</td>
                                    <td style="text-align: right;font-weight: bold;">{{number_format($listSale->price_after_discount, 2)}}</td>
                                    <td style="text-align: right;font-weight: bold;">{{number_format($listSale->diposit_amount, 2)}}</td>
                                    <td style="text-align: right;font-weight: bold;">{{number_format($listSale->final_price, 2)}}</td>
                                </table>
                                </div>
                                <div id="item_payment" class="tab-pane">
                                <table  class="table table-bordered table-striped table-condensed table-hover clientTable">
                                    <thead>
                                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.id') }}</th>
                                    <th style="vertical-align: middle;text-align: center;">Invoice No</th>
                                    <th style="vertical-align:middle; text-align: center;">Payment Date</th>
                                    <th style="vertical-align: middle;text-align: center;">Payment number</th>
                                    <th style="vertical-align:middle; text-align: center;">Paid Interest</th>
                                    <th style="vertical-align:middle; text-align: center;">Paid Fee</th>
                                    <th style="vertical-align:middle; text-align: center;">Penalty Amount</th>
                                    <th style="vertical-align:middle; text-align: center;">Paid Principal</th>
                                    <th style="vertical-align:middle; text-align: center;">Payment_type</th>
                                    <th style="vertical-align:middle; text-align: center;">Remark</th>
                                    </thead>

                                    <tbody>
                                        @forelse($payments as $d)
                                            <tr>
                                                <td class="isVerticalalign" align="center">{{ $d->id}}</td>
                                                <td class="isVerticalalign">{{ $d->invoice_number}}</td>
                                                <td class="isVerticalalign">{{ !empty($d->payment_date)?date('d-M-Y',strtotime($d->payment_date)):"N/A" }}</td>
                                                <td class="isVerticalalign">{{ $d->payment_number}}</td>
                                                <td class="isVerticalalign">{{ $d->paid_interest}}</td>
                                                <td class="isVerticalalign">{{ $d->paid_fee }}</td>
                                                <td class="isVerticalalign" >{{ $d->penalty_amount }}</td>
                                                <td class="isVerticalalign">{{ number_format($d->paid_principal) }}</td>
                                                <td class="isVerticalalign" >{{ $d->payment_type }}</td>
                                                <td class="isVerticalalign">{{ $d->note }}</td>                             
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>

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
