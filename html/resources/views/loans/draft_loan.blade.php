@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/loan-style.css',isset($secure) ? false : false)}}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <span>{{ trans('sidebar.draft_loan') }}</span>
                <span style="float: right"><a href="{{route ('add_product') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sidebar.draft_loan') }}</a></span>
            </header>
            <div class="panel-body">
                <section id="unseen" class="ox-scroll">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <th style="text-align: center;">{{ trans('multiple.contract_id') }}</th>
                        <th style="text-align: center;">{{ trans('multiple.client_name') }}</th>
                        <th style="text-align: center;">{{ trans('multiple.sell_price') }}</th>
                        <th style="text-align: center;">{{ trans('multiple.m_action') }}</th>
                        </thead>
                        <tbody>
                             @foreach($loans as $l)
                             	<tr>
                             		<td>{{$l->contract_id}}</td>
                             		<td>{{$l->client_name}}</td>
                             		<td>{{$l->sell_price}}</td>
                             		<td>
                             			<a href="{{$l->url}}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i></a>
                             		</td>
                             	</tr>
                             @endforeach
                        </tbody>
                    </table>
                </section>
            </div>
        </section>
    </div>
</div>
@endsection

@section('js')

@endsection