<?php

namespace App\Http\Controllers;
;

use Illuminate\Support\Facades\Session;
use App\Models\Bank;
use App\Models\Loan;
use App\Models\Project;
use App\Models\DealerBanks;
use App\Models\LoanDealer;
use App\Models\User;
use App\Models\Product;
use App\Models\CompanyBranch;
use App\Models\Representative;
use Illuminate\Support\Facades\Validator;
use Request;
use Auth;
use Image;
use File;
use DB;
use App;

class RepresentativeController extends Controller {

    public function __construct() {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function create() {
        $company = CompanyBranch::where('status',1)->select('id','branch_name')->get();
        return $this->view('representative.create', ['banks' => Bank::where('active','=',1)->get(),'company'=>$company]);
    }

    public function post_create() {
        $data = Request::all();
        $rules = [
            'name' => 'required|max:255',
            'name_en' => 'required|max:255',
            'gender' => 'required',
            'dob' => 'required|date',
            'national_id' => 'required|max:50',
            'national_issued_date' => 'required',
            'phone' => 'required',
            'national_front' => 'image|mimes:jpeg,png,jpg,gif,svg|required|max:2048',
            'national_back' => 'image|mimes:jpeg,png,jpg,gif,svg|required|max:2048',
            'contract' => 'image|mimes:jpeg,png,jpg,gif,svg|required|max:2048',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(['error' => 'Failed to Create Representative']);
        }else{
            $representative = new Representative;
            $representative->name = Request::input('name');
            $representative->name_en = Request::input('name_en');
            $representative->gender = Request::input('gender');
            $representative->dob = Request::input('dob');
            $representative->national_id = Request::input('national_id');
            $representative->national_issued_date = Request::input('national_issued_date');
            $representative->phone = Request::input('phone');
            $representative->active = 1;
            $representative->user_id = Auth::user()->id;
            if(Request::hasFile('national_front')) {
                if (Request::file('national_front')->isValid()) {
                    if ((Request::file('national_front')->getSize() / 1024 / 1024) > 1) {
                        return redirect()->back()->with('error', 'File size is too large!');
                    }
                    $file = Request::file('national_front');
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
                    $national_front = uniqid(date('dmY')) . '.jpg';
                    $image->save(public_path('data/representative/national_front') . '/' . $national_front);
                    $representative->national_front = $national_front;
                }
            }

            if(Request::hasFile('national_back')) {
                if (Request::file('national_back')->isValid()) {
                    if ((Request::file('national_back')->getSize() / 1024 / 1024) > 1) {
                        return redirect()->back()->with('error', 'File size is too large!');
                    }
                    $file = Request::file('national_back');
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
                    $national_back = uniqid(date('dmY')) . '.jpg';
                    $image->save(public_path('data/representative/national_back') . '/' . $national_back);
                    $representative->national_back = $national_back;
                }
            }

            if(Request::hasFile('contract')) {
                if (Request::file('contract')->isValid()) {
                    if ((Request::file('contract')->getSize() / 1024 / 1024) > 1) {
                        return redirect()->back()->with('error', 'File size is too large!');
                    }
                    $file = Request::file('contract');
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
                    $contract = uniqid(date('dmY')) . '.jpg';
                    $image->save(public_path('data/representative/contract') . '/' . $contract);
                    $representative->contract = $contract;
                }
            }

            if($representative->save()) {
                return redirect()->back()->with('msg', 'Representative Created success!');
            }
            return redirect()->back();
        }
    }

    public function getEdit($id = 0) {
        if ($id > 0) {
            $data['representative'] = Representative::find($id);
            if (!empty($data['representative'])) {
                return $this->view('representative.edit', $data);
            }
        }
        return redirect()->back();
    }

    public function postEdit($id = 0) {
        if ($id > 0) {
            $data = Request::all();
            $rules = [
                'name' => 'required|max:255',
                'name_en' => 'required|max:255',
                'gender' => 'required',
                'dob' => 'required|date',
                'national_id' => 'required|max:50',
                'national_issued_date' => 'required',
                'phone' => 'required',
                // 'national_front' => 'image|mimes:jpeg,png,jpg,gif,svg|required|max:2048',
                // 'national_back' => 'image|mimes:jpeg,png,jpg,gif,svg|required|max:2048',
                // 'contract' => 'image|mimes:jpeg,png,jpg,gif,svg|required|max:2048',
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return redirect()->back()->with(['error' => 'Failed to Update Representative']);
            }else{
                $representative = Representative::find($id);
                $representative->name = Request::input('name');
                $representative->name_en = Request::input('name_en');
                $representative->gender = Request::input('gender');
                $representative->dob = Request::input('dob');
                $representative->national_id = Request::input('national_id');
                $representative->national_issued_date = Request::input('national_issued_date');
                $representative->phone = Request::input('phone');
                $representative->active = 1;
                $representative->user_id = Auth::user()->id;
                if(Request::hasFile('national_front')) {
                    if (Request::file('national_front')->isValid()) {
                        if ((Request::file('national_front')->getSize() / 1024 / 1024) > 1) {
                            return redirect()->back()->with('error', 'File size is too large!');
                        }
                        $file = Request::file('national_front');
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
                        $national_front = uniqid(date('dmY')) . '.jpg';
                        $image->save(public_path('data/representative/national_front') . '/' . $national_front);
                        $representative->national_front = $national_front;
                    }
                }

                if(Request::hasFile('national_back')) {
                    if (Request::file('national_back')->isValid()) {
                        if ((Request::file('national_back')->getSize() / 1024 / 1024) > 1) {
                            return redirect()->back()->with('error', 'File size is too large!');
                        }
                        $file = Request::file('national_back');
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
                        $national_back = uniqid(date('dmY')) . '.jpg';
                        $image->save(public_path('data/representative/national_back') . '/' . $national_back);
                        $representative->national_back = $national_back;
                    }
                }

                if(Request::hasFile('contract')) {
                    if (Request::file('contract')->isValid()) {
                        if ((Request::file('contract')->getSize() / 1024 / 1024) > 1) {
                            return redirect()->back()->with('error', 'File size is too large!');
                        }
                        $file = Request::file('contract');
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
                        $contract = uniqid(date('dmY')) . '.jpg';
                        $image->save(public_path('data/representative/contract') . '/' . $contract);
                        $representative->contract = $contract;
                    }
                }

                if($representative->save()) {
                    return redirect()->back()->with('msg', 'Representative Updated success!');
                }
                return redirect()->back();
            }
        }
        return redirect()->back();
    }

    public function getDetail($id = 0) {
        if ($id > 0) {
            $data['project'] = Project::find($id);
            if (!empty($data['project'])) {
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

                return $this->view('representative.detail', $data);
            }
        }
        return redirect()->back();
    }

    public function listRepresentative() {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
    	$representative = new Representative();
        $name = null;
        if (Request::has('name')) {
            $name = Request::input('name');
            $representative = $representative->where(function($query) use($name){
                $query->where('name', 'like', '%' . $name . '%')
                ->orWhere('name_en', 'like', '%' . $name . '%');
            });
        }
        $phone = null;
        if (Request::has('phone')) {
            $phone = Request::input('phone');
            $representative = $representative->where('phone', str_replace('-', '', $phone));
        }
        $representative = $representative->paginate($offset);
        return $this->view('representative.list', ['lists' => $representative, 'name' => $name, 'phone' => $phone,'offset'=>$offset]);
    }

    public function representativeDisable($id = 0) {
        if ($id > 0) {
            $representative = Representative::find($id);
            if (!empty($representative)) {
                $representative->active = 0;
                $representative->save();
                $this->userActivity(Auth::user()->id, $representative->id, 0, 'Disable Representative', Request::fullUrl());
            }
        }
        return redirect()->back();
    }

    public function representativeEnable($id = 0) {
        if ($id > 0) {
            $representative = Representative::find($id);
            if (!empty($representative)) {
                $representative->active = 1;
                $representative->save();
                $this->userActivity(Auth::user()->id, $representative->id, 0, 'Enable representative', Request::fullUrl());
            }
        }
        return redirect()->back();
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


}
