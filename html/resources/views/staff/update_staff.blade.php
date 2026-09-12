@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}" />
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
<?php
$gender = config('static_data.gender');
$performance = config('static_data.performance');
?>

@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
             <header class="panel-heading">
                {{ trans('sidebar.sb_update_staff') }}
                @foreach($branch as $b)
                @if(isset($branch_id))
                @if($branch_id==$b->id)
                For Branch {{ $b->branch_name }}
                @endif
                @endif
                @endforeach
                @foreach($role as $r)
                @if(isset($role_id))
                @if($role_id==$r->id)
                For Role {{ $r->role_name }}
                @endif
                @endif
                @endforeach
             </header>
             <div class="panel-body">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                @endif
                @if(Session::has('error'))
                    <div class="alert alert-danger fad in">
                        <button type="button" class="close close-sm" data-dismiss="alert">x</button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                  <form class="cmxform form-horizontal" method="post" action="{{route('update_staff',[$s->id])}}" id="staffForm" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ trans('staff.s_staff_role') }}</label>
                        <div class="col-md-6">
                            <select class="form-control" id="ro" name="ro">
                                <option value="">-</option>
                                 @if(!empty($role))
                                    @foreach($role as $r)
                                        <option value="{{ $r->id }}" {{ ($s->role_id == $r->id)? 'selected':'' }}  >{{ $r->role_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ trans('staff.s_staff_branch') }}</label>
                        <div class="col-md-6">
                            <select class="form-control" id="br" name="br">
                                <option value="0">-</option>
                                @if(!empty($branch))
                                    @foreach($branch as $b)
                                    <option value="{{ $b->id }}" {{ ($s->branch_id == $b->id)? 'selected':'' }} >{{ $b->branch_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_phone',['num'=>1]) }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="phone" id="phone" value="{{$s->phone1}}" class="form-control" data-mask="999-999-999?9"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_phone',['num'=>2]) }} </label>
                        <div class="col-sm-6">
                            <input type="text" name="phone1" id="phone1" value="{{$s->phone2}}" class="form-control" data-mask="999-999-999?9"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_address') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" name="address" >{{ $s->address?$s->address:old('address') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('staff.s_salary') }}</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" value="{{ $s->salary?$s->salary:old('salary') }}" name="salary" id="salary" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('product.p_date') }}</label>
                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="input-append date dpYears col-sm-6">
                                <input type="text" name="update_date" value="{{ date('d/m/y') }}" size="16" class="form-control">
                                <span class="add-on birhtdateDatepicker">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ trans('staff.s_staff_performance') }}</label>
                        <div class="col-md-6">
                            <select class="form-control" id="performance" name="performance">
                                <option value="100">-</option>
                                @foreach($performance as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('product.p_remark') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" name="description" ></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <br/>
                        <div class="col-sm-offset-5 col-sm-6">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{ trans('multiple.m_update') }}</button>
                            <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>  {{ trans('multiple.m_reset') }}</button>
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
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery-multi-select/js/jquery.multi-select.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery-multi-select/js/jquery.quicksearch.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select-init.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
    <script typr="text/javascript">
        $('#phone').on('change', function(e){
            var i_phone = $('#phone').val();
            var new_phone = i_phone.replace(/ /g, "-");
            $('#phone').val(new_phone);
        })
        
        $(document).ready(function(){
            $('.dpYears').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
        });
        
        $(document).ready(function(){
        $('#e9').addClass('required');
        $('#e9').on('change',function(e){
            if((e.val).length>0){
                $(this).parent().find('label.error').css({'display':'none'});
                $(this).parent().find('.error').removeClass('error').addClass('valid');
            }
        });
    });
    });

    </script>
@endsection
