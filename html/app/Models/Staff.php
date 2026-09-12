<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model {
  protected $table = 'staffs';
  protected $hidden = ['created_at', 'updated_at'];
  public function history(){
    return $this->hasMany('App\Models\JobHistory','staff_id');
  }
  public function branch(){
    return $this->belongsTo('App\Models\CompanyBranch','branch_id');
  }
  public function role(){
    return $this->belongsTo('App\Models\Role','role_id');
  }
}
