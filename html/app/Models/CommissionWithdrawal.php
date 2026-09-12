<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 6/4/2015
 * Time: 11:22 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CommissionWithdrawal extends Model {
    protected $table = 'commission_withdrawal';
    protected $hidden = ['created_at', 'updated_at'];

    public function commissionWithdrawalTransaction(){
        return $this->hasMany('App\Models\CommissionWithdrawalTransaction','commission_withdrawal_id');
    }
} 