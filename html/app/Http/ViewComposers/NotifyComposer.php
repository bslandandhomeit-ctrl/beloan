<?php
namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 6/22/2015
 * Time: 2:52 PM
 */

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotifyComposer {


    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $last_login = [];
        if(Auth::check()){
            //$user = Auth::user();
            //$role = $user->getRole();
            $last_login = User::join('roles','roles.id','=','users.role_id')
                //->where('last_login','>=',date('Y-m-d 00:00:00'))
                //->whereNotIn('role',['super_admin','admin_user'])
                ->paginate(15,['users.id','name','photo','last_login','login_ip']);
            /*
            if($role == 'super_admin'){
                $last_login = User::join('roles','roles.id','=','users.role_id')
                                  ->where('last_login','>=',date('Y-m-d 00:00:00'))
                                  ->where('role','!=','super_admin')
                                  ->paginate(15,['users.id','name','photo','last_login','login_ip']);
            }elseif($role == 'admin_user'){
                $last_login = User::join('roles','roles.id','=','users.role_id')
                                  ->where('last_login','>=',date('Y-m-d 00:00:00'))
                                  ->whereNotIn('role',['super_admin','admin_user'])
                                  ->paginate(15,['users.id','name','photo','last_login','login_ip']);
            }
            */

        }
        $view->with('last_login',$last_login);
    }
} 