@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/loan-style.css',isset($secure) ? false : false)}}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?php
            $action_type = config('static_data.action_type');
            ?>
            <header class="panel-heading">
                <span>{{ trans('sidebar.s_staff_summary') }}</span>
                <span style="float: right"><a href="{{route ('add_staff') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sidebar.sb_add_new_staff') }}</a></span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    @if($errors->addCate->has('ipCate'))
                    <div class="alert alert-danger fade in">
                        <button class="close close-sm" type="button" data-dismiss="alert">x</button>
                        {{$errors->addCate->first('ipCate')}}
                    </div>
                    @endif

                    <form role="form" class="cmxform form-horizontal" id="search_frm" method="get">
                        <input type="hidden" name="offset" />
                    </form>
                    <div class="page">
                        <div class="custom-pagi">
                            <span class="pagi_label">Number of Rows:</span>
                            <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                            <a href="#" class="btn btn-danger">Go</a>
                        </div>
                    </div>
                </div>
                <br><br><br><br>
                <section id="unseen" class="ox-scroll">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <th style="text-align: center;">{{ trans('multiple.m_no') }}</th>
                        <th style="text-align: center;">{{ trans('staff.s_staff_id') }}</th>
                        <th style="text-align: center;">ID Card Number</th>
                        <th style="text-align: center;">{{ trans('user.u_user_name') }}</th>
                        <th style="text-align: center;">{{ trans('multiple.m_photo') }}</th>
                        <th style="text-align: center;">{{ trans('user.u_user_kh_name') }}</th>
                        <th style="text-align: center;">{{ trans('staff.s_staff_gender') }}</th>
                        <th style="text-align: center;">{{ trans('staff.s_nationality') }}</th>
                        <th style="text-align: center;">{{ trans('staff.s_date_of_birth') }}</th>
                        <th style="text-align: center;">{{ trans('multiple.m_address') }}</th>
                        <th style="text-align: center;">{{ trans('multiple.m_phone',['num'=>'']) }}</th>
                        <th style="text-align: center;">{{ trans('multiple.m_email') }}</th>
                        <th style="text-align: center;">{{ trans('staff.s_start_date') }}</th>
                        <th style="text-align: center;">{{ trans('staff.s_salary') }}</th>
                        <th style="text-align: center;">Role</th>
                        <th style="text-align: center;">{{ trans('staff.s_staff_branch') }}</th>
                        <th style="text-align: center;">{{ trans('multiple.m_action') }}</th>
                        </thead>
                        <?php $n = 1; ?>
                        <tbody>
                            @forelse($staffs as $pro)
                            <tr>
                                <td align="center">{{ $n }}</td>
                                <td align="center">{{ str_pad($pro->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td align="center">{{ $pro->id_card_num?$pro->id_card_num:'N/A' }}</td>
                                <td align="center">{{ $pro->name?$pro->name:'N/A' }}</td>
                                <td align="center"><img class="listPhoto" src="{{ $pro->photo?asset('data/staffs/'.$pro->photo, isset($secure)?false:false):asset('images/noimage.gif', isset($secure)?false:false)}}"></td>
                                <td align="center">{{ $pro->kh_name?$pro->kh_name:'N/A' }}</td>
                                <td align="center">{!! $pro->gender==1?'<span>Male</span>':'<span>Female</span>' !!}</td>
                                <td align="center">{{ $pro->nationality?$pro->nationality:'N/A' }}</td>
                                <td align="center">{{ $pro->date_of_birth?$pro->date_of_birth:'N/A' }}</td>
                                <td align="center">{{ $pro->address?$pro->address:'N/A' }}</td>
                                <td align="center">{{$pro->phone1}}{{ !empty($pro->phone2) ? ' / '.$pro->phone2  : ''}}</td>
                                <td align="center">{{ $pro->email?$pro->email:'N/A' }}</td>
                                <td align="center">{{ $pro->start_on?$pro->start_on:'N/A' }}</td>
                                <td align="center">${{ number_format($pro->salary,2,'.',',') }}</td>
                                <td align="center">{{ $pro->role?$pro->role->role_name:'N/A' }}</td>
                                <td align="center">{{ $pro->branch?$pro->branch->branch_name:'N/A' }}</td>
                                <td class="text-left">
                                    <a href="{{ route('staff_history',[$pro->id]) }}" class="btn btn-xs btn-info" title="Detail"><i class="fa fa-search-minus"></i></a>
                                    <a href="{{ route('edit_staff',[$pro->id]) }}" class="btn btn-success btn-xs" title="Edit"><i class="fa fa-pencil"></i></a>
                                    <a href="{{ route('update_staff',[$pro->id]) }}" class="btn btn-warning btn-xs" title="Update"><i class="fa fa-refresh"></i></a>
                                </td>
                            </tr>
                            <?php $n++; ?>
                            @empty
                            <tr><td colspan=12>{{ trans('multiple.m_no_result')}}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="page" style="margin-left: 1300px;">
                        <?PHP
                        echo $staffs->appends([
                            'offset' => Input::get('offset')
                        ])->render();
                        ?>
                    </div>
                </section>
            </div>
        </section>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function () {
        //pagination
        $('.custom-pagi a').on('click', function () {
            val = $(this).parent().find('input[name="set_offset"]').val();
            $('input[name="offset"]').val(val);
            $('#search_frm').submit();
            return false;
        });
    });
</script>
@endsection
