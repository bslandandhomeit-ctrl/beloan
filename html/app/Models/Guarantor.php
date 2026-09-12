<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 6/9/2015
 * Time: 4:27 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model{
    protected $table = 'guarantors';
    protected $hidden = ['created_at', 'updated_at'];

    public function collateral()
    {
        return $this->hasMany('App\Models\GuarantorCollateral');
    }
} 