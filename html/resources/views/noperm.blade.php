@extends('layouts.app')

@section('content')
   <div class="row">
        <div class="col-sm-12" style="min-height: 200px;">
             <p class="alert alert-class alert-danger">
               {{ trans('company.com_no_permission') }}
             </p>
        </div>
   </div>
@endsection