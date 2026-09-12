@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
             <header class="panel-heading">
                {{ trans('company.com_add_language') }}
             </header>
             <div class="panel-body">
                 <form class="cmxform form-horizontal" method="post" action="{{route('add_locale')}}" id="userForm" enctype="multipart/form-data">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     <div class="form-group">
                        <label class="col-md-3 control-label">{{ trans('multiple.m_language') }} <span style="color: red;">*</span></label>
                        <div class="col-md-6">
                            <input type="text" name="locale"  class="form-control" required />
                        </div>
                     </div>
                     <div class="form-group">
                         <label class="col-md-3 control-label">{{ trans('multiple.m_locale') }} <span style="color: red;">*</span></label>
                         <div class="col-md-6">
                             <input type="text" name="short_locale"  class="form-control" required maxlength="2"/>
                         </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-3 control-label">{{ trans('multiple.m_icon') }}(60 x 60)</label>
                        <div class="col-md-6">
                            <input type="file" class="form-control" name="icon" id="icon"/>
                        </div>
                     </div>
                     <br/><br/>
                     <div class="form-group">
                        <label class="col-md-3"></label>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">{{ trans('multiple.m_save') }}</button>
                        </div>
                     </div>
                 </form>
             </div>
        </section>
    </div>
</div>
@endsection