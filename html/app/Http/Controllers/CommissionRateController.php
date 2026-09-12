<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CommissionRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommissionRateController extends Controller
{
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'rate' => 'required|numeric',
		],[
			'rate.required' => 'Rate is required',
			'rate.numeric' => 'Rate must be numeric',
		]);
		
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

		$commissionRate = CommissionRate::first();

		if(is_null($commissionRate)) {
			$commissionRate = CommissionRate::create([
				'rate' => $request->input('rate'),
			]);
		} else {
			$commissionRate->update([
				'rate' => $request->input('rate'),
			]);
		}

		return redirect()->back();
	}

	public function index()
	{
		$commissionRate = CommissionRate::first();

		return view('sale.commission_rate', [
			'commissionRate' => $commissionRate,
		]);
	}

}
