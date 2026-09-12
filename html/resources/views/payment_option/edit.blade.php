@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/jquery-multi-select/css/multi-select.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/jquery-tags-input/jquery.tagsinput.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
@endsection
<style type="text/css">
    .form-horizontal .form-group {
        margin-right: 0px !important;
        margin-left: 0px !important;
    }
    .form-group {
        margin-bottom: 5px !important;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    {{ trans('payment_option.edit') }} : {{ $payment_option->name }}
                </header>
                <div class="panel-body">
                    @if(Session::has('error'))
                        <div class="alert alert-danger fade in">
                            <button class="close close-sm" data-dismiss="alert">x</button>
                            {{ Session::get('error') }}
                        </div>
                    @endif
                    @if(Session::has('msg'))
                        <div class="alert alert-success fade in">
                            <button class="close close-sm" data-dismiss="alert">x</button>
                            {{ Session::get('msg') }}
                        </div>
                    @endif

                    <form class="cmxform form-horizontal" method="post" action="{{route('edit_payment_option',['d' => $payment_option->id])}}" id="frm_payment">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('payment_option.name') }}:<span class="red-color"> *</span></label>
                                    <input type="text" value="{{ $payment_option->name }}" id="name" name="name" class="form-control" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.unit_type') }}:<span class="red-color"> *</span></label>
                                    <select class="form-control" name="unit_type_id">
                                        @foreach($unit_type as $unit_types)
                                            <option value="{{ $unit_types->id }}" {{ $unit_types->id?$unit_types->id == $payment_option->unit_type_id ?'selected':'':old('payment_option') }}>{{ $unit_types->name }} ({{ isset($unit_types->Projects->dealer)?$unit_types->Projects->dealer:''  }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('payment_option.initial_deposit_amount') }}:<span class="red-color"> *</span></label>
                                    <input type="text" value="{{ $payment_option->initial_deposit_amount }}" id="initial_deposit_amount" name="initial_deposit_amount" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="flat-green single-row icheck group">
                                    <div class="radio">
                                        <input type="checkbox" name="first_payment_plan" @if($payment_option->first_payment_plan == true) checked @endif>
                                    </div>
                                    <label class="control-label col">{{ trans('payment_option.first_pay_plan') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('payment_option.first_pay_duration_month') }}:<span class="red-color"> *</span></label>
                                    <input type="text" value="{{ $payment_option->first_payment_duration_month }}" id="first_pay_duration_month" name="first_payment_duration_month" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('payment_option.first_pay_per') }}:<span class="red-color"> *</span></label>
                                    <input type="text" value="{{ $payment_option->first_payment_per }}" id="first_pay_per" name="first_payment_per" class="form-control" required/>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('payment_option.loan_duration') }}:<span class="red-color"> *</span></label>
                                    <input type="text" value="{{ $payment_option->loan_duration_month }}" id="loan_duration" name="loan_duration_month" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('payment_option.interest_per_year') }}<span class="red-color"> *</span></label>
                                    <input type="text" value="{{ $payment_option->interest_per_year }}" id="interest_per_year" name="interest_per_year" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('payment_option.special_discount') }}<span class="red-color"> *</span></label>
                                    <input type="text" value="{{ $payment_option->special_discount }}" id="special_discount" name="special_discount" class="form-control" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="text-align: right;">
                                <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_save') }}
                                </button>
                                <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>&nbsp;&nbsp;{{ trans('multiple.m_reset') }}
                                </button>
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
    <script type="text/javascript"
            src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/jquery-multi-select/js/jquery.multi-select.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/jquery-multi-select/js/jquery.quicksearch.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select-init.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
    <script>
        $(document).ready(function () {
            $('#e9').addClass('required');
            $('#e9').on('change', function (e) {
                if ((e.val).length > 0) {
                    $(this).parent().find('label.error').css({'display': 'none'});
                    $(this).parent().find('.error').removeClass('error').addClass('valid');
                }
            });
            $('#initial_deposit_amount,#first_pay_duration_month,#first_pay_per,#loan_duration,#interest_per_year,#special_discount').on('input',function(evt) {
                numeric_only(evt);
            }); 
        });
        // Allow Numeric Only and max character
        function numeric_only(evt){
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && charCode > 57){
                // evt.target.value = evt.target.value.replace(/[^0-9]/g,'');
                return false;
            }else{
                evt.target.value = evt.target.value.replace(/[^0-9-.]/g,'');
            }   
            return true;
        }
    </script>
@endsection