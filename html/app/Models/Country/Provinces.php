<?php namespace App\Models\Country;
/**
 * Created by PhpStorm.
 * User: heng soheak
 * Date: 9/2/2016
 * Time: 3:31 PM
 */

use Illuminate\Database\Eloquent\Model;


class Provinces extends Model {

    protected $table = 'province';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at','updated_at'];

    final function Districts(){

        return $this->HasMany('App\Models\Country\Districts', 'prov_id', 'id');

    }
}
