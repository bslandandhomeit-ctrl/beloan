<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 6/4/2015
 * Time: 11:22 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CompanyBranch extends Model {
    protected $table = 'company_branch';
    protected $hidden = ['created_at', 'updated_at'];
    public function staff(){
        return $this->belongsTo('App\Models\Staff','staff_id');
    }
    public function history(){
        return $this->belongsTo('App\Models\JobHistory','history_id');
    }
    public function role(){
        return $this->belongsTo('App\Models\Role','role_id');
    }
    public function getBranch() {
        return self::all();
    }

    public function projects(){
        return $this->hasOne('App\Models\Project','company_id');
    }
} 