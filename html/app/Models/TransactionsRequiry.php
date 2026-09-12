<?php namespace App\Models;
/**
 * Created by PhpStorm.
 * User: ChamroeunDeab
 * Date: 6/30/2015
 * Time: 4:24 PM
 */

use Illuminate\Database\Eloquent\Model;

class TransactionsRequiry extends Model {
    protected $table = 'transactions_requiry';
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = false;

    public function repayment(){
        return $this->belongsTo('App\Models\LoanPayments','repayment_id');
    }

    public function charge()
    {
        return $this->belongsTo('App\Models\FeeCharge','fee_charge_id');
    }

    public function cost()
    {
        return $this->belongsTo('App\Models\LoanCostFee','cost_fee_id');
    }

    public function loan()
    {
        return $this->belongsTo('App\Models\Loan','loan_id');
    }
    public function journal(){
        return $this->hasMany('App\Models\JournalRequiry','tran_id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function company_branch(){
        return $this->belongsTo('App\Models\CompanyBranch','branch_id');
    }

    public function audit()
    {
        return $this->hasMany('App\Models\Audit','tbl_id')->where('tbl', 'transactions_requiry');
    }
}
