<?php namespace App\Models;
/**
 * Created by PhpStorm.
 * User: ChamroeunDeab
 * Date: 5/29/2015
 * Time: 4:24 PM
 */

use Illuminate\Database\Eloquent\Model;

class LoanPayments extends Model {
    protected $table = 'loan_payments';

    public function loan(){
        return $this->belongsTo('App\Models\Loan','loan_id');
    }

    protected $hidden = ['created_at','updated_at'];    
}
