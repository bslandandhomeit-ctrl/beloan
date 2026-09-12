<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Request;
use App\Models\User;
use App\Models\SalePerson;
use Auth;

class SalePersonController extends Controller {
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
		$offset = isset($_GET['offset']) ? $_GET['offset'] : 15;
		$sale_person = new SalePerson;
		$search = null;
        if (Request::has('search')) {
            $search = Request::input('search');
            $sale_person = $sale_person->where(function($query) use($search) {
                $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email','like', '%' . $search . '%')
                ->orWhere('phone','like', '%' . $search . '%');
            });
        }
        $sale_person = $sale_person->paginate($offset)->setPath('?search='.$search.'&offset='.$offset);
		return view('users.saleperson.index',['lists' => $sale_person, 'search' => $search,'offset'=>$offset]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$nationlity = parent::getCountry();
		$co_name = User::select('users.id', 'name')->join('roles', 'users.role_id', '=', 'roles.id')->whereIn('role', ['co', 'sco'])->get();
		return view('users.saleperson.create',compact('nationlity','co_name'));
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
            'sale_team_id' => 'required',
            'name' => 'required|string|max:255',
            'gender' => 'required',
            'dob' => 'required|date',
            'national_id' => 'required',
            'email' => 'required|unique:saleperson,email',
            'phone' => 'required',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return redirect()->back()->with(['error' => 'Failed to Create Sale Person']);
        }
        $sale_person = new SalePerson;
        $sale_person->sale_team_id = Request::input('sale_team_id');
        $sale_person->name = Request::input('name');
        $sale_person->gender = Request::input('gender');
        $sale_person->dob = Request::input('dob');
        $sale_person->national_id = Request::input('national_id');
        $sale_person->email = Request::input('email');
        $sale_person->phone = Request::input('phone');
        $sale_person->user_id = Auth::user()->id;
		$sale_person->active = 1;
        if($sale_person->save()) {
            return redirect()->back()->with('msg','Sale Person Created success!');
        }
        return redirect()->back();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if ($id > 0) {
			$data['nationlity'] = parent::getCountry();
			$data['co_name'] = User::select('users.id', 'name')->join('roles', 'users.role_id', '=', 'roles.id')->whereIn('role', ['co', 'sco'])->get();
        	$data['sale_person'] = SalePerson::find($id);
            if (!empty($data['sale_person'])) {
                return $this->view('users.saleperson.edit', $data);
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
		$data = Request::all();
        $rules = [
            'sale_team_id' => 'required',
            'name' => 'required|string|max:255',
            'gender' => 'required',
            'dob' => 'required|date',
            'national_id' => 'required',
            'email' => 'required|unique:saleperson,email,'.$id,
            'phone' => 'required',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return redirect()->back()->with(['error' => 'Failed to Update Sale Person']);
        }
        $sale_person = SalePerson::find($id);
        $sale_person->sale_team_id = Request::input('sale_team_id');
        $sale_person->name = Request::input('name');
        $sale_person->gender = Request::input('gender');
        $sale_person->dob = Request::input('dob');
        $sale_person->national_id = Request::input('national_id');
        $sale_person->email = Request::input('email');
        $sale_person->phone = Request::input('phone');
        $sale_person->user_id = Auth::user()->id;
		$sale_person->active = 1;
        if($sale_person->save()) {
            return redirect()->back()->with('msg','Sale Person Updated success!');
        }
        return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function Disable($id = 0) {
        if($id > 0) {
            $sale_person = SalePerson::find($id);
            if (!empty($sale_person)) {
                $sale_person->active = 0;
                $sale_person->save();
                $this->userActivity(Auth::user()->id, $sale_person->id, 0, 'Disable Sale Person', Request::fullUrl());
        		return redirect()->back()->with('msg','Sale Person Disable success!');
            }
        }
        return redirect()->back();
    }

    public function Enable($id = 0) {
        if($id > 0) {
            $sale_person = SalePerson::find($id);
            if (!empty($sale_person)) {
                $sale_person->active = 1;
                $sale_person->save();
                $this->userActivity(Auth::user()->id, $sale_person->id, 0, 'Enable Sale Person', Request::fullUrl());
                return redirect()->back()->with('msg','Sale Person Enable success!');
            }
        }
        return redirect()->back();
    }

}
