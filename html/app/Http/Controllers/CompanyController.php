<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Request;
use Config;
// use App\Http\Requests\Request;
use App\Models\CompanyBranch;
use Image;
use File;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function add_branch()
    {
        // var_dump( Session::activity());
        // var_dump((Config::get('session')));
        return $this->view('company.add_branch');
    }

    public function post_add_branch()
    {
        $data = Request::all();
        $rules = [
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|required|max:2048',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(['error' => 'Company Branch Save fails!']);
        }else{
            $inputs = Request::except(['_token']);
            if (!empty($inputs) && is_array($inputs)) {
                $branch_name = Request::Input('branch_name');
                $register_branch = CompanyBranch::select('branch_name')->get();
                foreach ($register_branch as $b) {
                    if ($branch_name == $b->branch_name) {
                        Session::flash('message', 'Branch already registered');
                        return redirect()->back()->with('error', true);
                    }
                }
                $branch = new CompanyBranch();
                $user_id = Auth::user()->id;
                foreach ($inputs as $key => $value) {
                    if ($key == 'tax_issued_date') {
                        $time = strtotime($value);
                        $date = date('Y-m-d', $time);
                        $value = $date;
                    }
                    if ($key == 'commercial_issued_date') {
                        $time = strtotime($value);
                        $date = date('Y-m-d', $time);
                        $value = $date;
                    }

                    if ($key == 'open_on') {
                        $time = strtotime($value);
                        $date = date('Y-m-d', $time);
                        $branch->$key = $date;
                        $branch->user_id = $user_id;
                        $branch->status = 1;
                    } else {
                        $branch->$key = $value;
                        if($key == 'logo'){
                            if(Request::hasFile('logo')) {
                                if (Request::file('logo')->isValid()) {
                                    if ((Request::file('logo')->getSize() / 2024 / 2024) > 1) {
                                        return redirect()->back()->with('error','File size is too large!');
                                    }
                                    $file = Request::file('logo');
                                    $logo = uniqid(date('dmY')) . '.jpg';
                                    $destinationPath = public_path('/data/company_logo');
                                    $file->move($destinationPath,$logo);
                                    $branch->logo = $logo;
                                }
                            }
                        }
                    }
                }
                if ($branch->save()) {
                    $this->userActivity($user_id, $branch->id, 1, 'Create company');
                    Session::flash('message', 'Save a company Success');
                    return redirect()->route('company_branch');
                }
            }
        }
        Session::flash('message', 'Save a company failed');
        return redirect()->back()->with('error', true);
    }

    public function edit_branch($id = 0)
    {
        if (is_numeric($id) && $id > 0) {
            $branch = CompanyBranch::find($id);
            if (!empty($branch))
                return $this->view('company.edit_branch', ['branch' => $branch]);
        }
        return redirect()->back();
    }

    public function post_edit_branch($id = 0)
    {
        $data = Request::all();
        $rules = [
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(['error' => 'Company Branch Save fails!']);
        }else{
            if (is_numeric($id) && $id > 0) {
                $branch = CompanyBranch::find($id);
                $image_url = $branch->logo;
                if (!empty($branch)) {
                    $inputs = Request::except(['_token']);
                    if (!empty($inputs) && is_array($inputs)) {
                        foreach ($inputs as $key => $value) {
                            if ($key == 'tax_issued_date') {
                                $time = strtotime($value);
                                $date = date('Y-m-d', $time);
                                $value = $date;
                            }
                            if ($key == 'commercial_issued_date') {
                                $time = strtotime($value);
                                $date = date('Y-m-d', $time);
                                $value = $date;
                            }
                            if ($key == 'open_on') {
                                $time = strtotime($value);
                                $date = date('Y-m-d', $time);
                                $branch->$key = $date;
                            } else {
                                $branch->$key = $value;
                                if($key == 'logo'){
                                    if(Request::hasFile('logo')) {
                                        if (Request::file('logo')->isValid()) {
                                            if ((Request::file('logo')->getSize() / 2024 / 2024) > 1) {
                                                return redirect()->back()->with('error','File size is too large!');
                                            }
                                            if($image_url){
                                                if(file_exists('data/company_logo/'.$image_url)){   
                                                    unlink('data/company_logo/'.$image_url);           
                                                } 
                                            }   
                                            $file = Request::file('logo');
                                            $logo = uniqid(date('dmY')) . '.jpg';
                                            $destinationPath = public_path('/data/company_logo');
                                            $file->move($destinationPath,$logo);
                                            $branch->logo = $logo;
                                        }
                                    }
                                }
                            }
                        }
                        if($branch->save()) {
                            $this->userActivity(Auth::user()->id, $id, 1, 'Update company branch');
                            return redirect()->route('company_branch');
                        } else {
                            Session::flash('message', 'Save a company branch failed');
                            return redirect()->back()->with('error', true);
                        }
                    }
                }
            }
        }
        return redirect()->back();
    }

    public function all_branch()
    {
        //$branches = CompanyBranch::where('status','=',1)->get();
        $branches = CompanyBranch::all();
        return $this->view('company.all', ['branches' => $branches]);
    }

    public function disableBranch($id)
    {
        $branch = CompanyBranch::find($id);
        $branch->status = 0;
        $branch->save();
        $this->userActivity(Auth::user()->id, $id, 1, 'Disable company branch');
        return redirect()->back();
    }

    public function enableBranch($id)
    {
        $branch = CompanyBranch::find($id);
        $branch->status = 1;
        $branch->save();
        $this->userActivity(Auth::user()->id, $id, 1, 'Enable company branch');
        return redirect()->back();
    }
}
