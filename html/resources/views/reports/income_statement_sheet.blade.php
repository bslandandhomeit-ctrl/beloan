@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure) ? false : false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure) ? false : false)}}" />
    <style>
        table tr td:not(:first-child){
            text-align:right;
        }
    </style>
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('sidebar.sb_income_statement') }}
            @if(isset($start) && isset($end))
                {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
            @else
                {{ isset($start)?'Report on'.date("d-M-Y", strtotime($start)):'' }}
                {{ isset($end)?'Report on '.date("d-M-Y", strtotime($end)):'' }}
            @endif
        </header>

        <div class="panel-body">
            <div class="position-center" style="width:100%;">
                <form role="form" class="cmxform form-horizontal" method="get" action="{{ route('rpt_income_statement_ytd') }}">
                    <div class="row">
                        <label class="control-label col-md-1">{{ trans('multiple.m_start_date') }}</label>
                        <div class="col-md-2">
                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpStart">
                                <input type="text" name="dpStart" size="16" class="form-control" value="{{ isset($start)?$start:old('dpStart') }}">
                                    <span class="add-on birhtdateDatepicker ptl-3">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                  </span>
                            </div>
                        </div>
                        <label class="control-label col-md-1">{{ trans('multiple.m_end_date') }}</label>
                        <div class="col-md-2">
                            <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpEnd">
                                <input type="text" name="dpEnd" size="16" class="form-control" value="{{ isset($end)?$end:old('dpEnd') }}">
                                    <span class="add-on birhtdateDatepicker ptl-3">
                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                  </span>
                            </div>
                        </div>
                    </div><br/>
                    <div class="row">
                        <div class="col-md-offset-4 col-md-5">
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                            <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <br/><br/>
            @var $rate = 4000
            <section id="unseen">
                <div class="row">
                    <div class="col-md-offset-9 col-md-1">
                        <div class="radio">
                            <label><input type="radio" name="cur" value="1" checked>Riel</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="cur" value="2">Dollar</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">{{ trans('report.rpt_exchange_rate') }}</label>
                        <input type="text" class="form-control" name="exchange_rate" id="ex" value="{{  $rate }}" onkeyup="update_ex(this,0)">
                        <label>in million riels</label>
                    </div>
                </div><br/>
                <div id="printArea">
                    @include('api.report_header',['co_phone'=>!empty($co_id->co_user) ? $co_id->co_user->phone: ''])
                    <h4 class="sch_title">
                        {{ trans('sidebar.sb_income_statement') }}
                        @if(isset($start) && isset($end))
                            {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
                        @else
                            {{ isset($start)?'Report on'.date("d-M-Y", strtotime($start)):'' }}
                            {{ isset($end)?'Report on '.date("d-M-Y", strtotime($end)):'' }}
                        @endif
                    </h4>
                    <table class="table table-bordered table-striped table-condensed cash_deposit" style="width:100%;">
                        <thead>
                            <tr>
                                <th rowspan="2"></th>
                                <th colspan="3" class="text-center">Current Month</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th class="text-center">Riels</th>
                                <th class="text-center">Other Currencies into Riels</th>
                                <th class="text-center">Total in Riels</th>
                                <th class="text-center">Year to date in Riels</th>
                            </tr>
                        </thead>
                        <tbody id="income_statement">
                            <?php
                                $lbl = [
                                    'int_income'=>'1. Interest Income',
                                        'int_income_1'=>'1.1. Loans and Advances to customers',
                                        'int_income_2'=>'1.2. Accounts with Banks and Fin. Institutions',
                                        'int_income_3'=>'1.3. Balance with NBC',
                                        'int_income_4'=>'1.4. Other',

                                    'int_expense'=>'2. Interest Expenses and Similar Charges',
                                        'int_expense_1'=>'2.1. Customer deposits',
                                        'int_expense_2'=>'2.2. Amounts owing to Banks and other Fin.',
                                        'int_expense_3'=>'2.3. Others',

                                    'net_int'=>'3. Net Interest Income (3=1-2)',
                                    'non_int'=>'4. Non Interest Income (Net)',
                                        'non_int_1'=>'4.1. Non-interest Income',
                                        'non_int_2'=>'4.2. Non-interest Expenses',

                                    'for_ex'=>'5. Foreign Exchange Gain (Loss)',
                                    'other_int'=>'6. Other Income',
                                    'opt_income'=>'7. Operating Income (7=3+4+5+6)',
                                    'pro_expense'=>'8. Program Expenses',

                                    'g_expense'=>'9. General and Administrative Expenses',
                                        'g_expense_1'=>'9.1. Personnel Expenses',
                                        'g_expense_2'=>'9.2. Office Expenses',
                                        'g_expense_3'=>'9.3. Occupancy Expenses',
                                        'g_expense_4'=>'9.4. Travel Expenses',
                                        'g_expense_5'=>'9.5. Village bank/association expenses',
                                        'g_expense_6'=>'9.6. Rebates/commission to borrowing groups',
                                        'g_expense_7'=>'9.7. Depreciation and Amortization',
                                        'g_expense_8'=>'9.8. Other general and administrative',

                                    'taxes'=>'10. Taxes',
                                    'other_ch'=>'11. Other Charges',

                                    'loan_int'=>'12. Loan and Interest Loss Provision (Net)',
                                        'loan_int_1'=>'12.1. General Loan Loss Provisions',
                                        'loan_int_2'=>'12.2. Specific Loan Loss Provisions',
                                        'loan_int_3'=>'12.3. Interest Loss Provisions',

                                    'pro_op'=>'13. Profit from Operations (13=7-8-9-10-11-12)',
                                    'gr_income'=>'14. Grant Income',
                                    'ext_item'=>'15. Extraordinary Items',
                                    'pro_tax'=>'16. Profit before Taxes (16=13-14-15)',
                                    'tax_pro'=>'17. Tax on Profit',
                                    'net_p'=>'18. Net Profit for the Period (18=16-17)',
                                    'd_payment'=>'19. Dividend Payments',
                                    'net_avail'=>'20. Net Profit available after dividends (20=18-19)',
                                ];
                                $total_result = [
                                    'int_income' =>[
                                        'int_income_1'=>[0,0,0],
                                        'int_income_2'=>[0,0,0],
                                        'int_income_3'=>[0,0,0],
                                        'int_income_4'=>[0,0,0]
                                    ],
                                    'int_expense' =>[
                                        'int_expense_1'=>[0,0,0],
                                        'int_expense_2'=>[0,0,0],
                                        'int_expense_3'=>[0,0,0]
                                    ],
                                    'net_int' =>[0,0,0],
                                    'non_int' =>[
                                        'non_int_1'=>[0,0,0],
                                        'non_int_2'=>[0,0,0]
                                    ],
                                    'for_ex'=>[0,0,0],
                                    'other_int' =>[0,0,0],
                                    'opt_income' =>[0,0,0],
                                    'pro_expense' =>[0,0,0],
                                    'g_expense' =>[
                                        'g_expense_1'=>[0,0,0],
                                        'g_expense_2'=>[0,0,0],
                                        'g_expense_3'=>[0,0,0],
                                        'g_expense_4'=>[0,0,0],
                                        'g_expense_5'=>[0,0,0],
                                        'g_expense_6'=>[0,0,0],
                                        'g_expense_7'=>[0,0,0],
                                        'g_expense_8'=>[0,0,0]
                                    ],
                                    'taxes' =>[0,0,0],
                                    'other_ch' =>[0,0,0],
                                    'loan_int' =>[
                                        'loan_int_1'=>[0,0,0],
                                        'loan_int_2'=>[0,0,0],
                                        'loan_int_3'=>[0,0,0]
                                    ],
                                    'pro_op' =>[0,0,0],
                                    'gr_income' =>[0,0,0],
                                    'ext_item' =>[0,0,0],
                                    'pro_tax' =>[0,0,0],
                                    'tax_pro' =>[0,0,0],
                                    'net_p' =>[0,0,0],
                                    'd_payment' =>[0,0,0],
                                    'net_avail' =>[0,0,0]
                                ];

                                $total_result['int_income']['int_income_1'][1] = $trans->t_interest;
                                $total_result['non_int']['non_int_1'][1] = $feecharge->t_charge_amount;
                                $total_result['g_expense']['g_expense_8'][1] = $costfee->t_cost_amount;
                                $total_result['loan_int']['loan_int_1'][1] = $writeoff->t_amount;
                                $total_result['int_income']['int_income_4'][1] = ($payoff->t_payoff_fee + $payment->t_penalty_amount);

                                $total_result['int_income']['int_income_1'][2] = $trans_ytd->t_interest;
                                $total_result['non_int']['non_int_1'][2] = $feecharge_ytd->t_charge_amount;
                                $total_result['g_expense']['g_expense_8'][2] = $costfee_ytd->t_cost_amount;
                                $total_result['loan_int']['loan_int_1'][2] = $writeoff_ytd->t_amount;
                                $total_result['int_income']['int_income_4'][2] = ($payoff_ytd->t_payoff_fee + $payment_ytd->t_penalty_amount);

                                $total_result['net_int'][1] = ($total_result['int_income']['int_income_1'][1]
                                                              + $total_result['int_income']['int_income_2'][1]
                                                              + $total_result['int_income']['int_income_3'][1]
                                                              + $total_result['int_income']['int_income_4'][1]
                                                              )
                                                              - ($total_result['int_expense']['int_expense_1'][1]
                                                                + $total_result['int_expense']['int_expense_2'][1]
                                                                + $total_result['int_expense']['int_expense_3'][1]
                                                                );
                                $total_result['net_int'][2] = ($total_result['int_income']['int_income_1'][2]
                                                              + $total_result['int_income']['int_income_2'][2]
                                                              + $total_result['int_income']['int_income_3'][2]
                                                              + $total_result['int_income']['int_income_4'][2]
                                                              )
                                                              - ($total_result['int_expense']['int_expense_1'][2]
                                                                + $total_result['int_expense']['int_expense_2'][2]
                                                                + $total_result['int_expense']['int_expense_3'][2]
                                                                );

                                $total_result['opt_income'][1] = $total_result['net_int'][1]
                                                                + ($total_result['non_int']['non_int_1'][1]
                                                                    +$total_result['non_int']['non_int_2'][1])
                                                                + $total_result['for_ex'][1]
                                                                + $total_result['other_int'][1];
                                $total_result['opt_income'][2] = $total_result['net_int'][2]
                                                                + ($total_result['non_int']['non_int_1'][2]
                                                                    + $total_result['non_int']['non_int_2'][2])
                                                                + $total_result['for_ex'][2]
                                                                + $total_result['other_int'][2];
                                $total_result['pro_op'][1] = $total_result['opt_income'][1]
                                                            - $total_result['pro_expense'][1]
                                                            - ($total_result['g_expense']['g_expense_1'][1]
                                                                + $total_result['g_expense']['g_expense_2'][1]
                                                                + $total_result['g_expense']['g_expense_3'][1]
                                                                + $total_result['g_expense']['g_expense_4'][1]
                                                                + $total_result['g_expense']['g_expense_5'][1]
                                                                + $total_result['g_expense']['g_expense_6'][1]
                                                                + $total_result['g_expense']['g_expense_7'][1]
                                                                + $total_result['g_expense']['g_expense_8'][1]
                                                                )
                                                            - $total_result['taxes'][1]
                                                            - $total_result['other_ch'][1]
                                                            - ($total_result['loan_int']['loan_int_1'][1]
                                                                + $total_result['loan_int']['loan_int_2'][1]
                                                                + $total_result['loan_int']['loan_int_3'][1]
                                                                );
                                $total_result['pro_op'][2] = $total_result['opt_income'][2]
                                                            - $total_result['pro_expense'][2]
                                                            - ($total_result['g_expense']['g_expense_1'][2]
                                                                + $total_result['g_expense']['g_expense_2'][2]
                                                                + $total_result['g_expense']['g_expense_3'][2]
                                                                + $total_result['g_expense']['g_expense_4'][2]
                                                                + $total_result['g_expense']['g_expense_5'][2]
                                                                + $total_result['g_expense']['g_expense_6'][2]
                                                                + $total_result['g_expense']['g_expense_7'][2]
                                                                + $total_result['g_expense']['g_expense_8'][2]
                                                                )
                                                            - $total_result['taxes'][2]
                                                            - $total_result['other_ch'][2]
                                                            - ($total_result['loan_int']['loan_int_1'][2]
                                                                + $total_result['loan_int']['loan_int_2'][2]
                                                                + $total_result['loan_int']['loan_int_3'][2]
                                                                );
                                $total_result['pro_tax'][1] = $total_result['pro_op'][1]
                                                            - $total_result['gr_income'][1]
                                                            - $total_result['ext_item'][1];
                                $total_result['pro_tax'][2] = $total_result['pro_op'][2]
                                                            - $total_result['gr_income'][2]
                                                            - $total_result['ext_item'][2];
                                $total_result['net_p'][1] = $total_result['pro_tax'][1] - $total_result['tax_pro'][1];
                                $total_result['net_p'][2] = $total_result['pro_tax'][2] - $total_result['tax_pro'][2];

                                $total_result['net_avail'][1] = $total_result['net_p'][1] - $total_result['d_payment'][1];
                                $total_result['net_avail'][2] = $total_result['net_p'][2] - $total_result['d_payment'][2];
                            ?>
                            @foreach($total_result as $key => $items)
                               @if(is_array(end($items)))
                                <?php
                                $child_tr = '';
                                $riel = 0.00;
                                $dolar = 0.00;
                                $ytd = 0.00;
                                    foreach ($items as $k => $item) {
                                        $riel += $item[0];
                                        $dolar += $item[1];
                                        $ytd += $item[2];

                                        $child_tr .= '<tr class="'.$k.'">';
                                            $child_tr .= '<td class="pl-1">'.$lbl[$k].'</td>';
                                            $child_tr .= '<td>'.$item[0] .'</td>';
                                            $child_tr .= '<td>'.number_format((($item[1] * $rate)/1000000),2,'.',',').'</td>';
                                            $child_tr .= '<td>'. number_format((($item[0] + ($item[1] * $rate))/1000000),2,'.',',') .'</td>';
                                            $child_tr .= '<td>'.number_format((($item[2] * $rate)/1000000),2,'.',',') .' </td>';
                                        $child_tr .= '</tr>';
                                    }
                                ?>
                                <tr class="t-bold {{ $key }}">
                                    <td>{{  $lbl[$key] }}</td>
                                    <td>{{ $riel }}</td>
                                    <td>{{ number_format((($dolar * $rate)/1000000),2,'.',',') }}</td>
                                    <td>{{ number_format(((($dolar * $rate) + $riel)/1000000),2,'.',',') }}</td>
                                    <td>{{ number_format((($ytd * $rate)/1000000),2,'.',',') }}</td>
                                </tr>
                               <?php echo $child_tr; ?>
                               @else
                                <tr class="t-bold {{ $key }}">
                                    <td>{{ $lbl[$key] }}</td>
                                    <td>{{ $items[0] }}</td>
                                    <td>{{ number_format((($items[1] * $rate)/1000000),2,'.',',') }}</td>
                                    <td>{{ number_format((($items[0]  + ($items[1] * $rate))/1000000),2,'.',',') }}</td>
                                    <td>{{ number_format((($items[2] * $rate)/1000000),2,'.',',') }}</td>
                                </tr>
                               @endif
                            @endforeach
                       </tbody>
                    </table>
                </div>
            </section>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure) ? false : false)}}"></script>
    <script type="text/javascript">
    var total_result = <?php echo json_encode($total_result); ?>;
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
        $('input[type="radio"][name="cur"]').change(function(){
            var $obj = $('input[type="text"][name="exchange_rate"]').get(0);
            var ind = parseInt($(this).val());
            update_ex($obj, ind);

            $.each($('table th'),function(){
                var lbl = $(this).html();
                if(ind == 1){
                    $(this).html(lbl.replace('Dollars','Riels'));
                }else{
                    $(this).html(lbl.replace('Riels','Dollars'));
                }
            });
        });
        function update_ex(input, cur){
            if(cur == 0){
                cur = parseInt($('input[type="radio"][name="cur"]:checked').val());
            }

            var rate = isNaN(parseFloat(input.value))?0:parseFloat(input.value);
            var temp = cur==1?(rate / 1000000):1;
            var temp_riel = cur==1?1:rate==0?0:(1/rate);

            $.each(total_result,function(key,item){
              if($.type(item) == 'array'){
                 var tr  = $("#income_statement").find('.'+key);
                 var td = $(">td",tr);
                 td[1].innerHTML = (item[0] * temp_riel).formatMoney(2);
                 td[2].innerHTML  = (item[1] * temp).formatMoney(2);
                 td[3].innerHTML  = ((item[1] * temp) + (item[0] * temp_riel)).formatMoney(2);
                 td[4].innerHTML  = (item[2] * temp).formatMoney(2);
              }else{
                var itm = new Array(0.0,0.0,0.0,0.0);
                $.each(item, function(k,it){
                     var tr  = $("#income_statement").find('.'+k);
                     var td = $(">td",tr);
                     
                     var td1 = it[1] * temp;
                     var td2 = td1 + (it[0] * temp_riel);
                     var td3 = it[2] * temp;
                     itm[0] += it[0] * temp_riel;
                     itm[1] += td1;
                     itm[2] += td2;
                     itm[3] += td3;

                     td[1].innerHTML = it[0] * temp_riel;
                     td[2].innerHTML  = td1.formatMoney(2);
                     td[3].innerHTML  = td2.formatMoney(2);
                     td[4].innerHTML  = td3.formatMoney(2);

                });
                var tr  = $("#income_statement").find('.'+key);
                var td = $(">td",tr);
                td[1].innerHTML = itm[0].formatMoney(2);
                td[2].innerHTML  = itm[1].formatMoney(2);
                td[3].innerHTML  = itm[2].formatMoney(2);
                td[4].innerHTML  = itm[3].formatMoney(2);
              }
           });
        }
    Number.prototype.formatMoney = function(c, d=".", t=","){
        var n = this, 
            c = isNaN(c = Math.abs(c)) ? 2 : c, 
            d = d == undefined ? "." : d, 
            t = t == undefined ? "," : t, 
            s = n < 0 ? "-" : "", 
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
            j = (j = i.length) > 3 ? j % 3 : 0;
           return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
         };
    </script>
@endsection