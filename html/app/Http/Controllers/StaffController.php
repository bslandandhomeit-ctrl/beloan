<?php

/**
 * Created by Netbean.
 * User: Sotheary
 * Date: 3/10/2016
 * Time: 12:00 AM
 */

namespace App\Http\Controllers;

use App\Models\Accessible;
use App\Models\AdminPassword;
use App\Models\FailedLogin;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use App\Models\JobHistory;
use App\Models\UserPermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\CompanyBranch;
use Request;
//use App\Http\Requests\Request;
use Auth;
use Image;

class StaffController extends Controller {

    private $maxHit = 3;
    private $delay = 15;

    public function __construct() {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function addStaff() {
    	$query_arr = array('id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
    	$B1 = new CompanyBranch();
    	$B1 = $this->getBranchByUser($B1, 'id', $query_arr);
    	
        $data['branch'] = $B1->select('id', 'branch_name')->where('status', '=', 1)->get();
        $data['role'] = Role::select('id', 'role_name')->get();
        //$roles = Role::where('role','!=','super_admin')->get();
        return $this->view('staff.add_staff', $data);
    }

    public function postAdd() {
        $data = Request::all();
        $rules = ['name' => 'required|min:3',
            'phone' => 'required',
            'br' => 'required',
            'ro' => 'required',
            'salary' => 'required',
            'start_date' => 'required',
                //'email'=>'required|email|unique:users'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(['error' => 'Failed to register new staff']);
        } else {
            $s = new Staff;
            $s->name = Request::input('name');
            $s->kh_name = Request::input('kh_name');
            $s->id_card_num = Request::input('id_card');
            $s->gender = Request::input('gender');
            $s->nationality = Request::input('nationality');
            $s->date_of_birth = Request::input('birth_date');
            $s->branch_id = Request::input('br')?Request::input('br'):Auth::user()->branch_id;
            $s->role_id = Request::input('ro');
            $s->email = Request::input('email');
            $s->phone1 = Request::input('phone');
            $s->phone2 = Request::input('phone1');
            $s->address = Request::input('address');
            $s->salary = Request::input('salary');
            $s->inc_tax = Request::input('tax');
            $s->start_on = Request::input('start_date');
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
                    $image->save(public_path('data/staffs') . '/' . $photo_name);
                    $s->photo = $photo_name;
                }
            }
            $s->save();
            $this->userActivity(Auth::user()->id, $s->id, 7, 'Add Staff', Request::fullUrl());

            $h = new JobHistory;
            $h->role_id = Request::input('ro');
            $h->phone1 = Request::input('phone');
            $h->address = Request::input('address');
            $h->salary = Request::input('salary');
            $h->date = Request::input('start_date');
            $h->branch_id = Request::input('br');
            $h->staff_id = $s->id;
            if ($s->save() && $h->save()) {
                $this->userActivity(Auth::user()->id, $h->id, 7, 'Add JobHistory', Request::fullUrl());
                return redirect()->route('staff_summary');
            }
            return redirect()->back();
        }
    }

    public function summaryStaff() {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
    	$B0 = new Staff();
    	$staffs = $B0->paginate($offset);
        //dd($staffs);
        return $this->view('staff.staff_summary', ['staffs' => $staffs,'offset'=>$offset]);
    }

    //staff detail
    public function historyStaff($id = 0) {
        if ($id > 0) {
            $data['s'] = Staff::where('id', '=', $id)->with(['history', 'branch', 'role'])->first();
        }
        return $this->view('staff.staff_history', $data);
    }

    public function editStaff($id = 0) {
    	$query_arr = array('id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
    	$B1 = new CompanyBranch();
    	$B1 = $this->getBranchByUser($B1, 'id', $query_arr);
    	
        $data['branch'] = $B1->select('id', 'branch_name')->where('status', '=', 1)->get();
        $data['role'] = Role::select('id', 'role_name')->get();
        if ($id > 0) {
            $data['s'] = Staff::where('id', '=', $id)->first();
            if (!empty($data['s']) && count($data['s']) > 0) {
                return $this->view('staff.edit_staff', $data);
            }
        }
        return redirect()->back();
    }

    public function postEditStaff($id = 0) {
        if ($id > 0) {
            $data = Request::except(['_token']);
            $rules = ['name' => 'required|min:3',
                'phone' => 'required'
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $s = Staff::find($id);
                $s->name = Request::input('name');
                $s->kh_name = Request::input('kh_name');
                $s->gender = Request::input('gender');
                $s->nationality = Request::input('nationality');
                $s->date_of_birth = Request::input('birth_date');
                $s->branch_id = Request::input('br');
                $s->role_id = Request::input('ro');
                $s->email = Request::input('email');
                $s->phone1 = Request::input('phone');
                $s->phone2 = Request::input('phone1');
                $s->address = Request::input('address');
                $s->salary = Request::input('salary');
                $s->inc_tax = Request::input('tax');
                $s->start_on = Request::input('start_date');
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
                        $image->save(public_path('data/staffs') . '/' . $photo_name);
                        $s->photo = $photo_name;
                    }
                }
                if ($s->save()) {
                    $this->userActivity(Auth::user()->id, $s->id, 7, 'Update Staff', Request::fullUrl());
                    return redirect()->route('staff_summary');
                }
            }
        }
        return redirect()->back();
    }

    public function getUpdateStaff($id = 0) {
    	$query_arr = array('id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
    	$B1 = new CompanyBranch();
    	$B1 = $this->getBranchByUser($B1, 'id', $query_arr);
    	
        $data['branch'] = $B1->select('id', 'branch_name')->where('status', '=', 1)->get();
        $data['role'] = Role::select('id', 'role_name')->get();
        if ($id > 0) {
            $data['s'] = Staff::where('id', '=', $id)->first();
            if (!empty($data['s']) && count($data['s']) > 0) {
                return $this->view('staff.update_staff', $data);
            }
        }
        return redirect()->back();
    }

    public function postUpdateStaff($id = 0) {
        $data['branch'] = CompanyBranch::select('id', 'branch_name')->where('status', '=', 1)->get();
        $data['role'] = Role::select('id', 'role_name')->get();
        $data = Request::all();
        $rules = [
            'br' => 'required'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(['error' => 'Failed to register new staff']);
        } else {
            $s = new JobHistory;
            $s->staff_id = $id;
            $s->date = Request::input('update_date');
            $s->branch_id = Request::input('br');
            $s->role_id = Request::input('ro');
            $s->salary = Request::input('salary');
            $s->phone1 = Request::input('phone');
            $s->address = Request::input('address');
            $s->performance = Request::input('performance');
            $s->description = Request::input('description');
            if ($s->save()) {
                //$this->userActivity($s->id, 5, 'Create staff');
                return redirect()->route('staff_history', [$s->staff_id]);
            }
            return redirect()->back();
        }
    }

}
