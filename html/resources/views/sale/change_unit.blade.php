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

@section('content')
    <section class = "panel">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('message') }}</p>
        @endif
        <div class = "panel-heading">
        CHANGE UNIT | ផ្លាស់ប្ដូរ Unit
        </div>
        <div class = "panel-body">
            <div class = "col-sm-12">
                <label class = "col-md-2"></label>
                <div class = "col-md-10">
                    @if (count($errors) > 0)
                        <div class = "alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @elseif(session('msg_success'))
                        <div class = "alert alert-success">
                            <ul>
                                <li>{{ session('msg_success') }}</li>
                            </ul>
                        </div>
                    @endif
                    @if(Session::has('msg'))
                        <div class = "alert alert-danger fade in">
                            <button class = "close close-sm" data-dismiss = "alert">x</button>
                            {{ Session::get('msg') }}
                        </div>
                    @endif
                </div>
                <?php
                $static = config('static_data');
                $select_branch_id = 0;
                $select_currency_id = 0;
                $auto_selected = $static['auto_select_option'];
                ?>
            </div>

            <form class = "cmxform form-horizontal" method = "post" action = "{{route('add_sale')}}" id = "frmSale">
                <div class = "col-lg-12">
                    <input type = "hidden" name = "_token" value = "{{ csrf_token() }}"/>
                    <div class = "form-group">    
                                        <div class="col-md-12"> 
                        <label for="unit_id">From {{ trans('unit.unit') }}</label>
                        <select name="from_unit_id" class="form-control" id="from_unit_id" required></select>

                </div>                   
                        <div class = "col-md-12">
                        <label class = "control-label">Customer<span class = "red-color">*</span></label>
                            <select class = "select2_type customer_select1" style = "width: 100%;" name = "client_id" id = "customer_id">
                                @foreach($clients as $c)
                                    <option value = "{{$c->id}}"> {{str_pad($c->id, 4, '0', STR_PAD_LEFT)}} ( {{ $c->client_name }} )</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class = "form-group" style="display: none;">                        
                        <div class = "col-md-6">
                        <label class = "control-label">Currency<span class = "red-color">*</span></label>
                            <select class = "select2_type customer_select1" required style = "width: 100%;" name="currency" id = "currency">
                                <option value = "">-</option>
                                @foreach($static['currency'] as $key => $value)
                                    <option value = "{{ $key }}" {{ $key?$key == $auto_selected['currency'] ? 'selected':'':old('') }}>{{ $value }}</option>
                                    <?php echo 'selected'; $select_currency_id = $key; ?>
                                @endforeach
                            </select>
                        </div>
                        <div class = "col-md-6">
                        <label class = "control-label">Created On<span class = "red-color">*</span></label>
                        <div data-date-viewmode = "years" style = "width: 100%;" data-initialize = "datepicker" data-date-format = "dd/mm/yyyy" data-date = "{{date('Y-m-d')}}" class = "input-append date dpYears col-md-6">
                            <input type = "text" name = "created_on" value = "{{date('Y-m-d')}}" size = "16" class = "form-control" id = "created_on">
                        <span class = "add-on birhtdateDatepicker">
                            <button class = "btn btn-primary" type = "button"><i class = "fa fa-calendar"></i></button>
                        </span>
                        </div>
                        </div>
                    </div>
                    <div class = "form-group">                        
                        <div class = "col-md-6">
                        <label class = "control-label">{{ trans('company.company') }}<span class = "red-color">*</span></label>
                        <select  class = "select2_type customer_select1"required style = "width: 100%;" name = "branch" id = "branch">
                                @if(isset($branches))
                                    <option value = "">-</option>
                                    @foreach($branches as $b)
                                        <option value = "{{ $b->branch_code }}" company-id="{{ $b->id }}">{{ $b->branch_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class = "col-md-6">
                        <label class = "control-label">{{ trans('loan.project') }}<span class = "red-color">*</span></label>
                        <select id="project_id" class = "select2_type customer_select1" required style = "width: 100%;" name="project_id">
                                    <option value="">-</option>
                        </select>
                        </div>
                    </div>
                    <div class = "form-group">                        
                        <div class = "col-md-6">
                        <label class = "control-label">{{ trans('loan.unit_type') }}<span class = "red-color">*</span></label>
                        <select id="unit_type_id" class = "select2_type customer_select1" required style = "width: 100%;" name="unit_type_id">
                                    <option value="">-</option>
                        </select>
                        </div>
                        <div class = "col-md-6">
                        <label class = "control-label">{{ trans('unit.unit') }}<span class = "red-color">*</span></label>
                        <select id="unit_id" class = "select2_type customer_select1" required style = "width: 100%;" name="unit_id">
                                    <option value="">-</option>
                        </select>
                        </div>
                    </div>

                    <div class = "form-group">
                        <div class="col-md-6">
                        <label class="control-label">Unit Sale Price<span class="red"> *</span></label>
                            <input type="text" class="form-control" id="unit_sale_price" required name="unit_sale_price" readonly value="" />
                        </div>
                        <div class="col-md-6">
                        <label class="control-label">Discount (Promotion)<span class="red"> *</span></label>
                            <input type="text" class="form-control" id="discount_promotion" name="discount_promotion" readonly value=""/>
                        </div>
                    </div>
                    <div class = "form-group hiden">
                        <div class="col-md-6 hiden">
                            <label class="control-label">Discount (Other)<span class="red"> *</span></label>   
                            <input type="text" class="form-control" id="discount_other" name="discount_other" value="0"/>
                        </div>
                        <div class="col-sm-6 hiden">
                        <label class="control-label">{{ trans('loan.clearance_amount')}}</label>
                            <input type="text" class="form-control" id="clearance_amount" name="clearance_amount" value="0"/>
                        </div>
                    </div>
                    <div class = "form-group hiden">   
                        <div class="col-md-6">
                            <label class="control-label">Select Financier Type<span class="red"> *</span></label>                                      
                            
                                <select id="payment_option_type" name="payment_option_type" required class = "select2_type customer_select1" style="width: 100%">
                                    <option value=""> - </option>
                                </select>
                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>                                
                            
                        </div>   
                        <div class="col-md-6">
                        <label class="control-label" style="width: 100%;text-align: left;">Status<span class="red"> *</span></label> 
                            <!-- <div class="form-check form-check-inline">                                    
                                    <input  class="form-check-input" type="radio" id="new_sale" name="sale_status" value="New_Sale" checked>
                                    <label class="form-check-label" for="inlineRadio1">New Sale</label>
                            </div> -->
                                    <div class="form-check form-check-inline">
                                    
                                    <input  class="form-check-input" type="radio" id="change_unit" name="sale_status" value="Change_Unit" checked>
                                    <label class="form-check-label" for="inlineRadio1">Change Unit</label>
                                    </div>
                                    
                                </div>                                                              
                            
                        </div>                                     
                        <div class="col-md-6" style="display: none;">
                            <label class="control-label">Selected Payment Option</label>                                      
                            
                                <select id="payment_option" name="payment_option"  class = "select2_type customer_select1" style="width: 100%">
                                    <option value=""> - </option>
                                </select>
                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                
                            
                        </div>
                    </div>
                    <div class = "form-group" style="display: none;">
                        <div class="col-md-6">
                            <label class="control-label">Discout % (Payment Option)<span class="red"> *</span></label>
                            <input type="text" class="form-control" id="discount_payment_option" name="discount_payment_option" value="0" readonly/>
                        </div>  
                        <div class="col-md-6">
                            <label class="control-label">Discount $ (Pyment Option)<span class="red"> *</span></label>                       
                            <input type="text" class="form-control" id="amount_discount_payment_option" name="amount_discount_payment_option" value="0" readonly/>
                        </div>                        
                    </div>
                    <div class="form-group"> 
                        <div class="col-md-12">
                            <label class="control-label">Sale Person</label>
                            <select id="sale_person_parent_l3" style="width: 100%" name="sale_person">
                            <option value="">-</option>
                                @foreach($saleRepresentative as $c)
                                    <option value={{$c['id']}} @if($sacc->co==$c['id']) selected="selected" @endif>{{$c['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>                  
                    <div class = "form-group">                       
                        <div class="col-md-12">
                            <label class="control-label">Remark</label>
                            <textarea name="remark" id="remark" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class = "form-group">
                        <div class="col-sm-6 text-right">
                            <label class="control-label">Price After Discount<span class="red"> *</span></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="price_after_discount" name="price_after_discount" readonly value="0"/>
                        </div>
                    </div>
                    <div class = "form-group">
                        <div class="col-sm-6 text-right">
                            <label class="control-label">Deposit Amount<span class="red"> *</span></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="diposit_amount" name="diposit_amount" value="0"/>
                        </div>
                    </div>
                    <div class = "form-group">
                        <div class="col-sm-6 text-right">
                            <label class="control-label text-right">Final Price<span class="red"> *</span></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="final_price" name="final_price" readonly value="0"/>
                        </div>
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
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js', isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js', isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/select2_v4.1.0/select2.min.js',isset($secure) ? false : false)}}"></script>

<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',false) }}"></script>
   
   <script type = "text/javascript">
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });

        var clients = <?php echo json_encode($clients);?>;
        var branches = <?php echo json_encode($branches);?>;
        $(document).ready(function () {
        var from_unit_id = '<?php echo $from_unit_id;?>';


    $("#from_unit_id").select2({
        minimumInputLength: 0,
        placeholder: "Select Unit",
         allowClear: true,
        ajax:{
            url: '{{ route('get_project_unit_unittype_with_loan_all_status') }}',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            timeout: 3000,
            data: function (term) {
                return {
                    search_unit: term.term
                }
            },
            processResults: function(data) {
                if(data.unit){
                    return {
                        results: 
                        $.map(data.unit, function (vals,keys){
                            return {
                                text: vals.code+'('+vals.price+')',
                                slug: vals.code,
                                id: vals.id,
                                name:'from_unit_id'
                            }
                        })
                    }; 
                }else{
                    $('<div id="loading"></div>').appendTo('body');
                    imgLoading(true,'Permission denied!!!',4,'warning');
                    return
                }
            },
        }
    });

            $("#project_id").select2();
            $("#unit_type_id").select2();
            $("#unit_id").select2();
            $("#branch").select2();
            $("#currency").select2();
            $("#payment_option_type").select2();
            // $('#payment_option').addClass('required');
            $("#payment_option").select2();
            $("#project_id").select2();
            $("#unit_type_id").select2();
            $("#co_name").select2();
            $("#sale_person_parent_l3").select2();
            $('#payment_option').on('change', function (e) {
                if ((e.val).length > 0) {
                    $(this).parent().find('label.error').css({'display': 'none'});
                    $(this).parent().find('.error').removeClass('error').addClass('valid');
                }
            });

            var customer_id = '<?php echo $customer_id;?>';
            $("#customer_id").select2({
                minimumInputLength: -1,
                placeholder: "Select Customer ID",
                data:[{id: customer_id,text:'<?php echo $client->client_name.'('.$client->cus_acc.')';?>'}],
                allowClear: true,
                ajax:{
                    url: '/teller/get-client',
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    timeout: 3000,
                    data: function (term) {
                        return {term: term.term}
                    },
                    processResults: function(data) {
                        if(data){
                            return {
                                results: $.map(data, function (vals,keys) {
                                    return {
                                        text: vals.client_name+' ( ' + vals.cus_acc + ')',
                                        slug: vals.client_name,
                                        id: vals.id,
                                        name:'customer_id'
                                    }
                                })
                            }; 
                        }else{
                            $('<div id="loading"></div>').appendTo('body');
                            imgLoading(true,'Permission denied!!!',4,'warning');
                            return
                        }
                    }
                }
            });
            $("#customer_id").val(customer_id).trigger('change');
            $('#frmSale').validate({
            rules:{
                client_id : {
                    required: true
                },
                branch: true
            },
            messages:{
                client_id:{
                    required: "Please select a customer"
                },
                branch:{
                    required: "Please select a Company"
                }
            },submitHandler:function(){
               if(confirm("Are you sure?")) {
                   $('#loading').remove();
                   $('<div id="loading"></div>').appendTo('body');
                   imgLoading(true,'Loading...',5,'warning');

                   $.ajax({
                      url: "add",
                       method: 'post',
                       dataType: 'json',
                       timeout: 3000,
                       headers: {
                           'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                       },
                       data: $('#frmSale').serialize() + '&_token=' + $('meta[name="_token"]').attr('content'),
                       success: function (data, status) {
                        if(data.success){
                            window.location.href = "/sale/list"; 
                        }
                        // window.location.href = "/list"; 
                        // window.location.reload();

                       }, error: function (xhr, status, errorThrown) {
                           xhr.status;
                           xhr.responseText;
                       }
                   })
               }
            }
        });

        });
        
        $(document).on('change', '#discount_other', function () {
            calculatePrice();
        });
        $(document).on('change', '#clearance_amount', function () {
            calculatePrice();
        });
        

        $(document).on('change', '#diposit_amount', function () {
            calculatePrice();
        });
        $("#branch").on('change', function () {
            getProjectByCompany();
        });
        $(document).on('change', '#project_id', function () {
            getUnitType();
        });
        $(document).on('change', '#unit_type_id', function () {
            getUnitByUnittype();
            
        });
        $(document).on('change', '#unit_id', function () {
            getUnitInfo();
            $.ajax({
                url: 'get-payment-term',
                type: 'GET',
                dataType: "json",
                success: function (data) {                   
                        $('#payment_option_type').html(data.option);
                        $("#payment_option_type").select2();     
                        $("#payment_option_type").val(1).trigger('change');                  
                }
            });
        });
        $(document).on('change', '#payment_option', function () {
            var payment_option = $(this).val();
            getPaymentOption(payment_option);
            calculatePrice();
            
            });
            $(document).on('change', '#payment_option_type', function () {
                var payment_option_type = $(this).val();
                // if(payment_option_type==1){
                //     $("#payment_option").attr('required',true);
                // }else{
                //     $("#payment_option").attr('required',false);
                // }
            });
            $(document).on('change', '#sale_person_parent_l1', function() {
                var sale_person_id = $(this).val();
                $.ajax({
                    url: '/sale/getSalePersonParent/2',
                    type: 'GET',
                    dataType: "json",
                    data:{sale_person_id:sale_person_id},
                    success: function (data) {
                        if(data){
                            $('#sale_person_parent_l2').html(data.option);
                            $("#sale_person_parent_l2").select2();
                        }                 
                    }
                });
            });
            $(document).on('change', '#sale_person_parent_l2', function() {
                var sale_person_id = $(this).val();
                $.ajax({
                    url: '/sale/getSalePersonParent/3',
                    type: 'GET',
                    dataType: "json",
                    data:{sale_person_id:sale_person_id},
                    success: function (data) {
                        if(data){
                            $('#sale_person_parent_l3').html(data.option);
                            $("#sale_person_parent_l3").select2();
                        }                 
                    }
                });
            });

            function getPaymentOption(payment_option){
            var unit_id = $('#unit_id').val();
            var interest_per_year = 0;
            $.ajax({
                url: '/getPaymentOption',
                type: 'GET',
                dataType: "json",
                // processData: false,
                // contentType: false,
                data:{payment_option:payment_option,unit_id:unit_id},
                success: function (data) {
                    var discount_payment_option = $('#discount_payment_option');
                    if(data == null){
                        discount_payment_option.attr('readonly',false).val(0);
                    }else{                       
                        discount_payment_option.attr('readonly',true);                       
                        $('#discount_payment_option').val(data.special_discount);

                        if(data.payment_option_type){
                            $('#payment_option_type').val(data.payment_option_type);
                        }
                    }
                    calculatePrice();
                }
            });
        }
        function getProjectByCompany(){
            var company_id = $('#branch option:selected').attr('company-id');
            $.ajax({
                url: '/getProjectByCompany',
                type: 'GET',
                dataType: "json",
                // processData: false,
                // contentType: false,
                data:{company_id:company_id},
                success: function (data) {
                    $('#project_id').html(data.option);
                    $("#project_id").select2();
                }
            });
        }
        function getUnitType(){
            var project_id = $('#project_id').val();
            $.ajax({
                url: '/getUnitType',
                type: 'GET',
                dataType: "json",
                // processData: false,
                // contentType: false,
                data:{project_id:project_id},
                success: function (data) {
                    $('#unit_type_id').html(data.option);
                    $("#unit_type_id").select2();
                }
            });
        }
        
        function getUnitInfo(unit_id){
            var unit_id = $('#unit_id').val();
            $('#discount_payment_option').val(0);
            $.ajax({
                url: '/getUnitInfo',
                type: 'GET',
                dataType: "json",
                // processData: false,
                // contentType: false,
                data:{unit_id:unit_id},
                success: function (data) {
                    $('#payment_option').html(data.option);
                    $("#payment_option").select2();
                   $('#unit_code').val(data.unitInfo.code);
                    $('#unit_sale_price').val(data.unitInfo.price);
                    $('#discount_promotion').val(data.promotion);
                    calculatePrice();
                }
            });
        }
        function getUnitByUnittype(){
            var unit_type_id = $('#unit_type_id').val();
            $.ajax({
                url: '/getTillerUnitByUnittype',
                type: 'GET',
                dataType: "json",
                // processData: false,
                // contentType: false,
                data:{unit_type_id:unit_type_id},
                success: function (data) {
                    $('#unit_id').html(data.option);
                    $("#unit_id").select2();
                }
            });
        }

        function getClient($this) {

            $.ajax({
                url: '/accounting/getCLientAccountType/' + $this.val(),
                type: 'json',
                method: 'get',
                headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                contentType: false,
                cache: false,
                processData: false,
                data: {
                    _token: $('meta[name=_token]').attr('content'),
                },
                beforeSend: function () {
                    $this.attr('disabled', true);
                },
                success: function (data, status) {
                    $this.attr('disabled', false);
                    if (typeof status != 'undefined' && status == 'success') {
                        var htmls = '', i = 1;

                        $.each(data, function (inx, vals) {

                            if(inx == 'clients') {
                                $.each(vals, function (inx, valss) {

                                    var name ='N/A', gender= 'N/A',contact = 'N/A';
                                    if (!$.isEmptyObject(valss.general)) {
                                        $.each(valss.contact, function (inx, contacts) {
                                            contact = contacts.contact_number_number;
                                        });
                                    }
                                    if (!$.isEmptyObject(valss.general)) {

                                        $.each(valss.general, function (inx, general) {

                                            name = general.family_name + ' ' + general.first_name;

                                            if(general.applicant_type != 'G') {
                                                htmls += '<tr>';
                                                htmls += '<td><a target="_blank" href="/'+'client/detail'+'/'+valss.id+'">' + i + '</a></td>';
                                                htmls += '<td><a target="_blank"  href="/'+'client/detail'+'/'+valss.id+'">' + name + '</a></td>';
                                                htmls += '<td><a target="_blank"  href="/'+'client/detail'+'/'+valss.id+'">' + general.gender + '</a></td>';
                                                htmls += '<td><a target="_blank"  href="/'+'client/detail'+'/'+valss.id+'">' + contact + '</a></td>';
                                                htmls += '<td><input name="sub_client_id[]" class="form-control sub_client_id" type="checkbox" value="'+valss.id+'"  style="height:30px; width: 30px;" /></td>';
                                                htmls += '</tr>';
                                                i++;

                                            }
                                        });
                                    }
                                });
                            }
                        });
                        $(htmls).appendTo('#_cust_type_result');

                    }

                },
                error: function (requestObject, error, errorThrown) {

                    switch (requestObject.status) {
                        case 401:
                            if (confirm('User\'s session was expired, Please click OK to login again')) {
                                window.location.href = '/user/login';
                            }
                            break;
                        case 500:
                            if (confirm('Internal Server Error, Please contact your technical ')) {
                                window.location.reload()
                            }
                            break;
                        default:
                            console.log(requestObject);
                    }
                }
            });
        }
        function calculatePrice(){
            var down_payment_type = $('#down_payment_type').val();
            var unit_sale_price = $('#unit_sale_price').val();
            var discount_promotion = $('#discount_promotion').val();
            var discount_other = $('#discount_other').val();
            var down_payment_value = $('#discount_payment_option').val();
            var discount_payment_option = $('#discount_payment_option').val();
            var down_pay = $('#down_payment').val();
            var clearance_amount = $('#clearance_amount').val();
            var diposit_amount = $('#diposit_amount').val();
            if(!unit_sale_price){
                unit_sale_price = 0;
            }
            if(!discount_promotion){
                discount_promotion = 0;
            }
            if(!discount_other){
                discount_other = 0;
            }
            if(!down_payment_value){
                down_payment_value = 0;
            }
            if(!discount_payment_option){
                discount_payment_option = 0;
            }
            if(!diposit_amount){
                diposit_amount = 0;
            }
            if(!clearance_amount){
                clearance_amount = 0;
            }
            var amount_discount_payment_option = 0;
            var price_after_discount = 0;
            var price_after_discount = 0;
            var final_price = 0;
            var last_price = 0;
            var total_down_payment = 0;
            var total_down_payment_final = 0;

            price_after_discount = parseFloat(unit_sale_price) - (parseFloat(discount_promotion) + parseFloat(discount_other));
            $('#price_after_discount').val(parseFloat(price_after_discount).toFixed(2));

            amount_discount_payment_option = parseFloat(price_after_discount) * (parseFloat(discount_payment_option) / 100);
            $('#amount_discount_payment_option').val(parseFloat(amount_discount_payment_option).toFixed(2));

            final_price = parseFloat(price_after_discount) - parseFloat(amount_discount_payment_option);
            final_price = parseFloat(final_price) - parseFloat(clearance_amount);

            $('#price_after_discount').val(parseFloat(final_price).toFixed(2));


            var grand_total = parseFloat(final_price);

            $('#final_price').val(parseFloat(grand_total).toFixed(2));
            
        }


    </script>
@endsection
