@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false)}}" />
<link rel="stylesheet" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}">
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
           {{ trans('loan.l_edit_loan_document') }}
        </header>

        <div class="panel-body">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
            @endif
            <form class="cmxform form-horizontal" method="post" action="{{ route('loan_edit_document',[$docu->id]) }}" id="editLoanDoc" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_document_type') }} <span class="red-color">*</span> </label>
                    <div class="col-sm-6">
                        <?php
                            $loan_doc = Config::get('static_data')['loan_doc_type'];
                        ?>
                         <select id="doc_type" name="doc_type" class="form-control">
                            <option value="">-</option>
                            @foreach($loan_doc as $doc)
                                <option value="{{ $doc }}" {{ $doc==$docu->doc_type?'selected':'' }}>{{ $doc }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('multiple.m_note') }}</label>
                    <div class="col-sm-6">
                        <textarea name="note" id="note" class="form-control">{{ $docu->note }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_file') }}</label>
                    <div class="col-sm-6">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="{{ $docu->doc_photo?asset('data/loans/documents/'.$docu->doc_photo, isset($secure) ? false : false):asset('images/noimage.gif', isset($secure) ? false : false) }}" alt="{{ $docu->doc_photo }}" />
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                            <div>
                               <span class="btn btn-white btn-file">
                               <span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{ trans('loan.l_select_file') }}</span>
                               <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                               <input type="file" name="photo" id="photo" class="default"/>
                               </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"></label>
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{ trans('multiple.m_update') }}</button>
                        <button type="button" class="btn btn-danger" onclick="javascript:history.back()"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
@endsection
