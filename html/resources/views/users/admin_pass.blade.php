@extends('layouts.app')
@section('content')
    <section class="panel">
        <div class="panel-heading">
            {{ trans('user.u_user_secret') }}
        </div>
        <div class="panel-body">
             @if(Session::has('message'))
                <p class="alert {{ session('css-class') }}">{{ Session::get('message') }}</p>
             @endif
            <form action="{{ route('pwadmin') }}" method="post" class="cmxform form-horizontal" id="frmPw">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('user.u_user_code') }}</label>
                    <div class="col-sm-6">
                        <input type="text" name="admin_pass" class="form-control" value="{{ !empty($pwadmin) ? $pwadmin->admin_pass : '' }}" required/>
                    </div>
                </div>
                <div class="form-group">
                     <label class="col-sm-3 control-label"></label>
                     <div class="col-sm-9">
                        <button type="submit" class="btn btn-primary">{{ trans('multiple.m_save') }}</button>
                     </div>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
@endsection