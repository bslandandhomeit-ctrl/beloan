@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" />
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
            @endif
            <header class="panel-heading">
               {{ trans('sidebar.sb_add_bank') }}
            </header>
            <div class="panel-body">
                <form class="cmxform form-horizontal" method="post" action="{{ route('add_bank') }}" id="bankForm">
                   <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                   <div class="form-group">
                       <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_bank_name') }} <span class="red-color">*</span></label>
                       <div class="col-sm-6">
                           <input type="text" name="bank_name" id="bank_name" class="form-control" />
                       </div>
                   </div>
                   <div class="form-group">
                       <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_account_name') }} <span class="red-color">*</span> </label>
                       <div class="col-sm-6">
                           <input type="text" name="account_name" id="account_name" class="form-control" />
                       </div>
                   </div>
                   <div class="form-group">
                       <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_account_number') }} <span class="red-color">*</span> </label>
                       <div class="col-sm-6">
                           <input type="text" name="account_number" id="account_number" class="form-control"/>
                       </div>
                   </div>
                   <div class="form-group">
                       <b/><br/>
                       <label class="col-sm-3 control-label"></label>
                       <div class="col-sm-6">
                           <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                           <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> {{ trans('multiple.m_reset') }}</button>
                       </div>
                   </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
@endsection
