@extends('layouts.app')

@section('css')
<link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
<style>
	@media print {
	   .hide-this{
	     display: none !important;
	   }
	}
</style>
@endsection

@section('content')
<section class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        <span>{{ trans('sidebar.sb_user_summary') }}</span>
                        <div style="float:right;">
                            <span><a href="{{route ('add_user') }}" class="btn btn-success"><i class="fa fa-plus"></i>{{ trans('sidebar.sb_add_user') }}</a></span>
                            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                            <button class="btn btn-primary" id="export"><i class="fa fa-sign-out"></i> {{ trans('multiple.export') }}</button>
                        </div>
                    </header>
                    <div class="position-center">
                    <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('all_user') }}">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name" class="col-lg-4 control-label">{{ trans('user.u_user_username') }}</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" id="name" value="{{ $name }}" name="name">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                    <br/><br/>
                    <form class="form-horizontal"  id="search_frm">
                        <input type="hidden" name="offset" />
                        <div class="page">
                            <div class="custom-pagi">
                                <span class="pagi_label">Number of Rows:</span>
                                <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                                <a href="#" class="btn btn-danger">Go</a>
                            </div>
                        </div>
                    </form>
                    <br/><br/>
                    <div class="panel-body">
                        <section id="flip-scroll">
                          <div id="printArea">
							@include('api.report_header')
                            <table class="table table-bordered table-striped table-condensed cf" id="user_list">
                                <thead class="cf">
                                    <tr>
                                        <th style="text-align: center;">{{ trans('multiple.m_no')  }}</th>
                                        <th style="text-align: center;">{{ trans('user.u_user_code' ) }}</th>
                                        <th style="text-align: center;">{{ trans('user.u_user_name') }}</th>
                                        <th style="text-align: center;">{{ trans('user.u_user_kh_name') }}</th>
                                        <th style="text-align: center;">{{ trans('user.u_user_username') }}</th>
                                        <th style="vertical-align: middle;text-align: center;">{{ trans('company.company') }}</th>
                                        <th style="text-align: center;">{{ trans('multiple.m_email') }}</th>
                                        <th style="text-align: center;">{{ trans('multiple.m_phone',['num'=>'']) }}</th>
                                        <th style="text-align: center;">{{ trans('multiple.m_address') }}</th>
                                        <th style="text-align: center;">{{ trans('multiple.m_status') }}</th>
                                        <th style="text-align: center;">{{ trans('multiple.m_role') }}</th>
                                        <th style="text-align: center;" class="hide-this">{{ trans('multiple.m_action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $n = 1;?>
                                    @if(!empty($users))
                                    @forelse($users as $u)
                                    <tr>
                                        <td align="center">{{ $n++ }}</td>
                                        <td align="center">{{ str_pad($u->id,6,0,STR_PAD_LEFT) }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ !empty($u->kh_name)? $u->kh_name : '-'}}</td>
                                        <td align="center">{{ $u->username }}</td>
                                        <td align="center">{{ !empty($u->get_branch->branch_name)? $u->get_branch->branch_name: '-' }}</td>
                                        <td align="center">{{ !empty($u->email)? $u->email: '-' }}</td>
                                        <td align="center">{{ !empty($u->phone)? $u->phone: '-' }}</td>
                                        <td>{{ !empty($u->address)? $u->address: '-' }}</td>
                                        <td id="{{$u->id}}" align="center">{{ ($u->status == 0)?'Inactive': 'Active' }}</td>
                                        <td id="{{$u->id}}" align="center">{{ $u->role->role_name }}</td>
                                        <td align="center" class="hide-this">
                                            <a href="{{ route('user_detail',[$u->id]) }}" class="btn btn-default btn-xs" title="Detail"><i class="fa fa-search-minus"></i></a>
                                            <a href="{{ route('edit_user',[$u->id]) }}" class="btn btn-default btn-xs" title="Edit"><i class="fa fa-pencil"></i></a>
                                            <a href="{{ route('make_permission',[$u->id]) }}" class="btn btn-default btn-xs" title="Permission"><i class="fa fa-key"></i></a>
                                            @if($u->status ==0)
                                            <a href="javascript:;" class="btn btn-default btn-xs active-user" data-id="{{ $u->id }}" data-status="1" title="Activate"><i class="fa fa-check-circle"></i><span class="title"></span></a>
                                            @else
                                            <a href="javascript:;" class="btn btn-default btn-xs active-user" data-id="{{ $u->id }}" data-status="0" title="Inactivate"><i class="fa fa-times-circle"></i> <span class="title"></span></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan=10>{{ trans('multiple.m_no_result') }}</td></tr>
                                    @endforelse
                                    @else
                                    <tr><td colspan=10>{{ trans('multiple.m_no_result') }}</td></tr>
                                    @endif
                                </tbody>
                            </table>
                          </div>
                        </section>
                    </div>
                    <div class="page">
                        <?PHP
                        echo $users->appends([
                            'offset' => Input::get('offset')
                        ])->render();
                        ?>
                    </div>
                </section>
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/users.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript">
$(document).ready(function () {
    //pagination
    $('.custom-pagi a').on('click', function () {
        var val = $(this).parent().find('input[name="set_offset"]').val();
        $('input[name="offset"]').val(val);
        $('#search_frm').submit();
        return false;
    });
});
	$("#export").click(function (event) {
		var con = confirm("Do you really want to export to CSV file?");
		if(con == true){
				new TableExport(document.getElementById('user_list'), {
						formats: ['csv']
				});
				$('button.csv').hide().click();
				$('.tableexport-caption').remove();
		}
	});
</script>
@endsection
