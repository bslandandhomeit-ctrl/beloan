@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure)?false:false)}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure)?false:false)}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure)?false:false) }}" />
    <link href="{{ asset('css/client.css',isset($secure)?false:false) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="panel">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
        @endif

        <header class="panel-heading">
            <?php if(isset($_GET['adjust'])){?>
            {{ trans('sidebar.sb_adjust_asset') }}
            <?php }else{ ?>
            {{ trans('sidebar.sb_edit_asset') }}
            <?php } ?>
        </header>
        <div class="panel-body">
            @if(Session::has('error'))
                <div class="alert alert-danger fad in">
                    <button type="button" class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('error') }}
                </div>
            @endif

            <?php if(isset($_GET['adjust'])){?>
            <form action="{{route('adjust_asset', [$res->id])}}" method="POST" class="cmxform form-horizontal" id="assetForm">
            <?php }else{ ?>
            <form action="{{route('edit_asset', [$res->id])}}" method="POST" class="cmxform form-horizontal" id="assetForm">
            <?php } ?>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <fieldset>
                    <table class="table table-4 borderless">
                        <tr>
                            <td class="i-label">{{ trans('multiple.branch') }}<span class="red"> *</span></td>
                            <td>
                                <select class="form-control" name="branch" id="branch">
                                    <option value="0">-</option>
                                    @foreach($branches as $a)
                                        <option value="{{ $a->id }}" @if($a->id==$res->branch) selected @endif>{{ $a->branch_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="i-label">{{ trans('asset.asset_name') }}<span class="red"> *</span></td>
                            <td><input type="text" name="asset_name" class="form-control" id="asset_name" value="{{ old('asset_name', $res->asset_name) }}" /></td>
                        </tr>

                        <tr>
                            <td class="i-label">{{ trans('multiple.category') }}<span class="red"> *</span></td>
                            <td>
                                <select class="select2_type category" style="width: 300px;" name="category" id="category">
                                    <option value="0">-</option>
                                    @foreach($asset_categories as $key=>$val)
                                        <option value="{{ $key }}" @if($key==$res->category) selected @endif>{{ $key }} - {{ $val }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="i-label">{{ trans('asset.purchased_date') }}<span class="red"> *</span></td>
                            <td>
                                <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" class="input-append date dpYears">
                                    <input type="text" name="purchased_date" size="16" class="form-control" value="{{ old('purchased_date', $res->purchased_date) }}" />
                                    <span class="add-on birhtdateDatepicker">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="i-label">{{ trans('multiple.location') }}<span class="red"> *</span></td>
                            <td>
                                <select class="select2_type location" style="width: 300px;" name="location" id="location">
                                    <option value="0">-</option>
                                    @foreach($asset_locations as $key=>$val)
                                        <option value="{{ $key }}" @if($key==$res->location) selected @endif>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="i-label">{{ trans('asset.original_cost') }}<span class="red"> *</span></td>
                            <td><input type="text" name="original_cost" class="form-control" id="original_cost" value="{{ old('original_cost', $res->original_cost) }}" /></td>
                        </tr>

                        <tr>
                            <td class="i-label">{{ trans('asset.classification') }}<span class="red"> *</span></td>
                            <td>
                                <select class="form-control" name="classification" id="classification">
                                    <option value="0">-</option>
                                    @foreach($asset_classifications as $key=>$val)
                                        <option value="{{ $key }}" @if($key==$res->classification) selected @endif>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="i-label">{{ trans('asset.depre_year') }}<span class="red"> *</span></td>
                            <td>
                                <input type="text" name="depre_year" class="form-control" id="depre_year" value="{{ old('depre_year', $res->depre_year) }}" />
                            </td>
                        </tr>

                        <tr>
                            <td class="i-label">{{ trans('asset.currency') }}<span class="red"> *</span></td>
                            <td>
                                <select class="form-control" name="currency" id="currency">
                                    <option value="0">-</option>
                                    @foreach($currency as $key=>$val)
                                        <option value="{{ $key }}" @if($key==$res->currency) selected @endif>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="i-label">{{ trans('asset.rate') }}<span class="red"> *</span></td>
                            <td><input type="text" name="rate" class="form-control" id="rate" value="{{ old('rate', $res->rate) }}" /></td>
                        </tr>

                        <tr>
                            <td class="i-label">{{ trans('asset.invoice_num') }}<span class="red"> *</span></td>
                            <td><input type="text" name="invoice_num" class="form-control" id="invoice_num" value="{{ old('invoice_num', $res->invoice_num) }}" /></td>
                            <td class="i-label">{{ trans('asset.supplier') }}<span class="red"> *</span></td>
                            <td><input type="text" name="supplier" class="form-control" id="supplier" value="{{ old('supplier', $res->supplier) }}" /></td>
                        </tr>

                        <tr>
                            <td class="i-label">{{ trans('asset.tag_num') }}<span class="red"> *</span></td>
                            <td colspan="3"><input type="text" name="tag_num" class="form-control" id="tag_num" value="{{ old('tag_num', $res->tag_num) }}" /></td>
                        </tr>

                        <?php if(isset($_GET['adjust'])){?>
                        <tr>
                            <td class="i-label">{{ trans('asset.approved_by') }}</td>
                            <td colspan="3">
                                    <select class="form-control" name="approved_by" id="approved_by">
                                        <option value="0">-</option>
                                        @foreach($users as $a)
                                            <option value="{{ $a->id }}" @if($a->id==$res->name) selected @endif>{{ $a->name }}</option>
                                        @endforeach
                                    </select>
                            </td>
                        </tr>
                        <?php } ?>

                        <tr>
                            <td class="i-label">{{ trans('asset.remark') }}</td>
                            <td colspan="3"><textarea name="remark" class="form-control" id="remark">{{ old('remark', $res->remark) }}</textarea></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td colspan="3">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                                <a href="javascript:history.go(-1)" class="btn btn-danger"><i class="fa fa-times-circle"></i>&nbsp;{{ trans('multiple.m_cancel') }}</a>
                            </td>
                        </tr>


                    </table>
                </fieldset>
            </form>
        </div>
    </section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure)?false:false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure)?false:false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure)?false:false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure)?false:false) }}"></script>
<script src="{{ asset('theme/js/select2/select2.js',isset($secure)?false:false) }}"></script>
<script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure)?false:false) }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });

        $(document).on('change', '#category', function () {
           ajax_tag_num();
        });

        $(document).on('change', '#classification', function () {
           ajax_tag_num();
        });

        $(document).on('change', '#branch', function () {
           ajax_tag_num();
        });
    });

    function ajax_tag_num(){
         $.ajax({
            url: "{{route('ajax_tag_num')}}",
            type:'GET',
            data:'category='+$('#category').val()+'&classification='+$('#classification').val()+'&branch='+$('#branch').val()+'&tag_num='+$('#tag_num').val(),
            success:function(res){
                $('#tag_num').val(res);
            }
        });
    }
    $(".select2_type").select2();
</script>
@endsection
