@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('report.rpt_write_off_loan_report') }}
            @if(isset($start) && isset($end))
                {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
            @else
                {{ isset($start)?'Loan write-off on '.date("d-M-Y", strtotime($start)):'' }}
                {{ isset($end)?'Loan write-off on '.date("d-M-Y", strtotime($end)):'' }}
            @endif
            @foreach($branch as $b)
                @if(isset($branch_id))
                    @if($branch_id==$b->id)
                        ({{ $b->branch_name }})
                    @endif
                @endif
            @endforeach
        </header>
        <div class="panel-body">
            <div class="position-center">
                <form role="form" class="cmxform" method="get" action="{{ route('rpt_writeoff') }}">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="Name" class="control-label">{{ trans('multiple.m_start_date') }}</label>
                                
                                    <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpStart">
                                        <input type="text" name="dpStart" size="16" class="form-control" value="{{ isset($start)?$start:old('dpStart') }}">
                                            <span class="add-on birhtdateDatepicker ptl-3">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                          </span>
                                    </div>
                                
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ trans('multiple.m_end_date') }}</label>
                                
                                    <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpEnd">
                                        <input type="text" name="dpEnd" size="16" class="form-control" value="{{ isset($end)?$end:old('dpEnd') }}">
                                            <span class="add-on birhtdateDatepicker ptl-3">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                          </span>
                                    </div>
                                
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label">{{ trans('report.rpt_co_name') }}</label>
                                
                                    <select class="form-control" id="co" name="co">
                                        <option value="">-</option>
                                        @foreach($co as $c)
                                            <option value="{{ $c->id }}" {{ isset($co_id)?$co_id==$c->id?'selected':'':''  }}>{{ $c->name}}</option>
                                        @endforeach
                                    </select>
                                
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ trans('report.rpt_branch_name') }}</label>
                                
                                    <select class="form-control" id="branch" name="branch">
                                        <option value="">-</option>
                                        @foreach($branch as $b)
                                            <option value="{{ $b->id }}" {{ isset($branch_id)?$branch_id==$b->id?'selected':'':''  }}>{{ $b->branch_name}}</option>
                                        @endforeach
                                    </select>
                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                            <a id="export" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.rpt_export') }}</a>
                            <a id="xexport" class="btn btn-primary"><i class="fa  fa-sign-out"></i> {{ trans('report.xrpt_export') }}</a>
                        </div>
                    </div>
                </form>
            </div>
            <br/><br/>
            <div id="printArea" style="clear: both">
                @include('api.report_header',['co_phone'=>!empty($co_phone->co_user) ? $co_phone->co_user->phone: ''])
                <h4 class="sch_title">
                    {{ trans('report.rpt_write_off_loan_report') }}
                    @if(isset($start) && isset($end))
                        {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
                    @else
                        {{ isset($start)?'Loan write-off on '.date("d-M-Y", strtotime($start)):'' }}
                        {{ isset($end)?'Loan write-off on '.date("d-M-Y", strtotime($end)):'' }}
                    @endif
                    @foreach($branch as $b)
                        @if(isset($branch_id))
                            @if($branch_id==$b->id)
                                ({{ $b->branch_name }})
                            @endif
                        @endif
                    @endforeach
                </h4>
                <section id="unseen">
                    <table class="table table-bordered table-striped table-condensed writeoff" id="writeoff">
                        <thead class="th-center">
                            <tr>
                                <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.m_no') }}</th>
                                <th rowspan=2 style="vertical-align:middle;">{{ trans('customer.cus_customer_name') }}</th>
                                <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_contract_id') }}</th>
                                <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_contract_date') }}</th>
                                <th rowspan=2 style="vertical-align:middle;">{{ trans('multiple.branch') }}</th>
                                <th rowspan=2 style="vertical-align:middle;">{{ trans('account.currency') }}</th>
                                <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_co_name') }}</th>
                                <th rowspan=2 style="vertical-align:middle;">{{ trans('product.p_productsTypes') }}</th>
                                <th rowspan=2 style="vertical-align:middle;">new {{ trans('product.p_productsTypes') }}</th>
                                <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_loan_amount') }}</th>
                                <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_int_rate') }}</th>
                                <th colspan=4 style="vertical-align:middle;">{{ trans('loan.l_total_paid_amount') }}</th>
                                <th rowspan=2 style="vertical-align:middle;">{{ trans('report.rpt_principal_balance') }}</th>
                                <th colspan=8 style="vertical-align:middle;">{{ trans('report.rpt_write_off') }}</th>
                            </tr>
                            <tr>
                                <th>{{ trans('loan.l_last_paid_date') }}</th>
                                <th>{{ trans('report.rpt_interest') }}</th>
                                <th>{{ trans('report.rpt_principal') }}</th>
                                <th>{{ trans('report.rpt_penalty') }}</th>
                                <th>{{ trans('report.rpt_date') }}</th>
                                <th>{{ trans('report.rpt_wo_amount') }}</th>
                                <th>{{ trans('report.prev_amount') }}</th>
                                <th>{{ trans('report.cur_amount') }}</th>
                                <th>{{ trans('report.balance') }}</th>
                                <th>{{ trans('report.last_paid_date') }}</th>
                                <th>{{ trans('report.last_paid_amount') }}</th>
                                <th>{{ trans('multiple.m_note') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; 
                                $new_loanCategory = config('static_data.new_loanCategory');
                            ?>
                            @forelse($loan_writeoff as $lw)
                                <?php
                                    $pre_per_balance = 0;
                                    $cur_per_paid = 0;
                                    $cur_per_balance = 0;
                                //dd($jd_wo_all->where('reference', 'LCF1505291203'));
                                //if($lw->contract_id == "TGL2016/008") dd($lw);
                                    $total_int = 0.0;
                                    $total_principal = 0.0;
                                    $total_penalty = 0.0;
                                    $temp_date = null;
                                    foreach($lw->payment as $p){
                                        $total_int += floatval($p->paid_interest);
                                        $total_principal += floatval($p->paid_principal);
                                        $total_penalty += floatval($p->penalty_amount);
                                        if($temp_date == null){
                                            $temp_date = $p->repayment_date;
                                        }else{
                                            if(strtotime($temp_date) < strtotime($p->repayment_date)){
                                                $temp_date = $p->repayment_date;
                                            }
                                        }
                                    }
                                    // Write Off Journal
                                    // previous paid
                                    $jd_wo_all_pre_journal = $jd_wo_all_pre->where('reference', $lw->contract_id);
                                    if(count($jd_wo_all_pre_journal) == 0){
                                        $pre_per_balance = 0;
                                    }else{
                                        foreach($jd_wo_all_pre_journal as $pre){
                                            $pre_per_balance += $pre->credit - $pre->debit;
                                        }
                                    }
                                    //$pre_per_balance = -get_balance_obj($jd_wo_all_pre, 'reference', $lw->contract_id);
                                    // current paid
                                    $jd_wo_all_cur_journal = $jd_wo_all->where('reference', $lw->contract_id);
                                    if(count($jd_wo_all_cur_journal) == 0){
                                        $cur_per_paid = 0;
                                    }else{
                                        foreach($jd_wo_all_cur_journal as $cur){
                                            $cur_per_paid += $cur->credit - $cur->debit;
                                        }
                                    }
                                    // current Balance
                                    $cur_per_balance = $lw->writeoff->amount - ($pre_per_balance + $cur_per_paid);
                                    // Last Paid Date/Amount
                                    $last_paid_date = $jd_wo_all_cur_journal->first()->journal->entry_date;
                                    $last_paid_amount = $jd_wo_all_cur_journal->first()->credit;
                                    $last_note = $jd_wo_all_cur_journal->first()->description;
                                    //        if($lw->contract_id == "TGL2016/008") dd($jd_wo_all_cur_journal->first());
                                    $acc_type = explode("-", $lw->client_loan_account->acc_key)[0];

                                ?>
                                <tr>
                                    <td align="center">{{ $i }}</td>
                                    <td><a href="{{ route('loan_writeoff_detail',[$lw->id]) }}">{{ $lw->client->client_name }}</td>
                                    <td align="center"><a href="{{ route('loan_detail',[$lw->id]) }}">{{ $lw->contract_id }}</a></td>
                                    <td align="center">{{ date("d-M-Y", strtotime($lw->start_date)) }}</td>
                                    <td align="center">{{ $lw->branch->short_name }}</td>
                                    <td align="center">{{ $lw->client_loan_account->currencies->code }}</td>
                                    <td>{{ $lw->co_user->name}}</td>
                                    <td>{{ $acc_type }}</td>
                                    <td>{{ $new_loanCategory[$acc_type] }}</td>
                                    <td align="right">{{ number_format($lw->loan_amount,2,'.',',') }}</td>
                                    <td align="center">{{ number_format($lw->interest_rate,2,'.',',') }}%</td>
                                    <td align="center"> {{((count($lw->payment) != 0)? date("d-M-Y", strtotime($temp_date)) : '-') }}</td>
                                    <td align="right">{{ ((count($lw->payment) != 0) ? number_format($total_int,2,'.',',') : '-') }}</td>
                                    <td align="right">{{ ((count($lw->payment) != 0) ? number_format($total_principal,2,'.',','): '-') }}</td>
                                    <td align="right">{{ ((count($lw->payment) != 0) ? number_format($total_penalty,2,'.',',') : '-') }}</td>
                                    <td align="right">{{ number_format($lw->loan_amount-$total_principal,2,'.',',') }}</td>
                                    <td align="center">{{ date("d-M-Y", strtotime($lw->writeoff->write_off_date)) }}</td>
                                    <td align="right">{{ number_format($lw->writeoff->amount,2,'.',',') }}</td>
                                    <td align="right">{{ number_format($pre_per_balance,2,'.',',') }}</td>
                                    <td align="right">{{ number_format($cur_per_paid,2,'.',',') }}</td>
                                    <td align="right">{{ number_format($cur_per_balance,2,'.',',') }}</td>
                                    <td align="right">{{ is_null($last_paid_date)?'-':date("d-M-Y", strtotime($last_paid_date))}}</td>
                                    <td align="right">{{ number_format($last_paid_amount,2,'.',',') }}</td>
                                    <td>{{ $last_note }}</td>
                                </tr>
                                <?php $i+=1; ?>
                            @empty
                                <tr><td colspan="14">{{ trans('multiple.m_no_result') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div>
                        @include('partials.pagination',['results'=>$loan_writeoff])
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/xlsx.full.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/Blob.min.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/FileSaver.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/tableexport.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.dpStart').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
            $('.dpEnd').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                setDate: new Date()
            });
        });
        $("#export").click(function (event) {
          var con = confirm("Do you really want to export to CSV file?");
          if(con == true){
              new TableExport(document.getElementById('writeoff'), {
                  formats: ['csv'],
                  filename:"writeoff"
              });
              $('button.csv').hide().click();
              $('.tableexport-caption').remove();
          }
          event.preventDefault();
      });
      $("#xexport").click(function (event) {
            var con = confirm("Do you really want to export to Excel file?");
            if(con == true){
                new TableExport(document.getElementById('writeoff'), {
                        formats: ['xlsx'],
                        filename: 'writeoff'
                    }).formatConfig.xlsx.mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $('button.xlsx').hide().click();
                    $('.tableexport-caption').remove();
            }
        });
    </script>
@endsection
