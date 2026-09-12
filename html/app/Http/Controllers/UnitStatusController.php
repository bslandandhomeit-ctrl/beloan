<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\UnitStatus;
use App\Models\ImageType;
use Request;
use Auth;
use Image;
use File;
use DB;
use App;

class UnitStatusController extends Controller {

	public function __construct() {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function create() {
        return $this->view('unit_status.create');
    }

    public function post_create(Request $request) {
        $data = Request::all();
        $rules = [
            'status' => 'required|unique:unit_status,status|min:3',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(['error' => 'Failed to Create Unit Status']);
        } else {
            $u = new UnitStatus;
            $u->status = Request::input('status');
            $u->description = Request::input('description');
            $u->user_id = Auth::user()->id;
            $u->active = 1;
            if($u->save()) {
                return redirect()->back()->with('msg','Unit Status Created success!');
            }
            return redirect()->back();
        }
    }
    
    public function getEdit($id = 0) {
        if ($id > 0) {
        	$unit_status = UnitStatus::find($id);
        	$data['unit_status'] = $unit_status;
            if (!empty($data['unit_status'])) {
                return $this->view('unit_status.edit', $data);
            }
        }
        return redirect()->back();
    }

    public function postEdit($id = 0) {
        if ($id > 0) {
            $data = Request::all();
	        $rules = [
	            'project_id' => 'required',
	            'name' => 'required|min:3',
	            'short_code' => 'required|unique:unit_types,short_code,'.$id,
	            'contract_template_id' => 'required',
	        ];
	        $validator = Validator::make($data, $rules);
	        if ($validator->fails()) {
	            return redirect()->back()->with(['error' => 'Failed to Update Uint Type']);
	        } else {
	            $u = UnitStatus::find($id);
	            $u->project_id = Request::input('project_id');
	            $u->name = Request::input('name');
	            $u->name_en = Request::input('name_en');
	            $u->name_cn = Request::input('name_cn');
	            return redirect()->back()->with('msg','Unit Status Updated success!');
	        }
        }
        return redirect()->back();
    }

    public function listUnitStatus() {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
        // $query_arr = array('user_id'=>Auth::user()->id, 'branch_id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
        $listUnitStatus = new UnitStatus();
        $status = null;
        if (Request::has('status')) {
            $status = Request::input('status');
            $listUnitStatus = $listUnitStatus->where(function($query) use($status) {
                $query->where('status', 'like', '%' . $status . '%')
                ->orWhere('description','like', '%' . $status . '%');
            });
        }
        $listUnitStatus = $listUnitStatus->paginate($offset);
        return $this->view('unit_status.list_unit_status', ['lists' => $listUnitStatus, 'status' => $status,'offset'=>$offset]);
    }

    public function getDetail($id =0){
    	if ($id > 0) {
        	$unit_status = UnitStatus::find($id);
            $data['unit_status'] = $unit_status;
            if (!empty($data['unit_status'])) {
    			return view('unit_status.detail',$data);
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

    public function unit_statusDisable($id = 0) {
        if ($id > 0) {
            $unit_status = UnitStatus::find($id);
            if (!empty($unit_status)) {
                $unit_status->active = 0;
                $unit_status->save();
                $this->userActivity(Auth::user()->id, $unit_status->id, 0, 'Disable UnitStatus', Request::fullUrl());
            }
        }
        return redirect()->back();
    }

    public function unit_statusEnable($id = 0) {
        if ($id > 0) {
            $unit_status = UnitStatus::find($id);
            if (!empty($unit_status)) {
                $unit_status->active = 1;
                $unit_status->save();
                $this->userActivity(Auth::user()->id, $unit_status->id, 0, 'Enable UnitStatus', Request::fullUrl());
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
