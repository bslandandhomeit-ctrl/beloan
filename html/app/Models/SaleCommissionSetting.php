<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleCommissionSetting extends Model 
{
    protected $table = 'sale_commission_setting';

    protected $guarded = [];

    public function unitType()
    {
        return $this->belongsTo(\App\Models\UnitType::class, 'unit_type_id');
    }

    public static function saveCommission($attributes = [])
    {
        $commissionType = @$attributes['commission_type'];
        $commissionValue = @$attributes['commission_value'];

        if(is_null($commissionType) && is_null($commissionValue))
            return null;

        if(!in_array($commissionType, ['%', '$']))
            return null;

        return static::create([
            'commission_type' => $commissionType,
            'commission_value' => $commissionValue,
            'start_date' => null,
            'end_date' => null,
        ]);
    }
}
