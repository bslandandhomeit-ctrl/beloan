<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model {
  // Do NOT prefix with "tb_" here - Laravel's DB config already adds that
  // prefix to every model automatically (see config/database.php). Setting
  // 'tb_payment_types' here makes it look for 'tb_tb_payment_types', which
  // does not exist. The real table is tb_payment_types; this must stay
  // 'payment_types'.
  protected $table = 'payment_types';
  protected $hidden = ['created_at', 'updated_at'];

  public static function activeList()
  {
      return static::where('is_active', 1)->orderBy('id')->lists('name', 'id');
  }
}
