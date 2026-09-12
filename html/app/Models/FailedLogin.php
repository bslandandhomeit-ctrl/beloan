<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 7/13/2015
 * Time: 10:19 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FailedLogin extends Model{
    protected $table = 'failed_logins';
    public $timestamps = false;
} 