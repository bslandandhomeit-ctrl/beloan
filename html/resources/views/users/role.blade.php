@extends('layouts.app')

@section('css')
     <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" />
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
             <header class="panel-heading">
                {{ trans('sidebar.sb_add_role') }}
             </header>
             <div class="panel-body">
               @if(Session::has('message'))
                   <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
               @endif
                  <form class="cmxform form-horizontal" method="post" action="{{route('add_role')}}" id="roleForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_role') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="role" id="role"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_role_name') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                             <input type="text" class="form-control" name="role_name" id="role_name" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_description') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                            <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> {{ trans('multiple.m_reset') }}</button>
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
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
@endsection
