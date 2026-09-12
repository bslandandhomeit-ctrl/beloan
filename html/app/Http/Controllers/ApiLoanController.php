<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 6/10/2015
 * Time: 1:33 PM
 */

namespace App\Http\Controllers;


use App\Models\FeeCharge;
use App\Models\Guarantor;
use App\Models\Holiday;
use App\Models\Loan;
use App\Models\LoanRestructure;
use App\Models\RepaymentSchedule;
use App\Models\RescheduleRepaymentTemp;
use App\Models\User;
use App\Models\LoanCollateral;
use App\Models\LoanCostFee;
use App\Models\LoanDocument;
use App\Models\LoanPayments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Request;
use LoanCalculate;
use App\Models\ScheduleFee;
use App\Models\Client;
use App\Models\LoanGuarantor;
use App\Models\CoBorrower;
use App\Models\TransferClient;
use App\Models\DrawdownAccounts;

class ApiLoanController extends Controller{

    public function __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }
    public function charge($loan_id = 0)
    {
        $data = [];
        if($loan_id > 0){
            $data['fee_charge'] = FeeCharge::where('loan_id','=',$loan_id)->get();
            $data['cost_fee'] = LoanCostFee::where('loan_id','=',$loan_id)->get();
        }
        return view('api.charge',['charge'=>$data])->render();
    }

    public function guarantor($loan_id = 0)
    {
        $data = [];
        if($loan_id > 0){
          //$data = Guarantor::with('collateral')->where('loan_id','=',$loan_id)->get();
            // $client_loan_account = Loan::where('id', $loan_id)->first()->client_loan_account;
            // $co_borrower_id_arr = $client_loan_account->sub_client_id;
            // $guarantor_id_arr = $client_loan_account->guarantor;
            // $co_borrower_id_arr = json_decode(preg_replace('/\s+/', ' ', $co_borrower_id_arr));
            // $guarantor_id_arr = json_decode(preg_replace('/\s+/', ' ', $guarantor_id_arr));

            // $co_borrowers = Client::whereIn('id', $co_borrower_id_arr)->get();
            // $guarantors = Client::whereIn('id', $guarantor_id_arr)->get();
            // $co_borrowers = $co_borrower_id_arr;
            // $guarantors = $guarantor_id_arr;
            $loan_guarantor  = LoanGuarantor::where('loan_id',$loan_id)->get();
        }
        return view('api.guarantor',['co_borrowers' => $co_borrowers,'guarantors' => $guarantors,'loan_guarantor'=>$loan_guarantor])->render();
    }

    public function co_borrower($loan_id = 0)
    {
        $data = [];
        if($loan_id > 0){
            $data['co_borrower']  = CoBorrower::where('loan_id',$loan_id)->get();
        }
        return view('api.co_borrower',$data)->render();
    }

    public function customertranfer($loan_id = 0)
    {
        $data = [];
        if($loan_id > 0){
            $data['customertranfer']  = TransferClient::where('loan_id',$loan_id)
                                        ->with(['Clients' => function ($query) {
                                            $query->select('id', 'client_name');
                                        },'newClients' => function ($query) {
                                            $query->select('id', 'client_name');
                                        }])
                                        ->get();
        }
        return view('api.customertranfer',$data)->render();
    }

    public function document($loan_id = 0)
    {
        $data = [];
        if($loan_id  > 0){
            $data['client_collateral'] = LoanCollateral::where('loan_id','=',$loan_id)->get();
            $data['loan_doc'] = LoanDocument::where('loan_id','=',$loan_id)->groupBy('doc_type')->get(); //print_r($data['loan_doc']); die();
            $data['loan_dealer'] = Loan::select('id','dealer_id','product_id')
                                        ->with(['loanDealer'=>function($query){
                                                $query->with('bank');
                                            },
                                            'dealer'=>function($query){
                                                $query->select('id','dealer');
                                            }])
                                        ->where('id','=',$loan_id)
                                        ->first();

            $arr_t = $this->doc_type_labe();
            $data['arr'] = $arr_t['arr'];
            $data['arrt'] = $arr_t['arrt'];
        }
        return view('api.document',$data)->render();
    }

    function doc_type_labe(){
    	$arr['1__lad'] = 'Loan Approval Document';
    	$arr['2__es'] = 'Executive Summary (ES)';
    	$arr['3__pi'] = 'Proof of Incomes (Financial Statement) with evidence';
    	$arr['4__cc'] = 'Customer Consent (for CBC checking)';
    	$arr['5__cbc'] = 'CBC Report';
    	$arr['6__cerie'] = 'Collateral Evaluation Report include evidences';
    	$arr['7__dibg'] = 'Draft of information of borrower(s) and guarantor(s)';
    	$arr['8__pbs'] = 'Pictures of business sites';

    	$arr['9__la'] = 'Loan Agreement';
    	$arr['10__ga'] = 'Guarantor Agreement';
    	$arr['11__ha_1'] = 'Hypothec Agreement (1)';
    	$arr['12__ha_2'] = 'Hypothec Agreement (2)';
    	$arr['13__ha_3'] = 'Hypothec Agreement (3)';
    	$arr['14__ha_4'] = 'Hypothec Agreement (4)';
    	$arr['15__ha_5'] = 'Hypothec Agreement (5)';
    	$arr['16__ha_6'] = 'Hypothec Agreement (6)';
    	$arr['17__ha_7'] = 'Hypothec Agreement (7)';

    	$arr['18__lmotd_1'] = 'Letter of maintaining the original title deed(s 1)';
    	$arr['19__lmotd_2'] = 'Letter of maintaining the original title deed(s 2)';
    	$arr['20__lmotd_3'] = 'Letter of maintaining the original title deed(s 3)';
    	$arr['21__lmotd_4'] = 'Letter of maintaining the original title deed(s 4)';
    	$arr['22__lmotd_5'] = 'Letter of maintaining the original title deed(s 5)';
    	$arr['23__lmotd_6'] = 'Letter of maintaining the original title deed(s 6)';
    	$arr['24__lmotd_7'] = 'Letter of maintaining the original title deed(s 7)';

    	$arr['25__ol_1'] = 'Obstructive Letter (Letter to Sangkat 1)';
    	$arr['26__ol_2'] = 'Obstructive Letter (Letter to Sangkat 2)';
    	$arr['27__ol_3'] = 'Obstructive Letter (Letter to Sangkat 3)';
    	$arr['28__ol_4'] = 'Obstructive Letter (Letter to Sangkat 4)';
    	$arr['29__ol_5'] = 'Obstructive Letter (Letter to Sangkat 5)';
    	$arr['30__ol_6'] = 'Obstructive Letter (Letter to Sangkat 6)';
    	$arr['31__ol_7'] = 'Obstructive Letter (Letter to Sangkat 7)';

    	$arr['32__otd_1'] = 'Original title deed(s 1)';
    	$arr['33__otd_2'] = 'Original title deed(s 2)';
    	$arr['34__otd_3'] = 'Original title deed(s 3)';
    	$arr['35__otd_4'] = 'Original title deed(s 4)';
    	$arr['36__otd_5'] = 'Original title deed(s 5)';
    	$arr['37__otd_6'] = 'Original title deed(s 6)';
    	$arr['38__otd_7'] = 'Original title deed(s 7)';

    	$arr['39__vsp'] = 'Vehicle sale purchase letter';
    	$arr['40__ovd'] = 'Original Vehicle document(s)';

    	$arr['41__bid'] = 'Borrower’s identification such as ID card, Passport, est. (hard copy)';
    	$arr['42__gid'] = 'Guarantor’s identification such as ID card, Passport, est. (hard copy)';

    	$arr['43__ord_1'] = 'Other Relevance Documents (1)';
    	$arr['44__ord_2'] = 'Other Relevance Documents (2)';
    	$arr['45__ord_3'] = 'Other Relevance Documents (3)';
    	$arr['46__ord_4'] = 'Other Relevance Documents (4)';
    	$arr['47__ord_5'] = 'Other Relevance Documents (5)';
    	$arr['48__ord_6'] = 'Other Relevance Documents (6)';
    	$arr['49__ord_7'] = 'Other Relevance Documents (7)';
    	$arr['50__ord_8'] = 'Other Relevance Documents (8)';
    	$arr['51__ord_9'] = 'Other Relevance Documents (9)';
    	$arr['52__ord_10'] = 'Other Relevance Documents (10)';

    	$arr['53__dr'] = 'Disbursement Request';
    	$arr['54__dv'] = 'Disbursement Voucher (copy of slip or check)';
    	$arr['55__rs'] = 'Repayment Schedule ';

        $arr['56__add'] = 'New contract';
        $arr['57__add_change_unit'] = 'Change Unit';
        $arr['58__add_downpayment'] = 'Down Payment';
        $arr['59__add_pay_off'] = 'Pay Off';
        $arr['60__add_sub_sale'] = 'Sub Sale';
        $arr['61__add_terminate_contract'] = 'Terminate Contract';
        $arr['62__add_restructure_loan'] = 'Restructure Loan';
        $arr['63__add_add_remove_name'] = 'Add and Remove Name';
        $arr['64__add_other'] = 'Other';

    	$arrt['i_d'] = 'I. DOCUMENTS OF CREDIT ASSESSMENT';
    	$arrt['ii_d'] = 'II. DOCUMENTS OF LOAN ADMINISTRATION';
    	$arrt['ii_d_a'] = 'a) Agreements';
    	$arrt['ii_d_b'] = 'b) Collaterals';
    	$arrt['ii_d_c'] = 'c) Identification';
    	$arrt['iii_d'] = 'III. DOCUMENTS OF LOAN DISBURSEMENT';
         $arrt['iiii_d'] = 'IV. Document of Contract Team';

    	return array('arr'=>$arr, 'arrt'=>$arrt);
    }


    public function actual_repayment($loan_id = 0)
    {
        if ( $loan_id > 0 ) {
            $loan = Loan::select(
                'id','loan_amount','interest_rate','start_date',
                'loan_duration','repayment_type','days_of_month',
                'balloon','balloon_month','balloon_amount_array',
                'monthly_payment','custom_flag','days_of_month',
                'holiday_flag','co','status','down_payment'
            )->with(['payment'=>function($q){
				$q->orderBy('id','asc');
			},'schedule' => function($query){
                $query->orderBy('schedule_date','asc');
            }])->where('id','=',$loan_id)->first();
            return view( 'api.actual_repayment', ['loan' => $loan] )->render();
        }
    }

    public function statement($loan_id = 0)
    {
        if ( $loan_id > 0 ) {
            $loan = Loan::select(
                'id','loan_amount','drawdown_acc','interest_rate','start_date',
                'loan_duration','repayment_type','days_of_month',
                'balloon','balloon_month','balloon_amount_array',
                'monthly_payment','custom_flag','days_of_month',
                'holiday_flag','co','status','down_payment','client_id','unit_id','project_id','unit_sale_price','discount_promotion','discount_other',
                'amount_discount_payment_option','clearance_amount','down_payment_value','loan_amount','annual_interest','loan_account_id','status_remark','status_remark_2'
            )->with(['payment'=>function($q){
				$q->orderBy('id','asc');
			},'schedule' => function($query){
                $query->orderBy('schedule_date','asc');
            },'client','units','projects','client_loan_account'])->where('id','=',$loan_id)->first();

            $dpDateClone = !is_null(Request::input('dpDate')) ? Request::input('dpDate') : date('Y-m-d');
            if(!$dpDateClone){
                $dpDateClone = date('Y-m-d');
            }
            $get_restructure_balance = get_restructure_balance($loan);
            $sum_principal = $get_restructure_balance['balance_loan'];
            $data['interest'] = get_total_int_till_today_restructure($loan ,$loan->client_loan_account, $dpDateClone,$sum_principal)['interest'];
            $data['interest_day'] = get_total_int_till_today_restructure($loan ,$loan->client_loan_account, $dpDateClone,$sum_principal)['days'];
            $data['drawdown_acc'] = DrawdownAccounts::where('client_id', $loan->client_id)
            ->where('account_no', $loan->drawdown_acc)
            ->first();
    
// dd($data);
            return view( 'api.statement', ['loan' => $loan,'data'=>$data] )->render();
        }
    }

    public function repayment_schedule($loan_id = 0)
    {
        if($loan_id > 0){
            $data = Request::input();
            $data['repayment_schedule'] = RepaymentSchedule::where('loan_id',$loan_id)->where('type','loan')->get();
            $data['downpayment'] = RepaymentSchedule::where('loan_id',$loan_id)->where('type','downpayment')->get();
            $data['currency_symbol'] = Loan::where('id', $loan_id)->first()->client_loan_account->currencies->symbol;
            return view('api.repayment_schedule',$data)->render();
        }
    }
    public function repayment_schedule_toapprove($loan_id = 0){
        if($loan_id > 0){
            $data = Request::input();
            $loan = Loan::select('restructure_id')->where('id',$loan_id)->first();
            $data['restructure'] = LoanRestructure::find($loan->restructure_id);
            $data['repayment_schedule'] = RescheduleRepaymentTemp::where('loan_id',$loan_id)->where('restructure_id',$loan->restructure_id)->where('type','loan')->orderBy('no','ASC')->get();
            $data['downpayment'] = RescheduleRepaymentTemp::where('loan_id',$loan_id)->where('restructure_id',$loan->restructure_id)->where('type','downpayment')->orderBy('no','ASC')->get();
            $data['currency_symbol'] = Loan::where('id', $loan_id)->first()->client_loan_account->currencies->symbol;
            return view('reschedule.repayment_reschedule',$data)->render();
        }
    }
    public function repaymentinfo()
    {
       
        $data = Request::input();
        $data['holidays'] = [];
        if(isset($data['holiday_flag'])){
            if($data['holiday_flag'] == 1){
                $date = add_month(date('Y-m-d', strtotime($data['l_start_date'])), $data['l_tenure'] + 1)->format('Y-m-d');
                $holidays = Holiday::whereBetween('holiday_date',[$data['l_start_date'],$date])->orderBy('holiday_date')->lists('holiday_date');
                $data['holidays'] = $holidays;
            }
        }
        return view('api.repaymentinfo',$data)->render();
    }
    public function todaypay($loan_id = 0)
    {
        $data = [];
        if($loan_id > 0){
            $loan = Loan::select(
                'id',
                'loan_amount',
                'interest_rate',
                'start_date',
                'loan_duration',
                'repayment_type',
                'days_of_month',
                'balloon',
                'balloon_month',
                'balloon_amount_array',
                'monthly_payment',
                'custom_flag',
                'days_of_month',
                'penalty_rate_type',
                'penalty_rate1',
                'penalty_rate2',
                'penalty_period1',
                'penalty_period2',
                'holiday_flag',
                'status',
                'loan_account_id',
                'loan_penalty_type'
            )->with(['payment' => function($query){
                 $query->select('id','loan_id','payment_month','repayment_date','paid_interest', 'paid_principal', 'paid_fee', 'paid_other_fee', 'condition_id',
                               'repayment_owed','status');
                },
                'schedule' => function($q){
                    $q->orderBy('schedule_date','asc');
                }
            ])->where('id','=',$loan_id)->first();
            $data['loan'] = $loan;
        }
        return view('api.todaypay',$data)->render();
    }
    public function transaction($loan_id = 0)
    {
        $data = [];
        if($loan_id > 0){
            $loan = Loan::select('loans.id','disburse_date', 'company_branch.branch_name', 'loan_account_id', 'interest_rate', 'rate_type', 'original_amount', 'disburse_date', 'transfer_date', 'contract_id')
                        ->leftJoin('company_branch','company_branch.id','=','loans.company_branch_id')
                        ->with(['transaction'=>function($q){
                            $q->orderBy('trans_date','asc');
                        }])->where('loans.id','=',$loan_id)->first();
            $data['loan'] = $loan;
        }
        return view('api.transaction',$data);
    }

    /*public function edit_repayment_schedule()
    {
        $id = Request::input('id',0);
        $lid = Request::input('lid',0);
        $data = Request::input();
        if($id > 0 && $lid > 0){
            if(Request::has('interest') && Request::has('principal') && Request::has('re_date')){
                $in = Request::input('interest',0);
                $pr = Request::input('principal',0);
                $date = Request::input('re_date');
                $rps = RepaymentSchedule::find($id);
                if(!empty($rps)){
                    $rps->interest = $in;
                    $rps->principal = $pr;
                    if(!empty($date)){
                        $rps->schedule_date = date('Y-m-d',strtotime($date));
                    }
                    if($rps->save()){
                        $data['repayment_schedule'] = RepaymentSchedule::where('loan_id',$lid)->get();
                        return view('api.repayment_schedule',$data)->render();
                    }
                }
            }
        }
        return null;
    }*/

    public function edit_repayment_schedule()
    {
        $id = Request::input('id',0);
        $lid = Request::input('lid',0);
        $data = Request::input();
        $addNew = Loan::find($lid);
        if($id==0){
            $addNew->disburse_date = date('Y-m-d', strtotime(Request::input('re_date')));
            $addNew->start_date = date('Y-m-d', strtotime(Request::input('re_date')));
            $addNew->save();
        }

        $rps = RepaymentSchedule::find($id);
        $rps_prev = RepaymentSchedule::where('loan_id', $lid)->where('id', '<', $id)->orderBy('id', 'DESC')->first();
        $rps_next = RepaymentSchedule::where('loan_id', $lid)->where('id', '>', $id)->orderBy('id', 'ASC')->first();

        if($rps_prev){
            $prev_date = $rps_prev->schedule_date;
        }elseif(!$rps_prev && $addNew->disburse_date){
            $prev_date = $addNew->disburse_date;
        }else{
            $prev_date = $addNew->submitted_on;
        }

        $reschedule_edit = array('start_date'=>Request::input('re_date'), 'prev_date'=>$prev_date, 'next_date'=>$rps_next?$rps_next->schedule_date:'');

        $holiday = [];
        if ($addNew->holiday_flag == 1) {
            $holiday = $addNew->holiday;
        }
        $repayment_array = LoanCalculate::monthly_loan_schedule($addNew->repayment_type, $addNew->start_date, $addNew->loan_duration, $addNew->loan_amount, $addNew->interest_rate, $addNew->balloon, $addNew->balloon_month,
            $addNew->monthly_payment, $addNew->balloon_amount_array, $addNew->custom_flag, $addNew->days_of_month, $addNew->holiday_flag, $holiday, 0, null, $addNew->disburse_date, null, $reschedule_edit)[0];

        if($lid > 0){
            if(Request::has('interest') && Request::has('principal') && Request::has('re_date')){
                $in = Request::input('interest',0);
                $pr = Request::input('principal',0);
                $date = Request::input('re_date');
                if(!empty($rps)){
                    $rps->interest = $in;
                    $rps->principal = $pr;
                    if(!empty($date)){
                        $rps->date_num = $repayment_array[1][1];
                        $rps->schedule_date = date('Y-m-d',strtotime($date));
                    }
                    if($rps->save()){

                    }
                }
                if($rps_next){
                    $rps1 = RepaymentSchedule::find($rps_next->id);
                    //$rps1->interest = $repayment_array[2][2];
                    $rps1->date_num = $repayment_array[2][1];
                    $rps1->save();
                }
            }

            $data['repayment_schedule'] = RepaymentSchedule::where('loan_id',$lid)->get();
            return view('api.repayment_schedule',$data)->render();
        }
        return null;
    }


    public function schedule_fee($loan_id = 0)
    {
        $data['res'] = ScheduleFee::where('loan_id', $loan_id)->orderBy('schedule_date', 'ASC')->get();
        return view('api.schedule_fee',$data);
    }

    function schedule_fee_update(){
        $fee = ScheduleFee::find(Request::input('id'));
        $fee->amount = Request::input('amount');
        $fee->note = Request::input('note');
        $fee->save();
        die('OK');
    }
}
