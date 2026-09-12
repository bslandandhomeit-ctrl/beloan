<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDrawdownAccounts extends Model {

    protected $table = 'company_drawdown_account';
    protected $hidden = ['created_at', 'updated_at'];

    public function client(){
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function Client_cbc_info(){
        return $this->belongsTo('App\Models\Cbc\Client_cbc_info', 'client_id');
    }

    public function coa() {
        return $this->belongsTo('App\Models\CoaCategory','coa_id');
    }

    public function currency_tbl(){
        return $this->belongsTo('App\Models\Currency','currency');
    }
    public function company_branch(){
        return $this->belongsTo('App\Models\CompanyBranch','branch','branch_code');
    }
    public function journal_detail(){
        return $this->hasMany('App\Models\JournalDetail', 'coa_id', 'coa_id');
    }
    public function projects(){
        return $this->belongsTo('App\Models\Project','project_id');
    }
    public function unitType(){
        return $this->belongsTo('App\Models\UnitType','unit_type_id');
    }
    public function units(){
        return $this->belongsTo('App\Models\Unit','unit_id');
    }
    public function loans(){
        return $this->hasMany('App\Models\Loan','drawdown_acc','account_no');
    }
}
