<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSavingAccount extends Model {
    protected $table = 'client_saving_accounts';
    protected $hidden = ['created_at', 'updated_at'];

    public function client(){
        return $this->belongsTo('App\Models\Client','client_id');
    }
}
