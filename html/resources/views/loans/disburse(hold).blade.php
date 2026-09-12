@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
    <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link href="{{ asset('theme/js/iCheck/skins/flat/green.css',isset($secure) ? false : false) }}" rel="stylesheet">
@endsection

<?php $currency_arr = config('static_data.currency');     $static = config('static_data');?>

@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('loan.l_disburse_loan') }}
        </header>

        <div class="panel-body">
            @if(Session::has('msg'))
                <div class="alert alert-success fade in">
                    <button class="close close-sm" data-dismiss="alert">x</button>
                    {{ Session::get('msg') }}
                </div>
            @endif
            @if($errors->has())
                <div class="alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    {{ HTML::ul($errors->all()) }}
                </div>
            @endif

            <form class="cmxform form-horizontal" method="post" action="{{ route('loan_disburse',[$loan->id]) }}"
                  id="frmDisburse">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_disburse_date') }} <span class="red">*</span></label>
                    <div class="input-append date dpYears col-sm-6" data-date-viewmode="years"
                         data-initialize="datepicker" data-date-format="dd/mm/yyyy"
                         data-date="{{date('Y-m-d', strtotime($loan->disburse_date))}}">
                        <input type="text" placeholder="Select a date"
                               value="{{ date('Y-m-d', strtotime($loan->disburse_date)) }}" class="form-control"
                               name="disburse_on" id="disburse_on"/>
                        <span class="add-on offonDatepicker">
                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_contact_date') }} <span class="red">*</span></label>
                    <div class="input-append date dpYears col-sm-3 contract_date" data-date-viewmode="years"
                         data-initialize="datepicker" data-date-format="dd/mm/yyyy"
                         data-date="{{date('Y-m-d', strtotime($loan->contract_date))}}">
                        <input type="text" placeholder="Select a date"
                               value="{{ date('Y-m-d', strtotime($loan->contract_date))  }}" class="form-control"
                               name="contract_date" id="contract_date" readonly="readonly"/>
                        <span class="add-on offonDatepicker">
                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                    </div>
                    <div class="col-sm-3">
                        <div class="icheck group">
                            <div class="flat-green single-row">
                                <div class="radio">
                                    <input type="checkbox" name="ch_contract_date" value="1"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_schedule_date') }} <span class="red">*</span></label>
                    <div class="input-append date dpYears col-sm-6" data-date-viewmode="years"
                         data-initialize="datepicker" data-date-format="dd/mm/yyyy"
                         data-date="{{date('Y-m-d', strtotime($loan->schedule[1]->schedule_date))}}">
                        <input type="text" placeholder="Select a date"
                               value="{{ date('Y-m-d', strtotime($loan->schedule[1]->schedule_date))  }}"
                               class="form-control" name="schedule_date" id="start_date"/>
                        <span class="add-on offonDatepicker">
                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_currency') }}</label>
                    <div class="col-sm-6">
                        <input type="text" readonly value="{{ $currency_arr[$currency] }}" class="form-control"
                               name="currency_"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_transaction_amount') }} <span
                                class="red">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" readonly placeholder="Enter transaction amount"
                               value="{{ $loan->loan_amount }}" class="form-control" name="amount" id="amount"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('company.company') }}</label>
                    <div class="col-sm-6">
                        <input type="text" readonly value="{{$branch_name}}" class="form-control" name="branch_name"/>
                        <input type="hidden" value="{{$branch_code}}" class="form-control" name="branch_code"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_currency') }}</label>
                    <div class="col-sm-6">
                        <input type="text" readonly value="{{$currency_arr[$currency]}}" class="form-control"
                               name="currency_name"/>
                        <input type="hidden" value="{{$currency}}" class="form-control" name="currency" id="currency"/>
                        <input type="hidden" value="{{$loan->client_loan_account->currencies['symbol']}}"
                               class="form-control" name="currency_symbol" id="currency_symbol"/>
                    </div>
                </div>
                <input type="hidden" id="admin_fee" name="admin_fee" value="{{ $loan->admin_fee  }}"/>
                <input type="hidden" id="maintain_fee" name="maintain_fee" value="{{ $loan->maintain_fee  }}"/>
                <input type="hidden" id="custom_flag" name="custom_flag" value="{{ $loan->custom_flag }}"/>
                {{--            <select type="hidden" id = "admin_fee_opt" name="admin_fee_opt" value="{{ $loan->admin_fee_opt}}" selected/>
                            <select type="hidden" id = "maintain_fee_opt" name="maintain_fee_opt" value="{{ $loan->maintain_fee_opt}}" selected/>
                --}}
                <input type="hidden" id="other_fee" name="other_fee" value="{{ $loan->other_fee  }}"/>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.l_contract_id') }}</label>
                    <div class="col-sm-6">
                        <input type="text" readonly value="{{$loan->contract_id}}" class="form-control"
                               name="contract_id"/>
                    </div>
                </div>

                {{--Tellers--}}
                {{-- <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.tellers') }} <span class="red">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" name="sel_tellers" id="sel_tellers">
                            <option value="0"> {{ trans('loan.select_teller') }} </option>
                            @foreach($tellers as $teller)
                                <option value="{{ $teller->id}}">{{ $teller->account_name }}
                                    ( {{ $teller->name }} )
                                </option>
                            @endforeach
                        </select>

                    </div>
                </div> --}}

                {{--<div class="form-group">--}}
                {{--<label class="col-sm-3 control-label">{{ trans('loan.l_commitment_fee') }}</label>--}}
                {{--<div class="col-sm-6">--}}
                {{--<div class="input-group">--}}
                {{--<input type="text" value="" class="form-control" name="commission_fee" id="commission_fee" />--}}
                {{--<div class="input-group-addon">%</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('loan.admin_fee') }}</label>
                    <div class="col-sm-6">
                        <input type="text" readonly value="{{round($loan->schedule[0]->fee,2)}}" class="form-control"
                               name="admin_fee"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('multiple.m_note') }}</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="note" name="note">{{ $loan->disburse_note }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">{{ trans('loan.l_payment_type') }} </label>
                    <div class="col-md-6">
                        <select class="form-control" name="select_type" id="select_type">
                            @foreach($static['payment_type_disbus'] as $key => $value)
                                <option value="{{ $key }}"
                                        @if($draft->payment_type == $value) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-6">
                        <a class="btn btn-info" href="#mSchedule" data-toggle="modal" id="view_pay_schedule">
                            <i class="fa fa-eye"></i>Repayment Schedule
                        </a>
                    </div>
                </div>

            <!--<h4>{{ trans('multiple.fee') }}</h4><hr/>
            <table class="journal-helper tb-search-box">
                <tbody>
                    <tr>
                        <td>
                            <?php $fee_type = Config::get('static_data')['fee_charge'];?>
                    <select class="form-control" name="fee_type[]">
                                @foreach($fee_type as $key=>$val)
                <option value="{{$key}}">{{$val}}</option>
                                @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" value="" class="form-control" name="fee_amount[]" id="fee_amount" placeholder="{{ trans('multiple.amount') }}" />
                        </td>
                        <td>
                            <input type="text" value="" class="form-control" name="fee_tenure[]" id="fee_tenure" placeholder="1,12,24" />
                        </td>
                        <td>
                            <input type="text" value="" class="form-control" name="fee_note[]" id="fee_note" placeholder="{{ trans('multiple.note') }}" />
                        </td>
                        <td>
                            <label class="btn btn-default add_fee" style="margin-top:7px">+</label>
                            <label class="btn btn-danger remove_fee" style="margin-top:7px">-</label>
                        </td>
                    </tr>
                </tbody>
            </table>
            -->

                <br/><br/>
                <h4>{{ trans('multiple.journal_detail') }}</h4>
                <hr/>
                <div class="form-group">
                    <strong>&nbsp;&nbsp;&nbsp;Disbursement</strong>
                    <?php
                    $params = array(
                        'debit' => $loan->loan_amount,
                        'credit' => $loan->loan_amount,
                        'parent_debit' => $coa->id,
                        'parent_credit' => $coa_1->coa->id,
                        'parent_debit_label' => $coa->name . ' (' . $branch_code . '-' . $coa->account_code . ')',
                        'parent_credit_label' => $coa_1->coa->name . ' (' . $branch_code . '-' . $coa_1->coa->account_code . ')',
                    );
                    echo getJournalDetail($params);
                    ?>
                </div>


                <div class="form-group">
                    <strong>&nbsp;&nbsp;&nbsp;Commitment Fee</strong>
                    <?php
                    $params = array(
                        'debit' => round($loan->schedule[0]->fee, 2),
                        'credit' => round($loan->schedule[0]->fee, 2),
                        'parent_debit' => $coa_1->coa->id,
                        'parent_credit' => $coa_2->id,
                        'parent_debit_label' => $coa_1->coa->name . ' (' . $branch_code . '-' . $coa_1->coa->account_code . ')',
                        'parent_credit_label' => $coa_2->name . ' (' . $branch_code . '-' . $coa_2->account_code . ')',
                    );
                    echo getJournalDetail($params);
                    ?>
                </div>

                <div class="form-group">
                    <label class="col-sm-3"></label>
                    <div class="col-sm-6">
                        <button type="submit" id="button" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;Save
                        </button>
                        <a href="javascript:history.back();" class="btn btn-danger"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;Cancel</a>
                    </div>
                    <br/><br/>
                </div>


                <div>
                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="mSchedule"
                         class="modal fade">
                        <div class="modal-dialog md-modify">
                            <div class="modal-content">
                                <div id="printArea" class="printArea">
                                    @include('api.report_header','')
                                    <div class="modal-header bo-border">
                                        <ul id="language" class="pull-right">
                                            @if(ROUND_NUM=='UP')
                                                <li><a class="btn btn-default" id="click_round" href="#"><i
                                                                class="glyphicon glyphicon-arrow-up"></i> {{trans('multiple.round')}}
                                                    </a></li>
                                            @elseif(ROUND_NUM=='DOWN')
                                                <li><a class="btn btn-default" id="click_round" href="#"><i
                                                                class="glyphicon glyphicon-arrow-down"></i> {{trans('multiple.round')}}
                                                    </a></li>
                                            @else
                                                <li><a class="btn btn-default" id="click_round" href="#"><i
                                                                class="glyphicon glyphicon-resize-vertical"></i> {{trans('multiple.round')}}
                                                    </a></li>
                                            @endif
                                            <li>
                                                <button class="btn btn-warning" id="printer"><i
                                                            class="fa fa-print"></i> {{ trans('multiple.m_print') }}
                                                </button>
                                            </li>
                                            <li>
                                                <button class="btn btn-warning" id="customer_printer"><i
                                                            class="fa fa-print"></i> {{trans('loan.l_customer_print')}}
                                                </button>
                                            </li>
                                            <li>
                                                <button aria-hidden="true" data-dismiss="modal" class="btn btn-danger"
                                                        id="close">×
                                                </button>
                                            </li>
                                        </ul>
                                        <h4 class="modal-title schedule_title">{{ trans('loan.l_repayment_schedule') }}</h4>
                                    </div>

                                    <div class="modal-body" id="schedule-table"></div>
                                    <div class="signature">
                                        <div class="left_content">
                                            <p style="text-align: center;">ជ.នាយកដ្ឋានឥណទាន</p>
                                            <br><br><br><br><br>
                                            <p>.............................................................</p>
                                            <p>ឈ្មោះ/Name:</p>
                                            <p>ចុះថ្ងៃទី............/............./.....................</p>
                                        </div>
                                        <div class="right_content">
                                            <p style="text-align: center">ស្នាមមេដៃស្តំាកូនបំណុល</p>
                                            <br><br><br><br><br>
                                            <p>.............................................................</p>
                                            <p>ឈ្មោះ/Name:</p>
                                            <p>ចុះថ្ងៃទី............/............./.....................</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="hide">
                <input type="hidden" name="is_disburse" id="is_disburse" value="1"/>
                <input type="hidden" name="loan_id" id="loan_id" value="{{$loan->id}}"/>
                <input type="hidden" name="repayment_type" id="repayment_type" value="{{$loan->repayment_type}}"/>
                <input type="hidden" name="balloon_amount_array" id="balloon_amount_array"
                       value="{{$loan->balloon_amount_array}}"/>
                <?php
                if ($loan->balloon_amount_array) {
                $ex = explode(',', $loan->balloon_amount_array);
                for ($i = 0; $i < count($ex); $i++) {
                ?>
                <input type="hidden" name="balloon_input[]" value="<?php echo $ex[$i] ?>" class="balloon_input"/>
                <?php
                }
                }
                ?>

                <?php foreach ($repayments as $rep) { ?>
                <input type="hidden" name="principal_input[]" value="<?php echo $rep->principal ?>"
                       class="principal_input"/>
                <?php } ?>

                <input type="hidden" name="account_name" id="client-name"
                       value="{{$loan->client_loan_account->account_name}}"/>
                <input type="hidden" name="days_of_month" id="days_of_month" value="{{$loan->days_of_month}}"/>
                <input type="hidden" name="loan_amount" id="loan_amount" value="{{$loan->loan_amount}}"/>
                <input type="hidden" name="loan_duration" id="loan_duration" value="{{$loan->loan_duration}}"/>
                <input type="hidden" name="interest_rate" id="interest_rate" value="{{$loan->interest_rate}}"/>
                <input type="hidden" name="balloon" id="balloon" value="{{$loan->balloon}}"/>
                <input type="hidden" name="balloon_month" id="balloon_month" value="{{$loan->balloon_month}}"/>
                <input type="hidden" name="monthly_payment" id="monthly_payment" value="{{$loan->monthly_payment}}"/>
                <input type="hidden" name="intraday_rate" id="intraday_rate" value="{{$repayments[0]->intraday_rate}}"/>
                <input type="hidden" name="round" id="round" value="{{ROUND_NUM}}"/>
            </div>

        </div>
    </section>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/form.v.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/add-loan.js',isset($secure) ? false : false) }}"></script>
    <script src="{{ asset('theme/js/iCheck/jquery.icheck.js',isset($secure) ? false : false)}}"></script>
    <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false) }}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>

    <script type="text/javascript">
        $('.dpYears').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            setDate: new Date()
        });

        $(document).ready(function () {
            var data = <?php echo json_encode($product_id); ?>;
            new AddLoan(data);
            var parent_debit_label_last = $('input[name="parent_debit_label"]:last').val();
            getDisburseType();
            $('select[name="select_type"]').change(function () {
                this_ = $(this);
                var coa_dd = <?php echo json_encode($coa_1); ?>;
                if (this_.val() == 0) {
                    //  var res = JSON.parse(coa_dd);
                    $('input[name="parent_credit_label"]:first').val(parent_debit_label_last);
                    $('.parent_credit_className:first').val(coa_dd.coa_id);
                    $('select[name="select_sub_type"]').hide();
                    $('input[name="parent_debit_label"]:last').val(parent_debit_label_last);
                    $('.parent_debit_className:last').val(coa_dd.coa_id);
                } else {
                    $.ajax({
                        url: "{{ route('getDisburseType') }}",
                        data: "select_type=" + this_.val() + "&currency=" + $('input[name="currency"]').val(),
                        method: 'get',
                        success: function (res) {
                            res = JSON.parse(res);
                            var credit_value = res.name + " (<?php echo $branch_code . '-' ?>" + res.account_code + ")";
                            $('input[name="parent_credit_label"]:first').val(credit_value);
                            $('.parent_credit_className:first').val(res.id);
                            $('select[name="select_sub_type"]').hide();

                            $('input[name="parent_debit_label"]:last').val(credit_value);
                            $('.parent_debit_className:last').val(res.id);
                        }
                    });
                }
            });

            $("body").on("change", "select[name='select_sub_type']", function () {
                this_val = $(this).val();
                $(this).find('option').each(function () {
                    if ($(this).attr('value') == this_val)
                        this_label = $(this).text();
                });

                $('input[name="parent_credit[]"]:first').val(this_val);
                $('input[name="parent_credit_label"]:first').val(this_label);
            });

            /*
                $("#commission_fee").on('keyup', function (e) {
                    this.value = this.value.replace(/[^0-9\.]/g, ''); //only number accept
                    if ($(this).val() != '') {
                        fee = $(this).val() * $('#amount').val() / 100;
                        $(this).parent().find('.input-group-addon').text(fee);
                        $('input[name="debit[]"]:last').val(fee);
                        $('input[name="credit[]"]:last').val(fee);
                    } else {
                        $(this).parent().find('.input-group-addon').text('%');
                    }
                });
            */
            $("#admin_fee").on('keyup', function (e) {
                this.value = this.value.replace(/[^0-9\.]/g, ''); //only number accept
                if ($(this).val() != '') {
                    fee = $(this).val();
                    $(this).parent().find('.input-group-addon').text(fee);
                    $('input[name="debit[]"]:last').val(fee);
                    $('input[name="credit[]"]:last').val(fee);
                } else {
                    $(this).parent().find('.input-group-addon').text('%');
                }
            });

            //make sure view_pay_schedule is clicked
            $('#view_pay_schedule').trigger('click');

            $("body").on("change", "#disburse_on", function () {
                $('#contract_date').val($(this).val());
            });


            $('.contract_date').hide();
            $('.group input').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            }).on('ifToggled', function (e) {
                e.preventDefault();
                var chck = $(this).prop('checked');
                if (chck) {
                    $(this).parents('.form-group').find('.input-append').show();
                } else {
                    $(this).parents('.form-group').find('.input-append').hide();
                }
            });

            $(document).on('click', '#click_round', function () {
                this_ = $(this).find('i');
                if (this_.hasClass('glyphicon-arrow-up')) {
                    this_.removeClass('glyphicon-arrow-up');
                    this_.addClass('glyphicon-resize-vertical');
                    $('#round').val('NONE');
                } else if (this_.hasClass('glyphicon-resize-vertical')) {
                    this_.removeClass('glyphicon-resize-vertical');
                    this_.addClass('glyphicon-arrow-down');
                    $('#round').val('DOWN');
                } else if (this_.hasClass('glyphicon-arrow-down')) {
                    this_.removeClass('glyphicon-arrow-down');
                    this_.addClass('glyphicon-arrow-up');
                    $('#round').val('UP');
                }

                $("#mSchedule #close").click();
                setTimeout(function () {
                    $('#view_pay_schedule').trigger('click');
                }, 1000);
                return false;
            });


            $(document).on('click', '.add_fee', function () {
                this_html = $(this).parents('tr').html();
                $(this).parents('tbody').append('<tr>' + this_html + '</tr>');
            });

            $(document).on('click', '.remove_fee', function () {
                ch_length = $('.remove_fee').length;
                if (ch_length > 1) $(this).parents('tr').remove();
            });

        });
        $(document).on('change', '#sel_tellers', function () {

            var till_account = JSON.parse('<?PHP echo json_encode($tellers);?>');
            var Sel_till_account_id = $('#sel_tellers option:selected').val();
            // console.log(till_account);
            $.each(till_account, function (inx, vals) {
                console.log(vals);
                if (parseInt(Sel_till_account_id) === parseInt(vals.id)) {
                    if (parseInt(vals.status) === 1) {
                        alert('This till account was closed'+vals.name + vals.account_name);
                        $('#sel_tellers').val(0);
                        $('#sel_tellers').attr('selected', true);
                    }
                }
            });
        });

        var loaded = 0;
        $("#start_date").on("change", function () {

            if (loaded == 1) {

                console.log(loaded);
                $('#view_pay_schedule').trigger('click');

            }
            loaded++;
        });

        function getDisburseType(){
            this_ = $('select[name="select_type"]');
            var parent_debit_label_last = $('input[name="parent_debit_label"]:last').val();
            var coa_dd = <?php echo json_encode($coa_1); ?>;
            if (this_.val() == 0) {
                //  var res = JSON.parse(coa_dd);
                $('input[name="parent_credit_label"]:first').val(parent_debit_label_last);
                $('.parent_credit_className:first').val(coa_dd.coa_id);
                $('select[name="select_sub_type"]').hide();
                $('input[name="parent_debit_label"]:last').val(parent_debit_label_last);
                $('.parent_debit_className:last').val(coa_dd.coa_id);
            } else {
                $.ajax({
                    url: "{{ route('getDisburseType') }}",
                    data: "select_type=" + this_.val() + "&currency=" + $('input[name="currency"]').val(),
                    method: 'get',
                    success: function (res) {
                        res = JSON.parse(res);
                        var credit_value = res.name + " (<?php echo $branch_code . '-' ?>" + res.account_code + ")";
                        $('input[name="parent_credit_label"]:first').val(credit_value);
                        $('.parent_credit_className:first').val(res.id);
                        $('select[name="select_sub_type"]').hide();

                        $('input[name="parent_debit_label"]:last').val(credit_value);
                        $('.parent_debit_className:last').val(res.id);
                    }
                });
            }
        }

    </script>
@endsection