<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentOption;
use App\Models\UnitType;
use Request;
use Auth;


class PaymentOptionController extends Controller {
	public function __construct() {
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
		$offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
    	$payment_option = new PaymentOption();
        $name = null;
        if(Request::has('name')) {
            $name = Request::input('name');
            $payment_option = $payment_option->where(function($query) use($name){
                $query->where('name', 'like', '%' . $name . '%');
            });
        }
        $payment_option = $payment_option->paginate($offset);
        return $this->view('payment_option.list', ['lists' => $payment_option, 'name' => $name,'offset'=>$offset]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$unit_type = UnitType::get();
		return view('payment_option.create',['unit_type' => $unit_type]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Request::all();
        $rules = [
            'name' => 'required|string|max:255',
            'unit_type_id' => 'required',
            'initial_deposit_amount' => 'required|numeric',
            'first_payment_duration_month' => 'required|numeric',
            'first_payment_per' => 'required|numeric',
            'loan_duration_month' => 'required|numeric',
            'interest_per_year' => 'required|numeric',
            'special_discount' => 'required|numeric'
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return redirect()->back()->with(['error' => 'Failed to Create Payment Option']);
        }else {
        	$p = new PaymentOption;
        	$p->name = Request::input('name');
            $p->unit_type_id = Request::input('unit_type_id');
            $p->initial_deposit_amount = Request::input('initial_deposit_amount');
            $p->first_payment_plan = Request::input('first_payment_plan') ? true : false; 
            $p->first_payment_duration_month = Request::input('first_payment_duration_month');
            $p->first_payment_per = Request::input('first_payment_per');
            $p->loan_duration_month = Request::input('loan_duration_month');
            $p->interest_per_year = Request::input('interest_per_year');
            $p->special_discount = Request::input('special_discount');
            $p->user_id = Auth::user()->id;
            if($p->save()) {
                return redirect()->back()->with('msg','Payment Option Created success!');
            }
            return redirect()->back();
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
		if ($id > 0) {
            $data['payment_option'] = PaymentOption::find($id);
            if (!empty($data['payment_option'])) {
            	$data['unit_type'] = UnitType::get();
                return $this->view('payment_option.edit', $data);
            }
        }
        return redirect()->back();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if($id > 0){
			$data = Request::all();
	        $rules = [
	            'name' => 'required|string|max:255',
	            'unit_type_id' => 'required',
	            'initial_deposit_amount' => 'required|numeric',
	            'first_payment_duration_month' => 'required|numeric',
	            'first_payment_per' => 'required|numeric',
	            'loan_duration_month' => 'required|numeric',
	            'interest_per_year' => 'required|numeric',
	            'special_discount' => 'required|numeric'
	        ];
	        $validator = Validator::make($data, $rules);
	        if($validator->fails()) {
	            return redirect()->back()->with(['error' => 'Failed to Update Payment Option']);
	        }else {
	        	$p = PaymentOption::find($id);
	        	$p->name = Request::input('name');
	            $p->unit_type_id = Request::input('unit_type_id');
	            $p->initial_deposit_amount = Request::input('initial_deposit_amount');
	            $p->first_payment_plan = Request::input('first_payment_plan') ? true : false; 
	            $p->first_payment_duration_month = Request::input('first_payment_duration_month');
	            $p->first_payment_per = Request::input('first_payment_per');
	            $p->loan_duration_month = Request::input('loan_duration_month');
	            $p->interest_per_year = Request::input('interest_per_year');
	            $p->special_discount = Request::input('special_discount');
	            $p->user_id = Auth::user()->id;
	            if($p->save()) {
	                return redirect()->back()->with('msg','Payment Option Updated success!');
	            }
	            return redirect()->back();
	        }
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function disable($id)
	{
		if ($id > 0) {
            $payment_option = PaymentOption::find($id);
            if (!empty($payment_option)) {
                $payment_option->active = 0;
                $payment_option->save();
                $this->userActivity(Auth::user()->id, $payment_option->id, 0, 'Disable Payment Option', Request::fullUrl());
            }
        }
        return redirect()->back();
	}

	public function enable($id)
	{
		if ($id > 0) {
            $payment_option = PaymentOption::find($id);
            if (!empty($payment_option)) {
                $payment_option->active = 1;
                $payment_option->save();
                $this->userActivity(Auth::user()->id, $payment_option->id, 1, 'Enable Payment Option', Request::fullUrl());
            }
        }
        return redirect()->back();
	}

}
