<?php namespace App\Models\Country;
/**
 * Created by PhpStorm.
 * User: heng soheak
 * Date: 9/2/2016
 * Time: 3:31 PM
 */

use Illuminate\Database\Eloquent\Model;

class CountryDescription extends Model {

    protected $table = 'country_descriptions';

    //protected $fillable = ['role_id','name', 'email','username'];

    protected $hidden = ['created_at','updated_at'];


    final function Country() {

      return $this->belongTo('App\Models\Country', 'id', 'country_id');

    }
}
