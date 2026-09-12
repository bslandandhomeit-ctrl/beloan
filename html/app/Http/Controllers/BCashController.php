<?php

namespace App\Http\Controllers;
use App\Http\Controllers\PrintController;
use App\Models\Cbc\Client;
use App\Models\Client as Clients;
use App\Models\CoaCategory;
use App\Models\Currency;
use App\Models\Loan;
use App\Models\Audit;
//use App\Models\Notification;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use App\Models\DrawdownAccounts;
use App\Models\JournalRequiry;
use App\Models\JournalDetail;
use App\Models\Teller;
use App\Models\Project;
use App\Models\TillTransaction;
use App\Models\CompanyBranch;
use App\Models\UserPermission;
use App\Models\UserRoles;
use App\Models\RepaymentSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Guard;
use Exception;
// use League\Flysystem\Exception;
use App\Models\slips;
use App\Models\Unit;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TillTransactionReject;
use App\Models\LoanPaymentsDraft;
use App\Models\ClientLoanAccounts;
use App\Models\BCashTransaction;
use App\Models\BCashLineItem;
use App\Models\UnitType;
use App\Models\TransactionsPosting;

use App\Models\SystemDate;
use Illuminate\Support\Facades\Input;
use LoanCalculate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Image;
class BCashController extends Controller {
    // protected $tillType;
    // private $_return = false;
    // private $_till_stat = false;
    // private $_isLogin = false;
    // private $_branch_id = false;
    // public $printServices;
 
 /**
  * Instantiate a new BCashController instance.
  */
  public function __construct()
  {
    parent::__construct();
    $this->middleware('xss');
    // //$this->middleware('auth');
    // $this->_till_stat = $this->Till_status();
    // $this->_isLogin = auth()->check();
    // $this->tillType = $this->CheckPermId_from_session();//true is chief false is teller
    // $this->_branch_id = auth()->user()->branch_id;
    // $this->user_id = Auth::user()->id;
    // $this->signature = Auth::user()->signature;
    // $this->is_signature = Auth::user()->is_signature;
    // $this->role = Auth::user()->role->role;
  }
  private function Till_status()
  {

      return Teller::select('status')->where(['assign_user_id' => auth()->user()->id])->first();
  }
      /*
     * Declare user types
     */
    private function CheckPermId_from_session($permisId = null)
    {
        $user = User::select("*")->join('roles', 'users.role_id', '=', 'roles.id')->where('users.id', '=', auth()->user()->id)->first();

        if ($user) {

            $role_name = trim($user->role);
            if (trim($role_name) == 'chief_of_teller' || trim($role_name) == 'cas' || trim($role_name) == 'super_admin') {
            //if (trim($role_name) == 'Admin') {
                    return true;
            }if(trim($role_name) == 'teller') {

                return false;

            }
        }
    }
  public function getBCash()
  {
      $result = [];
      $searchterm = '';
      if (!auth()->check()) return redirect()->route('login');

      $tellers = Teller::select('till_account.account_name as account_name', 'till_account.balance as balance', 'users.id as uid', 'users.name as name',
          'company_branch.branch_name as branch_name', 'company_branch.id as bid', 'currency_id', 'till_account.status','users.company_type','users.allow_postback_date')
          ->join('users', 'users.id', '=', 'till_account.assign_user_id')
          ->join('company_branch', 'company_branch.id', '=', 'till_account.branch_id')
          ->where('assign_user_id', '=', auth()->user()->id)
          ->get();

      return $this->view('bcash.home', ['tellers' => $tellers, 'tillType' => $this->CheckPermId_from_session()]);
  }


  public function printBECash($transaction_id){

    $bcash = BCashTransaction::select('bcash_transaction.*')
    ->where('bcash_transaction.id', '=', $transaction_id)
    ->first();
    if(empty($bcash)){
        return redirect()->back();
    }
        $B0 = new Loan();
        $loan = $B0->selectRaw('
        tb_loans.id,
        tb_loans.contract_id,
        tb_loans.company_branch_id,
        tb_loans.start_date,
        tb_loans.loan_type,
        tb_loans.penalty_rate_type,
        tb_loans.loan_amount,
        tb_loans.original_amount,
        tb_loans.interest_rate,
        tb_loans.loan_account_id,
        tb_loans.drawdown_acc,
        tb_loans.loan_penalty_type,
        tb_loans.penalty_rate1,
        tb_loans.penalty_rate2,
        tb_loans.penalty_period1,
        tb_loans.penalty_period2,
        tb_loans.unit_sale_price,
        tb_loans.amount_discount_payment_option,
        tb_loans.discount_payment_option,
        tb_loans.discount_other,
        tb_loans.down_payment_value,
        tb_loans.status,
        tb_loans.loan_duration,
        tb_loans.submitted_on,
        tb_loans.disburse_date,
        tb_loans.rejected_date,
        tb_loans.client_id,
        tb_loans.contract_date,
        tb_loans.created_at,
        tb_loans.updated_at,
        tb_units.code'
    )->leftJoin('units','units.id','=','loans.unit_id')
    ->where('loans.id', '=', $bcash->loan_id)
    ->first();

    $drawdown = DrawdownAccounts::where('id', $bcash->drawdown_acc_id)->first();
    $unit = Unit::find($drawdown->unit_id);
    $unit_type = UnitType::find($unit->unit_type_id);
    $projects_row = Project::find($unit_type->project_id);
    $company_branch = CompanyBranch::find($projects_row->company_id);
    $currecyType = Currency::where('id', $drawdown->currency)->first();

    return $this->view('bcash.receipt', [
                                        'loan' => $loan,
                                        'becash' => $bcash,
                                        'drawdown' => $drawdown,
                                        // 'journalr' => $journalR,
                                        'currency' => $currecyType,
                                        // 'user' => $user,
                                        // 'slips' => $slips,
                                        'company_branch'=>$company_branch,
                                        'projects_row'=>$projects_row,
                                        'unit_type'=>$unit_type,
                                        'unit'=>$unit
                                    ]);
}

public function printItemBECash($transaction_id,$description){

    $bcash = BCashTransaction::select('bcash_transaction.id','bcash_transaction.receipt_no','bcash_transaction.loan_id','bcash_transaction.client_id',
    'bcash_transaction.client_name','bcash_transaction.user_id','bcash_transaction.issue_date','bcash_line_item.amount as sub_total',
    'bcash_transaction.penalty','bcash_transaction.water','bcash_transaction.electric','bcash_line_item.amount as grand_total','bcash_line_item.description')
    ->join('bcash_line_item', 'bcash_transaction.id', '=', 'bcash_line_item.bcash_id')
    ->where('bcash_transaction.id', '=', $transaction_id)
    ->where('bcash_line_item.title', '=', $description)
    ->first();
    if(empty($bcash)){
        return redirect()->back();
    }
        $B0 = new Loan();
        $loan = $B0->selectRaw('
        tb_loans.id,
        tb_loans.contract_id,
        tb_loans.company_branch_id,
        tb_loans.start_date,
        tb_loans.loan_type,
        tb_loans.penalty_rate_type,
        tb_loans.loan_amount,
        tb_loans.original_amount,
        tb_loans.interest_rate,
        tb_loans.loan_account_id,
        tb_loans.drawdown_acc,
        tb_loans.loan_penalty_type,
        tb_loans.penalty_rate1,
        tb_loans.penalty_rate2,
        tb_loans.penalty_period1,
        tb_loans.penalty_period2,
        tb_loans.unit_sale_price,
        tb_loans.amount_discount_payment_option,
        tb_loans.discount_payment_option,
        tb_loans.discount_other,
        tb_loans.down_payment_value,
        tb_loans.status,
        tb_loans.loan_duration,
        tb_loans.submitted_on,
        tb_loans.disburse_date,
        tb_loans.rejected_date,
        tb_loans.client_id,
        tb_loans.contract_date,
        tb_loans.created_at,
        tb_loans.updated_at,
        tb_units.code'
    )->leftJoin('units','units.id','=','loans.unit_id')
    ->where('loans.id', '=', $bcash->loan_id)
    ->first();

    $drawdown = DrawdownAccounts::where('id', $loan->loan_account_id)->first();
    $unit = Unit::find($drawdown->unit_id);
    $unit_type = UnitType::find($unit->unit_type_id);
    $projects_row = Project::find($unit_type->project_id);
    $company_branch = CompanyBranch::find($projects_row->company_id);
    $journalR = JournalRequiry::where('id', @$slips->jr_id)->first();
    $currecyType = Currency::where('id', $drawdown->currency)->first();

    return $this->view('bcash.receipt', [
                                        'loan' => $loan,
                                        'becash' => $bcash,
                                        'drawdown' => $drawdown,
                                        // 'journalr' => $journalR,
                                        'currency' => $currecyType,
                                        // 'user' => $user,
                                        // 'slips' => $slips,
                                        'company_branch'=>$company_branch,
                                        'projects_row'=>$projects_row,
                                        'unit_type'=>$unit_type,
                                        'unit'=>$unit
                                    ]);
}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLoanDetail()
    {
        $query_arr = array('user_id' => Auth::user()->id, 'company_branch_id' => Auth::user()->branch_id, 'role_id' => Auth::user()->role_id);
        $B0 = new Loan();
        $loan = $B0->selectRaw('
                tb_loans.id,
                tb_loans.contract_id,
                tb_loans.company_branch_id,
                tb_loans.start_date,
                tb_loans.loan_type,
                tb_loans.penalty_rate_type,
                tb_loans.loan_amount,
                tb_loans.original_amount,
                tb_loans.interest_rate,
                tb_loans.loan_account_id,
                tb_loans.drawdown_acc,
                tb_loans.loan_penalty_type,
                tb_loans.penalty_rate1,
                tb_loans.penalty_rate2,
                tb_loans.penalty_period1,
                tb_loans.penalty_period2,
                tb_loans.unit_sale_price,
                tb_loans.amount_discount_payment_option,
                tb_loans.discount_payment_option,
                tb_loans.discount_other,
                tb_loans.down_payment_value,
                tb_loans.status,
                tb_loans.loan_duration,
                tb_loans.submitted_on,
                tb_loans.disburse_date,
                tb_loans.rejected_date,
                tb_loans.client_id,
                tb_loans.contract_date,
                tb_loans.created_at,
                tb_loans.updated_at,
                tb_clients.client_name,
                tb_clients.client_type,
                tb_projects.short_code,
                tb_unit_types.name,
                tb_units.code,
                tb_loans.settlement_date,
                tb_loans.rate_type,
                tb_currency.code AS currency_code,
                tb_repayment_schedule.schedule_date
            '
            )->with(['approval' => function ($query) {
                $query->select('id', 'loan_id', 'approval_date');
            }, 
            'client_loan_account',
            'payoff']
        )
        ->join('client_loan_accounts','loans.loan_account_id','=','client_loan_accounts.id')
        ->join('currency','client_loan_accounts.currency','=','currency.id')
        ->leftJoin('last_repay_schedule as lrs', 'lrs.loan_id', '=', 'loans.id')
        ->leftJoin('repayment_schedule', 'lrs.id', '=', 'repayment_schedule.id')
        ->leftJoin('clients', 'clients.id', '=', 'loans.client_id')
        ->leftJoin('projects','projects.id','=','loans.project_id')
        ->leftJoin('unit_types','unit_types.id','=','loans.unit_type_id')
        ->leftJoin('units','units.id','=','loans.unit_id');
        $loan = $loan->where('workflow_status', 'approve');
        $loan =  $loan->whereIn('loans.status', [1,2,3,7,8,9,10]);


        $contract_id = null;
        $query_url = [];
        if (Request::has('contract_id')) {
            $contract_id = Request::input('contract_id');
            $loan = $loan->where('contract_id', '=', $contract_id);
            $query_url['contract_id'] = $contract_id;
        }
        if (Request::has('client_name')) {
            $client_name = Request::input('client_name');
            $loan = $loan->where('clients.client_name', 'LIKE', '%' . $client_name . '%');
            $query_url['client_name'] = $client_name;
        }
        $status = '';
        if (Request::has('status')) {
            $status = Request::input('status');
            $loan = $loan->where('loans.status', '=', $status);
            $query_url['status'] = $status;
        }
        $project_id = null;
        if(Request::has('project_id')){
            $project_id = Request::input('project_id');
            $project = Project::find($project_id);
            $loan = $loan->where('loans.project_id',$project_id);
        }
        $unit_type_id = null;
        if(Request::has('unit_type_id')){
            $unit_type_id = Request::input('unit_type_id');
            $unit_type = UnitType::find($unit_type_id);
            $loan = $loan->where('loans.unit_type_id',$unit_type_id);
        }
        $unit_id = null;
        if(Request::has('unit_id')){
            $unit_id = Request::input('unit_id');
            $unit = Unit::find($unit_id);
            $loan = $loan->where('loans.unit_id',$unit_id);
        }
        // $now = Date('Y-m-d');
        $contract_date = Request::input('contract_date');
        $date = Request::input('date');
        if(Request::has('contract_date')){
            $loan = $loan->where('loans.contract_date',$contract_date);
        }
        if(Request::has('date')){
            $loan = $loan->whereDate('loans.created_at','=',$date);
        }

        $loan = $loan->get();
        $dpDate = date('Y-m-d');
    
        $data['search_results'] =  $loan->first();
        $sch_repay_arr = get_auto_repay_array_downpayment($data['search_results'], $dpDate);
        $sch_repay_down_loan_arr = $sch_repay_arr;

        $loan_account = ClientLoanAccounts::find($data['search_results']->loan_account_id);
        $coa = CoaCategory::find($loan_account->coa_id);
        $coa_dd = DrawdownAccounts::select('balance', 'currency', 'coa_id', 'client_id')->with('coa')->where('client_id', '=', $data['search_results']->client_id)->where('currency', '=', $loan_account->currency)->where('account_no', $data['search_results']->drawdown_acc)->first();
        $coa_air = CoaCategory::find($loan_account->air_id);
        $coa_int_inc = CoaCategory::select('id', 'account_code', 'name')->where('id', $loan_account->int_inc_id)->first();
        $coa_ofc = CoaCategory::select('id', 'account_code', 'name')->where('type', 6)->where('currency', $loan_account->currency)->where('name', 'Inc-Other Fees and Commissions from Loans and Leasing')->first();
        $coa_ap = CoaCategory::where('name', 'like', '%Accounts Payable-Other%')
            ->where('currency', '=',  $loan_account->currency)->first();
        $coa_pnt = CoaCategory::select('id', 'account_code', 'name')->where('type', 6)->where('currency', $loan_account->currency)->where('name', 'Inc-Fine and Penalty on Repayment of Loans and leasing')->first();

        $dpDate = date('Y-m-d');
        $penalty_arr = LoanCalculate::getTotalPenalty($data['search_results'], $dpDate);
        $data["overdue"] = $penalty_arr[2];
        $data["last_pay_date"] = $penalty_arr[3];

        $last_pay_date = strtotime($penalty_arr[3]);
        $next_sch_date = date("Y-m-d", strtotime("+1 month", $last_pay_date));
        $next_schedule = RepaymentSchedule::where('loan_id',intval($data['search_results']->id))->where('schedule_date',$next_sch_date)->where('type','loan')->first();

        $data["next_sch_date"] = $penalty_arr[5];
        $branch = CompanyBranch::find($data['search_results']->company_branch_id);
        $data['branch_name'] = $branch->branch_name;
        $data['branch_code'] = $branch->branch_code;
        $data['currency'] = $loan_account->currency;
        $data['coa'] = $coa;
        $data['coa_dd'] = $coa_dd->coa;
        $data['coa_air'] = $coa_air;
        $data['coa_ofc'] = $coa_ofc;
        $data['coa_pnt'] = $coa_pnt;
        $data['coa_int_inc'] = $coa_int_inc;
        $data['coa_ap'] = $coa_ap;
        $data['drawdown_bal'] = $coa_dd->balance;

        $drawdown_acc = DrawdownAccounts::with('coa')->where('client_id', $data['search_results']->client_id)
        ->where('currency', $loan_account->currency)->first();
        $coa_2 = CoaCategory::select('id', 'account_code', 'name')->where('type', 6)->where('currency', $loan_account->currency)->where('name', 'like', '%Fees and Commissions on Loans')->first();
        $data['coa_1'] = $drawdown_acc->coa;
        $data['coa_2'] = $coa_2;

        $downpayment_output = array(
            'debit_down' => $data['search_results']->loan_amount,
            'credit_down' => $data['search_results']->loan_amount,
            'parent_debit_down' =>  $data['coa_dd']->id,
            'parent_credit_down' => $data['coa']->id,
            'parent_debit_label_down' => $data['coa_dd']->name . ' (' . $branch->branch_code . $data['coa_dd']->account_code . ')',
            'parent_credit_down_label' => $data['coa']->name . ' (' .  $branch->branch_code .  $data['coa']->account_code . ')',
            'd_description_down'=>"Principal Repayment - ". $loan_account->account_name . " - ". $data['search_results']->contract_id,
            'c_description_down'=>"Principal Repayment - ". $loan_account->account_name . " - ". $data['search_results']->contract_id,
            'description_down'=>'',
        );

        $act_principal_output = array(
            'debit' => $data['search_results']->loan_amount,
            'credit' => $data['search_results']->loan_amount,
            'parent_debit' => $data['coa_dd']->id,
            'parent_credit' => $data['coa_dd']->id,
            'parent_debit_label' => $data['coa_dd']->name . ' (' . $branch->branch_code . $data['coa_dd']->account_code . ')',
            'parent_credit_label' => $data['coa']->name . ' (' . $branch->branch_code .  $data['coa']->account_code . ')',
            'd_description'=>"Principal Repayment - ".  $loan_account->account_name . " - ". $data['search_results']->contract_id,
            'c_description'=>"Principal Repayment - ".  $loan_account->account_name . " - ". $data['search_results']->contract_id,
            'description'=>'',
        );

        $act_air_interest_output = array(
            'debit' => $data['search_results']->loan_amount,
            'credit' => $data['search_results']->loan_amount,
            'parent_debit' => $data['coa_dd']->id,
            'parent_credit' =>  $data['coa_air']->id,
            'parent_debit_label' => $data['coa_dd']->name . ' (' . $branch->branch_code . $data['coa_dd']->account_code . ')',
            'parent_credit_label' => $data['coa_air']->name . ' (' . $branch->branch_code .  $data['coa_air']->account_code . ')',
            'd_description'=>"Interest Repayment - ".  $loan_account->account_name . " - ". $data['search_results']->contract_id,
            'c_description'=>"Interest Repayment - ".  $loan_account->account_name . " - ". $data['search_results']->contract_id,
            'description'=>'',
        );  
        

        $act_interest_output = array(
            'debit' => $data['search_results']->loan_amount,
            'credit' => $data['search_results']->loan_amount,
            'parent_debit' => $coa_dd->id,
            'parent_credit' => $coa_int_inc->id,
            'parent_debit_label' => $coa_dd->name . ' (' . $branch->branch_code . $coa_dd->account_code . ')',
            'parent_credit_label' => $coa_int_inc->name . ' (' .$branch->branch_code . $coa_int_inc->account_code . ')',
            'd_description'=>'',
            'c_description'=>'',
            'description'=>'',
        );
        $act_penalty = array(
            'debit' => $data['search_results']->loan_amount,
            'credit' => $data['search_results']->loan_amount,
            'parent_debit' => $data['coa_dd']->id,
            'parent_credit' => $coa_pnt->id,
            'parent_debit_label' => $data['coa_dd']->name . ' (' . $branch->branch_code . $data['coa_dd']->account_code . ')',
            'parent_credit_label' => $coa_pnt->name . ' (' . $branch->branch_code . $coa_pnt->account_code . ')',
            'd_description'=> "Penalty Repayment - ". $loan_account->account_name . " - ". $data['search_results']->contract_id,
            'c_description'=>"Penalty Repayment - ". $loan_account->account_name . " - ". $data['search_results']->contract_id,
            'description'=>'',
        );

        $act_fee = array(
            'debit' => $data['search_results']->loan_amount,
            'credit' => $data['search_results']->loan_amount,
            'parent_debit' => $coa_dd->id,
            'parent_credit' => $coa_ap->id,
            'parent_debit_label' => $coa_dd->name . ' (' . $branch->branch_code . $coa_dd->account_code . ')',
            'parent_credit_label' => $coa_ap->name . ' (' . $branch->branch_code . $coa_ap->account_code . ')',
            'd_description'=> "Other Fee Charge Repayment - ". $loan_account->account_name . " - ". $data['search_results']->contract_id,
            'c_description'=>"Other Fee Charge Repayment - ". $loan_account->account_name . " - ". $data['search_results']->contract_id,
            'description'=>'',
        );

        $add_loan_charge = array(
            'contract_id' => $data['search_results']->contract_id,
            'branch_label' => $branch->branch_name,
            'branch' => $data['search_results']->company_branch_id,
            'debit' => '',
            'credit' => '',
            'currency' => $loan_account->currency,
            'currency_label' => $data['search_results']->currency_code,
            'entry_date' => date('Y-m-d H:i:s'),
            'parent_debit' =>  $data['coa_1']->id,
            'parent_credit' =>$data['coa_2']->id,
            'parent_debit_label' => $data['coa_1']->name . ' (' . $branch->branch_code .  $data['coa_1']->account_code . ')',
            'parent_credit_label' => $data['coa_2']->name . ' (' . $branch->branch_code . $data['coa_2']->account_code . ')',
            'branch_code' => $branch->branch_code,
            'description'=>'',
    );
        

        return [
            'loans' => $loan,
            'project_id' => $project_id,'project' => $project, 
            'unit_type_id' => $unit_type_id,'unit_type' => $unit_type,
            'unit_id' => $unit_id,'unit' => $unit,
            'client_name'=>$client_name,'sch_repay_down_loan_arr'=>$sch_repay_down_loan_arr,
            'downpayment_output'=>$downpayment_output,
            'act_principal_output'=>$act_principal_output,
            'act_air_interest_output'=>$act_air_interest_output,
            'act_interest_output'=>$act_interest_output,
            'act_penalty'=>$act_penalty,
            'act_fee'=>$act_fee,
            'overdue'=>$data["overdue"],
            'last_pay_date'=>$data["last_pay_date"],
            'next_sch_date'=>$data["next_sch_date"],
            'branch_name' => $data['branch_name'],
            'branch_code' =>  $data['branch_code'],
            'add_loan_charge'=>$add_loan_charge,
            'next_schedule'=>$next_schedule
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addBcash($loan_id = 0)
    {
        // dd(Request::input());
        // exit();
        DB::beginTransaction();
        try {
            $record_date = Request::input('till_date')!=""?date_format(date_create(Request::input('till_date')),"Y-m-d H:i:s"):date("Y-m-d H:i:s");
            if($record_date === 'undefined'){
                $record_date = date("Y-m-d H:i:s");
            }
            // if(floatval(Request::input('loan_status')<3)){
            //      if (!Request::has('Deposit')) {
            //         Session::flash('Error', 'Loan is not yet disbursement! Available only  type Deposit.');
            //     $datas['error'] = 1;
            //     $datas['message'] = 'Loan is not yet disbursement! Available only  type Deposit.';
            
            //     return response()->json($datas);
            //      }
            // }
          
            

            $loan_id = Request::input('loan_id');
            $bCashTransaction = new BCashTransaction;            
            $bCashTransaction->receipt_no = 'RP'.date('YmdHis');
            $bCashTransaction->loan_id = $loan_id;
            $bCashTransaction->client_id= Request::input('client_id');
            $bCashTransaction->client_name=Request::input('client_name');
            $bCashTransaction->issue_date =  $record_date;
            $bCashTransaction->sub_total =  floatval(Request::input('sub_total'));
            $bCashTransaction->penalty =  floatval(Request::input('penalty'));
            $bCashTransaction->water =  floatval(Request::input('water'));
            $bCashTransaction->electric =  floatval(Request::input('electric'));
            $bCashTransaction->grand_total =  floatval(Request::input('grand_total'));
            $bCashTransaction->user_id = Auth::user()->id;
            $bCashTransaction->description = Request::input('descr');
            $bCashTransaction->drawdown_acc_id = Request::input('drawdown_acc_id');
             
            
            if($bCashTransaction->save()){
                    $lineitems=array();
                if (Request::has('Loan_Installment')) { 
                    app('App\Http\Controllers\TellerController')->postBCashDeposit($bCashTransaction->receipt_no,Request::input('Loan_Installment'),Request::input('note_Loan_Installment'),'Loan Installment','Real Estate');
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Loan Installment',
                        'description' => Request::input('note_Loan_Installment'),
                        'amount'=>Request::input('Loan_Installment')
                    ));
                }
                if (Request::has('Deposit')) {
                    app('App\Http\Controllers\TellerController')->postBCashDeposit($bCashTransaction->receipt_no,Request::input('Deposit'),Request::input('note_Deposit'),'Deposit','Real Estate');
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Deposit',
                        'description' =>  Request::input('note_Deposit'),
                        'amount'=>Request::input('Deposit')
                    ));
                }
                if (Request::has('Down_Payment')) {
                    app('App\Http\Controllers\TellerController')->postBCashDeposit($bCashTransaction->receipt_no,Request::input('Down_Payment'),Request::input('note_Down_Payment'),'Down-Payment','Real Estate');
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Down-Payment',
                        'description' =>Request::input('note_Down_Payment'),
                        'amount'=>Request::input('Down_Payment')
                    ));
                }
                if (Request::has('Pay_Off')) {
                    app('App\Http\Controllers\TellerController')->postBCashDeposit($bCashTransaction->receipt_no,Request::input('Pay_Off'),Request::input('note_Pay_Off'),'Pay-Off','Real Estate');
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Pay-Off',
                        'description' => Request::input('note_Pay_Off'),
                        'amount'=>Request::input('Pay_Off')
                    ));
                }
                if (Request::has('Penalty_Fee')) {
                    app('App\Http\Controllers\TellerController')->postBCashDeposit($bCashTransaction->receipt_no,Request::input('Penalty_Fee'),Request::input('note_Penalty_Fee'),'Penalty Fee','Real Estate');                
                    // app('App\Http\Controllers\RepaymentController')->postBCashLoanPenaltyFee(Request::input('Penalty_Fee'),'Penalty Fee');
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Penalty-Fee',
                        'description' => Request::input('note_Penalty_Fee'), 
                        'amount'=>Request::input('Penalty_Fee')
                    ));
                    
                }
                if (Request::has('Maintenance_Fee')) {         
                    $be_cash_item_note='Maintenance Fee_'.date('YmdHis');    
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Maintenance_Fee'),$this->getChargeType('Maintenance_Fee'),Request::input('note_Maintenance_Fee'),$bCashTransaction->id,'Property','Maintenance Fee', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Maintenance Fee_'.date('YmdHis'),
                        'description' => Request::input('note_Maintenance_Fee'),
                        'amount'=>Request::input('Maintenance_Fee')
                    ));
                    
                }
                if (Request::has('Admin_Fee_Sub_Sale')) {    
                    $be_cash_item_note='Admin Fee Sub Sale_'.date('YmdHis');          
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Admin_Fee_Sub_Sale'),$this->getChargeType('Admin_Fee_Sub_Sale'),Request::input('note_Admin_Fee_Sub_Sale'),$bCashTransaction->id,'Real Estate','Admin Fee Sub Sale', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Admin Fee Sub Sale_'.date('YmdHis'),
                        'description' => Request::input('note_Admin_Fee_Sub_Sale'),
                        'amount'=>Request::input('Admin_Fee_Sub_Sale')
                    ));                    
                }
                if (Request::has('Admin_Fee_Owner_Ship')) { 
                    $be_cash_item_note='Admin Fee Owner Ship_'.date('YmdHis');             
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Admin_Fee_Owner_Ship'),$this->getChargeType('Admin_Fee_Owner_Ship'),Request::input('note_Admin_Fee_Owner_Ship'),$bCashTransaction->id,'Real Estate','Admin Fee Owner Ship', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Admin Fee Owner Ship_'.date('YmdHis'),
                        'description' => Request::input('note_Admin_Fee_Owner_Ship'),
                        'amount'=>Request::input('Admin_Fee_Owner_Ship')
                    ));
                    
                }
                if (Request::has('Admin_Fee_Reschdule')) { 
                    $be_cash_item_note='Admin Fee Reschdule_'.date('YmdHis');               
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Admin_Fee_Reschdule'),$this->getChargeType('Admin_Fee_Reschdule'),Request::input('note_Admin_Fee_Reschdule'),$bCashTransaction->id,'Real Estate','Admin Fee Reschdule', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Admin Fee Reschdule_'.date('YmdHis'),
                        'description' => Request::input('note_Admin_Fee_Reschdule'),
                        'amount'=>Request::input('Admin_Fee_Reschdule')
                    ));
                    
                }
                if (Request::has('Admin_Fee_Change_Unit')) {
                    $be_cash_item_note='Admin Fee Change Unit_'.date('YmdHis');                
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Admin_Fee_Change_Unit'),$this->getChargeType('Admin_Fee_Change_Unit'),Request::input('note_Admin_Fee_Change_Unit'),$bCashTransaction->id,'Real Estate','Admin Fee Change Unit', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Admin Fee Change Unit_'.date('YmdHis'),
                        'description' => Request::input('note_Admin_Fee_Change_Unit'),
                        'amount'=>Request::input('Admin_Fee_Change_Unit')
                    ));
                    
                }

                if (Request::has('Tittle_Transfer_Fee')) {  
                    $be_cash_item_note='Tittle Transfer Fee_'.date('YmdHis');              
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Tittle_Transfer_Fee'),$this->getChargeType('Tittle_Transfer_Fee'),Request::input('note_Tittle_Transfer_Fee'),$bCashTransaction->id,'Real Estate','Tittle Transfer Fee', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Tittle Transfer Fee_'.date('YmdHis'),
                        'description' =>Request::input('note_Tittle_Transfer_Fee'),
                        'amount'=>Request::input('Tittle_Transfer_Fee')
                    ));
                    
                }
                if (Request::has('Stamp_Tax_Fee')) {    
                    $be_cash_item_note='Stamp Tax Fee_'.date('YmdHis');          
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Stamp_Tax_Fee'),$this->getChargeType('Stamp_Tax_Fee'),Request::input('note_Stamp_Tax_Fee'),$bCashTransaction->id,'Real Estate','Stamp Tax Fee', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Stamp Tax Fee_'.date('YmdHis'),
                        'description' => Request::input('note_Stamp_Tax_Fee'),
                        'amount'=>Request::input('Stamp_Tax_Fee')
                    ));
                    
                }

                if (Request::has('Renovation_Fee')) {
                    $be_cash_item_note='Renovation Fee_'.date('YmdHis');             
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Renovation_Fee'),$this->getChargeType('Renovation_Fee'),Request::input('note_Renovation_Fee'),$bCashTransaction->id,'Real Estate','Renovation Fee', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Renovation Fee_'.date('YmdHis'),
                        'description' => Request::input('note_Renovation_Fee'),
                        'amount'=>Request::input('Renovation_Fee')
                    ));
                    
                }

                if (Request::has('Water_Fee')) { 
                    $be_cash_item_note='Water Fee_'.date('YmdHis');               
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Water_Fee'),$this->getChargeType('Water_Fee'),Request::input('note_Water_Fee'),$bCashTransaction->id,'Property','Water Fee', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Water Fee_'.date('YmdHis'),
                        'description' => Request::input('note_Water_Fee'),
                        'amount'=>Request::input('Water_Fee')
                    ));
                    
                }
                if (Request::has('Rental_Fee')) {   
                    $be_cash_item_note='Rental Fee_'.date('YmdHis');         
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Rental_Fee'),$this->getChargeType('Rental_Fee'),Request::input('note_Rental_Fee'),$bCashTransaction->id,'Property','Rental Fee', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Rental Fee_'.date('YmdHis'),
                        'description' => Request::input('note_Rental_Fee'),
                        'amount'=>Request::input('Rental_Fee')
                    ));
                    
                }
                if (Request::has('Entrance_Card_Fee')) {
                    $be_cash_item_note='Entrance Card Fee_'.date('YmdHis');              
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Entrance_Card_Fee'),$this->getChargeType('Entrance_Card_Fee'), Request::input('note_Entrance_Card_Fee'),$bCashTransaction->id,'Property','Entrance Card Fee', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Entrance Card Fee_'.date('YmdHis'),
                        'description' =>  Request::input('note_Entrance_Card_Fee'),
                        'amount'=>Request::input('Entrance_Card_Fee')
                    ));
                    
                }

                if (Request::has('Internet_Service_Fee')) {  
                    $be_cash_item_note='Internet Service Fee_'.date('YmdHis');             
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Internet_Service_Fee'),$this->getChargeType('Internet_Service_Fee'), Request::input('note_Internet_Service_Fee'),$bCashTransaction->id,'IIP','Internet Service Fee', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Internet Service Fee_'.date('YmdHis'),
                        'description' =>  Request::input('note_Internet_Service_Fee'),
                        'amount'=>Request::input('Internet_Service_Fee')
                    ));
                    
                }

                
                if (Request::has('CCTV_Fee')) {       
                    $be_cash_item_note='CCTV Fee_'.date('YmdHis');      
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('CCTV_Fee'),$this->getChargeType('CCTV_Fee'),Request::input('note_CCTV_Fee'),$bCashTransaction->id,'IIP','CCTV Fee', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'CCTV Fee_'.date('YmdHis'),
                        'description' =>  Request::input('note_CCTV_Fee'),
                        'amount'=>Request::input('CCTV_Fee')
                    ));
                    
                }
                if (Request::has('Sport_Club_Fee')) {  
                    $be_cash_item_note='Sport Club Fee_'.date('YmdHis');            
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Sport_Club_Fee'),$this->getChargeType('Sport_Club_Fee'),Request::input('note_Sport_Club_Fee'),$bCashTransaction->id,'Property','Sport Club Fee', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Sport Club Fee_'.date('YmdHis'),
                        'description' =>  Request::input('note_Sport_Club_Fee'),
                        'amount'=>Request::input('Sport_Club_Fee')
                    ));
                    
                }
                if (Request::has('Electricity_Fee')) { 
                    $be_cash_item_note='Electricity Fee_'.date('YmdHis');              
                    app('App\Http\Controllers\LoanController')->postBCashLoanCharge($record_date,Request::input('Electricity_Fee'),$this->getChargeType('Electricity_Fee'),Request::input('note_Electricity_Fee'),$bCashTransaction->id,'Property','Electricity Fee', $bCashTransaction->receipt_no,$be_cash_item_note);
                    array_push($lineitems,array(
                        'bcash_id' => $bCashTransaction->id,
                        'title' => 'Electricity Fee_'.date('YmdHis'),
                        'description' =>  Request::input('note_Electricity_Fee'),
                        'amount'=>Request::input('Electricity_Fee')
                    ));
                    
                }
  
                BCashLineItem::insert($lineitems);
                $bcash = BCashTransaction::select('bcash_transaction.*')
                ->where('bcash_transaction.id', '=', $bCashTransaction->id)
                ->first();
                $datas['bcash'] = $bcash;


            }
            DB::commit();
            
            $B0 = new Loan();
            $loan = $B0->selectRaw('
            tb_loans.id,
            tb_loans.contract_id,
            tb_loans.company_branch_id,
            tb_loans.start_date,
            tb_loans.loan_type,
            tb_loans.penalty_rate_type,
            tb_loans.loan_amount,
            tb_loans.original_amount,
            tb_loans.interest_rate,
            tb_loans.loan_account_id,
            tb_loans.drawdown_acc,
            tb_loans.loan_penalty_type,
            tb_loans.penalty_rate1,
            tb_loans.penalty_rate2,
            tb_loans.penalty_period1,
            tb_loans.penalty_period2,
            tb_loans.unit_sale_price,
            tb_loans.amount_discount_payment_option,
            tb_loans.discount_payment_option,
            tb_loans.discount_other,
            tb_loans.down_payment_value,
            tb_loans.status,
            tb_loans.loan_duration,
            tb_loans.submitted_on,
            tb_loans.disburse_date,
            tb_loans.rejected_date,
            tb_loans.client_id,
            tb_loans.contract_date,
            tb_loans.created_at,
            tb_loans.updated_at,
            tb_units.code'
        )->leftJoin('units','units.id','=','loans.unit_id')
        ->where('loans.id', '=', $loan_id)
        ->first();


            $datas['success'] = 1;
            $datas['loan'] = $loan;
            
            return response()->json($datas);
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('message', 'Save not successfully');
            return redirect()->back();
        }
    }

    public function authorized($transaction_id = 0)
    {
        DB::beginTransaction();
        try {
            $till_transaction = TillTransaction::where('id', '=', $transaction_id)->first();
            if($till_transaction){
                $till_account = Teller::where('id', $till_transaction->till_account_id)->first();
                if($till_account){
                    $till_transaction->approve_status = 1;
                    if($till_transaction->methode=='Cash on Hand-Teller'){
                        $till_account->balance = $till_account->balance + floatval($till_transaction->cash_in);
                    }
                    $till_transaction->balance = $till_account->balance;
                    $till_transaction->save();
                    $till_account->save();
                    $transactionsPosting = TransactionsPosting::where('till_transaction_id', '=', $till_transaction->slips_id)->first();
                    if($transactionsPosting){
                        $transactionsPosting->status = 1;
                        $transactionsPosting->save();
                    }
                    
                }

            }

            DB::commit();
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('message', 'Save not successfully');
            return redirect()->back();
        }
    }

    public function rejceted($transaction_id = 0)
    {
        DB::beginTransaction();
        try {
            $till_transaction = TillTransaction::where('id', '=', $transaction_id)->first();
            if($till_transaction){
                $till_account = Teller::where('id', $till_transaction->till_account_id)->first();
                if($till_account){
                    $be_cash = BCashTransaction::where('id', $till_transaction->slips_id)->first();
                    if($be_cash){
                        $journal_require = JournalRequiry::where('receipt_no', '=', $be_cash->receipt_no)
                        ->where('user_id', '=', $till_transaction->operate_by)
                        ->first();  
                        
                    JournalDetail::where('journal_id', '=', $journal_require->id)->delete();
                    if($journal_require){
                        $journal_require->delete();
                    }
                    if($till_transaction){
                        $till_transaction->delete();
                    }
                    // if($notification){
                    //     $notification->delete();
                    // }
                    $this->userActivity(Auth::user()->id, $be_cash->id, 6, 'Fee Charge:'.$be_cash->description);                     

                    }
                }

            }

            DB::commit();
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('message', 'Save not successfully');
            return redirect()->back();
        }
    }

    private function getChargeType($type = '')
    {
        $charge_type=0;
        switch ($type) {
            case "Maintenance_Fee":
                $charge_type=4;
            case "Admin_Fee_Sub_Sale":
                $charge_type=10;
            case "Admin_Fee_Owner_Ship":
            case "Admin_Fee_Reschdule":
                $charge_type=2;
            case "Admin_Fee_Change_Unit":
                $charge_type=1;
            case "Tittle_Transfer_Fee":
                $charge_type=7;
            case "Stamp_Tax_Fee":
            case "Water_Fee":
                $charge_type=6;
            case "Sport_Club_Fee":
                $charge_type=9;
            case "Rental_Fee":
            case "Entrance_Card_Fee":
            case "Internet_Service_Fee":
            case "CCTV_Fee":
            case "Renovation_Fee":
                $charge_type=4;
              break;
            default:
            $charge_type=0;
          }
        return $charge_type;
    } 
}