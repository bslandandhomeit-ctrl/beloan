@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
@endsection
@section('content')
<div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <?php
                    $action_type = config('static_data.action_type');
                ?>
                <header class="panel-heading">
                    {{ trans('product.p_product_update') }}
                </header>
                <div class="panel-body">
                    <div class="position-center">
                        @if($errors->has())
                            <div class="alert alert-danger fade in">
                                <button class="close close-sm" type="button" data-dismiss="alert">x</button>
                                {!! HTML::ul($errors->all()) !!}
                            </div>
                        @endif
                        <form role="form" class="cmxform form-horizontal" id="frm-product" method="post" action="{{ route('update_product',[$pro->id]) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="ipProduct" class="col-lg-4 control-label">{{ trans('product.p_product_id') }}</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" id="inputProduct" value="{{ $pro->id?$pro->id:old('ipProduct') }}" readonly name="ipProduct" />
                                        </div>
                                    </div>
                                </div>
                            <input type="hidden" value="{{$pro->id}}" name="pro_id">
                                <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="ipType" class="col-lg-5 control-label"> {{ trans('product.p_product_model') }}</label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control" value="{{ $pro->product_type?$pro->product_type:old('ipType') }}" id="ipType" name="ipType" readonly/>
                                            </div>
                                        </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="ipName" class="col-lg-4 control-label">{{ trans('product.p_product_name') }}</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" id="inputProduct" value="{{ $pro->product_name?$pro->product_name:old('ipName') }}" name="ipName" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="inputSerial" class="col-lg-5 control-label">{{ trans('product.p_product_serial') }}</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" value="{{ $pro->serial_number?$pro->serial_number:old('ipSerial') }}" id="inputSerial" name="ipSerial" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="selBrand" class="col-lg-4 control-label">{{ trans('product.p_product_brand') }}</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" id="selBrand" value="{{ $pro->brand->brand_name?$pro->brand->brand_name:old('brand_name') }}" name="brand_name" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="ipEngine" class="col-lg-5 control-label">{{ trans('product.p_product_engine') }}</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" value="{{ $pro->engine_number?$pro->engine_number:old('ipEngine') }}" id="engine" name="ipEngine" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="inputProduct" class="col-lg-4 control-label">{{ trans('product.p_type') }}</label>
                                        <div class="col-lg-7">
                                            <select class="form-control" name="action_type">
                                                    <option value="">-</option>
                                                    @foreach($action_type as $key=>$value)
                                                        @if(isset($paction_type) && !in_array($value,$paction_type))
                                                             <option value="{{$value}}">{{$value}}</option>
                                                        @endif
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="inputProduct" class="col-lg-5 control-label">{{ trans('product.p_location') }}</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" id="inputProduct" value="" name="location" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="inputProduct" class="col-lg-4 control-label">{{ trans('product.p_date') }}</label>
                                        <div class="col-lg-7">
                                            <div id="start-date" data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd" data-date="" class="input-append date" style="width: 95%;">
                                                <input type="text" name="return_date" size="16"  class="form-control"/>
                                                <span class="input-group-btn add-on">
                                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="inputProduct" class="col-lg-5 control-label">{{ trans('product.p_price') }}</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" id="inputProduct" value="" name="resale_price" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="inputProduct" class="col-lg-2 control-label">{{ trans('product.p_remark') }}</label>
                                        <div class="col-lg-10">
                                            <textarea placeholder="Message" rows="13" class="form-control" name="remark" value=""></textarea>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-7">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{ trans('multiple.m_update') }}</button>
                                            <a href="javascript:history.go(-1)" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    @include('products.add_category')
                    @include('products.add_brand')
                </div>
            </section>
        </div>
 	</div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script>
        $('#start-date').datepicker({
               autoclose: true
            });
    </script>
@endsection
