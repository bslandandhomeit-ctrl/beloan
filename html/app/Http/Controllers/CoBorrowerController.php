<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CoBorrower;
use App\Models\Client;
use App\Models\Loan;
use Auth;

class CoBorrowerController extends Controller {

	public function __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }

	public function index()
	{
		//
	} 
	public function create($id)
	{
		if(!empty(is_numeric($id))){
			$co_borrower = CoBorrower::where('loan_id',$id)->first();
			$loan = Loan::select('id','loan_account_id')->with(['client_loan_account' => function($q){
				$q->select('id','sub_client_id','guarantor');

			}])->where('id',$id)->first();
			$coborrower = $loan->client_loan_account->sub_client_id;
			$coborrower = json_decode($coborrower);
			$customer = Client::whereIn('id',$coborrower)->get();
			if($co_borrower->loan_id == $id){
				return view('loans.co_borrower.add_co_borrower',compact('customer','id','co_borrower'));
			}else{
				return view('loans.co_borrower.add_co_borrower',compact('customer','id','co_borrower'));
			}
		}
	}

	public function store($id)
	{
		if(!empty(is_numeric($id))){
			$data = Request::all();
	        $rules = [
	            'relationship' => 'required',
	            'customer_id' => 'required',
	        ];
	        $validator = Validator::make($data, $rules);
	        if ($validator->fails()) {
	            return redirect()->back()->with(['error' => 'Failed to Create Co-Borrower']);
	        }else{
	        	$msg = '';
	        	$co_borrower = CoBorrower::where('loan_id',$id)->first();
	        	if($co_borrower->loan_id == $id){
	        		$co_borrower->loan_id = $id;
					$co_borrower->relationship = Request::input('relationship');
					$co_borrower->customer_id = Request::input('customer_id');
					$co_borrower->updated_by = Auth::id();
					$msg = 'Updated';
	        	}else{
					$co_borrower = new CoBorrower;
					$co_borrower->loan_id = $id;
					$co_borrower->relationship = Request::input('relationship');
					$co_borrower->customer_id = Request::input('customer_id');
					$co_borrower->created_by = Auth::id();
					$msg = 'Created';
	        	}
				if($co_borrower->save()) {
					return redirect()->route('loan_detail', [$id])->with('msg', 'Co-Borrower '.$msg.' success!');
	                // return redirect()->back()->with('msg', 'Co-Borrower '.$msg.' success!');
	            }
	            return redirect()->back()->withInput();
	        }
	    }
	}
	public function delete($loan_id='', $borrower_id = '')
	{
		$co_borrower = CoBorrower::where('loan_id',$loan_id)->where('id',$borrower_id)->first();
		if($co_borrower){
			$co_borrower->delete();
			return redirect()->route('loan_detail',$loan_id)->with('msg', 'Co-Borrower delete success!');
		}else{
			return redirect()->route('loan_detail',$loan_id)->with('msg', 'Co-Borrower delete failed!');
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
