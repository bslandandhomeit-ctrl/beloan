@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false)}}" />
<link rel="stylesheet" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}">
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
           {{ trans('loan.l_add_loan_document') }} #{{ $loan_id }}
        </header>

        <div class="panel-body">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
            @endif
            <form class="cmxform form-horizontal" method="post" action="{{ route('loan_add_document',[$loan->id]) }}" id="addDocumentForm" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="loan_id" value="{{ $loan_id }}" />
                <div class="col-sm-12">
                	<table class="table table-hover table-bordered document_upload">
                		<?php 
                			//dd($arr);
                		?>
                		@foreach($arr as $key => $value)
                			@if($key=='lad')
                				@var $i = 0;
                				<tr>
                					<td colspan="5"><strong>{{ $arrt['i_d'] }}</strong></td>
                				</tr>	 
                			@endif
                			
                			@if($key=='la')
                				@var $i = 0;
                				<tr>
                					<td colspan="5"><strong>{{ $arrt['ii_d'] }}</strong></td>
                				</tr>
                				<tr>
                					<td colspan="5">{{ $arrt['ii_d_a'] }}</td>
                				</tr>	 	 
                			@endif
                			
                			@if($key=='lmotd')
                				<tr>
                					<td colspan="5">{{ $arrt['ii_d_b'] }}</td>
                				</tr>	 	 
                			@endif
                			
                			@if($key=='bid')
                				<tr>
                					<td colspan="5">{{ $arrt['ii_d_c'] }}</td>
                				</tr>	 	 
                			@endif
                			
                			@if($key=='dr')
		                		<?php 
		                			//dd($arrt['iii_d']);
		                		?>
                				@var $i = 0;
                				<tr>
                					<td colspan="5"><strong>{{ $arrt['iii_d'] }}</strong></td>
                				</tr>	 
                			@endif
                			@if($key=='add')
                				@var $i = 0;
                				<tr>
                					<td colspan="5"><strong>{{ $arrt['iiii_d'] }}</strong></td>
                				</tr>	 
                			@endif
                			
                			
                			@if($key=='otd')
                				@var $i = 5;
                			@endif
                			
                			@if($key=='vsp')
                				@var $i = 6;
                			@endif
                			
                			@if($key=='ol_1')
                				@var $i = 4;
                			@endif
                			
                			@var $i =  $i+1
                			@var $checked = '';
                			@var $note = '';
                			
                			@if($key=='ord_1')
                				@var $i = 11.1;
                			@endif
                			@if($key=='ord_2')
                				@var $i = 11.2;
                			@endif
                			@if($key=='ord_3')
                				@var $i = 11.3;
                			@endif
                			@if($key=='ord_4')
                				@var $i = 11.4;
                			@endif
                			@if($key=='ord_5')
                				@var $i = 11.5;
                			@endif
                			@if($key=='ord_6')
                				@var $i = 11.6;
                			@endif
                			@if($key=='ord_7')
                				@var $i = 11.7;
                			@endif
                			@if($key=='ord_8')
                				@var $i = 11.8;
                			@endif
                			@if($key=='ord_9')
                				@var $i = 11.9;
                			@endif
                			@if($key=='ord_10')
                				@var $i = '11.10';
                			@endif
                			
                			@if($key=='ha_1')
                				@var $i = 3.1;
                			@endif
                			@if($key=='ha_2')
                				@var $i = 3.2;
                			@endif
                			@if($key=='ha_3')
                				@var $i = 3.3;
                			@endif
                			@if($key=='ha_4')
                				@var $i = 3.4;
                			@endif
                			@if($key=='ha_5')
                				@var $i = 3.5;
                			@endif
                			@if($key=='ha_6')
                				@var $i = 3.6;
                			@endif
                			@if($key=='ha_7')
                				@var $i = 3.7;
                			@endif
                			
                			@if($key=='ol_1')
                				@var $i = 5.1;
                			@endif
                			@if($key=='ol_2')
                				@var $i = 5.2;
                			@endif
                			@if($key=='ol_3')
                				@var $i = 5.3;
                			@endif
                			@if($key=='ol_4')
                				@var $i = 5.4;
                			@endif
                			@if($key=='ol_5')
                				@var $i = 5.5;
                			@endif
                			@if($key=='ol_6')
                				@var $i = 5.6;
                			@endif
                			@if($key=='ol_7')
                				@var $i = 5.7;
                			@endif
                			
                			@if($key=='otd_1')
                				@var $i = 6.1;
                			@endif
                			@if($key=='otd_2')
                				@var $i = 6.2;
                			@endif
                			@if($key=='otd_3')
                				@var $i = 6.3;
                			@endif
                			@if($key=='otd_4')
                				@var $i = 6.4;
                			@endif
                			@if($key=='otd_5')
                				@var $i = 6.5;
                			@endif
                			@if($key=='otd_6')
                				@var $i = 6.6;
                			@endif
                			@if($key=='otd_7')
                				@var $i = 6.7;
                			@endif
                			
                			@if($key=='lmotd_1')
                				@var $i = 4.1;
                			@endif
                			@if($key=='lmotd_2')
                				@var $i = 4.2;
                			@endif
                			@if($key=='lmotd_3')
                				@var $i = 4.3;
                			@endif
                			@if($key=='lmotd_4')
                				@var $i = 4.4;
                			@endif
                			@if($key=='lmotd_5')
                				@var $i = 4.5;
                			@endif
                			@if($key=='lmotd_6')
                				@var $i = 4.6;
                			@endif
                			@if($key=='lmotd_7')
                				@var $i = 4.7;
                			@endif
                			
                			
                			<tr>
                				<td>
                					{{ $i }}
                					<div>
	                					@if(isset($arr_doc_type[$key]))
	                						<span class="hide loan_document_id">{{ $arr_docid[$key] }}</span>
	                					@endif
                					</div>
                				</td>
                				<td>{{ $value }}</td>
                				<td class="fupload">
                					<input type="file" name="{{ $key }}" class="default" />
                					<div>
                						@if(isset($arr_doc_type[$key]))
                							<a href="{{ asset('data/loans/documents') }}/{{ $arr_doc_photo[$key] }}" target="_blank">{{ $arr_doc_photo[$key] }}</a>
                							@var $checked = 'checked = "checked"';
                							@var $note = $arr_note[$key];
                						@endif
                					</div>
                				</td>
                				<td>
                					<textarea name="note[]" class="form-control" style="height: 35px;">{{ $note }}</textarea>
                				</td>
                				<td><input type="checkbox" value="1" name="dcheck[]"{{ $checked }} /></td>
                			</tr>
                			
                		@endforeach
                	</table>
                </div>
                
                <div class="col-sm-12">
                	<a class="btn btn-primary" href="{{ $loan_detail }}"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</a>
                    <button type="button" class="btn btn-danger" onclick="javascript:history.back()"><i class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
<script src="{{ asset('js/bootbox.js',isset($secure) ? false : false) }}"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$('form')[0].reset();
		checkboxAction();
		
		_token = $('input[name="_token"]').val();
		
		//on file upload
		$('.document_upload input[type="file"]').on('change',function(){
			this_ = $(this);
			
			bootbox.confirm({
			    title: "{{ trans('loan.l_title_confirmation') }}",
			    message: "{{ trans('loan.l_add_loan_document_confirm') }}",
			    buttons: {
			        "cancel": {
			            label: "{{ trans('loan.l_no') }}",
			            className: "btn-default"
			        },
			        "confirm": {
			            label: "{{ trans('loan.l_yes') }}",
			            className: "btn-primary"
			        }
			    },
			    callback: function(result) {
			        if (result) {
			        	file = this_.parents('tr').find('input[type="file"]');
						note = this_.parents('tr').find('textarea[name="note[]"]').val();
						loan_id = $('input[name="loan_id"]').val();
						loan_document_id = this_.parents('tr').find('.loan_document_id').text().trim();
						
						formdata = false;
						formdata = new FormData($('#addDocumentForm')[0]);
						formdata.append('filename', file);
						formdata.append('_token', _token);
						formdata.append('fname', file.attr('name'));
						formdata.append('note', note);
						formdata.append('loan_id', loan_id);
						if(loan_document_id!=''){
							formdata.append('loan_document_id', loan_document_id);
							formdata.append('old_file', this_.parents('tr').find('.fupload a').text().trim());
						}
						
						$.ajax({
				            url: '/loans/do_ajax_upload',
				            type: 'POST',
				            dataType: "json",
				            processData: false, 
				            contentType: false,
				            data: formdata,
				            success: function(data){
					            if(data.status=='false'){
						            alert('Could not upload!');
									return false;
						        }
					            
				            	this_.parents('tr').find('td:first div').html('<span class="hide loan_document_id">'+data.loan_document_id+'</span>');
								this_.parents('tr').find('td.fupload div').html('<a style="margin: 10px;" href="'+data.path+'/'+data.photo_name+'" target="_blank">'+data.photo_name+'</a>');
								this_.parents('tr').find('input[type="checkbox"]').parent('td').html('<input type="checkbox" checked="checked" value="1" name="dcheck[]" />');
				            	this_.parents('tr').find('input[type="file"]').val('');
				            	checkboxAction();
					        }
						});
			        }
			    }
			});
		});

		//on textarea note
		$('.document_upload textarea').on('keyup', function(){
			this_ = $(this);
			loan_document_id = this_.parents('tr').find('.loan_document_id').text().trim();
			if(loan_document_id!=''){
				$.ajax({
		            url: '/loans/do_ajax_upload',
		            type: 'POST',
		            dataType: "json",
		            data: {is_update : 1, loan_document_id : loan_document_id, _token : _token, note : this_.val()},
		            success: function(data){
			        }
				});
			}
		});
		
	});


	function checkboxAction(){
		//on checkbox action
		$('.document_upload input[type="checkbox"]').on('change',function(){
			this_ = $(this);
			if(this_.is(":checked")){
				
			}else{
				bootbox.confirm({
				    title: "{{ trans('loan.l_title_confirmation') }}",
				    message: "{{ trans('loan.l_remove_loan_document_confirm') }}",
				    buttons: {
				        "cancel": {
				            label: "{{ trans('loan.l_no') }}",
				            className: "btn-default"
				        },
				        "confirm": {
				            label: "{{ trans('loan.l_yes') }}",
				            className: "btn-danger"
				        }
				    },
				    callback: function(result) {
				        if (result) {
							file = this_.parents('tr').find('input[type="file"]');
							note = this_.parents('tr').find('textarea[name="note[]"]').val();
							loan_id = $('input[name="loan_id"]').val();
							_token = $('input[name="_token"]').val();
							
							//remove back saved data
							loan_document_id = this_.parents('tr').find('.loan_document_id').text();
							old_file = this_.parents('tr').find('.fupload a').text().trim();
							$.ajax({
					            url: '/loans/do_ajax_upload',
					            type: 'POST',
					            dataType: "json",
					            data: {is_delete : 1, loan_document_id : loan_document_id, _token : _token, old_file : old_file},
					            success: function(data){ 
									this_.parents('tr').find('textarea').val('');
									this_.parents('tr').find('input[type="file"]').val('');
									this_.parents('tr').find('td.fupload div').html('');
									this_.parents('tr').find('td:first div').html('');
						        }
							});
				        }else{
							this_.prop("checked", true);	
						}
					}
				});
			}
		});
	}

	
</script>
@endsection