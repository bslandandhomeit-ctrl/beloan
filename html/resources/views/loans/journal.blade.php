@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false)}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <link href="{{ asset('theme/select2_v4.1.0/select2.min.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
@section('content')
<style type="text/css">
    .contract_id1,.contract_id2{
        width: 120px !important;
    }
</style>
    <section class="panel">
        <header class="panel-heading">
            {{ trans('sidebar.sb_add_journal') }}
        </header>
        <div class="panel-body">
            @if(Session::has('msg'))
                <div class="alert alert-success fade in">
                    <button class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('msg') }}
                </div>
            @endif
            @if($errors->has())
                <div class="alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    {{ HTML::ul($errors->all()) }}
                </div>
            @endif
            <form class="cmxform form-inline" role="form" method="post" action="{{ route('add_journal',[isset($id)?$id:0]) }}" id="frmJournal" style="margin:0 auto;width:90%;" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <?php
                    $trans_type = Config::get('static_data.trans_type');
                    $account_type = Config::get('static_data.account_type');
                    $currency = Config::get('static_data.currency');
                    isset($id)?$id:$id=0;
                    $init_branch = [];
                    $selected_branch = 0;
                    $selected_branch_code = "";
                    $id_flag = 0;
                ?>
                <div id="initRow">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="control-label">{{ trans('report.rpt_transaction_id') }}</label><br/>
                                @foreach($trans as $tran)
                                    <?php
                                      if($id == $tran->id){
                                        $selected_branch = $tran->loan->company_branch_id;
                                        $id_flag = 1;
                                      }
                                    ?>
                                @endforeach
                                <select name="selTran[]" id="dTran" class="form-control" onchange="loadTran(this)"  style="min-width: 120px;">>
                                    @if($id_flag == 0)
                                        <option value="0">-</option>
                                        @foreach($trans as $tran)
                                            <option value="{{ $tran->id }}">{{ str_pad($tran->id,8,'0',STR_PAD_LEFT)}}</option>
                                        @endforeach
                                    @else
                                        <option value="{{ $tran->id }}">{{ str_pad($tran->id,8,'0',STR_PAD_LEFT)}}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ trans('report.rpt_entry_number') }}</label><br/>
                                <input type="text" readonly value="{{ isset($entry_no)?str_pad($entry_no,7,0,STR_PAD_LEFT):0 }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Branch</label><br/>
                                <select name="branch" id="branch" class="form-control"  style="min-width: 120px;">
                                    @if($id_flag == 0)
                                        <option value = "">-</option>
                                        @foreach($branches as $b)
                                            <option value="{{ $b->branch_code }}">{{ $b->branch_name }}</option>
                                        @endforeach
                                    @else
                                        @foreach($branches as $b)
                                            <option value="{{ $b->branch_code }}" @if($b->id == $selected_branch) selected @endif>{{ $b->branch_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Currency</label><br/>
                                <select name="currency" id="curren" class="form-control"  style="min-width: 120px;">
                                    <option value="0">-</option>
                                    @foreach($currency as $key=>$val)
                                        <option value="{{ $key }}">{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Types </label><br/>
                                <select name="ref_name_type" id="ref_name_type" class="form-control"  style="min-width: 120px;">
                                    <option value="0">-</option>
                                    <?PHP
                                        $ref_name_type = Config::get('static_data.ref_name_type');
                                        foreach($ref_name_type as $k=>$val){
                                            echo '<option value="'.$k.'">'.$val.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Ref. Name</label><br/>
                                <select name="referral_name" id="referral_name" class="form-control" style="min-width: 240px; max-width: 240px;"></select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ trans('report.rpt_entry_date') }}</label><br/>
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d H:i:s')}}" class="input-append date dpYears">
                                    <input type="text" name="entry_date" value="{{date('Y-m-d H:i:s')}}" class="form-control" id="entry_date">
                                    <span class="add-on birhtdateDatepicker"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        if($id_flag == 1){
                            foreach($branches as $b){
                                if($selected_branch == $b->id){
                                    $selected_branch_code = $b->branch_code;
                                }
                            }
                        }
                    ?>
                    <div class="row mt-1 debit">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="control-label">{{ trans('report.rpt_debit') }} &nbsp;&nbsp;&nbsp;</label>
                            </div>
                            <div class="form-group">
                                <select class="js-data-example-ajax selType1" name="selType[]" style="width: 550px !important;"></select>
                            </div>
                            <select class="js-data-example-ajax contract_id1" name="contract_id[]"></select>
                            <div class="form-group">
                                <input type="text" name="ipDebit[]" id="dDebit" class="form-control" placeholder="{{ trans('report.rpt_debit') }}">
                                <input type="hidden" name="ipCredit[]" id="cCredit" value="0">
                            </div>
                            <div class="form-group">
                                <input type="text" name="ipDesc[]" id="dDesc" class="form-control" placeholder="{{ trans('multiple.m_description') }}">
                            </div>
                            <div class="form-group divRemove">
                                <a href="javascript:;" class="clsDd"><i class="fa fa-plus-square fa-action"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1 credit">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="control-label">{{ trans('report.rpt_credit') }} &nbsp;&nbsp;</label>
                            </div>
                            <div class="form-group">
                                <select class="js-data-example-ajax selType2" name="selType[]" style="width: 550px !important;"></select>
                            </div>
                            <select class="js-data-example-ajax contract_id2" name="contract_id[]"></select>
                            <div class="form-group">
                                <input type="hidden" name="ipDebit[]" id="dDebit" value="0">
                                <input type="text" name="ipCredit[]" id="cCredit" class="form-control" placeholder="{{ trans('report.rpt_credit') }}">
                            </div>
                            <div class="form-group">
                                <input type="text" name="ipDesc[]" id="cDesc" class="form-control" placeholder="{{ trans('multiple.m_description') }}">
                            </div>
                            <div class="form-group divRemove">
                                <a href="javascript:;" class="clsCd"><i class="fa fa-plus-square fa-action"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 row">
                        <div class="form-group">
                            <label class="control-label">{{ trans('report.rpt_invoice_number') }}</label>
                            <input type="text" name="invoice_number" class="form-control">
                            <label>Specify user for notifying</label>
                            <input type="checkbox" name="usertype" id="usertype" class="form-control"/>
                            <label>Choose a user </label>
                            <select id="chooseuser" class="select2_type " name="notify_user" style="min-width: 300px">
                                <option value=""> --- </option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 row">
                        <label class="control-label">{{ trans('report.rpt_receipt') }}</label>
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="{{ asset('images/noimage.gif', isset($secure)?false:false) }}" alt="" />
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                            <div>
                                <span class="btn btn-white btn-file">
                                    <span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{ trans('multiple.m_receipt') }}</span>
                                    <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                                    <input type="file" name="receipt" id="receipt" class="default" />
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-12 form-group">
                            <label class="control-label">{{ trans('multiple.m_description') }}</label><br/>
                            <textarea class="form-control" name="txtDesc[]" id="txtDesc" style="width:100%;" placeholder="{{ trans('report.rpt_enter_entry_description') }}"></textarea>
                        </div>
                    </div>
                    <hr/>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp;{{ trans('multiple.m_save') }}</button>
                    <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> &nbsp;{{ trans('multiple.m_reset') }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/select2_v4.1.0/select2.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script>
        $(document).ready(function () {
            setTimeout(function() {
        var allow_postback_date = '<?php echo $tellers->allow_postback_date ;?>';
        if(allow_postback_date!=='1'){
            $("#entry_date").attr("disabled", "disabled");
            }

            }, 250);
        });
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
        $('#usertype').on('change',function() {
            $.ajax({
                url:window.location.href,
                method:'get',
                dataType:'json',
                data:{
                    usertype : this.checked
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                },
                beforeSend:function(){
                    $('#usertype').attr('disabled', true);
                    $('#chooseuser option').remove();
                },
                success : function(data) {
                    var htmls = '<option value="">---</option>';
                    $.each(data, function(inx, vals){
                        htmls += '<option value="'+vals.id+'">'+vals.name+'</option>';
                    });
                    $(htmls).appendTo('#chooseuser');
                    $('#chooseuser').select2({theme: "classic"});
                    $('#usertype').attr('disabled', false);
                }
            });
        });
        $(document).on('click','a.clsDd', function(){
            addRowDC($(this),"clsDebit","clsDd");
        });
        $(document).on('change','#ref_name_type', function () {
            if($(this).is('#ref_name_type')) {
                get_user_referal($(this).val());
            }
        });
        $(document).on('click','a.clsCd', function(){
            addRowDC($(this),"clsCredit","clsCd");
        });

        function get_user_referal(ref_name_type){
            $.ajax({
                url: "/loans/accounting/get_user_referral/"+parseInt(ref_name_type),
                method: 'get',
                dataType: 'json',
                timeout: 3000,
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                },
                success: function (data, status) {
                    if(status === 'success') {
                        var option = '';
                        if(!$.isEmptyObject(data.vendor)){
                            $.each(data.vendor, function(ins, val){
                                option += '<option value="'+val.id+'">'+val.fname+' '+val.lname+'</option>';
                            })
                        }
                        if(!$.isEmptyObject(data.customer)){
                            $.each(data.customer, function(inx, val){
                                option += '<option value="'+val.id+'">'+val.fname+' '+val.lname+'</option>';
                            })
                        }
                        if(!$.isEmptyObject(data.staff)){
                            $.each(data.staff, function(inx, val){
                                option += '<option value="'+val.id+'">'+val.name+'</option>';
                            });
                        }
                        return $('#referral_name').html(option)
                    }
                }
            })
        }

        function addRowDC(initRow, cls, clsDel){
            initRow = initRow.parent().parent().parent();
            $(".selType1").select2('destroy');
            $(".selType2").select2('destroy');
            $('.contract_id1').select2('destroy');
            $('.contract_id2').select2('destroy');
            var newRow = initRow.clone().addClass(cls + " mt-1");
            newRow.find('input:text').val('');
            newRow.find('select').prop('selectedIndex',0);
            if(initRow.parent().find("." + cls).length>0){
                var last_row = initRow.parent().find("." + cls).last();
                newRow.insertAfter(last_row);
            }else{
                newRow.insertAfter(initRow);
            }
            $divRemove = newRow.find('.divRemove');
            var delBtn = $('<a href="javascript:;" class="delBtn"><i class="fa fa-trash-o fa-action"></i></a>');
            $divRemove.append(delBtn).on('click','a.delBtn',function(){
                newRow.remove();
            });
            $divRemove.find('.' + clsDel).remove();
            newRow.find(".selType1").select2().val(null).trigger("change");
            newRow.find(".selType2").select2().val(null).trigger("change");
            newRow.find(".contract_id1").select2().val(null).trigger("change");
            newRow.find(".contract_id2").select2().val(null).trigger("change");
            select_coa();
            get_contract();
        }
        $("#branch").on('change', function(){
            select_coa();
        });
        $("#curren").on('change', function(){
            select_coa();
        });
        function select_coa(){
            var branch_code = $("#branch").val();
            var currency_code = $("#curren").val();
            get_account(branch_code,currency_code);
            get_contract();         
        }
        $(".select2_type").select2({theme: "classic"});
        get_account(null,null);
        function get_account(branch_code,currency_code){
            $(".selType1,.selType2").select2({
                minimumInputLength: 0,
                placeholder: "{{trans('dealer.dl_dealer_account_number')}}",
                theme: "classic",
                ajax:{
                    url: '{{ route('get_journal_data') }}',
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    timeout: 3000,
                    data: function (term) {
                        return {
                            search_account: term.term,
                            currency_id:currency_code
                        }
                    },
                    processResults: function(data) {
                        if(data.account){
                            return {
                                results: 
                                $.map(data.account, function (vals,keys){
                                    return {
                                        text: branch_code +'-'+ vals.account_code +'('+vals.name+')',
                                        slug: vals.name,
                                        id: vals.id,
                                        name:'selType[]'
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
        }
        get_contract();
        function get_contract(){
            $(".contract_id1,.contract_id2").select2({
                minimumInputLength: 0,
                placeholder: "Contract ID",
                theme: "classic",
                ajax:{
                    url: '{{ route('get_journal_data') }}',
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    timeout: 3000,
                    data: function (term) {
                        return {contract_id: term.term};
                    },
                    processResults: function(data) {
                        if(data.loans){
                            return {
                                results: 
                                $.map(data.loans, function (vals,keys){
                                    return {
                                        text: vals.contract_id,
                                        slug: vals.contract_id,
                                        id: vals.id,
                                        name:'contract_id[]'
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
        }
    </script>
 @endsection
