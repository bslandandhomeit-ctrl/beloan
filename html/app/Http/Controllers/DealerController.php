<?php

namespace App\Http\Controllers;
;

use Illuminate\Support\Facades\Session;
use App\Models\Bank;
use App\Models\Loan;
use App\Models\Dealer;
use App\Models\DealerBanks;
use App\Models\LoanDealer;
use App\Models\User;
use App\Models\Product;
use App\Models\CompanyBranch;
use Illuminate\Support\Facades\Validator;
use Request;
//use App\Http\Requests\Request;
use Auth;
use Image;
use File;
use DB;
use App;

class DealerController extends Controller {

    public function __construct() {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function create() {
        $company = CompanyBranch::where('status',1)->select('id','branch_name')->get();
        return $this->view('dealers.create', ['banks' => Bank::where('active','=',1)->get(),'company'=>$company]);
    }

    public function post_create() {

        $data = Request::all();
        $rules = ['dealer' => 'required|min:3',
            'phone' => 'required'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(['error' => 'Failed to register new dealer']);
        } else {
            $d = new Dealer;
            $d->dealer = Request::input('dealer');
            $d->representative = Request::input('representative');
            $d->location = Request::input('location');
            $d->phone = Request::input('phone');
            $d->phone2 = Request::input('phone1');
            $d->email = Request::input('email');
            $d->description = Request::input('description');
            $d->user_id = Auth::user()->id;
            if (Request::hasFile('photo')) {
                if (Request::file('photo')->isValid()) {
                    if ((Request::file('photo')->getSize() / 1024 / 1024) > 1) {
                        return redirect()->back()->with('error', 'File size is too large!');
                    }
                    $file = Request::file('photo');
                    list($w, $h) = getimagesize($file);
                    if ($w >= 200) {
                        $h = ($h * 200) / $w;
                        $w = 200;
                        if ($h > $w) {
                            $w = ($w * 200) / $h;
                            $h = 200;
                        }
                    } elseif ($h >= 200) {
                        $w = ($w * 200) / $h;
                        $h = 200;
                        if ($w > $h) {
                            $h = ($h * 200) / $w;
                            $w = 200;
                        }
                    }
                    $image = Image::make($file)->resize($w, $h);
                    $photo_name = uniqid(date('dmY')) . '.jpg';
                    $image->save(public_path('data/dealers') . '/' . $photo_name);
                    $d->photo = $photo_name;
                }
            }
            if ($d->save()) {
                $banks = Request::input('e9');
                foreach ($banks as $bank) {
                    $d_bank = new DealerBanks;
                    $d_bank->dealer_id = $d->id;
                    $d_bank->bank_id = $bank;
                    $d_bank->save();
                    
                    Bank::where('id', $bank)
                    ->update(['active' => 0]);
                }
                $dealer_id = Dealer::select('id')->orderBy('id', 'DESC')->first();
                $this->userActivity($d->user_id, $d->id, 5, 'Create dealer');
                return redirect()->route('dealer_detail', [$dealer_id]);
            }
            return redirect()->back();
        }
    }

    public function getEdit($id = 0) {
        if ($id > 0) {
            $data['dealer'] = Dealer::find($id);
            if (!empty($data['dealer'])) {
                $data['dealer_bank'] = DealerBanks::where('dealer_id', '=', $id)->with(['bank'])->get();
                $data['banks'] = Bank::where('active', 1)->get();

                $db_id = [];
                $b_id = [];
                $obj_bank = [];
                foreach ($data['dealer_bank'] as $db) {
                    $db_id[] = $db->bank_id;
                }
                foreach ($data['banks'] as $b) {
                    $b_id[] = $b->id;
                }
                $result = array_diff($b_id, $db_id);
                $flag = FALSE;
                if (count($b_id) == count($db_id)) {
                    $flag = TRUE;
                }

                foreach ($result as $id) {
                    $obj_bank[] = Bank::where('id', '=', $id)->first();
                }
                $data['remain_b'] = $obj_bank;
                $data['flag'] = $flag;
                return $this->view('dealers.edit', $data);
            }
        }
        return redirect()->back();
    }

    public function postEdit($id = 0) {
        if ($id > 0) {
            $data = Request::all();
            $rules = ['dealer' => 'required|min:3',
                'phone' => 'required'
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return redirect()->back()->with(['error' => 'Failed to update dealer']);
            } else {
                $d = Dealer::find($id);
                if (!empty($d)) {

                    $d->dealer = Request::input('dealer');
                    $d->representative = Request::input('representative');
                    $d->location = Request::input('location');
                    $d->phone = Request::input('phone');
                    $d->phone2 = Request::input('phone1');
                    $d->email = Request::input('email');
                    $d->description = Request::input('description');
                    $d->user_id = Auth::user()->id;
                    if (Request::hasFile('photo')) {
                        if (Request::file('photo')->isValid()) {
                            if ((Request::file('photo')->getSize() / 1024 / 1024) > 1) {
                                return redirect()->back()->with('error', 'File size is too large!');
                            }
                            $file = Request::file('photo');
                            list($w, $h) = getimagesize($file);
                            if ($w >= 200) {
                                $h = ($h * 200) / $w;
                                $w = 200;
                                if ($h > $w) {
                                    $w = ($w * 200) / $h;
                                    $h = 200;
                                }
                            } elseif ($h >= 200) {
                                $w = ($w * 200) / $h;
                                $h = 200;
                                if ($w > $h) {
                                    $h = ($h * 200) / $w;
                                    $w = 200;
                                }
                            }
                            $old_file = public_path('data/dealers') . '/' . $d->photo;
                            if (File::exists($old_file)) {
                                File::delete($old_file);
                            }
                            $image = Image::make($file)->resize($w, $h);
                            $photo_name = uniqid(date('dmY')) . '.jpg';
                            $image->save(public_path('data/dealers') . '/' . $photo_name);
                            $d->photo = $photo_name;
                        }
                    }
                    $d->save();
                    $banks = Request::input('e9');
                    if ($banks != null) {
                        foreach ($banks as $bank) {
                            $d_bank = new DealerBanks;
                            $d_bank->dealer_id = $id;
                            $d_bank->bank_id = $bank;
                            $d_bank->save();

                            Bank::where('id', $bank)->update(['active' => 0]);
                        }
                    }
                    $dealer_bank = DealerBanks::where('dealer_id', '=', $id)->with(['bank'])->get();
                    $banks = Bank::all();
                    $db_id = [];
                    $b_id = [];
                    $obj_bank = [];
                    foreach ($dealer_bank as $db) {
                        $db_id[] = $db->bank_id;
                    }
                    foreach ($banks as $b) {
                        $b_id[] = $b->id;
                    }
                    $result = array_diff($b_id, $db_id);
                    $flag = FALSE;
                    if (count($b_id) == count($db_id)) {
                        $flag = TRUE;
                    }
                    foreach ($result as $id) {
                        $obj_bank[] = Bank::where('id', '=', $id)->first();
                    }
                    $this->userActivity($d->user_id, $d->id, 5, 'Update dealer');
                    return redirect()->back()->with(['msg' => 'Dealer has been updated successfully.', 'remain_b' => $obj_bank, 'flag' => $flag]);
                }
            }
        }
        return redirect()->back();
    }

    public function postAddBank() {
        $d_id = Request::input('d_id');
        $old_data = DealerBanks::where('dealer_id', '=', $d_id);
        $old_data->delete();
        $banks = Request::input('banks');
        foreach ($banks as $bank) {
            $d_bank = new DealerBanks;
            $d_bank->dealer_id = $d_id;
            $d_bank->bank_id = $bank;
            $d_bank->save();
            $this->userActivity(Auth::user()->id, $d_bank->id, 0, 'Add DealerBanks', Request::fullUrl());
        }
        return ['status' => true];
    }

    public function postDelBank() {
        if (Request::ajax()) {
            $id = Request::input('dealer_id');
            $bank_id = Request::input('bank_id');
            $db = DealerBanks::where('dealer_id', '=', $id)->where('bank_id', '=', $bank_id)->first();
            $this->userActivity(Auth::user()->id, $db->id, 0, 'Delete DealerBanks', Request::fullUrl());
            $db->delete();

            $dealer_bank = DealerBanks::where('dealer_id', '=', $id)->with(['bank'])->get();
            $banks = Bank::all();
            $db_id = [];
            $b_id = [];
            $obj_bank = [];
            foreach ($dealer_bank as $db) {
                $db_id[] = $db->bank_id;
            }
            foreach ($banks as $b) {
                $b_id[] = $b->id;
            }
            $result = array_diff($b_id, $db_id);
            $flag = FALSE;
            if (count($b_id) == count($db_id)) {
                $flag = TRUE;
            }
            foreach ($result as $id) {
                $obj_bank[] = Bank::where('id', '=', $id)->first();
            }

            return ['status' => true, 'remain_b' => $obj_bank, 'flag' => $flag, 'banks' => Bank::all()];
        }
        return ['status' => false];
    }

    public function getDetail($id = 0) {
        if ($id > 0) {
            $data['dealer'] = Dealer::find($id);
            if (!empty($data['dealer'])) {
                $data['banks'] = DealerBanks::where('dealer_id', '=', $id)->with(['bank'])->get();
                $data['loans'] = Loan::select(['loans.id', 'loans.product_id', 'loans.client_id', 'loans.contract_id'])
                                ->join('products', 'products.id', '=', 'loans.product_id')
                                ->with(['client' => function($query) {
                                        $query->select('id', 'client_name');
                                    }, 'loanDealer' => function($query) {
                                        $query->with('bank');
                                    }])->where('loans.dealer_id', '=', $id)
                                ->where('products.is_loan', 1)->paginate(1000000);

                $data['products'] = Product::with(['brand', 'category'])->where('dealer_id', '=', $id)->where('is_loan', '=', 0)->get();

                return $this->view('dealers.detail', $data);
            }
        }
        return redirect()->back();
    }

    public function bank_account() {
        return $this->view('dealers.bank_account');
    }

    public function listDealer() {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
    	$query_arr = array('user_id'=>Auth::user()->id, 'branch_id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
    	$B0 = new Dealer();
    	$B0 = $this->getUserByBranch($B0, 'user_id', $query_arr);
    	
        $listDealer = $B0->with(['bank']);
        $name = null;
        if (Request::has('name')) {
            $name = Request::input('name');
            $listDealer = $listDealer->where('dealer', 'like', '%' . $name . '%');
        }
        $phone = null;
        if (Request::has('phone')) {
            $phone = Request::input('phone');
            $listDealer = $listDealer->where(function($query) use($phone) {
                $query->where('phone', '=', $phone)->orWhere('phone2', '=', $phone);
            });
        }
        $listDealer = $listDealer->paginate($offset);
        return $this->view('dealers.list_dealer', ['lists' => $listDealer, 'name' => $name, 'phone' => $phone,'offset'=>$offset]);
    }

    public function dealerDisable($id = 0) {
        if ($id > 0) {
            $dealer = Dealer::find($id);
            if (!empty($dealer)) {
                $dealer->active = 0;
                $dealer->save();
                $this->userActivity(Auth::user()->id, $dealer->id, 0, 'Disable Dealer', Request::fullUrl());
            }
        }
        return redirect()->back();
    }

    public function dealerEnable($id = 0) {
        if ($id > 0) {
            $dealer = Dealer::find($id);
            if (!empty($dealer)) {
                $dealer->active = 1;
                $dealer->save();
                $this->userActivity(Auth::user()->id, $dealer->id, 0, 'Enable dealer', Request::fullUrl());
            }
        }
        return redirect()->back();
    }

    public function post_bank_account() {
        $input = Request::except(['_token']);
        $id = Auth::user()->id;
        if (!empty($input) && is_array($input)) {
            $addNew = new Bank();
            foreach ($input as $key => $value) {
                $addNew->$key = $value;
                $addNew->user_id = $id;
            }
            if ($addNew->save()) {
                $this->userActivity(Auth::user()->id, $addNew->id, 0, 'Add bank', Request::fullUrl());
            }
        }
        Session::flash('message', 'Add new bank account successfully');
        return redirect('bank/add');
    }

    public function postAddBankAccount() {
        if (Request::ajax()) {
            $data = Request::except(['_token']);
            $bank = new Bank;
            foreach ($data as $key => $value) {
                $bank->$key = $value;
            }
            if (Auth::check()) {
                $bank->user_id = Auth::user()->id;
                if ($bank->save()) {
                    $this->userActivity(Auth::user()->id, $bank->id, 0, 'Add bank', Request::fullUrl());
                    return ['status' => true, 'bank' => $data, 'id' => $bank->id];
                }
            }
        }
        return ['status' => false];
    }

    public function edit_bank_account($id = 0) {
        if (is_numeric($id) && $id > 0) {
            $editBank = Bank::find($id);
            if (!empty($editBank)) {
                return $this->view('dealers.edit_bank_account', ['bank' => $editBank]);
            }
        }
        return redirect()->back();
    }

    public function post_edit_bank_account($id = 0) {
        $input = Request::except(['_token']);
        if (!empty($input) && is_array($input)) {
            $updateBank = Bank::find($id);
            foreach ($input as $key => $value) {
                $updateBank->$key = $value;
            }
            if ($updateBank->save()) {
                $this->userActivity(Auth::user()->id, $updateBank->id, 0, 'Edit bank', Request::fullUrl());
                return redirect()->route('list_bank');
            }
        }
    }

    public function list_bank_account() {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
        $listBank = Bank::OrderBy('id', 'ASC');
        $number = null;
        $name = null;
        if (Request::has('number')) {
            $number = Request::input('number');
            $name = Request::input('name');
            $listBank = $listBank->where('account_number', '=', $number);
        } elseif (Request::has('name')) {
            $number = Request::input('number');
            $name = Request::input('name');
            $listBank = $listBank->where('account_name', 'like', '%' . $name . '%');
        }
        $listBank = $listBank->paginate($offset);
        return $this->view('dealers.list_bank_account', ['lists' => $listBank, 'name' => $name, 'number' => $number, 'offset'=>$offset]);
    }

    public function postDisable($id) {
        if ($id > 0) {
            $bank = Bank::find($id);
            if (!empty($bank)) {
                $bank->active = 0;
                $bank->save();
                $this->userActivity(Auth::user()->id, $bank->id, 0, 'Disable bank', Request::fullUrl());
            }
        }
        return redirect()->back();
    }

    public function postEnable($id) {
        if ($id > 0) {
            $bank = Bank::find($id);
            if (!empty($bank)) {
                $bank->active = 1;
                $bank->save();
                $this->userActivity(Auth::user()->id, $bank->id, 0, 'Enable bank', Request::fullUrl());
            }
        }
        return redirect()->back();
    }

    public function getProductLoan($id = 0) {
        if ($id > 0) {
            $loan_dealer = LoanDealer::where('loan_id', '=', $id)->first();
            if (!empty($loan_dealer)) {
                $data['loan_dealer'] = $loan_dealer;
            }
            $loan = Loan::select('id', 'loan_amount', 'dealer_id')->where('id', '=', $id)->first();
            if (!empty($loan)) {
                $dealer = Dealer::select('id', 'dealer')->where('id', '=', $loan->dealer_id)->first();
                if (!empty($dealer)) {
                    $data['loan'] = $loan;
                    $data['dealer'] = $dealer;
                    $data['dealer_bank'] = DealerBanks::where('dealer_id', '=', $loan->dealer_id)->with('bank')->get();
                    $data['senders'] = User::select('id', 'name')->whereIn('role_id', [5, 7, 8, 9, 10])->get();
                    return $this->view('dealers.product_info', $data);
                } else {
                    Session::flash('msg', 'Update is unavailable! This loan product isn\'t belong to any dealer.');
                }
            }
        }
        return redirect()->back();
    }

    public function postProductLoan($id = 0) {
        if ($id > 0) {
            $data = Request::except(['_token']);
            $rules = [
                'selName' => 'required',
                'selBank' => 'required',
                'selSender' => 'required',
                'dpDisbursement' => 'required|date',
                'dpTransfer' => 'required|date',
                'ipDisburseAmount' => 'required|numeric',
                'ipTransferAmount' => 'required|numeric'
            ];
            $attr = ['selName' => 'Dealer Name',
                'selBank' => 'Bank Account',
                'selSender' => 'Sender',
                'dpDisburse' => 'Disbursement Date',
                'dpTransfer' => 'Transfer Date',
                'ipDisburseAmount' => 'Disbursement Amount',
                'ipTransferAmount' => 'Transfer Amount'
            ];
            $validator = Validator::make($data, $rules);
            $validator->setAttributeNames($attr);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            } else {
                $loan_dealer = LoanDealer::where('loan_id', '=', $id)->first();
                if (empty($loan_dealer)) {
                    $loan_dealer = new LoanDealer;
                    $loan_dealer->loan_id = $id;
                    $loan_dealer->dealer_id = Request::input('selName');
                } else {
                    if (Request::hasFile('files')) {
                        $receipts = explode("|", $loan_dealer->dealer_receipt);
                        for ($i = 0; $i < count($receipts) - 1; $i++) {
                            $path = public_path('data/receipts/' . $receipts[$i]);
                            if (File::exists($path)) {
                                File::delete($path);
                            }
                        }
                    }
                }

                $loan_dealer->bank_id = Request::input('selBank');
                $loan_dealer->sender = Request::input('selSender');
                $loan_dealer->transfer_amount = Request::input('ipTransferAmount');
                $loan_dealer->transfer_date = Request::input('dpTransfer');
                $loan_dealer->disbursement_amount = Request::input('ipDisburseAmount');
                $loan_dealer->disbursement_date = Request::input('dpDisbursement');

                if (Request::hasFile('files')) {
                    $receipts = "";
                    foreach (Request::file('files') as $file) {
                        if ($file->isValid()) {
                            if (($file->getSize() / 1024 / 1024) > 1) {
                                return redirect()->back()->with('error', 'File size is too large!');
                            }
                            list($w, $h) = getimagesize($file);
                            if ($w >= 800) {
                                $h = ($h * 800) / $w;
                                $w = 800;
                                if ($h > $w) {
                                    $w = ($w * 800) / $h;
                                    $h = 800;
                                }
                            } elseif ($h >= 800) {
                                $w = ($w * 800) / $h;
                                $h = 800;
                                if ($w > $h) {
                                    $h = ($h * 800) / $w;
                                    $w = 800;
                                }
                            }
                            $image = Image::make($file)->resize($w, $h);
                            $photo_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . str_random(10) . '.jpg';
                            $image->save(public_path('data/receipts') . '/' . $photo_name);
                            $receipts .= $photo_name . '|';
                        }
                    }
                    $loan_dealer->dealer_receipt = $receipts;
                }
                $loan_dealer->save();
                $this->userActivity(Auth::user()->id, $loan_dealer->id, 0, 'Add or Update LoanDealer', Request::fullUrl());
                return redirect()->route('loan_detail', [$id]);
            }
        }
        return redirect()->back();
    }

}
