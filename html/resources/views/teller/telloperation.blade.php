@extends('layouts.app')
@section('css')
    <link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/css/teller.css',isset($secure) ? false : false)}}"/>
@endsection
<style type="text/css">
    .select2{
        width: 100% !important;
        margin-bottom: 10px !important;
    }
    #s2id_client,#s2id_from{
        width: 100% !important;
    }
</style>
<?php $currency = config('static_data.currency_symbol'); $flag = 1;?>
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <section class="panel reloaddive">
                <header class="panel-heading"><span>{{ trans('sidebar.sb_till_operation') }}</span></header>

                <div class="panel-body">
                    <section id="flip-scroll">
                        @foreach($tellers as $teller)
                            <div class="row">
                                <div class="col-lg-2">Till Account : <b>{{ $teller->account_name}}</b></div>
                                <div class="col-lg-2">Branch : <b>{{ $teller->branch_name }}</b></div>
                                <div class="col-lg-2">Balance : <b>{{ $currency[$teller->currency_id].number_format($teller->balance, 2)}} </b></div>
                                <div class="col-lg-2">Status : <b>{{ ($teller->status==1)? 'Closed':'Opened'}} </b></div>
                            </div>
                            <?php $flag = $flag*$teller->status;?>
                        @endforeach
                    </section>
                    <hr/>
                    <div class="panel-body" style="">
                        <div class="col-sm-12">
                            <div class="left_content">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <h4>{{ trans('report.rpt_cash_in') }}</h4>
                                        <div class="form-group">
                                            <ul class="list-unstyled list-group-item">
                                                <li><a class="btn btn-group btn-lg btn-primary issueTill " data-toggle="modal" data-target="issueTill">{{ trans('teller.t_issue_till') }}</a>
                                                </li><br/>
                                                <li><a class="btn btn-group btn-lg btn-primary deposit" href="#">{{ trans('teller.t_deposit') }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <h4>{{ trans('report.rpt_cash_out') }}</h4>
                                        <ul class="list-unstyled list-group-item ">
                                            <li><a class="btn btn-group btn-lg btn-primary returnTill" data-toggle="modal" data-target="returnTill">{{ trans('teller.t_return_till') }}</a>
                                            </li>
                                            <br/>
                                            {{--true = chief; false = teller--}}
                                            <?PHP if($tillType): ?>
                                            <li>
                                                <a class="btn btn-group btn-lg btn-primary transferTill">{{ trans('teller.t_transfer_till') }}</a>
                                            </li><br/>
                                            <?PHP endif; ?>
                                            <li><a class="btn btn-group btn-lg btn-primary cash_withdrawal" href="#">{{ trans('teller.t_cash_withdrawal') }}</a>  </li><br/>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/accounting.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript">
        var tellers = null;
        var cheifs = null;
        var chiefdata = null;
        var status_flag = '<?php echo $flag;?>';
        let repay_type = <?php echo json_encode(config('static_data.payment_type'));?>;
        repay_type = { ...repay_type, 129: '001367567 ABA Revenue-Exp (USD) - BS Property Management (BPM)',
            130:"001367568 ABA Revenue-Exp (USD) - ESAT Property Management (EPM)",
            131:"002779699 ABA Revenue-Exp (USD) - Internet Internal Provider (IIP)",
            132:"000102158 SBILH Revenue (USD) Borey Chaktomuk City (BCC)",
            133:"000159190 SBILH Revenue (USD) East Land and Home (EAST)",
            134:"000815896 ABA Expenses (USD) - East Sihanouk Park (ESP)",
            135:"100204285 BS&EAST LAND AND HOME (BEHQ) (USD)"
        };
        
            if(status_flag == 1){ // all account opened
                $(".issueTill").attr("disabled", "disabled");
                $(".returnTill").attr("disabled", "disabled");
                $(".transferTill").attr("disabled", "disabled");
                $(".repayLoan").attr("disabled", "disabled");
                $(".incomeFee").attr("disabled", "disabled");
                $(".DisburseLoan").attr("disabled", "disabled");
                $(".expense").attr("disabled", "disabled");
            }
        $(document).ready(function(){

            operationScript();

            $(".issueTill").click(function () {
                ScriptRequire(['/js/load/operation/issue_till.js'], function(status) {
                    $('<div id="result"></div>').appendTo('body');
                    $('<div id="loading"></div>').appendTo('body');
                    imgLoading(true, 'Loading....',5,'warning');
                    if(status === 'success'){
                        issueTill_for_chief('issueTill');
                    }
                });
            });
            $(".deposit").click(function () {
                ScriptRequire(['/js/load/deposit.js'], function(status) {
                    $('<div id="result"></div>').appendTo('body');
                    $('<div id="loading"></div>').appendTo('body');
                    imgLoading(true, 'Loading....',5,'warning');
                    if(status === 'success'){
                        deposit('issueTill'); 
                        setTimeout(function() {                      
                            var allow_postback_date = '<?php echo $tellers[0]->allow_postback_date ;?>';
                            if(allow_postback_date!=='1'){
                                $("#till_date").attr("disabled", "disabled");
                                $("#start_date .offonDatepicker").hide();
                                
                            }               
                        }, 500);
                    }
                });
            });
            $(".cash_withdrawal").click(function () {

                ScriptRequire(['/js/load/withdraw.js'], function(status) {
                    $('<div id="result"></div>').appendTo('body');
                    $('<div id="loading"></div>').appendTo('body');
                    imgLoading(true, 'Loading....',5,'warning');
                    if(status === 'success'){
                        withdraw('cash_withdrawal');
                        setTimeout(function() {                      
                            var allow_postback_date = '<?php echo $tellers[0]->allow_postback_date ;?>';
                            if(allow_postback_date!=='1'){
                                $("#till_date").attr("disabled", "disabled");
                                $("#start_date .offonDatepicker").hide();
                                
                            }               
                        }, 500);
                    }
                });
            });

            $(".transferTill").click(function () {
                ScriptRequire(['/js/load/transferTill.js'], function(status){
                    $('<div id="result"></div>').appendTo('body');
                    $('<div id="loading"></div>').appendTo('body');
                    imgLoading(true, 'Loading....',5,'warning');
                    if(status === 'success'){
                        transferTill('transferTill');
                    }
                });
            });
//            $(".expense").click(function () {
//
//                ScriptRequire(['/js/load/expends.js'], function(status){
//
//                    $('<div id="result"></div>').appendTo('body');
//                    $('<div id="loading"></div>').appendTo('body');
//                    imgLoading(true, 'Loading....',5,'warning');
//                    if(status === 'success'){
//                        return expense();
//                    }
//                });
//            });
            $(".returnTill").click(function () {
                ScriptRequire(['/js/load/returnTill.js'], function(status){
                    $('<div id="result"></div>').appendTo('body');
                    $('<div id="loading"></div>').appendTo('body');
                    imgLoading(true, 'Loading....',5,'warning');
                    if(status === 'success'){
                        returnTill('returnTills');
                    }
                });
            });

            $(document).on('change', '#tran_teller', function () {
                var id = $(this).attr('selected', true).val();
                checkTillAccountStatus(id);
            });
        });

        //i used ajax for calling script, yes I see
        function operationScript() {


            var url = '{{ asset('js/load/operation.js',isset($secure) ? false : false) }}';
            $.ajax({
                url: url,
                method: 'get',
                dataType: 'script',
                success: function (data, status) {
                    //if (status == 'success') {
                    //}
                }, error: function (jgxht, status, errorthrogh) {
                    imgLoading(true, "Fail!!!! refresh your page. Errors Type:" + status, 5, status);
                }
            });
        }

        function checkTillAccountStatus(id) {

            if (typeof id != 'undefined') {

                for (var key in tellers) {
                    var val = tellers[key];
                    if (Number(id) === Number(val.id)) {
                        if (Number(val.tillstatus) === 1) {
                            imgLoading(true, 'This till account named ' + '<b><i> ' + val.name + ' </i></b>' + ' was closed', 4, 'warning');
                            $('input[type=submit]').attr('disabled', true);
                        } else {
                            imgLoading(true, 'Till account ' + ' <b><i> ' + val.name + ' </i></b> ' + ' is opening', 1, 'success');
                            $('input[type=submit]').attr('disabled', false);
                        }
                    }
                }
            } else {
                return false;
            }
        }

        function ScriptRequire(script, retn) {

            $.each(script, function (inx, vals) {
                $.ajax({
                    url: vals,
                    dataType: "script",
                    cache: false,
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    },
                    success: function (data, status) {
                        return retn(status);
                    },
                    error: function () {
                        throw new Error("Could not load script " + script);
                    }
                });
            });
        }
    </script>
@endsection
