@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
           <span>{{ trans('product.p_product_detail') }}</span>
           <span style="float: right"><a href="{{ route('update_product',[$pro->id]) }}" class="btn btn-warning"><i class="fa fa-refresh"></i> {{ trans('multiple.m_update') }}</a></span>
        </header>

        <div class="panel-body">
            <section id="unseen">
                <h4><strong>{{ trans('product.p_product_info') }}</strong></h4>
                <div class="col-md-6">
                    <table class="table table-bordered table-striped table-condensed">
                         <tr>
                             <th style="width: 25%;">{{ trans('product.p_product_id') }}</th>
                             <td>{{ $pro->id?$pro->id:old('ipProduct') }}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('product.p_product_name') }}</th>
                             <td>{{ $pro->product_name?$pro->product_name:old('ipName') }}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('product.p_product_brand') }}</th>
                             <td>{{ $pro->brand->brand_name?$pro->brand->brand_name:old('selBrand') }}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('product.p_productsTypes') }}</th>
                             <td>{{ $pro->product_types->products_type_name?$pro->product_types->products_type_name:''}}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('product.p_product_year') }}</th>
                             <td>{{ $pro->product_year?$pro->product_year:old('product_year') }}</td>
                         </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered table-striped table-condensed">
                         <tr>
                             <th style="width: 25%;">{{ trans('product.p_product_price') }}</th>
                             <td>${{ $pro->product_price?$pro->product_price:old('ipType') }}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('dealer.dl_dealer_name') }}</th>
                             <td>
                                 @if(!empty($pro->dealer))
                                            <a href="{{ route('dealer_detail', [$pro->dealer->id])}}">{{ $pro->dealer->dealer }}</a>
                                    @else
                                        {{'N/A'}}
                                    @endif
                             </td>
                         </tr>
                         <tr>
                              <th>{{ trans('product.p_product_model') }}</th>
                              <td>{{ $pro->product_type?$pro->product_type:old('ipType') }}</td>
                         </tr>
                         <tr>
                             <th>{{ trans('product.p_loan_reference') }}</th>
                             <td>
                                @if(!empty($pro->loan))
                                    <a href="{{ route('dealer_detail', [$pro->loan->id])}}">{{ $pro->loan->contract_id }}</a>
                                @else
                                    {{'N/A'}}
                                @endif
                             </td>
                         </tr>
                         <tr>
                             <th>{{ trans('product.p_product_serial') }}</th>
                             <td>{{ $pro->serial_number?$pro->serial_number:old('ipSerial') }}</td>
                         </tr>
                         <tr>
                             <th style="width: 30%;">{{ trans('product.p_product_engine') }}</th>
                             <td>{{ $pro->engine_number?$pro->engine_number:old('ipEngine') }}</td>
                         </tr>
                    </table>
                </div>
                <br/><br/>
                <h4><strong>{{ trans('product.p_product_records') }}</strong></h4>
                <table class="table table-bordered table-striped table-condensed table-hover">
                    <thead>
                        <tr>
                            <th style="text-align: center; vertical-align:middle;">{{ trans('multiple.m_no') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('product.p_date') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('product.p_action_type') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('product.p_location') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('product.p_price') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ trans('product.p_remark') }}</th>
                        </tr>
                    </thead>
                    <tbody style="vertical-align: middle">
                        <?php $n = 0;?>
                        @if(!empty($pro->record) && count($pro->record) > 0)
                            @foreach($pro->record as $r)
                                <?php $n += 1;?>
                                <tr>
                                    <td align="center">{{ $n }}</td>
                                    <td align="center">{{ $r->date}}</td>
                                    <td align="center">{{ $r->action_type}}</td>
                                    <td align="center">{{ $r->location}}</td>
                                    <td align="right">{{ $r->price}}</td>
                                    <td align="left">{{ $r->remark }}</td>
                                </tr>
                            @endforeach
                        @endif    
                    </tbody>
                </table>
    </section>
        </div>
    </section>
@endsection