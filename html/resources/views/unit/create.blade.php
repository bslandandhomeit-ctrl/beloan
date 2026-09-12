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
                    {{ trans('unit.add_unit') }}
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
                    <form class="cmxform form-horizontal" method="post" action="{{route('add_unit')}}" id="frm_unit">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="control-label">{{ trans('unit.unit_type') }}</label>
                                <div class="input-group">
                                    <select id="unit_type_id" style="width: 100%" name="unit_type_id">
                                        @foreach($unit_type as $unit_types)
                                            <option value="{{ $unit_types->id }}">{{ $unit_types->name }} ({{ isset($unit_types->Projects->dealer)?$unit_types->Projects->dealer:''}} - {{  isset($unit_types->Projects->short_code)?$unit_types->Projects->short_code:'' }} )</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn" style="padding-left:5px;vertical-align:top">{{ trans('multiple.m_add') }}</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.zone') }}</label>
                                    <input type="text" id="zone_id" name="zone_id" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.unit_code') }}<span class="red-color"> *</span></label>
                                    <input type="text" id="unit_code" name="unit_code" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.price') }}<span class="red-color"> *</span></label>
                                    <input type="text" id="price" name="price" class="form-control" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.street') }}</label>
                                    <input type="text" id="street" name="street" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.street_corner') }}</label>
                                    <input type="text" id="street_corner" name="street_corner" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.street_size') }}</label>
                                    <input type="text" id="street_size" name="street_size" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.floor') }}</label>
                                    <input type="text" id="floor" name="floor" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 style="border-bottom: 1px solid #ddd;padding-bottom: 15px;">{{ trans('unit.measurements') }}:</h4>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.land_width') }} : ({{ trans('ទទឹងដី') }}):</label>
                                    <input type="text" id="land_width" name="land_width" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.land_length') }} : ({{ trans('បណ្ដោយដី') }}):</label>
                                    <input type="text" id="land_length" name="land_length" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.land_area') }}​ : ({{ trans('ទំហំដី') }}):</label>
                                    <input type="text" id="land_area" name="land_area" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.house_width') }} : ({{ trans('ទទឹងផ្ទះ') }}):</label>
                                    <input type="text" id="house_width" name="house_width" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.house_length') }}​ : ({{ trans('បណ្ដោយផ្ទះ') }}):</label>
                                    <input type="text" id="house_length" name="house_length" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.house_area') }}​ : ({{ trans('ទំហំផ្ទះ') }}):</label>
                                    <input type="text" id="house_area" name="house_area" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 style="border-bottom: 1px solid #ddd;padding-bottom: 15px;">{{ trans('unit.facilities') }}:</h4>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.living_room') }}</label>
                                    <input type="text" id="living_room" name="living_room" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.kitchen') }}</label>
                                    <input type="text" id="kitchen" name="kitchen" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.bedroom') }}</label>
                                    <input type="text" id="bedroom" name="bedroom" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.bathroom') }}</label>
                                    <input type="text" id="bathroom" name="bathroom" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('unit.swimming_pool') }}</label>
                                    <input type="text" id="swimming_pool" name="swimming_pool" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('multiple.status') }} <span class="red-color">*</span></label>
                                    <select class="form-control" name="status" required>
                                        @foreach($unit_status as $key => $status)
                                            <?php
                                                $selected = '';
                                                if($key == 'validate'){
                                                   $selected = "selected"; 
                                                }
                                            ?>

                                            <option value="{{ $key }}" {{ $selected }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
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
                    </form>
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
            $('#land_width').keyup(function() {
                calculate_land_area();
            });
            $("#unit_type_id").select2();
            
            $('#land_length').keyup(function() {
                calculate_land_area();
            });
            $('#house_width').keyup(function() {
                calculate_house_area();
            });
            $('#house_length').keyup(function() {
                calculate_house_area();
            });
        });
        function calculate_land_area(){
            var width = document.getElementById('land_width').value;
            var land_length = document.getElementById('land_length').value;
            var land_area_size = parseFloat(width) * parseFloat(land_length);
            if(isNaN(land_area_size)){
                if(width == '' || land_length == ''){
                    document.getElementById('land_area').value = '0.00';
                }
                // if(width != '' || land_length != ''){
                //     document.getElementById('land_area').readOnly = true;
                // }else{
                //     document.getElementById('land_area').readOnly = false;
                // }
            }else{
                document.getElementById('land_area').value = parseFloat(land_area_size).toFixed(2);
            }
        }
        calculate_land_area();

        function calculate_house_area(){
            var width = document.getElementById('house_width').value;
            var land_length = document.getElementById('house_length').value;
            var land_area_size = parseFloat(width) * parseFloat(land_length);
            if(isNaN(land_area_size)){
                if(width == '' || land_length == ''){
                    document.getElementById('house_area').value = '0.00';
                }
            }else{
                document.getElementById('house_area').value = parseFloat(land_area_size).toFixed(2);
            }
        }
        calculate_house_area();
    </script>
@endsection