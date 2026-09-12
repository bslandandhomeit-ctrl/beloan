<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoBorrower extends Model {
	protected $table = 'co_borrowers';
  	protected $hidden = ['created_at', 'updated_at'];
  	public function Clients(){
  		return $this->belongsTo('App\Models\Client','customer_id');
  	}
}
