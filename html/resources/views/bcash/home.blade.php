<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="FIGIX">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="_token" id="_token" value="{{csrf_token()}}" content="{{csrf_token()}}" />
        
        <meta name="description" content="">
        <meta name="author" content="FIGIX">
        <meta name="_token" id="_token" value="{{csrf_token()}}" content="{{csrf_token()}}" />
        <link rel="icon" href="{{ asset('favicon.png', $secure) }}" type="image/png" sizes="20x20">
        <title>BE Cash</title>
        
        <!-- CSS -->
        <link href="{{ asset('theme/font-awesome/css/font-awesome.css', false) }}" rel="stylesheet">
        <link href="{{ asset('theme/bs3/css/bootstrap.min.css', $secure) }}" rel="stylesheet">
        <link href="{{ asset('theme/css/bootstrap-reset.css', $secure) }}" rel="stylesheet">
        <link href="{{ asset('theme/css/bootstrap-reset.css', $secure) }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bcash/jquery-ui.css',isset($secure) ? false : false) }}" />
       
        <!-- Script -->
        <script src="{{ asset('theme/js/jquery.js',$secure) }}"></script>
        <script src="{{ asset('theme/bs3/js/bootstrap.min.js',$secure) }}"></script>
        <script src="{{ asset('js/bcash/jquery/jquery-ui-1-12-1.js',$secure) }}"></script>
        <script src="{{ asset('theme/select2_v4.1.0/select2.min.js',isset($secure) ? false : false) }}"></script>
        
        <!-- BECASH CSS + JS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bcash/bcash.css',isset($secure) ? false : false) }}" />

        <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
        <script type="text/javascript" src="{{ asset('js/bcash/bcash.js',isset($secure) ? false : false) }}"></script>
        <script type="text/javascript" src="{{ asset('js/bcash/moment.js',isset($secure) ? false : false) }}"></script>
        <link href="{{ asset('theme/select2_v4.1.0/select2.min.css',isset($secure) ? false : false) }}" rel="stylesheet" />

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.dark\:text-gray-500{--tw-text-opacity:1;color:#6b7280;color:rgba(107,114,128,var(--tw-text-opacity))}}
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
                display:block;
                padding-left: 10px;
                padding-right: 10px;
            }
            .hiden{
                display: none !important;
            }
            span.select2.select2-container.select2-container--default {
                width: 100% !important;
            }

            .footer-action{
                background: #ccc;
                padding: 7px;
                border: none !important;
                position:relative;
                bottom: 0;
                width: 100%;
            }
            .footer-action .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
                border:none;
            }
            .icon-info{
                color: #1fb5ad;
            }
            .dropdown-toggle{
                text-align: left;
                width: 100%;
            }
            .dropdown-toggle .caret{
                float:right;
                margin-top: 7px;
            }
            .panel {
                position: relative;
            }
            #receipt_print{
                margin: 0 auto;
            }
            .header-recipt{
                text-align: center;
                width: 500px;
                margin: 0 auto;
            }
            .header-recipt img{
                float: left;
            }
            .header-recipt .header-title-recipt{
                text-align: center;
            }
            .header-recipt .header-title-recipt h2,h3{
                font-weight: bold;
            }
            .header-recipt .header-title-recipt .red{
               color:red;
            }
           #receipt_print table{
                margin: 0 auto;
           }
           #receipt_print table td{
            padding: 7px;
            text-align: left;
           }
           .red-b{ 
            color: red;
            font-weight: bold;      
           }
           .add-on {
            float: right;
            margin-top: -39px;
            padding: 1px;
            text-align: center;
        }
        </style>
    </head>
    <body class="bg-gray-100 dark:bg-gray-900">    
        <div id="loading">
                <img src="/images/loading.gif" />  
        </div>    
        <div class="max-w-12xl mx-auto sm:px-12 lg:px-12">
            <div class="mt-12 dark:bg-gray-800 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
                @endif
                <?php
                $static = config('static_data');
                $prod_array = array('' => '');

                $currency_arr = config('static_data.currency');
                $dpDate = date('Y-m-d');
                ?>
                <?php
                 $currency = config('static_data.currency_symbol'); $flag = 1;
                 ?>
                    <div class="panel panel-default">
                        <header class="panel-heading">
                        <i class="fa fa-credit-card"></i> BE-Cash
                        <a class="pull-right" href="/"><i class="fa fa-dashboard"></i> Dashboard </a>
                        </header>
                        <div  class="panel-body">
                        <form action="" method="POST" id="addBCashLoanPaymentForm" enctype="form-data"   name="addBCashLoanPaymentForm" onsubmit="return false;" style="margin-bottom: 0px;" >
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">   
                                      
                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <select name="client" class="form-control" id="client" required></select>
                                        <label id="validationCustom" for="validationCustom" class="form-label">Please select customer</label>
                                    </div>                                    
                                </div>
                                <div class="col-lg-2">
                                <span><a class="btn btn-group btn-sm btn-primary view_schedule_deposit" href="#" style="margin: 0 10px;">View Schedules</a></span>
                                </div>
                            </div>
                            <input type="hidden" id="loan_id" name="loan_id" value="{{ $search_results->id }}" />  
                             <input type="hidden" id="loan_status" name="loan_status" value="" />             
                            <!-- (B) CART -->
                            <div class="table-responsive">                          
                                <table class="table table-item">
                                    <thead>
                                    <tr class="item-header">
                                    <th width="50" scope="col">#</th>
                                    <th scope="col">Deposit</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Description</th>
                                    <th width="50" scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="poscart">
                                </tbody>
                                </table>                                    
                            <div>                
                            </div>
                            </div>  
                            <div class="col-md-12 repayment-blog" id="jd-block">
                                <h4>Journal Detail</h4><hr/>
                                <div class="col-sm-12">
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
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label col-sm-5">{{ trans('loan.l_disburse_date') }}</label>
                                                <div class="col-md-7">
                                                    <input type="text" id="disburse_date" name="disburse_date" class="form-control" readonly="readonly" value="{{ $draft?$draft->disburse_date:$search_results->disburse_date }}" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-5">{{ trans('report.rpt_loan_amount') }}</label>
                                                <div class="col-md-7">
                                                    <input type="text" id="amount" name="amount" class="form-control"  readonly="readonly" value="{{ $draft?$draft->loan_amount:$search_results->loan_amount }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-5">{{ trans('report.rpt_outstanding_amount') }}</label>
                                                <div class="col-md-7">
                                                    <input type="text" id="loan_amount" name="loan_amount" class="form-control"  readonly="readonly" value="{{ $draft?$draft->loan_amount:$search_results->loan_amount }}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Grid to right  -->
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label col-sm-5">{{ trans('report.rpt_tenure') }}</label>
                                                <div class="col-md-7">
                                                    <input type="text" id="loan_duration" name="loan_duration" class="form-control"  readonly="readonly" value="{{ $draft?$draft->loan_duration:$search_results->loan_duration }}"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-5">{{ trans('report.rpt_principal_paid') }}</label>
                                                <div class="col-md-7">
                                                    <input type="text" id="principal_paid" name="principal_paid" class="form-control" id=""  readonly="readonly" value="{{$draft?$draft->principal_paid:$cal_result["total_paid_prin"]}}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-5">{{ trans('loan.l_interest_rate') }}</label>
                                                <div class="col-md-7">
                                                    <input type="text" id="interest_rate" name="interest_rate"  readonly="readonly" value="{{ $draft?$draft->interest_rate:$search_results->interest_rate }}" id="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                        <!-- Grid to left -->
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label class="control-label col-sm-5">{{ trans('account.a_customer_account') }}</label>
                                                <div class="col-md-7">
                                                    <input type="text" id="account_no" name="account_no" class="form-control"  readonly="readonly" value="{{$draft?$draft->account_no:$search_results->client_loan_account->account_no }}"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-5">{{ trans('customer.cus_customer_name') }}</label>
                                                <div class="col-md-7">
                                                    <input type="text" id="account_name" name="account_name" class="form-control"  readonly="readonly" value="{{ $draft?$draft->account_name:$search_results->client_loan_account->account_name }}"/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-5">{{ trans('product.p_loan_reference') }}</label>
                                                <div class="col-md-7">
                                                    <input type="text" id="contract_id" name="contract_id" class="form-control" readonly="readonly" value="{{ $draft?$draft->contract_id:$search_results->contract_id }}"/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-5">{{ trans('loan.l_branch') }}</label>
                                                <div class="col-md-7">
                                                    <input type="text" readonly value="{{$draft?$draft->branch_name:$branch_name}}" class="form-control" name="branch_name" id="branch_name" />
                                                    <input type="hidden" value="{{$draft?$draft->branch_code:$branch_code}}" class="form-control" name="branch_code" id="branch_code" />
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
                                                    <input type="text" class="form-control"  readonly="readonly" id="last_paid_date" name="last_paid_date" value="{{ $draft?$draft->last_paid_date:$cal_result['last_pay_date'] }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-5">{{ trans('loan.l_next_schedule_date') }}</label>
                                                <div class="col-md-7">
                                                    <?php $next_sch_date = $next_sch_date? date('Y-M-d', strtotime($next_sch_date)) : '_'?>
                                                    <input type="text" class="form-control"  readonly="readonly" id="next_schedule_date" name="next_schedule_date" value="{{ $draft?$draft->next_schedule_date:$cal_result['next_sch_date'] }}" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-5">{{ trans('loan.l_currency') }}</label>
                                                <div class="col-md-7">
                                                    <input type="text" readonly value="{{$draft?$draft->currency_name:@$currency_arr[2]}}" class="form-control" name="currency_name" id="currency_name"/>
                                                    <input type="hidden" value="{{$draft?$draft->currency:(is_array($currency) ? @$currency[2] : $currency)}}" class="form-control" name="currency" id="currency"/>
                                                </div>
                                            </div>
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
                                        </div>
                                    </div>
                                        </div>
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
                                <div class="b-block add_loan_charge hiden">
                                    <h5><strong>Add loancharge</strong></h5>
                                    <div id="add_loan_charge"></div>
                                </div>
                                <div class="b-block act_principal hiden">
                                    <h5><strong>Principal Repayment</strong></h5>
                                    <div id="act_principal"></div>
                                </div>
                                <div class="b-block act_air_interest hiden">
                                    <h5><strong>Interest Repayment(AIR)</strong></h5>
                                    <div id="act_air_interest"></div>
                                </div>
                                <div class="b-block act_interest hiden">
                                    <h5><strong>Interest Repayment(Interest Income)</strong></h5>
                                    <div id="act_interest"></div>
                                </div>
                                <div class="b-block act_penalty hiden">
                                    <h5><strong>Penalty Charge</strong></h5>
                                    <div id="act_penalty_blog"></div>
                                </div>
                                <div class="b-block act_fee hiden">
                                <h5><strong>Other Fee</strong></h5>
                                <div id="act_fee"></div>
                                </div>
                            </div>                          
                        </form>
                        </div>
                        <div class="footer-action">
                                <table class="table">
                                <thead>
                                <tbody>
                                    <tr>
                                        <td scope="row">
                                            <input type="hidden" id="totalBalance">
                                            <span>Installment:</span><i class="fa fa-info-circle icon-info" aria-hidden="true"></i><br/>
                                            <span class="total" id="Installment">0</span>
                                            <input type="hidden" id="is_Installment"  value="0">
                                        </td>
                                        <td scope="row">
                                            <span>Outstanding Balance:</span><br/>
                                            <span class="total" id="Outstanding">0</span>
                                        </td>
                                        <td scope="row">
                                            <span>Duration:</span><br/>
                                            <span class="total" id="Duration">0</span>
                                        </td>
                                        <td scope="row">
                                            <span>Penalty(+):</span> <i class="fa fa-info-circle icon-info" aria-hidden="true"></i><br/>
                                            <span class="total" id="Penalty">0</span>
                                            <input type="hidden" id="is_penalty" value="0">
                                        </td>
                                        <td scope="row">
                                            <span>Water:</span><br/>
                                            <span class="total" id="Water">0</span>
                                        </td>
                                        <td scope="row">
                                            <span>Electric(+): <i class="fa fa-info-circle icon-info" aria-hidden="true"></i></span><br/>
                                            <span class="total" id="Electric">0</span>
                                        </td>
                                        <td scope="row">
                                            <span>Total Payable:</span><br/>
                                            <span class="total" id="Total_Payable">0</span>
                                        </td>
                                    </tr>
                                </tbody>
                                </table>
                                <div class="action">                                   
                                    <button type="button" id="cempty" onclick="clearCart()">Clear Cart</button>
                                    <button class="pull-right" type="button" id="ccheckout" onclick="checkout()"><i class="fa fa-credit-card"></i> Payment</button>                                    
                                </div> 
                            </div>  
                    </div>

                    <div class="panel panel-default">
                        <header class="panel-heading">
                            All Deposit
                        </header>
                        <div  class="panel-body">
                        <div class="col col-lg-6">
                        <select class="form-select form-control dropdown-toggle" aria-label="Default select example" id="select_deposit_type">
                        <option selected>All Deposit Type</option>
                        <option value="Real_Estate_Loan">Real Estate-Loan</option>
                        <option value="Real_Estate_Other_Fee">Real Estate-Other Fee</option>
                        <option value="Property_Company">Property Company</option>
                        <option value="IIP">IIP</option>
                        </select>
                        </div>
                            <!-- (A) PRODUCTS LIST -->
                            <div id="poslist"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="repayment_sch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog md-modify modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                            </button>
                            <h4 class="modal-title">Preview Repayment Schedule</h4>
                        </div>
                        <div class="modal-body" id="repayment_schedule">
                        </div>
                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="printAreaContent">
    <div class="col-sm-12">

        <section class="panel" style="margin: 0 auto;">
            <div id="printArea" class="panel-body" style="padding: 45px !important;">
                <div class="box-img">
                    <img id="box-img" src="{{ $logo }}" style="opacity: 0.1;object-fit: contain;width: 100%;height: 100%;z-index: -1;">
                </div>
                <h1 class="title text-center brach_name">{{ $company_branch->branch_name }}</h1>
                <h2 class="title color-red text-center branch_location">{{ ($projects_row->dealer != '')?$projects_row->dealer:'&nbsp;' }}</h2>
                <h3 class="title text-center branch_address">Head office: {{ $company_branch->address_one }}</h3>
                <h3 class="title text-center branch_address2">{{ ($company_branch->address_two != '')?$company_branch->address_two:'&nbsp;' }}</h3>
                <h3 class="title color-red text-center company_tel">Tel: {{ $company_branch->contact_number }}</h3>
                <h3 class="title text-center company_mail">E-mail:{{ $company_branch->email }} / Page:{{ $company_branch->website }}</h3>
                <img src="{{ $logo }}" class="project-logo"/>
                <div style="border-bottom: double;margin-top: 10px !important;"></div>
                <div class="row">
                    <div class="col-sm-4">
                        <p class="posting_date"></p>
                        <p class="receipt_time"></p>
                    </div>
                    <div class="col-sm-4">
                        <h2 class="khmer-title text-center" style="margin-top: 10px !important;">បង្កាន់ដៃទទួលប្រាក់</h2>
                        <h2 class="title text-center">
                            <span style="border-bottom: 1px solid #000;">Receipt</span>
                        </h2>
                    </div>
                    <div class="col-sm-4 no" style="margin-top: 20px;">
                        <p style="text-align: left;">លេខ​ / <b>No</b> <span  class="horizontal_dotted_lines dotted_width receipt_no" style="width: 130px;">{{ $journalr->receipt_no }}</span> </p>
                    </div>
                </div>
                <div style="margin: 0 20px;">
                    <?php
                     
                        $name_kh = $drawdown->client->ClientCbcGeneral->family_name_kh.' '.$drawdown->client->ClientCbcGeneral->first_name_kh;
                    ?>
                    <p>បានទទួលប្រាក់ពីឈ្មោះ <span class="horizontal_dotted_lines receipt_client_name" style="width: 295px;">
                    {{ ($name_kh != ' ')?$name_kh: $drawdown->account_name}}</span> ទឹកប្រាក់ <b style="border: 1px solid;padding: 15px !important;"> <span class="horizontal_dotted_lines Paid_Amount" style="width: 138px;/*170px;*/">$ {{ number_format($slips->amount,2) }}</span></b></p>
                    <p class="p-english">Received From <span class="cust_no">{{ $drawdown->client->cus_acc }}</span></p>
                    <p>ទឹកប្រាក់សរសេរជាអក្សរ <span class="horizontal_dotted_lines" style="width: 519px;"></span></p>
                    <p class="p-english">Amount in Words</p>
                    <p>អត្ថន័យ <span class="horizontal_dotted_lines description receip_description" style="width: 398px;">{{ $slips->description }}</span>Property <span class="horizontal_dotted_lines Property" style="width: 162px;">{{ $unit->code }}</span></p>
                </div>
                <div class="row" style="margin: 0 20px;">
                    <div class="col-sm-6" style="padding:0px !important;"><p>Description</p></div>
                    <div class="col-sm-6" style="padding-left:75px !important;"><p>Address</p></div>
                </div>
                <div class="row" style="margin: 0 20px;">
                    <div class="col-sm-4" style="text-align: center;">
                        <p class="khmer-title">អតិថិជន</p>
                        <p>Customer</p>
                    </div>
                    <div class="col-sm-4" style="text-align: center;">
                        <p class="khmer-title">អ្នកលក់</p>
                        <p>Seller</p>
                    </div>
                    <div class="col-sm-4" style="text-align: center;">
                        <p class="khmer-title">បេឡា</p>
                        <p>Cashier</p>
                        @if ($slips->is_signature == 1)
                            <?php 
                                $signature = '';
                                if($slips->signature){
                                    if(file_exists('data/users/'.$slips->signature)){
                                        $signature = asset('data/users/'.$slips->signature);
                                    }
                                }

                            ?>
                            @if ($signature != '')
                                <img style="margin-top: -13px; height: 49px;" src="{{ $signature }}">
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

            <div id="paymentModal" class="modal fade" role="dialog">
                <div class="modal-dialog  modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirm Payment</h4>
                    </div>

                    <div class="modal-body">
                        <div class="container-fluid">
                        <div class="row">
                                <div class="col-sm-7">
                                    <div class="row" style="-webkit-box-shadow: 0px 1px 5px 0px rgb(0 0 0 / 75%);margin: 0px;padding: 5px;margin-bottom: 10px;border-radius: 4px; padding: 5px;padding-top: 10px; margin-bottom: 10px">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                <label>Total Amount *</label>
                                                    <input type="text" id="total_amount" class="form-control red-b" readonly />
                                                    <input type="hidden" id="sub_total" name="sub_total"  class="form-control" readonly />
                                                    <label id="validationtotal_amount" for="validationCustom" class="form-label">Please Enter Total Amount</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                <label>Received Amount *</label>
                                                    <input type="text" id="received_amount"  class="form-control res-grand-total" />
                                                    <label id="validationreceived_amount" for="validationCustom" class="form-label">Please Enter Received Amount</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                <label>Change *</label>
                                                    <input type="text" id="change_amount"  class="form-control red-b" readonly />                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label for="Notify to">Till Date *</label>
                                        <div class="input-append" >
                                            <input class="form-control" id="till_date" name="till_date" type="text"
                                            value="{{ date('Y-m-d H:i:s') }}">
                                            <span class="add-on">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                        </div>
                                        <div class="form-group">
                                        <label for="Notify to">Notify to *</label>
                                            <select class="select2" id="loan_admin" name="loan_admin">
                                                <option value="">
                                                Choose one
                                                </option>
                                            </select>
                                            <label id="validationCashier" for="validationCustom" class="form-label">Please Select Cashier</label>
                                        </div>
                                        <div class="form-group">
                                        <label for="Deposit type" >Payment Method *</label>
                                            <select class="select2" id="types" name="types">
                                                <option value="">
                                                Choose one
                                                </option>
                                            </select>
                                            <label id="validationAccountType" for="validationCustom" class="form-label">Please Select Account Type</label>
                                        </div>
                                        <div class="form-group appendData">
                                        </div>
                                        <div class="form-group">
                                        <label for="Description" style="height:30px;">Description</label>
                                            <textarea class="form-control" id="descr" name="descr" rows=
                                            "10"></textarea>
                                        </div>
                                        <label id="validationDescription" for="validationCustom" class="form-label">Please Enter Description</label>
                                        </div>
                                                  
                                
                        
                                <div class="col-sm-5">
                                    <table class="table table-striped">
                                    <thead>
                                        <tr>
                                        <th scope="row">Total Deposit</th>
                                        <td><span id="res-total-deposit"> 0</span></td>
                                        </tr>
                                    </thead>
                                    <tbody id="item-deposit-list">
                                       
                                    </tbody>
                                    <tfoot>
                                        <tr class="res-grand-total">
                                        <th scope="row">Grand Total</th>
                                        <td>(=) <span id="res-grand-total">0</span>
                                            <input type="hidden" name="grand_total" id="result-grand-total" />
                                        </td>
                                        </tr>
                                    </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">                   
                        <button type="button" id="bt_payment" class="btn btn-primary">Payment</button>
                        <button type="button"  id="bt_close" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>

                </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="text-center text-sm text-gray-500 sm:text-left">
                    <div class="flex items-center">
                        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="-mt-px w-5 h-5 text-gray-400">
                            <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>

                        <a href="/" class="ml-1 underline">
                            Back
                        </a>
                    </div>
                </div>

                <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                    BCash v1.0.0
                </div>
            </div>
        </div>        
    </body>


<script src="{{ asset('js/bcash/jquery/select2.min.js',$secure) }}" defer></script>
<script src="{{ asset('js/bcash/jquery/jquery-1.9.1.js',$secure) }}"></script>
<script src="{{ asset('js/bcash/jquery/jquery-ui.js',$secure) }}"></script>
<script src="{{ asset('js/bcash/jquery/jquery.validate.min.js',$secure) }}"></script>
<script src="{{ asset('js/bcash/jquery/jquery.min.js',$secure) }}"></script>

<script src="{{ asset('theme/js/jquery.js',$secure) }}"></script>

<script src="{{ asset('theme/bs3/js/bootstrap.min.js',$secure) }}"></script>

<script src="{{ asset('js/bcash/jquery/jquery-ui-1-11-0.js',$secure) }}"></script>


<script type="text/javascript">
var alldata = {};
var paymentModal=false;
var real_estate=false;
var property=false;
var iip=false;
$(document).keypress(function(e) {
    var keycode;
    if (window.event) keycode = window.event.keyCode;
    else if (e) keycode = e.which;
    else return true;

    if (keycode == 13)
    {
        if(paymentModal){
            $("#bt_payment").click();
        }else{
            $("#ccheckout").click();
        }

        return false;
    }
    else
        return true;
});
$(document).ready(function () {
    $('#till_date').datepicker({
        format: 'yyyy-mm-dd hh:ii:ss',
        autoclose: true,
        setDate: getNowTime(),
        onSelect: function(dateText) {
        var allow_postback_date = '<?php echo $tellers[0]->allow_postback_date ;?>';
        // if(allow_postback_date!=='1'){
        //     var d1 = new Date();
        //     var d2 = new Date(this.value);
        //     d1.setHours(0, 0, 0, 0);
        //     d2.setHours(0, 0, 0, 0);
        //     if(d2<d1){
        //         alert("You don't have permission allow postback date, Please contact administrator.");
        //         this.value=getNowTime();
        //     }
        //     }
        }
    });

    setTimeout(function() {

        var tellers = '<?php echo $tellers[0]->company_type ;?>';
        if(tellers){
            var company_type=tellers.split(",");
            if(company_type.length>0){
                for (let i = 0; i < company_type.length; i++) {
                    if(company_type[i]==='Real Estate'){
                        real_estate=true;
                        $(".Real_Estate_Loan").show();
                        $(".Real_Estate_Other_Fee").show();
                    }
                    if(company_type[i]==='Property'){
                        property=true;
                        $(".Property_Company").show();
                    }
                    if(company_type[i]==='IIP'){
                        iip=true;
                        $(".IIP").show();
                    }
                }
            }
        }
    }, 250);

    let windowHeight = window.innerHeight;
        $(".panel-body").css("height", windowHeight-230);        
        $("#printAreaContent").hide();
        $("#loading").hide();
        var tellers = null;
        var cheifs = null;
        var chiefdata = null;
        var status_flag = '<?php echo $flag;?>';

        let repay_type = <?php echo json_encode(\App\Models\PaymentType::activeList());?>;

        if(status_flag == 1){ // all account opened
            $(".issueTill").attr("disabled", "disabled");
            $(".returnTill").attr("disabled", "disabled");
            $(".transferTill").attr("disabled", "disabled");
            $(".repayLoan").attr("disabled", "disabled");
            $(".incomeFee").attr("disabled", "disabled");
            $(".DisburseLoan").attr("disabled", "disabled");
            $(".expense").attr("disabled", "disabled");
        }

    $('input').attr('autocomplete','off');
    var loan_id = '<?php echo $search_results->id ;?>';
    $("#client").select2({
        minimumInputLength: 0,
        placeholder: "Select Customer",
        data:[{id:loan_id,text:'<?php echo $loan->client_name."->".$loan->contract_id."->".$loan->drawdown_acc."->".$loan->short_code."->".$loan->name.'->'.$loan->code;?>'}],
         allowClear: true,
        ajax:{
            url: '/teller/deposit',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            // timeout: 5000,
            data: function (term) {
                return {
                    _token: CSRF_TOKEN,
                    term: term.term,
                }
            },
            processResults: function(data) {
                    if(data.withdraw){
                        if(data.withdraw.length>0){
                        alldata = data.withdraw;
                        return {
                            results: 
                            $.map(data.withdraw, function (vals,keys){
                                return {
                              text: vals.account_name+' ( ' + data.withdraw[keys].projects.dealer + ' - ' + data.withdraw[keys].unit_type.name + ' - ' + data.withdraw[keys].units.code + ') - ' + data.currency_list[data.withdraw[keys].currency],
                              slug: vals.account_name,
                              id: data.withdraw[keys].id,
                              name:'client'
                           }
                            })
                        }; 
                    }else{
                        $('<div id="loading"></div>').appendTo('body');
                        return
                    }
                }
            },
        }
    }); 

    var types = '<option value="">Choose one</option>';
    for(var keys in repay_type) {
    if(repay_type[keys] == 'Drawdown Account') continue;
    types += '<option value="' + repay_type[keys] + '"> ' + repay_type[keys] + ' </option>';
    }

    $("#types").html(types);
    $("#types").select2();
    $("#validationCustom").hide();
    $("#jd-block").hide();
    $("#bs_logo").hide();
    $("#east_logo").hide();
    $('.view_schedule_deposit').attr('disabled',true);
    var withd_acc = {};
    var till_act_def_balance = {};
    $('#client').on("change", function(e) {
        $('.view_schedule_deposit').attr('disabled',true);
        $("#validationCustom").fadeOut();
        var client_id = $("#client").val();
        if(client_id !==''){
            var selected=alldata.filter(x => x.id === parseInt(client_id));
            if(selected.length>0){
                $("#loan_status").val(selected[0].status);
                $("#loading").show();
                $.ajax({
                type:'GET',
                url:'/bcash/getLoanDetail',
                data:{
                    client_id:parseInt(selected[0].client_id),
                    client_name:selected[0].client_name,
                    project_id:parseInt(selected[0].project_id),
                    unit_id:parseInt(selected[0].unit_id),
                    unit_type:selected[0].unit_type,                   
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                    dataType: 'json',
                    success: function (data) {
                        if(data){
                            $("#loading").hide();
                            if(data.loans.length>0){
                                $("#loan_id").val(data.loans[0].id);
                                $("#overdue").val(data.overdue);
                                $("#disburse_date").val(data.loans[0].disburse_date);
                                $("#amount").val(data.loans[0].loan_amount);
                                $("#loan_amount").val(data.loans[0].loan_amount);
                                $("#loan_duration").val(data.loans[0].loan_duration);
                                $("#principal_paid").val(0);
                                $("#interest_rate").val(data.loans[0].interest_rate);
                                $("#contract_id").val(data.loans[0].contract_id);
                                $("#branch_name").val(data.branch_name);
                                $("#branch_code").val(data.branch_code);
                                $("#next_schedule_date").val(data.next_sch_date);
                                $("#currency_name").val(data.loans[0].currency_code);
                                
                                
                                
                                var loan_duration=data.loans[0].loan_duration+' M';
                                $("#Duration").html(loan_duration);                               
                                if(data.loans[0].client_loan_account){
                                    $("#account_no").val(data.loans[0].client_loan_account.account_no);
                                    $("#account_name").val(data.loans[0].client_loan_account.account_name);
                                    $("#currency").val(data.loans[0].client_loan_account.currency);
                                    
                                    
                                    var outstanding_balance=(data.loans[0].client_loan_account.balance).toFixed(2) + ' USD';
                                    $("#Outstanding").html(outstanding_balance);

                                    $('.view_schedule_deposit').click(function(){
                                        var url="/loan_detail_schedule/"+data.loans[0].id+'/'+data.loans[0].drawdown_acc
                                        get_repayment_schedule(url);
                                    });
                                    $('.view_schedule_deposit').attr('href','javascript:void(0);');
                                    $('.view_schedule_deposit').attr('disabled',false);
                                }  
                                
                                if(data.sch_repay_down_loan_arr){
                                    var t_sch_int = t_sch_prin = t_sch_penal = t_sch_fee = t_sch_other_fee = 0;
                                    var total_sch_prin=0
                                    if(data.sch_repay_down_loan_arr.downpayment){
                                        if(data.sch_repay_down_loan_arr.downpayment.length>0){
                                            t_sch_int =parseFloat(data.sch_repay_down_loan_arr.downpayment[0].interest);
                                            t_sch_prin =parseFloat(data.sch_repay_down_loan_arr.downpayment[0].principal);
                                        }
                                    }
                                    if(data.sch_repay_down_loan_arr.loan){
                                        if(data.sch_repay_down_loan_arr.loan.length>0){  
                                            data.sch_repay_down_loan_arr.loan.forEach((ar) => {
                                                // t_sch_int +=parseFloat(data.sch_repay_down_loan_arr.loan[0].interest);
                                                // t_sch_prin += parseFloat(data.sch_repay_down_loan_arr.loan[0].principal);
                                                // t_sch_penal +=parseFloat(data.sch_repay_down_loan_arr.loan[0].penalty);
                                                // t_sch_fee +=parseFloat(data.sch_repay_down_loan_arr.loan[0].fee);
                                                // t_sch_other_fee +=parseFloat(data.sch_repay_down_loan_arr.loan[0].other_fee); 
                                                t_sch_int += parseFloat(ar['interest']);
                                                t_sch_prin += parseFloat(ar['principal']);
                                                t_sch_penal += parseFloat(ar['penalty']);
                                                t_sch_fee += parseFloat(ar['fee']);
                                                t_sch_other_fee += parseFloat(ar['other_fee']);
                                                total_sch_prin+= (parseFloat(ar['total'])-parseFloat(ar['penalty']));


                                    });

                                    }
                                    }
                                   if(total_sch_prin<=0 && data.next_schedule){
                                        t_sch_int +=parseFloat(data.next_schedule.interest);
                                        t_sch_prin += parseFloat(data.next_schedule.principal);
                                        total_sch_prin+= parseFloat(data.next_schedule.total_payment);
                                   }                                            
                                        var sch_total = 0.0;
                                        sch_total = parseFloat(t_sch_prin) + parseFloat(t_sch_int) + parseFloat(t_sch_penal) + parseFloat(t_sch_fee) + parseFloat(t_sch_other_fee);
                                        sch_total = Math.round(sch_total * 100)/100;

                                        $("#Installment").html(total_sch_prin+' USD');
                                        $("#is_Installment").val(total_sch_prin);                                        
                                        $("#Penalty").html(t_sch_penal+' USD');
                                        $("#is_penalty").val(t_sch_penal);
                                        
                                        $("#Total_Payable").html(sch_total+' USD');

                                        $("#sch_penalty").val(t_sch_penal);
                                        $("#sch_interest").val(t_sch_int);
                                        $("#sch_fee").val(t_sch_fee);
                                        $("#sch_other_fee").val(t_sch_other_fee);
                                        $("#sch_principal").val(0);
                                        $("#act_interest").val(0);
                                        $("#act_fee").val(0);
                                        $("#act_other_fee").val(0);
                                        $("#act_penalty").val(0);
                                        $("#act_principal").val(0);
                                        $("#act_principal").val(0);                                        
                                        
                                    
                                }
                                            
                            }

                            if(data.add_loan_charge){
                                getJournalDetail(data.add_loan_charge,'add_loan_charge');
                            }
                            if(data.act_penalty){
                                getJournalDetail(data.act_penalty,'act_penalty_blog');
                            }
                         

                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            }

        }
    });
    $('#received_amount').on("change", function(e) {
        var value=this.value;
        var total_amount=$("#result-grand-total").val();
        if(parseFloat(value) < parseFloat(total_amount)){
            $("#validationreceived_amount").html('Received amount is less than Total amount');
            $('#received_amount').addClass( "validation" ); 
            $("#validationreceived_amount").fadeIn();
            $("#validationreceived_amount").addClass( "text-validation" ); 
        }else if(parseFloat(value) == parseFloat(total_amount)){
            $("#change_amount").val(0);
            $("#validationreceived_amount").hide();
            $('#received_amount').removeClass( "validation" );
        
        }else{
            $("#validationreceived_amount").hide();
            $('#received_amount').removeClass( "validation" ); 
            var change_amount=parseFloat(value)-parseFloat(total_amount);
            if(change_amount){
                $("#change_amount").val('-$'+change_amount);
            }

        }
    });
    $("#ccheckout").click(function () {
        GrandTotal();
        $("#validationCustom").removeClass( "text-validation" );
        var client = $('#client').find("option:selected").val();
        if(client===''){
        $("#validationCustom").fadeIn();
        $("#validationCustom").addClass( "text-validation" );
        $(".select2").addClass( "validation" ); 
        return;
        }

        const isItems = Object.keys(cart.items);
        if(isItems.length ===0){
            alert("Cart is empty, Please select item!");
            return;
        }

        var iSError=false;
        $('#poscart').find('.deposit_type').each(function() {
            var amount=$(this).find("input[class*='amount']").val();
            if(amount==='' || amount<=0){
            $(this).find("input[class*='amount']").addClass( "validation" );
            iSError=true;
            return;
            }else{
            $(this).find("input[class*='amount']").removeClass( "validation" );
            }  

            var input_amount_name=$(this).find("input[class*='amount']").attr('name');
            if(input_amount_name==='Penalty_Fee'){
                var is_penalty=$("#is_penalty").val();
                if(is_penalty<=0){
                    alert("No prepayment penalty loan. Pease remove penalty record");
                    iSError=true;
                    return;
                }
            }
            // if(input_amount_name==='Loan_Installment'){
            //     var is_Installment=$("#is_Installment").val();
            //     if(is_Installment<=0){
            //         alert("loan installment balance is zero! Cannot deposit amount.");
            //         iSError=true;
            //         return;
            //     }
            // }    
        });
        if(!iSError){
            $("#paymentModal").modal("toggle");
            paymentModal=true;
            $("#received_amount").val('');
            $("#types").val('').trigger('change');
            var client = $('#client').find("option:selected").text();
            $(".header-title").html(client);
            var loan_admins_opt = '<option value="">Choose one</option>';
            $.ajax({
                type:'GET',
                url:'/teller/deposit',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (data,status) {
                    if(status==='success'){
                        for (var keys in data.loan_admin) {
                            var loan_admins = data.loan_admin[keys];
                            loan_admins_opt += '<option value="' + loan_admins.id + '"> ' + loan_admins.name + ' </option>';
                        }
                        $("#loan_admin").html(loan_admins_opt);
                        $("#loan_admin").select2();
                    }
                },
                error: function (data) {
                    console.log(data);
                }
                
            });
        }
    });
});
$("#validationCashier").hide();
$("#validationAccountType").hide();
$("#validationDescription").hide();
$("#validationtotal_amount").hide();
$("#validationreceived_amount").hide();
$( "#bt_payment" ).click(function() {
        $("#validationtotal_amount").removeClass( "text-validation" );
        var total_amount = $('#total_amount').val();
        if(total_amount===''){
            $('#total_amount').addClass( "validation" ); 
            $("#validationtotal_amount").fadeIn();
            $("#validationtotal_amount").addClass( "text-validation" ); 
            return;
        }else{
            $("#validationDescription").hide();
            $('#total_amount').removeClass( "validation" ); 
        }
        $("#validationtotal_amount").removeClass( "text-validation" );
        var received_amount = $('#received_amount').val();
        var result_grand_amount=$("#result-grand-total").val();
        if(received_amount===''){
            $('#received_amount').addClass( "validation" ); 
            $("#validationreceived_amount").fadeIn();
            $("#validationreceived_amount").addClass( "text-validation" ); 
            return;
        }else if(parseFloat(received_amount)<parseFloat(result_grand_amount)){
            $("#validationreceived_amount").html('Received amount is less than Total amount');
            $('#received_amount').addClass( "validation" ); 
            $("#validationreceived_amount").fadeIn();
            $("#validationreceived_amount").addClass( "text-validation" );
            return;
        }else{
            $("#validationreceived_amount").hide();
            $('#received_amount').removeClass( "validation" ); 
            var change_amount=parseFloat(received_amount)-parseFloat(result_grand_amount);
            if(change_amount){
                $("#change_amount").val('-$'+change_amount);
            }
        }

        $("#validationCashier").removeClass( "text-validation" );
        var loan_admin = $('#loan_admin').find("option:selected").val();
        if(loan_admin===''){
            $("#validationCashier").fadeIn();
            $("#validationCashier").addClass( "text-validation" ); 
            return;
        }else{
            $("#validationCashier").hide();
        }

        $("#validationAccountType").removeClass( "text-validation" );
        var types = $('#types').find("option:selected").val();
        if(types===''){
            $("#validationAccountType").fadeIn();
            $("#validationAccountType").addClass( "text-validation" ); 
            return;
        }else{
            $("#validationAccountType").hide();
        }

        $("#validationDescription").removeClass( "text-validation" );
        var des = $('#descr').val();
        if(des===''){
            $('#descr').addClass( "validation" ); 
            $("#validationDescription").fadeIn();
            $("#validationDescription").addClass( "text-validation" ); 
            return;
        }else{
            $("#validationDescription").hide();
            $('#descr').removeClass( "validation" ); 
        }


        paymentModal=false;
        $("#bt_payment").prop("disabled", true).text("Processing...");
        formSubmit();

});
$(document).on('change', '#types', function () {
         var del = $('#paymentModal').find('.appendData');
         if ($(this).is('#types')) {
            for (var i = 0; i <= del.length; i++) {
               del.children().remove();
            }
            $('<label class="control-label" for="print"> Check number : </label>' +
               '<input type="text" value="" name="check_num" class="form-control" />' +
               '</div>' +
               '<label class="control-label" for="print"> Bank name: </label>' +
               '<input type="text" value="" name="bank_name" class="form-control" />').appendTo('.appendData');
         }
      });
function get_repayment_schedule(urls){
    if(urls){
        $.ajax({
            url: urls,
            type:'GET',
            dataType: "JSON",
            success:function(data){
                if(data){
                    $("#repayment_sch").css('z-index','2000');
                    $("#repayment_sch").modal({ backdrop: "static", keyboard: !1});
                    $("#repayment_sch").find('.modal-backdrop').css('opacity','0.5');
                    $("#repayment_schedule").html(data);
                }
            }
        })
    }
}

function formSubmit(){
    var client_id = $('#client').find("option:selected").val();
        if(client_id !==''){
            $.each(alldata, function (inx, vals) {
                till_act_def_balance  = parseFloat(vals.balance);
            });
            withd_acc = matchAndGetData(parseInt(client_id));
        }


            var bank_name = $('input[name=bank_name]').val() ? $('input[name=bank_name]').val() : 0;
            var check_num = $('input[name=check_num]').val() ? $('input[name=check_num]').val() : 0;
            var balance = (parseFloat(withd_acc.balance) + parseFloat($('#totalBalance').val()));
            var data = $('#addBCashLoanPaymentForm').serialize() + '&client_id=' + parseInt(withd_acc.client_id) + '&id='
            + parseInt(withd_acc.id) + '&users_id=' + parseInt($('#loan_admin option:selected').val());
                data += '&currency_id=' + parseInt(withd_acc.currency) + '' + '&client_name=' + withd_acc.account_name + '&description=' + $('#descr').val() 
                + '&balance=' + balance + '&types=' + $('#types option:selected').val() + '&bank_name='
                + bank_name + '&check_num=' + check_num + '&till_date=' + $('#till_date').val()
                + '&if_print=1&not_id=issueTill'
                + '&drawdown_acc_id='+ parseInt(withd_acc.id)
                + '&loan_admin='+ parseInt($('#loan_admin option:selected').val())
                + '&drawdown_acc='+ withd_acc.account_no
                + '&descr=' + $('#descr').val()
                + '&submit=Submit'
                + '&flag_button=ok'
                + '&from='+ parseInt(withd_acc.id)
                + '&sel_tellers='+ parseInt(0)
                + '&payment_type='+ parseInt(0)
                + '&sub_total='+ parseFloat($('#sub_total').val())
                + '&loan_status='+ parseFloat($('#loan_status').val())
                + '&grand_total='+ parseFloat($('#result-grand-total').val());
                
                
                

                $.ajax({
                type:'POST',
                url:'/bcash/addBcash',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                dataType: 'json',
                success: function (data) {
                    if(data.success){
                        if(data.bcash){
                            $(".posting_date").html('Posting Date: '+ moment(data.bcash.issue_date).format('Y-m-d'));
                            $(".receipt_time").html('Receipt Time: '+ moment(data.bcash.issue_date).format('Y-m-d H:i:s'));                        
                            $(".receipt_no").html(data.bcash.receipt_no);
                            $(".Paid_Amount").html('$'+data.bcash.grand_total);
                            $(".receipt_client_name").html(data.bcash.client_name);
                            $(".cust_no").html('CUS'+data.bcash.client_id);
                            $(".receip_description").html(data.bcash.description);                        
                        }
                        if(data.loan){
                            $(".Property").html(data.loan.code);
                            if(data.loan.company_branch_id===1){
                                $(".project-logo").attr("src","/images/bs_land.jpg");
                                $("#box-img").attr("src","/images/bs_land.jpg");
                                $(".brach_name").html("ប៊ីអេស លែន & ហូម ខូ អិលធីឌី");
                                $(".branch_location").html("បុរីចតុមុខស៊ីធី ២");
                                $(".branch_address").html("Head office: អគារលេខ B2-109, B2-110");
                                $(".branch_address2").html("សង្កាត់​ទន្លេបាសាក់ ខណ្ឌចំការមន រាជធានីភ្នំពេញ");
                                $(".company_tel").html("Tel: 069 455555/ 099 788883");
                                $(".company_mail").html("E-mail: / Page:");
                                
                                
                                
                                
                                
                                
                            }else{
                                $(".project-logo").attr("src","/images/east_land.jpg");
                                $("#box-img").attr("src","/images/east_land.jpg");
                            }

                            
                        }
                        clearCart();
                        $("#client").val('').trigger('change')
                        $("#jd-block").hide();
                        // printDiv();
                        location.href = "/bcash/print/"+data.bcash.id;
                    }else{
                        $("#bt_payment").prop("disabled", false).text("Payment");
                        alert("Payment failed to save. Please try again or contact admin.");
                    }

                },
                error: function (data) {
                    console.log(data);
                    $("#bt_payment").prop("disabled", false).text("Payment");
                    alert("Payment failed to save. Please try again or contact admin.");
                }

            });
                     
}
$('#select_deposit_type').on("change", function(e) {
    var classElement=this.value;
    if(classElement!=='All Deposit Type'){
        $(".Real_Estate_Loan").hide();
        $(".Real_Estate_Other_Fee").hide();
        $(".Property_Company").hide();
        $(".IIP").hide();
        if((classElement==='Real_Estate_Loan' || classElement==='Real_Estate_Other_Fee') && real_estate===true){
            $("."+classElement).show();
        }
        if(classElement==='Property_Company' && property===true){
            $("."+classElement).show();
        }
        if(classElement==='IIP' && iip===true){
            $("."+classElement).show();
        }
        
    }else{

        if(real_estate===true){
            $(".Real_Estate_Loan").show();
            $(".Real_Estate_Other_Fee").show();
        }
        if(property===true){
            $(".Property_Company").show();
        }
        if(iip===true){
            $(".IIP").show();
        }
    }

});
function printDiv() {
            $("#printAreaContent").show();
            var divContents = document.getElementById("printAreaContent").innerHTML;
            // var a = window.open('', '', 'height=500, width=500');
            var a = window.open('', '', '','');
            a.document.write('<html><head>');
            a.document.write('<link rel="stylesheet" href="/css/bcash/becash_receipt.css" type="text/css" />');
            a.document.write('</head>');
            a.document.write('<body');
            a.document.write(divContents);
            a.document.write('</body></html>');
            setTimeout(function() {
                a.document.close();
                a.print();
                location.reload();
            }, 250);
        }
function matchAndGetData(dd_id) {
    for (var keys in alldata) {
    if (parseInt(alldata[keys].id) === parseInt(dd_id)) {
        return alldata[keys];
    }
    }
}
function getJournalDetail(param,elementId){
    if(param){
    var output = '<table class="journal-helper tb-search-box">';
    if (param.entry_date) {
    output+= '<tr>';
    output+= '<td>';
    output+= '<span>Entry Date</span>';
    output+= '<input type="text" name="entry_date[]" class="form-control" readonly="readonly" value="' + param.entry_date + '" />';
    output+= '<input type="hidden" name="branch_code[]" value="' + param.branch_code + '" />';
    output+= '</td>';

    output+= '<td>';
    output+= '<span>Invoice Number</span>';
    output+= '<input type="text" name="invoice_number[]" class="form-control" />';
    output+= '</td>';

    output+= '<td>';
    output+= '<span>Branch</span>';
    output+= '<input type="text" name="branch" class="form-control" readonly="readonly" value="' + param.branch_label + '" />';
    output+= '<input type="hidden" name="branch_id[]" value="' + param.branch + '" />';
    output+= '</td>';

    output+= '<td>';
    output+= '<span>Currency</span>';
    output+= '<input type="text" name="currency_label" class="form-control" readonly="readonly" value="' + param.currency_label + '" />';
    output+= '<input type="hidden" name="currency[]" value="' + param.currency + '" />';
    output+= '</td>';

    output+= '<td></td>';
    output+= '</tr>';
    }

    output+= '<tr>';
    output+= '<td colspan="2" class="h-parent_debit" width="600">';
    output+= '<span>Debit</span>';
    output+= '<input type="text" name="parent_debit_label" class="parent_debit_label_className form-control" readonly="readonly" value="' + param.parent_debit_label + '" />';
    output+= '<input type="hidden" class="parent_debit_className" name="parent_debit[]" value="' + param.parent_debit + '" />';
    output+= '</td>';

    if (param.contract_id) {
    output+= '<td>';
    output+= '<span>Contract ID</span>';
    output+= '<input type="text" name="contract_id[]" class="form-control" readonly="readonly" value="' + param.contract_id + '" />';
    output+= '</td>';
    }
    output+= '<td>';
    output+= '<span>Debit</span>';
    output+= '<input type="text" name="debit[]" class="form-control parent_debit_className_value  '+elementId+'_deit" readonly="readonly" value="' + param.debit + '" />';
    output+= '</td>';

    output+= '<td>';
    output+= '<span>Description</span>';
    output+= '<input type="text" name="d_description[]" class="form-control" value="' + param.d_description + '" />';
    output+= '</td>';

    output+= '</tr>';

    output+= '<tr>';
    output+= '<td colspan="2" class="h-parent_credit">';
    output+= '<span>Credit</span>';
    output+= '<input type="text" name="parent_credit_label" class="form-control parent_credit_label_className" readonly="readonly" value="' + param.parent_credit_label + '" />';
    output+= '<input type="hidden" class="parent_credit_className" name="parent_credit[]" value="' + param.parent_credit + '" />';
    output+= '</td>';

    if (param.contract_id) {
    output+= '<td>';
    output+= '<span>Contract ID</span>';
    output+= '<input type="text" name="credit_contract_id" class="form-control" readonly="readonly" value="' + param.contract_id + '" />';
    output+= '</td>';
    }

    output+= '<td>';
    output+= '<span>Credit</span>';
    output+= '<input type="text" name="credit[]" class="form-control parent_credit_className_value '+elementId+'_credit" readonly="readonly" value="' + param.credit + '" />';
    output+= '</td>';

    output+= '<td>';
    output+= '<span>Description</span>';
    output+= '<input type="text" name="c_description[]" class="form-control" value="' + param.c_description + '" />';
    output+= '</td>';
    output+= '</tr>';

    output+= '<tr>';
    output+= '<td colspan="5">';
    output+= '<span>Description</span>';
    output+= '<textarea name="description[]" class="form-control">' + param.description + '</textarea>';
    output+= '</td>';
    output+= '</tr>';
    output+= '</table>';

    $("#"+elementId).html(output);

}

}

function leftPad(number, targetLength) {
    var output = number + '';
    while (output.length < targetLength) {
        output = '0' + output;
    }
    return output;
}
function getNowTime(){
    var now = new Date();
    return now.getFullYear() + "/" + leftPad(now.getMonth()+1,2) + "/" + leftPad(now.getDate(),2) + " " + leftPad(now.getHours(),2) + ":" + leftPad(now.getMinutes(),2) + ":" 
        + leftPad(now.getSeconds(), 2);
}
</script> 
</html>
