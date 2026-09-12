<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK Heng
 * Date: 5/25/2015
 * Time: 5:40 PM
 */

namespace App\Http\Controllers;

use App\Models\JournalRequiry;
use App\Models\Loan;
use App\Models\Notification;
use App\Models\Teller;
use App\Models\TillTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionsRequiry;
use Illuminate\Support\Facades\Session;
use App\Models\DrawdownAccounts;
use App\Models\JournalDetail;
use Request;
use Auth;

class NotificationController extends Controller
{
    protected $userType;
    private $_return = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        dd(['data' => $this->notification->NotificationData($this->user_id)]);
    }

    public function setNotify()
    {

    }

    /// this function simply messy please let me try to implement it letter.
    public function getNot()
    {
        if (Request::ajax()) {

            $this->_return['journal']  = Notification::where('chief_till_account_id', Auth::user()->id)->with(['JournalR'=>function($l){
                $l->where('is_audit', 0);
            },'user'])->where('n_activity_type', 'Add_Journal')->get();

            if ($this->CheckPermId_from_session(90)) {

                $this->_return['till'] = $this->notification->getNotification($this->user_id, $this->group_code);
                return $this->_return;
            } else {


                $this->_return['disburse'] = Notification::select('*', 'notification.id as not_id')
                    ->join('users', 'users.id', '=', 'chief_till_account_id')// loan_admin1 whose did a disburse for teller
                    ->orwhere(function ($q) {
                        $q->where('n_activity_type', '=', 'Disburse Loan')
                            ->where('users.branch_id', '=', Auth::user()->branch_id)
                            ->where('notification.n_user_id', '=', $this->user_id);
                    })->get();

                $this->_return['reject_disburse'] = Notification::select('*', 'notification.id as not_id')
                    ->join('users', 'users.id', '=', 'chief_till_account_id')// loan_admin1 whose did a disburse for teller
                    ->orwhere(function ($q) {
                        $q->where('n_activity_type', '=', 'reject_disburse')
                            ->where('users.branch_id', '=', Auth::user()->branch_id)
                            ->where('notification.n_user_id', '=', $this->user_id);
                    })->get();

                $this->_return['repayment'] = Notification::select('*', 'notification.id as not_id')
                    ->join('users', 'users.id', '=', 'chief_till_account_id')//loan_admin whose did a add repayment for teller
                    ->where('n_activity_type', '=', 'Loan Repayment')
                    ->where('users.branch_id', '=', Auth::user()->branch_id)
                    ->where('n_user_id', '=', $this->user_id)
                    ->get();

                $this->_return['reject_repayment'] = Notification::select('*', 'notification.id as not_id')
                    ->join('users', 'users.id', '=', 'chief_till_account_id')// loan_admin whose did a add repayment for teller
                    ->where('n_activity_type', '=', 'Reject Repayment')
                    ->where('users.branch_id', '=', Auth::user()->branch_id)
                    ->where('n_user_id', '=', $this->user_id)
                    ->get();

                $this->_return['fee_charge_repayment'] = Notification::select('*', 'notification.id as not_id')
                    ->join('users', 'users.id', '=', 'chief_till_account_id')
                    ->where('n_activity_type', '=', 'Fee Charge Repayment')
                    ->where('users.branch_id', '=', Auth::user()->branch_id)
                    ->where('n_user_id', '=', $this->user_id)
                    ->get();

                $this->_return['reject_fee_charge'] = Notification::select('*', 'notification.id as not_id')
                    ->join('users', 'users.id', '=', 'chief_till_account_id')
                    ->where('n_activity_type', '=', 'reject fee charge')
                    ->where('users.branch_id', '=', Auth::user()->branch_id)
                    ->where('n_user_id', '=', $this->user_id)
                    ->get();

                $this->_return['till'] = Notification::select('*', 'notification.id as not_id', 'till_transaction.methode')
                    ->join('till_account', 'till_account.id', '=', 'teller_till_account_id')
                    ->join('users', 'users.id', '=', 'till_account.assign_user_id')
                    ->join('till_transaction', 'notification.n_source_id', '=', 'till_transaction.id')
                    ->where('n_user_id', '=', $this->user_id)
                    ->where('users.branch_id', '=', Auth::user()->branch_id)
                    ->whereNotIn('n_activity_type', ['Disburse Loan', 'Loan Repayment', 'Reject Repayment', 'Fee Charge Repayment'])
                    ->get();

            }
        }
        return $this->_return;
    }

    private function createNotify()
    {

    }

    private function activity_types($types = null)
    {
        $activity_types = Notification::select('n_activity_type')->where('n_user_id', '=', $this->user_id)->get();
        foreach ($activity_types as $vals) {
            if ($types == $vals->n_activity_type) {
                return $vals->n_activity_type;
            }
        }

    }

    public function getNotification($id, $n_activity_type)
    {
        $data = null;
        if (Request::ajax() && !empty($id)) {
            if ($this->CheckPermId_from_session(90)) {
//                if ($this->activity_types('Loan Repayment')) {
//
//                    $this->_return['data'] = Notification::select('*', 'notification.id as not_id')
//                        ->join('users', 'users.id', '=', 'notification.chief_till_account_id')
//                        ->join('transactions_requiry', 'notification.n_source_id', '=', 'transactions_requiry.id')
//                        ->where('n_source_id', '=', $id)->where('n_activity_type', '=', $n_activity_type)
//                        ->get();
//                    return $this->_return;
//                } else {
                return $this->notification->NotificationData($this->user_id, $id, $n_activity_type);
//                }

            } else {
                return $this->notification->NotificationDataForTeller($this->user_id, $id, $n_activity_type);
            }
        }
    }

    public function repayment_loan_data($id, $n_activity_type)
    {
        $data = null;
        if (Request::ajax() && !empty($id)) {

            $this->_return['repayment'] = Notification::select('*', 'notification.id as not_id')
                ->join('users', 'users.id', '=', 'notification.chief_till_account_id')//This is the reflected id which can be loan_admin id, chief id
                ->join('transactions_requiry', 'notification.n_source_id', '=', 'transactions_requiry.id')
                ->orwhere(function ($q) use ($id, $n_activity_type) {
                    $q->where('n_source_id', '=', $id)
                        ->where('n_activity_type', '=', $n_activity_type);
                })->get();

            $this->_return['till'] = Notification::select('*')
                ->join('users', function ($join) use ($id, $n_activity_type) {
                    $join->on('users.id', '=', 'notification.n_user_id');
                })->join('till_account', function ($join) use ($id, $n_activity_type) {
                    $join->on('till_account.id', '=', 'teller_till_account_id');
                })->join('currency', 'currency.id', '=', 'till_account.currency_id')
                ->orwhere(function ($w) use ($id, $n_activity_type) {
                    $w->where('n_source_id', '=', $id)
                        ->where('n_activity_type', '=', $n_activity_type)
                        ->where('n_user_id', '=', $this->user_id);
                })->get();

            return $this->_return;
        }
    }

    public function ApproveRepayments($id)
    {
        if (Request::ajax()) {

            DB::beginTransaction();
            try {

                if (Request::has('type') && Request::input('type') == 'Loan Repayment') {

                    $res = $this->Add_Loan_repayment_to_teller($id);
                }
                if (!empty($res['del_notify'])) {
                    DB::commit();
                    return $res;
                }
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function RejectsRepayment($id)
    {
        if (Request::ajax()) {

            $data = [
                'n_user_id' => Request::input('n_user_id'),
                'n_source_id' => Request::input('n_source_id'),
                'chief_till_account_id' => Request::input('chief_till_account_id'),
                'n_activity_type' => Request::input('n_activity_type'),
                'n_create_times' => date("Y-m-d H:m:s", time()),
                'n_amount' => Request::input('n_amount'),
                'n_description' => Request::input('n_description'),
                'teller_till_account_id' => Request::input('teller_till_account_id'),
            ];
            DB::beginTransaction();
            try {

                $res['create_notify'] = DB::table('notification')->insertGetId($data);
                if (!empty($res['create_notify'])) {
                    $res['up_notify'] = Notification::where('id', '=', Request::input('not_id'))->update(['n_user_id' => 0, 'teller_till_account_id' => 0]);
                }
                if (!empty($res['up_notify'])) {
                    DB::commit();
                    return $res;
                }
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function repay_change_till_acc($assign_user_id)
    {
        if (Request::ajax()) {
            if (!empty($assign_user_id)) {

                DB::beginTransaction();
                try {

                    $res['up_notify'] = Notification::where(function ($q) {
                        $q->where('n_activity_type', '=', 'Loan Repayment')
                            ->where('n_source_id', '=', Request::input('n_source_id'));
                    })->update(['n_user_id' => Request::input('n_user_id'), 'teller_till_account_id' => Request::input('teller_till_account_id'), 'n_description' => Request::input('discription')]);
                    if (!empty($res['up_notify'])) {
                        $res['del_notify'] = Notification::where('id', '=', Request::input('not_id'))->delete();
                    }
                    if (!empty($res['up_notify'])) {
                        DB::commit();
                        return $res;
                    }
                } catch (Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
                return $res;
            }
        }
    }

    public function fee_charge_repayment($n_source_id, $n_activity_type)
    {
        $data = null;
        if (Request::ajax() && !empty($n_source_id)) {

            $this->_return['fee_charge_repayment'] = Notification::select('*', 'notification.id as not_id')
                ->join('users', 'users.id', '=', 'notification.chief_till_account_id')//This is the reflected id which can be loan_admin id, chief id
                ->join('transactions_requiry', 'notification.n_source_id', '=', 'transactions_requiry.id')
                ->orwhere(function ($q) use ($n_source_id, $n_activity_type) {
                    $q->where('n_source_id', '=', $n_source_id)
                        ->where('n_activity_type', '=', $n_activity_type);
                })->get();

            $this->_return['till'] = Notification::select('*')
                ->join('users', function ($join) use ($n_source_id, $n_activity_type) {
                    $join->on('users.id', '=', 'notification.n_user_id');
                })->join('till_account', function ($join) use ($n_source_id, $n_activity_type) {
                    $join->on('till_account.id', '=', 'teller_till_account_id')
                        ->where('till_account.branch_id', '=', auth::user()->branch_id);
                })->join('currency', 'currency.id', '=', 'till_account.currency_id')
                ->orwhere(function ($w) use ($n_source_id, $n_activity_type) {
                    $w->where('n_source_id', '=', $n_source_id)
                        ->where('n_activity_type', '=', $n_activity_type)
                        ->where('n_user_id', '=', $this->user_id);
                })->get();

            return $this->_return;
        }
    }

    public function approve_fee_charge_repayment($id)
    {
        if (Request::ajax()) {

            DB::beginTransaction();
            try {

                $teller = Teller::select('balance', 'account_name', 'account_no')->where('id', '=', $id)->first();
                $data = [
                    'till_account_id' => $id,
                    'from_account' => Request::input('from_account'),
                    'till_user_id' => Request::input('till_user_id'),
                    'branch_id' => Request::input('branch_id'),
                    'operate_by' => Request::input('operate_by'),
                    'type' => Request::input('type'),
                    'description' => Request::input('description'),
                    'tranx_time' => date('Y-m-d H:m:s', time()),
                    'tran_currency_id' => Request::input('currency_id')
                ];
                $CashIn = floatval($teller->balance) + floatval(Request::input('amount'));
                $res['up_till_account'] = Teller::where('id', '=', $id)->update(['balance' => $CashIn]);
                if (!empty($res['up_till_account'])) {

                    $data['to_account'] = $teller->account_name;
                    $data['cash_in'] = Request::input('amount');
                    $data['balance'] = $CashIn;
                    $res['ins_transaction'] = DB::table('till_transaction')->insertGetId($data);
                    if (!empty($res['ins_transaction']) && !empty($res['up_till_account'])) {

                        $res['del_notify'] = Notification::where('id', '=', Request::input('not_id'))->delete();
                    }
                }

                if (!empty($res['del_notify'])) {
                    DB::commit();
                    return $res;
                }
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function RejectsFeeCharges($id)
    {
        if (Request::ajax()) {

            $data = [
                'n_user_id' => Request::input('n_user_id'),
                'n_source_id' => Request::input('n_source_id'),
                'chief_till_account_id' => Request::input('chief_till_account_id'),
                'n_activity_type' => Request::input('n_activity_type'),
                'n_create_times' => date("Y-m-d H:m:s", time()),
                'n_amount' => Request::input('n_amount'),
                'n_description' => Request::input('n_description'),
                'teller_till_account_id' => Request::input('teller_till_account_id'),
            ];
            DB::beginTransaction();
            try {

                $res['create_notify'] = DB::table('notification')->insertGetId($data);
                if (!empty($res['create_notify'])) {
                    $res['up_notify'] = Notification::where('id', '=', Request::input('not_id'))->update(['n_user_id' => 0, 'teller_till_account_id' => 0]);
                }
                if (!empty($res['up_notify'])) {
                    DB::commit();
                    return $res;
                }
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function feeCharge_repay_change_till_acc($assign_user_id)
    {
        if (Request::ajax()) {
            if (!empty($assign_user_id)) {

                DB::beginTransaction();
                try {
                    $res['up_notify'] = Notification::where(function ($q) {
                        $q->where('n_activity_type', '=', 'Fee Charge Repayment')
                            ->where('n_source_id', '=', Request::input('n_source_id'));
                    })
                        ->update(['n_user_id' => Request::input('n_user_id'), 'teller_till_account_id' => Request::input('teller_till_account_id'), 'n_description' => Request::input('discription')]);
                    if (!empty($res['up_notify'])) {
                        $res['del_notify'] = Notification::where('id', '=', Request::input('not_id'))->delete();
                    }
                    if (!empty($res['up_notify'])) {
                        DB::commit();
                        return $res;
                    }
                } catch (Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
                return $res;
            }
        }
    }

    public function Get_disbursement($id, $n_activity_type)
    {
        if(Request::ajax()){
            $this->_return['disburse'] = Notification::select('*', 'notification.id as not_id', 'loans.id as loans_id','loans.drawdown_acc')
                ->join('users', 'users.id', '=', 'notification.chief_till_account_id')
                ->leftJoin('fee_charges', 'fee_charges.loan_id', '=', 'n_source_id')
                ->join('loans', 'n_source_id', '=', 'loans.id')
                ->orwhere(function ($w) use ($id, $n_activity_type) {
                    $w->where('n_activity_type', '=', $n_activity_type)
                        ->where('n_source_id', '=', $id);
                })->get();

            $this->_return['commission'] = Notification::select('*')
                ->join('transactions_requiry', 'notification.n_source_id', '=', 'transactions_requiry.loan_id')
                ->orwhere(function ($w) use ($id, $n_activity_type) {
                    $w->where('n_source_id', '=', $id)
//                        ->where('trans_type', 'Fee Charge Repayment')
                        ->where('n_activity_type', $n_activity_type);
                })->get();

            $this->_return['till'] = Notification::select('*')
                ->join('users', function ($join) use ($id, $n_activity_type) {
                    $join->on('users.id', '=', 'notification.n_user_id');
                })->join('till_account', function ($join) use ($id, $n_activity_type) {
                    $join->on('till_account.id', '=', 'teller_till_account_id');
                })->join('currency', function ($join) use ($id, $n_activity_type) {
                    $join->on('currency.id', '=', 'till_account.currency_id');
                })
                ->orwhere(function ($w) use ($id, $n_activity_type) {
                    $w->where('n_source_id', '=', $id)
                        ->where('n_activity_type', '=', $n_activity_type);
                })
                ->get();
            // dd($this->_return);
            return $this->_return;
        }
    }

    public function ApproveDisburse($id)
    {
        if (Request::ajax()) {

            $data = [
                'till_account_id' => Request::input('till_account_id'),
                'from_account' => Request::input('from_account'),
                'to_account' => Request::input('to_account'),
                'till_user_id' => Request::input('till_user_id'),
                'branch_id' => Request::input('branch_id'),
                'operate_by' => Request::input('operate_by'),
                'balance' => Request::input('remind_balance'),
                'type' => Request::input('type'),
                'tranx_time' => date('Y-m-d H:m:s', time()),
                'tran_currency_id' => Request::input('currency_id')
            ];
            dd(Request::input());
            DB::beginTransaction();
            try {
                $dd = DrawdownAccounts::select('id', 'balance', 'client_id', 'currency')->where('account_no',Request::input('drawdown_acc'))->where('currency', '=', Request::input('currency_id'))->first();
                $notification =  Notification::where('id', '=', Request::input('not_id'))->first();
                if($notification){
                    if($notification->disburs_loan_id != 0){
                        TransactionsRequiry::where('disburs_loan_id',$notification->disburs_loan_id)->update(['is_audit'=>1]);
                        JournalRequiry::where('disburs_loan_id',$notification->disburs_loan_id)->update(['is_audit'=>1]);
                        JournalDetail::where('disburs_loan_id',$notification->disburs_loan_id)->update(['is_audit'=>1]);
                    }
                }
                if($dd){
                    $dd->balance = $dd->balance + Request::input('cash_out');
                    $dd->update();
                }
                $res['up_com'] = Teller::where('id', '=', $id)->update(['balance' => Request::input('balancePlusCommision')]);
                if (!empty($res['up_com'])) {

                    $data['cash_out'] = Request::input('cash_out');
                    $res['ins_disburseTrans'] = DB::table('till_transaction')->insertGetId($data);

                    $data['cash_in'] = Request::input('cash_in');
                    $data['cash_out'] = 0;
                    $data['type'] = 'commission fee';
                    $data['balance'] = Request::input('balancePlusCommision');

                    $res['commisTrans'] = DB::table('till_transaction')->insertGetId($data);

                    if (!empty($res['ins_disburseTrans']) && !empty($res['commisTrans'])) {
                        $res['del_notify'] = Notification::where('id', '=', Request::input('not_id'))->delete();
                    }
                }
                if (!empty($res['del_notify'])) {
                    DB::commit();
                    return $res;
                }
                return false;
            } catch (Exception $e) {
                DB::rollBack();
                // dd($e->getMessage());
                throw $e;
            }
        }
    }

    public function RejectsDisburse($id)
    {
        if (Request::ajax()) {

            $data = [
                'n_user_id' => Request::input('n_user_id'),
                'n_source_id' => Request::input('n_source_id'),
                'chief_till_account_id' => Request::input('chief_till_account_id'),
                'n_activity_type' => Request::input('n_activity_type'),
                'n_create_times' => date("Y-m-d H:m:s", time()),
                'n_amount' => Request::input('n_amount'),
                'n_description' => Request::input('n_description'),
                'teller_till_account_id' => Request::input('teller_till_account_id'),
            ];
            DB::beginTransaction();
            try {

                $res['create_notify'] = DB::table('notification')->insertGetId($data);
                if (!empty($res['create_notify'])) {
                    $res['up_notify'] = Notification::where('id', '=', Request::input('not_id'))->update(['n_user_id' => 0, 'teller_till_account_id' => 0]);
                }
                if (!empty($res['up_notify'])) {
                    DB::commit();
                    return $res;
                }
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    private function Add_Loan_repayment_to_teller($id = null)
    {
        if(Request::ajax()){
            if (!empty($id)) {

                $teller = Teller::select('balance', 'account_name', 'account_no')
                    ->where('id', '=', $id)->first();
                $data = [
                    'till_account_id' => $id,
                    'from_account' => Request::input('from_account'),
                    'till_user_id' => Request::input('till_user_id'),
                    'branch_id' => Request::input('branch_id'),
                    'operate_by' => Request::input('operate_by'),
                    'type' => Request::input('type'),
                    'description' => Request::input('description'),
                    'tranx_time' => date('Y-m-d H:m:s', time()),
                    'tran_currency_id' => Request::input('currency_id')
                ];
                $CashIn = floatval($teller->balance) + floatval(Request::input('amount'));
                $res['up_till_account'] = Teller::where('id', '=', $id)->update(['balance' => $CashIn]);
                if (!empty($res['up_till_account'])) {

                    $data['to_account'] = $teller->account_name;
                    $data['cash_in'] = Request::input('amount');
                    $data['balance'] = $CashIn;
                    $res['ins_transaction'] = DB::table('till_transaction')->insertGetId($data);
                    if (!empty($res['ins_transaction']) && !empty($res['up_till_account'])) {

                        $res['del_notify'] = Notification::where('id', '=', Request::input('not_id'))->delete();
                    }
                }
                return $res;
            }
        }
    }

    private function CheckPermId_from_session($permisId = null)
    {
        $user = \App\Models\User::select("*")->join('roles', 'users.role_id', '=', 'roles.id')->where('users.id', '=', $this->user_id)->first();
        if (!$user) {
            return null;
        }
        $role_name = trim($user->role);

        if (!empty($role_name) && ($role_name == 'chief_of_teller' || $role_name == 'cas')) {

            return true;

        } else if ($role_name === 'teller') {

            return false;

        }
//        $data = Session::get('ROLE_PERMISSION');
//        if (!empty($permisId) && is_integer($permisId)) {
//
//            foreach ($data as $val) {
//                if ((int)$val->id == (int)$permisId) {
//                    return true;
//                }else{
//
//                }
//            }
//        } else {
//
//        }
    }

    public function change_till_account($assign_user_id)
    {
        if (Request::ajax()) {
            if (!empty($assign_user_id)) {

                DB::beginTransaction();
                try {
                    $res['up_notify'] = Notification::where(function ($q) {
                        $q->where('n_activity_type', '=', 'Disburse Loan')->where('n_source_id', '=', Request::input('n_source_id'));
                    })->update(['n_user_id' => Request::input('n_user_id'), 'teller_till_account_id' => Request::input('teller_till_account_id'), 'n_description' => Request::input('discription')]);
                    if (!empty($res['up_notify'])) {
                        $res['del_notify'] = Notification::where('id', '=', Request::input('not_id'))->delete();
                    }
                    if (!empty($res['up_notify'])) {
                        DB::commit();
                        return $res;
                    }
                } catch (Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
                return $res;
            }
        }
    }

}







