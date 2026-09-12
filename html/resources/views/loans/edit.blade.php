<?php
function html_form($ilabel, $irequired_flg, $iname, $iid, $value)
{
    $value = $value ? $value : "";
    $str = '<div class="col-sm-5"><label class="control-label">' . $ilabel;
    if ($irequired_flg == true) {
        $str .= '<span class="red">*</span>';
    }
    $str .= '</label></div>
                <div class="col-sm-7">
                <input type="text" name="' . $iname . '" id="' . $iid . '" class="form-control" value="' . $value . '">
            </div>';
    return $str;
}
?>
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <link href="{{ asset('theme/js/iCheck/skins/flat/green.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <style type="text/css">
        td.none-border {
            border-top: none !important;
            border-bottom: none !important;
        }
        td.no-border {
            border-bottom: none !important;
        }
        .tbrepayment tr, .tbrepayment td {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')
    <section class="panel">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
        @endif
        <?php $static = config('static_data');
        $prod_array = array('' => '');
        ?>
        <header class="panel-heading">
            {{ trans('loan.l_edit_loan_application') }}
        </header>
        <div class="panel-body">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('loan_edit',[$loan->id])}}" method="POST" class="cmxform form-horizontal" id="addloanForm" enctype="multipart/form-data">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#loan-frm">{{ trans('loan.detail') }}</a></li>
                    <li><a data-toggle="tab" href="#loan-schedule">{{ trans('loan.l_repayment_schedule') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="loan-frm" class="tab-pane active">
                        <br/>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="lid" value="{{ $loan->id }}"/>
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-12">
                                    <span class="blue">{{ trans('multiple.m_general') }}</span>
                                    <hr/>
                                </div>
                                <!-- Grid to left -->
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('customer.cus_customer_id') }}</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="client-id" name="client_id" value="{{ $loan->client->cus_acc }}" readonly />
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('report.rpt_contract_id') }}</label>
                                        </div>
                                        <div class="col-sm-7">
                                            @if( $loan->status == 7)
                                                <input type="hidden" id="contract_id" name="contract_id" value="{{$loan->contract_id }}" readonly/>
                                                <p class="form-control">{{$loan->contract_id }}</p>
                                            @else
                                                <input type="text" class="form-control" id="contract_id" name="contract_id" value="{{$loan->contract_id }}" readonly/>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('report.rpt_branch_name') }}<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <select id="branch_name" style="width: 100%" name="company_branch_id">
                                                <option value="0">-</option>
                                                @foreach($branch_name as $b)
                                                    <option value={{$b['id']}} {{ $loan->company_branch_id==$b['id']?'selected':'' }}>{{$b['branch_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.project') }}<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <select id="project_id" style="width: 100%" name="project_id">
                                                    @foreach($projects as $project)
                                                        <option value={{$project['id']}}>{{ $project['short_code']." - ".$project['dealer'] }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.unit_type') }}<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <select id="unit_type_id" style="width: 100%" name="unit_type_id">
                                                    @foreach($unit_types as $unit_type)
                                                        <option value={{$unit_type['id']}}>{{ $unit_type['short_code']." - ".$unit_type['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('unit.unit') }}<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <select id="unit_id" style="width: 100%" name="unit_id">
                                                    @foreach($units as $unit)
                                                        <option value={{$unit['id']}}>{{ $unit['code']." - ".$unit['price'] }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('report.rpt_loan_type') }}<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="loan_type" name="loan_type" value="{{$loan_type->product_type->products_type_name}}" readonly/>
                                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.l_submitted_on') }}<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}" class="input-append date dpYears">
                                                <input type="text" name="submitted_on" value="{{$loan->submitted_on?$loan->submitted_on:date('Y-m-d')}}" size="16" class="form-control" id="submitted_on">
                                                <span class="add-on birhtdateDatepicker">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('report.rpt_contract_date') }}</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div data-date-viewmode="years" data-initialize="datepicker" data-date="{{date('Y-m-d')}}" class="input-append date dpYears">
                                                <input type="text" name="contract_date" value="{{ $loan->contract_date or date('Y-m-d')}}" size="16" class="form-control" id="contract_date">
                                                <span class="add-on birhtdateDatepicker">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.contract_deadline') }}<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}" class="input-append date dpYears">
                                                <input type="text" name="contract_deadline" value="{{ $loan->contract_date?$loan->contract_date:date('Y-m-d')}}" size="16" class="form-control" id="contract_deadline">
                                                <span class="add-on birhtdateDatepicker">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Unit Sale Price<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="unit_sale_price" name="unit_sale_price" readonly value="{{ $loan->unit_sale_price }}"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Discount (Promotion)<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="discount_promotion" name="discount_promotion" readonly value="{{ $loan->discount_promotion }}"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Discount (Other)<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="discount_other" name="discount_other" value="{{ $loan->discount_other }}"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Remark<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="remark" name="status_remark" value="{{ $loan->status_remark }}" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Price After Discount<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="price_after_discount" name="price_after_discount" readonly value="{{ $loan->price_after_discount }}"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Discount $ (Pyment Option)<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="amount_discount_payment_option" name="amount_discount_payment_option"  value="{{ $loan->amount_discount_payment_option }}"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.clearance_amount')}}</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="clearance_amount" name="clearance_amount" value="{{ $loan->clearance_amount }}"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">{{ trans('loan.clearance_remark')}}</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="clearance_remark" name="clearance_remark" value="{{ $loan->clearance_remark }}"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Final Price<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="final_price" name="final_price" readonly value="{{ $loan->final_price }}"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Deposit Amount<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="diposit_amount" name="diposit_amount" value="{{ $loan->diposit_amount }}"/>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">Deposit Date<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}" class="input-append date dpYears">
                                                <input type="text" name="deposit_date" value="{{date('Y-m-d',strtotime($loan->deposit_date))}}" size="16" class="form-control" id="deposit_date">
                                                <span class="add-on birhtdateDatepicker">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">Start Payment Date<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}" class="input-append date dpYears">
                                                <input type="text" name="start_payment_date" value="{{date('Y-m-d',strtotime($loan->start_payment_date))}}" size="16" class="form-control" id="start_payment_date">
                                                <span class="add-on birhtdateDatepicker">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Start Payment No<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="start_payment_no" name="start_payment_no" value="{{ $loan->start_payment_no }}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="row" style="margin-bottom: 15px;">
                                        <div class="col-sm-5">
                                            <label class="control-label">Selected Payment Option<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <select id="payment_option" name="payment_option" style="width: 100%">
                                                    <option value=""> - </option>
                                                </select>
                                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                                <input type="hidden" class="form-control" id="payment_option_type" name="payment_option_type" value="0"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Has Down Payment ?<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <select id="hase_down_payment" name="hase_down_payment" class="form-control" readonly>
                                                    <option value="yes" <?php if($loan->hase_down_payment == 'yes') echo 'selected'?>>Yes</option>
                                                    <option value="no" <?php if($loan->hase_down_payment == 'no') echo 'selected'?>>No</option>
                                                </select>
                                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Down Payment Duration<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="down_payment_duration" name="down_payment_duration" value="{{ $loan->down_payment_duration }}" readonly/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="input-group" style="margin-top: 10px;">
                                                <select id="down_payment_type" name="down_payment_type" class="form-control" readonly>
                                                    <option value="%" <?php if($loan->down_payment_type == '%') echo 'selected'?>>Down Payment %</option>
                                                    <option value="$" <?php if($loan->down_payment_type == '$') echo 'selected'?>>Down Payment $</option>
                                                </select>
                                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="down_payment_value" name="down_payment_value" value="{{ $loan->down_payment_value }}" readonly/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Installment Duration (Months)<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="installment_duration" name="installment_duration" value="{{ $loan->installment_duration }}" readonly/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Annual Interest (%)<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="annual_interest" name="annual_interest" value="{{ $loan->annual_interest }}" readonly/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Discout % (Payment Option)<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="discount_payment_option" name="discount_payment_option" value="{{ $loan->discount_payment_option }}" readonly/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label class="control-label">Rouding Result<span class="red"> *</span></label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <select id="rouding_result" name="rouding_result" class="form-control">
                                                    <option value="yes" <?php if($loan->rouding_result == 'yes') echo 'selected'?>>Yes</option>
                                                    <option value="no" <?php if($loan->rouding_result == 'no') echo 'selected'?>>No</option>
                                                </select>
                                                <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Grid to right  -->
                                <div class="col-sm-12">
                                    <span>{{ trans('loan.l_terms') }}</span>
                                    <hr/>
                                </div>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('customer.cus_customer_name') }}</label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="client-name" value="{{ $loan->client->client_name }}" readonly/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('sale_person.sale_team') }}</label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <select id="co_name" style="width: 100%" name="co">
                                                        <option value="0">-</option>
                                                        @foreach($co_name as $c)
                                                            <option value={{$c->id}} {{ $loan->co==$c->id?'selected':'' }}>{{$c->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div><br>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('sale_person.sale_person') }}</label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="hidden" id="sale_team" value="{{ $loan->sale_person }}">
                                                    <select id="sale_person_id" style="width: 100%" name="sale_person">
                                                        <option value="0">-</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('loan.l_doc_location')}}</label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="text" name="doc_location" id="doc_location" class="form-control" value="{{$loan->doc_location}}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('loan.l_loan_purpose')}}</label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <textarea name="loan_purpose" id="loan_purpose" class="form-control" rows="5">{{ $loan->loan_purpose }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row">
                                            <div class="col-sm-5">
                                                <label class="control-label">Calculate loan after discount</label>
                                            </div>
                                            <div class="col-sm-7 icheck">
                                                <div class="single-row">
                                                    <div class="radio ">
                                                        <input type="checkbox" onclick='handleCalculateLoanAfterDiscountClick(this);' id="calculate_loan_after_discount" name="calculate_loan_after_discount" value="0" {{ ($loan->calculate_loan_after_discount == 1) ? 'checked' : '' }} />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('loan.l_sell_price') }} ($)</label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="sell_price" readonly value="{{ $loan->final_price }}"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('loan.l_down_payment') }} <span class="red">*</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="text" name="down_payment" id="down_payment" class="form-control" value="{{$loan->down_payment}}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('loan.loan_amount') }} ($)<span class="red"> *</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="loan_amount" id="loan_amount" readonly value="{{ $loan->loan_amount }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('report.rpt_tenure') }}(M) <span class="red">*</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="text" name="loan_duration" id="loan_duration" class="form-control" value="{{$loan->loan_duration}}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('loan.l_interest_rate') }}(P.M) <span class="red">*</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input type="text" name="interest_rate" id="interest_rate" class="form-control" value="{{$loan->interest_rate}}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('loan.l_loan_penalty_type') }} <span class="red"> *</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="loan_penalty_type" id="loan_penalty_type">
                                                        @foreach($static['loan_penalty_type'] as $key => $value)
                                                            <option value="{{ $key }}" {{ $loan->loan_penalty_type==$key?'selected':''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('loan.l_penalty_rate_type') }} <span class="red"> *</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="penalty_rate_type" id="penalty_rate_type">
                                                        <option value="0">-</option>
                                                        @foreach($static['penalty_rate_type'] as $key => $value)
                                                            <option value="{{ $key }}" {{ $loan->penalty_rate_type==$key?'selected':''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="control-label">{{ trans('loan.l_penalty_period',['num'=>1]) }} (D)</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="penalty_period1" id="penalty_period1" class="form-control" value="{{ $loan->penalty_period1?$loan->penalty_period1:0 }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="control-label">{{ trans('loan.l_penalty_rate',['num'=>1]) }} <span class="text_penalty_type"> (%)</span><span class="red"> *</span></label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="penalty_rate1" id="penalty_rate1" class="form-control" value="{{ $loan->penalty_rate1?$loan->penalty_rate1:0.70 }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row hidden" id="penal_rate2">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="control-label">Penalty Period2(D)</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="penalty_period2" id="penalty_period2" class="form-control" value="{{ $loan->penalty_period2?$loan->penalty_period2:''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="control-label">Penalty Rate2 <span class="text_penalty_type">(%)</span></label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="penalty_rate2" id="penalty_rate2" class="form-control" value="{{ $loan->penalty_rate2?$loan->penalty_rate2:'' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="control-label">{{ trans('loan.l_pay_off_period',['num'=>1]) }} (M)<span class="red">*</span></label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="payoff_period1" id="payoff_period1" class="form-control" value="{{ $loan->payoff_period1 }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="control-label">{{ trans('loan.l_pay_off_rate',['num'=>1]) }} <span class="text_penalty_type"> (%)</span><span class="red"> *</span> </label> 
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="pay_off_rate1" id="pay_off_rate1" class="form-control" value="{{ $loan->pay_off_rate1 }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="control-label">{{ trans('loan.l_pay_off_period',['num'=>2]) }} (M)<span class="red"> *</span></label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="payoff_period2" id="payoff_period2" class="form-control" value="{{ $loan->payoff_period2 }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <label class="control-label">{{ trans('loan.l_pay_off_rate',['num'=>2]) }} <span class="text_penalty_type"> (%)</span><span class="red"> *</span></label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="pay_off_rate2" id="pay_off_rate2" class="form-control" value="{{ $loan->pay_off_rate2 }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Grid to left -->
                                        <!-- Grid to right  -->
                                        <div class="col-sm-4">
                                            <div class="row hidden">
                                                {!! html_form("DSR(%)", false, 'dsr', 'dsr', $loan->dsr) !!}
                                            </div>
                                            <div class="row hidden">
                                                {!! html_form("MoF(%)", false, 'mof', 'mof', $loan->mof) !!}
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">  
                                                    <label class="control-label">{{ trans('loan.l_days_in_a_month') }}<span class="red"> *</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="days_of_month" id="days_of_month">
                                                        <option value="0">-</option>
                                                        @foreach($static['days_of_month'] as $key => $value)
                                                            <option value="{{ $value }}" {{ $loan->days_of_month==$value?'selected':''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('loan.frequency') }}<span class="red"> *</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="frequency" id="frequency">
                                                        <option value="0">-</option>
                                                        @foreach($static['payment_frequency'] as $key => $value)
                                                            <option value="{{ $key }}" {{ $loan->frequency==$key?'selected':''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div id="annual_yield" name="annual_yield" value="{{$loan->annual_yield}}" class="hidden">
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('loan.l_exclude_holidays') }} <span class="red"> *</span></label>
                                                </div>
                                                <div class="col-sm-7 icheck ">
                                                    <div class="flat-green single-row holiday">
                                                        <div class="radio ">
                                                            <input type="checkbox" id="holiday_flag" name="holiday_flag" value="1" {{ ($loan->holiday_flag == 1) ? 'checked' : '' }}/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('multiple.m_expect_disburse_date') }}</label>
                                                </div>
                                                <div data-date-viewmode="years" data-initialize="datepicker" class="input-append date dpYears col-sm-7">
                                                    <input type="text" name="disburse_date" value="{{ $loan->disburse_date }}" size="16" class="form-control" id="disburse_date">
                                                        <span class="add-on">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('multiple.m_start_date') }}<span class="red"> *</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <div data-date-viewmode="years" data-initialize="datepicker"
                                                         data-date="{{date('Y-m-d')}}" class="input-append date dpYears">
                                                        <input type="text" name="start_date"
                                                               value="{{ $loan->start_date?$loan->start_date:date('Y-m-d')}}"
                                                               size="16" class="form-control" id="start_date">
                                                        <span class="add-on">
                                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <label class="control-label">{{ trans('loan.l_repayment_type') }}<span class="red"> *</span></label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="repayment_type" id="repayment_type">
                                                        <option value="0">-</option>
                                                        @foreach($static['repayment_type'] as $key => $value)
                                                            <option value="{{ $key }}" {{ $loan->repayment_type==$key?'selected':'' }}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="balloon_input" class="hidden">
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <label class="control-label">{{ trans('loan.l_number_balloon') }} <span class="red">*</span></label>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="text" name="balloon" id="balloon" class="form-control" value="{{$loan->balloon}}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <label class="control-label">{{ trans('loan.l_custom_flag') }}</label>
                                                    </div>
                                                    <div class="col-sm-7 icheck ">
                                                        <div class="flat-green single-row customize">
                                                            <div class="radio ">
                                                                <input type="checkbox" id="custom_flag" name="custom_flag" {{ $loan->custom_flag==1?'checked':'' }}/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <label class="control-label">{{ trans('loan.l_balloon_month') }} <span class="red"> *</span></label>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="text" name="balloon_month" id="balloon_month" class="form-control" value="{{$loan->balloon_month}}">
                                                    </div>
                                                    <i class="control-label col-md-9 red">( {{ trans('loan.rpt_ballon_msg') }}
                                                        <b> ","</b>. Ex: 12,24,36 )</i>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <label class="control-label col-sm-3">{{ trans('loan.l_monthly_pay') }} ($)</label>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input type="text" name="monthly_payment" id="monthly_payment" class="form-control" readonly value="{{ $loan->monthly_payment?$loan->monthly_payment:0 }}">
                                                    </div>
                                                </div>
                                                <div id="custom">
                                                </div>
                                                <div id="balloon_amount_array" name="balloon_amount_array">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  Repayment Info Button -->
                            <div class="row"></div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-12" style="text-align: right;">
                                        <span>
                                            <a class="btn btn-info" href="#mSchedule" data-toggle="modal" id="view_pay_schedule">
                                                <i class="fa fa-eye"></i>
                                                {{ trans('loan.l_repayment_schedule') }}
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <label id="saving" class="" style="display: none;color: #8175C7;padding-left: 10px;">Saving...</label>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12" style="text-align: right;">
                                        <button type="submit" class="btn btn-info"><i class="fa fa-save"></i>&nbsp;{{ trans('multiple.m_update') }}</button>
                                        <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>&nbsp;{{ trans('multiple.m_reset') }}</button>
                                        <button type="button" class="btn btn-danger" onclick="javascript:history.back();"><i class="fa fa-times-circle"></i>&nbsp;{{ trans('multiple.m_cancel') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div id="loan-schedule" class="tab-pane"><br/>
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-condensed table-hover table-input">
                            <tr class="t-end" bgcolor="gray">
                                <td>
                                    <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="input-append date dpYears col-md-8">
                                        <input type="text" name="s_schedule_date[]" value="" size="16" class="form-control">
                                        <span class="add-on birhtdateDatepicker">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <select name="s_ppi[]" class="form-control">
                                        <option value='p'>P</option>
                                        <option value='pi'>PI</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="s_principal[]" class="form-control" value="0"/>
                                </td>

                                <td>
                                    <input type="text" name="s_term[]" class="form-control" placeholder="N"/>
                                </td>
                                <td>
                                    <div style="padding:10px">
                                        <a href="#" class="btn btn-xs btn-success t-add">+</a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="text-center"><a href="#" class="btn btn-warning t-generate hide">* Generate</a><br/><br/>
                        </div>
                        <div id="t-result"></div>
                        <div id="t-result-hide" class="hide"></div>
                        <div id="t-disburse-hide" class="hide">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div>
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="mSchedule"
                 class="modal fade">
                <div class="modal-dialog md-modify">
                    <div class="modal-content">
                        <div id="printArea">
                            @include('api.report_header',['co_phone'=>!empty($co_id->co_user) ? $co_id->co_user->phone: ''])
                            <div class="modal-header bo-border">
                                <ul id="language" class="pull-right">
                                    <li>
                                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                                    </li>
                                    <li>
                                        <button aria-hidden="true" data-dismiss="modal" class="btn btn-danger" id="close">×
                                        </button>
                                    </li>
                                </ul>
                                <h4 class="modal-title schedule_title">{{ trans('loan.l_repayment_schedule') }}</h4>
                            </div>
                            <div class="modal-body" id="schedule-table"></div>
                            <div class="signature">
                                <div class="left_content">
                                    <p style="text-align: center;">ហត្ថលេខាគណនេយ្យករ</p>
                                    <br><br>
                                    <p>.............................................................</p>
                                    <p>ឈ្មោះ/Name:</p>
                                    <br>
                                    <p>ចុះថ្ងៃទី............/............./.....................</p>
                                </div>
                                <div class="right_content">
                                    <p style="text-align: center">ស្នាមមេដៃស្តំាកូនបំណុល</p>
                                    <br><br>
                                    <p>.............................................................</p>
                                    <p>ឈ្មោះ/Name:</p>
                                    <br>
                                    <p>ចុះថ្ងៃទី............/............./.....................</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/accounting.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.editloan.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/iCheck/jquery.icheck.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/add-loan.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var rate_type = <?php echo $loan->penalty_rate_type;?>;
            var repayment_type = <?php echo $loan->repayment_type;?>;
            var chck = $("#custom_flag").prop('checked');
            var penalty_type = $('#loan_penalty_type').val();
            $('.text_penalty_type').text('(' + penalty_type + ')');
            if (chck) {
                $("#monthly_payment").prop('readonly', false);
            }
            var unit_id = $('#unit_id').val();
            if(unit_id > 0){
                getUnitInfo(unit_id);
            }
            var data = <?php echo json_encode($loan->product);?>;
            new AddLoan(data);
            if (rate_type == 3) {
                $("#penal_rate2").removeClass('hidden');
            }
            if (repayment_type == 3 || repayment_type == 4 || repayment_type == 5) {
                $("#balloon_input").removeClass('hidden');
                $("#balloon").keyup();
            }
            $('#unit_id').select2();
            $("#project_id").select2();
            $("#unit_type_id").select2();
            $('#payment_option').addClass('required');
            $("#payment_option").select2();
            $('#sale_person_id').select2();
            $('#loan_penalty_type').on('change',function(e){
                var penalty_type = $(this).val();
                $('.text_penalty_type').text('(' + penalty_type + ')');
            });
            $(document).on('click', '.t-generate', function () {
                f_this = $(this);
                f_this.attr('disabled', 'disabled');

                $('#t-result').html('');
                $('#t-result-hide').html('');
                var k = 0;

                disburse_on = [$('#start_date').val()];
                $('#t-disburse-hide span').each(function () {
                    disburse_on.push($(this).text());
                });
                $('#t-disburse-hide').text('');

                $('.t-row input[name="s_schedule_date[]"]').each(function (indexx) {
                    var this_ = $(this);
                    setTimeout(function () {
                        l_tenure = this_.parents('tr').find('input[name="s_term[]"]').val();
                        c_principal = this_.parents('tr').find('input[name="s_principal[]"]').val();
                        l_start_date = this_.parents('tr').find('input[name="s_schedule_date[]"]').val();
                        ppi = this_.parents('tr').find('select[name="s_ppi[]"]').val();
                        charge = this_.parents('tr').find('input[name="s_charge[]"]').val();

                        if ($('#t-result table').html() != '' && $('#t-result table').html() != null) {
                            l_amount = $('#t-result table tr.last_row:last td.col_5 .default-val').text();
                            l_amount = l_amount.replace(',', '');
                        } else {
                            l_amount = $("#loan_amount").val();
                        }

                        var repay_type = $("#repayment_type").val();
                        var chck = $("#custom_flag").prop('checked');
                        var holiday_chck = $("#holiday_flag").prop('checked');
                        var round = $('#round').val();

                        var balloon_input = $(".balloon_input").map(function () {
                            return $(this).val();
                        }).get().join();
                        $("#balloon_amount_array").val(balloon_input);

                        var data = {
                            l_client_name: $("#client-name").val(),
                            l_days_of_month: $("#days_of_month").val(),
                            l_start_date: l_start_date,
                            l_amount: l_amount,
                            l_tenure: l_tenure,
                            l_rate: $("#interest_rate").val(),
                            l_repayment_type: repay_type,
                            l_balloon_num: $("#balloon").val(),
                            l_balloon_month: $("#balloon_month").val(),
                            l_monthly_pay: $("#monthly_payment").val(),
                            l_bal_amount_array: balloon_input,
                            custom_flag: chck ? 1 : 0,
                            holiday_flag: holiday_chck ? 1 : 0,
                            round,
                            c_principal: c_principal,
                            disburse_on: disburse_on[k],
                            ppi: ppi,
                            charge: charge,
                            frequency: $('#frequency').val()
                        };
                        k++;
                        $.ajax({
                            url: '/api/loan/repaymentinfo',
                            type: 'GET',
                            data: data,
                            success: function (data) {
                                if ($('#t-result table').length == 0) {
                                    $('#t-result').html(data);
                                    $('.tbrepayment').remove();
                                } else {
                                    var data_last = [0];
                                    $('#t-result table:last tr:last td').each(function () {
                                        val_ = $(this).text();
                                        val_ = val_.replace(',', '');
                                        data_last.push(val_);
                                    });

                                    $('#t-result table:last tr:last').remove();
                                    $('.last_row td.col_5').attr('bgcolor', '');
                                    $("#t-result-hide").html(data);
                                    $('.tbrepayment').remove();

                                    //prepare data table
                                    $("#t-result-hide table:last tbody tr:first").remove(); //remove 0
                                    tr_length = $('#t-result table tr').length - 2;
                                    i = 0;
                                    $("#t-result-hide table:last tbody tr").each(function () {
                                        i++;
                                        j = tr_length + i;
                                        //$(this).removeClass('row_'+i);
                                        //$(this).addClass('row_'+j);
                                        if ($(this).find('td').attr('colspan') != 2) $(this).find('td:first').text(j);
                                    });
                                    tbody = $("#t-result-hide table:last tbody").html();
                                    $('#t-result table tbody').append(tbody);

                                    index = 0;
                                    $('#t-result table:last tr:last td').each(function () {
                                        index++;
                                        val_ = $(this).text();
                                        val_ = val_.replace(',', '');
                                        if (index >= 2 && index < 6) val_ = parseFloat(val_) + parseFloat(data_last[index]);
                                        if (index == 2) {
                                            $(this).text(val_);
                                        } else if (index > 2 && val_ != '') {
                                            $(this).text(accounting.formatMoney(val_, ''));
                                        }
                                    });
                                }
                                $('#t-disburse-hide').append('<span id="disburse_' + k + '">' + $('.last_row:last td.col_0 input[name="repayment_date[]"]').val() + '</span>');
                                //remove first repayment_date, repayment_principal
                                $('.row_0 input[name="repayment_date[]"]').remove();
                                $('.row_0 input[name="repayment_principal[]"]').remove();
                                f_this.removeAttr('disabled');
                            }
                        });
                    }, (indexx + 1) * 2000);
                });
            });
            $(document).on('click', '.t-add', function () {
                this_ = $(this);
                l_tenure = this_.parents('tr').find('input[name="s_term[]"]').val();
                l_start_date = this_.parents('tr').find('input[name="s_schedule_date[]"]').val();
                c_principal = this_.parents('tr').find('input[name="s_principal[]"]').val();
                ppi = this_.parents('tr').find('select[name="s_ppi[]"]').val();
                charge = this_.parents('tr').find('input[name="s_charge[]"]').val();

                if (l_tenure > 0 && l_start_date != '') {
                    pre_content = '<tr class="t-row">' + $('.t-end').html() + '</tr>';
                    $('#loan-schedule table.table-input').append(pre_content);
                    $('.t-row:last input[name="s_schedule_date[]"]').val(l_start_date);
                    $('.t-row:last input[name="s_term[]"]').val(l_tenure);
                    $('.t-row:last input[name="s_principal[]"]').val(c_principal);
                    $('.t-row:last select[name="s_ppi[]"]').val(ppi);
                    $('.t-row:last input[name="s_charge[]"]').val(charge);

                    $('.t-row:last td:last div').html('<a class="btn btn-danger btn-xs t-remove" href="#">-</a>');

                    $('.t-generate').trigger('click');
                }

                //t-end
                t_end = '<tr class="t-end" bgcolor="gray">' + $('.t-end').html() + '</tr>';
                $('.t-end').remove();
                $('#loan-schedule table.table-input').append(t_end);
                $('.dpYears').datepicker({
                    format: 'yyyy-m-d',
                    autoclose: true,
                    setDate: new Date()
                });
            });

            $(document).on('click', '.t-remove', function () {
                $(this).parents('tr').remove();
                $('#t-result').html('');
                $('.t-generate').trigger('click');
            });
            $(document).on('change', '#unit_id', function () {
                var unit_id = $(this).val();
                if(unit_id > 0){
                    getUnitInfo(unit_id);
                }
            });
            $(document).on('change', '#payment_option', function () {
                var payment_option = $(this).val();
                if(payment_option > 0){
                    $('#down_payment_duration').val('');
                    $('#down_payment_value').val('');
                    $('#installment_duration').val('');
                    $('#loan_duration').val(0);
                    $('#annual_interest').val(0);
                    $('#interest_rate').val(0);
                    $('#discount_payment_option').val(0);
                }
                // if(payment_option > 0){
                    getPaymentOption(payment_option);
                    calculatePrice();
                // }
            });
            $(document).on('change', '#start_payment_date', function () {
               addFirstCollectionDate();
            });
            $(document).on('input', '#discount_other', function () {
                calculatePrice();
            });
             $(document).on('input', '#diposit_amount', function () {
                calculatePrice();
            });

            $(document).on('change', '#project_id', function () {
                getUnitType();
            });
            $(document).on('change', '#unit_type_id', function () {
                getUnitByUnittype();
            });
            $(document).on('change', '#co_name', function() {
                var sale_person_id = $(this).val();
                getSalePerson(sale_person_id,null);
            });
            getSalePerson($('#co_name').val(),$('#sale_team').val());
            $(document).on('input','#down_payment_duration,#down_payment_value,#installment_duration,#annual_interest,#discount_payment_option,#down_payment_type,#amount_discount_payment_option,#diposit_amount,#clearance_amount',function(){
                var interest_per_year = $('#annual_interest').val();
                interest_per_year = parseFloat(interest_per_year) / 12;
                $('#interest_rate').val(parseFloat(interest_per_year).toFixed(4));
                installment_duration = $('#installment_duration').val();
                installment_duration = parseFloat(installment_duration).toFixed(2)
                $('#loan_duration').val(installment_duration);
                calculatePrice();
            });
        });
        $('#mSchedule').on('show.bs.modal', function (event) {
                var payment_option=$("#payment_option_type").val();
                var calculate_loan_after_discount=$("#calculate_loan_after_discount").prop('checked');
                var calculate_loan_after_discount_label=calculate_loan_after_discount?"Yes":"No";
                var diposit_amount=$("#diposit_amount").val();
                if(payment_option==8){
                    setTimeout(function () {
                        $('.tbrepayment tr:last').after('<tr><td colspan="2">Calculate loan after discount</td><td>'+calculate_loan_after_discount_label+'</td><td colspan="3">Deposit Amount</td><td style="text-align: center;">$'+parseFloat(diposit_amount).toFixed(2)+'</td></tr>');
                    }, 250);
                }
            })
        function getSalePerson(sale_person_id,sale_team){
            $.ajax({
                url: '/getSalePerson',
                type: 'GET',
                dataType: "json",
                data:{sale_person_id:sale_person_id,sale_team:sale_team},
                success: function (data) {
                    $('#sale_person_id').html(data.option);
                    $("#sale_person_id").select2();
                }
            });
        }

        function addFirstCollectionDate(){
            var start_payment_date = $('#start_payment_date').val();
            var down_payment_duration = $('#down_payment_duration').val();
            $.ajax({
                url: '/addFirstCollectionDate',
                type: 'GET',
                dataType: "json",
                data:{start_payment_date:start_payment_date,down_payment_duration:down_payment_duration},
                success: function (data) {
                    $('#start_date').datepicker({
                            format: 'yyyy-mm-dd',
                            autoclose: true,
                            changeMonth:true,
                            changeYear:true,
                    }).datepicker("update", data.start_payment_date);
                    $('#disburse_date').datepicker({
                            format: 'yyyy-mm-dd',
                            autoclose: true,
                            changeMonth:true,
                            changeYear:true,
                    }).datepicker("update", data.disburse_date);
                }
            }); 
        }
        function getUnitInfo(unit_id){
            // $('#down_payment_duration').val('');
            // $('#down_payment_value').val('');
            // $('#installment_duration').val('');
            // $('#annual_interest').val(0);
            // $('#discount_payment_option').val(0);
            $.ajax({
                url: '/getUnitInfo',
                type: 'GET',
                dataType: "json",
                // processData: false,
                // contentType: false,
                data:{unit_id:unit_id},
                success: function (data) {
                    $('#payment_option').html(data.option);
                    // $("#payment_option").select2();
                    $('#payment_option').val({{ $loan->payment_option }});
                    $('#payment_option').select2().trigger('change');
                    $('#unit_sale_price').val(data.unitInfo.price);
                    $('#discount_promotion').val(data.promotion);
                    calculatePrice();
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
        function getUnitByUnittype(){
            var unit_type_id = $('#unit_type_id').val();
            $.ajax({
                url: '/getUnitByUnittype',
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
                    var down_payment_duration = $('#down_payment_duration');
                    var down_payment_value = $('#down_payment_value');
                    var installment_duration = $('#installment_duration');
                    var loan_duration = $('#loan_duration');
                    var annual_interest = $('#annual_interest');
                    var interest_rate = $('#interest_rate');
                    var discount_payment_option = $('#discount_payment_option');
                    if(data == null){
                        down_payment_duration.attr('readonly',false);
                        down_payment_value.attr('readonly',false);
                        installment_duration.attr('readonly',false);
                        loan_duration.attr('readonly',false);
                        annual_interest.attr('readonly',false);
                        interest_rate.attr('readonly',false);
                        discount_payment_option.attr('readonly',false);
                    }else{
                        down_payment_duration.attr('readonly',true);
                        down_payment_value.attr('readonly',true);
                        installment_duration.attr('readonly',true);
                        loan_duration.attr('readonly',true);
                        annual_interest.attr('readonly',true);
                        interest_rate.attr('readonly',true);
                        discount_payment_option.attr('readonly',true);
                        if(data.first_payment_plan == 1){
                            $('#hase_down_payment').val('yes');
                        }else{
                            $('#hase_down_payment').val('no');
                        }
                        $('#down_payment_duration').val(data.first_payment_duration_month);
                        $('#down_payment_value').val(data.first_payment_per);
                        $('#installment_duration').val(data.loan_duration_month);
                        $('#loan_duration').val(data.loan_duration_month);
                        $('#annual_interest').val(data.interest_per_year);
                        interest_per_year = data.interest_per_year / 12;
                        $('#interest_rate').val(parseFloat(interest_per_year).toFixed(4));
                        $('#discount_payment_option').val(data.special_discount);

                        if(data.payment_option_type){
                            $('#payment_option_type').val(data.payment_option_type);
                        }
                    }
                    calculatePrice();
                    addFirstCollectionDate();
                    // $('#payment_option').html(data.option);
                    // $('#unit_sale_price').val(data.unitInfo.price);
                }
            });
        }

        function calculatePrice(){
            var down_payment_type = $('#down_payment_type').val();
            var unit_sale_price = $('#unit_sale_price').val();
            var discount_promotion = $('#discount_promotion').val();
            var discount_other = $('#discount_other').val();
            var down_payment_value = $('#down_payment_value').val();
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
            if(down_payment_type == '%'){
                total_down_payment = parseFloat(final_price) * (parseFloat(down_payment_value) / 100);
            }else{
                total_down_payment = parseFloat(down_payment_value);
            }

            // if(parseFloat(diposit_amount) > parseFloat(total_down_payment)){
            //     $('#diposit_amount').val(total_down_payment);
            // }
            total_down_payment = parseFloat(total_down_payment).toFixed(0);
            total_down_payment_final = total_down_payment -  parseFloat(diposit_amount);
            $('#final_price').val(parseFloat(final_price).toFixed(2));
            $('#sell_price').val(parseFloat(final_price).toFixed(2));
            // $('#down_payment').val(parseFloat(total_down_payment_final).toFixed(2));
            $('#down_payment').val(parseFloat(total_down_payment).toFixed(2));
            $("#loan_amount").val((final_price - total_down_payment).toFixed(2));

            var payment_option=$("#payment_option_type").val();

            var calculate_loan_after_discount=$("#calculate_loan_after_discount").prop('checked');
            if(payment_option==8){ //New page from tickit:Id:1641
                var calculate_loan_after_discount=$("#calculate_loan_after_discount").prop('checked');
                var final_price_after_diposit=parseFloat(final_price);
                if(calculate_loan_after_discount){
                    final_price_after_diposit=parseFloat(final_price)-parseFloat(diposit_amount);
                }
                if(down_payment_type == '%'){
                    total_down_payment = parseFloat(final_price_after_diposit) * (parseFloat(down_payment_value) / 100);
                }else{
                    total_down_payment = parseFloat(down_payment_value);
                }
                
                $('#down_payment').val(parseFloat(total_down_payment).toFixed(2));

                var loan_amount=parseFloat(final_price)-parseFloat(total_down_payment)-parseFloat(diposit_amount);
                $("#loan_amount").val(parseFloat(loan_amount).toFixed(2));
            }else{
                $("#loan_amount").val((final_price - total_down_payment).toFixed(2));
            }

        }
        function handleCalculateLoanAfterDiscountClick(e){            
            calculatePrice();
            var check=$("#calculate_loan_after_discount").prop('checked');
            if(check){
                $("#calculate_loan_after_discount").val(1);
            }else{
                $("#calculate_loan_after_discount").val(0);
            }
        }
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
    </script>
@endsection