<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 5/26/2015
 * Time: 8:25 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model{
    protected $table = 'roles';
    protected $hidden = ['created_at', 'updated_at'];
    public function staff(){
        return $this->belongsTo('App\Models\Staff','staff_id');
    }
    final function users()
    {
        return $this->hasMany('App\Models\User','role_id');
    }
} 