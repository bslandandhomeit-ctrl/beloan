@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
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
                <?php $static = config('static_data');?>
                <header class="panel-heading">
                    {{ trans('sidebar.sb_add_branch') }}
                </header>
                <div class="panel-body">
                    @if(Session::has('error'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}
                            <button data-dismiss="alert" class="close close-sm" type="button">
                                <i class="fa fa-times"></i>
                            </button>
                        </p>
                    @endif
                    @if(Session::has('message'))
                        @if(Session::has('error') && Session::get('error') == true)
                            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}
                                <button data-dismiss="alert" class="close close-sm" type="button">
                                    <i class="fa fa-times"></i>
                                </button>
                            </p>
                        @else
                            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}
                                <button data-dismiss="alert" class="close close-sm" type="button">
                                    <i class="fa fa-times"></i>
                                </button>
                            </p>
                        @endif
                    @endif
                    <form class="cmxform form-horizontal" method="post" action="{{ route('add_branch') }}" id="comBranch" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                                                    <label class="control-label">{{ trans('report.rpt_branch_name') }}<span class="red-color"> *</span></label>
                                                    <input type="text" id="branch_name" name="branch_name" class="form-control" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('company.address_one') }}</label>
                                                    <input type="text" id="address_one" name="address_one" class="form-control"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('company.address_two') }}</label>
                                                    <input type="text" id="address_two" name="address_two" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="english">
                                                <br/>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('report.rpt_branch_name') }}<span class="red-color"> *</span></label>
                                                    <input type="text" id="branch_name_en" name="branch_name_en" class="form-control" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('company.address_one') }}</label>
                                                    <input type="text" id="address_one_en" name="address_one_en" class="form-control"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('company.address_two') }}</label>
                                                    <input type="text" id="address_two_en" name="address_two_en" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="chinese">
                                                <br/>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('report.rpt_branch_name') }}<span class="red-color"> *</span></label>
                                                    <input type="text" id="branch_name_cn" name="branch_name_cn" class="form-control" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('company.address_one') }}</label>
                                                    <input type="text" id="address_one_cn" name="address_one_cn" class="form-control"/>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">{{ trans('company.address_two') }}</label>
                                                    <input type="text" id="address_two_cn" name="address_two_cn" class="form-control"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('report.rpt_branch_short_name') }}<span class="red-color"> *</span></label>
                                    <input type="text" id="short_name" name="short_name" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('company.contact_number') }}</label>
                                    <input type="text" id="contact_number" name="contact_number" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('company.email') }}</label>
                                    <input type="text" id="email" name="email" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('company.website') }}</label>
                                    <input type="text" id="website" name="website" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('company.tax_no') }}</label>
                                    <input type="text" id="tax_no" name="tax_no" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('company.issued_date') }}</label>
                                    <div class="input-group date form_datetime-component" style="padding: 4px 12px;">
                                        <input type="text" class="form-control" name="tax_issued_date" id="tax_issued_date" readonly="" style="background: #fff;">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary date-set" style="top: -4px;padding: 8px 12px;"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                    {{-- <input type="text" id="tax_issued_date" name="tax_issued_date" class="form-control" required/> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('company.commercial_licence_no') }}</label>
                                    <input type="text" id="commercial_licence_no" name="commercial_licence_no" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('company.issued_date') }}</label>
                                    <div class="input-group date form_datetime-component" style="padding: 4px 12px;">
                                        <input type="text" class="form-control" name="commercial_issued_date" id="commercial_issued_date" readonly="" style="background: #fff;">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary date-set" style="top: -4px;padding: 8px 12px;"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                    {{-- <input type="text" id="commercial_issued_date" name="commercial_issued_date" class="form-control" required/> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('company.dynamic_nav') }} <span class="red-color"> *</span></label>
                                    <input type="text" id="dynamic_nav" name="dynamic_nav" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('company.com_branch_code') }} <span class="red-color"> *</span></label>
                                    <input type="text" id="branch_code" name="branch_code" class="form-control" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div style="width: 180px; height: 150px;">
                                    <img src="{{ asset('images/noimage.gif',false) }}" alt="" id="logo" style="width: 180px; height: 150px;object-fit: cover;border: 1px solid #ddd;"/>
                                    <input type="file" onchange="reload_image_input(event);" class="default" name="logo" accept="image/*" style="position:absolute; width:180px; height:150px; top:0; left:0; opacity:0;cursor: pointer;"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="text-align: right;">
                                <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                                <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> {{ trans('multiple.m_reset') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".form_datetime-component").datepicker({
                format: "dd MM yyyy",
                autoclose: true
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
