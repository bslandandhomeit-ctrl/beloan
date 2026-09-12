<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleRepresentative extends Model {
  protected $table = 'sale_representatives';
  protected $hidden = ['created_at', 'updated_at'];
}
