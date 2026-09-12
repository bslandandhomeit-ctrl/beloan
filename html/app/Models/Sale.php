<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model {
	protected $table = 'sale_order';
  	protected $hidden = ['deleted_at','created_at', 'updated_at'];
    protected $guarded = [];

	public function sale_persons(){
        return $this->hasOne('App\Models\SalePerson','id','sale_person');
    }
	public function sale_person_parent_lavel1(){
        return $this->hasOne('App\Models\SalePerson','id','sale_person_parent_l1');
    }
	public function sale_person_parent_lavel2(){
        return $this->hasOne('App\Models\SalePerson','id','sale_person_parent_l2');
    }
}
