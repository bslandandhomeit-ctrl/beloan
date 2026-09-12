@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false)}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <link href="{{ asset('theme/js/iCheck/skins/flat/green.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="panel">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
        @endif
        <header class="panel-heading">
            <span>{{ trans('loan.l_arrears_repayment') }}</span>
            <span style="float: right"><a href="http://www.acledabank.com.kh/kh/eng/ps_cmforeignexchange" onclick="window.open('http://www.acledabank.com.kh/kh/eng/ps_cmforeignexchange', '', 'width=800,height=900'); return false;" class="btn btn-info btn-xs">{{ trans('loan.l_today_foreign_exchange') }}</a></span>

        </header>
        @if($loan->status != 3 && $loan->status != 8)
            {{"Loan can not do repayment."}}
        @else
            <?php
                // Parameter
                $static = config('static_data');
                if(count($repayment_owed_tb) == 0){
                    return;
                }
                $month_index = $repayment_owed_tb[0]->payment_month;
                $m_schedule = LoanCalculate::loan_schedule($loan->schedule,  $loan->start_date,$month_index);
                $repayment_schedule = $m_schedule[0];
                $next_payment_schedule = $m_schedule[1];
            ?>

            <div class="panel-body">
                <form action="{{ route('loan_repayment_owed',[$loan->id,$month_index])}}" class="cmxform form-horizontal" method="post" id="frmRepaymentOwed" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('report.rpt_invoice_number') }}</label>
                        <div class="col-sm-6">
                            <input type="text" name="invoice_number" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('loan.l_repayment_date') }} <span class="red">*</span></label>
                        <div class="input-append date dpYears col-sm-6" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}">
                            <input type="text" value="{{date('Y-m-d')}}" class="form-control" name="repayment_date" id="repayment_date">
                                <span class="add-on offonDatepicker">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('loan.l_pay_for_month') }}# <span class="red">*</span></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="payment_month" id="payment_month"
                                    readonly value = {{$month_index}}
                                />
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('loan.l_principal_amount') }} <span class="red">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="paid_principal" id="principal_amount"
                                value = "0.00"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('report.rpt_interest') }} <span class="red">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="paid_interest" id="interest_amount"
                                value = "0.00"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('report.rpt_penalty') }} <span class="red">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="penalty_amount" id="penalty_amount" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('loan.l_waived_penalty') }}</label>
                        <div class="col-sm-6 icheck">
                           <div class="flat-green single-row">
                               <div class="radio ">
                                   <input type="checkbox" id = "waived-penalty" name="waived-penalty"  value="1"/>
                               </div>
                           </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('loan.l_transaction_amount') }} <span class="red">*</span></label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="transaction_amount" name="transaction_amount"/>
                                <span class="input-group-btn" style="vertical-align:top">
                                    <a class="btn btn-info btn-edit" href="javascript:;">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('loan.l_repayment_owed') }} <span class="red">*</span></label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input type="text" class="form-control" readonly name="repayment_owed" id="repayment_owed" />
                                <span class="input-group-btn" style="vertical-align:top">
                                    <a class="btn btn-info btn-edit" href="javascript:;">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('loan.l_payment_type') }}</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="payment_type" id="payment_type">
                                <option value="0">-</option>
                                @foreach($static['payment_type'] as $key => $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_receipt') }}</label>
                        <div class="col-sm-6">
                              <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                    <img src="{{ asset('images/noimage.gif', isset($secure)?false:false) }}" alt="" />
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                <div>
                                   <span class="btn btn-white btn-file">
                                   <span class="fileupload-new "><i class="fa fa-plus"></i> {{ trans('loan.l_add_new_invoice') }}</span>
                                   <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                                   <input type="file" name="repayment_receipt" id="repayment_receipt" class="default" />
                                   </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_note') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="note" name="note"></textarea>
                        </div>
                    </div>
                    <div  id="condition_input" class="hidden">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('loan.l_condition') }} </label>
                            <div class="col-sm-6">
                                <select class="form-control" name="condition" id="condition" name="condition">
                                    <option value="0">-</option>
                                    @foreach($static['loan_payment_condition'] as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('loan.l_owed_reason') }} </label>
                            <div class="col-sm-6">
                                 <input type="text" class="form-control" id="reason" name="reason" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('report.rpt_action_taken') }} </label>
                            <div class="col-sm-6">
                                 <input type="text" class="form-control" id="action_taken" name="action_taken" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('report.rpt_todo_payment') }}</label>
                            <div class="input-append date dpYears col-sm-6" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}">
                                <input type="text" class="form-control" name="todo_payment" id="todo_payment">
                                    <span class="add-on offonDatepicker">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3"></label>
                        <div class="col-sm-6">
                            <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                            <button type="button" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</button>
                        </div>
                        <br/><br/>
                    </div>
                    <input type = "hidden" name="last_schedule_date" id = "last_schedule_date" value="0">
                    <input type = "hidden" name="late_day" id = "late_day" value="0">
                    <input type = "hidden" name="parc_level" id = "parc_level" value="0">
                    <input type = "hidden" id="waived_penalty" name="waived_penalty" value = "0" />
                </form>
            </div>
        @endif
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script src="{{ asset('theme/js/iCheck/jquery.icheck.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        }).on('changeDate',function(){
            penaltyCal();
            total_transaction = parseFloat($("#penalty_amount").val()) + arrear_amount;
            setData();
        });
        var status = <?php echo $loan->status;?>;
        if(status == 3 || status == 8){
            var repaymentSchData = <?php echo json_encode($repayment_schedule);?>;
            var loanData = <?php echo json_encode($loan);?>;
            var repayment_owed_array = <?php echo json_encode($repayment_owed_tb);?>;
            var repayment_owed_tb = repayment_owed_array[0];
            var holidays = <?php echo json_encode($loan->holiday);?>;
            var HOLIDAY_FLAG = 0; // 0:include holiday, 1:exclude weekends, 2:exclude holidays and weekends
        }
        var total_transaction = 0;
        var arrear_amount = 0;
        var penalty_original = 0;
        var owed_size = 0;
        var waived_penalty = 0;
        $(".btn-edit").on('click',function(){
            $input = $(this).parent().parent().find('input:text');
            $input.attr('readonly',!$input.attr('readonly'));
        });
        $(document).ready(function(){
            owed_size = repayment_owed_array.length;
            // set payment month
            $("#payment_month").val(repayment_owed_tb.payment_month);
            var last_paid_month = repayment_owed_tb.payment_month;
            var total_paid_principal = 0;
            var total_paid_interest = 0;
            for(var i = 0; i < owed_size; i++){
                total_paid_principal += parseFloat(repayment_owed_array[i]['paid_principal']);
                total_paid_interest += parseFloat(repayment_owed_array[i]['paid_interest']);
            }
            var arrear_interest = (parseFloat(repaymentSchData[last_paid_month][2]).toFixed(4) - total_paid_interest.toFixed(4));
            var arrear_principal = (parseFloat(repaymentSchData[last_paid_month][3]).toFixed(4) - total_paid_principal.toFixed(4));
            if(parseInt(arrear_principal*100)==0){
                arrear_principal = 0;
            }
            if(parseInt(arrear_interest*100)==0){
                arrear_interest = 0;
            }
            penalty_original = parseFloat(repayment_owed_array[0]['repayment_owed']) - arrear_interest - arrear_principal;
            if(penalty_original > -1 && penalty_original < 1){
                penalty_original = 0;
            }
//            console.log(arrear_interest);
//            console.log(arrear_principal);
//            console.log(repayment_owed_array);
            waived_penalty = penalty_original;
            $("#principal_amount").val(arrear_principal.toFixed(4));
            $("#interest_amount").val(arrear_interest.toFixed(4));
            arrear_amount = arrear_interest + arrear_principal;
            if(repayment_owed_tb.condition_id == 1){
                month_idx = parseInt($("#payment_month").val());
            }else{ // condition is "Pay next time"
                month_idx = parseInt($("#payment_month").val())+1;
            }
            penaltyCal();
            var penalty =  parseFloat($("#penalty_amount").val());
            total_transaction = penalty +  arrear_amount;
            $("#transaction_amount").val(total_transaction.toFixed(4));
            var repayment_owed = total_transaction - $("#transaction_amount").val();
//            if(parseInt(repayment_owed*100) <= 0){
//                repayment_owed = 0;
//            }
            $("#repayment_owed").val(repayment_owed.toFixed(4));
        });

        $("#penalty_amount").on('change',function(e){
            setData();
            var owed_amount = $("#repayment_owed").val();
            if(owed_amount > 0){
                $("#condition_input").removeClass("hidden");
            }
        });

        $("#repayment_date").on('change',function(e){
            penaltyCal();
            total_transaction = parseFloat($("#principal_amount").val()) + parseFloat($("#interest_amount").val()) + parseFloat($("#penalty_amount").val());
            setData();
        });

        $("#principal_amount").on('change',function(e){
            if(loanData['penalty_rate_type']!=1){
                penaltyCal();
                total_transaction += parseFloat($("#penalty_amount").val());
            }
            setData();
        });
        $("#interest_amount").on('change',function(e){
            if(loanData['penalty_rate_type']!=1){
                penaltyCal();
                total_transaction += parseFloat($("#penalty_amount").val());
            }
            setData();
        });
        $('.icheck input').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        }).on('ifToggled',function(e){
            e.preventDefault();
            var chck = $(this).prop('checked');
            if(chck){
                $("#penalty_amount").val(0);
                $("#penalty_amount").attr('readonly',true);
                $("#waived_penalty").val(waived_penalty.toFixed(4));
            }else{
                $("#penalty_amount").val(waived_penalty.toFixed(4));
                $("#penalty_amount").attr('readonly',false);
                $("#waived_penalty").val(0);
            }
            setData();
        });

        function setData(){
            var penalty = parseFloat($("#penalty_amount").val());
            var arrear_interest = parseFloat($("#interest_amount").val());
            var arrear_principal = parseFloat($("#principal_amount").val());
            var total_pay = penalty + arrear_interest + arrear_principal;
            var chck = $("#waived-penalty").prop('checked');
            var t_trans = total_transaction;
            if(chck){
                t_trans = (t_trans - waived_penalty);
            }
            $("#transaction_amount").val(total_pay.toFixed(4));
            //$("#repayment_owed").val((t_trans - $("#transaction_amount").val()).toFixed(4));
            var repayment_owed = t_trans - $("#transaction_amount").val();
//            if(parseInt(repayment_owed*100) <= 0){
//                repayment_owed = 0;
//            }
            $("#repayment_owed").val(repayment_owed.toFixed(4));
            var penalty_rest = penalty_original - parseFloat($("#penalty_amount").val());
            var owed_amount = $("#repayment_owed").val();
            if(owed_amount > 0){
                $("#condition_input").removeClass("hidden");
            }else{
                $("#condition_input").addClass("hidden");
            }
            if(0 < penalty_rest.toFixed(2)){
                $("#condition").val("Pay with next payment date");
            }
        }

        function penaltyCal(){
            // penalty
            var penalty = 0;
            var late_day = 0;
            var comp_to_sch_flag = 0;
            var select_date = $("#repayment_date").val();
            var sub_day = loanData['penalty_period1'];
            var pay_period = dateDiff(repaymentSchData[month_idx][0], repayment_owed_tb.repayment_date);
            if(pay_period <= sub_day){
//                late_day = dateDiff(repaymentSchData[month_idx][0], select_date);
                late_day = dateDiff_except_holiday(repaymentSchData[month_idx][0], select_date, HOLIDAY_FLAG, holidays); // exclude holiday and weekend
                if(late_day < sub_day){
                    num_day = 0;
                }else{
                    num_day = late_day;
                }
            }else{
//                late_day = dateDiff(repayment_owed_tb.repayment_date, select_date);
                late_day = dateDiff_except_holiday(repayment_owed_tb.repayment_date, select_date, HOLIDAY_FLAG, holidays);
                if(late_day < 0){
                    num_day = 0;
                }else{
                    num_day = late_day;
                }
            }
            if(late_day < 0){
                late_day = 0;
                $("#late_day").val(late_day);
                $("#penalty_amount").val(penalty.toFixed(4));
                waived_penalty = penalty;
               return 0;
            }
            var penalty = 0;
            var late_d = 0;
            var i = month_idx;
            var overdue_days = late_day - loanData['penalty_period1'];
            $("#late_day").val(num_day);
            $("#parc_level").val(getParcLevel(late_day));

            if(loanData['penalty_rate_type']==1){
                    penalty = (num_day) * (loanData['penalty_rate1']/100) * (arrear_amount);
            }else if(loanData['penalty_rate_type']==2){
                if((late_d = dateDiff_except_holiday(repaymentSchData[month_idx][0], select_date, HOLIDAY_FLAG, holidays)) > loanData['penalty_period1']){
                    var paid_amount = parseFloat($("#principal_amount").val()) + parseFloat($("#interest_amount").val()) ;
                    if(paid_amount < arrear_amount){
                        total_transaction = arrear_amount;
                    }
                    penalty = (loanData['penalty_rate1']/100) * (paid_amount);
                }
            }else if(loanData['penalty_rate_type']==3){
                if((late_d = dateDiff_except_holiday(repaymentSchData[month_idx][0], select_date, HOLIDAY_FLAG, holidays)) > loanData['penalty_period1']){
                    var paid_amount = parseFloat($("#principal_amount").val()) + parseFloat($("#interest_amount").val()) ;
                    if(paid_amount < arrear_amount){
                        total_transaction = arrear_amount;
                    }
                    if(late_d > loanData['penalty_period2']){
                        penalty = (loanData['penalty_rate2']/100) * paid_amount;
                    }else if(late_d > loanData['penalty_period1']){
                        penalty = (loanData['penalty_rate1']/100) * paid_amount;
                    }
                }
            }
//            console.log(penalty_original);
//            console.log(penalty);
            penalty += penalty_original;
            var penalty_floor = Math.floor(penalty);
            if(Math.round((penalty - penalty_floor)*100)/100 >= 0.45){
                penalty = Math.ceil(penalty);
            }else{
                penalty = penalty_floor;
            }
            $("#penalty_amount").val(penalty.toFixed(4));
            waived_penalty = penalty;
        }

        function getParcLevel(late_day){
            var parc_lvl = 0;
            if(late_day > 0){
                var t = Math.floor(late_day/7);
                switch(t){
                    case 0: parc_lvl = 0; break;
                    case 1: parc_lvl = 1; break;
                    case 2:
                    case 3: parc_lvl = 2; break;
                    case 4:
                    case 5: parc_lvl = 3; break;
                    case 6:
                    case 7: parc_lvl = 4; break;
                    case 8: parc_lvl = 5; break;
                    default: parc_lvl = 5; break;
                }
            }
            return parc_lvl;
        }
        function getArrear(){
           var arrear = repayment_owed_tb.repayment_owed.toFixed(4);
           //$("#arrear_amount").val(arrear);
            return arrear;
        }
        function dateDiff(start_date, end_date){
            var start_date_sub = start_date.split('-');
            var start_day = new Date(start_date_sub[0], start_date_sub[1]-1, start_date_sub[2]);
            var end_date_sub = end_date.split('-');
            var end_day = new Date(end_date_sub[0],end_date_sub[1]-1,end_date_sub[2]);
            var late_day = Math.floor((end_day.getTime() - start_day.getTime())/(24*60*60*1000));
            return late_day;
        }

        function dateDiff_except_holiday(start_date, end_date, holiday_flag, holiday){
            var dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            var d = new Date(start_date);
            var num_day = 0;
            var i;
            var total_day = dateDiff(start_date, end_date);

            if(holiday_flag == 1){ // exclude Saturday and Sunday
                for(i = 0; i < total_day; i++){
                    d.setDate(d.getDate()+1);
                    if(dayNames[d.getDay()] != 'Sat' && dayNames[d.getDay()] != 'Sun'){
                        num_day++;
                    }
                }
            }else if(holiday_flag == 2){ // exclude Sat, Sun and National Holiday
                 if(typeof holiday.length != 0){
                    for(i = 0; i < total_day; i++){
                        d.setDate(d.getDate()+1);
                        if(dayNames[d.getDay()] != 'Sat' && dayNames[d.getDay()] != 'Sun'){
                            var date = d.getFullYear()+'-'+ ("0" + (d.getMonth()+1)).slice(-2)+'-'+("0" + d.getDate()).slice(-2);
                            if($.inArray(date, holiday) == -1){ // if not match days in holidays
                                num_day++;
                            }
                        }
                    }
                 }else{
                     for(i = 0; i < total_day; i++){
                         d.setDate(d.getDate()+1);
                         if(dayNames[d.getDay()] != 'Sat' && dayNames[d.getDay()] != 'Sun'){
                             num_day++;
                         }
                     }
                 }
            }else{ // include holiday
                num_day = total_day;
            }
//            console.log(num_day);

            return num_day;
        }

    </script>
@endsection
