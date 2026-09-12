<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 5/25/2015
 * Time: 5:41 PM
 */

namespace App\Http\Controllers;


//use App\Models\CompanyBranch;
//use App\Models\LoanApproval;
//use App\Models\LoanCollateral;
use App\Models\LoanCostFee;
//use App\Models\LoanDocument;
//use App\Models\LoanPayments;
use App\Models\LoanWriteOff;
use App\Models\LoanClose;
//use App\Models\LoanPayOff;
//use App\Models\Product;
//use App\Models\GuarantorCollateral;
//use App\Models\Guarantor;
//use App\Models\User;
use App\Models\JournalRequiry;
use App\Models\JournalDetail;
//use App\Models\Client;
//use App\Models\Holiday;
use App\Models\TransactionsRequiry;
//use App\Models\Account;
//use App\Models\CoaCategory;
//use Illuminate\Support\Facades\Session;
//use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Collection;
use App\Models\Loan;
//use App\Models\RepaymentSchedule;
//use App\Models\ClientLoanAccounts;
//use App\Models\Teller;
use App\Models\DrawdownAccounts;
//use App\Models\Audit;
//use App\Models\LoanPaymentsDraft;
use App\Models\ScheduleFee;
use App\Models\Notification;
use Request;
//use App\Http\Requests\Request;
use Auth;
use Image;
use App;
use App\Models\FeeCharge;
use URL;
use File;
use LoanCalculate;
use DB;

class AuditController extends Controller
{
    public function __construct()
    {
        $this->user_id = Auth::user()->id;
        $this->middleware('xss');
        $this->middleware('auth');

        $this->tbl = Request::input('tbl');
        $this->id = Request::input('id');
        $this->action = Request::input('action');
        $this->user_id = Request::input('user_id');
    }

    function index(){
        switch ($this->tbl) {
            case 'journal_requiry':
                $this->journal_requiry();
                break;

            case 'transactions_requiry':
                $this->transactions_requiry();
                break;

            default:
                dd('No...');
        }

        return redirect()->back();
    }

    function journal_requiry() {

        if($this->action==1){
            $journal = JournalRequiry::where('id', $this->id);
            $journal->update(['is_audit'=>1]);

            $t = TransactionsRequiry::where('jid', $this->id)->first();

            $tran = TransactionsRequiry::where('jid', $this->id);
            $tran->update(['is_audit'=>1]);

            $jd = JournalDetail::where('journal_id', $this->id);
            $jd->update(['is_audit'=>1]);
            $journal_detail = $jd->get();

            if($journal_detail->count() > 0){
                foreach ($journal_detail as $jdetail) {
                    if($jdetail->debit > 0){
                        $drawdown_acc = DrawdownAccounts::where('coa_id', $jdetail->coa_id)->first();
                        if($drawdown_acc){
                            $drawdown_acc->balance = $drawdown_acc->balance - $jdetail->debit;
                            $drawdown_acc->save();
                        }
                    }
                    if($jdetail->credit > 0){
                        $drawdown_acc = DrawdownAccounts::where('coa_id', $jdetail->coa_id)->first();
                        if($drawdown_acc){
                            $drawdown_acc->balance = $drawdown_acc->balance + $jdetail->credit;
                            $drawdown_acc->save();
                        }
                    }
                }
            }
            if($t && $t->drawdown_acc_id){
                $da = DrawdownAccounts::find($t->drawdown_acc_id);
                $drawdown_acc = DrawdownAccounts::where('id', $t->drawdown_acc_id);
                $drawdown_acc->update(['balance'=> $da->balance - $t->charge_amount]);
            }

            if($t && $t->charge_id){
                $charge = FeeCharge::where('id', $t->charge_id);
                $charge->update(['is_audit'=>1]);
            }

            if($t && $t->cost_id){
                $charge = LoanCostFee::where('id', $t->cost_id);
                $charge->update(['is_audit'=>1]);
            }

            if($t && $t->write_off_id){
                $w = LoanWriteOff::where('id', $t->write_off_id);
                $w->update(['is_audit'=>1]);

                $loan = Loan::where('id', $t->loan_id);
                $loan->update(['status'=>5]);
            }

            if($t && $t->close_id){
                $cl = LoanClose::where('id', $t->close_id);
                $cl->update(['is_audit'=>1]);

                $loan = Loan::where('id', $t->loan_id);
                $loan->update(['status'=>6]);
            }

            $this->do_audit($this->id, $this->user_id, Auth::user()->id, $this->tbl, 1); //save to audit tbl
            $this->deleteNotification($this->id);
        }else{
            $journal = JournalRequiry::where('id', $this->id);
            $journal->delete(); 
            
            $t = TransactionsRequiry::where('jid', $this->id)->first();

            $tran = TransactionsRequiry::where('jid', $this->id);
            $tran->update(['flag'=>0, 'jid'=>'']);
            
            $jd = JournalDetail::where('journal_id', $this->id);
            $jd->delete();

            $c1 = FeeCharge::where('id', $t->charge_id);
            $c1->delete();

            $co1 = LoanCostFee::where('id', $t->cost_id);
            $co1->delete();

            $w = LoanWriteOff::where('id', $t->write_off_id);
            $w->delete();

            $cl = LoanClose::where('id', $t->close_id);
            $cl->delete();

            $this->do_audit($this->id, $this->user_id, Auth::user()->id, $this->tbl, 2); //update to audit tbl
            $this->deleteNotification($this->id);
        }
    }
    private function deleteNotification($id){

        return Notification::where('n_source_id', $id)->where('n_activity_type', 'Add_Journal')->delete();

    }

    function transactions_requiry(){
        if($this->action==1){
            $t = TransactionsRequiry::find($this->id);
            
            $tran = TransactionsRequiry::where('id', $this->id);
            $tran->update(['is_audit'=>1]);

            $journal = JournalRequiry::where('tran_id', $this->id);
            $journal->update(['is_audit'=>1]);

            $jd = JournalDetail::where('journal_id', $journal->id);
            $jd->update(['is_audit'=>1]);

            $fee = ScheduleFee::where('transaction_id', $this->id);
            $fee->update(['is_audit'=>1]);

            if($t && $t->drawdown_acc_id){
                $da = DrawdownAccounts::find($t->drawdown_acc_id);
                $drawdown_acc = DrawdownAccounts::where('id', $t->drawdown_acc_id);
                $drawdown_acc->update(['balance'=> $da->balance - $t->charge_amount]);
            }

            if($t && $t->charge_id){
                $charge = FeeCharge::where('id', $t->charge_id);
                $charge->update(['is_audit'=>1]);
            }

            if($t && $t->cost_id){
                $charge = LoanCostFee::where('id', $t->cost_id);
                $charge->update(['is_audit'=>1]);
            }

            if($t && $t->write_off_id){
                $w = LoanWriteOff::where('id', $t->write_off_id);
                $w->update(['is_audit'=>1]);

                $loan = Loan::where('id', $t->loan_id);
                $loan->update(['status'=>5]);
            }

            if($t && $t->close_id){
                $cl = LoanClose::where('id', $t->close_id);
                $cl->update(['is_audit'=>1]);

                $loan = Loan::where('id', $t->loan_id);
                $loan->update(['status'=>6]);
            }

            $this->do_audit($this->id, $this->user_id, Auth::user()->id, $this->tbl, 1); //save to audit tbl
        }else{ 
            $tran = TransactionsRequiry::where('id', $this->id);
            $tran->delete();

            $journal = JournalRequiry::where('tran_id', $this->id);
            $journal->delete();
            
            $jd = JournalDetail::where('journal_id', $journal->id);
            $jd->delete();

            $fee = ScheduleFee::where('transaction_id', $this->id);
            $fee->delete();

            $c1 = FeeCharge::where('id', $t->charge_id);
            $c1->delete();

            $co1 = LoanCostFee::where('id', $t->cost_id);
            $co1->delete();

            $w = LoanWriteOff::where('id', $t->write_off_id);
            $w->delete();

            $cl = LoanClose::where('id', $t->close_id);
            $cl->delete();

            $this->do_audit($this->id, $this->user_id, Auth::user()->id, $this->tbl, 2); //update to audit tbl
        }
    }
}
