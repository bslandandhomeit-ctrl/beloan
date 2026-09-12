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
                {{ trans('sidebar.sb_edit_staff') }}
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
                <form class="cmxform form-horizontal" method="post" action="{{route('edit_staff',[$s->id])}}" id="staffForm" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{trans('multiple.m_photo')}}(200x200)</label>
                        <div class="col-sm-6">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                    <img src="{{ asset('images/noimage.gif',isset($secure) ? false : false) }}" alt="" />
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                <div>
                                   <span class="btn btn-white btn-file">
                                       <span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{trans('multiple.m_select_image')}}</span>
                                       <span class="fileupload-exists"><i class="fa fa-undo"></i> {{trans('multiple.m_change')}}</span>
                                       <input type="file" class="default" name="photo" id="photo"/>
                                   </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_name') }}<span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" value="{{ $s->name?$s->name:old('name') }}" name="name" id="name" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_kh_name') }}</label>
                        <div class="col-sm-6">
                             <input type="text" class="form-control" value="{{ $s->kh_name?$s->kh_name:old('kh_name') }}" name="kh_name" id="kh_name" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('staff.s_staff_gender') }}</label>
                        <div class="col-md-6">
                            <?php $gender = config('static_data.gender');?>
                            <select class="form-control" id="gender" name="gender">
                                @foreach($gender as $key => $value)
                                <option value="{{ $key }}" {{ ($s->gender == $key)? 'selected':'' }} >{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('staff.s_nationality') }}</label>
                        <div class="col-sm-6">
                             <input type="text" class="form-control" value="{{ $s->nationality?$s->nationality:old('nationality') }}" name="nationality" id="nationality" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('staff.s_date_of_birth') }}</label>
                        <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="input-append date dpYears col-sm-6">
                            <input type="text" name="birth_date" value="{{ date('d F Y',strtotime($s->date_of_birth)) }}" size="16" class="form-control">
                                <span class="add-on birhtdateDatepicker">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ trans('staff.s_staff_role') }} <span class="red-color">*</span></label>
                        <div class="col-md-6">
                            <select class="form-control" id="ro" name="ro">
                                <option value="0">-</option>
                                @if(!empty($role))
                                    @foreach($role as $r)
                                        <option value="{{ $r->id }}" {{ ($s->role_id == $r->id)? 'selected':'' }}  >{{ $r->role_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    
                    @if(count($branch) > 1)
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
                    @endif
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_email') }}</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" value="{{ $s->email?$s->email:old('email') }}" name="email" id="email" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_phone',['num'=>'1']) }}</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" value="{{$s->phone1}}" name="phone" id="phone" data-mask="999-999-999?9" placeholder="___-___-____"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_phone',['num'=>2]) }} </label>
                        <div class="col-sm-6">
                            <input type="text" name="phone2" id="phone2" value="{{$s->phone2}}" class="form-control" data-mask="999-999-999?9" placeholder="___-___-____"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_address') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" value="" name="address">{{ $s->address?$s->address:old('address') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('staff.s_salary') }}</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" value="{{ $s->salary?$s->salary:old('salary') }}" name="salary" id="salary" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('staff.s_tax') }}</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" value="{{ $s->inc_tax?$s->inc_tax:old('tax') }}" name="tax" id="tax" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('staff.s_start_date') }}</label>
                        <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="input-append date dpYears col-sm-6">
                            <input type="text" name="start_date"  value="{{ date('d F Y',strtotime($s->start_on)) }}" size="16" class="form-control">
                                <span class="add-on birhtdateDatepicker">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-7">
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{ trans('multiple.m_update') }}</button>
                                <a href="javascript:history.go(-1)" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
                        </div>
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
