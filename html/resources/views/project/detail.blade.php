@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('dealer.del_dealer_detail') }}
        </header>

        <div class="panel-body">
            <div class="position-center" style="width:100%;">
                <div class="row">
                    <div class="col-lg-2">
                        <img src="{{ $dealer->photo?asset('/data/dealers/'.$dealer->photo,true):asset('images/noimage.gif',true) }}" class="img-thumbnail" alt="Profile Picture" width="200" height="150" style="height:150px"/>
                    </div>
                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-lg-5">
                                <table class="table-condensed">
                                    <tr>
                                        <th>{{ trans('dealer.dl_dealer_name') }} : </th>
                                        <td>{{ $dealer->dealer?$dealer->dealer:'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('dealer.dl_dealer_representative') }} : </th>
                                        <td>{{ $dealer->representative?$dealer->representative:'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('multiple.m_phone',['num'=>'']) }}:</th>
                                        <td>{{ $dealer->phone2?$dealer->phone.' / '.$dealer->phone2:$dealer->phone}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-5">
                                <table class="table-condensed">
                                    <tr>
                                        <th>{{ trans('multiple.m_email') }}:</th>
                                        <td>{{ $dealer->email?$dealer->email:'N/A' }}</td>
                                    </tr>
                                     <tr>
                                        <th>{{ trans('multiple.m_location') }}:</th>
                                        <td>{{ $dealer->location?$dealer->location:'N/A'}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('multiple.m_description') }}:</th>
                                        <td>{{ $dealer->description?$dealer->description:'N/A'}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br/><br/>
            <section id="unseen">
                <h4><strong>{{ trans('dealer.dl_dealer_bank_info') }}</strong></h4>
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <th>{{ trans('multiple.m_no') }}</th>
                        <th>{{ trans('dealer.dl_dealer_bank_name') }}</th>
                        <th>{{ trans('dealer.dl_dealer_account_name') }}</th>
                        <th>{{ trans('dealer.dl_dealer_account_number') }}</th>
                        <th>{{ trans('multiple.m_status') }}</th>
                    </thead>
                    <tbody>
                       @forelse($banks as $bank)
                       <tr>
                            <td>{{ $bank->bank->id }}</td>
                            <td>{{ $bank->bank->bank_name }}</td>
                            <td>{{ $bank->bank->account_name }}</td>
                            <td>{{ $bank->bank->account_number }}</td>
                            <td>{{ $bank->bank->active==0?'Inactive':'Active'}}</td>
                       </tr>
                       @empty
                        <tr><td colspan=5>{{ trans('multiple.m_no_result') }}</td></tr>
                       @endforelse
                    </tbody>
                </table>

                <br/><br/>
                <h4><strong>{{ trans('dealer.dl_dealer_product_in') }}</strong></h4>
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <th>{{ trans('multiple.m_no') }}</th>
                        <th>{{ trans('product.p_product_id') }}</th>
                        <th>{{ trans('product.p_product_name') }}</th>
                        <th>{{ trans('product.p_product_category') }}</th>
                        <th>{{ trans('product.p_product_brand') }}</th>
                        <th>{{ trans('product.p_product_model') }}</th>
                        <th>{{ trans('product.p_product_serial') }}</th>
                        <th>{{ trans('product.p_product_engine') }}</th>
                        <th>{{ trans('product.p_product_price') }}</th>
                        <th>{{ trans('multiple.m_status') }}</th>
                        <th>{{ trans('multiple.m_action') }}</th>
                    </thead>
                    <tbody>
                       <?php $n = 1; ?>
                       @forelse($products as $pro)
                       <tr>
                            <td>{{$n}}</td>
                            <td>{{ str_pad($pro->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $pro->product_name }}</td>
                            <td>{{ $pro->category->category_name }}</td>
                            <td>{{ $pro->brand->brand_name }}</td>
                            <td>{{ $pro->product_type }}</td>
                            <td>{{ $pro->serial_number?$pro->serial_number:'N/A' }}</td>
                            <td>{{ $pro->engine_number?$pro->engine_number:'N/A' }}</td>
                            <td align="right">{{ number_format($pro->product_price,2,'.',',') }}</td>
                            <td>{!! $pro->is_loan==0?'<span class="primary-color">In Stock</span>':'<span class="danger-color">Out Stock</span>' !!}</td>
                            <td class="text-center">
                                <a href="{{ route('edit_product',[$pro->id]) }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> </a>
                            </td>
                       </tr>
                       @empty
                        <tr><td colspan=11>{{ trans('multiple.m_no_result') }}</td></tr>
                       @endforelse
                    </tbody>
                </table>

                <br/><br/>
                <h4><strong>{{ trans('dealer.dl_dealer_product_out') }}</strong></h4>
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <th>{{ trans('multiple.m_no') }}</th>
                        <th>{{ trans('report.rpt_contract_id') }}</th>
                        <th>{{ trans('product.p_product_id') }}</th>
                        <th>{{ trans('customer.cus_customer_name') }}</th>
                        <th>{{ trans('dealer.dl_dealer_transfer_amount') }}</th>
                        <th>{{ trans('dealer.dl_dealer_transfer_date') }}</th>
                        <th>{{ trans('loan.l_disburse_amount') }}</th>
                        <th>{{ trans('loan.l_disburse_date') }}</th>
                        <th>{{ trans('dealer.dl_dealer_bank_name') }}</th>
                        <th>{{ trans('dealer.dl_dealer_account_name') }}</th>
                        <th>{{ trans('dealer.dl_dealer_sender') }}</th>
                        <th>{{ trans('multiple.m_action') }}</th>
                    </thead>
                    <tbody>
                        <?php $n = 1;;?>
                       @forelse($loans as $loan)
                           @if(!empty($loan->loanDealer))
                           <tr>
                                <td>{{ $n }}</td>
                                <td><a href="{{ route('loan_detail', [$loan->id])}}">{{ $loan->contract_id ? $loan->contract_id : '-'  }}</a></td>
                                <td>{{ str_pad($loan->product_id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $loan->client->client_name }}</td>
                                <td>{{ $loan->loanDealer->transfer_amount }}</td>
                                <td>{{ $loan->loanDealer->transfer_date }}</td>
                                <td>{{ $loan->loanDealer->disbursement_amount }}</td>
                                <td>{{ $loan->loanDealer->disbursement_date }}</td>
                                <td>{{ $loan->loanDealer->bank->bank_name }}</td>
                                <td>{{ $loan->loanDealer->bank->account_name }}</td>
                                <td>{{ $loan->loanDealer->sender }}</td>
                                <td>
                                    @if($loan->loanDealer->dealer_receipt!="")
                                        <a href="#receipt" class="btn btn-primary btn-xs" data-toggle="modal">View Receipt</a>
                                        <div class="modal fade" id="receipt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">Receipt For Product #{{$loan->product_id}}</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php 
                                                            $arr = explode('|', $loan->loanDealer->dealer_receipt);
                                                        ?>
                                                        @for($i=0;$i<count($arr)-1;$i++)
                                                            <p>+ Receipt Number #{{$i+1}}</p>
                                                            <img src="{{asset('data/receipts/'. $arr[$i],true)}}" style="width:100%"/>
                                                            <br/><br/>
                                                        @endfor
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button data-dismiss="modal" class="btn btn-default" type="button">{{ locale_trans($locale_titles,'close') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="btn btn-default btn-xs no-hover">No Receipt</span>
                                    @endif
                                </td>
                           </tr>
                           <?php $n+=1; ?>
                           @endif
                       @empty
                        <tr><td colspan=12>{{ trans('multiple.m_no_result') }}</td></tr>
                       @endforelse
                    </tbody>
                </table>
                <div>
                    @include('partials.pagination',['results'=>$loans])
                </div>

            </section>
        </div>
    </section>
@endsection