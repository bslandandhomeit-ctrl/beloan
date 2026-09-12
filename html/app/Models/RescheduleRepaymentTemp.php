<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 1/09/2015
 * Time: 3:34 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RescheduleRepaymentTemp extends Model{
    protected $table = 'reschedule_repayment_temp';
    public $timestamps = false;
    protected $guarded = [];
    public function payment(){
        return $this->hasMany('App\Models\LoanPayments','loan_id', 'loan_id')
        			->where('payment_month', $this->no);
    }

}
