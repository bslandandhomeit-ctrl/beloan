@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}" />
    <link rel="stylesheet" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}">
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
           {{ trans('loan.l_edit_collateral') }}
        </header>
        <?php
            $static = Config::get('static_data');
        ?>
        <div class="panel-body">
             @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
             @endif
            <form class="cmxform form-horizontal" method="post" action="{{ route('edit_collateral', [$edits->id]) }}" id="addCollateralForm" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_collateral_type') }} <span class="red-color">*</span></label>
                   
                    <div class="col-sm-8">
                        <select name="collateral_type" id="collateral_type" class="form-control" required>
                            <option value="">-</option>
                            @foreach($static['collateral_type'] as $key => $value)
                                <option value="{{$key}}" {{$value == $edits->collateral_type ? 'selected' : ''}}>{{$value}} ({{$key}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_collateral_number') }} <span class="red-color">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="collateral_no" value="{{$edits->collateral_no}}" id="collateral_no" class="form-control" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_collateral_value') }} <span class="red-color">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="collateral_value" value="{{$edits->collateral_value}}" id="collateral_value" class="form-control" required/>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_registration_type') }} <span class="red-color">*</span></label>
                    <div class="col-sm-8">
                        <select name="collateral_registration" id="collateral_registration" class="form-control" required>
                            <option value="">-</option>
                            @foreach($static['collateral_regis_type'] as $key => $value)
                                <option value="{{$value}}" {{$value == $edits->collateral_registration ? 'selected' : ''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
               <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_collateral_address') }} <span class="red-color">*</span></label>
                    <div class="col-sm-8">
                        <textarea name="collateral_address" id="collateral_address" class="form-control">{{$edits->collateral_address}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('multiple.m_note') }}</label>
                    <div class="col-sm-8">
                        <textarea name="note" id="note" class="form-control">{{$edits->note}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('multiple.m_photo') }}</label>
                    <div class="col-sm-8">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                               @if(!empty($edits->collateral_photo))
		                        <?php
		                            $ext = explode('.',$edits->collateral_photo);
		                            $ext_file = '';
		                            if(!empty($ext)){
		                                $ext_file = $ext[count($ext) -1];
		                            }

		                        ?>
		                        @if(strtolower($ext_file) == 'png' || strtolower($ext_file) == 'jpg' || strtolower($ext_file) == 'jpeg' )
		                            <a href="#" class="photo-popup" data-mfp-src="{{ asset('data/loans/collateral/'.$edits->collateral_photo, isset($secure) ? false : false) }}">
		                                <img src="{{ asset('data/loans/collateral/'.$edits->collateral_photo, isset($secure) ? false : false) }}" class="small-imag listPhoto"/>
		                            </a>
		                        @else
		                            <a href="{{ asset('data/loans/collateral/'.$edits->collateral_photo, isset($secure) ? false : false) }}">
		                               <span class="text-info">View</span>
		                           </a>
		                        @endif

			                    @else
			                        -
			                    @endif
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                            <div>
                               <span class="btn btn-white btn-file">
                                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{ trans('multiple.m_select_image') }}</span>
                                   <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                                   <input type="file" class="default" name="photo" id="photo"/>
                               </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"></label>
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{ trans('multiple.m_update') }}</button>
                        <button type="button" class="btn btn-danger" onclick="javascript:history.back()"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
@endsection
