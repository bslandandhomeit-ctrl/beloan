<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SetCurrency
 *
 * @author theary
 */
class Asset extends Model{
    //put your code here
    protected $table = 'fixed_asset';
    protected $hidden = ['created_at', 'updated_at'];

    public function depre_record()
    {
        return $this->hasMany('App\Models\AssetDepreRecord','fa_id')->orderBy('id', 'desc');
    }

    public function asset_record()
    {
        return $this->hasMany('App\Models\AssetRecord','fa_id')->orderBy('id', 'desc');
    }
    public function depre_journals()
    {
        return $this->hasMany('App\Models\JournalDetail','id', 'depre_gl_id');
    }

    public function depre_record_sum()
    {
        return $this->hasMany('App\Models\AssetDepreRecord','fa_id')->selectRaw('sum(depre_amount) as total_depre_amount');
    }

    public function gl_coa()
    {
        return $this->hasOne('App\Models\CoaCategory','id', 'main_gl_id');
    }
    public function depre_gl_coa()
    {
        return $this->hasOne('App\Models\CoaCategory','id', 'depre_gl_id');
    }
}
