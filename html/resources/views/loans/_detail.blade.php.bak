@extends('layouts.app')

@section('css')
 <link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
 <link href="{{ asset('css/popup.css',isset($secure) ? false : false) }}" rel="stylesheet" />
 <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
 <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet" />
 <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
 <link href="{{ asset('theme/js/iCheck/skins/flat/green.css',isset($secure) ? false : false) }}" rel="stylesheet">
 <style type="text/css">
     .tab-content{
         position: relative;
     }
     .tab-content .tab-pane{
         min-height: 200px;
     }
     .tab-content .loading{
         position: absolute;
         left: 50%;
         top: 20px;
         display: block;
         width: 40px;
         height: 40px;
         background: transparent url("{{ asset('images/loading.gif',true) }}") no-repeat scroll center center / contain;
     }
     .thumbnail{
         margin-bottom: 5px;
     }
 </style>
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            <?php $static = config('static_data');?>
            {{ trans('loan.l_loan_information') }}
            <span style="font-weight: bold;color:blue;"> {{(!empty($loan->client_loan_account->account_no))? $loan->client_loan_account->account_no : ""}}</span>
            @if(!empty($static) && !empty($static['loan_status']) &&
                array_key_exists($loan->status,$static['loan_status']))
                <span class="red-color" style="font-weight: bold;"> ( {{ $static['loan_status'][$loan->status] }} )</span>
            @endif
        </header>
        <div class="panel-body">
        @if(Session::has('msg'))
            <div class="alert alert-info fade in">
                <button class="close close-sm" type="button" data-dismiss="alert">x</button>
                {{ Session::get('msg') }}
            </div>
        @endif
        @if(!empty($loan))
            <div class="row">
                <div class="col-sm-12">
                <input type="hidden" id="loan-id" value="{{ $loan->id }}" />
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
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
                            {{--<ul class="nav navbar-nav navbar-left">--}}
                                {{--<li>--}}
                                {{--<a style="cursor: default; font-weight: bold;">--}}
                                    {{--<span>Status: </span>--}}
                                    {{--@if(!empty($static) && !empty($static['loan_status']) &&--}}
                                        {{--array_key_exists($loan->status,$static['loan_status']))--}}
                                        {{--<span class="red-color" style="font-size: large;">{{ $static['loan_status'][$loan->status] }}</span>--}}
                                    {{--@endif--}}
                                {{--</a>--}}
                                {{--</li>--}}
                            {{--</ul>--}}

                            <ul class="nav navbar-nav navbar-right">
                                @if($loan->status == 1)
                                <li>
                                    <a href="{{ route('loan_approval',[$loan->id]) }}" class="approval">
                                        <i class="fa fa-adn"></i>
                                        {{ trans('loan.l_approve')  }}
                                    </a>
                                </li>
                                @endif
                                @if($loan->status == 7)
                                <li>
                                    <a href="{{ route('loan_reschedule_approve',[$loan->id]) }}" class="approval">
                                        <i class="fa fa-adn"></i>
                                        {{ trans('loan.l_approve')  }}
                                    </a>
                                </li>
                                @endif
                                @if($loan->status == 2)
                                <li>
                                    <a href="{{ route('loan_disburse',[$loan->id]) }}" class="approval">
                                        <i class="fa fa-adn"></i>
                                        {{ trans('loan.l_disburse')  }}
                                    </a>
                                </li>
                                @endif

                                @if($loan->status == 1)
                                <li>
                                    <a href="{{ route('reject_loan',[$loan->id]) }}">
                                        <i class="fa fa-ban"></i>
                                        {{ trans('loan.l_reject')  }}
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
                              @if($loan->status == 6)
                                <li>
                                    <a href="{{ route('loan_reschedule', [$loan->id]) }}">
                                        <i class="fa fa-plus"></i>
                                        {{ trans('sidebar.sb_reschedule_loans') }}
                                    </a>
                                </li>
                              @endif
                              {{--@if(($loan->status < 4 || ($loan->status > 6 && $loan->status < 9)) )--}}
                              @if($loan->status < 9)
                                @if($loan->status != 8)
                                <li>
                                    <a href="{{ route('add_guarantor', [$loan->id]) }}">
                                        <i class="fa fa-plus"></i>
                                        {{ trans('loan.l_add_guarantor') }}
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
                                <li>
                                    <a href="{{ route('loan_add_document',[$loan->id]) }}">
                                        <i class="fa fa-plus"></i>
                                        {{ trans('loan.l_add_loan_document') }}
                                    </a>
                                </li>
                              @endif
                                <li>
                                    @if($loan->status == 1 || $loan->status == 7)
                                        <a href="{{ route('loan_edit',[$loan->id])}}" ><i class="fa fa-pencil"></i>&nbsp; {{ trans('loan.l_edit_loan') }}</a>
                                    @endif
                                </li>
                                @if($loan->status == 3 || $loan->status == 8)
                                <li class="dropdown">
                                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">{{ trans('loan.l_more') }} <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('loan_payoff',[$loan->id]) }}">{{ trans('sidebar.sb_payoff_loans') }}</a></li>
                                        <li><a href="{{ route('loan_writeoff',[$loan->id]) }}">{{ trans('sidebar.sb_write-off_loans') }}</a></li>
                                        @if($loan->status == 3)
                                        <li><a href="{{ route('dealer_loan',[$loan->id]) }}">{{ trans('loan.l_update_dealer_info') }}</a></li>
                                        <li><a href="{{ route('loan_close',[$loan->id]) }}">{{ trans('loan.l_close_loan') }}</a></li>
                                        @endif
                                        <li><a href="#update-parc" data-toggle="modal">{{ trans('loan.l_update_step') }}</a> </li>
                                    </ul>
                                </li>
                                @endif

                            </ul>

                        </div><!-- /.navbar-collapse -->
                    </nav>
                    <!--navigation end-->
                </div>
            </div>
<!--- start list loan information -->
            <div class="row">
                <div class="col-md-6">
                   <table class="table table-bordered table-striped table-condensed">
                        <tr>
                            <th style="width: 25%;">{{ trans('report.rpt_office') }}</th>
                            <td>{{ !empty($loan->branch->branch_name) ?$loan->branch->branch_name : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('report.rpt_contract_id') }}</th>
                            <td>{{ !empty($loan->contract_id) ? $loan->contract_id : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('loan.l_approval_id') }}</th>
                            <td>{{ !empty($loan->approval_id) ? 'C'.str_pad($loan->approval_id , 6, '0', STR_PAD_LEFT): '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('product.p_product_id') }}</th>
                            <td><a href="{{ route('list_product')}}">{{ !empty($loan->product_id) ? str_pad($loan->product_id , 6, '0', STR_PAD_LEFT): '-' }}</a></td>
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
                             <td>{{ !empty($loan->disburse_date) ? date('d-M-Y',strtotime($loan->disburse_date)): '-' }}</td>
                        </tr>
                        <tr>
                             <th>{{ trans('loan.l_settled_on') }}</th>
                             <td>{{ !empty($loan->settlement_date) ? date("d-M-Y", strtotime($loan->settlement_date)): '-' }}</td>
                        </tr>
                        <tr>
                             <th>{{ trans('report.rpt_contract_date') }}</th>
                             <td>
                                {{ !empty($loan->contract_date)  ? date('d-M-Y',strtotime($loan->contract_date)) : date('d-M-Y',strtotime($loan->start_date)) }}
                            </td>
                        </tr>
                         <tr>
                             <th>{{ trans('loan.l_loan_purpose') }}</th>
                             <td>{{ !empty($loan->loan_purpose) ? $loan->loan_purpose : '-'}}</td>
                        </tr>
                        <tr>
                             <th>{{ trans('loan.l_doc_location') }}</th>
                             <td>{{ $loan->doc_location }}</td>
                        </tr>
                        <tr><th>{{ trans('loan.l_step', ['num'=>'']) }}</th><td id="td_parc">{{ $loan->parc_step > 0 ? $loan->parc_step : ''}}</td></tr>
                   </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered table-striped table-condensed">
                        <tr>
                            <th style="width: 25%;">{{ trans('loan.l_repayment_type') }}</th>
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
                                 @if(!empty($static) && !empty($static['loan_type']) &&
                                                                   array_key_exists($loan->loan_type,$static['loan_type']))
                                   {{ $static['loan_type'][$loan->loan_type] }}
                                 @endif
                             </td>
                        </tr>
                        <tr>
                            <th>{{ trans('report.rpt_loan_amount') }}</th>
                            <td>{{ $loan->loan_amount }}</td>
                        </tr>
                       <tr>
                            <th>{{ trans('loan.l_down_payment') }}</th>
                            <td>{{ $loan->down_payment }}</td>
                        </tr>
                        <tr>
                             <th>{{ trans('report.rpt_tenure') }}</th>
                             <td>{{ $loan->loan_duration }} M</td>
                        </tr>
                        <tr>
                            <th>{{ trans('loan.l_interest_rate') }}</th>
                            <td>{{ number_format($loan->interest_rate,2) }}% (P.M), {{number_format(($loan->interest_rate*12),2)}}% (P.A)</td>
                        </tr>
                        <tr>
                             <th>{{ trans('loan.l_penalty_rate',['num'=>'']) }}</th>
                             <td>
                            <?php
                                if($loan->penalty_rate_type == 1){
                                    echo $loan->penalty_rate1.' % (for over '.$loan->penalty_period1.' days)';
                                }elseif($loan->penalty_rate_type == 2){
                                    echo $loan->penalty_rate1.' % (from '.$loan->penalty_period1.' to 30 days)';
                                }elseif($loan->penalty_rate_type == 3){
                                    echo $loan->penalty_rate1.' % ( from '.$loan->penalty_period1.' to '.$loan->penalty_period2.' days ), ';
                                    echo $loan->penalty_rate2.' % ( from '.($loan->penalty_period2+1).' to 30 days )';
                                }
                             ?>
                            </td>
                        </tr>
                        <tr>
                             <th>{{ trans('loan.l_pay_off_rate',['num'=>'']) }}</th>
                             <td>{{$loan->pay_off_rate1}}%(From 0 to {{ $loan->payoff_period1 }}months) {{$loan->pay_off_rate2}}%(From {{ $loan->payoff_period1 }} to {{ $loan->payoff_period2 }}months)
                                 {{ ($loan->pay_off_rate3 > 0) ? $loan->pay_off_rate3 .'%(From '. $loan->payoff_period2.' months)' : ''}}</td>
                        </tr>
                        <tr>
                             <th>DSR</th>
                              @if($loan->status == 1)
                               <td style="color: #ff0000">
                                  {{number_format($loan->dsr,2,'.',',')}}%
                               </td>
                              @else
                               <td>
                                  {{number_format($loan->dsr,2,'.',',')}}%
                               </td>
                              @endif
                        </tr>
                        <tr>
                             <th>MoF</th>
                             @if($loan->status == 1)
                              <td style="color: #ff0000">
                                 {{number_format($loan->mof,2,'.',',')}}%
                              </td>
                             @else
                              <td>
                                 {{number_format($loan->mof,2,'.',',')}}%
                              </td>
                             @endif
                        </tr>
                        <tr>
                             <th>{{ trans('loan.l_annual_yield') }}</th>
                            @if($loan->status == 1)
                             <td style="color: #ff0000">
                                {{number_format($loan->annual_yield,2,'.',',')}}%
                             </td>
                            @else
                             <td>
                                {{number_format($loan->annual_yield,2,'.',',')}}%
                             </td>
                            @endif
                        </tr>

                   </table>
                </div>
            </div>
<!--- end list loan information -->
<!-- start tab -->
            <ul id="language" class="pull-right" style="margin-top: -5px;">
                <li><button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button></li>
                <li><button class="btn btn-warning" id="customer_printer"><i class="fa fa-print"></i> {{trans('loan.l_customer_print')}}</button></li>
            </ul>
            <section>
                <ul class="nav nav-tabs">
                    <li class="active" id="client_detail"><a data-toggle="tab" href="#client">{{ trans('customer.cus_customer') }}</a></li>
                    <li id="schedule_repayment"><a data-toggle="tab" href="#schedule">{{ trans('loan.l_repayment_schedule') }}</a></li>
                    <li id="actual_repayment"><a data-toggle="tab" href="#actual">{{ trans('loan.l_repayment_actual') }}</a></li>
                    <li id="transaction_detail"><a data-toggle="tab" href="#transaction">{{ trans('loan.l_transaction') }}</a></li>
                    <li id="charge_detail"><a data-toggle="tab" href="#charge">{{ trans('loan.l_charge') }}</a></li>
                    <li id="guarantor_detail"><a data-toggle="tab" href="#guarantor">{{ trans('loan.l_guarantor') }}</a></li>
                    <li id="loandoc_detail"><a data-toggle="tab" href="#loandoc">{{ trans('loan.l_loan_document') }}</a></li>
                    <li id="loanpay_detail"><a data-toggle="tab" href="#loanpay">{{ trans('loan.l_detail_repayment_until_today') }}</a></li>
                    <li id="do_audit"><a data-toggle="tab" href="#audit">{{ trans('multiple.audit') }}</a></li>
                </ul>
                <div class="panel-body">
                    <div id="printArea" class="printArea">
                    @include('api.report_header',['co_phone'=>!empty($loan->co_user) ? $loan->co_user->phone: ''])
                    <div class="tab-content">
                        <span class="loading" id="loading"></span>
                        <div id="client" class="tab-pane active">
                           @if(!empty($loan->client))
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="fileupload-new thumbnail" style="width: 200px;">
                                            <img src="{{ $loan->client->photo?asset('/data/clients/'.$loan->client->photo,true):asset('images/noimage.gif',true) }}" alt="Profile Picture"/>
                                        </div>
                                         @if(!empty($loan->client->location_latitude) && !empty($loan->client->location_longitude))
                                            <a href="javascript:;" class="client-view view-map"
                                             data-lat="{{ $loan->client->location_latitude }}" data-long="{{ $loan->client->location_longitude }}"
                                            >{{ trans('multiple.m_view_map') }}</a> &nbsp;
                                         @endif
                                         @if(!empty($loan->client->signature))
                                         <a href="javascript:;" class="client-view"
                                                data-mfp-src="{{ asset('data/signatures/'.$loan->client->signature,true)}}"
                                                 id="client-signature">View Client Signature</a>
                                         @endif
                                         <br/>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <table class="table-condensed">
                                                    <tr>
                                                        <th>{{ trans('customer.cus_customer_name') }} : </th>
                                                        <td>{{ $loan->client->client_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ trans('user.u_user_kh_name') }} : </th>
                                                        <td>{{ !empty($loan->client->client_khmer_name)?$loan->client->client_khmer_name:'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ trans('multiple.m_gender') }} : </th>
                                                        <td>{{ !empty($loan->client->gender)?$loan->client->gender:'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ trans('customer.cus_nationality') }} : </th>
                                                        <td>{{ !empty($loan->client->nationality)?$loan->client->nationality:'N/A' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-4">
                                                <table class="table-condensed">
                                                    <tr>
                                                        <th style="width: 30%;">{{ trans('customer.cus_birth_date') }} : </th>
                                                        <td>{{ !empty($loan->client->birth_date)? date("d-M-Y", strtotime($loan->client->birth_date)):'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ trans('multiple.m_phone',['num'=>'']) }} : </th>
                                                        <td>{{ $loan->client->phone1}}{{ !empty($loan->client->phone2)? ' / '. $loan->client->phone2:''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ trans('multiple.m_address') }} : </th>
                                                        <td>{{ !empty($loan->client->address)? $loan->client->address:'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ trans('customer.cus_job') }} : </th>
                                                        <td>{{ !empty($loan->client->job) ? $loan->client->job:'N/A' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-4">
                                                <table class="table-condensed">
                                                    <tr>
                                                        <th>{{ trans('customer.cus_card_number') }} : </th>
                                                        <td>{{ $loan->client->card_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ trans('customer.cus_issued_date') }} : </th>
                                                        <td>{{ !empty($loan->client->card_date) ? Date("d-M-Y", strtotime($loan->client->card_date)):'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ trans('customer.cus_issued_by') }} : </th>
                                                        <td>{{ !empty($loan->client->card_issued_by) ? $loan->client->card_issued_by:'N/A'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ trans('customer.cus_card_expired_date') }} : </th>
                                                        <td>{{ !empty($loan->client->card_expired_date) ?  date("d-M-Y", strtotime($loan->client->card_expired_date)):'N/A' }}</td>
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
                            <div id="loandoc" class="tab-pane">
			
                            </div>
                            <div id="loanpay" class="tab-pane">

                            </div>

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
                        </div>
                    </div>
                </div>
            </section>
<!-- end tab  -------------->
        <div class="modal fade" id="map" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog md-modify">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">{{ trans('multiple.m_map') }}</h4>
                    </div>
                    <div class="modal-body">
                        <div id="googleMap" style="width:100%;height:500px;"></div>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">{{ trans('multiple.m_close') }}</button>
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
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd-M-yyyy" class="input-append date dpYears">
                                    <input type="text" name="m-date" value="" size="16" class="form-control" id="m-date">
                                        <span class="add-on">
                                            <button class="btn btn-primary"><i class="fa fa-calendar"></i></button>
                                        </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="m-interest">{{ trans('report.rpt_interest') }} <span style="color:red">*</span></label>
                                <input type="text" class="form-control" id="m-interest" name="m-interest" />
                            </div>
                            <div class="form-group">
                                <label for="m-principal">{{ trans('report.rpt_principal') }} <span style="color:red">*</span></label>
                                 <input type="text" class="form-control" id="m-principal" name="m-principal" />
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
            if(!empty($loan)){
                $start_date = $loan->start_date;
                $repay = [
                    'client_name'   => $loan->client->client_name,
                    'start_date'    => $start_date,
                    'loan_amount'   => $loan->loan_amount,
                    'interest_rate' => $loan->interest_rate,
                    'loan_duration' => $loan->loan_duration,
                    'repayment_type'=> $loan->repayment_type,
                    'num_balloon'   => $loan->balloon,
                    'balloon_month' => $loan->balloon_month,
                    'balloon_amount'=> $loan->balloon_amount_array,
                    'monthly_payment'=>$loan->monthly_payment,
                    'loan_status' => $loan->status,
                    'disbursement_date' => $loan->disburse_date
                ];
            }
        ?>
        <input type="hidden" id="repayment-data" value="{{ json_encode($repay) }}"/>
@endsection


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
					<input type="hidden" name="retrieve_type" value="" />
					<input type="hidden" name="retrieve_id" value="" />
					<div class="col-md-12">
						<div data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date retrieve_date">
							<input type="text" name="retrieve_date" size="16" class="form-control" value="" />
							<span class="add-on birhtdateDatepicker ptl-3">
								<button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
					
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
					<input type="submit" name="submit_frm" class="btn btn-primary" value="Save" />
				</div>
			</div>
		</div>
	</form>
</div>

@section('js')
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.popup.min.js',isset($secure) ? false : false) }}"></script>
<script src="http://maps.google.com/maps/api/js"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/loans-detail.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/iCheck/jquery.icheck.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
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
         $("body").on("click",".pop-retrieve-date", function(){
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
    $('#client_detail').on('click',function(){
        $('#language').hide();
    });
    $('#charge_detail').on('click',function(){
        $('#language').hide();
    });
    $('#loandoc_detail').on('click',function(){
        $('#language').hide();
    });
    $('#guarantor_detail').on('click',function(){
        $('#language').hide();
    });
    $('#schedule_repayment').on('click',function(){
        $('#language').show();
    });
    $('#actual_repayment').on('click',function(){
        $('#language').show();
    });
    $('#transaction_detail').on('click',function(){
        $('#language').show();
    });
    $('#loanpay_detail').on('click',function(){
        $('#language').show();
    });
    $("#save-parc-level").on('click',function(){
        var id = "{{ $loan->id }}";
        var parc = $("#td_step").val();
        var url = "{{ route('update_parc') }}";
        if(id != ""){
            $.ajax({
                url:url,
                data:{
                    parc_step: parc,
                    parc_id: id
                },
                success:function(data){
                    if(data.status == true){
                        $("#td_parc").text(parc);
                        $("#update-parc").modal('toggle');
                    }
                }
            });
        }
    });
    $("body").on("click","a.edit_data",function(e){
        e.preventDefault();
        var nRow = $(this).parents('tr')[0];
        var nCol = $(">td",nRow);
        $("#editSchModal input#m-interest").val(0);
        $("#editSchModal input#m-principal").val(0);
        $("#editSchModal input#rp-id").val($(this).attr("id"));
        for (var i = 0, iLen = nCol.length; i < iLen; i++) {
          if(nCol[i].className == 'interest'){
             $("#editSchModal input#m-interest").val((nCol[i].innerHTML).replace(',',''));
          }else if(nCol[i].className == 'principal'){
             $("#editSchModal input#m-principal").val((nCol[i].innerHTML).replace(',',''));
          }else if(nCol[i].className == 're_date'){
               $("#editSchModal input#m-date").val(nCol[i].innerHTML);
          }

        }
        $("#editSchModal").modal("toggle");
    });
    $('body').on('click','#editSchModal #save',function(e){
        e.preventDefault();
        var loan_id = "{{ $loan->id }}";
        var id = $("#editSchModal input#rp-id").val();
        var url = "{{ route('update_repayment_sch')}}";
        var int =  $("#editSchModal input#m-interest").val();
        var pri =  $("#editSchModal input#m-principal").val();
        var re_date =  $("#editSchModal input#m-date").val();
        var repaydata = $("#repayment-data").val();
        if(repaydata != '') {
            var data = $.parseJSON(repaydata);
            $.extend(data, {
                id: id,
                lid : loan_id,
                interest: int,
                principal: pri,
                re_date : re_date
            });
           if(int!= "" && pri!= ""){
               $.ajax({
                   url:url,
                   data:data,
                   success:function(d){
                       if(d != null){
                           $("#schedule").html(d);
                           $("#editSchModal").modal("toggle");
                       }
                   }
               });
           }
        }
    });
</script>
@endsection