@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',false) }}"/>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                {{ trans('sidebar.sb_add_account') }}
            </header>
            <div class="panel-body">
                <ol style="font-size: 14px; line-height: 35px;">
                    <li><a href="{{ route('add_coa_category',1) }}">{{ trans('account.add_category') }}</a> </li>
                    <li><a href="{{ route('add_coa_category',2) }}">{{ trans('account.add_sub_category') }}</a> </li>
                    <li><a href="{{ route('add_coa_category',3) }}">{{ trans('account.add_main_account') }}</a> </li>
                    <li><a href="{{ route('add_coa_category',4) }}">{{ trans('account.add_sub_account') }}</a> </li>
                    <li><a href="{{ route('add_coa_category',5) }}">{{ trans('account.add_subsidiary_account') }}</a> </li>
                </ol>
            </div>
        </section>
    </div>

</div>
@endsection

@section('js')
@endsection
