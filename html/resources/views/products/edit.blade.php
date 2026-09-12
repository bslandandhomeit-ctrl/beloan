@extends('layouts.app')

@section('content')
<div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    {{ trans('product.p_product_edit') }}
                </header>
                <div class="panel-body">
                    <div class="position-center">
                        @if($errors->has())
                            <div class="alert alert-danger fade in">
                                <button class="close close-sm" type="button" data-dismiss="alert">x</button>
                                {!! HTML::ul($errors->all()) !!}
                            </div>
                        @endif
                        <form role="form" class="cmxform form-horizontal" id="frm-product" method="post" action="{{ route('edit_product',[$pro->id]) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
	                        <div class="form-group">
                                <label for="ipDealer" class="col-lg-3 control-label">{{ trans('dealer.dl_dealer_name') }}</label>
                                <div class="col-lg-7">
                                    <select name="selDealer" class="form-control" <?php echo $pro->is_loan==1?'disabled="disabled"':''?>>
                                        <option value="">-</option>
                                        @foreach($dealers as $d)
                                            <option value="{{ $d->id }}" {{$pro->dealer_id?$pro->dealer_id == $d->id?'selected':'':old('selDealer')}}>{{ $d->dealer }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                            <div class="form-group">
                                <label for="inputProduct" class="col-lg-3 control-label">{{ trans('product.p_product_name') }}</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" id="inputProduct" value="{{ $pro->product_name?$pro->product_name:old('ipName') }}" name="ipName" <?php echo $pro->is_loan==1?'disabled="disabled"':''?> />
                                </div>
                            </div>

                            <!--Products category-->

                            <div class="form-group">
                                <label for="prod_cat" class="col-lg-3 control-label">{{ trans('product.p_product_category') }}<span style="color:red">*</span></label>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <select class="form-control" id="selCate" name="prod_cat">
                                            <option value="">-</option>

                                            @foreach($categories as $cate)
                                                    <option
                                                        <?PHP if($pro->category_id == $cate->id): ?> selected <?PHP endif; ?>
                                                    value="{{$cate->id}}">{{$cate->category_name}}</option>
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
                                <label for="prod_Prod_type" class="col-lg-3 control-label">{{ trans('product.p_productsTypes') }}<span style="color:red">*</span></label>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <select class="form-control" id="seltype" name="prod_Prod_type">
                                            <option value="">-</option>
                                            @foreach($productsProTypes as $prod)
                                                <option
                                                        <?PHP if($prod->id == $pro->product_type_id):?>
                                                            selected
                                                        <?PHP endif; ?>
                                                        value="{{$prod->id}}">{{$prod->type_name}}</option>
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
                            		<input type="text" class="form-control" value="{{ $pro->serial_number?$pro->serial_number:old('ipSerial') }}" id="inputSerial" name="ipSerial" <?php echo $pro->is_loan==1?'disabled="disabled"':''?> />
                            	</div>
                            </div>

                             <div class="form-group">
                                <label for="engine" class="col-lg-3 control-label">{{ trans('product.p_product_engine') }} </label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" value="{{ $pro->engine_number?$pro->engine_number:old('ipEngine') }}" id="engine" name="ipEngine" <?php echo $pro->is_loan==1?'disabled="disabled"':''?> />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ipType" class="col-lg-3 control-label">{{ trans('product.p_product_model') }}</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" value="{{ $pro->product_type?$pro->product_type:old('ipType') }}" id="ipType" name="ipType" <?php echo $pro->is_loan==1?'disabled="disabled"':''?> />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="selBrand" class="col-lg-3 control-label">{{ trans('product.p_product_brand') }}</label>
                                <div class="col-lg-7">
				                            
				    <div class="input-group">
                                        <select class="form-control" id="selBrand" name="selBrand" <?php echo $pro->is_loan==1?'disabled="disabled"':''?>>
			                    <option value="">-</option>
			                        @foreach($brands as $brand)
			                        <option value="{{$brand->id}}"
                                                    @if(!empty($pro->brand) && $brand->id==$pro->brand->id) selected
                                                    @endif
                                                    >{{$brand->brand_name}}</option>
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

                                    <select name="selYear" class="form-control" <?php echo $pro->is_loan==1?'disabled="disabled"':''?>>

                                        <option value="">-</option>
                                        @for($i=date('Y');$i>=1900;$i--)
                                            <option value="{{$i}}" {{$pro->product_year==$i?'selected':''}}>{{$i}}</option>
                                        @endfor
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ipPlateNum" class="col-lg-3 control-label">{{ trans('product.plate_num') }} </label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" value="{{ $pro->plate_num?$pro->plate_num:old('plate_num') }}" id="plate_num" name="plate_num" />
                                </div>
                            </div>
                            <div class="form-group">
                            	<label for="inputPrice" class="col-lg-3 control-label">{{ trans('product.p_product_price') }} <span style="color:red">*</span></label>
                            	<div class="col-lg-7">
                            		<input type="text" class="form-control" value="{{ $pro->product_price?$pro->product_price:old('ipPrice') }}" id="inputPrice" name="ipPrice" <?php echo $pro->is_loan==1?'disabled="disabled"':''?> />
                            	</div>
                            </div>
                            
                            <?php if($pro->is_loan==1){?>
                            <div class="form-group">
                            	<label for="inputPrice" class="col-lg-3 control-label">{{ trans('product.p_product_mou_price') }}</label>
                            	<div class="col-lg-7">
                            		<div class="input-group">
                            			<input type="text" class="form-control" value="{{ $pro->mou_price?$pro->mou_price:old('mou_price') }}" name="mou_price" />
                            			<div class="input-group-addon">%</div>
                            		</div>
                            	</div>
                            </div>
                            <?php }?>
                            
                            <div class="form-group">
                            	<div class="col-lg-offset-3 col-lg-7">
                            		<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{ trans('multiple.m_update') }}</button>
                            		<a href="javascript:history.go(-1)" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
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
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
@endsection