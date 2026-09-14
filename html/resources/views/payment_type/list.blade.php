@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <span>{{ trans('sidebar.sb_payment_type') }}</span>
                    <div class="pull-right">
                        <span><a href="{{ route('add_payment_type') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sidebar.sb_payment_type') }}</a></span>
                    </div>
                </header>
                <div class="panel-body">
                    @if(Session::has('msg'))
                    <p class="alert alert-success">{{ Session::get('msg') }}</p>
                    @endif
                    <table class="table table-bordered table-striped table-condensed">
                        <thead class="cf">
                            <tr>
                                <th>{{ trans('multiple.m_no') }}</th>
                                <th>Name</th>
                                <th>{{ trans('multiple.m_status') }}</th>
                                <th style="text-align: center">{{ trans('multiple.m_action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lists as $l)
                                <tr>
                                    <td>{{ $l->id }}</td>
                                    <td>{{ $l->name }}</td>
                                    <td>{{ $l->is_active ? "Active" : "Inactive" }}</td>
                                    <td align="center">
                                        <a href="{{ route('edit_payment_type',[$l->id]) }}" class="btn btn-default btn-xs" title="Edit"><i class="fa fa-pencil"></i></a>
                                        @if($l->is_active == 0)
                                            <a href="{{ route('enable_payment_type',[$l->id]) }}" class="btn btn-default btn-xs" title="Activate"><i class="fa fa-check-circle"></i></a>
                                        @else
                                            <a href="{{ route('disable_payment_type',[$l->id]) }}" class="btn btn-default btn-xs" title="Inactivate"><i class="fa fa-times-circle"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4">{{ trans('multiple.m_no_result') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
