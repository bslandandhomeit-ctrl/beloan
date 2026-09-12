@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                {{ trans('company.com_add_tran') }}
            </header>
            <div class="panel-body">
                <form class="cmxform form-horizontal" method="post" action="{{route('add_tran')}}" id="userForm" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Languages <span style="color: red;">*</span></label>
                        <div class="col-md-6">
                            <select class="form-control" id="locale" name="locale">
                                <option value="">-</option>
                                @foreach($locale as $lo)
                                <option value="{{ $lo->id }}"
                                        @if(isset($lo_id))
                                        @if($lo_id==$lo->id)
                                        selected
                                        @endif
                                        @endif>{{ $lo->locale}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ trans('company.com_key_word') }} <span style="color: red;">*</span></label>
                        <div class="col-md-6">
                            <input type="text" name="key"  class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ trans('company.com_text') }} <span style="color: red;">*</span></label>
                        <div class="col-md-6">
                            <input type="text" name="text"  class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ trans('company.group') }} <span style="color: red;">*</span></label>
                        <div class="col-md-6">
                            <input type="text" name="group"  class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3"></label>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">{{ trans('multiple.m_save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection