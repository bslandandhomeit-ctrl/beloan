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
            {{ trans('unit_type.unit_type_detail') }}
        </header>
        <div class="panel-body">
            <div class="position-center" style="width:100%;">
                <div class="row">
                    <div class="col-sm-12">
                        <label class="control-label">{{ trans('unit_type.project') }} :</label>
                        {{ isset($unit_type->Projects->dealer)?$unit_type->Projects->dealer:'N/A' }}
                    </div>
                    <br>
                    <div class="col-lg-9">
                        <div class="panel-body" style="border: 1px solid #dddddd; border-radius: 5px;">
                            <div id="exTab2">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-target="#khmer1" data-toggle="tab"> Khmer <span> <img src="{{ asset('images/flags/kh.gif') }}"></span></a></li>
                                    <li><a data-target="#english1" data-toggle="tab">English <img src="{{ asset('images/flags/en.gif') }}"></a></li>
                                    <li><a data-target="#chinese1" data-toggle="tab">Chinese <img src="{{ asset('images/flags/cn.gif') }}"></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="khmer1">
                                        <br/>
                                        <table class="table table-bordered table-striped table-condensed">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 25%">{{ trans('dealer.name') }}</th>
                                                    <td>{{ $unit_type->name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="english1">
                                        <br/>
                                         <table class="table table-bordered table-striped table-condensed">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 25%">{{ trans('dealer.name') }}</th>
                                                    <td>{{ $unit_type->name_en }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="chinese1">
                                        <br/>
                                         <table class="table table-bordered table-striped table-condensed">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 25%">{{ trans('dealer.name') }}</th>
                                                    <td>{{ $unit_type->name_cn }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="panel-body" style="border: 1px solid #dddddd; border-radius: 5px;">
                            <div id="exTab2">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-target="#khmer" data-toggle="tab"> Khmer <span> <img src="{{ asset('images/flags/kh.gif') }}"></span></a></li>
                                    <li><a data-target="#english" data-toggle="tab">English <img src="{{ asset('images/flags/en.gif') }}"></a></li>
                                    <li><a data-target="#chinese" data-toggle="tab">Chinese <img src="{{ asset('images/flags/cn.gif') }}"></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="khmer">
                                        <br/>
                                        <table class="table table-bordered table-striped table-condensed">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 25%">{{ trans('unit_type.title_clause') }}</th>
                                                    <td>{{ $unit_type->title_clause_kh }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 25%">{{ trans('unit_type.mgt_service_clause') }}</th>
                                                    <td>{{ $unit_type->management_service_kh }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 25%">{{ trans('unit_type.equipment_clause') }}</th>
                                                    <td>{!! $unit_type->equipment_text !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="english">
                                        <br/>
                                        <table class="table table-bordered table-striped table-condensed">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 25%">{{ trans('unit_type.title_clause') }}</th>
                                                    <td>{{ $unit_type->title_clause_en }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 25%">{{ trans('unit_type.mgt_service_clause') }}</th>
                                                    <td>{{ $unit_type->management_service_en }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 25%">{{ trans('unit_type.equipment_clause') }}</th>
                                                    <td>{!! $unit_type->equipment_text_en !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="chinese">
                                        <br/>
                                        <table class="table table-bordered table-striped table-condensed">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 25%">{{ trans('unit_type.title_clause') }}</th>
                                                    <td>{{ $unit_type->title_clause_cn }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 25%">{{ trans('unit_type.mgt_service_clause') }}</th>
                                                    <td>{{ $unit_type->management_service_cn }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 25%">{{ trans('unit_type.equipment_clause') }}</th>
                                                    <td>{!! $unit_type->equipment_text_cn !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="row">
                                    <div class="col-sm-6">
                                        {{ trans('unit_type.floor_plan') }}
                                    </div>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="fileupload fileupload-new">
                                                <div class="row" id="form_upload_floor_plan">
                                                    @foreach($img_floor_plan as $key => $val)
                                                        <div class="col-sm-6 floor_plan" style="padding: 10px;">
                                                            <div style="width: 180px; height: 150px;">
                                                                <?php
                                                                    $floor_plan = '';
                                                                    if($val->url){
                                                                        if(file_exists('data/image_type/floor_plan/'.$val->url)){
                                                                            $floor_plan = asset('data/image_type/floor_plan/'.$val->url);
                                                                        }else{
                                                                            $floor_plan = asset('images/noimage.gif');
                                                                        }
                                                                    }else{
                                                                        $floor_plan = asset('images/noimage.gif');
                                                                    }
                                                                ?>
                                                                <img src="{{ $floor_plan }}" alt="" id="img_floor_plan_1" style="width: 167px; height: 150px;object-fit: cover;border: 1px solid #ddd;"/>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="row">
                                    <div class="col-sm-6">
                                        {{ trans('unit_type.interior') }}
                                    </div>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="fileupload fileupload-new">
                                                <div class="row" id="form_upload_interior">
                                                    @foreach($img_interior as $val)
                                                        <div class="col-sm-6 interior" style="padding: 10px;">
                                                            <div style="width: 180px; height: 150px;">
                                                                <?php
                                                                    $interior = '';
                                                                    if($val->url){
                                                                        if(file_exists('data/image_type/interior/'.$val->url)){
                                                                            $interior = asset('data/image_type/interior/'.$val->url);
                                                                        }else{
                                                                            $interior = asset('images/noimage.gif');
                                                                        }
                                                                    }else{
                                                                        $interior = asset('images/noimage.gif');
                                                                    }
                                                                ?>
                                                                <img src="{{ $interior }}" alt="" id="img_interior_1" style="width: 167px; height: 150px;object-fit: cover;border: 1px solid #ddd;"/>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="row justify-content-between">
                                    <div class="col-sm-6 my-auto">
                                        {{ trans('unit_type.exterior') }}
                                    </div>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="fileupload fileupload-new">
                                                <div class="row" id="form_upload_exterior">
                                                    @foreach($img_exterior as $val)
                                                        <div class="col-sm-6 exterior" style="padding: 10px;">
                                                            <div style="width: 180px; height: 150px;">
                                                                <?php
                                                                    $exterior = '';
                                                                    if($val->url){
                                                                        if(file_exists('data/image_type/exterior/'.$val->url)){
                                                                            $exterior = asset('data/image_type/exterior/'.$val->url);
                                                                        }else{
                                                                            $exterior = asset('images/noimage.gif');
                                                                        }
                                                                    }else{
                                                                        $exterior = asset('images/noimage.gif');
                                                                    }
                                                                ?>
                                                                <img src="{{ $exterior }}" alt="" id="img_exterior_1" style="width: 167px; height: 150px;object-fit: cover;border: 1px solid #ddd;"/>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <br/><br/>
        </div>
    </section>
@endsection