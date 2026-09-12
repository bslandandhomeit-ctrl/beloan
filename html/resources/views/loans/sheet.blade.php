@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('theme/css/fileinput.min.css',isset($secure) ? false : false) }}">
@endsection

@section('content')
     <section class="panel">
        <header class="panel-heading">
            {{ trans('loan.l_upload_holiday_sheet') }}
        </header>
        <div class="panel-body">
            <div class="position-center">
                @if(Session::has('error'))
                    <div class="alert alert-danger fad in">
                        <button type="button" class="close close-sm" data-dismiss="alert">x</button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                <form enctype="multipart/form-data" method="post" action="{{ route('upload_sheet') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input id="file-0a" class="file" type="file" name="csv" accept=".csv">
                <br>
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> {{ trans('loan.l_upload') }}</button>
                <a class="btn btn-danger" href="javascript:history.back(-1)"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
            </form>
            </div>
        </div>
    </section>
@endsection   

@section('js')
   <script src="{{ asset('theme/js/fileinput.min.js',isset($secure) ? false : false) }}"></script>
   <script>
        $('#file-0a').fileinput({
            language: 'en',
            allowedFileExtensions : ['csv'],
            'showPreview' : false,
            'showUpload' : false
        });
   </script>
@endsection
