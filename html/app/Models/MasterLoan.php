<?php namespace App\Models;
/**
 * Created by
 * User: N.K
 * Date: 01/06/2021
 * Time: 10:30 PM
 */

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LoanCalculate;

class MasterLoan extends Model {
    protected $table = 'trn_master_loans';
    protected $hidden = ['created_at', 'updated_at'];
    protected $lastActionTmp = null;
    protected $penaltyInfoTmp = null;
    protected $penaltyCalculatedTmp = null;
    protected $loanTmp = null;
    protected static $ALLOW_ASSIGN_ROLE_CODES = ['lrm', 'admin']; // lrm: Loan Recovery Manager
    protected static $ALLOW_ASSIGNEE_ROLE_CODES = ['lrso', 'lro']; // lrso: Senior Loan Recovery, lro: Loan Recovery Officer
    public static $ASSIGN_OPTION_AUTO = array('value' => 1, 'label' => 'Auto Assign by Profile');
    public static $ASSIGN_OPTION_EXCEL = array('value' => 2, 'label' => 'Assign by Excel file');
    public static $ASSIGN_OPTIONS = array(
        array('value' => 1, 'label' => 'Auto Assign by Profile'),
        array('value' => 2, 'label' => 'Assign by Excel file')
    );

    private $journalDetailsTmp = null;
    private $totalCashInAmountTmp = null;
    private $paymentStatusTmp = null;

    private $negotiationProgressStatusTmp = null;
    private $assigneeUserTmp = null;

    public function product(){
        return $this->belongsTo('App\Models\Product','product_id');
    }
    public function client(){
        return $this->belongsTo('App\Models\Client','client_id');
    }
//    public function loanDealer(){
//
//        return $this->hasOne('App\Models\LoanDealer','loan_id');
//    }
    public function branch()
    {
        return $this->belongsTo('App\Models\CompanyBranch','company_branch_id');
    }
//    public function payment(){
//        return $this->hasMany('App\Models\LoanPayments','loan_id');
//    }

    public function approval(){
        return $this->hasOne('App\Models\LoanApproval','loan_id');
    }
//    public function close(){
//        return $this->hasOne('App\Models\LoanClose','loan_id');
//    }
//    public function writeoff(){
//        return $this->hasOne('App\Models\LoanWriteOff','loan_id');
//    }
    public function payoff(){
        return $this->hasOne('App\Models\LoanPayOff','loan_id');
    }
//    public function collateral(){
//        return $this->hasMany('App\Models\LoanCollateral','loan_id');
//    }
//    public function loanapproval(){
//        return $this->hasOne('App\Models\LoanApproval','loan_id');
//    }
//    public function feecharge(){
//        return $this->hasMany('App\Models\FeeCharge','loan_id');
//    }
//    public function costfee()
//    {
//        return $this->hasMany('App\Models\LoanCostFee','loan_id');
//    }
//    public function transaction()
//    {
//        return $this->hasMany('App\Models\TransactionsRequiry', 'loan_id');
//    }
//    public function dealer(){
//        return $this->belongsTo('App\Models\Dealer','dealer_id');
//    }
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function co_user(){
        return $this->belongsTo('App\Models\User','co');
    }
    public function schedule()
    {
        return $this->hasMany('App\Models\RepaymentSchedule','loan_id');
    }
//	public function getHolidayAttribute()
//	{
//        $date = add_month(date('Y-m-d', strtotime($this->start_date)), $this->loan_duration + 1)->format('Y-m-d');
//		return Holiday::whereBetween('holiday_date',[$this->start_date,$date])->orderBy('holiday_date')->lists('holiday_date');
//	}
//    public function transaction_req(){
//        return $this->hasMany('App\Models\TransactionsRequiry','loan_id');
//    }
    public function client_loan_account()
    {
        return $this->belongsTo('App\Models\ClientLoanAccounts','loan_account_id');
    }
//
//    public function transaction_reqLastBalance(){
//    	return $this->hasMany('App\Models\TransactionsRequiry','loan_id');
//    }

//    public function accrued_journal_detail(){
//    	return $this->hasMany('App\Models\JournalDetail', 'coa_id', 'air_id');
//    }

//    public function coa_journal_detail(){
//        return $this->hasMany('App\Models\JournalDetail', 'coa_id', 'coa_id');
//    }
//    public function accrued_repayment_schedule(){
//    	return $this->hasMany('App\Models\RepaymentSchedule', 'loan_id');
//    }

//    public function penalty_record(){
//        return $this->hasMany('App\Models\PenaltyRecord', 'loan_id');
//    }

//    public function scheduleOne()
//    {
//        return $this->hasMany('App\Models\RepaymentSchedule','loan_id');
//    }
//    public function product_type(){
//        return $this->belongsTo('App\Models\Products\Product_type', 'loan_type');
//    }
//    public function transaction_req_last(){
//    	return $this->hasMany('App\Models\TransactionsRequiry','loan_id');
//    }

    public function saveUpdate() {
        self::saveOrUpdate($this);
    }
    public static function saveOrUpdate($object) {
        $object = self::defineUserModify($object);
        $object->save();
    }
    public static function defineUserModify($object) {
        if (!$object->id) {
            $object->created_by = Auth::user()->username;
            $object->created_at = new DateTime();
        } else {
            $object->updated_by = Auth::user()->username;
            $object->updated_at = new DateTime();
        }
        return $object;
    }
    public static function isHasPermissionAssign($user_id = null) {
        $roleCode = '';
        if (!$user_id) {
            $roleCode = Auth::user()->role->role;
        } else {
            $user = User::find($user_id);
            if ($user) {
                $roleCode = $user->role->role;
            }
        }
        if (in_array($roleCode, self::$ALLOW_ASSIGN_ROLE_CODES)) {
            return true;
        }
        return false;
    }
    public static function isHasPermissionDoActionLoanStatic($loan_id, $user_id = null) {
        if ($loan_id) {
            $isHasPermissionAssign = self::isHasPermissionAssign($user_id);
            if ($isHasPermissionAssign) {
                return true;
            } else {
                $user_own_id = $user_id ? $user_id : Auth::user()->id;
                $master_loan = self::where('loan_id', $loan_id)->where('assignee_id', $user_own_id)->get();
                if ($master_loan != null) {
                    return true;
                }
            }
        }
        return false;
    }
    public function isHasPermissionDoActionLoan($user_id = null) {
        $isHasPermissionAssign = self::isHasPermissionAssign();
        if ($isHasPermissionAssign) {
            return true;
        } else {
            $user_own_id = $user_id ? $user_id : Auth::user()->id;
            if ($this->assignee_id && $this->assignee_id == $user_own_id) {
                return true;
            }
        }
        return false;
    }
    public static function getAssigneeRoles() {
        $roles = Role::whereIn('role', self::$ALLOW_ASSIGNEE_ROLE_CODES)->get();
        if ($roles) {
            return $roles;
        }
        return array();
    }
    /**
     * $option = [
     *      'assign_option' => 'required | numeric',
     *      'assignee_role' => 'required', // 'lrso' or 'lro' or ...
     * ]
     */
    public static function assignFreezeMasterLoanDataByOption($option) {
        if (self::isHasPermissionAssign() && $option) {
            if (isset($option['assign_option']) && isset($option['assignee_role'])) {
                switch ($option['assign_option']) {
                    // Todo: auto assign by role
                    case self::$ASSIGN_OPTION_AUTO['value'] :
                        return self::autoAssignByRole($option['assignee_role']);
                        break;
                    // Todo: assign by list from excel file {user_id: 1, loan_id: 1}
                    case self::$ASSIGN_OPTION_EXCEL['value'] :
                        return self::assignByUserList($option['assign_list']);
                        break;
                }
            }
        }
    }
    public static function autoAssignByRole($role) {
        if (self::isHasPermissionAssign() && $role && !empty($role)) {
            $role = Role::where('role', $role)->first();
            if ($role) {
                $users = User::select(['id'])->where('role_id', $role->id)->get();
                $users = collect($users);
                $users_count = $users->count();
                if ($users && $users_count > 0) {
                    $users->shuffle(); // Random index of array
                    $master_loans = self::select(['id', 'assignee_id', 'assigner_id'])->get();
                    $master_loans = collect($master_loans);
                    $master_loans_count = $master_loans->count();
                    $master_loans->shuffle();
                    $assigner_id = Auth::user()->id;
                    $loan_per_user = (int) ($master_loans_count / $users_count);
                    $loan_remain = (int) ($master_loans_count % $users_count);
                    $master_loans_split = $master_loans->chunk($loan_per_user);
                    try {
                        DB::beginTransaction();
                        foreach ($users as $index => $user) {
                            $loans = $master_loans_split[$index];
                            $assignee_id = $user->id;
                            if ($loans && count($loans) > 0) {
                                foreach ($loans as $loan) {
                                    $loan->assigner_id = $assigner_id;
                                    $loan->assignee_id = $assignee_id;
                                    $loan->saveUpdate();
                                }
                            }
                        }
                        // Todo: Remain loan after split with user
                        if ($loan_remain > 0) {
                            $loans = $master_loans_split[count($master_loans_split) - 1];
                            $users->shuffle();
                            foreach ($loans as $index => $loan) {
                                $loan->assigner_id = $assigner_id;
                                $loan->assignee_id = $users[$index]->id;
                                $loan->saveUpdate();
                            }
                        }
                        DB::commit();
                    } catch (\Exception $ex ) {
                        DB::rollBack();
                    }
                }
                return true;
            }
        }
        return false;
    }

    // Todo: assign by list from excel file {user_id: 1, loan_id: 1}
    public static function assignByUserList($userList) {
        if (self::isHasPermissionAssign() && $userList && !empty($userList)) {
            $assigner_id = Auth::user()->id;
            var_dump($userList);
            foreach ($userList as $item) {
                $loan_id = null;
                $assignee_id = null;
                if (isset($item['loan_id'])) {
                    $loan_id = $item['loan_id'];
                }
                if (isset($item['user_id'])) {
                    $assignee_id = $item['user_id'];
                } else if (isset($item['user_name']) && isset($item['loan_id'])) {
                    $user = User::select(['id'])->where('username', $item['user_name'])->first();
                    if ($user != null) {
                        $assignee_id = $user->id;
                    }
                }
                if ($loan_id != null && $assignee_id != null) {
                    try {
                        DB::beginTransaction();
                        $loan = self::where('loan_id', $loan_id)->first();
                        if ($loan != null) {
                            $loan->assigner_id = $assigner_id;
                            $loan->assignee_id = $assignee_id;
                            $loan->saveUpdate();
                        }
                        DB::commit();
                    } catch (\Exception $ex ) {
                        DB::rollBack();
                    }
                }
            }
            return true;
        }
        return false;
    }

    // ===>>> Start code Freeze Master Loans
    public static function freezeMasterLoanData() {
        self::clearBackupBeforeFreezeMasterLoanData();
        $loan_list = self::getLoanListForFreezing();
        try {
            DB::beginTransaction();
            foreach ($loan_list as $loan) {
                $object = new self();
                $object->loan_id = $loan['id'];
                $object->unit_id = $loan['unit_id'];
                $object->contract_id = $loan['contract_id'];
                $object->client_id = $loan['client_id'];
                $object->customer_name = $loan['client_name'];
                $object->customer_phone1 = $loan['phone1'];
                $object->customer_phone1 = $loan['phone1'];
                $object->main_project_name = $loan['main_project'];
                $object->variant_code = $loan['variance_code'];
                $object->outstanding_balance = $loan['outstanding_balance'];
                $object->drawdown_balance = $loan['drawdown_balance'];
                $object->loan_amount = $loan['loan_amount'];
                $object->schedule_amount = $loan['schedule_amount'];
                $object->schedule_date = $loan['schedule_date'];
                $object->disbursement_date = $loan['disbursement_date'];
                $object->payment_no = $loan['payment_no'];
                $object->drawdown_account = $loan['drawdown_account'];
                $object->project_id = $loan['project_id'];
                $object->saveUpdate();
            }
            DB::commit();
        } catch (\Exception $ex ) {
            DB::rollBack();
        }
    }
    public static function getLoanListForFreezing() {
        $beginOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t', strtotime($beginOfMonth));
        $loan_list = Loan::select([
                'loans.id',
                'loans.contract_id',
                'loans.loan_amount',
//                'loans.drawdown_acc',
                DB::raw("CONCAT('DD',tb_loans.drawdown_acc) as 'drawdown_account'"),
                'projects.short_code as main_project',
                'projects.id as project_id',
                'units.code as variance_code',
//                'unit_types.name',
                "repayment_schedule.no as payment_no",
                "repayment_schedule.schedule_date",
                DB::raw("tb_repayment_schedule.interest + tb_repayment_schedule.principal as schedule_amount"),
                'loans.disburse_date as disbursement_date',
                'client_loan_accounts.balance as outstanding_balance',
                'drawdown_account.balance as drawdown_balance',
                'loans.unit_sale_price',
                'loans.price_after_discount',
                'loans.down_payment',
                'loans.loan_duration',
                'loans.interest_rate',
                'loans.client_id',
                'clients.client_name',
                'clients.phone1',
                'clients.address',
                'loans.unit_id',
            ]
        )
            ->with(['approval' => function ($query) {
                $query->select('id', 'loan_id', 'approval_date');
            }, 'client_loan_account', 'payoff'])
            ->leftJoin('clients', 'clients.id', '=', 'loans.client_id')
            ->leftJoin('units', 'units.id', '=', 'loans.unit_id')
            ->leftJoin('unit_types', 'unit_types.id', '=', 'units.unit_type_id')
            ->leftJoin('projects', 'projects.id', '=', 'unit_types.project_id')
            ->leftJoin('repayment_schedule', 'repayment_schedule.loan_id', '=', 'loans.id')
//            ->leftJoin('client_loan_accounts', 'client_loan_accounts.loan_ref', '=', 'loans.contract_id')
            ->leftJoin('client_loan_accounts', 'client_loan_accounts.id', '=', 'loans.loan_account_id')
            ->leftJoin('drawdown_account', 'drawdown_account.account_no', '=', 'loans.drawdown_acc')
            ->whereBetween('repayment_schedule.schedule_date', [$beginOfMonth, $endOfMonth])
            ->where('loans.status', '=', '3')
            ->get();
        return $loan_list;
    }
    public static function clearBackupBeforeFreezeMasterLoanData() {
//        DB::statement('ALTER TABLE HS_Request AUTO_INCREMENT=9999');
        $affectedRows = self::query()->delete();
    }
    // ===>>> End code Freeze Master Loans

    public function getOutstandingBalance(){
        $principal_original_sum = 0.00;
        $principal_sum = 0;
        $payment_schedule = LoanCalculate::loan_schedule($this->schedule, $this->start_date)[0];
        foreach ($payment_schedule as $p) {
            $principal_original_sum += $p[3];
        }
        $repayment = $this->payment;
        foreach($repayment as $pay){
            $principal_sum += $pay->paid_principal;
        }
        return $principal_original_sum - $principal_sum;
    }

    public function getLastAction(){
        if (!$this->lastActionTmp) {
            $action = CollectionAction::getListActionByLoanId($this->loan_id, 1);
            if ($action) {
                $this->lastActionTmp = $action[0];
            }
        }
        return $this->lastActionTmp;
    }

    public function getPenaltyInfo(){
        // Todo: get penalty info
        if (!$this->penaltyInfoTmp) {
            try {
                $loan = Loan::with(['payment', 'schedule', 'client_loan_account'])->find($this->loan_id);
                $this->penaltyInfoTmp = LoanCalculate::getTotalPenalty($loan);
            } catch (\Exception $ex) {
            }
        }
        return $this->penaltyInfoTmp;
    }
    public function getOverdueDay(){
        $penalty_info = $this->getPenaltyInfo();
        if ($penalty_info != null) {
            // Todo: get overdue value from penalty index 2
            if (isset($penalty_info[2]) && $penalty_info[2] != null && !empty($penalty_info[2])) {
                return $penalty_info[2];
            }
        }
        return null;
    }
    public function getArearDate(){
        $penalty_info = $this->getPenaltyInfo();
        if ($penalty_info != null) {
            // Todo: get areer date from penalty index 3
            if (isset($penalty_info[3]) && $penalty_info[3] != null && !empty($penalty_info[3])) {
                return date('Y-m-d H:i:s', strtotime($penalty_info[3]));
            }
        }
        return null;
    }
    public function getLoan(){
        if (!$this->loanTmp) {
            $this->loanTmp = Loan::find($this->loan_id);
        }
        return $this->loanTmp;
    }
    public function getPenaltyAmount(){
        $penalty_data = $this->getPenaltyCalculated();
        if ($penalty_data != null && isset($penalty_data[2])) {
            return $penalty_data[2];
        }
        return null;
    }
    public function getOverdueAmount(){
        // Todo: Principle amount + Interest Overdue amount
        $penalty_data = $this->getPenaltyCalculated();
        if ($penalty_data != null) {
            $overdueAmount = $penalty_data[0] + $penalty_data[1];
            return $overdueAmount;
        }
        return null;
    }
    public function getAmountToCollect(){
        // Todo: Overdue amount + Penalty amount
        $penalty_data = $this->getPenaltyCalculated();
        if ($penalty_data != null) {
            $amountToCollect = $this->getPenaltyAmount();
            $overdueAmount = $this->getOverdueAmount();
            if (!$amountToCollect) {
                $amountToCollect = $overdueAmount;
            } else if ($overdueAmount) {
                $amountToCollect += $overdueAmount;
            }
            if ($this->drawdown_balance != null) {
                $amountToCollect = $amountToCollect - $this->drawdown_balance;
            }
            if ($amountToCollect < 0) {
                $amountToCollect = 0;
            }
            return $amountToCollect;
        }
        return null;
    }
    private function getPenaltyCalculated() {
        $penalty_info = $this->getPenaltyInfo();
        if ($penalty_info != null) {
            if (!$this->penaltyCalculatedTmp) {
                $loan = $this->getLoan();
                $result = $penalty_info[0];
                $to_principal = 0;
                $to_interest = 0;
                $penalty_amount = 0;
                $total_payment = 0;
                for ($i = 0; $i < $loan->loan_duration; $i++) {
                    if (empty($result[$i])) {
                        continue;
                    }
                    $to_principal += $result[$i][1];
                    $to_interest += $result[$i][2];
                    $penalty_amount += $result[$i][8];
                    $total_payment += $result[$i][9];
                }
                $this->penaltyCalculatedTmp = [$to_principal, $to_interest, $penalty_amount, $total_payment];
            }
            return $this->penaltyCalculatedTmp;
        }
        return null;
    }

    public function getJournalDetails($startDate, $endDate) {
        if (!$this->journalDetailsTmp && $this->drawdown_account) {
            $this->journalDetailsTmp = JournalDetail::select('*', 'journal_requiry.entry_date', 'journal_requiry.description')
                ->join('journal_requiry', 'journal_requiry.id', '=', 'journal_detail.journal_id')
                ->join('drawdown_account', function ($join) {
                    $join->where('drawdown_account.account_no', '=', $this->drawdown_account);
                })
                ->join('coa_categories', 'coa_categories.id', '=', 'drawdown_account.coa_id')
                ->where('journal_detail.is_audit', '=', 1)
                ->whereBetween('journal_requiry.entry_date', [$startDate, $endDate])
                ->get();
        }
        return $this->journalDetailsTmp;
    }
    public function getTotalCashInAmountCurrentMonth() {
        if (!$this->totalCashInAmountTmp) {
            $journalDetails = $this->getJournalDetails(date("Y-m-01"), date("Y-m-t"));
            $this->totalCashInAmountTmp = 0;
            if ($journalDetails) {
                foreach ($journalDetails as $detail) {
                    $this->totalCashInAmountTmp += ($detail->credit ? $detail->credit : 0);
                }
            }
        }
        return $this->totalCashInAmountTmp;
    }

    public function getPaymentStatus() {
        if (!$this->paymentStatusTmp) {
            $statusCode = null;
            // Todo: Full Paid (Total Amount Paid in Current Month >= Overdue Amount)
            if ($this->getAmountToCollect() <= 0) {
                $statusCode = ItemConstant::SUB_ITEM_REF_COL_PAYMENT_STATUS_1;
            } else {
                $cashInAmount = $this.$this->getTotalCashInAmountCurrentMonth();
                $overdueAmount = $this.$this->getOverdueAmount();
                // Todo: Partial paid (Total Amount Paid in Current Month >0 and < Overdue Amount)
                if ($cashInAmount > 0) {
                    if ($cashInAmount < $overdueAmount) {
                        $statusCode = ItemConstant::SUB_ITEM_REF_COL_PAYMENT_STATUS_2;
                    }
                } // Todo: Not yet pay (Total Amount Paid in Current Month <0)
                else {
                    $statusCode = ItemConstant::SUB_ITEM_REF_COL_PAYMENT_STATUS_3;
                }
            }
            if ($statusCode != null) {
                $this->paymentStatusTmp = ItemRefSub::getFirstByCode($statusCode);
            }
        }
        return $this->paymentStatusTmp;
    }

    public function getNegotiationProgressStatus() {
        if (!$this->negotiationProgressStatusTmp) {
            $action = $this->getLastAction();
            if ($action) {
                $this->negotiationProgressStatusTmp = ItemRefSub::getFirstByCode($action->negotiation);
            }
        }
//        if (!$this->negotiationProgressStatusTmp) {
//            $action = CollectionAction::getLastActionNegotiationProgressByLoanId($this->loan_id);
//            if ($action) {
//                $this->negotiationProgressStatusTmp = ItemRefSub::getFirstByCode($action->negotiation);
//            }
//        }
        return $this->negotiationProgressStatusTmp;
    }
    public function getAssignee(){
        if (!$this->assigneeUserTmp) {
            $this->assigneeUserTmp = User::select('name')->find($this->assignee_id);
        }
        return $this->assigneeUserTmp;
    }
}
