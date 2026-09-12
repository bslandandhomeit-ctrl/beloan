<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRestructure extends Model{
	protected $table="loan_restructure";
    protected $hidden = ['created_at', 'updated_at','restructure_data'];

	public function loan(){
		return $this->belongsTo('App\Models\Loan','loan_id');
	}
}