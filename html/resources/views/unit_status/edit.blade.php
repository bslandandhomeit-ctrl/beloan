@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/jquery-multi-select/css/multi-select.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/jquery-tags-input/jquery.tagsinput.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
    <link href="{{ asset('summernote-0.8.18-dist/summernote-lite.css') }}" rel="stylesheet">
    <link href="{{ asset('summernote-0.8.18-dist/summernote-lite.min.css') }}" rel="stylesheet">
@endsection
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
    <div class="row">
        <form class="cmxform form-horizontal" method="post" action="{{route('edit_unit_type',[$unit_type->id])}}" id="frmUnittype" enctype="multipart/form-data">
            <div class="col-sm-9">
                <section class="panel">
                    <header class="panel-heading">
                        {{ trans('unit_type.edit_unit_type') }} : {{ $unit_type->name }}
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
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit_type.project') }} <span
                                                class="red-color">*</span></label>
                                    <select class="form-control" name="project_id" required>
                                        @foreach($project as $projects)
                                            <option value="{{ $projects->id }}" {{ $projects->id?$projects->id == $unit_type->project_id?'selected':'':old('dealer') }}>{{ $projects->dealer}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel-body" style="border: 1px solid #dddddd; border-radius: 5px;">
                                    <div id="exTab2">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-target="#khmer1" data-toggle="tab"> Khmer <span> <img src="{{ asset('images/flags/kh.gif') }}"></span></a></li>
                                            <li><a data-target="#english1" data-toggle="tab">English <img src="{{ asset('images/flags/en.gif') }}"></a></li>
                                            <li><a data-target="#chinese1" data-toggle="tab">Chinese <img src="{{ asset('images/flags/cn.gif') }}"></a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="khmer1">
                                                <br/>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('dealer.name') }} <span class="red-color">*</span></label>
                                                    <input type="text" value="{{ $unit_type->name }}" class="form-control" name="name" required>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="english1">
                                                <br/>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('dealer.name') }}</label>
                                                    <input type="text" value="{{ $unit_type->name_en }}" class="form-control" name="name_en">
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="chinese1">
                                                <br/>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('dealer.name') }}</label>
                                                    <input type="text" value="{{ $unit_type->name_cn }}" class="form-control" name="name_cn">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('dealer.short_code') }} <span class="red-color">*</span></label>
                                    <input type="text" value="{{ $unit_type->short_code }}" name="short_code" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit_type.contract_template') }} <span class="red-color">*</span></label>
                                    <select class="form-control" name="contract_template_id" required>
                                        @foreach($contract_template as $key => $contract_templates)
                                            <option value="{{ $key }}" {{ $key?$key == $unit_type->contract_template_id?'selected':'':old('unit_type') }}>{{ $contract_templates }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="flat-green single-row icheck group">
                                    <div class="radio">
                                        <input type="checkbox" name="contractable" @if($unit_type->is_contractable == true) checked @endif>
                                    </div>
                                    <label class="control-label col">{{ trans('unit_type.contractable') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit_type.annual_management_fee') }}</label>
                                    <input type="number" value="{{ $unit_type->annual_management_fee }}" name="annual_management_fee" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit_type.contract_transfer_fee') }}</label>
                                    <input type="number" value="{{ $unit_type->contract_transfer_fee }}" name="contract_transfer_fee" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit_type.mgt_fee_per_square') }}</label>
                                    <input type="number" value="{{ $unit_type->management_fee_per_square }}" name="management_fee_per_square" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit_type.deadline') }}</label>
                                    <input type="text" value="{{ $unit_type->deadline }}" name="deadline" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit_type.extended_deadline') }}</label>
                                    <input type="text" value="{{ $unit_type->extended_deadline }}" name="extended_deadline" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit_type.payment_option_image') }}</label>
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                            <?php
                                                $payment_option_image = '';
                                                if($unit_type->payment_option_image_url){
                                                    if(file_exists('data/unit_type/payment_option_image/'.$unit_type->payment_option_image_url)){
                                                        $payment_option_image = asset('data/unit_type/payment_option_image/'.$unit_type->payment_option_image_url);
                                                    }else{
                                                        $payment_option_image = asset('images/noimage.gif');
                                                    }
                                                }else{
                                                    $payment_option_image = asset('images/noimage.gif');
                                                }
                                            ?>
                                            <img src="{{ $payment_option_image }}" alt="" />
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail"></div>
                                        <div>
                                            <span class="btn btn-white btn-file">
                                                <span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{ trans('multiple.m_select_image') }}</span>
                                                <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                                                <input type="file" class="default" name="payment_option_image" id="payment_option_image" accept="image/*"/>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit_type.feature_image') }}</label>
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                            <?php
                                                $feature_image = '';
                                                if($unit_type->feature_image_url){
                                                    if(file_exists('data/unit_type/feature_image/'.$unit_type->feature_image_url)){
                                                        $feature_image = asset('data/unit_type/feature_image/'.$unit_type->feature_image_url);
                                                    }else{
                                                        $feature_image = asset('images/noimage.gif');
                                                    }
                                                }else{
                                                    $feature_image = asset('images/noimage.gif');
                                                }
                                            ?>
                                            <img src="{{ $feature_image }}" alt="" />
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail"></div>
                                        <div>
                                            <span class="btn btn-white btn-file">
                                                <span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{ trans('multiple.m_select_image') }}</span>
                                                <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                                                <input type="file" class="default" name="feature_image" id="feature_image" accept="image/*"/>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                                                    <label class="control-label">{{ trans('unit_type.title_clause') }}</label>
                                                    <textarea class="form-control" name="title_clause_kh" cols="4" rows="4">{{ $unit_type->title_clause_kh }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('unit_type.mgt_service_clause') }}</label>
                                                    <textarea class="form-control" name="management_service_kh" cols="4" rows="4">{{ $unit_type->management_service_kh }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('unit_type.equipment_clause') }}</label>
                                                    <textarea class="form-control equipment_clause" name="equipment_text" id="equipment_clause" rows="8">{!! $unit_type->equipment_text !!}</textarea>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="english">
                                                <br/>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('unit_type.title_clause') }}</label>
                                                    <textarea class="form-control" name="title_clause_en" cols="4" rows="4">{{ $unit_type->title_clause_en }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('unit_type.mgt_service_clause') }}</label>
                                                    <textarea class="form-control" name="management_service_en" cols="4" rows="4">{{ $unit_type->management_service_en }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('unit_type.equipment_clause') }}</label>
                                                    <textarea class="form-control equipment_clause" name="equipment_text_en" id="equipment_clause" rows="8">{!! $unit_type->equipment_text_en !!}</textarea>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="chinese">
                                                <br/>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('unit_type.title_clause') }}</label>
                                                    <textarea class="form-control" name="title_clause_cn" cols="4" rows="4">{{ $unit_type->title_clause_cn }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('unit_type.mgt_service_clause') }}</label>
                                                    <textarea class="form-control" name="management_service_cn" cols="4" rows="4">{{ $unit_type->management_service_cn }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('unit_type.equipment_clause') }}</label>
                                                    <textarea class="form-control equipment_clause" name="equipment_text_cn" id="equipment_clause" rows="8">{!! $unit_type->equipment_text_cn !!}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12" style="text-align: right;">
                                <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_save') }}
                                </button>
                                <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>&nbsp;&nbsp;{{ trans('multiple.m_reset') }}
                                </button>
                            </div>
                            <br/><br/>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-sm-3">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="row">
                            <div class="col-sm-6">
                                {{ trans('unit_type.floor_plan') }}
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-primary-d btn-sm pull-right btn_add_floor_plan"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="fileupload fileupload-new">
                                        <div class="row" id="form_upload_floor_plan">
                                            @foreach($img_floor_plan as $key => $val)
                                                <div class="col-sm-6 floor_plan" style="padding: 10px;">
                                                    <button type="button" class="btn btn-danger btn_remove" style="position: absolute;right: 0;z-index: 2;" data-id="{{ $val->id }}">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                    <div style="width: 180px; height: 150px;">
                                                        <?php
                                                            $floor_plan = '';
                                                            if($val->url){
                                                                if(file_exists('data/image_type/floor_plan/'.$val->url)){
                                                                    $floor_plan = asset('data/image_type/floor_plan/'.$val->url);
                                                                }else{
                                                                    $floor_plan = asset('images/noimage.gif');
                                                                }
                                                            }else{
                                                                $floor_plan = asset('images/noimage.gif');
                                                            }
                                                        ?>
                                                        <img src="{{ $floor_plan }}" alt="" id="img_floor_plan_1" style="width: 180px; height: 150px;object-fit: cover;border: 1px solid #ddd;"/>
                                                        {{-- <input type="file" onchange="reload_image_floor_plan_input(1,event);" class="default" name="img_floor_plan[]" accept="image/*" style="position:absolute; width:180px; height:150px; top:0; left:0; opacity:0;cursor: pointer;"/> --}}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="panel">
                    <header class="panel-heading">
                        <div class="row">
                            <div class="col-sm-6">
                                {{ trans('unit_type.interior') }}
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-primary-d btn-sm pull-right btn_add_interior"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="fileupload fileupload-new">
                                        <div class="row" id="form_upload_interior">
                                            @foreach($img_interior as $val)
                                                <div class="col-sm-6 interior" style="padding: 10px;">
                                                    <button type="button" class="btn btn-danger btn_remove" style="position: absolute;right: 0;z-index: 2;" data-id="{{ $val->id }}">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                    <div style="width: 180px; height: 150px;">
                                                        <?php
                                                            $interior = '';
                                                            if($val->url){
                                                                if(file_exists('data/image_type/interior/'.$val->url)){
                                                                    $interior = asset('data/image_type/interior/'.$val->url);
                                                                }else{
                                                                    $interior = asset('images/noimage.gif');
                                                                }
                                                            }else{
                                                                $interior = asset('images/noimage.gif');
                                                            }
                                                        ?>
                                                        <img src="{{ $interior }}" alt="" id="img_interior_1" style="width: 180px; height: 150px;object-fit: cover;border: 1px solid #ddd;"/>
                                                        {{-- <input type="file" onchange="reload_image_interior_input(1,event);" class="default" name="img_interior[]" accept="image/*" style="position:absolute; width:180px; height:150px; top:0; left:0; opacity:0;cursor: pointer;"/> --}}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="panel">
                    <header class="panel-heading">
                        <div class="row">
                            <div class="col-sm-6">
                                {{ trans('unit_type.exterior') }}
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-primary-d btn-sm pull-right btn_add"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="fileupload fileupload-new">
                                        <div class="row" id="form_upload_exterior">
                                            @foreach($img_exterior as $val)
                                                <div class="col-sm-6 exterior" style="padding: 10px;">
                                                    <button type="button" class="btn btn-danger btn_remove" style="position: absolute;right: 0;z-index: 2;" data-id="{{ $val->id }}">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                    <div style="width: 180px; height: 150px;">
                                                        <?php
                                                            $exterior = '';
                                                            if($val->url){
                                                                if(file_exists('data/image_type/exterior/'.$val->url)){
                                                                    $exterior = asset('data/image_type/exterior/'.$val->url);
                                                                }else{
                                                                    $exterior = asset('images/noimage.gif');
                                                                }
                                                            }else{
                                                                $exterior = asset('images/noimage.gif');
                                                            }
                                                        ?>
                                                        <img src="{{ $exterior }}" alt="" id="img_exterior_1" style="width: 180px; height: 150px;object-fit: cover;border: 1px solid #ddd;"/>
                                                        {{-- <input type="file" onchange="reload_image_input(1,event);" class="default" name="img_exterior[]" accept="image/*" style="position:absolute; width:180px; height:150px; top:0; left:0; opacity:0;cursor: pointer;"/> --}}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false) }}"></script>
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

    <!-- include summernote css/js -->
    <script src="{{ asset('summernote-0.8.18-dist/summernote.min.js') }}"></script>
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
        $(document).ready(function() {
            $('.equipment_clause').summernote({
                height: 250,
                 onImageUpload: function(files, editor, welEditable) {
                    sendFile(files[0], editor, welEditable);
                }
            });
        });
        $(document).ready(function() {
            var i =1;
            // FLOOR PLAN
            $('.btn_add_floor_plan').click(function() {
                i++;
                var form_upl = '<div class="col-sm-6 floor_plan" style="padding: 10px;">'+
                '<button type="button" class="btn btn-danger btn_remove" style="position: absolute;right: 0;z-index: 2;" data-id="">'
                +'<i class="fa fa-trash-o"></i></button>'
                +'<div style="width: 180px; height: 150px;">'
                +'<img src="{{ asset('images/noimage.gif',false) }}" alt="" id="img_floor_plan_'+i+'" style="width: 180px; height: 150px;object-fit: cover;border: 1px solid #ddd;"/>'
                +'<input type="file" onchange="reload_image_floor_plan_input('+i+',event);" class="default" name="img_floor_plan[]" accept="image/*" multiple style="position:absolute; width:180px; height:150px; top:0; left:0; opacity:0;cursor: pointer;"/></div>';
                $('#form_upload_floor_plan').append(form_upl);
            });
            // Interior
            $('.btn_add_interior').click(function() {
                i++;
                var form_upl = '<div class="col-sm-6 interior" style="padding: 10px;">'+
                '<button type="button" class="btn btn-danger btn_remove" style="position: absolute;right: 0;z-index: 2;" data-id="">'
                +'<i class="fa fa-trash-o"></i></button>'
                +'<div style="width: 180px; height: 150px;">'
                +'<img src="{{ asset('images/noimage.gif',false) }}" alt="" id="img_interior_'+i+'" style="width: 180px; height: 150px;object-fit: cover;border: 1px solid #ddd;"/>'
                +'<input type="file" onchange="reload_image_interior_input('+i+',event);" class="default" name="img_interior[]" accept="image/*" multiple style="position:absolute; width:180px; height:150px; top:0; left:0; opacity:0;cursor: pointer;"/></div>';
                $('#form_upload_interior').append(form_upl);
            });
            // Exterior
            $('.btn_add').click(function() {
                i++;
                var form_upl = '<div class="col-sm-6 exterior" style="padding: 10px;">'+
                '<button type="button" class="btn btn-danger btn_remove" style="position: absolute;right: 0;z-index: 2;" data-id="">'
                +'<i class="fa fa-trash-o"></i></button>'
                +'<div style="width: 180px; height: 150px;">'
                +'<img src="{{ asset('images/noimage.gif',false) }}" alt="" id="img_exterior_'+i+'" style="width: 180px; height: 150px;object-fit: cover;border: 1px solid #ddd;"/>'
                +'<input type="file" onchange="reload_image_input('+i+',event);" class="default" name="img_exterior[]" accept="image/*" style="position:absolute; width:180px; height:150px; top:0; left:0; opacity:0;cursor: pointer;"/></div>';
                $('#form_upload_exterior').append(form_upl);
            });
            $('body').on('click','.btn_remove',function() {
                var id = $(this).attr('data-id');
                if(confirm('Do you want to delete?') == true) {
                    var eThis = $(this).parent().remove();
                    if(id != ''){
                        $.ajax({
                            url: '{{ route('remove_image') }}',
                            type: 'GET',
                            dataType: 'JSON',
                            data: {id: id},
                            success:function(response){
                                if(response.msg == 1){
                                    eThis;
                                }else{
                                    alert(0);
                                }
                            }
                        });                    
                    }else{
                        $(this).parent().remove();
                    }
                }
            });
        });
        function reload_image_floor_plan_input(j,event){
            var selectedFile = event.target.files[0];
            var reader = new FileReader();
            var img_id = 'img_floor_plan_'+j;
            var imgtag = document.getElementById(img_id);
            imgtag.title = selectedFile.name;
            reader.onload = function(event) {
                imgtag.src = event.target.result;
            };
            reader.readAsDataURL(selectedFile);
        }
        function reload_image_interior_input(j,event){
            var selectedFile = event.target.files[0];
            var reader = new FileReader();
            var img_id = 'img_interior_'+j;
            var imgtag = document.getElementById(img_id);
            imgtag.title = selectedFile.name;
            reader.onload = function(event) {
                imgtag.src = event.target.result;
            };
            reader.readAsDataURL(selectedFile);
        }
        function reload_image_input(j,event){
            var selectedFile = event.target.files[0];
            var reader = new FileReader();
            var img_id = 'img_exterior_'+j;
            var imgtag = document.getElementById(img_id);
            imgtag.title = selectedFile.name;
            reader.onload = function(event) {
                imgtag.src = event.target.result;
            };
            reader.readAsDataURL(selectedFile);
        }
    </script>
@endsection