<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model {
	protected $table = 'invoice_items';
  	protected $hidden = ['deleted_at','created_at', 'updated_at'];
}
