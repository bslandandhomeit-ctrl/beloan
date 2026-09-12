<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model {

	protected $table = 'promotions';
	protected $hidden = [
		'created_at','updated_at'
	];

	public function UnitType(){
		return $this->belongsTo('App\Models\UnitType','unit_type_id');
	}
	public function UnitTypePromotion(){
		return $this->hasMany('App\Models\UnitTypePromotion');
	}
}
