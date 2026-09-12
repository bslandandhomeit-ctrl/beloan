@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',false) }}"/>
@endsection
@section('content')
<section class="panel">
    <div class="panel-heading">
       Add New Currency
    </div>
    <div class="panel-body">
        <div class="col-sm-12">
        <label class="col-md-2"></label>
        <div class="col-md-10">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @elseif(session('msg_success'))
                 <div class="alert alert-success">
                    <ul>
                        <li>{{ session('msg_success') }}</li>
                    </ul>
                 </div>
            @endif
        </div>
        <?php $static = config('static_data'); ?>
        </div>
        <form class="cmxform form-horizontal" method="post" action="{{route('add_currency')}}" id="frmCurrency">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="form-group">
                <label class="col-md-3 control-label">Primary Currency<span class="red-color">*</span></label>
                <div class="col-md-8">
                    <select class="form-control" name="from_currency" id="from_currency">
                        <option value="">-</option>
                        @foreach($static['currency'] as $key=>$value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">Secondary Currency<span class="red-color">*</span></label>
                <div class="col-md-8">
                    <select class="form-control" name="to_currency" id="to_currency">
                        <option value="">-</option>
                        @foreach($static['currency'] as $key=>$value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Effective Date</label>
                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" data-date="{{date('Y-m-d')}}" class="input-append date dpYears col-md-8">
                    <input type="text" name="date" value="{{date('Y-m-d')}}" size="16" class="form-control" id="date">
                        <span class="add-on birhtdateDatepicker">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">Unit<span class="red-color">*</span></label>
                <div class="col-md-8">
                    <input type="text" name="unit" id="unit" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">Last Rate<span class="red-color">*</span></label>
                <div class="col-md-8">
                    <input type="text" name="last_rate" id="last_rate" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">Ask Rate<span class="red-color">*</span></label>
                <div class="col-md-8">
                    <input type="text" name="ask_rate" id="ask_rate" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">Bid Rate<span class="red-color">*</span></label>
                <div class="col-md-8">
                    <input type="text" name="bid_rate" id="bid_rate" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label"></label>
                <div class="col-md-8">
                    <button class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form-validate.js',false) }}"></script>
    <script type="text/javascript">
        $('.date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });
    </script>
@endsection
