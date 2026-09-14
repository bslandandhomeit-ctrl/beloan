@extends('layouts.app')
@section('css')
   <link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
   <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet"/>
   <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/select2/select2.css',isset($secure) ? false : false) }}"/>
   <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}"/>
@endsection
<style type="text/css">
   .select2{
      width: 100% !important;
      margin-bottom: 10px !important;
   }
   #s2id_from,#s2id_loan_admin{
      width: 100% !important;
   }
</style>
@section('content')
   <div class="row">
      <div class="col-sm-12">
         <section class="panel reloaddive">
            <header class="panel-heading"><span>{{ trans('Deposit') }}</span></header>
               <div class="panel-body">
                  <form action="" class="cmxform form-horizontal deposit" enctype="form-data" id="sdeposit" method="post" name="sdeposit" onsubmit="return false;" style="margin-bottom: 0px;">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     <div class="container">
                        <div class="row">
                           <div class="col-lg-2 col-sm-2 col-xs-2" style="padding:14px 0px 0px 22px;">
                              <div class="form-group" style="height:42px;">
                                 <label for="Date" style="height:30px;">Date</label> <label style=
                                 "float: right; margin-right:69px;">:</label>
                              </div>
                              <div class="form-group" style="height:42px;">
                                 <label for="From" style="height:30px;">From</label> <label style=
                                 "float: right; margin-right:69px;">:</label>
                              </div>
                              <div class="form-group" style="height:42px;">
                                 <label for="Notify to" style="height:30px;">Notify to</label>
                                 <label style="float: right; margin-right:69px;">:</label>
                              </div>
                              <div class="form-group" style="height:42px;">
                                 <label for="Deposit type" style="height:30px;">Deposit type</label>
                                 <label style="float: right; margin-right:69px;">:</label>
                              </div>
                              <div class="form-group" style="height:42px;">
                                 <label for="To customer" style="height:30px;">To customer</label>
                                 <label style="float: right; margin-right:69px;">:</label>
                              </div>
                              <div class="form-group" style="height:42px;">
                                 <label for="Drawdown Account" style="height:30px;">Drawdown
                                 Account</label> <label style=
                                 "float: right; margin-right:69px;">:</label>
                              </div>
                              <div class="form-group" style="height:42px;">
                                 <label for=" currency type" style="height:30px;">currency type</label>
                                 <label style="float: right; margin-right:69px;">:</label>
                              </div>
                              <div class="form-group" style="height:42px;">
                                 <label for="Amount" style="height:30px;">Amount</label> <label style=
                                 "float: right; margin-right:69px;">:</label>
                              </div>
                              <div class="form-group" style="height:42px;">
                                 <label for="Description" style="height:30px;">Description</label>
                                 <label style="float: right; margin-right:69px;">:</label>
                              </div>
                           </div>
                           <div class="col-lg-4 col-md-4 col-xs-5" style="padding-right: 36px;">
                              <div class="input-append date dpYears form-group" data-date-format="yyyy/mm/dd" data-date-viewmode="years" data-initialize="datepicker" id="start_date">
                                 <input class="form-control" id="till_date" name="till_date" type="text"
                                 value="{{ date('Y-m-d H:i:s') }}">
                                 <span class="add-on birhtdateDatepicker">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                 </span>
                              </div>
                              <div class="form-group">
                                 {{-- <select class="select2 drawdown_acc_from" id="from" name="from">
                                 </select> --}}
                                 <input type="text" name="from" class="drawdown_acc_from" id="from">
                              </div>
                              <div class="form-group">
                                 {{-- <select class="select2" id="loan_admin" name="loan_admin">
                                    <option value="">
                                       Choose one
                                    </option>
                                 </select> --}}
                                 <input type="text" name="loan_admin" id="loan_admin" class="loan_admin">
                              </div>
                              <div class="form-group">
                                 <select class="select2" id="types" name="types">
                                    <option value="">
                                       Choose one
                                    </option>
                                 </select>
                              </div>
                              <div class="form-group">
                                 <input class="form-control" disabled id="withd_acc" name="withd_acc"
                                 placeholder="" style="" type="text" value="">
                              </div>
                              <div class="form-group">
                                 <input class="form-control" disabled id="drawdown_acc" name=
                                 "drawdown_acc" placeholder="" style="" type="text" value="">
                              </div>
                              <div class="form-group">
                                 <input class="form-control" disabled id="currency" name="currency"
                                 placeholder="" style="" type="text" value="">
                              </div>
                              <div class="form-group">
                                 <input class="form-control" disabled id="amount" name="amount"
                                 placeholder="" style="" type="text" value="">
                              </div>
                              <div class="form-group">
                                 <textarea class="form-control" id="descr" name="descr" rows=
                                 "10"></textarea>
                              </div>
                           </div>
                           <div class="col-lg-5 col-md-5 col-xs-5" style="padding-right: 36px;">
                              <div class="appendData"></div>
                           </div>
                        </div>
                     </div>
                     <div class="modal-footer">
                        <span>Print</span><input name="if_print" id="printID" type="checkbox" value="0" class="btn btn-info" style="width: 30px;transform: scale(2.3);margin-right: 4px; margin-bottom: 4px;margin-left:11px" >
                        <input class="btn btn-info" id="tillSubmit" name="submit" type="submit" value=
                        "Submit" disabled>
                     </div>
                  </form>
               </div>
            </section>
         </div>
      </div>
   </div>
@endsection
@section('js')
   <script src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
   <script type="text/javascript" src="{{ asset('js/my_custom_js.js') }}"></script>
   <script src="{{ asset('theme/js/select2/select2.js',isset($secure) ? false : false)}}"></script>
   <script src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
   {{-- <script src="{{ asset('js/accounting.min.js',isset($secure) ? false : false) }}"></script> --}}
   <script type="text/javascript">
      var alldata = {};
      $(document).ready(function() {
         $('.drawdown_acc_from').select2({
            minimumInputLength: -1,
            placeholder: "Choose one",
            ajax:{
               url: '{{ route('deposit')}}',
               dataType: 'json',
               type: "GET",
               quietMillis: 50,
               timeout: 3000,
               data: function (term) {
                  return {term: term};
               },
               results: function (data) {
                  if(data){
                     alldata = data.withdraw;
                     return {
                        results: 
                        $.map(data.withdraw, function (vals,keys) {
                           return {
                              text: vals.account_name+' ( ' + data.withdraw[keys].projects.dealer + ' - ' + data.withdraw[keys].unit_type.name + ' - ' + data.withdraw[keys].units.code + ') - ' + data.currency_list[data.withdraw[keys].currency],
                              slug: vals.account_name,
                              id: data.withdraw[keys].id,
                              name:'from'
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
         $('.loan_admin').select2({
            minimumInputLength: -1,
            placeholder: "Choose one",
            ajax:{
               url: '{{ route('deposit')}}',
               dataType: 'json',
               type: "GET",
               quietMillis: 50,
               timeout: 10,
               data: function (term) {
                  return {term: term};
               },
               results: function (data) {
                  if(data){
                        return {
                           results: 
                           $.map(data.loan_admin, function (vals,keys) {
                              return {
                                 text: data.loan_admin[keys].name,
                                 slug: data.loan_admin[keys].name,
                                 id: data.loan_admin[keys].id,
                                 name:'loan_admin'
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
         var repay_type = <?php echo json_encode(\App\Models\PaymentType::activeList());?>;
         var types = '<option value="">Choose one</option>';
         for(var keys in repay_type) {
            if(repay_type[keys] == 'Drawdown Account') continue;
            types += '<option value="' + repay_type[keys] + '"> ' + repay_type[keys] + ' </option>';
         }
         $("#types").html(types);
         $("#types").select2();
         $('.dpYears').datepicker({
            format: 'yyyy-mm-dd hh:ii:ss',
            autoclose: true,
            setDate: new Date()
         });
      });

      // function submitD(not_id = 0) {
         var withd_acc = {};
         var till_act_def_balance = {};
         var clicks = 1;
         $(document).on('change', '#from, #sp_user, #all', function (e) {
            var selc_dd_id = e.target.value || null;//$('#from option:selected').val() || null;
            $('#amount').val('');
            if (selc_dd_id != null) {
               $.each(alldata, function (inx, vals) {
                  till_act_def_balance  = parseFloat(vals.balance);
               });
               withd_acc = matchAndGetData(parseInt(selc_dd_id));
               if(withd_acc.projects){
                  $('#withd_acc').val(withd_acc.account_name + ' ( ' + withd_acc.account_no + ' ) - '+'( '+ withd_acc.projects.dealer +' - '+  withd_acc.unit_type.name + ' - ' + withd_acc.units.code+')');
               }else{
                  $('#withd_acc').val(withd_acc.account_name + ' ( ' + withd_acc.account_no + ' ) - '+'( N/A' +' - '+  withd_acc.unit_type.name + ' - ' + withd_acc.units.code+')');
               }
               $('#amount').attr('disabled', false);
               $('#drawdown_acc').val(withd_acc.account_no);
               $('#drawdown_acc').attr('disabled', false);
               $('#drawdown_acc').attr('readonly', true);

               $('#currency').val(withd_acc.currency_tbl.code);
               $('input[type=submit]').attr('disabled',false);
               $(document).on('keyup','#amount', function() {
                  var amount_val = parseFloat($('#amount').val());
                  if($(this).is('#amount')) {
                     $('#loading').remove();
                     $('input[type=submit]').attr('disabled', false);
                  }
               });
               $('#sdeposit').validate({
                  rules: {
                     client: {
                        required: true
                     }, amount: {
                        required: true, number: true
                     }, descr: {
                        required: true
                     }, loan_admin: {
                        required: true
                     }, types: {
                        required: true
                     }
                  }, submitHandler: function () {
                     if (confirm('Are you sure?')) {
                        var bank_name = $('input[name=bank_name]').val() ? $('input[name=bank_name]').val() : 0;
                        var check_num = $('input[name=check_num]').val() ? $('input[name=check_num]').val() : 0;
                        var balance = (parseFloat(withd_acc.balance) + parseFloat($('#amount').val()));
                        var data = $('#sdeposit').serialize() + '&client_id=' + parseInt(withd_acc.client_id) + '&id='
                        + parseInt(withd_acc.id) + '&users_id=' + parseInt($('#loan_admin option:selected').val());
                        data += '&currency_id=' + parseInt(withd_acc.currency) + '' + '&client_name=' + withd_acc.account_name + '&description=' + $('#descr').val() + '&amount='
                           + parseFloat($('#amount').val())
                           + '&balance=' + balance + '&types=' + $('#types option:selected').val() + '&bank_name='
                           + bank_name + '&check_num=' + check_num + '&till_date=' + $('#till_date').val()
                           + '&if_print='+ $('#printID').val() + '&not_id=issueTill';
                        // Ajaxs('/teller/deposit', 'post', 'json', data, function (data, status) {
                           $.ajax({
                              url: '/teller/deposit',
                              type: 'POST',
                              dataType: 'json',
                              data: data,
                              success:function(data,status){
                                 $('#loading').remove();
                                 if (status !== 'success') {
                                    return imgLoading(true, 'We can\' save your data t!!!', 5, 'warning');
                                 } else {
                                    if(data.till_state === false) {
                                       return imgLoading(true, 'Please check your account balance and status.', 5, 'warning');
                                    }
                                    if (data.ins_notify === true) {
                                       imgLoading(true, 'successfully!!!', 5, status);
                                       $('#result').remove();
                                       if (!$.isEmptyObject(data.print_url)) {
                                          var redirectWindow = window.open(data.print_url, '') || {};
                                          redirectWindow.location;
                                       }
                                    }
                                 }
                              }
                        });
                     }
                  }
               });
            }
         });
      // }
      $(document).on('change', '#types', function () {
         var del = $('#sdeposit').find('.appendData');
         if ($(this).is('#types')) {
            for (var i = 0; i <= del.length; i++) {
               del.children().remove();
            }
            $('<div class="form-group">' +
               '<label class="control-label col-sm-4" for="print"> Check number : </label>' +
               '<div class="col-sm-8 form-checkbox"> ' +
               '<input type="text" value="" name="check_num" class="form-control" />' +
               '</div>' +
               '</div>' +
               '<div class="form-group">' +
               '<label class="control-label col-sm-4" for="print"> Bank name: </label>' +
               '<div class="col-sm-8 form-checkbox"> ' +
               '<input type="text" value="" name="bank_name" class="form-control" />' +
               '</div>' +
               '</div>').appendTo('.appendData');
         }
      });

      function matchAndGetData(dd_id) {
         for (var keys in alldata) {
            if (parseInt(alldata[keys].id) === parseInt(dd_id)) {
               return alldata[keys];
            }
         }
      }

      function checkit(obj) {
         var cbs = document.getElementsByClassName("ch");
         for (var i = 0; i < cbs.length; i++) {
            cbs[i].checked = false;
         }
         obj.checked = true;
      }

      $(document).on('click', '#printID', function () {
         var check = $('#printID'), vals = 0;
         if ($(this).is(':checked')) {
            var loads = $('#loading');
            for (var i = 0; i <= loads.length; i++) {
               loads.children().remove();
            }
            $('<div id="loading"></div>').appendTo('body');
            imgLoading(true, 'You will print data after submit', 1, 'warning');
            $('#printID').val(1);
         } else if ($(this).is(':not(:checked)')) {
            $('#loading').remove();
            $('#printID').val(0);
         }
      });
   </script>
@endsection