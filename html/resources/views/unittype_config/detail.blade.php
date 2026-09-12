@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
<style type="text/css">
    .panel-body,.panel-heading{border: 1px solid #ddd !important;}
</style>
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('unittype_config.unit_type_config_detail') }}
        </header>
        <div class="panel-body">
            <div class="position-center" style="width:100%;">
                <div class="row">
                    <br>
                    <div class="col-lg-12">
                        <div class="panel-body" style="border: 1px solid #dddddd; border-radius: 5px;">
                            <div class="tab-content">
                                <div class="tab-pane active" id="khmer">
                                    <br/>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <th style="width: 25%">{{ trans('unit.unit_type') }}</th>
                                                <?php
                                                $unit_types = $unit_type_config->UnitType;
                                                ?>
                                                <td>{{ $unit_types->name }} ({{ isset($unit_types->Projects->dealer)?$unit_types->Projects->dealer:''}} - {{  isset($unit_types->Projects->short_code)?$unit_types->Projects->short_code:'' }} )</td>
                                            </tr>
                                            <tr>
                                                <th style="width: 25%">{{ trans('report.rpt_loan_type') }}</th>
                                                <td>{{ isset($unit_type_config->product_type->products_type_name)?$unit_type_config->product_type->products_type_name:'' }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width: 25%">{{ trans('loan.l_penalty_rate_type') }}</th>
                                                <td>{{ isset($static['penalty_rate_type'][$unit_type_config->penalty_rate_type])?$static['penalty_rate_type'][$unit_type_config->penalty_rate_type]:'' }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width: 25%">{{ trans('loan.l_penalty_period',['num'=>1]) }} (D)</th>
                                                <td>{{ isset($static['penalty_rate_type'][$unit_type_config->penalty_rate_type])?$static['penalty_rate_type'][$unit_type_config->penalty_rate_type]:'' }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width: 25%">{{ trans('loan.l_penalty_rate_type') }}</th>
                                                <td>{{ isset($static['penalty_rate_type'][$unit_type_config->penalty_rate_type])?$static['penalty_rate_type'][$unit_type_config->penalty_rate_type]:'' }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width: 25%">{{ trans('loan.l_penalty_rate_type') }}</th>
                                                <td>{{ isset($static['penalty_rate_type'][$unit_type_config->penalty_rate_type])?$static['penalty_rate_type'][$unit_type_config->penalty_rate_type]:'' }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width: 25%">{{ trans('loan.l_penalty_rate_type') }}</th>
                                                <td>{{ isset($static['penalty_rate_type'][$unit_type_config->penalty_rate_type])?$static['penalty_rate_type'][$unit_type_config->penalty_rate_type]:'' }}</td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br/><br/>
        </div>
    </section>
@endsection