@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false)}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12" style="width: 50% !important;margin: 0 25% !important;">
        <section class="panel">
           @if(Session::has('error'))
                <div class="alert alert-danger fade in">
                    <button class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('error') }}
                </div>
            @endif
            @if(Session::has('msg'))
                <div class="alert alert-success fade in">
                    <button class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('msg') }}
                </div>
            @endif
            <?php $static = Config::get('static_data');?>
            <header class="panel-heading">
                {{ trans('loan.l_add_new_guarantor') }}
            </header>
            <div class="panel-body" style="margin: 0 auto;">
                <form class="cmxform form-horizontal" method="post" action="{{ route('add_loan_guarantor', [$id]) }}" id="frmloanGuarantor">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <fieldset>
                        <div class="row">
                            <!-- Grid to left -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{ trans('loan.l_relationship') }} <span class="red">*</span></label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="relationship" id="relationship_type">
                                            <option value="">-</option>
                                            @foreach($static['relationship_type'] as $key => $value)
                                                <option value="{{ $value }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <style type="text/css">
                                        #s2id_customer_id{
                                            width: 100% !important;
                                            height: 36px !important;
                                        }
                                    </style>
                                    <label class="control-label col-sm-3">{{ trans('customer.cus_customer') }} <span class="red">*</span></label>
                                    {{-- <div id="customer_id"  class="col-md-8"></div> --}}
                                    <div class="col-md-8">
                                        <input type="text" name="customer_id" class="form-control" id="customer_id" style="width: 300px; height:0px">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="addGuarantorCollateral" class="row"></div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <button type="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                                <button type="button" class="btn btn-danger" onclick="javascript:history.back()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;{{ trans('multiple.m_cancel') }}</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
            var getStatic = <?php echo json_encode(array('collateral_type'=> $static['collateral_type'],'collateral_regis_type'=>$static['collateral_regis_type']));?>;
            var i = 0;
    </script>
    <script type="text/javascript" src="{{ asset('js/guarantor-collateral.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            select();
        });
        function select() {
            $("#customer_id").select2({
                width: 'absolute',
                minimumInputLength: 3,
                placeholder: "select any Client account",
                ajax: {
                    url: '{{ asset('loan-guarantor/get_search_client')}}',
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    timeout: 3000,
                    data: function (term) {
                        return {
                            term: term,
                        };
                    },
                    results: function (data) {
                        if(data.permis != false){
                            return {
                                results: $.map(data.data, function (item) {
                                    return {
                                        text: item.cus_acc+' ( '+item.client_name+' / '+item.phone1+')',
                                        slug: item.cus_acc,
                                        id: item.id,
                                        name:'customer_id'
                                    }

                                })
                            };
                        }else{
                            $('<div id="loading"></div>').appendTo('body');
                            imgLoading(true,'Permission denied!!!',4,'warning');
                            return
                        }
                    }
                }
            });
        }
    </script>
@endsection
