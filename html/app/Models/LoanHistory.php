<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 6/15/2015
 * Time: 2:30 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LoanHistory extends Model{
    protected $table = 'loan_history';
    protected $hidden = ['created_at', 'updated_at'];
    public function loan(){
        return $this->belongsTo('App\Models\Loan','loan_id');
    }
} 