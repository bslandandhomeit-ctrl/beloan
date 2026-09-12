@extends('layouts.app')

@section('css')

<link rel="stylesheet" type="text/css" href="{{ asset('css/loan-style.css',isset($secure) ? false : false)}}"/>
@endsection

@section('content')
<section class="panel ox-scroll">
    <header class="panel-heading">
        {{ trans('sidebar.sb_todo_payment_for_today') }}
    </header>
    <div class="panel-body">
        <div class="position-center" style="width:90%;">
            <form role="form" class="cmxform form-horizontal" method="get"  id="search_frm">
                <input type="hidden" name="offset" />
                <div class="row">
                    <div class="page">
                        <div class="custom-pagi">
                            <span class="pagi_label">Number of Rows:</span>
                            <input type="text" class="form-control" name="set_offset" value="<?php echo $offset ?>" />
                            <a href="#" class="btn btn-danger">Go</a>
                        </div> 
                    </div>
                </div>
            </form>
        </div>
        <section id="unseen" class="ox-scroll">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="text-align: center">{{ trans('multiple.m_no') }}</th>
                        <th style="text-align: center">{{ trans('report.rpt_contract_id') }}</th>
                        <th style="text-align: center">{{ trans('report.rpt_contract_date') }}</th>
                        <th style="text-align: center">{{ trans('customer.cus_customer_name') }}</th>
                        <th style="text-align: center">{{ trans('multiple.m_phone',['num'=>'']) }}</th>
                        <th style="text-align: center">{{ trans('multiple.m_address') }}</th>
                        <th style="text-align: center">{{ trans('loan.l_schedule_date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 0;?>
                    @if(!empty($loans) && count($loans) > 0)
                    @foreach($loans as $l)
                    <?php $no++; ?>
                    <tr>
                        <td align="center">{{ $no }}</td>
                        <td align="center"><a href="{{route('loan_detail', [$l->id])}}">{{ $l->contract_id }}</a></td>
                        <td align="center">{{ date('d-M-Y',strtotime($l->start_date)) }}</td>
                        <td>{{ $l->client_name }}</td>
                        <td align="center">{{ $l->phone1 }} {{ !empty($l->phone2) ? ' / '.$l->phone2 : '' }}</td>
                        <td>{{ $l->address }}</td>
                        <td align="center">{{ add_month($l->last_schedule_date,1)->format('d-M-Y')}}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr><td colspan=7>{{ trans('multiple.m_no_result') }}</td></tr>
                    @endif
                </tbody>
            </table>
        </section>
        <div class="page" style="margin-left: 1140px;">
            <?PHP
            echo $loans->appends([
                'offset' => Input::get('offset')
            ])->render();
            ?>
        </div>
    </div>
</section>
@endsection

@section('js')
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