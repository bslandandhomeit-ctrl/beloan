<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePerson extends Model {

	protected $table = 'saleperson';
  	protected $hidden = ['created_at', 'updated_at'];

  	public function SaleTeam(){
  		return $this->belongsTo('App\Models\User','sale_team_id','id');
  	}
  	public function Nationalities(){
  		return $this->belongsTo('App\Models\Country\CountryDescription','national_id','id');
  	}
	public function parent(){
        return $this->hasOne('App\Models\SalePerson','id','parent_id');
    }

}
