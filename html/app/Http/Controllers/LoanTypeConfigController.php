<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\LoanTypeConfig;
use App\Models\UnitType;
use App\Models\Products\Product_type;
use App\Models\ImageType;
use Request;
use Auth;
use Image;
use File;
use DB;
use App;

class LoanTypeConfigController extends Controller {

	public function __construct() {
        $this->middleware('xss');
        $this->middleware('auth');
    }

    public function create() {
    	$unit_type = UnitType::where('active',1)->get();
    	$static = config('static_data');
    	$productsTypes = Product_type::all();
        return $this->view('unittype_config.create', [
        	'unit_type' => $unit_type,
        	'static' => $static,
        	'productsTypes' => $productsTypes,
        ]);
    }

    public function post_create(Request $request) {
        $data = Request::all();
        $rules = [
            'unit_type_id' => 'required|unique:loan_type_config,unit_type_id',
            'loan_penalty_type' => 'required',
            'penalty_rate_type' => 'required',
            'loan_type' => 'required',
            'days_of_month' => 'required|min:1',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
        	return redirect()->back()->with(['error' => $validator->messages()->toArray()]);
        } else {
            $unit_type_id = Request::input('unit_type_id');
            $save = 0;
            for($i=0; $i < count($unit_type_id); $i++) { 
                if(LoanTypeConfig::where('unit_type_id',$unit_type_id[$i])->first()){
                    continue;
                }
                $u = new LoanTypeConfig;
                $u->unit_type_id = Request::input('unit_type_id')[$i];
                $u->loan_penalty_type = Request::input('loan_penalty_type');
                $u->penalty_rate_type = Request::input('penalty_rate_type');
                $u->loan_type = Request::input('loan_type');
                $u->penalty_period1 = Request::input('penalty_period1');
                $u->penalty_rate1 = Request::input('penalty_rate1');
                $u->penalty_period2 = Request::input('penalty_period2');
                $u->penalty_rate2 = Request::input('penalty_rate2');
                $u->payoff_period1 = Request::input('payoff_period1');
                $u->pay_off_rate1 = Request::input('pay_off_rate1');
                $u->payoff_period2 = Request::input('payoff_period2');
                $u->pay_off_rate2 = Request::input('pay_off_rate2');
                $u->frequency = Request::input('frequency');
                $u->days_of_month = Request::input('days_of_month');
                $u->admin_fee = Request::input('admin_fee');
                $u->admin_fee_opt = Request::input('admin_fee_opt');
                $u->maintain_fee = Request::input('maintain_fee');
                $u->maintain_fee_opt = Request::input('maintain_fee_opt');
                $u->other_fee = Request::input('other_fee');
                $u->repayment_type = Request::input('repayment_type');
                $u->balloon = Request::input('balloon');
                $u->user_id = Auth::user()->id;
                $u->active = 1;

                if($u->save()){
                    $save++;
                }

            }
            if($save > 0){
                return redirect()->back()->with('msg','LoanTypeConfig Created success!');
            }else{
            	return redirect()->back()->with('msg','LoanTypeConfig Create Failed!');
            }
        }
        return redirect()->back();
    }
    
    public function getEdit($id = 0) {
        if ($id > 0) {
        	$unit_type_config = LoanTypeConfig::find($id);
        	$unit_type = UnitType::where('active',1)->get();
	    	$static = config('static_data');
	    	$productsTypes = Product_type::all();
	    	$data['unit_type_config'] = $unit_type_config;
	    	$data['unit_type'] = $unit_type;
	    	$data['static'] = $static;
	    	$data['productsTypes'] = $productsTypes;
            if (!empty($data['unit_type_config'])) {
                return $this->view('unittype_config.edit', $data);
            }
        }
        return redirect()->back();
    }

    public function postEdit(Request $request,$id = 0) {
        if ($id > 0) {
            $data = Request::all();
		        $rules = [
	            'unit_type_id' => 'required|unique:loan_type_config,unit_type_id,'.$id,
	            'loan_penalty_type' => 'required',
                'penalty_rate_type' => 'required',
	            'loan_type' => 'required',
	            'days_of_month' => 'required|min:1',
	        ];
	        $validator = Validator::make($data, $rules);
	        if ($validator->fails()) {
	        	 return redirect()->back()->with(['error' => $validator->messages()->toArray()]);
	        } else {
	            $u = LoanTypeConfig::find($id);
	            if($u){
		            $u->unit_type_id = Request::input('unit_type_id');
		            $u->loan_penalty_type = Request::input('loan_penalty_type');
                    $u->penalty_rate_type = Request::input('penalty_rate_type');
		            $u->loan_type = Request::input('loan_type');
		            $u->penalty_period1 = Request::input('penalty_period1');
		            $u->penalty_rate1 = Request::input('penalty_rate1');
		            $u->penalty_period2 = Request::input('penalty_period2');
		            $u->penalty_rate2 = Request::input('penalty_rate2');
		            $u->payoff_period1 = Request::input('payoff_period1');
		            $u->pay_off_rate1 = Request::input('pay_off_rate1');
		            $u->payoff_period2 = Request::input('payoff_period2');
		            $u->pay_off_rate2 = Request::input('pay_off_rate2');
		            $u->frequency = Request::input('frequency');
		            $u->days_of_month = Request::input('days_of_month');
		            $u->admin_fee = Request::input('admin_fee');
		            $u->admin_fee_opt = Request::input('admin_fee_opt');
		            $u->maintain_fee = Request::input('maintain_fee');
		            $u->maintain_fee_opt = Request::input('maintain_fee_opt');
		            $u->other_fee = Request::input('other_fee');
		            $u->repayment_type = Request::input('repayment_type');
		            $u->balloon = Request::input('balloon');
		            $u->user_id = Auth::user()->id;
		            if($u->save()) {
		                return redirect()->back()->with('msg','LoanTypeConfig Updated success!');
		            }
	            }
	            return redirect()->back();
	        }
        }
        return redirect()->back();
    }

    public function listUnitTypeConfig() {
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
        if(!$offset){
            $offset = 15;
        }
        $unit_type = UnitType::where('active',1)->get();
        $query_arr = array('user_id'=>Auth::user()->id, 'branch_id'=>Auth::user()->branch_id, 'role_id'=>Auth::user()->role_id);
        $listUnitType = new LoanTypeConfig();
        $static = config('static_data');
        $name = null;
        $name = Request::input('name');
        $unit_type_id = Request::input('unit_type_id');
        if (Request::has('name')) {
            $listUnitType = $listUnitType->where(function($query) use($name) {
                $query->where('name', 'like', '%' . $name . '%')
                ->orWhere('short_code','like', '%' . $name . '%');
            });
        }
        if($unit_type_id){
            $listUnitType = $listUnitType->where('unit_type_id',$unit_type_id);
        }
        $listUnitType = $listUnitType->paginate($offset)->setPath('?unit_type_id='.$unit_type_id.'&offset='.$offset);
        $contract_template = ['1'=>'condo','2'=>'apartment'];
        return $this->view('unittype_config.list', ['lists' => $listUnitType, 'name' => $name,'offset'=>$offset,'static' => $static, 'unit_type' => $unit_type, 'unit_type_id' => $unit_type_id, 'offset' => $offset]);
    }

    public function getDetail($id =0){
    	if ($id > 0) {
        	$unit_type_config = LoanTypeConfig::find($id);
        	$unit_type = UnitType::where('active',1)->get();
	    	$static = config('static_data');
	    	$productsTypes = Product_type::all();
	    	$data['unit_type_config'] = $unit_type_config;
	    	$data['unit_type'] = $unit_type;
	    	$data['static'] = $static;
	    	$data['productsTypes'] = $productsTypes;
            if (!empty($data['productsTypes'])) {
    			return view('unittype_config.detail',$data);
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

    public function Disable($id = 0) {
        if ($id > 0) {
            $data = LoanTypeConfig::find($id);
            if (!empty($data)) {
                $data->active = 0;
                $data->save();
                $this->userActivity(Auth::user()->id, $data->id, 0, 'Disable LoanTypeConfig', Request::fullUrl());
            }
        }
        return redirect()->back();
    }

    public function Enable($id = 0) {
        if ($id > 0) {
            $data = LoanTypeConfig::find($id);
            if (!empty($data)) {
                $data->active = 1;
                $data->save();
                $this->userActivity(Auth::user()->id, $data->id, 0, 'Enable LoanTypeConfig', Request::fullUrl());
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
