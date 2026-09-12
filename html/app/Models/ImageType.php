<?php namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ImageType extends Model {
	protected $table = 'image_types';
  	protected $hidden = ['deleted_at','created_at', 'updated_at'];
}
