<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPayOff extends Model{
	protected $table = 'loan_payoff';
        protected $hidden = ['created_at', 'updated_at'];

	public function loan(){
		return $this->belongsTo('App\Models\Loan','loan_id');
	}
}