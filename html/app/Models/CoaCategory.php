<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 2/11/2015
 * Time: 11:37 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CoaCategory extends Model
{
    protected $table = 'coa_categories';
    public $timestamps = false;

    public function children()
    {
        return $this->hasMany('App\Models\CoaCategory', 'parent_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo('App\Models\CoaCategory', 'parent_id');
    }

    final function detail () {

        return $this->hasMany('App\Models\JournalDetail', 'coa_id');
    }
    public function currency_i()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'currency');
    }

}

