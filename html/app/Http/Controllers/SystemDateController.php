<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Http\Controllers;

/**
 * Description of SettingController
 *
 * @author chuch
 */
use Request;
use DB; 
use Illuminate\Support\Facades\Session;
use Artisan;
use Response;
use App\Models\SystemDate;

class SystemDateController extends Controller{
    //put your code here
    public function __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function index()
    {
    	$offset = isset($_GET['offset']) ? $_GET['offset'] : 50;
		if(!$offset){
			$offset = 50;
		}
		$from_date = Request::input('from_date');
		$to_date = Request::input('to_date');
		$rows = new SystemDate;
        if($from_date && $to_date){
			$rows = $rows->where('corrent_date','>=',$from_date)->where('corrent_date','<=',$to_date);
		}

        $rows = $rows->orderby('corrent_date','DESC')->paginate($offset)->setPath('?from_date='.$from_date.'&to_date='.$to_date.'&offset='.$offset);
		return view('system_date.index',['rows' => $rows, 'unit_status_color' => $unit_status_color, 'search' => $search,'offset'=>$offset],compact('from_date','to_date'));
    }
	public function accruedInterest($id = 0) 
	{
		$data = SystemDate::find($id);
		if($data->is_accrued_interest == 0){
			$data->is_accrued_interest = 1;
			$data->save();
			return redirect()->back()->with('msg','Accrued Interest success!');
		}else{
			return redirect()->back()->with('msg','Accrued Interest failde!');
		}
	}

	public function AutoPayment($id = 0) 
	{
		$data = SystemDate::find($id);
		if($data->is_auto_payment == 0){
			$data->is_auto_payment = 1;
			$data->save();
			return redirect()->back()->with('msg','Auto Payment success!');
		}else{
			return redirect()->back()->with('msg','Auto Payment  failde!');
		}
	}
    
}
