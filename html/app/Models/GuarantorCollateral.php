<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 6/9/2015
 * Time: 4:27 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GuarantorCollateral extends Model{
    protected $table = 'guarantor_collaterals';
    protected $hidden = ['created_at', 'updated_at'];
} 