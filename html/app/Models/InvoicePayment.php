<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model {

	protected $table = 'invoice_payment';
	protected $hidden = [
		'created_at','updated_at'
	];
}
