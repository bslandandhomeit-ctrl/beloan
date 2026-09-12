<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {
  protected $table = 'projects';
  protected $hidden = ['created_at', 'updated_at'];

  public function projectBank(){
  	return $this->hasMany('App\Models\DealerBanks','dealer_id');
  }
  public function loans(){
  	return $this->hasMany('App\Models\LoanDealer','dealer_id');
  }
  public function product(){
  	return $this->hasMany('App\Models\Product','dealer_id');
  }
  public function bank()
  {
      return $this->belongsToMany('App\Models\Bank','dealer_banks','dealer_id','bank_id');
  }
  public function Representative(){
    return $this->belongsTo('App\Models\Representative','sale_representative_id');
  }
  public function Banks(){
    return $this->belongsTo('App\Models\Bank','bank_account');
  }
  public function Company(){
    return $this->belongsTo('App\Models\CompanyBranch','company_id');
  }

}
