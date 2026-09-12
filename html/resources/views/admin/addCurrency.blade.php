@extends('layouts.app')

@section('content')
<section class="panel panel-box-700">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
    @endif
    <header class="panel-heading">
        {{ trans('sidebar.sb_add_currencies') }}
    </header>
    <div class="panel-body">
        <form class="cmxform form-horizontal" method="post" action="{{ route('add_currencie') }}" id="comCurrencie">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="panel panel-default box-border-500">
                    <div class="panel-body">
                        <div class="row">
                            <!-- Grid to left -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">{{ trans('currency.c_set_currency_name') }}</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="currency_name" name="currency_name" value=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">{{ trans('user.u_user_code') }}</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="code" name="code" value=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">{{ trans('currency.c_symbol') }}</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="symbol" name="symbol" value="" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">{{ trans('currency.c_type') }}</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="type" name="type" value="" placeholder="Sub or Main"/>
<!--                                        <select class="form-control" id="type" name="type">
                                            <option value="">-</option>
                                            @foreach($currency as $r)
                                            <option value="{{ $r->id }}"
                                                    @if(isset($currency_id))
                                                    @if($currency_id==$r->id)
                                                    selected
                                                    @endif
                                                    @endif>{{ $r->type}}</option>
                                            @endforeach
                                        </select>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <div class="clear-fix"></div>
                    <button type="submit" class="btn btn-info"><i class="fa fa-save"></i>&nbsp;{{ trans('multiple.m_save') }}</button>
                    <button type="button" class="btn btn-danger" onclick="javascript:history.back();"><i class="fa fa-times-circle"></i>&nbsp;{{ trans('multiple.m_cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
