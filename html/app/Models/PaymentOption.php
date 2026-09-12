<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentOption extends Model {

	protected $table = 'payment_options';
	protected $hidden = [
		'created_at','updated_at'
	];

	public function UnitType(){
		return $this->belongsTo('App\Models\UnitType','unit_type_id');
	}
}
