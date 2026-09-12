@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/loan-style.css',isset($secure)?false:false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/client.css',isset($secure)?false:false) }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/js/bootstrap-datepicker/css/datepicker.css',isset($secure)?false:false)}}" />
    <style>
        table tr td:not(:first-child){
            text-align:right;
        }
    </style>
@endsection
@section('content')
    <section class="panel">
        <header class="panel-heading">
            {{ trans('sidebar.sb_depreciation_summary') }}
            @if(isset($start) && isset($end))
                {{ trans('multiple.m_from') }} {{ date("d-M-Y", strtotime($start)) }} {{ trans('multiple.m_to') }} {{ date("d-M-Y", strtotime($end)) }}
            @else
                {{ isset($start)?'Report on'.date("d-M-Y", strtotime($start)):'' }}
                {{ isset($end)?'Report on '.date("d-M-Y", strtotime($end)):'' }}
            @endif
        </header>
        <div class="panel-body">
            <div class="position-center text-center">
                <form role="form" class="cmxform form-inline" method="get" action="{{ route('depreciation_summary') }}">
                    <div class="form-group">
                        <div data-date-viewmode="years" data-initialize="datepicker" data-date-format="yyyy/mm/dd" data-date="{{date('Y-m-d')}}" class="input-append date dpStart">
                            <input type="text" name="dpStart" size="16" class="form-control" value="{{ isset($dpStart)?$dpStart:old('dpStart') }}">
                            <span class="birhtdateDatepicker ptl-3">
                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">&nbsp;&nbsp;&nbsp;
                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> {{ trans('multiple.m_search') }}</button>
                        <button class="btn btn-warning" id="printer"><i class="fa fa-print"></i> {{ trans('multiple.m_print') }}</button>
                    </div>
                </form>
            </div>

            <br/><br/>

            <section id="unseen">

                <div id="printArea">
                    @include('api.report_header_nbc')
                    <h4 class="sch_title" id="p-header"></h4>
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>{{ trans('asset.purchased_date') }}</th>
                                <th>{{ trans('asset.asset_name') }}</th>
                                <th>{{ trans('asset.account_no') }}</th>
                                <th>{{ trans('asset.original_cost') }}</th>
                                <th>{{ trans('asset.rate') }}</th>
                                <th>{{ trans('asset.last_accum_date') }}</th>
                                <th>{{ trans('asset.prev_depre') }}</th>
                                <th>{{ trans('asset.cur_depre') }}</th>
                                <th>{{ trans('asset.total_depre') }}</th>
                                <th class="text-center">{{ trans('asset.net_book') }}</th>
                                <th class="text-center">{{ trans('asset.tag_number') }}</th>
                                <th>{{ trans('asset.remark') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            <!--*****************************-->
                            <tr><td colspan="20">{{ trans('asset.land') }}</td></tr>
                            <?php
                                $t_existing_asset = 0;
                                $t_new_asset_adding = 0;
                                $t_dispose = 0;
                                $t_total_cost = 0;
                                $t_dpr = 0;
                                $t_dprn = 0;
                                $t_writen_back = 0;
                                $t_total_dpr = 0;
                                $t_prio_net_book = 0;
                                $t_net_book_value = 0;
                            ?>
                            @foreach($LND as $list)
                                <?php
                                    $dpr = 0;
                                    foreach($list->depre_record as $dp){
                                        $dpr += $dp->depre_amount;
                                    }
                                    $t_dpr += $dpr;
                                    $rate = 0;
                                ?>
                                <tr>
                                    <td>{{$list->purchased_date}}</td>
                                    <td>{{$list->asset_name}}</td>
                                    <td>{{$asset_locations[$list->location]}}</td>
                                    <td>{{$list->tag_num}}</td>
                                    <td>{{$list->remark}}</td>
                                    <td>{{$list->supplier}}</td>
                                    <td>{{$list->invoice_num}}</td>
                                    <td>
                                    <?php
                                        $existing_asset = $list->depre_record[0]->created_at ? $list->original_cost : '';
                                        echo number_format($existing_asset);
                                        $t_existing_asset += $existing_asset;
                                    ?>
                                    </td>
                                    <td>
                                    <?php
                                        $new_asset_adding = !$list->depre_record[0]->created_at ? $list->original_cost : '';
                                        echo number_format($new_asset_adding);
                                        $t_new_asset_adding += $new_asset_adding;
                                    ?>
                                    </td>
                                    <td><?php $dispose = $list->asset_record[0]->amount; echo number_format($dispose); $t_dispose += $t_dispose;?></td>
                                    <td><?php $total_cost = $existing_asset + $new_asset_adding + $dispose; echo number_format($total_cost); $t_total_cost += $total_cost?></td>
                                    <td>
                                        <?php
                                            $start_date = $list->depre_record[0]->created_at ? $list->depre_record[0]->created_at : $list->purchased_date;
                                            $date1=date_create($start_date);
                                            $date2=date_create($date);
                                            $diff=date_diff($date1,$date2)->days;
                                            echo $diff.' days';
                                        ?>
                                    </td>
                                    <td>{{$rate}}%</td>
                                    <td>{{$dpr}}</td>
                                    <td><?php $dprn = ($total_cost * $rate * $diff)/365; echo number_format($dprn); $t_dprn += $dprn;?></td>
                                    <td><?php $writen_back = 0; echo $writen_back; $t_writen_back += $writen_back?></td>
                                    <td><?php $total_dpr = $dpr + $dprn + $writen_back; echo number_format($total_dpr); $t_total_dpr += $total_dpr;?></td>
                                    <td><?php $prio_net_book = $existing_asset - $dpr; echo number_format($prio_net_book); $t_prio_net_book += $prio_net_book?></td>
                                    <td><?php $net_book_value = $total_cost + $total_dpr; echo number_format($net_book_value); $t_net_book_value += $net_book_value;?></td>
                                </tr>
                            @endforeach

                            <tr class="acc1">
                                <td colspan="7" class="text-right">{{ trans('multile.total') }}</td>
                                <td>{{number_format($t_existing_asset)}}</td>
                                <td>{{number_format($t_new_asset_adding)}}</td>
                                <td>{{number_format($t_dispose)}}</td>
                                <td>{{number_format($t_total_cost)}}</td>
                                <td></td>
                                <td></td>
                                <td>{{number_format($t_dpr)}}</td>
                                <td>{{number_format($t_dprn)}}</td>
                                <td>{{number_format($t_writen_back)}}</td>
                                <td>{{number_format($t_total_dpr)}}</td>
                                <td>{{number_format($t_prio_net_book)}}</td>
                                <td>{{number_format($t_net_book_value)}}</td>
                            </tr>


                            <!--*****************************-->
                            <tr><td colspan="20">{{ trans('asset.building') }}</td></tr>
                            <?php
                                $t_existing_asset = 0;
                                $t_new_asset_adding = 0;
                                $t_dispose = 0;
                                $t_total_cost = 0;
                                $t_dpr = 0;
                                $t_dprn = 0;
                                $t_writen_back = 0;
                                $t_total_dpr = 0;
                                $t_prio_net_book = 0;
                                $t_net_book_value = 0;
                            ?>
                            @foreach($BLD as $list)
                                <?php
                                    $dpr = 0;
                                    foreach($list->depre_record as $dp){
                                        $dpr += $dp->depre_amount;
                                    }
                                    $t_dpr += $dpr;
                                    $rate = 5;
                                ?>
                                <tr>
                                    <td>{{$list->purchased_date}}</td>
                                    <td>{{$list->asset_name}}</td>
                                    <td>{{$asset_locations[$list->location]}}</td>
                                    <td>{{$list->tag_num}}</td>
                                    <td>{{$list->remark}}</td>
                                    <td>{{$list->supplier}}</td>
                                    <td>{{$list->invoice_num}}</td>
                                    <td>
                                    <?php
                                        $existing_asset = $list->depre_record[0]->created_at ? $list->original_cost : '';
                                        echo number_format($existing_asset);
                                        $t_existing_asset += $existing_asset;
                                    ?>
                                    </td>
                                    <td>
                                    <?php
                                        $new_asset_adding = !$list->depre_record[0]->created_at ? $list->original_cost : '';
                                        echo number_format($new_asset_adding);
                                        $t_new_asset_adding += $new_asset_adding;
                                    ?>
                                    </td>
                                    <td><?php $dispose = $list->asset_record[0]->amount; echo number_format($dispose); $t_dispose += $t_dispose;?></td>
                                    <td><?php $total_cost = $existing_asset + $new_asset_adding + $dispose; echo number_format($total_cost); $t_total_cost += $total_cost?></td>
                                    <td>
                                        <?php
                                            $start_date = $list->depre_record[0]->created_at ? $list->depre_record[0]->created_at : $list->purchased_date;
                                            $date1=date_create($start_date);
                                            $date2=date_create($date);
                                            $diff=date_diff($date1,$date2)->days;
                                            echo $diff.' days';
                                        ?>
                                    </td>
                                    <td>{{$rate}}%</td>
                                    <td>{{$dpr}}</td>
                                    <td><?php $dprn = ($total_cost * $rate * $diff)/365; echo number_format($dprn); $t_dprn += $dprn;?></td>
                                    <td><?php $writen_back = 0; echo $writen_back; $t_writen_back += $writen_back?></td>
                                    <td><?php $total_dpr = $dpr + $dprn + $writen_back; echo number_format($total_dpr); $t_total_dpr += $total_dpr;?></td>
                                    <td><?php $prio_net_book = $existing_asset - $dpr; echo number_format($prio_net_book); $t_prio_net_book += $prio_net_book?></td>
                                    <td><?php $net_book_value = $total_cost + $total_dpr; echo number_format($net_book_value); $t_net_book_value += $net_book_value;?></td>
                                </tr>
                            @endforeach

                            <tr class="acc1">
                                <td colspan="7" class="text-right">{{ trans('multile.total') }}</td>
                                <td>{{number_format($t_existing_asset)}}</td>
                                <td>{{number_format($t_new_asset_adding)}}</td>
                                <td>{{number_format($t_dispose)}}</td>
                                <td>{{number_format($t_total_cost)}}</td>
                                <td></td>
                                <td></td>
                                <td>{{number_format($t_dpr)}}</td>
                                <td>{{number_format($t_dprn)}}</td>
                                <td>{{number_format($t_writen_back)}}</td>
                                <td>{{number_format($t_total_dpr)}}</td>
                                <td>{{number_format($t_prio_net_book)}}</td>
                                <td>{{number_format($t_net_book_value)}}</td>
                            </tr>
                       </tbody>
                    </table>
                    @include('api.report_footer_nbc')
                </div>
            </section>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('theme/js/bootstrap-datepicker/js/bootstrap-datepicker.js',isset($secure)?false:false)}}"></script>
    <script type="text/javascript" src="{{ asset('js/print.js',isset($secure)?false:false)}}"></script>
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
    </script>
@endsection
