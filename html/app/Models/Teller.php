<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\DB;

class Teller extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'till_account';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * company_branch, till_account, users
     * @var array
     */

    public function getTillerAccount($user_id, $branch_id)
    {
//        $data['chief'] = DB::table('users')->leftJoin('till_transaction', 'users.id', '=', 'till_transaction.operate_by')->where('users.id','=', $user_id)->get();
        $account = self::select('till_account.*', 'till_account.id as till_id', 'till_account.status as status','company_branch.branch_name as branch_name','currency.name as currency_name', 'company_branch.id as branch_id','users.username','currency.symbol as symbol')
            ->join('users', 'users.id', '=', 'assign_user_id')
            ->join('company_branch', 'users.branch_id', '=', 'company_branch.id')
            ->join('currency', 'till_account.currency_id', '=', 'currency.id')
            ->where('till_account.branch_id', '=', $branch_id);
            //->where('created_by', '=', $user_id)
        $account2 = $account->get();
        $account1 = $account->where('created_by', '=', $user_id)->get();
        if(!empty($account1) && count($account1) > 0){
            $data['account'] = $account1;
        }else {
            $data['account'] = $account2;
        }
        if (count($data) > 0) {

            return $data;
        } else {
            return false;
        }
    }

    public function teller_account($userId) {

        $tilldata = self::select('account_name','id')->where('assign_user_id','=', $userId)->get();
        foreach($tilldata as $item) {
            return [
                'account_name'=>$item->account_name,
                'till_user_id'=>$item->till_user_id,
                'branch_id'=>$item->brach_id,
                'operate_by'=>$item->operate_by,
                'id'=>$item->id
            ];
        }
    }

    public function teller_balance($userId){

        $bal = self::select('balance')->where('assign_user_id','=', $userId)->get();
        foreach($bal as $item){
            return $item->balance;
        }
    }


    public function user(){
        return $this->belongsTo('App\Models\User','assign_user_id');
    }


}