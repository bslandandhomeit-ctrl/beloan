@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.toast.min.css',isset($secure) ? false : false) }}" />
@endsection

@section('content')
   <div class="row">
       <div class="col-sm-12">
            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
            <section class="panel">
                <header class="panel-heading">
                    {{ trans('user.u_user_p_picture') }}
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-up" href="javascript:;"></a>
                    </span>
                </header>
                <div class="panel-body" style="display: none;">
                  <form class="cmxform form-horizontal bucket-form" method="post" id="photoForm" enctype="multipart/form-data">
                     <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                            <img src="{{ asset(!empty($user->photo) ? 'data/users/'.$user->photo : 'images/noimage.gif', isset($secure)?false:false) }}" alt="" />
                        </div>
                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                        <div>
                           <span class="btn btn-white btn-file">
                               <span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{ trans('multiple.m_select_image') }}</span>
                               <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                               <input type="file" class="default" name="photo" id="photo"/>
                           </span>
                            <button type="submit" class="btn btn-primary">{{ trans('multiple.m_save') }}</button>
                        </div>
                     </div>
                   </form>
                </div>
            </section>

            <section class="panel">
                <header class="panel-heading">
                     {{trans('user.u_user_name')}} : {{ !empty($user->kh_name)? $user->kh_name.' , ':'' }} {{ $user->name }}
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-up" href="javascript:;"></a>
                    </span>
                </header>
                <div class="panel-body" style="display: none">
                    <form class="cmxform form-horizontal bucket-form" method="post" action="" id="nameForm">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('user.u_user_kh_name') }}</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="kh_name" name="kh_name" value="{{ $user->kh_name }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"> {{trans('user.u_user_name')}}</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="name" name="name"  value="{{ $user->name }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">{{ trans('multiple.m_save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <section class="panel">
                 <header class="panel-heading">
                     Password
                     <span class="tools pull-right">
                         <a class="fa fa-chevron-up" href="javascript:;"></a>
                     </span>
                 </header>
                 <div class="panel-body" style="display: none">
                     <form class="cmxform form-horizontal bucket-form" method="post" action="" id="passwordForm">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('user.u_user_old_pass') }}</label>
                            <div class="col-sm-6">
                                <input type="password" id="old_password" name="old_password" class="form-control" />
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('user.u_user_new_pass') }}</label>
                              <div class="col-sm-6">
                                 <input type="password" id="password" name="password" class="form-control" />
                              </div>
                         </div>
                         <div class="form-group">
                             <label class="col-sm-3 control-label">{{ trans('user.u_user_conpassword') }}</label>
                             <div class="col-sm-6">
                                <input type="password" id="con_password" name="con_password" class="form-control" />
                             </div>
                         </div>

                         <div class="form-group">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">{{ trans('multiple.m_save') }}</button>
                            </div>
                         </div>
                     </form>
                 </div>
            </section>

             <section class="panel">
                 <header class="panel-heading">
                    {{ trans('multiple.m_email') }} : {{ $user->email }}
                    <span class="tools pull-right">
                       <a class="fa fa-chevron-up" href="javascript:;"></a>
                    </span>
                 </header>
                 <div class="panel-body" style="display: none">
                    <form class="cmxform form-horizontal bucket-form" method="post" action="" id="emailForm">
                         <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('multiple.m_email') }}</label>
                            <div class="col-sm-6">
                                 <input type="text" class="form-control" name="email" id="email" value="{{ $user->email }}"/>
                            </div>
                         </div>
                         <div class="form-group">
                            <label class="col-sm-3"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">{{ trans('multiple.m_save') }}</button>
                            </div>
                         </div>
                    </form>
                 </div>
             </section>

             <section class="panel">
                <header class="panel-heading">
                    {{ trans('user.u_user_other') }}
                     <span class="tools pull-right">
                        <a class="fa fa-chevron-up" href="javascript:;"></a>
                     </span>
                </header>
                <div class="panel-body" style="display: none">
                 <form class="cmxform form-horizontal bucket-form" method="post" action="" id="otherForm">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_phone',['num'=>'']) }}</label>
                        <div class="col-sm-6">
                             <input type="text" class="form-control" name="phone" id="phone" value="{{ $user->phone }}" data-mask="999-999-999?9"/>
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_address') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="address" name="address">{{ $user->address }}</textarea>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-6">
                            <button type="button" id="othersave" class="btn btn-primary"> {{ trans('multiple.m_save') }}</button>
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
    <script type="text/javascript" src="{{ asset('js/jquery.toast.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/users.js',isset($secure) ? false : false) }}"></script>
@endsection
