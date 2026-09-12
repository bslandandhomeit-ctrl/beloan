<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use App\Models\Loan;
use App\Models\LoanPayments;
use Request;

class CustomerController extends Controller {

    public function list_customer_statement_summary() {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 10;
        $exchange_rate = Request::has('exchange_rate')?Request::input('exchange_rate'):4000;
        $till_date = Request::has('till_date')?Request::input('till_date'):date('Y-m-d');
        $till_date1 = date('Y-m-d', strtotime($till_date . '+1 day'));
        
        $customer_statement_summary = Loan::select('loans.*', 'client_loan_accounts.account_name')
                ->whereIn('loans.status', [3,5,8])
                ->join('client_loan_accounts', 'client_loan_accounts.id', '=', 'loans.loan_account_id')
                ->with('client_loan_account')
                ->with(['co_user' => function($que){
                    $que->select('id', 'name');
                }])
                ->with(['product' => function($que){
                    $que->select('id', 'product_name');
                }])
                ->with(['product_type' => function($que){
                    $que->select('id', 'code');
                }])
                ->with(['schedule' => function($p) use($till_date1){
                      $p->where('schedule_date', '<', $till_date1)
                      ->orderBy('schedule_date', 'DESC')->get();
                    }])
                ->with(['transaction' => function($p) use($till_date1){
                      $p->whereIn('transactions_requiry.trans_type', ["Auto Loan Repayment", "Loan Repayment", "Arrears Repayment", "Pay-Off", "Close"])
                        ->where('transactions_requiry.trans_date', '<', $till_date1)
                        ->orderBy('trans_date', 'DESC')->get();
                    }]);
        if (Request::has('customerRef')){
            $customerRef = Request::input('customerRef');
            $customer_statement_summary = $customer_statement_summary->where('client_loan_accounts.account_name', 'LIKE', '%'.$customerRef.'%')->Orwhere('loans.contract_id', 'LIKE', '%'.$customerRef.'%');
        }
        $customer_statement_summary = $customer_statement_summary->paginate($offset);
        //dd($customer_statement_summary);
        return $this->view('loans.list_customer_statement_summary', ['customer_statement_summary' => $customer_statement_summary, 'offset' => $offset, 'search' => $customerRef, 'exchange_rate' => $exchange_rate, 'till_date' => $till_date]);
    }

    public function detail_customer_old($id) {
        $detail_customers = Loan::join('client_loan_accounts', 'loans.loan_account_id', '=', 'client_loan_accounts.id')
                ->join('repayment_schedule', 'loans.id', '=', 'repayment_schedule.loan_id')
                ->select('client_loan_accounts.account_name', 'contract_id', 'interest_rate', 'loan_amount', 'repayment_schedule.*')
                ->where('loans.id', $id)
                ->get();
        $i = 0;
        foreach ($detail_customers as $c) {
            $i++;
            $data['repayment'][$i] = LoanPayments::where('payment_month', $i)
                    ->where('loan_id', $id)
                    ->get();
        }

        $data['detail_customers'] = $detail_customers;

        return $this->view('loans.detail_customer_statement', $data);
    }public function detail_customer($id) {
        $loan = Loan::where('id', $id)->first();

        $data['loan'] = $loan;

        return $this->view('loans.detail_customer_statement', $data);
    }
}
