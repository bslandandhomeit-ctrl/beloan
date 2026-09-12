<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Request;
use Auth;

class UnitController extends Controller {
	public function __construct() {
        $this->middleware('xss');
        $this->middleware('auth');
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$offset = isset($_GET['offset']) ? $_GET['offset'] : 50;
		if(!$offset){
			$offset = 50;
		}
		$now = Carbon::now();
		$projects = Project::select('id','dealer','short_code')->get();
		$list_unit = Unit::select('units.*','unit_types.name AS unit_type_name','projects.id As project_id','projects.short_code As project','promotions.discount_amount','loans.unit_sale_price')
					->join('unit_types','units.unit_type_id','=','unit_types.id')
					->join('projects','unit_types.project_id','=','projects.id')
					->leftJoin('unit_type_promotions','unit_type_promotions.unit_type_id','=','unit_types.id')
					->leftJoin('loans','units.id','=','loans.unit_id')
					->leftJoin('promotions', function($join) use ($now) {
						$join->on('unit_type_promotions.promotion_id', '=', 'promotions.id')
							 ->on(DB::raw('MONTH(tb_promotions.start_date)'), '>=', DB::raw($now->month))
							 ->on(DB::raw('MONTH(tb_promotions.end_date)'), '<=', DB::raw($now->month))
							 ->on(DB::raw('YEAR(tb_promotions.start_date)'), '>=', DB::raw($now->year))
							 ->on(DB::raw('YEAR(tb_promotions.end_date)'), '<=', DB::raw($now->year));
					})
					->orderBy('promotions.id','DESC');
					
		$search = null;
        if (Request::has('search')) {
            $search = Request::input('search');
            $list_unit = $list_unit->where(function($query) use($search) {
                $query->where('code', 'like', '%' . $search . '%')
                ->orWhere('price','like', '%' . $search . '%');
            });
        }

		if (Request::has('project_id')) {
            $project_id = Request::input('project_id');
            $list_unit = $list_unit->where(function($query) use($project_id) {
                $query->where('unit_types.project_id',$project_id);
            });
        }

        $static = config('static_data');
        $unit_status_color = $static['unit_status_color'];
        $list_unit = $list_unit->paginate($offset)->setPath('?search='.$search.'&offset='.$offset);
        if(Request::has('is_excel') == 1 || Request::has('is_csv') == 1){
        	$xlsx = 'xlsx';
        	if(Request::has('is_csv') == 1){
        		$xlsx = 'csv';
        	}
	        return Excel::create('unit'.date('d-M-Y'), function($excel) use ($list_unit) {
				$excel->sheet('mySheet', function($sheet) use ($list_unit)
		        {
					$sheet->loadView('exports.view_unit_export',['lists' => $list_unit, 'unit_status_color' => $unit_status_color]);
		        });
			})->download($xlsx);
        }
		return view('unit.list',['lists' => $list_unit, 'unit_status_color' => $unit_status_color, 'search' => $search,'offset'=>$offset,'projects'=>$projects]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$static = config('static_data');
        $data['unit_status'] = $static['unit_status'];
		$data['unit_type'] = UnitType::where('active',1)->orderBy('project_id','ASC')->get();
		return view('unit.create',$data);
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
            'unit_code' => 'required|unique:units,code',
            'price' => 'required|numeric',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return redirect()->back()->with(['error' => 'Failed to Create Uint']);
        } else {
        	$status = Request::input('status');
            $u = new Unit;
            $u->unit_type_id = Request::input('unit_type_id');
            $u->zone_id  = Request::input('zone_id');
            $u->code = Request::input('unit_code');
            $u->price = str_replace(',', '', Request::input('price'));
            $u->street = Request::input('street');
            $u->street_corner = Request::input('street_corner');
            $u->street_size = Request::input('street_size');
            $u->floor = Request::input('floor');
            $u->land_size_width = str_replace(',', '', Request::input('land_width'));
            $u->land_size_length = str_replace(',', '', Request::input('land_length'));
            $u->land_area = str_replace(',', '', Request::input('land_area'));
            $u->building_size_width = str_replace(',', '', Request::input('house_width'));
            $u->building_size_length = str_replace(',', '', Request::input('house_length'));
            $u->building_area = str_replace(',', '', Request::input('house_area'));
            $u->living_room = Request::input('living_room');
            $u->kitchen = Request::input('kitchen');
            $u->bedroom = Request::input('bedroom');
            $u->bathroom = Request::input('bathroom');
            $u->status = isset($status)?$status:'available';
            $u->swimming_pool = Request::input('swimming_pool');
            // $u->status = 'available';
            $u->user_id = Auth::user()->id;
			$u->active = 1;
            if($u->save()) {
                return redirect()->back()->with('msg','Unit Created success!');
            }
            return redirect()->back();
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id = 0)
	{
		if ($id > 0) {
            $data['unit'] = Unit::find($id);
            if (!empty($data['unit'])) {
    			return view('unit.detail',$data);
            }
        }
        return redirect()->back();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id =0)
	{
		if ($id > 0) {
        	$data['unit'] = Unit::find($id);
        	$static = config('static_data');
        	$data['unit_status'] = $static['unit_status'];
            $data['unit_type'] = UnitType::where('active',1)->orderBy('project_id','ASC')->get();
            if (!empty($data['unit'])) {
                return $this->view('unit.edit', $data);
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
	public function update($id = 0)
	{
		if($id > 0){
			$data = Request::all();
	        $rules = [
	            'unit_code' => 'required|unique:units,code,'.$id,
	            'price' => 'required|numeric',
	        ];
	        $validator = Validator::make($data, $rules);
	        if($validator->fails()) {
	            return redirect()->back()->with(['error' => 'Failed to Update Uint']);
	        } else {
	            $u = Unit::find($id);
	            $u->unit_type_id = Request::input('unit_type_id');
	            $u->zone_id  = Request::input('zone_id');
	            $u->code = Request::input('unit_code');
	            $u->price = str_replace(',', '', Request::input('price'));
	            $u->street = Request::input('street');
	            $u->street_corner = Request::input('street_corner');
	            $u->street_size = Request::input('street_size');
	            $u->floor = Request::input('floor');
	            $u->land_size_width = str_replace(',', '', Request::input('land_width'));
	            $u->land_size_length = str_replace(',', '', Request::input('land_length'));
	            $u->land_area = str_replace(',', '', Request::input('land_area'));
	            $u->building_size_width = str_replace(',', '', Request::input('house_width'));
	            $u->building_size_length = str_replace(',', '', Request::input('house_length'));
	            $u->building_area = str_replace(',', '', Request::input('house_area'));
	            $u->living_room = Request::input('living_room');
	            $u->kitchen = Request::input('kitchen');
	            $u->bedroom = Request::input('bedroom');
	            $u->bathroom = Request::input('bathroom');
	            $u->status = Request::input('status');
	            $u->swimming_pool = Request::input('swimming_pool');
	            $u->user_id = Auth::user()->id;
	            if($u->save()) {
	                return redirect()->route('list_unit')->with('msg','Unit Updated success!');
	            }
	            return redirect()->back();
	        }
		}
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function unit_Disable($id = 0) {
        if ($id > 0) {
            $unit = Unit::find($id);
            if (!empty($unit)) {
                $unit->active = 0;
                $unit->save();
                $this->userActivity(Auth::user()->id, $unit->id, 0, 'Disable Unit', Request::fullUrl());
            }
        }
        return redirect()->back();
    }

    public function unit_Enable($id = 0) {
        if ($id > 0) {
            $unit = Unit::find($id);
            if (!empty($unit)) {
                $unit->active = 1;
                $unit->save();
                $this->userActivity(Auth::user()->id, $unit->id, 0, 'Enable Unit', Request::fullUrl());
            }
        }
        return redirect()->back();
    }
}
