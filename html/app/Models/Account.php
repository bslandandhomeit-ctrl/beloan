<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model {
  protected $table = 'chart_of_accounts';
  public $timestamps = false;
  protected $primaryKey = 'account_code';
  protected $fillable = array(
    'account_code',
    'account_name',
    'type',
    'category',
    'account_code',
    );
  protected $hidden = ['created_at', 'updated_at'];

  public function detail(){
      return $this->hasMany('App\Models\JournalDetail','account_code');
    }
}
