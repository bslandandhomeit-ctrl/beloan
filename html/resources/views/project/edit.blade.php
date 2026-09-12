@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/jquery-multi-select/css/multi-select.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/jquery-tags-input/jquery.tagsinput.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
@endsection
<style type="text/css">
    .form-horizontal .form-group {
        margin-right: 0px !important;
        margin-left: 0px !important;
    }
    .form-group {
        margin-bottom: 5px !important;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    {{ trans('dealer.edit_project') }} : {{ $dealer->dealer }}
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

                    <form class="cmxform form-horizontal" method="post" action="{{route('edit_project',[$dealer->id])}}" id="frmDealer"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('company.company') }} <span
                                                class="red-color">*</span></label>
                                    <select class="form-control" name="company_id">
                                        @foreach($company as $compan)
                                            <option value="{{ $compan->id }}" {{ $compan->id?$compan->id == $dealer->company_id?'selected':'':old('dealer') }}>{{ $compan->branch_name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="panel-body" style="border: 1px solid #dddddd; border-radius: 5px;">
                                    <div id="exTab2">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-target="#khmer" data-toggle="tab"> Khmer <span> <img src="{{ asset('images/flags/kh.gif') }}"></span></a></li>
                                            <li><a data-target="#english" data-toggle="tab">English <img src="{{ asset('images/flags/en.gif') }}"></a></li>
                                            <li><a data-target="#chinese" data-toggle="tab">Chinese <img src="{{ asset('images/flags/cn.gif') }}"></a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="khmer">
                                                <br/>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('dealer.name') }}<span class="red-color"> *</span></label>
                                                    <input type="text" value="{{ $dealer->dealer }}" id="dealer" name="dealer" class="form-control" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('dealer.address') }}</label>
                                                    <input type="text" value="{{ $dealer->address }}" id="address" name="address" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="english">
                                                <br/>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('dealer.name') }}<span class="red-color"> *</span></label>
                                                    <input type="text" value="{{ $dealer->dealer_en }}" id="dealer_en" name="dealer_en" class="form-control"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('dealer.address') }}</label>
                                                    <input type="text" value="{{ $dealer->address_en }}" id="address_en" name="address_en" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="chinese">
                                                <br/>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('dealer.name') }}<span class="red-color"> *</span></label>
                                                    <input type="text" value="{{ $dealer->dealer_cn }}" id="dealer_cn" name="dealer_cn" class="form-control"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('dealer.address') }}</label>
                                                    <input type="text" value="{{ $dealer->address_cn }}" id="address_cn" name="address_cn" class="form-control"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('dealer.short_code') }}<span class="red-color"> *</span></label>
                                    <input type="text" value="{{ $dealer->short_code }}" id="short_code" name="short_code" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('dealer.sale_representative') }}<span class="red-color"> *</span></label>
                                    <select name="sale_representative_id" id="sale_representative_id" style="width:100%" class="select2">
                                        @foreach($sale as $sales)
                                            <option value="{{ $sales->id }}" {{$sales->id?$sales->id == $dealer->sale_representative_id?'selected':'':old('dealer')}}>{{ $sales->name.'-'.$sales->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">{{ trans('dealer.bank_account') }} <span
                                    class="red-color">*</span></label>
                                <div class="input-group">
                                    <select name="bank_account" id="e9" style="width:100%" class="populate">
                                        <optgroup label="{{ trans('dealer.bank_account') }}" id="opt">
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}" {{$bank->id?$bank->id == $dealer->bank_account?'selected':'':old('dealer')}}>{{ $bank->account_name.' ('.$bank->account_number.')'  }}</option>
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
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('dealer.dynamic_code') }}<span class="red-color"> *</span></label>
                                    <input type="text" value="{{ $dealer->dynamic_code }}" id="dynamic_code" name="dynamic_code" class="form-control" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="flat-green single-row icheck group">
                                    <div class="radio">
                                        <input type="checkbox" name="published" @if($dealer->published == true) checked @endif>
                                    </div>
                                    <label class="control-label col">{{ trans('dealer.published') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div style="width: 180px; height: 150px;">
                                    <?php
                                        $logo = '';
                                        if($dealer->logo != ''){
                                            $logo = asset('data/projects/'.$dealer->logo,false);
                                        }else{
                                            $logo = asset('images/noimage.gif',false);
                                        }
                                    ?>
                                    <img src="{{ $logo }}" alt="" id="logo" style="width: 180px; height: 150px;object-fit: cover;border: 1px solid #ddd;"/>
                                    <input type="file" onchange="reload_image_input(event);" class="default" name="logo" accept="image/*" style="position:absolute; width:180px; height:150px; top:0; left:0; opacity:0;cursor: pointer;"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="text-align: right;">
                                <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_update') }}
                                </button>
                                <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>&nbsp;&nbsp;{{ trans('multiple.m_reset') }}
                                </button>
                            </div>
                            <br/><br/>
                        </div>
                    </form>
                </div>
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addBank"
                     class="modal fade">
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
                                        <label class="control-label">{{ trans('dealer.dl_dealer_bank_name') }}<span
                                                    class="red-color">*</span></label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">{{ trans('dealer.dl_dealer_account_name') }}<span
                                                    class="red-color">*</span> </label>
                                        <input type="text" name="account_name" id="account_name" class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">{{ trans('dealer.dl_dealer_account_number') }}
                                            <span class="red-color">*</span> </label>
                                        <input type="text" name="account_number" id="account_number"
                                               class="form-control"/>
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i
                                                class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                                    <a data-dismiss="modal" class="btn btn-danger"><i
                                                class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
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
<script type="text/javascript"
        src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript"
        src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript"
        src="{{ asset('theme/js/jquery-multi-select/js/jquery.multi-select.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript"
        src="{{ asset('theme/js/jquery-multi-select/js/jquery.quicksearch.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('theme/js/select-init.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript"
        src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
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
        $('#sale_representative_id').addClass('required');
        $("#sale_representative_id").select2();
        $('#sale_representative_id').on('change', function (e) {
            if ((e.val).length > 0) {
                $(this).parent().find('label.error').css({'display': 'none'});
                $(this).parent().find('.error').removeClass('error').addClass('valid');
            }
        });
    });
    function reload_image_input(event){
        var selectedFile = event.target.files[0];
        var reader = new FileReader();
        var img_id = 'logo';
        var imgtag = document.getElementById(img_id);
        imgtag.title = selectedFile.name;
        reader.onload = function(event) {
            imgtag.src = event.target.result;
        };
        reader.readAsDataURL(selectedFile);
    }
</script>
@endsection