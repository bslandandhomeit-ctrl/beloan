@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/jquery-multi-select/css/multi-select.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/jquery-tags-input/jquery.tagsinput.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
@endsection
<style type="text/css">
    .form-horizontal .form-group {
        margin-right: 0px !important;
        margin-left: 0px !important;
    }
    .form-group {
        margin-bottom: 5px !important;
    }
    .hiden{
            display: none;
        }
</style>
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                Sale Person : Add New
                </header>
                <div class="panel-body">
                    @if(Session::has('error'))
                        <div class="alert alert-danger fade in">
                            <button class="close close-sm" data-dismiss="alert">x</button>
                            {{ Session::get('error') }}
                        </div>
                    @endif
                    @if(Session::has('msg'))
                        <div class="alert alert-success fade in">
                            <button class="close close-sm" data-dismiss="alert">x</button>
                            {{ Session::get('msg') }}
                        </div>
                    @endif

                    <form class="cmxform form-horizontal" method="post" action="{{route('add_saleperson')}}" id="frmDealer" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('representative.name') }}<span class="red-color"> *</span></label>
                                    <input type="text"  id="name" name="name" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                <label class="control-label">Sale Team Lavel<span class="red-color"> *</span></label>
                                <select class="form-control" id="lavel" name="lavel" required>
                                <option value="">Select sale team</option>
                                        <option value="1">Sale Team L1</option>
                                        <option value="2" >Sale Team L2</option>
                                        <option value="3">Sale Team L3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 hiden sale_person_parent_l1">
                                <label class="control-label">Sale Team L1 | នៅក្រោម Team Lavel 1</label>
                                <select id="sale_person_parent_l1" style="width: 100%" name="sale_person_parent_l1" required>
                                <option value="">-</option>
                                    @foreach($saleRepresentativel1 as $c)
                                        <option value={{$c['id']}} @if($sacc->co==$c['id']) selected="selected" @endif>{{$c['name']}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-md-12 hiden sale_person_parent_l2">
                                <label class="control-label">Sale Team L2 | | នៅក្រោម Team Lavel 2</label>
                                <select id="sale_person_parent_l2" style="width: 100%" name="sale_person_parent_l2" required>
                                <option value="">-</option>
                                    @foreach($saleRepresentativel2 as $c)
                                        <option value={{$c['id']}} @if($sacc->co==$c['id']) selected="selected" @endif>{{$c['name']}}</option>
                                    @endforeach
                                </select>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('representative.gender') }}<span class="red-color"> *</span></label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('representative.dob') }}</label>
                                    <input type="date" id="dob" name="dob" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">{{ trans('representative.national_id') }} <span
                                    class="red-color">*</span></label>
                                <input type="text"  id="national_id" name="national_id" class="form-control" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('representative.contact_number') }}<span class="red-color"> *</span></label>
                                    <input type="text"  id="phone" name="phone" class="form-control" required/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12" style="text-align: right;">
                                <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp; Save
                                </button>
                            </div>
                            <br/><br/>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript"
            src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/jquery-multi-select/js/jquery.multi-select.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/jquery-multi-select/js/jquery.quicksearch.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select-init.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
    <script>
        $(document).ready(function () {
            $("#sale_person_parent_l1").select2();
            $("#sale_person_parent_l2").select2();
            $('#e9').addClass('required');
            $('#e9').on('change', function (e) {
                if ((e.val).length > 0) {
                    $(this).parent().find('label.error').css({'display': 'none'});
                    $(this).parent().find('.error').removeClass('error').addClass('valid');
                }
            });
            $(document).on('change', '#lavel', function() {
                var lavel = $(this).val();
                if(lavel==2){
                    $(".sale_person_parent_l1").css('display','block');
                    $(".sale_person_parent_l2").css('display','none');
                }else if(lavel==3){
                    $(".sale_person_parent_l2").css('display','block');
                    $(".sale_person_parent_l1").css('display','none');
                }else if(lavel==1){
                    $(".sale_person_parent_l2").css('display','none');
                    $(".sale_person_parent_l1").css('display','none');
                    $("#sale_person_parent_l1").val('').trigger('change');
                    $("#sale_person_parent_l2").val('').trigger('change');
                
                }else{
                    $(".sale_person_parent_l2").css('display','none');
                    $(".sale_person_parent_l1").css('display','none');
                    $("#sale_person_parent_l1").val('').trigger('change');
                    $("#sale_person_parent_l2").val('').trigger('change');
                }
            });
        });
    </script>
@endsection