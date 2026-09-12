<?php 
namespace App\Models;
/**
 * Created by PhpStorm.
 * User: ChamroeunDeab
 * Date: 5/29/2015
 * Time: 4:24 PM
 */

use Illuminate\Database\Eloquent\Model;
use LoanCalculate;

class Loan extends Model {
    protected $table = 'loans';
    protected $hidden = ['created_at', 'updated_at'];
    protected $lastActionTmp = null;

    public function product(){
        return $this->belongsTo('App\Models\Product','product_id');
    }
    public function client(){
        return $this->belongsTo('App\Models\Client','client_id');
    }
    public function loanDealer(){

        return $this->hasOne('App\Models\LoanDealer','loan_id');
    }
    public function branch()
    {
        return $this->belongsTo('App\Models\CompanyBranch','company_branch_id');
    }
    public function payment(){
        return $this->hasMany('App\Models\LoanPayments','loan_id');
    }

    public function approval(){
        return $this->hasOne('App\Models\LoanApproval','loan_id');
    }
    public function close(){
        return $this->hasOne('App\Models\LoanClose','loan_id');
    }
    public function writeoff(){
        return $this->hasOne('App\Models\LoanWriteOff','loan_id');
    }
    public function payoff(){
        return $this->hasOne('App\Models\LoanPayOff','loan_id');
    }
    public function collateral(){
        return $this->hasMany('App\Models\LoanCollateral','loan_id');
    }
    public function loanapproval(){
        return $this->hasOne('App\Models\LoanApproval','loan_id');
    }
    public function feecharge(){
        return $this->hasMany('App\Models\FeeCharge','loan_id');
    }
    public function costfee()
    {
        return $this->hasMany('App\Models\LoanCostFee','loan_id');
    }
    public function transaction()
    {
        return $this->hasMany('App\Models\TransactionsRequiry', 'loan_id');
    }
    public function dealer(){
        return $this->belongsTo('App\Models\Dealer','dealer_id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function co_user(){
        return $this->belongsTo('App\Models\User','co');
    }
    public function schedule()
    {
        return $this->hasMany('App\Models\RepaymentSchedule','loan_id');
    }
    public function schedule_type($type = 'loan')
    {
        return $this->hasMany('App\Models\RepaymentSchedule','loan_id')->where('repayment_schedule.type','=', (string)$type);
    }
	public function getHolidayAttribute()
	{
        $date = add_month(date('Y-m-d', strtotime($this->start_date)), $this->loan_duration + 1)->format('Y-m-d');
		return Holiday::whereBetween('holiday_date',[$this->start_date,$date])->orderBy('holiday_date')->lists('holiday_date');
	}
    public function transaction_req(){
        return $this->hasMany('App\Models\TransactionsRequiry','loan_id');
    }
    public function client_loan_account()
    {
        return $this->belongsTo('App\Models\ClientLoanAccounts','loan_account_id');
    }

    public function transaction_reqLastBalance(){
    	return $this->hasMany('App\Models\TransactionsRequiry','loan_id');
    }

    public function accrued_journal_detail(){
    	return $this->hasMany('App\Models\JournalDetail', 'coa_id', 'air_id');
    }

    public function coa_journal_detail(){
        return $this->hasMany('App\Models\JournalDetail', 'coa_id', 'coa_id');
    }
    public function accrued_repayment_schedule(){
    	return $this->hasMany('App\Models\RepaymentSchedule', 'loan_id');
    }

    public function penalty_record(){
        return $this->hasMany('App\Models\PenaltyRecord', 'loan_id');
    }

    public function scheduleOne()
    {
        return $this->hasMany('App\Models\RepaymentSchedule','loan_id');
    }
    public function product_type(){
        return $this->belongsTo('App\Models\Products\Product_type', 'loan_type');
    }
    public function transaction_req_last(){
    	return $this->hasMany('App\Models\TransactionsRequiry','loan_id');
    }

    public function unittypes(){
        return $this->belongsTo('App\Models\UnitType','unit_type_id');
    }

    public function projects(){
        return $this->belongsTo('App\Models\Project','project_id');
    }
    public function units(){
        return $this->belongsTo('App\Models\Unit','unit_id');
    }

    public function RepaymentSchedules(){
        return $this->hasMany('App\Models\RepaymentSchedule','loan_id');
    }
    function lastSchedule(){
        return $this->hasOne('App\Models\RepaymentSchedule','loan_id')->latest();
    }

    public function loan_drawdow_acc(){
        return $this->hasOne('App\Models\DrawdownAccounts', 'account_no', 'drawdown_acc');
    }

    public function co_borrowers(){
        return $this->hasOne('App\Models\CoBorrower','loan_id');
    }

    public function sale_persons(){
        return $this->hasOne('App\Models\SalePerson','id','sale_person');
    }

    public function sale_commission_persons(){
        return $this->hasOne('App\Models\SalePerson','id','saleperson_id');
    }

    

    public function PaymentOptions(){
        return $this->hasOne('App\Models\PaymentOption','id','payment_option');
    }

    public function getOutstandingBalance(){
        $principal_original_sum = 0.00;
        $principal_sum = 0;
        $payment_schedule = LoanCalculate::loan_schedule($this->schedule, $this->start_date)[0];
        foreach ($payment_schedule as $p) {
            $principal_original_sum += $p[3];
        }
        $repayment = $this->payment;
        foreach($repayment as $pay){
            $principal_sum += $pay->paid_principal;
        }
        return $principal_original_sum - $principal_sum;
    }

    public function getLastAction(){
        if (!$this->lastActionTmp) {
            $action = CollectionAction::getListActionByLoanId($this->id, 1);
            if ($action) {
                $this->lastActionTmp = $action[0];
            }
        }
        return $this->lastActionTmp;
    }

    public function disburse_user(){
        return $this->belongsTo('App\Models\User','disburse_byuserid');
    }

    public function SaleRepresentative(){
        return $this->hasOne('App\Models\SalePerson','id','sale_person');
    }
	public function sale_person_parent_lavel1(){
        return $this->hasOne('App\Models\SalePerson','id','sale_person_parent_l1');
    }
	public function sale_person_parent_lavel2(){
        return $this->hasOne('App\Models\SalePerson','id','sale_person_parent_l2');
    }
    public function commission_withdrawal_transaction(){
        return $this->hasMany('App\Models\CommissionWithdrawalTransaction','loan_id');
    }

}
