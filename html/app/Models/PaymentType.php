<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model {
  protected $table = 'payment_types';
  protected $hidden = ['created_at', 'updated_at'];

  public static function activeList()
  {
      return static::where('is_active', 1)->orderBy('id')->lists('name', 'id');
  }
}
