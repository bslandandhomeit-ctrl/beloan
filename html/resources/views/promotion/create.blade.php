@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/jquery-multi-select/css/multi-select.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/jquery-tags-input/jquery.tagsinput.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
@endsection
<style type="text/css">
    .form-horizontal .form-group {
        margin-right: 0px !important;
        margin-left: 0px !important;
    }
    .form-group {
        margin-bottom: 5px !important;
    }
    a.select2-choice {
        height: 31px !important;
    }
    .msg{text-align: center;}
    .border-red{
        border: 1px solid red !important;
        border-bottom: 1px solid red !important;
        background-color: #e91e6305;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    {{ trans('promotion.create_promotion') }}
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

                    <form class="cmxform form-horizontal" method="post" action="{{route('add_promotion')}}" id="frm_promotion">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('promotion.name') }}:<span class="red-color"> *</span></label>
                                    <input type="text" id="name" name="name" class="form-control" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('promotion.start_date') }}:</label>
                                    <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd" class="input-append date start_date">
                                        <input type="text" id ="start_date"  name="start_date" size="16" class="form-control" >
                                        <span class="add-on date">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('promotion.end_date') }}:</label>
                                    <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy-mm-dd" class="input-append date end_date">
                                        <input type="text" id ="end_date"  name="end_date" size="16" class="form-control" >
                                        <span class="add-on date">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('promotion.discount_amount') }}:</label>
                                    <div class="input-group" style="margin-top: 5px;">
                                        <span class="input-group-btn" style="vertical-align:top;">
                                            <a class="btn btn-light" href="#" style="background-color: #ddd;">
                                                $
                                            </a>
                                        </span>
                                        <input type="text" id="discount_amount" name="discount_amount" class="form-control"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-gorup">
                                <div class="col-sm-12">
                                    <label class="control-label">{{ trans('unit.unit_type') }}:<span class="red-color"> *</span></label>
                                    <div class="input-group" style="margin-top: 5px;">
                                        <span class="input-group-btn" style="vertical-align:top;">
                                            <a class="btn btn-light" href="#" style="background-color: #ddd;">
                                                {{ trans('promotion.find_unit_to_include') }}:
                                            </a>
                                        </span>
                                        <select class="select2" id="get_unit_type" style="width:100%">
                                            <option value="">{{ trans('promotion.search_for_a_unittype') }}</option>
                                            @foreach($unit_type as $unit_types)
                                                <option value="{{ $unit_types->id }}">{{ $unit_types->name }} ({{ isset($unit_types->Projects->dealer)?$unit_types->Projects->dealer:''}} - {{  isset($unit_types->Projects->short_code)?$unit_types->Projects->short_code:'' }} )</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div><br><br><br><br>
                            <div class="col-sm-12">
                                <section class="panel" style="border: 1px solid #ddd;">
                                    <header class="panel-heading">
                                        <div class="row rows">
                                            <div class="col-sm-6">
                                                {{ trans('promotion.name') }}
                                            </div>
                                        </div>
                                    </header>
                                    <div class="panel-body" id="content" style="padding: 0px 15px !important;">
                                    </div>
                                </section>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12" style="text-align: right;">
                                <button type="button" id="button" class="btn btn-primary btn_submit"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_save') }}
                                </button>
                                <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>&nbsp;&nbsp;{{ trans('multiple.m_reset') }}
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
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery-multi-select/js/jquery.multi-select.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery-multi-select/js/jquery.quicksearch.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select-init.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',false)}}"></script>
    <script>
        var data_arr = new Array();
        $(document).ready(function () {
            $('#e9').addClass('required');
            $('#e9').on('change', function (e) {
                if ((e.val).length > 0) {
                    $(this).parent().find('label.error').css({'display': 'none'});
                    $(this).parent().find('.error').removeClass('error').addClass('valid');
                }
            });
            $('#discount_amount').on('input',function(evt) {
                numeric_only(evt);
            }); 
            $('.start_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            $('.end_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            $('body').on('click','.btn_submit',function(){
                var name = $('#name').val();
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var discount_amount = $('#discount_amount').val();
                if(data_arr.filter(item => item)){
                    $.ajax({
                        url: '{{route('add_promotion')}}',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            unit_type: data_arr.filter(item => item), 
                            name:name,
                            start_date:start_date,
                            end_date:end_date,
                            discount_amount:discount_amount,
                            _token:'{{ csrf_token() }}'
                        },
                        beforeSend:function(){
                            $('.btn_submit').prop('disabled',true);
                        },
                        success:function(data){
                            if(data.status == 1){
                                window.location.reload();
                            }else{
                                window.location.reload();
                            }
                            $('.btn_submit').prop('disabled',false);
                        },
                        error: function (xhr, desc, err)
                        {
                            $('.btn_submit').prop('disabled',false);
                        }
                    });
                }
            });
            $("#get_unit_type").select2();

            $("#get_unit_type").change(function(event) {
                var id = event.target.value;
                if(id != ''){
                    $.ajax({
                        url: '{{ route('get_unit_type') }}',
                        type: 'GET',
                        dataType: 'json',
                        data: {id: id},
                        success:function(response){
                            if(response.status == 0){
                                $('#content').html(response.msg);
                            }else{
                                var obj_data = {
                                    id: response.id,
                                    name: response.name,
                                    short_code: response.short_code,
                                }
                                if(data_arr[id] == null){
                                    data_arr[id] = obj_data;
                                    $('.msg').hide();
                                    renderHtml();
                                }else{
                                    $('#row'+id+'').addClass('border-red').siblings().removeClass('border-red');
                                    return false;
                                } 
                            }
                        }
                    });
                }
            });

            $('body').on('click','.btn_remove',function(){
                var id = $(this).attr('data-id');
                var eThis = $(this);
                if(confirm('Do you want to delete?') == true){
                    delete data_arr[id];
                    eThis.parents('.rows').remove();
                }
                renderHtml();
            });
        });
        renderHtml();
        function renderHtml(){
            var html = '';
            if(data_arr.filter(item => item) != ''){
                data_arr.forEach(function(data){
                    html += `
                        <div class="row rows" id="row${data.id}" style="padding: 10px 0px 10px;border-bottom: 1px solid #eee;">
                            <div class="col-sm-10">
                                <input type="hidden" value="${data.id}" name="unit_type_id[]">
                                <p><b>${data.short_code}</b></p>
                                <p>${data.name}</p>
                            </div>
                            <div class="col-sm-2 text-right">
                                <button type="button" class="btn btn-danger btn_remove" data-id="${data.id}" style="background-color: #d9534f !important;">{{ trans('multiple.m_remove') }}</button>
                            </div>
                       </div>
                    `;
                });
            }else{
                html += `<p class="msg">Data not found.</p>`;
            }
            $('#content').html(html);
        }
        // Allow Numeric Only and max character
        function numeric_only(evt){
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && charCode > 57){
                // evt.target.value = evt.target.value.replace(/[^0-9]/g,'');
                return false;
            }else{
                evt.target.value = evt.target.value.replace(/[^0-9-.]/g,'');
            }   
            return true;
        }
    </script>
@endsection