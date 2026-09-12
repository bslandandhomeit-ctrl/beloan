<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK Heng
 * Date: 16/03/31
 * Time: 10:29 AM
 * Notification Module
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Models\user;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{

    protected $table = 'notification';
    public $timestamps = true;
    private $_data = [];

    public function __construct()
    {
        parent::__construct();
    }

    public final function queryData()
    {
        return self::select("*")->get();
    }

    /**
     * To initial value of this method without assign column name but should be follow by index of array.
     * options.
     * @param
     * 'n_user_id'=>$data[0],@param
     * 'n_group_code_id'=>$data[1],@param
     * 'n_source_id'=>$data[2],@param
     * 'chief_till_account_id'=>$data[3],@param
     * 'n_activity_type'=>$data[4],@param
     * 'n_create_times'=>$data[5],@param
     * 'n_description'=>$data[6],@param
     * 'url'=>$data[7],@param
     * @return mixed
     */
    public function setNotification($data)
    {
        if (is_array($data)) {
            $this->attributes = $this->getArrayDataForAttributes($data);
            return self::save();
        } else {
            return false;
        }
    }

    public function getArrayDataForAttributes($data)
    {

        if (is_array($data)) {
            return [
                'n_user_id' => $data[0],
                'n_group_code_id' => ($data[1]) ? $data[1] : 0,
                'n_source_id' => ($data[2]) ? $data[2] : 0,
                'chief_till_account_id' => (int)($data[3]) ? $data[3] : 0,
                'teller_till_account_id' => (int)($data[4]) ? $data[4] : 0,
                'n_activity_type' => ($data[5]) ? $data[5] : 0,
                'n_create_times' => ($data[6]) ? $data[6] : 0,
                'n_description' => ($data[7]) ? $data[7] : '-',
                'n_amount' => ($data[8]) ? $data[8] : 0,
                'disburs_loan_id'=> ($data[9]) ? $data[9] : 0,
            ];
        }
        return false;
    }

    private function getGroupCode($userId)
    {
        return $this->_data = self::select('n_group_code_id')->where('n_user_id', '=', $userId)->get();
    }

    public function getNotification($user_id = null, $gId = null)
    {
        $this->_data = self::select('*', 'notification.id as not_id')
            ->join('till_account','teller_till_account_id','=','till_account.id')
            ->join('users','users.id','=','assign_user_id')
            ->where('n_user_id','=', $user_id)->get();
        if (count($this->_data)) {
            return $this->_data;
        }
    }

    public function NotificationData($userId, $id, $type)
    {
        $this->_data['teller'] = self::select('*', 'till_transaction.balance as trans_balance', 'notification.id as not_id', 'till_account.id as chief_id')
            ->join('till_transaction', 'n_source_id', '=', 'till_transaction.id')->where('n_activity_type','=',$type)
            ->where('n_source_id', '=', $id)
            ->join('till_account', 'till_transaction.till_account_id', '=', 'till_account.id')
            ->join('currency', 'till_transaction.tran_currency_id', '=', 'currency.id')
            ->join('users', 'users.id', '=', 'till_account.assign_user_id')
            ->get();
//
//        $this->_data['loan'] = Loan::select('*')
//            ->join('users','users.id','=','loans.user_id')
//            ->join('fee_charges','loan_id','=','loans.id')
//            ->where('loans.id', '=', $id)->get();

        $this->_data['chief'] = self::select('*', 'notification.id as not_id')
            ->Join('till_account', 'till_account.id', '=', 'chief_till_account_id')
            ->where('assign_user_id','=', $userId)
            ->where('n_activity_type','=', $type)
            ->join('users','users.id','=', 'assign_user_id')->where('n_activity_type','=', $type)
            ->get();
//        $this->_data['teller_loan'] = self::select('*','notification.id as not_id')
//            ->join('till_account', 'teller_till_account_id', '=','till_account.id')
//            ->where('assign_user_id', '=', $userId)
//            ->where('n_activity_type', '=', $type)
//            ->get();
        return $this->_data;
    }

    public function NotificationDataForTeller($userId, $id, $type)
    {
        $this->_data['teller'] = self::select('*', 'till_transaction.balance as trans_balance', 'notification.id as not_id', 'till_account.id as chief_id')
            ->join('till_transaction', 'n_source_id', '=', 'till_transaction.id')
            ->where('n_source_id', '=', $id)->where('n_activity_type','=',$type)
            ->join('till_account', 'till_account.id', '=', 'teller_till_account_id')
            ->join('users', 'users.id', '=', 'till_account.assign_user_id')
            ->join('currency', 'tran_currency_id', '=', 'currency.id')
            ->get();

        $this->_data['chief'] = self::select('*','notification.id as not_id')
            ->join('till_account','till_account.id','=','chief_till_account_id')
            ->join('users','users.id','=','assign_user_id')
            ->where('n_source_id','=',$id)->get();

//        $this->_data['loan'] = Loan::select('*')
//            ->join('users','users.id','=','loans.user_id')
//            ->join('fee_charges','loan_id','=','loans.id')
//            ->where('loans.id', '=', $id)->get();
//
//        $this->_data['teller_loan'] = self::select('*','notification.id as not_id')
//            ->join('till_account', 'teller_till_account_id', '=','till_account.id')
//            ->where('assign_user_id', '=', $userId)
//            ->where('n_activity_type', '=', $type)
//            ->get();
        return $this->_data;
    }

    private function checkForm($data, $rules)
    {
        $res = false;
        $v = Validator::make($data, $rules);
        if ($v->fails()) {

            $res = false;
            $sms = $v->messages();
        } else {
            $res = true;
        }
        return ['res' => $res, 'data' => $data, 'sms' => $sms];
    }

    public function deleteNotsById($id)
    {
        return self::where('id', '=', $id)->delete();
    }

    public function JournalR(){
        return $this->hasOne('App\Models\JournalRequiry', 'id','n_source_id');
    }
    public function user(){
        return $this->hasOne('App\Models\User', 'id','chief_till_account_id');
    }


}





















