<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CurrencyExchangeRecord
 *
 * @author theary
 */
class CurrencyExchangeRecord extends Model{
    //put your code here
    protected $table = 'currency_exchange_record';
    protected $hidden = ['created_at', 'updated_at'];
    
    public function currency(){
        return $this->belongsTo('App\Models\Currency','from_currency');
    }
    public function currencies(){
        return $this->belongsTo('App\Models\Currency','to_currency');
    }
    public function symbol(){
        return $this->belongsTo('App\Models\Currency','from_currency');
    }
    public function symbols(){
        return $this->belongsTo('App\Models\Currency','to_currency');
    }
}
