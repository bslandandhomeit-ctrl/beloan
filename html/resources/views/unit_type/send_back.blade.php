@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            Send Back Commission Setting
        </header>

        <div class="panel-body">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
            @endif
            @if($errors->all())
                <div class="alert alert-danger fade in">
                    <button type="button" class="close" data-dimdiss="alert"></button>
                    {{ HTML::ul($errors->all()) }}
                </div>
            @endif
            @if(Session::has('danger'))
                <div class="alert alert-danger fade in">
                    <button class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('danger') }}
                </div>
            @endif
            <form class="cmxform form-horizontal" method="post" action="{{ route('sendBackCommissionSetting',[$unit_type->id]) }}" id="addApprovalLoanForm" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Unit Type Code</label>
                    <div class="col-sm-6">
                    <input type="text" readonly name="short_code" class="form-control" id="short_code" value="{{$unit_type->short_code}}"/>  
                    </div>
                    
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Unit Type</label>
                    <div class="col-sm-6">
                    <input type="text" readonly name="name" class="form-control" id="short_code" value="{{$unit_type->name}}"/>  
                    </div>
                    
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Commission type</label>
                    <div class="col-sm-6">
                    <input type="text"  readonly name="commission_type" class="form-control" id="commission_type" value="{{ $unit_type->activeSaleCommissionSetting->commission_type=='$'?'Fixed ($)':'Percentage (%)' }}"/>  
                    </div>
                    
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Commission value</label>
                    <div class="col-sm-6">
                    <input type="text" readonly name="commission_value" class="form-control" id="commission_value" value="{{ $unit_type->activeSaleCommissionSetting->commission_value}}"/>  
                    </div>
                    
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('multiple.m_note') }}</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="note" name="note"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"></label>
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_save') }}</button>
                        <a href="javascript:history.go(-1)" class="btn btn-danger"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;{{ trans('multiple.m_cancel') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        }); 
    </script>
 @endsection