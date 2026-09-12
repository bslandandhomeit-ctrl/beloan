@extends('layouts.app')

@section('css')
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<style>
.checked{
        color: #1fb5ad;
    }
    </style>
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
                <span>Sale Commission Detail</span>               
            </header>
            <div class="panel-body">
                <?php
                    $disable_request=false;

                                            $totalall_discount=floatval($listSale->discount_promotion) + floatval($listSale->discount_other) + floatval($dlistSale->amount_discount_payment_option);
                                            $net_selling_price=floatval($listSale->unit_sale_price) - floatval($totalall_discount); 
                                            $total_commission_paid=0;
                                            if(!empty($listSale->commission_withdrawal_transaction) && count($listSale->commission_withdrawal_transaction) > 0){
                                                $total_commission_paid=$listSale->commission_withdrawal_transaction->sum('received_amount');
                                                
                                                } 
                                                if(floatval($total_commission_paid)>=floatval($listSale->commission_value)){
                                                    $disable_request=true;
                                                }
                                            ?>

                <table class="table table-bordered table-striped table-condensed">
                <tr>
                        <th>Company</th>
                        <td><?php echo $listSale->company; ?></td>
                        <th>Project</th>
                        <td><?php echo $listSale->short_code; ?></td>
                    </tr>
                    <tr>
                        <th>Unit Types</th>
                        <td><?php echo $listSale->unit_type; ?></td>
                        <th>Unit</th>
                        <td><?php echo $listSale->unit; ?></td>
                    </tr>
                	<tr>
                        <th>Customer</th>
                        <td><?php echo $listSale->client_name; ?></td>
                        <th>Com.Type</th>
                        <td><?php echo $listSale->commission_type; ?></td>
                    </tr>
                    <tr>
                        <th>Unit Sale Price</th>
                        <td>$<?php echo $listSale->unit_sale_price;?></td>
                        <th>Com.Amount</th>
                        <td><?php echo $listSale->commission_value; ?></td>
                    </tr>
                    <tr>
                        <th>Total Discount</th>
                        <td>$<?php echo $totalall_discount; ?></td>
                        <th>Rate(%)</th>
                        <td><?php echo $commissionRate->rate; ?></td>
                </tr>
                <tr>
                        <th>Net Selling Price</th>
                        <td>$<?php echo $net_selling_price; ?></td>
                        <th>Customer Paid</th>
                        <td><?php echo number_format($listSale->coa_journal_detail->sum('credit'),2,'.',''); ?></td>
                </tr>
                
                </table>
                <table class="table table-bordered table-striped table-condensed">
                <?php $i=0;
                        ?>
                @forelse($sale_team_commission as  $c)
                <?php 
                $balance= floatval($c['total_com']) - floatval($c['total_com_paid']);
                ?>
                <tr>
                    <th>Sale Team</th><td>{{$c['sale_team']}}</td>
                        <td>Rate={{$c['com_rate']}}%</td>
                        <th>Total.com</th>
                        <td>${{$c['total_com']}}</td>
                        <th>Total Com. Paid</th>
                        <td>${{$c['total_com_paid']}}</td>
                        <th>Balance</th>
                        <td>$<?php echo $balance?></td>
                </tr>
               
                @endforeach
                </table>
                <h5><strong>Item Detail</strong></h5>
               
                <?php if(!$disable_request){?>
                    <a href="{{ route('request_withdraw', [$listSale->id])}}" class="btn btn-danger pull-right" title="Request withdraw"><i class="fa fa-plus"></i> Request Withdraw</a>  
               <?php }?>
               
                <table  class="table table-bordered table-striped table-condensed table-hover clientTable">
                <thead class="th-center">
                    <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.id') }}</th>
                    <th style="vertical-align:middle; text-align: center;">Withdraw Date</th>
                    <th style="vertical-align:middle; text-align: center;">Sale Team</th>
                    <th style="vertical-align: middle;text-align: center;">Withdraw.Amt</th>
                    <th style="vertical-align:middle; text-align: center;">Received.Amt</th>
                    <th style="vertical-align:middle; text-align: center;">Status</th>
                    <th style="vertical-align: middle;text-align: center;">Rate</th>
                    <th style="vertical-align:middle; text-align: center;">Payment Status</th>
                    <th style="vertical-align:middle; text-align: center;">SM. Approval</th>
                    <th style="vertical-align:middle; text-align: center;">Acc. Approval</th>
                    <th style="vertical-align:middle; text-align: center;">HOD. Approval</th>   
                    <th style="vertical-align:middle; text-align: center;">Chairman Approval</th> 
                    <th style="vertical-align:middle; text-align: center;">Action</th> 
                                       
                    </thead>

                    <tbody>
                        <?php $i=0;
                        ?>
          
                        @forelse($listSale->commission_withdrawal_transaction as $d)
                        <?php $i++;
                        ?>
                            <tr>
                                <td class="isVerticalalign" align="center">{{ $i}}</td>
                                <td class="isVerticalalign">{{ !empty($d->withdrawal_date)?date('d-M-Y',strtotime($d->withdrawal_date)):"N/A" }}</td>
                                <td style="text-align: center">{{ $d->sale_team }}</td> 
                                <td style="text-align: center">{{ number_format($d->withdrawal_amount,2,'.','') }}</td> 
                                <td style="text-align: center">{{ number_format($d->received_amount,2,'.','') }}</td> 
                                <td class="isVerticalalign define-width" align="center">
                                    @if( $d->status =='Withdrawal')
                                    <p class="btn btn-danger">{{$d->status}}</p>
                                    @else
                                    <p class="btn btn-primary">{{$d->status}}</p>
                                    @endif
                                </td> 
                                <td class="isVerticalalign" >{{ $d->commission_rate }}</td>                                
                                <td class="isVerticalalign define-width" align="center">
                                    @if( $d->payment_status =='Due')
                                    <p class="btn btn-danger">{{ $d->payment_status}}</p>
                                    @else
                                    <p class="btn btn-xs btn-default">{{ $d->payment_status}}</p>
                                    @endif
                                </td>   
                                <td style="text-align: center">
                                    @if(!empty($d->sales_manager_approval))                    
                                    <i class="fa fa-check-circle checked"></i>
                                    {{$d->sales_manager_approval}}
                                    @else
                                    <i class="fa fa-check-circle unchecked"></i>
                                    
                                    @endif
                                </td>
                                <td style="text-align: center">                                 
                                @if(!empty($d->accountant_approval))                    
                                    <i class="fa fa-check-circle checked"></i>
                                    {{$d->accountant_approval}}
                                    @else
                                    <i class="fa fa-check-circle unchecked"></i>
                                    
                                    @endif
                                </td> 
                                <td style="text-align: center">
                                @if(!empty($d->hof_approval))                    
                                    <i class="fa fa-check-circle checked"></i>
                                    {{$d->hof_approval}}
                                    @else
                                    <i class="fa fa-check-circle unchecked"></i>                                   
                                    @endif
                                </td> 
                                <td style="text-align: center">
                                              
                                @if(!empty($d->chairman_approval))                    
                                    <i class="fa fa-check-circle checked"></i>
                                    {{$d->chairman_approval}}
                                    @else
                                    <i class="fa fa-check-circle unchecked"></i>                                  
                                    
                                    @endif
                                </td> 
                                <td style="text-align: center">
                                @if(!empty($d->status=='Approved'))
                                <a href="{{ route('print_commission',[$d->id]) }}"  target="_blank"><span class="glyphicon glyphicon-print"></span></a>
                                    @if(!empty($d->payment_status=='Due'))
                                    <a href="{{ route('get_mark_commission_payment', [$d->id])}}" class="btn btn-danger" title="Mark Payment"><span class="glyphicon glyphicon-usd"></span> Mark Payment</a> 
        
                                    @endif
                                @endif
                            </td>                                      
                                
                            </tr>
                        @endforeach
                    </tbody>
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
