@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
<?php
$performance = config('static_data.performance');
?>
@section('content')
    <section class="panel">
        <header class="panel-heading">
           <span>{{ trans('sidebar.sb_staff_history') }}</span>
           <span style="float: right"><a href="{{ route('update_staff',[$s->id]) }}" class="btn btn-warning"><i class="fa fa-refresh"></i> {{ trans('multiple.m_update') }}</a></span>
        </header>

        <div class="panel-body">
            <section id="unseen">
                <h4><strong>{{ trans('staff.sb_staff_information') }}</strong></h4>
                <div class="col-md-6">
                    <table class="table table-bordered table-striped table-condensed">
                         <tr>
                             <th style="width: 25%;">{{ trans('staff.s_staff_id') }}</th>
                             <td>{{ str_pad($s->id, 6, '0', STR_PAD_LEFT) }}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('user.u_user_name') }}</th>
                             <td>{{ $s->name?$s->name:old('name') }}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('user.u_user_kh_name') }}</th>
                             <td>{{ $s->kh_name?$s->kh_name:old('kh_name ') }}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('staff.s_staff_gender') }}</th>
                             <td>{!! $s->gender==1?'<span>Male</span>':'<span>Female</span>' !!}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('staff.s_nationality') }}</th>
                             <td>{{ $s->nationality?$s->nationality:old('nationality') }}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('staff.s_date_of_birth') }}</th>
                             <td>{{ $s->date_of_birth?$s->date_of_birth:old('date_of_birth') }}</td>
                         </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered table-striped table-condensed">
                         <tr>
                             <th style="width: 25%;">{{ trans('multiple.m_address') }}</th>
                             <td>{{ $s->address?$s->address:old('address') }}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('multiple.m_phone',['num'=>'']) }}</th>
                             <td>{{$s->phone1}}{{ !empty($s->phone2) ? ' / '.$s->phone2  : ''}}</td>
                         </tr>
                         <tr>
                              <th>{{ trans('multiple.m_email') }}</th>
                              <td>{{ $s->email?$s->email:old('email') }}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('staff.s_start_date') }}</th>
                             <td>{{ $s->start_on?$s->start_on:old('start_on') }}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('staff.s_salary') }}</th>
                             <td>{{ number_format($s->salary,2,'.',',') }}</td>
                         </tr>
                         <tr>
                             <th style="width: 30%;">Role</th>
                             <td>{{ $s->role?$s->role->role_name:old('ro') }}</td>
                         </tr>
                         <tr>
                             <th style="width: 30%;">{{ trans('staff.s_staff_branch') }}</th>
                             <td>{{ $s->branch?$s->branch->branch_name:old('branch_id') }}</td>
                         </tr>
                    </table>
                </div>
                <br/><br/>
                <h4><strong>{{ trans('staff.s_staff_record') }}</strong></h4>
                <table class="table table-bordered table-striped table-condensed table-hover">
                    <thead>
                        <tr>
                            <th style="text-align: center; vertical-align:middle;">{{ trans('multiple.m_no') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('product.p_date') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('staff.s_staff_branch') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('staff.s_staff_role') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('staff.s_salary') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_phone',['num'=>'']) }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('multiple.m_address') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('staff.s_staff_performance') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('product.p_remark') }}</th>
                        </tr>
                    </thead>
                    <tbody style="vertical-align: middle">
                        <?php $n = 0;?>
                        @if(!empty($s->history) && count($s->history) > 0)
                            @foreach($s->history as $h)
                                <?php $n += 1; ?>
                                <tr>
                                    <td align="center">{{ $n }}</td>
                                    <td align="center">{{ $h->date}}</td>
                                    <td align="center">{{ $h->branch->branch_name}}</td>
                                    <td align="center">{{ $h->role->role_name}}</td>
                                    <td align="center">${{number_format($h->salary ? $h->salary : '',2,'.',',')}}</td>
                                    <td align="center">{{ $h->phone1 }}</td>
                                    <td align="center">{{ $h->address}}</td>
                                    <td align="center">     
                                        @if($h->performance!='' && $h->performance!=0)
                                            {{$performance[$h->performance] }}
                                        @endif
                                   </td>
                                    <td align="center">{{ $h->description}}</td>
                                </tr>
                            @endforeach
                        @endif    
                    </tbody>
                </table>
    </section>
        </div>
    </section>
@endsection