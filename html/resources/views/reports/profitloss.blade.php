@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('sidebar.sb_profit_loss') }}
            @if($start && $end){{ trans('multiple.m_from') }} {{ $start }} {{ trans('multiple.m_to') }} {{ $end }} @else Invalid date supplied. @endif
                @foreach($branch as $b)
                    @if(isset($branch_id))
                        @if($branch_id==$b->id)
                            ({{ $b->branch_name }})
                        @endif
                     @endif
                @endforeach
        </header>
        <div class="panel-body">
            <div class="position-center" style="width:100%;">
                <form role="form" method="post" action="{{ route('rpt_profitloss') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <label class="control-label col-md-1">{{ trans('multiple.m_start_date') }}</label>
                        <div class="col-md-2">
                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" class="input-append date dpStart">
                                <input type="text" name="dpStart" placeholder="Select start date" size="16" class="form-control" value="{{ $start?$start:old('dpStart') }}">
                                    <span class="add-on birhtdateDatepicker ptl-3">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                  </span>
                            </div>
                        </div>
                        <label class="control-label col-md-1">{{ trans('multiple.m_end_date') }}</label>
                        <div class="col-md-2">
                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpEnd">
                                <input type="text" name="dpEnd" placeholder="Select end date" size="16" class="form-control" value="{{ $end?$end:old('dpEnd') }}">
                                    <span class="add-on birhtdateDatepicker ptl-3">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                  </span>
                            </div>
                        </div>
                        <label class="control-label col-md-1">{{ trans('report.rpt_branch_name') }}</label>
                        <div class="col-md-2">
                            <select class="form-control" id="selBrand" name="selBrand">
                                <option value="">-</option>
                                @foreach($branch as $b)
                                    <option value="{{ $b->id }}" 
                                        @if(isset($branch_id)) 
                                            @if($branch_id==$b->id)
                                                selected
                                            @endif
                                        @endif>{{ $b->branch_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div><br/>
                    <div class="row">
                        <div class="col-lg-offset-7 col-lg-5">
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <br/><br/>
            <div id="printArea" style="clear: both;">
            @include('api.report_header',['co_phone'=>!empty($co_id->co_user) ? $co_id->co_user->phone: ''])
            <h4 class="sch_title">
                {{ trans('sidebar.sb_profit_loss') }}
                @if($start && $end){{ trans('multiple.m_from') }} {{ $start }} {{ trans('multiple.m_to') }} {{ $end }} @else Invalid date supplied. @endif
                    @foreach($branch as $b)
                        @if(isset($branch_id))
                            @if($branch_id==$b->id)
                                ({{ $b->branch_name }})
                            @endif
                         @endif
                    @endforeach
            </h4>
            <section id="unseen">
                <table class="table profitLoss table-bordered table-striped table-condensed">
                    <thead class="th-center">
                        <tr>
                            <th colspan=2>{{ trans('report.rpt_profit(revenue)') }}</th><th colspan=2>{{ trans('report.rpt_cost_and_loss') }}</th>
                        </tr>
                        <tr>
                            <th>{{ trans('report.rpt_item') }}</th><th>{{ trans('report.rpt_value') }}</th><th>{{ trans('report.rpt_item') }}</th><th>{{ trans('report.rpt_value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $totalProfit = 0.0; 
                            $totalCost = 0.0;
                            $revenue = 0.0;
                            $penalty = 0.0;

                        if(!empty($loan_repayment)){
                            foreach($loan_repayment as $p){
                                if(!empty($p->payment)){
                                    foreach($p->payment as $pay){
                                         $revenue += $pay->paid_interest;
                                          $penalty += $pay->penalty_amount;
                                    }
                                }
                            }
                        }
                        $f_charge = config('static_data.fee_charge');
                        $charge_payoff = array_fill(0,count($f_charge) + 1,0.00);
                        if(!empty($payoff)){
                            foreach($payoff as $pf){
                                $charge_payoff[0] += $pf->payoff_fee;
                            }
                        }
                        if(!empty($fee_charge)){
                            foreach($fee_charge as $charge){
                                foreach($charge->feecharge as $fc){
                                    if(!isset($charge_payoff[$fc->charge_type])) $charge_payoff[$fc->charge_type] = 0.00;
                                    $charge_payoff[$fc->charge_type] += $fc->charge_amount;
                                }
                            }
                        }

                        $c_fee = config('static_data.fee_cost_type');
                        $cost_write_off = array_fill(0,count($c_fee) + 1,0.00);
                        if(!empty($writeoff)){
                            foreach($writeoff as $wf){
                                $cost_write_off[0] += $wf->amount;
                            }
                        }
                        if(!empty($cost_fee)){
                            foreach($cost_fee as $cost){
                              foreach($cost->costfee as $cf){
                                  if(!isset($cost_write_off[$cf->cost_type])) $cost_write_off[$cf->cost_type] = 0.00;
                                  $cost_write_off[$cf->cost_type] += $cf->cost_amount;
                              }
                            }
                        }
                        $totalCost = array_sum($cost_write_off);
                        $totalProfit = array_sum($charge_payoff) + $penalty + $revenue;

                        $label_profit = [0 => 'Early Payment'];
                        $label_profit = array_merge($label_profit,$f_charge);
                        $label_loss  = [0=>'Write Off'];
                        $label_loss = array_merge($label_loss,$c_fee);
                        $ll_count = count($label_loss);
                        $lp_count = count($label_profit) + 2;
                        $count = $label_loss > $lp_count ? $ll_count : $lp_count;
                        for($i = 0; $i < $count; $i++){
                           $tr = '<tr>';
                              if($i < 2){
                                 if($i == 0){
                                    $tr .= '<td>Interest Revenue</td><td align="right">'.number_format($revenue,2,'.',',').'</td>';
                                 }elseif($i== 1){
                                    $tr .= '<td>Penalty Charge</td><td align="right">'.number_format($penalty,2,'.',',').'</td>';
                                 }
                              }else{
                                $index = $i - 2;
                                 if(array_key_exists($index,$label_profit)){
                                    $tr .= '<td align="left">'.$label_profit[$index].'</td>';
                                    if(array_key_exists($index,$charge_payoff)){
                                     $tr .= '<td align="right">'. number_format($charge_payoff[$index],2,'.',',') .'</td>';
                                    }else{
                                        $tr .= '<td>-</td>';
                                    }
                                  }else{
                                     $tr .= '<td>-</td><td align="right">-</td>';
                                  }
                              }

                              if(array_key_exists($i,$label_loss)){
                                  $tr .= '<td align="left">'.$label_loss[$i].'</td>';
                                  if(array_key_exists($i,$cost_write_off)){
                                      $tr .= '<td align="right">'. number_format($cost_write_off[$i],2,'.',',') .'</td>';
                                  }else{
                                      $tr .= '<td>-</td>';
                                  }
                              }else{
                                $tr .= '<td>-</td><td>-</td>';
                              }
                           $tr .= '</tr>';
                           echo $tr;
                        }
                        ?>
                        <tr><td>{{ trans('report.rpt_total') }}</td><td align="right">{{number_format($totalProfit,2,'.',',')}}</td><td></td><td align="right">{{number_format($totalCost,2,'.',',')}}</td></tr>
                    </tbody>
                </table>
                <h5>{{ trans('report.rpt_net_profit') }} : ${{number_format(($totalProfit - $totalCost),2,'.',',')}}</h5>
            </section>
        </div>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript">
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
    </script>
@endsection