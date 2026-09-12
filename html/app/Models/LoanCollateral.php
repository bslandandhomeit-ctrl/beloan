<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 6/9/2015
 * Time: 4:27 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LoanCollateral extends Model{
    protected $table = 'collateral';
    protected $hidden = ['created_at', 'updated_at'];

    public function loan(){
    	return $this->belongsTo('App\Models\Loan','loan_id');
    }
} 