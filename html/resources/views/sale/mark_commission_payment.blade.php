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
                <span>Mark Commission Payment</span>               
            </header>
            <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed">
               
                	<tr>
                        <th>Withdraw Date</th>
                        <td>{{ !empty($listSale->withdrawal_date)?date('d-M-Y',strtotime($listSale->withdrawal_date)):"N/A" }}</td>
                        <th>Withdraw Amount</th>
                        <td>$<?php echo $listSale->withdrawal_amount; ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td> @if( $listSale->status =='Withdrawal')
                                    <p class="btn btn-danger">{{$listSale->status}}</p>
                                    @else
                                    <p class="btn btn-primary">{{$listSale->status}}</p>
                                    @endif</td>
                        <th>Payment Status</th>
                        <td> @if( $listSale->payment_status =='Due')
                                    <p class="btn btn-danger">{{ $listSale->payment_status}}</p>
                                    @else
                                    <p class="btn btn-primary">{{ $listSale->payment_status}}</p>
                                    @endif
                                </td>
                    </tr>
                    <tr>
                        <th>SM. Approval</th>
                        <td style="text-align: center">
                                    @if(!empty($listSale->sales_manager_approval))                    
                                    <i class="fa fa-check-circle checked"></i>
                                    {{$listSale->sales_manager_approval}}
                                    @else
                                    <i class="fa fa-check-circle unchecked"></i>
                                    
                                    @endif
                                
                                </td>
                        <th>Acc. Approval</th>
                        <td style="text-align: center">                                 
                                @if(!empty($listSale->accountant_approval))                    
                                    <i class="fa fa-check-circle checked"></i>
                                    {{$listSale->accountant_approval}}
                                    @else
                                    <i class="fa fa-check-circle unchecked"></i>
                                    
                                    @endif
                                </td> 
                </tr>
                <tr>
                        <th>HOD. Approval</th>
                        <td style="text-align: center">
                                @if(!empty($listSale->hof_approval))                    
                                    <i class="fa fa-check-circle checked"></i>
                                    {{$listSale->hof_approval}}
                                    @else
                                    <i class="fa fa-check-circle unchecked"></i>                                   
                                    @endif
                                </td> 
                        <th>Chairman Approval</th>
                        <td style="text-align: center">
                                              
                                @if(!empty($listSale->chairman_approval))                    
                                    <i class="fa fa-check-circle checked"></i>
                                    {{$listSale->chairman_approval}}
                                    @else
                                    <i class="fa fa-check-circle unchecked"></i>                                  
                                    
                                    @endif
                                </td> 
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
                <form class = "cmxform form-horizontal" method = "post" action = "{{route('mark_commission_payment')}}" id = "frmSale">
                <div class = "col-lg-12">
                    <input type = "hidden" name = "_token" value = "{{ csrf_token() }}"/>
                    <input type = "hidden" name = "id" value = "{{ $listSale->id }}"/>
                    <input type = "hidden" name = "commission_rate" value = "{{ $commissionRate->rate }}"/>
                    <div class = "form-group">                       
                        <div class = "col-md-12">
                        <label class = "control-label">Sale Person<span class = "red-color">*</span></label>
                            <select  class = "select2_type customer_select1" required style = "width: 100%;" name = "saleperson_id" id = "saleperson_id">
                                @foreach($sale_team_commission as $c)
                                    <option value = "{{$c['saleperson_id']}}"  @if($listSale->saleperson_id==$c['saleperson_id']) selected="selected" @endif> {{ $c['com_level_type'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>                     
  
                    <div class = "form-group">
                        <div class="col-sm-12">
                            <label class="control-label">Withdraw Amount<span class="red"> *</span></label>
                        </div>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="withdrawal_amount" name="withdrawal_amount" value="{{$listSale->withdrawal_amount}}" readonly/>
                        </div>
                    </div>
                    <div class = "form-group">
                        <div class="col-sm-12">
                            <label class="control-label">Received Amount<span class="red"> *</span></label>
                        </div>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="received_amount" required name="received_amount" value="{{$listSale->withdrawal_amount}}"/>
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
                    required: false
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
    });
</script>
@endsection
