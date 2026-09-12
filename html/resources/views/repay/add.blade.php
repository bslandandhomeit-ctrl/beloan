@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false)}}" />
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}" />
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('theme/js/iCheck/skins/flat/green.css',isset($secure) ? false : false) }}" rel="stylesheet">
<style type="text/css">
    td.none-border{
        border-top: none!important;
        border-bottom: none !important;
    }
    td.no-border{
        border-bottom: none!important;
    }.tbrepayment tr, .tbrepayment td{
        vertical-align: middle!important;
    }
    /*.b-block{
        width: 100% !important;
    }
    .journal-helper{
         width: 100% !important;
    }*/
</style>
@endsection
@section('content')
<section class="panel panel-box">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
    @endif
    <?php
    $static = config('static_data');
    $prod_array = array('' => '');

    $currency_arr = config('static_data.currency');
    ?>
    <header class="panel-heading">
        {{ trans('sidebar.sb_repay_loan') }}
    </header>
    <div class="position-center">
        <div class="clear-fix"></div>
        <?php $id = $search_results->id?$search_results->id:$loan_row->id; $id = $id?$id:0; ?>
        <form role="form" class="cmxform form-inline text-center" method="get" action="{{ route('add_loan_repayment',[$id]) }}">
            <div class="form-group">
                <input class="form-control @if($loan_row) loan_contract_id @endif" id="keyword" name="key" value="{{$loan_row?$loan_row->contract_id:$key}}" placeholder="Contract ID or Customer Account or Customer Name" />
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-info _search"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
            </div>
            <input type = "hidden" name="ctr_id" id="ctr_id" >
            <input type = "hidden" name="dpDateClone" id="dpDateClone" />
        </form>

    </div>
    <div class="panel-body">
        @if($errors->has())
            <div class="alert alert-danger fade in">
                <button type="button" class="close" data-dismiss="alert"></button>
                {{ HTML::ul($errors->all()) }}
            </div>
        @endif

        @if(Session::has('danger'))
            <div class="alert alert-danger fade in">
                <button class="close close-sm" data-dismiss="alert">x</button>
                {{ Session::get('danger') }}
            </div>
        @endif
       <!-- <?php if($draft_id==0 || $draft->user_id == $user_id) {
            ?>
            <form action="{{ route('postRepaymentDraft',[$search_results->id, $draft_id])}}" class="cmxform form-horizontal" method="post" id="frmRepayment" enctype="multipart/form-data">
        <?php }
        else { ?>
            <form action="{{ route('add_loan_repayment',[$id]) }}" method="POST" class="cmxform form-horizontal" id="addLoanRepaymentForm" enctype="multipart/form-data" >
            <input type="hidden" name="draft_id" value="{{ $draft_id }}" />
        <?php } ?>
        -->
        <form action="{{ route('add_loan_repayment',[$id]) }}" method="POST" class="cmxform form-horizontal" id="addLoanRepaymentForm" enctype="multipart/form-data" >
        <?php
            $month_idx = [];
            $cal_result = calculateRepaySchedule($search_results, $dpDate, "repay");

            $t_sch_int = $t_sch_prin = $t_sch_penal = $t_sch_fee = $t_sch_other_fee = 0;
            $t_sch_int_arr = $t_sch_prin_arr = $t_sch_penal_arr = $t_sch_fee_arr = $t_sch_other_fee_arr = [];

            foreach ($sch_repay_down_loan_arr as $key => $sch_repay_arr) {
    		    foreach($sch_repay_arr as $ar){
                    $t_sch_int += $ar['interest'];
                    $t_sch_prin += $ar['principal'];
                    $t_sch_penal += $ar['penalty'];
                    $t_sch_fee += $ar['fee'];
                    $t_sch_other_fee += $ar['other_fee'];

                    $t_sch_int_arr[$key] += $ar['interest'];
                    $t_sch_prin_arr[$key] += $ar['principal'];
                    $t_sch_penal_arr[$key] += $ar['penalty'];
                    $t_sch_fee_arr[$key] += $ar['fee'];
                    $t_sch_other_fee_arr[$key] += $ar['other_fee'];

                    $month_idx[] = $ar['month_idx'];

                }

            }
        ?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="loan_id" value="{{ $search_results->id }}" />
            
            <hr/>
            <div class="row">

                    <!-- Grid to left -->
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label class="control-label col-sm-5">{{ trans('loan.l_disburse_date') }}</label>
                            <div class="col-md-7">
                                <input type="text" name="disburse_date" class="form-control" readonly="readonly" value="{{ $draft?$draft->disburse_date:$search_results->disburse_date }}" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-5">{{ trans('report.rpt_loan_amount') }}</label>
                            <div class="col-md-7">
                                <input type="text" name="amount" class="form-control"  readonly="readonly" value="{{ $draft?$draft->loan_amount:$search_results->loan_amount }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-5">{{ trans('report.rpt_outstanding_amount') }}</label>
                            <div class="col-md-7">
                                <input type="text" name="loan_amount" class="form-control"  readonly="readonly" value="{{ $draft?$draft->loan_amount:$search_results->loan_amount }}"/>
                            </div>
                        </div>
                    </div>
                    <!-- Grid to right  -->
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label class="control-label col-sm-5">{{ trans('report.rpt_tenure') }}</label>
                            <div class="col-md-7">
                                <input type="text" name="loan_duration" class="form-control"  readonly="readonly" value="{{ $draft?$draft->loan_duration:$search_results->loan_duration }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-5">{{ trans('report.rpt_principal_paid') }}</label>
                            <div class="col-md-7">
                                <input type="text" name="principal_paid" class="form-control" id=""  readonly="readonly" value="{{$draft?$draft->principal_paid:$cal_result["total_paid_prin"]}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-5">{{ trans('loan.l_interest_rate') }}</label>
                            <div class="col-md-7">
                                <input type="text" name="interest_rate"  readonly="readonly" value="{{ $draft?$draft->interest_rate:$search_results->interest_rate }}" id="" class="form-control">
                            </div>
                        </div>
                    </div>
            </div>
            <hr/>
            <!--second row -->
            <div class="row">
                <!-- Grid to left -->
                <div class="col-sm-5">
                    <div class="form-group">
                        <label class="control-label col-sm-5">{{ trans('account.a_customer_account') }}</label>
                        <div class="col-md-7">
                            <input type="text" name="account_no" class="form-control"  readonly="readonly" value="{{$draft?$draft->account_no:$search_results->client_loan_account->account_no }}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-5">{{ trans('customer.cus_customer_name') }}</label>
                        <div class="col-md-7">
                            <input type="text" name="account_name" class="form-control"  readonly="readonly" value="{{ $draft?$draft->account_name:$search_results->client_loan_account->account_name }}"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-5">{{ trans('product.p_loan_reference') }}</label>
                        <div class="col-md-7">
                            <input type="text" name="contract_id" class="form-control" readonly="readonly" value="{{ $draft?$draft->contract_id:$search_results->contract_id }}"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-5">{{ trans('loan.l_branch') }}</label>
                        <div class="col-md-7">
                            <input type="text" readonly value="{{$draft?$draft->branch_name:$branch_name}}" class="form-control" name="branch_name" />
                            <input type="hidden" value="{{$draft?$draft->branch_code:$branch_code}}" class="form-control" name="branch_code" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-5">{{ trans('loan.waive_panalty') }}</label>
                        <div class="col-sm-7">

                            <div class="flat-green single-row icheck group">
                                <div class="radio" >
                                    <input type="checkbox" name="ch_waive_panalty" value="1" @if($draft->ch_waive_panalty==1) checked="checked" @endif/>
                                </div>
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


                </div>
                <!-- Grid to right  -->
                <div class="col-sm-5">

                    <div class="form-group">
                        <label class="control-label col-sm-5">{{ trans('report.rpt_overdue') }}(day)</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control"  readonly="readonly" id="overdue" name="overdue" value="{{ $draft?$draft->overdue:$cal_result['overdue'] }}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-5">{{ trans('loan.l_last_paid_date') }}</label>
                        <div class="col-md-7">
                            <?php $last_pay_date = $last_pay_date? date('Y-M-d', strtotime($last_pay_date)) : '_'?>
                            <input type="text" class="form-control"  readonly="readonly" id="" name="last_paid_date" value="{{ $draft?$draft->last_paid_date:$cal_result['last_pay_date'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-5">{{ trans('loan.l_next_schedule_date') }}</label>
                        <div class="col-md-7">
                            <?php $next_sch_date = $next_sch_date? date('Y-M-d', strtotime($next_sch_date)) : '_'?>
                            <input type="text" class="form-control"  readonly="readonly" id="" name="next_schedule_date" value="{{ $draft?$draft->next_schedule_date:$cal_result['next_sch_date'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-5">{{ trans('loan.l_currency') }}</label>
                        <div class="col-md-7">
                            <input type="text" readonly value="{{$draft?$draft->currency_name:$currency_arr[$currency]}}" class="form-control" name="currency_name"/>
                            <input type="hidden" value="{{$draft?$draft->currency:$currency}}" class="form-control" name="currency"/>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <!--third row -->


            <div class="row">

                <div class="col-sm-5">
                    {{--teller--}}

                    <div class="form-group">
                        <label class="col-md-5 control-label">{{ trans('loan.l_repay_date') }} <span class="red-color">*</span></label>
                        <div class="input-append date dpYears col-md-7" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" >
                            <input type="text" value="<?PHP if($dpDate){echo $dpDate;}else{ echo $draft?$draft->dpDate:'';  }?> "
                                   class="form-control" name="dpDate" id="dpDate">
                        <span class="add-on offonDatepicker">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-5 control-label">{{ trans('loan.l_repayment_amount') }}</label>
                        <div class="col-md-7">
                            <input type="text" name="repayment_amount" id="repayment_amount" class="form-control" value="{{$draft->repayment_amount}}" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-5 control-label">{{ trans('loan.tellers') }} <span class="red">*</span></label>

                           <div class="col-md-7">
                               <select class="form-control" name="sel_tellers" id="sel_tellers">
                                   <option value="0"> {{ trans('loan.select_teller') }} </option>
                                   @foreach($teller as $tellers )
                                       <option value="{{$tellers->id}}" @if($draft->sel_tellers == $tellers->id) selected @endif>{{ $tellers->account_name }} ( {{$tellers->name}} )</option>
                                   @endforeach
                               </select>

                           </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-5 control-label">{{ trans('multiple.m_receipt') }}</label>
                        <div class="col-sm-6">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                    @if($draft->repayment_receipt)
                                        <a href="/data/loans/receipts/{{$draft->repayment_receipt}}" target="_blank">
                                            <img src="/data/loans/receipts/{{$draft->repayment_receipt}}" alt="" />
                                        </a>
                                    @else
                                        <img src="{{ asset('images/noimage.gif', isset($secure)?false:false) }}" alt="" />
                                    @endif
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
                        <label class="col-md-5 control-label">{{ trans('loan.l_note') }}</label>
                        <div class="col-md-7">
                            <textarea name="note" style="width: 100%" class="form-control">{{$draft->note}}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-5 control-label">Repayment Type</label>
                        <div class="col-md-7">
                            <select class="form-control" name="payment_type" id="payment_type">
                                <option value="0">-</option>
                                @foreach($static['payment_type'] as $key => $value)
                                    <option value="{{ $key }}" @if($draft->payment_type == $value) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="col-sm-7">
                    <div class="panel panel-default box-border-500">
                        <div class="panel-heading">
                            <a class="btn btn-info btn-xs" href="#mSchedule" data-toggle="modal" id="add_loan_repayment">
                                <i class="fa fa-eye"></i>
                                {{ trans('loan.l_repayment_schedule') }}
                            </a>
                        </div>
                        <!-- this is the repayment dialog-->
                        <div class="panel-body">
                            <div class="row">
                                <!-- Grid to left -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-5"></label>
                                        <div class="col-md-7">
                                            {{ trans('loan.l_schedule') }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.rpt_interest') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" id="sch_interest" name="sch_interest" value="{{$draft?$draft->sch_interest:$t_sch_int}}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.rpt_fee') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" id="sch_fee" name="sch_fee" value="{{$draft?$draft->sch_fee:$t_sch_fee}}" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.rpt_other_fee') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" id="sch_other_fee" name="sch_other_fee" value="{{$draft?$draft->sch_other_fee:$t_sch_other_fee}}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.rpt_penalty') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" id="sch_penalty" name="sch_penalty" value="{{$draft?$draft->penalty:$t_sch_penal}}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.rpt_principal') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" id="sch_principal" name="sch_principal" value="{{$draft?$draft->sch_principal:$t_sch_prin}}"/>
                                        </div>
                                    </div>
                                </div>
                                <!-- Grid to right  -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-5"></label>
                                        <div class="col-md-7">
                                            Actual
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.rpt_interest') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" name="act_interest" class="form-control"  id="act_interest"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.rpt_fee') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" name="act_fee" id="act_fee" class="form-control">
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.rpt_other_fee') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" name="act_other_fee" id="act_other_fee" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.rpt_penalty') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" name="act_penalty" id="act_penalty"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.rpt_principal') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" name="act_principal" class="form-control"  id="act_principal" value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.amount_payable') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" name="amount_payable" id="amount_payable"  value = "" readonly="readonly" class="form-control">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.rpt_total') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" id="sch_total"  readonly="readonly" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">{{ trans('report.rpt_total') }}</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control"  readonly="readonly" id="act_total" name="act_total" value=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="balloon_amount_array" name="balloon_amount_array"></div>
                    </div>
                </div>


                <div class="text-center">
                    <div class="clear-fix"></div>
                    <button type="submit" id="save" class="btn btn-info"><i class="fa fa-save"></i>&nbsp;{{ trans('multiple.m_save') }}</button>
                    <button type="button" class="btn btn-danger" onclick="javascript:history.back();"><i class="fa fa-times-circle"></i>&nbsp;{{ trans('multiple.m_cancel') }}</button>
                </div>

                <?php if ($search_results) {//&& $schedule_principal > 0?>
                    <div class="col-md-12" id="jd-block">
                        <h4>Journal Detail</h4><hr/>
                        @if(isset($sch_repay_down_loan_arr['downpayment']))
                        <div class="b-block act_principal_downpay">
                            <h5><strong>Down Payment Repayment</strong></h5>
                            <?php
                            $params = array(
                                'debit_down' => $loan->loan_amount,
                                'credit_down' => $loan->loan_amount,
                                'parent_debit_down' => $coa_dd->id,
                                'parent_credit_down' => $coa->id,
                                'parent_debit_label_down' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                                'parent_credit_down_label' => $coa->name . ' (' . $branch_code . $coa->account_code . ')',
                                'd_description_down'=>($draft->d_description4 != "")?$draft->d_description4 : "Principal Repayment - ". $search_results->client_loan_account->account_name . " - ". $search_results->contract_id,
                                'c_description_down'=>($draft->c_description4 != "")?$draft->c_description4 : "Principal Repayment - ". $search_results->client_loan_account->account_name . " - ". $search_results->contract_id,
                                'description_down'=>$draft->description4,
                            );
                            echo getJournalDetailDownpayment($params);
                            ?>
                        </div>
                        @endif
                        <div class="b-block act_principal">
                            <h5><strong>Principal Repayment</strong></h5>
                            <?php
                            $params = array(
                                'debit' => $loan->loan_amount,
                                'credit' => $loan->loan_amount,
                                'parent_debit' => $coa_dd->id,
                                'parent_credit' => $coa->id,
                                'parent_debit_label' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                                'parent_credit_label' => $coa->name . ' (' . $branch_code . $coa->account_code . ')',
                                'd_description'=>($draft->d_description4 != "")?$draft->d_description4 : "Principal Repayment - ". $search_results->client_loan_account->account_name . " - ". $search_results->contract_id,
                                'c_description'=>($draft->c_description4 != "")?$draft->c_description4 : "Principal Repayment - ". $search_results->client_loan_account->account_name . " - ". $search_results->contract_id,
                                'description'=>$draft->description4,
                            );
                            echo getJournalDetail($params);
                            ?>
                        </div>

                        <div class="b-block act_air_interest">
                            <h5><strong>Interest Repayment(AIR)</strong></h5>
                            <?php
                            $params = array(
                                'debit' => $loan->loan_amount,
                                'credit' => $loan->loan_amount,
                                'parent_debit' => $coa_dd->id,
                                'parent_credit' => $coa_air->id,
                                'parent_debit_label' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                                'parent_credit_label' => $coa_air->name . ' (' . $branch_code . $coa_air->account_code . ')',
                                'd_description'=>($draft->d_description1 != "")?$draft->d_description1 : "Interest Repayment - ". $search_results->client_loan_account->account_name . " - ". $search_results->contract_id,
                                'c_description'=>($draft->c_description1 != "")?$draft->c_description1 : "Interest Repayment - ". $search_results->client_loan_account->account_name . " - ". $search_results->contract_id,
                                'description'=>$draft->description1,
                            );
                            echo getJournalDetail($params);
                            ?>
                        </div>

                        <div class="b-block act_interest">
                            <h5><strong>Interest Repayment(Interest Income)</strong></h5>
                            <?php
                            $params = array(
                                'debit' => $loan->loan_amount,
                                'credit' => $loan->loan_amount,
                                'parent_debit' => $coa_dd->id,
                                'parent_credit' => $coa_int_inc->id,
                                'parent_debit_label' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                                'parent_credit_label' => $coa_int_inc->name . ' (' . $branch_code . $coa_int_inc->account_code . ')',
                                'd_description'=>$draft->d_description0,
                                'c_description'=>$draft->c_description0,
                                'description'=>$draft->description0,
                            );
                            echo getJournalDetail($params);
                            ?>
                        </div>

                        <div class="b-block act_penalty">
                            <h5><strong>Penalty Charge</strong></h5>
                            <?php
                            $params = array(
                                'debit' => $loan->loan_amount,
                                'credit' => $loan->loan_amount,
                                'parent_debit' => $coa_dd->id,
                                'parent_credit' => $coa_pnt->id,
                                'parent_debit_label' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                                'parent_credit_label' => $coa_pnt->name . ' (' . $branch_code . $coa_pnt->account_code . ')',
                                'd_description'=>($draft->d_description2 != "")?$draft->d_description2 : "Penalty Repayment - ". $search_results->client_loan_account->account_name . " - ". $search_results->contract_id,
                                'c_description'=>($draft->c_description2 != "")?$draft->c_description2 : "Penalty Repayment - ". $search_results->client_loan_account->account_name . " - ". $search_results->contract_id,
                                'description'=>$draft->description2,
                            );
                            echo getJournalDetail($params);
                            ?>
                        </div>

                        <div class="b-block act_fee">
                            <h5><strong>Other Fee</strong></h5>
                            <?php
                            $params = array(
                                'debit' => $loan->loan_amount,
                                'credit' => $loan->loan_amount,
                                'parent_debit' => $coa_dd->id,
                                'parent_credit' => $coa_ap->id,
                                'parent_debit_label' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                                'parent_credit_label' => $coa_ap->name . ' (' . $branch_code . $coa_ap->account_code . ')',
                                'd_description'=>($draft->d_description3 != "")?$draft->d_description3 : "Other Fee Charge Repayment - ". $search_results->client_loan_account->account_name . " - ". $search_results->contract_id,
                                'c_description'=>($draft->c_description3 != "")?$draft->c_description3 : "Other Fee Charge Repayment - ". $search_results->client_loan_account->account_name . " - ". $search_results->contract_id,
                                'description'=>$draft->description3,
                            );
                            echo getJournalDetail($params);
                            ?>
                        </div>

                    </div>
                <?php } ?>
            </div>
        </form>
        <div>
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="mSchedule" class="modal fade">
                <div class="modal-dialog md-modify">
                    <div class="modal-content">
                        <div id="printArea" class="printArea">
                            <div class="modal-header bo-border">
                                <ul id="language" class="pull-right">
                                    <li><button aria-hidden="true" data-dismiss="modal" class="btn btn-danger" id="close">ﾃ</button></li>
                                </ul>
                                <?php
                                $loan_id = 0;
                                foreach ($search_results->schedule as $s) {
                                    $loan_id = $s->loan_id;
                                }
                                ?>

                                <h4 class="modal-title schedule_title">{{ trans('loan.l_repayment_schedule') }}: {{ str_pad($loan_id, 6, '0', STR_PAD_LEFT) }}</h4>
                            </div>
                            <div class="modal-body" id ="schedule-table"></div>
                            <table class="table table-bordered table-striped table-condensed tbrepayment_sch">
                            <thead>
                            <tr>
                                <th style="text-align: center;" colspan="6">Down Payment Plan</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">No</th>
                                <th style="text-align: center;">Payment Date</th>
                                <th style="text-align: center;">Beginning Balance</th>
                                <th style="text-align: center;">Payment Amount</th>
                                <th style="text-align: center;">Ending Balance</th>
                                <th class="invisible_edit">Action</th>
                            </tr>
                            </thead>
                            <tbody id="tbody">
                                @include('partials.tb_repayment_schedule_downpayment',compact('downpayment'))
                            </tbody>
                        </table>
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <th style="text-align: center;">{{ trans('account.no') }}</th>
                                <th style="text-align: center;">{{ trans('loan.l_schedule_date') }}</th>
                                <th style="text-align: center;">{{ trans('account.a_date_number') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_principal') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_interest') }}</th>
                                <th style="text-align: center;">{{ trans('report.rpt_fee') }}</th>
                                <th style="text-align: center;">{{ trans('loan.l_monthly_pay') }}</th>
                                </thead>

                                <tbody>
                                    <?php
                                    $n = 1;
                                    foreach ($repayment_schedule as $schedule) {
                                        ?>
                                        <tr>
                                            <td align="center">{{ $schedule->loan_no }}</td>
                                            <td align="center">{{ $schedule?$schedule->schedule_date:'N/A' }}</td>
                                            <td align="center">{{ $schedule?$schedule->date_num:'N/A' }}</td>
                                            <td align="center">{{ $schedule?$schedule->principal:'N/A' }}</td>
                                            <td align="center">{{ $schedule?$schedule->interest:'N/A' }}</td>
                                            <td align="center">{{ $schedule?$schedule->fee:'N/A' }}</td>
                                            <td align="center">{{ ($schedule->principal + $schedule->interest + $schedule->fee) }}</td>
                                        </tr>
                                        <?php $n++;
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/accounting.min.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('theme/js/iCheck/jquery.icheck.js',isset($secure) ? false : false)}}"></script>
<script>
$(document).ready(function() {
  $('.dpYears').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        setDate: new Date()
  });
})
</script>
<script type="text/javascript">
    var m_penalty = 0;
    var sch_principal = parseFloat($("#sch_principal").val());
    var sch_interest = parseFloat($("#sch_interest").val());
    var sch_fee = parseFloat($("#sch_fee").val());
    var sch_other_fee = parseFloat($("#sch_other_fee").val());
    var sch_penalty = parseFloat($("#sch_penalty").val());
    var dd_balance = "<?php echo $drawdown_bal; ?>";
    dd_balance = parseFloat(dd_balance);
    var air_amount = "<?php echo $air_amount; ?>";

    var sch_repay_down_loan_arr = <?php echo json_encode($sch_repay_down_loan_arr); ?>;
    var GENERAL_LC_STATUS = <?php echo GENERAL_LC_STATUS; ?>;

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
        // $('.group input').iCheck({
        //     checkboxClass: 'icheckbox_flat-green',
        //     radioClass: 'iradio_flat-green'
        // }).on('ifToggled', function (e) {
        //     e.preventDefault();
        //     var chck = $(this).prop('checked');
        //     if (chck) {
        //         $('.file_waive_panalty').show();
        //         m_penalty = $("#sch_penalty").val();
        //         $("#sch_penalty").val(0);
        //         sch_penalty = parseFloat($("#sch_penalty").val());
        //         var sch_total = 0.0;
        //         sch_total = sch_principal + sch_interest + sch_penalty + sch_fee;
        //         sch_total = Math.round(sch_total * 100)/100;
        //         $("#sch_total").val(sch_total);
        //     } else {
        //         $('.file_waive_panalty').hide();
        //         $("#sch_penalty").val(m_penalty);
        //         sch_penalty = parseFloat($("#sch_penalty").val());
        //         var sch_total = 0.0;
        //         sch_total = sch_principal + sch_interest + sch_penalty + sch_fee;
        //         sch_total = Math.round(sch_total * 100)/100;
        //         $("#sch_total").val(sch_total);
        //     }
        // });
    <?php } ?>



    $(document).ready(function () {
        var sch_total = 0.0;
        var submited = false;
        sch_total = sch_principal + sch_interest + sch_penalty + sch_fee + sch_other_fee;
        sch_total = Math.round(sch_total * 100)/100;
        $("#sch_total").val(sch_total);

        $('#addLoanRepaymentForm').validate({
            rules: {
                sel_tellers: {
                    required: true
                },
                repayment_amount:{
                    required:true,
                    underBalance:true
                }
            },
			messages:{
				sel_tellers:{
					required:"Please select valid teller"
				},
                repayment_amount:{
                    required:"Please select valid number",
                    underBalance:"Customer drawdown account balance is " +  dd_balance
                }
			},
      submitHandler: function(form) {
          // some other code
          // maybe disabling submit button
          // then:
//          console.log("submite-----");
          if(!submited){
            submited = true;
//              console.log("submitedd-----");
               form.submit();
          }
        }
        });

        $.validator.addMethod("noVals", function (value, element, arg) {
            return arg != value;
        }, "please select teller");
        $.validator.addMethod("underBalance", function(val){
            return (val <= dd_balance);
        }, "");

        if($("#repayment_amount").val()!='' && $("#repayment_amount").val()!=null){
            $("#repayment_amount").trigger('keyup');
        }
    });

    $("#repayment_amount").on('keyup', function (e) {
        this.value = this.value.replace(/[^0-9\.]/g, ''); //only number accept
        calculation();
    });
    $("#act_interest").on('keyup', function (e) {
        this.value = this.value.replace(/[^0-9\.]/g, ''); //only number accept
        //Calculation();
        reCalculation();
    });
     $("#act_fee").on('keyup', function (e) {
        this.value = this.value.replace(/[^0-9\.]/g, ''); //only number accept
        // Calculation();
        reCalculation()
     });
    $("#act_penalty").on('keyup', function (e) {
        this.value = this.value.replace(/[^0-9\.]/g, ''); //only number accept
        // Calculation();
        reCalculation();
     });
    $("#act_principal").on('keyup', function (e) {
        this.value = this.value.replace(/[^0-9\.]/g, ''); //only number accept
        // Calculation();
        reCalculation()
    });

    $("#sch_interest").on('keyup', function (e) {
      this.value = this.value.replace(/[^0-9\.]/g, ''); //only number accept
      reCalculation();

    });
     $("#sch_fee").on('keyup', function (e) {
        this.value = this.value.replace(/[^0-9\.]/g, ''); //only number accept
        reCalculation();
     });
    $("#sch_penalty").on('keyup', function (e) {
        this.value = this.value.replace(/[^0-9\.]/g, ''); //only number accept
        reCalculation();
     });
    $("#sch_principal").on('keyup', function (e) {
             this.value = this.value.replace(/[^0-9\.]/g, ''); //only number accept
             reCalculation();
    });

    function reCalculation()
    {
        act_principal = act_int_income = act_interest = act_fee = act_penalty = act_total = 0;
        act_principal = Number($("#act_principal").val());
        act_interest = Number($("#act_interest").val());
        act_penalty = Number($("#act_penalty").val());
        act_fee =  Number($("#act_fee").val());
        act_other_fee =  Number($("#act_other_fee").val());
        act_total = act_principal + act_interest + act_penalty + act_fee + act_other_fee;
        $("#act_total").val(act_total.toFixed(2));
        // console.log(act_int_income);
        //chuch hide b-block
        if (act_principal <= 0) {
            $('.act_principal').hide();
        } else {
            $('.act_principal').show();
        }
        if (act_interest <= 0) {
            $('.act_air_interest').hide();
        } else {
            $('.act_air_interest').show();
        }
        if (act_int_income <= 0) {
            $('.act_int_income').hide();
        } else {
            $('.act_int_income').show();
        }

        if (act_penalty <= 0) {
            $('.act_penalty').hide();
        } else {
            $('.act_penalty').show();
        }
        if (act_fee <= 0) {
            $('.act_fee').hide();
        } else {
            $('.act_fee').show();
        }
		if (act_other_fee <= 0) {
            $('.act_other_fee').hide();
        } else {
            $('.act_other_fee').show();
        }
        if (amount_payable <= 0) {
            $('.amount_payable').hide();
        } else {
            $('.amount_payable').show();
        }

        i = 0;
        $('.parent_debit_className_value').each(function () {
            i++;
            if (i == 1)
                $(this).val(act_principal);
            if (i == 2)
                $(this).val(act_interest);
            if (i == 3)
                $(this).val(act_int_income);
            if (i == 4)
                $(this).val(act_penalty);
            if (i == 5)
                $(this).val(act_fee);
			if (i == 6)
                $(this).val(act_other_fee);
            if(i==7)
                $(this).val($('#amount_payable').val());
        });

        i = 0;
        $('.parent_credit_className_value').each(function () {
            i++;
            if (i == 1)
                $(this).val(act_principal);
            if (i == 2)
                $(this).val( act_interest );
            if (i == 3)
                $(this).val(act_int_income);
            if (i == 4)
                $(this).val(act_penalty);
            if (i == 5)
                $(this).val(act_fee);
			if (i == 6)
                $(this).val(act_fee);
            if(i==7)
                $(this).val($('#amount_payable').val());
        });
        
        sch_principal = Number($("#sch_principal").val());
        sch_interest = Number($("#sch_interest").val());
        sch_penalty = Number($("#sch_penalty").val());
        sch_fee =  Number($("#sch_fee").val());
        amount_payable = Number($("#amount_payable").val());
        sch_total = sch_principal + sch_interest + sch_penalty + sch_fee + amount_payable;
        $("#sch_total").val(sch_total.toFixed(2));
        calculation();
        

        /*
        $('.parent_debit_className_value').each(function () {
            i++;
            if (i == 1)
                $(this).val(act_principal.toFixed(2));
            if(i == 2)
                $(this).val(air_amount.toFixed(2));
            if (i == 4)
                $(this).val(act_penalty.toFixed(2));
            if (i == 5)
                $(this).val(act_fee.toFixed(2));
            if(i==6)
                $(this).val(amount_payable.toFixed(2));
        });

        i = 0;
        $('.parent_credit_className_value').each(function () {
            i++;
            if (i == 1)
                $(this).val(act_principal.toFixed(2));
             if(i == 2)
                  $(this).val(air_amount.toFixed(2));
            if (i == 4)
                $(this).val(act_penalty.toFixed(2));
            if (i == 5)
                $(this).val(act_fee.toFixed(2));
            if(i==6)
                $(this).val(amount_payable.toFixed(2));
        });
        */
    }
    function calculation()
    {
        var overdue = <?php if(isset($cal_result['overdue'])){ echo $cal_result['overdue'];}else{echo 0;}?>;
        var lc_status = <?php if(isset($cal_result['overdue'])){ echo $search_results->client_loan_account->status;}else{echo 0;} ?>;
        var act_principal = 0.0;
        var act_principal_downpay = 0.0;
        var act_interest = 0.0;
        var act_penalty = 0.0;
        var act_fee = 0.0;
		var act_other_fee = 0.0;
        var total_act_principal = 0.0;
        var total_act_principal_downpay = 0.0;
        var total_act_interest = 0.0;
        var total_act_penalty = 0.0;
        var total_act_fee = 0.0;
		var total_act_other_fee = 0.0;
        var amount_payable = 0.0;
        var act_total = 0.0;
        var repayment_amount = $("#repayment_amount").val();
        var sch_penalty = Number($("#sch_penalty").val());
        var sch_interest = Number($("#sch_interest").val());
        var sch_fee = Number($("#sch_fee").val());
        var sch_other_fee = Number($("#sch_other_fee").val());
        var sch_penalty = Number($("#sch_penalty").val());
        var sch_principal = Number($("#sch_principal").val());
        var sch_principal_downpay = Number("{{ $t_sch_prin_arr['downpayment'] }}");
        console.log(sch_principal_downpay)
        // priority: interest > penalty > principal
        if(repayment_amount == '' || repayment_amount == null){
            repayment_amount  = 0;
        }
        repayment_amount = parseFloat(repayment_amount);
        var dpDate = $("#dpDate").val();
        var balance = repayment_amount;
        var NPL_flg = 0;
        if(balance > 0){
            if(balance > Math.round(sch_principal)){
                total_act_principal = sch_principal;
            }else{
                total_act_principal = balance;
            }
            balance -= total_act_principal;
            if(total_act_principal > Math.round(sch_principal_downpay)){
                total_act_principal_downpay = sch_principal_downpay;
            }else{
                total_act_principal_downpay = total_act_principal;
            }
            total_act_principal -= total_act_principal_downpay;
        }

        if(balance > 0){
            if(balance > Math.round(sch_interest)){
                total_act_interest = sch_interest;
            }else{
                total_act_interest = balance;
            }
            balance -= total_act_interest;
        }

        if(balance > 0){
            if(balance > Math.round(sch_penalty)){
                total_act_penalty = sch_penalty;
            }else{
                total_act_penalty = balance;
            }
            balance -= total_act_penalty;
        }

        if(balance > 0){
            if(balance > Math.round(sch_fee)){
                total_act_fee = sch_fee;
            }else{
                total_act_fee = balance;
            }
            balance -= total_act_fee;
        }
        if(balance > 0){
            if(balance > Math.round(sch_other_fee)){
                total_act_other_fee = sch_other_fee;
            }else{
                total_act_other_fee = balance;
            }
            balance -= total_act_other_fee;
        }
        // total_act_principal_downpay += act_principal_downpay;
        // total_act_principal += act_principal;
        // total_act_interest += act_interest;
        // total_act_fee += act_fee;
        // total_act_other_fee += act_other_fee;
        // total_act_penalty += act_penalty;

       //  $.each(sch_repay_down_loan_arr,function (indexs,sch_repay_arr){
       //      act_interest = act_fee= act_penalty= act_principal = act_principal_downpay = other_fee =  0;
       //      $.each(sch_repay_arr, function (index, sch) {
       //          //interest
       //          if(balance > Math.round(sch.interest* 100)/100){
       //              act_interest =  Math.round(sch.interest* 100)/100;}
       //          else{
       //              act_interest = balance;
       //          };
       //          balance = balance - act_interest;
       //          // fee
       //          if(balance > 0){
       //              if(balance > Math.round(sch.fee* 100)/100){
       //                  act_fee =  Math.round(sch.fee* 100)/100;}
       //              else{
       //                  act_fee = balance;
       //              };
       //              balance -= act_fee;
       //          }
    			// // Other fee
       //          if(balance > 0){
       //              if(balance > Math.round(sch.other_fee* 100)/100){
       //                  act_other_fee =  Math.round(sch.other_fee* 100)/100;}
       //              else{
       //                  act_other_fee = balance;
       //              };
       //              balance -= act_other_fee;
       //          }
       //          //PL or NPL
       //          NPL_flg = (lc_status > GENERAL_LC_STATUS)? 1 : 0;
       //          // console.log(sch.penalty);
       //          if(sch_penalty >= sch.penalty){
       //              new_sch_penalty = sch.penalty;
       //          }else{
       //              new_sch_penalty = sch_penalty;
       //          }
       //          sch_penalty = sch_penalty - new_sch_penalty;
       //          // if(sch_penalty != sch.penalty){
       //          //     new_sch_penalty = sch_penalty;
       //          // }
       //          console.log(balance); 
       //          console.log('new_sch_penalty'+new_sch_penalty); 
       //          if(NPL_flg == 0){  // PL customer
       //              // penalty
       //              if(sch.type != 'downpayment'){
       //                  if(balance > 0){
       //                      if(balance > Math.round(new_sch_penalty* 100)/100){
       //                          act_penalty =  Math.round(new_sch_penalty* 100)/100;
       //                          // console.log(act_penalty);
       //                      }
       //                      else{
       //                          act_penalty = balance;
       //                      };
       //                      balance -= act_penalty;
       //                  }
       //                  // console.log(act_penalty);
       //              }
       //              // principal
       //              if(sch.type == 'downpayment'){
       //                  if(balance > 0){
       //                      if(balance > Math.round(sch.principal* 100)/100){
       //                          act_principal_downpay =  Math.round(sch.principal* 100)/100;}
       //                      else{
       //                          act_principal_downpay = balance;
       //                      };
       //                      balance -= act_principal_downpay;
       //                  }
       //              }else{
       //                  if(balance > 0){
       //                      if(balance > Math.round(sch.principal* 100)/100){
       //                          act_principal =  Math.round(sch.principal* 100)/100;}
       //                      else{
       //                          act_principal = balance;
       //                      };
       //                      balance -= act_principal;
       //                  }
       //              }
       //          }else{   
       //          console.log(balance);      
       //              // NPL customer
       //              // principal
       //              if(sch.type == 'downpayment'){
       //                  if(balance > 0){
       //                      if(balance > Math.round(sch.principal* 100)/100){
       //                          act_principal_downpay =  Math.round(sch.principal* 100)/100;}
       //                      else{
       //                          act_principal_downpay = balance;
       //                      };
       //                      balance -= act_principal_downpay;
       //                  }
       //              }else{
       //                  if(balance > 0){
       //                      if(balance > Math.round(sch.principal* 100)/100){
       //                          act_principal =  Math.round(sch.principal* 100)/100;}
       //                      else{
       //                          act_principal = balance;
       //                      };
       //                      balance -= act_principal;
       //                  }
       //              }
       //          }
       //          total_act_principal_downpay += act_principal_downpay;
       //          total_act_principal += act_principal;
       //          total_act_interest += act_interest;
       //          total_act_fee += act_fee;
    			// total_act_other_fee += act_other_fee;
       //          total_act_penalty += act_penalty;
       //      });

       //  });
        // if (repayment_amount <= sch_interest) {
        //     act_interest = repayment_amount;
        // } else {
        //     act_interest = sch_interest;
        //     repayment_amount -= act_interest;
        //     if(repayment_amount <= sch_fee) {
        //         act_fee = repayment_amount;
        //     }else{
        //       act_fee = sch_fee;
        //       repayment_amount -= act_fee;
        //       if (repayment_amount <= sch_penalty) {
        //         act_penalty = repayment_amount;
        //       } else {
        //         act_penalty = sch_penalty;
        //         repayment_amount -= act_penalty;
        //         if (repayment_amount <= sch_principal) {
        //             act_principal = repayment_amount;
        //         } else {
        //             act_principal = sch_principal;
        //             repayment_amount -= act_principal;
        //             amount_payable = Math.round(repayment_amount * 100)/100;
        //         }
        //       }
        //     }
        // }
        act_total = total_act_principal + total_act_principal_downpay + total_act_interest + total_act_penalty + total_act_fee + total_act_other_fee + amount_payable;
        var act_int_income = 0.0;
        var pay_int = 0.0;
        if(total_act_interest - air_amount > 0){
            act_int_income = total_act_interest - air_amount;
            pay_int = air_amount;
        }else{
            pay_int = total_act_interest;
        }
        if(act_total == NaN){
            act_total = 0;
        }
        if(act_interest == NaN){
            act_interest = 0;
        }
        $("#act_principal").val((total_act_principal + total_act_principal_downpay).toFixed(2));
        $("#act_interest").val(total_act_interest.toFixed(2));
        $("#act_penalty").val(total_act_penalty.toFixed(2));
        $("#act_fee").val(total_act_fee.toFixed(2));
        $("#act_other_fee").val(total_act_other_fee.toFixed(2));
        $("#amount_payable").val(amount_payable.toFixed(2));
        $("#act_total").val(act_total.toFixed(2));
        console.log(total_act_penalty.toFixed(2))
        //chuch hide b-block
        if (total_act_principal_downpay <= 0) {
            $('.act_principal_downpay').hide();
        } else {
            $('.act_principal_downpay').show();
        }
        if (total_act_principal <= 0) {
            $('.act_principal').hide();
        } else {
            $('.act_principal').show();
        }
        if (air_amount <= 0) {
            $('.act_air_interest').hide();
        } else {
            $('.act_air_interest').show();
        }
        if (act_int_income <= 0) {
            $('.act_interest').hide();
        } else {
            $('.act_interest').show();
        }

        if (total_act_penalty <= 0) {
            $('.act_penalty').hide();
        } else {
            $('.act_penalty').show();
        }
        if (total_act_fee <= 0) {
            $('.act_fee').hide();
        } else {
            $('.act_fee').show();
        }
		if (total_act_other_fee <= 0) {
            $('.act_other_fee').hide();
        } else {
            $('.act_other_fee').show();
        }
        if (amount_payable <= 0) {
            $('.amount_payable').hide();
        } else {
            $('.amount_payable').show();
        }

        i = 0;
        $('.parent_debit_className_value').each(function () {
            i++;
            if (i == 1)
                $(this).val(total_act_principal.toFixed(2));
            if (i == 2)
                $(this).val(pay_int);
            if (i == 3)
                $(this).val(act_int_income);
            if (i == 4)
                $(this).val($('#act_penalty').val());
            if (i == 5)
                $(this).val(total_act_fee);
            if (i == 6)
                $(this).val(total_act_other_fee);
            if(i==7)
                $(this).val($('#amount_payable').val());
        });
        d = 0;
        $('.parent_debit_down_className_value').each(function () {
            d++;
            if (d == 1)
                $(this).val(total_act_principal_downpay.toFixed(2));
        });
        i = 0;
        $('.parent_credit_className_value').each(function () {
            i++;
            if (i == 1)
                $(this).val(total_act_principal.toFixed(2));
            if (i == 2)
                $(this).val( pay_int );
            if (i == 3)
                $(this).val(act_int_income);
            if (i == 4)
                $(this).val($('#act_penalty').val());
            if (i == 5)
                $(this).val(total_act_fee);
            if (i == 6)
                $(this).val(total_act_other_fee);
            if(i==7)
                $(this).val($('#amount_payable').val());
        });
        d = 0;
        $('.parent_credit_down_className_value').each(function () {
            d++;
            if (d == 1)
                $(this).val(total_act_principal_downpay.toFixed(2));
        });
    }
    $(document).on('change', '#sel_tellers', function () {

        var till_account = JSON.parse('<?PHP echo json_encode($teller);?>');
        var Sel_till_account_id = $('#sel_tellers option:selected').val();

        $.each(till_account, function (inx, vals) {
            if (parseInt(Sel_till_account_id) === parseInt(vals.id)) {
                if(parseInt(vals.status) === 1) {
                    alert('This till account was closed');
                    $('#sel_tellers').val(0);
                    $('#sel_tellers').attr('selected', true);
                }
            }
        });
    });

    $("#dpDate").on("change", function () {
        //var loan_data = <?php echo json_encode($search_results);?>;
        var loan_id = "<?php echo $loan_id; ?>";

        $('#dpDateClone').val($("#dpDate").val());//Add value to dpDate after submit
        $('._search').trigger('click')
/*
        $.ajax({
            url: '/loans/tmp',
            type: 'GET',
            data: data,

            success: function(data, status){
                if(status == "success"){

                    console.log(data)
                }
         },error:function(r,statusText){
            console.log(statusText)
         }
    });
*/
    });
    if($('.loan_contract_id').length==1){
        $('.loan_contract_id').parents('form').submit();
    }


    var parent_debit_label_first = $('.parent_debit_label_className:first').val();
    var parent_debit_label_down_first = $('.parent_debit_label_down_className:first').val();

    $('select[name="payment_type"]').change(function () {
        this_ = $(this);
        var coa_dd = <?php echo json_encode($coa_dd); ?>;
        var branch_code = "<?php echo $branch_code . '-' ?>";
        if(this_.val() == 0){
             $('.parent_debit_label_className').each(function(){
                 $(this).val(parent_debit_label_first);
             });
             $('.parent_debit_label_down_className').each(function(){
                 $(this).val(parent_debit_label_down_first);
             });
             $('.parent_debit_className').each(function(){
                 $(this).val(coa_dd.id);
             });
             $('.parent_debit_down_className').each(function(){
                 $(this).val(coa_dd.id);
             });
        }else{
            $.ajax({
                url: "{{ route('getDisburseType') }}",
                data: "select_type=" + this_.val() + "&currency=" + $('input[name="currency"]').val(),
                method: 'get',
                success: function (res) {
                    res = JSON.parse(res);
                     var debit_value = res.name + " ("+ branch_code + res.account_code + ")";
                    $('.parent_debit_label_className').each(function(){
                        $(this).val(debit_value);
                    });
                    $('.parent_debit_label_down_className').each(function(){
                         $(this).val(debit_value);
                     });
                    $('.parent_debit_className').each(function(){
                        $(this).val(res.id);
                    });
                    $('.parent_debit_down_className').each(function(){
                         $(this).val(res.id);
                     });
                }
            });
        }
    });
</script>

@endsection
