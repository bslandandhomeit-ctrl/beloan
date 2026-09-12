<?php
/**
 * Created by PhpStorm.
 * User: SOTheary
 * Date: 6/27/2016
 * Time: 5:40 PM
 */

namespace App\Http\Controllers;

use App;
use App\Models\Audit;
use App\Models\CollectionAction;
use App\Models\ItemConstant;
use App\Models\ItemRefSub;
use App\Models\Loan;
use App\Models\MasterLoan;
use App\Models\Products\Product_type;
use App\Models\RepaymentSchedule;
use Auth;
use DB;
use File;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Image;
use Request;
use URL;

//use App\Http\Requests\Request;

class LoanRecoveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }

//    public function getSummary()
//    {
//        $offset = isset($_GET['offset']) ? $_GET['offset'] : 1500;
//        $loan_list = Loan::select([
//                'loans.id',
//                'loans.contract_id',
//                'loans.loan_amount',
//                'loans.unit_sale_price',
//                'loans.price_after_discount',
//                'loans.down_payment',
//                'loans.loan_duration',
//                'loans.interest_rate',
//                'clients.client_name',
//                'clients.phone1',
//                'clients.address',
//                'loans.unit_id',
//            ]
//        )
//            ->with(['approval' => function ($query) {
//                $query->select('id', 'loan_id', 'approval_date');
//            }, 'client_loan_account', 'payoff'])
//            ->leftJoin('clients', 'clients.id', '=', 'loans.client_id')
//            ->paginate($offset);
//
//        $data['loan_list'] = $loan_list;
//
//        return $this->view('loan_recovery.summary', [
//            'loans' => $loan_list, 'offset' => $offset
//        ]);
//    }

    public function getSummary()
    {
        $data = $this->getActionPlanCompleteData();
        $data['is_summary_page'] = true;
        return $this->view('loan_recovery.action_plan', $data);
    }

    public function getActionPlan()
    {
        $data = $this->getActionPlanCompleteData();
        return $this->view('loan_recovery.action_plan', $data);
    }
    public function getActionPlanJson()
    {
        $setting = [
            'is_json' => true
        ];
        $data = $this->getActionPlanCompleteData($setting);
        return response()->json($data);
    }

    private function getActionPlanCompleteData($setting = []) {
        //        $offset = isset($_GET['offset']) ? $_GET['offset'] : 1500;
        $pageIndex = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
        $pagi = isset($_GET['page']) ? ($_GET['page'] - 1) * $offset + 1 : 1;

//        $limit = isset($_GET['size']) ? (int) $_GET['size'] : 15;
//        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
//        $offset = ($page - 1) * $limit;

        $loan_list_query = MasterLoan::select([
//            Contract ID
                'trn_master_loans.contract_id',
//            Main Project
                'trn_master_loans.main_project_name',
//            Variance Code
                'trn_master_loans.variant_code',
//            Customer Name
                'clients.client_name',
//            Phone Number
                'clients.phone1',
//            Disbursement Date
                'trn_master_loans.disbursement_date',
//            Loan Amount
                'trn_master_loans.loan_amount',
//            Outstanding Balance
                'trn_master_loans.outstanding_balance',
//            DD Account
                'trn_master_loans.drawdown_account',
//            DD Balance
                'trn_master_loans.drawdown_balance',
//            Pmt. No.
                'trn_master_loans.payment_no',
//            Sch.Date
                'trn_master_loans.schedule_date',
//            Schedule Amount
                'trn_master_loans.schedule_amount',
//            Arrear Date
//            Overdue Day
//            Overdue Amount
//            Penalty Amount
//            Amount to be Collect
//            Payment Status
//            Last Calling Date
//            Negotiation Progress
//            Result
//            Customer Respond
                'trn_master_loans.id',
                'trn_master_loans.loan_id',
                'trn_master_loans.unit_sale_price',
                'trn_master_loans.price_after_discount',
//                'trn_master_loans.down_payment',
//                'trn_master_loans.loan_duration',
//                'trn_master_loans.interest_rate',
                'clients.address',
                'trn_master_loans.unit_id',
                'trn_master_loans.assignee_id',
            ]
        )
            ->with(['approval' => function ($query) {
                $query->select('id', 'loan_id', 'approval_date');
            }, 'client_loan_account', 'payoff'])
            ->leftJoin('clients', 'clients.id', '=', 'trn_master_loans.client_id')
            ->whereRaw("MONTH(tb_trn_master_loans.created_at) = MONTH('". date('Y-m-d') ."') AND " .
                "YEAR(tb_trn_master_loans.created_at) = YEAR('". date('Y-m-d') ."')");

        // Todo: Search Filter
        if (Request::isMethod('post') && isset($_POST['search_filter'])) {
            $searchData = Request::all();
            // Todo: search by Unit Code, LC, Name, Phone
            if (isset($searchData['input_search']) && !empty($searchData['input_search'])) {
                $loan_list_query->where(function($q) use ($searchData) {
                    $q->where('trn_master_loans.variant_code', $searchData['input_search'])
                        ->orwhere('trn_master_loans.contract_id', $searchData['input_search'])
                        ->orwhere('clients.phone1', $searchData['input_search'])
                        ->orwhere('clients.client_name', 'like', '%'. $searchData['input_search'] .'%');
                });
            }
            if (isset($searchData['filter_result']) || isset($searchData['filter_promise_date']) || isset($searchData['filter_last_call_date'])) {
                $actionListQuery = CollectionAction::select('loan_id');
                $isHasFilter = false;
                // Todo: search by Result ,filter_result
                if (isset($searchData['filter_result']) && !empty($searchData['filter_result'])) {
                    $isHasFilter = true;
                    $actionListQuery
                        ->where('customer_response', $searchData['filter_result']);
                }
                // Todo: search by Promise Date ,filter_promise_date
                if (isset($searchData['filter_promise_date']) && !empty($searchData['filter_promise_date'])) {
                    $isHasFilter = true;
                    $actionListQuery
                        ->whereDate('promise_pay_date', '=', $searchData['filter_promise_date']);
                }
                // Todo: search by Last Calling Date ,filter_last_call_date
                if (isset($searchData['filter_last_call_date']) && !empty($searchData['filter_last_call_date'])) {
                    $isHasFilter = true;
                    $actionListQuery
                        ->whereDate('action_date', '=', $searchData['filter_last_call_date'])
                        ->where('action', ItemConstant::SUB_ITEM_REF_COL_ACTION_1);
                }
                if ($isHasFilter) {
                    $loanIdList = [];
                    $actionList = $actionListQuery->get();
                    if ($actionList) {
                        foreach ($actionList as $action) {
                            $loanIdList[] = $action->loan_id;
                        }
                    }
                    $loan_list_query
                        ->whereIn('loan_id', $loanIdList);
                }
            }
            // Todo: search by Main Project ,filter_main_project
            if (isset($searchData['filter_main_project']) && !empty($searchData['filter_main_project'])) {
                $loan_list_query
                    ->where('project_id', $searchData['filter_main_project']);
            }
        }

        $action_customer_responses = ItemRefSub::getAllByRefCode(ItemConstant::ITEM_REF_COL_CUS_RESP);
        $data['action_customer_responses'] = $action_customer_responses;
        $action_payment_status = ItemRefSub::getAllByRefCode(ItemConstant::ITEM_REF_COL_PAYMENT_STATUS);
        $data['action_payment_status'] = $action_payment_status;
        $data['main_projects'] = App\Models\Project::select([
            'id as code',
//            DB::raw('IF(dealer_en IS NULL or dealer_en = \'\', dealer, dealer_en) as description')])
            DB::raw('short_code as description')])
            ->where('active', '1')->get();

        $data['is_can_assign'] = MasterLoan::isHasPermissionAssign();
        // Todo: check if not Sup user need to filter assign loan only
        if (!$data['is_can_assign']) {
            $loan_list_query->where('assignee_id', Auth::user()->id);
        }
        $data['loans'] = array();
        // Todo: Search Filter
        if (Request::isMethod('post') && isset($_POST['search_filter'])) {
            $searchData = Request::all();
            $loanList = $loan_list_query->get();
            $loanCollection = collect($loanList);
            if ($loanCollection->count() > 0) {
                $loanArray = $loanCollection->filter(function ($item) use ($searchData) {
                    $isMatch = true;
                    // Todo: search by Payment Status, filter_payment_status
                    if (isset($searchData['filter_payment_status']) && !empty($searchData['filter_payment_status'])) {
                        $status = $item->getPaymentStatus();
                        if ($status != null) {
                            $isMatch &= ($status->code == $searchData['filter_payment_status']);
                        }
                    }
                    // Todo: search by Overdue Day ,filter_overdue_day
                    if (isset($searchData['filter_overdue_day'])) {
                        $filterOverdue = (int) $searchData['filter_overdue_day'];
                        if ($filterOverdue > 0) {
                            $loanOverdue = (int) $item->getOverdueDay();
                            if ($loanOverdue < $filterOverdue) {
                                $isMatch &= false;
                            }
                        }
                    }
                    return $isMatch;
                })->all();
                $data['loans'] = $this->paginate($loanArray, $offset, $pageIndex);
            } else {
                $data['loans'] = $this->paginate([], $offset, $pageIndex);
            }
        } else {
            $data['loans'] = $loan_list_query->paginate($offset);
        }
        if ($setting != null && isset($setting['is_json']) && $setting['is_json']) {
            $listData = array();
            if ($data['loans'] != null) {
                foreach ($data['loans'] as $loan) {
                    $listData[] = $this->toArrayOfLoan($loan);
                }
            }
            return $listData;
        }
        $data['offset'] = $offset;
        $data['pagi'] = $pagi;
        $data['assign_options'] = MasterLoan::$ASSIGN_OPTIONS;
        $data['assignee_roles'] = MasterLoan::getAssigneeRoles();

        $data['promise_pay_date_code'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_6;
        $data['stop_pay_reason_code'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_8;
        $data['request_restructure_code'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_7;

        $data['request_partial_payment'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_11;
        $data['request_waive_penalty'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_12;
        $data['request_partial_payment_waive_penalty'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_13;
        $data['request_change_unit'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_14;
        $data['request_suspend_payment'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_15;
        $data['customer_cancel'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_2;

        $data['the_door_locked'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_16;
        $data['not_meet_customer'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_17;

        $data['other_code'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_10;
        $data['call_action_code'] = ItemConstant::SUB_ITEM_REF_COL_ACTION_1;
        $data['walk_in_action_code'] = ItemConstant::SUB_ITEM_REF_COL_ACTION_10;
        $data['visit_action_code'] = ItemConstant::SUB_ITEM_REF_COL_ACTION_11;
        return $data;
    }

    private function toArrayOfLoan($loan) {
        $arr = array();
        if ($loan != null) {
            $arr = array(
                'loan_id' => $loan->loan_id,
                'contract_id' => $loan->contract_id,
                'main_project_name' => $loan->main_project_name ? $loan->main_project_name : '-',
                'variant_code' => $loan->variant_code ? $loan->variant_code : '-',
                'client_name' => $loan->client_name,
                'phone' => $loan->phone1,
                'disbursement_date' => $loan->disbursement_date ? date("d-M-Y", strtotime($loan->disbursement_date)) : '-',
                'loan_amount' => $loan->loan_amount ? '$'.number_format($loan->loan_amount,2,'.',',') : '$'.number_format($loan->loan_amount,2,'.',','),
                'outstanding_balance' => '$'.number_format($loan->outstanding_balance,2,'.',','),
                'drawdown_account' => $loan->drawdown_account ? $loan->drawdown_account : '-',
                'drawdown_balance' => '$'.number_format($loan->drawdown_balance,2,'.',','),
                'payment_no' => $loan->payment_no ? $loan->payment_no : '-',
                'schedule_date' => $loan->schedule_date ? date("d-M-Y", strtotime($loan->schedule_date)) : '-',
                'schedule_amount' => $loan->schedule_amount ? '$'.number_format($loan->schedule_amount,2,'.',',') : '',
                'arear_date' => $loan->getArearDate() ? date("d-M-Y", strtotime($loan->getArearDate())) : '',
                'overdue_day' => $loan->getOverdueDay() ? $loan->getOverdueDay() : '',
                'overdue_amount' => $loan->getOverdueAmount() ? '$'.number_format($loan->getOverdueAmount(),2,'.',',') : '',
                'penalty_amount' => $loan->getPenaltyAmount() ? '$'.number_format($loan->getPenaltyAmount(),2,'.',',') : '',
                'amount_to_collect' => '$'.number_format($loan->getAmountToCollect(),2,'.',','),
                'payment_status' => $loan->getPaymentStatus() ? $loan->getPaymentStatus()->description_en : '-',
                'last_action_date' => $loan->getLastAction() ? date("d-M-Y G:i A", strtotime($loan->getLastAction()->action_date)) : '',
                'last_call_date' => $loan->getLastAction() ? date("d-M-Y G:i A", strtotime($loan->getLastAction()->action_date)) : '',
                'negotiation_progress_status' => $loan->getNegotiationProgressStatus() ? $loan->getNegotiationProgressStatus()->description_en : '',
                'action_result' => $loan->getLastAction() ? ($loan->getLastAction()->action == ItemConstant::SUB_ITEM_REF_COL_ACTION_1 ? $loan->getLastAction()->customerResponseRef->description_en : $loan->getLastAction()->actionRef->description_en) : '',
                'customer_respond' => $loan->getLastAction() ?
                    ($loan->getLastAction()->stopPayReasonRef ? $loan->getLastAction()->stopPayReasonRef->description_en : $loan->getLastAction()->reason_note)
                    : '',
                'assignee' => $loan->getAssignee() ? $loan->getAssignee()->name : ''
            );
        }
        return $arr;
    }

    public function paginate($items, $perPage = 15, $page = null, $options = []) {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $lap = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        $lap->setPath('');
        return $lap;
    }

    public function freezeMasterLoan() {
        $is_can_assign = MasterLoan::isHasPermissionAssign();
        if ($is_can_assign) {
            MasterLoan::freezeMasterLoanData();
        }
        return redirect()->route('loan_recovery_action_plan');
    }
    public function actionAssignLoan() {
        $is_can_assign = MasterLoan::isHasPermissionAssign();
        if (Request::isMethod('post') && $is_can_assign) {
            $data = Request::all();
            $rule = [
                'assign_option' => 'required | numeric',
//                'assignee_role' => 'required',
            ];

            $v = Validator::make($data, $rule);
            if (!$v->fails()) {
                MasterLoan::assignFreezeMasterLoanDataByOption($data);
            }
        }
        return redirect()->route('loan_recovery_action_plan');
    }

    public function actionPlanForm($master_loan_id = 0) {

        $master_loan = MasterLoan::where('id', $master_loan_id)
            ->first();
        if (!$master_loan || !$master_loan->isHasPermissionDoActionLoan()) {
            return redirect()->route('loan_recovery_action_plan');
//            ->withErrors([
//                'You don\'t has permission access this loan!'
//            ]);
        }
        $loan_id = $master_loan->loan_id;
        $error_request = null;
        if (Request::isMethod('post')) {
//            $data = Request::except([
//                'action', 'phone_number', 'whom', 'action_date'
//            ]);
            $data = Request::all();
            $rule_main = [
                'action' => 'required',
            ];
            $v_m = Validator::make($data, $rule_main);
            if ($v_m->fails()) {
                $error_request = $v_m->errors();
            } else {
                $action_value = $data['action'];
                $rule = [
//                    'phone_number' => 'required | numeric',
                    'whom' => 'required',
                    'action_date' => 'required | date',
                ];
                // Todo: Validate by customer reason fields required
                if ($action_value == ItemConstant::SUB_ITEM_REF_COL_ACTION_1) { // Action Call
                    $rule['customer_response'] = 'required';
                    $v_call = Validator::make($data, $rule);
                    if ($v_call->fails()) {
                        $error_request = $v_call->errors();
                    } else {
                        $customer_response = $data['customer_response'];
                        if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_8) {
                            // Todo: - Stop to Pay => Show Stop Pay Reason, Solution, Reason note.
                            $rule['stop_pay_reason'] = 'required';
                            $rule['solution'] = 'required';
                        } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_6) {
                            // Todo: - Promise to pay => Promise date, Promise Amount, Solution, Reason note.
                            $rule['promise_pay_date'] = 'required';
                            $rule['promise_amount'] = 'required';
                            $rule['solution'] = 'required';
                        } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_7) {
                            // Todo: - Request Restructure​ => Request Type, # of Month Request, Amount Request, Reason note.
                            $rule['request_type'] = 'required';
                            $rule['month_request'] = 'required';
                            $rule['amount_request'] = 'required';
                        } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_10) {
                            // Todo: - Other => Comment, Capacity, Progress, Solution, Reason Note
                            $rule['comment'] = 'required';
                            $rule['capacity'] = 'required';
                            $rule['construction_progress_note'] = 'required';
                            $rule['solution'] = 'required';
                        } else {
                            // Todo: - Can't contact, No answer, customer cancel, No service, Number not in use, Wrong Number
                            //              =>  Solution, Reason note.
                            $rule['solution'] = 'required';
                        }
                        $rule['reason_note'] = 'required';
                        $v_call_reason = Validator::make($data, $rule);
                        if ($v_call_reason->fails()) {
                            $error_request = $v_call_reason->errors();
                        }
                    }
                } if ($action_value == ItemConstant::SUB_ITEM_REF_COL_ACTION_10) { // Action Walk-In
                    $rule['customer_response'] = 'required';
                    $v_call = Validator::make($data, $rule);
                    if ($v_call->fails()) {
                        $error_request = $v_call->errors();
                    } else {
                        $customer_response = $data['customer_response'];
                        if (
                            $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_11 ||
                            $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_12 ||
                            $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_13 ||
                            $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_14 ||
                            $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_7
                        ) {
                            // Todo: (Request Partial Payment, Request Waive Penalty, Request Partial Payment & Waive Penalty
                            //        Request Restructure, Request Change Unit): Show Promise Date, Promise Amount, Comment, Capacity to pay,
                            //        Construction Progress, Solution, Negotiation Progress, Reason note
                            $rule['promise_pay_date'] = 'required';
                            $rule['promise_amount'] = 'required';
                            $rule['comment'] = 'required';
                            $rule['capacity'] = 'required';
                            $rule['construction_progress_note'] = 'required';
                            $rule['solution'] = 'required';
                            $rule['negotiation'] = 'required';
                            $rule['reason_note'] = 'required';
                        } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_8) {
                            // Todo: Stop to Pay => Show Request Type, # of Month Request, Amount Request
                            $rule['request_type'] = 'required';
                            $rule['month_request'] = 'required';
                            $rule['amount_request'] = 'required';
                        } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_15) {
                            // Todo: Request Suspend Payment => Show Construction Progress, # of Month Request, Reason note.
                            $rule['construction_progress_note'] = 'required';
                            $rule['month_request'] = 'required';
                            $rule['reason_note'] = 'required';
                        } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_2 || $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_10) {
                            // Todo: Customer Cancel, Other => Show only Reason note
                            $rule['reason_note'] = 'required';
                        }
                    }
                } else if ($action_value == ItemConstant::SUB_ITEM_REF_COL_ACTION_11) { // Action Visit
                    $rule['customer_response'] = 'required';
                    $v_call = Validator::make($data, $rule);
                    if ($v_call->fails()) {
                        $error_request = $v_call->errors();
                    } else {
                        $customer_response = $data['customer_response'];
                        if (
                            $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_16 ||
                            $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_17
                        ) {
                            // Todo: The Door is Locked, Not meet customer: Show Solution, Reason note.
                            $rule['solution'] = 'required';
                            $rule['reason_note'] = 'required';
                        } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_6) {
                            // Todo: Promise to Pay: Promise Date, Promise Amount, Capacity to Pay, Reason note.
                            $rule['promise_pay_date'] = 'required';
                            $rule['promise_amount'] = 'required';
                            $rule['capacity'] = 'required';
                            $rule['reason_note'] = 'required';
                        } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_8) {
                            // Todo: Stop to Pay : Capacity to Pay, Comment, Solution, Reason note
                            $rule['capacity'] = 'required';
                            $rule['comment'] = 'required';
                            $rule['solution'] = 'required';
                            $rule['reason_note'] = 'required';
                        } else if ($customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_2 || $customer_response == ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_10) {
                            // Todo: Customer Cancel, Other : Solution, Reason note.
                            $rule['solution'] = 'required';
                            $rule['reason_note'] = 'required';
                        }
                    }
                } else { // Action SMS or Letter
                    // $rule default
                }

                // Todo: Save data to table
                // Todo: List data on popup

                $v = Validator::make($data, $rule);
                if ($error_request != null) {
                    // response error
                } else if ($v->fails()) {
                    $error_request = $v->errors();
                } else {
                    $data['master_loan_id'] = $master_loan_id;
                    $data['loan_id'] = $loan_id;
                    $action = CollectionAction::createModel($data);
                    $action->saveUpdate();
                    return redirect()->route('loan_recovery_action_plan');
                }
            }
        }

        $action_items = ItemRefSub::getAllByRefCode(ItemConstant::ITEM_REF_COL_ACTION);
        $data['action_items'] = $action_items;

        $action_whoms = ItemRefSub::getAllByRefCode(ItemConstant::ITEM_REF_COL_WHOM);
        $data['action_whoms'] = $action_whoms;

        $action_customer_responses = ItemRefSub::getAllByRefCode(ItemConstant::ITEM_REF_COL_CUS_RESP);
        $data['action_customer_responses'] = $action_customer_responses;

        $stop_pay_reasons = ItemRefSub::getAllByRefCode(ItemConstant::ITEM_REF_COL_CUS_RESP_3);
        $data['stop_pay_reasons'] = $stop_pay_reasons;

        $comment_items = ItemRefSub::getAllByRefCode(ItemConstant::ITEM_REF_COL_COMM);
        $data['comment_items'] = $comment_items;
        $capacity_items = ItemRefSub::getAllByRefCode(ItemConstant::ITEM_REF_COL_CAP);
        $data['capacity_items'] = $capacity_items;
        $solution_items = ItemRefSub::getAllByRefCode(ItemConstant::ITEM_REF_COL_SOLU);
        $data['solution_items'] = $solution_items;
        $negotiation_items = ItemRefSub::getAllByRefCode(ItemConstant::ITEM_REF_COL_NEGO_PROG);
        $data['negotiation_items'] = $negotiation_items;
        $request_type_items = ItemRefSub::getAllByRefCode(ItemConstant::ITEM_REF_COL_COL_REQ_TYPE);
        $data['request_type_items'] = $request_type_items;

        $data['promise_pay_date_code'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_6;
        $data['stop_pay_reason_code'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_8;
        $data['stop_pay_code'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_8;
        $data['promise_pay_code'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_6;
        $data['request_restructure_code'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_7;

        $data['request_partial_payment'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_11;
        $data['request_waive_penalty'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_12;
        $data['request_partial_payment_waive_penalty'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_13;
        $data['request_change_unit'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_14;
        $data['request_suspend_payment'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_15;
        $data['customer_cancel'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_2;

        $data['the_door_locked'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_16;
        $data['not_meet_customer'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_17;

        $data['other_code'] = ItemConstant::SUB_ITEM_REF_COL_CUS_RESP_10;
        $data['call_action_code'] = ItemConstant::SUB_ITEM_REF_COL_ACTION_1;
        $data['walk_in_action_code'] = ItemConstant::SUB_ITEM_REF_COL_ACTION_10;
        $data['visit_action_code'] = ItemConstant::SUB_ITEM_REF_COL_ACTION_11;
        $data['master_loan_id'] = $master_loan_id;
        $data['loan_id'] = $loan_id;

//        $loan_info = Loan::select([
////                'loans.contract_id',
////                'loans.start_date',
////                'loans.loan_type',
////                'loans.loan_amount',
////                'loans.original_amount',
////                'loans.interest_rate',
////                'loans.loan_account_id',
////                'loans.status',
////                'submitted_on',
////                'loans.disburse_date',
////                'loans.rejected_date',
////                'loans.client_id',
////                'loans.updated_at',
//
//                'loans.id',
//                'loans.contract_id',
//                'loans.loan_amount',
//                'loans.unit_sale_price',
//                'loans.price_after_discount',
//                'loans.down_payment',
//                'loans.loan_duration',
//                'loans.interest_rate',
//                DB::raw('(unit_sale_price - price_after_discount) AS discount_amount'),
//                'clients.client_name',
//                'clients.phone1',
//                'clients.phone2',
//                'clients.address',
//                'client_cbc_general.family_name_kh',
//                'client_cbc_general.first_name_kh',
//                'client_cbc_general.date_of_birth',
//                'client_cbc_identification.id_number',
//                'units.code AS unit_code',
//                'unit_types.name AS unit_type_name',
//                'projects.dealer_en AS project_name',
//
////                'clients.client_type',
////                'loans.settlement_date',
////                'rate_type'
//            ]
//        )->leftJoin('clients', 'clients.id', '=', 'loans.client_id')
//            ->leftJoin('client_cbc_general', 'client_cbc_general.client_id', '=', 'clients.id')
//            ->leftJoin('client_cbc_identification', 'client_cbc_identification.client_id', '=', 'clients.id')
//            ->leftJoin('units', 'loans.unit_id', '=', 'units.id')
//            ->leftJoin('unit_types', 'units.unit_type_id', '=', 'unit_types.id')
//            ->leftJoin('projects', 'unit_types.project_id', '=', 'projects.id')
//            ->where('loans.id', $loan_id)
//            ->first();

//        $data['loan_info'] = $loan_info;

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
        $schedule_id = RepaymentSchedule::where('loan_id', $loan_id)->first()->id;

        $data['loan'] = $loan;
        $data['audit'] = $audit;
        $data['schedule_id'] = $schedule_id;
        $data['product_type_arr'] = $product_type_arr;

        $view = $this->view('loan_recovery.action_plan_form', $data);
        if ($error_request) {
            $view->withErrors($error_request);
        }
        return $view;
    }

    public function jsonLoanActionByLoanId($loan_id = 0) {
        $resultList = CollectionAction::getListActionByLoanId($loan_id);
//        $result = [];
//        if ($resultList) {
//            foreach ($resultList as $index => $item) {
////                var_dump($item->action());
////                $result[$index] = $item->toArray();
//            }
//        }
        return response()->json($resultList);
    }
}
