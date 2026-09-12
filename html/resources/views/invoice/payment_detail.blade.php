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
                <span>Invoice Detail</span>               
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
                    <th style="vertical-align:middle; text-align: center;">Saller</th>
                    <th style="vertical-align:middle; text-align: center;">Unit Sale Price</th>
                    <th style="vertical-align:middle; text-align: center;">Clearance Amount</th>
                    <th style="vertical-align:middle; text-align: center;">Dis.Promotion</th>
                    <th style="vertical-align:middle; text-align: center;">Dis.Other</th>
                    <th style="vertical-align:middle; text-align: center;">Dis.Payment Option</th>
                    <th style="vertical-align:middle; text-align: center;">Sub Total</th>
                    <th style="vertical-align:middle; text-align: center;">VAT</th>
                    <th style="vertical-align:middle; text-align: center;">Diposit</th>
                    <th style="vertical-align:middle; text-align: center;">Grand Total</th>
                    <th style="vertical-align:middle; text-align: center;">Paid</th>
                    <th style="vertical-align:middle; text-align: center;">Balance</th>                             
                    <th style="vertical-align:middle; text-align: center;">Payment Term</th>           
                    <th style="vertical-align:middle; text-align: center;">Payment Status</th>
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
                                <td class="isVerticalalign" >{{ $listSale->saleperson }}</td>
                                <td class="isVerticalalign">${{ $listSale->unit_sale_price }}</td>
                                <td class="isVerticalalign">{{ $listSale->clearance_amount }}</td>
                                <td class="isVerticalalign">{{ $listSale->discount_promotion }}</td>
                                <td class="isVerticalalign">{{ $listSale->discount_other }}</td>
                                <td class="isVerticalalign">{{ $listSale->discount_payment_option }}</td>
                                <td class="isVerticalalign">{{ $listSale->price_after_discount }}</td>
                                <td class="isVerticalalign">{{ $listSale->vat }}</td>
                                <td class="isVerticalalign">{{ $listSale->diposit_amount }}</td>
                                <td class="isVerticalalign">{{ $listSale->final_price }}</td>                              
                                <td class="isVerticalalign">{{ $listSale->paid }}</td>     
                                <td class="isVerticalalign">{{ $listSale->balance }}</td> 
                                <td class="isVerticalalign">{{ $listSale->payment_term }}</td>                                 
                                <td class="isVerticalalign">
                                    @if($listSale->balance >0)
                                    <span class="btn btn btn-danger" title="{{$listSale->invoice_status}}">{{$listSale->payment_status}}</span>                                       
                                    @else
                                    <span class="btn btn-primary" title="{{$listSale->invoice_status}}">{{$listSale->payment_status}}</span>                                    
                                    @endif  
                                </td>                            
                                <td class="isVerticalalign">
                                    @if($listSale->invoice_status =='Invoice')
                                        <span class="btn btn-default" title="{{$listSale->invoice_status}}">{{$listSale->invoice_status}}</span>                                        
                                    @elseif($listSale->invoice_status =='Void')
                                        <span class="btn btn btn-danger" title="{{$listSale->invoice_status}}">{{$listSale->invoice_status}}</span>
                                    @else
                                    <span class="btn btn-dark" title="{{$listSale->invoice_status}}">{{$listSale->invoice_status}}</span>                                    
                                    @endif  
                                </td> 
                                <td class="isVerticalalign">{{ $listSale->remark }}</td> 
                                
                            </tr>
                    </tbody>
                </table>
                <form class = "cmxform form-horizontal" method = "post" action = "{{route('add_invoice_payment')}}" id = "frmSale">
                <div class = "col-lg-12">
                    <input type = "hidden" name = "_token" value = "{{ csrf_token() }}"/>
                    <div class = "form-group">
                        <div class="col-sm-6 text-right">
                            <label class="control-label">Interest</label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="paid_interest" name="paid_interest"  value="0"/>
                        </div>
                    </div>
                    <div class = "form-group">
                        <div class="col-sm-6 text-right">
                            <label class="control-label">Paid Fee</label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="paid_fee" name="paid_fee"  value="0"/>
                        </div>
                    </div>
                    <div class = "form-group">
                        <div class="col-sm-6 text-right">
                            <label class="control-label text-right">Penalty</label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="penalty_amount" name="penalty_amount"   value="0"/>
                        </div>
                    </div>
                    <div class = "form-group">
                        <div class="col-sm-6 text-right">
                            <label class="control-label text-right">Paid Amount<span class="red"> *</span></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="paid_principal" name="paid_principal" required  />
                        </div>
                    </div>                                   
                    <div class = "form-group">
                        <label class = "col-md-3 control-label"></label>
                        <div class = "col-md-12">
                            <a href="/invoice/list"  class = "btn btn-secondary pull-right"> Cancel </a>
                            <button type="submit" class = "btn btn-primary pull-right" type="submit">Payment</button>                            
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
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js', isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',false) }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
    //pagination
        $('.custom-pagi a').on('click', function () {
            val = $(this).parent().find('input[name="set_offset"]').val();
            $('input[name="offset"]').val(val);
            $('#search_frm').submit();
            return false;
        });

        $('#frmSale').validate({
            rules:{
                paid_principal : {
                    required: true
                }
            },
            messages:{
                paid_principal:{
                    required: "Please Enther a Principal"
                }
            },submitHandler:function(){
               if(confirm("Are you sure?")) {
                   $('#loading').remove();
                   $('<div id="loading"></div>').appendTo('body');
                   imgLoading(true,'Loading...',5,'warning');

                   $.ajax({
                    //   url: "add_invoice_payment",
                       method: 'post',
                       dataType: 'json',
                       timeout: 3000,
                       headers: {
                           'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                       },
                       data: $('#frmSale').serialize() + '&_token=' + $('meta[name="_token"]').attr('content'),
                       success: function (data, status) {
                        window.location.href = "invoice/list";
                           if (status !== 'success' && data.save !== true) {
                               return imgLoading(true,'Please try again',9,'warning');

                           }
                           $('#loading').remove();                           
                           $('<div id="loading"></div>').appendTo('body');
                           imgLoading(true,'Successfully',5,status);
                           delete_allModel('.modal');
                       }, error: function (xhr, status, errorThrown) {
                           xhr.status;
                           xhr.responseText;
                       }
                   })
               }
            }
        });
 
    });
</script>
@endsection
