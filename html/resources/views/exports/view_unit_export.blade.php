<table class="table table-bordered table-striped table-condensed table-hover dealerTable" id="dealers_list">
    <thead class="th-center">
    <tr>
        <th style="vertical-align:middle; text-align: center;">{{ trans('representative.id') }}</th>
        <th style="vertical-align:middle; text-align: center;">Project</th>
        <th style="vertical-align:middle; text-align: center;">{{ trans('unit.unit_type') }}</th>
        <th style="vertical-align:middle; text-align: center;">{{ trans('unit.zone') }}</th>
        <th style="vertical-align:middle; text-align: center;">{{ trans('unit.unit_code') }}</th>
        <th style="vertical-align:middle; text-align: center;">{{ trans('unit.price') }}</th>
        <th style="vertical-align:middle; text-align: center;">Discount</th>
        <th style="vertical-align:middle; text-align: center;">Net Selling Price</th>
                    <th style="vertical-align:middle; text-align: center;">Contract Price</th>
        <th style="vertical-align:middle; text-align: center;">{{ trans('unit.street') }}</th>
        <th style="vertical-align:middle; text-align: center;">{{ trans('unit.street_corner') }}</th>
        <th style="vertical-align:middle; text-align: center;">{{ trans('unit.street_size') }}</th>
        <th style="vertical-align:middle; text-align: center;">{{ trans('unit.floor') }}</th>
        <th style="vertical-align:middle; text-align: center;">{{ trans('unit.unit_status') }}</th>
        <th style="vertical-align:middle; text-align: center;">{{ trans('multiple.status') }}</th>
    </tr>
    </thead>
    <tbody>
        @forelse($lists as $unit)
        <tr>
            <td class="isVerticalalign" align="center">{{ $unit->id}}</td>
            <td class="isVerticalalign" align="center">{{ isset($unit->project)?$unit->project:'N/A' }}</td>
            <td class="isVerticalalign" align="center">{{ isset($unit->unit_type_name)?$unit->unit_type_name:'N/A' }}</td>
            <td class="isVerticalalign" align="center">{{ $unit->zone_id }}</td>
            <td class="isVerticalalign" align="center">{{ $unit->code}}</td>
            <td class="isVerticalalign" align="center">{{ $unit->price}}</td>
            <td class="isVerticalalign" align="center">{{ $unit->discount_amount?$unit->discount_amount:''}}</td>
            <td class="isVerticalalign" align="center">{{ $unit->discount_amount?number_format(($unit->price - $unit->discount_amount),2,'.',''):$unit->price}}</td>
            <td class="isVerticalalign" align="center">{{ $unit->unit_sale_price?$unit->unit_sale_price:''}}</td>
            <td class="isVerticalalign" align="center">{{ $unit->street}}</td>
            <td class="isVerticalalign" align="center">{{ $unit->street_corner}}</td>
            <td class="isVerticalalign" align="center">{{ $unit->street_size }}</td>
            <td class="isVerticalalign" align="center">{{ $unit->floor }}</td>
            <td class="isVerticalalign" align="center"><span class="unit_status" style="color: {{ $unit_status_color[$unit->status] }}">{{ ucfirst($unit->status) }}</span></td>
            <td class="isVerticalalign" align="center">
                {{ $unit->active? "Active":"Inactive" }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>