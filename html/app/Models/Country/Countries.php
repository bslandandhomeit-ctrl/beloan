<?php namespace App\Models\Country;
/**
 * Created by PhpStorm.
 * User: heng soheak
 * Date: 9/2/2016
 * Time: 3:31 PM
 */

use Illuminate\Database\Eloquent\Model;


class Countries extends Model {

    protected $table = 'countries';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at','updated_at'];


    final function description() {

      return $this->hasMany('App\Models\Country\CountryDescription', 'country_id', 'id');

    }
    final function provinces() {

        return $this->hasMany('App\Models\Country\Provinces', 'count_id', 'id');
    }

}
