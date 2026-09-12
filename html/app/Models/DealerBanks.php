<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealerBanks extends Model{
  protected $table = 'dealer_banks';
  protected $hidden = ['created_at', 'updated_at'];

  public function bank(){
  	return $this->belongsTo('App\Models\Bank','bank_id','id');
  }

  public function dealer(){
  	return $this->belongsTo('App\Models\Dealer','dealer_id','id');
  }
}
