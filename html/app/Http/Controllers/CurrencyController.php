<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CurrencyController
 *
 * @author theary
 */
namespace App\Http\Controllers;
use App\Models\Account;
use App\Models\Client;
use App\Models\ClientLoanAccounts;
use App\Models\CoaCategory;
use App\Models\Teller;
use App\Models\Loan;
use App\Models\JournalDetail;
use App\Models\JournalRequiry;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Request;
//use App\Http\Requests\Request;
use Auth;
use Image;

class CurrencyController extends Controller{
    //put your code here
    public function  __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }
    public function getCurrency(){
        return $this->view('admin.currency');
    }
//    public function postAdd() {
//        $data = Request::except(['_token']);
//        $rules = [ 'selCate' => 'required',
//            'ipPrice' => 'required|numeric'
//        ];
//        $validator = Validator::make($data, $rules);
//        if ($validator->fails()) {
//            return redirect()->back()->withInput()->withErrors($validator);
//        } else {
//            $pro = new Product;
//            $pro->product_name = Request::input('ipName');
//            $pro->product_type = Request::input('ipType');
//            $pro->product_price = Request::input('ipPrice');
//            if ($pro->save()) {
//                $this->userActivity($pro->user_id, $pro->id, 7, 'Add product');
//                return redirect()->route('list_product');
//            }
//        }
//        return redirect()->back();
//    }
}
