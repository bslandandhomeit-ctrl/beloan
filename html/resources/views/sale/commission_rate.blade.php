@extends('layouts.app')

<style type="text/css">
    .form-horizontal .form-group {
        margin-right: 0px !important;
        margin-left: 0px !important;
    }
    .form-group {
        margin-bottom: 5px !important;
    }
    .modal-backdrop.in {
        z-index: -1 !important;
    }
    .btn-primary-d {
        color: #fff;
        background-color: #007bff !important;
        border-color: #007bff !important;
    }
    img.thumb {
        width: 350px;
    }
    .btn-danger {
        color: #fff !important;
        background-color: #d9534f !important;
        border-color: #d43f3a !important;
    }
</style>

@section('content')
<form class="cmxform form-horizontal" method="post" action="{{route('commission_rate_post')}}">
    <div class="row">
        <div class="col-sm-5">
            <section class="panel">
                <header class="panel-heading">
                    {{ trans('sidebar.sb_commission_rate') }}
                </header>
                <div class="panel-body">
                    @if($errors->count())
                        <div class="alert alert-danger fade in">
                            <button class="close close-sm" data-dismiss="alert">x</button>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Rate <span class="red-color">*</span></label>
                                <input type="text" value="{{ $commissionRate->rate }}" name="rate" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="text-align: right;">
                        <div class="col-sm-12">
                            <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</form>
@endsection