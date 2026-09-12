
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false)}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
    <link href="{{ asset('theme/js/iCheck/skins/flat/green.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('sidebar.sb_payoff_loans') }}
        </header>
        <?php
            //$m_schedule = LoanCalculate::loan_schedule($loan->schedule, $loan->start_date);
            $repayment_schedule = schedule_sort_by_no($loan->schedule);
            $int_options = Config::get('static_data.interest_option');
        ?>

        <div class="panel-body">
            @if(Session::has('msg'))
                <div class="alert alert-success fade in">
                    <button class="close close-md" data-dimdiss="alert">x</button>
                    {{ Session::get('msg') }}
                </div>
            @endif
            @if($errors->has())
                <div class="alert alert-danger fade in">
                    <button type="button" class="close" data-dimdiss="alert"></button>
                    {{ HTML::ul($errors->all()) }}
                </div>
            @endif

            {{-- <?php if($draft_id==0 || $draft->user_id == $user_id){?>
                <form action="{{ route('postRepaymentDraft',[$loan->id, $draft_id])}}" class="cmxform form-horizontal" method="post" id="frmRepayment" enctype="multipart/form-data">
            <?php }else{?> --}}
                <form class="cmxform form-horizontal" method="post" action="{{ route('loan_payoff',[$loan->id]) }}" id="frmPayoff" enctype="multipart/form-data">
                <input type="hidden" name="draft_id" value="{{ $draft_id }}" />
            {{-- <?php } ?> --}}
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="ipStartDate" id="ipStartDate" value="{{$draft?$draft->ipStartDate:$loan->disburse_date}}">
                <input type="hidden" name="ipId" id="ipId" value="{{$loan->id}}">
                <input type="hidden" name="iPrincipal" id="iPrincipal">
                <input type="hidden" name="iInterest" id="iInterest">
				<input type="hidden" name="iAIR_deduct" id="iAIR_deduct">
                <input type="hidden" name="iPenalty" id="iPenalty">
                <input type="hidden" name='ipPayOffFee' id="ipPayOffFee">
                <input type="hidden" name='type' value="2">

                <div class="form-group">
                    <label class="col-md-3 control-label">{{ trans('loan.l_pay_off_date') }} <span class="red-color">*</span></label>
                    <div class="input-append date dpYears col-md-6" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}">
                        <input type="text" value="{{ $draft?$draft->dpDate:date('Y-m-d') }}" selected class="form-control" name="dpDate" id="dpDate">
                            <span class="add-on offonDatepicker">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">{{ trans('report.rpt_tenure') }} <span class="red-color">*</span></label>
                    <div class="col-md-6">
                        <div class="input-group input-large">
                            <span class="input-group-addon" style="border-right: 1px solid #cccccc;">Months</span>
                            <div class="input-group">
                               <input type="text" readonly value="{{$draft?$draft->ipTenure:0}}"  class="form-control" name="ipTenure" id="ipTenure"  style="border-radius: 0px !important; border-left: none;"/>
                               <span class="input-group-btn" style="vertical-align:top">
                                   <a class="btn btn-info" id="btnTenure" style="border-radius: 0px !important;">
                                       <i class="fa fa-pencil"></i>
                                   </a>
                               </span>
                           </div>
                               <span class="input-group-addon">Days</span>
                               <div class="input-group">
                                  <input type="text" readonly value="{{$draft?$draft->ipTenureDate:0}}"  class="form-control" name="ipTenureDate" id="ipTenureDate" style="border-radius: 0px !important;" />
                                  <span class="input-group-btn" style="vertical-align:top">
                                      <a class="btn btn-info" id="btnTenureDate">
                                          <i class="fa fa-pencil"></i>
                                      </a>
                                  </span>
                              </div>
                           </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"></label>

                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">{{ trans('loan.l_interest_rate') }} (%) <span class="red-color">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="ipInterest" id="ipInterest" value="{{$draft?$draft->ipInterest:$loan->interest_rate}}" readonly />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">{{ trans('multiple.m_note') }} </label>
                    <div class="col-md-6">
                        <textarea name="ipNote" class="form-control">{{$draft->ipNote}}</textarea>
                    </div>
                </div>
                <div class = "form-group">
                    <label class="col-md-3 control-label">{{ trans('loan.interest_option') }}</label>
                    <div class="col-md-6">
                        <select id="int_option" class="form-control" name="ipInterestOption">
                            @foreach($int_options as $key=>$opt)
                                <option value="{{ $opt }}" @if($draft->ipInterestOption == $opt) selected @endif>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-md-3 control-label">{{ trans('multiple.m_invoice') }} <span class="red-color">*</span></label>
                    <div class="col-md-6">
                        <input type="text" name="invoice_number" class="form-control" id="invoice_number" value="{{$draft->invoice_number}}" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">{{ trans('loan.waive_panalty') }}</label>
                    <div class="col-md-6">
                        <div class="flat-green single-row icheck group">
                            <div class="radio" >
                                <input type="checkbox" name="ch_waive_panalty" value="1" @if($draft->ip_ch_waive_panalty==1) checked="checked" @endif />
                            </div>
                        </div>

                        <div class="input_waive_panalty ihide">
                            <input type="text" name="waive_panalty_amount" class="form-control" id="waive_panalty_amount" value="{{$draft->ip_waive_panalty_amount}}" />
                        </div>

                        <div class="fileupload fileupload-new file_waive_panalty ihide" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                @if($draft->waive_panalty_receipt)
                                    <a href="/data/loans/documents/{{$draft->waive_panalty_receipt}}" target="_blank">
                                        <img src="/data/loans/documents/{{$draft->waive_panalty_receipt}}" alt="" />
                                    </a>
                                @else
                                    <img src="{{ asset('images/noimage.gif', isset($secure)?false:false) }}" alt="" />
                                @endif
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                            <div>
                               <span class="btn btn-white btn-file">
                               <span class="fileupload-new "><i class="fa fa-plus"></i> {{ trans('multiple.add_image') }}</span>
                               <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                               <input type="file" name="waive_panalty_receipt" id="waive_panalty_receipt" class="default" />
                               </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="num_date_diff" hidden>
                </div>

                <div class="form-group">
                    <label class="col-md-3"></label>
                    <div class="col-md-6">
                        <a href="javascript:;" id="btnPreview" class="btn btn-primary"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;{{ trans('loan.l_preview') }}</a>
                        <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-dollar"></i>&nbsp;&nbsp;{{ trans('multiple.m_save') }} </button>
                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                        <a href="javascript:history.back();" class="btn btn-danger"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;{{ trans('multiple.m_cancel') }} </a>
                    </div>
                    <br/><br/>
                </div>
            <br/>
            <div id="printArea">
                @include('api.report_header')
                <div id="preview" style="clear: both"></div>
            </div>

            <div class="col-md-12" id="jd-block">
                <h4>Journal Detail</h4><hr/>
                <div class="b-block act_principal">
                    <h5><strong>Principal Repayment</strong></h5>
                    <?php
                    $params = array(
                        'debit' => '',
                        'credit' => '',
                        'parent_debit' => $coa_pcp->id,
                        'parent_credit' => $coa->id,
                        'parent_debit_label' => $coa_pcp->name . ' (' . $branch_code . $coa_pcp->account_code . ')',
                        'parent_credit_label' => $coa->name . ' (' . $branch_code . $coa->account_code . ')',
                        'd_description'=>$draft->d_description0,
                        'c_description'=>$draft->c_description0,
                        'description'=>$draft->description0,
                    );
                    echo getJournalDetail($params);
                    ?>
                </div>

                <div class="b-block act_interest">
                    <h5><strong>Interest Repayment</strong></h5>
                    <?php
                    $params = array(
                        'debit' => '',
                        'credit' => '',
                        'parent_debit' => $coa_pcp->id,
                        'parent_credit' => $coa_air->id,
                        'parent_debit_label' => $coa_pcp->name . ' (' . $branch_code . $coa_pcp->account_code . ')',
                        'parent_credit_label' => $coa_air->name . ' (' . $branch_code . $coa_air->account_code . ')',
                        'd_description'=>$draft->d_description1,
                        'c_description'=>$draft->c_description1,
                        'description'=>$draft->description1,
                    );
                    echo getJournalDetail($params);
                    ?>
                </div>

                <div class="b-block act_penalty">
                    <h5><strong>Penalty Charge</strong></h5>
                    <?php
                    $params = array(
                        'debit' => '',
                        'credit' => '',
                        'parent_debit' => $coa_pcp->id,
                        'parent_credit' => $coa_pnt->id,
                        'parent_debit_label' => $coa_pcp->name . ' (' . $branch_code . $coa_pcp->account_code . ')',
                        'parent_credit_label' => $coa_pnt->name . ' (' . $branch_code . $coa_pnt->account_code . ')',
                        'd_description'=>$draft->d_description2,
                        'c_description'=>$draft->c_description2,
                        'description'=>$draft->description2,
                    );
                    echo getJournalDetail($params);
                    ?>
                </div>

                <div class="b-block act_fee">
                    <strong>{{ trans('loan.rpt_payoff_fee')  }}</strong>
                    <?php
                    $params = array(
                        'debit' => '',
                        'credit' => '',
                        'parent_debit' => $coa_pcp->id,
                        'parent_credit' => $coa_fee->id,
                        'parent_debit_label' => $coa_pcp->name . ' (' . $branch_code . $coa_pcp->account_code . ')',
                        'parent_credit_label' => $coa_fee->name . ' (' . $branch_code . $coa_fee->account_code . ')',
                        'd_description'=>$draft->d_description3,
                        'c_description'=>$draft->c_description3,
                        'description'=>$draft->description3,
                    );
                    echo getJournalDetail($params);
                    ?>
                </div>
            </div>
          </form>

        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.numeric.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/accounting.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false)}}"></script>
    <script src="{{ asset('theme/js/iCheck/jquery.icheck.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript">
    <?php if($draft_id!=0 && $draft->user_id != $user_id){?>
            $('input').each(function(){
                $(this).attr('readonly', 'readonly');
            });

            $('select').each(function(){
                $(this).attr('readonly', 'readonly');
                $(this).on('mouseover',function(e){
                    $(this).hide();
                });
            });

            $('input[type="checkbox"]').each(function(){
                $(this).on('mouseover',function(e){
                   $(this).hide();
                });
            });

            $('textarea').each(function(){
                $(this).attr('readonly', 'readonly');
            });

            $('.btn-file').remove();
    <?php }else{ ?>
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
        $('#btnTenure').click(function(){
            $('#ipTenure').attr('readonly', !$('#ipTenure').attr('readonly'));
        });
        $('#btnTenureDate').click(function(){
            $('#ipTenureDate').attr('readonly', !$('#ipTenureDate').attr('readonly'));
        });

        $('.group input').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        }).on('ifToggled', function (e) {
            e.preventDefault();
            var chck = $(this).prop('checked');
            if (chck) {
                $('.file_waive_panalty').show();
                $('.input_waive_panalty').show();
            } else {
                $('.file_waive_panalty').hide();
                $('.input_waive_panalty').hide();
                $('.input_waive_panalty input').val('');
            }

            calculatePayOff();
            $('#btnPreview').trigger('click');
        });
    <?php }?>
    var tenure_date = 0;
    var number_month = 0;
    var payoff = null;
    var loan_data = <?php echo $loan;?>;
    var repaymentSchData = <?php echo json_encode($repayment_schedule);?>;
    var holidays = <?php echo json_encode($loan->holiday);?>;
    var accrued_int_arr = <?php echo json_encode($accrued_int_arr);?>;
    var HOLIDAY_FLAG = 0;
    var foo = false;
    var penalty_list = [];
    var drawdown_acc = <?php echo $drawdown_acc;?>;
	var air_sch_flg = <?php echo AIR_SCH_FLG;?>;
    $('#button').click(function(){
        if(foo==false){
            bootbox.alert("{{ trans('loan_payoff_not_correct_date') }}", function() {});
            //return false;
        }
    });
        $("#frmPayoff").validate({
            rules:{
                dpDate:{
                    required: true
                },
                ipTenure:{
                    required: true
                },
                ipTenureDate:{
                    required: true
                },
                ipInterest:{
                    required: true
                },
                invioce_number:{
                    required: true
                },

            },
            messages:{
                dpDate:{
                    required: "Please select a date"
                },
                ipTenure:{
                    required: "Please enter tenure months"
                },
                ipTenureDate:{
                    required: "Please enter tenure dates"
                },
                ipInterest:{
                    required: "Please enter interest rate"
                },
                invioce_number:{
                    required: "Please enter invoice number"
                }
            }
        });

        var lastJQueryTS = 0 ;
        $('#dpDate').change(function(event){
          event.preventDefault();

          // prevent datapicker firing 3 times
          var send = true;
          if (typeof(event) == 'object'){
              if (event.timeStamp - lastJQueryTS < 300){
                  send = false;
              }
              lastJQueryTS = event.timeStamp;
          }
          // wrap code inside this condition to prevent duplicate firing
          if (send){
            var start = $('#ipStartDate').val();
            var end = $(this).val();
            var start_date = new Date(repaymentSchData[1].schedule_date);
            var end_date = new Date(end);
            var idate = payoffDuration(repaymentSchData,start_date, end);
            number_month = idate[0];
            tenure_date = idate[1];
            $('#ipTenure').attr('value',number_month);
            if(tenure_date >=0 ){
               $('#ipTenureDate').attr('value',tenure_date);
            }

            // use LoanCalculate::getTotalPenalty()
             $.ajax({
                 type: "get",
                 url:  '{{ route("get_my_penalty") }}',
                 //contentType: "application/json; charset=utf-8",
                 //dataType: "json",
                 data: {
                         id:loan_data.id, //can pass any values  (key:value) format
                         date:$('#dpDate').val()
                       },
                 success: function(data) {

                     penalty_list = data[0];
                     //console.log(penalty_list);
                     payoff = calculatePayOff();
                    //console.log(payoff);
                    fillFrm(payoff);
                     //$('#ipInterest').val(data.date);  //get an element’s id and assign it the value from the ajax
                 },
                 error: function(err){
                   console.log(err);
                 }
             });
             $('#btnPreview').click();

          }
        });
        $('#dpDate').change();

        $("#ipTenure").numeric({ decimal : ".",  negative : false, scale: 5 });
        $("#ipTenure").on("change",function(e){
            payoff = calculatePayOff();
            fillFrm(payoff);
        });

        $("#ipTenureDate").numeric({ decimal : ".",  negative : false, scale: 5 });
        $("#ipTenureDate").on("change",function(e){
            payoff = calculatePayOff();
            fillFrm(payoff);
        });

        $("#ipInterest").numeric({ decimal : ".",  negative : false, scale: 5 });
        $("#ipInterest").on("change",function(e){
            payoff = calculatePayOff();
            fillFrm(payoff);
        });

        function number_of_months(start, end){
            var usrYear, usrMonth = start.getMonth()+1;
            var curYear, curMonth = end.getMonth()+1;
            if((usrYear=start.getFullYear()) < (curYear=end.getFullYear())){
                curMonth += (curYear - usrYear) * 12;
            }
            var diffMonths = curMonth - usrMonth;
            if(start.getDate() > end.getDate()) diffMonths--;
            return diffMonths;
        }
        function payoffDuration(schedule, start, end){
            $.each(schedule, function(key, sch){
                if(dateDiff(sch.schedule_date, end) < 0){
                    imonth = key - 1;
                    return false;
                }
            });
            iday = dateDiff(schedule[imonth].schedule_date, end);
            return [imonth,iday];
        }

        function isNumeric(n) {
          return !isNaN(parseFloat(n)) && isFinite(n);
        }
        function round(num){
            return Math.round(num * 100) / 100;
        }
        function penaltyCal(){
            var day = 0;
            var month_idx = 1;
            var select_date = $("#dpDate").val();
            var late_day = 0;
            var idx = 0;
            var month_array = new Array();
            var overdue_days = 0;
            var penalty = new Array();
            var total_arrears = new Array();
            var next_month_idx = new Array();
            var paid_principal_sum = 0;
            var paid_interest_sum = 0;
            var paid_fee_sum = 0;
            var penalty_owed = 0;
            var principal_owed = 0;
            var interest_owed = 0;
            var fee_owed = 0;
            var m_payment = 0;
            var ow_p_month = 0;
            var result = [];
            var next_month_idx = [];
            var next_time_idx = [];
            var paid_month_idx = [];

            for(var i = 0; i <= loan_data.loan_duration; i++){
                month_array[i] = 0.0;
                overdue_days[i] = 0.0;
                penalty[i] = 0.0;
                total_arrears[i] = 0.0;
            }
            var sch_month = 0;
            var d;
            while(repaymentSchData[month_idx]['schedule_date'] < loan_data.last_schedule_date){
                month_idx++;
            }
            //console.log(month_idx);
            d = new Date(repaymentSchData[month_idx]['schedule_date']);
            sch_month = d.getMonth();
            late_day = dateDiff_except_holiday(repaymentSchData[month_idx]['schedule_date'], select_date, HOLIDAY_FLAG, holidays);
            var num_month = dateDiff( loan_data.start_date,select_date);
            var start_idx = 0;
            if(loan_data.payment.length>0){
                var pay_next_month = [];
                var groupPaymentArray = [];
                var owed_payment_tb = <?php echo json_encode($loan->payment->where('status', 0,false)->first());  ?>;
				if(owed_payment_tb != null && owed_payment_tb != ""){
					start_idx = owed_payment_tb.payment_month;
				}
                $.each(loan_data.payment, function(key, v){
                    if(v.status == '0'){
                        if(v.condition_id == 1){
                            next_time_idx.push(parseInt(v.payment_month));
                        }else if(v.condition_id == 2){
                            next_month_idx.push(parseInt(v.payment_month));
                        }
                    }else if(v.status == '1'){
                       paid_month_idx.push(parseInt(v.payment_month));
                    }
                });
                // for owed repayment
                var group_payment = <?php echo json_encode($loan->payment->groupBy('payment_month')); ?>;
                groupPaymentArray = group_payment;
                // console.log(group_payment);
				paid_principal_sum = 0;
				paid_interest_sum = 0;
				paid_fee_sum = 0;
                $.each(group_payment,function(key,v){
                    penalty_owed = 0;
                    var lp = v[v.length - 1];
                    var p_month = lp.payment_month;
                    if(lp.status == 0){
                        $.each(v, function(key, v1){
                            paid_principal_sum += parseFloat(v1.paid_principal);
                            paid_interest_sum += parseFloat(v1.paid_interest);
                            paid_fee_sum += parseFloat(v1.paid_fee);
                        //console.log(v1.paid_interest);
                        //console.log( paid_interest_sum);
						});
                        principal_owed = parseFloat(repaymentSchData[p_month]['principal']) - paid_principal_sum;
                        interest_owed = parseFloat(repaymentSchData[p_month]['interest']) - paid_interest_sum;
                        fee_owed = parseFloat(repaymentSchData[p_month]['fee']) - paid_fee_sum;
                        m_payment = principal_owed + interest_owed + fee_owed;
                        penalty_owed = lp.repayment_owed - m_payment;
                        ow_p_month = p_month + 1;
                        if(lp.condition_id == 1) { /* next time*/
                            ow_p_month = p_month;
                        }
                        var s_date = repaymentSchData[ow_p_month]['schedule_date'];
                        d = new Date(s_date);
                        var month = d.getMonth();
                        late_day = dateDiff_except_holiday(s_date, select_date, HOLIDAY_FLAG, holidays);
                        if (late_day <= 0) {
                            late_day = 0;
                            return false;
                        }
                        var sub_day = loan_data.penalty_period1;
                        var p_rate = 0;
                        var p_rate_amount = 0;
                        var p_amount = 0;
                        var num_day = 0;
                        if(late_day > sub_day){
                            if(loan_data.penalty_rate_type==1){
                                var pay_period = lp.repayment_date - s_date;
                                if(pay_period <= sub_day){
                                    late_day = dateDiff_except_holiday(s_date, select_date, HOLIDAY_FLAG, holidays);
                                    if(late_day < sub_day){
                                        num_day = 0;
                                    }else{
                                        num_day = late_day;
                                    }
                                }else{
                                    late_day = dateDiff_except_holiday(lp.repayment_date, select_date, HOLIDAY_FLAG, holidays);
                                    if(late_day < 0){
                                        num_day = 0;
                                    }else{
                                        num_day = late_day;
                                    }
                                }
                                p_rate = loan_data.penalty_rate1;
                                p_rate_amount = m_payment * (p_rate / 100);
                                p_amount = p_rate_amount * num_day;
                            }else if(loan_data.penalty_rate_type==2){
                                p_rate = loan_data.penalty_rate1;
                                p_rate_amount = m_payment * (p_rate / 100);
                                for (i = ow_p_month; i <= num_month; i++) {
                                    if (i in repaymentSchData) {
                                        var sch_date = repaymentSchData[i]['schedule_date'];
                                        d = dateDiff_except_holiday(sch_date, select_date, HOLIDAY_FLAG, holidays);
                                        if (d - sub_day > 0) {
                                            p_amount+= p_rate_amount;
                                        }
                                    }
                                }
                            }else if(loan_data.penalty_rate_type==3){
                                sch_date = repaymentSchData[ow_p_month]['schedule_date'];
                                d = dateDiff_except_holiday(sch_date, select_date, HOLIDAY_FLAG, holidays);
                                if (d > loan_data.penalty_period2) {
                                    p_rate = loan_data.penalty_rate2;
                                    p_rate_amount = m_payment * (p_rate / 100);
                                    p_amount = p_rate_amount;
                                } else if (d > loan_data.penalty_period1) {
                                    p_rate = loan_data.penalty_rate1;
                                    p_rate_amount = m_payment * (p_rate / 100);
                                    p_amount = p_rate_amount;
                                }

                            }
                            overdue_days = late_day;
                            var penalty_floor = Math.floor(p_amount);
                            if((p_amount - penalty_floor) >= 0.45){
                                p_amount = Math.ceil(p_amount);
                            }else{
                                p_amount = penalty_floor;
                            }
                            p_amount +=  penalty_owed;
                            var to_amount = p_amount + m_payment;
                            if(lp.condition_id == 1){
                                result[ow_p_month] =  [month+1, overdue_days, principal_owed, interest_owed,0,m_payment,p_rate, p_rate_amount, late_day, p_amount, to_amount];
                            }else{
                                pay_next_month[ow_p_month] = [month+1,overdue_days, principal_owed, interest_owed,0,m_payment,p_rate, p_rate_amount, late_day, p_amount, to_amount];
                            }
                        }
                    }
                });
            }
            for (i = start_idx; i < repaymentSchData.length; i++) {
                var flg = 0;
                m_payment = 0;
                for(j = 0; j < next_time_idx.length; j++){
                    if(i == next_time_idx[j]){
                        flg = 1;
                    }
                }
                for(var k = 0; k < paid_month_idx.length; k++){
                    if(i == paid_month_idx[k]){
                        flg = 1;
                    }
                }
                if(flg == 1) continue;
                    var date = repaymentSchData[i]['schedule_date'];
                    d = new Date(date);
                    var month = d.getMonth();
                    day = dateDiff_except_holiday(date, select_date, HOLIDAY_FLAG, holidays);
                    if (day <= 0) {
                        break;
                    }
                    var sub_day = loan_data.penalty_period1;
                    var p_rate = 0;
                    var p_amount = 0;
                    m_payment += parseFloat(repaymentSchData[i]['principal'])+ parseFloat(repaymentSchData[i]['interest'])+ parseFloat(repaymentSchData[i]['fee']);

                     var p_rate_amount = 0;
                    if (loan_data.penalty_rate_type == 1) { /* day */
                        p_rate = loan_data.penalty_rate1;
                        p_rate_amount = m_payment * (p_rate / 100);
                        var num_day = day - sub_day;
                        if (num_day > 0) {
                            p_amount = p_rate_amount * day;
                        }
                    }else if (loan_data.penalty_rate_type == 2) { /* month */
                        p_rate = loan_data.penalty_rate1;
                        p_rate_amount = m_payment * (p_rate / 100);
                        for (var j = i; j <= num_month; j++) {
                            if (j in repaymentSchData) {
                                var sch_date = repaymentSchData[j]['schedule_date'];
                                num_day = dateDiff_except_holiday(sch_date, select_date, HOLIDAY_FLAG, holidays);
                                if (num_day - sub_day > 0) {
                                    p_amount += p_rate_amount;
                                }
                            }
                        }
                    } else {
                        for (j = i; j <= num_month; j++) {
                            if (j in repaymentSchData) {
                                sch_date = repaymentSchData[j]['schedule_date'];
                                num_day = dateDiff_except_holiday(sch_date, select_date, HOLIDAY_FLAG, holidays);
                                if (num_day > loan_data.penalty_period2) {
                                    p_rate = loan_data.penalty_rate2;
                                    p_rate_amount = m_payment * (p_rate / 100);
                                    p_amount = p_rate_amount;
                                } else if (num_day > loan_data.penalty_period1) {
                                    p_rate = loan_data.penalty_rate1;
                                    p_rate_amount = m_payment * (p_rate / 100);
                                    p_amount = p_rate_amount;
                                }
                            }
                        }
                    }
                    late_day = day;
                    if (late_day < loan_data.penalty_period1) late_day = 0;
                    overdue_days = late_day;
                    var to_amount = p_amount + m_payment;
                    var principal = parseFloat(repaymentSchData[i]['principal']);
                    var interest = parseFloat(repaymentSchData[i]['interest']);
                    var arr_amount = 0;
                    if(pay_next_month != null){
                        if (pay_next_month.length > 0 && i in pay_next_month) {
                            principal += pay_next_month[i][2];
                            interest += pay_next_month[i][3];
                            arr_amount += pay_next_month[i][5];
                            p_rate_amount += pay_next_month[i][7];
                            p_amount +=  pay_next_month[i][9];
                            to_amount += pay_next_month[i][10];
                        }
                    }
                    var penalty_floor = Math.floor(p_amount);
                    if((p_amount - penalty_floor) >= 0.45){
                        p_amount = Math.ceil(p_amount);
                    }else{
                        p_amount = penalty_floor;
                    }
                    result[i] = [month+1, overdue_days, principal, interest,m_payment,arr_amount ,p_rate, p_rate_amount, late_day, p_amount, to_amount];
                }
             return result;
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
            return num_day;
        }


        function calculatePayOff(){
            var waive_panalty_amount = $('.input_waive_panalty input').val();
            if(!waive_panalty_amount || waive_panalty_amount=='' || waive_panalty_amount==null){
                waive_panalty_amount = 0;
            }
            waive_panalty_amount = parseFloat(waive_panalty_amount);


            var tenure = parseFloat($('#ipTenure').val());
            var tenure_date = parseFloat($('#ipTenureDate').val());
            var interest = $('#ipInterest').val();
            var actualPaid = 0.0;
            var actualInterest = 0.0;
	        var actualFee = 0.0;
            var payoff_rate = 0.0;
            var interest_repayment = 0.0;
			var totalPayOff = 0.0;
            var int_opt = $("#int_option").val();
            var days = 0;
            var select_date =  $("#dpDate").val();
            var total_sch_fee = 0.0;
			var total_sch_interest = 0.0;
			var last_sch_date;
			var next_int_amount = 0;
			var next_num_date = 0;
			var air_deduct = 0;

            for(i=0;i<loan_data.payment.length;i++){
                actualPaid += parseFloat(loan_data.payment[i].paid_principal);
                actualInterest += parseFloat(loan_data.payment[i].paid_interest);
		        actualFee += parseFloat(loan_data.payment[i].paid_fee);
            }
            //console.log(actualFee);
            $.each(repaymentSchData, function(key, sch){
                if(key <= imonth){
                    total_sch_fee += parseFloat(sch.fee);
					total_sch_interest += parseFloat(sch.interest);
					last_sch_date = sch.schedule_date;
                }else{
					next_int_amount = sch.interest;
					next_num_date = sch.date_num;
                    return false;
                }
            });
            // for(var i=0;i<=number_month;i++){
            //     total_sch_fee += parseFloat(loan_data.schedule[i].fee);
            // }
            if(repaymentSchData[imonth].date_num == 0) repaymentSchData[imonth].date_num = 1;
            total_sch_fee += tenure_date * parseFloat(repaymentSchData[imonth+1].fee)/ parseFloat(repaymentSchData[imonth+1].date_num);
            //console.log(loan_data.schedule[i]);
//console.log(tenure_date, total_sch_fee,parseFloat(loan_data.schedule[i+1].fee),parseFloat(loan_data.schedule[i+1].date_num));
            if(0 < tenure && tenure < parseFloat(loan_data.payoff_period1)){
                payoff_rate = parseFloat(loan_data.pay_off_rate1);
            }else if( parseFloat(loan_data.payoff_period1) <= tenure && tenure < parseFloat(loan_data.payoff_period2)){
                payoff_rate = parseFloat(loan_data.pay_off_rate2);
            }else if( tenure > parseFloat(loan_data.payoff_period2)){
                payoff_rate = 0;
            }
            var start = $('#ipStartDate').val();
            var end = $('#dpDate').val();
            var num_days_diff = dateDiff(start, end);
            $("#num_date_diff").val(num_days_diff);
            if(int_opt == "monthly"){
                days = tenure*30 + tenure_date;
            }else if(int_opt == "daily"){
                days = num_days_diff;
            }
            var total_debit_acc = 0;
            var total_credit_acc = 0;
            var entry_date;
            var last_acc_date;
            $.each(accrued_int_arr, function(key, acc){
                entry_date = (acc.entry_date).substring(0,10);
                if(parseFloat(acc.debit) > 0) last_acc_date = entry_date;
                if(dateDiff(entry_date, end) >= 0){
                    total_debit_acc += parseFloat(acc.debit);
                    total_credit_acc += parseFloat(acc.credit);
				}
            });
             //console.log(last_acc_date);
             //console.log(end);
            var os_balance = parseFloat(loan_data.loan_amount) - actualPaid;
            if(loan_data.rate_type == "Flat") os_balance = loan_data.original_amount
            /*
			var unpaid_air = os_balance*(interest/100)*(12/360)*dateDiff(last_acc_date, end);
            if(unpaid_air < 0) unpaid_air = 0;
            //console.log(interest, os_balance, dateDiff(last_acc_date, end), unpaid_air);
            interest_repayment = Math.round(total_debit_acc * 100)/100 + unpaid_air;
			*/
			//actualInterest = Math.round(total_credit_acc * 100)/100;
			if(air_sch_flg == 1){
				add_interest = round(next_int_amount * dateDiff(last_sch_date, end) / next_num_date , 2);
				total_sch_interest = total_sch_interest + add_interest;
				air_deduct = (total_debit_acc - total_credit_acc) - (total_sch_interest - actualInterest);
			}else{
                total_sch_interest = Math.round(total_debit_acc * 100)/100;
                actualInterest = Math.round(total_credit_acc * 100)/100;   
                add_interest = round(os_balance * loan_data.interest_rate *dateDiff(last_acc_date, end) * 12 / 36000 , 2);
                total_sch_interest += add_interest;
            }
			//console.log(total_debit_acc);console.log(unpaid_air);console.log(actualInterest);
            var penalty_array = null;
            penalty_array = penaltyCal();
            //console.log(penalty_array);
            var subTotal1 = actualPaid + actualInterest;
            var subTotal2 = Math.round((parseFloat(loan_data.loan_amount) - actualPaid)*(payoff_rate/100)*100)/100 + round(total_sch_fee - actualFee); //payoff fee
            var subTotal3 = 0.0;
            var principal = parseFloat(loan_data.loan_amount) - actualPaid;
            var interest = total_sch_interest - actualInterest;

            // for(i = 0; i < loan_data.loan_duration; i++){
            //     if(penalty_array[i] != null){
            //         subTotal3 += parseFloat(penalty_array[i][9]);
            //     }
            // }

            var schedule_month_arr = [];
            var overdue_arr = [];
            var total_arrear_arr = [];
            var penalty_arr = [];

            var penalty_arr_result = [];
            // for(i = 0; i < loan_data.loan_duration; i++){
            //     if(penalty_array[i] != null){
            //         schedule_month_arr.push(penalty_array[i][0]);
            //         overdue_arr.push( penalty_array[i][1]);
            //         total_arrear_arr.push(penalty_array[i][5]+ penalty_array[i][4]);
            //         penalty_arr.push(penalty_array[i][9]);
            //     }
            // }
            if (penalty_list != 'undefined'){
                var count = Object.keys(penalty_list).length;
                if(count > 0){
                    $.each(penalty_list, function(key, v){
                        // console.log(v);
                        schedule_month_arr.push(v[0]);
                        overdue_arr.push(v[7]);
                        total_arrear_arr.push(v[3]);
                        penalty_arr.push(round(v[8],2));
                        subTotal3 += v[8];
                    });
                }
            }
            // console.log(penalty_arr);

//console.log(total_sch_fee);console.log(actualFee);

            var totalPayOff = (parseFloat(loan_data.loan_amount) + total_sch_interest) - subTotal1 + subTotal2 + subTotal3;
            //console.log(total_sch_fee);
            var data = {
                actual_paid: (round(actualPaid)).toFixed(2),
                actual_interest: (round(actualInterest)).toFixed(2),
		        fee_owed:(round(total_sch_fee - actualFee)).toFixed(2),
		        payoff_fee: (round((parseFloat(loan_data.loan_amount) - actualPaid)*(payoff_rate/100)*100)/100).toFixed(2),
                payoff_rate: (round(payoff_rate)).toFixed(2),
                interest_repayment: (round(interest_repayment)).toFixed(2),
                total1: (round(subTotal1)).toFixed(2),
                total2: (round(subTotal2)).toFixed(2),
                total3: (round(subTotal3)).toFixed(2),
                principal: (round(principal)).toFixed(2),
                interest: (round(interest)).toFixed(2),
				air_deduct: (round(air_deduct)).toFixed(2),
                schedule_month : schedule_month_arr,
                overdue_days: overdue_arr,
                total_arrears: total_arrear_arr,
                penalty: penalty_arr,
                totalPayOff: (round(totalPayOff)).toFixed(2)
            };
            data.total3 = data.total3 - waive_panalty_amount;
            data.totalPayOff = data.totalPayOff - waive_panalty_amount;
            $('#iPrincipal').val(data.principal);
            $('#iInterest').val(data.interest);
			$('#iAIR_deduct').val(data.air_deduct);
            $('#iPenalty').val(data.total3);
            $('#ipPayOffFee').val(data.total2);
            if(data.totalPayOff > drawdown_acc.balance){
            $('<div id="loading"></div>').appendTo('body');
                imgLoading(true, "Balance in Drawdown Account is not sufficient! <br/>" +
                 "<p style='text-align: left'>Current Drawdown Account balance is <strong style = 'color:red;'>" + accounting.formatMoney(drawdown_acc.balance, drawdown_acc.currency_tbl.symbol)+"</strong></p>",5, 'warning');
            }
            //chuch add debit, credit val
            $('.act_principal input[name="debit[]"]').val(data.principal);
            $('.act_principal input[name="credit[]"]').val(data.principal);
            $('.act_interest input[name="debit[]"]').val(data.interest);
            $('.act_interest input[name="credit[]"]').val(data.interest);
            $('.act_penalty input[name="debit[]"]').val(data.total3);
            $('.act_penalty input[name="credit[]"]').val(data.total3);
            $('.act_fee input[name="debit[]"]').val(data.total2);
            $('.act_fee input[name="credit[]"]').val(data.total2);
            return data;
      }
        function get_days_num(){
            var tenure = $('#ipTenure').val();
            var tenure_date = parseFloat($('#ipTenureDate').val());
            var num_days_diff = $("#num_date_diff").val();
            var int_opt = $("#int_option").val();
            var days = 0;
            if(int_opt == "monthly"){
                days = tenure*30 + tenure_date;
            }else if(int_opt == "daily"){
                days = num_days_diff;
            }
             return days;
        }


        $('#btnPreview').click(function(){
            if(isNumeric($('#ipInterest').val()) && isNumeric($('#ipTenure').val())){
                var tenure = $('#ipTenure').val();
                var tenure_date = parseFloat($('#ipTenureDate').val());
                var num_days_diff = $("#num_date_diff").val();
                var interest = $('#ipInterest').val();
                var int_opt = $("#int_option").val();
                var days = get_days_num();
                var payoff = calculatePayOff();
				console.log(payoff);
                var loanbrand = '';
                
                var detail = '<h5 class="btnHide"><strong>{{ trans('loan.l_payoff_cal') }}</strong>&nbsp;&nbsp;<a href="javascript:;" id="btnHide" class="btn btn-primary btn-xs"><i class="fa fa-angle-up"></i> Hide</a></h5>';
                detail +='<div class="row">\
                    <div class="col-md-6">\
                    <table class="table table-bordered table-striped table-condensed payoff_repayment">\
                        <thead>\
                            <tr><th colspan=2>{{ trans('loan.l_customer_info') }}</th></tr>\
                        </thead>\
                        <tbody>\
                            <tr>\
                                <td>{{ trans('customer.cus_customer_name') }}</td><td>' + loan_data.client.client_name + '</td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('customer.cus_card_number') }}</td><td>'+ loan_data.client.card_number + '</td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('multiple.m_address') }}</td><td>'+ loan_data.client.address + '</td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('multiple.m_phone',['num'=>'']) }}</td><td>'+ loan_data.client.phone1 + '</td>\
                            </tr>\
                        </tbody>\
                    </table>\
                    </div>\
                    <div class="col-md-6">\
                    <table class="table table-bordered table-striped table-condensed payoff_repayment">\
                        <thead>\
                            <tr><th colspan=2>{{ trans('loan.l_product_info') }}</th></tr>\
                        </thead>\
                        <tbody>\
                            <tr>\
                                <td>{{ trans('product.p_product_model') }}</td><td></td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('product.p_product_brand') }}</td><td>'+ loanbrand + '</td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('product.p_product_serial') }}</td><td></td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('product.p_product_price') }}</td><td></td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('loan.l_down_payment')}}</td><td>$ '+ loan_data.down_payment +'</td>\
                            </tr>\
                        </tbody>\
                    </table>\
                    </div>\
                </div>\
                <div class="row">\
                    <div class="col-md-6">\
                    <table class="table table-bordered table-striped table-condensed payoff_repayment">\
                        <thead>\
                            <tr><th colspan=2>{{ trans('loan.l_agreement') }}</th></tr>\
                        </thead>\
                        <tbody>\
                            <tr>\
                                <td>{{ trans('report.rpt_contract_id') }}</td><td>'+ loan_data.contract_id + '</td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('report.rpt_contract_date') }}</td><td>'+ loan_data.disburse_date +'</td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('report.rpt_loan_amount')  }}</td><td>$ '+ loan_data.loan_amount +'</td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('loan.l_interest_rate')  }}</td><td>'+ loan_data.interest_rate +'%</td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('report.rpt_tenure') }}</td><td>'+ loan_data.loan_duration +' months</td>\
                            </tr>\
                        </tbody>\
                    </table>\
                    </div>\
                    <div class="col-md-6">\
                    <table class="table table-bordered table-striped table-condensed payoff_repayment">\
                        <thead>\
                            <tr><th colspan=2>{{ trans('loan.l_operation_ch')  }}</th></tr>\
                        </thead>\
                        <tbody>\
                            <tr>\
                                <td>{{ trans('loan.l_pri_repayment') }}</td><td>$ '+ loan_data.loan_amount +'</td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('loan.l_int_repayment')  }}</td><td>$ '+ payoff.interest_repayment +'</td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('loan.l_interest_rate') }}</td><td>'+ interest +'%</td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('report.rpt_tenure') }}</td><td>'+ tenure +'M '+ tenure_date +'D ' + '( '+ days +'days )</td>\
                            </tr>\
                        </tbody>\
                    </table>\
                    </div>\
                </div>\
                <br/>\
                <h5><strong>{{ trans('loan.l_loan_collection') }}</strong></h5>\
                <div class="row">\
                    <div class="col-md-6">\
                    <table class="table table-bordered table-striped table-condensed payoff_repayment">\
                        <thead>\
                            <tr><th>{{ trans('loan.l_actual_paid')  }}</th><th>Amount</th></tr>\
                        </thead>\
                        <tbody>\
                            <tr>\
                                <td>{{ trans('report.rpt_principal')  }}</td><td>$ '+ payoff.actual_paid +'</td>\
                            </tr>\
                            <tr>\
                                <td>{{ trans('report.rpt_interest') }}</td><td>$ '+ payoff.actual_interest +'</td>\
                            </tr>\
                            <tr>\
                                <th>{{ trans('loan.l_sub_total')  }} (I)</th><th>$ '+ payoff.total1 +'</th>\
                            </tr>\
                        </tbody>\
                    </table>\
                    </div>\
                    <div class="col-md-6">\
                    <table class="table table-bordered table-striped table-condensed payoff_repayment">\
                        <thead>\
                            <tr><th colspan=4>{{ trans('loan.l_fee_charge') }}</th></tr>\
                            <tr><th>{{trans('report.type')}}</th><th>{{ trans('report.rpt_principal')  }}</th><th>{{ trans('loan.l_interest_rate') }}</th><th>{{ trans('report.rpt_amount') }}</th></tr>\
			</thead>\
                        <tbody>\
                           <tr>\
				<td> {{trans("report.rpt_payoff_fee")}}</td>\
                                <td>$ '+ (loan_data.loan_amount - payoff.actual_paid)+'</td>\
                                <td>' + payoff.payoff_rate +'%</td>\
                                <td>$ '+ payoff.payoff_fee +'</td>\
			   </tr>\
			   <tr>\
				<td> {{trans("report.rpt_other_fee")}}</td>\
                                <td>-</td>\
                                <td>-</td>\
                                <td>$ '+ payoff.fee_owed +'</td>\
                            </tr>\
                            <tr><th>{{ trans('loan.l_sub_total')  }} (II)</th><th></th><th></th><th>$ '+ payoff.total2 + '</th></tr>\
			</tbody>\
                    </table>\
                    </div>\
                </div>\
                <div class="row">\
                    <div class="col-md-6">\
                    <table class="table table-bordered table-striped table-condensed payoff_repayment">\
                        <thead>\
                            <tr><th colspan=4>{{ trans('loan.l_late_payment') }}</th></tr>\
                            <tr><th>{{ trans('multiple.m_month') }}</th><th>{{ trans('report.rpt_overdue') }}</th><th>{{ trans('loan.l_unpaid_amount') }}</th><th>{{ trans('report.rpt_penalty') }}</th></tr>\
                        </thead>\
                        <tbody>';
                        //console.log(payoff);
                        for(var i = 0; i < payoff.overdue_days.length; i++){

                            //if(payoff.schedule_month[i] != 'undefined'){

                                detail +=
                                    '<tr>\
                                        <td>'+(payoff.schedule_month[i])+'</td>\
                                        <td>'+(payoff.overdue_days[i])+'</td>\
                                        <td>$'+payoff.total_arrears[i]+'</td>\
                                        <td>$'+payoff.penalty[i]+'</td>\
                                    </tr>';
                            //}
                        }
                detail +='<tr><th>{{ trans('loan.l_sub_total')  }} (III)</th><th></th><th></th><th>$ '+ payoff.total3 + '</th></tr>\
                        </tbody>\
                    </table>\
                    </div>\
                </div>\
                <h5 style="color: red"><strong>{{ trans('loan.detail') }}</strong></h5>\
                 <div class="row">\
                     <div class="col-md-6">\
                     <table class="table table-bordered table-striped table-condensed payoff_repayment">\
                         <thead>\
                             <tr><th>{{ trans('loan.item')  }}</th><th>Amount</th></tr>\
                         </thead>\
                         <tbody>\
                             <tr>\
                                 <td>{{ trans('report.rpt_principal')  }}</td><td style="text-align: right;">$ '+ (loan_data.loan_amount - payoff.actual_paid).toFixed(2)+'</td>\
                             </tr>\
                             <tr>\
                                 <td>{{ trans('report.rpt_interest') }}</td><td style="text-align: right;">$ '+ parseFloat(payoff.interest).toFixed(2) +'</td>\
                             </tr>\
                             <tr>\
                                 <td>{{ trans('report.rpt_penalty') }}</td><td style="text-align: right;">$ '+ payoff.total3 +'</td>\
                             </tr>\
                             <tr>\
                                 <td>{{ trans('loan.l_total_fee')  }}</td><td style="text-align: right;">$ '+ payoff.total2 +'</td>\
                             </tr>\
                             <tr>\
                                 <th>{{ trans('loan.total')  }}</th><th style="color:red; text-align: right;">$ '+ payoff.totalPayOff +'</th>\
                             </tr>\
                         </tbody>\
                     </table>\
                     </div>\
                 </div>\
                <h4><strong>{{ trans('loan.l_total_repayment') }} : <span style="color:red" >US$ '+ payoff.totalPayOff + '</span></strong></h4>';

                $('#preview').html(detail);
                $('#btnHide').click(function(){
                    $('#preview').html('');
                });

            }else{
                if(!isNumeric($('#ipInterest').val())){
                    $('#ipInterest').addClass('error');
                }
                if(!isNumeric($('#ipTenure').val())){
                    $('#ipTenure').addClass('error');
                }
            }
        });



        $('.input_waive_panalty input').keyup(function(){
            payoff = calculatePayOff();
            fillFrm(payoff);
        });

        function fillFrm(payoff){
            $('#iPrincipal').val(payoff.principal);
            $('#iInterest').val(payoff.interest);
			$('#iAIR_deduct').val(payoff.air_deduct);
            $('#iPenalty').val(payoff.total3);
            $('#ipPayOffFee').val(payoff.total2);
            $('#btnPreview').trigger('click');
        }
    </script>
@endsection
