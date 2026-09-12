<?php
/**
 * Created by PhpStorm.
 * User: heng soheak
 * Date: 9/2/2016
 * Time: 3:31 PM
 */
 namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class AccountCustomer extends Model {

    protected $table = 'acc_customer';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at','updated_at'];

    final function JournalRequiry (){
        return $this->hasMany('App\Models\JournalRequiry', 'ref_name_id', 'id');
    }
    final function JournalRequiryWhere(){
        return $this->hasMany('App\Models\JournalRequiry', 'ref_name_id', 'id')->where('ref_name_type', 2);
    }
}

