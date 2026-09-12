@extends('layouts.app')

@section('content')
<section class="panel panel-box-700">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
    @endif
    <header class="panel-heading">
        {{ trans('sidebar.sb_currency') }}
    </header>
    <div class="panel-body">
        <form action="{{ route('set_currency_rate') }}" method="post" class="cmxform form-horizontal" id="addloanForm" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="panel panel-default box-border-500">
                    <div class="panel-heading">
                        <a class="btn btn-info btn-xs" href="<?php echo NBC_EXCHANGE_URL?>" target="_blank">
                            {{ trans('currency.c_view_at_nbc') }}
                        </a>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <!-- Grid to left -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">{{ trans('currency.c_currency') }}</label>
                                    <div class="col-md-6">
                                        <select class="form-control" id="cur" name="cur">
                                            <option value="">-</option>
                                            @foreach($currency as $r)
                                            <option value="{{ $r->id }}" title="<?php echo trim($r->code)?>">{{ $r->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">{{ trans('currency.c_unit') }}</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="sch_penalty" name="unit" value="1" readonly/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">{{ trans('currency.c_ask_rate') }}</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="ask_rate" name="ask_rate" value=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">{{ trans('currency.c_bid_rate') }}</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="bid_rate" name="bid_rate" value="" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4">{{ trans('currency.c_mid_rate') }}</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="mid_rate" name="mid_rate" value="" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-4">{{ trans('currency.c_nbc_rate') }}</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="nbc_rate" name="nbc_rate" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="hide">
                	<?php $currency_static = config('static_data.currency');?>
                	@foreach($currency_static as $r)
                    	<input type="hidden" name="cur_{{$r}}" value="{{$currency_arr[$r]}}" />              
                    @endforeach
                </div>
                
                <div class="text-center">
                    <div class="clear-fix"></div>
                    <button type="submit" class="btn btn-info"><i class="fa fa-save"></i>&nbsp;{{ trans('multiple.m_save') }}</button>
                    <button type="button" class="btn btn-danger" onclick="javascript:history.back();"><i class="fa fa-times-circle"></i>&nbsp;{{ trans('multiple.m_cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function () {
        $("#bid_rate").on('keyup', function (e) {
            var bid_rate = $("#bid_rate").val();
            var ask_rate = $("#ask_rate").val();
            mid_rate = (parseFloat(ask_rate) + parseFloat(bid_rate))/2;
            $("#mid_rate").val(mid_rate);
        });

        $('select[name="cur"]').change(function () {
            this_ = $(this);
            $.ajax({
                url: "{{ route('getLatestRate') }}",
                data: "cur=" + this_.val(),
                method: 'get',
                success: function (res) {
                    res = JSON.parse(res);
                    $("#bid_rate").val(res.bid_rate);
                    $("#ask_rate").val(res.ask_rate);
                    $("#mid_rate").val(res.mid_rate);
                    $("#nbc_rate").val(res.nbc_rate);

                    //for NBC
                    cur_usd = $('input[name="cur_USD"]').val(); //USD to KHR
                    cur_selected_label = this_.find('option:selected').attr('title'); //SELECTED to KHR
    				cur_selected_value = parseFloat($('input[name="cur_'+cur_selected_label+'"]').val());
    				
                	if(cur_usd!='' && cur_selected_value!=''){
        				rest = (parseFloat(cur_usd) / parseFloat(cur_selected_value));
        				$("#nbc_rate").val(rest.toFixed(2));
                	}else{
                    	alert("{{ trans('There is no data from NBC website!') }}");
                	}
                }
            })
        });

        
    });
</script>

@endsection
