<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferClient extends Model {
	protected $table = 'transfer_clients';
  	protected $hidden = ['created_at', 'updated_at'];
  	public function Clients(){
  		return $this->belongsTo('App\Models\Client','client_id');
  	}

  	public function newClients(){
  		return $this->belongsTo('App\Models\Client','new_client_id');
  	}
}
