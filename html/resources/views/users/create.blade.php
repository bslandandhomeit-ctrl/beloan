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
                {{ trans('sidebar.sb_add_user') }}

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
                <form class="cmxform form-horizontal" method="post" action="{{route('add_user')}}" id="userForm" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{trans('multiple.m_photo')}}(200x200)</label>
                        <div class="col-sm-6">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                    <img style="width: 100%; object-fit: cover;" src="{{ asset('no_profile.jpg') }}" alt="" />
                                    {{-- <img src="{{ asset('images/noimage.gif',isset($secure) ? false : false) }}" alt="" /> --}}
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
                            <input type="text" class="form-control" name="name" id="name" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_kh_name') }}</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="kh_name" id="kh_name" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_username') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            (<span class="red-color" style="font-size: 10px;">{{ trans('user.u_user_notchange') }}</span>)
                            <input type="text" class="form-control" name="username" id="username" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_password') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" name="password" id="password" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_conpassword') }}</label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" name="con_password" id="con_password" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_email') }}</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="email" id="email" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_phone',['num'=>'']) }}</label>
                        <div class="col-sm-6">
                            {{--<input type="text" class="form-control" name="phone" id="phone" data-mask="999-999-999?9 ?/ 999-999-999?9" data-inputmask="'mask': '999-999-999 ?/ 999-999-999?9'"/>--}}
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="___-___-____/___-___-_____" style="color: #d3d3d3;"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_address') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" name="address"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_role') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <select class="form-control" name="role_id" id="role">
                                    <option value="0">-</option>
                                    @if(!empty($roles))
                                    @foreach($roles as $r)
                                    <option value="{{ $r->id }}"> {{ $r->role_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="input-group-btn" style="vertical-align:top">
                                    <a class="btn btn-info" href="#mRole" data-toggle="modal">
                                        <i class="fa fa-plus"></i>
                                        {{ trans('multiple.m_add') }}
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('user.u_user_branch') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <select class="form-control" name="branch_id" id="branch">
                                    @foreach($branch as $val)
                                        <option value="{{ $val->id }}"> {{ $val->branch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
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
                            <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i>  {{ trans('multiple.m_save') }}</button>
                            <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>  {{ trans('multiple.m_reset') }}</button>
                        </div>
                        <br/><br/>
                    </div>

                </form>
            </div>

            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="mRole" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">{{ trans('sidebar.sb_add_role') }}</h4>
                        </div>
                        <div class="modal-body">
                            <form class="cmxform" method="post" id="roleFormAjax">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="_token">
                                <div class="form-group">
                                    <label for="role">{{ trans('user.u_user_role') }}  <span class="red-color">*</span></label>
                                    <input type="text" class="form-control" name="role" id="role" />
                                </div>
                                <div class="form-group">
                                    <label for="role_name">{{ trans('user.u_user_role_name') }} <span class="red-color">*</span></label>
                                    <input type="text" class="form-control" name="role_name" id="role_name" />
                                </div>
                                <div class="form-group">
                                    <label for="desc">{{ trans('multiple.m_description') }}</label>
                                    <textarea class="form-control" name="description" id="desc" ></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_save') }}</button>
                                    <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>&nbsp;&nbsp;{{ trans('multiple.m_reset') }}</button>
                                </div>
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
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
<script typr="text/javascript">
$('#phone').on('change', function (e) {
var i_phone = $('#phone').val();
var new_phone = i_phone.replace(/ /g, "-");
$('#phone').val(new_phone);
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