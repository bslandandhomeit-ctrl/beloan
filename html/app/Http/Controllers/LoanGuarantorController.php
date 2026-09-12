<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Request;
use Illuminate\Support\Facades\Validator;
use App\Models\LoanGuarantor;
use App\Models\Client;
use App\Models\Loan;
use Auth;

class LoanGuarantorController extends Controller {
	public function __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
		if(!empty(is_numeric($id))){
			$loan = Loan::select('id','loan_account_id')->with(['client_loan_account' => function($q){
				$q->select('id','sub_client_id','guarantor');

			}])->where('id',$id)->first();
			$guarantor = $loan->client_loan_account->guarantor;
			$guarantor = json_decode($guarantor);
			$customer = Client::whereIn('id',$guarantor)->get();
			return view('loans.add_loan_guarantor',compact('customer','id'));
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
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
	            return redirect()->back()->with(['error' => 'Failed to Create Loan Guarantor']);
	        }else{
				$loan_guarantor = new LoanGuarantor;
				$loan_guarantor->loan_id = $id;
				$loan_guarantor->relationship = Request::input('relationship');
				$loan_guarantor->customer_id = Request::input('customer_id');
				$loan_guarantor->created_by = Auth::id();
				if($loan_guarantor->save()) {
					return redirect()->route('loan_detail', [$id])->withInput()->with('msg', 'Loan Guarantor Created success!');
	                // return redirect()->back()->withInput()->with('msg', 'Loan Guarantor Created success!');
	            }
	            return redirect()->back();
	        }
	    }
	}	

	public function get_search_client(){
        $retn = ['res' => false, 'permis' => false, 'data' => []];
		if (Request::ajax()) {
            if (Request::has('term')) {
                $name = Request::input('term');
                // $trans = Client::where('account_cbc_type','G')
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

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
