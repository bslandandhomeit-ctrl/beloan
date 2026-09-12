<?php

namespace App\Http\Controllers;

use Request;
use DB;
use Session;
use Auth;
use App\Models\ClientLoanAccounts;
use App\Models\CompanyBranch;
use App\Models\JournalDetail;
use App\Models\CoaCategory;
use App\Models\Currency;
use LoanCalculate;

class CreditController extends Controller {

    public function __construct() {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function get_credit_classification() {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 1500;
        $query_arr = array('id' => Auth::user()->branch_id, 'role_id' => Auth::user()->role_id);
        $B1 = new CompanyBranch();
        //$B1 = $this->getBranchByUser($B1, 'id', $query_arr);
        
        $branch = $B1->select('id', 'branch_code', 'branch_name')->where('status', '=', 1)->get();
        $summary = ClientLoanAccounts::join('loans', 'client_loan_accounts.id', '=', 'loans.loan_account_id')
                ->join('transactions_requiry', 'loans.id', '=', 'transactions_requiry.loan_id')
                ->join('repayment_schedule', 'loans.id', '=', 'repayment_schedule.loan_id')
                ->select('client_loan_accounts.*', 'loans.id as loanid', 'loans.status as loanstatus','loans.disburse_date', DB::raw('MAX(tb_transactions_requiry.trans_date) as trans_date'))
                ->with(['client'=>function($q){
                    $q->with(['Address'=>function($que){
                        $que->with('country', 'province', 'District', 'Commune', 'Village');
                    }]);
                }])
                ->groupBy('loanid');

        if (Request::has('status')){
            $data['status'] = Request::input('status');
            $summary = $summary->where('client_loan_accounts.status', $data['status']);
        }
        if (Request::has('branch')){
            $data['branch_code'] = Request::input('branch');
            $summary = $summary->where('client_loan_accounts.branch', $data['branch_code']);
        }
        $summary = $summary->with(['loan'=>function($q){
            $q->with(['schedule', 'payment', 'writeoff']);

        }]);
        //dd($summary->get());
        $summary = $summary->take($offset)->get();
        // auto provision
        if(Request::input('flag') == "1"){
            $set_status = Request::input('set_status');
            $exe_date = (Request::has('date'))?Request::input('date'):date('Y-m-d');
            // $array_test = [];
            // foreach($summary as $sum){
            //     if($sum->account_name == "Chea Virak") $array_test[] = $sum;
            // }
            // dd($array_test);
            foreach($summary as $sum){
                $penalty_arr = [];
                if(is_null($sum->loan)) continue;
                if($sum->loanstatus == 3 || $sum->loanstatus == 8){
                    $penalty_arr = LoanCalculate::getTotalPenalty($sum->loan, $exe_date);
                    $i_overdue = $penalty_arr[2];
                    if($penalty_arr == -1) continue;
                    $exp_status = 0;
                    $exp_status = get_auto_provision_old($sum, $sum->loan, $i_overdue);
                    if($exp_status == '-') continue;
                    if(intval($exp_status) == intval($sum->status) && $set_status[$sum->id] == "-")
                        continue;
                    if(intval($sum->status) > $exp_status){ // upgrade manual
                        if(!is_null($set_status[$sum->id]) && $set_status[$sum->id] != "-"){
                            $exp_status = intval($set_status[$sum->id]);
                        }else{
                            continue;
                        }
                    }else{ //downgrade
                        if(!is_null($set_status[$sum->id]) && $set_status[$sum->id] != "-"){
                            $exp_status = intval($set_status[$sum->id]);
                        }
                    }

                    $this->auto_exe_provision($sum, $exp_status, $exe_date);
                }
            }
            
            return redirect()->route('creditSummary');
        }
        //$summary = $summary->get();
        if(Request::has('overdue')){
          $overdue = Request::input('overdue');
          //$data['overdue'] = $overdue;
        }else{
            $overdue = null;
        }
        //dd($summary[3]->client->Address[0]->Commune->en_name);
        $currency_list = [];
        $currency_arr = Currency::select('id', 'code')->get();
        foreach($currency_arr as $cur){
            $currency_list[$cur->id] = $cur->code;
        }
        $data['currency_list'] = $currency_list;
        $data['summary'] = $summary;
        $data['branch'] = $branch;
        $data['offset'] = $offset;
        $data['set_overdue'] = $overdue;
        $data['dpDate'] = Request::input('dpDate');

        return $this->view('credits.list_credit', $data);
    }

    function exe_provision($loan_account_id = null) {
        if(!is_null($loan_account_id))
        {
            $loan_account = ClientLoanAccounts::find($loan_account_id);
            $user_id = Auth::user()->id;
            $branch_id = Auth::user()->branch_id;
            $record_date = date('Y-m-d H:i:s');
            $static = config('static_data');
            $key_pre = $static['client_loan_account_prefix'];
            if(strpos( $loan_account->name, 'Leasing') != false){
                $key_pre = $static['client_leasing_account_prefix'];
            }
            switch ($loan_account->status) {
                case 2 : // Standard -> Sub-Standard
                {
                    $sub_loan_account = $loan_account;
                    $loan_account->prefix = $key_pre[$loan_account->status]; //"Sub-Std-"
                    // Create new COA for Sub-Standard
                    // COA
                    $pre_prefix = $static['client_loan_account_pre_prefix']['coa']; //"";
                    $new_coa = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    //if($new_coa->new_flg == '0')
                    // AIR
                    $pre_prefix = $static['client_loan_account_pre_prefix']['air']; //"Air-";
                    $new_air = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    // Interest Income
                    $pre_prefix = $static['client_loan_account_pre_prefix']['interest']; //"Int-Inc-";
                    $new_int = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    // Income in suspense
                    $pre_prefix = $static['client_loan_account_pre_prefix']['suspense']; //'Int-In Sus-'
                    $new_int_sus = createLoanAccountCoa($sub_loan_account, $pre_prefix)['coa'];
                    // Add journal ( debit: sub_coa_id, credit: coa_id )
                    $desc_str = "provision " . $loan_account->loan_ref . "(" . $static['client_loan_account_status'][$loan_account->status] . " -> " . $static['client_loan_account_status'][$loan_account->status + 1] . ")";
                    $journal_arr = [];
                    $new_coa_id = $new_coa->id;
                    $old_coa_id = $loan_account->coa_id;
                    $old_coa_amount = JournalDetail::selectRaw('sum(debit) - sum(credit) as bal')->where('coa_id',$old_coa_id)->first()->bal;
                    if(is_null($old_coa_amount)) $old_coa_amount = 0;
                    $new_coa_amount = $old_coa_amount;
                    array_push($journal_arr, [$new_coa_id, $old_coa_amount, $desc_str, $old_coa_id, $old_coa_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id);
                    // Add journal ( debit: sub_air_id, credit: air_id )
                    $journal_arr = [];
                    $new_air_id = $new_air->id;
                    $old_air_id = $loan_account->air_id;
                    $old_air_amount = JournalDetail::selectRaw('sum(debit) - sum(credit) as bal')->where('coa_id',$old_air_id)->first()->bal;
                    if(is_null($old_air_amount)) $old_air_amount = 0;
                    $new_air_amount = $old_air_amount;
                    array_push($journal_arr, [$new_air_id, $old_air_amount, $desc_str, $old_air_id, $old_air_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id);
                    // Add journal ( debit: int_inc_id, credit: int_sus_id )
                    $journal_arr = [];
                    $new_int_sus_id = $new_int_sus->id;
                    $old_int_id = $loan_account->int_inc_id;
                    $old_int_amount = JournalDetail::selectRaw('sum(credit) - sum(debit) as bal')->where('coa_id',$old_int_id)->first()->bal;
                    if(is_null($old_int_amount)) $old_int_amount = 0;
                    $new_int_amount = $old_int_amount;
                    array_push($journal_arr, [$old_int_id, $old_int_amount, $desc_str, $new_int_sus_id, $old_int_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id);

                    // Provision
                    $provision_amount = PROV_RATE_TO_SUB_STD * $loan_account->balance;
                    $journal_arr = [];
                    $desc_str .= ' - prov amount = '.$provision_amount;
                    $pat1 = '%'.substr($loan_account->acc_key,0,7).'%';
                    if(strpos($loan_account->acc_key,'<') !== false){
                        $pat2 = '%<%';
                    }elseif(strpos($loan_account->acc_key,'>') !== false){
                        $pat2 = '%>%';
                    }
                    $debit_acc = CoaCategory::select('*')->where('name', 'LIKE', 'Exp-Bad and Doubtful Debt%')->where('name', 'LIKE', $pat1)->where('name', 'LIKE',  $pat2)->where('type', 6)->where('currency', $loan_account->currency)->first();
                    $credit_acc = CoaCategory::select('*')->where('name', 'LIKE', '(Less) Specific Loan Loses%')->where('name', 'LIKE', $pat1)->where('name', 'LIKE',  $pat2)->where('type', 6)->where('currency', $loan_account->currency)->first();
                    array_push($journal_arr, [$debit_acc->id, $provision_amount, $desc_str, $credit_acc->id, $provision_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);

                    // Update loan_account
                    $loan_account->coa_id = $new_coa->id;
                    $loan_account->air_id = $new_air->id;
                    $loan_account->int_inc_id = $new_int->id;
                    $loan_account->sus_id = $new_int_sus->id;
                    $loan_account->status = $loan_account->status + 1;
                    $loan_account->save();
                    break;
                }
                case 3 : // Sub-Standard -> Doubtful
                {
                    $sub_loan_account = $loan_account;
                    $loan_account->prefix = $key_pre[$loan_account->status]; //"Doubtful "
                    // Create new COA for Doubtful
                    // COA
                    $pre_prefix = $static['client_loan_account_pre_prefix']['coa']; //"";
                    $new_coa = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    // AIR
                    $pre_prefix = $static['client_loan_account_pre_prefix']['air']; //"Air-";
                    $new_air = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    // Interest Income
                    $pre_prefix = $static['client_loan_account_pre_prefix']['interest']; //"Int-Inc-";
                    $new_int = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    // Income in suspense
                    $pre_prefix = $static['client_loan_account_pre_prefix']['suspense']; //'Int-In Sus-'
                    $new_int_sus = createLoanAccountCoa($sub_loan_account, $pre_prefix)['coa'];
                    // Add journal ( debit: sub_coa_id, credit: coa_id )
                    $desc_str = "provision " . $loan_account->loan_ref . "(" . $static['client_loan_account_status'][$loan_account->status] . " -> " . $static['client_loan_account_status'][$loan_account->status + 1] . ")";
                    $journal_arr = [];
                    $new_coa_id = $new_coa->id;
                    $old_coa_id = $loan_account->coa_id;
                    $old_coa_amount = JournalDetail::selectRaw('sum(debit) - sum(credit) as bal')->where('coa_id',$old_coa_id)->first()->bal;
                    if(is_null($old_coa_amount)) $old_coa_amount = 0;
                    $new_coa_amount = $old_coa_amount;
                    array_push($journal_arr, [$new_coa_id, $old_coa_amount, $desc_str, $old_coa_id, $old_coa_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);

                    // Add journal ( debit: sub_air_id, credit: air_id )
                    $journal_arr = [];
                    $new_air_id = $new_air->id;
                    $old_air_id = $loan_account->air_id;
                    $old_air_amount = JournalDetail::selectRaw('sum(debit) - sum(credit) as bal')->where('coa_id',$old_air_id)->first()->bal;
                    if(is_null($old_air_amount)) $old_air_amount = 0;
                    $new_air_amount = $old_air_amount;
                    array_push($journal_arr, [$new_air_id, $new_air_amount, $desc_str, $old_air_id, $old_air_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);

                    // Add journal ( debit: old_suspense_id, credit: new_suspense_id )
                    $journal_arr = [];
                    $new_sus_id = $new_int_sus->id;
                    $old_sus_id = $loan_account->sus_id;
                    $old_sus_amount = JournalDetail::selectRaw('sum(credit) - sum(debit) as bal')->where('coa_id',$old_sus_id)->first()->bal;
                    if(is_null($old_sus_amount)) $old_sus_amount = 0;
                    $new_sus_amount = $old_sus_amount;
                    array_push($journal_arr, [$old_sus_id, $old_sus_amount, $desc_str, $new_sus_id, $old_sus_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id);

                    // Provision ( Sub-Standard -> Default )
                    $provision_amount = PROV_RATE_TO_DOUBTFUL * $loan_account->balance;
                    $journal_arr = [];
                    $desc_str .= ' - prov amount = '.$provision_amount;
                    $pat1 = '%'.substr($loan_account->acc_key,0,7).'%';
                    if(strpos($loan_account->acc_key,'<') !== false){
                        $pat2 = '%<%';
                    }elseif(strpos($loan_account->acc_key,'>') !== false){
                        $pat2 = '%>%';
                    }
                    $debit_acc = CoaCategory::select('*')->where('name', 'LIKE', 'Exp-Bad and Doubtful Debt%')->where('name', 'LIKE', $pat1)->where('name', 'LIKE', $pat2)->where('type', 6)->where('currency', $loan_account->currency)->first();
                    $credit_acc = CoaCategory::select('*')->where('name', 'LIKE', '(Less) Specific Loan Loses%')->where('name', 'LIKE', $pat1)->where('name', 'LIKE', $pat2)->where('type', 6)->where('currency', $loan_account->currency)->first();
                    array_push($journal_arr, [$debit_acc->id, $provision_amount, $desc_str, $credit_acc->id, $provision_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);

                    // Update loan_account
                    $loan_account->coa_id = $new_coa->id;
                    $loan_account->air_id = $new_air->id;
                    $loan_account->int_inc_id = $new_int->id;
                    $loan_account->sus_id = $new_int_sus->id;
                    $loan_account->status = $loan_account->status + 1;
                    $loan_account->save();
                    break;
                }
                case 4 : // Doubtful -> Loss Loan
                {
                    $sub_loan_account = $loan_account;
                    $loan_account->prefix = $key_pre[$loan_account->status]; //"Loss Loan "
                    // Create new COA for Doubtful
                    // COA
                    $pre_prefix = $static['client_loan_account_pre_prefix']['coa']; //"";
                    $new_coa = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    // AIR
                    $pre_prefix = $static['client_loan_account_pre_prefix']['air']; //"Air-";
                    $new_air = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    // Interest Income
                    $pre_prefix = $static['client_loan_account_pre_prefix']['interest']; //"Int-Inc-";
                    $new_int = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    // Income in suspense
                    $pre_prefix = $static['client_loan_account_pre_prefix']['suspense']; //'Int-In Sus-'
                    $new_int_sus = createLoanAccountCoa($sub_loan_account, $pre_prefix)['coa'];
                    // Add journal ( debit: sub_coa_id, credit: coa_id )
                    $desc_str = "provision " . $loan_account->loan_ref . "(" . $static['client_loan_account_status'][$loan_account->status] . " -> " . $static['client_loan_account_status'][$loan_account->status + 1] . ")";
                    $journal_arr = [];
                    $new_coa_id = $new_coa->id;
                    $old_coa_id = $loan_account->coa_id;
                    $old_coa_amount = JournalDetail::selectRaw('sum(debit) - sum(credit) as bal')->where('coa_id',$old_coa_id)->first()->bal;
                    if(is_null($old_coa_amount)) $old_coa_amount = 0;
                    $new_coa_amount = $old_coa_amount;
                    array_push($journal_arr, [$new_coa_id, $new_coa_amount, $desc_str, $old_coa_id, $old_coa_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);

                    // Add journal ( debit: sub_air_id, credit: air_id )
                    $journal_arr = [];
                    $new_air_id = $new_air->id;
                    $old_air_id = $loan_account->air_id;
                    $old_air_amount = JournalDetail::selectRaw('sum(debit) - sum(credit) as bal')->where('coa_id',$old_air_id)->first()->bal;
                    if(is_null($old_air_amount)) $old_air_amount = 0;
                    $new_air_amount = $old_air_amount;
                    array_push($journal_arr, [$new_air_id, $new_air_amount, $desc_str, $old_air_id, $old_air_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);

                    // Add journal ( debit: old_suspense_id, credit: new_sus_id )
                    $journal_arr = [];
                    $new_sus_id = $new_int_sus->id;
                    $old_sus_id = $loan_account->sus_id;
                    $old_sus_amount = JournalDetail::selectRaw('sum(debit) - sum(credit) as bal')->where('coa_id',$old_sus_id)->first()->bal;
                    if(is_null($old_sus_amount)) $old_sus_amount = 0;
                    $new_sus_amount = $old_sus_amount;
                    array_push($journal_arr, [$old_sus_id, $old_sus_amount, $desc_str, $new_sus_id, $old_sus_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);

                    // Provision ( Doubtful -> Loss Loan )
                    $provision_amount = PROV_RATE_TO_LOSS_LOAN * $loan_account->balance;
                    $journal_arr = [];
                    $desc_str .= ' - prov amount = '.$provision_amount;
                    $pat1 = '%'.substr($loan_account->acc_key,0,7).'%';
                    if(strpos($loan_account->acc_key,'<') !== false){
                        $pat2 = '%<%';
                    }elseif(strpos($loan_account->acc_key,'>') !== false){
                        $pat2 = '%>%';
                    }
                    $debit_acc = CoaCategory::select('*')->where('name', 'LIKE', 'Exp-Bad and Doubtful Debt%')->where('name', 'LIKE', $pat1)->where('name', 'LIKE',  $pat2)->where('type', 6)->where('currency', $loan_account->currency)->first();
                    $credit_acc = CoaCategory::select('*')->where('name', 'LIKE', '(Less) Specific Loan Loses%')->where('name', 'LIKE', $pat1)->where('name', 'LIKE',  $pat2)->where('type', 6)->where('currency', $loan_account->currency)->first();
                    array_push($journal_arr, [$debit_acc->id, $provision_amount, $desc_str, $credit_acc->id, $provision_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);

                    // Update loan_account
                    $loan_account->coa_id = $new_coa->id;
                    $loan_account->air_id = $new_air->id;
                    $loan_account->int_inc_id = $new_int->id;
                    $loan_account->sus_id = $new_int_sus->id;
                    $loan_account->status = $loan_account->status + 1;
                    $loan_account->save();
                    break;
                }
                default: {
                break;
                }
            }
        }
        return redirect()->back();
    }

    function auto_exe_provision($loan_account = null, $exp_status = null, $exe_date = null) {
        if(!is_null($loan_account))
        {
//            $loan_account = ClientLoanAccounts::find($loan_account_id);
            $user_id = Auth::user()->id;
            $branch_id = Auth::user()->branch_id;
            $record_date = ($exe_date == null)? date('Y-m-d H:i:s'):$exe_date;
            $static = config('static_data');
            $key_pre = $static['client_loan_account_prefix'];
            if(strpos( $loan_account->name, 'Leasing') != false){
                $key_pre = $static['client_leasing_account_prefix'];
            }
            $old_status = $status = intval($loan_account->status);
            if($status < $exp_status){
                for($status; $status < $exp_status; $status++){
                    // 'client_loan_account_status' => [
                    //     0 => 'Inactive',
                    //     1 => 'Open',
                    //     2 => 'Standard',
                    //     3 => 'Sub-Standard',
                    //     4 => 'Doubtful',
                    //     5 => 'Loss Loan',
                    //     6 => 'Write-Off',
                    //     7 => 'Completed',
                    //     8 => 'Closed'
                    // ],
                    // 'client_loan_account_prefix' => [
                    //     2 => 'Stand-L-',
                    //     3 => 'Sub-Stand-L-',
                    //     4 => 'Doubtful-L-',
                    //     5 => 'Loss-L-'
                    // ],
                    // 'client_leasing_account_prefix' => [
                    //     2 => 'Stand-Leasing-',
                    //     3 => 'Sub-Stand-Leasing-',
                    //     4 => 'Doubtful-Leasing-',
                    //     5 => 'Loss-Leasing-'
                    // ],
                    // 'provision_rate' => [ // old provision
                    //     2 => 0,
                    //     3 => 20,  // sub standard
                    //     4 => 30,  // doubtful
                    //     5 => 50   // loan loss
                    // ],
                    $new_coa = $new_int = $new_int_inc = 0;
                    $next_status = $status + 1; // Sub-Standard
                    $sub_loan_account = $loan_account;
                    $loan_account->prefix = $key_pre[$next_status]; //"Std-"
                    // Create new COA for Sub-Standard
                    // COA
                    $pre_prefix = $static['client_loan_account_pre_prefix']['coa']; //"";
                    $new_coa = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    //if($new_coa->new_flg == '0')
                    // AIR
                    $pre_prefix = $static['client_loan_account_pre_prefix']['air']; //"Air-";
                    $new_air = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    //var_dump($pre_prefix); dd($loan_account);
                    if($status >= GENERAL_LC_STATUS){ // From Sub-stand
                        // Income in suspense
                        $pre_prefix = $static['client_loan_account_pre_prefix']['suspense']; //'Int-In Sus-'
                        $new_int_sus = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    }
                    // Interest Income
                    $pre_prefix = $static['client_loan_account_pre_prefix']['interest']; //"Int-Inc-";
                    $new_int_inc = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    
                    // Add journal ( debit: sub_coa_id, credit: coa_id )
                    $desc_str = "provision " . $loan_account->loan_ref . "(" . $static['client_loan_account_status'][$status] . " -> " . $static['client_loan_account_status'][$next_status] . ")";
                    $journal_arr = [];
                    $new_coa_id = $new_coa->id;
                    $old_coa_id = $loan_account->coa_id;
                    $old_coa_amount = JournalDetail::selectRaw('sum(debit) - sum(credit) as bal')->where('coa_id',$old_coa_id)->first()->bal;
                    if(is_null($old_coa_amount)) $old_coa_amount = 0;
                    $new_coa_amount = $old_coa_amount;
                    if($old_coa_amount != 0){
                        array_push($journal_arr, [$new_coa_id, $old_coa_amount, $desc_str, $old_coa_id, $old_coa_amount, $desc_str, $desc_str]);
                        record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);
                        // Add journal ( debit: sub_air_id, credit: air_id )
                        $journal_arr = [];
                        $new_air_id = $new_air->id;
                        $old_air_id = $loan_account->air_id;
                        $old_air_amount = JournalDetail::selectRaw('sum(debit) - sum(credit) as bal')->where('coa_id',$old_air_id)->first()->bal;
                        if(is_null($old_air_amount)) $old_air_amount = 0;
                        $new_air_amount = $old_air_amount;
                        array_push($journal_arr, [$new_air_id, $old_air_amount, $desc_str, $old_air_id, $old_air_amount, $desc_str, $desc_str]);
                        record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);
                        // Add journal ( debit: int_inc_id, credit: int_sus_id )
                        $journal_arr = [];
                        if($status >= GENERAL_LC_STATUS){ // From Sub-stand
                            $new_int_inc_id = $new_int_sus->id;
                        }else{
                            $new_int_inc_id = $new_int_inc->id;
                        }
                        if($status > GENERAL_LC_STATUS){ // From Sub-stand
                            $old_int_inc_id = $loan_account->sus_id;
                        }else{
                            $old_int_inc_id = $loan_account->int_inc_id;
                        }
                        $old_int_inc_amount = JournalDetail::selectRaw('sum(credit) - sum(debit) as bal')->where('coa_id',$old_int_inc_id)->first()->bal;
                        //$old_int_amount = JournalDetail::selectRaw('sum(credit) - sum(debit) as bal')->where('coa_id',$old_int_id)->first()->bal;
                        if(is_null($old_int_inc_amount)) $old_int_inc_amount = 0;
                        array_push($journal_arr, [$old_int_inc_id, $old_int_inc_amount, $desc_str, $new_int_inc_id, $old_int_inc_amount, $desc_str, $desc_str]);
                        record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);
                    }
                    // Update loan_account
                    $loan_account->coa_id = $new_coa->id;
                    $loan_account->air_id = $new_air->id;
                    $loan_account->int_inc_id = $new_int_inc->id;
                    if($status >= GENERAL_LC_STATUS) $loan_account->sus_id = $new_int_sus->id;
                    $loan_account->status = $next_status;
                    $loan_account->save();
                }
                // Provision
                $prov_rate = 0;
                for($status = $old_status; $status < $exp_status; $status++){
                    $prov_rate += $static['provision_rate'][$status + 1];
                }
                $provision_amount = $prov_rate * $loan_account->balance / 100;
                $journal_arr = [];
                $desc_str = "provision " . $loan_account->loan_ref . "(" . $static['client_loan_account_status'][$old_status] . " -> " . $static['client_loan_account_status'][$next_status] . ")";
                $desc_str .= ' - prov amount('.$prov_rate.'% x '. $loan_account->balance . ' ) = '. $provision_amount;
                $pat1 = '%'.substr($loan_account->acc_key,0,7).'%';
                if(strpos($loan_account->acc_key,'<') !== false){
                    $pat2 = '%<%';
                }elseif(strpos($loan_account->acc_key,'>') !== false){
                    $pat2 = '%>%';
                }
                $debit_acc = CoaCategory::select('*')->where('name', 'LIKE', 'Exp-Bad and Doubtful Debt%')->where('name', 'LIKE', $pat1)->where('name', 'LIKE',  $pat2)->where('type', 6)->where('currency', $loan_account->currency)->first();
                $credit_acc = CoaCategory::select('*')->where('name', 'LIKE', '(Less) Specific Loan Loses%')->where('name', 'LIKE', $pat1)->where('name', 'LIKE',  $pat2)->where('type', 6)->where('currency', $loan_account->currency)->first();
                if(is_null($credit_acc)) $credit_acc = CoaCategory::select('*')->where('name', 'LIKE', '(Less) Specific Loans Loses%')->where('name', 'LIKE', $pat1)->where('name', 'LIKE',  $pat2)->where('type', 6)->where('currency', $loan_account->currency)->first();

                array_push($journal_arr, [$debit_acc->id, $provision_amount, $desc_str, $credit_acc->id, $provision_amount, $desc_str, $desc_str]);
                record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);
            }elseif($status > $exp_status){
                for($status; $status > $exp_status; $status--){
                    // 'client_loan_account_status' => [
                    //     0 => 'Inactive',
                    //     1 => 'Open',
                    //     2 => 'Standard',
                    //     3 => 'Sub-Standard',
                    //     4 => 'Doubtful',
                    //     5 => 'Loss Loan',
                    //     6 => 'Write-Off',
                    //     7 => 'Completed',
                    //     8 => 'Closed'
                    // ],
                    // 'client_loan_account_prefix' => [
                    //     2 => 'Stand-L-',
                    //     3 => 'Sub-Stand-L-',
                    //     4 => 'Doubtful-L-',
                    //     5 => 'Loss-L-'
                    // ],
                    // 'client_leasing_account_prefix' => [
                    //     2 => 'Stand-Leasing-',
                    //     3 => 'Sub-Stand-Leasing-',
                    //     4 => 'Doubtful-Leasing-',
                    //     5 => 'Loss-Leasing-'
                    // ],
                    // 'provision_rate' => [ // old provision
                    //     2 => 0,
                    //     3 => 20,  // sub standard
                    //     4 => 30,  // doubtful
                    //     5 => 50   // loan loss
                    // ],
                    $new_coa = $new_int = $new_int_inc = 0;
                    $next_status = $status - 1; // Standard
                    $loan_account->prefix = $key_pre[$next_status]; //"Std-"

                    // Create new COA for Sub-Standard
                    // COA
                    $pre_prefix = $static['client_loan_account_pre_prefix']['coa']; //"";
                    $new_coa = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    //if($new_coa->new_flg == '0')
                    // AIR
                    $pre_prefix = $static['client_loan_account_pre_prefix']['air']; //"Air-";
                    $new_air = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    // Interest Income or Interest in Suspense
                    if($status > GENERAL_LC_STATUS){ // From Sub-stand
                        // Income in suspense
                        $pre_prefix = $static['client_loan_account_pre_prefix']['suspense']; //'Int-In Sus-'
                        $new_int_sus = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    }
                    // Interest Income
                    $pre_prefix = $static['client_loan_account_pre_prefix']['interest']; //"Int-Inc-";
                    $new_int_inc = createLoanAccountCoa($loan_account, $pre_prefix)['coa'];
                    
                    // Add journal ( debit: coa_id, credit: sub_coa_id )
                    $desc_str = "provision back " . $loan_account->loan_ref . "(" . $static['client_loan_account_status'][$status] . " -> " . $static['client_loan_account_status'][$next_status] . ")";
                    $journal_arr = [];
                    $new_coa_id = $new_coa->id;
                    $old_coa_id = $loan_account->coa_id;
                    $old_coa_amount = JournalDetail::selectRaw('sum(debit) - sum(credit) as bal')->where('coa_id',$old_coa_id)->first()->bal;
                    if(is_null($old_coa_amount)) $old_coa_amount = 0;
                    $new_coa_amount = $old_coa_amount;
                    array_push($journal_arr, [$new_coa_id, $old_coa_amount, $desc_str, $old_coa_id, $old_coa_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);
                    // Add journal ( debit: air_id, credit: sub_air_id )
                    $journal_arr = [];
                    $new_air_id = $new_air->id;
                    $old_air_id = $loan_account->air_id;
                    $old_air_amount = JournalDetail::selectRaw('sum(debit) - sum(credit) as bal')->where('coa_id',$old_air_id)->first()->bal;
                    if(is_null($old_air_amount)) $old_air_amount = 0;
                    $new_air_amount = $old_air_amount;
                    array_push($journal_arr, [$new_air_id, $old_air_amount, $desc_str, $old_air_id, $old_air_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);
                    // Add journal ( debit: int_sus_id, credit: int_inc_id )
                    $journal_arr = [];
                    if($status > GENERAL_LC_STATUS + 1){ // From Sub-stand
                        $new_int_inc_id = $new_int_sus->id;
                        $old_int_inc_id = $loan_account->sus_id;
                    }elseif($status == GENERAL_LC_STATUS + 1){
                        $new_int_inc_id = $new_int_inc->id;
                        $old_int_inc_id = $loan_account->sus_id;
                    }else{
                        $new_int_inc_id = $new_int_inc->id;
                        $old_int_inc_id = $loan_account->int_inc_id;
                    }
                    $old_int_inc_amount = JournalDetail::selectRaw('sum(credit) - sum(debit) as bal')->where('coa_id',$old_int_inc_id)->first()->bal;
                    //$old_int_amount = JournalDetail::selectRaw('sum(credit) - sum(debit) as bal')->where('coa_id',$old_int_id)->first()->bal;
                    if(is_null($old_int_inc_amount)) $old_int_inc_amount = 0;
                    array_push($journal_arr, [$old_int_inc_id, $old_int_inc_amount, $desc_str, $new_int_inc_id, $old_int_inc_amount, $desc_str, $desc_str]);
                    record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);
                
                    // Update loan_account
                    $loan_account->coa_id = $new_coa->id;
                    $loan_account->air_id = $new_air->id;
                    $loan_account->int_inc_id = $new_int_inc->id;
                    $loan_account->sus_id = ($status > GENERAL_LC_STATUS + 1)? $new_int_sus->id:0;
                    $loan_account->status = $next_status;
                    $loan_account->save();

                }
                // Provision
                $prov_rate = 0;
                for($status = $old_status; $status > $exp_status; $status--){
                    $prov_rate += $static['provision_rate'][$status];
                }
                $provision_amount = $prov_rate * $loan_account->balance / 100;
                $journal_arr = [];
                $desc_str = "provision back" . $loan_account->loan_ref . "(" . $static['client_loan_account_status'][$old_status] . " -> " . $static['client_loan_account_status'][$next_status] . ")";
                $desc_str .= ' - prov amount('.$prov_rate.'% x '. $loan_account->balance . ' ) = '. $provision_amount;
                $pat1 = '%'.substr($loan_account->acc_key,0,7).'%';
                if(strpos($loan_account->acc_key,'<') !== false){
                    $pat2 = '%<%';
                }elseif(strpos($loan_account->acc_key,'>') !== false){
                    $pat2 = '%>%';
                }
                $credit_acc = CoaCategory::select('*')->where('name', 'LIKE', 'Exp-Bad and Doubtful Debt%')->where('name', 'LIKE', $pat1)->where('name', 'LIKE',  $pat2)->where('type', 6)->where('currency', $loan_account->currency)->first();
                $debit_acc = CoaCategory::select('*')->where('name', 'LIKE', '(Less) Specific Loan Loses%')->where('name', 'LIKE', $pat1)->where('name', 'LIKE',  $pat2)->where('type', 6)->where('currency', $loan_account->currency)->first();
                if(is_null($credit_acc)) $credit_acc = CoaCategory::select('*')->where('name', 'LIKE', '(Less) Specific Loans Loses%')->where('name', 'LIKE', $pat1)->where('name', 'LIKE',  $pat2)->where('type', 6)->where('currency', $loan_account->currency)->first();

                array_push($journal_arr, [$debit_acc->id, $provision_amount, $desc_str, $credit_acc->id, $provision_amount, $desc_str, $desc_str]);
                record_journal_no_trans($loan_account, $record_date, $journal_arr, $branch_id, $user_id, 1);
            }else{
            }
        }
    }

}
