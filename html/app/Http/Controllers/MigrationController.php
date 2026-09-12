<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 5/25/201000000
 * Time: 5:40 PM
 */

namespace App\Http\Controllers;
use App\Models\Accessible;
use App\Models\AdminPassword;
use App\Models\CompanyBranch;
use App\Models\FailedLogin;
use App\Models\Permission;

use App\Models\Client;
use App\Models\Loan;
use App\Models\DrawdownAccounts;
use App\Models\ClientLoanAccounts;
use App\Models\CoaCategory;
use App\Models\RepaymentSchedule;
use App\Models\LoanApproval;
use App\Models\MigrateDeposit;
use App\Models\MigrateInstallment;
use App\Models\JournalRequiry;
use App\Models\LoanPayments;
use App\Models\TransactionsRequiry;
use App\Models\LoanPaymentsDraft;


use App\Models\Role;
use App\Models\User;
use Faker\Provider\zh_CN\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Request;
//use App\Http\Requests\Request;
use Auth;
use Image;
class MigrationController extends Controller {

    public function __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function CreateDradownAcc()
    {
        $loans = Loan::select(
                    'loans.id',
                    'loans.unit_id',
                    'loans.company_branch_id',
                    'loans.client_id',
                    'loans.loan_account_id',
                    'loans.drawdown_acc',
                    'loans.contract_id',
                    'loans.project_id',
                    'loans.unit_type_id',
                    'loans.deposit_date',
                    'loans.loan_amount',
                    'loans.contract_date',
                    'loans.created_at',

                    'clients.client_name',
                    'company_branch.branch_code'
                )
                ->Join('clients','clients.id','=','loans.client_id')
                ->Join('company_branch','company_branch.id','=','loans.company_branch_id')
                ->where('loans.is_generate',0)
                ->where('loans.workflow_status','approve')
                ->whereNotNull('loans.client_id')
                ->where('loans.con_status','Release')
                ->limit(5000)
                ->get();
        $last = DrawdownAccounts::orderBy('id','DESC')->first()->id;
        if(!$last){
            $last=0;
        }
        $last +=1;
        foreach ($loans as $key => $row) {
            if($row->created_at == '0000-00-00 00:00:00' || $row->created_at == '-0001-11-30 00:00:00.000000'){
                $row->created_at = date('Y-m-d H:i:s');
            }
            $dd_account_no = $this->gennerateDrawdownAcc($row->branch_code);
            $account_no = $row->contract_id;
            $dd_parent_id = '3954';
            $branch_code = $row->branch_code;
            $new_loan_account = new ClientLoanAccounts();
            $data = [
                    'currency'      => 2,
                    'branch'        => $row->branch_code,
                    'project_id'    => $row->project_id,
                    'unit_type_id'  => $row->unit_type_id,
                    'unit_id'       => $row->unit_id,
                    'parent_id'     => '378',
                    'created_on'    => isset($row->created_at)?date('Y-m-d',strtotime($row->created_at)):date('Y-m-d') ,
                    'client_id'     => $row->client_id ,
                    'account_no'    => $row->contract_id ,
                    'account_name'  => $row->client_name ,
                    'acc_type'      => '0',
                    'guarantor'     => '[]'

                ];
                // $check_dd_acc = DrawdownAccounts::where('account_no',$dd_account_no)->first();
                $last = (int)filter_var($last, FILTER_SANITIZE_NUMBER_INT);

                $dd_account_no = $branch_code.$this->getClientNumber($last,7);

                // if($check_dd_acc){
                //     $last = isset(DrawdownAccounts::latest()->first()->id)?DrawdownAccounts::latest()->first()->id:null;
                //     var_dump($last);
                //     $dd_account_no = $this->gennerateDrawdownAcc($row->branch_code);
                //     var_dump($dd_account_no);
                // }

                foreach ($data as $key => $value) {
                    $new_loan_account->$key = $value;
                }
                if($new_loan_account->created_on == '0000-00-00' || $new_loan_account->created_on == '-0001-11-30'){
                    $new_loan_account->created_on = date('Y-m-d');
                }
                $new_loan_account->years = isset($new_loan_account->created_on)?date('Y',strtotime($new_loan_account->created_on)):date('Y');
                if($new_loan_account->years == '0000'){
                    $new_loan_account->years = date('Y');
                }
                $new_loan_account->acc_no = $row->contract_id;
                $static = config('static_data');
                $auto_authorize_drawdown = $static['auto_authorize_drawdown'];
                $auto_authorize_account = $static['auto_authorize_account'];
                $new_loan_account->balance = $row->loan_amount;

                $parent_name = CoaCategory::select('*')->where('id','=',$new_loan_account->parent_id)->first();

                $key_pre = $static['client_loan_account_prefix'];

                $new_loan_account->acc_key = substr($parent_name->name,8,50);
                $new_loan_account->prefix = $key_pre[2]; // "Std-"
                // COA
                $pre_prefix = $static['client_loan_account_pre_prefix']['coa']; //"";
                $new_coa = createLoanAccountCoaNewMigrate($new_loan_account, $pre_prefix);
                if($new_coa['new_flg'] == 0){ // If account already exist
                    // return redirect()->back()->with('msg', 'The Loan Account Exists!!');
                }
                $new_coa = $new_coa['coa'];
                $new_loan_account->coa_id = $new_coa->id;
                // AIR
                $pre_prefix = $static['client_loan_account_pre_prefix']['air']; //"Air-";
                $new_air = createLoanAccountCoaNewMigrate($new_loan_account, $pre_prefix);
                if($new_air['new_flg'] == 0){ // If account already exist
                    // return redirect()->back()->with('msg', 'The Loan Account Exists!!');
                }
                $new_air = $new_air['coa'];
                $new_loan_account->air_id = $new_air->id;

                // Interest Income
                $pre_prefix = $static['client_loan_account_pre_prefix']['interest']; //"Int-Inc-";
                $new_int = createLoanAccountCoaNewMigrate($new_loan_account, $pre_prefix);

                if($new_int['new_flg'] == 0){ // If account already exist
                    // return redirect()->back()->with('msg', 'The Loan Account Exists!!');
                }
                $new_int = $new_int['coa'];
                $new_loan_account->int_inc_id = $new_int->id;

                $new_loan_account->acc_type = ($data['acc_type'])?$data['acc_type']:'S';
                $new_loan_account->status = 0; // Inactive
                $new_loan_account->created_by = Auth::user()->id;

                if($new_loan_account->save()){
                    $this->userActivity(Auth::user()->id, $new_loan_account->id, 0, 'Create client loan account', Request::fullUrl());
                    $this->do_audit($new_loan_account->id, Auth::user()->id, '', 'client_loan_accounts', 0, 'add loan account');

                    //Drawdown Account
                    if($auto_authorize_account == 1){
                        $this->auto_authorize_account($new_loan_account->id);
                    }
                    if($new_loan_account->prefix == "Stand-L") {
                        $pre_prefix = "Drawdown Account Loan";
                    }else{
                        $pre_prefix = "Drawdown Account Lease";
                    }
                    // $acc_code = substr(Request::input('dd_account_no'), 4);
                    $acc_exist = CoaCategory::select('id', 'account_code')->where('account_code', '=', $acc_code)->first();
                    if(is_null($acc_exist)){

                        $new_dd_account = createLoanAccountCoaNewMigrate($new_loan_account, $pre_prefix, $dd_parent_id, $acc_code, $new_loan_account->account_name);
                        $dd_account = new DrawdownAccounts;
                        $dd_account->client_id = $new_loan_account->client_id;
                        $dd_account->client_loan_id = $new_loan_account->id;
                        $dd_account->account_no = $dd_account_no;
                        $dd_account->account_name = $new_loan_account->account_name;
                        $dd_account->currency = $new_loan_account->currency;
                        $dd_account->branch = $new_loan_account->branch;
                        $dd_account->parent_id = $dd_parent_id;
                        $dd_account->coa_id = $new_dd_account['coa']->id;
                        $dd_account->balance = 0;
                        $dd_account->created_on = $new_loan_account->created_on;
                        $dd_account->created_by = $new_loan_account->created_by;
                        $dd_account->project_id =  $data['project_id'];
                        $dd_account->unit_type_id =  $data['unit_type_id'];
                        $dd_account->unit_id =  $data['unit_id'];
                        $dd_account->status = 0; // Inactive
                        if($dd_account->save()){
                            // if($dd_account->unit_id){
                            //     $units = Unit::where('id',$dd_account->unit_id)->where('status','available')->first();
                            //     if($units){
                            //         $units->status = 'booking';
                            //         $units->save();
                            //     }
                            // }
                            $this->userActivity(Auth::user()->id, $dd_account->id, 0, 'Add drawdown account', Request::fullUrl());
                            $this->do_audit($dd_account->id, Auth::user()->id, '', 'drawdown_account', 0, 'add drawdown account');

                            if($auto_authorize_drawdown == 1){
                                $this->auto_authorize_drawdown($dd_account->id);
                            }
                        }
                        
                    }
                    $row->loan_account_id = $new_loan_account->id;
                    $row->drawdown_acc = $dd_account->account_no;
                    $loan->workflow_status = 'approve';
                    $row->is_generate = 1;
                    $row->save();
                    $last ++;
                }
        }
        dd($loans);
    }

    public function gennerateDrawdownAcc($branch_code = '01'){
        $last = isset(DrawdownAccounts::latest()->first()->id)?DrawdownAccounts::latest()->first()->id:null;
        if(!$last){
            $last=0;
        }
        $last = (int)filter_var($last, FILTER_SANITIZE_NUMBER_INT);
        $no = $last+1;
        $no = $branch_code.$this->getClientNumber($no,7);
        return $no;
    }

    public function UpdateLoanAmount()
    {
        // $loa_in_no = $this->loanInstallmentNo();
        $loans = Loan::select('loans.id','loans.loan_amount','repayment_schedule.beginning')
                ->Join('repayment_schedule','loans.id','=','repayment_schedule.loan_id')
                ->where('repayment_schedule.no',1)
                ->where('repayment_schedule.beginning','>',0)
                // ->whereIn('loans.loan_installment_no',$loa_in_no)
                ->where('loans.workflow_status','approve')
                // ->where('loans.loan_installment_no','F-01(FLAT)')
                ->get();
        foreach ($loans as $key => $row) {
            $row->loan_amount = $row->beginning;
            $row->save();

        }
        dd($loans);
    }

    public function UpdateLoanAccountBalance()
    {
        // $loa_in_no = $this->loanInstallmentNo();
        $loan_account = ClientLoanAccounts::select('client_loan_accounts.id','client_loan_accounts.balance','loans.loan_amount')
            ->Join('loans','client_loan_accounts.id','=','loans.loan_account_id')
            // ->whereIn('loans.loan_installment_no',$loa_in_no)
            ->where('loans.workflow_status','approve')
            // ->where('loans.loan_installment_no','F-01(FLAT)')
            ->get();
        foreach ($loan_account as $key => $row) {
            $row->balance = $row->loan_amount;
            $row->save();

        }
        dd($loan_account);
    }

    public function UpdateLoan()
    {
        // $loa_in_no = $this->loanInstallmentNo();
        $loans = Loan::select('loans.id','loans.client_id','clients.id AS clients_id')
                ->Join('clients','clients.cus_acc','=','loans.cus_no')
                // ->whereIn('loans.loan_installment_no',$loa_in_no)
                // ->where('loans.workflow_status','approve')
                ->get();
        foreach ($loans as $key => $row) {
                $row->client_id = $row->clients_id;
                $row->save();
        }
        dd($loans);
    }
    public function loanInstallmentNo()
    {
        $loa_in_no = ['B-61',
                    'B822(1)',
                    'C-03',
                    'C-13',
                    'C-14',
                    'C-21',
                    'C-26',
                    'C-36',
                    'D-241',
                    'D-466',
                    'D-481',
                    'F-11',
                    'F-118',
                    'F-135',
                    'F-159',
                    'F-175',
                    'F-313',
                    'F-325',
                    'F-360',
                    'F-371',
                    'F-400',
                    'F-404',
                    'H-128',
                    'H-230',
                    'H-294',
                    'H-295',
                    'H-349',
                    'H-353',
                    'H-392',
                    'H-498',
                    'J-001',
                    'J-042',
                    'J-045',
                    'J-067',
                    'J-112',
                    'J-187',
                    'J-221',
                    'J-222',
                    'J-286',
                    'KSL1R01155',
                    'KSV3R02009',
                    'L-109',
                    'L-110',
                    'L-119',
                    'L-120',
                    'L-146',
                    'L-38',
                    'M-34',
                    'M-371',
                    'M-71',
                    'MC-720',
                    'MF-338',
                    'MF-542',
                    'MP-601',
                    'MP-717',
                    'N-113 (1)',
                    'N-133',
                    'N-19',
                    'Q-05',
                    'Q-06',
                    'Q-18',
                    'Q-21',
                    'SHG-084',
                    'T-32',
                    'V-297',
                    'V-426A',
                    'VH2-098',
                    'VH2-106',
                    'VH2-163',
                    'VH2-191',
                    'VH2-200',
                    'VH2-201O',
                    'VH2-355',
                    'VH2-421',
                    'VH2-442',
                    'VH2A-079',
                    'VH2A-101',
                    'VH2A-159',
                    'VM-07',
                    'Y-36'];
        return $loa_in_no;
    }
    public function UpdateRepaymentSchedule()
    {
        // $loa_in_no = $this->loanInstallmentNo();
        $schedule = RepaymentSchedule::select('repayment_schedule.id','repayment_schedule.loan_installment_no','loans.id AS loans_id')
                                    ->Join('loans','loans.loan_installment_no','=','repayment_schedule.loan_installment_no')
                                    ->where('repayment_schedule.is_generate',0)
                                    // ->where('repayment_schedule.sch_status',1)
                                    ->where('loans.con_status','Release')
                                    // ->whereIn('loans.loan_installment_no',$loa_in_no)
                                    ->where('loans.workflow_status','approve')
                                    // ->where('loans.loan_installment_no','B-61')
                                    // ->groupBy('repayment_schedule.loan_installment_no')
                                    ->limit(1000000)
                                    ->get();
        foreach ($schedule as $key => $row) {
            // $loan = Loan::select('id','loan_installment_no')->where('id',$row->loans_id)->first();
            // if($loan){
            $row->loan_id = $row->loans_id;
            $row->is_generate = 1;
            $row->save();
                    // RepaymentSchedule::where('loan_installment_no',$row->loan_installment_no)->update([
                    //                         'loan_id'       => $row->loans_id,
                    //                         'is_generate'   => 1
                    //                         ]);
                    // $update->loan_id = $loan->id;
                    // $update->is_generate = 1;
                    // $update->save();
            // }
        }
        // return redirect('migration/update-loan-repayment-schedule');
        dd($schedule->count());
    }
    public function LoanPaymentToPaid()
    {
        $data = LoanPayments::where('repayment_owed','>',0)->where('repayment_owed','<',1)->where('status',0)->where('is_generate_to_paid',0)->limit(1000000)->get();

        foreach ($data as $row) {
            $repay_schedule = RepaymentSchedule::where('loan_id',$row->loan_id)->where('no',$row->payment_month)->where('status',0)->first();
            if($repay_schedule){
                $repay_schedule->status = 1;
                $repay_schedule->is_generate_to_paid = 1;
                $repay_schedule->save();
            }
            $row->is_generate_to_paid = 1;
            $row->status = 1;
            $row->save();
        }

        dd($data);
    }
    public function LoanApproval()
    {
        $loans = Loan::select(
                    'loans.id',
                    'loans.unit_id',
                    'loans.company_branch_id',
                    'loans.client_id',
                    'loans.loan_account_id',
                    'loans.drawdown_acc',
                    'loans.contract_id',
                    'loans.project_id',
                    'loans.unit_type_id',
                    'loans.deposit_date',
                    'loans.loan_amount',
                    'loans.contract_date',
                    'loans.created_at',

                    'clients.client_name',
                    'company_branch.branch_code'
                )
                ->Join('clients','clients.id','=','loans.client_id')
                ->Join('company_branch','company_branch.id','=','loans.company_branch_id')
                ->where('con_status','Release')
                ->where('workflow_status','approve')
                ->orderBy('loans.id','ASC')
                ->get();
            foreach ($loans as $key => $row) {
                if($row->created_at == '0000-00-00 00:00:00' || $row->created_at == '-0001-11-30 00:00:00.000000'){
                    $row->created_at = date('Y-m-d H:i:s');
                }
                if($row->created_at == '0000-00-00'){
                    $row->created_at = $row->deposit_date;
                }
                if($row->created_at == '0000-00-00 00:00:00'){
                    $row->created_at = $row->deposit_date;
                }
                if(date('Y-m-d',strtotime($row->created_at)) == '-0001-11-30'){
                    $row->created_at = $row->deposit_date;
                }
                $napproval = new LoanApproval();
                $napproval->loan_id = $row->id;
                $napproval->user_id = Auth::user()->id;
                $date = $row->created_at;
                $date = date('Y-m-d', strtotime($date));
                $napproval->approval_date = $date;
                $napproval->note = 'Migration';
                $napproval->created_at = date('Y-m-d H:i:s');
                $napproval->save();
            }
    }

    public function UpdateLoanUnit()
    {
        $loans = Loan::select('loans.*','units.id AS units_id','units.unit_type_id AS units_type_id','projects.id AS projects_id','projects.company_id AS companies_id')
                ->Join('units','units.code','=','loans.unit_code')
                ->Join('unit_types','unit_types.id','=','units.unit_type_id')
                ->Join('projects','projects.id','=','unit_types.project_id')
                ->get();
        foreach ($loans as $key => $row) {
            if($row->unit_code){
                    $row->unit_id = $row->units_id;
                    $row->unit_type_id = $row->units_type_id;
                    $row->project_id = $row->projects_id;
                    $row->company_branch_id = $row->companies_id;
                    $row->interest_rate = round(($row->interest_rate/12),3);
                    $row->workflow_status = 'approve';
                    $row->save();

            }else{
                $row->interest_rate = round(($row->interest_rate/12),3);
                $row->save();
            }
        }
        dd($loans);
    }
    public function GetMigrateDepositData()
    {
        return $this->view('migration.deposit');
    }
    public function PostMigrateDepositData()
    {

        $data = MigrateDeposit::select('migrate_deposit.*',
                                    'clients.client_name',
                                    'clients.id as clients_id',
                                    'drawdown_account.account_no',
                                    'drawdown_account.id AS drawdown_id',
                                    'drawdown_account.coa_id',
                                    'projects.company_id AS companies_id')
                                ->Join('units','migrate_deposit.varriant_code','=','units.code')
                                ->Join('drawdown_account','drawdown_account.unit_id','=','units.id')
                                ->Join('clients','clients.id','=','drawdown_account.client_id')
                                ->Join('projects','projects.id','=','drawdown_account.project_id')
                                ->groupBy('migrate_deposit.id')
                                ->where('migrate_deposit.is_generate',0)
                                ->where('migrate_deposit.varriant_code','VH2-278')
                                // ->where('migrate_deposit.post_date','<','2021-09-01')
                                ->where(function($query){
                                    $query->where('migrate_deposit.status','<>','Cancel')
                                            ->orWhereNull('migrate_deposit.status');
                                })
                                ->where(function($query){
                                    $query->where('migrate_deposit.document_type','<>','Invoice');
                                })
                                ->where('migrate_deposit.reversd',0)
                                // ->where('migrate_deposit.loan_no','F-298')
                                ->limit(5000)
                                ->get();
        $teller_coa_id = CoaCategory::where('name', 'Like', '%Cash on Hand-Teller%')->where('currency', 2)->first()->id;

        foreach ($data as $key => $row) {
            $record_date = $row->post_date;
            if($row->reversd == 0){
                $depos_ammount = ($row->amount) * -1;
                $drawDownAct = DrawdownAccounts::where('id', $row->drawdown_id)->first();
                $dd_balance = $drawDownAct->balance + floatval($depos_ammount);
                $drawDownAct->balance = $dd_balance;
                // var_dump($drawDownAct->coa_id);
                // $drawDownAct->save();
                if( $drawDownAct->save()){
                    $journal_arr = [];
                    array_push($journal_arr, [
                                            $teller_coa_id, 
                                            $depos_ammount, 
                                            $row->description,
                                            $drawDownAct->coa_id, 
                                            $depos_ammount, 
                                            $row->description,
                                            $row->description
                                        ]
                                    );
                    record_journal_no_trans(null, $record_date, $journal_arr, $row->companies_id, Auth::user()->id, 1,null,1);
                }
                $row->is_success = 1;
                // record_journal_no_trans(null, $record_date, $journal_arr, $this->_branch_id, $this->user_id, 1);
            }else{
                // $record_date = $row->post_date;
                // if($row->reversd == 0){
                //     $depos_ammount = $row->amount;
                //     $drawDownAct = DrawdownAccounts::where('id', $row->drawdown_id)->first();
                //     $dd_balance = $drawDownAct->balance - floatval($depos_ammount);
                //     $drawDownAct->balance = $dd_balance;
                //     // var_dump($drawDownAct->coa_id);
                //     // $drawDownAct->save();
                //     if( $drawDownAct->save()){
                //         $journal_arr = [];
                //         array_push($journal_arr, [
                //                                 $teller_coa_id, 
                //                                 $depos_ammount, 
                //                                 $row->description,
                //                                 $drawDownAct->coa_id, 
                //                                 $depos_ammount, 
                //                                 $row->description,
                //                                 $row->description
                //                             ]
                //                         );
                //     // var_dump($journal_arr);
                //         record_journal_no_trans(null, $record_date, $journal_arr, $row->companies_id, $this->user_id, 1,null,1);
                //     }
            }
            $row->is_generate = 1;
            $row->save();
        }
        if($data->count() > 0){
            $datas['success'] = 1;
            echo json_encode($datas);
        }

         // "till_date" => "2021/08/30 11:18:57"
         //  "from" => "4111"
         //  "loan_admin" => "6"
         //  "types" => "Cash on Hand-Teller"
         //  "drawdown_acc" => "010004111"
         //  "amount" => "12000"
         //  "descr" => "haha"
         //  "check_num" => "0"
         //  "bank_name" => "0"
         //  "submit" => "Submit"
         //  "client_id" => "3691"
         //  "id" => "4111"
         //  "users_id" => "6"
         //  "currency_id" => "2"
         //  "client_name" => "  អ៊ូ គឿន​"
         //  "description" => "haha"
         //  "balance" => "12000"
         //  "if_print" => "0"
         //  "not_id" => "issueTill"
    }
    public function GettMigrateInstallment()
    {
        return $this->view('migration.installment');
    }

    public function PostMigrateInstallment()
    {
        $data = MigrateInstallment::select('migrate_installment.*',
                                'clients.client_name',
                                'clients.id as clients_id',
                                'drawdown_account.account_no',
                                'drawdown_account.id AS drawdown_id',
                                'drawdown_account.coa_id',
                                'drawdown_account.account_name AS dr_account_name',
                                'projects.company_id AS companies_id')
                                ->Join('units','migrate_installment.varriant_code','=','units.code')
                                ->Join('drawdown_account','drawdown_account.unit_id','=','units.id')
                                ->Join('clients','clients.id','=','drawdown_account.client_id')
                                ->Join('projects','projects.id','=','drawdown_account.project_id')
                                ->groupBy('migrate_installment.id')
                                // ->where('migrate_installment.post_date','<','2021-09-01')
                                ->where('migrate_installment.is_generate',0)
                                ->where('migrate_installment.varriant_code','VH2-278')
                                // ->where('migrate_installment.is_success',0)
                                // ->where('migrate_installment.is_regenerate',0)
                                ->where(function($query){
                                    $query->where('migrate_installment.status','<>','Cancel')
                                            ->orWhereNull('migrate_installment.status');
                                })
                                ->where(function($query){
                                    $query->where('migrate_installment.document_type','<>','Invoice');
                                })

                                ->where('migrate_installment.reversd',0)
                                ->where('migrate_installment.payment_type','Installment')
                                ->whereNotNull('migrate_installment.loan_no')
                                // ->where('migrate_installment.varriant_code','VH1-237')
                                // ->where('migrate_installment.pmt_no','11')
                                ->limit(5000)
                                ->get();
        foreach ($data as $key => $row) {
            $user_id = Auth::user()->id;
            $loan = Loan::with('client_loan_account')
                            ->with('schedule')
                            ->with('payment')
                            ->with(['penalty_record' => function ($q) {
                                    $q->select('id', 'loan_id', 'record_date', 'owed_penalty')->orderBy('id', 'DESC');
                                }])
                            ->where('loans.workflow_status','approve')
                            // ->where('loans.loan_installment_no', $row->loan_no)->first();
                            ->where('loans.unit_code', $row->varriant_code)->first();
            if($loan && $loan->schedule){
                $loan_id = $loan->id;
                $coa_dd = DrawdownAccounts::select('id', 'balance', 'coa_id', 'client_id')
                                            ->with('coa')
                                            ->where('id', '=', $row->drawdown_id)->first();
                $coa_dd_update = $coa_dd;

                $branch = CompanyBranch::find($loan->company_branch_id);
                if($row->payment_type == 'Installment'){
                    $pay_amount = ($row->amount * -1);
                    $repay_date = date('Y-m-d',strtotime($row->post_date));
                    // $schedule = $loans->schedule->where('no',$row->pnt_no);
                    $repay_arr = get_auto_repay_array_migrate($loan,date('Y-m-d',strtotime($row->post_date)),$row->pmt_no);
                    $branch_code = $branch->branch_code;
                    $coa_dd = $coa_dd->coa;
                    if($repay_arr['loan']){
                        $loan_account = $loan->client_loan_account;
                        $coa = CoaCategory::find($loan_account->coa_id);
                        $coa_air = CoaCategory::find($loan_account->air_id);
                        $coa_int_inc = CoaCategory::select('id', 'account_code', 'name')->where('id', $loan_account->int_inc_id)->first();
                        $coa_ofc = CoaCategory::select('id', 'account_code', 'name')->where('type', '6')->where('currency', $draft ? $draft->currency : $loan_account->currency)
                                            ->where('name','like','%Inc-Other Fees and Commissions from Loans and Leasing%')->first();
                        $coa_ap = CoaCategory::where('name', 'like', '%Accounts Payable-Other%')
                            ->where('currency', '=', $draft ? $draft->currency : $loan_account->currency)->first();
                        $coa_pnt = CoaCategory::select('id', 'account_code', 'name')->where('type', 6)->where('currency', $draft ? $draft->currency : $loan_account->currency)->where('name', 'Inc-Fine and Penalty on Repayment of Loans and leasing')->first();

                        $pay_principal = isset($repay_arr['loan'][0]['principal'])?$repay_arr['loan'][0]['principal']:0;
                        $pay_interest = isset($repay_arr['loan'][0]['interest'])?$repay_arr['loan'][0]['interest']:0;
                        // var_dump($pay_amount.'===='.($pay_principal + $pay_interest));
                        if($pay_amount > 0){
                            $principal_to_pay = 0;
                            $interest_to_pay = 0;
                            if($pay_amount >= $pay_principal){
                                $principal_to_pay = $pay_principal;
                            }else{
                                $principal_to_pay = $pay_amount;
                            }

                            if($pay_amount >= $pay_interest){
                                $interest_to_pay = $pay_interest;
                            }else{
                                $interest_to_pay = $pay_amount;
                            }
                            $journal_arr = [];

                            $debit_arr = [];
                            $credit_arr = [];
                            $desc_arr = [];
                            // ===== Principal ========
                            if($principal_to_pay > 0){
                                $params = array(
                                    'debit' => $loan->loan_amount,
                                    'credit' => $loan->loan_amount,
                                    'parent_debit' => $coa_dd->id,
                                    'parent_credit' => $coa->id,
                                    'parent_debit_label' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                                    'parent_credit_label' => $coa->name . ' (' . $branch_code . $coa->account_code . ')',
                                    'd_description'=>($draft->d_description4 != "")?$draft->d_description4 : "Principal Repayment - ". $loan->client_loan_account->account_name . " - ". $loan->contract_id,
                                    'c_description'=>($draft->c_description4 != "")?$draft->c_description4 : "Principal Repayment - ". $loan->client_loan_account->account_name . " - ". $loan->contract_id,
                                    'description'=>$draft->description4,
                                );

                                $debit_arr[0]['coa'] = $coa_dd->id;
                                $debit_arr[0]['amount'] = floatval($principal_to_pay);
                                $debit_arr[0]['desc'] = $params['d_description'];
                                $credit_arr[0]['coa'] = $params['parent_credit'];
                                $credit_arr[0]['amount'] = floatval($principal_to_pay);
                                $credit_arr[0]['desc'] = $params['c_description'];
                                $desc_arr[0]['desc'] = 'Migration';

                                array_push($journal_arr, [
                                                            $params['parent_debit'],
                                                            $principal_to_pay,
                                                            $params['d_description'],
                                                            $params['parent_credit'],
                                                            $principal_to_pay,
                                                            $params['c_description'],
                                                            $params['description'],
                                                        ]);

                            }

                            if($interest_to_pay > 0){
                                $params = array(
                                    'debit' => $loan->loan_amount,
                                    'credit' => $loan->loan_amount,
                                    'parent_debit' => $coa_dd->id,
                                    'parent_credit' => $coa_int_inc->id,
                                    'parent_debit_label' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                                    'parent_credit_label' => $coa_int_inc->name . ' (' . $branch_code . $coa_int_inc->account_code . ')',
                                    'd_description'=>$draft->d_description0,
                                    'c_description'=>$draft->c_description0,
                                    'description'=>$draft->description0,
                                );

                                $debit_arr[0]['coa'] = $coa_dd->id;
                                $debit_arr[0]['amount'] = floatval($interest_to_pay);
                                $debit_arr[0]['desc'] = $params['d_description'];
                                $credit_arr[0]['coa'] = $params['parent_credit'];
                                $credit_arr[0]['amount'] = floatval($interest_to_pay);
                                $credit_arr[0]['desc'] = $params['c_description'];
                                $desc_arr[0]['desc'] = 'Migration';
                                array_push($journal_arr, [
                                                            $params['parent_debit'],
                                                            $interest_to_pay,
                                                            $params['d_description'],
                                                            $params['parent_credit'],
                                                            $interest_to_pay,
                                                            $params['c_description'],
                                                            $params['description'],
                                                        ]);
                            }
                            $principal_data = [
                                'act_interest'  => $interest_to_pay,
                                'act_principal' => $principal_to_pay,
                                'act_fee'       => 0,
                                'act_other_fee' => 0,
                                'act_penalty'   => 0,
                                'act_total'     => ($principal_to_pay + $interest_to_pay),
                                'note'          => 'Migration: '.$row->description
                            ];

                            if ($coa_dd) {
                                $coa_dd_update->balance -= ($principal_to_pay + $interest_to_pay);
                            }
                            $repay_actual_loantype_arr = [];
                            $repay_actual_loantype_arr = get_manual_payment_actual_migrate($loan, $repay_date, $interest_to_pay, 0, $principal_to_pay, 0, 1, 0,$row->pmt_no);
                            $record_date = date('Y-m-d H:i:s', strtotime($repay_date));
                            $journal_id = JournalRequiry::max('id') + 1;
                            if (!empty($repay_actual_loantype_arr) && count($repay_actual_loantype_arr) > 0) {
                                foreach ($repay_actual_loantype_arr as $key => $repay_actual_arr) {
                                    // var_dump($repay_actual_arr);
                                    foreach ($repay_actual_arr as $repay_table) {
                                        $paid_prin = $repay_table['act_principal'];
                                        $paid_int = $repay_table['act_interest'];
                                        $paid_fee = $repay_table['act_fee'];
                                        $paid_other_fee = $repay_table['act_other_fee'];
                                        $paid_penalty = $repay_table['act_penalty'];
                                        $total_paid += $repay_table['act_total'];
                                        $loan_payment = new LoanPayments();
                                        $loan_payment->loan_id = $loan_id;
                                        $loan_payment->invoice_number = str_pad($journal_id, 8, '0', STR_PAD_LEFT);
                                        $loan_payment->repayment_date = $repay_date;
                                        $loan_payment->payment_month = $repay_table['month_idx'];
                                        $loan_payment->paid_principal = $paid_prin;
                                        $loan_payment->paid_interest = $paid_int;
                                        $loan_payment->paid_fee = $paid_fee;
                                        $loan_payment->loan_repayment_type = $repay_table['type'];
                                        $loan_payment->paid_other_fee = $paid_other_fee;
                                        $loan_payment->penalty_amount = $paid_penalty;
                                        $loan_payment->payment_type = 6; // Cash on hand-teller
                                        $loan_payment->status = $repay_table['status'];
                                        $loan_payment->condition_id = $repay_table['condition'];
                                        $loan_payment->repayment_owed = $repay_table['repayment_owed'];
                                        $loan_payment->waived_penalty = $repay_table['act_waive_penalty'];
                                        $total_waive_penalty += $repay_table['act_waive_penalty'];
                                        $month_idx = $repay_table['month_idx'];
                                       
                                        if ($loan_payment->status == 1) {
                                            $exist_loan_repay = LoanPayments::where('loan_id', $loan_id)->where('payment_month', $month_idx)->get();
                                            $total_p_int = $paid_prin;
                                            $total_p_prin = $paid_int;
                                            // dd($exist_loan_repay);
                                            foreach ($exist_loan_repay as $r) {
                                                $total_p_prin += $r->paid_principal;
                                                $total_p_int += $r->paid_interest;
                                            }
                                            $update = ['status' => 1];
                                            $schedule = RepaymentSchedule::where('loan_id', $loan_id)
                                                // ->where('interest', round($total_p_int,2))
                                                // ->where('principal', round($total_p_prin,2))
                                                ->where('no', $month_idx)
                                                ->update($update);
                                        }
                                        $loan_payment->note = 'Migration: '.$row->description;
                                        $loan_payment->late_day = (!empty($repay_table['overdue']) ? $repay_table['overdue'] : 0);
                                        $loan_payment->user_id = $user_id;
                                        // Update old payment status
                                        $repayment_owed = LoanPayments::select('id', 'status')
                                            ->where('loan_id', '=', $loan_id)
                                            ->where('status', '=', 0)
                                            ->where('condition_id', '!=', 0)
                                            ->where('payment_month', '=', $loan_payment->payment_month)
                                            ->orderBy('id', 'DESC')
                                            ->first();
                                        $loan_payment->save();
                                        // add notifiction data
                                        $this->userActivity(Auth::user()->id, $loan_payment->id, 0, 'Add LoanPayments', Request::fullUrl());

                                        if (!empty($repayment_owed)) {
                                            $repayment_owed->status = 2; /* old repayment month status */
                                            if (!$repayment_owed->save()) {
                                                // return redirect()->route('loan_detail', [$loan_id]);
                                            }
                                        }
                                    }
                                }
                                // Record Transaction and Journal
                            }

                            record_journal($loan, $record_date, "Loan Repayment", $principal_data, $journal_arr, $loan->company_branch_id, $user_id, 1, 'loan');

                            // Update Loan Account Balance
                            $loan_account = $loan->client_loan_account;
                            $loan_account->balance -= $principal_to_pay;
                            // Update Drawdown Acc. Balance
                            if ($coa_dd) {
                                $coa_dd_update->save();
                                $this->userActivity(Auth::user()->id, $coa_dd_update->id, 0, 'Update DrawdownAccounts Balance', Request::fullUrl());
                            }
                            // Till Transaction
                            $is_file = false;
                            $transaction = TransactionsRequiry::where('loan_id', $loan_id)->orderBy('id', 'DESC')->first();

                            // $sch_penalty = floatval(Request::input('sch_penalty'));
                            // if ($sch_penalty > $total_penalty) {
                            //     $penalty_record_new = new PenaltyRecord();
                            //     $penalty_record_new->loan_id = $loan_id;
                            //     $penalty_record_new->record_date = $record_date;
                            //     $penalty_record_new->start_date = $last_paid_date;
                            //     $penalty_record_new->end_date = $repay_date;
                            //     $penalty_record_new->overdue = floatval(Request::input('overdue'));
                            //     $penalty_record_new->type = "Collection";
                            //     $penalty_record_new->paid_penalty = $total_penalty;
                            //     $penalty_record_new->owed_penalty = $sch_penalty - $total_penalty;
                            //     $penalty_record_new->inputted_by = Auth::user()->id;
                            //     $penalty_record_new->save();
                            // }
                            // if ($total_waive_penalty > 0) {
                            //     $penalty_record_new = new PenaltyRecord();
                            //     $penalty_record_new->loan_id = $loan_id;
                            //     $penalty_record_new->record_date = $record_date;
                            //     $penalty_record_new->start_date = $last_paid_date;
                            //     $penalty_record_new->end_date = $repay_date;
                            //     $penalty_record_new->overdue = floatval(Request::input('overdue'));
                            //     $penalty_record_new->type = "Waive";
                            //     $penalty_record_new->paid_penalty = $total_waive_penalty;
                            //     $penalty_record_new->owed_penalty = 0;
                            //     $penalty_record_new->inputted_by = Auth::user()->id;
                            //     $penalty_record_new->save();
                            // }
                            $this->userActivity($user_id, $loan_id, 6, 'Make repayment');

                            // if (0 == round($loan_account->balance, 2)) {
                            //     $loan_update = Loan::with(['payment', 'schedule'])->find($loan_id);
                            //     $pass_due = LoanCalculate::getTotalPenalty($loan_update, $repay_date)[4];
                            //     if (intval($pass_due * 100) <= 0) {
                            //         $loan_update->status = 10;
                            //         $loan_update->settlement_date = $repay_date;
                            //         $loan_update->save();
                            //         // Update Loan Account Balance
                            //         $loan_account->status = 8; //completed
                            //     }
                            // }
                            $loan_account->save();

                            // $draft_id = Request::input('draft_id');
                            // $draft = LoanPaymentsDraft::find($draft_id);
                            // if (!empty($draft)) {
                            //     $draft->status = 1;
                            //     $draft->save();
                            // }

                            // $loan_account = $loan_account - $pay_principal;

                        }
                        $row->is_success = 1;
                    }
                    // dd($repay_arr['loan']);
                    // $params = array(
                    //     'debit' => $loan->loan_amount,
                    //     'credit' => $loan->loan_amount,
                    //     'parent_debit' => $coa_dd->id,
                    //     'parent_credit' => $coa->id,
                    //     'parent_debit_label' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                    //     'parent_credit_label' => $coa->name . ' (' . $branch_code . $coa->account_code . ')',
                    //     'd_description'=>($draft->d_description4 != "")?$draft->d_description4 : "Principal Repayment - ". $loan->client_loan_account->account_name . " - ". $loan->contract_id,
                    //     'c_description'=>($draft->c_description4 != "")?$draft->c_description4 : "Principal Repayment - ". $loan->client_loan_account->account_name . " - ". $loan->contract_id,
                    //     'description'=>$draft->description4,
                    // );

                    // var_dump($params);
                    // dd($coa_pnt);
                    
                }else{

                }
                // var_dump($row->drawdown_id);
                // var_dump($coa_dd);
                // var_dump($loan->schedule->count());
            }
            $row->is_generate = 1;
            // $row->is_regenerate = 1;
            $row->save();
        }

        if($data->count() > 0){
            $datas['success'] = 1;
            echo json_encode($datas);
        }
        // var_dump($data->all());
        // return redirect('migration/post-migrate-installment');








    }

    public function GetMigrateInstallmentDeposit()
    {
        return $this->view('migration.reinstallment_deposit');
    }

    public function PostMigrateInstallmentDeposit()
    {
        
        $data = MigrateDeposit::select('migrate_deposit.*',
                                'clients.client_name',
                                'clients.id as clients_id',
                                'drawdown_account.account_no',
                                'drawdown_account.id AS drawdown_id',
                                'drawdown_account.coa_id',
                                'drawdown_account.account_name AS dr_account_name',
                                'projects.company_id AS companies_id')
                                ->Join('units','migrate_deposit.varriant_code','=','units.code')
                                ->Join('drawdown_account','drawdown_account.unit_id','=','units.id')
                                ->Join('clients','clients.id','=','drawdown_account.client_id')
                                ->Join('projects','projects.id','=','drawdown_account.project_id')
                                ->groupBy('migrate_deposit.id')
                                // ->where('migrate_deposit.post_date','>=','2021-09-01')
                                ->where('migrate_deposit.varriant_code','VH2-278')
                                ->where('migrate_deposit.is_re_generate',0)
                                ->where('migrate_deposit.pmt_no','>',0)
                                ->where(function($query){
                                    $query->where('migrate_deposit.status','<>','Cancel')
                                            ->orWhereNull('migrate_deposit.status');
                                })
                                ->where(function($query){
                                    $query->where('migrate_deposit.document_type','<>','Invoice');
                                })

                                ->where('migrate_deposit.reversd',0)
                                ->where('migrate_deposit.payment_type','Deposit')
                                ->whereNotNull('migrate_deposit.loan_no')
                                // ->where('migrate_deposit.loan_no','F-298')
                                ->limit(5000)
                                ->get();
        foreach ($data as $key => $row) {
            $user_id = Auth::user()->id;
            $loan = Loan::with('client_loan_account')
                            ->with('schedule')
                            ->with('payment')
                            ->with(['penalty_record' => function ($q) {
                                    $q->select('id', 'loan_id', 'record_date', 'owed_penalty')->orderBy('id', 'DESC');
                                }])
                            ->where('loans.workflow_status','approve')
                            // ->where('loans.loan_installment_no', $row->loan_no)->first();
                            // ->where('loans.loan_installment_no', $row->varriant_code)->first();
                            ->where('loans.unit_code', $row->varriant_code)->first();
            if($loan && $loan->schedule){
                $loan_id = $loan->id;
                $coa_dd = DrawdownAccounts::select('id', 'balance', 'coa_id', 'client_id')
                                            ->with('coa')
                                            ->where('id', '=', $row->drawdown_id)->first();
                $coa_dd_update = $coa_dd;

                $branch = CompanyBranch::find($loan->company_branch_id);
                if($row->payment_type == 'Deposit'){
                    $pay_amount = ($row->amount * -1);
                    $repay_date = date('Y-m-d',strtotime($row->post_date));
                    // $schedule = $loans->schedule->where('no',$row->pnt_no);
                    $repay_arr = get_auto_repay_array_migrate($loan,date('Y-m-d',strtotime($row->post_date)),$row->pmt_no);
                    $branch_code = $branch->branch_code;
                    $coa_dd = $coa_dd->coa;
                    if($repay_arr['loan']){
                        $loan_account = $loan->client_loan_account;
                        $coa = CoaCategory::find($loan_account->coa_id);
                        $coa_air = CoaCategory::find($loan_account->air_id);
                        $coa_int_inc = CoaCategory::select('id', 'account_code', 'name')->where('id', $loan_account->int_inc_id)->first();
                        $coa_ofc = CoaCategory::select('id', 'account_code', 'name')->where('type', '6')->where('currency', $draft ? $draft->currency : $loan_account->currency)
                                            ->where('name','like','%Inc-Other Fees and Commissions from Loans and Leasing%')->first();
                        $coa_ap = CoaCategory::where('name', 'like', '%Accounts Payable-Other%')
                            ->where('currency', '=', $draft ? $draft->currency : $loan_account->currency)->first();
                        $coa_pnt = CoaCategory::select('id', 'account_code', 'name')->where('type', 6)->where('currency', $draft ? $draft->currency : $loan_account->currency)->where('name', 'Inc-Fine and Penalty on Repayment of Loans and leasing')->first();

                        $pay_principal = isset($repay_arr['loan'][0]['principal'])?$repay_arr['loan'][0]['principal']:0;
                        $pay_interest = isset($repay_arr['loan'][0]['interest'])?$repay_arr['loan'][0]['interest']:0;
                        // var_dump($pay_amount.'===='.($pay_principal + $pay_interest));
                        if($pay_amount > 0){
                            $principal_to_pay = 0;
                            $interest_to_pay = 0;
                            if($pay_amount >= $pay_principal){
                                $principal_to_pay = $pay_principal;
                            }else{
                                $principal_to_pay = $pay_amount;
                            }

                            if($pay_amount >= $pay_interest){
                                $interest_to_pay = $pay_interest;
                            }else{
                                $interest_to_pay = $pay_amount;
                            }
                            $journal_arr = [];

                            $debit_arr = [];
                            $credit_arr = [];
                            $desc_arr = [];
                            // ===== Principal ========
                            if($principal_to_pay > 0){
                                $params = array(
                                    'debit' => $loan->loan_amount,
                                    'credit' => $loan->loan_amount,
                                    'parent_debit' => $coa_dd->id,
                                    'parent_credit' => $coa->id,
                                    'parent_debit_label' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                                    'parent_credit_label' => $coa->name . ' (' . $branch_code . $coa->account_code . ')',
                                    'd_description'=>($draft->d_description4 != "")?$draft->d_description4 : "Principal Repayment - ". $loan->client_loan_account->account_name . " - ". $loan->contract_id,
                                    'c_description'=>($draft->c_description4 != "")?$draft->c_description4 : "Principal Repayment - ". $loan->client_loan_account->account_name . " - ". $loan->contract_id,
                                    'description'=>$draft->description4,
                                );

                                $debit_arr[0]['coa'] = $coa_dd->id;
                                $debit_arr[0]['amount'] = floatval($principal_to_pay);
                                $debit_arr[0]['desc'] = $params['d_description'];
                                $credit_arr[0]['coa'] = $params['parent_credit'];
                                $credit_arr[0]['amount'] = floatval($principal_to_pay);
                                $credit_arr[0]['desc'] = $params['c_description'];
                                $desc_arr[0]['desc'] = 'Migration';

                                array_push($journal_arr, [
                                                            $params['parent_debit'],
                                                            $principal_to_pay,
                                                            $params['d_description'],
                                                            $params['parent_credit'],
                                                            $principal_to_pay,
                                                            $params['c_description'],
                                                            $params['description'],
                                                        ]);

                            }

                            if($interest_to_pay > 0){
                                $params = array(
                                    'debit' => $loan->loan_amount,
                                    'credit' => $loan->loan_amount,
                                    'parent_debit' => $coa_dd->id,
                                    'parent_credit' => $coa_int_inc->id,
                                    'parent_debit_label' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                                    'parent_credit_label' => $coa_int_inc->name . ' (' . $branch_code . $coa_int_inc->account_code . ')',
                                    'd_description'=>$draft->d_description0,
                                    'c_description'=>$draft->c_description0,
                                    'description'=>$draft->description0,
                                );

                                $debit_arr[0]['coa'] = $coa_dd->id;
                                $debit_arr[0]['amount'] = floatval($interest_to_pay);
                                $debit_arr[0]['desc'] = $params['d_description'];
                                $credit_arr[0]['coa'] = $params['parent_credit'];
                                $credit_arr[0]['amount'] = floatval($interest_to_pay);
                                $credit_arr[0]['desc'] = $params['c_description'];
                                $desc_arr[0]['desc'] = 'Migration';
                                array_push($journal_arr, [
                                                            $params['parent_debit'],
                                                            $interest_to_pay,
                                                            $params['d_description'],
                                                            $params['parent_credit'],
                                                            $interest_to_pay,
                                                            $params['c_description'],
                                                            $params['description'],
                                                        ]);
                            }
                            $principal_data = [
                                'act_interest'  => $interest_to_pay,
                                'act_principal' => $principal_to_pay,
                                'act_fee'       => 0,
                                'act_other_fee' => 0,
                                'act_penalty'   => 0,
                                'act_total'     => ($principal_to_pay + $interest_to_pay),
                                'note'          => 'Migration: '.$row->description
                            ];

                            if ($coa_dd) {
                                $coa_dd_update->balance -= ($principal_to_pay + $interest_to_pay);
                            }
                            $repay_actual_loantype_arr = [];
                            $repay_actual_loantype_arr = get_manual_payment_actual_migrate($loan, $repay_date, $interest_to_pay, 0, $principal_to_pay, 0, 1, 0,$row->pmt_no);
                            $record_date = date('Y-m-d H:i:s', strtotime($repay_date));

                            $journal_id = JournalRequiry::max('id') + 1;
                            if (!empty($repay_actual_loantype_arr) && count($repay_actual_loantype_arr) > 0) {
                                foreach ($repay_actual_loantype_arr as $key => $repay_actual_arr) {
                                    foreach ($repay_actual_arr as $repay_table) {
                                        $paid_prin = $repay_table['act_principal'];
                                        $paid_int = $repay_table['act_interest'];
                                        $paid_fee = $repay_table['act_fee'];
                                        $paid_other_fee = $repay_table['act_other_fee'];
                                        $paid_penalty = $repay_table['act_penalty'];
                                        $total_paid += $repay_table['act_total'];
                                        $loan_payment = new LoanPayments();
                                        $loan_payment->loan_id = $loan_id;
                                        $loan_payment->invoice_number = str_pad($journal_id, 8, '0', STR_PAD_LEFT);
                                        $loan_payment->repayment_date = $repay_date;
                                        $loan_payment->payment_month = $repay_table['month_idx'];
                                        $loan_payment->paid_principal = $paid_prin;
                                        $loan_payment->paid_interest = $paid_int;
                                        $loan_payment->paid_fee = $paid_fee;
                                        $loan_payment->loan_repayment_type = $repay_table['type'];
                                        $loan_payment->paid_other_fee = $paid_other_fee;
                                        $loan_payment->penalty_amount = $paid_penalty;
                                        $loan_payment->payment_type = 6; // Cash on hand-teller
                                        $loan_payment->status = $repay_table['status'];
                                        $loan_payment->condition_id = $repay_table['condition'];
                                        $loan_payment->repayment_owed = $repay_table['repayment_owed'];
                                        $loan_payment->waived_penalty = $repay_table['act_waive_penalty'];
                                        $total_waive_penalty += $repay_table['act_waive_penalty'];
                                        $month_idx = $repay_table['month_idx'];
                                        if ($loan_payment->status == 1) {
                                            $exist_loan_repay = LoanPayments::where('loan_id', $loan_id)->where('payment_month', $month_idx)->get();
                                            $total_p_int = $paid_prin;
                                            $total_p_prin = $paid_int;
                                            // dd($exist_loan_repay);
                                            foreach ($exist_loan_repay as $r) {
                                                $total_p_prin += $r->paid_principal;
                                                $total_p_int += $r->paid_interest;
                                            }
                                            $update = ['status' => 1];
                                            $schedule = RepaymentSchedule::where('loan_id', $loan_id)
                                                // ->where('interest', round($total_p_int,2))
                                                // ->where('principal', round($total_p_prin,2))
                                                ->where('no', $month_idx)
                                                ->update($update);
                                        }

                                        $loan_payment->note = 'Migration: '.$row->description;
                                        $loan_payment->late_day = (!empty($repay_table['overdue']) ? $repay_table['overdue'] : 0);
                                        $loan_payment->user_id = $user_id;
                                        // Update old payment status
                                        $repayment_owed = LoanPayments::select('id', 'status')
                                            ->where('loan_id', '=', $loan_id)
                                            ->where('status', '=', 0)
                                            ->where('condition_id', '!=', 0)
                                            ->where('payment_month', '=', $loan_payment->payment_month)
                                            ->orderBy('id', 'DESC')
                                            ->first();
                                        $loan_payment->save();
                                        // add notifiction data
                                        $this->userActivity(Auth::user()->id, $loan_payment->id, 0, 'Add LoanPayments', Request::fullUrl());

                                        if (!empty($repayment_owed)) {
                                            $repayment_owed->status = 2; /* old repayment month status */
                                            if (!$repayment_owed->save()) {
                                                // return redirect()->route('loan_detail', [$loan_id]);
                                            }
                                        }
                                    }
                                }
                                // Record Transaction and Journal
                            }

                            record_journal($loan, $record_date, "Loan Repayment", $principal_data, $journal_arr, $loan->company_branch_id, $user_id, 1, 'loan');

                            // Update Loan Account Balance
                            $loan_account = $loan->client_loan_account;
                            $loan_account->balance -= $principal_to_pay;
                            // Update Drawdown Acc. Balance
                            if ($coa_dd) {
                                $coa_dd_update->save();
                                $this->userActivity(Auth::user()->id, $coa_dd_update->id, 0, 'Update DrawdownAccounts Balance', Request::fullUrl());
                            }
                            // Till Transaction
                            $is_file = false;
                            $transaction = TransactionsRequiry::where('loan_id', $loan_id)->orderBy('id', 'DESC')->first();

                            // $sch_penalty = floatval(Request::input('sch_penalty'));
                            // if ($sch_penalty > $total_penalty) {
                            //     $penalty_record_new = new PenaltyRecord();
                            //     $penalty_record_new->loan_id = $loan_id;
                            //     $penalty_record_new->record_date = $record_date;
                            //     $penalty_record_new->start_date = $last_paid_date;
                            //     $penalty_record_new->end_date = $repay_date;
                            //     $penalty_record_new->overdue = floatval(Request::input('overdue'));
                            //     $penalty_record_new->type = "Collection";
                            //     $penalty_record_new->paid_penalty = $total_penalty;
                            //     $penalty_record_new->owed_penalty = $sch_penalty - $total_penalty;
                            //     $penalty_record_new->inputted_by = Auth::user()->id;
                            //     $penalty_record_new->save();
                            // }
                            // if ($total_waive_penalty > 0) {
                            //     $penalty_record_new = new PenaltyRecord();
                            //     $penalty_record_new->loan_id = $loan_id;
                            //     $penalty_record_new->record_date = $record_date;
                            //     $penalty_record_new->start_date = $last_paid_date;
                            //     $penalty_record_new->end_date = $repay_date;
                            //     $penalty_record_new->overdue = floatval(Request::input('overdue'));
                            //     $penalty_record_new->type = "Waive";
                            //     $penalty_record_new->paid_penalty = $total_waive_penalty;
                            //     $penalty_record_new->owed_penalty = 0;
                            //     $penalty_record_new->inputted_by = Auth::user()->id;
                            //     $penalty_record_new->save();
                            // }
                            $this->userActivity($user_id, $loan_id, 6, 'Make repayment');

                            // if (0 == round($loan_account->balance, 2)) {
                            //     $loan_update = Loan::with(['payment', 'schedule'])->find($loan_id);
                            //     $pass_due = LoanCalculate::getTotalPenalty($loan_update, $repay_date)[4];
                            //     if (intval($pass_due * 100) <= 0) {
                            //         $loan_update->status = 10;
                            //         $loan_update->settlement_date = $repay_date;
                            //         $loan_update->save();
                            //         // Update Loan Account Balance
                            //         $loan_account->status = 8; //completed
                            //     }
                            // }
                            $loan_account->save();

                            $draft_id = Request::input('draft_id');
                            $draft = LoanPaymentsDraft::find($draft_id);
                            if (!empty($draft)) {
                                $draft->status = 1;
                                $draft->save();
                            }

                            // $loan_account = $loan_account - $pay_principal;

                        }
                        $row->is_re_success = 1;
                    }
                    // dd($repay_arr['loan']);
                    // $params = array(
                    //     'debit' => $loan->loan_amount,
                    //     'credit' => $loan->loan_amount,
                    //     'parent_debit' => $coa_dd->id,
                    //     'parent_credit' => $coa->id,
                    //     'parent_debit_label' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                    //     'parent_credit_label' => $coa->name . ' (' . $branch_code . $coa->account_code . ')',
                    //     'd_description'=>($draft->d_description4 != "")?$draft->d_description4 : "Principal Repayment - ". $loan->client_loan_account->account_name . " - ". $loan->contract_id,
                    //     'c_description'=>($draft->c_description4 != "")?$draft->c_description4 : "Principal Repayment - ". $loan->client_loan_account->account_name . " - ". $loan->contract_id,
                    //     'description'=>$draft->description4,
                    // );

                    // var_dump($params);
                    // dd($coa_pnt);
                    
                }else{

                }
                // var_dump($row->drawdown_id);
                // var_dump($coa_dd);
                // var_dump($loan->schedule->count());
            }
            $row->is_re_generate = 1;
            $row->save();
        }

        if($data->count() > 0){
            $datas['success'] = 1;
            echo json_encode($datas);
        }
        // var_dump($data->all());
        // return redirect('migration/post-migrate-installment');








    }
}
