@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('unit.unit_detail') }}
            <span style="float: right"><a href="{{route ('list_unit') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('unit.list_unit') }}</a></span>
        </header>
        <div class="panel-body">
            <div class="position-center" style="width:100%;">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 style="border-bottom: 1px solid #ddd;padding-bottom: 15px;">{{ trans('unit.measurements') }}:</h4>
                    </div>
                    <div class="col-lg-12">
                        <table class="table table-bordered table-striped table-condensed">
                            <tbody>
                                <tr>
                                    <th style="width: 15%">{{ trans('unit.land_width') }}</th>
                                    <td>{{ $unit->land_size_width }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 15%">{{ trans('unit.land_length') }}</th>
                                    <td>{{ $unit->land_size_length }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 15%">{{ trans('unit.land_area') }}</th>
                                    <td>{{ $unit->land_area}}</td>
                                </tr>
                                <tr>
                                    <th style="width: 15%">{{ trans('unit.house_width') }}</th>
                                    <td>{{ $unit->building_size_width }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 15%">{{ trans('unit.house_length') }}</th>
                                    <td>{{ $unit->building_size_length }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 15%">{{ trans('unit.house_area') }}</th>
                                    <td>{{ $unit->building_area }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h4 style="border-bottom: 1px solid #ddd;padding-bottom: 15px;">{{ trans('unit.facilities') }}:</h4>
                    </div>
                    <div class="col-lg-12">
                        <table class="table table-bordered table-striped table-condensed">
                            <tbody>
                                <tr>
                                    <th style="width: 15%">{{ trans('unit.living_room') }}</th>
                                    <td>{{ $unit->living_room }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 15%">{{ trans('unit.kitchen') }}</th>
                                    <td>{{ $unit->kitchen }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 15%">{{ trans('unit.bedroom') }}</th>
                                    <td>{{ $unit->bedroom}}</td>
                                </tr>
                                <tr>
                                    <th style="width: 15%">{{ trans('unit.bathroom') }}</th>
                                    <td>{{ $unit->bathroom }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 15%">{{ trans('unit.swimming_pool') }}</th>
                                    <td>{{ $unit->swimming_pool }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection