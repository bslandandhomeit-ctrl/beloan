@extends('layouts.app')

@section('css')
     <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}" />
     <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" />
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
             <header class="panel-heading">
                {{ trans('user.u_user_edit') }}
             </header>

             @if(!empty($user))

             <div class="panel-body">
                @if(Session::has('error'))
                    <div class="alert alert-danger fad in">
                        <button type="button" class="close close-sm" data-dismiss="alert">x</button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                  <form class="cmxform form-horizontal" method="post" action="{{route('edit_user',[$user->id])}}" id="userEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{trans('multiple.m_photo')}}(200x200)</label>
                        <div class="col-sm-6">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                    <?php 
                                        $url = '';
                                        if($user->photo){
                                            if(file_exists('data/users/'.$user->photo)){
                                                $url = asset('data/users/'.$user->photo);
                                            }else{
                                                $url = asset('no_profile.jpg');
                                            }
                                        }else{
                                            $url = asset('no_profile.jpg');
                                        }

                                    ?>
                                    <img style="width: 100%; object-fit: cover;" src="{{ $url }}" alt="" />

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
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_name') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_kh_name') }}</label>
                        <div class="col-sm-6">
                             <input type="text" class="form-control" name="kh_name" id="kh_name" value="{{ $user->kh_name }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_username') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="username" id="username" value="{{ $user->username }}" disabled/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_email') }}</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="email" id="email" value="{{ $user->email }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_phone',['num'=>'']) }}</label>
                        <div class="col-sm-6">
                            {{--<input type="text" class="form-control" name="phone" id="phone" data-mask="999-999-999?9" value="{{ $user->phone }}" />--}}
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="___-___-____/___-___-_____" style="color: #d3d3d3;"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_address') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" name="address">{{ $user->address }}</textarea>
                        </div>
                    </div>

                    @if(!empty($roles))
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_role') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                             <select class="form-control" name="role_id" id="role">
                                 <option value="0">-</option>
                                    @foreach($roles as $r)
                                        <option value="{{ $r->id }}" {{ ($user->role_id == $r->id)? 'selected':'' }} > {{ $r->role_name }}</option>
                                    @endforeach

                             </select>
                        </div>
                    </div>
                    @endif


                    <div class="form-group">
                    	<label class="col-sm-3 control-label">{{ trans('user.u_user_branch') }} <span class="red-color">*</span></label>
                    	<div class="col-sm-6">
                        	{{-- <div class="input-group"> --}}
                        		<select class="form-control" name="branch_id" id="branch" required>
                                    <option value="">-</option>
                        			@foreach($branches as $b)
                                    	<option value="{{ $b->id }}" {{ ($user->branch_id == $b->id)? 'selected':'' }} > {{ $b->branch_name }}</option>
                                    @endforeach
                                </select>
                        	{{-- </div> --}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{trans('multiple.signature')}}(200x200)</label>
                        <div class="col-sm-6">
                            <?php 
                                $url = '';
                                if($user->signature){
                                    if(file_exists('data/users/'.$user->signature)){
                                        $url = asset('data/users/'.$user->signature);
                                    }else{
                                        $url = asset('no_profile.jpg');
                                    }
                                }else{
                                    $url = asset('no_profile.jpg');
                                }

                            ?>
                            <div style="width: 180px; height: 150px;">
                                <img src="{{ $url }}" alt="" id="signature" style="width: 180px; height: 150px;object-fit: cover;border: 1px solid #ddd;"/>
                                <input type="file" onchange="reload_image_input(event);" class="default" name="signature" accept="image/*" style="position:absolute; width:180px; height:150px; top:0; left:0; opacity:0;cursor: pointer;"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">{{ trans('multiple.is_signature') }}</label>
                        <div class="col-lg-6">
                            <?PHP
                            $check = '';
                            if ($user->is_signature == 1) {
                                $check = 'checked';
                            }?>
                            <input type="checkbox" id="is_signature" name="is_signature" class="form-control" {{ $check }} value="1" style="height:32px; width: 32px;"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <br/>
                        <div class="col-sm-offset-3 col-sm-6">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_save') }}</button>
                            <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>&nbsp;&nbsp;{{ trans('multiple.m_reset') }}</button>
                            <a href="#change_pass" data-toggle="modal" class="btn btn-success "><i class="fa fa-refresh"></i>&nbsp;&nbsp;Change Password</a>
                        </div>
                    </div>
                  </form>
             </div>
             <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="change_pass" class="modal fade">
                 <div class="modal-dialog">
                     <div class="modal-content">
                         <div class="modal-header">
                             <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                             <h4 class="modal-title"> {{ trans('user.u_user_new_pass') }}</h4>
                         </div>
                         <div class="modal-body">
                             <form role="form" method="post" class="cmxform" id="PasswordUpdate">
                                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                 <input type="hidden" name="u_id" id="u_id" value="{{ $user->id }}">
                                 <div class="form-group">
                                     <label class="control-label">{{ trans('user.u_user_new_pass') }} <span class="red-color">*</span></label>
                                     <input type="text" name="password" id="password" class="form-control"/>
                                 </div>
                                 <div class="form-group">
                                     <label class="control-label">{{ trans('user.u_user_conpassword') }} <span class="red-color">*</span> </label>
                                     <input type="text" name="con_password" id="con_password" class="form-control"/>
                                 </div>
                                 <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_update') }}</button>
                                 <a data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>
             @endif
        </section>
    </div>
</div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/password_update.js',isset($secure) ? false : false) }}"></script>
    <script typr="text/javascript">
        $('#phone').on('change', function(e){
            var i_phone = $('#phone').val();
            var new_phone = i_phone.replace(/ /g, "-");
            $('#phone').val(new_phone);
        })
        $('#signature').on('change', function(e){
            var i_phone = $('#signature').val();
            var new_phone = i_phone.replace(/ /g, "-");
            $('#signature').val(new_phone);
        })
        function reload_image_input(event){
            var selectedFile = event.target.files[0];
            var reader = new FileReader();
            var img_id = 'signature';
            var imgtag = document.getElementById(img_id);
            imgtag.title = selectedFile.name;
            reader.onload = function(event) {
                imgtag.src = event.target.result;
            };
            reader.readAsDataURL(selectedFile);
        }
    </script>
@endsection
