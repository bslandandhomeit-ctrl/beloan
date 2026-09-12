<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class JournalRequiry extends Model{
protected $table = 'journal_requiry';
    protected $hidden = ['created_at', 'updated_at'];

	public function transaction(){
		return $this->belongsTo('App\Models\TransactionsRequiry','tran_id');
	}
	public function user(){
		return $this->belongsTo('App\Models\User','user_id');
	}
	public function detail() {

		return $this->hasMany('App\Models\JournalDetail', 'journal_id');
	}

	final function AccountCustomer() {

		return $this->hasOne('App\Models\AccountCustomer', 'id','ref_name_id');
	}
	final function vendor () {

		return $this->hasOne('App\Models\Vendor', 'id','ref_name_id');
	}

	public function audit()
    {
        return $this->hasMany('App\Models\Audit','tbl_id')->where('tbl', 'journal_requiry');
    }
}