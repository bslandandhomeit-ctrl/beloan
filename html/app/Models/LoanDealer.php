<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanDealer extends Model {
  protected $table = 'loan_dealers';
  protected $hidden = ['created_at', 'updated_at'];
  
  public function dealer(){
    return $this->belongsTo('App\Models\Dealer','dealer_id');
  }
  public function loan(){
    return $this->belongsTo('App\Models\Loan','loan_id');
  }
  public function bank(){
    return $this->belongsTo('App\Models\Bank','bank_id');
  }
}
