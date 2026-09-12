<?php namespace App\Models\Country;
/**
 * Created by PhpStorm.
 * User: heng soheak
 * Date: 9/2/2016
 * Time: 3:31 PM
 */

use Illuminate\Database\Eloquent\Model;


class Communes extends Model {

    protected $table = 'commune';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at','updated_at'];

    final function villages() {

      return $this->hasMany('App\Models\Country\Villages', 'comm_id', 'id');

    }
    final function Description() {

      return $this->hasMany('App\Models\Country\CountryDescription', 'country_id', 'id');

    }
}
