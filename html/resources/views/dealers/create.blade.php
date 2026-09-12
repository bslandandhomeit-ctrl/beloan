@extends('layouts.app')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/jquery-multi-select/css/multi-select.css',isset($secure) ? false : false) }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/jquery-tags-input/jquery.tagsinput.css',isset($secure) ? false : false) }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}" />
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                {{ trans('sidebar.sb_add_dealer') }}
            </header>
            <div class="panel-body">
                @if(Session::has('error'))
                <div class="alert alert-danger fade in">
                    <button class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('error') }}
                </div>
                @endif
                @if(Session::has('msg'))
                <div class="alert alert-success fade in">
                    <button class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('msg') }}
                </div>
                @endif

                <form class="cmxform form-horizontal" method="post" action="{{route('add_dealer')}}" id="frmDealer" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_name') }}<span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="dealer" id="dealer">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_representative') }} </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="representative" id="representative" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_phone',['num'=>1]) }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="phone" id="phone" class="form-control" data-mask="999-999-999?9"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_phone',['num'=>2]) }} </label>
                        <div class="col-sm-6">
                            <input type="text" name="phone1" id="phone1" class="form-control" data-mask="999-999-999?9"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_email') }} </label>
                        <div class="col-sm-6">
                            <input type="text" name="email" id="email" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_location') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="location" name="location"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_description') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_bank') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <select multiple="multiple" name="e9[]" id="e9" style="width:100%" class="populate">
                                    <optgroup label="{{ trans('dealer.dl_dealer_account_name') }}" id="opt">
                                        @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->account_name.' ('.$bank->account_number.')'  }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                <span class="input-group-btn" style="vertical-align: top">
                                    <a class="btn btn-info" href="#addBank" data-toggle="modal">
                                        <i class="fa fa-plus"></i>
                                        {{ trans('multiple.m_add') }}
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_photo') }}</label>
                        <div class="col-sm-6">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                    <img src="{{ asset('images/noimage.gif',false) }}" alt="" />
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                <div>
                                    <span class="btn btn-white btn-file">
                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{ trans('multiple.m_select_image') }}</span>
                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                                        <input type="file" class="default" name="photo" id="photo" accept="image/*"/>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3"></label>
                        <div class="col-sm-6">
                            <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_save') }}</button>
                            <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>&nbsp;&nbsp;{{ trans('multiple.m_reset') }}</button>
                        </div>
                        <br/><br/>
                    </div>
                </form>
            </div>
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addBank" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title"> {{ trans('dealer.del_add_new_bank') }}</h4>
                        </div>
                        <div class="modal-body">

                            <form role="form" method="post" id="frmBankAcc" class="cmxform">
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('dealer.dl_dealer_bank_name') }}<span class="red-color">*</span></label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ trans('dealer.dl_dealer_account_name') }}<span class="red-color">*</span> </label>
                                    <input type="text" name="account_name" id="account_name" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ trans('dealer.dl_dealer_account_number') }} <span class="red-color">*</span> </label>
                                    <input type="text" name="account_number" id="account_number" class="form-control"/>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                                <a data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery-multi-select/js/jquery.multi-select.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery-multi-select/js/jquery.quicksearch.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('theme/js/select-init.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
<script>
$(document).ready(function () {
$('#e9').addClass('required');
$('#e9').on('change', function (e) {
    if ((e.val).length > 0) {
        $(this).parent().find('label.error').css({'display': 'none'});
        $(this).parent().find('.error').removeClass('error').addClass('valid');
    }
});
});
</script>
@endsection