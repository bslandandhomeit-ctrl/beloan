@extends('layouts.app')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/jquery-multi-select/css/multi-select.css',isset($secure) ? false : false) }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/jquery-tags-input/jquery.tagsinput.css',isset($secure) ? false : false) }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}" />
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
             <header class="panel-heading">
                {{ trans('dealer.del_edit_dealer')}}
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

                <form class="cmxform form-horizontal" method="post" action="{{route('edit_dealer', [$dealer->id])}}" id="frmDealer" enctype="multipart/form-data">
                    <input type="hidden" name="d_id" id="d_id" value="{{ $dealer->id }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_name') }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" value="{{$dealer->dealer?$dealer->dealer:old('dealer')}}" class="form-control" name="dealer" id="dealer">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_representative') }}  <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" value="{{$dealer->representative?$dealer->representative:old('representative')}}" class="form-control" name="representative" id="representative" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_phone',['num'=>1]) }} <span class="red-color">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" value="{{$dealer->phone?$dealer->phone:old('phone')}}" name="phone" id="phone" class="form-control" data-mask="999-999-999?9"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_phone',['num'=>2]) }}</label>
                        <div class="col-sm-6">
                            <input type="text" value="{{$dealer->phone2?$dealer->phone2:old('phone1')}}" name="phone1" id="phone1" class="form-control" data-mask="999-999-999?9"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_email') }}</label>
                        <div class="col-sm-6">
                            <input type="email" value="{{$dealer->email?$dealer->email:old('email')}}" name="email" id="email" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_location') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="location" name="location">{{$dealer->location?$dealer->location:old('location')}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_description') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="description" name="description">{{$dealer->description?$dealer->description:old('description')}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('dealer.dl_dealer_bank') }} </label>
                        <div class="col-sm-6">
                            <table id="tbDelBank" class="table-striped table-condensed" style="width:100%">
                                @foreach($dealer_bank as $db)
                                    <tr id="{{$db->bank->id}}"><td>{{ $db->bank->account_name.' ('.$db->bank->account_number.')' }}</td>
                                        @if(count($dealer_bank)>1)
                                            <td id="{{$dealer->id}}" style="text-align:right"><a href="javascript:;" class="fa fa-trash-o red-color" style="font-size:20px;"></a></td>
                                        @else
                                            <td id="{{$dealer->id}}" style="text-align:right"><i class="fa fa-trash-o" style="font-size:20px;"></i></td>
                                        @endif
                                    </tr>
                                @endforeach
                                <tr id="add"><td></td><td style="text-align:right"><a href="javascript:;" id="btnAdd" class="fa fa-plus-square primary-color" style="font-size:20px;"></a></td></tr>
                            </table>

                            <br/>
                            <div class="input-group" id="inputgroup">
                                <select multiple="multiple" name="e9[]" id="e9" style="width:100%" class="populate">
                                    <optgroup label="{{ trans('dealer.dl_dealer_account_name') }}" id="opt">
                                        @if(!$flag)
                                            @if(count($remain_b)>0)
                                                @foreach($remain_b as $obj)
                                                    <option value="{{ $obj->id }}">{{ $obj->account_name.' ('.$obj->account_number.')' }}</option>
                                                @endforeach
                                            @else
                                                @foreach($banks as $b)
                                                    <option value="{{ $b->id }}">{{ $b->account_name.' ('.$b->account_number.')' }}</option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </optgroup>
                                </select>
                                <span class="input-group-btn" style="vertical-align: top">
                                    <a class="btn btn-info" href="#addBank" data-toggle="modal">
                                        <i class="fa fa-plus"></i>
                                         {{ trans('multiple.m_add') }}
                                    </a>
                                 </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('multiple.m_photo') }}</label>
                        <div class="col-sm-6">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                    <img src="{{ $dealer->photo?asset('data/dealers/'.$dealer->photo,true):asset('images/noimage.gif',true) }}" alt="" />
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                <div>
                                   <span class="btn btn-white btn-file">
                                       <span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{ trans('multiple.m_select_image') }}</span>
                                       <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                                       <input type="file" class="default" name="photo" id="photo" accept="image/*"/>
                                   </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3"></label>
                        <div class="col-sm-6">
                            <button type="submit" id="button" class="btn btn-success"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ trans('multiple.m_update') }}</button>
                            <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i>&nbsp;&nbsp;{{ trans('multiple.m_reset') }}</button>
                        </div>
                        <br/><br/>
                    </div>
                </form>
             </div>
             <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addBank" class="modal fade">
                 <div class="modal-dialog">
                     <div class="modal-content">
                         <div class="modal-header">
                             <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                             <h4 class="modal-title"> {{ trans('dealer.del_add_new_bank') }}</h4>
                         </div>
                         <div class="modal-body">

                             <form role="form" method="post" id="frmBankAcc" class="cmxform">
                                 <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                                 <div class="form-group">
                                     <label class="control-label">{{ trans('dealer.dl_dealer_bank_name') }} <span class="red-color">*</span></label>
                                     <input type="text" name="bank_name" id="bank_name" class="form-control"/>
                                 </div>
                                 <div class="form-group">
                                     <label class="control-label">{{ trans('dealer.dl_dealer_account_name') }} <span class="red-color">*</span> </label>
                                     <input type="text" name="account_name" id="account_name" class="form-control"/>
                                 </div>
                                 <div class="form-group">
                                     <label class="control-label">{{ trans('dealer.dl_dealer_account_number') }} <span class="red-color">*</span> </label>
                                     <input type="text" name="account_number" id="account_number" class="form-control"/>
                                 </div>
                                 <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                                 <a data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</a>
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
    <script type="text/javascript" src="{{ asset('theme/js/jquery-multi-select/js/jquery.multi-select.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery-multi-select/js/jquery.quicksearch.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/select-init.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('js/form-validate.js',isset($secure) ? false : false) }}"></script>
    <script>
        $("table#tbDelBank td a").click(function(){
            if($(this).parent().parent().attr("id")!="add"){
                var d = confirm("Are you sure?");
                if(d == true){
                    var b_id = $(this).parent().parent().attr("id");
                    var tr = $(this).parent().parent();
                    var d_id = $(this).parent().attr("id");
                    $.ajax({
                        url: '/dealer/delbank',
                        dataType: "json",
                        data: { dealer_id : d_id, bank_id : b_id},
                        success: function(data)
                        {
                            if(data.status == true) {
                                tr.fadeOut('slow', function() {
                                    $(this).remove();
                                    if($('#tbDelBank tr').length == 2){
                                        var td = $('#tbDelBank tr:first').find('td:nth-child(2)');
                                        td.html('');
                                        td.html('<i class="fa fa-trash-o" style="font-size:20px;"></i>');
                                    }
                                });
                                $("#e9 option").each(function() {
                                    $(this).remove();
                                });
                                if(!data.flag){
                                    if(data.remain_b.length>0){
                                        for(var i=0;i<data.remain_b.length;i++){
                                            $("#e9 optgroup")
                                                .append($("<option></option>")
                                                .attr("value", data.remain_b[i].id)
                                                .text(data.remain_b[i].account_name + " ("+data.remain_b[i].account_number+")")
                                                );
                                        }
                                    }else{
                                        for(var i=0;i<data.banks.length;i++){
                                            $("#e9 optgroup")
                                                .append($("<option></option>")
                                                .attr("value", data.banks[i].id)
                                                .text(data.banks[i].account_name + " ("+data.banks[i].account_number+")")
                                                );
                                        }
                                    }
                                }



                            }else{
                                alert("Error in deleting.");
                            }
                        },
                        error: function(xhr, textStatus, errorThrown)
                        {
                            // console.log(xhr.status);
                             //console.log(xhr.responseText);
                            // console.log(errorThrown);
                        }
                    });
                }
            }
        });
    </script>
@endsection