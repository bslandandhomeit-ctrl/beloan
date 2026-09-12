<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model {
	protected $table = 'sale_items';
  	protected $hidden = ['deleted_at','created_at', 'updated_at'];
}
