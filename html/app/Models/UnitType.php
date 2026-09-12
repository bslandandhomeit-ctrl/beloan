<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class UnitType extends Model
{
    protected $table = 'unit_types';

    protected $guarded = [];

    protected $hidden = ['deleted_at','created_at', 'updated_at'];

    public function Projects()
    {
		return $this->belongsTo('App\Models\Project','project_id');
    }

    public function saleCommissionSettings()
    {
		return $this->hasMany(\App\Models\SaleCommissionSetting::class, 'unit_type_id');
    }

    public function activeSaleCommissionSetting()
    {
		return $this->hasOne(\App\Models\SaleCommissionSetting::class, 'unit_type_id')
	    	->where('is_active', 1);
    }
    
    public function units()
    {
		return $this->hasMany(\App\Models\Unit::class, 'unit_type_id');
    }

    public function saveCommission($commissionValue, $commissionType)
    {
		if(!in_array($commissionType, ['%', '$'])) {
			throw new InvalidArgumentException('Commission type has to be percentage or fixed');
		}

		$this->saleCommissionSettings()->update([
			'is_active' => 0,	
		]);
		
		$this->saleCommissionSettings()->create([
			'commission_value' => $commissionValue,
			'commission_type' => $commissionType,
			'is_active' => 1,
			'approval' => 'pending',
		]);

		$this->units()->update([
			'commission_value' => $commissionValue,
			'commission_type' => $commissionType,
		]);
    }
}
