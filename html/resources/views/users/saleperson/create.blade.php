@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/jquery-multi-select/css/multi-select.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/jquery-tags-input/jquery.tagsinput.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
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
</style>
@section('content')
    <div class="row">
        <form class="form-horizontal" method="post" action="{{route('add_saleperson')}}">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        {{ trans('sale_person.add_sale_person') }}
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
                                    <label class="control-label">{{ trans('sale_person.select_sale_team') }} <span class="red-color">*</span></label>
                                    <select class="form-control" name="sale_team_id" required>
                                        <option value="">-</option>
                                        @foreach ($co_name as $val)
                                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('sale_person.name') }} <span class="red-color">*</span></label>
                                    <input type="text" name="name" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('sale_person.gender') }} <span class="red-color">*</span></label>
                                    <select class="form-control" name="gender" required>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('sale_person.dob') }} <span class="red-color">*</span></label>
                                    <input type="text" name="dob" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="form-control input-append date dpYears" size="16" value="{{ date('Y-m-d') }}" required/>
                                    <span class="add-on birhtdateDatepicker">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('sale_person.select_national') }} <span class="red-color">*</span></label>
                                    <select class="form-control" name="national_id" required>
                                        <option value="">-</option>
                                        @foreach ($nationlity['countries'] as $val)
                                            @foreach ($val->description as $row)
                                                <option value="{{ $row->country_id }}">{{ $row->name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('sale_person.email') }} <span class="red-color">*</span></label>
                                    <input type="email" name="email" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('sale_person.phone') }} <span class="red-color">*</span></label>
                                    <input type="text" name="phone" class="form-control" id="phone" data-mask="999-999-999?9" placeholder="___-___-____"  required/>
                                </div>
                            </div>
                        </div>
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
        </form>
    </div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery-multi-select/js/jquery.multi-select.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery-multi-select/js/jquery.quicksearch.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select-init.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
        $('#phone').on('change', function (e) {
            var i_phone = $('#phone').val();
            var new_phone = i_phone.replace(/ /g, "-");
            $('#phone').val(new_phone);
        });

        $(document).ready(function () {
            $('.dpYears').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
        });
    </script>
@endsection