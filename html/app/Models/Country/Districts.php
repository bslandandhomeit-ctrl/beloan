<?php namespace App\Models\Country;
/**
 * Created by PhpStorm.
 * User: heng soheak
 * Date: 9/2/2016
 * Time: 3:31 PM
 */

use Illuminate\Database\Eloquent\Model;


class Districts extends Model {

    protected $table = 'districts';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at','updated_at'];

    final function communes() {

      return $this->HasMany('App\Models\Country\Communes', 'distr_id', 'id');

    }
}
