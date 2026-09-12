<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitTypePromotion extends Model {

	// protected $table = 'unit_type_promotions';
	protected $fillable = ['promotion_id', 'unit_type_id'];
	protected $hidden = [
		'created_at','updated_at'
	];

	public function UnitType(){
		return $this->belongsTo('App\Models\UnitType','unit_type_id');
	}

	public function Promotion(){
		return $this->belongsTo('App\Models\Promotion','promotion_id');
	}

}
