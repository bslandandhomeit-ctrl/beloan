@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
<link rel="stylesheet" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}">
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
           Add History
        </header>

        <div class="panel-body">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
            @endif
            <form class="cmxform form-horizontal" method="post" action="{{ route('loan_add_history',[$loan->id]) }}" id="addCostForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Interest <span class="red-color">*</span></label>
                    <div class="col-sm-4">
                        <input type="text" name="interest" value="{{$loanHistory->interest}}" id="interest" class="form-control"/>
                    </div>
                    <div class="col-sm-3">                                          
                        <select id="interest_status" name="interest_status" class="form-control">
                                <option value="">Select status</option>
                                <option value="Pending" {{ ('Pending' == $loanHistory->interest_status)?'selected' : "" }}>Pending</option>
                                <option value="Approved" {{ ('Approved' == $loanHistory->interest_status)?'selected' : "" }}>Approved</option>
                                <option value="Rejected" {{ ('Rejected' == $loanHistory->interest_status)?'selected' : "" }}>Rejected</option>
                    
                        </select>                                           
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Principal <span class="red-color">*</span></label>
                    <div class="col-sm-4">
                        <input type="text" name="principal" value="{{$loanHistory->principal}}" id="principal" class="form-control"/>
                    </div>
                    <div class="col-sm-3">                                          
                        <select id="principal_status" name="principal_status" class="form-control">
                                <option value="">Select status</option>
                                <option value="Pending" {{ ('Pending' == $loanHistory->principal_status)?'selected' : "" }}>Pending</option>
                                <option value="Approved" {{ ('Approved' == $loanHistory->principal_status)?'selected' : "" }}>Approved</option>
                                <option value="Rejected" {{ ('Rejected' == $loanHistory->principal_status)?'selected' : "" }}>Rejected</option>
                    
                        </select>                                           
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Total Paid <span class="red-color">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="total_paid"  value="{{$loanHistory->total_paid}}" id="total_paid" class="form-control"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('multiple.m_note') }}</label>
                    <div class="col-sm-8">
                        <textarea name="note" value="{{$loanHistory->note}}"  id="note" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3"></label>
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                        <button type="button" class="btn btn-danger" onclick="javascript:history.back()"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
