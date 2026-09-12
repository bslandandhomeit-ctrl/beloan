<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;

class TillTransaction extends Model
{
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'till_transaction';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */

    public function saveTransTill($data)
    {
        if (is_array($data)) {
            $this->attributes = $this->getArrayDataForAttributes($data);
            return self::save();
        } else {
            return false;
        }
    }

    public function updateTransTill($data)
    {
        if (is_array($data)) {
            $this->attributes = $this->getArrayDataForAttributes($data);
            return self::update();
        } else {
            return false;
        }
    }

    private function getArrayDataForAttributes($data)
    {

        $rule = [
            'till_account_id' => 'required',
            'from_account' => 'required',
            'to_account' => 'required',
            'branch_id' => 'required',
            'operate_by' => 'required',
            'tranx_time' => 'required',
            'type' => 'required',
            'cash_in' => 'required',
            'balance' => 'required',
            'description' => 'required',
        ];
        $data = [
            'till_account_id' => Request::input('till_account_id'),
            'from_account' => Request::input('from_acc'),
            'to_account' => Request::input('to_acc'),
            'branch_id' => Request::input('branch_id'),
            'operate_by' => Request::input('operate_by'),
            'tranx_time' => date("Y-m-d H:m:s", time()),
            'type' => Request::input('type'),
            'cash_in' => Request::input('cash_in'),
            'balance' => Request::input('balance'),
            'description' => Request::input('descr'),
            'status' => 1,
        ];
        $v = Validator::make($data, $rule);
        if ($v->fails()) {

            return false;
        } else {
            if (is_array($data)) {
                return $data;
            }
        }
        return false;
    }

    public function InsertTransactionFromNotification($data)
    {
        if (is_array($data)) {

            $this->attributes = $this->getArrayDataForAttributess($data);
            self::save();
            return $this->attributes['id'];

        } else {

            return false;
        }
    }

    private function getArrayDataForAttributess($data)
    {

        if (is_array($data)) {

            return $data;
        }
        return false;
    }



    public function draw_account(){

        return $this->belongsTo('App\Models\DrawdownAccounts','draw_acc_id');
    }

    public function slips(){

        return $this->belongsTo('App\Models\slips','slips_id');
    }

    public function company_branch(){

        return $this->belongsTo('App\Models\CompanyBranch','branch','branch_code');
    }

    public function till_account(){

        return $this->belongsTo('App\Models\Teller','till_account_id','assign_user_id');
    }

    public function currency(){
        return $this->belongsTo('App\Models\Currency', 'tran_currency_id');
    }
    public function feecharge(){
        return $this->hasMany('App\Models\FeeCharge','loan_id');
    }
}