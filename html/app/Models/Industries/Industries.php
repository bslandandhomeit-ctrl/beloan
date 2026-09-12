<?php namespace App\Models\Industries;

/**
 * Created by PhpStorm.
 * User: hengsoheak
 * Date: 12/30/2016
 * Time: 10:53 AM
 */
use App\Models\MyModels;

class Industries extends MyModels
{
    protected $table = 'industries';
    protected $fillable = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    //protected $visible = ['id','status','salutation'];
    //protected $attributes = ['id','status','salutation'];
    //protected $timestapes = false;

}