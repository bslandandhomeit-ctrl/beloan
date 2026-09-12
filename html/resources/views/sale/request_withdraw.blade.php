@extends('layouts.app')

@section('css')
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link rel = "stylesheet" type = "text/css" href = "{{ asset('css/loan-style.css',false) }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('theme/select2_v4.1.0/select2.min.css',isset($secure) ? false : false) }}"/>
<link rel = "stylesheet" type = "text/css" href = "{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',false)}}"/>

    <style>
        .col-md-3.control-label.text-left {
            text-align: left !important;
        }
        #customer_types, #guarantor {
            max-height: 300px;
        }
        .form-check-inline{
            float: left;
            margin-right: 10px;
        }
        .hiden{
            display: none;
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
                <span>Withdraw Commission</span>               
            </header>
            <div class="panel-body">
                <?php 
                                            $totalall_discount=floatval($listSale->discount_promotion) + floatval($listSale->discount_other) + floatval($dlistSale->amount_discount_payment_option);
                                            $net_selling_price=floatval($listSale->unit_sale_price) - floatval($totalall_discount); 
                                            $total_commission_paid=0;
                                            if(!empty($listSale->commission_withdrawal_transaction) && count($listSale->commission_withdrawal_transaction) > 0){
                                                $total_commission_paid=$listSale->commission_withdrawal_transaction->sum('received_amount');
                                                
                                                }                                                
                                            ?>

                <table class="table table-bordered table-striped table-condensed">
               
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
                    <th>Sale Team</th><td>{{$c['com_level_type']}}</td>
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
                <form class = "cmxform form-horizontal" method = "post" action = "{{route('postrequest_withdraw')}}" id = "frmSale">
                <div class = "col-lg-12">
                    <input type = "hidden" name = "_token" value = "{{ csrf_token() }}"/>
                    <input type = "hidden" name = "loan_id" value = "{{ $listSale->id }}"/>
                    <input type = "hidden" name = "commission_rate" value = "{{ $commissionRate->rate }}"/>
                    <div class = "form-group">                       
                        <div class = "col-md-12">
                        <label class = "control-label">Sale Person<span class = "red-color">*</span></label>
                            <select class = "select2_type customer_select1" required style = "width: 100%;" name = "saleperson_id" id = "saleperson_id">
                            <option value = "">--- Select sale person ---</option>
                                @foreach($sale_team_commission as $c)
                                    <option value = "{{$c['saleperson_id']}}"> {{ $c['com_level_type'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>                     
  
                    <div class = "form-group">
                        <div class="col-sm-12">
                            <label class="control-label">Withdraw Amount<span class="red"> *</span></label>
                        </div>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="withdrawal_amount" name="withdrawal_amount" required value="" readonly/>
                        </div>
                    </div>
                    <div class="form-group">                       
                        <div class="col-md-12">
                            <label class="control-label">Remark</label>
                            <textarea name="noted" id="noted" class="form-control" rows="2"></textarea>
                        </div>
                                           
                    <div class = "form-group">
                        <label class = "col-md-3 control-label"></label>
                        <div class = "col-md-12">
                            <a href="/sale/list"  class = "btn btn-secondary pull-right"> Cancel </a>
                            <button type="submit" class = "btn btn-primary pull-right" type="submit">Save</button>                            
                        </div>                   
                    </div>               
                </div>
            </form>

            </div>




        </section>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js', isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js', isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/select2_v4.1.0/select2.min.js',isset($secure) ? false : false)}}"></script>

<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',false) }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#saleperson_id").select2();
    //pagination
        $('.custom-pagi a').on('click', function () {
            val = $(this).parent().find('input[name="set_offset"]').val();
            $('input[name="offset"]').val(val);
            $('#search_frm').submit();
            return false;
        });
        $('#frmSale').validate({
            rules:{
                saleperson_id : {
                    required: true
                }
            },
            messages:{
                saleperson_id:{
                    required: "Please select a sale person"
                }
            },submitHandler:function(){

                   $('#frmSale').submit();
               
            }
        });
        $('#saleperson_id').on('change', function (e) {
           var data=<?php echo $sale_team_commission_json;?> 
           var sale_team_data=data.find(x => x.saleperson_id == this.value);
        //    var withdrawal_amount=sale_team_data.total_commission_tobe_pay*sale_team_data.com_rate/100; 
        var withdrawal_amount=sale_team_data.total_commission_tobe_pay; 
           if(parseFloat(withdrawal_amount)<=0){
                alert('Commission to be pay not enough!');
                return;
           }      
           if(sale_team_data.total_commission_tobe_pay<=0){
                alert('Commission to be pay not enough!');
                return;
           }

           if(withdrawal_amount>sale_team_data.total_commission_tobe_pay){
                alert('Commission to be pay not enough!');
                return;
           }
           var balance=parseFloat(sale_team_data.total_com)-parseFloat(sale_team_data.total_com_paid);
           if(parseFloat(balance)<=0){
                alert('Commission to be pay not enough!');
                return;
           }  

           if(withdrawal_amount>balance){
            $("#withdrawal_amount").val(parseFloat(balance).toFixed(2));
           }else{
            $("#withdrawal_amount").val(parseFloat(withdrawal_amount).toFixed(2));
           }
           
              

            });
    });
</script>
@endsection
