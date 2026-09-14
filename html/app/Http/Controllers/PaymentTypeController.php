<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentType;
use Request;
use Auth;

class PaymentTypeController extends Controller {

    public function __construct() {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function create() {
        return $this->view('payment_type.create');
    }

    public function post_create() {
        $data = Request::except(['_token']);
        $rules = [
            'name' => 'required|min:1',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $p = new PaymentType;
        $p->name = Request::input('name');
        $p->is_active = 1;
        if ($p->save()) {
            $this->userActivity(Auth::user()->id, $p->id, 1, 'Add payment type', Request::fullUrl());
            return redirect()->route('list_payment_type')->with('msg', 'Payment Type Created success!');
        }
        return redirect()->back();
    }

    public function getEdit($id = 0) {
        if ($id > 0) {
            $payment_type = PaymentType::find($id);
            if (!empty($payment_type)) {
                return $this->view('payment_type.edit', ['payment_type' => $payment_type]);
            }
        }
        return redirect()->back();
    }

    public function postEdit($id = 0) {
        if ($id > 0) {
            $payment_type = PaymentType::find($id);
            if (!empty($payment_type)) {
                $data = Request::except(['_token']);
                $rules = [
                    'name' => 'required|min:1',
                ];
                $validator = Validator::make($data, $rules);
                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator);
                }

                $payment_type->name = Request::input('name');
                if ($payment_type->save()) {
                    $this->userActivity(Auth::user()->id, $payment_type->id, 1, 'Update payment type', Request::fullUrl());
                    return redirect()->route('list_payment_type')->with('msg', 'Payment Type Updated success!');
                }
            }
        }
        return redirect()->back();
    }

    public function listPaymentType() {
        $lists = PaymentType::orderBy('id')->get();
        return $this->view('payment_type.list', ['lists' => $lists]);
    }

    public function Disable($id = 0) {
        if ($id > 0) {
            $payment_type = PaymentType::find($id);
            if (!empty($payment_type)) {
                $payment_type->is_active = 0;
                $payment_type->save();
                $this->userActivity(Auth::user()->id, $payment_type->id, 0, 'Disable payment type', Request::fullUrl());
            }
        }
        return redirect()->back();
    }

    public function Enable($id = 0) {
        if ($id > 0) {
            $payment_type = PaymentType::find($id);
            if (!empty($payment_type)) {
                $payment_type->is_active = 1;
                $payment_type->save();
                $this->userActivity(Auth::user()->id, $payment_type->id, 0, 'Enable payment type', Request::fullUrl());
            }
        }
        return redirect()->back();
    }
}
