@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure)?false:false)}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure)?false:false)}}" />
    <link href="{{ asset('css/client.css',isset($secure)?false:false) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="panel">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
        @endif
        <?php
            $branch = config('static_data.branch');
            $client_type = config('static_data.client_type');
            $letter_type = config('static_data.letter_type');
            $gender = config('static_data.gender');
        ?>
        <header class="panel-heading">
           {{ trans('customer.cus_edit_customer') }}
        </header>

        @if(!empty($editClient))

        <div class="panel-body">
            @if(Session::has('error'))
                <div class="alert alert-danger fad in">
                    <button type="button" class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('error') }}
                </div>
            @endif
            <form action="{{route('edit_client', [$editClient->id])}}" method="POST" class="cmxform form-horizontal" id="clientForm" enctype="multipart/form-data">
                <fieldset>
                    <div class="row">
                        <!-- Grid to left -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-2">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                            <img src="{{ $editClient->photo?asset('data/clients/'.$editClient->photo,true):asset('images/noimage.gif',true) }}" alt="" />
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                        <div>
                                           <span class="btn btn-white btn-file">
                                           <span class="fileupload-new"><i class="fa fa-paper-clip"></i>{{ trans('multiple.m_select_image') }}e</span>
                                           <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                                           <input type="file" name="photo" id="photo" class="default" />
                                           </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_type') }}<span class="red"> *</span></label>
                                <div class="col-md-8">
                                    <select class="form-control" name="client_type" id="client_type">
                                        <option>-</option>
                                        @foreach($client_type as $key => $value)
                                            <option value="{{ $value }}" {{$editClient->client_type ? $editClient->client_type == $value ? 'selected' : '' : old('client_type')}}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_customer_name') }}<span class="red"> *</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="client_name" id="client_name" value="{{ $editClient->client_name }}"  class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('user.u_user_kh_name') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="client_khmer_name" value="{{$editClient->client_khmer_name}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('multiple.m_gender') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="gender">
                                        <option value="">-</option>
                                        @foreach($gender as $key=>$value)
                                            <option value="{{$value}}" {{$editClient->gender ? $editClient->gender == $value ? 'selected' : '' : old('gender')}}>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_nationality') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="nationality" value="{{$editClient->nationality}}"  class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_birth_date') }}</label>
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="input-append date dpYears col-md-8">
                                    <input type="text" name="birth_date"  value="{{$editClient->birth_date}}" size="16" class="form-control">
                                        <span class="add-on birhtdateDatepicker">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                      </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_birth_place') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="birth_place" value="{{$editClient->birth_place}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('multiple.m_phone',['num'=>1]) }}<span class="red"> *</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="phone1" value="{{$editClient->phone1}}"  id="phone1" class="form-control" data-mask="999-999-999?9">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('multiple.m_phone',['num'=>2]) }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="phone2" value="{{$editClient->phone2}}"  class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_city_province') }}<span class="red"> *</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="city" id="address-city">
                                        <option value="0">-</option>
                                        @foreach($branch as $key => $value)
                                            <option value="{{ $value }}" {{$editClient->city == $value ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('multiple.m_address') }}<span class="red"> *</span></label>
                                <div class="col-md-8">
                                    <textarea name="address" id="address" class="form-control">{{$editClient->address}}</textarea>
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
                                            <img src="{{ $editClient->signature?asset('data/signatures/'.$editClient->signature,true):asset('images/noimage.gif',true) }}" alt="" />
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                        <div>
                                           <span class="btn btn-white btn-file">
                                           <span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{ trans('multiple.m_signature') }}</span>
                                           <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                                           <input type="file" name="signature" id="signature" class="default" />
                                           </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_latitude') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="location_latitude" value="{{$editClient->location_latitude}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_longitude') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="location_longitude" value="{{$editClient->location_longitude}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_job') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="job" value="{{$editClient->job}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_work_place') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="work_place" value="{{$editClient->work_place}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_card_number') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="card_number" value="{{$editClient->card_number}}" id="card_number" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_issued_date') }}</label>
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="input-append date dpYears col-md-8">
                                    <input type="text" name="card_date" value="{{$editClient->card_date}}" size="16" class="form-control">
                                        <span class="add-on birhtdateDatepicker">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_issued_by') }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="card_issued_by" value="{{$editClient->card_issued_by}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_card_expired_date') }}</label>
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="input-append date dpYears col-md-8">
                                    <input type="text" name="card_expired_date" value="{{$editClient->card_expired_date}}" size="16" class="form-control">
                                        <span class="add-on birhtdateDatepicker">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">{{ trans('customer.cus_letter_type') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="letter_type">
                                        <option> - </option>
                                        @foreach($letter_type as $key => $value)
                                            <option value="{{ $value }}" {{$editClient->letter_type ? $editClient->letter_type == $value ? 'selected' : '' : old('letter_type')}}>{{ $value }}</option>
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
                                    <input type="text" name="expired_date" value="{{$editClient->expired_date}}" size="16" class="form-control">
                                        <span class="add-on birhtdateDatepicker">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>&nbsp;{{ trans('multiple.m_update') }}</button>
                        <a href="javascript:history.go(-1)" class="btn btn-danger"><i class="fa fa-times-circle"></i>&nbsp;{{ trans('multiple.m_cancel') }}</a>
                    </div>
                </div>
                </fieldset>
            </form>
        </div>
        @endif
    </section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure)?false:false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure)?false:false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure)?false:false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure)?false:false) }}"></script>
<script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure)?false:false) }}"></script>
<script type="text/javascript">
    $('.dpYears').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        setDate: new Date()
    });
</script>
@endsection
