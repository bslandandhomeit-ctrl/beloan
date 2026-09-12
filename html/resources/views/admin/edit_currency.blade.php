@extends('layouts.app')

@section('content')
<section class="panel panel-box-700">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
    @endif
    <header class="panel-heading">
        Edit Currency
    </header>
    <div class="panel-body">
        <form class="cmxform form-horizontal" method="post" action="{{route('edit_currencie',[$cur->id])}}" id="comCurrencie">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="panel panel-default box-border-500">
                    <div class="panel-body">
                        <div class="row">
                            <!-- Grid to left -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Currency Name</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="currency_name" name="currency_name" value="{{ $cur->name?$cur->name:old('name') }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Code</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="code" name="code" value="{{ $cur->code?$cur->code:old('code') }}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Symbol</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="symbol" name="symbol" value="{{ $cur->symbol?$cur->symbol:old('symbol') }}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Type</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="type" name="type" value="{{ $cur->type?$cur->type:old('type') }}" placeholder="Sub or Main"/>
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
