<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CoBorrower;
use App\Models\Client;
use App\Models\ClientLoanAccounts;
use App\Models\DrawdownAccounts;
use App\Models\CoaCategory;
use App\Models\TransferClient;
use App\Models\Loan;
use Auth;
use DB;

class TransferClientController extends Controller {
	public function __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }
	public function index()
	{
		//
	} 
	public function transfer_client($id)
	{
		if(!empty(is_numeric($id))){
			$co_borrower = CoBorrower::where('loan_id',$id)->first();
			$loan = Loan::select('id','loan_account_id')
				->with(['client_loan_account' => function($q){
				$q->select('id','sub_client_id','guarantor','client_id');

			}])->where('id',$id)->first();
			$client_name = isset($loan->client_loan_account->client->client_name)?$loan->client_loan_account->client->client_name.' | '.$loan->client_loan_account->client->phone1:'N/A';
			if($co_borrower->loan_id == $id){
				return view('loans.transfer_client.transfer',compact('id','loan','client_name'));
			}else{
				return view('loans.transfer_client.transfer',compact('id','loan','client_name'));
			}
		}
	}

	public function store($id)
	{
		if(!empty(is_numeric($id))){
			$data = Request::all();
	        $rules = [
	            'transfer_client_type' => 'required',
	            'customer_id' => 'required',
	        ];
	        $validator = Validator::make($data, $rules);
	        if ($validator->fails()) {
	            return redirect()->back()->with(['error' => 'Failed to Transfer Client']);
	        }else{
	        	$msg = '';
	        	$loan = Loan::select('id','loan_account_id')
					->with(['client_loan_account' => function($q){
					$q->select('id','sub_client_id','guarantor','client_id');

				}])->where('id',$id)->first();
				$loan_account_id = $loan->loan_account_id;
				$loan_accounts = ClientLoanAccounts::select('client_loan_accounts.*', 'company_branch.*', 'client_loan_accounts.status', 'client_loan_accounts.id')
			            ->join('company_branch', 'client_loan_accounts.branch', '=', 'company_branch.branch_code')
			            ->with(['journal_detail_coa' => function ($q) {
			                $q->select('coa_id', DB::raw('sum(debit) AS sum_debit, sum(credit) AS sum_credit'));
			            }])
			            ->with(['journal_detail_air' => function ($q) {
			                $q->select('coa_id', DB::raw('sum(debit) AS sum_debit, sum(credit) AS sum_credit'));
			            }])
			            ->with(['journal_detail_int_inc' => function ($q) {
			                $q->select('coa_id', DB::raw('sum(debit) AS sum_debit, sum(credit) AS sum_credit'));
			            }])
			            ->with(['journal_detail_sus' => function ($q) {
			                $q->select('coa_id', DB::raw('sum(debit) AS sum_debit, sum(credit) AS sum_credit'));
			            }])
	            ->find($loan_account_id);
	            if($loan_accounts){
	            	$drawdown_acc = DrawdownAccounts::where('client_loan_id',$loan_accounts->id)->first();
	            	if($drawdown_acc){
	            		$cate = CoaCategory::whereIn('id', [$loan_accounts->coa_id, $loan_accounts->air_id, $loan_accounts->int_inc_id, $loan_accounts->sus_id, $loan_accounts->ap_id,$drawdown_acc->coa_id])->get();
	            		$client_id = Request::input('customer_id');
	            		$client_old_name = isset($loan->client_loan_account->client->client_name)?$loan->client_loan_account->client->client_name:'';
	            		$client_old_id = isset($loan->client_loan_account->client_id)?$loan->client_loan_account->client_id:'';
	            		$client = Client::find($client_id);
	            		$client_name = $client->client_name;
	            		if($client_old_name && $client_name){
	            			foreach ($cate as $coa_row) {
	            				$resStr = str_replace($client_old_name, $client_name, $coa_row->name);
	            				$resStrdesc = str_replace($client_old_name, $client_name, $coa_row->description);
	            				$coa_row->name = $resStr;
	            				$coa_row->description = $resStrdesc;
	            				$coa_row->save();
	            			}

	            			$drawdown_acc->account_name = $client_name;
	            			$drawdown_acc->client_id = $client_id;
	            			$drawdown_acc->save();

	            			$loan_accounts->client_id = $client_id;
	            			$loan_accounts->account_name = $client_name;
	            			$loan_accounts->save();
	            		}


	            		$transfer_client = new TransferClient;
	            		$transfer_client->client_id	= $client_old_id;
	            		$transfer_client->new_client_id = $client_id;
						$transfer_client->loan_id = $loan->id;
						$transfer_client->transfer_type = Request::input('transfer_client_type');
						$transfer_client->coa_categories = json_encode($cate);
						$transfer_client->drawdown_account = json_encode($drawdown_acc);
						$transfer_client->client_loan_accounts = json_encode($loan_accounts);
						$transfer_client->remark = Request::input('remark');
						$transfer_client->created_by = Auth::id();
						$transfer_client->save();

						$loan->client_id = $client_id;
						$loan->save();
	            		// var_dump($client_old_id);
	            		// var_dump($client_old_name);
	            		// var_dump($client);
	            		// var_dump($customer_id);
	            		return redirect()->route('loan_detail', [$id])->with(['msg' => 'Transfer Client successfully.']);
	            		// return redirect()->back()->with('msg', 'Co-Borrower '.$msg.' success!');

	            	}
	            }
	            
	            return redirect()->back()->with(['error' => 'Failed to Transfer Client']);
	        }
	    }
	}
	public function get_search_borrower(){
        $retn = ['res' => false, 'permis' => false, 'data' => []];
		if (Request::ajax()) {
            if (Request::has('term')) {
                $name = Request::input('term');
                // $trans = Client::where('account_cbc_type','J')
                $trans = Client::where(function ($q) use ($name) {
                            $q->where('client_name', 'like', '%'.$name.'%')
                        		->orWhere('cus_acc', 'like', '%'.$name.'%')
                                ->orWhere('phone1', 'like', '%'.$name.'%');
                        })->get();
                    $retn = ['res' => true, 'permis' => true, 'data' => $trans];
            }
            return $retn;
        } else {
            return false;
        }
	}
}
