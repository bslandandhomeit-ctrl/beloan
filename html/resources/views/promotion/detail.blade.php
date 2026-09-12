<?php
    $unit_type_promotion = $promotion->UnitTypePromotion;
?>
<div class="panel-body">
    <div class="row text-center">
        <div class="col-lg-12">
            <table width="100%" class="table-condensed table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('dealer.short_code') }}</th>
                        <th>{{ trans('promotion.name') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($unit_type_promotion) > 0)
                        @foreach($unit_type_promotion as $key => $val)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $val->UnitType->short_code }}</td>
                                <td>{{ $val->UnitType->name }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">Data not found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>