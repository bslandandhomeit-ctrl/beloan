<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanTypeConfig extends Model {
	protected $table = 'loan_type_config';
  	protected $hidden = ['deleted_at','created_at', 'updated_at'];

  	public function UnitType(){
  		return $this->belongsTo('App\Models\UnitType','unit_type_id');
  	}
  	public function product_type(){
        return $this->belongsTo('App\Models\Products\Product_type', 'loan_type');
    }
}
