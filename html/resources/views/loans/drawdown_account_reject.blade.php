@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('loan.l_reject_loan') }}
        </header>
        <div class="panel-body">
            @if($errors->has())
                <div class="alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    {{ HTML::ul($errors->all()) }}
                </div>
            @endif
            <form class="cmxform form-horizontal"  id="frmReject">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label class="col-sm-3 control-label"> {{ trans('loan.l_reject_on') }} <span class="red">*</span></label>
                    <div class="input-append date dpYears col-sm-6" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}">
                        <input type="text" value="{{ date('Y-m-d') }}" class="form-control" name="reject_on" id="reject_on">
                            <span class="add-on offonDatepicker">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('multiple.m_note') }}</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="note" name="note"></textarea>
                    </div>
                </div>
                <!-- Blog Reject -->
                <div id="blog-rejected" style="display: none;">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('loan.l_close_date') }} <span class="red">*</span></label>
                        <div class="input-append date dpYears col-sm-6" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}">
                            <input type="text" value="{{($dpDateClone)?$dpDateClone:date('Y-m-d')}}" class="form-control" name="close_date" id="close_on">
                                <span class="add-on offonDatepicker">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- End -->
                <div class="form-group">
                    <label class="col-sm-3"></label>
                    <div class="col-sm-6">
                        <button class="pull-right btn btn-primary" type="button"  onclick="reject({{ $id}})" ><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_save') }}</button>
                        <a href="javascript:history.back();" class="btn btn-danger"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;{{ trans('multiple.m_cancel') }}</a>
                    </div>
                    <br/><br/>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',false) }}"></script>
    <script type="text/javascript">
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
        function reject(value) {
        var note=$("#note").val();
        if(note==''){
            alert('Please enter reason for reject!');
            return;
        }
        $.ajax({
            url: '/loans/drawdown_account_info/'+value,
            type:'GET',
            dataType: "JSON",
            success:function(data){
                if(data){
                    if(data.drawdown_account.loans.length>0){
                        var con = confirm("This Account is already apply loan! Do you really want to reject?");
                        if(con == true){
                            drawdown_reject(value,data.fee_bal,data.other_fee,data.sch_repay_arr,data.unit_code.code,data.loan,data.drawdown_account.loans[0].id,data.drawdown_account.loans[0].status,data.drawdown_account.unit_type_id,data.drawdown_account.unit_id,note);
                        }                   

                    }else{
                        var con = confirm("Do you really want to reject?");
                        if(con == true){
                            drawdown_reject(value,data.fee_bal,data.other_fee,data.sch_repay_arr,data.unit_code.code,null,null,null,data.drawdown_account.unit_type_id,data.drawdown_account.unit_id,note);
                        }
                    }
                }
            }
        })
}
function drawdown_reject(id,fee_bal,other_fee,sch_repay_arr,unit_code,loan,loan_id,loan_status,unit_type_id,unit_id,note){
        var prin_amount=loan?loan.client_loan_account.balance:0;
        var t_sch_penal=0;
        var air_amount=0;  
        if(sch_repay_arr){
            if(sch_repay_arr.length>0){  
            sch_repay_arr.forEach((ar) => {
                t_sch_penal += parseFloat(ar['penalty']);
            });

        }
        }     

        var total_amount=Math.round((parseFloat(prin_amount) + parseFloat(air_amount) + parseFloat(fee_bal) + parseFloat(t_sch_penal)) *100)/100
        $.ajax({
            url: '/loans/postDrawdownAccountReject',
            type:'POST',
            dataType: "JSON",
            data: {
                id,loan_id,loan_status,unit_type_id,unit_id,
                "_token":'<?php echo csrf_token() ?>',
                "close_date" :  $('#close_on').val(),
                "transType" : "Write-Off",
                "prin_amount" : prin_amount,
                "air_amount" :air_amount,
                "fee_amount" : fee_bal,
                "other_fee_amount" : other_fee,
                "penalty" :t_sch_penal,
                "total_wo_amount" : "0.00",
                "total_amount" :total_amount,
                "note" : note,
                "ex_air_amount" : "0"
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            success:function(data){
                if(data.success=='success'){
                    $.ajax({
                        url:'https://contract.chaktomukcity.com/api/unit_actions/change_unit_statu_from_be_cash',
                        type:'GET',
                        headers: {
                            'Access-Control-Allow-Origin': '*',
                            'Content-Type':'application/json'
                                },
                        dataType: 'jsonp',
                        data:{'code':unit_code,'note':note,type:'Write-Off'},
                        success:function(data){                            
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                    setTimeout(function() { 
                        window.location.href = '/loans/drawdown_account'
                    }, 2000);
                    

                }
            }
    })
}
    </script>
@endsection