@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.css',isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}">
@endsection
@section('content')
    <section class="panel">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
        @endif
        <header class="panel-heading">
            {{ trans('loan.l_add_loan_charge') }}
        </header>
        <?php $static = config('static_data');?>
        <div class="panel-body">
            @if(Session::has('msg'))
                <div class="alert alert-success fade in">
                    <button class="close close-md" data-dimdiss="alert">x</button>
                    {{ Session::get('msg') }}
                </div>
            @endif
            @if($errors->has())
                <div class="alert alert-danger fade in">
                    <button type="button" class="close" data-dimdiss="alert"></button>
                    {{ HTML::ul($errors->all()) }}
                </div>
            @endif
            @if(Session::has('danger'))
                <div class="alert alert-danger fade in">
                    <button class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('danger') }}
                </div>
            @endif

            <form class="cmxform form-horizontal" method="post" action="{{ route('loan_add_charge',[$loan_id]) }}?fee_id={{$fee->id}}" id="addChargeForm" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                @if($fee->id) <input type="hidden" name="fee_id" value="{{ $fee->id }}" /> @endif
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_charge_type') }} <span
                                class="red-color">*</span> </label>
                    <div class="col-sm-6">
                        <select id="charge_type" name="charge_type" class="form-control">
                            <option value="">-</option>
                            @foreach($static['fee_charge'] as $key=>$val)
                                <option value="{{$key}}" @if($fee->fee_type==$key) selected @endif>{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('report.rpt_amount') }} <span
                                class="red-color">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" name="charge_amount" id="charge_amount" class="form-control" required value="{{$fee->amount}}" @if($fee) readonly @endif />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_charge_date') }} <span class="red-color">*</span></label>
                    <div class="input-append date dpYears col-sm-3" data-date-viewmode="years" data-initialize="datepicker" data-date-format="dd/mm/yyyy" >
                        <input type="text" value=""
                               class="form-control" name="charge_date" id="charge_date">
                    <span class="add-on offonDatepicker">
                            <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_waived_amount') }}</label>
                    <div class="col-sm-6">
                        <input type="text" id="waived_amount" name="waived_amount" class="form-control"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.tellers') }} <span class="red">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" name="sel_tellers" id="sel_tellers">
                            <option value="0"> {{ trans('loan.select_teller') }} </option>
                            @foreach($teller as $tellers )
                                <option value="{{$tellers->id}}">{{ $tellers->account_name }} ( {{$tellers->name}})
                                </option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Repayment Type</label>
                    <div class="col-sm-6">
                        <select class="form-control" name="payment_type" id="payment_type">
                            <option value="0">-</option>
                            @foreach($static['payment_type'] as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-2">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="{{ asset('images/noimage.gif', isset($secure) ? false : false) }}" alt=""/>
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail"
                                 style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                            <div>
                               <span class="btn btn-white btn-file">
                               <span class="fileupload-new"><i class="fa fa-paper-clip"></i>Add Receipt</span>
                               <span class="fileupload-exists"><i
                                           class="fa fa-undo"></i> {{ trans('multiple.m_change') }}</span>
                               <input type="file" name="receipt" class="default"/>
                               </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('multiple.m_note') }}</label>
                    <div class="col-sm-6">
                        <textarea name="note" id="note" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3"></label>
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary"><i
                                    class="fa fa-save"></i> {{ trans('multiple.m_save') }}</button>
                        <button type="button" class="btn btn-danger" onclick="javascript:history.back()"><i
                                    class="fa fa-times-circle"></i> {{ trans('multiple.m_cancel') }}</button>
                    </div>
                </div>

                <h4>Journal Detail</h4>
                <hr/>
                <?php
                $currency_arr = config('static_data.currency');
                $params = array(
                        'contract_id' => $loan->contract_id,
                        'branch_label' => $branch_name,
                        'branch' => $loan->company_branch_id,
                        'debit' => '',
                        'credit' => '',
                        'currency' => $currency,
                        'currency_label' => $currency_arr[$currency],
                        'entry_date' => date('Y-m-d H:i:s'),
                        'parent_debit' => $coa_1->id,
                        'parent_credit' => $coa_2->id,
                        'parent_debit_label' => $coa_1->name . ' (' . $branch_code . $coa_1->account_code . ')',
                        'parent_credit_label' => $coa_2->name . ' (' . $branch_code . $coa_2->account_code . ')',
                        'branch_code' => $branch_code
                );
                echo getJournalDetail($params);
                ?>
            </form>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript"
            src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/bootstrap-fileupload/bootstrap-fileupload.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript"
            src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $('.dpYears').datepicker({
                  format: 'yyyy-mm-dd',
                  autoclose: true,
                  setDate: new Date()
            });
            $("#charge_amount").on('keyup', function (e) {
                $('input[name="debit[]"]:first').val($(this).val());
                $('input[name="credit[]"]:first').val($(this).val());
            });
        });
        var fee_charges = <?php echo json_encode($static['fee_charge']); ?>;

        $('#charge_date').on('change', function () {
          $('input[name="entry_date[]"]:first').val($(this).val());
        });
        $('#charge_type').on('change', function () {
			var coa_suspense = <?php echo json_encode($coa_suspense); ?>;
			var charge_type = $('#charge_type').val();
			console.log(charge_type);
			if( charge_type == 9){ // Other Fee Charge
				$('.parent_credit_label_className').each(function(){
                     $(this).val(coa_suspense.name);
                 });
				$('.parent_credit_className').each(function(){
				   $(this).val(coa_suspense.id);
				});
			}
            $.each(fee_charges, function (i, v) {
                if (i == charge_type) {
                    $('#note').val(v);
                }
            });

        });

        $(document).on('change', '#sel_tellers', function () {

            var till_account = JSON.parse('<?PHP echo json_encode($teller);?>');
            var Sel_till_account_id = $('#sel_tellers option:selected').val();

            $.each(till_account, function (inx, vals) {
                if (parseInt(Sel_till_account_id) === parseInt(vals.id)) {
                    if(parseInt(vals.status) === 1) {

                        alert('This till account was closed');
                        $('#sel_tellers').val(0);
                        $('#sel_tellers').attr('selected', true);
                    }
                }
            });
        });
        var parent_debit_label_first = $('.parent_debit_label_className:first').val();

        $('select[name="payment_type"]').change(function () {
            this_ = $(this);
            var coa_dd = <?php echo json_encode($coa_1); ?>;
            var branch_code = "<?php echo $branch_code . '-' ?>";
            var currency = "<?php echo $currency?>"
            if(this_.val() == 0){
                 $('.parent_debit_label_className').each(function(){
                     $(this).val(parent_debit_label_first);
                 });
                 $('.parent_debit_className').each(function(){
                     $(this).val(coa_dd.id);
                 });
            }else{

                $.ajax({
                    url: "{{ route('getDisburseType') }}",
                    data: "select_type=" + this_.val() + "&currency=" + currency,
                    method: 'get',
                    success: function (res) {
                        res = JSON.parse(res);
                        console.log(res);
                         var debit_value = res.name + " ("+ branch_code + res.account_code + ")";
                        $('.parent_debit_label_className').each(function(){
                            $(this).val(debit_value);
                        });
                        $('.parent_debit_className').each(function(){
                            $(this).val(res.id);
                        });
                    }
                });
            }
        });

    </script>
@endsection
