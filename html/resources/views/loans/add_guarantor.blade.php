@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false)}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="panel">
        @if(Session::has('message'))
            <div class="alert alert-success alert-block fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button">
                                    <i class="fa fa-times"></i>
                                </button>
                                <p>{{ Session::get('message') }}</p>
                            </div>
        @endif
        <?php $static = Config::get('static_data');?>
        <header class="panel-heading">
            {{ trans('loan.l_add_new_guarantor') }}
        </header>

        <div class="panel-body">
            <form class="cmxform form-horizontal" method="post" action="{{ route('add_guarantor', [$id]) }}" id="frmGuarantor" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <fieldset>
                    <div class="row">
                        <!-- Grid to left -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-2">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                            <img src="{{ asset('images/noimage.gif', isset($secure) ? false : false) }}" alt="" />
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                        <div>
                                           <span class="btn btn-white btn-file">
                                           <span class="fileupload-new"><i class="fa fa-paper-clip"></i>{{ trans('multiple.m_select_image') }}</span>
                                           <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                                           <input type="file" name="photo" id="photo" class="default" />
                                           </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{ trans('loan.l_relationship') }} <span class="red">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control" name="relationship" id="relationship_type">
                                        <option value="">-</option>
                                        @foreach($static['relationship_type'] as $key => $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('loan.l_guarantor_name') }}<span class="red"> *</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('user.u_user_kh_name') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="kh_name" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('multiple.m_gender') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="gender">
                                        <option value="">-</option>
                                        @foreach($static['gender'] as $key => $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_nationality') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="nationality" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_birth_date') }}</label>
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="input-append date dpYears col-md-8">
                                    <input type="text" name="birth_date" size="16" class="form-control">
                                        <span class="add-on birhtdateDatepicker">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                      </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_birth_place') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="birth_place" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('multiple.m_phone',['num'=>1]) }}<span class="red"> *</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="phone1" id="phone1" class="form-control" data-mask="999-999-999?9" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('multiple.m_phone',['num'=>2]) }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="phone2" class="form-control" data-mask="999-999-999?9"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('multiple.m_address') }}<span class="red"> *</span></label>
                                <div class="col-md-8">
                                    <textarea name="address" id="address" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Grid to right  -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-2">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                            <img src="{{ asset('images/noimage.gif', isset($secure) ? false : false) }}" alt="" />
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                        <div>
                                           <span class="btn btn-white btn-file">
                                           <span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{ trans('multiple.m_signature') }}</span>
                                           <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                                           <input type="file" name="signature" id="photo" class="default" />
                                           </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_latitude') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="location_latitude" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_longitude') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="location_longitude" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_job') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="job" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_work_place') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="work_place" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_card_number') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="card_number" id="card_number" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_issued_date') }}</label>
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="input-append date dpYears col-md-8">
                                    <input type="text" name="card_date" size="16" class="form-control">
                                        <span class="add-on birhtdateDatepicker">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_issued_by') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="card_issued_by" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_card_expired_date') }}</label>
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="input-append date dpYears col-md-8">
                                    <input type="text" name="card_expired_date" size="16" class="form-control">
                                        <span class="add-on birhtdateDatepicker">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_letter_type') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="letter_type">
                                        <option value="">-</option>
                                         @foreach($static['letter_type'] as $key => $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_letter_no') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="letter_no" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_expired_date') }}</label>
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="input-append date dpYears col-md-8">
                                    <input type="text" name="expired_date" size="16" class="form-control">
                                        <span class="add-on birhtdateDatepicker">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="addGuarantorCollateral" class="row"></div>
                    <div class="col-sm-6">
                        {{--<div class="form-group">--}}
                            {{--<div class="col-sm-3"></div>--}}
                            {{--<button type="button" id="addBtn" class="btn btn-info"><i class="fa fa-plus"></i> {{ trans('loan.l_add_collateral') }}</button>--}}
                        {{--</div>--}}
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <button type="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                            <button type="button" class="btn btn-danger" onclick="javascript:history.back()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;{{ trans('multiple.m_cancel') }}</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
            var getStatic = <?php echo json_encode(array('collateral_type'=> $static['collateral_type'],'collateral_regis_type'=>$static['collateral_regis_type']));?>;
            var i = 0;
    </script>
    <script type="text/javascript" src="{{ asset('js/guarantor-collateral.js',isset($secure) ? false : false) }}"></script>
@endsection
