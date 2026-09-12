<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Loan;
use App\Models\Products\Product_type;
use App\Models\Audit;
use App\Models\RepaymentSchedule;
use App\Models\LoanStatus;
use App\Models\LoanApproval;
use Auth;
use Request;
use DB;
use DateTime;
use Session;

class LoanVerifyAndApproveController extends Controller {
	public function getLoanVerify()
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
        ])->with(['approval' => function ($query) {
            $query->select('id', 'loan_id', 'approval_date');
        }, 'client_loan_account', 'payoff'])->leftJoin('clients', 'clients.id', '=', 'loans.client_id')->where('workflow_status','create');//->orWhere('workflow_status','reject');
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
        return $this->view('loans.loan_to_verify.loan_to_verify', ['loans' => $loan,'contract_id' => $contract_id, 'offset' => $offset, 'product_type' => $product_type]);
    }

    public function loan_verify_detail($id = 0)
    {
        if (is_numeric($id) && $id > 0) {
            $loan = Loan::select(['loans.*', 'loan_approval.id as approval_id', 'loan_approval.approval_date'])
                ->leftJoin('loan_approval', 'loans.id', '=', 'loan_approval.loan_id')
                ->with(['co_user' => function ($query) {
                    $query->select('id', 'phone', 'name');
                }, 'client', 'branch', 'client_loan_account' => function ($q) {
                    $q->select('id', 'account_no', 'balance', 'currency');
                },'unittypes'])
                ->where('loans.id', '=', $id)->first();
            $product_type = Product_type::select('id', 'code', 'products_type_name')->get();
            $product_type_arr = [];
            foreach ($product_type as $pt) {
                $product_type_arr[$pt->id] = $pt->products_type_name;
            }
            $audit = Audit::where('tbl', 'loans')->where('tbl_id', $id)->orderBy('id', 'asc')->with(['audit1', 'audit2'])->get();
            $schedule_id = RepaymentSchedule::where('loan_id', $id)->first()->id;

            return $this->view('loans.loan_to_verify.detail', ['loan' => $loan, 'audit' => $audit, 'schedule_id' => $schedule_id, 'product_type_arr' => $product_type_arr]);
        }
        return $this->view('loans.loan_to_verify.detail', ['loan' => null]);
    }

    public function verify(){
    	$id = Request::input('id');
    	$loan = Loan::find($id);
    	if(!$loan){
    		return redirect()->back();
    	}
    	$loan->workflow_status = 'verify';
    	if($loan->save()){
    		$this->userActivity(Auth::id(), $loan->id, 6, 'Verify loan');
    	}

    	return response()->json(['status' => 1,Session::flash('msg', 'Verify success.')]);
    }

    public function reject(){
    	$data = Request::all();
        $rules = [
            'description' => 'required|string|max:255',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response()->json(['status' => 0,'msg' => 'The Description field is required.']);
        }else{
	    	$id = Request::input('id');
	    	$loan = Loan::find($id);
	    	if(!$loan){
	    		return redirect()->back();
	    	}
	    	$loan_status = new LoanStatus;
	    	$loan_status->loan_id = $loan->id;
	    	$loan_status->description = Request::input('description');
	    	$loan_status->created_by = Auth::id();
	    	$loan_status->save();
	    	$loan->workflow_status = 'send_back';
	    	if($loan->save()){
	    		$this->userActivity(Auth::id(), $loan->id, 6, 'Send Back Loan');
	    	}
	    	return response()->json(['status' => 1, Session::flash('msg', 'Send Back success.')]);
	    }
    }

   	public function approve(){
    	$id = Request::input('id');
    	$loan = Loan::find($id);
    	if(!$loan){
    		return redirect()->back();
    	}
        $loan_id = $id;
        if ($loan_id > 0) {
            $loan = Loan::select('id', 'status')->where('id', '=', $loan_id)->first();
            if (!empty($loan) && $loan->status == 1) { /* Unauthorized */
                $loan->status = 2;
                $loan->workflow_status = 'approve';
                $approval = LoanApproval::where('loan_id', '=', $loan_id)->first();
                if (!empty($approval)) {
                    $approval->loan_id = $loan_id;
                    $approval->user_id = Auth::user()->id;
                    $date = date('Y-m-d');
                    $approval->approval_date = $date;
                    if (Request::has('note')) {
                        $approval->note = Request::input('note');
                    }
                    $approval->updated_at = date('Y-m-d H:i:s');
                    if ($approval->save()) {
                        if ($loan->save()) {
                            $this->do_audit($loan_id, '', Auth::user()->id, 'loans', 1, 'approve loan');

                            $this->userActivity($approval->user_id, $loan_id, 6, 'Approval loan');
                            return redirect()->route('loan_detail', [$loan_id]);
                        }
                    }
                } else {
                    $napproval = new LoanApproval();
                    $napproval->loan_id = $loan_id;
                    $napproval->user_id = Auth::user()->id;
                    $date = date('Y-m-d');
                    $napproval->approval_date = $date;
                    if (Request::has('note')) {
                        $napproval->note = Request::input('note');
                    }
                    $napproval->created_at = date('Y-m-d H:i:s');
                    if ($napproval->save()) {
                        if ($loan->save()) {
                            $this->do_audit($loan_id, '', Auth::user()->id, 'loans', 1, 'approve loan');
                            $this->userActivity($napproval->user_id, $loan_id, 6, 'Approval loan');
                        }
                    }
                }
            }
        }
    	if($loan->save()){
    		$this->userActivity(Auth::id(), $loan->id, 6, 'Approval loan');
    	}
    	return response()->json(['status' => 1]);
    }

    public function getLoanApprove()
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
        ])->with(['approval' => function ($query) {
            $query->select('id', 'loan_id', 'approval_date');
        }, 'client_loan_account', 'payoff'])->leftJoin('clients', 'clients.id', '=', 'loans.client_id')->where('workflow_status','verify');
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
        return $this->view('loans.waiting_to_approve.waiting_to_approve', ['loans' => $loan,'contract_id' => $contract_id, 'offset' => $offset, 'product_type' => $product_type]);
    }

    public function loan_approve_detail($id = 0)
    {
        if (is_numeric($id) && $id > 0) {
            $loan = Loan::select(['loans.*', 'loan_approval.id as approval_id', 'loan_approval.approval_date'])
                ->leftJoin('loan_approval', 'loans.id', '=', 'loan_approval.loan_id')
                ->with(['co_user' => function ($query) {
                    $query->select('id', 'phone', 'name');
                }, 'client', 'branch', 'client_loan_account' => function ($q) {
                    $q->select('id', 'account_no', 'balance', 'currency');
                },'unittypes'])
                ->where('loans.id', '=', $id)->first();
            $product_type = Product_type::select('id', 'code', 'products_type_name')->get();
            $product_type_arr = [];
            foreach ($product_type as $pt) {
                $product_type_arr[$pt->id] = $pt->products_type_name;
            }
            $audit = Audit::where('tbl', 'loans')->where('tbl_id', $id)->orderBy('id', 'asc')->with(['audit1', 'audit2'])->get();
            $schedule_id = RepaymentSchedule::where('loan_id', $id)->first()->id;

            return $this->view('loans.waiting_to_approve.detail', ['loan' => $loan, 'audit' => $audit, 'schedule_id' => $schedule_id, 'product_type_arr' => $product_type_arr]);
        }
        return $this->view('loans.waiting_to_approve.detail', ['loan' => null]);
    }

    public function getLoanReject()
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
        ])->with(['approval' => function ($query) {
            $query->select('id', 'loan_id', 'approval_date');
        }, 'client_loan_account', 'payoff'])->leftJoin('clients', 'clients.id', '=', 'loans.client_id')->where('workflow_status','send_back');
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
        return $this->view('loans.loan_to_reject.loan_to_reject', ['loans' => $loan,'contract_id' => $contract_id, 'offset' => $offset, 'product_type' => $product_type]);
    }

    public function loan_reject_detail($id = 0)
    {
        if (is_numeric($id) && $id > 0) {
            $loan = Loan::select(['loans.*', 'loan_approval.id as approval_id', 'loan_approval.approval_date'])
                ->leftJoin('loan_approval', 'loans.id', '=', 'loan_approval.loan_id')
                ->with(['co_user' => function ($query) {
                    $query->select('id', 'phone', 'name');
                }, 'client', 'branch', 'client_loan_account' => function ($q) {
                    $q->select('id', 'account_no', 'balance', 'currency');
                },'unittypes'])
                ->where('loans.id', '=', $id)->first();
            $product_type = Product_type::select('id', 'code', 'products_type_name')->get();
            $product_type_arr = [];
            foreach ($product_type as $pt) {
                $product_type_arr[$pt->id] = $pt->products_type_name;
            }
            // $audit = Audit::where('tbl', 'loans')->where('tbl_id', $id)->orderBy('id', 'asc')->with(['audit1', 'audit2'])->get();
            $loan_status = LoanStatus::where('loan_id',$id)->orderBy('id','DESC')->get();
            $schedule_id = RepaymentSchedule::where('loan_id', $id)->first()->id;

            return $this->view('loans.loan_to_reject.detail', ['loan' => $loan, 'audit' => $loan_status, 'schedule_id' => $schedule_id, 'product_type_arr' => $product_type_arr]);
        }
        return $this->view('loans.loan_to_reject.detail', ['loan' => null]);
    }

}
