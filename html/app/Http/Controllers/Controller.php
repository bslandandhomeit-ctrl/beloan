<?php namespace App\Http\Controllers;
error_reporting(0);
use App\Models\Country\Communes;
use App\Models\Country\CountryDescription;
use App\Models\Country\Districts;
use App\Models\Country\Provinces;
use App\Models\Country\Villages;
use App\Models\Locale;
use App\Models\LocaleTitle;
use App\Models\UserActivity;
use App\Models\Notification;
use Illuminate\Foundation\Bus\DispatchesCommands;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DrawdownAccounts;
use App\Models\ClientLoanAccounts;
use App\Models\Audit;
use App\Models\Country\Countries;
use Illuminate\Support\Facades\Session;
use Request;
use Auth;

abstract class Controller extends BaseController {
	use DispatchesCommands, ValidatesRequests;

    protected $locales = [];
    protected $locale_titles = [];
    protected $notification = false;
    public $permission_id = false;
    public $group_code = false;
    public $user_id;
    public $last_Id = false;


    public function __construct()
    {
        $this->user_id = Auth::user()->id;
        $this->permission_id    =   $this->PermisID($this->user_id);
        $this->group_code       =   $this->group_Code($this->permission_id);
        $this->notification     =   new Notification();
//        dd($this->permission_id);

//        $permissions = Auth::user();
//        dd($permissions);
    }

    protected function userActivity($id,$relation_id,$type,$activity=null,$note=null)
    {
        $uactivity = new UserActivity();
        $uactivity->user_id = $id;
        $uactivity->relation_id = $relation_id;
        $uactivity->relation_type = $type;
        $uactivity->activity = $activity;
        $uactivity->activity_date = date('Y-m-d H:i:s');
        $uactivity->note = $note;
        $uactivity->save();
    }

    protected function view($view = null, $data = array(), $mergeData = array())
    {
	$this->loadLocale();
        $data['locales'] = $this->locales;
        $data['secure'] = false;
        return view($view, $data, $mergeData);
    }

    protected function loadLocale()
    {
       if(!Request::ajax()){
         /*  $locale_id = session('locale',1);
           $action_name = Request::route()->getName();
           $titles = LocaleTitle::where('locale_id','=',$locale_id);
           if(!empty($action_name)){
               $titles = $titles->whereIn('group',['sidebar',$action_name,'multiple']);
           }else{
               $titles = $titles->whereIn('group',['sidebar','multiple']);
           }
           $this->locale_titles = $titles->get(['title','key']);*/
           $this->locales = Locale::get();
       }
    }
    public function accept_num($num){

    }
    public function setUrl($url,$query)
    {
        if(str_contains($url,'?')){
            return $url.'&'.$query;
        }
        return $url.'?'.$query;
    }

    public function getUserByBranch(Model $m, $field , $query_arr)
    {
    	if($query_arr['role_id']==1 || $query_arr['role_id']==2) return $m; //admin and super admin do all

    	if($field=='user_id') {
    		$users = User::select('id')->where('branch_id', $query_arr['branch_id'])->get();
    		foreach ($users as $user){
    			$user_arr[] = $user->id;
    		}
    		$obj = $m::whereIn('user_id', $user_arr);
    	}else{
    		$obj = $m::where($field, $query_arr[$field]);
    	}
    	return $obj;
    }

    //for all branches select list
    public function getBranchByUser(Model $m, $field , $query_arr)
    {
    	if($query_arr['role_id']==1 || $query_arr['role_id']==2) return $m; //admin and super admin do all
    	$obj = $m::where($field, $query_arr[$field]);
    	return $obj;
    }

    private function PermisID($permisId = 0)
    {
        $data = Session::get('ROLE_PERMISSION');
        foreach($data as $k=>$val) {
            return $val->pivot->permission_id;
        }
    }

    private function group_Code($permisId = 0)
    {
        $data = Session::get('ROLE_PERMISSION');
        if (!empty($permisId) && is_int($permisId)) {
            foreach ($data as $key=>$vals) {
                $datas[] = $vals->group_code;
            }
        }else{
            return false;
        }
        return $datas;
    }

    function is_access(){
    	if(Auth::user()->role_id!=1 && Auth::user()->role_id!=2){
    		die("You don't have permission to access that page!");
    	}
    }

    function do_audit($id, $user_id, $audit_id, $tbl, $status, $type=''){
        if($status=='' || $status == 0){
            $audit = new Audit();
        }else{
            $audit = Audit::where('tbl_id', $id)->where('tbl', $tbl)->first();
        }

        if(!$audit) $audit = new Audit();
        $audit->tbl_id = $id;
        if($user_id) $audit->user_id = $user_id;
        $audit->audit_id = $audit_id;
        $audit->tbl = $tbl;
        $audit->status = $status;
        $audit->updated_at = date('Y-m-d H:i:s');
        $audit->type = $type;
        $audit->save();
    }

    function check_audit($id, $tbl){
       $audit = Audit::where('tbl_id', $id)->where('tbl', $tbl)->first();
       if($audit->status >0 ) return true;
    }

    protected function UploadImageFile($files, $path)
    {
        if(Request::hasFile($files)) {

            $extension = Request::file($files)->getClientOriginalExtension();
            $ex = ['jpg', 'jpeg', 'bmp', 'png','pdf'];
            if (!Request::hasFile($files)) {
                $res['error'] = 'file not found';
            }
            if (!Request::file($files)->isValid()) {
                $res['error'] = 'file not valid';
            }
            if (Request::file($files)->getClientSize() >= Request::file($files)->getMaxFilesize()) {
                $res['error'] = 'This file size is not allow';
            }
            if (!in_array(strtolower($extension), $ex)) {
                $res['error'] = $ex;
            }
            if (!empty($res)) {

                return $res;

            }else {

                $dir = $path.'/';
                $filename = uniqid() . '_' . time() . '.' . $extension;
                Request::file($files)->move($dir, $filename);
                return ['uploaded'=>$filename];
            }
        }
    }


    protected function get_client_ip() {

        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

     protected function getCountry() {

        $data['countries']  =   Countries::with(['description'=>function($e){
            $e->where('language_id',2);}]
        )->where('status', 1)->get();
        $data['province']   =   Provinces::whereIn('count_id', $data['countries']->lists('id'))->get();
        $data['distric']    =   Districts::all();
        $data['commune']    =   Communes::all();
        $data['village']    =   Villages::all();

        return $data;

        //return Countries::with(['description','provinces.Districts.communes.villages'])->where('status', 1)->get()->toArray();

    }

    protected function auto_authorize_drawdown($id)
    {
        $dd = DrawdownAccounts::find($id);
        if (!empty($dd)) {
            $dd->status = 1;
            $dd->save();
            $this->userActivity(Auth::user()->id, $id, 4, 'Enable Drawdown account');
        }
        $this->do_audit($id, '', Auth::user()->id, 'drawdown_account', 1, 'approve drawdown account');
        return 1;
    }
    protected function auto_authorize_account($id){
        $ClientLoanAccounts = ClientLoanAccounts::find($id);
        $ClientLoanAccounts->status = 1;
        $ClientLoanAccounts->activated_on = date('Y-m-d');
        $ClientLoanAccounts->save();
        $this->userActivity(Auth::user()->id, $ClientLoanAccounts->id, 0, 'Audit ClientLoanAccount', Request::fullUrl());

        $this->do_audit($id, '', Auth::user()->id, 'client_loan_accounts', 1, 'approve client loan account');
        return 1;
    }
    protected function getClientNumber($no , $digit = 6){
        $str = $no;
        $result = str_pad($str,$digit,"0",STR_PAD_LEFT);
        return $result;
    }
}
