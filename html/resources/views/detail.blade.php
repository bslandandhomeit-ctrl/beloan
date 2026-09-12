@extends('layouts.app')

@section('css')
    <link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link href="{{ asset('css/popup.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
    <link href="{{ asset('theme/js/iCheck/skins/flat/green.css',isset($secure) ? false : false) }}" rel="stylesheet">
    <style type="text/css">
            @font-face {
                src: url("{{ asset('publict/fonts/KhmerOSmuollight.ttf') }}") format("truetype");
                src: url("{{ asset('publict/fonts/KhmerOScontent.ttf') }}") format("truetype");
            }
            #printArea{
                font-family: 'Times New Roman','Khmer OS Battambang';
            }
        .tab-content {
            position: relative;
        }
        .tab-content .tab-pane {
            min-height: 200px;
        }
        .tab-content .loading {
            position: absolute;
            left: 50%;
            top: 20px;
            display: block;
            width: 40px;
            height: 40px;
            background: transparent url("{{ asset('images/loading.gif', isset($secure) ? false : false) }}") no-repeat scroll center center / contain;
        }
        .thumbnail {
            margin-bottom: 5px;
        }

        .nav>li>a {
            position: relative;
            display: block;
            padding: 10px 10px;
        }

table.fixed tbody tr td {
    white-space: normal !important;
}

        @media print {
            a[href]:after {
                content: none !important;
            }
            .hide-this {
                display: none !important;
            }
            .invisible_edit, .td-schedule-hide {
                display: none !important;
            }
            table td, table th {
                font-size: 1.4em;
            }
        }
    </style>
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            <?php $static = config('static_data');?>
            {{ trans('loan.l_loan_information') }}
            <span style="font-weight: bold;color:blue;"> {{(!empty($loan->client_loan_account->account_no))? $loan->client_loan_account->account_no : ""}}</span>
            @if(!empty($static) && !empty($static['loan_status']) && array_key_exists($loan->status,$static['loan_status']))  
            @if ($loan->status == 8 )
                    <span class="red-color" style="font-weight: bold;"> ( {{ $static['reschedule_status'][$loan->reschedule_status] }} )</span>
            @elseif ($loan->status == 12 )
                    <span class="red-color" style="font-weight: bold;"> (  Loan buy back )</span>
                @else
                    <span class="red-color" style="font-weight: bold;"> ( {{ $static['loan_status'][$loan->status] }} )</span>
                @endif
            @endif
        </header>
        <div class="panel-body">
            @if(Session::has('msg'))
                <div class="alert alert-success fade in">
                    <button class="close close-sm" type="button" data-dismiss="alert">x</button>
                    {{ Session::get('msg') }}
                </div>
            @endif
            @if(!empty($loan))
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" id="loan-id" value="{{ $loan->id }}"/>
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}"/>
                        <!--navigation start-->
                        <nav class="navbar navbar-default" role="navigation">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                                    <span class="sr-only"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse navbar-ex1-collapse">
                                <ul class="nav navbar-nav navbar-right">
                                    
                                    @if($loan->status == 2)
                                        <li>
                                            <a href="{{ route('loan_disburse',[$loan->id]) }}" class="approval">
                                                <i class="fa fa-adn"></i>
                                                {{ trans('loan.l_disburse')  }}
                                            </a>
                                        </li>
                                    @endif
                                    @if($loan->status == 3 || $loan->status == 8)
                                        <li>
                                            <a href="{{ route('add_loan_repayment',[$loan->id]) }}">
                                                <i class="fa fa-usd"></i>
                                                {{ trans('loan.l_repayment')  }}
                                            </a>
                                        </li>
                                    @endif
                                    @if($loan->status < 9)
                                        @if($loan->status != 5 || $loan->status != 6 )
                                            <li>
                                                <a href="{{ route('add_loan_guarantor', [$loan->id]) }}">
                                                    <i class="fa fa-plus"></i>
                                                    {{ trans('loan.add_loan_guarantor') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('add_co_borrower',[$loan->id]) }}">
                                                    <i class="fa fa-plus"></i>
                                                    Add Co-Borrower
                                                </a>
                                            </li>
                                            <li><a href="{{ route('add_collateral', [$loan->id]) }}">
                                                    <i class="fa fa-plus"></i>
                                                    {{ trans('loan.l_add_collateral') }}
                                                </a>
                                            </li>
                                        @endif
                                        <li>
                                            <a href="{{ route('loan_add_charge',[$loan->id]) }}">
                                                <i class="fa fa-plus"></i>
                                                {{ trans('loan.l_add_loan_charge') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('loan_add_cost',[$loan->id]) }}">
                                                <i class="fa fa-plus"></i>
                                                {{ trans('loan.l_loan_cost') }}
                                            </a>
                                        </li>
                                        <!-- <li>
                                            <a href="{{ route('loan_add_document',[$loan->id]) }}">
                                                <i class="fa fa-plus"></i>
                                                {{ trans('loan.l_add_loan_document') }}
                                            </a>
                                        </li> -->
                                    @endif
                                    <li>
                                        @if($loan->status == 1)
                                            <a href="{{ route('loan_edit',[$loan->id])}}"><i class="fa fa-pencil"></i>&nbsp; {{ trans('loan.l_edit_loan') }}
                                            </a>
                                        @endif
                                    </li>
                                    <li>
                                        @if($loan->status == 5)
                                            <a href="{{ route('loan_writeoff_pay',[$loan->id]) }}"><i class="fa fa-plus"></i>&nbsp;{{ trans('multi.write-off_pay') }}
                                            </a>
                                        @endif
                                    </li>
                                    <li>
                                            <a href="{{ route('loan_add_document',[$loan->id]) }}">
                                                <i class="fa fa-plus"></i>
                                                {{ trans('loan.l_add_loan_document') }}
                                            </a>
                                        </li>
                                    <li><a href="{{ route('loan_buy_back',[$loan->id]) }}"><i class="fa fa-plus"></i>&nbsp; Loan Buy Back</a></li>
                                    <li><a href="{{ route('loan_add_history',[$loan->id]) }}"><i class="fa fa-plus"></i>&nbsp; Add History</a></li>                                    
                                    <?php
                                        $unit_type = $loan->unittypes->contract_template_id;
                                        $html = "";
                                        switch($unit_type) {
                                            case 1:
                                                $html =  '<li>
                                                        <a href='.route('house',[$loan->id]).'><i class="fa fa-print"></i>&nbsp;'.trans('multiple.m_print').'
                                                        </a>
                                                    </li>';
                                                break;
                                            case 2:
                                                $html =  '<li>
                                                        <a href='.route('land',[$loan->id]).'><i class="fa fa-print"></i>&nbsp;'.trans('multiple.m_print').'
                                                        </a>
                                                    </li>';
                                                break;
                                            case 3:
                                                $html = '<li>
                                                        <a href='.route('condo',[$loan->id]).'><i class="fa fa-print"></i>&nbsp;'.trans('multiple.m_print').'
                                                        </a>
                                                    </li>';
                                                break;
                                            case 4:
                                                $html = '<li>
                                                        <a href='.route('shop',[$loan->id]).'><i class="fa fa-print"></i>&nbsp;'.trans('multiple.m_print').'
                                                        </a>
                                                    </li>';
                                                break;
                                            default:
                                        }
                                        echo $html;
                                    ?>
                                    @if(in_array($loan->status, array(3, 8)))
                                        <li class="dropdown">
                                            <a href="javascript:;" class="dropdown-toggle"
                                               data-toggle="dropdown">{{ trans('loan.l_more') }} <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="{{ route('loan_payoff',[$loan->id]) }}">{{ trans('sidebar.sb_payoff_loans') }}</a>
                                                </li>
                                                @if($loan->status == 5)
                                                @endif
                                                @if($loan->status == 3 || $loan->status == 8)
                                                    <li>
                                                        {{-- <a href="{{ route('dealer_loan',[$loan->id]) }}">{{ trans('loan.l_update_dealer_info') }}</a> --}}
                                                        <a href="{{ route('transfer_client',[$loan->id]) }}">{{ trans('loan.l_transfer_client') }}</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('loan_close',[$loan->id]) }}">{{ trans('loan.l_close_loan') }}</a>
                                                    </li>
                                                     <li>
                                                        <a href="{{ route('loan_restructure',[$loan->id]) }}">{{ trans('sidebar.sb_reschedule_loans') }}</a>
                                                    </li>
                                                @endif
                                                <li><a href="#update-parc" data-toggle="modal">{{ trans('loan.l_update_step') }}</a></li>                                              
            
                                            </ul>
                                        </li>
                                    @endif
                                </ul>
                            </div><!-- /.navbar-collapse -->
                        </nav>
                        <br/><br/>
                    </div>
                </div>
                <!--- start list loan information -->
                <div id="myprint_area">
                    @include('api.report_header')
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-bordered table-striped table-condensed">
                                <tr>
                                    <th style="width: 30%;">{{ trans('report.rpt_office') }}</th>
                                    <td>{{ !empty($loan->branch->branch_name) ?$loan->branch->branch_name : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('report.rpt_contract_id') }}</th>
                                    <td>{{ $loan->contract_id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.credit_officer') }}</th>
                                    <td style="font-weight:bold">{{ !empty($loan->co_user->name) ? $loan->co_user->name : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('sale_person.sale_person') }}</th>
                                    <td style="font-weight:bold">{{ !empty($loan->sale_persons->name) ? $loan->sale_persons->name : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_loan_purpose') }}</th>
                                    <td style="font-weight:bold">{{ !empty($loan->loan_purpose) ? $loan->loan_purpose : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('representative.sale_representative') }}</th>
                                    <td style="font-weight:bold">{{ !empty($loan->projects->Representative->name_en) ? $loan->projects->Representative->name_en : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('project.project_code') }}</th>
                                    <td style="font-weight:bold">{{ !empty($loan->projects->short_code) ? $loan->projects->short_code : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('unit.unit_code') }}</th>
                                    <td>
                                        {{ !empty($loan->units->code) ? $loan->units->code: '-' }} ({{ !empty($loan->units->UnitType->name) ?$loan->units->UnitType->name: '-' }})
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_submitted_on') }}</th>
                                    <td>
                                        {{ date('d-M-Y',strtotime($loan->submitted_on)) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_approved_on') }}</th>
                                    <td>{{ !empty($loan->approval_date) ? date('d-M-Y',strtotime($loan->approval_date)): '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_disbursed_on') }}</th>
                                    @if ($loan->status > 2)
                                        <td>{{ !empty($loan->disburse_date) ? date('d-M-Y',strtotime($loan->disburse_date)): '-' }}</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.disburse_note') }}</th>
                                    @if ($loan->status > 2)
                                        <td>{{ !empty($loan->disburse_note) ? $loan->disburse_note: '-' }}</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_settled_on') }}</th>
                                    <td>{{ (!empty($loan->settlement_date) && ($loan->settlement_date != "0000-00-00")) ? date("d-M-Y", strtotime($loan->settlement_date)): '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('report.rpt_contract_date') }}</th>
                                    <td>
                                        @if ($loan->status > 2)
                                            {{ !empty($loan->contract_date)  ? date('d-M-Y',strtotime($loan->contract_date)) : '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.contract_deadline') }}</th>
                                    <td>
                                        {{ !empty($loan->contract_deadline)  ? date('d-M-Y',strtotime($loan->contract_deadline)) : "-" }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Customer Name</th>
                                    <td>
                                        {{ !empty($loan->client)  ? $loan->client->client_name : "-" }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Phone Number</th>
                                    <td>
                                        <?php
                                        if(!empty($loan->client)){?>
                                       {{$loan->client->phone1}} {{ !empty($loan->client->phone2)  ? ' / '.$loan->client->phone2 : "" }}
                                      <?php  } ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered table-striped table-condensed">
                                <tr>
                                    <th style="width: 42% !important;">{{ trans('loan.unit_price') }}</th>
                                    <td>
                                        {{ number_format($loan->unit_sale_price,2) }}
                                    </td> 
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.discount_promotion') }}</th>
                                    <td>{{ $loan->discount_promotion }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.discount_other') }}</th>
                                    <td>{{ $loan->discount_other }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.remark_discount') }}</th>
                                    <td>{{ !empty($loan->status_remark)?$loan->status_remark:'-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.price_after_discount') }}</th>
                                    <td>
                                        {{ !empty($loan->price_after_discount)?$loan->price_after_discount:0 }}
                                        {{-- {{ number_format($loan->interest_rate,2) }}%
                                        (P.M), {{number_format(($loan->interest_rate*12),2)}}% (P.A) --}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.payment_option') }}</th>
                                    <td>
                                        <?php
                                            if($loan->payment_option == 0){
                                                echo "Other";
                                            }else{
                                                echo !empty($loan->PaymentOptions->name)?$loan->PaymentOptions->name:"-";
                                            }  
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.discount_pay_option_percentage') }}</th>
                                    <td>{{ !empty($loan->discount_payment_option)?$loan->discount_payment_option:0 }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.discount_pay_option_usd') }}</th>
                                    <td>{{ !empty($loan->amount_discount_payment_option)?$loan->amount_discount_payment_option:0 }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_total_discount') }}</th>
                                    <td>{{ number_format(($loan->discount_promotion + $loan->discount_other + $loan->amount_discount_payment_option),2) }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.clearance_amount') }}</th>
                                    <td>{{ !empty($loan->clearance_amount)?number_format($loan->clearance_amount,2):0 }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.clearance_remark') }}</th>
                                    <td>{{ $loan->clearance_remark }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.final_price') }}</th>
                                    <td>{{ !empty($loan->final_price)?number_format($loan->final_price,2):0 }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_repayment_type') }}</th>
                                    <td>
                                        @if(!empty($static) && !empty($static['repayment_type']) &&
                                           array_key_exists($loan->repayment_type,$static['repayment_type']))
                                            {{ $static['repayment_type'][$loan->repayment_type] }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('report.rpt_loan_type') }}</th>
                                    <td>
                                        {{$product_type_arr[$loan->loan_type]}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered table-striped table-condensed">
                            <?php
                                 if($loan_history){?>
                                    <tr>
                                        <th style="width: 42% !important;">Principal Paid</th>
                                        <td>
                                            {{ number_format($loan_history->principal,2) }}
                                        </td> 
                                    </tr>
                                    <tr>
                                        <th style="width: 42% !important;">Total Paid</th>
                                        <td>
                                            {{ number_format($loan_history->total_paid,2) }}
                                        </td> 
                                    </tr>
                               <?php  } ?>
                                <tr>
                                    <th style="width: 36% !important;">Calculate loan after discount</th>
                                    <td>{{ !empty($loan->calculate_loan_after_discount)?"Yes":"No" }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 36% !important;">Deposit Amount</th>
                                    <td>{{ !empty($loan->diposit_amount)?number_format($loan->diposit_amount,2):0 }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 36% !important;">{{ trans('loan.down_payment') }}</th>
                                    <td>{{ !empty($loan->down_payment)?number_format($loan->down_payment,2):0 }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.down_payment_duration') }}</th>
                                    <td>{{ !empty($loan->down_payment_duration)?$loan->down_payment_duration:"-" }}</td>
                                </tr>
                                <tr class="hidden">
                                    <th>{{ trans('loan.l_down_payment') }}</th>
                                    <td>{{ $loan->down_payment }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.loan_amount') }}</th>
                                    <td>{{ !empty($loan->loan_amount)?number_format($loan->loan_amount,2):0 }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_tenure') }}</th>
                                    <td>{{ !empty($loan->loan_duration)?$loan->loan_duration:"-" }} M</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_interest_rate') }}</th>
                                    <td>{{ number_format($loan->interest_rate,2) }}% (P.M), {{number_format(($loan->interest_rate*12),2)}}% (P.A)</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_penalty_rate',['num'=>'']) }}</th>
                                    <td>
                                        <?php
                                            if ($loan->penalty_rate_type == 1) {
                                                echo $loan->penalty_rate1 . $loan->loan_penalty_type.' (for over ' . $loan->penalty_period1 . ' days)';
                                            } elseif ($loan->penalty_rate_type == 2) {
                                                echo $loan->penalty_rate1 . $loan->loan_penalty_type.' (from ' . $loan->penalty_period1 . ' to 30 days)';
                                            } elseif ($loan->penalty_rate_type == 3) {
                                                echo $loan->penalty_rate1 . $loan->loan_penalty_type.' ( from ' . $loan->penalty_period1 . ' to ' . $loan->penalty_period2 . ' days ), ';
                                                echo $loan->penalty_rate2 . $loan->loan_penalty_type.' ( from ' . ($loan->penalty_period2 + 1) . ' to 30 days )';
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_pay_off_rate',['num'=>'']) }}</th>
                                    <td>{{$loan->pay_off_rate1}}%(From 0 to {{ $loan->payoff_period1 }}
                                        months) {{$loan->pay_off_rate2}}%(From {{ $loan->payoff_period1 }}
                                        to {{ $loan->payoff_period2 }}months)
                                        {{ ($loan->pay_off_rate3 > 0) ? $loan->pay_off_rate3 .'%(From '. $loan->payoff_period2.' months)' : ''}}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('loan.l_currency') }}</th>
                                    <td>
                                        {{$loan->client_loan_account->currencies->code}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('report.rpt_outstanding_balance') }}</th>
                                    <td>
                                        <a href="{{ route('loan_account', [$loan->client_loan_account->id])}}">{{number_format($loan->client_loan_account->balance,2,'.',',')}} {{$loan->client_loan_account->currencies->code}}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('report.rpt_downpayment_balance') }}</th>
                                    <td>
                                        {{number_format($loan->client_loan_account->balance_downpayment,2,'.',',')}} {{$loan->client_loan_account->currencies->code}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('account.drawdown_acc') }}</th>
                                    <td>
                                        <?php
                                            $drawdow_acc = $loan->loan_drawdow_acc;
                                        ?>
                                        @if($drawdow_acc->currency == $loan->client_loan_account->currency)
                                            <a href="{{ route('drawdown_account_detail', [$drawdow_acc->id])}}">{{number_format($drawdow_acc->balance,2,'.',',')}} {{$loan->client_loan_account->currencies->code}}</a>
                                            <br>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!--- end list loan information -->
                <!-- start tab -->
                <section>
                <div class="row">    
                    <div style="width:50%;float: right;">                         

                          <p style="margin-left: 20px;">Date</p>
                          <div data-date-viewmode = "years" style = "width: 100%;" data-initialize = "datepicker" data-date-format = "dd/mm/yyyy" data-date = "{{date('Y-m-d')}}" class = "input-append date dpYears col-md-6">
                            <input type = "text" name = "dpDate" value = "{{date('Y-m-d')}}" size = "16" class = "form-control" id = "dpDate">
                            <span class = "add-on birhtdateDatepicker">
                                <button class = "btn btn-primary" type = "button"><i class = "fa fa-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                    </div>
                
                    <ul class="nav nav-tabs">
                        <li class="active" id="client_detail"><a data-toggle="tab" href="#client">{{ trans('customer.cus_customer') }}</a>
                        </li>
                        <li id="schedule_repayment"><a data-toggle="tab" href="#schedule">{{ trans('loan.l_repayment_schedule') }}</a>
                        </li>
                        <li id="actual_repayment"><a data-toggle="tab" href="#actual">{{ trans('loan.l_repayment_actual') }}</a></li>
                        <li id="transaction_detail"><a data-toggle="tab" href="#transaction">{{ trans('loan.l_transaction') }}</a></li>
                        <li id="charge_detail"><a data-toggle="tab" href="#charge">{{ trans('loan.l_charge') }}</a></li>
                        <li id="guarantor_detail"><a data-toggle="tab" href="#guarantor">{{ trans('loan.l_guarantor') }}</a></li>
                        <li id="co_borrower_detail"><a data-toggle="tab" href="#co_borrower">{{ trans('Co-Borrower') }}</a></li>
                        <li id="loandoc_detail"><a data-toggle="tab" href="#loandoc">{{ trans('loan.l_loan_document') }}</a></li>
                        <li id="loanpay_detail"><a data-toggle="tab" href="#loanpay">{{ trans('loan.l_detail_repayment_until_today') }}</a>
                        <li id="customertranfer_detail"><a data-toggle="tab" href="#customertranfer">{{ trans('loan.transfer_clients') }}</a>
                        </li>
                        <li id="do_audit"><a data-toggle="tab" href="#audit">{{ trans('multiple.audit') }}</a></li>
                        <li id="Statement"><a data-toggle="tab" href="#statement_tap">Statement</a></li>
                    </ul>

                    <div class="panel-body">
                        <ul id="language" style="margin-top:1rem;padding-left:0;margin-bottom:2rem;">
                            <li><a class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</a></li>
                            <li><a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a></li>
                            <li><a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a></li>
                        </ul>
                        <div id="printArea" class="printArea">
                            <div class="report_header">
                                <img src="{{ asset('images/header2.PNG') }}" style="width:100%;"/>
                            </div>
                            <div class="tab-content">
                                <span class="loading" id="loading"></span>
                                <div id="client" class="tab-pane active">
                                    @if(!empty($loan->client))
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="fileupload-new thumbnail" style="width: 150px;">
                                                    <img src="{{ !empty($loan->client->general->first()->photo)?asset('/data/clients/'.$loan->client->general->first()->photo, isset($secure) ? false : false):asset('images/noimage.gif', isset($secure) ? false : false) }}" alt="Profile Picture"/>
                                                </div>
                                                @if(!empty($loan->client->location_latitude) && !empty($loan->client->location_longitude))
                                                    <a href="javascript:;" class="client-view view-map"
                                                       data-lat="{{ $loan->client->location_latitude }}"
                                                       data-long="{{ $loan->client->location_longitude }}"
                                                    >{{ trans('multiple.m_view_map') }}</a> &nbsp;
                                                @endif
                                                @if(!empty($loan->client->general->first()->signature))
                                                    <a href="javascript:;" class="client-view"
                                                       data-mfp-src="{{ asset('data/signatures/'.$loan->client->general->first()->signature, isset($secure) ? false : false)}}" id="client-signature">View Client Front</a>
                                                @endif
                                                <br/>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <table class="table-condensed">
                                                            <tr>
                                                                <th>{{ trans('customer.cus_customer_name') }} :</th>
                                                                <td style="font-family: 'Open Sans',sans-serif,'Battambang';">{{ $loan->client->client_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('user.u_user_kh_name') }} :</th>
                                                                <td style="font-family: 'Open Sans',sans-serif,'Battambang';">{{ !empty($loan->client->general->first()->family_name_kh)?$loan->client->general->first()->family_name_kh .' '.$loan->client->general->first()->first_name_kh:'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('multiple.m_gender') }} :</th>
                                                                <td>{{ !empty($loan->client->general->first()->gender)?$loan->client->general->first()->gender:'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('customer.cus_nationality') }} :</th>
                                                                {{-- @if(!empty($loan->client->general->first()->nationality->description->first()))
                                                                    <td>{{ !empty($loan->client->general->first()->nationality->description->first()->name)?$loan->client->general->first()->nationality->description->first()->name:'N/A' }}</td>
                                                                @else
                                                                    <td>N/A</td>
                                                                @endif --}}
                                                                @if(!empty($loan->client->general->first()->national_code))
                                                                    <td>{{ !empty($loan->client->general->first()->national_code)?$loan->client->general->first()->national_code:'N/A' }}</td>
                                                                @else
                                                                    <td>N/A</td>
                                                                @endif
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <table class="table-condensed">
                                                            <tr>
                                                                <th style="width: 30%;">{{ trans('customer.cus_birth_date') }}
                                                                    :
                                                                </th>
                                                                <td>{{ !empty($loan->client->general->first()->date_of_birth)? date("d-M-Y", strtotime($loan->client->general->first()->date_of_birth)):'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('multiple.m_phone',['num'=>'']) }} :</th>
                                                                <td>{{ $loan->client->phone1}}{{ !empty($loan->client->phone2)? ' / '. $loan->client->phone2:''}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('multiple.m_address') }} :</th>
                                                                <td>{{ !empty($loan->client->address)? $loan->client->address:'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('multiple.house_no') }} :</th>
                                                                <td>{{ !empty($loan->client->Address->first()->address_en1)?$loan->client->Address->first()->address_en1:'N/A' }}</td></td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('multiple.street_no') }} :</th>
                                                                <td>{{ !empty($loan->client->Address->first()->address_kh1)?$loan->client->Address->first()->address_kh1:'N/A' }}</td></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <table class="table-condensed">
                                                            <tr>
                                                                <th>{{ trans('customer.cus_card_number') }} :</th>
                                                                <td>{{ !empty($loan->client->Identification->first()->id_number)?$loan->client->Identification->first()->id_number:'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('customer.cus_issued_date') }} :</th>
                                                                <td>{{ !empty($loan->client->Identification->first()->issued_date) ? Date("d-M-Y", strtotime($loan->client->Identification->first()->issued_date)):'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('customer.cus_issued_by') }} :</th>
                                                                <td>{{ !empty($loan->client->Identification->first()->issued_by) ? $loan->client->Identification->first()->issued_by:'N/A'}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>{{ trans('customer.cus_card_expired_date') }} :</th>
                                                                <td>{{ !empty($loan->client->Identification->first()->id_expiry_date) ?  date("d-M-Y", strtotime($loan->client->Identification->first()->id_expiry_date)):'N/A' }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                    {{-- end client --}}
                                </div>
                                <div id="schedule" class="tab-pane">

                                </div>
                                <div id="actual" class="tab-pane">

                                </div>
                                <div id="transaction" class="tab-pane">

                                </div>
                                <div id="charge" class="tab-pane">

                                </div>
                                <div id="guarantor" class="tab-pane">

                                </div>
                                <div id="co_borrower" class="tab-pane">
                                </div>
                                <div id="loandoc" class="tab-pane">

                                </div>
                                <div id="loanpay" class="tab-pane">

                                </div>
                                <div id="customertranfer" class="tab-pane">

                                </div>
                                <!-- <div id="schedule_fee" class="tab-pane"></div> -->

                                <div id="audit" class="tab-pane">
                                    <h5><strong>{{ trans('multiple.audit_title') }}</strong></h5>
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <th>{{ trans('multiple.action') }}</th>
                                            <th>{{ trans('multiple.by') }}</th>
                                            <th>{{ trans('multiple.date') }}</th>
                                            <th>{{ trans('multiple.status') }}</th>
                                        </tr>
                                        @foreach($audit as $au)
                                            <tr>
                                                <td>{{ trans('multiple.audit_created') }}</td>
                                                <td>{{$au->audit1->name}}</td>
                                                <td>{{$au->created_at}}</td>
                                                <td>{{ trans('multiple.unauthorized') }}</td>
                                            </tr>

                                            @if($au->audit2)
                                                <tr>
                                                    <td>{{ trans('multiple.audit_updated') }}</td>
                                                    <td>{{$au->audit2->name}}</td>
                                                    <td>{{$au->updated_at}}</td>
                                                    <td>{{ trans('multiple.authorized') }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>

                                <div id="statement_tap" class="tab-pane">

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- end tab  -------------->
                <div class="modal fade" id="map" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog md-modify">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title">{{ trans('multiple.m_map') }}</h4>
                            </div>
                            <div class="modal-body">
                                <div id="googleMap" style="width:100%;height:500px;"></div>
                            </div>
                            <div class="modal-footer">
                                <button data-dismiss="modal" class="btn btn-default"
                                        type="button">{{ trans('multiple.m_close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div aria-hidden="true" role="dialog" tabindex="-1" id="editSchModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">&nbsp;</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="rp-id" id="rp-id" value="0">
                                <div class="form-group">
                                    <label for="m-date">{{ trans('report.rpt_date') }} <span style="color:red">*</span></label>
                                    <div data-date-viewmode="years" data-initialize="datepicker"
                                         data-date-format="dd-M-yyyy" class="input-append date dpYears">
                                        <input type="text" name="m-date" value="" size="16" class="form-control"
                                               id="m-date">
                                        <span class="add-on">
                                            <button class="btn btn-primary"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="m-interest">{{ trans('report.rpt_interest') }} <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="m-interest" name="m-interest"/>
                                </div>
                                <div class="form-group">
                                    <label for="m-principal">{{ trans('report.rpt_principal') }} <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="m-principal" name="m-principal"/>
                                </div>
                                <button type="button" class="btn btn-primary" id="save"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                                <a data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{"No data for this loan."}}
            @endif
        </div>
    </section>
    @include('loans.update_parc')
    <?php
    $repay = [];
    if (!empty($loan)) {
        $start_date = $loan->start_date;
        $repay = [
            'loan_id' => $loan->id,
            'client_name' => $loan->client->client_name,
            'start_date' => $start_date,
            'loan_amount' => $loan->loan_amount,
            'interest_rate' => $loan->interest_rate,
            'loan_duration' => $loan->loan_duration,
            'repayment_type' => $loan->repayment_type,
            'num_balloon' => $loan->balloon,
            'balloon_month' => $loan->balloon_month,
            'balloon_amount' => $loan->balloon_amount_array,
            'monthly_payment' => $loan->monthly_payment,
            'loan_status' => $loan->status,
            'disbursement_date' => $loan->disburse_date,
            'admin_fee' => $loan->admin_fee,
            'maintain_fee' => $loan->maintain_fee,
            'admin_fee_opt' => $loan->admin_fee_opt,
            'maintain_fee_opt' => $loan->maintain_fee_opt,
            'other_fee' => $loan->other_fee
        ];
    }
    ?>
    <input type="hidden" id="repayment-data" value="{{ json_encode($repay) }}"/>

    <!-- added by chuch -->
    <div aria-hidden="true" role="dialog" id="pop-retrieve-date" class="modal fade">
        <form action="{{ route('pop_retrieve_date') }}" method="get">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="retrieve_type" value=""/>
                        <input type="hidden" name="retrieve_id" value=""/>
                        <div class="col-md-12">
                            <div data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date retrieve_date">
                                <input type="text" name="retrieve_date" size="16" class="form-control" value=""/>
                                <span class="add-on birhtdateDatepicker ptl-3">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="submit_frm" class="btn btn-primary" value="Save"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.popup.min.js',isset($secure) ? false : false) }}"></script>
    <script src="http://maps.google.com/maps/api/js"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/loans-detail.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/iCheck/jquery.icheck.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/xlsx.full.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>

    <script type="text/javascript">
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });

        $("#export").click(function (event) {
            var con = confirm("Do you really want to export to CSV file?");
            if (con == true) {
                new TableExport(document.getElementsByClassName('tab-pane active')[0].getElementsByTagName('table'), {
                    formats: ['csv'],
                    filename: document.getElementsByClassName('tab-pane active')[0].id
                });
                $('button.csv').hide().click();
                $('.tableexport-caption').remove();
            }
            event.preventDefault();
        });

        $("#xexport").click(function (event) {
            var con = confirm("Do you really want to export to Excel file?");
            if (con == true) {
                new TableExport(document.getElementsByClassName('tab-pane active')[0].getElementsByTagName('table'), {
                    formats: ['xlsx'],
                    filename: document.getElementsByClassName('tab-pane active')[0].id
                });
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
            }
            event.preventDefault();
        });


        $(document).ready(function () {
            $('.icheck input').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
            $('.dpYears').datepicker({
                format: 'dd-M-yyyy',
                autoclose: true
            });
            $('#editSchModal').on('show.bs.modal', function (e) {
                var zindex = parseInt($('#editSchModal').css("z-index")) + 10;
                $(document.body).find('div.datepicker').first().css("z-index", zindex);
            });

            //chuch
            $("body").on("click", ".pop-retrieve-date", function () {
                ids = $(this).attr('href').split('/');
                $('input[name="retrieve_type"]').val(ids[0]);
                $('input[name="retrieve_id"]').val(ids[1]);
                $('#pop-retrieve-date .modal-title').text($(this).attr('title'));
                $("#pop-retrieve-date").modal('show');
                return false;
            });

            $('.retrieve_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
        });
        $('#language').hide();
        $('#client_detail').on('click', function () {
            $('#language').hide();
        });
        $('#charge_detail').on('click', function () {
            $('#language').hide();
        });
        $('#loandoc_detail').on('click', function () {
            $('#language').hide();
        });
        $('#guarantor_detail').on('click', function () {
            $('#language').hide();
        });
        $('#co_borrower_detail').on('click', function () {
            $('#language').hide();
        });
        $('#schedule_repayment').on('click', function () {
            $('#language').show();
        });
        $('#actual_repayment').on('click', function () {
            $('#language').show();
        });
        $('#transaction_detail').on('click', function () {
            $('#language').show();
        });
        $('#loanpay_detail').on('click', function () {
            $('#language').show();
        });
        $('#customertranfer_detail').on('click', function () {
            $('#language').show();
        });

        $("#save-parc-level").on('click', function () {
            var id = "{{ $loan->id }}";
            var parc = $("#td_step").val();
            var url = "{{ route('update_parc') }}";
            if (id != "") {
                $.ajax({
                    url: url,
                    data: {
                        parc_step: parc,
                        parc_id: id
                    },
                    success: function (data) {
                        if (data.status == true) {
                            $("#td_parc").text(parc);
                            $("#update-parc").modal('toggle');
                        }
                    }
                });
            }
        });
        $("body").on("click", "a.edit_data", function (e) {
            e.preventDefault();
            var nRow = $(this).parents('tr')[0];
            var nCol = $(">td", nRow);
            $("#editSchModal input#m-interest").val(0);
            $("#editSchModal input#m-principal").val(0);
            $("#editSchModal input#rp-id").val($(this).attr("id"));
            for (var i = 0, iLen = nCol.length; i < iLen; i++) {
                if (nCol[i].className == 'interest') {
                    $("#editSchModal input#m-interest").val((nCol[i].innerHTML).replace(',', ''));
                } else if (nCol[i].className == 'principal') {
                    $("#editSchModal input#m-principal").val((nCol[i].innerHTML).replace(',', ''));
                } else if (nCol[i].className == 're_date') {
                    $("#editSchModal input#m-date").val(nCol[i].innerHTML);
                }

            }
            $("#editSchModal").modal("toggle");
        });
        $('body').on('click', '#editSchModal #save', function (e) {
            e.preventDefault();
            var loan_id = "{{ $loan->id }}";
            var schedule_id = "{{$schedule_id}}";
            var id = $("#editSchModal input#rp-id").val();
            var url = "{{ route('update_repayment_sch')}}";
            var int = $("#editSchModal input#m-interest").val();
            var pri = $("#editSchModal input#m-principal").val();
            var re_date = $("#editSchModal input#m-date").val();
            var repaydata = $("#repayment-data").val();
            if (repaydata != '') {
                var data = $.parseJSON(repaydata);
                $.extend(data, {
                    id: parseFloat(schedule_id) + parseFloat(id),
                    lid: loan_id,
                    interest: int,
                    principal: pri,
                    re_date: re_date
                });
                console.log(data);
                if (int != "" && pri != "") {
                    $.ajax({
                        url: url,
                        data: data,
                        success: function (d) {
                            if (d != null) {
                                $("#schedule").html(d);
                                $("#editSchModal").modal("toggle");
                            }
                        }
                    });
                }
            }
        });
        $('#Statement').on('click', function () {
            $('#language').show();
        });

        /*function exportTableToCSV($table, filename) {
            var $headers = $table.find('tr:has(th)')
                    , $rows = $table.find('tr:has(td)')

                    // Temporary delimiter characters unlikely to be typed by keyboard
                    // This is to avoid accidentally splitting the actual contents
                    , tmpColDelim = String.fromCharCode(11) // vertical tab character
                    , tmpRowDelim = String.fromCharCode(0) // null character

                    // actual delimiter characters for CSV format
                    , colDelim = '","'
                    , rowDelim = '"\r\n"';

            // Grab text from table into CSV formatted string
            var csv = '"';
            csv += ($('.sch_title').html()).trim();
            csv += rowDelim;
            csv += formatRows($headers.map(grabRow));
            csv += rowDelim;
            csv += formatRows($rows.map(grabRow)) + '"';
            // Data URI
            var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

            $(this)
                    .attr({
                        'download': filename
                        , 'href': csvData
                                //,'target' : '_blank' //if you want it to open in a new window
                    });

            //------------------------------------------------------------
            // Helper Functions
            //------------------------------------------------------------
            // Format the output so it has the appropriate delimiters
            function formatRows(rows) {
                return rows.get().join(tmpRowDelim)
                        .split(tmpRowDelim).join(rowDelim)
                        .split(tmpColDelim).join(colDelim);
            }
            // Grab and format a row from the table
            function grabRow(i, row) {

                var $row = $(row);
                //for some reason $cols = $row.find('td') || $row.find('th') won't work...
                var $cols = $row.find('td');
                if (!$cols.length)
                    $cols = $row.find('th');

                return $cols.map(grabCol)
                        .get().join(tmpColDelim);
            }
            // Grab and format a column from the table
            function grabCol(j, col) {
                var $col = $(col),
                        $text = $col.text().trim();

                return $text.replace('"', '""'); // escape double quotes

            }
        }*/


        /*// This must be a hyperlink
        $('body').on('click','.export_schedule_repayment',function(e){
            // var outputFile = 'export'
            var con = confirm("Do you really want to export to CSV file?");
            if (con == true) {
                var outputFile = 'schedule.csv';
                // CSV
                exportTableToCSV.apply(this, [$('#schedule table'), outputFile]);

                // IF CSV, don't do event.preventDefault() or return false
                // We actually need this to be a typical hyperlink
            }
        });

        // This must be a hyperlink
        $('body').on('click','.export_actual_repayment',function(e){
            // var outputFile = 'export'
            var con = confirm("Do you really want to export to CSV file?");
            if (con == true) {
                var outputFile = 'actual.csv';
                // CSV
                exportTableToCSV.apply(this, [$('#actual table'), outputFile]);

                // IF CSV, don't do event.preventDefault() or return false
                // We actually need this to be a typical hyperlink
            }
        });

        // This must be a hyperlink
        $('body').on('click','.export_transaction_detail',function(e){
            // var outputFile = 'export'
            var con = confirm("Do you really want to export to CSV file?");
            if (con == true) {
                var outputFile = 'transaction.csv';
                // CSV
                exportTableToCSV.apply(this, [$('#transaction table'), outputFile]);

                // IF CSV, don't do event.preventDefault() or return false
                // We actually need this to be a typical hyperlink
            }
        });

        // This must be a hyperlink
        $('body').on('click','.export_loanpay_detail',function(e){
            // var outputFile = 'export'
            var con = confirm("Do you really want to export to CSV file?");
            if (con == true) {
                var outputFile = 'loanpay.csv';
                // CSV
                exportTableToCSV.apply(this, [$('#loanpay table'), outputFile]);

                // IF CSV, don't do event.preventDefault() or return false
                // We actually need this to be a typical hyperlink
            }
        });*/

        $(".nav-tabs li").click(function () {
            $('#export').attr('class', 'btn btn-primary export_' + $(this).attr('id'));
        });

function discount(){
    var dic=$("#discount").val();    
    var total_dic=parseFloat($("#outstanding_balance").val()) * parseFloat(dic) / 100;
    $("#total_discount").html(parseFloat(total_dic).toFixed(2));
    var interest_accrual=$("#interest_accrual").val();
    var net_outstanding_balance=parseFloat($("#outstanding_balance").val())-parseFloat(total_dic) + parseFloat(interest_accrual);
    
    $("#net_outstanding_balance").html(parseFloat(net_outstanding_balance).toFixed(2));
}
    </script>
@endsection
