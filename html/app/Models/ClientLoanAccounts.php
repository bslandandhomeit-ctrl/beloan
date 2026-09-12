<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientLoanAccounts extends Model {
    protected $table = 'client_loan_accounts';
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = [];
    public function client(){
        return $this->belongsTo('App\Models\Client','client_id');
    }

    public function loan()
    {
        return $this->hasOne('App\Models\Loan','loan_account_id', 'id')->orderBy('loans.id', 'DESC')->whereIn('loans.status',[3,8,5,6,9,10]);
    }
    public function journal_detail_coa()
    {
        return $this->hasMany('App\Models\JournalDetail','coa_id', 'coa_id');
    }
    public function journal_detail_air()
    {
        return $this->hasMany('App\Models\JournalDetail','coa_id', 'air_id');
    }
    public function journal_detail_int_inc()
    {
        return $this->hasMany('App\Models\JournalDetail','coa_id', 'int_inc_id');
    }
    public function journal_detail_sus()
    {
        return $this->hasMany('App\Models\JournalDetail','coa_id', 'sus_id');
    }
    public function schedule()
    {
    	return $this->hasMany('App\Models\RepaymentSchedule','loan_id');
    }
    public function currencies()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'currency');
    }
	public function get_branch()
    {
        return $this->hasOne('App\Models\CompanyBranch', 'branch_code', 'branch');
    }
    public function projects(){
        return $this->hasOne('App\Models\Project','id','project_id');
    }
    public function drawdown_acc(){
        return $this->hasOne('App\Models\DrawdownAccounts','client_loan_id','id');
    }
}
