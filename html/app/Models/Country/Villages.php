<?php namespace App\Models\Country;
/**
 * Created by PhpStorm.
 * User: heng soheak
 * Date: 9/2/2016
 * Time: 3:31 PM
 */

use Illuminate\Database\Eloquent\Model;


class Villages extends Model {

    protected $table = 'village';
    //protected $fillable = ['role_id','name', 'email','username'];
    protected $hidden = ['created_at','updated_at'];

}
