<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model {

	protected $table = 'payment_term';
	protected $hidden = [
		'created_at','updated_at'
	];
}
