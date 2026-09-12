@extends('layouts.app')

@section('css')
<link href="{{ asset('css/client.css',isset($secure) ? false : false) }}" rel="stylesheet">
<link href="{{ asset('theme/css/table-responsive.css',isset($secure) ? false : false) }}" rel="stylesheet" />
<link href="{{ asset('css/loan-style.css',isset($secure) ? false : false) }}" rel="stylesheet" />
@endsection
    <?php
        $currency = config('static_data.currency_symbol');
        $status = config('static_data.client_loan_account_status');
        $drawdown_status = config('static_data.drawdown_status');
    ?>

@section('content')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                <span>{{ trans('sidebar.sb_drawdown_account_detail') }}</span>
                @if($dad->status==0)<a class="btn btn-default pull-right" href="{{ route('loan_audit', [$dad->id]) }}"><i class="fa fa-key"></i> {{ trans('multiple.audit') }}</a>@endif
            </header>
            <div class="panel-body">

                <table class="table table-bordered table-striped table-condensed">
                	<tr>
                        <th style="width: 25%;">{{ trans('multiple.account_name') }}</th>
                        <td><?php echo $dad->account_name; ?></td>
                    </tr>

                    <tr>
                        <th>{{ trans('multiple.account_no') }}</th>
                        <td><?php echo $dad->account_no; ?></td>
                    </tr>
                    <tr>
                        <th>{{ trans('multiple.branch') }}</th>
                        <td><?php echo $branch_name; ?></td>
                    </tr>

                    <tr>
                        <th>{{ trans('multiple.balance') }}</th>
                        <!-- <td>{{$currency[$dad->currency].number_format($balance_drawdown_acc, 2)}}</td> -->
                        <td>{{$currency[$dad->currency].number_format($dad->balance, 2)}}</td>
                    </tr>

                    <tr>
                        <th>COA ID</th>
                        <td><?php echo $dad->coa_id; ?></td>
                    </tr>

                    <tr>
                        <th>COA Name</th>
                        <td><?php echo $coa->name; ?></td>
                    </tr>

                    <tr>
                        <th>{{ trans('multiple.status') }}</th>
                        <td>{{$drawdown_status[$dad->status]}}</td>
                    </tr>

                </table>
                <h5><strong>{{ trans('multiple.transaction') }}</strong></h5>
                <table  class="table table-bordered table-striped table-condensed table-hover clientTable">
                    <thead>
                    <th style="text-align: center;">{{ trans('multiple.id') }}</th>
                    <th style="text-align: center;">{{ trans('multiple.date') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_cash_in') }}</th>
                    <th style="text-align: center;">{{ trans('report.rpt_cash_out') }}</th>
                    <th style="text-align: center;">{{ trans('multiple.balance') }}</th>
		    <th style="text-align: center;">{{ trans('multiple.m_description') }}</th>
                    </thead>
                    <tbody>
                        <?php $balance = $t_cash_in = $t_cash_out = 0;
                        ?>
                        @forelse($trans as $trs)
                            @foreach($trs as $tran)
                            <?php
                                $i++;
                                $balance += $tran->credit - $tran->debit;
                                $t_cash_in += $tran->credit;
                                $t_cash_out += $tran->debit;
                            ?>
                            <tr>
                                <td style="text-align:center;">{{$i}}</td>
                                <td style="text-align:center;">{{Date("Y-m-d", strtotime($tran->entry_date))}}</td>
                                <td style="text-align:right;">{{number_format($tran->credit, 2)}}</td>
                                <td style="text-align:right;">{{number_format($tran->debit, 2)}}</td>
                                <td style="text-align:right;">{{number_format($balance, 2)}}</td>
                                <td style="text-align:right;">{{($tran->description != "")?$tran->description : $tran->j_description}}</td>
                            </tr>
                            @endforeach
                        @empty
                        <tr><td colspan=9>{{ trans('multiple.m_no_result') }}</td></tr>
                        @endforelse
                    </tbody>
                      <td colspan="2" style="text-align: right;font-weight: bold;">{{ trans('loan.total') }}</td>
                      <td style="text-align: right;font-weight: bold;">{{number_format($t_cash_in, 2)}}</td>
                      <td style="text-align: right;font-weight: bold;">{{number_format($t_cash_out, 2)}}</td>
                </table>

                <h5><strong>{{ trans('multiple.audit_title') }}</strong></h5>
            <table class="table table-bordered table-condensed">
                <tr>
                    <th>{{ trans('multiple.action') }}</th>
                    <th>{{ trans('multiple.by') }}</th>
                    <th>{{ trans('multiple.date') }}</th>
                    <th>{{ trans('multiple.status') }}</th>
                </tr>
                @foreach($audit as $au)
                    <tr>
                        <td>{{ trans('multiple.audit_created') }}</td>
                        <td>{{$au->audit1->name}}</td>
                        <td>{{$au->created_at}}</td>
                        <td>{{ trans('multiple.unauthorized') }}</td>
                    </tr>

                     @if($au->audit2)
                    <tr>
                        <td>{{ trans('multiple.audit_updated') }}</td>
                        <td>{{$au->audit2->name}}</td>
                        <td>{{$au->updated_at}}</td>
                        <td>{{ trans('multiple.authorized') }}</td>
                    </tr>
                    @endif
                @endforeach
            </table>

            </div>




        </section>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('theme/js/bootstrap-inputmask/bootstrap-inputmask.min.js', isset($secure) ? false : false) }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
    //pagination
        $('.custom-pagi a').on('click', function () {
            val = $(this).parent().find('input[name="set_offset"]').val();
            $('input[name="offset"]').val(val);
            $('#search_frm').submit();
            return false;
        });
    });
</script>
@endsection
