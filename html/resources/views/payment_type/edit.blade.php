@extends('layouts.app')

@section('content')
<section class="panel panel-box-700">
    @if($errors->any())
    <p class="alert alert-danger">{{ $errors->first() }}</p>
    @endif
    <header class="panel-heading">
        {{ trans('sidebar.sb_payment_type') }}
    </header>
    <div class="panel-body">
        <form class="cmxform form-horizontal" method="post" action="{{ route('edit_payment_type',[$payment_type->id]) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="panel panel-default box-border-500">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Name</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name" value="{{ old('name', $payment_type->name) }}"/>
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
