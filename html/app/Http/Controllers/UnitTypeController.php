<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use App\Models\UnitType;
use App\Models\ImageType;
use Request;
use Auth;
use Image;
use File;
use DB;
use App;

class UnitTypeController extends Controller {

    public function __construct() {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function create() {
        $project = Project::where('active',1)->get();

        $contract_template = config('static_data.contract_template');

        return $this->view('unit_type.create', [
            'project' => $project,
            'contract_template' => $contract_template,
        ]);
    }

    public function post_create(Request $request) {
        $data = Request::all();
        $rules = [
            'project_id' => 'required',
            'name' => 'required|min:1',
            'short_code' => 'required|unique:unit_types,short_code',
            'contract_template_id' => 'required',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(['error' => 'Failed to Create Uint Type']);
        } else {
            $u = new UnitType;
            $u->project_id = Request::input('project_id');
            $u->name = Request::input('name');
            $u->name_en = Request::input('name_en');
            $u->name_cn = Request::input('name_cn');
            $u->short_code = Request::input('short_code');
            $u->is_contractable = Request::input('contractable')  ? true : false;
            $u->contract_template_id = Request::input('contract_template_id');
            $u->annual_management_fee = Request::input('annual_management_fee');
            $u->contract_transfer_fee = Request::input('contract_transfer_fee');
            $u->management_fee_per_square = Request::input('management_fee_per_square');
            $u->deadline = Request::input('deadline');
            $u->extended_deadline = Request::input('extended_deadline');
            $u->title_clause_kh = Request::input('title_clause_kh');
            $u->title_clause_en = Request::input('title_clause_en');
            $u->title_clause_cn = Request::input('title_clause_cn');
            $u->management_service_kh = Request::input('management_service_kh');
            $u->management_service_en = Request::input('management_service_en');
            $u->management_service_cn = Request::input('management_service_cn');
            $u->equipment_text = $_POST['equipment_text'];//Request::input('equipment_text');
            $u->equipment_text_en = $_POST['equipment_text_en'];//Request::input('equipment_text_en');
            $u->equipment_text_cn = $_POST['equipment_text_cn'];//Request::input('equipment_text_cn');
            $u->user_id = Auth::user()->id;
            $u->active = 1;
            if(Request::hasFile('payment_option_image')) {
                if (Request::file('payment_option_image')->isValid()) {
                    if ((Request::file('payment_option_image')->getSize() / 1024 / 1024) > 1) {
                        return redirect()->back()->with('error', 'File size is too large!');
                    }
                    $file = Request::file('payment_option_image');
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
                    $payment_option_image = uniqid(date('dmY')) . '.jpg';
                    $image->save(public_path('data/unit_type/payment_option_image') . '/' . $payment_option_image);
                    $u->payment_option_image_url = $payment_option_image;
                }
            }

            if(Request::hasFile('feature_image')) {
                if (Request::file('feature_image')->isValid()) {
                    if ((Request::file('feature_image')->getSize() / 1024 / 1024) > 1) {
                        return redirect()->back()->with('error', 'File size is too large!');
                    }
                    $file = Request::file('feature_image');
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
                    $feature_image = uniqid(date('dmY')) . '.jpg';
                    $image->save(public_path('data/unit_type/feature_image') . '/' . $feature_image);
                    $u->feature_image_url = $feature_image;
                }
            }

            if($u->save()) {
                if(Request::hasFile('img_floor_plan')) {
                    $file = Request::file('img_floor_plan');
                    foreach ($file as $key => $files) {
                        $thisFile = Request::file('img_floor_plan')[$key];
                        if ($thisFile->isValid()) {
                            $checkSize = Request::file('img_floor_plan')[$key];
                            if (($checkSize->getSize() / 1024 / 1024) > 1) {
                                return redirect()->back()->with('error', 'File size is too large!');
                            }
                            $file_s = Request::file('img_floor_plan')[$key];
                            $path = public_path('data/image_type/floor_plan');
                            $file_name = $this->resizeImage($file_s,$path,$key);
                            if($file_name){	
                                $image_type = new ImageType;
                                $image_type->unit_type_id = $u->id;
                                $image_type->type = 'floor_plan';
                                $image_type->url = $file_name;
                                $image_type->save();
                            }
                        }
                    }
                }

                if(Request::hasFile('img_interior')) {
                    $file = Request::file('img_interior');
                    foreach ($file as $key => $files) {
                        $thisFile = Request::file('img_interior')[$key];
                        if ($thisFile->isValid()) {
                            $checkSize = Request::file('img_interior')[$key];
                            if (($checkSize->getSize() / 1024 / 1024) > 1) {
                                return redirect()->back()->with('error', 'File size is too large!');
                            }
                            $file_s = Request::file('img_interior')[$key];
                            $path = public_path('data/image_type/interior');
                            $file_name = $this->resizeImage($file_s,$path,$key);
                            if($file_name){	
                                $image_type = new ImageType;
                                $image_type->unit_type_id = $u->id;
                                $image_type->type = 'interior';
                                $image_type->url = $file_name;
                                $image_type->save();
                            }
                        }
                    }
                }

                if(Request::hasFile('img_exterior')) {
                    $file = Request::file('img_exterior');
                    foreach ($file as $key => $files) {
                        $thisFile = Request::file('img_exterior')[$key];
                        if ($thisFile->isValid()) {
                            $checkSize = Request::file('img_exterior')[$key];
                            if (($checkSize->getSize() / 1024 / 1024) > 1) {
                                return redirect()->back()->with('error', 'File size is too large!');
                            }
                            $file_s = Request::file('img_exterior')[$key];
                            $path = public_path('data/image_type/exterior');
                            $file_name = $this->resizeImage($file_s,$path,$key);
                            if($file_name){	
                                $image_type = new ImageType;
                                $image_type->unit_type_id = $u->id;
                                $image_type->type = 'exterior';
                                $image_type->url = $file_name;
                                $image_type->save();
                            }
                        }
                    }
                }

                // save commission setting
                $commissionType = Request::input('commission_type');
                $commissionValue = Request::input('commission_value');

                if($commissionType && $commissionValue) {
                    $u->saveCommission($commissionValue, $commissionType);
                }
                // End saving commission setting
                
                return redirect()->back()->with('msg','UnitType Created success!');
            }
            return redirect()->back();
        }
    }

    public function getEdit($id = 0) {
        if ($id > 0) {
            $unit_type = UnitType::with('activeSaleCommissionSetting')->find($id);
            $data['img_floor_plan'] = ImageType::where('type','floor_plan')->where('unit_type_id','=',$unit_type->id)->get();
            $data['img_interior'] = ImageType::where('type','interior')->where('unit_type_id','=',$unit_type->id)->get();
            $data['img_exterior'] = ImageType::where('type','exterior')->where('unit_type_id','=',$unit_type->id)->get();
            $data['unit_type'] = $unit_type;
            $data['project'] = Project::where('active',1)->get();
            $data['contract_template'] = config('static_data.contract_template');
            if (!empty($data['unit_type'])) {
                return $this->view('unit_type.edit', $data);
            }
        }
        return redirect()->back();
    }

    public function postEdit($id = 0) {
        if ($id > 0) {
            $data = Request::all();
            $rules = [
                'project_id' => 'required',
                'name' => 'required|min:1',
                'short_code' => 'required|unique:unit_types,short_code,'.$id,
                'contract_template_id' => 'required',
                ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return redirect()->back()->with(['error' => 'Failed to Update Uint Type']);
                } else {
                    $u = UnitType::find($id);
                    $u->project_id = Request::input('project_id');
                    $u->name = Request::input('name');
                    $u->name_en = Request::input('name_en');
                    $u->name_cn = Request::input('name_cn');
                    $u->short_code = Request::input('short_code');
                    $u->is_contractable = Request::input('contractable')  ? true : false;
                    $u->contract_template_id = Request::input('contract_template_id');
                    $u->annual_management_fee = Request::input('annual_management_fee');
                    $u->contract_transfer_fee = Request::input('contract_transfer_fee');
                    $u->management_fee_per_square = Request::input('management_fee_per_square');
                    $u->deadline = Request::input('deadline');
                    $u->extended_deadline = Request::input('extended_deadline');
                    $u->title_clause_kh = Request::input('title_clause_kh');
                    $u->title_clause_en = Request::input('title_clause_en');
                    $u->title_clause_cn = Request::input('title_clause_cn');
                    $u->management_service_kh = Request::input('management_service_kh');
                    $u->management_service_en = Request::input('management_service_en');
                    $u->management_service_cn = Request::input('management_service_cn');
                    $u->equipment_text = $_POST['equipment_text'];//Request::input('equipment_text');
                    $u->equipment_text_en = $_POST['equipment_text_en'];//Request::input('equipment_text_en');
                    $u->equipment_text_cn = $_POST['equipment_text_cn'];//Request::input('equipment_text_cn');
                    $u->user_id = Auth::user()->id;
                    if(Request::hasFile('payment_option_image')) {
                        if (Request::file('payment_option_image')->isValid()) {
                            if ((Request::file('payment_option_image')->getSize() / 1024 / 1024) > 1) {
                                return redirect()->back()->with('error', 'File size is too large!');
                            }
                            $file = Request::file('payment_option_image');
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
                            $payment_option_image = uniqid(date('dmY')) . $file->getClientOriginalName();
                            $image->save(public_path('data/unit_type/payment_option_image') . '/' . $payment_option_image);
                            $u->payment_option_image_url = $payment_option_image;
                        }
                    }

                    if(Request::hasFile('feature_image')) {
                        if (Request::file('feature_image')->isValid()) {
                            if ((Request::file('feature_image')->getSize() / 1024 / 1024) > 1) {
                                return redirect()->back()->with('error', 'File size is too large!');
                            }
                            $file = Request::file('feature_image');
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
                            $feature_image = uniqid(date('dmY')) . $file->getClientOriginalName();
                            $image->save(public_path('data/unit_type/feature_image') . '/' . $feature_image);
                            $u->feature_image_url = $feature_image;
                        }
                    }

                    if($u->save()) {
                        if(Request::hasFile('img_floor_plan')) {
                            $file = Request::file('img_floor_plan');
                            foreach ($file as $key => $files) {
                                $thisFile = Request::file('img_floor_plan')[$key];
                                if ($thisFile->isValid()) {
                                    $checkSize = Request::file('img_floor_plan')[$key];
                                    
                                    if (($checkSize->getSize() / 1024 / 1024) > 1) {
                                        return redirect()->back()->with('error', 'File size is too large!');
                                    }

                                    $file_s = Request::file('img_floor_plan')[$key];
                                    $path = public_path('data/image_type/floor_plan');
                                    $file_name = $this->resizeImage($file_s,$path,$key);
                                    if($file_name){	
                                        $image_type = new ImageType;
                                        $image_type->unit_type_id = $u->id;
                                        $image_type->type = 'floor_plan';
                                        $image_type->url = $file_name;
                                        $image_type->save();
                                    }
                                }
                            }
                        }

                        if(Request::hasFile('img_interior')) {
                            $file = Request::file('img_interior');
                            foreach ($file as $key => $files) {
                                $thisFile = Request::file('img_interior')[$key];
                                if ($thisFile->isValid()) {
                                    $checkSize = Request::file('img_interior')[$key];

                                    if (($checkSize->getSize() / 1024 / 1024) > 1) {
                                        return redirect()->back()->with('error', 'File size is too large!');
                                    }

                                    $file_s = Request::file('img_interior')[$key];
                                    $path = public_path('data/image_type/interior');
                                    $file_name = $this->resizeImage($file_s,$path,$key);
                                    if($file_name){	
                                        $image_type = new ImageType;
                                        $image_type->unit_type_id = $u->id;
                                        $image_type->type = 'interior';
                                        $image_type->url = $file_name;
                                        $image_type->save();
                                    }
                                }
                            }
                        }

                        if(Request::hasFile('img_exterior')) {
                            $file = Request::file('img_exterior');
                            foreach ($file as $key => $files) {
                                $thisFile = Request::file('img_exterior')[$key];
                                if($thisFile->isValid()) {
                                    $checkSize = Request::file('img_exterior')[$key];
                                    
                                    if (($checkSize->getSize() / 1024 / 1024) > 1) {
                                        return redirect()->back()->with('error', 'File size is too large!');
                                    }

                                    $file_s = Request::file('img_exterior')[$key];
                                    $path = public_path('data/image_type/exterior');
                                    $file_name = $this->resizeImage($file_s,$path,$key);
                                    if($file_name){	
                                        $image_type = new ImageType;
                                        $image_type->unit_type_id = $u->id;
                                        $image_type->type = 'exterior';
                                        $image_type->url = $file_name;
                                        $image_type->save();
                                    }
                                }
                            }
                        }

                        // save commission setting
                        $commissionType = Request::input('commission_type');
                        $commissionValue = Request::input('commission_value');

                        if($commissionType && $commissionValue) {
                            $u->saveCommission($commissionValue, $commissionType);
                        }
                        // End saving commission setting

                        return redirect()->back()->with('msg','UnitType Updated success!');
                    }
                    return redirect()->back();
                }
        }
        return redirect()->back();
    }

    public function listUnitType() {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 50;
        if(!$offset){
            $offset = 50;
        }
        $query_arr = array('user_id'=>Auth::user()->id, 'branch_id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
        $listUnitType = new UnitType();
        $name = null;
        if (Request::has('name')) {
            $name = trim(Request::input('name'));
            $listUnitType = $listUnitType->where(function($query) use($name) {
                $query->where('name', 'like', '%' . $name . '%')
   ->orWhere('short_code','like', '%' . $name . '%');
            });
        }
        $listUnitType= $listUnitType->with(['activeSaleCommissionSetting','Projects']);
        $listUnitType = $listUnitType->paginate($offset)->setPath('list?name='.$name.'&offset='.$offset);
        $contract_template = config('static_data.contract_template');
        return $this->view('unit_type.list_unit_type', ['lists' => $listUnitType, 'name' => $name,'offset'=>$offset,'contract_template' => $contract_template]);
    }

    public function getDetail($id =0){
        if ($id > 0) {
            $unit_type = UnitType::find($id);
            $data['img_floor_plan'] = ImageType::where('type','floor_plan')->where('unit_type_id','=',$unit_type->id)->get();
            $data['img_interior'] = ImageType::where('type','interior')->where('unit_type_id','=',$unit_type->id)->get();
            $data['img_exterior'] = ImageType::where('type','exterior')->where('unit_type_id','=',$unit_type->id)->get();
            $data['unit_type'] = $unit_type;
            $data['project'] = Project::where('active',1)->get();
            $data['contract_template'] = config('static_data.contract_template');
            if (!empty($data['unit_type'])) {
                return view('unit_type.detail',$data);
            }
        }
        return redirect()->back();
    }

        public function remove_image(){
            $image_type = ImageType::find(Request::input('id'));
            $path = public_path("data/image_type/{$image_type->type}/{$image_type->url}");
            if(file_exists($path)){
                unlink($path);
                $image_type->delete();
                }
                return response()->json(['msg' => 1]);
        }

    public function unit_typeDisable($id = 0) {
        if ($id > 0) {
            $unit_type = UnitType::find($id);
            if (!empty($unit_type)) {
                $unit_type->active = 0;
                $unit_type->save();
                $this->userActivity(Auth::user()->id, $unit_type->id, 0, 'Disable UnitType', Request::fullUrl());
            }
        }
        return redirect()->back();
    }

    public function unit_typeEnable($id = 0) {
        if ($id > 0) {
            $unit_type = UnitType::find($id);
            if (!empty($unit_type)) {
                $unit_type->active = 1;
                $unit_type->save();
                $this->userActivity(Auth::user()->id, $unit_type->id, 0, 'Enable UnitType', Request::fullUrl());
            }
        }
        return redirect()->back();
    }

    private  function resizeImage($file = '',$path = '',$key = 0){
        if($file){
            $file_s = $file;
            list($w, $h) = getimagesize($file_s);
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
                $image = Image::make($file_s)->resize($w, $h);
                $file_name = uniqid(date('dmY')).'-'.$key.$file->getClientOriginalName();
                $image->save($path . '/' . $file_name);
                return $file_name;
        }
    }
}
