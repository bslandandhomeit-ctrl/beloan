<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model{
	protected $table = 'journal_detail';
    public $timestamps = false;

    public function journal(){
    	return $this->belongsTo('App\Models\JournalRequiry','journal_id');
    }
    public function account(){
    	return $this->belongsTo('App\Models\CoaCategory','coa_id');
    }

    public function draw_act()
    {
        return $this->belongsTo('App\Models\DrawdownAccounts', 'coa_id');
    }
    public function branch()
    {
        return $this->hasOne('App\Models\CompanyBranch', 'branch_code', 'branch_code');
    }

}