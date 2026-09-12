<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class slips extends Model {

    protected $table = 'slips';
    protected $hidden = ['created_at', 'updated_at'];

    public function client(){
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function coa() {

        return $this->belongsTo('App\Models\CoaCategory','coa_id');
    }

    public function currency(){

        return $this->belongsTo('App\Models\Currency','currency_id');
    }
    public function journal_rquiry() {

        return $this->belongsTo('App\Models\JournalRequiry', 'jr_id');
    }

    final protected function draw_dow_act() {

        return $this->belongsTo('App\Models\DrawdownAccounts','draw_acc_id');
    }

    public function company_branch(){
        return $this->belongsTo('App\Models\CompanyBranch','branch','branch_code');
    }
}
