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

class Nationality extends Model {

    protected $table = 'nbc_nationality';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at','updated_at'];

    final function JournalRequiry() {
        return $this->hasMany('App\Models\JournalRequiry', 'ref_name_id', 'id');
    }
    final protected function client(){
        return $this->hasOne('App\Models\Client','fk_client_id');
    }
}

