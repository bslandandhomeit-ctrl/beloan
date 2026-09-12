<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['role_id','name', 'email','username'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token','created_at','updated_at'];

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }
    public function loan(){
        return $this->hasMany('App\Models\Loan','user_id');
    }
	public function get_branch()
    {
        return $this->hasOne('App\Models\CompanyBranch', 'id', 'branch_id');
    }
    public function requiry(){
        return $this->hasMany('App\Models\JournalRequiry','user_id');
    }
    public function hasRole($role)
    {
        if(is_array($role)){
            foreach($this->role()->get() as $r){
                if(in_array($r->role,$role))
                    return true;
            }
        }else{
            foreach($this->role()->get() as $r){
                if($r->role == $role)
                    return true;
            }
        }
        return false;
    }
    public function getRole()
    {
        return $this->role()->first()->role;
    }

    public function permission()
    {
        return $this->belongsToMany('App\Models\Permission','user_permission','user_id','permission_id');
    }

    public function checkPermission($action)
    {
        $has = false;
        $permissions = null;
        if(session('ROLE_PERMISSION') != null){
            $permissions = session('ROLE_PERMISSION');
        }else{
            $permissions = $this->permission()->get(['code','action_name','action_type']);
            if(count($permissions) > 0){
                session(['ROLE_PERMISSION'=>$permissions]);
            }
        }
        if(!empty($permissions) && count($permissions) > 0){
            foreach($permissions as $p){
                $action_name = explode(',',$p->action_name);
                if($p->code == 'ALL_FUNCTIONS'){
                    $has = true;
                    break;
                }elseif(in_array(strtoupper($action),$action_name)){
                    $has = true;
                    break;
                }
            }
        }
        return $has;
    }
}
