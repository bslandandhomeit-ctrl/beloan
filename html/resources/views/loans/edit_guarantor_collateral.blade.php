@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false) }}" />
    <link rel="stylesheet" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}">
@endsection
@section('content')
    <section class="panel">
            @if(Session::has('message'))
                <div class="alert alert-success alert-block fade in">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                    <p>{{ Session::get('message') }}</p>
                </div>
            @endif
             <?php $static = Config::get('static_data');?>
        <header class="panel-heading">
           {{ trans('loan.l_edit_guarantor_collateral') }}
        </header>
        <div class="panel-body">
            <form class="cmxform form-horizontal" method="post" action="{{ route('edit_guarantor_collateral', [$edits->id]) }}" id="frmGuarantorCollateral" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="id[]" value="{{$edits->id}}">
                <input type="hidden" name="loan_id" value="{{$guarantor->loan_id}}">
                <div class="form-group">
                <label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_collateral_type') }} <span class="red-color">*</span></label>
                    <div class="col-sm-6">
                        <select name="collateral_type[]" id="collateral_type" class="form-control">
                            <option value="">-</option>
                            @foreach($static['collateral_type'] as $key => $value)
                                <option value="{{ $value }}" {{$edits->collateral_type ? $edits->collateral_type == $value ? 'selected' : '' : old('collateral_type') }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_collateral_number') }} <span class="red-color">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" name="collateral_no[]" value="{{$edits->collateral_no}}" id="collateral_no" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_collateral_value') }} <span class="red-color">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" name="collateral_value[]" value="{{$edits->collateral_value}}" id="collateral_value" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_registration_type') }} <span class="red-color">*</span></label>
                    <div class="col-sm-6">
                        <select name="collateral_registration[]" id="collateral_registration" class="form-control">
                            <option value="">-</option>
                            @foreach($static['collateral_regis_type'] as $key => $value)
                                <option value="{{ $value }}" {{$edits->collateral_registration ? $edits->collateral_registration == $value ? 'selected' : '' : old('collateral_regis_type')}}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('multiple.m_photo') }}</label>
                    <div class="col-sm-6">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="{{ $edits->collateral_photo?asset('data/guarantors_collateral/'.$edits->collateral_photo, isset($secure)?false:false):asset('images/noimage.gif', isset($secure)?false:false) }}" alt="" />
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                            <div>
                               <span class="btn btn-white btn-file">
                                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{ trans('multiple.m_select_image') }}</span>
                                   <span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                                   <input type="file" class="default" name="collateral_photo[]" id="collateral_photo"/>
                               </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_collateral_address') }} <span class="red-color">*</span></label>
                    <div class="col-sm-6">
                        <textarea name="collateral_address[]" id="collateral_address" class="form-control">{{$edits->collateral_address}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('multiple.m_note') }}</label>
                    <div class="col-sm-6">
                        <textarea name="note[]" id="note" class="form-control">{{$edits->note}}</textarea>
                    </div>
                </div>
                <div id="addGuarantorCollateral"></div>
                <?php
                    $count = count($collateral);
                    if($count < 3){
                        echo '<div class="form-group">
                            <label class="col-sm-3"></label>
                            <div class="col-sm-6">
                                <button type="button" id="addCollateral" class="btn btn-info"><i class="fa fa-plus"></i>&nbsp;&nbsp;'. trans('loan.l_more').'</button>
                            </div>
                        </div>';
                    }
                ?>
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
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
        var i = <?php echo $count; ?>;
        $("#addCollateral").on('click',function(){
            i++;
            if(i < 3){
                document.getElementById('removeElement_'+ i +'');
            }
            else{
                var disableElement = document.getElementById('addCollateral');
                    disableElement.disabled = "disabled";
            }
            function create(htmlStr) {
                var frag = document.createDocumentFragment(),
                    temp = document.createElement('div');
                temp.innerHTML = htmlStr;
                while (temp.firstChild) {
                    frag.appendChild(temp.firstChild);
                }
                return frag;
            }
            var fragment = create(
                                '<div id="removeElement_'+ i +'">' +
                                    '<div class="col-sm-12">' +
                                        '<hr/>' +
                                    '</div>' +
                                     '<span class="tools pull-right">' +
                                        '<button type="button" onclick="removeDiv('+ i +')" class="fa fa-times btn btn-danger"></button>' +
                                    '</span>' +
                                    '<input type="hidden" name="id[]" value="0">'+
                                    '<input type="hidden" name="guarantor_id" value="{{$edits->guarantor_id}}">'+
                                    '<div class="form-group">' +
                                        '<label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_collateral_type') }} <span class="red-color">*</span></label>' +
                                            '<div class="col-md-8">' +
                                            '<select name="collateral_type[]" id="collateral_type'+ i +'" class="form-control" required>' +
                                                '<option value="">-</option>' +
                                                '@foreach($static['collateral_type'] as $key => $value)' +
                                                    '<option value="{{ $value }}">{{ $value }}</option>' +
                                                '@endforeach' +
                                            '</select>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="form-group">' +
                                        '<label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_collateral_number') }} <span class="red-color">*</span></label>' +
                                            '<div class="col-md-8">' +
                                            '<input type="text" class="form-control" name="collateral_no[]" id="collateral_no'+ i +'" required/>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="form-group">' +
                                        '<label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_collateral_value') }} <span class="red-color">*</span></label>' +
                                            '<div class="col-md-8">' +
                                            '<input type="text" class="form-control" name="collateral_value[]" id="collateral_value'+ i +'"  required/>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="form-group">' +
                                        '<label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_registration_type') }} <span class="red-color">*</span></label>' +
                                            '<div class="col-md-8">' +
                                            '<select name="collateral_registration[]" id="collateral_registration'+ i +'" class="form-control" required>' +
                                                '<option value="">-</option>' +
                                                '@foreach($static['collateral_regis_type'] as $key => $value)' +
                                                    '<option value="{{ $value }}">{{ $value }}</option>' +
                                                '@endforeach' +
                                            '</select>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="form-group">' +
                                    '<label class="col-sm-3 control-label">{{ trans('multiple.m_photo') }}</label>' +
                                        '<div class="col-sm-2">' +
                                            '<div class="fileupload fileupload-new" data-provides="fileupload">' +
                                                '<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">' +
                                                    '<img src="{{ asset('images/noimage.gif', isset($secure)?false:false) }}" alt="" />' +
                                                '</div>' +
                                                '<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>' +
                                                '<div>' +
                                                   '<span class="btn btn-white btn-file">' +
                                                   '<span class="fileupload-new"><i class="fa fa-paper-clip"></i> {{ trans('multiple.m_select_image') }}</span>' +
                                                   '<span class="fileupload-exists"><i class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>' +
                                                   '<input type="file" name="collateral_photo[]" id="collateral_photo" class="default"/>' +
                                                   '</span>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="form-group">' +
                                        '<label class="col-sm-3 control-label">{{ trans('loan.l_guarantor_collateral_address') }} <span class="red-color">*</span></label>' +
                                            '<div class="col-md-8">' +
                                            '<textarea type="text" class="form-control" name="collateral_address[]" id="collateral_address'+ i +'" required></textarea>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="form-group">' +
                                        '<label class="col-sm-3 control-label">{{ trans('multiple.m_note')}}</label>' +
                                            '<div class="col-md-8">' +
                                                '<textarea type="text" class="form-control" name="note[]" id="collateral_note" ></textarea>' +
                                            '</div>' +
                                    '</div>' +
                                '</div>'
            );

            $("#addGuarantorCollateral").append(fragment);
        });

        function removeDiv(rid){
            var removeElement = document.getElementById('removeElement_'+ rid +'');
            console.log(removeElement.parentNode);
                removeElement.parentNode.removeChild(removeElement);
                i = i - 1;
            var disableElement = document.getElementById('addCollateral');
            disableElement.disabled = false;
        }
    </script>
@endsection
