<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 7/08/2015
 * Time: 2:54 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Locale  extends Model {
    protected $table = 'locales';
    public $timestamps = false;

    public function trans()
    {
        return $this->hasMany('App\Models\LocaleTitle','locale_id');
    }
}
