<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Loan;
use App\Models\Products\Product_type;
use App\Models\Audit;
use App\Models\RepaymentSchedule;
use App\Models\RescheduleRepaymentTemp;
use App\Models\LoanStatus;
use App\Models\LoanApproval;
use App\Models\LoanRestructure;
use App\Models\DrawdownAccounts;
use App\Models\ClientLoanAccounts;
use Illuminate\Support\Facades\Log;
use App\Models\CoaCategory;
use App\Models\JournalRequiry;
use App\Models\LoanPayments;
use Auth;
use Request;
use DB;
use DateTime;
use Session;
use URL;
class LoanRescheduleApproveController extends Controller {

	public function getLoanReschedule()
    {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
        $query_arr = array('user_id' => Auth::user()->id, 'company_branch_id' => Auth::user()->branch_id, 'role_id' => Auth::user()->role_id);
        $B0 = new Loan();
        $loan = $B0->select([
            'loans.id',
            'loans.contract_id',
            'loans.start_date',
            'loans.loan_type',
            'loans.loan_amount',
            'loans.original_amount',
            'loans.interest_rate',
            'loans.loan_account_id',
            'loans.status',
            'loans.loan_duration',
            'submitted_on',
            'loans.disburse_date',
            'loans.rejected_date',
            'loans.client_id',
            'loans.updated_at',
            'clients.client_name',
            'clients.client_type',
            'loans.settlement_date',
            'rate_type'
        ])->with(['client_loan_account', 'payoff'])
        ->leftJoin('clients', 'clients.id', '=', 'loans.client_id')
        ->where('loans.status','7');
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
        $loan = $loan->paginate($offset)->setPath('?client_name='.$client_name.'&contract_id='.$contract_id.'&offset='.$offset);
        $product_type = Product_type::get();
        return $this->view('loans.loan_reschedule.index', ['loans' => $loan,'contract_id' => $contract_id, 'offset' => $offset, 'product_type' => $product_type]);
    }

    public function loan_reschedule_detail($loan_id = 0)
    {
        if (is_numeric($loan_id) && $loan_id > 0) {
            $loan = Loan::select(['loans.*', 'loan_approval.id as approval_id', 'loan_approval.approval_date'])
                ->leftJoin('loan_approval', 'loans.id', '=', 'loan_approval.loan_id')
                ->with(['co_user' => function ($query) {
                    $query->select('id', 'phone', 'name');
                }, 'client', 'branch', 'client_loan_account' => function ($q) {
                    $q->select('id', 'account_no', 'balance', 'currency');
                },'unittypes'])
                ->where('loans.id', '=', $loan_id)->first();
            $product_type = Product_type::select('id', 'code', 'products_type_name')->get();
            $product_type_arr = [];
            foreach ($product_type as $pt) {
                $product_type_arr[$pt->id] = $pt->products_type_name;
            }
            $audit = Audit::where('tbl', 'loans')->where('tbl_id', $loan_id)->orderBy('id', 'asc')->with(['audit1', 'audit2'])->get();
            $schedule_id = RescheduleRepaymentTemp::where('loan_id', $loan_id)->where('restructure_id',$loan->restructure_id)->first()->id;
            $restructure = LoanRestructure::find($loan->restructure_id);
            return $this->view('loans.loan_reschedule.detail', ['loan' => $loan, 'restructure' => $restructure, 'audit' => $audit, 'schedule_id' => $schedule_id, 'product_type_arr' => $product_type_arr]);
        }
        return $this->view('loans.loan_reschedule.detail', ['loan' => null]);
    }
    public function getRescheduleApprove($loan_id = ''){
        Session::flash('pre_url', URL::previous());
        if ($loan_id > 0) {
            $loan = Loan::select('id', 'status')->where('id', '=', $loan_id)->first();
            if (!empty($loan) && $loan->status == 7) { /* Unauthorized */
                return $this->view('loans.loan_reschedule.approval', ['loan' => $loan]);
            }
        }
        return redirect()->back();
    }

    public function rescheduleApproval($loan_id = ''){
        $data = Request::except(['_token']);
        $rules = [
            'approval_date' => 'required|date',
        ];
        $attribs = [
            'approval_date' => 'Approval Date',
        ];
        $validator = Validator::make($data, $rules);
        $validator->setAttributeNames($attribs);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $loan = Loan::where('id', '=', $loan_id)->with('client_loan_account')->first();
            $restructure = LoanRestructure::where('id','=',$loan->restructure_id)->first();
            $drawdown_acc = DrawdownAccounts::where('client_id', '=', $loan->client_id)->where('account_no',$loan->drawdown_acc)->first();
            if (round($drawdown_acc->balance, 2) < round($restructure->drawdown_principal_amount, 2)) {
                $balance = $drawdown_acc->currency_tbl->symbol . number_format($drawdown_acc->balance, 2, '.', ',');
                Session::flash('danger', 'Drawdown Principal Amount is ('.(number_format($restructure->drawdown_principal_amount, 2)).') Balance in Drawdown Account is not sufficient! Current Drawdown Account balance is ' . $balance);
                return redirect()->back();
            }
            if (!empty($loan) && $loan->status == 7) {
                // Reschedule(approved)
                    $napproval = new LoanApproval();
                    $napproval->loan_id = $loan_id;
                    if (Auth::check()) {
                        $napproval->user_id = Auth::user()->id;
                    } else {
                        return redirect()->route('login');
                    }
                    if (Request::has('approval_date')) {
                        $date = Request::input('approval_date');
                        $date = date('Y-m-d', strtotime($date));
                        $napproval->approval_date = $date;
                    }
                    if (Request::has('note')) {
                        $napproval->note = Request::input('note');
                    }
                    $napproval->created_at = date('Y-m-d H:i:s');
                // End Reschedule(approved)
                if(!$restructure){
                    return redirect()->route('reschedule_to_approve');
                }
                $loan->status = 8;
                $loan->reschedule_status = 1;
                $restructure->loan_data = json_encode($loan);
                $loan->loan_amount = ($loan->loan_amount + $restructure->interest + $restructure->penalty);
                $restructure->save();
                // $loan->disburse_date = $date;
                if ($napproval->save()) {
                // if (1 == 1) {
                    // ===== Update Loan 
                    $loan->repayment_type = $restructure->loan_repayment_type;
                    $loan->drawdown_principal_amount = $restructure->drawdown_principal_amount;
                    $loan->installment_duration = $restructure->loan_installment_duration;
                    $loan->interest_rate = $restructure->loan_interest_rate;
                    $loan->balloon = $restructure->loan_balloon;
                    $loan->balloon_month = $restructure->loan_balloon_month;
                    $loan->monthly_payment = $restructure->loan_monthly_payment;
                    $loan->down_payment_duration = $restructure->loan_down_payment_duration;
                    $loan->balloon_amount_array = $restructure->loan_balloon_amount_array;
                    $loan->custom_flag = $restructure->loan_custom_flag;
                    $loan->days_of_month = $restructure->loan_days_of_month;
                    $loan->holiday_flag = $restructure->loan_holiday_flag;
                    $loan->frequency = $restructure->loan_frequency;
                    $loan->monthly_amount = $restructure->loan_monthly_amount;
                    $loan->admin_fee = $restructure->loan_admin_fee;
                    $loan->maintain_fee = $restructure->loan_maintain_fee;
                    $loan->maintain_fee_opt = $restructure->loan_maintain_fee_opt;
                    $loan->admin_fee_opt = $restructure->loan_admin_fee_opt;
                    $loan->other_fee = $restructure->loan_other_fee;
                    if($loan->repayment_type == 7){
                        $loan->rate_type = "Annuity";
                    }else{
                        $loan->rate_type = "Declining";
                    }

                    $loan_amount = $restructure->old_loan_amount;
                    $down_payment = $restructure->down_payment;                    
                    // ===== End Update Loan
                    if ($loan->save()) {
                    // if (1 == 1) {
                        $scheduleToDelete = [];
                        $rescheduleTemp = RescheduleRepaymentTemp::where('loan_id',$loan->id)->where('restructure_id',$loan->restructure_id)->get();
                        $old_schedule = RepaymentSchedule::where('loan_id',$loan->id)->get();
                        $loan_installment_duration = $restructure->loan_installment_duration;
                        if($rescheduleTemp){ 
                            foreach ($rescheduleTemp as $reTemp) {
                                $data_schedule = [
                                    'no' => $reTemp->no,
                                    'loan_no' => $reTemp->loan_no, 
                                    'schedule_date' => $reTemp->schedule_date,
                                    'date_num' => $reTemp->date_num, 
                                    'beginning' => $reTemp->beginning,
                                    'interest' => $reTemp->interest,
                                    'principal' => $reTemp->principal, 
                                    'fee' => $reTemp->fee,
                                    'status' => $reTemp->status,
                                    'other_fee' => $reTemp->other_fee, 
                                    'intraday_rate' => $reTemp->intraday_rate,
                                    'balance' => $reTemp->balance,
                                    'type' => $reTemp->type,
                                    'is_restructure' =>1
                                ];

                                RepaymentSchedule::updateOrCreate(['loan_id' => $loan_id, 'no' => $reTemp->no],$data_schedule);
                                $loan_installment_duration = $reTemp->no;
                            }
                            RescheduleRepaymentTemp::where('loan_id',$loan->id)->where('restructure_id',$loan->restructure_id)->delete();
                            $scheduleToDelete = RepaymentSchedule::select('id')->where('loan_id',$loan_id)->where('no','>',$loan_installment_duration)->get();
                        }
                       
                        //  Log File
                        // Log::useDailyFiles(storage_path().'/logs/restructure/loan_id-'.$loan_id.'-'.Auth::user()->username.'-'.date('Y-m-d H:i:s').'.log');
                        // $dataToLog = [
                        //             'info'  => [
                        //                     'username'  => Auth::user()->username,
                        //                     'datetime'  => date('Y-m-d H:i:s'),
                        //                 ],
                        //             'beforupdate'   => $loanBeforUpdate,
                        //             'restructure'   => $scheduleToDelete,
                        //             'old_schedule'   => $old_schedule,
                        //             ];

                        // Log::info(json_encode($dataToLog));
                        //  End Log File
                        if($scheduleToDelete){
                            RepaymentSchedule::whereIn('id',array_column($scheduleToDelete->toArray(),'id'))->where('loan_id',$loan_id)->delete();
                        }

                        $loan_acc = ClientLoanAccounts::select(['id', 'loan_ref'])->where('id', '=', $loan->loan_account_id)->first();
                        $loan_acc->status = 2;
                        if ($loan_acc->save()) {
                            $this->do_audit($loan_id, '', Auth::user()->id, 'reschedule_loan', 1, 'authorize reschedule loan');
                        }
                        $this->userActivity($napproval->user_id, $loan_id, 6, 'Reschedule(Approved) Loan');

                        $journal_arr = [];
                        $amount = floatval(Request::input('amount'));

                        //  ====== Journal =========
                        $coa_dd = CoaCategory::find($drawdown_acc->coa_id);
                        $loan_account = $loan->client_loan_account;
                        $coa = CoaCategory::find($loan_account->coa_id);

                        $desc = "Restructure Loan : " . $restructure->note;
                        $drawdown_principal_amount = $restructure->drawdown_principal_amount;
                        if($drawdown_principal_amount > 0){
                            $params = array(
                                'debit' => $drawdown_principal_amount,
                                'credit' => $drawdown_principal_amount,
                                'parent_debit' => $coa_dd->id,
                                'parent_credit' => $coa->id,
                                'parent_debit_label' => $coa_dd->name . ' (' . $branch_code . $coa_dd->account_code . ')',
                                'parent_credit_label' => $coa->name . ' (' . $branch_code . $coa->account_code . ')',
                                'd_description'=> "Reschedule Loan - ". $loan->client_loan_account->account_name . " - ". $loan->contract_id,
                                'c_description'=> "Reschedule Loan - ". $loan->client_loan_account->account_name . " - ". $loan->contract_id,
                                'description'=> $desc,
                            );

                            array_push($journal_arr, [
                                                        $params['parent_debit'],
                                                        $params['debit'],
                                                        $params['d_description'],
                                                        $params['parent_credit'],
                                                        $params['credit'],
                                                        $params['c_description'],
                                                        $params['description'],
                                                    ]);

                            $principal_data = [
                                'act_interest'  => 0,
                                'act_principal' => $drawdown_principal_amount,
                                'act_fee'       => 0,
                                'act_other_fee' => 0,
                                'act_penalty'   => 0,
                                'act_total'     => $drawdown_principal_amount,
                                'note'          => $desc
                            ];

                            $journal_id = JournalRequiry::max('id') + 1;
                            $loan_payment = new LoanPayments();
                            $loan_payment->loan_id = $loan_id;
                            $loan_payment->invoice_number = str_pad($journal_id, 8, '0', STR_PAD_LEFT);
                            $loan_payment->repayment_date = $date;
                            $loan_payment->payment_month = $restructure->month_idx;
                            $loan_payment->paid_principal = $drawdown_principal_amount;
                            $loan_payment->paid_interest = 0;
                            $loan_payment->paid_fee = 0;
                            $loan_payment->loan_repayment_type = 'loan';
                            $loan_payment->paid_other_fee = 0;
                            $loan_payment->penalty_amount = 0;
                            $loan_payment->payment_type = 6;
                            $loan_payment->status = 1;
                            $loan_payment->condition_id = 0;
                            $loan_payment->repayment_owed = 0;
                            $loan_payment->waived_penalty = 0;
                            $loan_payment->save();

                            record_journal($loan, $date, "Reschedule Loan", $principal_data, $journal_arr, $loan->company_branch_id, Auth::user()->id, 1, 'loan');
                            $drawdown_acc->balance = round($drawdown_acc->balance,2) - round($drawdown_principal_amount);
                            $drawdown_acc->save();
                        }

                        //  ===== End Journal ======


                        // array_push($journal_arr, [$loan->client_loan_account->coa_id, $amount, Request::input('note'),
                        //     $drawdown_acc->coa_id, $amount, Request::input('note'),
                        //     Request::input('note')]);
                        // record_journal($loan, $date, $type = "Reschedule Loan", $data, $journal_arr, Auth::user()->branch_id, Auth::user()->id);
                        // update drawdown account

                                    // $drawdown_acc->balance = floatval($drawdown_acc->balance) - floatval($cl->amount);
                                    // $drawdown_acc->save();
                        
                            // if (Request::has('amount')) {
                            //     $transaction = new TransactionsRequiry();
                            //     $transaction->loan_id = $loan_id;
                            //     $transaction->trans_date = $napproval->approval_date;
                            //     $transaction->trans_type = "Reschedule(Approved)";
                            //     $transaction->amount = $amount;
                            //     $transaction->description = $napproval->note;
                            //     $transaction->balance = $amount;
                            //     $transaction->user_id = $napproval->user_id;
                            //     if ($transaction->save()) {
                            //         Session::flash('message', 'Loan Reschedule Approve Successfully !');
                            //         return redirect()->route('loan_detail', [$loan_id]);
                            //     } else {
                            //         $loan->status = 7;
                            //         $loan->save();
                            //         $napproval->delete();
                            //         return redirect()->route('reschedule_to_approve');
                            //     }
                            // } else {
                            //     return redirect()->route('reschedule_to_approve');
                            // }
                        $loan_account = ClientLoanAccounts::select('id', 'balance')->where('id', $loan->client_loan_account->id)->first();
                        if($loan_account){
                            $loan_account->balance = ($loan_account->balance + $restructure->interest + $restructure->penalty) - $drawdown_principal_amount;
                            $loan_account->balance_downpayment = $down_payment;
                            if (CLASS_NEW_PRAKAS == 1) {
                                $loan_account->status = 7; // closed
                            } else {
                                $loan_account->status = 7; // closed
                            }
                            $loan_account->save();
                        }

                        Session::flash('message', 'Loan Reschedule Approve Successfully !');
                        return redirect()->route('loan_detail', [$loan_id])->with(['msg' => 'Loan Reschedule Approve Successfully !']);
                    }
                }
            }
        }
        return redirect()->route('reschedule_to_approve');
    }

    public function getRescheduleReject($loan_id = ''){
        Session::flash('pre_url', URL::previous());
        if ($loan_id > 0) {
            $loan = Loan::select('id', 'status')->where('id', '=', $loan_id)->first();
            if (!empty($loan) && $loan->status == 7) { /* Unauthorized */
                return $this->view('loans.loan_reschedule.reject', ['loan' => $loan,'id'=>$loan_id]);
            }
        }
        return redirect()->back();
    }
    
    public function rescheduleRejected($loan_id = ''){
         $data = Request::except(['_token']);
        $rules = [
            'reject_date' => 'required|date',
        ];
        $attribs = [
            'reject_date' => 'Reject Date',
        ];
        $validator = Validator::make($data, $rules);
        $validator->setAttributeNames($attribs);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $loan = Loan::where('id', $loan_id)->first();
            if (!empty($loan) && $loan->status == 7) {
                $loan->status = 8;
                $loan->reschedule_status = 0;
                if($loan->save()) {
                    $rescheduleTemp = RescheduleRepaymentTemp::where('loan_id',$loan->id)->where('restructure_id',$loan->restructure_id)->delete();
                    $this->userActivity(Auth::user()->id, $loan_id, 6, 'Reject Reschedule Loan');
                    $loanStatus = new LoanStatus;
                    $loanStatus->loan_id = $loan_id;
                    $loanStatus->description = Request::input('note');
                    $loanStatus->created_by = Auth::user()->id;
                    $loanStatus->save();
                    return redirect()->route('loan_detail', [$loan_id])->with(['msg' => 'Reject success']);
                }
            }else{
                return redirect('/');
            }
        }
    }

}
