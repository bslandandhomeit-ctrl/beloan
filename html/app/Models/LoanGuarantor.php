<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanGuarantor extends Model {
	protected $table = 'loan_guarantors';
  	protected $hidden = ['created_at', 'updated_at'];
  	public function Clients(){
  		return $this->belongsTo('App\Models\Client','customer_id');
  	}
}
