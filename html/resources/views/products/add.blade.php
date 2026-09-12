@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   {{ trans('sidebar.sb_add_product') }}
                </header>
                <div class="panel-body">
                    <div class="position-center">
                        @if($errors->has())
                            <div class="alert alert-danger fade in">
                                <button class="close close-sm" type="button" data-dismiss="alert">x</button>
                                {!! HTML::ul($errors->all()) !!}
                            </div>
                        @endif
                        <form role="form" class="cmxform form-horizontal" id="frm-product" method="post" action="{{ route('add_product',[],false) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
	                        <div class="form-group">
                                <label for="ipDealer" class="col-lg-3 control-label">{{ trans('dealer.dl_dealer_name') }}</label>
                                <div class="col-lg-7">
                                    <select name="selDealer" class="form-control">
                                        <option value="">-</option>
                                        @foreach($dealers as $d)
                                            <option value="{{ $d->id }}">{{ $d->dealer }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputProduct" class="col-lg-3 control-label">{{ trans('product.p_product_name') }}</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" id="inputProduct" value="{{ old('ipName') }}" name="ipName" />
                                </div>
                            </div>

                            <!-- Produts category -->

                            <div class="form-group">
                                <label for="selCate" class="col-lg-3 control-label">{{ trans('product.p_product_category') }}<span style="color:red">*</span></label>
                                <div class="col-lg-7">
				                    <div class="input-group">
                                        <select class="form-control" id="selCate" name="prod_cat">
                                         	<option value="">-</option>
			                                @foreach($categories as $cate)
			                                	<option value="{{$cate->id}}">{{$cate->category_name}}</option>
			                                @endforeach
			                            </select>
                                          <span class="input-group-btn" style="padding-left:5px;vertical-align:top">
                                            <a class="btn btn-info" href="#mCate" data-toggle="modal">
                                            	<i class="fa fa-plus"></i>
                                            	{{ trans('multiple.m_add') }}
                                            </a>
                                          </span>
                            		</div>
                                </div>
                            </div>


                            <!--Products Type -->

                            <div class="form-group">
                                <label for="selCate" class="col-lg-3 control-label">{{ trans('product.p_productsTypes') }}<span style="color:red">*</span></label>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <select class="form-control" id="seltype" name="prod_Prod_type">
                                            <option value="">-</option>
                                            @foreach($productsProTypes as $prod)
                                                <option value="{{$prod->id}}">{{$prod->code}}-{{$prod->products_type_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-btn" style="padding-left:5px;vertical-align:top">
                                            <a class="btn btn-info" href="#prod_prod_type" data-toggle="modal">
                                                <i class="fa fa-plus"></i>
                                                {{ trans('multiple.m_add') }}
                                                    </a>
                                          </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                            	<label for="inputSerial" class="col-lg-3 control-label">{{ trans('product.p_product_serial') }}</label>
                            	<div class="col-lg-7">
                            		<input type="text" class="form-control" value="{{ old('ipSerial') }}" id="inputSerial" name="ipSerial" />
                            	</div>
                            </div>
                            <div class="form-group">
                                <label for="engine" class="col-lg-3 control-label">{{ trans('product.p_product_engine') }} </label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" value="{{ old('ipEngine') }}" id="engine" name="ipEngine" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ipType" class="col-lg-3 control-label">{{ trans('product.p_product_model') }} </label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" value="{{ old('ipType') }}" id="ipType" name="ipType" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="selBrand" class="col-lg-3 control-label">{{ trans('product.p_product_brand') }} </label>
                                <div class="col-lg-7">

				                    <div class="input-group">
                                        <select class="form-control" id="selBrand" name="selBrand">
			                                <option value="">-</option>
			                                    @foreach($brands as $brand)
			                                	    <option value="{{$brand->id}}">{{$brand->brand_name}}</option>
			                                    @endforeach

			                            </select>
                                          <span class="input-group-btn" style="padding-left:5px;vertical-align:top">
                                            <a class="btn btn-info" href="#mBrand" data-toggle="modal">
                                            	<i class="fa fa-plus"></i>
                                            	{{ trans('multiple.m_add') }}
                                            </a>
                                          </span>

                            		</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputSerial" class="col-lg-3 control-label">{{ trans('product.p_product_year') }}</label>
                                <div class="col-lg-7">
                                    <select name="selYear" class="form-control">
                                        <option value="">-</option>
                                        @for($i=date('Y');$i>=1900;$i--)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ipPlateNum" class="col-lg-3 control-label">{{ trans('product.plate_num') }} </label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" value="{{ old('plate_num') }}" id="plate_num" name="plate_num" />
                                </div>
                            </div>

                            <div class="form-group">
                            	<label for="inputPrice" class="col-lg-3 control-label">{{ trans('product.p_product_price') }} <span style="color:red">*</span></label>
                            	<div class="col-lg-7">
                            		<input type="text" class="form-control" value="{{ old('ipPrice') }}" id="inputPrice" name="ipPrice" />
                            	</div>
                            </div>
                            <div class="form-group">
                            	<div class="col-lg-offset-3 col-lg-7">
                            		<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                            		<button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>  {{ trans('multiple.m_reset') }}</button>
                            	</div>
                            </div>
                    	</form>
                    </div>
                    @include('products.add_category')
                    @include('products.add_prod_type')
                    @include('products.add_brand')
                </div>
            </section>
        </div>
 	</div>

@endsection

@section('js')
    <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
@endsection
