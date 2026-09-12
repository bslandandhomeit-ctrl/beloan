<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCBCEmployer extends Model {
    protected $table = 'client_cbc_employer';
    protected $hidden = ['created_at', 'updated_at'];

    public function currency(){
        return $this->hasOne('App\Models\Currency', 'id', 'currency_id');
    }
}
