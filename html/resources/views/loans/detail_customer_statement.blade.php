@extends('layouts.app')

@section('css')
   <link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
   <link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
   <link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
<?php $loan_status = config('static_data.loan_status');?>
@section('content')
   <div class="row">
       <div class="col-sm-12">
           <section class="panel">
                <header class="panel-heading">
                    <span>{{ trans('sidebar.sb_customer_account_statement') }}</span>
                </header>
                <div class="panel-body">
                  <div class="position-center" style="width:90%;">
                        @if($errors->addCate->has('ipCate'))
                            <div class="alert alert-danger fade in">
                                <button class="close close-sm" type="button" data-dismiss="alert">x</button>
                                {{$errors->addCate->first('ipCate')}}
                            </div>
                        @endif

                      <form role="form" id="frm_customer" method="get" action="{{ route('detail_customer_statement') }}">
                          <div class="row">
                              <div class="col-lg-12">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Customer Name: </label>
                                        <input type="text" class="form-control" id="custName" name="custName" value="{{$loan->client_loan_account->account_name}}" readonly="readonly" />
                                    </div>
                                    <div class="form-group">
                                        <label>Contract Number: </label>
                                        <input type="text" class="form-control" id="contractNum" name="contractNum" value="{{$loan->contract_id}}" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Loan Limit: </label>
                                        <input type="text" class="form-control" id="loanLimit" name="loanLimit" value="{{number_format($loan->loan_amount,2)}}" readonly="readonly" />
                                    </div>
                                    <div class="form-group">
                                        <label>Interest Rate: </label>
                                        <input type="text" class="form-control" id="interRate" name="interRate" value="{{$loan->interest_rate}}" readonly="readonly" />
                                    </div>
                                </div>

                              </div>
                          </div>
                      </form>
                      <div class="row">
                        <div class="col-lg-offset-3 col-lg-3">
                          <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                          <button class="btn btn-primary" id="export"><i class="fa fa-sign-out"></i> {{ trans('multiple.export') }}</button>
                        </div>
                      </div>
                    </div>
                    <br/><br/>
                    <div id="printArea">
                      @include('api.report_header')
                       <table class="table table-bordered table-striped table-condensed sticky-header">
                          <thead class="cf" style = "background : #1fb5ad">
                              <tr>
                                <th rowspan="2" style="text-align: center;">No</th>
                                <th rowspan="2" style="text-align: center;">Repayment Date</th>
                                <th colspan="4" style="text-align: center;">Plan</th>
                                <th rowspan="2" style="text-align: center;">Paid Date</th>
                                <th colspan="5" style="text-align: center;">Actual</th>
                                <th rowspan="2" style="text-align: center;">Remark</th>
                              </tr>
                              <tr>
                                <th style="text-align: center;">Principal</th>
                                <th style="text-align: center;">Interest</th>
                                <th style="text-align: center;">Fee</th>
                                <th style="text-align: center;">Other Fee</th>
                                <th style="text-align: center;">Principal</th>
                                <th style="text-align: center;">Interest</th>
                                <th style="text-align: center;">Fee</th>
                                <th style="text-align: center;">Other Fee</th>
                                <th style="text-align: center;">Penalty</th>
                              </tr>
                          </thead>
                            <tbody>
                              @forelse($loan->schedule as $sch)
                                <?php $count_n = count($repayment[$n]);?>
                                <tr>
                                    <?php
                                        $principal[] = $sch->principal;
                                        $interest[] = $sch->interest;
                                        $fee[] = $sch->fee;
                                        $other_fee[] = $sch->other_fee;
                                    ?>
                                      <td style="text-align: center">{{$sch->no}}</td>
                                      <td style="text-align: right">{{$sch->schedule_date}}</td>
                                      <td style="text-align: right">{{number_format($sch->principal,2)}}</td>
                                      <td style="text-align: right">{{number_format($sch->interest, 2)}}</td>
                                      <td style="text-align: right">{{number_format($sch->fee, 2)}}</td>
                                      <td style="text-align: right">{{number_format($sch->other_fee, 2)}}</td>
                                      
                                      @if(count($sch->payment) == 0)
                                        <?php $sch_int_left[] = $sch->interest?>
                                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                      @endif
                                      <?php
                                        $n = 0;
                                        foreach($sch->payment as $pay){
                                          $paid_principal[] = $pay->paid_principal;
                                          $paid_interest[] = $pay->paid_interest;
                                          $paid_fee[] = $pay->paid_fee;
                                          $paid_fee[] = $pay->paid_other_fee;
                                          $penalty_amount[] = $pay->penalty_amount;
                                      ?>
                                      @if($n > 0)
                                    </tr>
                                    <tr>
                                      <td colspan = 6></td>
                                      @endif
                                      <td style="text-align: center">{{$pay->repayment_date}}</td>
                                      <td style="text-align: right">{{number_format($pay->paid_principal, 2)}}</td>
                                      <td style="text-align: right">{{number_format($pay->paid_interest, 2)}}</td>
                                      <td style="text-align: right">{{number_format($pay->paid_fee, 2)}}</td>
                                      <td style="text-align: right">{{number_format($pay->paid_other_fee, 2)}}</td>
                                      <td style="text-align: right;">{{number_format($pay->penalty_amount, 2)}}</td>
                                      <td style="text-align: left;">{{$pay->note}}</td>
                                    <?php 
                                        $n++;
                                      }?>
                                </tr>
                              @empty
                               <tr><td colspan="9">{{ trans('multiple.m_no_result')}}</td></tr>
                              @endforelse
                              <?php 
                                $loan_account = $loan->client_loan_account;
                                $air_arr = get_journal_bal($loan_account->air_id);
                                $air_left = $air_arr['balance'] - array_sum($sch_int_left);
                              ?>
                              <tr style="background : lightyellow">
                                <td style="font-weight: bold; text-align: right;" colspan="3">Air till {{date('Y-m-d')}} :</td>
                                <td style="font-weight: bold; text-align: right;">{{number_format($air_left, 2)}}</td>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                              </tr>
                              <tr >
                                <td style="font-weight: bold;" colspan="2">Total</td>
                                <td style="font-weight: bold; text-align: right;">{{number_format(array_sum($principal), 2)}}</td>
                                <td style="font-weight: bold; text-align: right;">{{number_format(array_sum($interest) + $air_left, 2)}}</td>
                                <td style="font-weight: bold; text-align: right;">{{number_format(array_sum($fee), 2)}}</td>
                                <td style="font-weight: bold; text-align: right;">{{number_format(array_sum($other_fee), 2)}}</td>
                                <td></td>
                                <td style="font-weight: bold; text-align: right;">{{number_format(array_sum($paid_principal), 2)}}</td>
                                <td style="font-weight: bold; text-align: right;">{{number_format(array_sum($paid_interest), 2)}}</td>
                                <td style="font-weight: bold; text-align: right;">{{number_format(array_sum($paid_fee), 2)}}</td>
                                <td style="font-weight: bold; text-align: right;">{{number_format(array_sum($paid_other_fee), 2)}}</td>
                                <td style="font-weight: bold; text-align: right;">{{number_format(array_sum($penalty_amount), 2)}}</td>
                                <td></td>
                              </tr>
                            </tbody>
                       </table>
                     </div>
                    </div>
                </div>
           </section>
       </div>
   </div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/jquery-1.11.1.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery.validate.min.js',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/additional-methods.min',isset($secure) ? false : false) }}"></script>
<script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.floatThead.min.js',isset($secure) ? false : false)}}"></script>

<script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
<script>
    $(document).ready(function () {
      $(".sticky-header").floatThead({scrollingTop: 77});
    });
    $("#export").click(function (event) {
      var con = confirm("Do you really want to export to CSV file?");
      if(con == true){
          new TableExport(document.getElementsByTagName('table'), {
              formats: ['csv'],
              filename:"detail_customer_statement"
          });
          $('button.csv').hide().click();
          $('.tableexport-caption').remove();
      }
      event.preventDefault();
    });
</script>
@endsection
