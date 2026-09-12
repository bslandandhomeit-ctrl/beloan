<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model {

  	protected $table = 'audit';

	public function audit1()
	{
		return $this->hasOne('App\Models\User', 'id', 'user_id');
	}

	public function audit2()
	{
		return $this->hasOne('App\Models\User', 'id', 'audit_id');
	}
}
