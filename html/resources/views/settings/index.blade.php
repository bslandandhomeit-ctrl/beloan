@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false)}}" />
<link href="{{ asset('theme/js/iCheck/skins/flat/green.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection

@section('content')
<section class="panel panel-box-700">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
    @endif
    <header class="panel-heading">
        {{ trans('sidebar.settings') }}
    </header>

    <div class="panel-body">
        <form action="{{ route('setting') }}" method="POST" class="cmxform form-horizontal">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <hr/>
            <?php 
            	$i=-1; 
            	while (($line = fgets($l)) !== false) { 
            		$i++; 
            		if($i==0) continue;
            		$ex = explode(', "', $line);
                    $ex_ = explode('"', $ex[0]);
                    $ex0[1] = $ex_[1];
                    $ex1[0] = $ex[1];
            		if(!$ex1[0]) continue;
            ?>
            <div class="form-group">
            	<label class="control-label col-sm-4"><?php echo $ex0[1]?></label>
               	<div class="col-md-8">
               		<input type="hidden" name="constant_label[]" value="<?php echo $ex0[1]?>" />
                	<input type="text" name="constant_value[]" class="form-control"  value="<?php echo $ex1[0]?>" />
                </div>
            </div>
            <?php }?>
            
            <label class="control-label col-sm-4"></label>
            <div class="col-md-8">
                <button type="submit" class="btn btn-info" name="submit_frm"><i class="fa fa-save"></i>&nbsp;{{ trans('multiple.m_save') }}</button>
            </div>
        </form>
</section>

@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('theme/js/iCheck/jquery.icheck.js',isset($secure) ? false : false)}}"></script>

@endsection