@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" />
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
             <header class="panel-heading">
               {{ trans('dealer.del_edit_dealer') }}
             </header>
             <div class="panel-body">
                @if($errors->has())
                    <div class="alert alert-danger fade in">
                        <button class="close close-sm" data-dismiss="alert">x</button>
                        {!! HTML::ul($errors->all()) !!}
                    </div>
                @endif
                @if(Session::has('msg'))
                    <div class="alert alert-success fade in">
                        <button class="close close-sm" data-dismiss="alert">x</button>
                        {{ Session::get('msg') }}
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="alert alert-danger fade in">
                        <button class="close close-sm" data-dismiss="alert">x</button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                <form class="cmxform form-horizontal" method="post" action="{{ route('dealer_loan',[$loan->id])}}" id="fileupload" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_name') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <select name="selName" class="form-control" readonly>
                                <option value="{{ $dealer->id }}">{{ $dealer->dealer }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_account_name') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <select name="selBank" class="form-control">
                                <option value="">-</option>
                                @foreach($dealer_bank as $db)
                                    <option value="{{ $db->bank->id }}" {{ isset($loan_dealer)?$loan_dealer->bank_id==$db->bank->id?'selected':'':'' }}>{{ $db->bank->bank_name }} ({{ $db->bank->account_number }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_sender') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <select name="selSender" class="form-control">
                                <option value="">-</option>
                                @foreach($senders as $s)
                                    <option value="{{ $s->name }}" {{ isset($loan_dealer)?$loan_dealer->sender==$s->name?'selected':'':'' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_transfer_date') }} <span class="red-color">*</span></label>
                        <div class="input-append date dpYears col-sm-3" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}">
                            <input type="text" value="{{ isset($loan_dealer)?$loan_dealer->transfer_date:date('Y-m-d') }}"  class="form-control" name="dpTransfer" id="dpTransfer">
                                <span class="add-on offonDatepicker">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_transfer_amount') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="ipTransferAmount" value="{{ isset($loan_dealer)?$loan_dealer->transfer_amount:$loan->loan_amount }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('loan.l_disburse_date') }}<span class="red-color">*</span></label>
                        <div class="input-append date dpYears col-sm-3" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}">
                            <input type="text" value="{{ isset($loan_dealer)?$loan_dealer->disbursement_date:date('Y-m-d') }}" class="form-control" name="dpDisbursement" id="dpDisbursement">
                                <span class="add-on offonDatepicker">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('loan.l_disburse_amount') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="ipDisburseAmount" value="{{ isset($loan_dealer)?$loan_dealer->disbursement_amount:$loan->loan_amount }}" placeholder="Enter disbursement amount">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_receipt') }} </label>
                        <div class="col-sm-6">
                            <span class="btn btn-success btn-sm btn-file">
                                @if(!empty($loan_dealer))
                                    <i class="glyphicon glyphicon-refresh"></i> {{ trans('multiple.m_change') }}
                                @else
                                    <i class="glyphicon glyphicon-plus"></i> {{ trans('multiple.m_receipt') }}
                                @endif
                                <input type="file" name="files[]" id="files" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/bmp">
                            </span>
                            <span class="reset"></span><span class="default"></span>
                            <table class="table table-striped"><tbody class="files"></tbody></table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3"></label>
                        <div class="col-sm-6">
                            <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_save') }}</button>
                            <a href="javascript:history.go(-1);" class="btn btn-danger"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;{{ trans('multiple.m_cancel') }}</a>
                        </div>
                        <br/><br/>
                    </div>
                </form>
             </div>
        </section>
    </div>
</div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    @if(empty($loan_dealer))
        <script src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
    @else
        <script>
            $("#fileupload").validate({
                rules:{
                    ipDealer:{
                        required: true
                    },
                    selBank:{
                        required: true
                    },
                    selSender:{
                        required: true
                    },
                    dpDisbursement:{
                        required: true
                    },
                    dpTransfer:{
                        required: true
                    },
                    ipDisburseAmount:{
                        required: true
                    },
                    ipTransferAmount:{
                        required: true
                    }
                },
                messages:{
                    ipDealer:{
                        required: "Dealer Name is required."
                    },
                    selBank:{
                        required: "Bank Account is required."
                    },
                    selSender:{
                        required: "Sender is required."
                    },
                    dpDisbursement:{
                        required: "Disbursement Date is required."
                    },
                    dpTransfer:{
                        required: "Transfer Date is required."
                    },
                    ipDisburseAmount:{
                        required: "Disbursement Amount is required."
                    },
                    ipTransferAmount:{
                        required: "Transfer Amount is required."
                    }
                }
            });
        </script> 
    @endif
    <script>

        $(document).ready(function(){
            $('.dpYears').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
        });
        function load_receipts(){
            <?php
                $tr = "";
                if(!empty($loan_dealer)){
                    $receipts = explode("|", $loan_dealer->dealer_receipt);
                    for($i=0;$i<count($receipts)-1;$i++){
                        $tr .= '<tr><td><img src="/data/receipts/'.$receipts[$i].'" width="100"/></td>';
                        $tr .= '<td>'. $receipts[$i].'<p>'.date("F d Y",filemtime(public_path("data/receipts/".$receipts[$i]))).'</p></td>';
                    }
                }
            ?>
            var tr = '<?php echo $tr;?>';
            $('.files').html(tr);
        }
        var loan_dealer = '<?php echo isset($loan_dealer)?$loan_dealer:""; ?>';
        if(!$.isEmptyObject(loan_dealer)){
            load_receipts();
        }
        function readURL(input) {
            for(var i = 0; i<input.files.length;i++){
                if (input.files && input.files[i]) {
                    var reader = new FileReader();
                    var file = input.files[i];
                    reader.onload = (function(f){
                                        return function(e){
                                                var date = f.lastModifiedDate;
                                                var tr = '<tr>'
                                                           +'<td><img src="'+ e.target.result + '" width="100"/></td>'
                                                           +'<td>'+ f.name +'<p>'+ Math.round((f.size/1000)*100)/100 +' KB</p></td>'
                                                           +'<td>'+ date.getFullYear() + "/" + (date.getMonth()+1) + "/" + date.getDate()  +'</td>'
                                                           +'</tr>';
                                                $('.files').append(tr).hide().fadeIn(500); 
                                        };
                                    })(file); 

                    reader.readAsDataURL(file);
                }
            }
            
        }
        $("input:file").change(function (){
            if(this.files[0].name!=""){
                $(this).attr("class","valid");
                $(this).parent().find(".error").css({"display":"none"});
            }
            if(!$.isEmptyObject(loan_dealer)){
                $('.files').html('');
                $('.default').html('<button type="button" class="btn btn-warning btn-sm btnDefault"><i class="fa fa-undo"></i></button>');
            }else{
                $('.reset').html('<button type="button" class="btn btn-warning btn-sm btnReset"><i class="fa fa-reply"></i>{{ trans('multiple.m_reset')}}</button>');
                $('.btn-file').attr('disabled', true);
            }
            readURL(this);
        });
        $(document).on('click','.btnDefault',function(){
            $('#files').val('');
            $('.default').html('');
            load_receipts();
        });
        $(document).on('click','.btnReset',function(){
            $('#files').val('');
            $('.files').fadeOut(500, function(){
                $(this).html('');
            });
            $('.reset').html('');
            $('.btn-file').attr('disabled', false);
        });
    </script>
    
@endsection