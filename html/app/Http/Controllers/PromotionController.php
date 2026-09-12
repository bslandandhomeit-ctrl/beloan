<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Promotion;
use App\Models\UnitType;
use App\Models\UnitTypePromotion;
use Request;
use Auth;
use Session;

class PromotionController extends Controller {
	public function __construct() {
        $this->middleware('xss');
        // $this->middleware('auth');
         if (!Request::ajax()) {
            $this->middleware('auth');
        }
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
    	$promotion = new Promotion();
        $name = null;
        if(Request::has('name')) {
            $name = Request::input('name');
            $promotion = $promotion->where(function($query) use($name){
                $query->where('name', 'like', '%' . $name . '%');
            });
        }
        $promotion = $promotion->paginate($offset);
        return $this->view('promotion.list', ['lists' => $promotion, 'name' => $name,'offset'=>$offset]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$data['unit_type'] = UnitType::get();
		return view('promotion.create',$data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Request::all();
        $rules = [
            'name' => 'required|string|max:255',
            'discount_amount' => 'numeric',
            // 'unit_type_id.*' => 'required',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response()->json(['status'=>0,Session::flash('error' ,'Failed to Create Promotion')]);
        }else {
        	$p = new Promotion;
        	$p->name = Request::input('name');
        	$p->start_date = Request::input('start_date');
        	$p->end_date = Request::input('end_date');
        	$p->discount_amount = Request::input('discount_amount');
            $p->user_id = Auth::user()->id;
            if($p->save()) {
            	for($i=0; $i < count(Request::input('unit_type')); $i++) { 
            		UnitTypePromotion::create([
            			'promotion_id' => $p->id,
            			'unit_type_id' => Request::input('unit_type')[$i]['id']
            		]);
            	}
                return response()->json(['status'=>1,Session::flash('msg','Promotion Created success!')]);
            }
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		$id = Request::input('id');
		if($id > 0){
			$promotion = Promotion::find($id);
			return view('promotion.detail',compact('promotion'));
		}
		return redirect()->back();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if($id > 0){
			$data['promotion'] = Promotion::find($id);
			$data['unit_type_promotion'] = UnitTypePromotion::whereIn('promotion_id',[$data['promotion']['id']])
			->join('unit_types','unit_types.id','=','unit_type_promotions.unit_type_id')
			->select('unit_types.id','unit_types.name','unit_types.short_code','unit_type_promotions.id as unit_type_promotion_id')
			->get();
            if (!empty($data['promotion'])) {
            	$data['unit_type'] = UnitType::get();
                return $this->view('promotion.edit', $data);
            }
		}
		return redirect()->back();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if($id > 0){
			$data = Request::all();
	        $rules = [
	            'name' => 'required|string|max:255',
	            'discount_amount' => 'numeric',
	            // 'unit_type_id.*' => 'required',
	        ];
	        $validator = Validator::make($data, $rules);
	        if($validator->fails()) {
	            return redirect()->back()->with(['error' => 'Failed to Update Promotion']);
	        }else {
	        	$p = Promotion::find($id);
	        	$p->name = Request::input('name');
	        	$p->start_date = Request::input('start_date');
	        	$p->end_date = Request::input('end_date');
	        	$p->discount_amount = Request::input('discount_amount');
	            $p->user_id = Auth::user()->id;
	            if($p->save()) {
	            	for($i=0; $i < count(Request::input('unit_type_id')); $i++) { 
	            		UnitTypePromotion::updateOrCreate([
	            			'promotion_id' => $p->id,
	            			'unit_type_id' => Request::input('unit_type_id')[$i]
	            		],[
	            			'promotion_id' => $p->id,
	            			'unit_type_id' => Request::input('unit_type_id')[$i]
	            		]);
	            	}
	                return redirect()->back()->with('msg','Promotion Updated success!');
	            }
	            return redirect()->back();
	        }
		}
	}

	public function disable($id){
		if($id > 0){
			$promotion = Promotion::find($id);
			$promotion->active = 0;
			$promotion->save();
		}
		return redirect()->back();
	}

	public function enable($id){
		if($id > 0){
			$promotion = Promotion::find($id);
			$promotion->active = 1;
			$promotion->save();
		}
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
		return response()->json(UnitTypePromotion::find(Request::input('id'))->delete());
	}

	public function get_unit_type(){
		$unit_type = UnitType::select('id','name','short_code')->find(Request::input('id'));
		if(!$unit_type){
			return response()->json(['status' => 0,'msg' => '<p class="msg">Data not found.</p>']);
		}else{
			return response()->json($unit_type);
		}
	}
}
