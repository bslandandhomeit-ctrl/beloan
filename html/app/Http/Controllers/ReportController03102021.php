<?php

namespace App\Http\Controllers;

/* set_time_limit(200); */

use App\Models\CoaCategory;
use App\Models\Loan;
use App\Models\LoanClose;
use App\Models\LoanPayOff;
use App\Models\LoanWriteOff;
use App\Models\FeeCharge;
use App\Models\LoanCostFee;
use App\Models\ProductCategory;
use App\Models\Products\Product_type;
use App\Models\User;
use App\Models\LoanPayments;
use App\Models\CompanyBranch;
use App\Models\Currency;
use App\Models\TransactionsRequiry;
use App\Models\JournalRequiry;
use App\Models\JournalDetail;
use App\Models\Account;
use App\Models\RepaymentSchedule;
use App\Models\DealerBanks;
use Illuminate\Support\Collection;
use Request;
use DB;
use Auth;
use LoanCalculate;
class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getDisbursement()
    {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
        $data['branch'] = CompanyBranch::where('status', '=', 1)->get(['id', 'branch_name']);
        $disbursement = Loan::
            whereIn('status', [3,9,10])
            ->with(['client' => function ($query) {
                $query->select('id', 'client_name');
            }, 'product' => function ($query) {
                $query->select('id', 'product_name', 'product_type_id');
            }, 'dealer' => function ($query) {
                $query->select('id', 'dealer');
            }, 'client_loan_account' => function ($query) {
                $query->with('currencies');
            }, 'co_user']);
        $query_url = [];
        if (Request::has('dpStart') && Request::has('dpEnd')) {
            $data['start'] = Request::input('dpStart');
            $data['end'] = Request::input('dpEnd');
            $disbursement->whereBetween('disburse_date', [$data['start'], $data['end']]);
            $query_url['dpStart'] = $data['start'];
            $query_url['dpEnd'] = $data['end'];
        } else if (Request::has('dpStart')) {
            $data['start'] = Request::input('dpStart');
            $disbursement->whereBetween('disburse_date', '>=', $data['start']);
            $query_url['dpStart'] = $data['start'];
        } else if (Request::has('dpEnd')) {
            $data['end'] = Request::input('dpEnd');
            $disbursement->whereBetween('disburse_date', '<=', $data['end']);
            $query_url['dpEnd'] = $data['end'];
        } else {
            $data['start'] = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
            $data['end'] = date('Y-m-d');
            $disbursement->whereBetween('disburse_date', [$data['start'], $data['end']]);
        }
        $disbursement->with(['loanDealer' => function ($query) {
            $query->with(['bank']);
        }]);

        if (Request::has('selBrand')) {
            $data['branch_id'] = Request::input('selBrand');
            $disbursement->where('company_branch_id', '=', $data['branch_id']);
            $query_url['selBrand'] = $data['branch_id'];
        }
        $data['co_id'] = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();
        $data['disbursement'] = $disbursement->paginate($offset);
        $data['disbursement']->appends($query_url);
        $data['bank_dealer'] = DealerBanks::with('bank')->get();
		$product_type = Product_type::get();
		$pro_type = [];
		foreach($product_type as $pt){
			$pro_type[$pt->id] = $pt->code;
		}
		$data['pro_type'] = $pro_type;
        return $this->view('reports.disbursement', $data,['offset'=>$offset]);
    }

    public function getPayOff()
    {
        $data['branch'] = CompanyBranch::select('id', 'branch_name')->where('status', '=', 1)->get();
        $payoff = Loan::select('id', 'client_id', 'loan_amount', 'interest_rate', 'loan_type', 'contract_id', 'submitted_on', 'loan_duration', 'settlement_date')
            ->where('status', '=', 9)
            ->with(['client' => function ($query) {
                $query->select('id', 'client_name');
            }, 'payoff']);
        $query_url = [];
        if (Request::has('dpStart') && Request::has('dpEnd')) {
            $data['start'] = Request::input('dpStart');
            $data['end'] = Request::input('dpEnd');
            $payoff->whereBetween('settlement_date', [$data['start'], $data['end']]);
            $query_url['dpStart'] = $data['start'];
            $query_url['dpEnd'] = $data['end'];
        } else if (Request::has('dpStart')) {
            $data['start'] = Request::input('dpStart');
            $payoff->where('settlement_date', $data['start']);
            $query_url['dpStart'] = $data['start'];
        } else if (Request::has('dpEnd')) {
            $data['end'] = Request::input('dpEnd');
            $payoff->where('settlement_date', $data['end']);
            $query_url['dpEnd'] = $data['end'];
        } else {
            $data['start'] = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
            $data['end'] = date('Y-m-d');
            $payoff->whereBetween('settlement_date', [$data['start'], $data['end']]);
        }
        if (Request::has('selBrand')) {
            $data['branch_id'] = Request::input('selBrand');
            $payoff->where('company_branch_id', '=', $data['branch_id']);
            $query_url['selBrand'] = $data['branch_id'];
        }
        $data['payoff'] = $payoff->paginate(1000000);
        $data['payoff']->appends($query_url);
        $data['co_id'] = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();
        return $this->view('reports.payoff', $data);
    }

    public function getWriteOff()
    {
        $data['co'] = User::select('id', 'name')->where('role_id', '=', 10)->get();
        $data['branch'] = CompanyBranch::select('id', 'branch_name')->where('status', '=', 1)->get();
        $loan_writeoff = Loan::select('id', 'client_id', 'contract_id', 'loan_account_id', 'start_date', 'loan_amount', 'interest_rate', 'status', 'company_branch_id', 'co')
            ->where('status', CLOSE_L_STATUS-1) // Write-off = CLOSE_L_STATUS-1
            ->with(['writeoff', 'client_loan_account','client' => function ($query) {
                $query->select('id', 'client_name');
            }, 'payment' => function ($query) {
                $query->select('id', 'loan_id', 'paid_interest', 'paid_principal', 'penalty_amount', 'repayment_date');
            }]);
        $coa_charged_off = CoaCategory::select('id', 'name', 'type')->where('name', '=', 'Recovery on Loans Previously Charged-Off')->where('type', 6)->get();
        $coa_wo_arr = [];
        foreach($coa_charged_off as $coa){
            $coa_wo_arr[] = $coa->id;
        }
        $jd_wo_all = JournalDetail::whereIn('coa_id', $coa_wo_arr);
        

        //dd($jd_wo_all->journal);
        $jd_wo_all_pre = clone $jd_wo_all;
        $query_url = [];
        if (Request::has('dpStart') && Request::has('dpEnd')) {
            $data['start'] = Request::input('dpStart');
            $data['end'] = Request::input('dpEnd');
            // $loan_writeoff->whereHas('writeoff', function ($query) use ($data) {
            //     $query->whereBetween('write_off_date', [$data['start'], $data['end']]);
            // });
            $jd_wo_all_pre->whereHas('journal', function ($query) use ($data) {
                $query->where('entry_date', '<', $data['start']);
            });
            $jd_wo_all->whereHas('journal', function ($query) use ($data) {
                $query->whereBetween('entry_date', [$data['start'], date('Y-m-d', strtotime($data['end'] . ' +1 day'))]);
            });
            $query_url['dpStart'] = $data['start'];
            $query_url['dpEnd'] = $data['end'];
        } else if (Request::has('dpStart')) {
            $data['start'] = Request::input('dpStart');
            // $loan_writeoff->whereHas('writeoff', function ($query) use ($data) {
            //     $query->where('write_off_date', '=', $data['start']);
            // });
            $jd_wo_all_pre->whereHas('journal', function ($query) use ($data) {
                $query->where('entry_date', '<', $data['start']);
            });
            $jd_wo_all->whereHas('journal', function ($query) use ($data) {
                $query->where('entry_date', '=>', $data['start']);
            });
            $query_url['dpStart'] = $data['start'];

        } else if (Request::has('dpEnd')) {
            $data['end'] = Request::input('dpEnd');
            // $loan_writeoff->whereHas('writeoff', function ($query) use ($data) {
            //     $query->where('write_off_date', '=', $data['end']);
            // });
            $jd_wo_all_pre->whereHas('journal', function ($query) use ($data) {
                $query->where('entry_date', '<', MFI_START_DATE);
            });
            $jd_wo_all->whereHas('journal', function ($query) use ($data) {
                $query->where('entry_date', '<=', $data['end']);
            });
            $query_url['dpEnd'] = $data['end'];
        } else {
            $data['start'] = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
            $data['end'] = date('Y-m-d');
            // $loan_writeoff->whereHas('writeoff', function ($query) use ($data) {
            //     $query->whereBetween('write_off_date', [$data['start'], $data['end']]);
            // });
            $jd_wo_all_pre->whereHas('journal', function ($query) use ($data) {
                $query->where('entry_date', '<', $data['start']);
            });
            $jd_wo_all->whereHas('journal', function ($query) use ($data) {
                $query->whereBetween('entry_date', [$data['start'], date('Y-m-d', strtotime($data['end'] . ' +1 day'))]);
            });
        }
        if (Request::has('co')) {
            $data['co_id'] = Request::input('co');
            $loan_writeoff->where('co', '=', $data['co_id']);
            $query_url['co'] = $data['co_id'];
        }
        if (Request::has('branch')) {
            $data['branch_id'] = Request::input('branch');
            $loan_writeoff->where('company_branch_id', '=', $data['branch_id']);
            $query_url['branch'] = $data['branch_id'];
        }
        $data['co_phone'] = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();
        $data['jd_wo_all_pre'] = $jd_wo_all_pre->get();
        $data['jd_wo_all'] = $jd_wo_all->orderBy('id', 'DESC')->get();
        $data['loan_writeoff'] = $loan_writeoff->paginate(1000000);
        $data['loan_writeoff']->appends($query_url);
        return $this->view('reports.writeoff', $data);
    }

    public function getClosed()
    {
        $data['branch'] = CompanyBranch::where('status', '=', 1)->get(['id', 'branch_name']);
        $closed = Loan::select('id', 'client_id', 'contract_id', 'loan_type', 'settlement_date', 'loan_amount', 'interest_rate', 'start_date', 'submitted_on', 'loan_duration')
            ->where('status', '=', 6)->with(['client'])
            ->with(['payment' => function ($query) {
                $query->select('id', 'loan_id', 'paid_interest', 'paid_principal', 'penalty_amount', 'repayment_date');
            }]);
        $query_url = [];
        if (Request::has('dpStart') && Request::has('dpEnd')) {
            $data['start'] = Request::input('dpStart');
            $data['end'] = Request::input('dpEnd');
            $closed->whereBetween('settlement_date', [$data['start'], $data['end']]);
            $query_url['dpStart'] = $data['start'];
            $query_url['dpEnd'] = $data['end'];
        } else if (Request::has('dpStart')) {
            $data['start'] = Request::input('dpStart');
            $closed->where('settlement_date', $data['start']);
            $query_url['dpStart'] = $data['start'];
        } else if (Request::has('dpEnd')) {
            $data['end'] = Request::input('dpEnd');
            $closed->where('settlement_date', $data['end']);
            $query_url['dpEnd'] = $data['end'];
        } else {
            $data['start'] = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
            $data['end'] = date('Y-m-d');
            $closed->whereBetween('settlement_date', [$data['start'], $data['end']]);
        }
        if (Request::has('selBrand')) {
            $data['branch_id'] = Request::input('selBrand');
            $closed->where('company_branch_id', '=', $data['branch_id']);
            $query_url['selBrand'] = $data['branch_id'];
        }
        $data['closed'] = $closed->paginate(1000000);
        $data['closed']->appends($query_url);
        $data['co_id'] = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();
        return $this->view('reports.closed', $data);
    }

    public function getCompleted()
    {
        $data['branch'] = CompanyBranch::where('status', '=', 1)->get(['id', 'branch_name']);
        $completed = Loan::select('id', 'client_id', 'contract_id', 'loan_type', 'settlement_date', 'loan_amount', 'interest_rate', 'start_date', 'submitted_on', 'loan_duration')
            ->where('status', '=', 10)
            ->with(['client'])
            ->with(['payment' => function ($query) {
                $query->select('id', 'loan_id', 'paid_interest', 'paid_principal', 'penalty_amount', 'repayment_date');
            }]);
        $query_url = [];
        if (Request::has('dpStart') && Request::has('dpEnd')) {
            $data['start'] = Request::input('dpStart');
            $data['end'] = Request::input('dpEnd');
            $completed->whereBetween('settlement_date', [$data['start'], $data['end']]);
            $query_url['dpStart'] = $data['start'];
            $query_url['dpEnd'] = $data['end'];
        } else if (Request::has('dpStart')) {
            $data['start'] = Request::input('dpStart');
            $completed->where('settlement_date', $data['start']);
            $query_url['dpStart'] = $data['start'];
        } else if (Request::has('dpEnd')) {
            $data['end'] = Request::input('dpEnd');
            $completed->where('settlement_date', $data['end']);
            $query_url['dpEnd'] = $data['end'];
        } else {
            $data['start'] = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
            $data['end'] = date('Y-m-d');
            $completed->whereBetween('settlement_date', [$data['start'], $data['end']]);
        }
        if (Request::has('selBrand')) {
            $data['branch_id'] = Request::input('selBrand');
            $completed->where('company_branch_id', '=', $data['branch_id']);
            $query_url['selBrand'] = $data['branch_id'];
        }
        $data['completed'] = $completed->paginate(1000000);
        $data['completed']->appends($query_url);
        $data['co_id'] = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();
        return $this->view('reports.completed', $data);
    }

    public function getIncomeStatementYTD()
    {

        $trans = TransactionsRequiry::select(DB::raw('SUM(interest) as t_interest'));
        $feecharge = FeeCharge::select(DB::raw('SUM(charge_amount) as t_charge_amount'));
        $costfee = LoanCostFee::select(DB::raw('SUM(cost_amount) as t_cost_amount'));
        $writeoff = LoanWriteOff::select(DB::raw('SUM(amount) as t_amount'));
        $payoff = LoanPayOff::select(DB::raw('SUM(payoff_fee) as t_payoff_fee'));
        $payment = LoanPayments::select(DB::raw('SUM(penalty_amount) as t_penalty_amount'));

        if (Request::has('dpStart') && Request::has('dpEnd')) {
            $data['start'] = Request::input('dpStart');
            $data['end'] = Request::input('dpEnd');

            $trans->whereBetween('trans_date', [$data['start'], $data['end']]);
            $feecharge->whereBetween('charge_date', [$data['start'], $data['end']]);
            $costfee->whereBetween('cost_date', [$data['start'], $data['end']]);
            $writeoff->whereBetween('write_off_date', [$data['start'], $data['end']]);
            $payoff->whereBetween('payoff_date', [$data['start'], $data['end']]);
            $payment->whereBetween('repayment_date', [$data['start'], $data['end']]);
        } else if (Request::has('dpStart')) {
            $data['start'] = Request::input('dpStart');

            $trans->where('trans_date', $data['start']);
            $feecharge->where('charge_date', $data['start']);
            $costfee->where('cost_date', $data['start']);
            $writeoff->where('write_off_date', $data['start']);
            $payoff->where('payoff_date', $data['start']);
            $payment->where('repayment_date', $data['start']);
        } else if (Request::has('dpEnd')) {
            $data['end'] = Request::input('dpEnd');

            $trans->where('trans_date', $data['end']);
            $feecharge->where('charge_date', $data['end']);
            $costfee->where('cost_date', $data['end']);
            $writeoff->where('write_off_date', $data['end']);
            $payoff->where('payoff_date', $data['end']);
            $payment->where('repayment_date', $data['end']);
        } else {
            $data['start'] = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
            $data['end'] = date('Y-m-d');

            $trans->whereBetween('trans_date', [$data['start'], $data['end']]);
            $feecharge->whereBetween('charge_date', [$data['start'], $data['end']]);
            $costfee->whereBetween('cost_date', [$data['start'], $data['end']]);
            $writeoff->whereBetween('write_off_date', [$data['start'], $data['end']]);
            $payoff->whereBetween('payoff_date', [$data['start'], $data['end']]);
            $payment->whereBetween('repayment_date', [$data['start'], $data['end']]);
        }

        $data['trans'] = $trans->first();
        $data['feecharge'] = $feecharge->first();
        $data['costfee'] = $costfee->first();
        $data['writeoff'] = $writeoff->first();
        $data['payoff'] = $payoff->first();
        $data['payment'] = $payment->first();

        $data['trans_ytd'] = TransactionsRequiry::select(DB::raw('SUM(interest) as t_interest'))->where('trans_date', '<=', date('Y-m-d'))->first();
        $data['feecharge_ytd'] = FeeCharge::select(DB::raw('SUM(charge_amount) as t_charge_amount'))->where('charge_date', '<=', date('Y-m-d'))->first();
        $data['costfee_ytd'] = LoanCostFee::select(DB::raw('SUM(cost_amount) as t_cost_amount'))->where('cost_date', '<=', date('Y-m-d'))->first();
        $data['writeoff_ytd'] = LoanWriteOff::select(DB::raw('SUM(amount) as t_amount'))->where('write_off_date', '<=', date('Y-m-d'))->first();
        $data['payoff_ytd'] = LoanPayOff::select(DB::raw('SUM(payoff_fee) as t_payoff_fee'))->where('payoff_date', '<=', date('Y-m-d'))->first();
        $data['payment_ytd'] = LoanPayments::select(DB::raw('SUM(penalty_amount) as t_penalty_amount'))->where('repayment_date', '<=', date('Y-m-d'))->first();
        $data['co_id'] = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();

        return $this->view('reports.income_statement_sheet', $data);
    }

    private function incomeStatement()
    {
        $data['year'] = date('Y');
        $loan_payment = Loan::select('id', 'loan_type');
        $loan_feecharge = Loan::select('id', 'loan_type');
        $loan_writeoff = Loan::select('id', 'loan_type');
        $loan_payoff = Loan::select('id', 'loan_type');
        $loan_costfee = Loan::select('id', 'loan_type');

        if (Request::has('selYear')) {
            $data['year'] = Request::input('selYear');
        }
        if (Request::has('selBrand')) {
            $data['branch_id'] = Request::input('selBrand');
            $loan_payment->where('company_branch_id', '=', $data['branch_id']);
            $loan_feecharge->where('company_branch_id', '=', $data['branch_id']);
            $loan_writeoff->where('company_branch_id', '=', $data['branch_id']);
            $loan_payoff->where('company_branch_id', '=', $data['branch_id']);
            $loan_costfee->where('company_branch_id', '=', $data['branch_id']);
        }
        $data['co_id'] = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();
        $data['loan_payment'] = $loan_payment->whereHas('payment', function ($query) use ($data) {
            $query->whereRaw("DATE_FORMAT(repayment_date,'%Y') =" . $data['year']);
        })->with(['payment' => function ($query) use ($data) {
            $query->whereRaw("DATE_FORMAT(repayment_date,'%Y') =" . $data['year']);
        }])->get();

        $data['loan_feecharge'] = $loan_feecharge->whereHas('feecharge', function ($query) use ($data) {
            $query->whereRaw("DATE_FORMAT(charge_date,'%Y') =" . $data['year']);
        })->with(['feecharge' => function ($query) use ($data) {
            $query->whereRaw("DATE_FORMAT(charge_date,'%Y') =" . $data['year']);
        }])->get();

        $data['loan_writeoff'] = $loan_writeoff->where('status', '=', 5)->with(['writeoff' => function ($query) use ($data) {
            $query->whereRaw("DATE_FORMAT(write_off_date,'%Y') =" . $data['year']);
        }])->get();
        $data['loan_payoff'] = $loan_payoff->where('status', '=', 9)->with(['payoff' => function ($query) use ($data) {
            $query->whereRaw("DATE_FORMAT(payoff_date,'%Y') =" . $data['year']);
        }])->get();
        $data['loan_costfee'] = $loan_costfee->whereHas('costfee', function ($query) use ($data) {
            $query->whereRaw("DATE_FORMAT(cost_date,'%Y') =" . $data['year']);
        })->with(['costfee' => function ($query) use ($data) {
            $query->whereRaw("DATE_FORMAT(cost_date,'%Y') =" . $data['year']);
        }])->get();

        $data['branch'] = CompanyBranch::where('status', '=', 1)->get(['id', 'branch_name']);
        return $data;
    }

    public function getIncomeStatement()
    {
        $data = $this->incomeStatement();
        return $this->view('reports.income_statement', $data);
    }

    public function postIncomeStatement()
    {

        $data = $this->incomeStatement();
        return $this->view('reports.income_statement', $data);
    }

    private function profileLoss()
    {
        $data['start'] = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
        $data['end'] = date('Y-m-d');
        if (Request::has('dpStart')) {
            $data['start'] = date('Y-m-d', strtotime(Request::input('dpStart')));
        }
        if (Request::has('dpEnd')) {
            $data['end'] = date('Y-m-d', strtotime(Request::input('dpEnd')));
        }
        /* repayment */
        $loan_repayment = Loan::select('id');
        $loan_repayment->whereHas('payment', function ($query) use ($data) {
            $query->whereBetween('repayment_date', [$data['start'], $data['end']]);
        });
        $loan_repayment->with(['payment' => function ($query) use ($data) {
            $query->whereBetween('repayment_date', [$data['start'], $data['end']]);
        }]);

        /* fee charge && payoff */
        $fee_charge = Loan::select('id')
            ->whereHas('feecharge', function ($query) use ($data) {
                $query->whereBetween('charge_date', [$data['start'], $data['end']]);
            })
            ->with(['feecharge' => function ($query) use ($data) {
                $query->select('loan_id', 'charge_type', 'charge_amount')->whereBetween('charge_date', [$data['start'], $data['end']]);
            }]);

        $payoff = Loan::select('loans.id', 'loan_payoff.payoff_fee')->leftJoin('loan_payoff', 'loans.id', '=', 'loan_payoff.loan_id')
            ->where('loans.status', '=', 9)
            ->whereBetween('loan_payoff.payoff_date', [$data['start'], $data['end']]);

        /* cost && write off */

        $cost_fee = Loan::select('id')
            ->whereHas('costfee', function ($query) use ($data) {
                $query->whereBetween('cost_date', [$data['start'], $data['end']]);
            })
            ->with(['costfee' => function ($query) use ($data) {
                $query->select('loan_id', 'cost_type', 'cost_amount')->whereBetween('cost_date', [$data['start'], $data['end']]);
            }]);

        $writeoff = Loan::select('loans.id', 'loan_write_off.amount')->leftJoin('loan_write_off', 'loans.id', '=', 'loan_write_off.loan_id')
            ->where('loans.status', '=', 5)
            ->whereBetween('loan_write_off.write_off_date', [$data['start'], $data['end']]);

        if (Request::has('selBrand')) {
            $bid = Request::input('selBrand');
            $data['branch_id'] = $bid;
            $loan_repayment->where('company_branch_id', '=', $bid);
            $payoff->where('company_branch_id', '=', $bid);
            $fee_charge->where('company_branch_id', '=', $bid);
            $cost_fee->where('company_branch_id', '=', $bid);
            $writeoff->where('company_branch_id', '=', $bid);
        }

        /* select */
        $loan_repayment->whereNotIn('status', [1, 2]);
        $loan_repayment = $loan_repayment->get();
        $data['loan_repayment'] = $loan_repayment;

        $fee_charge->whereNotIn('status', [1, 2]);
        $fee_charge = $fee_charge->get();
        $data['fee_charge'] = $fee_charge;

        $payoff = $payoff->get();
        $data['payoff'] = $payoff;

        $cost_fee->whereNotIn('status', [1, 2]);
        $cost_fee = $cost_fee->get();
        $data['cost_fee'] = $cost_fee;

        $writeoff = $writeoff->get();
        $data['writeoff'] = $writeoff;

        $data['branch'] = CompanyBranch::where('status', '=', 1)->get(['id', 'branch_name']);
        $data['co_id'] = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();
        return $data;
    }

    public function getProfitLoss()
    {
        $data = $this->profileLoss();
        return $this->view('reports.profitloss', $data);
    }

    public function postProfitLoss()
    {
        $data = $this->profileLoss();
        return $this->view('reports.profitloss', $data);
    }

    public function getLoanRepayment()
    {
        $offset = ($_GET['offset'] != '') ? $_GET['offset'] : 10000;
        // $start = Request::has('dpStart')?Request::input('dpStart'): date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
        $start = Request::has('dpStart')?Request::input('dpStart'): date('Y-m-d', strtotime(date('Y-m-d') . ' -7 days'));
        $end = Request::has('dpEnd')? Request::input('dpEnd') : date('Y-m-d');
        $loan = Loan::select([
            'loans.id',
            'loans.start_date',
            'loans.loan_amount',
            'loans.contract_id',
            'client_id',
            'interest_rate',
            'disburse_date',
            'loan_account_id',
            'co'
        ])->whereIn('status', [3,8,9,10])
         ->whereHas('payment', function ($query) use ($start, $end) {
            $query->whereBetween('repayment_date', [$start, $end]);
          })->with(['payment' => function ($query) use ($start, $end) {
                $query->whereBetween('repayment_date', [$start, $end]);
          }, 'co_user' => function ($query) {
                $query->select('id', 'phone');
          }
        ]);

        $d['start'] = $start;
        $d['end'] = $end;
        $cur = Request::has('cur')?Request::input('cur'):2;
        $d['cur'] = $cur;

        $loan = $loan->whereHas('client_loan_account', function ($query) use ($cur){
          $query->where('currency', $cur);
        });

        if (Request::has('co_name')) {
            $coName = Request::input('co_name');
            $d['co'] = $coName;
            $loan = $loan->where('co', '=', $coName);
        }
        if (Request::has('selBrand')) {
            $branch = Request::input('selBrand');
            $d['bn'] = $branch;
            $loan = $loan->where('company_branch_id', '=', $branch);
        }
        $loan->with(['client' => function ($query) {
            $query->select('id', 'client_name');
        }, 'schedule' => function ($query) {
            $query->orderBy('schedule_date', 'asc');
        }, 'transaction'
        ]);


        $loan = $loan->paginate($offset);
        //dd($loan);
        $selBranch = CompanyBranch::where('status', '=', 1)->get(['id', 'branch_name']);
        $creditOfficer = User::select('users.id', 'name')->join('roles', 'users.role_id', '=', 'roles.id')->where('role', '=', 'co_user')->get();
        /*$co_id = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();*/
        return $this->view('reports.loan_repayment', ['loans' => $loan, 'branch' => $selBranch, 'co' => $creditOfficer, 'data' => $d, 'offset'=>$offset]);
    }

    public function getIrregularRepayment()
    {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 1500;
        $order = 'id';
        $order_dir = 'asc';
        $order_type = [
            'tenure' => 'loan_duration',
            'contract_id' => 'contract_id',
            'loan_amount' => 'loan_amount',
            'rpt_int_rate' => 'rpt_int_rate',
            'maturity_date' => 's.maturity_date'
        ];
        $loan = Loan::select([
            'loans.id',
            'loans.contract_id',
            'loans.repayment_type',
            'loans.balloon_amount_array',
            'loans.start_date',
            'loans.loan_duration',
            'loans.loan_amount',
            'loans.interest_rate',
            'loans.balloon',
            'loans.balloon_month',
            'loans.monthly_payment',
            'loans.balloon_amount_array',
            'loans.custom_flag',
            'loans.status',
            'loans.days_of_month',
            'loans.co',
            'client_id',
            'product_id',
            'last_schedule_date',
            'penalty_rate_type',
            'penalty_period1',
            'penalty_period2',
            'penalty_rate1',
            'penalty_rate2',
            'holiday_flag',
            'client_name',
            'phone1', 'phone2'
        ])->leftJoin('clients', 'clients.id', '=', 'loans.client_id')
            ->with(['payment',
                'product' => function ($query) {
                    $query->select('id', 'product_type', 'brand_id')->with(['brand']);
                },
                'schedule' => function ($q) {
                    $q->orderBy('schedule_date', 'asc');
                }
            ]);
        $is_detail = 0;
        if (Request::has('is_detail')) {
            $is_detail = Request::input('is_detail');
        }
        if ($is_detail == 0) {
            $loan->where(function ($query) {
                $query->WhereHas('payment', function ($query) {
                    $query->where('status', '=', 0)
                        ->where('condition_id', '=', 1);
                })
                ->orWhere(function ($q) {
                    $q->whereRaw("ADDDATE(last_schedule_date, INTERVAL 1 MONTH) <= DATE_FORMAT(NOW(),'%Y-%m-%d')")
                        ->whereNotNull('last_schedule_date');
                });
            });
        } else {
            $loan->WhereHas('payment', function ($query) {
//                $query->where('status','=',0)
//                    ->where('condition_id','!=',0)
//                    ->where('todo_payment','=', date('Y-m-d'));
                $query->where('todo_payment', '=', date('Y-m-d'));
            });
        }
        $query_url = [];
        $order_class = [
            'tenure' => ($order == 'tenure') ? 'fa-sort-' . $order_dir : 'fa-sort',
            'contract_id' => ($order == 'contract_id') ? 'fa-sort-' . $order_dir : 'fa-sort',
            'loan_amount' => ($order == 'loan_amount') ? 'fa-sort-' . $order_dir : 'fa-sort',
            'rpt_int_rate' => ($order == 'rpt_int_rate') ? 'fa-sort-' . $order_dir : 'fa-sort',
            'maturity_date' => ($order == 'maturity_date') ? 'fa-sort-' . $order_dir : 'fa-sort'
        ];
        if (Request::has('client_name')) {
            $name = Request::input('client_name');
            $loan = $loan->where('client_name', 'like', '%' . $name . '%');
            $query_url['client_name'] = $name;
        }
        if (Request::has('branch')) {
            $branch = Request::input('branch');
            $loan = $loan->where('company_branch_id', '=', $branch);
            $query_url['branch'] = $branch;
        }
        if (Request::has('phone')) {
            $phone = Request::input('phone');
            $loan = $loan->where(function ($query) use ($phone) {
                $query->where('phone1', '=', $phone)->orWhere('phone2', '=', $phone);
            });
            $query_url['phone'] = $phone;
        }
        if (Request::has('contract_id')) {
            $contractID = Request::input('contract_id');
            $loan = $loan->where('contract_id', 'like', '%' . $contractID . '%');
            $query_url['contract_id'] = $contractID;
        }
        if (Request::has('o')) {
            $order = Request::input('o');
            if (!array_key_exists($order, $order_type)) $order = 'id';
            $querystringArray['o'] = $order;
        }
        if (Request::has('od')) {
            $order_dir = Request::input('od');
            if ($order_dir != 'desc' && $order_dir != 'asc') $order_dir = 'desc';
            $querystringArray['od'] = $order;
        }

        if (Request::has('co')) {
            $co = Request::input('co');
            $loan = $loan->where('co', $co);
            $query_url['co'] = $co;
        }

        $url = url('reports/irregular_repayment');
        $order_url = [
            'tenure' => ($order == 'tenure' && $order_dir == 'asc') ? $this->setUrl($url, 'o=tenure&od=desc') : $this->setUrl($url, 'o=tenure&od=asc'),
            'contract_id' => ($order == 'contract_id' && $order_dir == 'asc') ? $this->setUrl($url, 'o=contract_id&od=desc') : $this->setUrl($url, 'o=contract_id&od=asc'),
            'loan_amount' => ($order == 'loan_amount' && $order_dir == 'asc') ? $this->setUrl($url, 'o=loan_amount&od=desc') : $this->setUrl($url, 'o=loan_amount&od=asc'),
            'rpt_int_rate' => ($order == 'rpt_int_rate' && $order_dir == 'asc') ? $this->setUrl($url, 'o=rpt_int_rate&od=desc') : $this->setUrl($url, 'o=rpt_int_rate&od=asc'),
            'maturity_date' => ($order == 'maturity_date' && $order_dir == 'asc') ? $this->setUrl($url, 'o=maturity_date&od=desc') : $this->setUrl($url, 'o=maturity_date&od=asc')
        ];
        $co_id = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();

        if ($order == 'maturity_date') {
            $loan = $loan->addSelect('s.maturity_date')->join(DB::raw("(SELECT  loan_id, schedule_date as maturity_date FROM  tb_repayment_schedule ORDER BY schedule_date ) tb_s"), function ($join) {
                $join->on('s.loan_id', ' =', 'loans.id');
            }, null, null, 'left');
        }

        if (array_key_exists($order, $order_type)) {
            $loan = $loan->whereIn('loans.status', [3, 8])->orderBy($order_type[$order], $order_dir)->groupBy('loans.id');
        } else {
            $loan = $loan->whereIn('loans.status', [3, 8]);
        }



        //chuch
        $loan = $loan->with(['co_user' => function ($query) {
            $query->select('id', 'phone','name');
        }]);

        $loan = $loan->paginate($offset);
        $loan->appends($query_url);

        //get co list
        $users = User::where('role_id', 10)->orderBy('name')->get();

        $data['loans'] = $loan;
        $data['co_id'] = $co_id;
        $data['order_class'] = $order_class;
        $data['order_url'] = $order_url;
        $data['offset'] = $offset;
        $data['users'] = $users;

        if (Request::ajax()) {
            if ($is_detail == 0) {
                return view('partials.irregular', $data)->render();
            } else {
                return view('partials.detail_irregular', $data)->render();
            }
        } else {
            $data['branch'] = CompanyBranch::where('status', '=', 1)->get(['id', 'branch_name']);
            return $this->view('reports.irregular_repayment', $data);
        }
    }

    public function postIrregularRepayment($id = 0)
    {
        if (is_numeric($id) && $id > 0) {
            $edit = Request::only('reason', 'action_taken', 'todo_payment');
            if (!empty($edit) && is_array($edit)) {
                $addNew = LoanPayments::find($id);
                foreach ($edit as $key => $value) {
                    if ($key == 'todo_payment') {
                        $date = date('Y-m-d', strtotime($value));
                        $value = $date;
                    }
                    $addNew->$key = $value;
                }
            }
            if ($addNew->save()) {
                $this->userActivity(Auth::user()->id, $addNew->id, 0, 'Update LoanPayments', Request::fullUrl());
                return ['status' => true, 'result' => $edit];
            }
        }
        return ['status' => false];
    }

    public function getRescheduleRepayment()
    {
        $start = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
        $end = date('Y-m-d');
        $select = [
            'id',
            'client_id',
            'product_id',
            'contract_id',
            'start_date',
            'loan_duration',
            'loan_amount',
            'interest_rate',
            'status',
            'user_id'
        ];
        $reports = Loan::select($select);
        $reports->with(['client' => function ($query) {
            $query->select('id', 'client_name');
        }]);
        $reports->with(['payment' => function ($query) {
            $query->select('id', 'loan_id', 'repayment_date', 'paid_interest', 'paid_principal', 'penalty_amount', 'status', 'note');
        }]);
        $reports->with(['user' => function ($query) {
            $query->select('id', 'name');
        }]);
        $reports->where('status', 8);

        $query_url = [];
        if (Request::has('dpStart') && Request::has('dpEnd')) {
            $start = Request::input('dpStart');
            $end = Request::input('dpEnd');
            $reports = $reports->whereBetween('start_date', [$start, $end]);
            $query_url['dpStart'] = $start;
            $query_url['dpEnd'] = $end;
        } elseif (Request::has('dpEnd')) {
            $end = Request::input('dpEnd');
            $start = '';
            $reports = $reports->where('start_date', '<=', $end);
            $query_url['dpEnd'] = $end;
        } elseif (Request::has('dpStart')) {
            $start = Request::input('dpStart');
            $end = '';
            $reports = $reports->where('start_date', '>=', $start);
            $query_url['dpStart'] = $start;
        }
        $d['start'] = $start;
        $d['end'] = $end;
        if (Request::has('officer')) {
            $officer_name = Request::input('officer');
            $reports = $reports->where('co', '=', $officer_name);
            $d['co'] = $officer_name;
            $query_url['officer'] = $officer_name;
        }
        if (Request::has('selBrand')) {
            $branch = Request::input('selBrand');
            $reports = $reports->where('company_branch_id', '=', $branch);
            $d['bn'] = $branch;
            $query_url['selBrand'] = $branch;
        }

        $reports = $reports->orderBy('contract_id')->paginate(1000000);
        $reports->appends($query_url);
        $loans = [];
        if (count($reports) > 0) {
            $contract_id = $reports->fetch('contract_id')->toArray();
            $loans = Loan::with(['payment' => function ($query) {
                $query->select('id', 'loan_id', 'repayment_date', 'paid_interest', 'paid_principal', 'penalty_amount', 'status', 'note');
            }])
                ->whereIn('contract_id', $contract_id)
                ->where('status', 6)->get($select);
        }
        $creditOfficer = User::select('users.id', 'name')->join('roles', 'users.role_id', '=', 'roles.id')->where('role', '=', 'co_user')->get();
        $selBranch = CompanyBranch::where('status', '=', 1)->get(['id', 'branch_name']);
        $co_id = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();
        return $this->view('reports.rescheduled_repayment_report', ['reports' => $reports, 'loans' => $loans, 'officer' => $creditOfficer, 'branch' => $selBranch, 'data' => $d, 'co_id' => $co_id]);
    }

    public function getRejected()
    {
        $data['user'] = User::select('id', 'name')->get();
        $data['branch'] = CompanyBranch::select('id', 'branch_name')->get();

        $loan_rejected = Loan::select('id', 'client_id', 'contract_id', 'user_id', 'start_date', 'loan_amount', 'interest_rate', 'rejected_date', 'rejected_note', 'rejected_byuserid')
            ->with(['client' => function ($query) {
                $query->select('id', 'client_name');
            }, 'user' => function ($query) {
                $query->select('id', 'name');
            }]);
        $query_url = [];
        if (Request::has('dpStart') && Request::has('dpEnd')) {
            $data['start'] = Request::input('dpStart');
            $data['end'] = Request::input('dpEnd');
            $loan_rejected->whereBetween('rejected_date', [$data['start'], $data['end']]);
            $query_url['dpStart'] = $data['start'];
            $query_url['dpEnd'] = $data['end'];
        } else if (Request::has('dpStart')) {
            $data['start'] = Request::input('dpStart');
            $loan_rejected->where('rejected_date', '=', $data['start']);
            $query_url['dpStart'] = $data['start'];
        } else if (Request::has('dpEnd')) {
            $data['end'] = Request::input('dpEnd');
            $loan_rejected->where('rejected_date', '=', $data['end']);
            $query_url['dpEnd'] = $data['end'];
        } else {
            $data['start'] = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
            $data['end'] = date('Y-m-d');
            $loan_rejected->whereBetween('rejected_date', [$data['start'], $data['end']]);
        }
        if (Request::has('user')) {
            $data['u_id'] = Request::input('user');
            $loan_rejected->where('rejected_byuserid', '=', $data['u_id']);
            $query_url['user'] = $data['u_id'];
        }
        if (Request::has('branch')) {
            $data['branch_id'] = Request::input('branch');
            $loan_rejected->where('company_branch_id', '=', $data['branch_id']);
            $query_url['branch'] = $data['branch_id'];
        }
        $data['loan_rejected'] = $loan_rejected->paginate(1000000);
        $data['loan_rejected']->appends($query_url);
        $data['co_id'] = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();
        return $this->view('reports.reject', $data);
    }

    public function getCollection()
    {
        $offset = Request::has('set_offset') ? Request::input('set_offset') : 15;
        $data['currency'] = config('static_data.currency');
        $data['branch'] = CompanyBranch::select('id', 'branch_code','branch_name', 'branch_code')->where('status','=',1)->get();
        $data['currency_id'] = Request::input('cur');
        $data['branch_id'] = Request::input('br');
        if (Request::has('dpStart')) {
            $data['sdate'] = Request::input('dpStart');
        } else {
            $data['sdate'] = date('Y-m-d');
        }
        if (Request::has('dpEnd')) {
            $data['edate'] = Request::input('dpEnd');
        } else {
            $data['edate'] = date('Y-m-d');
        }

        $trans = TransactionsRequiry::with(['loan' => function ($query) {
            $query->select('id', 'client_id', 'company_branch_id', 'contract_id', 'start_date', 'loan_account_id', 'co', 'disburse_date','loan_amount')
                ->with(['client' => function ($query) {
                    $query->select('id', 'client_name', 'phone1','address');//'city'
                }, 'client_loan_account' => function ($que){
                    $que->select('id', 'currency','branch', 'status', 'acc_key', 'balance');
                }]);
        }])
            ->where( function($q){
                $q->where('trans_type','LIKE',"%Loan Repayment%")
                    ->orWhere('trans_type','=',"Fee Charge Repayment")
                    ->orWhere('trans_type','=',"Cost Repayment")
                    ->orWhere('trans_type','=',"Close Loan")
                    ->orWhere('trans_type','=',"Pay-Off")
                    ->orWhere('trans_type','=',"Arrears Repayment");
            })
            ->where('trans_date', ">=", date('Y-m-d', strtotime($data['sdate'])))
            ->where('trans_date', "<", date('Y-m-d', strtotime($data['edate'] . '+1 days')))
            ->orderBy('trans_date', 'ASC');
        if($offset == "All"){
            $data['trans'] = $trans->get();            
        }else{
            $data['trans'] = $trans->paginate($offset);
        }
        $data['co_id'] = Loan::select('id', 'co')->with(['co_user' => function ($query) {
            $query->select('id', 'phone');
        }])->first();
        return $this->view('reports.collection', $data,['offset'=>$offset]);
    }


    public function getRepaymentPlan()
    {
        $date = date('Y-m-d');
        $from_date = Request::input('from_date');
        $to_date = Request::input('to_date');
        isset($from_date) ? $from_date : $from_date = (date('Y-m-d', strtotime($date .'-1 month')));
        isset($to_date) ? $to_date : $to_date = $date;
        $loans = Loan::select('id', 'start_date', 'disburse_date', 'loan_amount', 'product_id', 'status', 'last_schedule_date', 'loan_account_id')
                      ->with(['client_loan_account' => function($q){
                          $q->select('id', 'currency');
                      }])
                      ->with(['payment' => function ($query) {
                          $query->select('id', 'loan_id', 'payment_month', 'repayment_date', 'paid_interest', 'paid_principal', 'paid_fee');
                      }])
                      ->where('start_date','>=',$from_date)
                      ->where('start_date','<=',$to_date)
                      ->whereIn('status', [3, 5, 8, 9, 10]);

        $pay_offs = LoanPayOff::select('payoff_date', 'principal', 'interest', 'payoff_fee', 'loan_id');
        $write_offs = LoanWriteOff::select('write_off_date', 'amount', 'loan_id')->orderBy('write_off_date');
        $closes = LoanClose::select('closed_date', 'amount', 'loan_id')->orderBy('closed_date');
        $company_branch = CompanyBranch::where('status', '=', 1)->get(['id', 'branch_name']);
        $transactions = TransactionsRequiry::select('id', 'trans_date', 'trans_type', 'loan_id', 'principal', 'interest')->where('trans_type', '=', 'Pay-Off')->get();

        //$product_type = ProductCategory::select('id', 'category_name')->get();
        
        $product_type  = new Product_type();
        $product_type_label = '';
        $company_branch_label = '';
        $loan_type = '';
        if (Request::has('loan_type')) {
            $loan_type = Request::input('loan_type');
            $loans->where('loan_type', $loan_type);

            $pay_offs->whereHas('loan', function ($q) use ($loan_type) {
                $q->where('loan_type', $loan_type);
            });
            $write_offs->whereHas('loan', function ($q) use ($loan_type) {
                $q->where('loan_type', $loan_type);
            });
            $closes->whereHas('loan', function ($q) use ($loan_type) {
                $q->where('loan_type', $loan_type);
            });
        }
        $p_type = '';
        if (Request::has('product_type')) {
            $p_type = Request::input('product_type');
            $loans->whereHas('product', function($q)use($p_type){
                $q->where('product_type_id', $p_type);
            });
            $pay_offs->whereHas('loan.product', function ($q) use ($p_type) {
                $q->where('product_type_id', $p_type);
            });
            $write_offs->whereHas('loan.product', function ($q) use ($p_type) {
                $q->where('product_type_id', $p_type);
            });
            $closes->whereHas('loan.product', function ($q) use ($p_type) {
                $q->where('product_type_id', $p_type);
            });
            $product_type_label = $product_type->where('id', $p_type, false)->first();
            if (!empty($product_type_label)) {
                $product_type_label = $product_type_label->category_name;
            }
        }

        $company_branch_id = '';
        if (Request::has('company_branch_id')) {
            $company_branch_id = Request::input('company_branch_id');
            $loans->where('company_branch_id', $company_branch_id);
            $pay_offs->whereHas('loan', function ($q) use ($company_branch_id) {
                $q->where('company_branch_id', $company_branch_id);
            });
            $write_offs->whereHas('loan', function ($q) use ($company_branch_id) {
                $q->where('company_branch_id', $company_branch_id);
            });
            $closes->whereHas('loan', function ($q) use ($company_branch_id) {
                $q->where('company_branch_id', $company_branch_id);
            });
            $company_branch_label = $company_branch->where('id', $company_branch_id, false)->first();
            if (!empty($company_branch_label)) {
                $company_branch_label = ' (' . $company_branch_label->branch_name . ')';
            }
        }
        $loans->with(['schedule' => function ($query) {
            $query->orderBy('schedule_date', 'asc');
        }]);
    
        // $collect = collect();
        // $loans->chunk(1000, function($rows) use(&$collect){
        //   $collect->push($rows);
        // });

        $data['loans'] = $loans->get();
        $data['pay_offs'] = $pay_offs->get();
        $data['write_offs'] = $write_offs->get();
        $data['closes'] = $closes->get();
        $data['product_type'] = $product_type->get();
        $data['t_id'] = $loan_type;
        $data['p_type'] = $p_type;
        $data['company_branch_id'] = $company_branch_id;
        $data['company_branch'] = $company_branch;
        $data['product_type_label'] = $product_type_label;
        $data['company_branch_label'] = $company_branch_label;
        $data['transactions'] = $transactions;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        return $this->view('reports.repayment_plan', $data);
    }

    public function getBalanceSheet()
    {
        $data['start'] = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
        $data['end'] = date('Y-m-d');

        if (Request::has('start')) {
            $data['start'] = date('Y-m-d', strtotime(Request::input('start')));
        }
        if (Request::has('end')) {
            $data['end'] = date('Y-m-d', strtotime(Request::input('end')));
        }
        $loans = Loan::select([
            'loans.id',
            'loans.contract_id',
            'loans.repayment_type',
            'loans.balloon_amount_array',
            'loans.start_date',
            'loans.loan_duration',
            'loans.loan_amount',
            'loans.interest_rate',
            'loans.balloon',
            'loans.balloon_month',
            'loans.monthly_payment',
            'loans.balloon_amount_array',
            'loans.custom_flag',
            'loans.days_of_month',
            'loans.client_id',
            'loans.last_schedule_date',
            'loans.penalty_rate_type',
            'loans.penalty_period1',
            'loans.penalty_period2',
            'loans.penalty_rate1',
            'loans.penalty_rate2',
            'loans.holiday_flag'
        ])
            ->with(['payment' => function ($query) use ($data) {
                $query->select('id', 'loan_id', 'paid_principal', 'paid_interest', 'penalty_amount');
                if (empty($data['start']) && !empty($data['end'])) {
                    $query->where('repayment_date', '<=', $data['end']);
                } elseif (empty($data['end']) && !empty($data['start'])) {
                    $query->where('repayment_date', '>=', $data['start']);
                } else {
                    $query->whereBetween('repayment_date', [$data['start'], $data['end']]);
                }
            }]);
        $writeoff = LoanWriteOff::select('id', 'amount');
        $fee_charge = FeeCharge::select('id', 'charge_amount');
        $cost_fee = LoanCostFee::select('id', 'cost_amount');
        $payoff = LoanPayOff::select('id', 'payoff_fee');

        if (empty($data['start']) && !empty($data['end'])) {
            $loans->where('start_date', '<=', $data['end']);
            $writeoff->where('write_off_date', '<=', $data['end']);
            $fee_charge->where('charge_date', '<=', $data['end']);
            $cost_fee->where('cost_date', '<=', $data['end']);
            $payoff->where('payoff_date', '<=', $data['end']);
        } elseif (empty($data['end']) && !empty($data['start'])) {
            $loans->where('start_date', '>=', $data['start']);
            $writeoff->where('write_off_date', '>=', $data['start']);
            $fee_charge->where('charge_date', '>=', $data['start']);
            $cost_fee->where('cost_date', '>=', $data['start']);
            $payoff->where('payoff_date', '>=', $data['start']);
        } else {
            $loans->whereBetween('start_date', [$data['start'], $data['end']]);
            $writeoff->whereBetween('write_off_date', [$data['start'], $data['end']]);
            $fee_charge->whereBetween('charge_date', [$data['start'], $data['end']]);
            $cost_fee->whereBetween('cost_date', [$data['start'], $data['end']]);
            $payoff->whereBetween('payoff_date', [$data['start'], $data['end']]);
        }
        $loans->whereIn('status', [3, 5, 6, 8, 9, 10]);

        //fee_charge
        $fee_charge = $fee_charge->get();
        $data['fee_charge'] = $fee_charge;

        //write_off
        $writeoff = $writeoff->get();
        $data['writeoff'] = $writeoff;

        //loan
        $loans = $loans->get();
        $data['loans'] = $loans;

        //cost_fee
        $cost_fee = $cost_fee->get();
        $data['cost_fee'] = $cost_fee;

        //pay_off
        $payoff = $payoff->get();
        $data['payoff'] = $payoff;
//        dd($data);
        return $this->view('reports.balance_sheet', $data);
    }

    public function getCashFlow()
    {
        $data = [];
        if(Request::has('currency')){
            $data['requiry'] = JournalRequiry::with(['detail' => function ($query) {
                $query->select('id', 'coa_id', 'journal_id', 'reference','debit', 'credit')->where('coa_id', Request::input('currency'));
            }, 'transaction'])
                ->whereHas('detail', function ($query) {
                    $query->whereHas('account', function($query){
                        $query->where('type', 5)
                              ->where('name', 'LIKE', "%Cash in Vault and on Hand%")
                              ->where('currency', Request::input('currency'));
                    });
                });
        }else { // Cash on hand USD (default)
            $data['requiry'] = JournalRequiry::with(['detail' => function ($query) {
                $query->select('id', 'coa_id', 'journal_id', 'reference', 'debit', 'credit')->where('coa_id', 5);
            }, 'transaction'])
                ->whereHas('detail', function ($query) {
                    $query->where('coa_id', 5);
                });
        }

        $data['requiry']->where('is_audit', 1);

        $query_url = [];
        if (Request::has('start') && Request::has('end')) {
            $data['start'] = Request::input('start');
            $data['end'] = Request::input('end');
            $query_url['start'] = $data['start'];
            $query_url['end'] = $data['end'];
            $data['requiry']->whereBetween('entry_date', [$data['start'], $data['end']]);
        } else if (Request::has('start')) {
            $data['start'] = Request::input('start');
            $query_url['start'] = $data['start'];
            $data['requiry']->where('entry_date', $data['start']);
        } else if (Request::has('end')) {
            $data['end'] = Request::input('end');
            $query_url['end'] = $data['end'];
            $data['requiry']->where('entry_date', $data['end']);
        } else {
            $data['start'] = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
            $data['end'] = date('Y-m-d');
            $data['requiry']->whereBetween('entry_date', [$data['start'], $data['end']]);
        }
        $data['requiry'] = $data['requiry']->paginate(1000000);
        $data['requiry']->appends($query_url);
        return $this->view('reports.cash_flow', $data);
    }

    public function getCashDeposit()
    {
        $data = [];
//        $requiry = JournalRequiry::with(['detail' => function ($query) {
////            $query->whereHas('account', function ($query) {
////                $query->WhereBetween('id', 5);
////             });
//            $query->where('coa_id', 5);
//        }, 'transaction' => function ($query) {
//            $query->select('id', 'loan_id', 'principal', 'interest', 'penalty', 'fee')
//                ->with(['loan' => function ($query) {
//                    $query->select('id', 'contract_id');
//                }]);
//        }])->groupBy('tran_id');

        $transactions = TransactionsRequiry::with(['journal' => function($q){
            $q->with(['detail' => function($q){
                $q->where('coa_id',5);
            }], ['loan' => function($que){
                $que->select('id', 'contract_id');
            }]);
        }])->where('description','not like','%Auto Accrued Interest%')->orderBy('id','asc');

//        $requiry = JournalRequiry::with(['detail' => function($q){
//            $q->where('coa_id',5);
//        }])->where('tran_id', 0);
        // 2021-09-26
        // if (Request::has('dpStart') && Request::has('dpEnd')) {
        //     $data['start'] = Request::input('dpStart');
        //     $data['end'] = Request::input('dpEnd');
        //     $transactions->whereBetween('trans_date', [$data['start'], $data['end']]);
        // } else if (Request::has('dpStart')) {
        //     $data['start'] = Request::input('dpStart');
        //     $transactions->where('trans_date', $data['start']);
        // } else if (Request::has('dpEnd')) {
        //     $data['end'] = Request::input('dpEnd');
        //     $transactions->where('trans_date', $data['end']);
        // } else {
        //     $data['start'] = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
        //     $data['end'] = date('Y-m-d');
        //     $transactions->whereBetween('trans_date', [$data['start'], $data['end']]);
        // } 
        $data['start'] = Request::input('dpStart');
        $data['end'] = Request::input('dpEnd');
        $transactions->whereBetween('trans_date', [$data['start'], $data['end']]);
        if (Request::has('dpStart')) {
            $data['start'] = Request::input('dpStart');
            $transactions->where('trans_date', $data['start']);
        } else if (Request::has('dpEnd')) {
            $data['end'] = Request::input('dpEnd');
            $transactions->where('trans_date', $data['end']);
        } else {
            $data['start'] = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 month'));
            $data['end'] = date('Y-m-d');
            $transactions->whereBetween('trans_date', [$data['start'], $data['end']]);
        }
        $data['transactions'] = $transactions->paginate(1000000);

        return $this->view('reports.cash_deposit', $data);
    }

    public function getLoanCollection()
    {
        $data = $this->incomeStatement();
        return $this->view('reports.loan_collection_summary', $data);
    }

    public function postLoanCollection()
    {
        $data = $this->incomeStatement();
        return $this->view('reports.loan_collection_summary', $data);
    }

    public function getIs()
    {
        define('USDTOKHR', 4100);
        //define('KHRM', 1000000);
        define('KHRM', 1);
        $data['currency'] = config('static_data.currency');
        $data['report_title'] = 'Income Statement Report';

        $start_date = null;
        $end_date = null;
        $data['nbc'] = 0;
        $data['type'] = 7;
        $p_branch_code = '000';
        if(Request::has('nbc')) $data['nbc'] = 1;
        if(Request::has('type')) $data['type'] = Request::input('type');
        (Request::has('dpStart'))? $start_date = Request::input('dpStart') : $start_date = date('Y-m-d',strtotime('-1 month'));
        $data['start_date'] = $start_date;
        $data['start'] = $start_date;
        (Request::has('dpEnd'))? $end_date = Request::input('dpEnd') : $end_date = date('Y-m-d');
        (Request::has('rate'))? $rate = Request::input('rate') : $rate = USDTOKHR;
        $data['end_date'] = $end_date;
        $data['end'] = $end_date;
        if(Request::has('consolidate') && Request::input('consolidate')==1){
            $data['consolidate'] = 1;
        }else{
            $data['consolidate'] = 0;
        }
        $data['currency_id'] = 2;
        if(Request::has('cur')){
          $data['currency_id'] = Request::input('cur');
        }

        //reset select for view
        if(Request::has('consolidate') && Request::input('consolidate')==1){
          $data['consolidate'] = 1;
          $data['rate'] = Request::has('rate')?Request::input('rate'):USDTOKHR;
          if(Request::has('exchange_rate') && Request::input('exchange_rate')==1){
            $data['exchange_rate'] = 1;
          }else{
            $data['exchange_rate'] = 2;
          }
        }

        if(Auth::user()->role_id!=1 && Auth::user()->role_id!=2) $data['branch_code'] = Auth::user()->branch_code;

        $query_arr = array('id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
        $B1 = new CompanyBranch();
        $B1 = $this->getBranchByUser($B1, 'id', $query_arr);
        $data['branch'] = $B1->select('id', 'branch_code','branch_name', 'branch_code')->where('status','=',1)->get();
          $branch_code = CompanyBranch::select('branch_code')->where('id', Auth::user()->branch_id)->first()->branch_code;
          $query_arr = array('user_id'=>Auth::user()->id, 'branch_id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id, 'branch_code'=>$branch_code);

          //in journal inquiry
          $JournalRequiry_arr = array();

          $jd_coa = [];
          $jd_coa_pre = [];

          $B3 = new JournalDetail();
          $B3 = $this->getUserByBranch($B3, 'branch_code', $query_arr);

          $pcurJournalDetail = $B3->select('journal_detail.id as jd_id', 'debit', 'credit', 'branch_code', 'coa_id', 'journal_id', 'journal_requiry.is_audit as audit', 'entry_date', 'coa_categories.parent_id as parent_id', 'account_code', 'nbc_code', 'coa_categories.name as coa_name', 'currency')
                              ->join('journal_requiry', 'journal_detail.journal_id', '=', 'journal_requiry.id')
                              ->join('coa_categories', 'journal_detail.coa_id', '=', 'coa_categories.id');
          $pcurJournalDetail = $pcurJournalDetail->orderBy('journal_detail.id', 'ASC')->where('journal_detail.is_audit', 1)
                              ->where(function($q){ $q->where('nbc_code', 'LIKE', '5%')->orWhere('nbc_code', 'LIKE', '6%');});
          if ($start_date || $end_date){
            // start < tr < end
            if ($start_date) $pcurJournalDetail = $pcurJournalDetail->where('entry_date', '>=', $data['start_date'] );
            if ($end_date) $pcurJournalDetail = $pcurJournalDetail->where('entry_date', '<', date('Y-m-d', strtotime($data['end_date'] . '+1 days')));
            //$curJournalDetail = $curJournalDetail->get();
          }
          if($data['consolidate'] == 0){
            $pcurJournalDetail = $pcurJournalDetail->where('currency', $data['currency_id']);
          }
          $pcurJournalDetail = $pcurJournalDetail->get();
          $pre_balance = [];
          $pre_balance_val = 0;
          $coa_details = [];
          $coa_all = CoaCategory::where(function($q){ $q->where('nbc_code', 'LIKE', '5%')->orWhere('nbc_code', 'LIKE', '6%');})->orderBy('id', 'DESC')->get();

          if(count($pcurJournalDetail) > 0){
            $cnt = 0; $old_coa = 0; $new_coa = 0;
              foreach($pcurJournalDetail as $pre){
                $new_coa = $pre->coa_id;
                if($cnt == 0 && $new_coa != $old_coa){
                  $coa_details[$pre->coa_id]["cur_debit"] = 0;
                  $coa_details[$pre->coa_id]["cur_credit"] = 0;
                }

                if($data['consolidate'] == 1){
                  if($data['currency_id'] == 1){//KHR (show in million)
                    if($pre->currency == 2 ){
                      $coa_details[$pre->coa_id]["cur_debit"] += $pre->debit * $rate / KHRM;
                      $coa_details[$pre->coa_id]["cur_credit"] += $pre->credit * $rate / KHRM;
                    }elseif($pre->currency == 1){//if already KHR
                      $coa_details[$pre->coa_id]["cur_debit"] += $pre->debit / KHRM;
                      $coa_details[$pre->coa_id]["cur_credit"] += $pre->credit / KHRM;
                    }
                  }elseif($data['currency_id'] == 2){//USD
                    if($pre->currency == 2 ){
                      $coa_details[$pre->coa_id]["cur_debit"] += $pre->debit;
                      $coa_details[$pre->coa_id]["cur_credit"] += $pre->credit;
                    }elseif($pre->currency == 1){//if KHR convert to USD
                      $coa_details[$pre->coa_id]["cur_debit"] += $pre->debit / $rate;
                      $coa_details[$pre->coa_id]["cur_credit"] += $pre->credit / $rate;
                    }
                  }
                }else{ //no consolidate
                    if($data['currency_id'] == 1){//KHR (show in million)
                      if($pre->currency==2){
                          continue;
                      }elseif($pre->currency==1){
                        $coa_details[$pre->coa_id]["cur_debit"] += $pre->debit/KHRM;
                        $coa_details[$pre->coa_id]["cur_credit"] += $pre->credit/KHRM;
                      }
                    }elseif($data['currency_id'] == 2){
                      if($pre->currency==1){
                          continue;
                      }elseif($pre->currency==2){
                        $coa_details[$pre->coa_id]["cur_debit"] += $pre->debit;
                        $coa_details[$pre->coa_id]["cur_credit"] += $pre->credit;
                      }
                    }
                }
                $coa_details[$pre->coa_id]["parent"] = $pre->parent_id;
                $coa_details[$pre->coa_id]["code"] = $pre->account_code;
                $coa_details[$pre->coa_id]["initial"] = substr($pre->account_code, 0, 1);
                $coa_details[$pre->coa_id]["branch"] = $pre->branch_code;
                $coa_details[$pre->coa_id]["currency"] = $pre->currency;
                $coa_details[$pre->coa_id]["nbc_code"] = $pre->nbc_code;

                $old_coa = $pre->coa_id;
                $cnt++;
              }
              $test = [];
              foreach($coa_details as $d){
              //  dd(substr($d["nbc_code"], 0,2));
                if(substr($d["nbc_code"], 0,2) == "69"){
                  $test[] = $d;
                }
              }
              //dd($test);
              foreach($coa_all as $icoa){
                foreach($coa_details as $v){
                  if($icoa->id == $v["parent"]){
                    $coa_details[$icoa->id]["cur_debit"]  += $v["cur_debit"];
                    $coa_details[$icoa->id]["cur_credit"]  += $v["cur_credit"];
                    $coa_details[$icoa->id]["parent"]  = $icoa->parent_id;
                    $coa_details[$icoa->id]["code"] = $icoa->account_code;
                    $coa_details[$icoa->id]["nbc_code"] = $icoa->nbc_code;
                    $coa_details[$icoa->id]["initial"] = substr($icoa->account_code, 0, 1);
                    $coa_details[$icoa->id]["branch"] = $v->branch_code;
                    $coa_details[$icoa->id]["currency"] = $v->currency;
                    if($icoa->type == 4){
                      switch ($coa_details[$icoa->id]["initial"]) {
                        case '1':
                        case '2':
                        case '6':
                            $array_bal["symbol_".$icoa->symbol] += Round($v["cur_debit"] - $v["cur_credit"],2);
                          break;
                        case '3':
                        case '4':
                        case '5':
                            $array_bal["symbol_".$icoa->symbol] += Round($v["cur_credit"] - $v["cur_debit"],2);
                          break;
                        default:
                          break;
                      }
                    }
                  }
                }
              }
              //ksort($coa_details);
          }
          //dd($array_bal);
          $data['array_bal'] = $array_bal;
          $data['iil'] = $array_bal['symbol_iil'];
          $data['oii'] = $array_bal['symbol_oii'];
          $data['income'] = $data['iil'] + $data['oii'];

          $data['ied'] = $array_bal['symbol_ied'];
          $data['ieb'] = $array_bal['symbol_ieb'];
          $data['oie'] = $array_bal['symbol_oie'];
          $data['expense'] = $data['ied'] + $data['ieb'] + $data['oie'];

          $data['net_interest'] = $data['income'] - $data['expense'];

          $data['ilcf'] = $array_bal['symbol_ilcf'];
          $data['iofc'] = $array_bal['symbol_iofc'];
          $data['igfe'] = $array_bal['symbol_igfe'];
          $data['igdpe'] = $array_bal['symbol_igdpe'];
          $data['rl'] = $array_bal['symbol_rl'];
          $data['onii'] = $array_bal['symbol_onii'];
          $data['non_interest_income'] = $data['ilcf'] + $data['iofc'] + $data['igfe'] + $data['igdpe'] + $data['rl'] + $data['onii'];

          $data['posc'] = $array_bal['symbol_posc'];
          $data['dpe'] = $array_bal['symbol_dpe'];
          $data['ooe'] = $array_bal['symbol_ooe'];
          $data['non_interest_expense'] = $data['posc'] + $data['ooe'] + $data['dpe'];;

          $data['operating_profit_bp'] = $data['net_interest'] + $data['non_interest_income'] - $data['non_interest_expense'];

          $data['pdbd'] = $array_bal['symbol_pdbd']; //expense

          $data['profit_bit'] = $data['operating_profit_bp'] - $data['pdbd'];

          $data['eit'] = $array_bal['symbol_eit']; //expense

          $data['pcy'] = $data['profit_bit'] - $data['eit'];

          $data['net_profit_rp'] = $data['profit_bit'] - $data['eit'];

          /////*********************************************************************************************

          $data['shareholder'] = $data['sc'] + $data['r'] + $data['se_sd'] + $data['re'] + $data['pcy'];
          $data['total_ls'] = $data['liability'] + $data['shareholder'];

/*
        //in journal inquiry
        $JournalRequiry_arr = array();
        if ($start_date || $end_date){
            $JournalRequiry = JournalRequiry::where('id', '>', 0);
            if ($start_date) $JournalRequiry = $JournalRequiry->where('entry_date', '>=', $start_date);
            if ($end_date) $JournalRequiry = $JournalRequiry->where('entry_date', '<=', $end_date);
            $JournalRequiry = $JournalRequiry->get();
            foreach ($JournalRequiry as $ji) {
                $JournalRequiry_arr[] = $ji->id;
            }
        }

        //get income 5,6xx (5) **********************************************************
        $subsub_in = CoaCategory::select('id', 'parent_id', 'nbc_code','name', 'currency', 'type', 'symbol')
            ->where(function($q){ $q->where('nbc_code', 'LIKE', '5%')->orWhere('nbc_code', 'LIKE', '6%');})
            ->where('type', 6)->orderBy('nbc_code', 'asc');

        $data['currency_id'] = 2;
        if(Request::has('cur')){
        	$data['currency_id'] = Request::input('cur');
        }
        if($data['currency_id']!=100) $subsub_in->where('currency','=', $data['currency_id']);
        $subsub_in = $subsub_in->get();

        $arr_in['parent_ids'] = array();
        foreach ($subsub_in as $in) {
        	//get type 6
        	$t6 = CoaCategory::select('id')->where('type', 7)->where('parent_id', $in->id)->get();
        	$tr6 = array();
        	$tr6[] = $in->id;
        	foreach ($t6 as $t){
        		$tr6[] = $t->id;
        	}

            $B0 = new JournalDetail();
            $B0 = $this->getUserByBranch($B0, 'branch_code', $query_arr);
            $JournalDetail = $B0->select('journal_detail.id as jd_id', 'debit', 'credit', 'journal_id', 'coa_id', 'journal_requiry.id', 'journal_requiry.is_audit as audit')
                ->join('journal_requiry', 'journal_detail.journal_id', '=', 'journal_requiry.id');
        	if(Request::has('br')){
        		$p_branch_code = $data['branch_code'] = Request::input('br');
        		$JournalDetail = $JournalDetail->where('branch_code','=', $data['branch_code']);
        	}

        	$JournalDetail = $JournalDetail->whereIn('coa_id', $tr6);
            $JournalDetail = $JournalDetail->orderBy('jd_id', 'ASC');
            if ($start_date || $end_date) $JournalDetail = $JournalDetail->whereIn('journal_id', $JournalRequiry_arr);
            $JournalDetail = $JournalDetail->where('journal_requiry.is_audit', 1);
            $JournalDetail = $JournalDetail->get();

        	$count_jd = $JournalDetail->count();
            if($count_jd >=1){
                $counter = 0;
                $sum_arr = array();

        		foreach ($JournalDetail as $JD){
                    $counter++;
        			$b_debit = $JD->credit;
        			$b_credit = $JD->debit;
        			//exchange currency
        			if(Request::has('consolidate') && Request::input('consolidate')==1){
        				$data['consolidate'] = 1;
        				$rate = $data['rate'] = Request::has('rate')?Request::input('rate'):USDTOKHR;
        				if(Request::has('exchange_rate') && Request::input('exchange_rate')==1){
        					$data['exchange_rate'] = 1;
        					if($in->currency==2){ //if USD change to KHR mill....
        						$b_debit = ($b_debit*$rate)/KHRM;
        						$b_credit = ($b_credit*$rate)/KHRM;
        					}else{ //if KHR change to KHR mill....
        						$b_debit = ($b_debit)/KHRM;
        						$b_credit = ($b_credit)/KHRM;
        					}
        				}elseif (Request::has('exchange_rate') && Request::input('exchange_rate')==2){
        					$data['exchange_rate'] = 2;
        					if($in->currency==1){ //if KHR change to USD
        						$b_debit = $b_debit/$rate;
        						$b_credit = $b_credit/$rate;
        					}
        				}

        			}else{ //no consolidate
        				if($in->currency==1 && $data['currency_id']==100){
        					$b_debit = $b_debit/USDTOKHR;
        					$b_credit = $b_credit/USDTOKHR;
        				}
        			}
                    $sum_arr['b_debit'][] = $b_debit;
                    $sum_arr['b_credit'][] = $b_credit;
        		}

                $arr_in['parent_ids'][] = $in->parent_id;
                $arr_in['sum_b_debit_'.$in->parent_id][] = array_sum($sum_arr['b_debit']);
                $arr_in['sum_b_credit_'.$in->parent_id][] = array_sum($sum_arr['b_credit']);
        	}else{
                continue;
            }
        }

        //get income (5)
        $arr_subacc['parent_ids'] = array();
        $subacc_in = CoaCategory::select('id', 'parent_id', 'nbc_code', 'name', 'type', 'symbol')
            ->whereIn('id', $arr_in['parent_ids'])->get();
        foreach ($subacc_in as $in) {
            //if ($in->type == 3) $in->parent_id = $in->id;
            $arr_subacc['parent_ids'][] = $in->parent_id;
            $arr_subacc['name_' . $in->parent_id][] = $in->name; //2058

            $arr_subacc['sum_b_debit_'.$in->parent_id][] = array_sum($arr_in['sum_b_debit_'.$in->id]);
            $arr_subacc['sum_b_credit_'.$in->parent_id][] = array_sum($arr_in['sum_b_credit_'.$in->id]);
        }

        //get income (4)
        $arr_acc = array();
        $arr_acc['parent_ids'] = array();
        $acc_in = CoaCategory::select('id', 'parent_id', 'nbc_code', 'name', 'type', 'symbol')
            ->whereIn('id', $arr_subacc['parent_ids'])->get();
        foreach ($acc_in as $in) {
            $arr_acc['symbol_b_debit_'.$in->symbol][] = array_sum($arr_subacc['sum_b_debit_'.$in->id]);
            $arr_acc['symbol_b_credit_'.$in->symbol][] = array_sum($arr_subacc['sum_b_credit_'.$in->id]);
        }

        $data['iil'] = array_sum($arr_acc['symbol_b_debit_iil']);
        $data['oii'] = array_sum($arr_acc['symbol_b_debit_oii']);
        $data['income'] = $data['iil'] + $data['oii'];

        $data['ied'] = array_sum($arr_acc['symbol_b_credit_ied']);
        $data['ieb'] = array_sum($arr_acc['symbol_b_credit_ieb']);
        $data['oie'] = array_sum($arr_acc['symbol_b_credit_oie']);
        $data['expense'] = $data['ied'] + $data['ieb'] + $data['oie'];

        $data['net_interest'] = $data['income'] - $data['expense'];

        $data['ilcf'] = array_sum($arr_acc['symbol_b_debit_ilcf']);
        $data['iofc'] = array_sum($arr_acc['symbol_b_debit_iofc']);
        $data['igfe'] = array_sum($arr_acc['symbol_b_debit_igfe']);
        $data['igdpe'] = array_sum($arr_acc['symbol_b_debit_igdpe']);
        $data['rl'] = array_sum($arr_acc['symbol_b_debit_rl']);
        $data['onii'] = array_sum($arr_acc['symbol_b_debit_onii']);
        $data['non_interest_income'] = $data['ilcf'] + $data['iofc'] + $data['igfe'] + $data['igdpe'] + $data['rl'] + $data['onii'];

        $data['posc'] = array_sum($arr_acc['symbol_b_credit_posc']);
        $data['dpe'] = array_sum($arr_acc['symbol_b_credit_dpe']);
        $data['ooe'] = array_sum($arr_acc['symbol_b_credit_ooe']);
        $data['non_interest_expense'] = $data['posc'] + $data['ooe'];

        $data['operating_profit_bp'] = $data['net_interest'] + $data['non_interest_income'] - $data['non_interest_expense'];

        $data['pdbd'] = array_sum($arr_acc['symbol_b_credit_pdbd']); //expense

        $data['profit_bit'] = $data['operating_profit_bp'] - $data['pdbd'];

        $data['eit'] = array_sum($arr_acc['symbol_b_credit_eit']); //expense

        $data['net_profit_rp'] = $data['profit_bit'] - $data['eit'];
*/
        //if(Request::input('pcy')==1) die($data['net_profit_rp']);

        return $this->view('reports.income_statement_is', $data);
    }


    public function getArrears()
    {
    	$order_dir = Request::input('od')?Request::input('od'):'desc';
    	$order_class = [
    			'overdue' => Request::input('od') ? 'fa-sort-' . Request::input('od') : 'fa-sort'
    	];

    	$url = url(Request::fullUrl());
    	$order_url = [
    			'overdue' => $order_dir == 'asc' ? $this->setUrl($url, 'od=desc') : $this->setUrl($url, 'od=asc')
    	];

    	define('MODE_PAGI', 10);
    	$offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
    	$pagi = isset($_GET['page']) ? ($_GET['page'] - 1) * $offset + 1 : 1;

    	$query_arr = array('user_id'=>Auth::user()->id, 'company_branch_id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
    	$B0 = new Loan();
    	$loans_ = $B0::select('*', 'id as loan_id')->with('payment');
    	$last_month = date('Y-m-d', strtotime("-1 month"));
    	$loans_->where('last_schedule_date', '<=', $last_month)->whereIn('status', [3, 8]);
    	$arrears_date = date('Y-m-d');
    	if (Request::has('arrears_date')) {
    		$arrears_date = Request::input('arrears_date');
    		if(!empty($loans->payment)) {
    			$loans_ = $loans->whereHas('payment', function ($query) use ($arrears_date) {
    				$query->where('repayment_date', '<=', $arrears_date);
    			})->with(['payment' => function ($query) use ($arrears_date) {
    				$query->where('repayment_date', '<=', $arrears_date);
    			}]);
    		}else{
    			$loans_->where('last_schedule_date', '<=', $arrears_date);
    		}
    	}

    	if (Request::has('is_print')){
    		$loans_ = $loans_->get();
    	}else{
    		$loans_ = $loans_->paginate($offset);
    	}

    	foreach ($loans_ as $s){
    		$overdue = LoanCalculate::getTotalPenalty($s)[2];
    		$odata[] = array($overdue, $s);
    	}
    	$values = array_values($odata);

    	if($order_dir=='desc'){
    		$sort = SORT_DESC;
    	}else{
    		$sort = SORT_ASC;
    	}
    	array_multisort($values, $sort, $odata);

    	foreach ($odata as $od){
    		$s = $od[1];
    		$overdue[$s->loan_id] = LoanCalculate::getTotalPenalty($s)[2];

	        $loans = Loan::select([
	            'loans.id',
	            'loans.start_date',
	            'loans.loan_amount',
	            'loans.contract_id',
	            'loans.loan_duration',
	            'loans.down_payment',
	            'loans.interest_rate',
	            'loans.repayment_type',
	            'loans.penalty_rate_type',
	            'loans.penalty_rate1',
	            'loans.penalty_rate2',
	            'loans.penalty_period1',
	            'loans.penalty_period2',
	            'loans.payoff_period1',
	            'loans.payoff_period2',
	            'loans.payoff_period3',
	            'loans.pay_off_rate1',
	            'loans.pay_off_rate2',
	            'loans.pay_off_rate3',
	            'loans.days_of_month',
	            'loans.balloon',
	            'loans.balloon_month',
	            'loans.balloon_amount_array',
	            'loans.monthly_payment',
	            'loans.custom_flag',
	            'loans.days_of_month',
	            'loans.holiday_flag',
	            'loans.last_schedule_date',
	            'loans.co',
	            'client_id',
	            'dealer_id',
	            'product_id'
	        ]);
	        $loans->with([
	            'client' => function ($query) {
	                $query->select('id', 'client_name', 'address');
	            },
	            'schedule' => function ($query) {
	                $query->orderBy('schedule_date', 'asc');
	            },
	            'product.record' => function ($query) {
	                $query->select('id', 'product_id', 'location', 'date', 'action_type', 'remark', 'price');
	            },
	            'dealer' => function ($query) {
	                $query->select('id', 'dealer');
	            },
	            'co_user' => function ($query) {
	                $query->select('id', 'name');
	            },
	            'payment' => function ($query) {
	                $query->select(['id', 'loan_id', 'repayment_date', 'payment_month', 'status', 'condition_id', 'paid_interest', 'paid_principal', 'repayment_owed'])->orderBy('repayment_date','asc');
	            },
	            'costfee' => function ($query) {
	                $query->select(['id', 'loan_id', 'cost_amount', 'cost_type']);
	            }]
	        );
	        $loans_arr[$s->loan_id] = $loans->where('id', $s->loan_id)->get();
    	}


    	$data['odata'] = $odata;
    	$data['order_class'] = $order_class;
    	$data['order_url'] = $order_url;
    	$data['overdue'] = $overdue;
    	$data['loans_'] = $loans_;
    	$data['loans'] = $loans_arr;
    	$data['arrears_date'] = $arrears_date;
    	$data['pagi'] = $pagi;
    	$data['offset'] = $offset;
    	$data['set_url_print'] = $this->setUrl(Request::fullUrl(), 'is_print=1');

        return $this->view('reports.arrears_report', $data);
    }
    function parc_report(){
        $data['pagi'] = 5000000;
        $data['dpStart'] = Request::input('dpStart');
        $till_date = date('Y-m-d', strtotime($data['dpStart'] ." +1day"));
        $loans = Loan::with(['transaction_req'=>function($q){
                       $q->select('id','loan_id','balance','principal','interest', 'penalty', 'fee', 'other_fee', 'trans_date', 'trans_type')
                         ->whereIn('trans_type', ["Loan Repayment", "Auto Loan Repayment", "Pay-Off", "Fee Charge Repayment"])
                         ->orderBy('id', 'DESC');
                  }])
                    ->with(['schedule' => function($p) use($till_date){
                      $p->where('schedule_date', '<', $till_date)
                      ->orderBy('schedule_date', 'ASC')->get();
                    }])
                    ->with(['payment' => function($p) use($till_date){
                      $p->where('repayment_date', '<', $till_date)
                      ->orderBy('repayment_date', 'ASC')->get();
                    }])
                    ->whereIn('status', [3,8])->get();
        $today = Request::has('dpStart')?Request::input('dpStart'):date('Y-m-d');
        

        $data['company_branch_id'] = Request::input('br');
        $data['dpStart'] = Request::input('dpStart');
        $data['borrow_name'] = Request::input('borrow_name');
        $data['loan_ref'] = Request::input('loan_ref');
        $data['co_id'] = Request::input('co_id');
        $data['category_id'] = Request::input('category_id');
        $data['overdue'] = Request::has('overdue')?Request::input('overdue'):30;
        $data['loan_type_id'] = Request::input('loan_type');
        $data['exchange_rate'] = Request::has('exchange_rate')?Request::input('exchange_rate'):4000;

        $data['branch'] = $branch;
        $data['co'] = $co;
        $data['product_categories'] = $product_categories;
        $product_types= Product_type::select('id', 'code', 'products_type_name')->get();
        foreach($product_types as $pro){
        $data['loan_types'][$pro->id] = $pro;
        }
        $data['loans'] = $loans;
    
        return $this->view('reports.parc_report', $data);

    }

    function parc_report_old(){

      $data['pagi'] = 5000000;
      $order_dir = Request::input('od')?Request::input('od'):'desc';
        $order_class = ['overdue' => Request::input('od') ? 'fa-sort-' . Request::input('od') : 'fa-sort'];

        $url = url(Request::fullUrl());
        $order_url = [ 'overdue' => $order_dir == 'asc' ? $this->setUrl($url, 'od=desc') : $this->setUrl($url, 'od=asc') ];
        $query_arr = array('id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);

        $branch_d = CompanyBranch::select('id', 'branch_name')->where('status','=',1)->get();
		$branch = [];
		foreach($branch_d as $br){
			$branch[$br->id] = $br;
		}
        $query_arr = array('user_id'=>Auth::user()->id, 'company_branch_id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);

      $B0 = new User();
    	$co = $this->getUserByBranch($B0, 'branch_id', $query_arr)->where('role_id', 10)->get();
    	$product_categories = ProductCategory::get();

    	//$query_arr = array('user_id'=>Auth::user()->id, 'company_branch_id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
      //$select_date = Request::has('dpStart')?Request::input('dpStart') : date('Y-m-d');
      // ??? Many redundant queries > need to remove somehow

    	//$repayments = Loan::select('*', 'id as loan_id')->with('payment')->get();
      $act_loans = Loan::select('*')
                  ->with('client')
                  ->with('client_loan_account')
                  ->with(['transaction_req'=>function($q){
                       $q->select('id','loan_id','balance','principal','interest', 'penalty', 'fee', 'other_fee', 'trans_date', 'trans_type')
                         ->whereIn('trans_type', ["Loan Repayment", "Auto Loan Repayment", "Pay-Off", "Fee Charge Repayment"])
                         ->orderBy('id', 'DESC');
                  }])
                  ->whereIn('status', [3,8])->get();
       $today = Request::has('dpStart')?Request::input('dpStart'):date('Y-m-d');
       $total_act_loanx = 0;
       $total_paid_prinx = 0;

       $total_sch_prin = 0;
       $total_paid_prin = 0;
       $pass_due = [];

      // this is a slowing part
     foreach ($act_loans as $s){
           $sum_sch_prin = 0;
           $sum_sch_int = 0;

            $total_act_loanx += ($s->client_loan_account->currency == 1)? doubleval($s->loan_amount)/4000.00 : doubleval($s->loan_amount);

          foreach($s->schedule as $sch){
               if(date_dif($sch->schedule_date, $today, 1 ,false) >= 0) {
                   $sum_sch_prin += floatval($sch->principal);
                   $sum_sch_int += floatval($sch->interest);
               }
           }
           $total_sch_prin += $s->loan_amount;
           $sum_paid_prin = 0;
           $sum_paid_int = 0;
           foreach($s->transaction_req as $pay){
             if(date_dif($pay->trans_date, $today, 1 ,false) >= 0) {
               $sum_paid_prin += floatval($pay->principal);
               $sum_paid_int += floatval($pay->interest);

               $total_paid_prinx += ($s->client_loan_account->currency == 1)? doubleval($pay->principal)/4000.00 : doubleval($pay->principal);

             }
           }
           $total_paid_prin += $sum_paid_prin;

           $pass_due[$s->id] = get_pass_due($s, null, $today);
           $overdue = $overdue_[$s->id] = $pass_due[$s->id]->overdue;
           if(Request::has('overdue')){
               if($overdue < Request::input('overdue')) continue;
           }else{
               if($overdue < 30) continue;
           }
           // overdue only on principal
           //if($s->id == 22) {var_dump($sum_paid_prin); var_dump($sum_sch_prin); dd($s);dd($pass_due[$s->id] );}
           //if(($sum_sch_prin - $sum_paid_prin) <= 0) continue;
           if($overdue <= 0) continue;
           $loans = Loan::select('loans.*', 'product_brand.brand_name', 'product_category.category_name', 'products.product_type', 'products.product_price', 'clients.client_name', 'products.mou_price', 'products.status as p_status')
                         ->LeftJoin('products', 'product_id', '=', 'products.id')
                         ->LeftJoin('product_brand', 'product_brand.id', '=', 'brand_id')
                         ->LeftJoin('product_category', 'category_id', '=', 'product_category.id')
                         ->LeftJoin('clients', 'loans.client_id', '=', 'clients.id')
                         ->LeftJoin('transactions_requiry', 'transactions_requiry.loan_id', '=', 'loans.id')
                         ->with(['schedule' => function ($query) { $query->select(DB::raw('SUM(interest) as s_interest'), 'schedule_date', 'loan_id')->groupBy('loan_id'); }])
                         ->with(['transaction_req' => function ($query) { $query->select(DB::raw('SUM(interest) as t_interest'), 'loan_id')->groupBy('loan_id'); }])
                         ->with(['transaction_reqLastBalance' => function ($query) { $query->select('balance', 'loan_id')->orderBy('id', 'DESC')->first();}])
                         ->with(['transaction_req_last' => function ($query) { $query->select('id', 'balance', 'loan_id')->orderBy('id', 'DESC')->first(); }])
                         ->groupBy('transactions_requiry.loan_id')
                         ->where('transactions_requiry.loan_id', $s->id) // 0 not pay, 1 pay some, 2 paid
                         ->whereIn('loans.status',[3,8]);

       if(Request::has('borrow_name')){
         $loans = $loans->where('client_name', 'like', '%'.Request::input('borrow_name').'%');
       }

       if(Request::has('loan_ref')){
         $loans = $loans->where('contract_id', Request::input('loan_ref'));
       }

       if(Request::has('co_id')){
         $loans = $loans->where('loans.co', Request::input('co_id'));
       }

       if(Request::has('category_id')){
         $loans = $loans->where('category_id', Request::input('category_id'));
       }

       if(Request::has('dpStart')){
         $loans = $loans->where('trans_date', '<=',  Request::input('dpStart'));
       }

       if(Request::has('br')){
         $loans = $loans->where('loans.company_branch_id', Request::input('br'));
       }

       if(Request::has('loan_type')){
           $loans = $loans->where('loans.loan_type', Request::input('loan_type'));
       }
       $loans_[$s->id] = $loans->get();
     }

     $data['company_branch_id'] = Request::input('br');
     $data['dpStart'] = Request::input('dpStart');
     $data['borrow_name'] = Request::input('borrow_name');
     $data['loan_ref'] = Request::input('loan_ref');
     $data['co_id'] = Request::input('co_id');
     $data['category_id'] = Request::input('category_id');
     $data['overdue'] = Request::input('overdue');
     $data['overdue_'] = $overdue_;
     $data['pass_due'] = $pass_due;
     $data['loan_type_id'] = Request::input('loan_type');

     $data['branch'] = $branch;
     $data['co'] = $co;
     $data['product_categories'] = $product_categories;
     $product_types= Product_type::select('id', 'code', 'products_type_name')->get();
     foreach($product_types as $pro){
        $data['loan_types'][$pro->id] = $pro;
     }
     $data['act_loans'] = $act_loans;
     $data['loans'] = $loans_;
     $data['TOS'] = $total_act_loanx - $total_paid_prinx;
    //   dd($loans_);
/*
    	foreach ($repayments as $s){
    		$overdue = LoanCalculate::getTotalPenalty($s)[2];
    		$odata[$overdue][] = array($overdue, $s);
    	}

    	if($order_dir=='desc'){
    		krsort($odata);
    	}else{
    		ksort($odata);
    	}

    	foreach ($odata as $od){
    		$s = $od[0][1];
    		$overdue = $overdue_[$s->loan_id] = LoanCalculate::getTotalPenalty($s)[2];
    		if(Request::has('overdue')){
    			if($overdue < Request::input('overdue')) continue;
    		}else{
    			if($overdue < 30) continue;
    		}

	    	$loans = Loan::select('loans.*', 'product_brand.brand_name', 'product_category.category_name', 'products.product_type', 'products.product_price', 'client_loan_accounts.balance', 'clients.client_name', 'products.mou_price', 'products.status as p_status')
	    	->Join('products', 'product_id', '=', 'products.id')
	    	->Join('product_brand', 'product_brand.id', '=', 'brand_id')
	    	->Join('product_category', 'category_id', '=', 'product_category.id')
	    	->Join('client_loan_accounts', 'loan_account_id', '=', 'client_loan_accounts.id')
	    	->Join('clients', 'loans.client_id', '=', 'clients.id')
	    	->Join('transactions_requiry', 'transactions_requiry.loan_id', '=', 'loans.id')
            ->with(['payment'])
	    	->with(['schedule' => function ($query) { $query->select(DB::raw('*', 'SUM(interest) as s_interest'))->groupBy('loan_id'); }])
            ->with(['scheduleOne'])
	    	->with(['transaction_req' => function ($query) { $query->select(DB::raw('SUM(interest) as t_interest'), 'loan_id')->groupBy('loan_id'); }])
	    	->with(['transaction_reqLastBalance' => function ($query) { $query->select('balance', 'loan_id')->orderBy('id', 'DESC')->first();}])
	    	->groupBy('transactions_requiry.loan_id')
	    	->where('transactions_requiry.loan_id', $s->loan_id) // 0 not pay, 1 pay some, 2 paid
	    	->whereIn('loans.status',[3,8]);
	    	if(Request::has('borrow_name')){
	    		$loans = $loans->where('client_name', 'like', '%'.Request::input('borrow_name').'%');
	    	}

	    	if(Request::has('loan_ref')){
	    		$loans = $loans->where('contract_id', Request::input('loan_ref'));
	    	}

	    	if(Request::has('co_id')){
	    		$loans = $loans->where('loans.co', Request::input('co_id'));
	    	}

	    	if(Request::has('category_id')){
	    		$loans = $loans->where('category_id', Request::input('category_id'));
	    	}

	    	if(Request::has('dpStart')){
	    		$loans = $loans->where('trans_date', '<=',  Request::input('dpStart'));
	    	}

	    	if(Request::has('br')){
	    		$loans = $loans->where('loans.company_branch_id', Request::input('br'));
	    	}

			if(Request::has('loan_type')){
                $loans = $loans->where('loans.loan_type', Request::input('loan_type'));
            }

	    	$loans_[$s->loan_id] = $loans->get();
    	}

    	$data['odata'] = $odata;
    	$data['order_class'] = $order_class;
    	$data['order_url'] = $order_url;

    	$data['company_branch_id'] = Request::input('br');
    	$data['dpStart'] = Request::input('dpStart');
    	$data['borrow_name'] = Request::input('borrow_name');
    	$data['loan_ref'] = Request::input('loan_ref');
    	$data['co_id'] = Request::input('co_id');
    	$data['category_id'] = Request::input('category_id');
    	$data['overdue'] = Request::input('overdue');
    	$data['overdue_'] = $overdue_;
		$data['loan_type_id'] = Request::input('loan_type');

    	$data['branch'] = $branch;
    	$data['co'] = $co;
    	$data['product_categories'] = $product_categories;
    	$data['repayments'] = $repayments;
    	$data['loans'] = $loans_;
    	$data['pagi'] = $pagi;
      $data['TOS'] = $total_act_loan - $total_paid_prin;
      */
    	return $this->view('reports.parc_report', $data);
    }
}
