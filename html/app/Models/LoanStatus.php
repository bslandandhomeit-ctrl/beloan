<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanStatus extends Model {

	protected $table="loan_status";
    // protected $hidden = ['created_at', 'updated_at'];

    public function user(){
    	return $this->hasOne('App\Models\User','id','created_by');
    }

}
