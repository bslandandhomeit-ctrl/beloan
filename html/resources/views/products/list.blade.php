@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/loan-style.css',isset($secure) ? false : false)}}">
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
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <?php
            $action_type = config('static_data.action_type');
            ?>
            <header class="panel-heading">
                <span>{{ trans('sidebar.sb_product_summary') }}</span>
                <span style="float: right"><a href="{{route ('add_product') }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('sidebar.sb_add_product') }}</a></span>
            </header>
            <div class="panel-body">
                <div class="position-center" style="width:90%;">
                    @if($errors->addCate->has('ipCate'))
                    <div class="alert alert-danger fade in">
                        <button class="close close-sm" type="button" data-dismiss="alert">x</button>
                        {{$errors->addCate->first('ipCate')}}
                    </div>
                    @endif

                    <form role="form" class="cmxform form-horizontal" id="search_frm" method="get" action="{{ route('list_product') }}">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="ipName" class="col-lg-3 control-label">{{ trans('product.p_product_name') }}</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" id="ipName" value="{{ isset($ipName)?$ipName:old('ipName') }}" name="ipName">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ipCode" class="col-lg-3 control-label">{{ trans('product.p_product_id') }}</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" value="{{ isset($ipCode)?$ipCode:old('ipCode') }}" id="ipCode" name="ipCode">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inputSerial" class="col-lg-3 control-label">{{ trans('product.p_product_serial') }}</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" value="{{ isset($ipSerial)?$ipSerial:old('ipSerial') }}" id="inputSerial" name="ipSerial">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputModel" class="col-lg-3 control-label">{{ trans('product.p_product_model') }}</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" value="{{ isset($ipModel)?$ipModel:old('ipModel') }}" id="inputSerial" name="ipModel">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <input type="hidden" name="offset" />
                                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                                        <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                                        <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="custom-pagi">
                    <span class="pagi_label">Number of Rows:</span>
                    <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                    <a href="#" class="btn btn-danger">Go</a>
                </div>
                <br/><br/><br/><br/>
                  <section id="unseen" class="ox-scroll table-responsive">
                    <div id="printArea">
											@include('api.report_header')
                      <table class="ddd table table-bordered" id="list_product">
                          <thead>
                          <th style="text-align: center;">{{ trans('multiple.m_no') }}</th>
                          <th style="text-align: center;">{{ trans('product.p_product_id') }}</th>
                          <th style="text-align: center;min-width:160px;">{{ trans('product.p_product_name') }}</th>
                          <th style="text-align: center;min-width:140px;">{{ trans('dealer.dl_dealer_name') }}</th>
                          <th style="text-align: center;">{{ trans('product.p_productsTypes') }}</th>
                          <th style="text-align: center;">{{ trans('product.p_product_category') }}</th>
                          <th style="text-align: center;">{{ trans('product.p_product_brand') }}</th>
                          <th style="text-align: center;">{{ trans('product.p_product_model') }}</th>
                          <th style="text-align: center;">{{ trans('product.p_product_serial') }}</th>
                          <th style="text-align: center;">{{ trans('product.p_product_engine') }}</th>
                          <th style="text-align: center;">{{ trans('product.plate_num') }}</th>
                          <th style="text-align: center;">{{ trans('product.p_product_price') }}</th>
                          <th style="text-align: center;min-width: 100px">{{ trans('multiple.m_status') }}</th>
                          <th style="text-align: center;min-width: 100px;" class="hide-this">{{ trans('multiple.m_action') }}</th>
                          </thead>
                          <?php $n = 1; ?>
                          <tbody>
                              @forelse($proList as $pro)

                              <tr>
                                  <td align="center">{{ $n }}</td>
                                  <td align="center">{{ str_pad($pro->id, 6, '0', STR_PAD_LEFT) }}</td>
                                  <td>{{ $pro->product_name?$pro->product_name:'N/A' }}</td>
                                  <td>
                                      @if(!empty($pro->dealer))
                                          <a href="{{ route('dealer_detail', [$pro->dealer->id])}}">{{ $pro->dealer->dealer }}</a>
                                      @else
                                      {{'N/A'}}
                                      @endif
                                  </td>

                                  <td>{{(!empty($pro->category->category_name)) ? $pro->category->category_name :'N/A'}}</td>
                                  <td>{{(!empty($pro->prod_prod_type->type_name)) ? $pro->prod_prod_type->type_name :'N/A'}}</td>
                                  <td>{{ (!empty($pro->brand)) ? $pro->brand->brand_name : 'N/A' }}</td>
                                  <td>{{ (!empty($pro->product_type)) ? $pro->product_type : 'N/A'}}</td>
                                  <td>{{ $pro->serial_number?$pro->serial_number:'N/A' }}</td>
                                  <td>{{ $pro->engine_number?$pro->engine_number:'N/A' }}</td>
                                  <td>{{ $pro->plate_num?$pro->plate_num:'N/A' }}</td>
                                  <td align="right">{{ number_format($pro->product_price,2,'.',',') }}</td>
                                  <!--<td align="center">{!! $pro->is_loan==0?'<span class="primary-color">In Stock</span>':'<span class="danger-color">Out Stock</span>' !!}{{ (!empty($pro->status)) ? $action_type[$pro->status] : '' }}</td>-->
                                  <td align="center">
                                      @if(isset($pro->status) && isset($action_type[$pro->status]))
                                      <span class="text-success">{{$action_type[$pro->status]}}</span>
                                      @else
                                      {!! $pro->is_loan==0?'<span class="primary-color">In Stock</span>':'<span class="danger-color">Out Stock</span>' !!}
                                      @endif
                                  </td>
                                  @if($pro->status==8)
                                  <td class="text-left">
                                      <a href="{{ route('product_detail',[$pro->id]) }}" class="btn btn-xs btn-info" title="Detail"><i class="fa fa-search-minus"></i></a>
                                  </td>
                                  @elseif($pro->status==9)
                                  <td class="text-left">
                                      <a href="{{ route('product_detail',[$pro->id]) }}" class="btn btn-xs btn-info" title="Detail"><i class="fa fa-search-minus"></i></a>
                                  </td>
                                  @else
                                  <td class="text-left hide-this">
                                      <a href="{{ route('product_detail',[$pro->id]) }}" class="btn btn-xs btn-info" title="Detail"><i class="fa fa-search-minus"></i></a>
                                      <a href="{{ route('edit_product',[$pro->id]) }}" class="btn btn-success btn-xs" title="Edit"><i class="fa fa-pencil"></i></a>
                                      @if($pro->is_loan==1)
                                      <a href="{{ route('update_product',[$pro->id]) }}" class="btn btn-warning btn-xs" title="Update"><i class="fa fa-refresh"></i></a>
                                      @endif
                                  </td>
                                  @endif
                              </tr>
                              <?php $n++; ?>
                              @empty
                              <tr><td colspan=12>{{ trans('multiple.m_no_result')}}</td></tr>
                              @endforelse
                          </tbody>
                      </table>
                    </div>
                      <div class="page">
                          <?PHP
                        //   echo $proList->appends([
                        //       'ipName' => Input::get('ipName'),
                        //       'ipSerial' => Input::get('ipSerial'),
                        //       'ipCode' => Input::get('ipCode'),
                        //       'ipModel' => Input::get('ipModel'),
                        //       'offset' => Input::get('offset')
                        //   ])->render();
                          ?>
                      </div>
                </section>
            </div>
        </section>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/xlsx.core.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        //pagination
        $('.custom-pagi a').on('click', function () {
            val = $(this).parent().find('input[name="set_offset"]').val();
            $('input[name="offset"]').val(val);
            $('#search_frm').submit();
            return false;
        });

        $("#export").click(function (event) {
            var con = confirm("Do you really want to export to CSV file?");
            if(con == true){
                new TableExport(document.getElementById('list_product'), {
                    formats: ['csv'],
                    filename:'list_product'
                });
                $('button.csv').hide().click();
                $('.tableexport-caption').remove();
            }
        });

        $("#xexport").click(function (event) {
        var con = confirm("Do you really want to export to Excel file?");
        if(con == true){
            new TableExport(document.getElementById('list_product'), {
                    formats: ['xlsx'],
                    filename: 'list_product'
                }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $('button.xlsx').hide().click();
                $('.tableexport-caption').remove();
        }
    });

    });
</script>
@endsection
