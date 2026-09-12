<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanWriteOff extends Model{

	protected $table="loan_write_off";
    protected $hidden = ['created_at', 'updated_at'];

	public function loan(){
		return $this->belongsTo('App\Models\Loan','loan_id');
	}
}