@if(!empty($charge) && count($charge) > 0)
<section id="flip-scroll">
    @if(!empty($charge['fee_charge']) && count($charge['fee_charge']) > 0)
        <div>
            <h4 class="sch_title">{{ trans('loan.l_fee_charge') }}</h4>
        </div>
        <table class="table table-bordered table-striped table-condensed cf charge">
            <tr>
                <th style="text-align: center;">{{ trans('multiple.m_no') }}</th>
                <th style="text-align: center;">{{ trans('report.rpt_date') }}</th>
                <th style="text-align: center;">{{ trans('loan.l_cost_type') }}</th>
                <th style="text-align: center;">{{ trans('report.rpt_amount') }}</th>
                <th style="text-align: center;">{{ trans('loan.l_waived_amount') }}</th>
                <th style="text-align: center;">{{ trans('multiple.m_note') }}</th>
            </tr>
            <?php $cn = 0;
                $charge_type = config('static_data.fee_charge');
            ?>
            @foreach($charge['fee_charge'] as $c)
                <?php $cn++ ?>
                 <tr>
                    <td align="center">{{ $cn }}</td>
                    <td align="center">{{ date('d-M-Y',strtotime($c->charge_date)) }}</td>
                    <td align="center">
                        @if(!empty($charge_type) && array_key_exists( $c->charge_type,$charge_type))
                            {{ $charge_type[$c->charge_type] }}
                        @endif
                    </td>
                    <td align="center">{{ $c->charge_amount }}</td>
                    <td align="center">{{ $c->waived_amount }}</td>
                    <td align="center">{{ !empty($c->note) ? $c->note : '-' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        No fee data
        <br/><br/>
    @endif

    @if(!empty($charge['cost_fee']) && count($charge['cost_fee']) > 0)
        <div>
            <h4>{{ trans('loan.l_charge_on_loan') }}</h4>
        </div>
        <table class="table table-bordered table-striped table-condensed cf charge">
            <tr>
                 <th style="text-align: center;">{{ trans('multiple.m_no') }}</th>
                 <th style="text-align: center;">{{ trans('report.rpt_date') }}</th>
                 <th style="text-align: center;">{{ trans('loan.l_cost_type') }}</th>
                 <th style="text-align: center;">{{ trans('report.rpt_amount') }}</th>
                 <th style="text-align: center;">{{ trans('multiple.m_note') }}</th>
            </tr>
            <?php $cn = 0;
                $cost_type = config('static_data.fee_cost_type');
            ?>
            @foreach($charge['cost_fee'] as $c)
                <?php $cn++ ?>
                 <tr>
                    <td aligh="center">{{ $cn }}</td>
                    <td aligh="center">{{ date('d-M-Y',strtotime($c->cost_date)) }}</td>
                    <td aligh="center">
                        @if(!empty($cost_type) && array_key_exists($c->cost_type,$cost_type))
                             {{ $cost_type[$c->cost_type] }}
                        @endif
                    </td>
                    <td aligh="center">{{ $c->cost_amount }}</td>
                    <td aligh="center">{{ !empty($c->note) ? $c->note : '-' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        No cost data
    @endif
</section>
@endif