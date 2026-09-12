<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitStatus extends Model {
	protected $table = 'unit_status';
  	protected $hidden = ['deleted_at','created_at', 'updated_at'];

  	public function unit(){
  		return $this->belongsTo('App\Models\Unit','unit_status_id');
  	}
}
